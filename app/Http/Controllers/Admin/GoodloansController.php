<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\Donation;



class GoodloansController extends Controller
{
    //القرض الحسن
    //داله لعرض جميع التبرعات في صفحة التبرعات في القرض الحسمه
    // و عند وجود اكثر من تبرع لمتبرع يظهر داله لاظهار جميع التبرعا

    // {- التبرعات والمتبرعين-}

    //function to return amount of donation for the donor if it just one donation
    //but if he had more than one donation ? display all his donations
    public function indexLoansDonations(Request $request)
    {
        if ($request->ajax()) {
            $donations = Donation::where("donation_type", 2)
                ->with('donor')
                ->get()
                ->groupBy('donor_id');

            $DonationsForDonor = $donations->map(function ($donationsByDonor) {
                if ($donationsByDonor->count() == 1) {
                    return [
                        'id' => $donationsByDonor->first()->id,
                        'donor_name' => $donationsByDonor->first()->donor->name ?? 'غير معروف',
                        'donation_amount' => $donationsByDonor->first()->donation_amount ?? 0,
                        "operations" => "-",
                        'created_at' => optional($donationsByDonor->first()->created_at)->format('d-m-y'),
                    ];
                } else {
                    return [
                        'id' => $donationsByDonor->first()->donor_id,
                        'donor_name' => $donationsByDonor->first()->donor->name ?? 'غير معروف',
                        'donation_amount' => $donationsByDonor->sum('donation_amount'),
                        "operations" => '<button class="btn btn-primary view-donations" data-total="'.$donationsByDonor->sum('donation_amount'). ' " data-donor="' . $donationsByDonor->first()->donor_id . '">  <i class="fa fa-eye"></i> </button>',
                        'created_at' => optional($donationsByDonor->first()->created_at)->format('d-m-y') ?? '-',
                    ];
                }
            })->values();

            return DataTables::of($DonationsForDonor)
                ->rawColumns(['donation_amount'])
                ->rawColumns(['operations'])
                ->escapeColumns([])
                ->make(true);
        } else {
            $loans = Donation::where("donation_type", 2)->sum("donation_amount");
            return view('Admin/safer/loans', ["loans" => $loans]);
        }
    }
    // {- التبرعات والمتبرعين-}

//    داله لاظهار جميع التبرعات للمتبرع في صفحة التبرعات في القرض الحسمه
    public  function getDonors(Request $request)
    {
        $donations = Donation::where("donor_id", $request->donor_id)
            ->where("donation_type", 2)
            ->get(['donation_amount', 'created_at']);

        return response()->json($donations->map(function ($donation) {
            return [
                'amount' => $donation->donation_amount,
                'date' => optional($donation->created_at)->format('d-m-y'),
            ];
        }));
    }
}
