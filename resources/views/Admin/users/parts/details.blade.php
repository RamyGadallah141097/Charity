@extends('Admin/layouts/master')

@section('title')
    {{ $setting->title ?? '' }} | المستفيدين
@endsection
@section('page_name')
    المستفيدين
@endsection



@section('content')
    <h3>المستفيد</h3>
    <div class="table-responsive">
        <table class="table table-striped table-bordered w-100">
            <thead>
                <tr class="fw-bolder text-muted bg-light">
                    <th class="min-w-25px">اسم الزوج</th>
                    <th class="min-w-25px">اسم الزوجة</th>
                    <th class="min-w-25px">الرقم القومى للزوج </th>
                    <th class="min-w-25px"> الرقم القومى للزوجة</th>
                    <th class="min-w-25px">عمر الزوج</th>
                    <th class="min-w-25px">عمر الزوجة</th>
                    <th class="min-w-25px">الحالة الاجتماعية</th>
                    <th class="min-w-25px">الهاتف</th>
                    <th class="min-w-25px">نوع العمل</th>
                    <th class="min-w-25px"> العنوان</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $user->husband_name }}</td>
                    <td>{{ $user->wife_name }}</td>
                    <td>{{ $user->husband_national_id }}</td>
                    <td>{{ $user->wife_national_id }}</td>
                    <td>{{ $user->age_husband }}</td>
                    <td>{{ $user->age_wife }}</td>
                    <td>{{ $user->social_status }}</td>
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

        <h3>الحالة الصحية</h3>
        <div class="table-responsive">
            <!--begin::Table-->
            <table class="table table-striped table-bordered w-100">
                <thead>
                    <tr class="fw-bolder text-muted bg-light">
                        <th class="min-w-25px"> #</th>
                        <th class="min-w-25px">اسم المريض</th>
                        <th class="min-w-20px">الطبيب المعالج</th>
                        <th class="min-w-20px">نوع المريض</th>
                        <th class="min-w-20px">وسيلة صرف الدواء</th>
                        <th class="min-w-20px">هل تأمين ؟</th>
                        <th class="min-w-20px">الدواء</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($patients as $patient)
                        <tr>
                            <td>{{ $patient->id }}</td>
                            <td>{{ $patient->patient_name }}</td>
                            <td>{{ $patient->doctor_name }} </td>
                            <td>{{ $patient->type == 0 ? 'انثى' : 'ذكر' }}</td>
                            <td>{{ $patient->treatment_pay_by }}</td>
                            <td>{{ $patient->is_insurance == 0 ? 'لا' : 'نعم' }}</td>
                            <td>{{ $patient->treatment }}</td>
                            <td>{{ Str::limit($patient->treatment, 40) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
        </div>

        @if ($user->attachments)
            @php
                $attachments = json_decode($user->attachments, true);
            @endphp

            @foreach ($attachments as $attachment)
                <img src="{{ asset('storage/' . $attachment) }}" alt="Attachment" width="150" height="150">
            @endforeach
        @endif

    @stop
