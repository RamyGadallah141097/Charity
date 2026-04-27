@extends('admin/layouts/master')

@section('title')
    {{ $setting->title ?? '' }} | التعريفات العامة
@endsection

@section('page_name')
    التعريفات العامة
@endsection

@section('content')
    <div class="row">
        @can('references.index')
            @foreach ($lookups as $key => $lookup)
                <div class="col-md-6 col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div>
                                    <h4 class="mb-1">{{ $lookup['title'] }}</h4>
                                    <p class="text-muted mb-0">جدول مرجعي مستقل قابل لإعادة الاستخدام.</p>
                                </div>
                                <span class="badge badge-primary">{{ $counts[$key] ?? 0 }}</span>
                            </div>
                            <a href="{{ route('references.index', $key) }}" class="btn btn-outline-primary btn-block">
                                إدارة {{ $lookup['title'] }}
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        @endcan
    </div>
@endsection
