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
                    return '
                    <div class="d-flex">
                        <!-- Edit Button -->
                        <button type="button" data-id="' . $admins->id . '" class="btn btn-pill btn-info-light editBtn">
                            <i class="fa fa-edit"></i>
                        </button>

                        <!-- Change Role Form -->
                        <form action="' . route('showChangeRole') . '" method="POST" style="display: inline;">
                            ' . csrf_field() . '
                            <input type="hidden" name="admin_id" value="' . $admins->id . '">
                            <button class="btn btn-pill btn-secondary-light" type="submit">Change Role</button>
                        </form>

                        <!-- Delete Button -->
                        <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                data-id="' . $admins->id . '" data-title="' . $admins->name . '">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                ';
                })
                ->addColumn("select_role", function ($admin) {
                    return optional($admin->role)->name ?? 'No Role';
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
        return view('Admin/admin.parts.create');
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
            if(Admin::create($inputs))
                return response()->json(['status'=>200]);
            else
                return response()->json(['status'=>405]);
    }

    public function edit(Admin $admin){
        return view('Admin/admin.parts.edit',compact('admin'));
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
        if ($admin->update($inputs))
            return response()->json(['status' => 200]);
        else
            return response()->json(['status' => 405]);
    }

    public function showChangeRole(Request $request){
            $admin = Admin::find($request->admin_id);
            $roles = Role::all();
            if (!$admin) {
                return redirect()->back()->with('error', 'Admin not found');
            }

            return view('Admin.admin.parts.changeRole', compact('admin' , "roles"));

    }

        public function changeRole(Request $request)
        {


            $admin = Admin::find($request->id);


            // Find role
            $role = Role::findById($request->adminRole);





            $admin->syncRoles([$role->id]); // Correct way to assign a new role and remove old ones


            return response()->json(['status' => 200, 'message' => 'Role updated successfully']);
        }

}//end class
