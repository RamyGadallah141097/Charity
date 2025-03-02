<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Donor;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SaferController extends Controller
{

    public function index()
    {
        return "not exist ";
    }

    //زكاة مال
    public function indexCharityZakat(Request $request)
    {
        if ($request->ajax()) {
            $donations = Donation::whereIn("donation_type", [0, 1])->get();
            return Datatables::of($donations)
                ->addColumn("donor_name", function ($donation) {
                    return $donation->donor->name ?? 'غير معروف';
                })
                ->editColumn('created_at', function ($donation) {
                    return $donation->created_at ? $donation->created_at->format('d-m-y') : 'غير متوفر';
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            $Charity = Donation::where("donation_type", 0)->sum("donation_amount");
            return view('Admin/safer/charity', ["Charity" => $Charity]);
        }
    }


    //القرض الحسن
//    function to return amount of donation for the donor if it just one donation
//but if he had more than one donation ? display all his donations هححححح
    public function indexLoans(Request $request)
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
                        'created_at' => optional($donationsByDonor->first()->created_at)->format('d-m-y'),
                    ];
                } else {
                    return [
                        'id' => $donationsByDonor->first()->donor_id,
                        'donor_name' => $donationsByDonor->first()->donor->name ?? 'غير معروف',
                        'donation_amount' => '<button class="btn btn-primary view-donations" data-donor="' . $donationsByDonor->first()->donor_id . '">عرض التبرعات </button>',
                        'created_at' => '-',
                    ];
                }
            })->values();

            return DataTables::of($DonationsForDonor)
                ->rawColumns(['donation_amount'])
                ->escapeColumns([])
                ->make(true);
        } else {
            $loans = Donation::where("donation_type", 2)->sum("donation_amount");
            return view('Admin/safer/loans', ["loans" => $loans]);
        }

    }


    //تبرعات عينية
    public function InKindDonations(Request $request)
    {
        if ($request->ajax()) {
            $donations = Donation::where("donation_type", [3])->get();
            return Datatables::of($donations)
                ->addColumn("donor_name", function ($donation) {
                    return $donation->donor->name ?? 'غير معروف';
                })
                ->editColumn('created_at', function ($donation) {
                    return $donation->created_at ? $donation->created_at->format('d-m-y') : 'غير متوفر';
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            return view('Admin/safer/InKindDonations');
        }
    }

    public function Donors(Request $request)
    {
        if ($request->ajax()) {
            $donations = Donation::where("donation_type", [3])->get();
            return Datatables::of($donations)
                ->addColumn("donor_name", function ($donation) {
                    return $donation->donor->name ?? 'غير معروف';
                })
                ->editColumn('created_at', function ($donation) {
                    return $donation->created_at ? $donation->created_at->format('d-m-y') : 'غير متوفر';
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            return view('Admin/safer/loansDonors');
        }
    }


    public  function getDonation(Request $request)
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
