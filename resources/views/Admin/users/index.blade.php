@extends('Admin/layouts/master')

@section('title')
    {{ isset($setting) ? $setting->title : '' }}
 | المستفيدين
@endsection

@section('page_name')
    المستفيدين
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        قائمة بالمستفدين من {{ isset($setting) ? $setting->title : '' }}

                    </h3>
                    <div class="row">
                        <div class="col-3 m-3">
                            <select class="form-control" id="filterNStatus">
                                <option value="">الحاله الاجتماعيه</option>
                                <option value="0">أعزب</option>
                                <option value="1">متزوج</option>
                                <option value="2">مطلق</option>
                                <option value="3">أرمل</option>
                            </select>
                        </div>

                        <div class="col-3 m-3">
                            <select class="form-control" id="filterLifeLevel">
                                <option value="">مستوي المعيشه</option>
                                <option value="500">500</option>
                                <option value="1000">1000</option>
                                <option value="2000">2000</option>
                                <option value="3000">3000</option>
                                <option value="4000">4000</option>
                                <option value="5000">5000</option>
                                <option value="6000">6000</option>
                            </select>
                        </div>

                        <div class="col-3 m-3">
                            <select class="form-control" id="filterFamilyNumber">
                                <option value="">عدد الاطفال</option>
                                @for($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <a href="{{ route('users.create') }}" class="btn btn-secondary btn-icon text-white">
                        <span><i class="fe fe-plus"></i></span> اضافة مستفيد
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered text-nowrap w-100" id="dataTable">
                            <thead>
                            <tr class="fw-bolder text-muted bg-light">
                                <th>#</th>
                                <th>اسم الزوج</th>
                                <th>اسم الزوجة</th>
                                <th>الحالة الاجتماعية</th>
                                <th>الهاتف</th>
                                <th>اجمالي الدخل</th>
                                <th>اجمالي المصاريف</th>
                                <th>مستوى المعيشة</th>
                                <th>الحالة</th>
                                <th>تحديث</th>
                                <th>العمليات</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('Admin/layouts/myAjaxHelper')
@endsection

@section('ajaxCalls')
    <script>
        $(document).ready(function () {
            var status = "{{ request()->segment(3) }}";

            var table = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('users.index', ':status') }}".replace(':status', status),
                    type: "GET",  // Make sure request type matches your route
                    data: function (d) {
                        d.social_status = $('#filterNStatus').val() || null;
                        d.standard_living = $('#filterLifeLevel').val() || null;
                        d.family_number = $('#filterFamilyNumber').val() || null;
                    },
                    error: function(xhr) {
                        console.log("Error:", xhr.responseText); // Debugging
                    }
                },

                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'husband_name', name: 'husband_name' },
                    { data: 'wife_name', name: 'wife_name' },
                    { data: 'social_status', name: 'social_status' },
                    { data: 'nearest_phone', name: 'nearest_phone' },
                    { data: 'gross_income', name: 'gross_income' },
                    { data: 'gross_expenses', name: 'gross_expenses' },
                    { data: 'standard_living', name: 'standard_living' },
                    { data: 'status', name: 'status' },
                    { data: 'statusChange', name: 'statusChange' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ]
            });

            $('#filterNStatus, #filterLifeLevel, #filterFamilyNumber').on('change', function () {
                table.ajax.reload(null, false);
            });
        });
    </script>


    <script>
        $(document).ready(function () {
            $(".statusBtn").on("click", function (e) {
                // e.preventDefault();

                console.log("asd")
                let userId = $(this).data("id");
                let status = $(this).data("status");
                let url = "{{ route('updateUserStatus') }}";

                $.ajax({
                    url: url,
                    type: "POST",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'), // جلب CSRF Token
                        id: userId,
                        status: status
                    },
                    success: function (response) {
                        alert("تم تحديث الحالة بنجاح!");
                        location.reload();
                    },
                    error: function (xhr) {
                        alert("حدث خطأ، الرجاء المحاولة مرة أخرى.");
                    }
                });
            });
        });
    </script>

@endsection
