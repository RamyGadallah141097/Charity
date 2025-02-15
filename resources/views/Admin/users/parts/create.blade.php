@extends('Admin/layouts/master')
@section('title') {{$setting->title}} | اضافة مستفيد @endsection
@section('page_name') مستفيد جديد @endsection
@section('content')
    @if(count($errors) > 0 )
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <ul class="p-0 m-0" style="list-style: none;">
                @foreach($errors->all() as $error)
                    <li><i class="fa fa-times-circle"></i> {{$error}}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{route('users.store')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"><h2 class="mb-0">اضافة بيانات مستفيد جديد</h2></div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">اسم الزوج</label>
                                    <input type="text" class="form-control" name="husband_name" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">اسم الزوجة</label>
                                    <input type="text" class="form-control" name="wife_name" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">الحالة الاجتماعية</label>
                                    <select name="social_status" class="form-control select2"
                                            data-placeholder="اختيار الحالة الاجتماعية">
                                        <option value="single">أعزب</option>
                                        <option value="married">متزوج</option>
                                        <option value="divorced">مطلق</option>
                                        <option value="widow">أرمل</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">نوع العمل</label>
                                    <input type="text" class="form-control" name="work_type" placeholder="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">تاريخ الميلاد</label>
                                    <input type="date" class="form-control" name="husband_birthday" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">تاريخ الميلاد</label>
                                    <input type="date" class="form-control" name="wife_birthday" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">العنوان</label>
                                    <input type="text" class="form-control" name="address" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">أقرب تليفون</label>
                                    <input type="text" class="form-control" name="nearest_phone" placeholder="">
                                </div>
                            </div>
                        </div>

                        <h3 class="mt-2">اجمالي الدخل</h3>
                        <div class="row">
                            <div class="col-md-2">
                                <label class="form-label">مرتب</label>
                                <input type="number" class="form-control" name="salary" placeholder="">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">معاش</label>
                                <input type="number" class="form-control" name="pension" placeholder="">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">تأمين</label>
                                <input type="number" class="form-control" name="insurance" placeholder="">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">كرامة</label>
                                <input type="number" class="form-control" name="dignity" placeholder="">
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">تجارة</label>
                                <input type="number" class="form-control" name="trade" placeholder="">
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">سادات</label>
                                <input type="number" class="form-control" name="pillows" placeholder="">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">غير ذلك</label>
                                <input type="number" class="form-control" name="other" placeholder="">
                            </div>
                        </div>

                        <h3 class="mt-4">اجمالي النفقات</h3>
                        <div class="row">
                            <div class="col-md-2">
                                <label class="form-label">إيجار</label>
                                <input type="number" class="form-control" name="rent" placeholder="">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">غاز</label>
                                <input type="number" class="form-control" name="gas" placeholder="">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">ديون</label>
                                <input type="number" class="form-control" name="debt" placeholder="">
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">مياه</label>
                                <input type="number" class="form-control" name="water" placeholder="">
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">علاج</label>
                                <input type="number" class="form-control" name="treatment" placeholder="">
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">كهرباء</label>
                                <input type="number" class="form-control" name="electricity" placeholder="">
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">جمعية</label>
                                <input type="number" class="form-control" name="association" placeholder="">
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">طعام</label>
                                <input type="number" class="form-control" name="food" placeholder="">
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">دراسة</label>
                                <input type="number" class="form-control" name="study" placeholder="">
                            </div>
                        </div>


                        <h3 class="mt-4">الاولاد</h3>

                        <div class="table-responsive-md col-sm-12">
                            <table class="table table-striped-table-bordered table-hover table-checkable table-"
                                   id="tbl_posts">
                                <thead>
                                <tr>

                                    <th>#</th>
                                    <th>الاسم</th>
                                    <th>مدرسة / جامعة</th>
                                    <th>تكلفة الدروس</th>
                                    <th>السنة الدراسية</th>
                                    <th>التكلفة الشهرية</th>
                                    <th>ملاحظات</th>
                                    <th>
                                        <a class="btn btn-success text-white add-record click" data-added="0"> ادراج سجل
                                            <i class="fa fa-plus"></i></a>
                                    </th>
                                </tr>
                                </thead>
                                <tbody id="tbl_posts_body">
                                <tr>
                                    <td><span class="sn">1</span>.</td>
                                    <td><input type="text" name="names[]" class="form-control" value="" placeholder="">
                                    </td>
                                    <td><input type="text" name="schools[]" class="form-control" value=""
                                               placeholder=""></td>
                                    <td><input type="number" name="lessons_costs[]" class="form-control" value=""
                                               placeholder=""></td>
                                    <td><input type="text" name="academic_year[]" class="form-control" value=""
                                               placeholder=""></td>
                                    <td><input type="number" name="monthly_cost[]" class="form-control" value=""
                                               placeholder=""></td>
                                    <td><textarea rows="3" type="text" name="notes[]" class="form-control"
                                                  placeholder=""></textarea></td>
                                    <td></td>
                                </tr>
                                </tbody>
                            </table>

                        </div>
                        <div style="display:none;">
                            <table id="sample_table">
                                <tr id="">
                                    <td><span class="sn"></span>.</td>
                                    <td><input type="text" name="names[]" class="form-control" value="" placeholder="">
                                    </td>
                                    <td><input type="text" name="schools[]" class="form-control" value=""
                                               placeholder=""></td>
                                    <td><input type="text" name="lessons_costs[]" class="form-control" value=""
                                               placeholder=""></td>
                                    <td><input type="text" name="academic_year[]" class="form-control" value=""
                                               placeholder=""></td>
                                    <td><input type="text" name="monthly_cost[]" class="form-control" value=""
                                               placeholder=""></td>
                                    <td><textarea rows="3" type="text" name="notes[]" class="form-control"
                                                  placeholder=""></textarea></td>
                                    <td><a class="btn btn-xs delete-record " data-id="1"><i style="color: #f4516c"
                                                                                            class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <h3 class="mt-4">الحالة الصحية</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">اسم المريض</label>
                                    <input type="text" class="form-control" name="name" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">نوع المريض</label>
                                    <select name="type" class="form-control select2"
                                            data-placeholder="اختيار نوع المريض">
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
                                        <label for="is_insurance" class="label-success"></label></div>
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
                                        <label for="has_property" class="label-success"></label></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label mb-4">هل فيه دفتر توفير</label>
                                    <div class="material-switch pull-left mb-5">
                                        <input id="has_savings_book" name="has_savings_book" type="checkbox">
                                        <label for="has_savings_book" class="label-success"></label></div>
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
        jQuery(document).delegate('a.add-record', 'click', function (e) {
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
        jQuery(document).delegate('a.delete-record', 'click', function (e) {

            e.preventDefault();

            var numdivs = $('.MainDivs').length;

            if (numdivs == 2) {
                alert('لا يمكن الحذف')
            } else {
                var id = jQuery(this).attr('data-id');
                var targetDiv = jQuery(this).attr('targetDiv');
                jQuery('#rec-' + id).remove();

                //regnerate index number on table
                $('#tbl_posts_body tr').each(function (index) {
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
