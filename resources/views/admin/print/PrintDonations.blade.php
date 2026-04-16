@php use Carbon\Carbon; @endphp
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>طباعة التبرعات</title>
    <link rel="stylesheet" href="{{ asset('invoices/css/bootstrap.min.css') }}">
    <style>
        body {
            font-weight: bold;
            direction: rtl;
        }

        table,
        td,
        th {
            border: 1px solid black !important;
        }

        .blue-color {
            background-color: gray;
            color: white;
        }

        .border-color {
            border: 1px solid white !important;
        }

        /* طباعة كل صفحة لوحدها */
        .page-break {
            page-break-after: always;
        }

        @media print {
            .page-break {
                page-break-after: always;
            }

            .no-print {
                display: none;
            }
        }

        .header,
        .footer {
            width: 100%;
            text-align: center;
            position: fixed;
        }

        .header {
            top: 0;
        }

        .footer {
            bottom: 0;
            font-size: 14px;
        }

        .content {
            margin-top: 180px;
            margin-bottom: 100px;
        }
    </style>
</head>

<body>
    @foreach ($Donations->chunk(10) as $pageIndex => $chunk)
        <div class="page">
            <!-- Header ثابت -->
            <div class="header">
                <h4 class="text-center mt-1 mb-1">قطاع التكافل / الادارة العامة للزكاة (فرع شبين الكوم)</h4>
                <h4 class="text-center mt-1 mb-1">بيان بأسماء الحالات المطلوبة تقرير / صرف / زكاة لهم</h4>
                <h5 class="text-center mt-1 mb-1">بيان بأسماء حالات الاعانة الشهرية /
                    {{ isset($setting) ? $setting->title : '' }}</h5>
                <h5 class="text-center mt-1 mb-1">كفر طنبدى - شارع البحر بعد صيدلية ناصف بجوار الاستاذ على داود المحامى
                </h5>
                <div style="display: flex; justify-content: space-between; padding: 0 40px;">
                    <p>شبين الكوم - محافظة المنوفية</p>
                    <p>بتاريخ {{ \Carbon\Carbon::now()->format('Y-m-d') }}</p>
                </div>
            </div>

            <!-- المحتوى -->
            <div class="content container-fluid">
                <table class="table">
                    <thead>
                        <tr class="blue-color text-center">
                            <th>#</th>
                            <th>اسم المتبرع</th>
                            <th> هاتف المتبرع</th>
                            <th> تاريخ التبرع</th>
                            <th> نوع التبرع</th>
                            <th> قيمة التبرع</th>
                            <th>التوقيع</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($chunk as $index => $Donations)
                            <tr class="text-center">
                                <td>{{ $pageIndex * 10 + $loop->iteration }}</td>
                                <td>{{ @$Donations->donor->name }}</td>
                                <td>{{ @$Donations->donor->phone }}</td>
                                <td>{{ Carbon::parse($Donations->created_at)->format('Y-m-d') }}</td>
                                <td>
                                    @if (@$Donations->donation_type == '0')
                                        زكاة مال
                                    @elseif(@$Donations->donation_type == '1')
                                        صدقات
                                    @elseif(@$Donations->donation_type == '2')
                                        قرض حسن
                                    @endif
                                </td>
                                <td>{{ @$Donations->donation_amount }}</td>
                                <td></td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

            <!-- Footer ثابت -->
            <div class="footer d-flex justify-content-center fw-normal">
                <p>عضو له حق التوقيع ----------- أمين الصندوق ----------- مقر اللجنة -----------</p>
            </div>
        </div>

        @if (!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach

     <script>
        window.addEventListener('DOMContentLoaded', () => {
            window.print();
        });
     </script>
</body>


</html>
