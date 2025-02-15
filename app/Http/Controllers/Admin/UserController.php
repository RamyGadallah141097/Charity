<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUser;
use App\Models\Children;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{

    public function index(request $request,$status)
    {
        if($request->ajax()) {
            $users = User::where('status',$status)->get();
            return Datatables::of($users)
                ->addColumn('action', function ($users) {
                    return '
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $users->id . '" data-title="' . $users->husband_name . '">
                                    <i class="fas fa-trash"></i>
                            </button>
                       ';
                })
                ->addColumn('statusChange',function ($users){
                    if($users->status == 'refused'){
                        $available_actions = '
                               <li><a data-id="'.$users->id.'" data-status="accepted" href="#" class="statusBtn text-dark">قبول</a></li>
                               <li><a data-id="'.$users->id.'" data-status="preparing" href="#" class="statusBtn text-dark">قيد التنفيذ</a></li>
                    ';
                    }
                    elseif ($users->status == 'preparing'){
                        $available_actions = '
                               <li><a data-id="'.$users->id.'" data-status="accepted" href="#" class="statusBtn text-dark">قبول</a></li>
                               <li><a data-id="'.$users->id.'" data-status="refused" href="#" class="statusBtn text-danger">رفض</a></li>
                    ';
                    }
                    elseif($users->status == 'accepted'){
                        $available_actions = '
                               <li><a data-id="'.$users->id.'" data-status="refused" href="#" class="statusBtn text-danger">رفض</a></li>
                               <li><a data-id="'.$users->id.'" data-status="preparing" href="#" class="statusBtn text-dark">قيد التنفيذ</a></li>
                    ';
                    }
                    else{
                        $available_actions = '
                               <li><a data-id="'.$users->id.'" data-status="accepted" href="#" class="statusBtn text-dark">قبول</a></li>
                               <li><a data-id="'.$users->id.'" data-status="preparing" href="#" class="statusBtn text-dark">قيد التنفيذ</a></li>
                               <li><a data-id="'.$users->id.'" data-status="refused" href="#" class="statusBtn text-danger">رفض</a></li>
                        ';
                    }

                   return '
                        <div class="btn-group mb-2">
                            <button type="button" class="btn btn-default btn-pill dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> تحديث <span class="caret"></span> </button>
                             <ul class="dropdown-menu" role="menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 38px, 0px);">
                               '.$available_actions.'
                             </ul>
                        </div>
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
            return view('Admin/users/index',compact('status'));
        }
    }

    public function userDetails($id){
        $user = User::find($id);
        return view('Admin/users/parts/details',compact('user'));
    }

    public function updateUserStatus(request $request){
        try{
            $user = User::find($request->id);
            $user->update(['status' => $request->status]);
            return response(['message'=>'تم تحديث حالة المستفيد بنجاح','status'=>true],200);
        }
        catch (\Exception $ex){
            return response(['message'=>$ex->getMessage(),'status'=>false],200);
        }

    }



    public function create()
    {
        return view('Admin/users/parts/create');
    }



    public function store(StoreUser $request)
    {
        try{
            // fetch user data
            $userData = $request->except('_token','names','schools','lessons_costs','academic_year','monthly_cost','notes','name','type','treatment_pay_by','is_insurance','doctor_name');

            // adjust user data then save it
            if($request->has('has_savings_book'))
                $userData['has_savings_book'] = '1';
            else
                $userData['has_savings_book'] = '0';

            if($request->has('has_property'))
                $userData['has_property'] = '1';
            else
                $userData['has_property'] = '0';

            $userData['gross_income']   = $userData['salary']+$userData['pension']+$userData['insurance']+$userData['dignity']+$userData['trade']+$userData['pillows']+$userData['other'];
            $userData['total_expenses'] = $userData['rent']+$userData['gas']+$userData['debt']+$userData['water']+$userData['electricity']+$userData['association']+$userData['food']+$userData['study'];

            $user = User::create($userData);

            if($user){
                foreach ($request->names as $key=>$name){
                    if ($request->names[$key] != null){
                        Children::create([
                            'user_id'      => $user->id,
                            'name'         => $request->names[$key],
                            'school'       => $request->schools[$key],
                            'lessons_cost' => $request->lessons_costs[$key],
                            'academic_year'=> $request->academic_year[$key],
                            'monthly_cost' => $request->monthly_cost[$key],
                            'notes'        => $request->notes[$key],
                        ]);
                    }
                }
                $patientData = $request->only('name','type','treatment_pay_by','is_insurance','doctor_name','treatment');

                if($request->has('is_insurance') && $request->is_insurance == 'on')
                    $patientData['is_insurance'] = 1;
                else
                    $patientData['is_insurance'] = 0;

                $patientData['user_id'] = $user->id;
                if ($request->name != null)
                    Patient::create($patientData);
                toastr('تم اضافة مستفيد جديد','success');
                return redirect(route('users.index','new'));
            }
        }
        catch (\Exception $ex){
            return back()->withErrors($ex->getMessage());
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


    public function delete(Request $request)
    {
        User::find($request->id)->delete();
        return response(['message'=>'تم الحذف بنجاح','status'=>200],200);
    }
}
