@extends('admin/layouts/master')
@section('title')
    {{ isset($setting->title) ? $setting->title : '' }} | اضافة مستفيد
@endsection
@section('page_name')
    مستفيد جديد
@endsection
@section('content')

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif



@section('content')
    <form id="addForm" action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-8 col-md-12">
                <!-- Main Information Card -->
                <div class="card custom-card">
                    <div class="card-header border-bottom">
                        <h3 class="card-title text-primary"><i class="fe fe-user mr-2"></i>البيانات الأساسية للمستفيد</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label class="form-label font-weight-bold">كود المستفيد</label>
                                <input type="text" value="{{ old('beneficiary_code') }}" class="form-control rounded-pill" name="beneficiary_code" placeholder="أدخل كود المستفيد">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label font-weight-bold">اسم الزوج <span class="text-danger">*</span></label>
                                <input type="text" value="{{ old('husband_name') }}" class="form-control rounded-pill" name="husband_name" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label font-weight-bold">اسم الزوجة <span class="text-danger">*</span></label>
                                <input type="text" value="{{ old('wife_name') }}" class="form-control rounded-pill" name="wife_name" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label font-weight-bold">الرقم القومى للزوج</label>
                                <input type="number" value="{{ old('husband_national_id') }}" class="form-control rounded-pill" name="husband_national_id">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label font-weight-bold">الرقم القومى للزوجة</label>
                                <input type="number" value="{{ old('wife_national_id') }}" class="form-control rounded-pill" name="wife_national_id">
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-label">عمر الزوج</label>
                                <input type="number" value="{{ old('age_husband') }}" class="form-control bg-light" name="age_husband" readonly>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-label">عمر الزوجة</label>
                                <input type="number" value="{{ old('age_wife') }}" class="form-control bg-light" name="age_wife" readonly>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-label">الحالة الاجتماعية</label>
                                <select name="social_status" class="form-control select2">
                                    <option value="0" {{ old('social_status') == '0' ? 'selected' : '' }}>أعزب</option>
                                    <option value="1" {{ old('social_status') == '1' || !old('social_status') ? 'selected' : '' }}>متزوج</option>
                                    <option value="2" {{ old('social_status') == '2' ? 'selected' : '' }}>مطلق</option>
                                    <option value="3" {{ old('social_status') == '3' ? 'selected' : '' }}>أرمل</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label class="form-label">تصنيف المستفيد</label>
                                <select name="beneficiary_category_id" class="form-control select2">
                                    <option value="">اختر التصنيف</option>
                                    @foreach ($beneficiaryCategories as $category)
                                        <option value="{{ $category->id }}" {{ old('beneficiary_category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <label class="form-label">العنوان بالتفصيل</label>
                                <input type="text" value="{{ old('address') }}" class="form-control" name="address">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="form-label">المحافظة</label>
                                <select name="governorate_id" class="form-control select2">
                                    <option value="">اختر المحافظة</option>
                                    @foreach ($governorates as $governorate)
                                        <option value="{{ $governorate->id }}" {{ old('governorate_id') == $governorate->id ? 'selected' : '' }}>{{ $governorate->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="form-label">المركز</label>
                                <select name="center_id" class="form-control select2">
                                    <option value="">اختر المركز</option>
                                    @foreach ($centers as $center)
                                        <option value="{{ $center->id }}" data-governorate-id="{{ $center->governorate_id }}" {{ old('center_id') == $center->id ? 'selected' : '' }}>{{ $center->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="form-label">القرية</label>
                                <select name="village_id" class="form-control select2">
                                    <option value="">اختر القرية</option>
                                    @foreach ($villages as $village)
                                        <option value="{{ $village->id }}" data-center-id="{{ $village->center_id }}" {{ old('village_id') == $village->id ? 'selected' : '' }}>{{ $village->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label">نوع العمل</label>
                                <input type="text" value="{{ old('work_type') }}" class="form-control" name="work_type">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label">أقرب تليفون</label>
                                <input type="number" value="{{ old('nearest_phone') }}" class="form-control" name="nearest_phone">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Financial Data: Income & Expenses -->
                <div class="card custom-card">
                    <div class="card-header border-bottom">
                        <h3 class="card-title text-success"><i class="fe fe-dollar-sign mr-2"></i>البيانات المالية ومستوى المعيشة</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Income Section -->
                            <div class="col-md-6 border-left">
                                <p class="font-weight-bold text-muted mb-4 border-bottom pb-2">تفاصيل الدخل الشهري</p>
                                <div class="form-group row">
                                    <label class="col-md-4 form-label">مرتب</label>
                                    <div class="col-md-8"><input type="number" class="form-control income-input" name="salary" value="{{ old('salary', 0) }}"></div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 form-label">معاش</label>
                                    <div class="col-md-8"><input type="number" class="form-control income-input" name="pension" value="{{ old('pension', 0) }}"></div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 form-label">كرامة</label>
                                    <div class="col-md-8"><input type="number" class="form-control income-input" name="dignity" value="{{ old('dignity', 0) }}"></div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 form-label">إيراد آخر (1)</label>
                                    <div class="col-md-8"><input type="number" class="form-control income-input" name="trade" value="{{ old('trade', 0) }}"></div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 form-label">إيراد آخر (2)</label>
                                    <div class="col-md-8"><input type="number" class="form-control income-input" name="pillows" value="{{ old('pillows', 0) }}"></div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 form-label font-weight-bold text-primary">إجمالي الدخل</label>
                                    <div class="col-md-8"><input type="number" id="gross_income" name="gross_income" class="form-control bg-primary-transparent font-weight-bold border-primary" value="{{ old('gross_income', 0) }}" readonly></div>
                                </div>
                            </div>

                            <!-- Expenses Section -->
                            <div class="col-md-6">
                                <p class="font-weight-bold text-muted mb-4 border-bottom pb-2">تفاصيل المصروفات الشهرية</p>
                                <div class="form-group row">
                                    <label class="col-md-4 form-label">إيجار</label>
                                    <div class="col-md-8"><input type="number" class="form-control expense-input" name="rent" value="{{ old('rent', 0) }}"></div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 form-label">مرافق (غاز/مياه/كهرباء)</label>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <input type="number" class="form-control expense-input" name="gas" value="{{ old('gas', 0) }}" placeholder="غاز">
                                            <input type="number" class="form-control expense-input" name="water" value="{{ old('water', 0) }}" placeholder="مياه">
                                            <input type="number" class="form-control expense-input" name="electricity" value="{{ old('electricity', 0) }}" placeholder="كهرباء">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 form-label">طعام</label>
                                    <div class="col-md-8"><input type="number" class="form-control expense-input" name="food" value="{{ old('food', 0) }}"></div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 form-label">تعليم ومصاريف طبية</label>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <input type="number" class="form-control expense-input" name="study" value="{{ old('study', 0) }}" placeholder="تعليم">
                                            <input type="number" class="form-control expense-input" name="medical_expenses" value="{{ old('medical_expenses', 0) }}" placeholder="طبية">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 form-label">جمعيات وديون</label>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <input type="number" class="form-control expense-input" name="association" value="{{ old('association', 0) }}" placeholder="جمعية">
                                            <input type="number" class="form-control expense-input" name="debt" value="{{ old('debt', 0) }}" placeholder="ديون">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-4 form-label font-weight-bold text-danger">إجمالي المصاريف</label>
                                    <div class="col-md-8"><input type="number" id="gross_expenses" name="gross_expenses" class="form-control bg-danger-transparent font-weight-bold border-danger" value="{{ old('gross_expenses', 0) }}" readonly></div>
                                </div>
                            </div>
                        </div>

                        <!-- Standard of Living Summary -->
                        <div class="mt-4 bg-light p-4 rounded-lg d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0 font-weight-bold">مستوى المعيشة التقديري</h4>
                                <small class="text-muted">(إجمالي الدخل - إجمالي المصروفات)</small>
                            </div>
                            <div>
                                <input type="number" id="standard_living" name="standard_living" class="form-control form-control-lg text-center font-weight-bold bg-white" style="width: 200px; font-size: 24px;" value="{{ old('standard_living', 0) }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Children List -->
                <div class="card custom-card">
                    <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                        <h3 class="card-title text-info"><i class="fe fe-users mr-2"></i>الأبناء (أفراد الأسرة)</h3>
                        <button type="button" class="btn btn-info btn-pill btn-sm" id="add">
                            <i class="fe fe-plus mr-1"></i> إضافة ابن جديد
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="child_container">
                            <!-- Initial/Existing children rows -->
                            <div class="bg-light p-3 rounded mb-3 position-relative child-row border">
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
                                        <select class="form-control form-control-sm" name="child_gender[]">
                                            <option value="">اختر</option>
                                            <option value="1">ذكر</option>
                                            <option value="0">أنثى</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mt-3">
                                        <label class="form-label font-weight-bold small">المدرسة/الكلية</label>
                                        <input type="text" class="form-control form-control-sm" name="schools[]">
                                    </div>
                                    <div class="col-md-2 mt-3">
                                        <label class="form-label font-weight-bold small">التكلفة الشهرية</label>
                                        <input type="number" class="form-control form-control-sm" name="monthly_cost[]">
                                    </div>
                                    <div class="col-md-7 mt-3">
                                        <label class="form-label font-weight-bold small">ملاحظات إضافية</label>
                                        <input type="text" class="form-control form-control-sm" name="notes[]">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="child_containerN"></div>
                    </div>
                </div>

                <!-- Health Status -->
                <div class="card custom-card">
                    <div class="card-header border-bottom d-flex justify-content-between align-items-center">
                        <h3 class="card-title text-danger"><i class="fe fe-heart mr-2"></i>الحالات الصحية والعلاج</h3>
                        <button type="button" class="btn btn-danger btn-pill btn-sm" id="add_patient">
                            <i class="fe fe-plus mr-1"></i> إضافة حالة مرضية
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="patient_container">
                            <div class="bg-light p-3 rounded mb-3 position-relative patient-row border border-danger-transparent">
                                <div class="row align-items-end">
                                    <div class="col-md-4">
                                        <label class="form-label font-weight-bold small">اسم المريض</label>
                                        <input type="text" class="form-control form-control-sm" name="patient_name[]">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label font-weight-bold small">وسيلة صرف العلاج</label>
                                        <input type="text" class="form-control form-control-sm" name="treatment_pay_by[]">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label font-weight-bold small">الطبيب المعالج</label>
                                        <input type="text" class="form-control form-control-sm" name="doctor_name[]">
                                    </div>
                                    <div class="col-md-8 mt-3">
                                        <label class="form-label font-weight-bold small">تفاصيل العلاج (الأدوية)</label>
                                        <input type="text" class="form-control form-control-sm" name="treatment[]">
                                    </div>
                                    <div class="col-md-4 mt-3">
                                        <label class="form-label font-weight-bold small">نوع المريض</label>
                                        <select class="form-control form-control-sm" name="type[]">
                                            <option value="1">ذكر</option>
                                            <option value="0">أنثى</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar / Action Panel -->
            <div class="col-lg-4 col-md-12">
                <!-- Case Subvention Toggle -->
                <div class="card custom-card">
                    <div class="card-header border-bottom pb-2">
                        <h3 class="card-title"><i class="fe fe-info mr-2"></i>الإعانة والقرار</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label font-weight-bold mb-0">تفعيل إعانة شهرية</label>
                                <label class="custom-switch">
                                    <input type="checkbox" id="has_monthly_subvention" name="has_monthly_subvention" value="1" class="custom-switch-input" {{ old('has_monthly_subvention') ? 'checked' : '' }}>
                                    <span class="custom-switch-indicator custom-switch-indicator-lg custom-switch-indicator-success"></span>
                                </label>
                            </div>
                            <div class="mt-3">
                                <label class="form-label small">مبلغ الإعانة المقترح (ج.م)</label>
                                <input type="number" id="monthly_subvention_amount" name="monthly_subvention_amount" class="form-control form-control-lg font-weight-bold text-success border-success" value="{{ old('monthly_subvention_amount') }}" placeholder="0.00">
                            </div>
                        </div>

                        <hr class="border-top">

                        <div class="form-group mt-4">
                            <label class="form-label font-weight-bold">ممتلكات المتقدم وقرار اللجنة</label>
                            <textarea rows="6" class="form-control" name="Case_evaluation" id="Case_evaluation" placeholder="اكتب تقييم الحالة هنا...">{{ old('Case_evaluation') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Attachments -->
                <div class="card custom-card">
                    <div class="card-header border-bottom">
                        <h3 class="card-title font-weight-bold"><i class="fe fe-paperclip mr-2"></i>المرفقات والوثائق</h3>
                    </div>
                    <div class="card-body">
                        <input type="file" class="dropify" name="attachments[]" data-height="120" accept="image/*,.pdf" multiple>
                        <p class="text-muted small mt-2"><i class="fe fe-info mr-1"></i>يمكنك رفع الصور، البطاقات الشخصية، وتقارير طبية.</p>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="card custom-card shadow-sm border-primary">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary btn-block btn-lg btn-pill shadow-sm">
                            <i class="fe fe-save mr-2"></i> حفظ بيانات المستفيد
                        </button>
                        <a href="{{ route('users.index') }}" class="btn btn-light btn-block mt-3">إلغاء والعودة</a>
                    </div>
                </div>
            </div>
        </div>
    </form>


    <script>
        $(function () {
            // Governorate/Center/Village Filtering
            const $governorate = $('select[name="governorate_id"]');
            const $center = $('select[name="center_id"]');
            const $village = $('select[name="village_id"]');
            const centerOptions = $center.html();
            const villageOptions = $village.html();

            function refreshSelect2($select) {
                if ($select.hasClass('select2-hidden-accessible')) {
                    $select.trigger('change.select2');
                }
            }

            function filterCenters(selectedCenterId) {
                const governorateId = $governorate.val();
                const filteredCenters = $(centerOptions).filter(function () {
                    return !this.value || $(this).data('governorate-id') == governorateId;
                });
                $center.html(filteredCenters).val(selectedCenterId);
                refreshSelect2($center);
            }

            function filterVillages(selectedVillageId) {
                const centerId = $center.val();
                const filteredVillages = $(villageOptions).filter(function () {
                    return !this.value || $(this).data('center-id') == centerId;
                });
                $village.html(filteredVillages).val(selectedVillageId);
                refreshSelect2($village);
            }

            $governorate.on('change', function () { filterCenters(''); filterVillages(''); });
            $center.on('change', function () { filterVillages(''); });

            // Initial load
            filterCenters('{{ old('center_id') }}');
            filterVillages('{{ old('village_id') }}');

            // Financial Calculations
            function calculateFinancials() {
                let income = 0;
                let expenses = 0;

                $('.income-input').each(function() { income += parseFloat($(this).val()) || 0; });
                $('.expense-input').each(function() { expenses += parseFloat($(this).val()) || 0; });

                $('#gross_income').val(income.toFixed(0));
                $('#gross_expenses').val(expenses.toFixed(0));
                $('#standard_living').val((income - expenses).toFixed(0));
            }

            $(document).on('input', '.income-input, .expense-input', calculateFinancials);
            calculateFinancials();

            // National ID -> Age Logic
            function getAgeFromNationalId(nationalId) {
                if (!nationalId || nationalId.length !== 14) return '';
                const yearPrefix = nationalId.charAt(0) === '2' ? 1900 : 2000;
                const birthYear = yearPrefix + parseInt(nationalId.substring(1, 3));
                const currentYear = new Date().getFullYear();
                return currentYear - birthYear;
            }

            $(document).on('input', '[name="husband_national_id"]', function() {
                $('[name="age_husband"]').val(getAgeFromNationalId($(this).val()));
            });

            $(document).on('input', '[name="wife_national_id"]', function() {
                $('[name="age_wife"]').val(getAgeFromNationalId($(this).val()));
            });

            $(document).on('input', '[name="children_national_id[]"]', function() {
                const age = getAgeFromNationalId($(this).val());
                $(this).closest('.row').find('[name="age[]"]').val(age);
            });

            // Dynamic Rows: Children
            $('#add').on('click', function() {
                const newChild = `
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
                                <select class="form-control form-control-sm" name="child_gender[]">
                                    <option value="">اختر</option>
                                    <option value="1">ذكر</option>
                                    <option value="0">أنثى</option>
                                </select>
                            </div>
                            <div class="col-md-3 mt-3">
                                <label class="form-label font-weight-bold small">المدرسة/الكلية</label>
                                <input type="text" class="form-control form-control-sm" name="schools[]">
                            </div>
                            <div class="col-md-2 mt-3">
                                <label class="form-label font-weight-bold small">التكلفة الشهرية</label>
                                <input type="number" class="form-control form-control-sm" name="monthly_cost[]">
                            </div>
                            <div class="col-md-7 mt-3">
                                <label class="form-label font-weight-bold small">ملاحظات إضافية</label>
                                <input type="text" class="form-control form-control-sm" name="notes[]">
                            </div>
                        </div>
                    </div>`;
                $('#child_containerN').append(newChild);
            });

            // Dynamic Rows: Health Cases
            $('#add_patient').on('click', function() {
                const newPatient = `
                    <div class="bg-light p-3 rounded mb-3 patient-row border border-danger-transparent position-relative">
                        <button type="button" class="btn btn-danger btn-sm position-absolute" style="top: 10px; left: 10px; z-index: 10" onclick="$(this).closest('.patient-row').remove()">
                            <i class="fe fe-trash"></i>
                        </button>
                        <div class="row align-items-end">
                            <div class="col-md-4">
                                <label class="form-label font-weight-bold small">اسم المريض</label>
                                <input type="text" class="form-control form-control-sm" name="patient_name[]">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label font-weight-bold small">وسيلة صرف العلاج</label>
                                <input type="text" class="form-control form-control-sm" name="treatment_pay_by[]">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label font-weight-bold small">الطبيب المعالج</label>
                                <input type="text" class="form-control form-control-sm" name="doctor_name[]">
                            </div>
                            <div class="col-md-8 mt-3">
                                <label class="form-label font-weight-bold small">تفاصيل العلاج (الأدوية)</label>
                                <input type="text" class="form-control form-control-sm" name="treatment[]">
                            </div>
                            <div class="col-md-4 mt-3">
                                <label class="form-label font-weight-bold small">نوع المريض</label>
                                <select class="form-control form-control-sm" name="type[]">
                                    <option value="1">ذكر</option>
                                    <option value="0">أنثى</option>
                                </select>
                            </div>
                        </div>
                    </div>`;
                $('#patient_container').append(newPatient);
            });

            // Subvention Toggle Logic
            $('#has_monthly_subvention').on('change', function() {
                const isChecked = $(this).is(':checked');
                $('#monthly_subvention_amount').prop('readonly', !isChecked);
                if (!isChecked) $('#monthly_subvention_amount').val('');
            }).trigger('change');

            $('.dropify').dropify();
            $('.select2').select2({ width: '100%', minimumResultsForSearch: 10 });
        });
    </script>
@endsection
