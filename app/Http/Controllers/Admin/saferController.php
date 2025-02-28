<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Donor;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class saferController extends Controller
{

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
    public function indexLoans(Request $request)
    {
        if ($request->ajax()) {
            $donations = Donation::where("donation_type", [2])->get();
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
}
