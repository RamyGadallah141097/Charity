@extends('Admin/layouts/master')

@section('title')
    {{ $setting->title ?? '' }} | القروض الحسنة
@endsection
@section('page_name')
    القروض الحسنة
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="p-3">
                    <h3 class="card-title"> القروض الحسنة {{ $setting->title ?? '' }}</h3>

                    <div class="card-body w-100">
                        <div class="row w-100"> <!-- Ensuring full width -->
                            <div class="col-12"> <!-- Making it take full width -->
                                <div class="card bg-secondary img-card box-secondary-shadow">
                                    <div class="d-flex justify-content-between pr-3 pl-3 pt-3 w-100">
                                        <span class="text-white fs-30"> خزنة القروض الحسنة </span>
                                        <span class="text-white fs-30">{{ $loans }} EGP</span>
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
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table table-striped table-bordered text-nowrap w-75" id="dataTable">
                            <thead>
                                <tr class="fw-bolder text-muted bg-light">
                                    <th class="min-w-25px">#</th>
                                    <th class="min-w-50px"> الاسم المتبرع</th>
                                    <th class="min-w-125px">تاريخ التبرع</th>
                                    <th class="min-w-125px">قيمه التبرع </th>
                                    <th class="min-w-125px">العمليات </th>
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
                    <form action="{{ route('donations_delete') }}" method="post">
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
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="example-Modal3">بيانات المتبرع</h5>
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

        <!-- Modal for view all donations of the donor !!!!!! -->
        <div class="modal fade" id="donationsModal" tabindex="-1" aria-labelledby="donationsModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="donationsModalLabel">تفاصيل التبرعات</h5>
                        <button type="button" class="btn btn-danger" id="forceCloseModal"><i class="fa fa-window-close"></i></button>
                    </div>

                    <div class="card-body w-100">
                        <div class="row w-100">
                            <div class="col-12">
                                <div class="card bg-secondary img-card box-secondary-shadow">
                                    <div class="d-flex justify-content-between pr-3 pl-3 pt-3 w-100">
                                        <span class="text-white fs-20">  مجموع التبرعات  </span>
                                        <span class="text-white fs-20"><p id="total_amount"></p> </span>
                                    </div>
                                </div>
                    <div class="modal-body" id="donationsModalBody">
                        <div class="total_amount">
                            <div class="card-body w-100">
                                <div class="row w-100"> <!-- Ensuring full width -->
                                    <div class="col-12"> <!-- Making it take full width -->
                                        <div class="card bg-secondary img-card box-secondary-shadow">
                                            <div class="d-flex justify-content-between pr-3 pl-3 pt-3 w-100">
                                                <span class="text-white fs-30"> خزنة القروض الحسنة </span>
                                                <span class="text-white fs-30">${total} EGP</span>
                                                <!-- Changed dollar icon to EGP -->
                                            </div>

                                            <div class="card-body">
                                                <div class="row text-white">
                                                    <div class="col-4 text-end">
                                                        <!-- Added text-end for right alignment -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!-- COL END -->
                                </div><!-- ROW END -->
                            </div>
                        </div>
                    </div>
                    <div class="modal-body" id="donationsModalBody">
                        <hr>
                        <table class="table">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">المبلغ</th>
                                <th scope="col">التاريخ</th>
                            </tr>
                            </thead>
                            <tbody id="donationsModalBodyTable">

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
        <!-- Modal for view all donations of the donor !!!!!! -->

    <!-- Modal for view all donations of the donor !!!!!! -->

    <!-- Create Or Edit Modal -->

    </div>
    @include('Admin/layouts/myAjaxHelper')
@endsection
@section('ajaxCalls')
    <script>
        var columns = [{
                data: 'id',
                name: 'id'
            },
            {
                data: 'donor_name',
                name: 'donor_name'
            },
            {
                data: 'created_at',
                name: 'created_at'
            },
            {
                data: 'donation_amount',
                name: 'donation_amount'
            },

            {
                data: 'operations',
                name: 'operations'
            },
        ]
        showData('{{ route('indexLoansDonations') }}', columns);
    </script>



    <script>
        $(document).ready(function() {
            $(document).on('click', '.view-donations', function() {
                let donorId = $(this).data('donor');
                let total = $(this).data('total');

                $.ajax({
                    url: "{{ route('getDonors') }}",
                    type: "GET",

                    data: { donor_id: donorId },
                    success: function (response) {
                        let modalBody = $('#donationsModalBodyTable');
                    data: {
                        donor_id: donorId
                    },
                    success: function(response) {
                        let modalBody = $('#donationsModalBody');
                        modalBody.empty();
                        if (response.length > 0) {
                            response.forEach(function(donation, index) {
                                modalBody.append(
                                    `

                                            <tr>
                                              <th scope="row">${index+1}</th>
                                              <td>${donation.amount}</td>
                                              <td> ${donation.date}</td>
                                            </tr>
                                    `
                                    <table class="table">
                                      <thead class="thead-light">
                                        <tr>
                                          <th scope="col">${index+1}</th>
                                          <th scope="col">المبلغ</th>
                                          <th scope="col">${donation.amount}</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <tr>
                                          <th scope="row"> </th>
                                          <td>التاريخ</td>
                                          <td> ${donation.date}</td>
                                        </tr>
                                      </tbody>
                                    </table>   `
                                );
                                $("#total_amount").text(total);
                            });
                        } else {
                            modalBody.append('<p>لا يوجد تبرعات</p>');
                        }
                        $('#donationsModal').modal('show');
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#forceCloseModal').click(function() {
                $('#donationsModal').modal('hide');
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
            });
        });

    </script>

@endsection
