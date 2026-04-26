<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BeneficiaryCategory;
use App\Models\DonationType;
use App\Models\LockerLog;
use App\Models\Subvention;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Mpdf\Mpdf;
use Yajra\DataTables\DataTables;

class SubventionsLoansController extends Controller
{
    public function index(request $request)
    {
        [$fromDate, $toDate] = $this->resolveDateRange($request);

        if ($request->ajax()) {
            $data = Subvention::query()
                ->where('type', 'once')
                ->with('user')
                ->whereBetween('created_at', [$fromDate, $toDate])
                ->latest()
                ->get();

            return Datatables::of($data)
                ->addColumn('row_checkbox', function ($data) {
                    return '<div class="d-flex justify-content-center">
                        <input type="checkbox" class="subvention-row-checkbox" value="' . $data->id . '">
                    </div>';
                })
                ->addColumn('action', function ($data) {
                    return '<div class="d-flex" style="gap: 5px;">
                        <a href="' . route('SubventionsLoans.print-receipt', $data->id) . '" target="_blank" class="btn btn-sm btn-success-light" title="طباعة">
                            <i class="fas fa-print"></i>
                        </a>
                        <button class="btn btn-sm btn-danger-light" data-toggle="modal" data-target="#delete_modal" data-id="' . $data->id . '" data-title="هذه الإعانة الفردية" title="حذف">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>';
                })
                ->addColumn('beneficiary_code', function ($data) {
                    return $data->user->beneficiary_code ?? '-';
                })
                ->editColumn('user_id', function ($data) {
                    return ($data->user->wife_name ?: $data->user->husband_name) ?? 'تم حذفه';
                })
                ->editColumn('price', function ($data) {
                    return " مبلغ قدره : " . $data->price . " جنيه ";
                })
                ->editColumn('type', function ($data) {
                    return 'إعانة فردية';
                })
                ->editColumn('created_at', function ($data) {
                    return $data->created_at->format('d/m/Y');
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            $totalSpent = Subvention::where('type', 'once')
                ->whereBetween('created_at', [$fromDate, $toDate])
                ->sum('price');

            $periodLabel = $request->filled('from') && $request->filled('to')
                ? 'إجمالي المنصرف خلال الفترة من ' . $fromDate->format('Y-m-d') . ' إلى ' . $toDate->format('Y-m-d')
                : 'إجمالي المنصرف خلال الشهر الحالي';

            return view('admin/SubventionsLoans/index', compact('fromDate', 'toDate', 'totalSpent', 'periodLabel'));
        }
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
            ->with('beneficiaryCategory:id,name')
            ->select('id', 'beneficiary_code', 'husband_name', 'wife_name', 'beneficiary_category_id')
            ->latest()
            ->get();
        $beneficiaryCategories = $this->beneficiaryCategoriesForFilters();
        $lockerTypes = DonationType::cashLockerTypes()->active()->orderBy('sort_order')->get();

        return view('admin/SubventionsLoans/create', compact('users', 'beneficiaryCategories', 'lockerTypes'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_ids' => ['required', 'array', 'min:1'],
            'user_ids.*' => ['required', 'exists:users,id'],
            'donation_type_id' => ['required', 'exists:donation_types,id'],
            'price' => ['required', 'numeric', 'min:0.01'],
            'comment' => ['nullable', 'string'],
        ], [
            'user_ids.required' => 'يرجى اختيار مستفيد واحد على الأقل.',
            'user_ids.array' => 'قائمة المستفيدين غير صالحة.',
            'user_ids.min' => 'يرجى اختيار مستفيد واحد على الأقل.',
            'user_ids.*.exists' => 'أحد المستفيدين المختارين غير موجود.',
            'donation_type_id.required' => 'يرجى اختيار الخزنة التي سيتم الصرف منها.',
            'price.required' => 'يرجى إدخال مبلغ الإعانة الفردية.',
            'price.numeric' => 'مبلغ الإعانة الفردية يجب أن يكون رقمًا.',
            'price.min' => 'مبلغ الإعانة الفردية يجب أن يكون أكبر من صفر.',
        ]);

        DB::beginTransaction();

        try {
            $userIds = collect($validated['user_ids'])->filter()->unique()->values();
            $users = User::whereIn('id', $userIds)->get()->keyBy('id');

            if ($users->isEmpty()) {
                toastr()->error('المستفيدون المختارون غير موجودين.');
                return redirect()->back()->withInput();
            }

            $lockerType = DonationType::cashLockerTypes()->find($validated['donation_type_id']);
            $lockerMoneyType = $lockerType?->lockerMoneyType();

            if (!$lockerType || !$lockerMoneyType) {
                toastr()->error('الخزنة المختارة غير صالحة للصرف المالي.');
                return redirect()->back()->withInput();
            }

            $pricePerUser = (float) $validated['price'];
            $totalRequiredAmount = $pricePerUser * $users->count();

            $availableBalance =
                LockerLog::where('moneyType', $lockerMoneyType)->where('type', LockerLog::TYPE_PLUS)->sum('amount')
                - LockerLog::where('moneyType', $lockerMoneyType)->where('type', LockerLog::TYPE_MINUS)->sum('amount');

            if ($availableBalance < $totalRequiredAmount) {
                toastr()->error('رصيد الخزنة المختارة لا يكفي لصرف الإعانات الفردية.');
                return redirect()->back()->withInput();
            }

            foreach ($userIds as $userId) {
                $user = $users->get($userId);

                if (!$user) {
                    continue;
                }

                $subvention = Subvention::create([
                    'user_id' => $user->id,
                    'price' => $pricePerUser,
                    'type' => 'once',
                    'comment' => $validated['comment'] ?? null,
                ]);

                LockerLog::create([
                    "moneyType" => $lockerMoneyType,
                    "amount" => $pricePerUser,
                    "type" => LockerLog::TYPE_MINUS,
                    "admin_id" => auth()->id(),
                    "subvention_id" => $subvention->id,
                    "comment" => "صرف إعانة فردية من خزنة " . $lockerType->name . " إلى " . ($user->husband_name ?: $user->wife_name)
                        . " ورقم هاتفه " . ($user->nearest_phone ?: "غير متوفر"),
                ]);
            }

            DB::commit();
            toastr()->success('تمت إضافة الإعانة الفردية بنجاح');
            return redirect()->route('SubventionsLoans.index');
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
        abort(404);
    }


    public function update(Request $request, $id)
    {
        abort(404);
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
            $subvention = Subvention::findOrFail($request->id);
            LockerLog::where('subvention_id', $subvention->id)
                ->where('type', LockerLog::TYPE_MINUS)
                ->delete();
            $subvention->delete();
            return redirect()->back();
            //            return response(['message'=>'تم الحذف بنجاح','status'=>200],200);
        } catch (\Exception $ex) {
            return response(['message' => $ex->getMessage(), 'status' => 400]);
        }
    }

    public function showSubventions()
    {
        $request = request();
        $subventions = Subvention::where('type', 'once')
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
            $html = view('admin.print.once-subventions-report', compact('subventions'))->render();
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
                $mpdf->Output('once-subventions-report-' . now()->format('Y-m-d-His') . '.pdf', 'S'),
                200,
                [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'attachment; filename="once-subventions-report-' . now()->format('Y-m-d-His') . '.pdf"',
                ]
            );
        }

        return view('admin/print/once-subventions-report', compact('subventions'));
    }

    public function printReceipt(Subvention $subvention)
    {
        $subvention->load('user');

        $lockerLog = LockerLog::with('admin')
            ->where('subvention_id', $subvention->id)
            ->where('type', LockerLog::TYPE_MINUS)
            ->latest()
            ->first();

        return view('admin/print/one-subvention-receipt', compact('subvention', 'lockerLog'));
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
