@extends('admin/layouts/master')

@section('title')
    {{ $setting->title ?? '' }} | {{ $lookup['title'] }}
@endsection

@section('page_name')
    {{ $lookup['title'] }}
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h3 class="card-title mb-1">{{ $lookup['title'] }}</h3>
                        <p class="mb-0 text-muted">يمكن استخدام هذه القيم كمرجع داخل باقي الوحدات.</p>
                    </div>
                    <div class="">
                        <button class="btn btn-secondary btn-icon text-white addBtn">
                            <span><i class="fe fe-plus"></i></span> إضافة جديد
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered text-nowrap w-100" id="dataTable">
                            <thead>
                                <tr class="fw-bolder text-muted bg-light">
                                    <th>#</th>
                                    <th>الاسم</th>
                                    @if ($lookup['show_code'])
                                        <th>الكود</th>
                                    @endif
                                    @if ($lookup['parent_label'])
                                        <th>{{ $lookup['parent_label'] }}</th>
                                    @endif
                                    @if ($type === 'disbursement-frequencies')
                                        <th>الفاصل بالشهور</th>
                                    @endif
                                    @if ($lookup['show_sort_order'])
                                        <th>الترتيب</th>
                                    @endif
                                    <th>الحالة</th>
                                    @if ($lookup['show_notes'])
                                        <th>ملاحظات</th>
                                    @endif
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
                    <form action="{{ route('references.delete', $type) }}" method="post">
                        @csrf
                        <div class="modal-body">
                            <input id="delete_id" name="id" type="hidden">
                            <p>هل أنت متأكد من حذف: <span id="title" class="text-danger"></span> ؟</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal" id="dismiss_delete_modal">إغلاق</button>
                            <button type="submit" class="btn btn-danger" id="delete_btn">حذف</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editOrCreate" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">إدارة {{ $lookup['title'] }}</h5>
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
            { data: 'name', name: 'name' },
            @if ($lookup['show_code'])
            { data: 'code', name: 'code' },
            @endif
            @if ($lookup['parent_label'])
            { data: 'parent', name: 'parent', orderable: false, searchable: false },
            @endif
            @if ($type === 'disbursement-frequencies')
            { data: 'months_interval', name: 'months_interval' },
            @endif
            @if ($lookup['show_sort_order'])
            { data: 'sort_order', name: 'sort_order' },
            @endif
            { data: 'is_active', name: 'is_active', orderable: false, searchable: false },
            @if ($lookup['show_notes'])
            { data: 'notes', name: 'notes' },
            @endif
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ];

        showData('{{ route('references.index', $type) }}', columns);
        deleteScript('{{ route('references.delete', $type) }}');
        showAddModal('{{ route('references.create', $type) }}');
        addScript();
        showEditModal('{{ route('references.edit', [$type, ':id']) }}');
        editScript();

        $(document).on('change', '.reference-status-toggle', function () {
            var checkbox = $(this);
            var originalState = !checkbox.is(':checked');

            $.ajax({
                url: '{{ route('references.toggle-status', [$type, ':id']) }}'.replace(':id', checkbox.data('id')),
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    is_active: checkbox.is(':checked') ? 1 : 0
                },
                success: function (data) {
                    if (data.status === 200) {
                        toastr.success(data.message || 'تم تحديث الحالة بنجاح');
                        $('#dataTable').DataTable().ajax.reload(null, false);
                    } else {
                        checkbox.prop('checked', originalState);
                        toastr.error(data.message || 'تعذر تحديث الحالة');
                    }
                },
                error: function () {
                    checkbox.prop('checked', originalState);
                    toastr.error('تعذر تحديث الحالة');
                }
            });
        });
    </script>
@endsection
