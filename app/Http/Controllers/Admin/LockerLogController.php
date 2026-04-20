<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\DonationType;
use App\Models\LockerLog;
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
        $lockerTypes = DonationType::active()->orderBy('sort_order')->get();
        $selectedLockerType = $lockerTypes->firstWhere('id', (int) $selectedTypeId);

        if (! $selectedLockerType && $lockerTypes->isNotEmpty()) {
            $selectedLockerType = $lockerTypes->first();
            $selectedTypeId = $selectedLockerType->id;
        }

        $isCashLocker = $selectedLockerType?->isCashLockerType() ?? false;
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

            $donations = Donation::with(['donor', 'unit', 'referenceDonationType'])
                ->where('donation_type_id', $selectedTypeId);

            if ($request->filled('from') && $request->filled('to')) {
                $donations->whereBetween('created_at', [
                    $request->from . ' 00:00:00',
                    $request->to . ' 23:59:59',
                ]);
            }

            return DataTables::of($donations->latest()->get())
                ->addColumn('name', function ($donation) {
                    return optional($donation->donor)->name ?? 'غير معروف';
                })
                ->editColumn('amount', function ($donation) {
                    return $donation->display_value ?: '---';
                })
                ->editColumn('comment', function ($donation) {
                    $typeName = optional($donation->referenceDonationType)->name ?: $donation->display_type_name;
                    $unitName = optional($donation->unit)->name;

                    return trim($typeName . ($unitName ? ' - ' . $unitName : ''));
                })
                ->editColumn('created_at', function ($donation) {
                    return $donation->created_at ? $donation->created_at->format('d-m-Y') : 'غير متوفر';
                })
                ->escapeColumns([])
                ->make(true);
        }

        $title = $selectedLockerType?->name ?? 'الخزنة';
        $totalPlus = 0;
        $totalMinus = 0;
        $total = 0;
        $error = null;

        if ($isCashLocker && $moneyType) {
            $totalPlus = LockerLog::where('moneyType', $moneyType)->where('type', LockerLog::TYPE_PLUS)->sum('amount');
            $totalMinus = LockerLog::where('moneyType', $moneyType)->where('type', LockerLog::TYPE_MINUS)->sum('amount');

            if ($totalPlus < $totalMinus) {
                $error = 'المجموع الكلي للخارج اكبر من الداخل';
            } else {
                $total = $totalPlus - $totalMinus;
            }
        }

        return view('admin/lock/stdPage', [
            'lockerTypes' => $lockerTypes,
            'selectedTypeId' => $selectedTypeId,
            'title' => $title,
            'isCashLocker' => $isCashLocker,
            'total' => $total,
            'totalMinus' => $totalMinus,
            'totalPlus' => $totalPlus,
            'error' => $error,
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
