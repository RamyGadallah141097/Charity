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
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="card-title mb-0">عمليات صرف التبرعات العينية</h3>
                    <a href="{{ route('in-kind-disbursements.create') }}" class="btn btn-primary">
                        <i class="fe fe-plus ml-1"></i> صرف تبرع عيني
                    </a>
                </div>
                <div class="card-body">
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
                                </tr>
                            </thead>
                        </table>
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
            $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('in-kind-disbursements.index') }}",
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
                ]
            });
        });
    </script>
@endsection
