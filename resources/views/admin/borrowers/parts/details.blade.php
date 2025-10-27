@extends('admin/layouts/master')


@section('page_name')
    المقترضين
@endsection


@section('content')
    <div class="card bg-white p-3 shadow-sm">


        <style>
            .gallery-image {
                cursor: pointer;
                transition: 0.3s;
                margin: 5px;
            }

            .gallery-image:hover {
                opacity: 0.7;
            }

            .modal {
                display: none;
                position: fixed;
                z-index: 1000;
                padding-top: 100px;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                overflow: auto;
                background-color: rgba(0, 0, 0, 0.9);
            }


            .modal-content {
                margin: auto;
                display: block;
                max-width: 100%;
                max-height: 100%;
                top: 20%;

            }


            .close {
                position: absolute;
                top: 15px;
                right: 35px;
                color: #f1f1f1;
                font-size: 40px;
                font-weight: bold;
                transition: 0.3s;
            }

            .close:hover,
            .close:focus {
                color: #bbb;
                text-decoration: none;
                cursor: pointer;
            }
        </style>


        <h3>بيانات المقترض</h3>
        <div class="table-responsive">
            <table class="table table-striped table-bordered w-100">
                <thead>
                    <tr class="fw-bolder text-muted bg-light">
                        <th class="min-w-25px">الاسم </th>
                        <th class="min-w-25px">رقم الهاتف </th>
                        <th class="min-w-25px">المراجعة </th>
                        <th class="min-w-25px">تاريخ الميلاد </th>
                        <th class="min-w-25px"> الرقم القومى </th>
                        <th class="min-w-25px"> العنوان </th>
                        <th class="min-w-25px"> التقييم </th>
                        <th class="min-w-25px"> الوظيفة </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $borrower->name }}</td>
                        <td>{{ $borrower->phone }}</td>
                        <td>{{ $borrower->review }}</td>
                        <td>{{ $borrower->borrower_age }}</td>
                        <td>{{ $borrower->nationalID }}</td>
                        <td>{{ $borrower->address }}</td>
                        <td>{{ $borrower->rate . ' نجوم' }}</td>
                        <td>{{ $borrower->job }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered w-100">
                <thead>
                    <div class="card">

                        <div class="card-body">
                            <h3> القروض</h3>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered w-100">
                                    <thead>
                                        <tr class="fw-bolder text-muted bg-light">
                                            <th class="min-w-25px"> تاريخ القرض</th>
                                            <th class="min-w-25px"> المبلغ </th>
                                            <th class="min-w-25px"> نوع القرض </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                </thead>
                <tbody>
                    @foreach ($loans as $loan)
                        <tr>
                            <td>{{ $loan->loan_date }}</td>
                            <td>{{ $loan->loan_amount }}</td>
                            <td>
                                @if ($loan->type == 0)
                                    قرض عادى
                                @else
                                    قرض جمعية
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>


        <div class="table-responsive">
            <table class="table table-striped table-bordered w-100">
                <thead>
                    <div class="card">

                        <div class="card-body">
                            <h3> الضامنين</h3>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered w-100">
                                    <thead>
                                        <tr class="fw-bolder text-muted bg-light">
                                            <th class="min-w-25px"> اسم الضامن</th>
                                            <th class="min-w-25px"> رقم الهاتف </th>
                                            <th class="min-w-25px"> الرقم القومى </th>
                                            <th class="min-w-25px"> العنوان </th>
                                            <th class="min-w-25px"> الوظيفة </th>
                                            <th class="min-w-25px"> عمر الضامن </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                </thead>
                <tbody>
                    @foreach ($guarantors as $guarantor)
                        <tr>
                            <td>{{ $guarantor->name }}</td>
                            <td>{{ $guarantor->phone }}</td>
                            <td>{{ $guarantor->nationalID }}</td>
                            <td>{{ $guarantor->address }}</td>
                            <td>{{ $guarantor->job }}</td>
                            <td>{{ $guarantor->guarantorAge }}</td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>


        <script>
            function openModal(imageSrc) {
                document.getElementById('modalImage').src = imageSrc;
                document.getElementById('imageModal').style.display = "block";
            }

            function closeModal() {
                document.getElementById('imageModal').style.display = "none";
            }


            window.onclick = function(event) {
                const modal = document.getElementById('imageModal');
                if (event.target == modal) {
                    closeModal();
                }
            }
        </script>
    </div>
@endsection
