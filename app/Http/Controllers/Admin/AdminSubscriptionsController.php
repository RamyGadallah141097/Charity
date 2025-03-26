<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\adminSubscription;
use App\Models\Asset;
use App\Models\Setting;
use App\Models\subscriptionFee;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AdminSubscriptionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = AdminSubscription::with("admin")->get();

            return DataTables::of($data)
                ->editColumn("created_at", function ($data) {
                    return $data->created_at ? $data->created_at->format("Y-m-d") : "-";
                })
                ->editColumn("admin_id", function ($data) {
                    return $data->admin ? $data->admin->name  : "-";
                })
                ->editColumn("months_count" , function($data){
                    $monthNames = [
                        1 => "شهر واحد" ,     2 => "شهران",         3 => "ثلاثة أشهر",   4 => "أربعة أشهر",
                        5 => "خمسة أشهر",     6 => "ستة أشهر",    7 => "سبعة أشهر",
                        8 => "ثمانية أشهر",   9 => "تسعة أشهر",   10 => "عشرة أشهر",
                        11 => "أحد عشر شهرًا", 12 => "اثنا عشر شهرًا"
                    ];
                    return $monthNames[$data->months_count];
                })

                ->rawColumns(['name'])
                ->make(true);
        }
        $total = AdminSubscription::sum("amount") - subscriptionFee::sum("amount");
        return view('Admin.Supscriptions.adminSupscriptions.index' , compact("total"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $admins = Admin::all();
        return view("Admin.Supscriptions.adminSupscriptions.parts.create" , compact('admins'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (AdminSubscription::create($request->except("_token"))){
            return response()->json(["status" => 200]);
        }else{
            return response()->json(["status" => 500]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
