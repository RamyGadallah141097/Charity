<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminSubscription;
use App\Models\subscriptionFee;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SubscriptionFeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = subscriptionFee::get();

            return DataTables::of($data)
                ->editColumn("created_at", function ($data) {
                    return $data->created_at ? $data->created_at->format("Y-m-d") : "-";
                })
                ->rawColumns(['name'])
                ->make(true);
        }
        $total = subscriptionFee::sum("amount");
        return view('Admin/Supscriptions/supscriptionFees/index' , compact("total"));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("Admin/Supscriptions/supscriptionFees/parts/create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (subscriptionFee::create($request->except("_token"))){
            return response()->json(["status" => 200]);
        }else{
            return response()->json(["status" => 500]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\subscriptionFee  $subscriptionFee
     * @return \Illuminate\Http\Response
     */
    public function show(subscriptionFee $subscriptionFee)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\subscriptionFee  $subscriptionFee
     * @return \Illuminate\Http\Response
     */
    public function edit(subscriptionFee $subscriptionFee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\subscriptionFee  $subscriptionFee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, subscriptionFee $subscriptionFee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\subscriptionFee  $subscriptionFee
     * @return \Illuminate\Http\Response
     */
    public function destroy(subscriptionFee $subscriptionFee)
    {
        //
    }
}
