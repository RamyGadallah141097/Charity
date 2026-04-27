<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssociationRevenue;
use App\Models\LockerLog;
use App\Models\RevenueType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class AssociationRevenueController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $revenues = AssociationRevenue::with(['revenueType', 'admin'])->latest()->get();

            return DataTables::of($revenues)
                ->addColumn('type_name', function ($revenue) {
                    return optional($revenue->revenueType)->name ?? '-';
                })
                ->addColumn('created_by', function ($revenue) {
                    return optional($revenue->admin)->name ?? '-';
                })
                ->editColumn('amount', function ($revenue) {
                    return number_format((float) $revenue->amount, 2);
                })
                ->editColumn('transaction_date', function ($revenue) {
                    return optional($revenue->transaction_date)->format('Y-m-d') ?: '-';
                })
                ->addColumn('action', function ($revenue) {
                    $buttons = [];

                    if (auth()->guard('admin')->user()->can('association.revenues.edit')) {
                        $buttons[] = '<button type="button" data-id="' . $revenue->id . '" class="btn btn-pill btn-info-light editBtn"><i class="fa fa-edit"></i></button>';
                    }

                    if (auth()->guard('admin')->user()->can('association.revenues.delete')) {
                        $buttons[] = '<button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal" data-id="' . $revenue->id . '" data-title="' . e(optional($revenue->revenueType)->name ?? 'إيراد') . '"><i class="fas fa-trash"></i></button>';
                    }

                    return '<div class="d-flex">' . implode('', $buttons) . '</div>';
                })
                ->escapeColumns([])
                ->make(true);
        }

        return view('admin.association-revenues.index');
    }

    public function create()
    {
        $revenueTypes = RevenueType::active()->orderBy('name')->get();
        return view('admin.association-revenues.parts.create', compact('revenueTypes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'revenue_type_id' => 'required|exists:revenue_types,id',
            'amount' => 'required|numeric|min:0.01',
            'transaction_date' => 'required|date',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($data) {
            $revenue = AssociationRevenue::create($data + [
                'admin_id' => auth()->id(),
            ]);

            $log = new LockerLog([
                'moneyType' => LockerLog::moneyTypeAssociation,
                'type' => LockerLog::TYPE_PLUS,
                'admin_id' => auth()->id(),
                'revenue_id' => $revenue->id,
                'amount' => $revenue->amount,
                'comment' => 'إيراد جمعية: ' . (optional($revenue->revenueType)->name ?? 'إيراد'),
            ]);
            $log->created_at = $revenue->transaction_date;
            $log->updated_at = $revenue->transaction_date;
            $log->save();
        });

        return response()->json(['status' => 200]);
    }

    public function edit(AssociationRevenue $associationRevenue)
    {
        $revenueTypes = RevenueType::active()->orderBy('name')->get();
        return view('admin.association-revenues.parts.edit', [
            'revenue' => $associationRevenue,
            'revenueTypes' => $revenueTypes,
        ]);
    }

    public function update(Request $request, AssociationRevenue $associationRevenue)
    {
        $data = $request->validate([
            'revenue_type_id' => 'required|exists:revenue_types,id',
            'amount' => 'required|numeric|min:0.01',
            'transaction_date' => 'required|date',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($associationRevenue, $data) {
            $associationRevenue->update($data);

            $lockerLog = LockerLog::where('revenue_id', $associationRevenue->id)->first();
            if ($lockerLog) {
                $lockerLog->update([
                    'amount' => $associationRevenue->amount,
                    'comment' => 'إيراد جمعية: ' . (optional($associationRevenue->revenueType)->name ?? 'إيراد'),
                ]);
                $lockerLog->created_at = $associationRevenue->transaction_date;
                $lockerLog->updated_at = $associationRevenue->transaction_date;
                $lockerLog->save();
            }
        });

        return response()->json(['status' => 200]);
    }

    public function delete(Request $request)
    {
        $revenue = AssociationRevenue::findOrFail($request->id);
        $revenue->delete();

        return response()->json(['status' => 200, 'message' => 'تم حذف الإيراد بنجاح']);
    }
}
