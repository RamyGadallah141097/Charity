<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borrower;
use App\Models\Donation;
use App\Models\Donor;
use App\Models\Loan;
use App\Models\Order;
use App\Models\OrderOfferDetails;
use App\Models\Product;
use App\Models\Provider;
use App\Models\Reviews;
use App\Models\Setting;
use App\Models\Subvention;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {

        //        users
        $users_count     = User::all()->count();
        $newUsers     = User::where("status",  "new")->count();
        $accepedUsers     = User::where("status", "accepted")->count();
        $preparingUsers     = User::where("status", "preparing")->count();
        $rejectedUsers     = User::where("status", "refused")->count();
        $social_status0     = User::where("social_status", '0')->count();
        $social_status1     = User::where("social_status", '1')->count();
        $social_status2     = User::where("social_status", '2')->count();
        $social_status3    = User::where("social_status", '3')->count();
        //        dd($social_status0 , $social_status1 , $social_status2 , $social_status3 );

        //        donors and donations
        $donors_count    = Donor::all()->count();
        $total_donors_money    = Donor::all()->sum('price');
        $totalDonations    = Donation::all()->sum('donation_amount');

        //  loans
        $totalLoans = Loan::count();
        $totalLoanOut     = Loan::all()->sum('loan_amount');
        $totalBorrowers     = Borrower::all()->count();
        $totalLoansDonations = Donation::where('donation_type', 2)->sum('donation_amount');


        //        zaka
        $totalMonthlySubventions  = Subvention::where('type', 'monthly')->sum('price');
        $totalZakat  = Donation::whereIn('donation_type', [0, 1])->sum('donation_amount');;



        $users_month          = User::whereMonth('created_at', '=', Carbon::now()->month)->count();
        $users_last_month     = User::whereMonth('created_at', '=', Carbon::now()->subMonth()->month)->count();
        if ($users_last_month != 0)
            $diff = $users_month / $users_last_month;
        else
            $diff = 1;

        $donors      = Donor::take(5)->get();
        $subvention  = Subvention::orderBy('price', 'DESC')->first();
        $totalSubventions  = Subvention::all()->sum('price');
        //        $subvention  = Subvention::orderBy('price', 'DESC')->first();

        $users       = User::latest()->take(5)->get();




        $progressData = [];

        $tasks = Task::select('title', 'status')->get();

        foreach ($tasks as $key => $task) {
            $progressData[$key] = [
                'title' => $task->title,
                'progress' => $task->status == 0 ? 0 : ($task->status == 1 ? 25 : ($task->status == 2 ? 50 : ($task->status == 3 ? 75 : 100)))
            ];
        }


        $setting = Setting::first();


        return view('admin/index', compact('total_donors_money', "totalDonations", "donors_count", 'preparingUsers', "progressData", 'users', "accepedUsers", "newUsers", "rejectedUsers", 'donors', 'diff', 'users_count', 'subvention', "totalSubventions", 'users_month', 'users_last_month', 'totalMonthlySubventions', "totalZakat", "totalLoans", "totalBorrowers", "totalLoanOut", "totalLoansDonations", "setting", "social_status3", "social_status2", "social_status1", "social_status0"));
    }
}
