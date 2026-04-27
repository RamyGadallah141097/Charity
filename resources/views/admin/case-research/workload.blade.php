@extends('admin/layouts/master')

@section('title')
    {{ isset($setting) ? $setting->title : '' }} | عبء العمل على الباحثين
@endsection

@section('page_name')
    عبء العمل على الباحثين
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title mb-0">متابعة الحمل التشغيلي للباحثين</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>الباحث</th>
                            <th>المشرف</th>
                            <th>إجمالي الحالات</th>
                            <th>الحالات المكتملة</th>
                            <th>الحالات المتأخرة</th>
                            <th>متوسط زمن الإنجاز</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($researchers as $researcher)
                            <tr>
                                <td>
                                    <strong>{{ $researcher->name }}</strong>
                                    <div class="small text-muted">{{ $researcher->email ?: ($researcher->phone ?: '-') }}</div>
                                </td>
                                <td>{{ $researcher->supervisor?->name ?: 'غير محدد' }}</td>
                                <td>{{ number_format($researcher->case_research_files_count) }}</td>
                                <td><span class="badge badge-success">{{ number_format($researcher->completed_cases_count) }}</span></td>
                                <td><span class="badge badge-danger">{{ number_format($researcher->delayed_cases_count) }}</span></td>
                                <td>
                                    {{ $researcher->average_completion_days !== null ? number_format($researcher->average_completion_days, 1) . ' يوم' : 'لا يوجد' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">لا يوجد باحثون لعرض عبء العمل.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
