@extends('admin/layouts/master')

@section('title')
    {{ isset($setting->title) ? $setting->title : '' }} | إنشاء إعانة فردية
@endsection

@section('page_name')
    إنشاء إعانة فردية
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <style>
                .subvention-create-card {
                    border: 0;
                    border-radius: 18px;
                    box-shadow: 0 14px 35px rgba(67, 97, 238, 0.08);
                    overflow: hidden;
                    width: 100%;
                }

                .subvention-create-card .card-header {
                    background: linear-gradient(135deg, #f8faff 0%, #eef3ff 100%);
                    border-bottom: 1px solid #e7ecfb;
                    padding: 22px 28px;
                }

                .subvention-create-card .card-body {
                    padding: 24px;
                }

                .subvention-section {
                    background: #ffffff;
                    border: 1px solid #edf1fb;
                    border-radius: 16px;
                    padding: 22px;
                    margin-bottom: 22px;
                }

                .subvention-section__title {
                    margin-bottom: 16px;
                    font-size: 18px;
                    font-weight: 700;
                    color: #1f2a56;
                }

                .subvention-helper {
                    margin-top: 8px;
                    color: #7a88b6;
                    font-size: 13px;
                }

                .subvention-create-card .form-label,
                .subvention-create-card label {
                    font-weight: 700;
                    color: #24335b;
                    margin-bottom: 10px;
                }

                .subvention-create-card .form-control,
                .subvention-create-card .form-select,
                .subvention-create-card .select2-container--default .select2-selection--single,
                .subvention-create-card .select2-container--default .select2-selection--multiple {
                    min-height: 48px;
                    border-radius: 12px;
                    border-color: #dbe3f7;
                }

                .subvention-create-card .select2-container--default .select2-selection--multiple {
                    padding: 6px 10px;
                }

                .subvention-actions {
                    display: flex;
                    justify-content: flex-start;
                    gap: 12px;
                    margin-top: 28px;
                }

                .subvention-actions .btn {
                    min-width: 120px;
                    border-radius: 12px;
                    padding: 10px 18px;
                    font-weight: 700;
                }
            </style>

            <div class="card subvention-create-card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="card-title mb-0">بيانات الإعانة الفردية</h3>
                    <span class="badge badge-primary-light">إعانة فردية</span>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('SubventionsLoans.store') }}">
                        @csrf

                        <div class="subvention-section">
                            <div class="subvention-section__title">اختيار المستفيدين</div>
                            <div class="form-group mb-0">
                                <label class="form-label">المستفيدون</label>
                                <select name="user_ids[]" class="form-control select2" multiple required data-placeholder="اختيار المستفيدين">
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ collect(old('user_ids', []))->contains($user->id) ? 'selected' : '' }}>
                                            {{ $user->wife_name ?: $user->husband_name }}{{ $user->beneficiary_code ? ' - ' . $user->beneficiary_code : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="subvention-helper">يمكن صرف إعانات فردية لنفس المستفيد أكثر من مرة بدون أي قيود.</div>
                            </div>
                        </div>

                        <div class="subvention-section">
                            <div class="subvention-section__title">الخزنة والمبلغ</div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-0">
                                        <label>الخزنة التي سيتم الصرف منها</label>
                                        <select name="donation_type_id" class="form-control">
                                            <option value="">اختر الخزنة</option>
                                            @foreach($lockerTypes as $lockerType)
                                                <option value="{{ $lockerType->id }}" {{ old('donation_type_id') == $lockerType->id ? 'selected' : '' }}>
                                                    {{ $lockerType->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-0">
                                        <label>مبلغ الإعانة لكل مستفيد</label>
                                        <input type="number" step="0.01" min="0.01" class="form-control" name="price" value="{{ old('price') }}" placeholder="أدخل المبلغ">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="subvention-helper">إذا اخترت أكثر من مستفيد، سيتم صرف نفس المبلغ لكل مستفيد من المختارين.</div>
                                </div>
                            </div>
                        </div>

                        <div class="subvention-section">
                            <div class="subvention-section__title">سبب الإعانة</div>
                            <div class="form-group mb-0">
                                <label>سبب الاعانة</label>
                                <input class="form-control" name="comment" value="{{ old('comment') }}" placeholder="اكتب سبب الإعانة">
                            </div>
                        </div>

                        <div class="subvention-actions">
                            <button type="submit" class="btn btn-primary">اضافة</button>
                            <a href="{{ route('SubventionsLoans.index') }}" class="btn btn-secondary">رجوع</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('select[name="user_ids[]"]').select2({
                placeholder: 'اختيار المستفيدين',
                closeOnSelect: false
            });
        });
    </script>
@endsection
