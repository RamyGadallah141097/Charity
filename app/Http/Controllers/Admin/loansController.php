<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoanRequest;
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
                })->editColumn('borrower_id', function ($loans) {
                    return $loans->borrower->name;
                })
                ->editColumn('borrower_phone', function ($loans) {
                    $phone = $loans->borrower_phone;
                    return '<a href="tel:' . $phone . '">' . $phone . '</a>';
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin.loans.index');
        }
    }

    // مشترك ف خدمة الاهلى فووون

    public function createLoans()
    {
        $borrowers = Borrower::all();
        return view('admin.loans.parts.create', ["borrowers" => $borrowers]);
    }

    public function searchBorrowers(Request $request)
    {
        try {

            $query = $request->input('borrower_name');

            // Make sure the table exists and the column name is correct
            $borrowers = Borrower::where('name', 'like', "%{$query}%")->get();
            return response()->json($borrowers);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function storeLoans(LoanRequest $request)
    {


        try {
            Loan::create($request->all());
            return response()->json(['status' => 200]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function personLoans(Request $request, $id)
    {
        if ($request->ajax()) {

            $loans = Loan::where('borrower_id', $id)->get();
            return datatables()->of($loans)
                ->addColumn('action', function ($loans) {
                    return '
                        <button class="btn btn-pill btn-secondary-light"
                            data-id="' . $loans->id . '">
                            اللى جوه القروض
                            <i class="fas fa-money-check-alt"></i>
                        </button>
                    ';
                })
                ->editColumn('borrower_id', function ($loans) {
                    return $loans->borrower->name;
                })
                ->editColumn('borrower_phone', function ($loans) {
                    $phone = $loans->borrower->phone; // تأكد أن العلاقة صحيحة
                    return '<a href="tel:' . $phone . '">' . $phone . '</a>';
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin.loans.indexloan');
        }
    }
}
