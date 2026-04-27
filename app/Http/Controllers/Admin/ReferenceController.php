<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BeneficiaryCategory;
use App\Models\Center;
use App\Models\DisbursementFrequency;
use App\Models\DonationCategory;
use App\Models\DonationType;
use App\Models\DonationUnit;
use App\Models\ExpenseType;
use App\Models\Governorate;
use App\Models\JobTitle;
use App\Models\RequiredDocument;
use App\Models\RevenueType;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ReferenceController extends Controller
{
    public function dashboard()
    {
        $lookups = $this->lookupConfig();
        $counts = [];

        foreach ($lookups as $key => $lookup) {
            $counts[$key] = $lookup['model']::count();
        }

        return view('admin.references.dashboard', compact('lookups', 'counts'));
    }

    public function index(Request $request, string $type)
    {
        $lookup = $this->lookupOrFail($type);

        if ($request->ajax()) {
            return DataTables::of($lookup['model']::query()->latest())
                ->addColumn('parent', function ($row) use ($type) {
                    if ($type === 'centers') {
                        return optional($row->governorate)->name ?? '-';
                    }

                    if ($type === 'villages') {
                        return optional($row->center)->name ?? '-';
                    }

                    if ($type === 'donation-units') {
                        return '-';
                    }

                    if ($type === 'donation-categories') {
                        return $row->units->pluck('name')->filter()->implode(' - ') ?: '-';
                    }

                    return '-';
                })
                ->addColumn('action', function ($row) use ($type) {
                    if ($this->isProtectedDonationType($type, $row)) {
                        return '<span class="badge badge-secondary">نوع ثابت</span>';
                    }

                    $editButton = '<button type="button" data-id="' . $row->id . '" class="btn btn-pill btn-info-light editBtn"><i class="fa fa-edit"></i></button>';
                    $deleteButton = '<button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal" data-id="' . $row->id . '" data-title="' . e($row->name) . '"><i class="fas fa-trash"></i></button>';

                    return '<div class="d-flex">' . $editButton . $deleteButton . '</div>';
                })
                ->editColumn('is_active', function ($row) use ($type) {
                    if ($this->isProtectedDonationType($type, $row)) {
                        return '<span class="badge badge-success">مفعل</span>';
                    }

                    return '<label class="custom-switch mb-0 reference-status-switch">
                                <input type="checkbox" class="custom-switch-input reference-status-toggle" data-id="' . $row->id . '" ' . ($row->is_active ? 'checked' : '') . '>
                                <span class="custom-switch-indicator"></span>
                            </label>';
                })
                ->rawColumns(['is_active', 'action'])
                ->make(true);
        }

        return view('admin.references.index', compact('type', 'lookup'));
    }

    public function create(string $type)
    {
        return view('admin.references.parts.form', $this->formViewData($type, null, route('references.store', $type), 'POST', 'addForm', 'إضافة'));
    }

    public function store(Request $request, string $type)
    {
        $lookup = $this->lookupOrFail($type);
        $validator = Validator::make($request->all(), $this->rulesFor($type), $this->messages());

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $item = $lookup['model']::create($this->payload($request, $type) + ['is_active' => true]);

        if ($type === 'donation-categories' && $item) {
            $item->units()->sync($request->input('donation_unit_ids', []));
        }

        return response()->json(['status' => 200]);
    }

    public function edit(string $type, int $id)
    {
        $lookup = $this->lookupOrFail($type);
        $item = $lookup['model']::findOrFail($id);
        $this->abortIfProtectedDonationType($type, $item);

        return view('admin.references.parts.form', $this->formViewData($type, $item, route('references.update', [$type, $id]), 'PUT', 'updateForm', 'تعديل'));
    }

    public function update(Request $request, string $type, int $id)
    {
        $lookup = $this->lookupOrFail($type);
        $item = $lookup['model']::findOrFail($id);
        $this->abortIfProtectedDonationType($type, $item);
        $validator = Validator::make($request->all(), $this->rulesFor($type), $this->messages());

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $item->update($this->payload($request, $type));

        if ($type === 'donation-categories') {
            $item->units()->sync($request->input('donation_unit_ids', []));
        }

        return response()->json(['status' => 200]);
    }

    public function toggleStatus(Request $request, string $type, int $id)
    {
        $lookup = $this->lookupOrFail($type);
        $item = $lookup['model']::findOrFail($id);
        $this->abortIfProtectedDonationType($type, $item);

        $item->update([
            'is_active' => $request->boolean('is_active'),
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'تم تحديث الحالة بنجاح',
        ]);
    }

    public function delete(Request $request, string $type)
    {
        $lookup = $this->lookupOrFail($type);
        $item = $lookup['model']::findOrFail($request->id);
        $this->abortIfProtectedDonationType($type, $item);
        $item->delete();

        toastr()->success('تم الحذف بنجاح.');

        return redirect()->route('references.index', $type);
    }

    private function isProtectedDonationType(string $type, $item): bool
    {
        return $type === 'donation-types'
            && $item instanceof DonationType
            && $item->isProtectedType();
    }

    private function abortIfProtectedDonationType(string $type, $item): void
    {
        if (! $this->isProtectedDonationType($type, $item)) {
            return;
        }

        abort(403, 'هذا النوع ثابت ولا يمكن تعديله أو حذفه أو تعطيله.');
    }

    private function formViewData(string $type, $item, string $formAction, string $formMethod, string $formId, string $submitLabel): array
    {
        return [
            'type' => $type,
            'lookup' => $this->lookupOrFail($type),
            'parents' => $this->parentOptions($type),
            'units' => $this->unitOptions($type),
            'item' => $item,
            'formAction' => $formAction,
            'formMethod' => $formMethod,
            'formId' => $formId,
            'submitLabel' => $submitLabel,
        ];
    }

    private function payload(Request $request, string $type): array
    {
        $lookup = $this->lookupOrFail($type);
        $payload = [
            'name' => $request->name,
        ];

        if ($lookup['show_code']) {
            $payload['code'] = $request->code;
        }

        if ($lookup['show_sort_order']) {
            $payload['sort_order'] = $request->sort_order ?? 0;
        }

        if ($lookup['show_notes']) {
            $payload['notes'] = $request->notes;
        }

        if ($type === 'centers') {
            $payload['governorate_id'] = $request->governorate_id;
        }

        if ($type === 'villages') {
            $payload['center_id'] = $request->center_id;
        }

        if ($type === 'disbursement-frequencies') {
            $payload['months_interval'] = $request->months_interval;
        }

        return $payload;
    }

    private function rulesFor(string $type): array
    {
        $lookup = $this->lookupOrFail($type);

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'is_active' => ['nullable'],
        ];

        if ($lookup['show_code']) {
            $rules['code'] = ['nullable', 'string', 'max:255'];
        }

        if ($lookup['show_sort_order']) {
            $rules['sort_order'] = ['nullable', 'integer', 'min:0'];
        }

        if ($lookup['show_notes']) {
            $rules['notes'] = ['nullable', 'string'];
        }

        if ($type === 'centers') {
            $rules['governorate_id'] = ['required', 'exists:governorates,id'];
        }

        if ($type === 'villages') {
            $rules['center_id'] = ['required', 'exists:centers,id'];
        }

        if ($type === 'disbursement-frequencies') {
            $rules['months_interval'] = ['nullable', 'integer', 'min:1', 'max:12'];
        }

        if ($type === 'donation-units') {
            $rules['name'][] = Rule::unique('donation_units', 'name')->ignore(request()->route('id'));
        }

        if ($type === 'donation-categories') {
            $rules['donation_unit_ids'] = ['nullable', 'array'];
            $rules['donation_unit_ids.*'] = ['exists:donation_units,id'];
        }

        return $rules;
    }

    private function messages(): array
    {
        return [
            'name.required' => 'الاسم مطلوب',
            'governorate_id.required' => 'يرجى اختيار المحافظة',
            'governorate_id.exists' => 'المحافظة المختارة غير موجودة',
            'center_id.required' => 'يرجى اختيار المركز',
            'center_id.exists' => 'المركز المختار غير موجود',
            'name.unique' => 'هذا الاسم مستخدم بالفعل',
            'donation_unit_ids.*.exists' => 'إحدى وحدات التبرع المختارة غير موجودة',
            'months_interval.integer' => 'الفاصل الزمني يجب أن يكون رقمًا صحيحًا',
        ];
    }

    private function parentOptions(string $type)
    {
        if ($type === 'centers') {
            return Governorate::active()->orderBy('name')->get(['id', 'name']);
        }

        if ($type === 'villages') {
            return Center::active()
                ->with(['governorate' => function ($query) {
                    $query->active();
                }])
                ->orderBy('name')
                ->get()
                ->filter(function ($center) {
                    return !is_null($center->governorate);
                })
                ->values();
        }

        return collect();
    }

    private function unitOptions(string $type)
    {
        if ($type === 'donation-categories') {
            return DonationUnit::active()->orderBy('name')->get(['id', 'name']);
        }

        return collect();
    }

    private function lookupOrFail(string $type): array
    {
        $lookups = $this->lookupConfig();
        abort_unless(isset($lookups[$type]), 404);

        return $lookups[$type];
    }

    private function lookupConfig(): array
    {
        return [
            'governorates' => ['model' => Governorate::class, 'title' => 'المحافظات', 'parent_label' => null, 'show_code' => false, 'show_sort_order' => false, 'show_notes' => false],
            'centers' => ['model' => Center::class, 'title' => 'المراكز', 'parent_label' => 'المحافظة', 'show_code' => false, 'show_sort_order' => false, 'show_notes' => false],
            'villages' => ['model' => Village::class, 'title' => 'القرى', 'parent_label' => 'المركز', 'show_code' => false, 'show_sort_order' => false, 'show_notes' => false],
            'beneficiary-categories' => ['model' => BeneficiaryCategory::class, 'title' => 'تصنيفات المعاش والمستفيد', 'parent_label' => null, 'show_code' => false, 'show_sort_order' => false, 'show_notes' => false],
            'donation-types' => ['model' => DonationType::class, 'title' => 'أنواع التبرعات', 'parent_label' => null, 'show_code' => false, 'show_sort_order' => false, 'show_notes' => false],
            'donation-categories' => ['model' => DonationCategory::class, 'title' => 'أصناف التبرعات العينية', 'parent_label' => 'وحدات التبرع', 'show_code' => false, 'show_sort_order' => false, 'show_notes' => false],
            'donation-units' => ['model' => DonationUnit::class, 'title' => 'وحدات التبرع', 'parent_label' => null, 'show_code' => false, 'show_sort_order' => false, 'show_notes' => false],
            'expense-types' => ['model' => ExpenseType::class, 'title' => 'أنواع المصروفات', 'parent_label' => null, 'show_code' => false, 'show_sort_order' => false, 'show_notes' => false],
            'revenue-types' => ['model' => RevenueType::class, 'title' => 'أنواع الإيرادات الأخرى', 'parent_label' => null, 'show_code' => false, 'show_sort_order' => false, 'show_notes' => false],
            'disbursement-frequencies' => ['model' => DisbursementFrequency::class, 'title' => 'توقيتات وأنماط الصرف', 'parent_label' => null, 'show_code' => false, 'show_sort_order' => false, 'show_notes' => false],
            'job-titles' => ['model' => JobTitle::class, 'title' => 'المناصب والوظائف', 'parent_label' => null, 'show_code' => false, 'show_sort_order' => false, 'show_notes' => false],
            'required-documents' => ['model' => RequiredDocument::class, 'title' => 'المستندات المطلوبة', 'parent_label' => null, 'show_code' => false, 'show_sort_order' => false, 'show_notes' => false],
        ];
    }
}
