@extends('admin/layouts/master')

@section('title')
    {{ isset($setting) ? $setting->title : '' }}
    | القروض
@endsection
@section('page_name')
    التبرعات
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"> القروض </h3>
                    <div class="">
                        {{--                        <button onclick="printDataTable()" title="طباعة" class="btn btn-success btn-icon text-white"> --}}
                        {{--                            طباعة --}}
                        {{--                            <i class="fa fa-print"></i> --}}
                        {{--                        </button> --}}
                        <a href="{{ route('printLoan') }}" title="طباعة" class="btn btn-success btn-icon text-white">
                            طباعة
                            <i class="fa fa-print"></i>
                        </a>
                        <button class="btn btn-secondary btn-icon text-white addBtn">
                            <span>
                                <i class="fe fe-plus"></i>
                            </span> اضافة جديد
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table table-striped table-bordered text-nowrap w-75" id="dataTable">
                            <thead>
                                <tr class="fw-bolder text-muted bg-light">
                                    <th class="min-w-25px">#</th>
                                    <th class="min-w-50px"> اسم المقترض</th>
                                    <th class="min-w-125px"> رقم الهاتف</th>
                                    <th class="min-w-125px">مبلغ القرض </th>
                                    <th class="min-w-125px"> تاريخ القرض </th>
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
                data: 'borrower_id',
                name: 'borrower_id'
            },
            {
                data: 'borrower_phone',
                name: 'borrower_phone'
            },
            {
                data: 'loan_amount',
                name: 'loan_amount'
            },
            {
                data: 'loan_date',
                name: 'loan_date'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
        ]
        showData('{{ route('index.Loans') }}', columns);
        deleteScript('{{ route('donations_delete') }}');
        showAddModal('{{ route('create.Loans') }}');
        addScript();
        showEditModal('{{ route('Donations.edit', ':id') }}');
        editScript();
    </script>

    {{--    <script> --}}
    {{--        function printDataTable() { --}}
    {{--            var table = document.querySelector('.dataTable'); --}}


    {{--            var tableHTML = table.outerHTML; --}}

    {{--            var printWindow = window.open('', '', 'width=900,height=600'); --}}

    {{--            printWindow.document.write(` --}}
    {{--            <html> --}}
    {{--            <head> --}}
    {{--                <title>طباعة الجدول</title> --}}
    {{--                <style> --}}
    {{--                    body { font-family: Arial, sans-serif; direction: rtl; text-align: right; } --}}
    {{--                    table { width: 100%; border-collapse: collapse; margin: 20px 0; } --}}
    {{--                    th, td { border: 1px solid #ddd; padding: 8px; text-align: center; } --}}
    {{--                    th { background-color: #f2f2f2; } --}}
    {{--                </style> --}}
    {{--            </head> --}}
    {{--            <body> --}}

    {{--            <div class="text-center" style="margin-top:100px "> --}}
    {{--                        <h4 class="mt-4 mb-1">بيان باسماء حالات  الاعانه الشهريه / {{ isset($setting) ? $setting->title : '' }}
</h4> --}}
    {{--                        <hr style="width: 40%" class="mt-0 mb-1 text-dark"></hr> --}}
    {{--                        <h4 class="mt-4 mb-2"></h4> --}}
    {{--            </div> --}}
    {{--            <div class="card-header mt-4 mb-2" style="justify-content:space-between"> --}}
    {{--                <div class="fw-bold" style="font-size: 1.125rem"> --}}
    {{--                    شبين الكوم - محافطة المنوفية --}}
    {{--                </div> --}}
    {{--                <div class="fw-bold" style="font-size: 1.125rem"> --}}
    {{--                    بتاريخ --}}
    {{--                    {{date('Y/m/d')}} --}}
    {{--                </div> --}}
    {{--            </div> --}}

    {{--                ${tableHTML} --}}
    {{--                <div style="margin-top:50px"></div> --}}
    {{--                <hr/> --}}
    {{--                 <h4 class="mb-2" style="margin-top:50px"> --}}
    {{--                            عضو له حق التوقيع ...................................... أمين الصندوق .............................................. مقرر اللجنة.............................................. --}}
    {{--                        </h4> --}}
    {{--                <script> --}}
    {{--                    window.onload = function() { --}}
    {{--                        window.print(); --}}
    {{--                        window.onafterprint = function() { window.close(); }; --}}
    {{--                    }; --}}
    {{--                <\/script> --}}
    {{--            </body> --}}
    {{--            </html> --}}
    {{--        `); --}}

    {{--            printWindow.document.close(); --}}
    {{--        } --}}
    {{--    </script> --}}
@endsection
