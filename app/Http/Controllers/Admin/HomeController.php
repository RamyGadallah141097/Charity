<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borrower;
use App\Models\Donation;
use App\Models\DonationCategory;
use App\Models\DonationType;
use App\Models\Donor;
use App\Models\Loan;
use App\Models\LockerLog;
use App\Models\Setting;
use App\Models\Subvention;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $setting = Setting::first();
        $now = Carbon::now();
        $startOfDay = $now->copy()->startOfDay();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfYear = $now->copy()->startOfYear();
        $lastMonthStart = $now->copy()->subMonth()->startOfMonth();
        $lastMonthEnd = $now->copy()->subMonth()->endOfMonth();

        // Donations stats (Financial)
        $donationsToday = Donation::where('donation_kind', '!=', 'in_kind')->whereDate('created_at', $startOfDay)->sum(DB::raw('COALESCE(amount_value, donation_amount)'));
        $donationsMonth = Donation::where('donation_kind', '!=', 'in_kind')->whereMonth('created_at', $now->month)->whereYear('created_at', $now->year)->sum(DB::raw('COALESCE(amount_value, donation_amount)'));
        $donationsYear = Donation::where('donation_kind', '!=', 'in_kind')->whereYear('created_at', $now->year)->sum(DB::raw('COALESCE(amount_value, donation_amount)'));
        
        $donationsLastMonth = Donation::where('donation_kind', '!=', 'in_kind')->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])->sum(DB::raw('COALESCE(amount_value, donation_amount)'));
        $donationsLastYear = Donation::where('donation_kind', '!=', 'in_kind')->whereYear('created_at', $now->copy()->subYear()->year)->sum(DB::raw('COALESCE(amount_value, donation_amount)'));
        
        // Donors stats
        $activeDonors = Donor::count(); 
        $newDonorsMonth = Donor::whereMonth('created_at', $now->month)->whereYear('created_at', $now->year)->count();

        // Beneficiaries
        $totalBeneficiaries = User::where('status', 'accepted')->count();
        $newBeneficiariesMonth = User::where('status', 'accepted')->whereMonth('created_at', $now->month)->whereYear('created_at', $now->year)->count();

        // Subventions
        $totalMonthlySubventionsUsers = Subvention::where('type', 'monthly')->distinct('user_id')->count('user_id');
        $totalMonthlySubventionsValue = Subvention::where('type', 'monthly')->sum('price');

        // Expenses and Revenues (LockerLogs)
        $revenueMonth = LockerLog::where('type', LockerLog::TYPE_PLUS)->whereMonth('created_at', $now->month)->whereYear('created_at', $now->year)->sum('amount');
        $expenseMonth = LockerLog::where('type', LockerLog::TYPE_MINUS)->whereMonth('created_at', $now->month)->whereYear('created_at', $now->year)->sum('amount');
        $surplusMonth = $revenueMonth - $expenseMonth;

        $revenueYear = LockerLog::where('type', LockerLog::TYPE_PLUS)->whereYear('created_at', $now->year)->sum('amount');
        $expenseYear = LockerLog::where('type', LockerLog::TYPE_MINUS)->whereYear('created_at', $now->year)->sum('amount');
        $surplusYear = $revenueYear - $expenseYear;

        // Distributions
        $donationsByKind = Donation::select('donation_kind', DB::raw('count(*) as total'))
            ->groupBy('donation_kind')
            ->pluck('total', 'donation_kind')->toArray();

        // Monthly Data for charts (Last 6 months)
        $monthlyChartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthDate = $now->copy()->subMonths($i);
            $monthStart = $monthDate->copy()->startOfMonth();
            $monthEnd = $monthDate->copy()->endOfMonth();
            $monthLabel = $monthDate->translatedFormat('F Y');

            $rev = LockerLog::where('type', LockerLog::TYPE_PLUS)->whereBetween('created_at', [$monthStart, $monthEnd])->sum('amount');
            $exp = LockerLog::where('type', LockerLog::TYPE_MINUS)->whereBetween('created_at', [$monthStart, $monthEnd])->sum('amount');
            $ben = User::where('status', 'accepted')->whereBetween('created_at', [$monthStart, $monthEnd])->count();
            
            $monthlyChartData['labels'][] = $monthLabel;
            $monthlyChartData['revenues'][] = $rev;
            $monthlyChartData['expenses'][] = $exp;
            $monthlyChartData['beneficiaries'][] = $ben;
        }

        // Widgets
        $topDonors = Donor::withSum('donations', 'amount_value')->orderByDesc('donations_sum_amount_value')->take(5)->get();
        $mostCommonDonationCategories = DonationCategory::withCount('donations')->orderByDesc('donations_count')->take(5)->get();
        
        $beneficiaryCategoriesCount = User::select('beneficiary_category_id', DB::raw('count(*) as total'))
            ->whereNotNull('beneficiary_category_id')
            ->groupBy('beneficiary_category_id')
            ->with('beneficiaryCategory')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        // Locker Balances
        $moneyLockerBalance = LockerLog::whereIn('moneyType', [LockerLog::moneyTypeSadaka, LockerLog::moneyTypeZakat])
            ->selectRaw('SUM(CASE WHEN type = "plus" THEN amount ELSE -amount END) as balance')
            ->value('balance') ?? 0;

        $loanLockerBalance = LockerLog::where('moneyType', LockerLog::moneyTypeLoans)
            ->selectRaw('SUM(CASE WHEN type = "plus" THEN amount ELSE -amount END) as balance')
            ->value('balance') ?? 0;

        $associationLockerBalance = LockerLog::where('moneyType', LockerLog::moneyTypeAssociation)
            ->selectRaw('SUM(CASE WHEN type = "plus" THEN amount ELSE -amount END) as balance')
            ->value('balance') ?? 0;

        $inKindTypeId = DonationType::where('code', 'in_kind')->first()?->id;
        $inKindIncoming = Donation::where('donation_type_id', $inKindTypeId)->sum(DB::raw('COALESCE(asset_count, amount_value, 0)'));
        $inKindSpent = Subvention::where('price', 0)->sum('asset_count');
        $inKindLockerBalance = max($inKindIncoming - $inKindSpent, 0);

        return view('admin.index', compact(
            'setting', 'donationsToday', 'donationsMonth', 'donationsYear',
            'donationsLastMonth', 'donationsLastYear', 'activeDonors', 'newDonorsMonth',
            'totalBeneficiaries', 'newBeneficiariesMonth', 'revenueMonth', 'expenseMonth',
            'surplusMonth', 'revenueYear', 'expenseYear', 'surplusYear', 'donationsByKind',
            'monthlyChartData', 'topDonors', 'mostCommonDonationCategories', 'beneficiaryCategoriesCount',
            'totalMonthlySubventionsUsers', 'totalMonthlySubventionsValue',
            'moneyLockerBalance', 'loanLockerBalance', 'associationLockerBalance', 'inKindLockerBalance'
        ));
    }
}
