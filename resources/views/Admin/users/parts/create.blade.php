@extends('Admin/layouts/master')
@section('title')
    {{ $setting->title }} | اضافة مستفيد
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
    <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="mb-0">اضافة بيانات مستفيد جديد</h2>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <div class="form-group col-md-6">
                                <label class="form-label">اسم الزوج</label>
                                <input type="text" class="form-control" name="husband_name" placeholder="">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label ">اسم الزوجة</label>
                                <input type="text" class="form-control" name="wife_name" placeholder="">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label "> الرقم القومى للزوج</label>
                                <input type="text" class="form-control" name="husband_national_id" placeholder="">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label "> الرقم القومى للزوجة</label>
                                <input type="text" class="form-control" name="wife_national_id" placeholder="">
                            </div>

                            <div class="form-group col-md-6">
                                <label class="form-label">تاريخ الميلاد الزوج</label>
                                <input type="date" class="form-control" name="husband_birthday" placeholder="">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="form-label">تاريخ الميلاد الزوجة</label>
                                <input type="date" class="form-control" name="wife_birthday" placeholder="">
                            </div>

                            <div class="form-group col-md-2">
                                <label class="form-label"> عمر الزوج</label>
                                <input type="text" class="form-control" name="age_husband" placeholder="">
                            </div>

                            <div class="form-group col-md-8">
                                <label class="form-label"> العنوان </label>
                                <input type="text" class="form-control" name="address" placeholder="">
                            </div>

                            <div class="form-group col-md-2">
                                <label class="form-label"> عمر الزوجة</label>
                                <input type="text" class="form-control" name="age_wife" placeholder="">
                            </div>

                            <div class="form-group col-md-2">
                                <label class="form-label">الحالة الاجتماعية للاب</label>
                                <select name="social_status" class="form-control select2"
                                    data-placeholder="اختيار الحالة الاجتماعية">
                                    <option value="0">أعزب</option>
                                    <option value="1">متزوج</option>
                                    <option value="2">مطلق</option>
                                    <option value="3">متوفى</option>
                                </select>
                            </div>


                            <div class="form-group col-md-4">
                                <label class="form-label">نوع العمل</label>
                                <input type="text" class="form-control" name="work_type" placeholder="">
                            </div>

                            <div class="form-group col-md-4">
                                <label class="form-label">أقرب تليفون</label>
                                <input type="text" class="form-control" name="nearest_phone" placeholder="">
                            </div>
                        </div>
                        <hr>

                        <h3 class="mt-2">اجمالي الدخل</h3>
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">مرتب</label>
                                <input type="text" class="form-control" name="salary" placeholder="">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">معاش</label>
                                <input type="text" class="form-control" name="pension" placeholder="">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">تأمين</label>
                                <input type="text" class="form-control" name="insurance" placeholder="">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">كرامة</label>
                                <input type="text" class="form-control" name="dignity" placeholder="">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">تجارة</label>
                                <input type="text" class="form-control" name="trade" placeholder="">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">سادات</label>
                                <input type="text" class="form-control" name="pillows" placeholder="">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">غير ذلك</label>
                                <input type="text" class="form-control" name="other" placeholder="">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label"> اجمالى الدخل</label>
                                <input type="text" class="form-control" name="gross_income" placeholder="" readonly>
                            </div>
                        </div>
                        <hr>
                        <h3 class="mt-4">اجمالي النفقات</h3>
                        <div class="row">
                            <div class="col-md-2">
                                <label class="form-label">إيجار</label>
                                <input type="text" class="form-control" name="rent" placeholder="">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">غاز</label>
                                <input type="number" class="form-control" name="gas" placeholder="">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">ديون</label>
                                <input type="number" class="form-control" name="debt" placeholder="">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">مياه</label>
                                <input type="number" class="form-control" name="water" placeholder="">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">علاج</label>
                                <input type="number" class="form-control" name="treatment" placeholder="">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">كهرباء</label>
                                <input type="number" class="form-control" name="electricity" placeholder="">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">جمعية</label>
                                <input type="number" class="form-control" name="association" placeholder="">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">طعام</label>
                                <input type="number" class="form-control" name="food" placeholder="">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">دراسة</label>
                                <input type="number" class="form-control" name="study" placeholder="">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label"> اجمالى النفقات</label>
                                <input type="text" class="form-control" name="gross_expenses" placeholder=""
                                    readonly>
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <h3 class="mt-4">الاولاد</h3>
                            <div class="d-flex justify-content-end mb-3">
                                <button type="button" class="btn btn-success" id="add">
                                    <i class="fe fe-plus"></i> {{ 'Add' }}
                                </button>
                            </div>
                        </div>
                        <hr>

                        <div id="bus_times_container">
                            <div class="bus-time-row row">

                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="name" class="form-control-label"> الاسم </label>
                                        <input type="text" class="form-control" name="name[]" required>
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="children_national_id" class="form-control-label"> الرقم القومى
                                        </label>
                                        <input type="text" class="form-control" name="children_national_id[]"
                                            required>
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="schools" class="form-control-label"> تاريخ الميلاد </label>
                                        <input type="date" class="form-control" name="birthday[]" required>
                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="form-group">
                                        <label for="schools" class="form-control-label"> السن </label>
                                        <input type="text" class="form-control" name="age[]">
                                    </div>
                                </div>

                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="schools" class="form-control-label"> المدرسة </label>
                                        <input type="text" class="form-control" name="schools[]" required>
                                    </div>
                                </div>

                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="lessons_costs" class="form-control-label"> تكلفة الدروس </label>
                                        <input type="text" class="form-control" name="lessons_costs[]" required>
                                    </div>
                                </div>

                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="academic_year" class="form-control-label"> السنة الدراسية </label>
                                        <input type="text" class="form-control" name="academic_year[]" required>
                                    </div>
                                </div>

                                <div class="col-2">
                                    <div class="form-group">
                                        <label for="monthly_cost" class="form-control-label"> التكلفة الشهرية </label>
                                        <input type="text" class="form-control" name="monthly_cost[]" required>
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="notes" class="form-control-label"> ملاحظات </label>
                                        <input type="text" class="form-control" name="notes[]" required>
                                    </div>
                                </div>

                            </div>

                        </div>



                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">{{ 'close' }}</button>
                            <button type="submit" class="btn btn-primary" id="addButton">{{ 'save' }}</button>
                        </div>
    </form>



    <h3 class="mt-4">الحالة الصحية</h3>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="form-label">اسم المريض</label>
                <input type="text" class="form-control" name="name" placeholder="">
            </div>
            <div class="form-group">
                <label class="form-label">نوع المريض</label>
                <select name="type" class="form-control select2" data-placeholder="اختيار نوع المريض">
                    <option value="1">ذكر</option>
                    <option value="0">أنثي</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">الدواء</label>
                <input type="text" class="form-control" name="treatment" placeholder="">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="form-label">وسيلة صرف العلاج</label>
                <input type="text" class="form-control" name="treatment_pay_by" placeholder="">
            </div>
            <div class="form-group">
                <label class="form-label mb-4">هل تأمين</label>
                <div class="material-switch pull-left mb-5">
                    <input id="is_insurance" name="is_insurance" type="checkbox">
                    <label for="is_insurance" class="label-success"></label>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">الطبيب المعالج</label>
                <input type="text" class="form-control" name="doctor_name" placeholder="">
            </div>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-6">
            <div class="form-group">
                <label class="form-label mb-4">هل فيه أملاك</label>
                <div class="material-switch pull-left mb-5">
                    <input id="has_property" name="has_property" type="checkbox">
                    <label for="has_property" class="label-success"></label>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label class="form-label mb-4">هل فيه دفتر توفير</label>
                <div class="material-switch pull-left mb-5">
                    <input id="has_savings_book" name="has_savings_book" type="checkbox">
                    <label for="has_savings_book" class="label-success"></label>
                </div>
            </div>
        </div>
    </div>
    </div>
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
        jQuery(document).delegate('a.add-record', 'click', function(e) {
            e.preventDefault();
            var content = jQuery('#sample_table tr'),
                size = jQuery('#tbl_posts >tbody >tr').length + 1,
                element = null,
                element = content.clone();
            element.attr('id', 'rec-' + size);
            element.find('.delete-record').attr('data-id', size);
            element.appendTo('#tbl_posts_body');
            element.find('.sn').html(size);

        });
        jQuery(document).delegate('a.delete-record', 'click', function(e) {

            e.preventDefault();

            var numdivs = $('.MainDivs').length;

            if (numdivs == 2) {
                alert('لا يمكن الحذف')
            } else {
                var id = jQuery(this).attr('data-id');
                var targetDiv = jQuery(this).attr('targetDiv');
                jQuery('#rec-' + id).remove();

                //regnerate index number on table
                $('#tbl_posts_body tr').each(function(index) {
                    //alert(index);
                    $(this).find('span.sn').html(index + 1);
                });
                return true;
            }
            // var didConfirm = confirm("Are you sure You want to delete");
            // if (didConfirm == true) {

            // } else {
            //   return false;
            // }
        });
    </script>
@endsection
