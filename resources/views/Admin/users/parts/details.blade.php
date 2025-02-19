<h3>المستفيد</h3>
<div class="table-responsive">
    <table class="table table-striped table-bordered w-100">
        <thead>
            <tr class="fw-bolder text-muted bg-light">
                <th class="min-w-25px">اسم الزوج</th>
                <th class="min-w-25px">اسم الزوجة</th>
                <th class="min-w-25px">الرقم القومى للزوج </th>
                <th class="min-w-25px"> الرقم القومى للزوجة</th>
                <th class="min-w-25px"> تاريخ ميلاد الزوج</th>
                <th class="min-w-25px"> تاريخ ميلاد الزوجة</th>
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
                <td>{{ $user->husband_birthday }}</td>
                <td>{{ $user->wife_birthday }}</td>
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

@if (count($user->childrens))
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
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <li class="list-group-item"> هل فيه املاك
                    <div class="material-switch pull-left mt-4">
                        <input id="has_property" disabled readonly name="has_property" type="checkbox"
                            {{ $user->has_property == 1 ? 'checked' : '' }}>
                        <label for="has_property" class="label-success mt-2"></label>
                    </div>
                </li>
            </div>
            <div class="col-md-6">
                <li class="list-group-item"> هل فيه دفتر توفير
                    <div class="material-switch pull-left mt-4">
                        <input id="has_savings_book" disabled readonly name="has_savings_book" type="checkbox"
                            {{ $user->has_savings_book == 1 ? 'checked' : '' }}>
                        <label for="has_savings_book" class="label-success mt-2"></label>
                    </div>
                </li>
            </div>
        </div>
    </div>
</div>


<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
</div>
