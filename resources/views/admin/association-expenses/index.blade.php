@extends('admin/layouts/master')

@section('title')
    {{ isset($setting) ? $setting->title : '' }} | مصروفات الجمعية
@endsection
@section('page_name')
    مصروفات الجمعية
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h3 class="card-title mb-1">مصروفات الجمعية</h3>
                        <small class="text-muted">رصيد خزنة الجمعية الحالي: {{ number_format($balance, 2) }}</small>
                    </div>
                    @if (auth()->user()->can('association.expenses.create'))
                        <div class="">
                            <button class="btn btn-secondary btn-icon text-white addBtn">
                                <span><i class="fe fe-plus"></i></span> إضافة مصروف
                            </button>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered text-nowrap w-100" id="dataTable">
                            <thead>
                                <tr class="fw-bolder text-muted bg-light">
                                    <th>#</th>
                                    <th>نوع المصروف</th>
                                    <th>المبلغ</th>
                                    <th>التاريخ</th>
                                    <th>رقم المرجع</th>
                                    <th>ملاحظات</th>
                                    <th>بواسطة</th>
                                    <th>العمليات</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">حذف بيانات</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form action="{{ route('association-expenses.delete') }}" method="post">
                        @csrf
                        <div class="modal-body">
                            <input id="delete_id" name="id" type="hidden">
                            <p>هل انت متأكد من حذف البيانات التالية <span id="title" class="text-danger"></span>؟</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal" id="dismiss_delete_modal">اغلاق</button>
                            <button type="submit" class="btn btn-danger" id="delete_btn">حذف !</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editOrCreate" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">بيانات المصروف</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="modal-body"></div>
                </div>
            </div>
        </div>
    </div>
    @include('admin/layouts/myAjaxHelper')
@endsection

@section('ajaxCalls')
    <script>
        var columns = [
            { data: 'id', name: 'id' },
            { data: 'type_name', name: 'type_name' },
            { data: 'amount', name: 'amount' },
            { data: 'transaction_date', name: 'transaction_date' },
            { data: 'reference_number', name: 'reference_number' },
            { data: 'notes', name: 'notes' },
            { data: 'created_by', name: 'created_by' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ];

        showData('{{ route('association-expenses.index') }}', columns);
        deleteScript('{{ route('association-expenses.delete') }}');
        showAddModal('{{ route('association-expenses.create') }}');
        addScript();
        showEditModal('{{ route('association-expenses.edit', ':id') }}');
        editScript();
    </script>
@endsection
