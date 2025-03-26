@extends('Admin/layouts/master')
@section('title')
    {{ isset($setting->title) ? $setting->title : '' }} | اضافة مستفيد
@endsection
@section('page_name')
    مستفيد جديد
@endsection
@section('content')
    @if (count($errors) > 0)
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <ul class="p-0 m-0" style="list-style: none;">
                @foreach ($errors->all() as $error)
                    <li><i class="fa fa-times-circle"></i> {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form id="addForm" action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="mb-0 btn btn-success" style="pointer-events: none; user-select: none;">اضافة بيانات
                            مستفيد جديد</h2>
                    </div>

                    <div class="card-body">
                        <div class="row">

                            <div class="form-group col-md-6">
                                <label class="form-label">اسم الزوج</label>
                                <input type="text" value="{{old("husband_name")}}" class="form-control" name="husband_name" placeholder="">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label ">اسم الزوجة</label>
                                <input type="text" value="{{old("wife_name")}}" class="form-control" name="wife_name" placeholder="">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label "> الرقم القومى للزوج</label>
                                <input type="number" value="{{old("husband_national_id")}}" class="form-control" name="husband_national_id" placeholder="">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label "> الرقم القومى للزوجة</label>
                                <input type="number" value="{{old("wife_national_id")}}" class="form-control" name="wife_national_id" placeholder="">
                            </div>

                            <div class="form-group col-md-2">
                                <label class="form-label"> عمر الزوج</label>
                                <input type="number" value="{{old("age_husband")}}" class="form-control" name="age_husband" placeholder="" readonly>
                            </div>

                            <div class="form-group col-md-2">
                                <label class="form-label"> عمر الزوجة</label>
                                <input type="number" value="{{old("age_wife")}}" class="form-control" name="age_wife" placeholder="" readonly>
                            </div>

                            <div class="form-group col-md-8">
                                <label class="form-label"> العنوان </label>
                                <input type="text" value="{{old("address")}}" class="form-control" name="address" placeholder="">
                            </div>

                            <div class="form-group col-md-2">
                                <label class="form-label">الحالة الاجتماعية للاب</label>
                                <select name="social_status" class="form-control select2"
                                    data-placeholder="اختيار الحالة الاجتماعية">
                                    <option value="{{old("social_status")}}">{{old("social_status")}}</option>
                                    <option value="0">أعزب</option>
                                    <option value="1">متزوج</option>
                                    <option value="2">مطلق</option>
                                    <option value="3">متوفى</option>
                                </select>
                            </div>


                            <div class="form-group col-md-4">
                                <label class="form-label">نوع العمل</label>
                                <input type="text" value="{{old("work_type")}}" class="form-control" name="work_type" placeholder="">
                            </div>

                            <div class="form-group col-md-4">
                                <label class="form-label">أقرب تليفون</label>
                                <input value="{{old('nearest_phone')}}" type="nubmer" class="form-control"  value="{{old("nearest_phone")}}" name="nearest_phone" placeholder="">
                            </div>
                        </div>
                        <hr>
                        {{-- ______________________________________________________________________________________________________________________________ --}}

                        <div class="card-header">
                            <h2 class="mb-0 btn btn-success" style="pointer-events: none; user-select: none;">
                                إجمالى الدخل
                            </h2>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">مرتب</label>
                                <input value="{{old('salary')}}" type="number" class="form-control income-input"  value="{{old("salary")}}" name="salary" placeholder="">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">معاش</label>
                                <input type="number" class="form-control income-input"  value="{{old("pension")}}" name="pension" placeholder="">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">تأمين</label>
                                <input type="number" class="form-control income-input"  value="{{old("insurance")}}" name="insurance" placeholder="">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">كرامة</label>
                                <input type="number" class="form-control income-input"  value="{{old("dignity")}}" name="dignity" placeholder="">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">تجارة</label>
                                <input type="number" class="form-control income-input"  value="{{old("trade")}}" name="trade" placeholder="">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">سادات</label>
                                <input type="number" class="form-control income-input"  value="{{old("pillows")}}" name="pillows" placeholder="">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">غير ذلك</label>
                                <input type="number" class="form-control income-input"  value="{{old("other")}}" name="other" placeholder="">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">إجمالى الدخل</label>
                                <input type="number" class="form-control"  value="{{old("gross_income")}}" name="gross_income" id="gross_income"
                                    placeholder="" readonly>
                            </div>
                        </div>
                        {{-- ______________________________________________________________________________________________________________________________ --}}
                        <hr>
                        <div class="card-header">
                            <h2 class="mb-0 btn btn-success" style="pointer-events: none; user-select: none;">
                                اجمالى النفقات
                            </h2>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <label class="form-label">إيجار</label>
                                <input type="number" class="form-control expense-input"  value="{{old("rent")}}" name="rent" placeholder="">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">غاز</label>
                                <input type="number" class="form-control expense-input"  value="{{old("gas")}}" name="gas" placeholder="">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">ديون</label>
                                <input type="number" class="form-control expense-input"  value="{{old("debt")}}" name="debt" placeholder="">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">مياه</label>
                                <input type="number" class="form-control expense-input"  value="{{old("water")}}" name="water" placeholder="">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">كهرباء</label>
                                <input type="number" class="form-control expense-input"  value="{{old("electricity")}}" name="electricity"
                                    placeholder="">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">جمعية</label>
                                <input type="number" class="form-control expense-input"  value="{{old("association")}}" name="association"
                                    placeholder="">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">طعام</label>
                                <input type="number" class="form-control expense-input"  value="{{old("food")}}" name="food" placeholder="">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">دراسة</label>
                                <input type="number" class="form-control expense-input"  value="{{old("study")}}" name="study" placeholder="">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">إجمالى النفقات</label>
                                <input type="number" class="form-control"  value="{{old("gross_expenses")}}" name="gross_expenses" id="gross_expenses"
                                    placeholder="" readonly>
                            </div>
                        </div>

                        <hr>
                        {{-- ______________________________________________________________________________________________________________________________ --}}
                        <div>
                            <h2 class="mb-0 btn btn-success mb-3" style="pointer-events: none; user-select: none;">
                                مستوى المعيشة
                            </h2>
                        </div>
                        <input type="nubmer" class="form-control col-md-3 "  value="{{old("standard_living")}}" name="standard_living"
                            id="standard_living"readonly>
                        <hr>
                        {{-- ______________________________________________________________________________________________________________________________ --}}

                        <div class="d-flex justify-content-between mt-3">
                            <div>
                                <h2 class="mb-0 btn btn-success" style="pointer-events: none; user-select: none;">الاطفال
                                </h2>
                            </div>
                            <div class="d-flex justify-content-end mb-3">
                                <button type="button" class="btn btn-primary" id="add">
                                    <i class="fe fe-plus"></i> {{ ' اضافة طفل' }}
                                </button>
                            </div>
                        </div>
                        <hr>

                        {{-- ______________________________________________________________________________________________________________________________ --}}
                        <div id="child_container">
                            <div class="child-row row">
                                <div class="col-12">
                                    <h4 class="bg-danger text-white"
                                        style="width: max-content;
                                       padding: 8px 15px;
                                       border-radius: 5px;">
                                        الطفل
                                        <span id="child_number">1</span>
                                    </h4>
                                </div>

{{--                                @if(old('child_names'))--}}
{{--                                    @foreach(old('child_names') as $child_name)--}}
{{--                                        <input type="text" class="form-control" value="{{ $child_name }}" name="child_names[]">--}}
{{--                                    @endforeach--}}
{{--                                @else--}}
{{--                                    <input type="text" class="form-control" name="child_names[]">--}}
{{--                                @endif--}}





                                @if(old('child_names'))
                                    @foreach(old('child_names') as $index => $child_name)
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="child_names" class="form-control-label"> اسم الطفل </label>
                                                <input type="text" class="form-control" value="{{ $child_name }}" name="child_names[]">
                                            </div>
                                        </div>

                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="children_national_id" class="form-control-label"> الرقم القومي </label>
                                                <input type="number" class="form-control" value="{{ old('children_national_id')[$index] ?? '' }}" name="children_national_id[]">
                                            </div>
                                        </div>

                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="age" class="form-control-label"> السن </label>
                                                <input type="text" class="form-control" value="{{ old('age')[$index] ?? '' }}" name="age[]" readonly>
                                            </div>
                                        </div>

                                        <div class="col-2">
                                            <div class="form-group">
                                                <label for="schools" class="form-control-label"> المدرسة </label>
                                                <input type="text" class="form-control" value="{{ old('schools')[$index] ?? '' }}" name="schools[]">
                                            </div>
                                        </div>

                                        <div class="col-2">
                                            <div class="form-group">
                                                <label for="monthly_cost" class="form-control-label"> التكلفة الشهرية </label>
                                                <input type="text" class="form-control" value="{{ old('monthly_cost')[$index] ?? '' }}" name="monthly_cost[]">
                                            </div>
                                        </div>

                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="notes" class="form-control-label"> ملاحظات </label>
                                                <input type="text" class="form-control" value="{{ old('notes')[$index] ?? '' }}" name="notes[]">
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="child_names" class="form-control-label"> اسم الطفل </label>
                                            <input type="text" class="form-control" name="child_names[]">
                                        </div>
                                    </div>

                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="children_national_id" class="form-control-label"> الرقم القومي </label>
                                            <input type="number" class="form-control" name="children_national_id[]">
                                        </div>
                                    </div>

                                    <div class="col-3">
                                        <div class="form-group">
                                            <label for="age" class="form-control-label"> السن </label>
                                            <input type="text" class="form-control" name="age[]" readonly>
                                        </div>
                                    </div>

                                    <div class="col-2">
                                        <div class="form-group">
                                            <label for="schools" class="form-control-label"> المدرسة </label>
                                            <input type="text" class="form-control" name="schools[]">
                                        </div>
                                    </div>

                                    <div class="col-2">
                                        <div class="form-group">
                                            <label for="monthly_cost" class="form-control-label"> التكلفة الشهرية </label>
                                            <input type="text" class="form-control" name="monthly_cost[]">
                                        </div>
                                    </div>

                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="notes" class="form-control-label"> ملاحظات </label>
                                            <input type="text" class="form-control" name="notes[]">
                                        </div>
                                    </div>
                                @endif

                            </div>
                        {{-- ______________________________________________________________________________________________________________________________ --}}
                        <hr>

                        <div class="d-flex justify-content-between mt-3">
                            <div>
                                <h2 class="mb-0 btn btn-success" style="pointer-events: none; user-select: none;"> الحالة
                                    الصحية</h2>
                            </div>
                            <div class="d-flex justify-content-end mb-3">
                                <button type="button" class="btn btn-primary" id="add_patient">
                                    <i class="fe fe-plus"></i> {{ 'اضافة حالة ' }}
                                </button>
                            </div>
                        </div>
                        <hr>
                        <div class="col-12">
                            <h4 class="bg-danger text-white"
                                style="width: max-content;
                               padding: 8px 15px;
                               border-radius: 5px;">
                                الحالة
                                <span id="child_number">1</span>
                            </h4>

                        </div>

                            <div id="patient_container">
                                @if(old('patient_name'))
                                    @foreach(old('patient_name') as $index => $patient)
                                        <div class="patient-row row">
                                            <!-- Patient Name and Treatment Pay By -->
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">اسم المريض</label>
                                                    <input type="text" class="form-control" value="{{ $patient }}" name="patient_name[]" placeholder="">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">وسيلة صرف العلاج</label>
                                                    <input type="text" class="form-control" value="{{ old('treatment_pay_by')[$index] ?? '' }}" name="treatment_pay_by[]" placeholder="">
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label">الدواء</label>
                                                    <input type="text" class="form-control" value="{{ old('treatment')[$index] ?? '' }}" name="treatment[]" placeholder="">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <!-- Patient Type, Attending Doctor, and Insurance -->
                                            <div class="col-md-4 col-12">
                                                <div class="form-group">
                                                    <label class="form-label">نوع المريض</label>
                                                    <select class="form-control select2" name="type[]" data-placeholder="اختيار نوع المريض">
                                                        <option value="1" {{ old('type')[$index] == '1' ? 'selected' : '' }}>ذكر</option>
                                                        <option value="0" {{ old('type')[$index] == '0' ? 'selected' : '' }}>أنثي</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-4 col-12">
                                                <div class="form-group">
                                                    <label class="form-label">الطبيب المعالج</label>
                                                    <input type="text" class="form-control" value="{{ old('doctor_name')[$index] ?? '' }}" name="doctor_name[]" placeholder="">
                                                </div>
                                            </div>

                                            <div class="col-md-4 col-12">
                                                <div class="form-group">
                                                    <label class="form-label mb-4">هل له تأمين</label>
                                                    <div class="material-switch pull-left mb-5">
                                                        <input id="is_insurance_{{ $index }}" type="checkbox" name="is_insurance[]" value="1"
                                                            {{ is_array(old('is_insurance')) && isset(old('is_insurance')[$index]) && old('is_insurance')[$index] == '1' ? 'checked' : '' }}>
                                                        <label for="is_insurance_{{ $index }}" class="label-success"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                        </div>
                                    @endforeach
                                @else
                                    <!-- Default empty input for new form -->
                                    <div class="patient-row row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">اسم المريض</label>
                                                <input type="text" class="form-control" name="patient_name[]" placeholder="">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">وسيلة صرف العلاج</label>
                                                <input type="text" class="form-control" name="treatment_pay_by[]" placeholder="">
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">الدواء</label>
                                                <input type="text" class="form-control" name="treatment[]" placeholder="">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label class="form-label">نوع المريض</label>
                                                <select class="form-control select2" name="type[]" data-placeholder="اختيار نوع المريض">
                                                    <option value="1">ذكر</option>
                                                    <option value="0">أنثي</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label class="form-label">الطبيب المعالج</label>
                                                <input type="text" class="form-control" name="doctor_name[]" placeholder="">
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label class="form-label mb-4">هل له تأمين</label>
                                                <div class="material-switch pull-left mb-5">
                                                    <input id="is_insurance_new" type="checkbox" name="is_insurance[]" value="1">
                                                    <label for="is_insurance_new" class="label-success"></label>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                    </div>
                                @endif
                            </div>

                            <hr>

                        <div class="card-header">
                            <h2 class="mb-0 btn btn-success" style="pointer-events: none; user-select: none;">
                                ممتلكات المستفيد</h2>
                        </div>

                        <textarea rows="5" class="form-control"   name="Case_evaluation" id="Case_evaluation">{{old("Case_evaluation")}}</textarea>

                        <div class="card-header">
                            <h2 class="mb-0 btn btn-success" style="pointer-events: none; user-select: none;">
                                المرفقات </h2>
                        </div>

                    </div>
{{--                    <input type="file" class="dropify"  value=""{{old("attachments")}}" name="attachments[]"  accept="image/png, image/gif, image/jpeg,image/jpg"/>--}}
                        <input type="file" class="dropify" name="attachments[]" accept="image/png, image/gif, image/jpeg, image/jpg">

                    <div class="col-12 text-center">
                        <button class="btn btn-lg btn-outline-primary mt-2 mb-2">حفظ البيانات</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- COL END -->
    </form>
@endsection

@section('js')
    <script>
        $('.dropify').dropify()
    </script>

    <script>
        function calculateIncome() {
            let total = 0;
            document.querySelectorAll('.income-input').forEach(input => {
                let value = parseFloat(input.value) || 0;
                total += value;
                addAgeCalculationListener();
            });
            if (total % 1 === 0) {
                document.getElementById('gross_income').value = total;
            } else {
                document.getElementById('gross_income').value = total.toFixed(1);
            }
        }
        document.querySelectorAll('.income-input').forEach(input => {
            input.addEventListener('input', calculateIncome);
        });


        function calculateExpenses() {
            let total = 0;
            document.querySelectorAll('.expense-input').forEach(input => {
                let value = parseFloat(input.value) || 0;
                total += value;
            });

            document.getElementById('gross_expenses').value = (total % 1 === 0) ? total : total.toFixed(1);
        }

        document.querySelectorAll('.expense-input').forEach(input => {
            input.addEventListener('input', calculateExpenses);
        });

        function calculateStandardLiving() {
            let income = parseFloat(document.getElementById('gross_income').value);
            let expenses = parseFloat(document.getElementById('gross_expenses').value);

            let standardLiving = income - expenses;
            document.getElementById('standard_living').value = standardLiving;
        }

        document.querySelectorAll('.income-input').forEach(input => {
            input.addEventListener('input', () => {
                calculateIncome();
                calculateStandardLiving();
            });
        });

        document.querySelectorAll('.expense-input').forEach(input => {
            input.addEventListener('input', () => {
                calculateExpenses();
                calculateStandardLiving();
            });
        });


        // Event Listeners
        document.querySelectorAll('.income-input').forEach(input => {
            input.addEventListener('input', calculateIncome);
        });

        document.querySelectorAll('.expense-input').forEach(input => {
            input.addEventListener('input', calculateExpenses);
        });
    </script>




    <script>
        // دالة لحساب العمر بناءً على السنة فقط من الرقم القومي
        function calculateAge(nationalIdField, ageField) {
            const nationalId = document.querySelector(nationalIdField).value;
            const currentYear = new Date().getFullYear();

            if (nationalId.length === 14) {
                // استخراج السنة من الرقم القومي
                const yearPrefix = nationalId.charAt(0) === '2' ? 1900 : 2000;
                const year = yearPrefix + parseInt(nationalId.substring(1, 3)); // السنة فقط

                // حساب العمر بناءً على السنة
                let age = currentYear - year;

                // تعيين العمر في حقل العمر
                document.querySelector(ageField).value = age;
            }
        }

        // إضافة مستمعات الأحداث
        document.querySelector('[name="husband_national_id"]').addEventListener('input', function() {
            calculateAge('[name="husband_national_id"]', '[name="age_husband"]');
        });

        document.querySelector('[name="wife_national_id"]').addEventListener('input', function() {
            calculateAge('[name="wife_national_id"]', '[name="age_wife"]');
        });
    </script>

    <script>
        // دالة لحساب العمر بناءً على السنة فقط من الرقم القومي للأطفال
        function calculateAgeForChildren(nationalIdField, ageFieldName) {
            const nationalId = nationalIdField.value;
            const currentYear = new Date().getFullYear();

            if (nationalId.length === 14) {
                // استخراج السنة من الرقم القومي
                const yearPrefix = nationalId.charAt(0) === '2' ? 1900 : 2000;
                const year = yearPrefix + parseInt(nationalId.substring(1, 3)); // السنة فقط

                // حساب العمر بناءً على السنة
                let age = currentYear - year;

                // تعيين العمر في حقل العمر
                // نحصل على الحقل الذي يحمل نفس الاسم
                const ageField = document.querySelector(`[name="${ageFieldName}"]`);
                if (ageField) {
                    ageField.value = age; // تعيين العمر في الحقل
                }
            }
        }

        function onInputChange(event) {
            calculateAgeForChildren(event.target, 'age[]'); // حساب العمر بناءً على الرقم القومي
        }

        function addAgeCalculationListener() {
            document.querySelectorAll('[name="children_national_id[]"]').forEach(input => {
                input.removeEventListener('input', onInputChange); // Remove previous listeners to avoid duplicates
                input.addEventListener('input', onInputChange); // Add the listener to the new field
            });
        }

        // إضافة مستمعات الأحداث لكل حقل رقم قومي للأطفال
        document.querySelectorAll('[name="children_national_id[]"]').forEach(input => {
            input.addEventListener('input', function() {
                calculateAgeForChildren(this, 'age[]'); // حساب العمر بناءً على الرقم القومي
            });
        });

        // Call the function to add event listeners initially and after adding new children.
        addAgeCalculationListener();
    </script>




    <script>
        // ✅ حساب الدخل والمصروفات ومستوى المعيشة
        function calculateIncome() {
            let total = 0;
            document.querySelectorAll('.income-input').forEach(input => {
                let value = parseFloat(input.value) || 0;
                total += value;
            });
            document.getElementById('gross_income').value = total % 1 === 0 ? total : total.toFixed(1);
        }

        function calculateExpenses() {
            let total = 0;
            document.querySelectorAll('.expense-input').forEach(input => {
                let value = parseFloat(input.value) || 0;
                total += value;
            });
            document.getElementById('gross_expenses').value = total % 1 === 0 ? total : total.toFixed(1);
        }

        function calculateStandardLiving() {
            let income = parseFloat(document.getElementById('gross_income').value) || 0;
            let expenses = parseFloat(document.getElementById('gross_expenses').value) || 0;
            let standardLiving = income - expenses;
            document.getElementById('standard_living').value = standardLiving % 1 === 0 ? standardLiving : standardLiving
                .toFixed(1);
        }

        // ✅ تشغيل الأحداث عند الإدخال
        document.querySelectorAll('.income-input').forEach(input => {
            input.addEventListener('input', () => {
                calculateIncome();
                calculateStandardLiving();
            });
        });

        document.querySelectorAll('.expense-input').forEach(input => {
            input.addEventListener('input', () => {
                calculateExpenses();
                calculateStandardLiving();
            });
        });


        // ✅ إضافة الأطفال
        let childNumber = 1;
        $('#add').on('click', function() {
            childNumber++;

            let newRow = `
        <div class="child-row row">
            <div class="col-12">
                <h4 class="bg-danger text-white" style="width: max-content; padding: 8px 15px; border-radius: 5px;">
                    الطفل <span class="child_number">${childNumber}</span>
                </h4>
            </div>

            <div class="col-3">
                <div class="form-group">
                    <label for="name" class="form-control-label">الاسم</label>
                    <input type="text" class="form-control" name="child_names[]" >
                </div>
            </div>

            <div class="col-3">
                <div class="form-group">
                    <label for="children_national_id" class="form-control-label">الرقم القومي</label>
                    <input type="text" class="form-control" name="children_national_id[]" >
                </div>
            </div>

            <div class="col-3">
                <div class="form-group">
                    <label for="age" class="form-control-label">السن</label>
                    <input type="text" class="form-control" name="age[]" >
                </div>
            </div>

            <div class="col-2">
                <div class="form-group">
                    <label for="schools" class="form-control-label">المدرسة</label>
                    <input type="text" class="form-control" name="schools[]" >
                </div>
            </div>

            <div class="col-2">
                <div class="form-group">
                    <label for="monthly_cost" class="form-control-label">التكلفة الشهرية</label>
                    <input type="text" class="form-control" name="monthly_cost[]" >
                </div>
            </div>

            <div class="col-3">
                <div class="form-group">
                    <label for="notes" class="form-control-label">ملاحظات</label>
                    <input type="text" class="form-control" name="notes[]" >
                </div>
            </div>

            <div class="col-1">
                <button type="button" class="btn btn-danger mt-5 removeColor">
                    <i class="fe fe-trash"></i>
                </button>
            </div>
        </div>
    `;

            $('#child_container').append(newRow);
        });

        // ✅ حذف الطفل
        $('#child_container').on('click', '.removeColor', function() {
            $(this).closest('.child-row').remove();
            $('.child_number').each(function(index) {
                $(this).text(index + 1);
            });
        });

        addAgeCalculationListener();


        // ✅ إضافة الحالات المرضية
        let patientNumber = 1;
        $('#add_patient').on('click', function() {
            patientNumber++;

            let newRowPatient = `
        <hr>
        <div class="patient-row row">
            <div class="col-12">
                <h4 class="bg-danger text-white" style="width: max-content; padding: 8px 15px; border-radius: 5px;">
                    الحالة <span class="patient_number">${patientNumber}</span>
                </h4>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">اسم المريض</label>
                    <input type="text" class="form-control" name="patient_name[]" placeholder="">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">وسيلة صرف العلاج</label>
                    <input type="text" class="form-control" name="treatment_pay_by[]" placeholder="">
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label">الدواء</label>
                    <input type="text" class="form-control" name="treatment[]" placeholder="">
                </div>
            </div>

            <div class="col-md-4 col-12">
                <div class="form-group">
                    <label class="form-label">نوع المريض</label>
                    <select class="form-control select2" name="type[]" data-placeholder="اختيار نوع المريض">
                        <option value="1">ذكر</option>
                        <option value="0">أنثي</option>
                    </select>
                </div>
            </div>

            <div class="col-md-4 col-12">
                <div class="form-group">
                    <label class="form-label">الطبيب المعالج</label>
                    <input type="text" class="form-control" name="doctor_name[]" placeholder="">
                </div>
            </div>

            <div class="col-md-3 col-12">
                <div class="form-group">
                    <label class="form-label mb-4">هل له تأمين</label>
                    <div class="material-switch pull-left mb-5">
                        <input id="is_insurance${patientNumber}" name="is_insurance[]" type="checkbox">
                        <label for="is_insurance${patientNumber}" class="label-success"></label>
                    </div>
                </div>
            </div>

            <div class="col-md-1">
                <button type="button" class="btn btn-danger mt-5 removepatient">
                    <i class="fe fe-trash"></i>
                </button>
            </div>
        </div>
    `;

            $('#patient_container').append(newRowPatient);
        });

        // ✅ حذف الحالة المرضية
        $('#patient_container').on('click', '.removepatient', function() {
            $(this).closest('.patient-row').remove();
            $('.patient_number').each(function(index) {
                $(this).text(index + 1);
            });
        });
    </script>


@endsection
