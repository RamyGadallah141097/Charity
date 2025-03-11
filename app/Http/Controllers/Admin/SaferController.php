<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
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
            $Charity = Donation::whereIn("donation_type", [0,1])->sum("donation_amount");
            return view('Admin/safer/charity', ["Charity" => $Charity]);
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
