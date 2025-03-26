<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoanRequest;
use App\Models\Donor;
use App\Models\LockerLog;
use App\Models\PersonalLoan;
use App\Models\Setting;
use Yajra\DataTables\DataTables;
use App\Models\Borrower;
use App\Models\Loan;
use Illuminate\Http\Request;

class loansController extends Controller
{
    public function indexLoans(Request $request)
    {
        if ($request->ajax()) {
            $loans = Loan::latest()->get();
            return Datatables::of($loans)
                ->addColumn('action', function ($loans) {
                    return '
                            <a href="' . route("person.loans", $loans->borrower_id) . '" class="btn btn-pill btn-secondary-light"
                                    data-id="' . $loans->id . '">
                                     القروض
                                    <i class="fas fa-money-check-alt"></i>
                            </a>
                       ';
                })
                ->editColumn('borrower_id', function ($loans) {
                    return $loans->borrower->name;
                })
                ->editColumn('borrower_phone', function ($loans) {
                    $phone = $loans->borrower_phone;
                    return '<a href="tel:' . $phone . '">' . $phone . '</a>';
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            $setting = Setting::first();
            return view('admin.loans.index' , compact("setting"));
        }
    }

    // مشترك ف خدمة الاهلى فووون

    public function createLoans()
    {
//        $borrowers = Borrower::all();
//        foreach ($borrowers as $borrower) {
//            if($borrower->loans()->count() > 0){
//                $borrowers = $borrowers->except($borrower->id);
//            }
//        }
        $borrowers = Borrower::doesntHave('loans')->get();
        return view('admin.loans.parts.create', ["borrowers" => $borrowers]);
    }

    public function searchBorrowers(Request $request)
    {
        try {

            $query = $request->input('borrower_name');

            // Make sure the table exists and the column name is correct
            $borrowers = Borrower::where('name', 'like', "%{$query}%")->doesntHave('loans')->get();
            return response()->json($borrowers);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function searchBorrower(Request $request)
    {
        $borrower = Borrower::find($request->id);
        return response()->json($borrower);
    }



    public function storeLoans(LoanRequest $request)
    {
        if ((LockerLog::where("moneyType", LockerLog::moneyTypeLoans)->where("type" , LockerLog::TYPE_PLUS)->sum("amount")) - (LockerLog::where("moneyType", LockerLog::moneyTypeLoans)->where("type" , LockerLog::TYPE_MINUS)->sum("amount")) >= $request->loan_amount ) {
            try {
                $data = $request->all();



                if ($request->type == 0) {
                    $data['isStarted'] = now()->format("Y-m");
                    $amount = $request->loan_amount / 10;
                    $loan = Loan::create($data);
                    for ($i = 0; $i < 10; $i++) {
                        PersonalLoan::create([
                            "amount" => $amount,
                            "loan_id" => $loan->id,
                            "borrower_id" => $request->borrower_id,
                            "month" => now()->addMonths($i)->format("Y-m-d"),
                            "status" => 0
                        ]);
                    }
                    $borrower = Borrower::find($request->borrower_id);
                    LockerLog::create([
                        "moneyType" => LockerLog::moneyTypeLoans,
                        "amount" => $request->loan_amount,
                        "type" => LockerLog::TYPE_MINUS,
                        "admin_id" => auth()->id(),
                        "donation_id " => null,
                        "subvention_id" => null,
                        "loan_id" => $loan->id,
                        "comment" => "قرض جديد الي " . ($borrower ? $borrower->name : "مجهول") .
                            " ورقم هاتفه " . ($borrower ? $borrower->phone : "غير متوفر"),
                    ]);
                }else{
                    $amount = $request->loan_amount / 10;
                    $loan = Loan::create($data);
                    for ($i = 0; $i < 10; $i++) {
                        PersonalLoan::create([
                            "amount" => $amount,
                            "loan_id" => $loan->id,
                            "borrower_id" => $request->borrower_id,
                            "month" => now()->addMonths($i)->format("Y-m-d"),
                            "status" => 0
                        ]);
                    }
                }




                return response()->json(['status' => 200]);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        } else {
            toastr()->error("لا يوجد رصيد كافي");
            return response()->json(['message' => 'لا يوجد رصيد كافي'], 500);
        }
    }


    public function personLoans(Request $request, $id)
    {
        if ($request->ajax()) {
            $loans = PersonalLoan::where('borrower_id', $id)->get();

            if ($loans->isEmpty()) {
                return response()->json(['error' => 'لا توجد قروض لهذا المقترض'], 404);
            }

            return datatables()->of($loans)
                ->addColumn('borrower_name', function ($loan) {
                    return $loan->borrower ? $loan->borrower->name : 'غير معروف';
                })
                ->addColumn('borrower_phone', function ($loan) {
                    return $loan->borrower ? '<a href="tel:' . $loan->borrower->phone . '">' . $loan->borrower->phone . '</a>' : 'غير متوفر';
                })
                ->addColumn('status', function ($loan) {
                    return $loan->status == 1 ? '<span class="badge badge-success">مدفوع</span>' : '<span class="badge badge-warning">غير مدفوع</span>';
                })
                ->addColumn('action', function ($loan) {
                    return '
                    <button class="btn btn-success pay-btn" data-id="' . $loan->id . '">
                        دفع <i class="fas fa-money-check-alt"></i>
                    </button>
                    ';
                })
                ->rawColumns(['borrower_phone', 'status', 'action'])
                ->make(true);
        }
        $total = PersonalLoan::where('borrower_id', $id)->sum('amount');
        $totalIn = PersonalLoan::where('borrower_id', $id)->where('status', 1)->sum('amount');
        $totalOut = PersonalLoan::where('borrower_id', $id)->where('status', 0)->sum('amount');
        $pay = Loan::where('borrower_id', $id)->value('type');

        return view('admin.loans.indexloan', compact('id' , "totalIn" , "totalOut" , "total" , "pay"));
    }

    public function checkout($id)
    {
        try {
            $loan = Borrower::find($id)->loans()->first();
            if (LockerLog::where("moneyType" , LockerLog::moneyTypeLoans)->sum("amount") >= $loan->loan_amount){
                $borrower = Borrower::find($id);
                LockerLog::create([
                    "moneyType" => LockerLog::moneyTypeLoans,
                    "amount" => $loan->loan_amount,
                    "type" => LockerLog::TYPE_MINUS,
                    "admin_id" => auth()->id(),
                    "donation_id" => null,
                    "subvention_id" => null,
                    "loan_id" => $loan->id,
                    "comment" => "قرض جديد الي " . ($borrower ? $borrower->name : "مجهول") .
                        " ورقم هاتفه " . ($borrower ? $borrower->phone : "غير متوفر"),
                ]);
                $loan->isStarted = now()->format('Y-m-d');
                $loan->type = 0;
                $loan->save();
                return response()->json(['status' => 200 , "message" => "تم بنجاح"]);
            }
        }catch (\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function payLoan($id)
    {
        $loan = PersonalLoan::find($id);
        if (!$loan) {
            return response()->json(['error' => 'القرض غير موجود'], 404);
        }
        LockerLog::create([
            "moneyType" => LockerLog::moneyTypeLoans,
            "amount" => $loan->amount,
            "type" => LockerLog::TYPE_PLUS,
            "admin_id" => auth()->id(),
            "donation_id" => null,
            "subvention_id" => null,
            "loan_id" => $loan->id,
            "comment" => "  دفع قرض من  " . ($loan->borrower_id ??  "مجهول") .
                " ورقم هاتفه " . ($loan->borrower->phone   ?? "غير متوفر"),
        ]);
        $loan->status = 1;
        $loan->save();

        return response()->json(['message' => 'تم دفع القرض بنجاح']);
    }

    public function printLoan()
    {
        $loans = Loan::all();
        return view('Admin.print.printLoan',compact('loans'));
    }
}
