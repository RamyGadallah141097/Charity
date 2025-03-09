<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Product;
use App\Traits\PhotoTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class AdminController extends Controller
{
    use PhotoTrait;
    public function index(request $request)
    {
        if($request->ajax()) {
            $admins = Admin::latest()->get();

            return Datatables::of($admins)
                ->addColumn('action', function ($admins) {
                    $editButton = '';
                    $deleteButton = '';

                                if (auth()->user()->can('admins.edit')) {
                                            $editButton = '
                                            <button type="button" data-id="' . $admins->id . '" class="btn btn-pill btn-info-light editBtn">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                        ';
                                }
                                if (auth()->user()->can('admins.destroy')) {
                                    $deleteButton = '
                                        <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                                data-id="' . $admins->id . '" data-title="' . $admins->name . '">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    ';
                                }

                        return '<div class="d-flex">' . $editButton . $deleteButton . '</div>';
                    })

                ->addColumn("select_role", function ($admin) {
                    return $admin->roles->pluck('name')->implode(', ') ?: 'No Role';
                })

                ->editColumn('created_at', function ($admins) {
                    return $admins->created_at->diffForHumans();
                })
                ->editColumn('image', function ($admins) {
                    return '
                    <img alt="image" onclick="window.open(this.src)" class="avatar avatar-md rounded-circle" src="'.get_user_file($admins->image).'">
                    ';
                })
                ->escapeColumns([])
                ->make(true);
        }else{
            return view('Admin/admin/index');
        }
    }


    public function delete(Request $request)
    {
        $admin = Admin::where('id', $request->id)->first();
        if ($admin == auth()->guard('admin')->user()) {
            return response(['message'=>"لا يمكن حذف المشرف المسجل به !",'status'=>501],200);
        } else {
            if (file_exists($admin->image)) {
                unlink($admin->image);
            }
            $admin->delete();
            return redirect()->back();
            return response(['message'=>'تم الحذف بنجاح','status'=>200],200);
        }
    }

    public function myProfile()
    {
        $admin = auth()->guard('admin')->user();
        return view('Admin/admin/profile',compact('admin'));
    }//end fun



    public function create(){
        $roles = Role::all();
        return view('Admin/admin.parts.create' , ["roles" => $roles]);
    }

    public function store(request $request): \Illuminate\Http\JsonResponse
    {
            $inputs = $request->validate([
                'email'     => 'required|unique:admins',
                'name'      => 'required',
                'password'  => 'required|min:6',
                'image'     => 'nullable|mimes:jpeg,jpg,png,gif',
            ]);
            if($request->has('image')){
                $file_name = $this->saveImage($request->image,'assets/uploads/admins');
                $inputs['image'] = 'assets/uploads/admins/'.$file_name;
            }
            $inputs['password'] = Hash::make($request->password);
            if(Admin::create($inputs)->assignRole($request->adminRole))

                return response()->json(['status'=>200]);
            else
                return response()->json(['status'=>405]);
    }

    public function edit(Admin $admin){
        $roles = Role::all();
        return view('Admin/admin.parts.edit',compact('admin' , "roles"));
    }

    public function update(request $request,$id)
    {
        $inputs = $request->validate([
            'email'    => 'required|unique:admins,email,'.$id,
            'name'     => 'required',
            'image'    => 'nullable',
            'password' => 'nullable|min:6',
        ]);
        if ($request->has('image')) {
            $file_name = $this->saveImage($request->image, 'assets/uploads/admins');
            $inputs['image'] = 'assets/uploads/admins/' . $file_name;
        }
        if ($request->has('password') && $request->password != null)
            $inputs['password'] = Hash::make($request->password);
        else
            unset($inputs['password']);
        $admin = Admin::findOrFail($id);
//        $admin->syncRoles($request->adminRole);
//        $admin->assignRole($request->adminRole);
            $admin->syncRoles($request->adminRole);
        if ($admin->update($inputs))
            return response()->json(['status' => 200]);
        else
            return response()->json(['status' => 405]);
    }

//    public function showChangeRole(Request $request){
//            $admin = Admin::find($request->admin_id);
//            $roles = Role::all();
//            if (!$admin) {
//                return redirect()->back()->with('error', 'Admin not found');
//            }
//
//            return view('Admin.admin.parts.changeRole', compact('admin' , "roles"));
//
//    }
//
//    public function changeRole(Request $request)
//    {
//
////        dd($request->id);
////        dd($request->id);
//        $admin = Admin::find($request->adminId);
//
//
//
//        $role = Role::findById($request->adminRole);
//
//
//        $admin->syncRoles($role);
//
//
//        return response()->json(['status' => 200, 'message' => 'Role updated successfully']);
//    }


}//end class
