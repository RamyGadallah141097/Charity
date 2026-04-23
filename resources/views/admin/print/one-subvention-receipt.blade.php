@php
    use Carbon\Carbon;

    $user = $subvention->user;
    $name = $user?->wife_name ?: ($user?->husband_name ?: '---');
    $nationalId = $user?->wife_national_id ?: ($user?->husband_national_id ?: '---');
    $socialStatus = match ((string) ($user?->social_status ?? '')) {
        '0' => 'أعزب',
        '1' => 'متزوج',
        '2' => 'مطلق',
        '3' => 'أرمل',
        default => 'غير محدد',
    };
    $paymentDate = optional($subvention->created_at)->format('Y/m/d') ?: Carbon::now()->format('Y/m/d');
    $aidType = $subvention->price > 0 ? 'إعانة فردية مالية' : 'إعانة فردية عينية';
    $aidValue = $subvention->price > 0
        ? number_format((float) $subvention->price, 0) . ' جنيه'
        : number_format((float) $subvention->asset_count, 0) . ' قطعة';
    $adminName = optional(optional($lockerLog)->admin)->name ?: 'غير محدد';
@endphp
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نموذج صرف حالة غير ثابتة</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 14mm 12mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            color: #111;
            background: #fff;
            font-family: "DejaVu Sans", Tahoma, Arial, sans-serif;
            direction: rtl;
        }

        .receipt {
            width: 100%;
            min-height: 260mm;
            padding: 8mm 4mm;
        }

        .header {
            text-align: right;
            line-height: 1.8;
            font-size: 18px;
            font-weight: 800;
            margin-bottom: 18px;
        }

        .meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            font-size: 15px;
            font-weight: 700;
        }

        .title {
            text-align: center;
            font-size: 23px;
            font-weight: 900;
            text-decoration: underline;
            margin: 18px 0 24px;
        }

        .details {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 26px;
        }

        .details th,
        .details td {
            border: 1px solid #111;
            padding: 12px 10px;
            font-size: 17px;
            vertical-align: middle;
        }

        .details th {
            width: 30%;
            text-align: right;
            background: #f3f3f3;
            font-weight: 900;
        }

        .details td {
            text-align: right;
            font-weight: 700;
        }

        .acknowledgement {
            border: 1px solid #111;
            padding: 18px;
            line-height: 2;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 36px;
        }

        .signatures {
            display: flex;
            justify-content: space-between;
            gap: 24px;
            margin-top: 38px;
            font-size: 18px;
            font-weight: 800;
        }

        .signature-box {
            flex: 1;
            text-align: center;
        }

        .signature-line {
            margin-top: 52px;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <section class="receipt">
        <div class="header">
            <div>جمعية أنصار السنة المحمدية</div>
            <div>فرع شبين الكوم</div>
        </div>

        <div class="meta">
            <div>التاريخ: {{ Carbon::now()->format('Y/m/d') }}</div>
            <div>كود الحالة: {{ $user?->beneficiary_code ?: '-' }}</div>
        </div>

        <div class="title">نموذج صرف إلى الحالات غير الثابتة</div>

        <table class="details">
            <tbody>
                <tr>
                    <th>الاسم</th>
                    <td>{{ $name }}</td>
                </tr>
                <tr>
                    <th>الرقم القومي</th>
                    <td>{{ $nationalId }}</td>
                </tr>
                <tr>
                    <th>الحالة الاجتماعية</th>
                    <td>{{ $socialStatus }}</td>
                </tr>
                <tr>
                    <th>تاريخ الصرف</th>
                    <td>{{ $paymentDate }}</td>
                </tr>
                <tr>
                    <th>نوع المساعدة</th>
                    <td>{{ $aidType }}</td>
                </tr>
                <tr>
                    <th>مبلغ المساعدة أو عددها</th>
                    <td>{{ $aidValue }}</td>
                </tr>
                <tr>
                    <th>اسم مسؤول الصرف</th>
                    <td>{{ $adminName }}</td>
                </tr>
            </tbody>
        </table>

        <div class="acknowledgement">
            أقر أنا / {{ $name }}، صاحب الرقم القومي / {{ $nationalId }}، بأنني استلمت المساعدة الموضحة أعلاه بتاريخ {{ $paymentDate }}.
        </div>

        <div class="signatures">
            <div class="signature-box">
                <div>توقيع المستلم</div>
                <div class="signature-line">......................</div>
            </div>
            <div class="signature-box">
                <div>مسؤول الصرف</div>
                <div class="signature-line">......................</div>
            </div>
            <div class="signature-box">
                <div>رئيس مجلس الإدارة</div>
                <div class="signature-line">......................</div>
            </div>
        </div>
    </section>

    <script>
        window.print();
    </script>
</body>
</html>
