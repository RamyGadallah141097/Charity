<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DonationType;
use App\Models\LockerLog;
use App\Models\Subvention;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class SubventionsLoansController extends Controller
{
    public function index(request $request)
    {
        $fromDate = $request->filled('from')
            ? Carbon::parse($request->from)->startOfDay()
            : now()->startOfMonth();
        $toDate = $request->filled('to')
            ? Carbon::parse($request->to)->endOfDay()
            : now()->endOfMonth();

        if ($request->ajax()) {
            $data = Subvention::query()
                ->where('type', 'once')
                ->with('user')
                ->whereBetween('created_at', [$fromDate, $toDate])
                ->latest()
                ->get();

            return Datatables::of($data)
                ->addColumn('action', function ($data) {
                    return '<a href="' . route('SubventionsLoans.print-receipt', $data->id) . '" target="_blank" class="btn btn-sm btn-success-light" title="طباعة">
                        <i class="fas fa-print"></i>
                    </a>';
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



    public function create()
    {
        $users = User::where('status', 'accepted')
            ->select('id', 'beneficiary_code', 'husband_name', 'wife_name')
            ->latest()
            ->get();
        $lockerTypes = DonationType::cashLockerTypes()->active()->orderBy('sort_order')->get();

        return view('admin/SubventionsLoans/create', compact('users', 'lockerTypes'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'donation_type_id' => ['required', 'exists:donation_types,id'],
            'price' => ['required', 'numeric', 'min:0.01'],
            'comment' => ['nullable', 'string'],
        ], [
            'user_id.required' => 'يرجى اختيار المستفيد.',
            'user_id.exists' => 'المستفيد المختار غير موجود.',
            'donation_type_id.required' => 'يرجى اختيار الخزنة التي سيتم الصرف منها.',
            'price.required' => 'يرجى إدخال مبلغ الإعانة الفردية.',
            'price.numeric' => 'مبلغ الإعانة الفردية يجب أن يكون رقمًا.',
            'price.min' => 'مبلغ الإعانة الفردية يجب أن يكون أكبر من صفر.',
        ]);

        DB::beginTransaction();

        try {
            $user = User::findOrFail($validated['user_id']);

            $lockerType = DonationType::cashLockerTypes()->find($validated['donation_type_id']);
            $lockerMoneyType = $lockerType?->lockerMoneyType();

            if (!$lockerType || !$lockerMoneyType) {
                toastr()->error('الخزنة المختارة غير صالحة للصرف المالي.');
                return redirect()->back()->withInput();
            }

            $pricePerUser = (float) $validated['price'];

            $availableBalance =
                LockerLog::where('moneyType', $lockerMoneyType)->where('type', LockerLog::TYPE_PLUS)->sum('amount')
                - LockerLog::where('moneyType', $lockerMoneyType)->where('type', LockerLog::TYPE_MINUS)->sum('amount');

            if ($availableBalance < $pricePerUser) {
                toastr()->error('رصيد الخزنة المختارة لا يكفي لصرف الإعانات الفردية.');
                return redirect()->back()->withInput();
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
        $subventions = Subvention::where('type', 'once')->latest()->get();
        return view('admin/print/subvention-print', compact('subventions'));
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
}
