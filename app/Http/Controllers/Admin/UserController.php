<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUser;
use App\Models\BeneficiaryCategory;
use App\Models\Borrower;
use App\Models\Center;
use App\Models\Children;
use App\Models\Donation;
use App\Models\Governorate;
use App\Models\Guarantor;
use App\Models\Patient;
use App\Models\Setting;
use App\Models\User;
use App\Models\Village;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
//solve path
class UserController extends Controller
{
    public function index(Request $request, $status = null)
    {
        $allowedStatuses = ['new', 'accepted', 'preparing', 'refused'];
        $status = in_array($status, $allowedStatuses, true) ? $status : null;
        $selectedStatus = $request->filled('status') && in_array($request->status, $allowedStatuses, true)
            ? $request->status
            : $status;


        if ($request->ajax()) {

            $query = User::query();

            if ($selectedStatus) {
                $query->where('status', $selectedStatus);
            }

            if ($request->filled('social_status')) {
                $query->where('social_status', $request->social_status);
            }

            if ($request->filled('has_monthly_subvention')) {
                $query->where('has_monthly_subvention', $request->has_monthly_subvention);
            }



            if ($request->filled('standard_living')) {
                $query->where('standard_living', "<=", $request->standard_living);
            }

             if ($request->filled('beneficiary_category_id')) {
                 $query->where('beneficiary_category_id', $request->beneficiary_category_id);
             }

            if ($request->filled('family_number')) {
                $familyNumber = (int) $request->family_number;

                if ($familyNumber == 1) {
                    $query->where(function ($q) {
                        $q->where('husband_name', "")
                            ->orWhere('wife_name', "");
                    })
                        ->whereDoesntHave('childrens');
                } elseif ($familyNumber == 2) {
                    $query->where(function ($q) {
                        $q->where([
                            ['husband_name', '!=', null],
                            ['wife_name', '!=', null]
                        ])->doesntHave('childrens');
                        // or condition for compine the both
                        $q->orWhere(function ($q2) {
                            $q2->whereNull('husband_name')
                                ->orWhereNull('wife_name');
                        })
                            ->has('childrens', '=', 1);
                    });
                } else {
                    $query->withCount('childrens')
                        ->having('childrens_count', '=', $familyNumber - 2);
                }
            }

            $users = $query->get();
            return Datatables::of($users)
                ->addColumn('action', function ($users) {
                    $canDelete = $this->canDeleteUser($users);
                    $deleteButton = $canDelete
                        ? '<button class="btn btn-sm btn-danger-light delete_button px-3 py-2" data-id="' . $users->id . '" data-title="' . e($users->husband_name) . '" title="حذف">
                                <i class="fe fe-trash"></i>
                            </button>'
                        : '';

                    return '
                        <div class="btn-list d-flex justify-content-center align-items-center" style="gap: 8px;">
                            <button class="btn btn-sm btn-primary-light detailsBtn px-3 py-2" data-id="' . $users->id . '" title="عرض التفاصيل">
                                <i class="fe fe-eye"></i>
                            </button>
                            <a href="' . route("users.edit", $users->id) . '" class="btn btn-sm btn-info-light px-3 py-2" title="تعديل">
                                <i class="fe fe-edit"></i>
                            </a>
                            ' . $deleteButton . '
                        </div>
                    ';
                })
                ->addColumn('beneficiary', function ($user) {
                    $husband = $user->husband_name ?: '<span class="text-muted">نقص</span>';
                    $wife = $user->wife_name ?: '<span class="text-muted">نقص</span>';
                    $codeHtml = $user->beneficiary_code
                        ? '<span class="badge badge-light text-dark border">الكود: ' . e($user->beneficiary_code) . '</span>'
                        : '<span class="badge badge-light text-muted border">الكود: -</span>';
                    return '<div class="d-flex align-items-center">
                                <div class="ml-3 text-right">
                                    <div class="mb-1">' . $codeHtml . '</div>
                                    <h6 class="mb-0 font-weight-bold text-primary">' . $husband . '</h6>
                                    <small class="text-muted"><i class="fe fe-user mr-1"></i>الزوجة: ' . $wife . '</small>
                                </div>
                            </div>';
                })
                ->addColumn('financials', function ($user) {
                    return '<div class="small">
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">الدخل:</span>
                                    <span class="text-success font-weight-bold">' . number_format($user->gross_income) . '</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span class="text-muted">المصاريف:</span>
                                    <span class="text-danger font-weight-bold">' . number_format($user->gross_expenses) . '</span>
                                </div>
                                <div class="border-top pt-1 d-flex justify-content-between">
                                    <span class="font-weight-bold">المستوى:</span>
                                    <span class="badge badge-primary-light">' . number_format($user->standard_living) . '</span>
                                </div>
                            </div>';
                })
                ->addColumn('monthlySubventionToggle', function ($users) {
                    $checked = $users->has_monthly_subvention ? 'checked' : '';

                    return '
                        <div class="d-flex justify-content-center">
                            <label class="custom-switch mb-0">
                                <input type="checkbox" class="custom-switch-input monthly-subvention-toggle" data-id="' . $users->id . '" ' . $checked . '>
                                <span class="custom-switch-indicator custom-switch-indicator-lg custom-switch-indicator-success"></span>
                            </label>
                        </div>
                    ';
                })
                ->editColumn('monthly_subvention_amount', function ($users) {
                    return '<span class="text-dark font-weight-bold">' . number_format($users->monthly_subvention_amount ?? 0, 0) . ' <small>ج.م</small></span>';
                })
                ->addColumn('statusChange', function ($users) {
                    if ($users->status == 'new') {
                        $available_actions = '
                                <li><a data-id="' . $users->id . '" data-status="accepted" href="#" class="statusBtn ">قبول</a></li>
                               <li><a data-id="' . $users->id . '" data-status="refused" href="#" class="statusBtn ">رفض </a></li>
                            ';
                    } elseif ($users->status == 'accepted') {
                        $available_actions = '
                            <li>
                                <a data-id="' . $users->id . '" data-status="preparing" href="#" class="statusBtn">قيد التنفيذ</a>
                            </li>
                            <li>
                                <a data-id="' . $users->id . '" data-status="refused" href="#" class="statusBtn">رفض</a>
                            </li>
                    ';
                    } elseif ($users->status == 'preparing') {
                        $available_actions = '
                                <li><a data-id="' . $users->id . '" data-status="accepted" href="#" class="statusBtn ">قبول</a></li>
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
            $beneficiaryCategories = BeneficiaryCategory::active()->orderBy('sort_order')->orderBy('name')->get();
            $totalUsers = User::count();
            $acceptedUsers = User::where('status', 'accepted')->count();
            $preparingUsers = User::where('status', 'preparing')->count();
            $refusedUsers = User::where('status', 'refused')->count();

            return view('admin/users/index', compact(
                'selectedStatus',
                'beneficiaryCategories',
                'totalUsers',
                'acceptedUsers',
                'preparingUsers',
                'refusedUsers'
            ));
        }
    }

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
            ->first()
        ) {
            $user = $found;
            $patients = $user->patients;
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

        if (!$user) {
            toastr()->error("لا يوجد مستفيد لهذا الرقم القومي");
            return redirect()->route("adminHome");
        }
        $user->load(['childrens', 'patients', 'governorate', 'center', 'village', 'beneficiaryCategory']);
        $patients = $user ? $user->patients : collect();

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
                    return $user->subventions ?
                        $user->subventions->sum('price') : 0;
                })
                ->escapeColumns([])
                ->make(true);
        } else {
            return view('admin/users/parts/DonationDetails', ['id' => $id]);
        }
    }

    public function viewAttachment(Request $request)
    {
        $path = $request->query('path');

        if (!$path || !Storage::disk('public')->exists($path)) {
            abort(404);
        }

        return Response::file(Storage::disk('public')->path($path));
    }


    public function updateUserStatus(Request $request)
    {
        try {
            $user = User::find($request->id);
            $user->update(["status" => $request->status]);
            return response(['message' => 'تم تحديث حالة المستفيد بنجاح', 'status' => true], 200);
        } catch (\Exception $ex) {
            return response(['message' => $ex->getMessage(), 'status' => false], 200);
        }
    }

    public function toggleMonthlySubvention(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|exists:users,id',
                'has_monthly_subvention' => 'required|boolean',
            ]);

            $user = User::findOrFail($request->id);
            $isActive = $request->boolean('has_monthly_subvention');

            $user->update([
                'has_monthly_subvention' => $isActive,
            ]);

            return response([
                'status' => true,
                'message' => 'تم تحديث حالة الإعانة الشهرية بنجاح',
                'data' => [
                    'has_monthly_subvention' => $user->has_monthly_subvention,
                    'monthly_subvention_amount' => $user->monthly_subvention_amount,
                ],
            ], 200);
        } catch (\Exception $ex) {
            return response(['message' => $ex->getMessage(), 'status' => false], 200);
        }
    }



    public function create()
    {
        return view('admin/users/parts/create', $this->referenceViewData());
    }




    public function store(StoreUser $request)
    {

        $user = User::create([
            'beneficiary_code' => @$request->beneficiary_code,
            'husband_name' => @$request->husband_name,
            'wife_name' => @$request->wife_name,
            'husband_national_id' => @$request->husband_national_id,
            'wife_national_id' => @$request->wife_national_id,
            'age_husband' => @$request->age_husband,
            'address' => @$request->address,
            'governorate_id' => $request->governorate_id,
            'center_id' => $request->center_id,
            'village_id' => $request->village_id,
            'age_wife' => @$request->age_wife,
            'social_status' => @$request->social_status,
            'work_type' => @$request->work_type,
            'nearest_phone' => @$request->nearest_phone,
            'beneficiary_category_id' => $request->beneficiary_category_id,
            'salary' => @$request->salary,
            'pension' => @$request->pension,
            'has_monthly_subvention' => $request->boolean('has_monthly_subvention'),
            'monthly_subvention_amount' => $request->boolean('has_monthly_subvention')
                ? ($request->monthly_subvention_amount ?? 0)
                : ($request->monthly_subvention_amount ?? 0),
            'dignity' => @$request->dignity,
            'trade' => @$request->trade,
            'pillows' => @$request->pillows,
            'other' => @$request->other,
            'gross_income' => @$request->gross_income,
            'rent' => @$request->rent,
            'gas' => @$request->gas,
            'debt' => @$request->debt,
            'water' => @$request->water,
            'electricity' => @$request->electricity,
            'association' => @$request->association,
            'food' => @$request->food,
            'study' => @$request->study,
            'medical_expenses' => @$request->medical_expenses,
            'gross_expenses' => @$request->gross_expenses,
            'standard_living' => @$request->standard_living,
            'Case_evaluation' => @$request->Case_evaluation,
            "status" => "new"
        ]);

        // إضافة الأطفال
        if ($request->filled('child_names')) {
            foreach ($request->child_names as $index => $childName) {
                $child = Children::create([
                    'user_id' => $user->id,
                    'child_name' => $childName,
                    'children_national_id' => $request->children_national_id[$index] ?? null,
                    'age' => $request->age[$index] ?? null,
                    'gender' => $request->child_gender[$index] ?? null,
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
        return redirect()->route('users.index');
    }

    public function edit($id)
    {

        $user = User::with(['childrens', 'patients', 'governorate', 'center', 'village', 'beneficiaryCategory'])->findOrFail($id);
        $patients = $user->patients;
        return view('admin/users/parts/edit', array_merge($this->referenceViewData(), [
            'user' => $user,
            "patients" => $patients,
            'setting' => Setting::first()
        ]));
    }

    public function update(StoreUser $request, $id)
    {
        DB::beginTransaction();

        try {

            $user = User::findOrFail($id);
            $user->update([
                'beneficiary_code' => $request->beneficiary_code,
                'husband_name' => $request['husband_name'],
                'wife_name' => $request['wife_name'],
                'husband_national_id' => $request['husband_national_id'],
                'wife_national_id' => $request['wife_national_id'],
                'age_husband' => $request->age_husband,
                'age_wife' => $request->age_wife,
                'address' => $request['address'],
                'governorate_id' => $request->governorate_id,
                'center_id' => $request->center_id,
                'village_id' => $request->village_id,
                'social_status' => $request['social_status'],
                'work_type' => $request['work_type'],
                'nearest_phone' => $request['nearest_phone'],
                'beneficiary_category_id' => $request->beneficiary_category_id,
                'salary' => $request['salary'] ?? 0,
                'pension' => $request['pension'] ?? 0,
                'has_monthly_subvention' => $request->boolean('has_monthly_subvention'),
                'monthly_subvention_amount' => $request->boolean('has_monthly_subvention')
                    ? ($request->monthly_subvention_amount ?? 0)
                    : ($request->monthly_subvention_amount ?? 0),
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
                'medical_expenses' => $request->medical_expenses ?? 0,
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
                            'gender' => $request['child_gender'][$index] ?? null,
                            'school' => $request['schools'][$index] ?? null,
                            'monthly_cost' => $request['monthly_cost'][$index] ?? null,
                            'notes' => $request['notes'][$index] ?? null
                        ]);
                    }
                }
            }



            $user->patients()->delete();
            if (!empty($request['patient_name'])) {
                foreach ($request['patient_name'] as $index => $patientName) {
                    if (!empty($patientName)) {
                        $user->patients()->create([
                            'patient_name' => $patientName,
                            'treatment_pay_by' => $request['treatment_pay_by'][$index] ?? null,
                            'treatment' => $request['treatment'][$index] ?? null,
                            'type' => $request['type'][$index] ?? 1,
                            'doctor_name' => $request['doctor_name'][$index] ?? null,
                        ]);
                    }
                }
            }



            if ($request->hasFile('attachments')) {
                $existingAttachments = is_array($user->attachments) ? $user->attachments : [];

                foreach ($existingAttachments as $existingAttachment) {
                    if ($existingAttachment && Storage::disk('public')->exists($existingAttachment)) {
                        Storage::disk('public')->delete($existingAttachment);
                    }
                }

                $newAttachments = [];
                foreach ($request->file('attachments') as $attachment) {
                    $newAttachments[] = $attachment->store('attachments', 'public');
                }

                $user->attachments = $newAttachments;
                $user->save();
            }




            DB::commit();


            return redirect()->route('users.index')->with('success', 'تم تحديث بيانات المستفيد بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        $user = User::findOrFail($request->id);

        if (! $this->canDeleteUser($user)) {
            return redirect()
                ->back()
                ->with('error', 'لا يمكن حذف هذا المستفيد لأنه مقبول أو تمت عليه عمليات إعانة.');
        }

        $user->delete();

        return redirect()->back()->with('success', 'تم الحذف بنجاح!');
    }

    private function canDeleteUser(User $user): bool
    {
        return $user->status !== 'accepted' && ! $user->subventions()->exists();
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

    public function PrintUsersNew()
    {
        $users = User::where('status', 'new')->get();
        return view('admin/print/PrintUsersNew', compact('users'));
    }
    public function PrintUsersAccepted()
    {
        $users = User::where('status', 'accepted')->get();
        return view('admin/print/PrintUsersAccepted', compact('users'));
    }
    public function PrintUsersPennding()
    {
        $users = User::where('status', 'preparing')->get();
        return view('admin/print/PrintUsersPennding', compact('users'));
    }
    public function PrintUsersRefused()
    {
        $users = User::where('status', 'refused')->get();
        return view('admin/print/PrintUsersRefused', compact('users'));
    }

    private function referenceViewData(): array
    {
        return [
            'governorates' => Governorate::active()->orderBy('name')->get(),
            'centers' => Center::active()->orderBy('name')->get(),
            'villages' => Village::active()->orderBy('name')->get(),
            'beneficiaryCategories' => BeneficiaryCategory::active()->orderBy('sort_order')->orderBy('name')->get(),
        ];
    }
}
