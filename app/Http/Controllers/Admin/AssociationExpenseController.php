<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AssociationExpense;
use App\Models\ExpenseType;
use App\Models\LockerLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class AssociationExpenseController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $expenses = AssociationExpense::with(['expenseType', 'admin'])->latest()->get();

            return DataTables::of($expenses)
                ->addColumn('type_name', function ($expense) {
                    return optional($expense->expenseType)->name ?? '-';
                })
                ->addColumn('created_by', function ($expense) {
                    return optional($expense->admin)->name ?? '-';
                })
                ->editColumn('amount', function ($expense) {
                    return number_format((float) $expense->amount, 2);
                })
                ->editColumn('transaction_date', function ($expense) {
                    return optional($expense->transaction_date)->format('Y-m-d') ?: '-';
                })
                ->addColumn('action', function ($expense) {
                    return '<div class="d-flex">'
                        . '<button type="button" data-id="' . $expense->id . '" class="btn btn-pill btn-info-light editBtn"><i class="fa fa-edit"></i></button>'
                        . '<button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal" data-id="' . $expense->id . '" data-title="' . e(optional($expense->expenseType)->name ?? 'مصروف') . '"><i class="fas fa-trash"></i></button>'
                        . '</div>';
                })
                ->escapeColumns([])
                ->make(true);
        }

        return view('admin.association-expenses.index', [
            'balance' => $this->associationBalance(),
        ]);
    }

    public function create()
    {
        $expenseTypes = ExpenseType::active()->orderBy('name')->get();
        return view('admin.association-expenses.parts.create', [
            'expenseTypes' => $expenseTypes,
            'balance' => $this->associationBalance(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'expense_type_id' => 'required|exists:expense_types,id',
            'amount' => 'required|numeric|min:0.01',
            'transaction_date' => 'required|date',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        if ($data['amount'] > $this->associationBalance()) {
            return response()->json(['message' => ['amount' => ['رصيد خزنة الجمعية لا يكفي لهذا المصروف.']]], 422);
        }

        DB::transaction(function () use ($data) {
            $expense = AssociationExpense::create($data + [
                'admin_id' => auth()->id(),
            ]);

            $log = new LockerLog([
                'moneyType' => LockerLog::moneyTypeAssociation,
                'type' => LockerLog::TYPE_MINUS,
                'admin_id' => auth()->id(),
                'expense_id' => $expense->id,
                'amount' => $expense->amount,
                'comment' => 'مصروف جمعية: ' . (optional($expense->expenseType)->name ?? 'مصروف'),
            ]);
            $log->created_at = $expense->transaction_date;
            $log->updated_at = $expense->transaction_date;
            $log->save();
        });

        return response()->json(['status' => 200]);
    }

    public function edit(AssociationExpense $associationExpense)
    {
        $expenseTypes = ExpenseType::active()->orderBy('name')->get();
        return view('admin.association-expenses.parts.edit', [
            'expense' => $associationExpense,
            'expenseTypes' => $expenseTypes,
            'balance' => $this->associationBalance() + $associationExpense->amount,
        ]);
    }

    public function update(Request $request, AssociationExpense $associationExpense)
    {
        $data = $request->validate([
            'expense_type_id' => 'required|exists:expense_types,id',
            'amount' => 'required|numeric|min:0.01',
            'transaction_date' => 'required|date',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        if ($data['amount'] > ($this->associationBalance() + $associationExpense->amount)) {
            return response()->json(['message' => ['amount' => ['رصيد خزنة الجمعية لا يكفي بعد التعديل.']]], 422);
        }

        DB::transaction(function () use ($associationExpense, $data) {
            $associationExpense->update($data);

            $lockerLog = LockerLog::where('expense_id', $associationExpense->id)->first();
            if ($lockerLog) {
                $lockerLog->update([
                    'amount' => $associationExpense->amount,
                    'comment' => 'مصروف جمعية: ' . (optional($associationExpense->expenseType)->name ?? 'مصروف'),
                ]);
                $lockerLog->created_at = $associationExpense->transaction_date;
                $lockerLog->updated_at = $associationExpense->transaction_date;
                $lockerLog->save();
            }
        });

        return response()->json(['status' => 200]);
    }

    public function delete(Request $request)
    {
        $expense = AssociationExpense::findOrFail($request->id);
        $expense->delete();

        return response()->json(['status' => 200, 'message' => 'تم حذف المصروف بنجاح']);
    }

    private function associationBalance(): float
    {
        return (float) LockerLog::where('moneyType', LockerLog::moneyTypeAssociation)->where('type', LockerLog::TYPE_PLUS)->sum('amount')
            - (float) LockerLog::where('moneyType', LockerLog::moneyTypeAssociation)->where('type', LockerLog::TYPE_MINUS)->sum('amount');
    }
}
