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
                    $editButton = '';
                    $deleteButton = '';

                    if (auth()->user()->can('donors.edit') && auth()->user()->can('donors.update')) {
                        $editButton = '
                            <button type="button" data-id="' . $donors->id . '" class="btn btn-pill btn-info-light editBtn">
                                <i class="fa fa-edit"></i>
                            </button>
                        ';
                    }

                    if (auth()->user()->can('donors.destroy')) {
                        $deleteButton = '
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $donors->id . '" data-title="' . $donors->name . '">
                                <i class="fas fa-trash"></i>
                            </button>
                        ';
                    }

                    return '<div class="d-flex">' . $editButton . $deleteButton . '</div>';
                })

                ->editColumn('notes', function ($donors) {
                    return '<span class="small-text-hover">' . ($donors->notes ?? '-----') . '</span>';
                })
                ->editColumn('created_at', function ($donors) {
                    return $donors->created_at? $donors->created_at->format('d-m-y') : "--";
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


    public function update(Request $request, $id)
    {
        $donor = Donor::findOrFail($id);

        $donor->update($request->except('_token', '_method'));
        toastr()->success("تم التحديث بنجاح");
        return redirect()->route('donors.index');
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
            Donor::destroy($request->id);
            return redirect()->back();
            return response(['message'=>'تم الحذف بنجاح','status'=>200],200);
        }
        catch (\Exception $ex){
            return response(['message'=>$ex->getMessage(),'status'=>400]);
        }

    }
}
