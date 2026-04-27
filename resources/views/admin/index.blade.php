@extends('admin/layouts/master')

@section('title')
    {{ isset($setting) ? $setting->title : 'الجمعية الخيرية' }} | الصفحة الرئيسية
@endsection

@section('page_name')
    الرئـيسية
@endsection

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<style>
    body {
        font-family: 'Cairo', sans-serif;
    }
    
    /* Hero Section */
    .hero-section {
        background: linear-gradient(135deg, #2c3e50 0%, #3498db 100%);
        border-radius: 20px;
        padding: 40px;
        color: white;
        margin-bottom: 30px;
        box-shadow: 0 10px 30px rgba(52, 152, 219, 0.2);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .hero-content h1 {
        font-weight: 800;
        font-size: 2.5rem;
        margin-bottom: 10px;
    }
    .hero-content p {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-bottom: 0;
    }
    .hero-logo img {
        height: 100px;
        background: white;
        padding: 10px;
        border-radius: 15px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }

    /* Modules Section */
    .module-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
        text-align: center;
        transition: all 0.3s ease;
        border: 1px solid #edf2f9;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        display: block;
        color: #2c3e50;
        text-decoration: none !important;
    }
    .module-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(52, 152, 219, 0.15);
        border-color: #3498db;
    }
    .module-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        font-size: 24px;
        background: rgba(52, 152, 219, 0.1);
        color: #3498db;
    }
    .module-card h4 {
        font-size: 1.1rem;
        font-weight: 700;
        margin: 0;
    }

    /* Stats Cards */
    .stats-row > [class*='col-'] {
        display: flex;
    }
    .stat-card {
        --stat-accent: #3498db;
        background: linear-gradient(180deg, #ffffff 0%, #f9fbff 100%);
        border-radius: 24px;
        padding: 22px 22px 20px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 14px 35px rgba(31, 45, 61, 0.08);
        border: 1px solid rgba(126, 144, 168, 0.16);
        margin-bottom: 25px;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        min-height: 100%;
        width: 100%;
        display: flex;
        flex-direction: column;
    }
    .stat-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 45px rgba(31, 45, 61, 0.14);
    }
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100%;
        height: 5px;
    }
    .stat-card::after {
        content: '';
        position: absolute;
        left: -40px;
        bottom: -40px;
        width: 130px;
        height: 130px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255,255,255,0) 28%, rgba(255,255,255,0.42) 100%);
        opacity: 0.55;
        pointer-events: none;
    }
    .stat-card.primary { --stat-accent: #3498db; }
    .stat-card.success { --stat-accent: #2ecc71; }
    .stat-card.warning { --stat-accent: #f1c40f; }
    .stat-card.danger { --stat-accent: #e74c3c; }
    .stat-card.purple { --stat-accent: #9b59b6; }
    .stat-card.primary::before { background: #3498db; }
    .stat-card.success::before { background: #2ecc71; }
    .stat-card.warning::before { background: #f1c40f; }
    .stat-card.danger::before { background: #e74c3c; }
    .stat-card.purple::before { background: #9b59b6; }

    .stat-card-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 14px;
        margin-bottom: 18px;
    }
    .stat-icon-wrap {
        width: 54px;
        height: 54px;
        flex-shrink: 0;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(52, 152, 219, 0.12);
        box-shadow: inset 0 0 0 1px rgba(52, 152, 219, 0.08);
    }
    .stat-card.primary .stat-icon-wrap { background: rgba(52, 152, 219, 0.12); box-shadow: inset 0 0 0 1px rgba(52, 152, 219, 0.08); }
    .stat-card.success .stat-icon-wrap { background: rgba(46, 204, 113, 0.12); box-shadow: inset 0 0 0 1px rgba(46, 204, 113, 0.08); }
    .stat-card.warning .stat-icon-wrap { background: rgba(241, 196, 15, 0.16); box-shadow: inset 0 0 0 1px rgba(241, 196, 15, 0.1); }
    .stat-card.danger .stat-icon-wrap { background: rgba(231, 76, 60, 0.12); box-shadow: inset 0 0 0 1px rgba(231, 76, 60, 0.08); }
    .stat-card.purple .stat-icon-wrap { background: rgba(155, 89, 182, 0.12); box-shadow: inset 0 0 0 1px rgba(155, 89, 182, 0.08); }
    .stat-icon {
        font-size: 24px;
        color: var(--stat-accent);
    }
    .stat-content {
        flex: 1;
        min-width: 0;
    }
    .stat-title {
        color: #5f7086;
        font-size: 1rem;
        font-weight: 700;
        line-height: 1.5;
        margin-bottom: 0;
    }
    .stat-value {
        font-size: clamp(2rem, 2.5vw, 2.45rem);
        font-weight: 800;
        color: #20324a;
        line-height: 1;
        margin-bottom: 14px;
        letter-spacing: -0.02em;
    }
    .stat-sub {
        font-size: 0.95rem;
        line-height: 1.75;
        color: #7a8aa0;
        margin-top: auto;
        padding-top: 14px;
        border-top: 1px solid rgba(126, 144, 168, 0.14);
    }
    .stat-sub .text-muted {
        color: inherit !important;
    }

    @media (max-width: 991.98px) {
        .stat-card {
            padding: 20px 18px;
            border-radius: 20px;
        }
    }
    @media (max-width: 575.98px) {
        .stat-card-head {
            align-items: center;
        }
        .stat-title {
            font-size: 0.95rem;
        }
        .stat-value {
            font-size: 1.85rem;
        }
    }
    .text-success-custom { color: #2ecc71; }
    .text-danger-custom { color: #e74c3c; }

    .badge-success-light { background: rgba(46, 204, 113, 0.1); color: #2ecc71; border-radius: 6px; padding: 5px 10px; font-weight: 600; }
    .badge-primary-light { background: rgba(52, 152, 219, 0.1); color: #3498db; border-radius: 6px; padding: 5px 10px; font-weight: 600; }
    .badge-purple-light { background: rgba(155, 89, 182, 0.1); color: #9b59b6; border-radius: 6px; padding: 5px 10px; font-weight: 600; }
    .badge-warning-light { background: rgba(241, 196, 15, 0.1); color: #f1c40f; border-radius: 6px; padding: 5px 10px; font-weight: 600; }
    .badge-danger-light { background: rgba(231, 76, 60, 0.1); color: #e74c3c; border-radius: 6px; padding: 5px 10px; font-weight: 600; }

    /* Chart Cards */
    .chart-card {
        background: white;
        border-radius: 20px;
        padding: 25px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.04);
        margin-bottom: 25px;
    }
    .chart-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    .chart-card-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
    }

    /* List Widgets */
    .widget-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .widget-list-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px dashed #eee;
    }
    .widget-list-item:last-child {
        border-bottom: none;
    }
    .widget-list-title {
        font-weight: 600;
        color: #34495e;
    }
    .widget-list-value {
        font-weight: 700;
        color: #3498db;
        background: rgba(52, 152, 219, 0.1);
        padding: 4px 10px;
        border-radius: 8px;
    }
</style>

<div class="container-fluid">

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="hero-content">
            <h1>مرحباً بك في لوحة تحكم {{ isset($setting) ? $setting->title : 'الجمعية الخيرية' }}</h1>
            <p>نظام إدارة الموارد والعمليات الخيرية - إحصائيات دقيقة وإدارة شاملة</p>
        </div>
        <div class="hero-logo">
            @if(isset($setting) && $setting->logo)
                <img src="{{ asset($setting->logo) }}" alt="Logo">
            @else
                <div style="font-size: 40px; font-weight: 900; color: #3498db;">شعار</div>
            @endif
        </div>
    </div>

    <!-- Key Statistics -->
    <div class="row stats-row">
        <!-- Total Donors -->
        <div class="col-xl col-lg-6 col-md-6">
            <div class="stat-card primary">
                <div class="stat-card-head">
                    <div class="stat-content">
                        <div class="stat-title">إجمالي المتبرعين</div>
                    </div>
                    <div class="stat-icon-wrap">
                        <i class="fas fa-hand-holding-heart stat-icon"></i>
                    </div>
                </div>
                <div class="stat-value">{{ number_format($activeDonors) }}</div>
                <div class="stat-sub">
                    <span class="text-muted">عدد الأشخاص المتبرعين بالجمعية</span>
                </div>
            </div>
        </div>
        <!-- Monthly Donor Amounts -->
        <div class="col-xl col-lg-6 col-md-6">
            <div class="stat-card purple">
                <div class="stat-card-head">
                    <div class="stat-content">
                        <div class="stat-title">إجمالي مبالغ المتبرعين الشهرية</div>
                    </div>
                    <div class="stat-icon-wrap">
                        <i class="fas fa-calendar-alt stat-icon"></i>
                    </div>
                </div>
                <div class="stat-value">{{ number_format($donationsMonth) }}</div>
                <div class="stat-sub">
                    <span class="text-muted">إجمالي التبرعات النقدية لهذا الشهر</span>
                </div>
            </div>
        </div>
        <!-- Total Beneficiaries -->
        <div class="col-xl col-lg-6 col-md-6">
            <div class="stat-card success">
                <div class="stat-card-head">
                    <div class="stat-content">
                        <div class="stat-title">المستفيدين المقبولين</div>
                    </div>
                    <div class="stat-icon-wrap">
                        <i class="fas fa-users stat-icon"></i>
                    </div>
                </div>
                <div class="stat-value">{{ number_format($totalBeneficiaries) }}</div>
                <div class="stat-sub">
                    <span class="text-muted">إجمالي عدد المستفيدين المعتمدين</span>
                </div>
            </div>
        </div>
        <!-- Subvention Users -->
        <div class="col-xl col-lg-6 col-md-6">
            <div class="stat-card warning">
                <div class="stat-card-head">
                    <div class="stat-content">
                        <div class="stat-title">مستفيدي الإعانات الشهرية</div>
                    </div>
                    <div class="stat-icon-wrap">
                        <i class="fas fa-user-check stat-icon"></i>
                    </div>
                </div>
                <div class="stat-value">{{ number_format($totalMonthlySubventionsUsers) }}</div>
                <div class="stat-sub">
                    <span class="text-muted">إجمالي الأشخاص أصحاب الإعانات الشهرية</span>
                </div>
            </div>
        </div>
        <!-- Subvention Value -->
        <div class="col-xl col-lg-6 col-md-6">
            <div class="stat-card danger">
                <div class="stat-card-head">
                    <div class="stat-content">
                        <div class="stat-title">قيمة الإعانات الشهرية</div>
                    </div>
                    <div class="stat-icon-wrap">
                        <i class="fas fa-money-bill-wave stat-icon"></i>
                    </div>
                </div>
                <div class="stat-value">{{ number_format($totalMonthlySubventionsValue, 0) }}</div>
                <div class="stat-sub">
                    <span class="text-muted">إجمالي القيمة النقدية للإعانات الشهرية</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Locker Balances -->
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="mb-4" style="font-weight: 800; color: #2c3e50; display: flex; align-items: center; gap: 10px;">
                <span style="background: #3498db; color: white; width: 35px; height: 35px; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-vault" style="font-size: 18px;"></i>
                </span>
                أرصدة الخزائن الحالية
            </h4>
        </div>
        <!-- Money Locker -->
        <div class="col-xl-3 col-lg-6">
            <div class="stat-card success">
                <i class="fas fa-money-bill-wave stat-icon"></i>
                <div class="stat-title">خزنة المال (تبرعات)</div>
                <div class="stat-value" style="color: #2ecc71;">{{ number_format($moneyLockerBalance, 2) }}</div>
                <div class="stat-sub">
                    <span class="badge badge-success-light">جنيه مصري</span>
                </div>
            </div>
        </div>
        <!-- Loan Locker -->
        <div class="col-xl-3 col-lg-6">
            <div class="stat-card primary">
                <i class="fas fa-hand-holding-usd stat-icon"></i>
                <div class="stat-title">خزنة القروض الحسنة</div>
                <div class="stat-value" style="color: #3498db;">{{ number_format($loanLockerBalance, 2) }}</div>
                <div class="stat-sub">
                    <span class="badge badge-primary-light">جنيه مصري</span>
                </div>
            </div>
        </div>
        <!-- Association Locker -->
        <div class="col-xl-3 col-lg-6">
            <div class="stat-card purple">
                <i class="fas fa-building stat-icon"></i>
                <div class="stat-title">خزنة الجمعية</div>
                <div class="stat-value" style="color: #9b59b6;">{{ number_format($associationLockerBalance, 2) }}</div>
                <div class="stat-sub">
                    <span class="badge badge-purple-light">جنيه مصري</span>
                </div>
            </div>
        </div>
        <!-- In-kind Locker -->
        <div class="col-xl-3 col-lg-6">
            <div class="stat-card warning">
                <i class="fas fa-box-open stat-icon"></i>
                <div class="stat-title">خزنة العينيات</div>
                <div class="stat-value" style="color: #f1c40f;">{{ number_format($inKindLockerBalance) }}</div>
                <div class="stat-sub">
                    <span class="badge badge-warning-light">إجمالي عدد الأصناف</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <!-- Revenue vs Expenses Chart -->
        <div class="col-xl-8 col-lg-12">
            <div class="chart-card">
                <div class="chart-card-header">
                    <h3 class="chart-card-title">حركة الخزينة (وارد ومصروف) لآخر 6 شهور</h3>
                </div>
                <div id="revenueExpensesChart"></div>
            </div>
        </div>
        
        <!-- Donations by Kind (Pie Chart) -->
        <div class="col-xl-4 col-lg-12">
            <div class="chart-card">
                <div class="chart-card-header">
                    <h3 class="chart-card-title">توزيع التبرعات (مالية / عينية)</h3>
                </div>
                <div id="donationsKindChart" style="display: flex; justify-content: center; align-items: center; height: calc(100% - 50px);"></div>
            </div>
        </div>
    </div>

    <!-- Secondary Charts & Widgets Row -->
    <div class="row">
        <!-- Top Beneficiary Categories -->
        <div class="col-xl-4 col-lg-4 col-md-12">
            <div class="chart-card">
                <div class="chart-card-header">
                    <h3 class="chart-card-title"><i class="fas fa-users-cog text-success mr-2"></i> أكثر تصنيفات المستفيدين</h3>
                </div>
                <ul class="widget-list">
                    @forelse($beneficiaryCategoriesCount as $cat)
                        <li class="widget-list-item">
                            <span class="widget-list-title">{{ $cat->beneficiaryCategory->name ?? 'غير محدد' }}</span>
                            <span class="widget-list-value">{{ $cat->total }} مستفيد</span>
                        </li>
                    @empty
                        <li class="text-center text-muted py-4">لا توجد بيانات</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <!-- Top Donors -->
        <div class="col-xl-4 col-lg-4 col-md-6">
            <div class="chart-card">
                <div class="chart-card-header">
                    <h3 class="chart-card-title"><i class="fas fa-trophy text-warning mr-2"></i> أعلى 5 متبرعين إفادة</h3>
                </div>
                <ul class="widget-list">
                    @forelse($topDonors as $donor)
                        <li class="widget-list-item">
                            <span class="widget-list-title">{{ $donor->name }}</span>
                            <span class="widget-list-value">{{ number_format($donor->donations_sum_amount_value ?? 0) }}</span>
                        </li>
                    @empty
                        <li class="text-center text-muted py-4">لا توجد بيانات</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <!-- Top Donation Categories -->
        <div class="col-xl-4 col-lg-4 col-md-6">
            <div class="chart-card">
                <div class="chart-card-header">
                    <h3 class="chart-card-title"><i class="fas fa-chart-pie text-primary mr-2"></i> أكثر أصناف التبرعات شيوعاً</h3>
                </div>
                <ul class="widget-list">
                    @forelse($mostCommonDonationCategories as $cat)
                        <li class="widget-list-item">
                            <span class="widget-list-title">{{ $cat->name }}</span>
                            <span class="widget-list-value">{{ $cat->donations_count }} عملية</span>
                        </li>
                    @empty
                        <li class="text-center text-muted py-4">لا توجد بيانات</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <!-- Bottom Charts Row -->
    <div class="row mt-4">
        <!-- Beneficiaries Chart -->
        <div class="col-xl-12 col-lg-12">
            <div class="chart-card">
                <div class="chart-card-header">
                    <h3 class="chart-card-title">نمو المستفيدين النشطين</h3>
                    <span class="badge badge-success">+{{ $newBeneficiariesMonth }} هذا الشهر</span>
                </div>
                <div id="beneficiariesChart"></div>
            </div>
        </div>
    </div>

</div>

@endsection

@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        
        // 1. Revenue vs Expenses Chart (Bar Chart)
        var optionsRevExp = {
            series: [{
                name: 'الواردات',
                data: {!! json_encode($monthlyChartData['revenues'] ?? []) !!}
            }, {
                name: 'المصروفات',
                data: {!! json_encode($monthlyChartData['expenses'] ?? []) !!}
            }],
            chart: {
                type: 'bar',
                height: 350,
                fontFamily: 'Cairo, sans-serif',
                toolbar: { show: false }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    endingShape: 'rounded',
                    borderRadius: 4
                },
            },
            dataLabels: { enabled: false },
            stroke: { show: true, width: 2, colors: ['transparent'] },
            xaxis: {
                categories: {!! json_encode($monthlyChartData['labels'] ?? []) !!},
            },
            yaxis: {
                title: { text: 'المبلغ (جنيه)' }
            },
            fill: { opacity: 1 },
            colors: ['#2ecc71', '#e74c3c'],
            tooltip: {
                y: { formatter: function (val) { return val + " جنيه" } }
            }
        };
        var chartRevExp = new ApexCharts(document.querySelector("#revenueExpensesChart"), optionsRevExp);
        chartRevExp.render();

        // 2. Donations Kind Pie Chart
        var kindLabels = [];
        var kindData = [];
        @foreach($donationsByKind as $kind => $total)
            kindLabels.push('{{ $kind == 'financial' ? "مالية" : ($kind == 'in_kind' ? "عينية" : "أخرى") }}');
            kindData.push({{ $total }});
        @endforeach

        if(kindData.length === 0) {
            kindLabels = ['لا توجد تبرعات'];
            kindData = [1];
        }

        var optionsKind = {
            series: kindData,
            chart: { type: 'donut', height: 320, fontFamily: 'Cairo, sans-serif' },
            labels: kindLabels,
            colors: ['#3498db', '#f1c40f', '#9b59b6'],
            plotOptions: {
                pie: {
                    donut: {
                        size: '70%',
                        labels: {
                            show: true,
                            name: { show: true, fontSize: '22px' },
                            value: { show: true, fontSize: '16px' },
                            total: {
                                show: true,
                                showAlways: true,
                                label: 'الإجمالي',
                                color: '#373d3f'
                            }
                        }
                    }
                }
            },
            dataLabels: { enabled: false },
            legend: { position: 'bottom' }
        };
        var chartKind = new ApexCharts(document.querySelector("#donationsKindChart"), optionsKind);
        chartKind.render();

        // 3. Beneficiaries Line Chart
        var optionsBen = {
            series: [{
                name: "المستفيدين الجدد",
                data: {!! json_encode($monthlyChartData['beneficiaries'] ?? []) !!}
            }],
            chart: {
                height: 300,
                type: 'area',
                fontFamily: 'Cairo, sans-serif',
                toolbar: { show: false },
                zoom: { enabled: false }
            },
            colors: ['#9b59b6'],
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 3 },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.1,
                    stops: [0, 90, 100]
                }
            },
            xaxis: {
                categories: {!! json_encode($monthlyChartData['labels'] ?? []) !!},
            },
            yaxis: {
                labels: {
                    formatter: function (val) { return val.toFixed(0); }
                }
            }
        };
        var chartBen = new ApexCharts(document.querySelector("#beneficiariesChart"), optionsBen);
        chartBen.render();
    });
</script>
@endsection
