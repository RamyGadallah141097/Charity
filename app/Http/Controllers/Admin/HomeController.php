<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donor;
use App\Models\Order;
use App\Models\OrderOfferDetails;
use App\Models\Product;
use App\Models\Provider;
use App\Models\Reviews;
use App\Models\Subvention;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $total_donors    = Donor::all()->sum('price');
        $donors_count    = Donor::all()->count();
        $users_count     = User::all()->count();
        $totalMonthlySubventions  = Subvention::where('type', 'monthly')->sum('price');
        $users_month          = User::whereMonth('created_at', '=', Carbon::now()->month)->count();
        $users_last_month     = User::whereMonth('created_at', '=', Carbon::now()->subMonth()->month)->count();
        if ($users_last_month != 0)
            $diff = $users_month / $users_last_month;
        else
            $diff = 1;

        $donors      = Donor::take(5)->get();
        $subvention  = Subvention::orderBy('price','DESC')->first();
        $subvention  = Subvention::orderBy('price', 'DESC')->first();

        $users       = User::latest()->take(5)->get();




        $progressData = [];

        $tasks = Task::select('title', 'status')->get();

        foreach ($tasks as $key => $task) {
            $progressData[$key] = [
                'title' => $task->title,
                'progress' => $task->status == 0 ? 0 : ($task->status == 1 ? 25 : ($task->status == 2 ? 50 : ($task->status == 3 ? 75 : 100)))
            ];
        }




        return view('Admin/index', compact('total_donors' , "progressData" , 'users', 'donors', 'donors_count', 'diff', 'users_count', 'subvention', 'users_month', 'users_last_month', 'totalMonthlySubventions'));
    }
}
