<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDonate;
use App\Models\Donor;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DonorController extends Controller
{

    public function index(request $request)
    {
        if($request->ajax()) {
            $donors = Donor::latest()->get();
            return Datatables::of($donors)
                ->addColumn('action', function ($donors) {
                    return '
                            <button type="button" data-id="' . $donors->id . '" class="btn btn-pill btn-info-light editBtn"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $donors->id . '" data-title="' . $donors->name . '">
                                    <i class="fas fa-trash"></i>
                            </button>
                       ';
                })
                ->editColumn('notes', function ($donors) {
                    return ($donors->notes) ?? '-----';
                })
                ->editColumn('created_at', function ($donors) {
                    return $donors->created_at->diffForHumans();
                })
                ->escapeColumns([])
                ->make(true);
        }else{
            return view('Admin/donors/index');
        }
    }

    public function create()
    {
        return view('Admin/donors/parts/create');
    }



    public function store(StoreDonate $request)
    {
        if(Donor::create($request->except('_token')))
            return response()->json(['status'=>200]);
        else
            return response()->json(['status'=>405]);
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


    public function edit(Donor $donor)
    {
        return view('Admin/donors/parts/edit',compact('donor'));
    }



    public function update(StoreDonate $request, $id)
    {
        if(Donor::find($id)->update($request->except('_token','id')))
            return response()->json(['status'=>200]);
        else
            return response()->json(['status'=>405]);
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

    public function delete(Request $request)
    {
        try {
            Donor::find($request->id)->delete();
            return response(['message'=>'تم الحذف بنجاح','status'=>200],200);
        }
        catch (\Exception $ex){
            return response(['message'=>$ex->getMessage(),'status'=>400]);
        }

    }
}
