@extends('admin/layouts/master')

@section('title')
    {{ isset($setting) ? $setting->title : '' }} | الباحثون الاجتماعيون
@endsection

@section('page_name')
    الباحثون الاجتماعيون
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0">إضافة باحث جديد</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('case-research.researchers.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>اسم الباحث</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>ربط بحساب مشرف</label>
                            <select name="admin_id" class="form-control select2">
                                <option value="">بدون ربط</option>
                                @foreach($researcherAdmins as $admin)
                                    <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>المشرف المباشر</label>
                            <select name="supervisor_admin_id" class="form-control select2">
                                <option value="">بدون تحديد</option>
                                @foreach($admins as $admin)
                                    <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>الهاتف</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>البريد الإلكتروني</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>ملاحظات</label>
                            <textarea name="notes" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label class="custom-switch">
                                <input type="checkbox" name="is_active" class="custom-switch-input" value="1" checked>
                                <span class="custom-switch-indicator"></span>
                                <span class="custom-switch-description">نشط</span>
                            </label>
                        </div>
                        <button class="btn btn-primary">حفظ الباحث</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title mb-0">قائمة الباحثين</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>الباحث</th>
                                    <th>المشرف المرتبط</th>
                                    <th>المشرف المباشر</th>
                                    <th>الحالات</th>
                                    <th>الحالة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($researchers as $researcher)
                                    <tr>
                                        <td>
                                            <strong>{{ $researcher->name }}</strong>
                                            <div class="small text-muted">{{ $researcher->phone ?: '-' }}</div>
                                        </td>
                                        <td>{{ $researcher->admin?->name ?: 'غير مرتبط' }}</td>
                                        <td>{{ $researcher->supervisor?->name ?: 'غير محدد' }}</td>
                                        <td>{{ number_format($researcher->case_research_files_count) }}</td>
                                        <td>
                                            @if($researcher->is_active)
                                                <span class="badge badge-success">نشط</span>
                                            @else
                                                <span class="badge badge-secondary">موقوف</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">لا يوجد باحثون مسجلون حتى الآن.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
