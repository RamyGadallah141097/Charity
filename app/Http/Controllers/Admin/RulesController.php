<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donor;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class RulesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $roles = Role::all();

            return Datatables::of($roles)
                ->editColumn("name", function ($role) {
                    return $role->name;
                })
                ->addColumn("permissions", function ($role) {
                    $permissions = $role->permissions
                        ->pluck('name')
                        ->map(fn($permission) => __('permissions.' . $permission))
                        ->implode(" - ");



                    return '<span style="
                        display: inline-block;max-width: 330px;overflow: hidden;white-space: nowrap;text-overflow: ellipsis;padding: 5px;transition: max-width 0.3s ease-in-out;position: relative;cursor: pointer;"onmouseover="this.style.maxWidth=\'100%\'; this.style.whiteSpace=\'normal\'; this.style.overflow=\'visible\';
                        this.style.background=\'rgba(0, 0, 0, 0.7)\'; this.style.color=\'white\'; this.style.padding=\'8px\';
                        this.style.borderRadius=\'5px\'; this.style.position=\'absolute\'; this.style.zIndex=\'1000\';"

                        onmouseout="this.style.maxWidth=\'330px\'; this.style.whiteSpace=\'nowrap\'; this.style.overflow=\'hidden\';
                        this.style.background=\'transparent\'; this.style.color=\'inherit\'; this.style.padding=\'5px\';
                        this.style.borderRadius=\'0px\'; this.style.position=\'relative\'; this.style.zIndex=\'auto\';"

                        title="' . e($permissions) . '">'

                        . (!empty($permissions) ? e($permissions) : "-") .

                        '</span>';
                })
                ->addColumn('action', function ($role) {
                    return '
                        <button type="button" data-id="' . e($role->id) . '"
                                class="btn btn-pill btn-info-light editBtn">
                            <i class="fa fa-edit"></i>
                        </button>

                        <button class="btn btn-pill btn-danger-light"
                                data-toggle="modal" data-target="#delete_modal"
                                data-id="' . e($role->id) . '"
                                data-title="' . e($role->name) . '">
                            <i class="fas fa-trash"></i>
                        </button>
                    ';
                })


                ->escapeColumns([])
                ->make(true);
        } else {
            return view('Admin/Roles/Roles');
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('Admin/Roles/parts/create' , ["permissions" =>$permissions]) ;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            // Create the role
            $role = Role::create(['name' => $request->name , "guard_name" => 'admin']);

            if (!empty($request->permissions)) {
                $permissionNames = \Spatie\Permission\Models\Permission::whereIn('id', $request->permissions)->pluck('name')->toArray();

                $role->givePermissionTo($permissionNames);
            }

            return response()->json([
                "status" => 200,
                "message" => "Role created successfully"
            ]);
        } catch (\Exception $e) {
            \Log::error('Error creating role: ' . $e->getMessage());

            return response()->json([
                "status" => 500,
                "message" => "Failed to create role",
                "error" => $e->getMessage()
            ]);
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

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
//        $role = Role::with("permission")->where("id" , $id)->find($id);
        $permissions = Permission::all();
        return view('Admin/Roles/parts/edit' , ["permissions" =>$permissions , "role" =>$role]) ;
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
        try {


            $role = Role::find($id);
            $role->name = $request->name;
            $role->save();
            $role->permissions()->sync($request->permissions);

            return response()->json([
                "status"=>200,
                "message"=>"<div class=\"alert alert-success\" role=\"alert\"> تم التعديل بنجاح </div>"
            ]);
        }catch (\Exception $e){
            return response()->json([
                "status"=>405,
                "message"=>"<div class=\"alert alert-danger\" role=\"alert\"> ".$e->getMessage()." </div>"
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function delete(Request $request)
    {
        try {
            Role::destroy($request->id);
            return redirect()->back();
            return response(['message'=>'تم الحذف بنجاح','status'=>200],200);
        }
        catch (\Exception $ex){
            return response(['message'=>$ex->getMessage(),'status'=>400]);
        }

    }

}
