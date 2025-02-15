<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ResearchController extends Controller
{
    public function index(request $request)
    {
        if($request->ajax()) {
            $users = User::latest()->get();
            return Datatables::of($users)
                ->addColumn('action', function ($users) {
                    return '
                            <a href="'.route("social_research",$users->id).'" class="btn btn-pill btn-secondary-light"
                                    data-id="' . $users->id . '">
                                    طباعة بحث
                                    <i class="fas fa-print"></i>
                            </a>
                       ';
                })

                ->addColumn('details', function ($users) {
                    return '<button type="button" data-id="' . $users->id . '" class="btn btn-pill btn-default detailsBtn"> عرض</button>';
                })

                ->editColumn('social_status', function ($users) {
                    if($users->social_status == 'single')
                        return 'أعزب';
                    elseif ($users->social_status == 'married')
                        return 'متزوج';
                    elseif ($users->social_status == 'divorced')
                        return 'مطلق';
                    else
                        return 'أرمل';
                })
                ->editColumn('nearest_phone', function ($users) {
                    $phone = $users->nearest_phone;
                    return '<a href="tel:'.$phone.'">'.$phone.'</a>';
                })
                ->editColumn('status', function ($users) {
                    if($users->status == 'new')
                        return '<span class="badge badge-primary">جديد</span>';
                    elseif ($users->status == 'preparing')
                        return '<span class="badge badge-warning">قيد التنفيذ</span>';
                    elseif ($users->status == 'accepted')
                        return '<span class="badge badge-success">مقبول</span>';
                    else
                        return '<span class="badge badge-danger">مرفوض</span>';
                })
                ->escapeColumns([])
                ->make(true);
        }else{
            return view('Admin/users/research');
        }
    }

    public function social_research($user_id){
        $user = User::findOrFail($user_id);
        return view('Admin/print/social-research',compact('user'));
    }

    public function researchReceive(){
        return view('Admin/print/research-receive');
    }
}
