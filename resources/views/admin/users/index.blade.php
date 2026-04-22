@extends('admin/layouts/master')

@section('title')
    {{ isset($setting) ? $setting->title : '' }} | المستفيدين
@endsection

@section('page_name')
    المستفيدين
@endsection

@section('content')
    <div class="row">
        <!-- Stats Summary -->
        <div class="col-xl-3 col-md-6 col-lg-6">
            <div class="card overflow-hidden bg-primary-transparent border-0 shadow-none">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="mt-2 text-primary">
                            <h6 class="">إجمالي المستفيدين</h6>
                            <h2 class="mb-0 number-font" id="total_users_count">0</h2>
                        </div>
                        <div class="mr-auto">
                            <div class="chart-circle chart-circle-xs mr-1 text-primary" data-value="100" data-thickness="3" data-color="#5a5ce0">
                                <i class="fe fe-users fs-20"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-lg-6">
            <div class="card overflow-hidden bg-success-transparent border-0 shadow-none">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="mt-2 text-success">
                            <h6 class="">المقبولين</h6>
                            <h2 class="mb-0 number-font" id="accepted_users_count">0</h2>
                        </div>
                        <div class="mr-auto">
                            <i class="fe fe-user-check fs-40 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-lg-6">
            <div class="card overflow-hidden bg-warning-transparent border-0 shadow-none">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="mt-2 text-warning">
                            <h6 class="">قيد الانتظار</h6>
                            <h2 class="mb-0 number-font" id="preparing_users_count">0</h2>
                        </div>
                        <div class="mr-auto">
                            <i class="fe fe-clock fs-40 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 col-lg-6">
            <div class="card overflow-hidden bg-danger-transparent border-0 shadow-none">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="mt-2 text-danger">
                            <h6 class="">المرفوضين</h6>
                            <h2 class="mb-0 number-font" id="refused_users_count">0</h2>
                        </div>
                        <div class="mr-auto">
                            <i class="fe fe-user-x fs-40 mt-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-lg-12">
            <div class="card custom-card overflow-hidden">
                <div class="card-header border-bottom-0 pb-0">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <h3 class="card-title">
                            <i class="fe fe-list mr-2"></i> إدارة المستفيدين
                        </h3>
                        <a href="{{ route('users.create') }}" class="btn btn-primary btn-pill">
                            <i class="fe fe-plus mr-1"></i> إضافة مستفيد جديد
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Modern Filter Panel -->
                    <div class="bg-light p-4 rounded-lg mb-5 border">
                        <div class="row align-items-end g-3">
                            <div class="col-xl-2 col-md-4">
                                <label class="form-label font-weight-bold">حالة المستفيد</label>
                                <select id="status_filter" class="form-control select2">
                                    <option value="">الكل</option>
                                    <option value="new" {{ ($selectedStatus ?? null) === 'new' ? 'selected' : '' }}>جديد</option>
                                    <option value="accepted" {{ ($selectedStatus ?? null) === 'accepted' ? 'selected' : '' }}>مقبول</option>
                                    <option value="preparing" {{ ($selectedStatus ?? null) === 'preparing' ? 'selected' : '' }}>قيد التنفيذ</option>
                                    <option value="refused" {{ ($selectedStatus ?? null) === 'refused' ? 'selected' : '' }}>مرفوض</option>
                                </select>
                            </div>
                            <div class="col-xl-2 col-md-4">
                                <label class="form-label font-weight-bold">الحالة الاجتماعية</label>
                                <select id="social_status" class="form-control select2">
                                    <option value="">الكل</option>
                                    <option value="0">أعزب</option>
                                    <option value="1">متزوج</option>
                                    <option value="2">مطلق</option>
                                    <option value="3">أرمل</option>
                                </select>
                            </div>
                            <div class="col-xl-2 col-md-4">
                                <label class="form-label font-weight-bold">تصنيف المستفيد</label>
                                <select id="category_filter" class="form-control select2">
                                    <option value="">الكل</option>
                                    @foreach ($beneficiaryCategories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xl-2 col-md-4">
                                <label class="form-label font-weight-bold">الإعانة الشهرية</label>
                                <select id="monthly_subvention_filter" class="form-control select2">
                                    <option value="">الكل</option>
                                    <option value="1">له إعانة</option>
                                    <option value="0">ليس له إعانة</option>
                                </select>
                            </div>
                            <div class="col-xl-2 col-md-4">
                                <button type="button" class="btn btn-outline-danger btn-block" id="resetFilters">
                                    <i class="fe fe-refresh-ccw mr-1"></i> إعادة تعيين
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-vcenter text-wrap dataTable no-footer border-top-0 w-100" id="dataTable">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th class="border-bottom-0">#</th>
                                    <th class="border-bottom-0">المستفيد</th>
                                    <th class="border-bottom-0">إعانة شهرية</th>
                                    <th class="border-bottom-0">مبلغ الإعانة</th>
                                    <th class="border-bottom-0">الدخل / المصاريف / المستوى</th>
                                    <th class="border-bottom-0 text-center">الحالة</th>
                                    <th class="border-bottom-0 text-center">تحديث الحالة</th>
                                    <th class="border-bottom-0 text-center">العمليات</th>
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
                    <form id="delete_form" action="{{ route('delete_users') }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="modal-body">
                            <input id="delete_id" name="id" type="hidden">
                            <p>هل انت متأكد من حذف البيانات التالية <span id="title" class="text-danger font-weight-bold"></span>؟</p>
                            <p class="text-muted small">هذا الإجراء لا يمكن التراجع عنه.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-dismiss="modal" id="dismiss_delete_modal">إغلاق</button>
                            <button type="button" class="btn btn-danger" id="delete_btn">حذف نهائي</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Details MODAL -->
        <div class="modal fade" id="editOrCreate" data-backdrop="static" tabindex="-1"
            role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document" style="max-width: 96vw; width: 96vw;">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="example-Modal3 shadow-none">تفاصيل المستفيد</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-0" id="modal-body">
                        <!-- Content loaded via AJAX -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin/layouts/myAjaxHelper')
@endsection

@section('ajaxCalls')
    <script>
        $(document).ready(function() {
            // Initialize Stats from Blade
            $('#total_users_count').text('{{ number_format($totalUsers) }}');
            $('#accepted_users_count').text('{{ number_format($acceptedUsers) }}');
            $('#preparing_users_count').text('{{ number_format($preparingUsers) }}');
            $('#refused_users_count').text('{{ number_format($refusedUsers) }}');

            // Define columns configuration
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
                    data: 'beneficiary',
                    name: 'husband_name'
                },
                {
                    data: 'monthlySubventionToggle',
                    name: 'has_monthly_subvention',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'monthly_subvention_amount',
                    name: 'monthly_subvention_amount'
                },
                {
                    data: 'financials',
                    name: 'gross_income',
                    searchable: false
                },
                {
                    data: 'status',
                    name: 'status',
                    className: 'text-center'
                },
                {
                    data: 'statusChange',
                    name: 'statusChange',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center'
                }
            ];

            // Initialize table variable
            var table;
            var tableUrl = "{{ route('users.index') }}";

            // Function to initialize/reinitialize DataTable
            function initDataTable() {
                // Destroy existing table if it exists
                if ($.fn.DataTable.isDataTable('#dataTable')) {
                    var state = table.state();
                    localStorage.setItem('DataTable_state', JSON.stringify(state));
                    table.destroy();
                }

                // Initialize DataTable
                table = $('#dataTable').DataTable({
                    processing: true,
                    serverSide: true,
                    stateSave: true,
                    language: {
                        "sProcessing": "جاري التحميل...",
                        "sLengthMenu": "إظهار _MENU_ سجل",
                        "sZeroRecords": "لم يتم العثور على أية نتائج",
                        "sEmptyTable": "لا توجد بيانات متاحة في هذا الجدول",
                        "sInfo": "إظهار _START_ إلى _END_ من أصل _TOTAL_ سجل",
                        "sInfoEmpty": "يعرض 0 إلى 0 من أصل 0 سجل",
                        "sInfoFiltered": "(منتقاة من مجموع _MAX_ سجل)",
                        "sSearch": "بحث عام:",
                        "oPaginate": {
                            "sFirst": "الأول",
                            "sPrevious": "السابق",
                            "sNext": "التالي",
                            "sLast": "الأخير"
                        },
                    },
                    ajax: {
                        url: tableUrl,
                        data: function(d) {
                             d.status = $('#status_filter').val();
                             d.social_status = $('#social_status').val();
                             d.beneficiary_category_id = $('#category_filter').val();
                             d.has_monthly_subvention = $('#monthly_subvention_filter').val();
                        }
                    },
                    columns: columns,
                    drawCallback: function() {
                        $('.select2').select2({
                            minimumResultsForSearch: Infinity
                        });
                    }
                });
            }

            // Initial table load
            initDataTable();

             // Filter change handler
             $('#status_filter, #social_status, #category_filter, #monthly_subvention_filter').change(function() {
                 table.ajax.reload();
             });

            $('#resetFilters').on('click', function() {
                 $('#status_filter, #social_status, #category_filter, #monthly_subvention_filter').val('').trigger('change');
                if (table) {
                    table.search('').columns().search('').ajax.reload();
                }
                $('#dataTable_filter input').val('');
            });

            // Delete button handler
            $(document).on("click", ".delete_button", function() {
                let id = $(this).data("id");
                let title = $(this).data("title");

                $("#delete_id").val(id);
                $("#title").text(title);
                $("#delete_modal").modal("show");
            });

            // Delete form submission handler
            $("#delete_btn").on("click", function() {
                $("#delete_form").submit();
            });

            // Status change handler
            $(document).on('click', '.statusBtn', function(event) {
                event.preventDefault();
                var id = $(this).data("id"),
                    status = $(this).data("status");

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
                            table.ajax.reload(null, false);
                            toastr.success(data.message);
                        } else {
                            toastr.error('هناك خطأ ما يرجي اعادة المحاولة');
                        }
                    },
                    error: function(data) {
                        toastr.error('تعذر تحديث الحالة');
                    }
                });
            });

            $(document).on('change', '.monthly-subvention-toggle', function() {
                var checkbox = $(this);
                var id = checkbox.data('id');
                var hasMonthlySubvention = checkbox.is(':checked') ? 1 : 0;

                $.ajax({
                    type: 'POST',
                    url: "{{ route('users.toggleMonthlySubvention') }}",
                    data: {
                        '_token': "{{ csrf_token() }}",
                        'id': id,
                        'has_monthly_subvention': hasMonthlySubvention,
                    },
                    success: function(data) {
                        if (data.status === true) {
                            table.ajax.reload(null, false);
                            toastr.success(data.message);
                        } else {
                            checkbox.prop('checked', !hasMonthlySubvention);
                            toastr.error('هناك خطأ ما يرجي اعادة المحاولة');
                        }
                    },
                    error: function() {
                        checkbox.prop('checked', !hasMonthlySubvention);
                        toastr.error('تعذر تحديث حالة الإعانة الشهرية');
                    }
                });
            });

            // Details modal handler
            $(document).on('click', '.detailsBtn', function() {
                var id = $(this).data('id');
                var url = "{{ route('userDetails', ':id') }}";
                url = url.replace(':id', id);
                $('#modal-body').html('<div class="p-5 text-center"><i class="fa fa-spinner fa-spin fa-2x"></i></div>');
                $('#editOrCreate').modal('show');
                $('#modal-body').load(url);
            });
        });
    </script>
@endsection
