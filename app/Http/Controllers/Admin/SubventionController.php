<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\subventionRequest;
use App\Models\Asset;
use App\Models\Donation;
use App\Models\Donor;
use App\Models\DonationType;
use App\Models\LockerLog;
use App\Models\Subvention;
use App\Models\User;
use App\Models\BeneficiaryCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Mpdf\Mpdf;
use Yajra\DataTables\DataTables;

class SubventionController extends Controller
{
    public function index(Request $request)
    {
        [$fromDate, $toDate] = $this->resolveDateRange($request);

        if ($request->ajax()) {

            $data = Subvention::query()
                ->where('type', 'monthly');

            $data->whereBetween('created_at', [$fromDate, $toDate]);

            $data = $data->latest()->get();

            return DataTables::of($data)
                ->addColumn('row_checkbox', function ($data) {
                    return '<div class="d-flex justify-content-center">
                        <input type="checkbox" class="subvention-row-checkbox" value="' . $data->id . '">
                    </div>';
                })
                ->addColumn('beneficiary_code', function ($data) {
                    return $data->user->beneficiary_code ?? '-';
                })
                ->editColumn('user_id', function ($data) {
                    return $data->user->wife_name ?? 'تم حذفه';
                })
                ->editColumn('created_at', function ($data) {
                    return $data->created_at->format('d/m/Y');
                })
                ->editColumn('price', function ($data) {
                    if ($data->price == 0) {
                        return ' عدد : ' . $data->asset_count . ' من ' .
                            ($data->asset ? ($data->asset->name ?? '-') : '-');
                    } else {
                        return " مبلغ قدره : " . $data->price . " جنيه ";
                    }
                })
                ->editColumn('type', function ($data) {
                    return $data->type == 'once' ? 'مرة واحدة' : 'إعانة شهرية';
                })
                ->addColumn('action', function ($data) {
                    $buttons = [];

                    if (auth()->guard('admin')->user()->can('subventions.edit')) {
                        $buttons[] = '
                            <button type="button" data-id="' . $data->id . '" class="btn btn-sm btn-info-light editBtn" title="تعديل">
                                <i class="fe fe-edit"></i>
                            </button>
                        ';
                    }

                    if (auth()->guard('admin')->user()->can('delete_subventions')) {
                        $buttons[] = '
                            <button class="btn btn-sm btn-danger-light" data-toggle="modal" data-target="#delete_modal" data-id="' . $data->id . '" data-title="هذه الإعانة" title="حذف">
                                <i class="fe fe-trash"></i>
                            </button>
                        ';
                    }

                    return implode('', $buttons);
                })
                ->escapeColumns([])
                ->make(true);
        }

        $totalSpent = Subvention::query()
            ->where('type', 'monthly')
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->sum('price');

        $periodLabel = $request->filled('from') && $request->filled('to')
            ? 'إجمالي المنصرف خلال الفترة من ' . $fromDate->format('Y-m-d') . ' إلى ' . $toDate->format('Y-m-d')
            : 'إجمالي المنصرف خلال الشهر الحالي';

        return view('admin/subventions/index', compact("totalSpent", 'fromDate', 'toDate', 'periodLabel'));
    }

    private function resolveDateRange(Request $request): array
    {
        $rawFrom = (string) $request->query('from', '');
        $rawTo = (string) $request->query('to', '');

        if (!$rawTo && str_contains($rawFrom, 'to=')) {
            [$fromPart, $toPart] = explode('to=', $rawFrom, 2);
            $rawFrom = rtrim($fromPart, '&?');
            $rawTo = trim($toPart);
        }

        $fromDate = $rawFrom !== ''
            ? Carbon::parse($rawFrom)->startOfDay()
            : now()->startOfMonth();
        $toDate = $rawTo !== ''
            ? Carbon::parse($rawTo)->endOfDay()
            : now()->endOfMonth();

        return [$fromDate, $toDate];
    }




