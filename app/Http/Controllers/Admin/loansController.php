<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoanRequest;
use App\Models\Donor;
use App\Models\LockerLog;
use App\Models\PersonalLoan;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
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
                            <a href="' . route("person.loans", $loans->id) . '" class="btn btn-pill btn-secondary-light"
                                    data-id="' . $loans->id . '"
                                    >
                                     القروض
                                    <i class="fas fa-money-check-alt"></i>
                            </a>
                       ';
                })
                ->editColumn('borrower_id', function ($loans) {
                    return $loans->borrower ? $loans->borrower->name : "-";
                })
                ->editColumn('borrower_phone', function ($loans) {
                    $phone = $loans->borrower->phone;
                    return '<a href="tel:' . $phone . '">' . $phone . '</a>';
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            $setting = Setting::first();
            return view('admin/loans/index' , compact("setting"));
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
//        solve error
        $borrowers = Borrower::get();
        return view('admin/loans/parts/create', ["borrowers" => $borrowers]);
    }

    public function searchBorrowers(Request $request)
    {
        
        try {

            
            $borrowers = Borrower::where('id', $request->input("borrower_id"))
                // ->doesntHave('loans')
                ->get();

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
                    // dd($request->all() , 1);

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
                        $pLoan = PersonalLoan::create([
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
            $loans = PersonalLoan::where('loan_id', $id)->get();

            if ($loans->isEmpty()) {
                return response()->json(['error' => 'لا توجد قروض لهذا المقترض'], 404);
            }

            return datatables()->of($loans)
                ->addColumn('borrower_name', function ($loan) {
                    return $loan->borrower ? $loan->borrower->name : 'غير معروف';
                })
                ->editColumn('borrower_phone', function ($loan) {
                    return $loan->borrower ? '<a href="tel:' . $loan->borrower->phone . '">' . $loan->borrower->phone . '</a>' : 'غير متوفر';
                })
                ->addColumn('status', function ($loan) {
                    return $loan->status == 1 ? '<span class="badge badge-success">مدفوع</span>' : '<span class="badge badge-warning">غير مدفوع</span>';
                })
                ->addColumn('action', function ($loan) {
                    return '
                    <button class="btn btn-success pay-btn" data-id="' . $loan->id . '"  data-amount="' . $loan->amount . '"  data-status="'.$loan->status.'">
                        دفع <i class="fas fa-money-check-alt"></i>
                    </button>
                    ';
                })
                ->rawColumns(['borrower_phone', 'status', 'action'])
                ->make(true);
        }
        $total = Loan::where('id', $id)->first()->loan_amount;
        $totalIn = Loan::where("id", $id)->first()->loan_amount - PersonalLoan::where('loan_id', $id)->where('status', 0)->sum('amount');
        $totalOut = PersonalLoan::where('loan_id', $id)->where('status', 0)->sum('amount');
        $pay = Loan::where('id', $id)->value('type');

//        return view('admin/loans/indexloan', compact('id' , "totalIn" , "totalOut" , "total" , "pay"));
        return view('admin.loans.indexloan', compact('id', 'totalIn', 'totalOut', 'total', 'pay'));

//        solve the path .
    }

    public function checkout($id)
    {
    
        try {
            $loan = Loan::find($id);
            if (LockerLog::where("moneyType" , LockerLog::moneyTypeLoans)->sum("amount") >= $loan->loan_amount){
                $borrower = $loan->borrower;
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
    public function payLoan( Request $request , $id)
    {
        //get the loan
        // dd($id);
        try {
            DB::beginTransaction();

            $loan = PersonalLoan::find($id);
            $borrower = $loan->borrower;
            $amount = $request->amount;
            $totalLoan = PersonalLoan::where("loan_id" , $loan->loan_id)->where("status" , 0)->sum("amount"); 
            //check if loan exist
            if (!$loan) {
                return response()->json(['error' => 'القرض غير موجود'], 404);
            }
//        check if amount smaller than all loan amount
            if ($amount > $totalLoan){ // check the pay amount is smaller than amount must pay
                return response()->json(['error' => 'القيمه المدفوعه اكبر من القيمه الاقساط المتبقيه'], 404);
            }else{

                if ($amount > $loan->amount){  // if he rich and pay more
                    $loan->status = 1;
                    $loan->save();
                    $amount = $amount - $loan->amount;
                    while ($amount > 0) {
                        $personal_loan = PersonalLoan::where("loan_id", $loan->loan_id)->where("status", 0)->latest()->first();
                        if ($personal_loan) {
                            if ($amount == $personal_loan->amount) {
                                $personal_loan->status = 1;
                                $personal_loan->save();
                                $amount = 0;
                            } elseif ($personal_loan->amount > $amount) {
                                $personal_loan->amount = $personal_loan->amount - $amount;
                                $personal_loan->save();
                                $amount = 0;
                            } else {
                                $personal_loan->status = 1;
                                $amount = $amount - $personal_loan->amount;
                                $personal_loan->save();
                            }

                        }
                    }
                }elseif ($amount < $loan->amount){
                    $loan->status = 1;
                    $amount = $loan->amount - $amount;
                    $loan->amount = $loan->amount -  $amount;
                    $loan->save();
                    PersonalLoan::create([
                        "loan_id" => $loan->loan->id,
                        "borrower_id" => $loan->borrower_id,
                        "amount" => $amount,
                        "month" => \Carbon\Carbon::parse($loan->month)
                            ->addMonths($loan->loan->month)
                            ->format("Y-m-d"),
                        "status" => 0

                    ]);
                }else{
                    $loan->status = 1;
                    $loan->save();
                }


                // LockerLog::create([
                //     "moneyType" => LockerLog::moneyTypeLoans,
                //     "amount" => $request->amount,
                //     "type" => LockerLog::TYPE_PLUS,
                //     "admin_id" => auth()->id(),
                //     "donation_id" => null,
                //     "subvention_id" => null,
                //     "loan_id" => $loan->id,
                //     "comment" => "  دفع قرض من  " . ($loan->borrower->name ??  "مجهول") .
                //         " ورقم هاتفه " . ($loan->borrower->phone   ?? "غير متوفر"),
                // ]);
            }

            LockerLog::create([
                "moneyType" => LockerLog::moneyTypeLoans,
                "amount" => $request->amount,
                "type" => LockerLog::TYPE_PLUS,
                "admin_id" => auth()->id(),
                "donation_id" => null,
                "subvention_id" => null,
                "loan_id" => $loan->loan_id,
                "comment" => "  دفع قرض من  " . ($borrower->name ??  "مجهول") .
                    " ورقم هاتفه " . ($borrower->phone   ?? "غير متوفر"),
            ]);

            DB::commit();
            return response()->json(['message' => 'تم دفع القرض بنجاح']);
        }catch (\Exception $e){
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function printLoan()
    {
        $loans = Loan::all();
        return view('admin/print/printLoan',compact('loans'));
    }
}
