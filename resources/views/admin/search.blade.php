@extends('Admin/layouts/master')
@section('content')
    @if($user != null)
        <!-- User details section (keep your existing user display code) -->
        <div class="card bg-white p-3 shadow-sm">


                <div class="card bg-white p-3 shadow-sm">


                    <style>
                        .gallery-image {
                            cursor: pointer;
                            transition: 0.3s;
                            margin: 5px;
                        }

                        .gallery-image:hover {
                            opacity: 0.7;
                        }

                        .modal {
                            display: none;
                            position: fixed;
                            z-index: 1000;
                            padding-top: 100px;
                            left: 0;
                            top: 0;
                            width: 100%;
                            height: 100%;
                            overflow: auto;
                            background-color: rgba(0,0,0,0.9);
                        }


                        .modal-content {
                            margin: auto;
                            display: block;
                            max-width: 100%;
                            max-height: 100%;
                            top: 20%;
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
                    <h3>المستفيد</h3>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered w-100">
                            <thead>
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">بيانات المستفيد</h3>
                                </div>
                                <div class="card-body">
                                    <h3>المستفيد</h3>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-bordered w-100">
                                            <thead>
                                            <tr class="fw-bolder text-muted bg-light">
                                                <th class="min-w-25px">اسم الزوج</th>
                                                <th class="min-w-25px">اسم الزوجة</th>
                                                <th class="min-w-25px">الرقم القومى للزوج </th>
                                                <th class="min-w-25px"> الرقم القومى للزوجة</th>
                                                {{--                                        <th class="min-w-25px"> تاريخ ميلاد الزوج</th>--}}
                                                {{--                                        <th class="min-w-25px"> تاريخ ميلاد الزوجة</th>--}}
                                                <th class="min-w-25px">عمر الزوج</th>
                                                <th class="min-w-25px">عمر الزوجة</th>
                                                <th class="min-w-25px">الحالة الاجتماعية</th>
                                                <th class="min-w-25px">الهاتف</th>
                                                <th class="min-w-25px">نوع العمل</th>
                                                <th class="min-w-25px"> العنوان</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                            </thead>
                            <tbody>
                            <tr>
                                <td>{{ $user->husband_name }}</td>
                                <td>{{ $user->wife_name }}</td>
                                <td>{{ $user->husband_national_id }}</td>
                                <td>{{ $user->wife_national_id }}</td>
                                {{--                    <td>{{ $user->husband_birthday }}</td>--}}
                                {{--                    <td>{{ $user->wife_birthday }}</td>--}}
                                <td>{{ $user->age_husband }}</td>
                                <td>{{ $user->age_wife }}</td>
                                <td>{{ $user->social_status == 0 ? "اعزب" : ($user->social_status == 1 ? "متزوج" : ($user->social_status == 2 ? "مطلق" : "ارمل")) }}</td>
                                <td>{{ $user->nearest_phone }}</td>
                                <td>{{ $user->work_type }}</td>
                                <td>{{ $user->address }}</td>

                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <h3> تفاصيل الدخل</h3>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered w-100">
                            <thead>
                            <tr class="fw-bolder text-muted bg-light">
                                <th class="min-w-25px">الراتب </th>
                                <th class="min-w-25px">معاش </th>
                                <th class="min-w-25px">تامين </th>
                                <th class="min-w-25px">كرامة </th>
                                <th class="min-w-25px">تجارة </th>
                                <th class="min-w-25px">الوسائد </th>
                                <th class="min-w-25px">اخرى </th>
                                <th class="min-w-25px">اجمالى الدخل </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>{{ $user->salary }}</td>
                                <td>{{ $user->pension }}</td>
                                <td>{{ $user->insurance }}</td>
                                <td>{{ $user->dignity }}</td>
                                <td>{{ $user->trade }}</td>
                                <td>{{ $user->pillows }}</td>
                                <td>{{ $user->other }}</td>
                                <td>{{ $user->gross_income }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <h3> تفاصيل النفقات</h3>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered w-100">
                            <thead>
                            <tr class="fw-bolder text-muted bg-light">
                                <th class="min-w-25px">ايجار </th>
                                <th class="min-w-25px">غاز </th>
                                <th class="min-w-25px">دين </th>
                                <th class="min-w-25px">مياه </th>
                                <th class="min-w-25px">علاج </th>
                                <th class="min-w-25px">كهرباء </th>
                                <th class="min-w-25px">منظمة </th>
                                <th class="min-w-25px">طعام </th>
                                <th class="min-w-25px">دراسة</th>
                                <th class="min-w-25px">اجمالى النفقات </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>{{ $user->rent }}</td>
                                <td>{{ $user->gas }}</td>
                                <td>{{ $user->debt }}</td>
                                <td>{{ $user->water }}</td>
                                <td>{{ $user->treatment }}</td>
                                <td>{{ $user->electricity }}</td>
                                <td>{{ $user->association }}</td>
                                <td>{{ $user->food }}</td>
                                <td>{{ $user->study }}</td>
                                <td>{{ $user->gross_expenses }}</td>

                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <h3> مستوى المعيشة</h3>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered w-100">
                            <thead>
                            <tr class="fw-bolder text-muted bg-light">
                                <th class="min-w-25px">مستوى المعيشة </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>{{ $user->standard_living }}</td>
                            </tr>
                            </tbody>
                        </table>


                        @if($user->childrens)
                            <h3>الابناء </h3>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered w-100">
                                    <thead>
                                    <tr class="fw-bolder text-muted bg-light">
                                    <tr class="fw-bolder text-muted bg-light">
                                        <th class="min-w-25px"> #</th>
                                        <th class="min-w-25px"> اسم الطفل</th>
                                        <th class="min-w-25px">الرقم القومى للطفل</th>
                                        <th class="min-w-25px">العمر </th>
                                        <th class="min-w-25px">المدرسة</th>
                                        <th class="min-w-25px"> التكلفة الشهرية</th>
                                        <th class="min-w-25px">ملاحظات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($user->childrens as $boy)
                                        <tr>
                                            <td>{{ $boy->id }}</td>
                                            <td>{{ $boy->child_name }}</td>
                                            <td>{{ $boy->children_national_id }}</td>
                                            <td>{{ $boy->age }}</td>
                                            <td>{{ $boy->school }}</td>
                                            <td>{{ $boy->monthly_cost }}</td>
                                            <td>{{ $boy->notes }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

{{--                        @if($patients)--}}
{{--                            <h3>الحالة الصحية</h3>--}}
{{--                            <div class="table-responsive">--}}
{{--                                <!--begin::Table-->--}}
{{--                                <table class="table table-striped table-bordered w-100">--}}
{{--                                    <thead>--}}
{{--                                    <tr class="fw-bolder text-muted bg-light">--}}
{{--                                        <th class="min-w-25px"> #</th>--}}
{{--                                        <th class="min-w-25px">اسم المريض</th>--}}
{{--                                        <th class="min-w-20px">الطبيب المعالج</th>--}}
{{--                                        <th class="min-w-20px">نوع المريض</th>--}}
{{--                                        <th class="min-w-20px">وسيلة صرف الدواء</th>--}}
{{--                                        <th class="min-w-20px">هل تأمين ؟</th>--}}
{{--                                        <th class="min-w-20px">الدواء</th>--}}
{{--                                    </tr>--}}
{{--                                    </thead>--}}
{{--                                    <tbody>--}}
{{--                                    @foreach ($patients as $patient)--}}
{{--                                        <tr>--}}
{{--                                            <td>{{ $patient->id }}</td>--}}
{{--                                            <td>{{ $patient->patient_name }}</td>--}}
{{--                                            <td>{{ $patient->doctor_name }} </td>--}}
{{--                                            <td>{{ $patient->type == 0 ? 'انثى' : 'ذكر' }}</td>--}}
{{--                                            <td>{{ $patient->treatment_pay_by }}</td>--}}
{{--                                            <td>{{ $patient->is_insurance == 0 ? 'لا' : 'نعم' }}</td>--}}
{{--                                            <td>{{ $patient->treatment }}</td>--}}
{{--                                            <td>{{ Str::limit($patient->treatment, 40) }}</td>--}}
{{--                                        </tr>--}}
{{--                                    @endforeach--}}
{{--                                    </tbody>--}}
{{--                                </table>--}}
{{--                            </div>--}}
{{--                        @endif--}}

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
                        </div>

                        @if ($user->attachments)
                            @php
                                $attachments = is_string($user->attachments) ? json_decode($user->attachments, true) : $user->attachments;

                            @endphp

                            <div class="image-gallery">
                                @foreach ($attachments as $attachment)
                                    <img src="{{ asset('storage/' . $attachment) }}"
                                         alt="Attachment"
                                         style="max-width: 500px"
                                         height="150"
                                         class="gallery-image"
                                         onclick="openModal('{{ asset('storage/' . $attachment) }}')">
                                @endforeach
                            </div>

                            <div id="imageModal" class="modal">
                                <span class="close" onclick="closeModal()">&times;</span>
                                <img class="modal-content" style="width: 1500px" id="modalImage">
                            </div>
                        @endif


                        @if ($user->childrens)
                            <h3>الاولاد</h3>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered w-100">
                                    <thead>
                                    <tr class="fw-bolder text-muted bg-light">
                                        <th class="min-w-25px">م</th>
                                        <th class="min-w-25px">الاسم</th>
                                        <th class="min-w-25px">مدرسة/جامعة</th>
                                        <th class="min-w-25px">تكلفة الدروس</th>
                                        <th class="min-w-25px">سنة دراسية</th>
                                        <th class="min-w-25px">التكلفة الشهرية</th>
                                        <th class="min-w-25px">ملاحظات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($user->childrens as $boy)
                                        <tr>
                                            <td>{{ $boy->id }}</td>
                                            <td>{{ $boy->name }}</td>
                                            <td>{{ $boy->school }}</td>
                                            <td>{{ $boy->lessons_cost }}</td>
                                            <td>{{ $boy->academic_year }}</td>
                                            <td>{{ $boy->monthly_cost }}</td>
                                            <td>{{ $boy->notes }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif


                        @if ($user->patient != null)
                            <h3>الحالة الصحية</h3>
                            <div class="table-responsive">
                                <!--begin::Table-->
                                <table class="table table-striped table-bordered w-100">
                                    <thead>
                                    <tr class="fw-bolder text-muted bg-light">
                                        <th class="min-w-25px">اسم المريض</th>
                                        <th class="min-w-20px">نوع المريض</th>
                                        <th class="min-w-20px">الدواء</th>
                                        <th class="min-w-20px">وسيلة صرف الدواء</th>
                                        <th class="min-w-20px">هل تأمين ؟</th>
                                        <th class="min-w-20px">الطبيب المعالج</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>{{ $user->patient->name }}</td>
                                        <td>{{ $user->patient->type == 1 ? 'ذكر' : 'أنثي' }}</td>
                                        <td>{{ $user->patient->treatment }}</td>
                                        <td>{{ $user->patient->treatment_pay_by }}</td>
                                        <td>{{ $user->patient->is_insurance == 0 ? 'لا' : 'نعم' }}</td>
                                        <td>{{ $user->patient->doctor_name }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            @endif

                            </tbody>
                            </table>
{{--                            @stop--}}
                    </div>
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
                </div>

        </div>
    @elseif($borrower != null)
        <!-- Borrower details section -->
        <div class="card bg-white p-3 shadow-sm">
            <h3>المقترض</h3>
            <div class="table-responsive">
                <table class="table table-striped table-bordered w-100">
                    <thead>
                    <tr class="fw-bolder text-muted bg-light">
                        <th class="min-w-25px">الاسم</th>
                        <th class="min-w-25px">الهاتف</th>
                        <th class="min-w-25px">الرقم القومي</th>
                        <th class="min-w-25px">العنوان</th>
                        <th class="min-w-25px">العمل</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>{{ $borrower->name }}</td>
                        <td>{{ $borrower->phone }}</td>
                        <td>{{ $borrower->nationalID }}</td>
                        <td>{{ $borrower->address }}</td>
                        <td>{{ $borrower->job }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>


        </div>
    @elseif($guarantor != null)
        <!-- Guarantor details section -->
        <div class="card bg-white p-3 shadow-sm">
            <h3>الضامن</h3>
            <div class="table-responsive">
                <table class="table table-striped table-bordered w-100">
                    <thead>
                    <tr class="fw-bolder text-muted bg-light">
                        <th class="min-w-25px">الاسم</th>
                        <th class="min-w-25px">الهاتف</th>
                        <th class="min-w-25px">الرقم القومي</th>
                        <th class="min-w-25px">العنوان</th>
                        <th class="min-w-25px">العمل</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>{{ $guarantor->name }}</td>
                        <td>{{ $guarantor->phone }}</td>
                        <td>{{ $guarantor->nationalID }}</td>
                        <td>{{ $guarantor->address }}</td>
                        <td>{{ $guarantor->job }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @elseif($child != null)
        <!-- Child details section -->
        <div class="card bg-white p-3 shadow-sm">
            <h3>الطفل</h3>
            <div class="table-responsive">
                <table class="table table-striped table-bordered w-100">
                    <thead>
                    <tr class="fw-bolder text-muted bg-light">
                        <th class="min-w-25px">اسم الطفل</th>
                        <th class="min-w-25px">الرقم القومي</th>
                        <th class="min-w-25px">العمر</th>
                        <th class="min-w-25px">المدرسة</th>
                        <th class="min-w-25px">التكلفة الشهرية</th>
                        <th class="min-w-25px">ملاحظات</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>{{ $child->child_name }}</td>
                        <td>{{ $child->children_national_id }}</td>
                        <td>{{ $child->age }}</td>
                        <td>{{ $child->school }}</td>
                        <td>{{ $child->monthly_cost }}</td>
                        <td>{{ $child->notes }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <!-- Link to parent if available -->
            @if($child->user)
                <div class="mt-3">
                    <a href="{{ route('users.show', $child->user->id) }}" class="btn btn-info">
                        عرض بيانات الأسرة
                    </a>
                </div>
            @endif
        </div>
    @endif

    <!-- Modal for images (keep your existing modal code) -->
    <div id="imageModal" class="modal">
        <span class="close" onclick="closeModal()">&times;</span>
        <img class="modal-content" style="width: 1500px" id="modalImage">
    </div>
@stop

@section('scripts')
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