    public function create()
    {
        $users = User::where('status', 'accepted')
            ->where('has_monthly_subvention', 1)
            ->whereDoesntHave('subventions', function ($query) {
                $query->where('type', 'monthly')
                    ->whereYear('created_at', now()->year)
                    ->whereMonth('created_at', now()->month);
            })
            ->with('beneficiaryCategory:id,name')
            ->select('id', 'husband_name', 'wife_name', 'monthly_subvention_amount', 'beneficiary_category_id')
            ->latest()
            ->get();
        $beneficiaryCategories = $this->beneficiaryCategoriesForFilters();
        $lockerTypes = DonationType::cashLockerTypes()->active()->orderBy('sort_order')->get();
        return view('admin/subventions/create', compact('users', 'beneficiaryCategories', "lockerTypes"));
    }


    public function store(subventionRequest $request)
    {
        DB::beginTransaction();
        try {
            $userIds = collect($request->input('user_ids', []))->filter()->unique()->values();
            $users = User::whereIn('id', $userIds)->get()->keyBy('id');

            if ($users->isEmpty()) {
                toastr()->error("المستخدم غير موجود");
                return redirect()->back()->withInput();
            }

            if ($request->type === 'monthly') {
                $alreadyPaidUsers = Subvention::whereIn('user_id', $userIds)
                    ->where('type', 'monthly')
                    ->whereYear('created_at', now()->year)
                    ->whereMonth('created_at', now()->month)
                    ->pluck('user_id');

                if ($alreadyPaidUsers->isNotEmpty()) {
                    toastr()->error('يوجد مستفيد تم صرف إعانة شهرية له بالفعل خلال هذا الشهر.');
                    return redirect()->back()->withInput();
                }
            }

            $lockerType = DonationType::cashLockerTypes()->find($request->donation_type_id);
            $lockerMoneyType = $lockerType?->lockerMoneyType();

            if (!$lockerType || !$lockerMoneyType) {
                toastr()->error('الخزنة المختارة غير صالحة للصرف المالي.');
                return redirect()->back()->withInput();
            }

            $totalRequiredAmount = $users->sum(function ($user) {
                return (float) ($user->monthly_subvention_amount ?? 0);
            });

            $availableBalance =
                LockerLog::where('moneyType', $lockerMoneyType)->where('type', LockerLog::TYPE_PLUS)->sum('amount')
                - LockerLog::where('moneyType', $lockerMoneyType)->where('type', LockerLog::TYPE_MINUS)->sum('amount');

            if ($totalRequiredAmount <= 0) {
                toastr()->error('إجمالي مبلغ الإعانات الشهرية يجب أن يكون أكبر من صفر.');
                return redirect()->back()->withInput();
            }

            if ($availableBalance < $totalRequiredAmount) {
                toastr()->error('رصيد الخزنة المختارة لا يكفي لصرف الإعانات الشهرية.');
                return redirect()->back()->withInput();
            }

            foreach ($userIds as $userId) {
                $user = $users->get($userId);
                $userAmount = (float) ($user->monthly_subvention_amount ?? 0);

                if ($userAmount <= 0) {
                    continue;
                }

                $subvention = Subvention::create([
                    'user_id' => $userId,
                    'price' => $userAmount,
                    'type' => 'monthly',
                    'comment' => $request->comment,
                ]);

                LockerLog::create([
                    "moneyType" => $lockerMoneyType,
                    "amount" => $userAmount,
                    "type" => LockerLog::TYPE_MINUS,
                    "admin_id" => auth()->id(),
                    "subvention_id" => $subvention->id,
                    "comment" => "صرف إعانة شهرية من خزنة " . $lockerType->name . " إلى " . ($user ? ($user->husband_name ?: $user->wife_name) : "مجهول")
                        . " ورقم هاتفه " . ($user ? $user->nearest_phone : "غير متوفر"),
                ]);
            }

            DB::commit();
            toastr()->success('تمت إضافة الإعانة بنجاح');
            return redirect()->route('subventions.index');
        } catch (\Exception $e) {
            DB::rollBack();
            toastr()->error($e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


    public function edit(Subvention $subvention)
    {
        $lockerLogs = LockerLog::where("amount", $subvention->price)
            ->where("created_at", $subvention->created_at)
            ->where("type", LockerLog::TYPE_MINUS);

        $lockerLog = $lockerLogs->first();
        $Dtype = $lockerLog ? $lockerLog->moneyType : 'subvention';

        //        $Dtype = $Dtype  == "sadaka" ? "صدقه" : ($Dtype == "zakat" ? "زكاة" : "عينيه");
        $users = User::where('status', 'accepted')
            // ->whereDoesntHave('subvention')
            ->orWhere('id', $subvention->user_id)
            ->select('id', 'wife_name')
            ->latest()->get();
        $assets = Asset::all();

        return view('admin/subventions/parts/edit', compact('users', 'subvention', "assets", "Dtype"));
    }


    public function update(subventionRequest $request, $id)
    {
        try {
            $user = User::find($request->user_id);
            if ($user) {

                if ($request->sub_type == 0 && $request->asset_count <= 0) {
                    if ($request->moneyType == 0) {
                        if ($request->price <= $totalDonation = Donation::where("donation_type", 0)->sum("donation_amount")) {
                            $subvention = Subvention::find($id);
                            Subvention::find($id)->update($request->except('_token', "sub_type", "moneyType"));
                            $suv = Subvention::find($id);
                            $lockerLogs = LockerLog::where("amount", $subvention->price)->where("created_at", $subvention->created_at)->where("type", LockerLog::TYPE_MINUS);
                            $lockerLogs->update([
                                "amount" => $suv->price,
                                "asset_id" => $suv->asset_id,
                                "asset_count" => $suv->asset_count,
                                "admin_id" => auth()->id(),
                            ]);
                        } else {
                            toastr()->error("لا توجد سيوله لهذه الاعانه");
                            return response()->json(["status" => 500, "message" => "لا توجد سيوله لهذه الاعانه"]);
                        }
                    } else {
                        if ($request->price <= $totalDonation = Donation::where("donation_type", 1)->sum("donation_amount")) {
                            $subvention = Subvention::find($id);
                            Subvention::find($id)->update($request->except('_token', "sub_type", "moneyType"));
                            $suv = Subvention::find($id);

                            $lockerLogs = LockerLog::where("amount", $subvention->price)->where("created_at", $subvention->created_at)->where("type", LockerLog::TYPE_MINUS);
                            $lockerLogs->update([
                                "amount" => $suv->price,
                                "asset_id" => $suv->asset_id,
                                "asset_count" => $suv->asset_count,
                                "admin_id" => auth()->id(),
                            ]);
                        } else {
                            toastr()->error("لا توجد سيوله لهذه الاعانه");
                            return response()->json(["status" => 500, "message" => "لا توجد سيوله لهذه الاعانه"]);
                        }
                    }
                } else {
                    $asset = Asset::find($request->asset_id);
                    $subvention = Subvention::find($id);
                    if (!$asset || !$subvention) {
                        abort(404, "البيانات غير موجودة");
                    }
                    $newCounter = $asset->counter + $subvention->asset_count;
                    if ($request->asset_count <= $newCounter) {
                        $asset->counter = $newCounter - $request->asset_count;
                        $asset->save();
                        $totalAssets = Asset::where("id", $request->asset_id)->first();
                        $subvention = Subvention::find($id);
                        Subvention::find($id)->update($request->except('_token', "sub_type", "moneyType"));
                        $suv = Subvention::find($id);

                        $lockerLogs = LockerLog::where("amount", $subvention->price)->where("created_at", $subvention->created_at)->where("type", LockerLog::TYPE_MINUS);
                        $lockerLogs->update([
                            "amount" => $suv->price,
                            "asset_id" => $suv->asset_id,
                            "asset_count" => $suv->asset_count,
                            "admin_id" => auth()->id(),
                        ]);
                    } else {
                        toastr()->error("لا توجد سيوله لهذه الاعانه");
                        return response()->json(["status" => 500,  "message" => "لا توجد سيوله لهذه الاعانه"]);
                    }
                }

                return response()->json(['status' => 200]);
            } else {
                toastr()->error("المستخدم غير موجود");
                return response()->json(["status" => 500,  "message" => "المستخدم غير موجود"]);
            }
        } catch (\Exception $e) {
            toastr()->error("لا توجد سيوله لهذه الاعانه");
            return response()->json(["status" => 500,  "message" => "لا توجد سيوله لهذه الاعانه"]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function delete(Request $request)
    {
        try {

            $subvention = Subvention::where('id', $request->id)->first();
            $lockerLogs = LockerLog::where("amount", $subvention->price)->where("created_at", $subvention->created_at)->where("type", LockerLog::TYPE_MINUS);
            $lockerLogs->delete();
            $asset = Asset::find($subvention->asset_id);
            if ($asset) {
                $asset->counter += Subvention::where('id', $request->id)->first()->asset_count;
                $asset->save();
            }
            Subvention::destroy($request->id);
            return redirect()->back();
            //            return response(['message'=>'تم الحذف بنجاح','status'=>200],200);
        } catch (\Exception $ex) {
            return response(['message' => $ex->getMessage(), 'status' => 400]);
        }
    }

    public function showSubventions(Request $request)
    {
        $subventions = Subvention::where('type', 'monthly')
            ->with('user')
            ->when($request->filled('ids'), function ($query) use ($request) {
                $ids = collect(explode(',', $request->ids))
                    ->filter(fn ($id) => trim((string) $id) !== '')
                    ->map(fn ($id) => (int) $id)
                    ->filter(fn ($id) => $id > 0)
                    ->values();

                if ($ids->isNotEmpty()) {
                    $query->whereIn('id', $ids);
                }
            })
            ->latest()
            ->get();

        if ($request->get('download') === 'pdf') {
            $html = view('admin.print.invoice', compact('subventions'))->render();
            $tempDir = storage_path('app/mpdf-temp');

            if (!File::exists($tempDir)) {
                File::makeDirectory($tempDir, 0755, true);
            }

            $mpdf = new Mpdf([
                'mode' => 'utf-8',
                'format' => 'A4',
                'orientation' => 'P',
                'margin_top' => 10,
                'margin_bottom' => 10,
                'margin_left' => 10,
                'margin_right' => 10,
                'tempDir' => $tempDir,
                'default_font' => 'dejavusans',
            ]);

            $mpdf->autoScriptToLang = true;
            $mpdf->autoLangToFont = true;
            $mpdf->SetDirectionality('rtl');
            $mpdf->WriteHTML($html);

            return response(
                $mpdf->Output('monthly-subventions-report-' . now()->format('Y-m-d-His') . '.pdf', 'S'),
                200,
                [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'attachment; filename="monthly-subventions-report-' . now()->format('Y-m-d-His') . '.pdf"',
                ]
            );
        }

        return view('admin/print/invoice', compact('subventions'));
    }

    public function showOneSubvention($id)
    {
        // where('type','once')->

        $subventions = Subvention::where('id', $id)->latest()->first();
        return view('admin/print/invoices2', compact('subventions'));
    }

    private function beneficiaryCategoriesForFilters()
    {
        $categories = BeneficiaryCategory::active()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name']);

        if ($categories->isNotEmpty()) {
            return $categories;
        }

        $usedCategoryIds = User::where('status', 'accepted')
            ->whereNotNull('beneficiary_category_id')
            ->distinct()
            ->pluck('beneficiary_category_id');

        return BeneficiaryCategory::query()
            ->whereIn('id', $usedCategoryIds)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name']);
    }
}
