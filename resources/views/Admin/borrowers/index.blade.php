@extends('Admin/layouts/master')

@section('title')
    {{ $setting->title ?? '' }} | القروض الحسنة
@endsection
@section('page_name')
    القروض الحسنة
@endsection
@section('content')
    <style>
        .modal-xl {
            max-width: 90% !important; /* Make modal 90% of the screen */
        }

        .modal-body {
            max-height: 80vh; /* Allow better scrolling */
            overflow-y: auto;
        }
    </style>
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
               @foreach($errors->all() as $error)
                   {{$error}}
               @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="p-3">
                    <h3 class="card-title">المقترضين من  القروض الحسنة  {{ $setting->title ?? '' }}</h3>

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
                        <table class="table table-striped table-bordered text-nowrap w-75" id="dataTable">
                            <thead>
                            <tr class="fw-bolder text-muted bg-light">
                                <th class="min-w-25px">#</th>
                                <th class="min-w-50px"> الاسم المقترض</th>
                                <th class="min-w-125px">رقم المقترض</th>
                                <th class="min-w-125px">الرقم القومي للمقترض  </th>
                                <th class="min-w-125px">عنوان المقترض  </th>
                                <th class="min-w-125px">عمل  المقترض  </th>
                                <th class="min-w-125px">  العمليات  </th>
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
                    <form action="{{route("delete_borrowers")}}" method="post" >
                        @csrf
                        @method("post")
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
        <!-- Guarantor Details Modal -->
        <div class="modal fade" id="guarantorModal" tabindex="-1" aria-labelledby="guarantorModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl"> <!-- Change modal size here -->
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="guarantorModalLabel">تفاصيل الكفلاء</h5>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">
                            <i class="fa fa-window-close"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table">
                            <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>الاسم</th>
                                <th>الهاتف</th>
                                <th>الرقم القومي</th>
                                <th>العنوان</th>
                                <th>العمل</th>
                            </tr>
                            </thead>
                            <tbody id="guarantorModalBodyTable"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for view all donations of the donor !!!!!! -->


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
                data: 'name',
                name: 'name'
            },
            {
                data: 'phone',
                name: 'phone'
            },
            {
                data: 'nationalID',
                name: 'nationalID'
            },
            {
                data: 'phone',
                name: 'phone'
            },
            {
                data: 'address',
                name: 'address'
            },
            {
                data: 'action',
                name: 'action'
            },

        ]
        showData('{{ route('borrowers.index') }}', columns);

        deleteScript('{{route('delete_borrowers')}}');

        // Add Using Ajax
        showAddModal('{{route('borrowers.create')}}');
        addScript();
        // Add Using Ajax
        showEditModal('{{route('borrowers.edit',':id')}}');
        editScript();
    </script>


<script>
    $(document).ready(function () {
        $(document).on('click', '.view-guarantors', function () {
            let borrower_id = $(this).data('id'); // Correct parameter

            $.ajax({
                url: "{{ route('getGuarantor') }}",
                type: "GET",
                data: { borrower_id: borrower_id }, // Correct parameter name
                success: function (response) {
                    let modalBody = $('#guarantorModalBodyTable'); // Correct modal
                    modalBody.empty();

                    if (response.length > 0) {
                        response.forEach(function (guarantor, index) {
                            modalBody.append(
                                `<tr>
                                <th scope="row">${index + 1}</th>
                                <td>${guarantor.name}</td>
                                <td>${guarantor.phone}</td>
                                <td>${guarantor.nationalID}</td>
                                <td>${guarantor.address}</td>
                                <td>${guarantor.job}</td>
                            </tr>`
                            );
                        });
                    } else {
                        modalBody.append('<p class="text-center text-danger">لا يوجد كفلاء</p>');
                    }

                    $('#guarantorModal').modal('show'); // Show correct modal
                },
                error: function () {
                    alert('حدث خطأ أثناء جلب بيانات الكفيل');
                }
            });
        });
    });

</script>

    <script>
        $(document).ready(function() {
            @if ($errors->any())
            $('#borrowerModal').modal('show');
            @endif
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

