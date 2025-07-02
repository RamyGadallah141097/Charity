@extends('Admin/layouts/master')
@section('title')
    {{ isset($setting->title) ? $setting->title : '' }} | تعديل مستفيد
@endsection
@section('page_name')
    تعديل مستفيد
@endsection
@section('content')
    <style>
        .gallery-image {
            cursor: pointer;
            transition: 0.3s;
            margin: 5px;
        }

        .gallery-image:hover {
            opacity: 0.5;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            padding-top: 50px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.9);
        }


        .modal-content {
            margin: auto;
            display: block;
            max-width: 80%;
            max-height: 80%;
            top: 10%;
            /*    // make image in semi center */
        }


        .close {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            transition: 0.3s;
        }

        .close:hover,
        .close:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }
    </style>





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
    <form id="editForm" action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="mb-0 btn btn-success" style="pointer-events: none; user-select: none;">تعديل بيانات
                            مستفيد</h2>
                    </div>

                    <div class="card-body">
                        <div class="row">

                            <div class="form-group col-md-6">
                                <label class="form-label">اسم الزوج</label>
                                <input type="text" value="{{ old('husband_name', $user->husband_name) }}"
                                    class="form-control" name="husband_name" placeholder="">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label ">اسم الزوجة</label>
                                <input type="text" value="{{ old('wife_name', $user->wife_name) }}" class="form-control"
                                    name="wife_name" placeholder="">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label "> الرقم القومى للزوج</label>
                                <input type="number" value="{{ old('husband_national_id', $user->husband_national_id) }}"
                                    class="form-control" name="husband_national_id" placeholder="">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label "> الرقم القومى للزوجة</label>
                                <input type="number" value="{{ old('wife_national_id', $user->wife_national_id) }}"
                                    class="form-control" name="wife_national_id" placeholder="">
                            </div>

                            <div class="form-group col-md-2">
                                <label class="form-label"> عمر الزوج</label>
                                <input type="number" value="{{ old('age_husband', $user->age_husband) }}"
                                    class="form-control" name="age_husband" placeholder="" readonly>
                            </div>

                            <div class="form-group col-md-2">
                                <label class="form-label"> عمر الزوجة</label>
                                <input type="number" value="{{ old('age_wife', $user->age_wife) }}" class="form-control"
                                    name="age_wife" placeholder="" readonly>
                            </div>

                            <div class="form-group col-md-8">
                                <label class="form-label"> العنوان </label>
                                <input type="text" value="{{ old('address', $user->address) }}" class="form-control"
                                    name="address" placeholder="">
                            </div>

                            <div class="form-group col-md-2">
                                <label class="form-label">الحالة الاجتماعية للاب</label>
                                <select name="social_status" class="form-control select2"
                                    data-placeholder="اختيار الحالة الاجتماعية">
                                    <option value="{{ old('social_status', $user->social_status) }}" selected>
                                        {{ old('social_status', $user->social_status) }}</option>
                                    <option value="0">أعزب</option>
                                    <option value="1">متزوج</option>
                                    <option value="2">مطلق</option>
                                    <option value="3">متوفى</option>
                                </select>
                            </div>


                            <div class="form-group col-md-4">
                                <label class="form-label">نوع العمل</label>
                                <input type="text" value="{{ old('work_type', $user->work_type) }}" class="form-control"
                                    name="work_type" placeholder="">
                            </div>

                            <div class="form-group col-md-4">
                                <label class="form-label">أقرب تليفون</label>
                                <input type="nubmer" class="form-control"
                                    value="{{ old('nearest_phone', $user->nearest_phone) }}" name="nearest_phone"
                                    placeholder="">
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
                                <input type="number" class="form-control income-input"
                                    value="{{ old('salary', $user->salary) }}" name="salary" placeholder="">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">معاش</label>
                                <input type="number" class="form-control income-input"
                                    value="{{ old('pension', $user->pension) }}" name="pension" placeholder="">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">تأمين</label>
                                <input type="number" class="form-control income-input"
                                    value="{{ old('insurance', $user->insurance) }}" name="insurance" placeholder="">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">كرامة</label>
                                <input type="number" class="form-control income-input"
                                    value="{{ old('dignity', $user->dignity) }}" name="dignity" placeholder="">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">تجارة</label>
                                <input type="number" class="form-control income-input"
                                    value="{{ old('trade', $user->trade) }}" name="trade" placeholder="">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">سادات</label>
                                <input type="number" class="form-control income-input"
                                    value="{{ old('pillows', $user->pillows) }}" name="pillows" placeholder="">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">غير ذلك</label>
                                <input type="number" class="form-control income-input"
                                    value="{{ old('other', $user->other) }}" name="other" placeholder="">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">إجمالى الدخل</label>
                                <input type="number" class="form-control"
                                    value="{{ old('gross_income', $user->gross_income) }}" name="gross_income"
                                    id="gross_income" placeholder="" readonly>
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
                                <input type="number" class="form-control expense-input"
                                    value="{{ old('rent', $user->rent) }}" name="rent" placeholder="">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">غاز</label>
                                <input type="number" class="form-control expense-input"
                                    value="{{ old('gas', $user->gas) }}" name="gas" placeholder="">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">ديون</label>
                                <input type="number" class="form-control expense-input"
                                    value="{{ old('debt', $user->debt) }}" name="debt" placeholder="">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">مياه</label>
                                <input type="number" class="form-control expense-input"
                                    value="{{ old('water', $user->water) }}" name="water" placeholder="">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">كهرباء</label>
                                <input type="number" class="form-control expense-input"
                                    value="{{ old('electricity', $user->electricity) }}" name="electricity"
                                    placeholder="">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">جمعية</label>
                                <input type="number" class="form-control expense-input"
                                    value="{{ old('association', $user->association) }}" name="association"
                                    placeholder="">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">طعام</label>
                                <input type="number" class="form-control expense-input"
                                    value="{{ old('food', $user->food) }}" name="food" placeholder="">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">دراسة</label>
                                <input type="number" class="form-control expense-input"
                                    value="{{ old('study', $user->study) }}" name="study" placeholder="">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">إجمالى النفقات</label>
                                <input type="number" class="form-control"
                                    value="{{ old('gross_expenses', $user->gross_expenses) }}" name="gross_expenses"
                                    id="gross_expenses" placeholder="" readonly>
                            </div>
                        </div>

                        <hr>
                        {{-- ______________________________________________________________________________________________________________________________ --}}
                        <div>
                            <h2 class="mb-0 btn btn-success mb-3" style="pointer-events: none; user-select: none;">
                                مستوى المعيشة
                            </h2>
                        </div>
                        <input type="nubmer" class="form-control col-md-3 "
                            value="{{ old('standard_living', $user->standard_living) }}" name="standard_living"
                            id="standard_living"readonly>
                        <hr>
                        {{-- ______________________________________________________________________________________________________________________________ --}}

                        <div class="d-flex justify-content-between mt-3">
                            <div>
                                <h2 class="mb-0 btn btn-success" style="pointer-events: none; user-select: none;">الابناء
                                </h2>
                            </div>
                            <div class="d-flex justify-content-end mb-3">
                                <i class="fe fe-plus"></i> {{ 'اضافة ابن' }} </button>
                            </div>
                        </div>
                        <hr>

                        {{-- ______________________________________________________________________________________________________________________________ --}}
                        <div id="child_container">
                            @foreach ($user->childrens as $index => $child)
                                <div class="child-row row">
                                    <div class="col-12">
                                        <h4 class="bg-danger text-white" style="width: max-content; padding: 8px 15px;">
                                            الابن <span class="child_number">{{ $index + 1 }}</span>
                                        </h4>
                                    </div>

                                    <div class="col-3">
                                        <label for="child_names" class="form-control-label"> اسم الابن </label> <input
                                            type="text" class="form-control"
                                            value="{{ old('child_names.' . $index, $child->child_name) }}"
                                            name="child_names[]">
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="children_national_id" class="form-control-label"> الرقم القومي
                                        </label>
                                        <input type="number" class="form-control"
                                            value="{{ old('children_national_id.' . $index, $child->children_national_id) }}"
                                            name="children_national_id[]">
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="age" class="form-control-label"> السن </label>
                                        <input type="text" class="form-control"
                                            value="{{ old('age.' . $index, $child->age) }}" name="age[]" readonly>
                                    </div>
                                </div>

                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="schools" class="form-control-label"> المدرسة </label>
                                        <input type="text" class="form-control"
                                            value="{{ old('schools.' . $index, $child->school) }}" name="schools[]">
                                    </div>
                                </div>

                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="monthly_cost" class="form-control-label"> التكلفة الشهرية </label>
                                        <input type="text" class="form-control"
                                            value="{{ old('monthly_cost.' . $index, $child->monthly_cost) }}"
                                            name="monthly_cost[]">
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="notes" class="form-control-label"> ملاحظات </label>
                                        <input type="text" class="form-control"
                                            value="{{ old('notes.' . $index, $child->notes) }}" name="notes[]">
                                    </div>
                                </div>

                                @if ($index > 0)
                                    <div class="col-1">
                                        <button type="button" class="btn btn-danger mt-5 removeColor">
                                            <i class="fe fe-trash"></i>
                                        </button>
                                    </div>
                                @endif
                        </div>
                        @endforeach
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

                    <div id="patient_container">
                        @foreach ($patients as $index => $patient)
                            <div class="patient-row row">
                                <div class="col-12">
                                    <h4 class="bg-danger text-white"
                                        style="width: max-content;
                                       padding: 8px 15px;
                                       border-radius: 5px;">
                                        الحالة
                                        <span class="patient_number">{{ $index + 1 }}</span>
                                    </h4>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">اسم المريض</label>
                                        <input type="text" class="form-control"
                                            value="{{ old('patient_name.' . $index, $patient->patient_name) }}"
                                            name="patient_name[]" placeholder="">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">وسيلة صرف العلاج</label>
                                        <input type="text" class="form-control"
                                            value="{{ old('treatment_pay_by.' . $index, $patient->treatment_pay_by) }}"
                                            name="treatment_pay_by[]" placeholder="">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="form-label">الدواء</label>
                                        <input type="text" class="form-control"
                                            value="{{ old('treatment.' . $index, $patient->treatment) }}"
                                            name="treatment[]" placeholder="">
                                    </div>
                                </div>

                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label class="form-label">نوع المريض</label>
                                        <select class="form-control select2" name="type[]"
                                            data-placeholder="اختيار نوع المريض">
                                            <option value="1"
                                                {{ old('type.' . $index, $patient->type) == '1' ? 'selected' : '' }}>ذكر
                                            </option>
                                            <option value="0"
                                                {{ old('type.' . $index, $patient->type) == '0' ? 'selected' : '' }}>أنثي
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4 col-12">
                                    <div class="form-group">
                                        <label class="form-label">الطبيب المعالج</label>
                                        <input type="text" class="form-control"
                                            value="{{ old('doctor_name.' . $index, $patient->doctor_name) }}"
                                            name="doctor_name[]" placeholder="">
                                    </div>
                                </div>

                                <div class="col-md-3 col-12">
                                    <div class="form-group">
                                        <label class="form-label mb-4">هل له تأمين</label>
                                        <div class="material-switch pull-left mb-5">
                                            <input id="is_insurance{{ $index }}" name="is_insurance[]"
                                                type="checkbox" value="1"
                                                {{ old('is_insurance.' . $index, $patient->is_insurance) == '1' ? 'checked' : '' }}>
                                            <label for="is_insurance{{ $index }}" class="label-success"></label>
                                        </div>
                                    </div>
                                </div>

                                @if ($index > 0)
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-danger mt-5 removepatient">
                                            <i class="fe fe-trash"></i>
                                        </button>
                                    </div>
                                @endif
                                <hr>
                            </div>
                        @endforeach
                    </div>

                    <hr>

                    <div class="card-header">
                        <h2 class="mb-0 btn btn-success" style="pointer-events: none; user-select: none;">
                            ممتلكات المتقدم</h2>

                    </div>

                    <textarea rows="5" class="form-control" name="Case_evaluation" id="Case_evaluation">{{ old('Case_evaluation', $user->Case_evaluation) }}</textarea>

                    <div class="card-header">
                        <h2 class="mb-0 btn btn-success" style="pointer-events: none; user-select: none;">
                            المرفقات </h2>
                    </div>





                    <div class="col-12">


                        @if ($user->attachments)
                            @php
                                $attachments = is_string($user->attachments)
                                    ? json_decode($user->attachments, true)
                                    : $user->attachments;

                            @endphp

                            <div class="image-gallery">
                                @foreach ($attachments as $attachment)
                                    <img src="{{ asset('storage/' . $attachment) }}" alt="Attachment"
                                        style="max-width: 500px" height="150" class="gallery-image"
                                        onclick="openModal('{{ asset('storage/' . $attachment) }}')">
                                @endforeach
                            </div>

                            <div id="imageModal" class="modal">
                                <span class="close" onclick="closeModal()">&times;</span>
                                <img class="modal-content" style="width: 1500px" id="modalImage">
                            </div>
                        @endif

                        <div id="imageModal" class="modal">
                            <span class="close" onclick="closeModal()">&times;</span>
                            <img class="modal-content" style="width: 1500px" id="modalImage">
                        </div>



                    </div>
                    <input type="file" class="dropify" name="attachments[]"
                        accept="image/png, image/gif, image/jpeg, image/jpg" multiple>

                </div>

                <div class="col-12 text-center">
                    <button class="btn btn-lg btn-outline-primary mt-2 mb-2">تحديث البيانات</button>
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
        // Calculate income, expenses and standard living
        function calculateIncome() {
            let total = 0;
            document.querySelectorAll('.income-input').forEach(input => {
                let value = parseFloat(input.value) || 0;
                total += value;
            });
            document.getElementById('gross_income').value = total % 1 === 0 ? total : total.toFixed(1);
            calculateStandardLiving();
        }

        function calculateExpenses() {
            let total = 0;
            document.querySelectorAll('.expense-input').forEach(input => {
                let value = parseFloat(input.value) || 0;
                total += value;
            });
            document.getElementById('gross_expenses').value = total % 1 === 0 ? total : total.toFixed(1);
            calculateStandardLiving();
        }

        function calculateStandardLiving() {
            let income = parseFloat(document.getElementById('gross_income').value) || 0;
            let expenses = parseFloat(document.getElementById('gross_expenses').value) || 0;
            let standardLiving = income - expenses;
            document.getElementById('standard_living').value = standardLiving % 1 === 0 ? standardLiving : standardLiving
                .toFixed(1);
        }

        // Set up event listeners for income and expenses
        document.querySelectorAll('.income-input').forEach(input => {
            input.addEventListener('input', calculateIncome);
        });

        document.querySelectorAll('.expense-input').forEach(input => {
            input.addEventListener('input', calculateExpenses);
        });

        // Initialize calculations on page load
        document.addEventListener('DOMContentLoaded', function() {
            calculateIncome();
            calculateExpenses();
            calculateStandardLiving();
        });
    </script>

    <script>
        // Calculate age from national ID
        function calculateAge(nationalIdField, ageField) {
            const nationalId = document.querySelector(nationalIdField).value;
            const currentYear = new Date().getFullYear();

            if (nationalId.length === 14) {
                const yearPrefix = nationalId.charAt(0) === '2' ? 1900 : 2000;
                const year = yearPrefix + parseInt(nationalId.substring(1, 3));
                let age = currentYear - year;
                document.querySelector(ageField).value = age;
            }
        }

        // Set up event listeners for husband and wife national IDs
        document.querySelector('[name="husband_national_id"]')?.addEventListener('input', function() {
            calculateAge('[name="husband_national_id"]', '[name="age_husband"]');
        });

        document.querySelector('[name="wife_national_id"]')?.addEventListener('input', function() {
            calculateAge('[name="wife_national_id"]', '[name="age_wife"]');
        });
    </script>

    <script>
        // Function to calculate age from national ID
        function calculateAgeFromNationalId(nationalId) {
            if (!nationalId || nationalId.length !== 14) return null;

            const currentDate = new Date();
            const currentYear = currentDate.getFullYear();
            const currentMonth = currentDate.getMonth() + 1;

            const yearPrefix = nationalId.charAt(0) === '2' ? 1900 : 2000;
            const birthYear = yearPrefix + parseInt(nationalId.substring(1, 3), 10);
            const birthMonth = parseInt(nationalId.substring(3, 5), 10);

            let ageYears = currentYear - birthYear;
            let ageMonths = currentMonth - birthMonth;

            if (ageMonths < 0) {
                ageYears -= 1;
                ageMonths += 12;
            }

            return ageYears;
        }

        // Function to handle national ID input changes
        function handleNationalIdInput(event) {
            const nationalIdInput = event.target;
            const row = nationalIdInput.closest('.child-row');
            if (!row) return;

            const ageInput = row.querySelector('[name="age[]"]');
            if (ageInput) {
                const age = calculateAgeFromNationalId(nationalIdInput.value);
                ageInput.value = age || '';
            }
        }

        // Add event delegation for dynamically added children
        document.getElementById('child_container').addEventListener('input', function(e) {
            if (e.target.matches('[name="children_national_id[]"]')) {
                handleNationalIdInput(e);
            }
        });

        // Initialize existing inputs on page load
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('[name="children_national_id[]"]').forEach(input => {
                input.addEventListener('input', handleNationalIdInput);
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function calculateAgeFromNationalId(nationalId) {
                if (nationalId.length !== 14) return '';

                const currentDate = new Date();
                const currentYear = currentDate.getFullYear();
                const currentMonth = currentDate.getMonth() + 1;

                const yearPrefix = nationalId.charAt(0) === '2' ? 1900 : 2000;
                const birthYear = yearPrefix + parseInt(nationalId.substring(1, 3), 10);
                const birthMonth = parseInt(nationalId.substring(3, 5), 10);

                let ageYears = currentYear - birthYear;
                let ageMonths = currentMonth - birthMonth;

                if (ageMonths < 0) {
                    ageYears -= 1;
                    ageMonths += 12;
                }

                return ageYears;
            }

            function updateAgeField(event) {
                const nationalIdField = event.target;
                const ageField = nationalIdField.closest('tr')?.querySelector('[name="age[]"]');

                if (ageField) {
                    ageField.value = calculateAgeFromNationalId(nationalIdField.value);
                }
            }

            function addEventListeners() {
                document.querySelectorAll('[name="children_national_id[]"]').forEach(input => {
                    input.removeEventListener('input', updateAgeField);
                    input.addEventListener('input', updateAgeField);
                });
            }

            addEventListeners();
        });
    </script>

    <script>
        // Children management
        let childNumber = {{ count($user->childrens ?? []) }};
        $('#add').on('click', function() {
            childNumber++;

            let newRow = `
                <div class="child-row row">
                    <div class="col-12">
                        الابن <span class="child_number">${childNumber}</span> </h4>
                    </div>

                    <div class="col-3">
                        <div class="form-group">
                            <label for="name" class="form-control-label">الاسم</label>
                            <input type="text" class="form-control" name="child_names[]">
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="form-group">
                            <label for="children_national_id" class="form-control-label">الرقم القومي</label>
                            <input type="text" class="form-control"  name="children_national_id[]">
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="form-group">
                            <label for="age" class="form-control-label">السن</label>
                            <input type="text" class="form-control"  name="age[]">
                        </div>
                    </div>

                    <div class="col-2">
                        <div class="form-group">
                            <label for="schools" class="form-control-label">المدرسة</label>
                            <input type="text" class="form-control" name="schools[]">
                        </div>
                    </div>

                    <div class="col-2">
                        <div class="form-group">
                            <label for="monthly_cost" class="form-control-label">التكلفة الشهرية</label>
                            <input type="text" class="form-control" name="monthly_cost[]">
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="form-group">
                            <label for="notes" class="form-control-label">ملاحظات</label>
                            <input type="text" class="form-control" name="notes[]">
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
            addAgeCalculationListener();
        });

        // Remove child
        $('#child_container').on('click', '.removeColor', function() {
            $(this).closest('.child-row').remove();
            $('.child_number').each(function(index) {
                $(this).text(index + 1);
            });
        });

        // Patient management
        let patientNumber = {{ count($patients ?? []) }};
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
                                <input id="is_insurance${patientNumber}" name="is_insurance[]" type="checkbox" value="1">
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

        // Remove patient
        $('#patient_container').on('click', '.removepatient', function() {
            $(this).closest('.patient-row').remove();
            $('.patient_number').each(function(index) {
                $(this).text(index + 1);
            });
        });

        // Delete attachment (commented out as per your original code)
        $('.delete-attachment').on('click', function() {
            const attachmentId = $(this).data('id');
            const button = $(this);
            // Your AJAX code here if needed
        });
    </script>




    <script>
        function openModal(imageSrc) {
            document.getElementById('modalImage').src = imageSrc;
            document.getElementById('imageModal').style.display = "block";
        }

        function closeModal() {
            document.getElementById('imageModal').style.display = "none";
        }


        window.onclick = function(event) {
            const modal = document.getElementById('imageModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
@endsection
