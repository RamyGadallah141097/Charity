@extends('Admin/layouts/master')

@section('title')
    {{($setting->title) ?? ''}} | البحوث
@endsection
@section('page_name') البحوث @endsection
@section('content')

    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        قائمة بالمستفدين من {{($setting->title) ?? ''}}
                    </h3>
                    <div class="">

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
                                <th class="min-w-50px">نوع العمل</th>
                                <th class="min-w-50px">اجمالي الدخل</th>
                                <th class="min-w-50px">اجمالي المصاريف</th>
                                <th class="min-w-50px">الحالة</th>
                                <th class="min-w-50px">تفاصيل</th>
                                <th class="min-w-50px rounded-end">البحوث</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        <!-- Edit MODAL -->
        <div class="modal fade bd-example-modal-lg" id="editOrCreate" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
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
        var columns = [
            {data: 'id', name: 'id'},
            {data: 'husband_name', name: 'husband_name'},
            {data: 'wife_name', name: 'wife_name'},
            {data: 'social_status', name: 'social_status'},
            {data: 'nearest_phone', name: 'nearest_phone'},
            {data: 'work_type', name: 'work_type'},
            {data: 'gross_income', name: 'gross_income'},
            {data: 'total_expenses', name: 'total_expenses'},
            {data: 'status', name: 'status'},
            {data: 'details', name: 'details'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
        showData('{{route('research.index')}}', columns);
    </script>
@endsection


