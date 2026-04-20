@extends('admin/layouts/master')

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
                <div class="card-header w-100">
                    <h3 class="card-title">
                        قائمة بالمستفدين
                    </h3>

                    <div class="d-flex">

                        <div class="mr-4">
                            <a href="{{ route('users.create') }}" class="btn btn-secondary btn-icon text-white">
                                <span>
                                    <i class="fe fe-plus"></i>
                                </span> مستفيد جديد
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3 w-100">
                        <div class="col-2">
                            <label for="status_filter">حالة المستفيد</label>
                            <select id="status_filter" class="form-control">
                                <option value="">الكل</option>
                                <option value="new" {{ ($selectedStatus ?? null) === 'new' ? 'selected' : '' }}>جديد</option>
                                <option value="accepted" {{ ($selectedStatus ?? null) === 'accepted' ? 'selected' : '' }}>مقبول</option>
                                <option value="preparing" {{ ($selectedStatus ?? null) === 'preparing' ? 'selected' : '' }}>قيد الانتظار</option>
                                <option value="refused" {{ ($selectedStatus ?? null) === 'refused' ? 'selected' : '' }}>مرفوض</option>
                            </select>
                        </div>
                         <div class="col-2">
                             <label for="social_status" > الحالة الاجتماعية للمستفيد </label>
                             <select id="social_status" class="form-control">
                                 <option value="">اختر </option>
                                 <option value="0">اعزب</option>
                                 <option value="1">متزوج</option>
                                 <option value="2">مطلق</option>
                                 <option value="3">ارمل</option>
                             </select>
                         </div>
                         <div class="col-2">
                             <label for="category_filter">تصنيف المستفيد</label>
                             <select id="category_filter" class="form-control">
                                 <option value="">الكل</option>
                                 @foreach ($beneficiaryCategories as $category)
                                     <option value="{{ $category->id }}">{{ $category->name }}</option>
                                 @endforeach
                             </select>
                         </div>

                        <div class="col-2 d-flex align-items-end">
                            <button type="button" class="btn btn-outline-danger w-100" id="resetFilters">
                                <i class="fe fe-refresh-ccw"></i> مسح الفلتر
                            </button>
                        </div>
                    </div>
                    <div class="table-responsive mt-4">
                        <!--begin::Table-->
                        <table class="table table-striped table-bordered text-nowrap w-100" id="dataTable">
                            <thead>
                                <tr class="fw-bolder text-muted bg-light">
                                    <th class="min-w-25px">#</th>
                                    <th class="min-w-50px">اسم الزوج</th>
                                    <th class="min-w-50px">اسم الزوجة</th>
                                    <th class="min-w-50px" >الحالة الاجتماعية </th>
                                    <th class="min-w-50px">اجمالي الدخل</th>
                                    <th class="min-w-50px">اجمالي المصاريف</th>
                                    <th class="min-w-50px">مستوى المعيشة</th>
                                    {{-- <th class="min-w-50px">اجمالي التبرعات </th> --}}
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
                    <form id="delete_form" action="{{ route('delete_users') }}" method="POST">
                        @csrf
                        @method('DELETE')
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
                    </form>
                </div>
            </div>
        </div>
        <!-- MODAL CLOSED -->


        <!-- Edit MODAL -->
        <div class="modal fade bd-example-modal-lg" id="editOrCreate" data-backdrop="static" tabindex="-1"
            role="dialog" aria-hidden="true">
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
    @include('admin/layouts/myAjaxHelper')
@endsection
@section('ajaxCalls')
    <script>
        $(document).ready(function() {
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
                }
            ];

            // Initialize table variable
            var table;
            var tableUrl = "{{ route('users.index') }}";

            // Function to initialize/reinitialize DataTable
            function initDataTable() {
                // Destroy existing table if it exists
                if ($.fn.DataTable.isDataTable('#dataTable')) {
                    // Save current state before destroying
                    var state = table.state();
                    localStorage.setItem('DataTable_state', JSON.stringify(state));
                    table.destroy();
                }

                // Initialize DataTable
                table = $('#dataTable').DataTable({
                    processing: true,
                    serverSide: true,
                    stateSave: true, // Enable DataTables state saving
                    ajax: {
                        url: tableUrl,
                        data: function(d) {
                             d.status = $('#status_filter').val();
                             d.social_status = $('#social_status').val();
                             d.beneficiary_category_id = $('#category_filter').val();
                             d.standard_living = $('#standard_living').val();
                             d.family_number = $('#family_number').val();
                        }
                    },
                    columns: columns,
                    initComplete: function() {
                        // Load saved state if exists
                        var savedState = localStorage.getItem('DataTable_state');
                        if (savedState) {
                            var state = JSON.parse(savedState);
                            table.columns().search(state.search.search);
                            table.page(state.start / state.length).draw(false);
                        }
                    }
                });

                // Save state on various events
                table.on('stateSaveParams', function(e, settings, data) {
                    localStorage.setItem('DataTable_state', JSON.stringify(data));
                });
            }

            // Initial table load
            initDataTable();

             // Filter change handler
             $('#status_filter, #social_status, #category_filter, #standard_living, #family_number').change(function() {
                 table.ajax.reload();
             });

            $('#resetFilters').on('click', function() {
                 $('#status_filter').val('').trigger('change');
                 $('#social_status').val('').trigger('change');
                 $('#category_filter').val('').trigger('change');
                 $('#standard_living').val('');
                 $('#family_number').val('');

                if (table) {
                    table.search('');
                    table.columns().search('');
                    table.ajax.reload();
                }

                $('#dataTable_filter input').val('');
            });

            // Delete button handler
            $(".delete_button").on("click", function() {
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
                            table.ajax.reload(null,
                                false); // Reload without resetting pagination
                            toastr.success(data.message);
                        } else {
                            toastr.error('هناك خطأ ما يرجي اعادة المحاولة');
                        }
                    },
                    error: function(data) {
                        toastr.error(data.message);
                    }
                });
            });

            // Details modal handler
            $(document).on('click', '.detailsBtn', function() {
                var id = $(this).data('id');
                var url = "{{ route('userDetails', ':id') }}";
                url = url.replace(':id', id);
                $('#modal-body').html(loader);
                $('#editOrCreate').modal('show');
                setTimeout(function() {
                    $('#modal-body').load(url);
                }, 500);
            });
        });
    </script>
@endsection
