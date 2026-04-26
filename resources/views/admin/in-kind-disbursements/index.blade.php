@extends('admin/layouts/master')

@section('title')
    {{ isset($setting->title) ? $setting->title : '' }} | صرف التبرعات العينية
@endsection

@section('page_name')
    صرف التبرعات العينية
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <style>
                .subventions-summary-card {
                    border: 0;
                    border-radius: 22px;
                    overflow: hidden;
                    background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 55%, #60a5fa 100%);
                    box-shadow: 0 18px 45px rgba(37, 99, 235, 0.18);
                }

                .subventions-summary-card__body {
                    padding: 26px 30px;
                    color: #fff;
                }

                .subventions-summary-card__eyebrow {
                    font-size: 14px;
                    opacity: 0.9;
                    margin-bottom: 10px;
                }

                .subventions-summary-card__title {
                    font-size: 28px;
                    font-weight: 800;
                    margin-bottom: 24px;
                }

                .subventions-summary-tile {
                    background: rgba(255, 255, 255, 0.12);
                    border: 1px solid rgba(255, 255, 255, 0.16);
                    border-radius: 18px;
                    padding: 18px 20px;
                }

                .subventions-summary-tile__label {
                    font-size: 15px;
                    opacity: 0.92;
                    margin-bottom: 8px;
                }

                .subventions-summary-tile__value {
                    font-size: 34px;
                    font-weight: 800;
                    line-height: 1.1;
                }
            </style>

            <div class="card subventions-summary-card mb-4">
                <div class="subventions-summary-card__body">
                    <div class="subventions-summary-card__eyebrow">ملخص الصرف العيني</div>
                    <div class="subventions-summary-card__title">{{ $periodLabel }}</div>
                    <div class="subventions-summary-tile">
                        <div class="subventions-summary-tile__label">إجمالي المنصرف</div>
                        <div class="subventions-summary-tile__value">{{ number_format($totalItems, 0) }}</div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="card-title mb-0">عمليات صرف التبرعات العينية</h3>
                    <a href="{{ route('in-kind-disbursements.create') }}" class="btn btn-primary">
                        <i class="fe fe-plus ml-1"></i> صرف تبرع عيني
                    </a>
                </div>
                <div class="card-body">
                    <form class="mb-5" method="GET" action="{{ route('in-kind-disbursements.index') }}">
                        <div class="input-group">
                            <label class="input-group-text">من تاريخ</label>
                            <input style="margin-left:20px" type="date" name="from" class="form-control"
                                value="{{ $fromDate?->format('Y-m-d') }}" placeholder="من تاريخ">
                            <label class="input-group-text">الى تاريخ</label>
                            <input style="margin-left:20px" type="date" name="to" class="form-control"
                                value="{{ $toDate?->format('Y-m-d') }}" placeholder="الى تاريخ">
                            <button type="submit" class="btn btn-primary">بحث</button>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered text-nowrap w-100" id="dataTable">
                            <thead>
                                <tr class="fw-bolder text-muted bg-light">
                                    <th>#</th>
                                    <th>كود المستفيد</th>
                                    <th>المستفيد</th>
                                    <th>صنف التبرع</th>
                                    <th>الكمية</th>
                                    <th>ملاحظات</th>
                                    <th>التاريخ</th>
                                    <th>العمليات</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete MODAL -->
    <div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">حذف بيانات</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('in-kind-disbursements.delete') }}" method="post">
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
    @include('admin/layouts/myAjaxHelper')
@endsection

@section('ajaxCalls')
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('in-kind-disbursements.index', ['from' => $fromDate->format('Y-m-d'), 'to' => $toDate->format('Y-m-d')]) }}",
                columns: [
                    {
                        data: null,
                        name: 'index',
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        },
                        orderable: false,
                        searchable: false
                    },
                    { data: 'beneficiary_code', name: 'beneficiary_code' },
                    { data: 'beneficiary_name', name: 'beneficiary_name' },
                    { data: 'category_name', name: 'category_name' },
                    { data: 'quantity', name: 'quantity' },
                    { data: 'comment', name: 'comment' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });
            deleteScript('{{ route('in-kind-disbursements.delete') }}');
        });
    </script>
@endsection
