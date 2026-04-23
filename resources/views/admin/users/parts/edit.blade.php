@extends('admin/layouts/master')
@section('title')
    {{ isset($setting->title) ? $setting->title : '' }} | تعديل مستفيد
@endsection
@section('page_name')
    تعديل بيانات: {{ $user->husband_name }} / {{ $user->wife_name }}
@endsection

@section('content')
    <style>
        .gallery-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        .gallery-item {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            height: 120px;
        }
        .gallery-item:hover {
            transform: translateY(-5px);
        }
        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            cursor: pointer;
        }
        .gallery-item .overlay {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: 0.3s;
        }
        .gallery-item:hover .overlay {
            opacity: 1;
        }
        .modal-img-container {
            text-align: center;
            padding: 20px;
        }
        .modal-img-container img {
            max-width: 100%;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
    </style>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <form id="editForm" action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <!-- Core Info -->
                <div class="card custom-card">
                    <div class="card-header border-bottom">
                        <h3 class="card-title text-primary"><i class="fe fe-user mr-2"></i>البيانات الأساسية</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-label font-weight-bold">كود المستفيد</label>
                                <input type="text" value="{{ old('beneficiary_code', $user->beneficiary_code) }}" class="form-control rounded-pill" name="beneficiary_code" placeholder="أدخل كود المستفيد">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label font-weight-bold">اسم الزوج <span class="text-danger">*</span></label>
                                <input type="text" value="{{ old('husband_name', $user->husband_name) }}" class="form-control rounded-pill" name="husband_name" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label font-weight-bold">اسم الزوجة <span class="text-danger">*</span></label>
                                <input type="text" value="{{ old('wife_name', $user->wife_name) }}" class="form-control rounded-pill" name="wife_name" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label font-weight-bold">الرقم القومى للزوج</label>
                                <input type="number" value="{{ old('husband_national_id', $user->husband_national_id) }}" class="form-control rounded-pill" name="husband_national_id">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label font-weight-bold">الرقم القومى للزوجة</label>
                                <input type="number" value="{{ old('wife_national_id', $user->wife_national_id) }}" class="form-control rounded-pill" name="wife_national_id">
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-label">عمر الزوج</label>
                                <input type="number" value="{{ old('age_husband', $user->age_husband) }}" class="form-control bg-light" name="age_husband" readonly>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-label">عمر الزوجة</label>
                                <input type="number" value="{{ old('age_wife', $user->age_wife) }}" class="form-control bg-light" name="age_wife" readonly>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-label">الحالة الاجتماعية</label>
                                <select name="social_status" class="form-control select2">
                                    <option value="0" {{ old('social_status', $user->social_status) == '0' ? 'selected' : '' }}>أعزب</option>
                                    <option value="1" {{ old('social_status', $user->social_status) == '1' ? 'selected' : '' }}>متزوج</option>
                                    <option value="2" {{ old('social_status', $user->social_status) == '2' ? 'selected' : '' }}>مطلق</option>
                                    <option value="3" {{ old('social_status', $user->social_status) == '3' ? 'selected' : '' }}>أرمل</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-label">تصنيف المستفيد</label>
                                <select name="beneficiary_category_id" class="form-control select2">
                                    <option value="">اختر التصنيف</option>
                                    @foreach ($beneficiaryCategories as $category)
                                        <option value="{{ $category->id }}" {{ old('beneficiary_category_id', $user->beneficiary_category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label">نوع العمل</label>
                                <input type="text" value="{{ old('work_type', $user->work_type) }}" class="form-control" name="work_type">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label">أقرب تليفون</label>
                                <input type="number" value="{{ old('nearest_phone', $user->nearest_phone) }}" class="form-control" name="nearest_phone">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Location -->
                <div class="card custom-card">
                    <div class="card-header border-bottom">
                        <h3 class="card-title text-info"><i class="fe fe-map-pin mr-2"></i>العنوان والمكان</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label class="form-label">المحافظة</label>
                                <select name="governorate_id" class="form-control select2">
                                    <option value="">اختر المحافظة</option>
                                    @foreach ($governorates as $governorate)
                                        <option value="{{ $governorate->id }}" {{ old('governorate_id', $user->governorate_id) == $governorate->id ? 'selected' : '' }}>{{ $governorate->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="form-label">المركز</label>
                                <select name="center_id" class="form-control select2">
                                    <option value="">اختر المركز</option>
                                    @foreach ($centers as $center)
                                        <option value="{{ $center->id }}" data-governorate-id="{{ $center->governorate_id }}" {{ old('center_id', $user->center_id) == $center->id ? 'selected' : '' }}>{{ $center->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="form-label">القرية</label>
                                <select name="village_id" class="form-control select2">
                                    <option value="">اختر القرية</option>
                                    @foreach ($villages as $village)
                                        <option value="{{ $village->id }}" data-center-id="{{ $village->center_id }}" {{ old('village_id', $user->village_id) == $village->id ? 'selected' : '' }}>{{ $village->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="form-label">العنوان بالتفصيل</label>
                                <input type="text" value="{{ old('address', $user->address) }}" class="form-control" name="address">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Financials -->
                <div class="card custom-card">
                    <div class="card-header border-bottom">
                        <h3 class="card-title text-success"><i class="fe fe-dollar-sign mr-2"></i>البيانات المالية</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Income -->
                            <div class="col-md-6 border-left">
                                <p class="font-weight-bold text-muted mb-4 border-bottom pb-2">تفاصيل الدخل</p>
                                <div class="form-group row">
                                    <label class="col-md-4 form-label">مرتب</label>
                                    <div class="col-md-8"><input type="number" class="form-control income-input" name="salary" value="{{ old('salary', $user->salary ?: 0) }}"></div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 form-label">معاش</label>
                                    <div class="col-md-8"><input type="number" class="form-control income-input" name="pension" value="{{ old('pension', $user->pension ?: 0) }}"></div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 form-label">كرامة/تكافل</label>
                                    <div class="col-md-8"><input type="number" class="form-control income-input" name="dignity" value="{{ old('dignity', $user->dignity ?: 0) }}"></div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 form-label">إيراد آخر (1)</label>
                                    <div class="col-md-8"><input type="number" class="form-control income-input" name="trade" value="{{ old('trade', $user->trade ?: 0) }}"></div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 form-label">إيراد آخر (2)</label>
                                    <div class="col-md-8"><input type="number" class="form-control income-input" name="pillows" value="{{ old('pillows', $user->pillows ?: 0) }}"></div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 form-label font-weight-bold text-primary">إجمالي الدخل</label>
                                    <div class="col-md-8"><input type="number" id="gross_income" name="gross_income" class="form-control bg-primary-transparent font-weight-bold" value="{{ old('gross_income', $user->gross_income ?: 0) }}" readonly></div>
                                </div>
                            </div>

                            <!-- Expenses -->
                            <div class="col-md-6">
                                <p class="font-weight-bold text-muted mb-4 border-bottom pb-2">تفاصيل المصروفات</p>
                                <div class="form-group row">
                                    <label class="col-md-4 form-label">إيجار</label>
                                    <div class="col-md-8"><input type="number" class="form-control expense-input" name="rent" value="{{ old('rent', $user->rent ?: 0) }}"></div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 form-label">مرافق</label>
                                    <div class="col-md-8">
                                        <div class="input-group input-group-sm">
                                            <input type="number" class="form-control expense-input" name="gas" value="{{ old('gas', $user->gas ?: 0) }}" placeholder="غاز">
                                            <input type="number" class="form-control expense-input" name="water" value="{{ old('water', $user->water ?: 0) }}" placeholder="مياه">
                                            <input type="number" class="form-control expense-input" name="electricity" value="{{ old('electricity', $user->electricity ?: 0) }}" placeholder="كهرباء">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 form-label">طعام</label>
                                    <div class="col-md-8"><input type="number" class="form-control expense-input" name="food" value="{{ old('food', $user->food ?: 0) }}"></div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 form-label">تعليم/طبية</label>
                                    <div class="col-md-8">
                                        <div class="input-group input-group-sm">
                                            <input type="number" class="form-control expense-input" name="study" value="{{ old('study', $user->study ?: 0) }}" placeholder="تعليم">
                                            <input type="number" class="form-control expense-input" name="medical_expenses" value="{{ old('medical_expenses', $user->medical_expenses ?: 0) }}" placeholder="طبية">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 form-label">ديون/جمعيات</label>
                                    <div class="col-md-8">
                                        <div class="input-group input-group-sm">
                                            <input type="number" class="form-control expense-input" name="debt" value="{{ old('debt', $user->debt ?: 0) }}" placeholder="ديون">
                                            <input type="number" class="form-control expense-input" name="association" value="{{ old('association', $user->association ?: 0) }}" placeholder="جمعية">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 form-label font-weight-bold text-danger">إجمالي المصاريف</label>
                                    <div class="col-md-8"><input type="number" id="gross_expenses" name="gross_expenses" class="form-control bg-danger-transparent font-weight-bold" value="{{ old('gross_expenses', $user->gross_expenses ?: 0) }}" readonly></div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 bg-light p-3 rounded-lg d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 font-weight-bold">مستوى المعيشة التقديري</h5>
                            <input type="number" id="standard_living" name="standard_living" class="form-control text-center font-weight-bold bg-white" style="width: 150px;" value="{{ old('standard_living', $user->standard_living ?: 0) }}" readonly>
                        </div>
                    </div>
                </div>

                <!-- Children -->
                <div class="card custom-card">
                    <div class="card-header border-bottom d-flex justify-content-between">
                        <h3 class="card-title text-info"><i class="fe fe-users mr-2"></i>الأبناء والأسرة</h3>
                        <button type="button" class="btn btn-info btn-sm btn-pill" id="add"><i class="fe fe-plus mr-1"></i> إضافة ابن</button>
                    </div>
                    <div class="card-body">
                        <div id="child_container">
                            @foreach ($user->childrens as $index => $child)
                                <div class="bg-light p-3 rounded mb-3 child-row border position-relative">
                                    <button type="button" class="btn btn-danger btn-sm position-absolute" style="top: 10px; left: 10px; z-index: 10" onclick="$(this).closest('.child-row').remove()">
                                        <i class="fe fe-trash"></i>
                                    </button>
                                    <div class="row align-items-end">
                                        <div class="col-md-4">
                                            <label class="form-label font-weight-bold small">اسم الابن</label>
                                            <input type="text" class="form-control form-control-sm" name="child_names[]" value="{{ $child->child_name }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label font-weight-bold small">الرقم القومي</label>
                                            <input type="number" class="form-control form-control-sm" name="children_national_id[]" value="{{ $child->children_national_id }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label font-weight-bold small">السن</label>
                                            <input type="number" class="form-control form-control-sm bg-white" name="age[]" value="{{ $child->age }}" readonly>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label font-weight-bold small">النوع</label>
                                            <select class="form-control form-control-sm no-select2" name="child_gender[]">
                                                <option value="1" {{ $child->gender == 1 ? 'selected' : '' }}>ذكر</option>
                                                <option value="0" {{ $child->gender == 0 ? 'selected' : '' }}>أنثى</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 mt-3">
                                            <label class="form-label font-weight-bold small">المدرسة/الكلية</label>
                                            <input type="text" class="form-control form-control-sm" name="schools[]" value="{{ $child->school }}">
                                        </div>
                                        <div class="col-md-2 mt-3">
                                            <label class="form-label font-weight-bold small">التكلفة (ج.م)</label>
                                            <input type="number" class="form-control form-control-sm" name="monthly_cost[]" value="{{ $child->monthly_cost }}">
                                        </div>
                                        <div class="col-md-6 mt-3">
                                            <label class="form-label font-weight-bold small">ملاحظات</label>
                                            <input type="text" class="form-control form-control-sm" name="notes[]" value="{{ $child->notes }}">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div id="child_containerN"></div>
                    </div>
                </div>

                <!-- Health Status -->
                <div class="card custom-card">
                    <div class="card-header border-bottom d-flex justify-content-between">
                        <h3 class="card-title text-danger"><i class="fe fe-heart mr-2"></i>الحالات الصحية</h3>
                        <button type="button" class="btn btn-danger btn-sm btn-pill" id="add_patient"><i class="fe fe-plus mr-1"></i> إضافة حالة</button>
                    </div>
                    <div class="card-body">
                        <div id="patient_container">
                            @foreach ($user->patients as $index => $patient)
                                <div class="bg-light p-3 rounded mb-3 patient-row border border-danger-transparent position-relative">
                                    <button type="button" class="btn btn-danger btn-sm position-absolute" style="top: 10px; left: 10px; z-index: 10" onclick="$(this).closest('.patient-row').remove()">
                                        <i class="fe fe-trash"></i>
                                    </button>
                                    <div class="row align-items-end">
                                        <div class="col-md-4">
                                            <label class="form-label font-weight-bold small">اسم المريض</label>
                                            <input type="text" class="form-control form-control-sm" name="patient_name[]" value="{{ $patient->patient_name }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label font-weight-bold small">وسيلة صرف العلاج</label>
                                            <input type="text" class="form-control form-control-sm" name="treatment_pay_by[]" value="{{ $patient->treatment_pay_by }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label font-weight-bold small">الطبيب المعالج</label>
                                            <input type="text" class="form-control form-control-sm" name="doctor_name[]" value="{{ $patient->doctor_name }}">
                                        </div>
                                        <div class="col-md-8 mt-3">
                                            <label class="form-label font-weight-bold small">تفاصيل العلاج</label>
                                            <input type="text" class="form-control form-control-sm" name="treatment[]" value="{{ $patient->treatment }}">
                                        </div>
                                        <div class="col-md-4 mt-3">
                                            <label class="form-label font-weight-bold small">النوع</label>
                                            <select class="form-control form-control-sm no-select2" name="type[]">
                                                <option value="1" {{ $patient->type == 1 ? 'selected' : '' }}>ذكر</option>
                                                <option value="0" {{ $patient->type == 0 ? 'selected' : '' }}>أنثى</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-12">
                <!-- Subvention -->
                <div class="card custom-card">
                    <div class="card-header border-bottom">
                        <h3 class="card-title"><i class="fe fe-check-circle mr-2"></i>الإعانة والقرار</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label font-weight-bold mb-0">إعانة شهرية</label>
                                <label class="custom-switch">
                                    <input type="checkbox" id="has_monthly_subvention" name="has_monthly_subvention" value="1" class="custom-switch-input" {{ old('has_monthly_subvention', $user->has_monthly_subvention) ? 'checked' : '' }}>
                                    <span class="custom-switch-indicator custom-switch-indicator-lg custom-switch-indicator-success"></span>
                                </label>
                            </div>
                            <div class="mt-3">
                                <label class="form-label small">المبلغ الحالي (ج.م)</label>
                                <input type="number" id="monthly_subvention_amount" name="monthly_subvention_amount" class="form-control form-control-lg font-weight-bold text-success border-success" value="{{ old('monthly_subvention_amount', $user->monthly_subvention_amount) }}">
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label class="form-label font-weight-bold">قرار اللجنة والتقييم</label>
                            <textarea rows="6" class="form-control" name="Case_evaluation" placeholder="...">{{ old('Case_evaluation', $user->Case_evaluation) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Attachments Gallery -->
                <div class="card custom-card">
                    <div class="card-header border-bottom">
                        <h3 class="card-title"><i class="fe fe-paperclip mr-2"></i>المرفقات الحالية</h3>
                    </div>
                    <div class="card-body">
                        @php
                            $attachments = is_string($user->attachments) ? json_decode($user->attachments, true) : $user->attachments;
                        @endphp
                        
                        @if ($attachments && count($attachments) > 0)
                            <div class="gallery-container">
                                @foreach ($attachments as $attachment)
                                    <div class="gallery-item" onclick="showImage('{{ route('attachments.view', ['path' => $attachment]) }}')">
                                        <img src="{{ route('attachments.view', ['path' => $attachment]) }}" alt="Doc">
                                        <div class="overlay"><i class="fe fe-maximize text-white"></i></div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4 bg-light rounded">
                                <i class="fe fe-image text-muted" style="font-size: 2rem;"></i>
                                <p class="text-muted mb-0">لا توجد مرفقات حالياً</p>
                            </div>
                        @endif
                        
                        <div class="mt-4">
                            <label class="form-label font-weight-bold">إضافة مرفقات جديدة</label>
                            <input type="file" class="dropify" name="attachments[]" data-height="100" multiple>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="card custom-card border-primary shadow-sm">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary btn-block btn-lg btn-pill shadow">
                            <i class="fe fe-save mr-2"></i> تحديث والحفظ
                        </button>
                        <a href="{{ route('users.index') }}" class="btn btn-light btn-block mt-3">إلغاء</a>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Image Modal -->
    <div id="imageModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content bg-transparent border-0">
                <div class="modal-img-container text-right">
                    <button type="button" class="btn btn-light btn-sm mb-2" data-dismiss="modal"><i class="fe fe-x"></i> اغلاق</button>
                    <img id="modalImage" src="" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>
        $(function () {
            // Dropdown Filtering
            const $governorate = $('select[name="governorate_id"]');
            const $center = $('select[name="center_id"]');
            const $village = $('select[name="village_id"]');
            const centerOptions = $center.html();
            const villageOptions = $village.html();

            function refreshSelect2($select) {
                if ($select.hasClass('select2-hidden-accessible')) $select.trigger('change.select2');
            }

            function filterCenters(selectedCenterId) {
                const govId = $governorate.val();
                $center.html($(centerOptions).filter(function() { return !this.value || $(this).data('governorate-id') == govId; })).val(selectedCenterId);
                refreshSelect2($center);
            }

            function filterVillages(selectedVillageId) {
                const cId = $center.val();
                $village.html($(villageOptions).filter(function() { return !this.value || $(this).data('center-id') == cId; })).val(selectedVillageId);
                refreshSelect2($village);
            }

            $governorate.on('change', () => { filterCenters(''); filterVillages(''); });
            $center.on('change', () => { filterVillages(''); });

            // Initial Filter load
            filterCenters('{{ $user->center_id }}');
            filterVillages('{{ $user->village_id }}');

            // Financials
            function calculateFinancials() {
                let income = 0, expenses = 0;
                $('.income-input').each(function() { income += parseFloat($(this).val()) || 0; });
                $('.expense-input').each(function() { expenses += parseFloat($(this).val()) || 0; });
                $('#gross_income').val(income.toFixed(0));
                $('#gross_expenses').val(expenses.toFixed(0));
                $('#standard_living').val((income - expenses).toFixed(0));
            }
            $(document).on('input', '.income-input, .expense-input', calculateFinancials);
            calculateFinancials();

            // National ID Age
            function getAge(nid) {
                if (!nid || nid.length !== 14) return '';
                const year = (nid.charAt(0) === '2' ? 1900 : 2000) + parseInt(nid.substring(1, 3));
                return new Date().getFullYear() - year;
            }
            function getGender(nid) {
                if (!nid || nid.length !== 14) return '';
                const genderDigit = parseInt(nid.charAt(12), 10);
                if (Number.isNaN(genderDigit)) return '';
                return genderDigit % 2 === 0 ? '0' : '1';
            }
            $(document).on('input', '[name="husband_national_id"]', function() { $('[name="age_husband"]').val(getAge($(this).val())); });
            $(document).on('input', '[name="wife_national_id"]', function() { $('[name="age_wife"]').val(getAge($(this).val())); });
            $(document).on('input', '[name="children_national_id[]"]', function() {
                const nationalId = $(this).val();
                const $row = $(this).closest('.row');
                $row.find('[name="age[]"]').val(getAge(nationalId));
                const gender = getGender(nationalId);
                if (gender !== '') {
                    $row.find('[name="child_gender[]"]').val(gender);
                }
            });

            // Dynamic Rows
            $('#add').on('click', () => {
                $('#child_containerN').append(`
                    <div class="bg-light p-3 rounded mb-3 child-row border position-relative">
                        <button type="button" class="btn btn-danger btn-sm position-absolute" style="top: 10px; left: 10px; z-index: 10" onclick="$(this).closest('.child-row').remove()">
                            <i class="fe fe-trash"></i>
                        </button>
                        <div class="row align-items-end">
                            <div class="col-md-4">
                                <label class="form-label font-weight-bold small">اسم الابن</label>
                                <input type="text" class="form-control form-control-sm" name="child_names[]">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label font-weight-bold small">الرقم القومي</label>
                                <input type="number" class="form-control form-control-sm" name="children_national_id[]">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label font-weight-bold small">السن</label>
                                <input type="number" class="form-control form-control-sm bg-white" name="age[]" readonly>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label font-weight-bold small">النوع</label>
                                <select class="form-control form-control-sm no-select2" name="child_gender[]"><option value="1">ذكر</option><option value="0">أنثى</option></select>
                            </div>
                            <div class="col-md-4 mt-3"><label class="form-label font-weight-bold small">المدرسة</label><input type="text" class="form-control form-control-sm" name="schools[]"></div>
                            <div class="col-md-2 mt-3"><label class="form-label font-weight-bold small">التكلفة</label><input type="number" class="form-control form-control-sm" name="monthly_cost[]"></div>
                            <div class="col-md-6 mt-3"><label class="form-label font-weight-bold small">ملاحظات</label><input type="text" class="form-control form-control-sm" name="notes[]"></div>
                        </div>
                    </div>`);
            });

            $('#add_patient').on('click', () => {
                $('#patient_container').append(`
                    <div class="bg-light p-3 rounded mb-3 patient-row border border-danger-transparent position-relative">
                        <button type="button" class="btn btn-danger btn-sm position-absolute" style="top: 10px; left: 10px; z-index: 10" onclick="$(this).closest('.patient-row').remove()">
                            <i class="fe fe-trash"></i>
                        </button>
                        <div class="row align-items-end">
                            <div class="col-md-4"><label class="form-label font-weight-bold small">المريض</label><input type="text" class="form-control form-control-sm" name="patient_name[]"></div>
                            <div class="col-md-4"><label class="form-label font-weight-bold small">وسيلة الصرف</label><input type="text" class="form-control form-control-sm" name="treatment_pay_by[]"></div>
                            <div class="col-md-4"><label class="form-label font-weight-bold small">الطبيب</label><input type="text" class="form-control form-control-sm" name="doctor_name[]"></div>
                            <div class="col-md-8 mt-3"><label class="form-label font-weight-bold small">العلاج</label><input type="text" class="form-control form-control-sm" name="treatment[]"></div>
                            <div class="col-md-4 mt-3"><label class="form-label font-weight-bold small">النوع</label><select class="form-control form-control-sm no-select2" name="type[]"><option value="1">ذكر</option><option value="0">أنثى</option></select></div>
                        </div>
                    </div>`);
            });

            $('#has_monthly_subvention').on('change', function() {
                const isChecked = $(this).is(':checked');
                $('#monthly_subvention_amount').prop('readonly', !isChecked);
                if (!isChecked) $('#monthly_subvention_amount').val('');
            }).trigger('change');

            $('.dropify').dropify();
            $('.select2').select2({ width: '100%', minimumResultsForSearch: 10 });
            
            // Image viewer
            window.showImage = function(src) {
                $('#modalImage').attr('src', src);
                $('#imageModal').modal('show');
            };
        });
    </script>
@endsection
