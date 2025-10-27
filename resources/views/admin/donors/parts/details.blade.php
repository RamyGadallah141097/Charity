@extends('admin/layouts/master')


@section('page_name')
    المتبرعين
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
                /*    // make image in semi center */
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


        <h3>بيانات المتبرع</h3>
        <div class="table-responsive">
            <table class="table table-striped table-bordered w-100">
                <thead>
                    <tr class="fw-bolder text-muted bg-light">
                        <th class="min-w-25px">الاسم </th>
                        <th class="min-w-25px">رقم الهاتف </th>
                        <th class="min-w-25px">العنوان </th>
                        <th class="min-w-25px">تاريخ الميلاد </th>
                        <th class="min-w-25px">ملاحظات </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $donor->name }}</td>
                        <td>{{ $donor->phone }}</td>
                        <td>{{ $donor->address }}</td>
                        <td>{{ $donor->burn_date }}</td>
                        <td>{{ $donor->notes }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered w-100">
                <thead>
                    <div class="card">

                        <div class="card-body">
                            <h3> التبرعات</h3>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered w-100">
                                    <thead>
                                        <tr class="fw-bolder text-muted bg-light">
                                            <th class="min-w-25px"> # </th>
                                            <th class="min-w-25px"> تاريخ التبرع</th>
                                            <th class="min-w-25px">نوع التبرع </th>
                                            <th class="min-w-25px"> مبلغ التبرع </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                </thead>
                <tbody>
                    @foreach ($donations as $donation)
                        <tr>
                            <td>{{ $donation->id }}</td>
                            <td>{{ $donation->created_at }}</td>
                            <td>
                                @if ($donation->donation_type == 0)
                                    زكاة مال
                                @elseif ($donation->donation_type == 1)
                                    صدقات
                                @elseif ($donation->donation_type == 2)
                                    قرض حسن
                                @else
                                    تبرع عيني
                                @endif
                            </td>
                            <td>{{ $donation->donation_amount }}</td>
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
