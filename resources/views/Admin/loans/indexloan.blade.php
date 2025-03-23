
@extends('Admin.layouts.master')

@section('title')
    {{ isset($setting) ? $setting->title : '' }}
 | القروض الشخصية
@endsection
@section('page_name')
    القروض الشخصية
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 col-lg-12">
                <div class="card-body w-100">
                    <div class="row w-100"> <!-- Ensuring full width -->
                        <div class="col-12"> <!-- Making it take full width -->
                            <div class="card bg-secondary img-card box-secondary-shadow">
                                <div class="d-flex justify-content-between pr-3 pl-3 pt-3 w-100">
                                    <span class="text-white fs-30">قيمه القرض   </span>
                                    <span class="text-white fs-30"> {{$total}} EGP</span>
                                    <!-- Changed dollar icon to EGP -->
                                </div>
                                <div class="d-flex justify-content-between pr-3 pl-3 pt-3 w-100">
                                    <span class="text-white fs-30"> المدفوع </span>
                                    <span class="text-white fs-30"> {{$totalIn}} EGP <i class='fas fa-arrow-down' style='color: #63E6BE; font-size: 30px ;transform: rotate(45deg); margin-right: 20px;'></i></span>
                                    <!-- Changed dollar icon to EGP -->
                                </div>
                                <div class="d-flex justify-content-between pr-3 pl-3 pt-3 w-100">
                                    <span class="text-white fs-30"> المتبقي </span>
                                    <span class="text-white fs-30"> {{$totalOut}} EGP <i class='fas fa-arrow-up' style='color: #e42f2f; font-size: 30px ; transform: rotate(45deg);margin-right: 20px;'></i></span>
                                    <!-- Changed dollar icon to EGP -->
                                </div>

                                <div class="card-body">
                                    <div class="row text-white">
                                        <div class="col-4 text-end"> <!-- Added text-end for right alignment -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- COL END -->
                    </div><!-- ROW END -->
                </div>
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"> القروض الشخصية </h3>
                    @if($pay == 1)
                        <button class="btn btn-success btn-icon text-white loan-btn" data-id="{{$id}}" >  صرف القرض </button>
                    @endif
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered text-nowrap w-75" id="dataTable">
                            <thead>
                            <tr class="fw-bolder text-muted bg-light">
                                <th>#</th>
                                <th>اسم المقترض</th>
                                <th>رقم الهاتف</th>
                                <th>قيمة القسط</th>
                                <th>تاريخ القرض</th>
                                <th>الحالة</th>
                                <th>العمليات</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('Admin.layouts.myAjaxHelper')
@endsection

@section('ajaxCalls')
    <script>
        $(document).ready(function () {
            var borrowerId = "{{ $id }}";

            var table = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('person.loans', ':id') }}".replace(':id', borrowerId),
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'borrower_name', name: 'borrower_name' },
                    { data: 'borrower_phone', name: 'borrower_phone' },
                    { data: 'amount', name: 'amount' },
                    { data: 'month', name: 'month' },
                    { data: 'status', name: 'status' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });

            $(document).on('click', '.loan-btn', function () {
                let loanId = $(this).data('id');

                if (confirm("هل أنت متأكد من صرف هذا القرض؟")) {
                    $.ajax({
                        url: "{{ route('loan.checkout', ':id') }}".replace(':id', loanId),
                        type: 'get',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            window.location.reload();
                            alert(response.message);
                            table.ajax.reload();
                        },
                        error: function(response) {
                            alert(response.message);
                            alert(response.responseJSON.error);
                        }
                    });
                }
            });

            $(document).on('click', '.pay-btn', function () {
                let loanId = $(this).data('id');

                if (confirm("هل أنت متأكد من دفع هذا القرض؟")) {
                    $.ajax({
                        url: "{{ route('loan.pay', ':id') }}".replace(':id', loanId),
                        type: 'POST',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            window.location.reload();
                            alert(response.message);
                            table.ajax.reload();
                        },
                        error: function(response) {
                            alert(response.responseJSON.error);
                        }
                    });
                }
            });

        });
    </script>
@endsection
