<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donor;
use App\Models\Subvention;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SubventionController extends Controller
{
    public function index(request $request)
    {
        if($request->ajax()) {
            $data = Subvention::latest()->get();
            return Datatables::of($data)
                ->addColumn('action', function ($data) {
                    return '
                            <button type="button" data-id="' . $data->id . '" class="btn btn-pill btn-info-light editBtn"><i class="fa fa-edit"></i></button>
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $data->id . '" data-title="' . $data->user->name . '">
                                    <i class="fas fa-trash"></i>
                            </button>
                       ';
                })
                ->editColumn('user_id', function ($data) {
                    return ($data->user->husband_name) ?? 'تم حذفه';
                })
                ->editColumn('type', function ($data) {
                    if($data->type == 'once')
                        return 'مرة واحدة';
                    else
                        return 'إعانة شهرية';
                })
                ->escapeColumns([])
                ->make(true);
        }else{
            return view('Admin/subventions/index');
        }
    }



    public function create()
    {
        $users = User::where('status','accepted')->whereDoesntHave('subvention')->select('id','husband_name')->latest()->get();
        return view('Admin/subventions/parts/create',compact('users'));
    }


    public function store(Request $request)
    {
        if(Subvention::create($request->except('_token')))
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


    public function edit(Subvention $subvention)
    {
        $users = User::where('status','accepted')
            ->whereDoesntHave('subvention')
            ->orWhere('id',$subvention->user_id)
            ->select('id','husband_name')
            ->latest()->get();
        return view('Admin/subventions/parts/edit',compact('users','subvention'));
    }


    public function update(Request $request, $id)
    {
        if(Subvention::find($id)->update($request->except('_token')))
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
            Subvention::find($request->id)->delete();
            return response(['message'=>'تم الحذف بنجاح','status'=>200],200);
        }
        catch (\Exception $ex){
            return response(['message'=>$ex->getMessage(),'status'=>400]);
        }
    }

    public function showSubventions(){
        $subventions = Subvention::where('type','monthly')->latest()->get();
        return view('Admin.print.subvention-print',compact('subventions'));
    }
}
