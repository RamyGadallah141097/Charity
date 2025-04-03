@php use Carbon\Carbon; @endphp
    <!DOCTYPE html>
<html lang="en">
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

        @media print {
            .signature-section {
                position: fixed;
                bottom: 0;
                left: 0;
                width: 100%;
                text-align: center;
                page-break-after: always;
            }
        }
    </style>
</head>
<body>
<div class="pt-5 pb-5">
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
                    <th scope="col" class="border-color text-center">#</th>
                    <th scope="col" class="border-color text-center">الاسم</th>
                    <th scope="col" class="border-color text-center">الرقم القومى</th>
                    <th scope="col" class="border-color text-center">المبلغ</th>
                    <th scope="col" class="border-color text-center">التوقيع</th>
                </tr>
                </thead>
                <tbody>
                {{$total = 0}}
                @foreach($subventions as $subvention)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td class="text-sm font-weight-600">{{$subvention?->user->wife_name}}</td>
                        <td class="text-sm font-weight-600">{{$subvention?->user->wife_national_id}}</td>
                        <td>{{$subvention?->price}}</td>
                        {{$total += $subvention?->price}}
                        <td></td>
                    </tr>
                @endforeach
                <tr>
                    <th class="text-center" scope="row" colspan="3">الاجمالى</th>
                    <td>{{$total}}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="d-flex justify-content-center fw-normal pt-5 pb-5 signature-section">
    <p>عضو له حق التوقيع</p>
    <p>-----------</p>
    <p>امين الصندوق</p>
    <p>-----------</p>
    <p>مقر اللجنة</p>
    <p>-----------</p>
</div>

<script src="{{asset('invoices/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('invoices/js/feather.min.js')}}"></script>
<script>
    function myfunction() {
        window.print();
    }
</script>
</body>
</html>
