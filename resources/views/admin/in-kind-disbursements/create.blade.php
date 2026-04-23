@extends('admin/layouts/master')

@section('title')
    {{ isset($setting->title) ? $setting->title : '' }} | صرف تبرع عيني
@endsection

@section('page_name')
    صرف تبرع عيني
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <style>
                .in-kind-form-card {
                    border: 0;
                    border-radius: 18px;
                    box-shadow: 0 14px 35px rgba(67, 97, 238, 0.08);
                    overflow: hidden;
                }

                .in-kind-form-card .card-header {
                    background: linear-gradient(135deg, #f8faff 0%, #eef3ff 100%);
                    border-bottom: 1px solid #e7ecfb;
                    padding: 22px 28px;
                }

                .in-kind-form-card .card-body {
                    padding: 24px;
                }

                .in-kind-section {
                    background: #ffffff;
                    border: 1px solid #edf1fb;
                    border-radius: 16px;
                    padding: 22px;
                    margin-bottom: 22px;
                }

                .in-kind-section__title {
                    margin-bottom: 16px;
                    font-size: 18px;
                    font-weight: 700;
                    color: #1f2a56;
                }

                .in-kind-form-card label {
                    font-weight: 700;
                    color: #24335b;
                    margin-bottom: 10px;
                }

                .in-kind-form-card .form-control,
                .in-kind-form-card .select2-container--default .select2-selection--single {
                    min-height: 48px;
                    border-radius: 12px;
                    border-color: #dbe3f7;
                }

                .in-kind-balance-note {
                    margin-top: 10px;
                    color: #1f8f4d;
                    font-weight: 700;
                }

                .in-kind-actions {
                    display: flex;
                    justify-content: flex-start;
                    gap: 12px;
                    margin-top: 28px;
                }

                .in-kind-actions .btn {
                    min-width: 120px;
                    border-radius: 12px;
                    padding: 10px 18px;
                    font-weight: 700;
                }
            </style>

            <div class="card in-kind-form-card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="card-title mb-0">بيانات صرف التبرع العيني</h3>
                    <span class="badge badge-primary-light">خارج من الخزنة العينية</span>
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

                    <form method="POST" action="{{ route('in-kind-disbursements.store') }}">
                        @csrf

                        <div class="in-kind-section">
                            <div class="in-kind-section__title">المستفيد والصنف</div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>المستفيد</label>
                                        <select name="user_id" class="form-control select2" required>
                                            <option value="">اختر المستفيد</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                    {{ $user->wife_name ?: $user->husband_name }}{{ $user->beneficiary_code ? ' - ' . $user->beneficiary_code : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>صنف التبرع العيني</label>
                                        <select name="donation_category_id" id="donation_category_id" class="form-control select2" required>
                                            <option value="">اختر الصنف</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('donation_category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="in-kind-balance-note" id="categoryBalanceNote"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="in-kind-section">
                            <div class="in-kind-section__title">كمية الصرف</div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>كمية الصرف</label>
                                        <input type="number" step="0.01" min="0.01" name="quantity" class="form-control" value="{{ old('quantity') }}" placeholder="أدخل الكمية" required>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group mb-0">
                                        <label>ملاحظات</label>
                                        <input type="text" name="comment" class="form-control" value="{{ old('comment') }}" placeholder="مثال: صرف بطاطين لحالة عاجلة">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="in-kind-actions">
                            <button type="submit" class="btn btn-primary">صرف</button>
                            <a href="{{ route('in-kind-disbursements.index') }}" class="btn btn-secondary">رجوع</a>
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
            const balances = @json($balances);
            const $category = $('#donation_category_id');

            $('.select2').select2({
                width: '100%'
            });

            function syncCategoryState() {
                const selectedCategoryId = $category.val();
                const balance = balances[selectedCategoryId];

                if (balance) {
                    $('#categoryBalanceNote').text('المتاح: ' + Number(balance.available).toLocaleString('en-US') + (balance.unit ? ' ' + balance.unit : ''));
                } else {
                    $('#categoryBalanceNote').text('');
                }
            }

            syncCategoryState();
            $category.on('change', syncCategoryState);
        });
    </script>
@endsection
