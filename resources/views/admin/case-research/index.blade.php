@extends('admin/layouts/master')

@section('title')
    {{ isset($setting) ? $setting->title : '' }} | ملفات البحث الاجتماعي
@endsection

@section('page_name')
    ملفات البحث الاجتماعي
@endsection

@php
    $statusLabels = [
        'new' => 'جديد',
        'in_progress' => 'جاري',
        'completed' => 'مكتمل',
        'delayed' => 'متأخر',
        'cancelled' => 'ملغي',
    ];
    $resultLabels = [
        'eligible' => 'مستحق',
        'not_eligible' => 'غير مستحق',
        'needs_follow_up' => 'يحتاج متابعة',
        'needs_documents' => 'يحتاج مستندات إضافية',
    ];
    $statusClasses = [
        'new' => 'primary',
        'in_progress' => 'warning',
        'completed' => 'success',
        'delayed' => 'danger',
        'cancelled' => 'secondary',
    ];
@endphp

@section('content')
    <style>
        .research-toolbar {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 24px;
        }
        .research-kpi {
            border: 1px solid #e9eef5;
            border-radius: 18px;
            background: #fff;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
        }
        .research-kpi .card-body {
            padding: 22px;
        }
        .research-kpi h6 {
            color: #6b7a90;
            margin-bottom: 10px;
        }
        .research-kpi h2 {
            font-weight: 800;
            margin-bottom: 0;
        }
        .research-file-card {
            border: 1px solid #e8edf5;
            border-radius: 22px;
            box-shadow: 0 14px 35px rgba(15, 23, 42, 0.06);
            overflow: hidden;
            height: 100%;
        }
        .research-file-card .card-body {
            padding: 22px;
        }
        .research-meta {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }
        .research-meta-item {
            background: #f8fbff;
            border-radius: 14px;
            padding: 12px 14px;
        }
        .research-meta-item span {
            display: block;
            color: #77859b;
            font-size: 12px;
            margin-bottom: 4px;
        }
        .research-meta-item strong {
            color: #213047;
        }
        .research-visit-list {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px dashed #dbe4f0;
        }
        .research-visit-item {
            padding: 10px 0;
            border-bottom: 1px dashed #edf2f7;
        }
        .research-visit-item:last-child {
            border-bottom: 0;
            padding-bottom: 0;
        }
    </style>

    <div class="research-toolbar">
        <div>
            <h3 class="mb-1">إدارة ومتابعة ملفات البحث</h3>
            <p class="text-muted mb-0">كل المستفيدين المضاف لهم ملف بحث مع حالة التنفيذ والزيارات والنتيجة النهائية.</p>
        </div>
        <div class="d-flex flex-wrap" style="gap: 10px;">
            <a href="{{ route('case-research.create') }}" class="btn btn-primary btn-pill">
                <i class="fe fe-plus ml-1"></i> إضافة ملف بحث
            </a>
            <a href="{{ route('case-research.researchers') }}" class="btn btn-outline-info btn-pill">
                <i class="fe fe-users ml-1"></i> الباحثون
            </a>
            <a href="{{ route('case-research.workload') }}" class="btn btn-outline-dark btn-pill">
                <i class="fe fe-bar-chart-2 ml-1"></i> عبء العمل
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-2 col-md-4 col-6">
            <div class="research-kpi card">
                <div class="card-body">
                    <h6>إجمالي المستفيدين</h6>
                    <h2>{{ number_format($stats['total_beneficiaries']) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="research-kpi card">
                <div class="card-body">
                    <h6>إجمالي الملفات</h6>
                    <h2>{{ number_format($stats['total']) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="research-kpi card">
                <div class="card-body">
                    <h6>بدون ملف بحث</h6>
                    <h2 class="text-dark">{{ number_format($stats['without_file']) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="research-kpi card">
                <div class="card-body">
                    <h6>جديد</h6>
                    <h2 class="text-primary">{{ number_format($stats['new']) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="research-kpi card">
                <div class="card-body">
                    <h6>جاري</h6>
                    <h2 class="text-warning">{{ number_format($stats['in_progress']) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="research-kpi card">
                <div class="card-body">
                    <h6>متأخر</h6>
                    <h2 class="text-danger">{{ number_format($stats['delayed']) }}</h2>
                </div>
            </div>
        </div>
        <div class="col-xl-2 col-md-4 col-6">
            <div class="research-kpi card">
                <div class="card-body">
                    <h6>مكتمل</h6>
                    <h2 class="text-success">{{ number_format($stats['completed']) }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <form method="GET" action="{{ route('case-research.index') }}">
                <div class="row align-items-end">
                    <div class="col-lg-3">
                        <label class="form-label">بحث</label>
                        <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="اسم المستفيد أو الكود أو رقم الملف">
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label">الباحث</label>
                        <select class="form-control select2" name="social_researcher_id">
                            <option value="">الكل</option>
                            @foreach($researchers as $researcher)
                                <option value="{{ $researcher->id }}" {{ (string) request('social_researcher_id') === (string) $researcher->id ? 'selected' : '' }}>
                                    {{ $researcher->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label">الحالة</label>
                        <select class="form-control" name="status">
                            <option value="">الكل</option>
                            <option value="without_file" {{ request('status') === 'without_file' ? 'selected' : '' }}>بدون ملف بحث</option>
                            @foreach($statusLabels as $key => $label)
                                <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label">النتيجة</label>
                        <select class="form-control" name="final_result">
                            <option value="">الكل</option>
                            @foreach($resultLabels as $key => $label)
                                <option value="{{ $key }}" {{ request('final_result') === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <div class="d-flex gap-3">
                            <button class="btn btn-primary"><i class="fe fe-search"></i></button>
                            <a href="{{ route('case-research.index') }}" class="btn btn-outline-secondary">حذف البحث</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row mt-4">
        @forelse($beneficiaries as $beneficiary)
            @php
                $file = $beneficiary->latestCaseResearchFile;
                $userName = $beneficiary->husband_name ?: ($beneficiary->wife_name ?: 'بدون اسم');
                $badgeClass = $file ? ($statusClasses[$file->status] ?? 'secondary') : 'dark';
            @endphp
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card research-file-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <div class="text-muted small mb-1">رقم الملف</div>
                                <h5 class="mb-1">{{ $file?->file_number ?: 'لم يتم فتح ملف بعد' }}</h5>
                                <div class="badge badge-{{ $badgeClass }}">{{ $file ? ($statusLabels[$file->status] ?? $file->status) : 'بدون ملف بحث' }}</div>
                            </div>
                            @if($file)
                                <a href="{{ route('case-research.edit', $file->id) }}" class="btn btn-sm btn-outline-primary">إدارة</a>
                            @else
                                <a href="{{ route('case-research.create', ['user_id' => $beneficiary->id]) }}" class="btn btn-sm btn-primary">فتح ملف</a>
                            @endif
                        </div>

                        <div class="research-meta">
                            <div class="research-meta-item">
                                <span>المستفيد</span>
                                <strong>{{ $userName }}</strong>
                                <div class="small text-muted mt-1">{{ $beneficiary->beneficiary_code ?: 'بدون كود' }}</div>
                            </div>
                            <div class="research-meta-item">
                                <span>الباحث</span>
                                <strong>{{ $file?->researcher?->name ?: 'غير محدد' }}</strong>
                            </div>
                            <div class="research-meta-item">
                                <span>تاريخ البدء</span>
                                <strong>{{ optional($file?->started_at)->format('Y-m-d') ?: '-' }}</strong>
                            </div>
                            <div class="research-meta-item">
                                <span>النهاية المتوقعة</span>
                                <strong>{{ optional($file?->expected_end_at)->format('Y-m-d') ?: '-' }}</strong>
                            </div>
                        </div>

                        <div class="mt-3">
                            <span class="text-muted d-block small mb-1">النتيجة النهائية</span>
                            <strong>{{ $file && $file->final_result ? ($resultLabels[$file->final_result] ?? $file->final_result) : 'لم تُحدد بعد' }}</strong>
                        </div>

                        @if($file && $file->delay_reason)
                            <div class="alert alert-danger-light mt-3 mb-0">
                                <strong>سبب التأخر:</strong> {{ $file->delay_reason }}
                            </div>
                        @endif

                        <div class="research-visit-list">
                            <div class="d-flex justify-content-between mb-2">
                                <strong>محطات البحث والزيارات</strong>
                                <span class="badge badge-light">{{ $file?->visits?->count() ?: 0 }}</span>
                            </div>
                            @if($file)
                                @forelse($file->visits->take(3) as $visit)
                                    <div class="research-visit-item">
                                        <div class="small text-muted">{{ optional($visit->visited_at)->format('Y-m-d') }}</div>
                                        <div>{{ $visit->notes ?: 'بدون ملاحظات' }}</div>
                                    </div>
                                @empty
                                    <div class="text-muted small">لا توجد زيارات مسجلة حتى الآن.</div>
                                @endforelse
                            @else
                                <div class="text-muted small">هذا المستفيد لم يتم فتح ملف بحث له بعد.</div>
                            @endif
                        </div>

                        @if(!$file)
                            <div class="alert alert-light border mt-3 mb-0">
                                المستفيد ظاهر هنا حتى قبل إنشاء الملف، ويمكن فتح ملف البحث له مباشرة من نفس الشاشة.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-light border text-center">لا توجد نتائج مطابقة للفلاتر الحالية.</div>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center">
        {{ $beneficiaries->links() }}
    </div>
@endsection
