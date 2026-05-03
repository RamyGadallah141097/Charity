<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\DonationCategory;
use App\Models\DonationType;
use App\Models\LockerLog;
use App\Models\Subvention;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class LockerLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request, $model = null)
    {
        $selectedTypeId = $request->input('locker_type', $model);
        $lockerTypes = DonationType::active()
            ->lockerTypes()
            ->orderBy('sort_order')
            ->get()
            ->map(function (DonationType $lockerType) {
                $lockerType->display_name = $lockerType->isInKindType()
                    ? 'خزنة التبرعات العينية'
                    : (str_starts_with($lockerType->name, 'خزنة') ? $lockerType->name : 'خزنة ' . $lockerType->name);

                return $lockerType;
            });
        $selectedLockerType = $lockerTypes->firstWhere('id', (int) $selectedTypeId);

        if (! $selectedLockerType && $lockerTypes->isNotEmpty()) {
            $selectedLockerType = $lockerTypes->first();
            $selectedTypeId = $selectedLockerType->id;
        }

        $isCashLocker = $selectedLockerType?->isCashLockerType() ?? false;
        $isInKindLocker = $selectedLockerType?->isInKindType() ?? false;
        $showsBalanceCard = ! $isInKindLocker;
        $moneyType = $selectedLockerType?->lockerMoneyType();

        if ($request->ajax()) {
            if ($isCashLocker && $moneyType) {
                $donations = LockerLog::where('moneyType', $moneyType)->with('admin');

                if ($request->filled('from') && $request->filled('to')) {
                    $donations->whereBetween('created_at', [
                        $request->from . ' 00:00:00',
                        $request->to . ' 23:59:59',
                    ]);
                }

                if ($request->filled('type') && $request->type !== 'all') {
                    if ($request->type === LockerLog::TYPE_PLUS) {
                        $donations->where('type', LockerLog::TYPE_PLUS);
                    } elseif ($request->type === LockerLog::TYPE_MINUS) {
                        $donations->where('type', LockerLog::TYPE_MINUS);
                    }
                }

                return DataTables::of($donations->latest()->get())
                    ->addColumn('name', function ($donation) {
                        return optional($donation->admin)->name ?? 'غير متوفر';
                    })
                    ->editColumn('amount', function ($donation) {
                        $amount = number_format((float) $donation->amount, 2);
                        return $donation->type === LockerLog::TYPE_PLUS
                            ? $amount . " <i class='fas fa-arrow-down' style='color: #63E6BE; font-size: 22px; transform: rotate(45deg); margin-right: 10px;'></i>"
                            : $amount . " <i class='fas fa-arrow-up' style='color: #e42f2f; font-size: 22px; transform: rotate(45deg); margin-right: 10px;'></i>";
                    })
                    ->editColumn('comment', function ($donation) {
                        $icon = $donation->type === LockerLog::TYPE_PLUS
                            ? "<i class='fa-solid fa-circle-arrow-up' style='color: green;'></i>"
                            : "<i class='fa-solid fa-circle-arrow-down' style='color: red;'></i>";

                        return ($donation->comment ?: '---') . ' ' . $icon;
                    })
                    ->editColumn('created_at', function ($donation) {
                        return $donation->created_at ? $donation->created_at->format('d-m-Y') : 'غير متوفر';
                    })
                    ->escapeColumns([])
                    ->make(true);
            }

            $donations = Donation::with(['donor', 'unit', 'referenceDonationType', 'donationCategory'])
                ->where('donation_type_id', $selectedTypeId);

            if ($request->filled('from') && $request->filled('to')) {
                $donations->whereBetween('created_at', [
                    $request->from . ' 00:00:00',
                    $request->to . ' 23:59:59',
                ]);
            }

            $incomingRows = $donations->latest()->get()->map(function (Donation $donation) use ($isInKindLocker) {
                $value = $donation->display_value ?: '---';

                return [
                    'name' => optional($donation->donor)->name ?? 'غير معروف',
                    'category_name' => $isInKindLocker ? (optional($donation->donationCategory)->name ?? '--') : (optional($donation->referenceDonationType)->name ?? '--'),
                    'amount' => $value . " <span class='locker-movement locker-movement--in'><i class='fas fa-arrow-down'></i> داخل</span>",
                    'comment' => 'تبرع وارد',
                    'created_at' => $donation->created_at ? $donation->created_at->format('d-m-Y') : 'غير متوفر',
                    'sort_date' => optional($donation->created_at)->timestamp ?? 0,
                ];
            });

            if (! $isInKindLocker) {
                return DataTables::of($incomingRows->sortByDesc('sort_date')->values())
                    ->escapeColumns([])
                    ->make(true);
            }

            $outgoingRows = Subvention::with(['user', 'asset', 'donationCategory', 'donationUnit'])
                ->where('price', 0)
                ->whereNotNull('donation_category_id')
                ->when($request->filled('from') && $request->filled('to'), function ($query) use ($request) {
                    $query->whereBetween('created_at', [
                        $request->from . ' 00:00:00',
                        $request->to . ' 23:59:59',
                    ]);
                })
                ->latest()
                ->get()
                ->map(function (Subvention $subvention) {
                    $categoryName = optional($subvention->donationCategory)->name ?? optional($subvention->asset)->name ?? '--';
                    $unitName = optional($subvention->donationUnit)->name ?: 'قطعة';
                    $value = trim(($subvention->asset_count ?? 0) . ' ' . $unitName);

                    return [
                        'name' => optional($subvention->user)->wife_name ?? optional($subvention->user)->husband_name ?? 'غير معروف',
                        'category_name' => $categoryName,
                        'amount' => $value . " <span class='locker-movement locker-movement--out'><i class='fas fa-arrow-up'></i> خارج</span>",
                        'comment' => $subvention->comment ?: 'صرف عيني',
                        'created_at' => $subvention->created_at ? $subvention->created_at->format('d-m-Y') : 'غير متوفر',
                        'sort_date' => optional($subvention->created_at)->timestamp ?? 0,
                    ];
                })
                ->filter(function (array $row) {
                    return $row['category_name'] !== '--';
                });

            return DataTables::of($incomingRows->concat($outgoingRows)->sortByDesc('sort_date')->values())
                ->escapeColumns([])
                ->make(true);
        }

        $title = $selectedLockerType?->display_name ?? 'الخزنة';
        $totalPlus = 0;
        $totalMinus = 0;
        $total = 0;
        $error = null;
        $inKindCategorySummaries = collect();

        if ($isCashLocker && $moneyType) {
            $totalPlus = LockerLog::where('moneyType', $moneyType)->where('type', LockerLog::TYPE_PLUS)->sum('amount');
            $totalMinus = LockerLog::where('moneyType', $moneyType)->where('type', LockerLog::TYPE_MINUS)->sum('amount');

            if ($totalPlus < $totalMinus) {
                $error = 'المجموع الكلي للخارج اكبر من الداخل';
            } else {
                $total = $totalPlus - $totalMinus;
            }
        } elseif ($selectedLockerType && $selectedLockerType->isInKindType()) {
            $inKindCategoryCodes = [
                'new_clothes',
                'used_clothes',
                'bags',
                'blankets',
                'meat',
                'new_furniture',
                'used_furniture',
                'electrical_devices',
            ];

            $inKindCategorySummaries = DonationCategory::active()
                ->with('units:id,name')
                ->whereIn('code', $inKindCategoryCodes)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get()
                ->map(function (DonationCategory $category) use ($selectedTypeId) {
                    $incoming = Donation::where('donation_type_id', $selectedTypeId)
                        ->where('donation_category_id', $category->id)
                        ->get()
                        ->sum(function (Donation $donation) {
                            return (float) ($donation->amount_value ?? $donation->asset_count ?? $donation->donation_amount ?? 0);
                        });

                    $spent = Subvention::where('price', 0)
                        ->where('donation_category_id', $category->id)
                        ->sum('asset_count');

                    return [
                        'name' => $category->name,
                        'unit' => optional($category->units->first())->name,
                        'incoming' => $incoming,
                        'spent' => (float) $spent,
                        'remaining' => max($incoming - (float) $spent, 0),
                    ];
                });
        } elseif ($selectedLockerType) {
            $totalPlus = Donation::where('donation_type_id', $selectedTypeId)->sum('amount_value');
            $total = $totalPlus;
        }

        return view('admin/lock/stdPage', [
            'lockerTypes' => $lockerTypes,
            'selectedTypeId' => $selectedTypeId,
            'title' => $title,
            'isCashLocker' => $isCashLocker,
            'isInKindLocker' => $isInKindLocker,
            'showsBalanceCard' => $showsBalanceCard,
            'total' => $total,
            'totalMinus' => $totalMinus,
            'totalPlus' => $totalPlus,
            'error' => $error,
            'inKindCategorySummaries' => $inKindCategorySummaries,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LockerLog  $lockerLog
     * @return \Illuminate\Http\Response
     */
    public function show(LockerLog $lockerLog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LockerLog  $lockerLog
     * @return \Illuminate\Http\Response
     */
    public function edit(LockerLog $lockerLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LockerLog  $lockerLog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LockerLog $lockerLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LockerLog  $lockerLog
     * @return \Illuminate\Http\Response
     */
    public function destroy(LockerLog $lockerLog)
    {
        //
    }
}
