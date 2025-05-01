<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUser;
use App\Models\Borrower;
use App\Models\Children;
use App\Models\Donation;
use App\Models\Guarantor;
use App\Models\Patient;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
//solve path
class UserController extends Controller
{
    public function index(Request $request, $status)
    {

        if ($request->ajax()) {


            $query = User::where('status', $status);

            if ($request->filled('social_status')) {
                $query->where('social_status', $request->social_status);
            }

            if ($request->filled('standard_living')) {
                $query->where('standard_living', "<=", $request->standard_living);
            }

//            if ($request->filled('family_number')) {
//                $parents = User::where("wife_name" != null)->count();
//                $parents += User::where("husband_name" != null)->count();
//                if ($request->has('family_number') && (int)$request->family_number >= 1) {
//                    $query->whereHas('childrens', function ($q) use ($request) {
//                        $q->groupBy('user_id')
//                            ->havingRaw('COUNT(id) = ?', [$request->family_number]);
//                    });
//                } else {
//                    $query->whereDoesntHave('childrens');
//                }
//            }

            if ($request->filled('family_number')) {
                $familyNumber = (int) $request->family_number;

                if($familyNumber == 1){
                    $query->where(function($q) {
                        $q->where('husband_name' , "")
                            ->orWhere('wife_name' , "");
                    })
                        ->whereDoesntHave('childrens');
                }
                elseif ($familyNumber == 2) {
                    $query->where(function($q) {
                        $q->where([
                            ['husband_name', '!=', null],
                            ['wife_name', '!=', null]
                        ])->doesntHave('childrens');
                        // or condition for compine the both
                        $q->orWhere(function($q2) {
                            $q2->whereNull('husband_name')
                                ->orWhereNull('wife_name');
                        })
                            ->has('childrens', '=', 1);
                    });

                }
                else {
                    $query->withCount('childrens')
                        ->having('childrens_count', '=', $familyNumber - 2);

                }
            }



            $users = $query->get();
            return Datatables::of($users)
                ->addColumn('action', function ($users) {
                    return '
                     <div class="dropdown">
                      <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        actions
                      </button>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                        <p class="dropdown-item text-center" href="#">
                            <a href="' . route('DonationDetails', $users->id) . '" data-id="' . $users->id . '" > عرض التبرعات</a>
                        </p>
                        <p class="dropdown-item  text-center" href="#">
                            <a class="btn btn-pill btn-success-light" href="' . route('userDetails', $users->id) . '" data-id="' . $users->id . '" > <i class="fas fa-eye"></i></a>
                        </p>
                         <p class="dropdown-item text-center" href="#">
                                <a href="'. route("users.edit" , $users->id ) .'" class="btn btn-pill btn-primary-light ">
                                    <i class="fas fa-edit"> </i>
                                </a>
                        </p>
                        <p class="dropdown-item text-center" href="#">
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                 data-id="' . $users->id . '" data-title="' . $users->husband_name . '">
                                 <i class="fas fa-trash"></i>
                            </button>
                        </p>

                      </div>
                    </div>
                        ';
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
                })->editColumn('standard_living', function ($users) {
                    return '<span class="badge badge-danger p-2" style="font-size: 12px;">' . number_format($users->standard_living, 1) . ' EGP</span>';
                })->addColumn('statusChange', function ($users) {
                    if ($users->status == 'new') {
                        $available_actions = '
                                <li><a data-id="' . $users->id . '" data-status="accepted" href="#" class="statusBtn ">قبول</a></li>
                               <li><a data-id="' . $users->id . '" data-status="refused" href="#" class="statusBtn ">رفض </a></li>
                            ';

                    } elseif ($users->status == 'accepted') {
                        $available_actions = '
                            <li>
                                <a data-id="{{ $users->id }}" data-status="preparing" href="#" class="statusBtn">قيد التنفيذ</a>
                            </li>
                            <li>
                                <a data-id="{{ $users->id }}" data-status="refused" href="#" class="statusBtn">رفض</a>
                            </li>
                    ';
                    } elseif ($users->status == 'preparing') {
                        $available_actions = '
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
                ->editColumn('wife_name', function ($users) {
                    return $users->wife_name ? $users->wife_name : '-';
                })->editColumn('husband_name', function ($users) {
                    return $users->husband_name ? $users->husband_name : '-';
                })

                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin/users/index');
        }
    }

//    public function searchNID(Request $request)
//    {
//        if (is_numeric($request->searchNID)){
//            $searchNID = $request->searchNID;
//
//            $user = User::where("husband_national_id", $searchNID)
//                ->orWhere("wife_national_id", $searchNID)
//                ->first()
//                ?? Borrower::where("nationalID", $searchNID)->first()
//                ?? Guarantor::where("nationalID", $searchNID)->first()
//                ?? Children::where("children_national_id", $searchNID)->first();
//
//
//
//            if (!$user) {
//                toastr()->error("لا يوجد مستفيد لهذا الرقم القومي");
//                return redirect()->route("adminHome");
//            }
//
//            $patients = Patient::where('user_id', $user->id)->get();
//            if ($patients->isEmpty()) {
//                $patients = null;
//            }
//
//            return view('admin/search', compact('user', 'patients'));
//        }else{
//            toastr()->error("الرقم االومي يجب ان يكون رقم ");
//            return redirect()->back();
//        }
//    }

//    public function searchNID(Request $request)
//    {
//        if (!is_numeric($request->searchNID)) {
//            toastr()->error("الرقم القومي يجب أن يكون رقماً");
//            return redirect()->back();
//        }
//
//        $searchNID = $request->searchNID;
//
//        $user = null;
//        $borrower = null;
//        $guarantor = null;
//        $child = null;
//
//        if ($found = User::where("husband_national_id", $searchNID)
//            ->orWhere("wife_national_id", $searchNID)
//            ->first()) {
//            $user = $found;
//        } elseif ($found = Borrower::where("nationalID", $searchNID)->first()) {
//            $borrower = $found;
//        } elseif ($found = Guarantor::where("nationalID", $searchNID)->first()) {
//            $guarantor = $found;
//        } elseif ($found = Children::where("children_national_id", $searchNID)->first()) {
//            $child = $found;
//        } else {
//            toastr()->error("لا يوجد مستفيد لهذا الرقم القومي");
//            return redirect()->route("adminHome");
//        }
//
//        $patients = $user ? Patient::where('user_id', $user->id)->get() : null;
//
//        return view('admin/search', compact('user', 'borrower', 'guarantor', 'child', 'patients'));
//    }
    public function searchNID(Request $request)
    {
        if (!is_numeric($request->searchNID)) {
            toastr()->error("الرقم القومي يجب أن يكون رقماً");
            return redirect()->back();
        }

        $searchNID = $request->searchNID;

        $user = null;
        $borrower = null;
        $guarantor = null;
        $child = null;
        $patients = null;

        if ($found = User::where("husband_national_id", $searchNID)
            ->orWhere("wife_national_id", $searchNID)
            ->first()) {
            $user = $found;
            $patients = $user->patient;
        } elseif ($found = Borrower::where("nationalID", $searchNID)->first()) {
            $borrower = $found;
        } elseif ($found = Guarantor::where("nationalID", $searchNID)->first()) {
            $guarantor = $found;
        } elseif ($found = Children::where("children_national_id", $searchNID)->first()) {
            $child = $found;
        } else {
            toastr()->error("لا يوجد مستفيد لهذا الرقم القومي");
            return redirect()->route("adminHome");
        }

        return view('admin/search', compact('user', 'borrower', 'guarantor', 'child', 'patients'));
    }


    public function userDetails($id, Request $request)
    {
        if ($request->has('searchNID')) {
            $user = User::where("husband_national_id", $request->searchNID)->orWhere("wife_national_id", $request->searchNID)->first();
        } else {
            $user = User::find($id);
        }

        if (!$user){
            toastr()->error("لا يوجد مستفيد لهذا الرقم القومي");
            return redirect()->route("adminHome");
        }
        $patients = $user ? Patient::where('user_id', $user->id)->get() : [];

        return view('admin/users/parts/details', compact('user', 'patients'));
    }

    public function DonationDetails($id, Request $request)
    {
        if ($request->ajax()) {
            $user = User::where("id", $id)->get();

            return Datatables::of($user)
                ->addColumn('name', function ($user) {
                    return $user->husband_name;
                })->addColumn('zakahTotal', function ($user) {
                    return $user->subvention ?$user->subvention->price :  0;

                })
                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin/users/parts/DonationDetails', ['id' => $id]);
        }
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

//
//    public function updateUserStatus(Request $request)
//    {
//        try {
//            $user = User::where("id" , $request->user_id)->first();
//           if ($user){
//               $user->status = $request->status;
//               $user->save();
//           }else{
//               toastr()->error("المستخدم غير موجود");
//               return redirect("admin/users/new");
//           }
//            toastr()->success("success");
//            return redirect("admin/users/new");
//        } catch (\Exception $ex) {
//            toastr()->error($ex->getMessage());
//            return redirect("admin/users/new");
//        }
//    }

    public function create()
    {
        return view('admin/users/parts/create');
    }


//
//    public function store(StoreUser $request)
//    {
//        $userData = $request->except('_token', 'attachments', 'child_names',  'children_national_id',  'age', 'schools', 'monthly_cost', 'notes', 'patient_name',  'treatment_pay_by', 'type', 'doctor_name', 'treatment');
//
//        $user = User::create([
//            'husband_name' => $request->husband_name,
//            'wife_name' => $request->wife_name,
//            'husband_national_id' => $request->husband_national_id,
//            'wife_national_id' => $request->wife_national_id,
//            'age_husband' => $request->age_husband,
//            'address' => $request->address,
//            'age_wife' => $request->age_wife,
//            'social_status' => $request->social_status,
//            'work_type' => $request->work_type,
//            'nearest_phone' => $request->nearest_phone,
//            'salary' => $request->salary,
//            'pension' => $request->pension,
//            'insurance' => $request->insurance,
//            'dignity' => $request->dignity,
//            'trade' => $request->trade,
//            'pillows' => $request->pillows,
//            'other' => $request->other,
//            'gross_income' => $request->gross_income,
//            'rent' => $request->rent,
//            'gas' => $request->gas,
//            'debt' => $request->debt,
//            'water' => $request->water,
//            'electricity' => $request->electricity,
//            'association' => $request->association,
//            'food' => $request->food,
//            'study' => $request->study,
//            'gross_expenses' => $request->gross_expenses,
//            'standard_living' => $request->standard_living,
//            'Case_evaluation' => $request->Case_evaluation,
//        ]);
//
//
//        if (isset($request->child_names)) {
//            for ($i = 0; $i < count($request->child_names); $i++) {
//                Children::create([
//                    'user_id' => $user->id,
//                    'child_name' => $request->child_names[$i] ?? null,
//                    'children_national_id' => $request->children_national_id[$i] ?? null,
//                    'age' => $request->age[$i] ?? null,
//                    'school' => $request->schools[$i] ?? null,
//                    'monthly_cost' => $request->monthly_cost[$i] ?? null,
//                    'notes' => $request->notes[$i] ?? null,
//                ]);
//            }
//        }
//
//        $patientData = $request->only('patient_name',  'treatment', 'treatment_pay_by', 'type', 'doctor_name', 'is_insurance', 'notes');
//
//        if ($request->has('is_insurance') && $request->is_insurance == 'on')
//            $patientData['is_insurance'] = '1';
//        else
//            $patientData['is_insurance'] = '0';
//
//        $patientData['user_id'] = $user->id;
//
//        if (isset($patientData['patient_name']))
//            for ($i = 0; $i < count($patientData['patient_name']); $i++) {
//                Patient::create([
//                    'user_id' => $patientData['user_id'],
//                    'patient_name' => $patientData['patient_name'][$i],
//                    'treatment' => $patientData['treatment'][$i],
//                    'treatment_pay_by' => $patientData['treatment_pay_by'][$i],
//                    'type' => $patientData['type'][$i],
//                    'doctor_name' => $patientData['doctor_name'][$i],
//                    'is_insurance' => $patientData['is_insurance'],
//                    'notes' => $patientData['notes'][$i],
//                ]);
//            }
//
//        if ($request->has('attachments')) {
//            $attachmentsName = [];
//            foreach ($request->attachments as $attachment) {
//                $attachmentsName[] = $attachment->store('attachments', 'public');
//                $user->attachments = $attachmentsName;
//                $user->save();
//            }
//        }
//        toastr('تم اضافة مستفيد جديد', 'success');
//        return redirect(route('users.index', 'new'));
//    }
//

    public function store(StoreUser $request)
    {
        DB::beginTransaction();
        try {
        // إنشاء المستخدم
        $userData = $request->except('_token', 'attachments', 'child_names',  'children_national_id',  'age', 'schools', 'monthly_cost', 'notes', 'patient_name',  'treatment_pay_by', 'type', 'doctor_name', 'treatment');

        $user = User::create([
            'husband_name' => $request->husband_name,
            'wife_name' => $request->wife_name,
            'husband_national_id' => $request->husband_national_id,
            'wife_national_id' => $request->wife_national_id,
            'age_husband' => $request->age_husband,
            'address' => $request->address,
            'age_wife' => $request->age_wife,
            'social_status' => $request->social_status,
            'work_type' => $request->work_type,
            'nearest_phone' => $request->nearest_phone,
            'salary' => $request->salary,
            'pension' => $request->pension,
            'insurance' => $request->insurance,
            'dignity' => $request->dignity,
            'trade' => $request->trade,
            'pillows' => $request->pillows,
            'other' => $request->other,
            'gross_income' => $request->gross_income,
            'rent' => $request->rent,
            'gas' => $request->gas,
            'debt' => $request->debt,
            'water' => $request->water,
            'electricity' => $request->electricity,
            'association' => $request->association,
            'food' => $request->food,
            'study' => $request->study,
            'gross_expenses' => $request->gross_expenses,
            'standard_living' => $request->standard_living,
            'Case_evaluation' => $request->Case_evaluation,
        ]);





            // إضافة الأطفال
            if ($request->filled('child_names')) {
                foreach ($request->child_names as $index => $childName) {
                    Children::create([
                        'user_id' => $user->id,
                        'child_name' => $childName,
                        'children_national_id' => $request->children_national_id[$index] ?? null,
                        'age' => $request->age[$index] ?? null,
                        'school' => $request->schools[$index] ?? null,
                        'monthly_cost' => $request->monthly_cost[$index] ?? null,
                        'notes' => $request->notes[$index] ?? null,
                    ]);
                }
            }

            // إضافة بيانات المرضى
            if ($request->filled('patient_name')) {
                foreach ($request->patient_name as $index => $patientName) {
                    Patient::create([
                        'user_id' => $user->id,
                        'patient_name' => $patientName,
                        'treatment' => $request->treatment[$index] ?? null,
                        'treatment_pay_by' => $request->treatment_pay_by[$index] ?? null,
                        'type' => $request->type[$index] ?? null,
                        'doctor_name' => $request->doctor_name[$index] ?? null,
                        'is_insurance' => $request->has('is_insurance') ? '1' : '0',
                        'notes' => $request->notes[$index] ?? null,
                    ]);
                }
            }

            // حفظ المرفقات
            if ($request->hasFile('attachments')) {
                $attachments = [];
                foreach ($request->file('attachments') as $attachment) {
                    $attachments[] = $attachment->store('attachments', 'public');
                }
                $user->update(['attachments' => $attachments]);
            }

            DB::commit();

            toastr('تم اضافة مستفيد جديد', 'success');
            return redirect(route('users.index', 'new'));

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'حدث خطأ أثناء حفظ البيانات.');
        }
    }

    public function edit($id)
    {

        $user = User::with(['childrens', 'patient'])->findOrFail($id);
        $patients = Patient::where("user_id" , $id)->get();
//        dd($patients , $user);
        return view('admin/users/parts/edit', [
            'user' => $user,
            "patients" => $patients,
            'setting' => Setting::first()
        ]);
    }

    public function update(Request $request, $id)
    {
//        dd($request->all());

        DB::beginTransaction();

        try {

            $user = User::findOrFail($id);
            $user->update([
                'husband_name' => $request['husband_name'],
                'wife_name' => $request['wife_name'],
                'husband_national_id' => $request['husband_national_id'],
                'wife_national_id' => $request['wife_national_id'],
                'age_husband' => $request->age_husband,
                'age_wife' => $request->age_wife,
                'address' => $request['address'],
                'social_status' => $request['social_status'],
                'work_type' => $request['work_type'],
                'nearest_phone' => $request['nearest_phone'],
                'salary' => $request['salary'] ?? 0,
                'pension' => $request['pension'] ?? 0,
                'insurance' => $request['insurance'] ?? 0,
                'dignity' => $request['dignity'] ?? 0,
                'trade' => $request['trade'] ?? 0,
                'pillows' => $request['pillows'] ?? 0,
                'other' => $request['other'] ?? 0,
                'gross_income' => $request->gross_income ?? 0,
                'rent' => $request['rent'] ?? 0,
                'gas' => $request['gas'] ?? 0,
                'debt' => $request['debt'] ?? 0,
                'water' => $request['water'] ?? 0,
                'electricity' => $request['electricity'] ?? 0,
                'association' => $request['association'] ?? 0,
                'food' => $request['food'] ?? 0,
                'study' => $request['study'] ?? 0,
                'gross_expenses' => $request->gross_expenses ?? 0,
                'standard_living' => $request->standard_living ?? 0,
                'Case_evaluation' => $request['Case_evaluation'] ?? null
            ]);


            // Update Children
            $user->childrens()->delete();
            if (!empty($request['child_names'])) {
                foreach ($request['child_names'] as $index => $childName) {
                    if (!empty($childName)) {
                        $user->childrens()->create([
                            'child_name' => $childName,
                            'children_national_id' => $request['children_national_id'][$index] ?? null,
                            'age' => $request->age[$index] ?? null,
                            'school' => $request['schools'][$index] ?? null,
                            'monthly_cost' => $request['monthly_cost'][$index] ?? null,
                            'notes' => $request['notes'][$index] ?? null
                        ]);
                    }
                }
            }



            $user->patient()->delete();
            if (!empty($request['patient_name'])) {
                foreach ($request['patient_name'] as $index => $patientName) {
                    if (!empty($patientName)) {
                        $user->patient()->create([
                            'patient_name' => $patientName,
                            'treatment_pay_by' => $request['treatment_pay_by'][$index] ?? null,
                            'treatment' => $request['treatment'][$index] ?? null,
                            'type' => $request['type'][$index] ?? 1,
                            'doctor_name' => $request['doctor_name'][$index] ?? null,
                            'is_insurance' => isset($request['is_insurance'][$index]) ? 1 : 0
                        ]);
                    }
                }
            }



            if ($request->hasFile('attachments')) {
                foreach ($request->attachments as $attachment) {
                    $attachmentsName[] = $attachment->store('attachments', 'public');
                    $user->attachments = $attachmentsName;
                    $user->save();
                }
            }




            DB::commit();


            return redirect("admin/users/new")->with('success', 'تم تحديث بيانات المستفيد بنجاح');


        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $e->getMessage());

        }
    }

    public function destroy(Request $request)
    {
        $user = User::findOrFail($request->id);
        $user->delete();

        return redirect()->back()->with('success', 'تم الحذف بنجاح!');
    }



//    funciton to the arrow charts
    public function CartIndex()
    {
        return view('charts.dashboard');
    }

    public function getChartData()
    {
        $data = User::selectRaw('COUNT(id) as count, DATE(created_at) as date')
            ->whereNotNull('created_at') // Avoid NULL values
            ->groupBy('date')
            ->orderBy('date', 'ASC') // Newest first
            ->get();

        return response()->json($data);
    }
}



//the form of change the status
//<form action="'. route('updateUserStatus') .'" method="POST">
//'. csrf_field() .'
//<input type="hidden" name="user_id" value="'. $users->id .'">
//<input type="hidden" name="status" value="accepted">
//<button class="btn btn-outline-success">قبول</button>
//</form>
//
//<form action="'. route('updateUserStatus') .'" method="POST">
//'. csrf_field() .'
//<input type="hidden" name="user_id" value="'. $users->id .'">
//<input type="hidden" name="status" value="refused">
//<button class="btn btn-outline-danger">رفض</button>
//</form>
