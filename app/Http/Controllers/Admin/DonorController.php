<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDonate;
use App\Models\Donation;
use App\Models\Donor;
use App\Models\Loan;
use App\Models\LockerLog;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DonorController extends Controller
{

    public function index(request $request)
    {

        if ($request->ajax()) {
            $donors = Donor::latest()->with("donation")->get();
            return Datatables::of($donors)
                ->addColumn('action', function ($donors) {
                    $editButton = '';
                    $deleteButton = '';

                    $editButton = '
                            <button type="button" data-id="' . $donors->id . '" class="btn btn-pill btn-info-light editBtn">
                                <i class="fa fa-edit"></i>
                            </button>
                        ';

                    // $deleteButton = '
                    //     <button class="btn btn-pill btn-danger-light donationReturnBtn" data-toggle="modal" data-target="#delete_modal"
                    //             data-id="' . $donors->id . '" data-title="' . $donors->name . '">
                    //         <i class="fas fa-trash"></i>
                    //     </button>
                    // ';
                    if ($donors->has('donation') && $donors->donation->contains('donation_type', 2)) {
                        $totalLoansDonations = $donors->donation->where('donation_type', 2)->sum("donation_amount");
                        // $totalLoansDonations = $DonationsAmount - LockerLog::where("donor_id" , $donors->id)->where("moneyType" , LockerLog::moneyTypeLoans)->where("type" , LockerLog::TYPE_MINUS)->sum("amount");

                        $totalLoanAmount = LockerLog::where("moneyType", LockerLog::moneyTypeLoans)->where("type", LockerLog::TYPE_PLUS)->sum("amount") - LockerLog::where("moneyType", LockerLog::moneyTypeLoans)->where("type", LockerLog::TYPE_MINUS)->sum("amount");
                        $returnMoneyBtn = '
                                    <button
                                        class="btn btn-pill btn-success donationReturnBtn"
                                        data-toggle="modal"
                                        data-target="#donationReturnModal"
                                        data-id="' . $donors->id . '"
                                        data-avalable ="' . $totalLoanAmount . '"
                                        data-amount ="' . $totalLoansDonations . '"
                                        data-title="' . $donors->name . '">
                                        <i class="fas fa-hand-holding-usd"></i>
                                    </button>
                                ';
                    } else {
                        $returnMoneyBtn = "";
                    }

                    return '<div class="d-flex">' . $editButton . $deleteButton . $returnMoneyBtn . '</div>';
                })

                ->editColumn('notes', function ($donors) {
                    return '<span class="small-text-hover">' . ($donors->notes ?? '-----') . '</span>';
                })
                ->editColumn('created_at', function ($donors) {
                    return $donors->created_at ? $donors->created_at->format('d-m-y') : "--";
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin/donors/index');
        }
    }

    public function returnDonationMoney(Request $request)
    {
        try {
            $donor = Donor::with("donation")->find($request->donor_id);
            $totalLoansDonations = $donor->donation->where('donation_type', 2)->sum("donation_amount");
            $returnAmount = $request->DonationReturnAmount;

            if ($returnAmount > $totalLoansDonations) { // check if returned amount smaller than donation amount
                return response()->json([
                    'success' => false,
                    'message' => 'القيمه المسترده اكبر من القيمه المتبرع بها  '
                ]);
            }

            $totalLoanAmount = LockerLog::where("moneyType", LockerLog::moneyTypeLoans)->where("type", LockerLog::TYPE_PLUS)->sum("amount") - LockerLog::where("moneyType", LockerLog::moneyTypeLoans)->where("type", LockerLog::TYPE_MINUS)->sum("amount");

            if ($returnAmount >  $totalLoanAmount) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا توجد اموال كافيه في خزنة القروض  '
                ]);
            }

            $donations = Donation::where("donation_type", 2)
                ->where("donor_id", $request->donor_id)
                ->get();


            foreach ($donations as $donation) {
                if ($returnAmount <= 0) break;

                if ($donation->donation_amount <= $returnAmount) {
                    $returnAmount -= $donation->donation_amount;

                    $donation->donation_amount = 0;
                } else {
                    $donation->donation_amount -= $returnAmount;
                    $returnAmount = 0;
                }

                $donation->save();
            }



            LockerLog::create([
                "moneyType" => LockerLog::moneyTypeLoans,
                "amount" => $request->DonationReturnAmount,
                "type" => LockerLog::TYPE_MINUS,
                "admin_id" => auth()->id(),
                "donation_id" => null,
                "subvention_id" => null,
                "donor_id" => $donor->id,
                "loan_id" => null,
                "comment" => " تم استرداد أموال المتبرع " . $donor->name . " في يوم " . \Carbon\Carbon::now()->format("Y-m-d"),
            ]);

            return response()->json(['status' => 'success', 'message' => 'Amount returned successfully']);
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ]);
        }
    }

    public function create()
    {
        return view('admin/donors/parts/create');
    }



    public function store(StoreDonate $request)
    {
        if (Donor::create($request->except('_token')))
            return response()->json(['status' => 200]);
        else
            return response()->json(['status' => 405]);
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


    public function edit(Donor $donor)
    {
        return view('admin/donors/parts/edit', compact('donor'));
    }


    public function update(Request $request, $id)
    {
        $donor = Donor::findOrFail($id);

        $donor->update($request->except('_token', '_method'));
        toastr()->success("تم التحديث بنجاح");
        return redirect()->route('donors.index');
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
            Donor::destroy($request->id);
            return redirect()->back();
            return response(['message' => 'تم الحذف بنجاح', 'status' => 200], 200);
        } catch (\Exception $ex) {
            return response(['message' => $ex->getMessage(), 'status' => 400]);
        }
    }


    //    funciton to the arrow charts
    public function CartIndex()
    {
        return view('charts.dashboard');
    }

    public function getChartData()
    {
        $data = Donor::selectRaw('COUNT(id) as count, DATE(created_at) as date')
            ->whereNotNull('created_at') // Ignore NULL values
            ->groupBy('date')
            ->orderBy('date', 'ASC') // Order by oldest to newest
            ->get();

        return response()->json($data);
    }
}
