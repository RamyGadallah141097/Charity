<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUser;
use App\Models\Children;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function index(Request $request, $status)
    {

        if ($request->ajax()) {
            $users = User::where('status', $status)->get();
            return Datatables::of($users)
                ->addColumn('action', function ($users) {
                    return '
                     <a href="' . route('userDetails', $users->id) . '" data-id="' . $users->id . '" class="btn btn-pill btn-default "> عرض</a>
                     <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                             data-id="' . $users->id . '" data-title="' . $users->husband_name . '">
                             <i class="fas fa-trash"></i>
                     </button>';
                })
                ->editColumn('work_type', function ($users) {
                    return '<span title="' . e($users->work_type) . '">' . Str::limit($users->work_type, 20, '...') . '</span>';
                })->editColumn('address', function ($users) {
                    return '<span title="' . e($users->address) . '">' . Str::limit($users->address, 20, '...') . '</span>';
                })
                ->editColumn('social_status', function ($users) {
                    if ($users->social_status == 0)
                        return 'أعزب';
                    elseif ($users->social_status == 1)
                        return 'متزوج';
                    elseif ($users->social_status == 2)
                        return 'مطلق';
                    else
                        return 'أرمل';
                })
                ->editColumn('gross_income', function ($users) {
                    return '<span class="badge badge-success p-2" style="font-size: 12px;">' . number_format($users->gross_income, 1) . ' EGP</span>';
                })
                ->editColumn('gross_expenses', function ($users) {
                    return '<span class="badge badge-success p-2" style="font-size: 12px;">' . number_format($users->gross_expenses, 1) . ' EGP</span>';
                })->addColumn('statusChange', function ($users) {
                    if ($users->status == 'new') {
                        $available_actions = '
                               <li><a data-id="' . $users->id . '" data-status="accepted" href="#" class="statusBtn ">قبول</a></li>
                               <li><a data-id="' . $users->id . '" data-status="refused" href="#" class="statusBtn ">رفض </a></li>
                    ';
                    } elseif ($users->status == 'accepted') {
                        $available_actions = '
                               <li><a data-id="' . $users->id . '" data-status="preparing" href="#" class="statusBtn ">قيد التنفيذ</a></li>
                               <li><a data-id="' . $users->id . '" data-status="waiting" href="#" class="statusBtn ">فى الانتظار </a></li>
                               <li><a data-id="' . $users->id . '" data-status="refused" href="#" class="statusBtn "> رفض</a></li>
                    ';
                    } elseif ($users->status == 'preparing') {
                        $available_actions = '
                               <li><a data-id="' . $users->id . '" data-status="waiting" href="#" class="statusBtn ">فى الانتظار </a></li>
                               <li><a data-id="' . $users->id . '" data-status="refused" href="#" class="statusBtn ">رفض</a></li>
                    ';
                    } else { // waiting
                        $available_actions = '
                               <li><a data-id="' . $users->id . '" data-status="preparing" href="#" class="statusBtn ">قيد التنفيذ</a></li>
                               <li><a data-id="' . $users->id . '" data-status="refused" href="#" class="statusBtn ">رفض</a></li>
                        ';
                    }
                    return '
                        <div class="btn-group mb-2">
                            <button type="button" class="btn btn-default btn-pill dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> تحديث <span class="caret"></span> </button>
                             <ul class="dropdown-menu" role="menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 38px, 0px);">
                               ' . $available_actions . '
                             </ul>
                        </div>
                   ';
                })

                ->editColumn('status', function ($users) {
                    if ($users->status == 'new')
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
        } else {
            return view('Admin/users/index');
        }
    }

    public function userDetails($id)
    {
        $user = User::find($id);
        return view('Admin/users/parts/detailswwww', compact('user'));
    }

    public function updateUserStatus(Request $request)
    {
        try {
            $user = User::find($request->id);
            $user->update(['status' => $request->status]);
            return response(['message' => 'تم تحديث حالة المستفيد بنجاح', 'status' => true], 200);
        } catch (\Exception $ex) {
            return response(['message' => $ex->getMessage(), 'status' => false], 200);
        }
    }

    public function create()
    {
        return view('Admin/users/parts/create');
    }



    public function store(StoreUser $request)
    {

        try {

            // fetch user data
            $userData = $request->except('_token', 'attachments', 'child_name', 'children_national_id', 'birthday', 'age', 'schools', 'lessons_costs', 'academic_year', 'monthly_cost', 'notes', 'patient_name', 'type', 'treatment_pay_by', 'is_insurance', 'doctor_name');

            // adjust user data then save it
            if ($request->has('has_savings_book'))
                $userData['has_savings_book'] = '1';
            else
                $userData['has_savings_book'] = '0';

            if ($request->has('has_property'))
                $userData['has_property'] = '1';
            else
                $userData['has_property'] = '0';


            if ($request->has('attachments')) {
                $attachmentsName = [];
                foreach ($request->attachments as $attachment) {
                    $attachmentsName[] = $attachment->store('attachments', 'public');
                }
                $userData['attachments'] = $attachmentsName;
            }



            $user = User::create($userData);

            if (isset($request->child_name)) {
                foreach (array_keys($request->child_name) as $key) {

                    Children::create([
                        'user_id'      => $user->id,
                        'child_name'  => $request->child_name[$key] ?? null,
                        'children_national_id'  => $request->children_national_id[$key] ?? null,
                        'birthday'     => $request->birthday[$key] ?? null,
                        'age'          => $request->age[$key] ?? null,
                        'school'       => $request->schools[$key] ?? null,
                        'lessons_cost' => $request->lessons_costs[$key] ?? null,
                        'academic_year' => $request->academic_year[$key] ?? null,
                        'monthly_cost' => $request->monthly_cost[$key] ?? null,
                        'notes'        => $request->notes[$key] ?? null,
                    ]);
                }
            }
            $patientData = $request->only('patient_name', 'type',  'treatment', 'treatment_pay_by', 'is_insurance', 'doctor_name');

            if ($request->has('is_insurance') && $request->is_insurance == 'on')
                $patientData['is_insurance'] = 1;
            else
                $patientData['is_insurance'] = 0;

            $patientData['user_id'] = $user->id;
            if ($request->patient_name != null)
                Patient::create($patientData);
            toastr('تم اضافة مستفيد جديد', 'success');
            return redirect(route('users.index', 'new'));
        } catch (\Exception $ex) {
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
        return response(['message' => 'تم الحذف بنجاح', 'status' => 200], 200);
    }
}
