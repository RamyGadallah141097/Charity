@php use Carbon\Carbon; @endphp
    <!DOCTYPE html>
<html lang="en">
@php
    $total = 0;
    $chunks = $subventions->chunk(13);
    $grandTotal = 0;
@endphp
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <!-- bootstrap -->
    <link rel="stylesheet" href="{{asset('invoices/css/all.min.css')}}">
    <link rel="stylesheet" href="{{asset('invoices/css/bootstrap.min.css')}}">

    <style>
        body {
            font-weight: bold;
            direction: rtl;
        }
        .header {
            display: flex;
            justify-content: flex-end;
        }
        .logo {
            width: 300px;
            height: 80px;
        }
        table, td, th {
            border: 1px solid black !important;
        }
        .scroll {
            overflow-y: auto;
            margin-bottom: 10px;
        }
        .blue-color {
            background-color: gray;
            color: white;
        }
        .border-color {
            border: 1px solid white !important;
        }
        .print-section {
            page-break-after: always;
        }
        .print-section:last-child {
            page-break-after: auto;
        }

        @media print {
            .signature-section {
                position: relative;
                bottom: 0;
                left: 0;
                width: 100%;
                text-align: center;
            }
            .print-section {
                page-break-after: always;
                margin-bottom: 50px;
            }
            .print-section:last-child {
                page-break-after: auto;
            }
        }
    </style>
</head>
<body>
@foreach($chunks as $chunk)
    <div class="print-section" id="content">
        <div class="pt-5 ">
            <div class="container-fluid">
                <h4 class="text-center mt-1 mb-3">قطاع التكافل / الادارة العامة للزكاة(فرع شبين الكوم)</h4>
                <h4 class="text-center mt-1 mb-3">بيان باسماء الحالات المطلوبة تقرير / صرف / زكاة لهم</h4>
                <h5 class="text-center mt-1 mb-3">بيان باسماء حالات الاعانه الشهريه / {{ isset($setting) ? $setting->title : '' }}</h5>
                <h5 class="text-center mt-1 mb-3">وعنوانها كفر طنبدى -شارع البحر بعد صيدلية ناصف بجوار الاستاذ على داود المحامى</h5>
                <div style="display: flex; justify-content: space-between;">
                    <p>شبين الكوم - محافظة المنوفية</p>
                    <p>بتاريخ {{\Carbon\Carbon::now()->format('Y-m-d')}}</p>
                </div>
                <div class="scroll">
                    <table class="table">
                        <thead>
                        <tr class="blue-color">
{{--                            <th scope="col" class="border-color text-center">#</th>--}}
                            <th scope="col" class="border-color text-center">الاسم</th>
                            <th scope="col" class="border-color text-center">الرقم القومى</th>
                            <th scope="col" class="border-color text-center">المبلغ</th>
                            <th scope="col" class="border-color text-center">التوقيع</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $chunkTotal = 0;
//                            $startNumber = ($loop->index * 10) + 1;
                        @endphp
                        @foreach($chunk as $subvention)
                            <tr>
{{--                                <td>{{ $startNumber + $loop->index }}</td>--}}
                                <td class="text-sm font-weight-600">{{ optional($subvention)->user->wife_name ?? '---' }}</td>
                                <td class="text-sm font-weight-600">{{ optional($subvention)->user->wife_national_id ?? "---"}}</td>
                                <td>{{optional($subvention)->price}}</td>
                                @php
                                    $chunkTotal += optional($subvention)->price;
                                    $grandTotal += optional($subvention)->price;
                                @endphp
                                <td style="width: 30%"></td>
                            </tr>
                        @endforeach
                        <tr>
                            <th class="text-center" scope="row" colspan="3">الاجمالى الجزئى</th>
                            <td>{{$chunkTotal}}</td>
                            <td></td>
                        </tr>
                        @if($loop->last)
                            <tr>
                                <th class="text-center" scope="row" colspan="3">الاجمالى الكلى</th>
                                <td>{{$grandTotal}}</td>
                                <td></td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center fw-normal  pb-5 signature-section">
            <p>عضو له حق التوقيع</p>
            <p>-----------</p>
            <p>امين الصندوق</p>
            <p>-----------</p>
            <p>مقر اللجنة</p>
            <p>-----------</p>
        </div>
    </div>
@endforeach

<script src="{{asset('invoices/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('invoices/js/feather.min.js')}}"></script>
<script>
    function myfunction() {
        window.print();
    }
    window.addEventListener('DOMContentLoaded', (event) => {
        myfunction();
    });
</script>
</body>
</html>
