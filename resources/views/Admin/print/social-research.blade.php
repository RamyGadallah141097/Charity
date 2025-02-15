@extends('Admin/layouts/master')
@section('title') {{$setting->title}} | بحث اجتماعي @endsection
@section('page_name') بحث اجتماعي @endsection
@section('content')
        <div class="row">
            <div class="col-md-12" id="printDiv">
                <div class="card">
                    <p class="text-right mt-4 mr-3" style="font-weight: bold">{{$setting->title}}</p>
                    <p class="text-right mt-1 mr-3" style="font-weight: bold">المشهرة برقم {{$setting->vat_number}}</p>
                    <h1 class="text-center mt-4" style="font-weight: bold">بحث اجتماعي</h1>
                    <div class="card-header mt-4 mb-2" style="justify-content:space-between">
                        <div class="fw-bold" style="font-size: 1.125rem">
                            الحالة :
                        </div>
                        <div class="fw-bold" style="font-size: 1.125rem">
                            رقم الحالة : #{{$user->id}}
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">اسم الزوج</label>
                                    <input type="text" class="form-control" name="husband_name" value="{{$user->husband_name}}" readonly>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">اسم الزوجة</label>
                                    <input type="text" class="form-control" name="wife_name"  value="{{$user->wife_name}}" disabled>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">الحالة الاجتماعية</label>
                                    @if($user->social_status == 'single')
                                        <input class="form-control" type="text" value="اعزب" disabled>
                                    @elseif ($user->social_status == 'married')
                                        <input class="form-control" type="text" value="متزوج" disabled>
                                    @elseif ($user->social_status == 'divorced')
                                        <input class="form-control" type="text" value="مطلق" disabled>
                                    @else
                                        <input class="form-control" type="text" value="أرمل" disabled>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="form-label">نوع العمل</label>
                                    <input type="text" class="form-control" name="work_type" value="{{$user->work_type}}" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">تاريخ الميلاد</label>
                                    <input type="date" class="form-control" name="husband_birthday" value="{{$user->husband_birthday}}" disabled>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">تاريخ الميلاد</label>
                                    <input type="date" class="form-control" name="wife_birthday" value="{{$user->wife_birthday}}" disabled>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">العنوان</label>
                                    <input type="text" class="form-control" name="address" value="{{$user->address}}" disabled>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">أقرب تليفون</label>
                                    <input type="text" class="form-control" name="nearest_phone" value="{{$user->nearest_phone}}" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <label class="form-label">مرتب</label>
                                <input type="number" class="form-control" name="salary" value="{{$user->salary}}" disabled>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">معاش</label>
                                <input type="number" class="form-control" name="pension" value="{{$user->pension}}" disabled>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">تأمين</label>
                                <input type="number" class="form-control" name="insurance" value="{{$user->insurance}}" disabled>
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">كرامة</label>
                                <input type="number" class="form-control" name="dignity" value="{{$user->dignity}}" disabled>
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">تجارة</label>
                                <input type="number" class="form-control" name="trade" value="{{$user->trade}}" disabled>
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">سادات</label>
                                <input type="number" class="form-control" name="pillows" value="{{$user->pillows}}" disabled>
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">غير ذلك</label>
                                <input type="number" class="form-control" name="other" value="{{$user->other}}" disabled>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">اجمالي الدخل</label>
                                <input type="number" class="form-control" name="gross_income" value="{{$user->gross_income}}" disabled>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-2">
                                <label class="form-label">اجمالي النفقات</label>
                                <input type="number" class="form-control" name="total_expenses" value="{{$user->total_expenses}}" disabled>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">إيجار</label>
                                <input type="number" class="form-control" name="rent" value="{{$user->rent}}" disabled>
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">غاز</label>
                                <input type="number" class="form-control" name="gas" value="{{$user->gas}}" disabled>
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">ديون</label>
                                <input type="number" class="form-control" name="debt" value="{{$user->debt}}" disabled>
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">مياه</label>
                                <input type="number" class="form-control" name="water" value="{{$user->water}}" disabled>
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">علاج</label>
                                <input type="number" class="form-control" name="treatment" value="{{$user->treatment}}" disabled>
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">كهرباء</label>
                                <input type="number" class="form-control" name="electricity" value="{{$user->electricity}}" disabled>
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">جمعية</label>
                                <input type="number" class="form-control" name="association" value="{{$user->association}}" disabled>
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">طعام</label>
                                <input type="number" class="form-control" name="food" value="{{$user->food}}" disabled>
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">دراسة</label>
                                <input type="number" class="form-control" name="study" value="{{$user->study}}" disabled>
                            </div>
                        </div>

                        @if(count($user->childrens))
                            <h3 class="mt-4 text-center">الأولاد</h3>
                            <div class="table-responsive mt-4">
                                <!--begin::Table-->
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
                                    @foreach($user->childrens as $boy)
                                        <tr>
                                            <td>{{$boy->id}}</td>
                                            <td>{{$boy->name}}</td>
                                            <td>{{$boy->school}}</td>
                                            <td>{{$boy->lessons_cost}}</td>
                                            <td>{{$boy->academic_year}}</td>
                                            <td>{{$boy->monthly_cost}}</td>
                                            <td>{{$boy->notes}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif


                        @if($user->patient != null)
                            <h3 class="mt-4 text-center">الحالة الصحية</h3>
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
                                        <td>{{$user->patient->name}}</td>
                                        <td>{{($user->patient->type == 1) ? 'ذكر' : 'أنثي'}}</td>
                                        <td>{{$user->patient->treatment}}</td>
                                        <td>{{$user->patient->treatment_pay_by}}</td>
                                        <td>{{($user->patient->is_insurance == 0) ? 'لا' : 'نعم'}}</td>
                                        <td>{{$user->patient->doctor_name}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        <div class="card mt-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">هل فيه املاك</label>
                                            <input type="text" class="form-control" value="{{($user->has_property == 1) ? 'نعم' : 'لا'}}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">هل فيه دفتر توفير</label>
                                            <input type="text" class="form-control" value="{{($user->has_savings_book == 1) ? 'نعم' : 'لا'}}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <h4 class="mb-6 text-right">
                                رؤية الباحث وقراره :
                            </h4>
                            <h4 class="mb-6">
                                ترفق صورة من المستندات التي تثبت صحة البيانات المعطاة
                            </h4>
                            <h4 class="mb-6">
                                توقيع الباحث :
                            </h4>
                            <h4 class="mb-6">
                                <span class="text-center">امين الصندوق</span>
                                <span style="float: left">المقرر</span>
                            </h4>
                        </div>
                    </div>
                    <div class="col-12 text-center">
                        <button title="طباعة" class="btn btn-lg btn-outline-success mt-2 mb-2" id="printBtn">طباعة <i
                                class="fa fa-print"></i></button>
                    </div>
                </div>
            </div>
        </div>
        <!-- COL END -->



@endsection
@section('js')
    <script>
        $("#printBtn").click(function () {
            //Hide all other elements other than printarea.
            $(this).hide();
            var printContents = document.getElementById('printDiv').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            $(".app-header .header").css("display", "none");
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        });
    </script>
@endsection
