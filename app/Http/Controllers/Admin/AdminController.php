<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Center;
use App\Models\Governorate;
use App\Models\Village;
use App\Traits\PhotoTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class AdminController extends Controller
{
    use PhotoTrait;
    public function index(request $request)
    {
        if($request->ajax()) {
            $admins = Admin::with(['governorate', 'center', 'village', 'roles'])->latest()->get();

            return Datatables::of($admins)
                ->addColumn('action', function ($admins) {
                    $editButton = '';
                    $deleteButton = '';
                                        $editButton = '
                                            <button type="button" data-id="' . $admins->id . '" class="btn btn-pill btn-info-light editBtn">
                                                <i class="fa fa-edit"></i>
                                            </button>
                                        ';
                                    $deleteButton = '
                                        <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                                data-id="' . $admins->id . '" data-title="' . $admins->name . '">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    ';


                        return '<div class="d-flex">' . $editButton . $deleteButton . '</div>';
                    })

                ->addColumn("select_role", function ($admin) {
                    return $admin->is_system_user
                        ? ($admin->roles->pluck('name')->implode(', ') ?: '-')
                        : 'غير مستخدم للنظام';
                })
                ->addColumn('job_title', function ($admin) {
                    return $admin->job_title ?: '-';
                })
                ->addColumn('phone', function ($admin) {
                    return $admin->phone ?: '-';
                })
                ->addColumn('system_user', function ($admin) {
                    return $admin->is_system_user
                        ? '<span class="badge badge-success">نعم</span>'
                        : '<span class="badge badge-secondary">لا</span>';
                })
                ->addColumn('location', function ($admin) {
                    $parts = array_filter([
                        optional($admin->governorate)->name,
                        optional($admin->center)->name,
                        optional($admin->village)->name,
                    ]);

                    return count($parts) ? implode(' / ', $parts) : '-';
                })

                ->editColumn('created_at', function ($admins) {
                    return $admins->created_at->diffForHumans();
                })
                ->editColumn('email', function ($admin) {
                    return $admin->email ?: '-';
                })
                ->editColumn('image', function ($admins) {
                    return '
                    <img alt="image" onclick="window.open(this.src)" class="avatar avatar-md rounded-circle" src="'.get_user_file($admins->image).'">
                    ';
                })
                ->escapeColumns([])
                ->make(true);
        }else{
            return view('admin/admin/index');
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
        return view('admin/admin/profile',compact('admin'));
    }//end fun



    public function create(){
        $roles = Role::all();
        return view('admin/admin/parts/create' , [
            "roles" => $roles,
            'governorates' => Governorate::active()->orderBy('name')->get(),
            'centers' => Center::active()->orderBy('name')->get(),
            'villages' => Village::active()->orderBy('name')->get(),
        ]);
    }

    public function store(request $request): \Illuminate\Http\JsonResponse
    {
            $isSystemUser = $request->boolean('is_system_user');

            $inputs = $request->validate([
                'name' => 'required|string|max:255',
                'job_title' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:30',
                'national_id' => 'nullable|digits:14|unique:admins,national_id',
                'address' => 'nullable|string',
                'governorate_id' => 'nullable|exists:governorates,id',
                'center_id' => 'nullable|exists:centers,id',
                'village_id' => 'nullable|exists:villages,id',
                'notes' => 'nullable|string',
                'is_system_user' => 'nullable|boolean',
                'email' => ($isSystemUser ? 'required' : 'nullable') . '|email|unique:admins,email',
                'password' => ($isSystemUser ? 'required' : 'nullable') . '|min:6',
                'adminRole' => $isSystemUser ? 'required|exists:roles,name' : 'nullable',
                'image' => 'nullable|mimes:jpeg,jpg,png,gif',
                'documents.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,gif,webp',
            ]);

            $inputs['is_system_user'] = $isSystemUser;

            if($request->hasFile('image')){
                $file_name = $this->saveImage($request->file('image'),'assets/uploads/admins');
                $inputs['image'] = 'assets/uploads/admins/'.$file_name;
            }

            $inputs['documents'] = $this->storeDocuments($request);

            if (!$isSystemUser) {
                $inputs['email'] = null;
                $inputs['password'] = Str::random(16);
            }

            $admin = Admin::create($inputs);

            if ($isSystemUser && $request->filled('adminRole')) {
                $admin->assignRole($request->adminRole);
            }

            return response()->json(['status'=>200]);
    }

    public function edit(Admin $admin){
        $roles = Role::all();
        return view('admin/admin/parts/edit',[
            'admin' => $admin,
            'roles' => $roles,
            'governorates' => Governorate::active()->orderBy('name')->get(),
            'centers' => Center::active()->orderBy('name')->get(),
            'villages' => Village::active()->orderBy('name')->get(),
        ]);
    }


    public function setting(){
        $admin = auth()->guard('admin')->user();
        return view('admin/admin/setting',compact('admin'));
    }

    public function show()
    {
        $admin = auth()->guard('admin')->user();
        $roles = Role::all();
        return view('admin/admin/parts/setting',compact('admin' , "roles"));
    }

//    public function update(request $request,$id)
//    {
//        $inputs = $request->validate([
//            'email'    => 'required|unique:admins,email,'.$id,
//            'name'     => 'required',
//            'image'    => 'nullable',
//            'password' => 'nullable|min:6',
//        ]);
//        if ($request->has('image')) {
//            $file_name = $this->saveImage($request->image, 'assets/uploads/admins');
//            $inputs['image'] = 'assets/uploads/admins/' . $file_name;
//        }
//        if ($request->has('password') && $request->password != null)
//            $inputs['password'] = Hash::make($request->password);
//        else
//            unset($inputs['password']);
//        $admin = admin::findOrFail($id);
////        $admin->syncRoles($request->adminRole);
////        $admin->assignRole($request->adminRole);
//            $admin->syncRoles($request->adminRole);
//        if ($admin->update($inputs)) {
//            toastr()->success("تم التحديث بنجاح");
//        } else {
//            toastr()->error("فشل التحديث، يرجى المحاولة مرة أخرى");
//        }
//
//        return redirect()->route('admins.index');
//    }

    public function update(Request $request, $id)
    {
        $isSystemUser = $request->boolean('is_system_user');

        $inputs = $request->validate([
            'name' => 'required|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:30',
            'national_id' => 'nullable|digits:14|unique:admins,national_id,' . $id . ',id',
            'address' => 'nullable|string',
            'governorate_id' => 'nullable|exists:governorates,id',
            'center_id' => 'nullable|exists:centers,id',
            'village_id' => 'nullable|exists:villages,id',
            'notes' => 'nullable|string',
            'is_system_user' => 'nullable|boolean',
            'email' => ($isSystemUser ? 'required' : 'nullable') . '|email|unique:admins,email,' . $id . ',id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'password' => 'nullable|min:6',
            'adminRole' => $isSystemUser ? 'required|exists:roles,name' : 'nullable',
            'documents.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,gif,webp',
        ]);

        $inputs['is_system_user'] = $isSystemUser;

        if ($request->hasFile('image')) {
            $file_name = $this->saveImage($request->file('image'), 'assets/uploads/admins');
            $inputs['image'] = 'assets/uploads/admins/' . $file_name;
        }

        $admin = Admin::findOrFail($id);

        if (!empty($request->password)) {
            $inputs['password'] = $request->password;
        } else {
            unset($inputs['password']);
        }

        if ($request->hasFile('documents')) {
            $existingDocuments = is_array($admin->documents) ? $admin->documents : [];
            $inputs['documents'] = array_merge($existingDocuments, $this->storeDocuments($request));
        }

        if ($isSystemUser && $request->has('adminRole')) {
            $admin->syncRoles($request->adminRole);
        } elseif (! $isSystemUser) {
            $admin->syncRoles([]);
            $inputs['email'] = null;
            $inputs['password'] = !empty($request->password) ? $request->password : Str::random(16);
        }

        if ($admin->update($inputs)) {
            toastr()->success("تم التحديث بنجاح");
            return response()->json(['status'=>200]);
        } else {
            toastr()->error("فشل التحديث، يرجى المحاولة مرة أخرى");
        }

        return redirect()->route('admins.index');
    }

    private function storeDocuments(Request $request): array
    {
        $documents = [];
        $targetPath = 'assets/uploads/admins/documents';

        if (! $request->hasFile('documents')) {
            return $documents;
        }

        if (! file_exists($targetPath)) {
            mkdir($targetPath, 0777, true);
        }

        foreach ($request->file('documents') as $document) {
            $fileName = rand(1, 9999) . time() . '_' . $document->getClientOriginalName();
            $document->move($targetPath, $fileName);
            $documents[] = $targetPath . '/' . $fileName;
        }

        return $documents;
    }

//    public function showChangeRole(Request $request){
//            $admin = admin::find($request->admin_id);
//            $roles = Role::all();
//            if (!$admin) {
//                return redirect()->back()->with('error', 'admin not found');
//            }
//
//            return view('admin.admin.parts.changeRole', compact('admin' , "roles"));
//
//    }
//
//    public function changeRole(Request $request)
//    {
//
////        dd($request->id);
////        dd($request->id);
//        $admin = admin::find($request->adminId);
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
