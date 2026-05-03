@extends('admin/layouts/master')

@section('title')
    {{ $setting->title ?? '' }} | {{ $title }}
@endsection

@section('page_name')
    {{ $title }}
@endsection

@section('content')
    <style>
        .report-shell {
            display: grid;
            gap: 20px;
        }
        .report-panel {
            background: #fff;
            border: 1px solid #e8eef7;
            border-radius: 18px;
            box-shadow: 0 12px 28px rgba(31, 45, 61, 0.06);
            padding: 20px;
        }
        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 16px;
        }
        .report-header h3 {
            margin: 0 0 6px;
            font-weight: 800;
            color: #22324b;
        }
        .report-header p {
            margin: 0;
            color: #6f7f95;
        }
        .report-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .report-actions-group {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .report-filters {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
        }
        .report-summary {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
        }
        .report-summary-card {
            background: linear-gradient(135deg, #344f8a 0%, #6f4ef6 100%);
            color: #fff;
            border-radius: 16px;
            padding: 16px;
        }
        .report-summary-card__label {
            font-size: 14px;
            opacity: .88;
            margin-bottom: 8px;
        }
        .report-summary-card__value {
            font-size: 28px;
            font-weight: 800;
        }
        .report-table-tools {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
            flex-wrap: wrap;
        }
        @media (max-width: 1199px) {
            .report-filters, .report-summary {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
        @media (max-width: 767px) {
            .report-header {
                flex-direction: column;
            }
            .report-filters, .report-summary {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="report-shell">
        <div class="report-panel">
            <div class="report-header">
                <div>
                    <h3>{{ $title }}</h3>
                    <p>{{ $description }}</p>
                </div>
                <div class="report-actions">
                    <button type="button" class="btn btn-success export-trigger" data-export="excel">Excel</button>
                    <button type="button" class="btn btn-primary export-trigger" data-export="print">طباعة</button>
                </div>
            </div>

            <form method="GET" class="report-filters" id="reportFiltersForm">
                @foreach($filters as $filter)
                    <div class="form-group mb-0">
                        <label class="form-label">{{ $filter['label'] }}</label>
                        @if($filter['type'] === 'select')
                            <select name="{{ $filter['name'] }}" class="form-control">
                                @foreach($filter['options'] as $optionValue => $optionLabel)
                                    <option value="{{ $optionValue }}" {{ (string) $filter['value'] === (string) $optionValue ? 'selected' : '' }}>
                                        {{ $optionLabel }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <input type="{{ $filter['type'] }}" name="{{ $filter['name'] }}" value="{{ $filter['value'] }}" class="form-control">
                        @endif
                    </div>
                @endforeach
                <input type="hidden" name="export" id="reportExportType" value="">
                <input type="hidden" name="export_scope" id="reportExportScope" value="all">
                <input type="hidden" name="selected_rows" id="reportSelectedRows" value="">
                <div class="form-group mb-0 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary w-100">عرض التقرير</button>
                </div>
            </form>
        </div>

        <div class="report-summary">
            @foreach($summaryCards as $card)
                <div class="report-summary-card">
                    <div class="report-summary-card__label">{{ $card['label'] }}</div>
                    <div class="report-summary-card__value">{{ $card['value'] }}</div>
                </div>
            @endforeach
        </div>

        <div class="report-panel">
            <div class="report-table-tools">
                <div></div>
                <small class="text-muted">يمكنك الطباعة أو التصدير للمحدد فقط أو لكل الصفوف.</small>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered text-nowrap w-100" id="reportTable">
                    <thead>
                        <tr class="fw-bolder text-muted bg-light">
                            <th style="width: 50px;">
                                <input type="checkbox" id="selectAllRowsHeader">
                            </th>
                            @foreach($columns as $column)
                                <th>{{ $column }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $index => $row)
                            <tr>
                                <td>
                                    <input type="checkbox" class="report-row-checkbox" value="{{ $index }}">
                                </td>
                                @foreach($columns as $column)
                                    <td>{{ $row[$column] ?? '--' }}</td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ count($columns) + 1 }}" class="text-center text-muted py-4">لا توجد بيانات متاحة لهذا التقرير.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const form = document.getElementById('reportFiltersForm');
            const exportType = document.getElementById('reportExportType');
            const exportScope = document.getElementById('reportExportScope');
            const selectedRows = document.getElementById('reportSelectedRows');
            const rowCheckboxes = Array.from(document.querySelectorAll('.report-row-checkbox'));
            const selectAll = document.getElementById('selectAllRows');
            const selectAllHeader = document.getElementById('selectAllRowsHeader');

            function syncSelectedRows() {
                selectedRows.value = rowCheckboxes.filter(item => item.checked).map(item => item.value).join(',');
                const allChecked = rowCheckboxes.length > 0 && rowCheckboxes.every(item => item.checked);
                if (selectAll) selectAll.checked = allChecked;
                if (selectAllHeader) selectAllHeader.checked = allChecked;
            }

            function toggleAll(checked) {
                rowCheckboxes.forEach(item => {
                    item.checked = checked;
                });
                syncSelectedRows();
            }

            rowCheckboxes.forEach(item => item.addEventListener('change', syncSelectedRows));
            if (selectAll) {
                selectAll.addEventListener('change', function () {
                    toggleAll(this.checked);
                });
            }
            if (selectAllHeader) {
                selectAllHeader.addEventListener('change', function () {
                    toggleAll(this.checked);
                });
            }

            document.querySelectorAll('.export-trigger').forEach(button => {
                button.addEventListener('click', function () {
                    syncSelectedRows();
                    exportType.value = this.dataset.export;
                    exportScope.value = selectedRows.value ? 'selected' : 'all';

                    form.submit();
                });
            });
        })();
    </script>
@endsection
