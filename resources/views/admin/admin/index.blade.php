@extends('admin/layouts/master')

@section('title')
    {{ isset($setting) ? $setting->title : '' }}
    | المشرفين
@endsection
@section('page_name')
    المشرفين
@endsection
@section('content')
    <style>
        #editOrCreate .modal-dialog {
            max-width: 1100px;
            width: min(1100px, 95vw);
            margin: 1.75rem auto;
        }

        #editOrCreate .modal-content {
            border: 0;
            border-radius: 22px;
            overflow: hidden;
            box-shadow: 0 24px 60px rgba(16, 24, 40, 0.18);
        }

        #editOrCreate .modal-header {
            padding: 1.15rem 1.5rem;
            border-bottom: 1px solid #eef2ff;
            background: linear-gradient(135deg, #f8fbff 0%, #eef4ff 100%);
        }

        #editOrCreate .modal-title {
            font-size: 1.35rem;
            font-weight: 700;
            color: #243b6b;
        }

        #editOrCreate .close {
            margin: 0;
            padding: 0;
            font-size: 2rem;
            color: #7183a6;
            opacity: 1;
        }

        #editOrCreate .modal-body {
            padding: 0;
            background: #f7f9fc;
            max-height: calc(100vh - 120px);
            overflow-y: auto;
        }

        #editOrCreate .management-form {
            padding: 1.5rem;
        }

        #editOrCreate .management-form .form-section {
            background: #fff;
            border: 1px solid #edf1f7;
            border-radius: 18px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.04);
        }

        #editOrCreate .management-form .section-title {
            margin-bottom: 1rem;
            font-size: 1rem;
            font-weight: 700;
            color: #2c4a86;
        }

        #editOrCreate .management-form label {
            font-weight: 700;
            color: #344767;
            margin-bottom: .45rem;
        }

        #editOrCreate .management-form .form-control,
        #editOrCreate .management-form .select2-container--default .select2-selection--single,
        #editOrCreate .management-form .select2-container--default .select2-selection--multiple {
            min-height: 46px;
            border-radius: 14px;
            border-color: #d9e2f2;
        }

        #editOrCreate .management-form textarea.form-control {
            min-height: 96px;
        }

        #editOrCreate .management-form .select2-container .select2-selection--single .select2-selection__rendered {
            line-height: 44px;
        }

        #editOrCreate .management-form .select2-container .select2-selection--single .select2-selection__arrow {
            height: 44px;
        }

        #editOrCreate .management-form .custom-switch {
            padding: .85rem 1rem;
            width: 100%;
            background: #fff;
            border: 1px solid #edf1f7;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.04);
        }

        #editOrCreate .management-form .custom-switch-description {
            font-weight: 700;
            color: #243b6b;
        }

        #editOrCreate .management-form .modal-footer {
            border-top: 0;
            padding: 1.25rem 0 0;
        }

        #editOrCreate .management-form .dropify-wrapper {
            border-radius: 18px;
            border-color: #d9e2f2;
        }

        #editOrCreate .management-documents-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
            gap: 12px;
        }

        #editOrCreate .management-document-card {
            display: block;
            height: 130px;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #d9e2f2;
            background: #f8fbff;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.05);
            transition: transform .2s ease, box-shadow .2s ease;
        }

        #editOrCreate .management-document-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 30px rgba(15, 23, 42, 0.09);
        }

        #editOrCreate .management-document-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        #editOrCreate .management-document-file {
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 10px;
            color: #2c4a86;
            font-weight: 700;
            background: linear-gradient(135deg, #eef4ff 0%, #f9fbff 100%);
        }

        #editOrCreate .management-document-file i {
            font-size: 2rem;
        }

        @media (max-width: 767.98px) {
            #editOrCreate .modal-dialog {
                width: calc(100vw - 16px);
                max-width: calc(100vw - 16px);
                margin: .5rem auto;
            }

            #editOrCreate .management-form {
                padding: .85rem;
            }

            #editOrCreate .management-form .form-section {
                padding: 1rem;
                border-radius: 14px;
            }
        }
    </style>
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ isset($setting) ? $setting->title : '' }}</h3>
                    <div class="">
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
                        <table class="table table-striped table-bordered text-nowrap w-100" id="dataTable">
                            <thead>
                                <tr class="fw-bolder text-muted bg-light">
                                    <th class="min-w-25px">#</th>
                                    <th class="min-w-50px">الصورة</th>
                                    <th class="min-w-50px">الاسم</th>
                                    <th class="min-w-125px">المنصب</th>
                                    <th class="min-w-125px">الهاتف</th>
                                    <th class="min-w-100px">مستخدم للنظام</th>
                                    <th class="min-w-125px">الايميل</th>
                                    <th class="min-w-175px">مكان السكن</th>
                                    <th class="min-w-125px">القواعد</th>
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
                    <form action="{{ route('delete_admin') }}" method="post">
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
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="example-Modal3">بيانات المشرف</h5>
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
                data: 'image',
                name: 'image'
            },
            {
                data: 'name',
                name: 'name'
            },
            {
                data: 'job_title',
                name: 'job_title'
            },
            {
                data: 'phone',
                name: 'phone'
            },
            {
                data: 'system_user',
                name: 'system_user'
            },
            {
                data: 'email',
                name: 'email'
            },
            {
                data: 'location',
                name: 'location'
            },
            {
                data: 'select_role',
                name: 'select_role'
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
        ]
        showData('{{ route('admins.index') }}', columns);
        // Delete Using Ajax
        deleteScript('{{ route('delete_admin') }}');
        // Add Using Ajax
        showAddModal('{{ route('admins.create') }}');
        addScript();
        // Add Using Ajax
        showEditModal('{{ route('admins.edit', ':id') }}');
        editScript();
    </script>
    <!-- test uploading the storage  -->
@endsection
