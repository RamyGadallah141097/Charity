@extends('Admin/layouts/master')

@section('title')
    {{ isset($setting) ? isset($setting->title) : '' }} | المستفيدين
@endsection
@section('page_name')
    المستفيدين
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        قائمة بالمستفدين من {{ isset($setting) ? isset($setting->title) : '' }}
                    </h3>
                    <div class="row">
                        <div class="col-3 m-3">
                            <select class="form-control" id="filterNStatus">
                                <option value="">الحاله الاجتماعيه</option>
                                <option value="0">أعزب</option>
                                <option value="1">متزوج</option>
                                <option value="2">مطلق</option>
                                <option value="3">أرمل</option>
                            </select>
                        </div>

                        <div class="col-3 m-3">
                            <select class="form-control" id="filterLifeLevel">
                                <option value="">مستوي المعيشه</option>
                                <option value="500">500</option>
                                <option value="1000">1000</option>
                                <option value="2000">2000</option>
                                <option value="3000">3000</option>
                                <option value="4000">4000</option>
                                <option value="5000">5000</option>
                                <option value="6000">6000</option>
                            </select>
                        </div>

                        <div class="col-3 m-3">
                            <select class="form-control" id="filterFamilyNumber">
                                <option value="">عدد الاطفال</option>
                                @for($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <a href="{{ route('users.create') }}" class="btn btn-secondary btn-icon text-white">
                        <span><i class="fe fe-plus"></i></span> اضافة مستفيد
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered text-nowrap w-100" id="dataTable">
                            <thead>
                            <tr class="fw-bolder text-muted bg-light">
                                <th>#</th>
                                <th>اسم الزوج</th>
                                <th>اسم الزوجة</th>
                                <th>الحالة الاجتماعية</th>
                                <th>الهاتف</th>
                                <th>اجمالي الدخل</th>
                                <th>اجمالي المصاريف</th>
                                <th>مستوى المعيشة</th>
                                <th>الحالة</th>
                                <th>تحديث</th>
                                <th>العمليات</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="">
                        <a href="{{ route('users.create') }}" class="btn btn-secondary btn-icon text-white">
                            <span>
                                <i class="fe fe-plus"></i>
                            </span> اضافة مستفيد
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table table-striped table-bordered text-nowrap w-100" id="dataTable">
                            <thead>
                            <tr class="fw-bolder text-muted bg-light">
                                <th class="min-w-25px">#</th>
                                <th class="min-w-50px">اسم الزوج</th>
                                <th class="min-w-50px">اسم الزوجة</th>
                                <th class="min-w-50px">الحالة الاجتماعية</th>
                                <th class="min-w-50px">الهاتف</th>
                                <th class="min-w-50px">اجمالي الدخل</th>
                                <th class="min-w-50px">اجمالي المصاريف</th>
                                <th class="min-w-50px">مستوى المعيشة</th>
                                <th class="min-w-50px">الحالة </th>
                                <th class="min-w-50px"> تحديث</th>
                                <th class="min-w-50px rounded-end">العمليات</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!--Delete MODAL -->
        <div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">حذف بيانات</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input id="delete_id" name="id" type="hidden">
                        <p>هل انت متأكد من حذف البيانات التالية <span id="title" class="text-danger"></span>؟</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal" id="dismiss_delete_modal">
                            اغلاق
                        </button>
                        <button type="button" class="btn btn-danger" id="delete_btn">حذف !</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- MODAL CLOSED -->


        <!-- Edit MODAL -->
        <div class="modal fade bd-example-modal-lg" id="editOrCreate" data-backdrop="static" tabindex="-1" role="dialog"
             aria-hidden="true">
            <div class="modal-dialog modal-" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="example-Modal3">تفاصيل عن المستفيد</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="modal-body">




                    </div>
                </div>
            </div>
        </div>
        <!-- Edit MODAL CLOSED -->
    </div>
    @include('Admin/layouts/myAjaxHelper')
@endsection
@section('ajaxCalls')
    <script>
        {{-- alert("{{Request::segment(3)}}") --}}
        var columns = [{
            data: 'id',
            name: 'id'
        },
            {
                data: 'husband_name',
                name: 'husband_name'
            },
            {
                data: 'wife_name',
                name: 'wife_name'
            },
            {
                data: 'social_status',
                name: 'social_status'
            },
            {
                data: 'nearest_phone',
                name: 'nearest_phone'
            },
            {
                data: 'gross_income',
                name: 'gross_income'
            },
            {
                data: 'gross_expenses',
                name: 'gross_expenses'
            },
            {
                data: 'standard_living',
                name: 'standard_living'
            },

            {
                data: 'status',
                name: 'status'
            },
            {
                data: 'statusChange',
                name: 'statusChange'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
        ]
        showData('{{ route('users.index', request()->segment(3)) }}', columns);

        // Delete Using Ajax
        deleteScript('{{ route('delete_users') }}');

        // Show Details Modal
        $(document).on('click', '.detailsBtn', function() {
            var id = $(this).data('id')
            var url = "{{ route('userDetails', ':id') }}";
            url = url.replace(':id', id)
            $('#modal-body').html(loader)
            $('#editOrCreate').modal('show')
            setTimeout(function() {
                $('#modal-body').load(url)
            }, 500)
        })

        // Change Status Using Ajax
        $(document).on('click', '.statusBtn', function(event) {
            event.preventDefault()
            var id = $(this).data("id"),
                status = $(this).data("status")
            $.ajax({
                type: 'POST',
                url: "{{ route('updateUserStatus') }}",
                data: {
                    '_token': "{{ csrf_token() }}",
                    'id': id,
                    'status': status,
                },
                success: function(data) {
                    if (data.status === true) {
                        $('#dataTable').DataTable().ajax.reload();
                        toastr.success(data.message)
                    } else
                        toastr.error('هناك خطأ ما يرجي اعادة المحاولة')
                },
                error: function(data) {
                    toastr.error(data.message)
                }
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            var status = "{{ request()->segment(3) }}";

            var table = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('users.index', ':status') }}".replace(':status', status),
                    type: "GET",  // Make sure request type matches your route
                    data: function (d) {
                        d.social_status = $('#filterNStatus').val() || null;
                        d.standard_living = $('#filterLifeLevel').val() || null;
                        d.family_number = $('#filterFamilyNumber').val() || null;
                    },
                    error: function(xhr) {
                        console.log("Error:", xhr.responseText); // Debugging
                    }
                },

                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'husband_name', name: 'husband_name' },
                    { data: 'wife_name', name: 'wife_name' },
                    { data: 'social_status', name: 'social_status' },
                    { data: 'nearest_phone', name: 'nearest_phone' },
                    { data: 'gross_income', name: 'gross_income' },
                    { data: 'gross_expenses', name: 'gross_expenses' },
                    { data: 'standard_living', name: 'standard_living' },
                    { data: 'status', name: 'status' },
                    { data: 'statusChange', name: 'statusChange' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });

            $('#filterNStatus, #filterLifeLevel, #filterFamilyNumber').on('change', function () {
                table.ajax.reload(null, false);
            });
        });
    </script>

@endsection
