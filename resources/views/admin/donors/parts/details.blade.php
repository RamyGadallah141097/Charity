@extends('admin/layouts/master')

@section('page_name')
    المتبرعين
@endsection

@section('content')
    <style>
        .donor-details-card {
            border: 1px solid #e8ebf3;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(35, 47, 85, 0.06);
        }

        .donor-section-title {
            font-size: 28px;
            font-weight: 700;
            color: #1f2a56;
            margin-bottom: 0;
        }

        .donor-meta-card {
            border: 1px solid #edf1f7;
            border-radius: 14px;
            background: linear-gradient(180deg, #ffffff 0%, #f9fbff 100%);
            height: 100%;
        }

        .donor-meta-label {
            font-size: 14px;
            color: #7a86a8;
            margin-bottom: 8px;
        }

        .donor-meta-value {
            font-size: 24px;
            font-weight: 700;
            color: #1f2a56;
            line-height: 1.4;
        }

        .donor-info-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .donor-info-item {
            border: 1px solid #edf1f7;
            border-radius: 14px;
            padding: 16px 18px;
            background-color: #fff;
            min-height: 100px;
        }

        .donor-info-item--wide {
            grid-column: 1 / -1;
        }

        .donor-info-item__label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            color: #7a86a8;
            margin-bottom: 10px;
        }

        .donor-info-item__value {
            font-size: 20px;
            font-weight: 600;
            color: #1f2a56;
            line-height: 1.7;
            word-break: break-word;
        }

        .donor-info-item__value--muted {
            color: #5c678c;
            font-size: 17px;
            font-weight: 500;
        }

        .donor-table-card {
            border: 1px solid #e8ebf3;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(35, 47, 85, 0.05);
        }

        .donor-table-card .card-header {
            background: #fff;
            border-bottom: 1px solid #eef2f8;
            padding: 18px 22px;
        }

        .donor-table-card .card-title {
            font-size: 26px;
            font-weight: 700;
            color: #1f2a56;
            margin-bottom: 0;
        }

        .donor-table-card .table {
            margin-bottom: 0;
        }

        .donor-table-card .table thead th {
            background: #f5f8ff;
            color: #3b4a76;
            font-weight: 700;
            white-space: nowrap;
        }

        .donor-table-card .table td,
        .donor-table-card .table th {
            vertical-align: middle;
        }

        @media (max-width: 991.98px) {
            .donor-info-grid {
                grid-template-columns: 1fr;
            }

            .donor-meta-value {
                font-size: 20px;
            }

            .donor-info-item__value {
                font-size: 18px;
            }
        }
    </style>

    <div class="card donor-details-card bg-white p-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
            <h3 class="donor-section-title">بيانات المتبرع</h3>
            <span class="badge badge-light px-3 py-2" style="font-size: 14px;">{{ $donor->name }}</span>
        </div>

        <div class="row g-3 mt-1">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card donor-meta-card text-center">
                    <div class="card-body">
                        <div class="donor-meta-label">أول تبرع</div>
                        <div class="donor-meta-value">{{ optional($donor->first_donation_date)->format('d-m-Y') ?: '--' }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card donor-meta-card text-center">
                    <div class="card-body">
                        <div class="donor-meta-label">آخر تبرع</div>
                        <div class="donor-meta-value">{{ optional($donor->last_donation_date)->format('d-m-Y') ?: '--' }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card donor-meta-card text-center">
                    <div class="card-body">
                        <div class="donor-meta-label">عدد مرات التبرع</div>
                        <div class="donor-meta-value">{{ $donor->donations_count }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card donor-meta-card text-center">
                    <div class="card-body">
                        <div class="donor-meta-label">إجمالي التبرعات النقدية</div>
                        <div class="donor-meta-value">{{ rtrim(rtrim(number_format((float) $cashDonationsTotal, 2, '.', ''), '0'), '.') }} جنيه</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="donor-info-grid mt-3">
            <div class="donor-info-item">
                <span class="donor-info-item__label">الاسم</span>
                <div class="donor-info-item__value">{{ $donor->name ?: '--' }}</div>
            </div>
            <div class="donor-info-item">
                <span class="donor-info-item__label">رقم التليفون 1</span>
                <div class="donor-info-item__value">{{ $donor->phone ?: '--' }}</div>
            </div>
            <div class="donor-info-item">
                <span class="donor-info-item__label">رقم التليفون 2</span>
                <div class="donor-info-item__value">{{ $donor->phone_second ?: '--' }}</div>
            </div>
            <div class="donor-info-item">
                <span class="donor-info-item__label">رقم تليفون قريب</span>
                <div class="donor-info-item__value">{{ $donor->relative_phone ?: '--' }}</div>
            </div>
            <div class="donor-info-item donor-info-item--wide">
                <span class="donor-info-item__label">العنوان</span>
                <div class="donor-info-item__value donor-info-item__value--muted">{{ $donor->full_address ?: '--' }}</div>
            </div>
            <div class="donor-info-item donor-info-item--wide">
                <span class="donor-info-item__label">أنواع التبرع المفضلة</span>
                <div class="donor-info-item__value donor-info-item__value--muted">{{ $donor->preferred_donation_types_text ?: '--' }}</div>
            </div>
            <div class="donor-info-item">
                <span class="donor-info-item__label">تاريخ الميلاد</span>
                <div class="donor-info-item__value">{{ $donor->burn_date ?: '--' }}</div>
            </div>
            <div class="donor-info-item">
                <span class="donor-info-item__label">ملاحظات</span>
                <div class="donor-info-item__value donor-info-item__value--muted">{{ $donor->notes ?: '--' }}</div>
            </div>
        </div>
    </div>

    <div class="card donor-table-card mt-4">
        <div class="card-header">
            <h3 class="card-title">سجل التبرعات النقدية</h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-bordered w-100">
                    <thead>
                        <tr>
                            <th style="width: 80px;">#</th>
                            <th>تاريخ التبرع</th>
                            <th>نوع التبرع</th>
                            <th>قيمة التبرع</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($cashDonations as $donation)
                            <tr>
                                <td>{{ $donation->id }}</td>
                                <td>{{ optional($donation->received_at)->format('Y-m-d') ?: optional($donation->created_at)->format('Y-m-d H:i') }}</td>
                                <td>{{ $donation->display_type_name }}</td>
                                <td>{{ $donation->display_value }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">لا يوجد تبرعات مسجلة</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card donor-table-card mt-4">
        <div class="card-header">
            <h3 class="card-title">سجل التبرعات غير النقدية</h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-bordered w-100">
                    <thead>
                        <tr>
                            <th style="width: 80px;">#</th>
                            <th>تاريخ التبرع</th>
                            <th>نوع التبرع</th>
                            <th>الكمية / الوحدة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($nonCashDonations as $donation)
                            <tr>
                                <td>{{ $donation->id }}</td>
                                <td>{{ optional($donation->received_at)->format('Y-m-d') ?: optional($donation->created_at)->format('Y-m-d H:i') }}</td>
                                <td>{{ $donation->display_type_name }}</td>
                                <td>{{ $donation->display_value }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">لا يوجد تبرعات غير نقدية مسجلة</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
