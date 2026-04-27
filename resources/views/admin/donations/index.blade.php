@extends('admin/layouts/master')

@section('title')
    {{ isset($setting) ? $setting->title : '' }}
    | التبرعات
@endsection
@section('page_name')
    التبرعات
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"> التبرعات المالية الواردة </h3>
                    <div class="">
                        @if (auth()->user()->can('Donations.index'))
                            <a href="{{ route('PrintDonations') }}" class="btn btn-warning">
                                <i class="fa fa-print"></i> طباعة التبرعات
                            </a>
                        @endif
                        @if (auth()->user()->can('Donations.create'))
                            <button class="btn btn-secondary btn-icon text-white addBtn">
                                <span>
                                    <i class="fe fe-plus"></i>
                                </span> اضافة جديد
                            </button>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table table-striped table-bordered text-nowrap w-75" id="dataTable">
                            <thead>
                                <tr class="fw-bolder text-muted bg-light">
                                    <th class="min-w-25px">#</th>
                                    <th class="min-w-50px">اسم المتبرع</th>
                                    <th class="min-w-125px">الهاتف</th>
                                    <th class="min-w-125px">تاريخ الاستلام</th>
                                    <th class="min-w-125px">تصنيف التبرع</th>
                                    <th class="min-w-125px">الصنف</th>
                                    <th class="min-w-125px">المبلغ/الكمية</th>
                                    <th class="min-w-125px">الوحدة</th>
                                    <th class="min-w-125px">رقم الوصل</th>
                                    <th class="min-w-125px">المسؤول</th>
                                    <th class="min-w-125px">شهر التبرع</th>
                                    <th class="min-w-125px">المناسبة</th>
                                    <th class="min-w-125px">العمليات</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-lg-12 mt-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"> التبرعات غير النقدية </h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered text-nowrap w-75" id="nonCashTable">
                            <thead>
                                <tr class="fw-bolder text-muted bg-light">
                                    <th class="min-w-25px">#</th>
                                    <th class="min-w-50px">اسم المتبرع</th>
                                    <th class="min-w-125px">الهاتف</th>
                                    <th class="min-w-125px">تاريخ الاستلام</th>
                                    <th class="min-w-125px">تصنيف التبرع</th>
                                    <th class="min-w-125px">الصنف</th>
                                    <th class="min-w-125px">المبلغ/الكمية</th>
                                    <th class="min-w-125px">الوحدة</th>
                                    <th class="min-w-125px">رقم الوصل</th>
                                    <th class="min-w-125px">المسؤول</th>
                                    <th class="min-w-125px">شهر التبرع</th>
                                    <th class="min-w-125px">المناسبة</th>
                                    <th class="min-w-125px">العمليات</th>
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
                    <form action="{{ route('donations_delete') }}" method="post">
                        @csrf
                        @method('post')
                        <div class="modal-body">
                            <input id="delete_id" name="id" type="hidden">
                            <p>هل انت متأكد من حذف البيانات التالية <span id="title" class="text-danger"></span>؟</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal" id="dismiss_delete_modal">
                                اغلاق
                            </button>
                            <button type="submit" class="btn btn-danger" id="delete_btn">حذف !</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- MODAL CLOSED -->

        <!-- Create Or Edit Modal -->
        <div class="modal fade" id="editOrCreate" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="example-Modal3">بيانات المتبرع</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="modal-body">

                    </div>
                </div>
            </div>
        </div>
        <!-- Create Or Edit Modal -->
    </div>
    @include('admin/layouts/myAjaxHelper')
@endsection
@section('ajaxCalls')
    <script>
        var columns = [{
                data: null,
                name: 'index',
                render: function(data, type, row, meta) {
                    return meta.row + 1;
                },
                orderable: false,
                searchable: false
            },
            {
                data: 'donor_name',
                name: 'donor_name'
            },
            {
                data: 'donor_phone',
                name: 'donor_phone'
            },
            {
                data: 'received_at_display',
                name: 'received_at_display'
            },
            {
                data: 'donation_type_name',
                name: 'donation_type_name'
            },
            {
                data: 'donation_category_name',
                name: 'donation_category_name'
            },
            {
                data: 'value_with_unit',
                name: 'value_with_unit'
            },
            {
                data: 'unit_name',
                name: 'unit_name'
            },
            {
                data: 'receipt_number',
                name: 'receipt_number'
            },
            {
                data: 'received_by_name',
                name: 'received_by_name'
            },
            {
                data: 'donation_month_name',
                name: 'donation_month_name'
            },
            {
                data: 'occasion_name',
                name: 'occasion_name'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
        ]
        showData('{{ route('Donations.index', ['category' => 'cash']) }}', columns);
        deleteScript('{{ route('donations_delete') }}');
        showAddModal('{{ route('Donations.create') }}');
        addScript();
        showEditModal('{{ route('Donations.edit', ':id') }}');
        editScript();

        $('#nonCashTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('Donations.index', ['category' => 'non_cash']) }}',
            columns: columns,
            order: [
                [0, "ASC"]
            ],
            language: {
                sProcessing: "جاري التحميل ..",
                sLengthMenu: "اظهار _MENU_ سجل",
                sZeroRecords: "لا يوجد نتائج",
                sInfo: "اظهار _START_ الى  _END_ من _TOTAL_ سجل",
                sInfoEmpty: "لا نتائج",
                sInfoFiltered: "للبحث",
                sSearch: "بحث :    ",
                oPaginate: {
                    sPrevious: "السابق",
                    sNext: "التالي",
                }
            }
        });
    </script>
@endsection
