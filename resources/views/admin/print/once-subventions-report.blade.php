@php
    use Carbon\Carbon;

    $chunks = $subventions->values()->chunk(18);
    $grandTotal = $subventions->sum('price');
    $printDate = Carbon::now()->format('Y/m/d');
    $printMonth = Carbon::now()->translatedFormat('F Y');
@endphp
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>كشف صرف الإعانات الفردية</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 14mm 10mm;
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

        .page {
            page-break-after: always;
        }

        .page:last-child {
            page-break-after: auto;
        }

        .sheet {
            width: 100%;
        }

        .sheet-header {
            text-align: right;
            margin-bottom: 12px;
            line-height: 1.8;
            font-size: 18px;
            font-weight: 700;
        }

        .sheet-header__meta {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
            font-size: 15px;
            font-weight: 700;
        }

        .sheet-title {
            text-align: center;
            font-size: 22px;
            font-weight: 800;
            text-decoration: underline;
            margin: 8px 0 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid #111;
            padding: 7px 6px;
            text-align: center;
            vertical-align: middle;
            font-size: 15px;
        }

        thead th {
            font-weight: 800;
        }

        .col-serial { width: 6%; }
        .col-code { width: 11%; }
        .col-name { width: 31%; }
        .col-card { width: 22%; }
        .col-amount { width: 12%; }
        .col-sign { width: 18%; }

        .text-right {
            text-align: right;
        }

        .total-row th,
        .total-row td {
            font-weight: 800;
            background: #f4f4f4;
        }

        .signatures {
            display: flex;
            justify-content: space-between;
            gap: 24px;
            margin-top: 28px;
            font-size: 18px;
            font-weight: 700;
        }

        .signature-box {
            flex: 1;
            text-align: center;
        }

        .signature-line {
            margin-top: 46px;
        }
    </style>
</head>
<body>
    @foreach ($chunks as $chunkIndex => $chunk)
        @php
            $chunkTotal = $chunk->sum('price');
            $startSerial = ($chunkIndex * 18) + 1;
        @endphp
        <section class="page">
            <div class="sheet">
                <div class="sheet-header">
                    <div>جمعية أنصار السنة المحمدية</div>
                    <div>فرع شبين الكوم</div>
                    <div>كشف صرف الإعانات الفردية عن شهر {{ $printMonth }}</div>
                </div>

                <div class="sheet-header__meta">
                    <div>التاريخ: {{ $printDate }}</div>
                    <div>عدد الحالات: {{ $chunk->count() }}</div>
                </div>

                <div class="sheet-title">كشف صرف الإعانات الفردية</div>

                <table>
                    <thead>
                        <tr>
                            <th class="col-serial">م</th>
                            <th class="col-code">كود الحالة</th>
                            <th class="col-name">الاسم</th>
                            <th class="col-card">رقم البطاقة</th>
                            <th class="col-amount">المبلغ</th>
                            <th class="col-sign">التوقيع بالاستلام</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($chunk as $index => $subvention)
                            @php
                                $user = optional($subvention)->user;
                                $name = $user?->husband_name ?: ($user?->wife_name ?: '---');
                                $nationalId = $user?->husband_national_id ?: ($user?->wife_national_id ?: '---');
                            @endphp
                            <tr>
                                <td>{{ $startSerial + $index }}</td>
                                <td>{{ $user?->beneficiary_code ?: '-' }}</td>
                                <td class="text-right">{{ $name }}</td>
                                <td>{{ $nationalId }}</td>
                                <td>{{ number_format((float) $subvention->price, 0) }}</td>
                                <td></td>
                            </tr>
                        @endforeach

                        <tr class="total-row">
                            <th colspan="4">إجمالي الصفحة</th>
                            <td>{{ number_format((float) $chunkTotal, 0) }}</td>
                            <td></td>
                        </tr>

                        @if ($loop->last)
                            <tr class="total-row">
                                <th colspan="4">إجمالي المبلغ</th>
                                <td>{{ number_format((float) $grandTotal, 0) }}</td>
                                <td></td>
                            </tr>
                        @endif
                    </tbody>
                </table>

                <div class="signatures">
                    <div class="signature-box">
                        <div>القائم بالصرف</div>
                        <div class="signature-line">......................</div>
                    </div>
                    <div class="signature-box">
                        <div>أمين الصندوق</div>
                        <div class="signature-line">......................</div>
                    </div>
                    <div class="signature-box">
                        <div>رئيس مجلس الإدارة</div>
                        <div class="signature-line">......................</div>
                    </div>
                </div>
            </div>
        </section>
    @endforeach
</body>
</html>
