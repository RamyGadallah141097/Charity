<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        @page { size: A4 portrait; margin: 12mm; }
        body { font-family: sans-serif; color: #1f2937; font-size: 12px; }
        .header { text-align: center; border-bottom: 2px solid #dbe4f0; padding-bottom: 12px; margin-bottom: 16px; }
        .title { font-size: 22px; font-weight: 800; margin-bottom: 6px; }
        .subtitle { color: #5f6f86; margin-bottom: 3px; }
        table.data { width: 100%; border-collapse: collapse; margin-top: 12px; }
        table.data th, table.data td { border: 1px solid #dbe4f0; padding: 6px; text-align: right; }
        table.data th { background: #eef4ff; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">{{ $setting->title ?? 'اسم الجمعية' }}</div>
        <div class="subtitle">تقرير خاص بـ {{ $title }}</div>
        <div class="subtitle">تاريخ الطباعة: {{ now()->format('Y-m-d H:i') }}</div>
    </div>

    <table class="data">
        <thead>
            <tr>
                @foreach($columns as $column)
                    <th>{{ $column }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $row)
                <tr>
                    @foreach($columns as $column)
                        <td>{{ $row[$column] ?? '--' }}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($columns) }}">لا توجد بيانات.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
