<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\Donation;
use App\Models\LockerLog;
use App\Models\Subvention;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use function PHPUnit\Framework\matches;

class LockerLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request, $model = null)
    {
        if ($request->ajax()) {
            $type = $model == 0 ? LockerLog::moneyTypeZakat : ($model == 1 ? LockerLog::moneyTypeSadaka : ($model == 2 ? LockerLog::moneyTypeLoans : ""));
            $donations = LockerLog::where("moneyType", $type)->with("admin")->get();

            if ($model == 3){
                return DataTables::of($donations)
                    ->editColumn('admin_id', function ($donation) {
                        return optional($donation->admin)->name ?? "غير متوفر";
                    })
                    ->editColumn('amount', function ($donation) {
                        return $donation->type == LockerLog::TYPE_PLUS
                            ? ' عدد : ' . $donation->asset_count . ' من ' . ($donation->asset ? ($donation->asset->name ?? '-') : '-'). "<i class='fas fa-arrow-down' style='color: #63E6BE; font-size: 30px ;transform: rotate(45deg); margin-right: 20px;'></i>"
                            :' عدد : ' . $donation->asset_count . ' من ' . ($donation->asset ? ($donation->asset->name ?? '-') : '-') . "<i class='fas fa-arrow-up' style='color: #e42f2f; font-size: 30px ; transform: rotate(45deg);margin-right: 20px;'></i>";
                    })
                    ->editColumn('comment', function ($donation) {
                        $icon = $donation->type === LockerLog::TYPE_PLUS
                            ? "<i class='fa-solid fa-circle-arrow-up' style='color: green;'></i>"
                            : "<i class='fa-solid fa-circle-arrow-down' style='color: red;'></i>";
                        return $donation->comment . " " . $icon;
                    })
                    ->editColumn('created_at', function ($donation) {
                        return $donation->created_at ? $donation->created_at->format('d-m-Y') : 'غير متوفر';
                    })
                    ->escapeColumns([])
                    ->make(true);
            }else{
                return DataTables::of($donations)
                    ->addColumn('admin_id', function ($donation) {
                        return optional($donation->admin)->name ?? "غير متوفر";
                    })
                    ->editColumn('amount', function ($donation) {
                        return $donation->type == LockerLog::TYPE_PLUS
                            ? $donation->amount . "<i class='fas fa-arrow-down' style='color: #63E6BE; font-size: 30px ;transform: rotate(45deg); margin-right: 20px;'></i>"
                            : $donation->amount . "<i class='fas fa-arrow-up' style='color: #e42f2f; font-size: 30px ; transform: rotate(45deg);margin-right: 20px;'></i>";
                    })
                    ->editColumn('comment', function ($donation) {
                        $icon = $donation->type === LockerLog::TYPE_PLUS
                            ? "<i class='fa-solid fa-circle-arrow-up' style='color: green;'></i>"
                            : "<i class='fa-solid fa-circle-arrow-down' style='color: red;'></i>";
                        return $donation->comment . " " . $icon;
                    })
                    ->editColumn('created_at', function ($donation) {
                        return $donation->created_at ? $donation->created_at->format('d-m-Y') : 'غير متوفر';
                    })
                    ->escapeColumns([])
                    ->make(true);
            }


        }else {
            $title = $model == 0 ? "زكاة" : ($model == 1 ? "صدقات" : ($model == 2 ? "قرض حسن " : "عينيات"));
            $type = $model == 0 ? LockerLog::moneyTypeZakat : ($model == 1 ? LockerLog::moneyTypeSadaka : ($model == 2 ? LockerLog::moneyTypeLoans : LockerLog::moneyTypeSubvention));
            $totalPlus = LockerLog::where("moneyType", $type)->where("type" , LockerLog::TYPE_PLUS)->sum("amount");
            $totalMinus = LockerLog::where("moneyType", $type)->where("type" , LockerLog::TYPE_MINUS)->sum("amount");
            $total = $totalPlus - $totalMinus;

            return view('admin/lock/stdPage', compact('model', "title" , "total" , "totalMinus" , "totalPlus"));
        }
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
