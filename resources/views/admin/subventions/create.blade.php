@extends('admin/layouts/master')

@section('title')
    {{ isset($setting->title) ? $setting->title : '' }} | إنشاء إعانة
@endsection

@section('page_name')
    إنشاء إعانة
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
                .subvention-create-card .form-control-label,
                .subvention-create-card label {
                    font-weight: 700;
                    color: #24335b;
                    margin-bottom: 10px;
                }

                .subvention-create-card .form-control,
                .subvention-create-card .form-select {
                    min-height: 48px;
                    border-radius: 12px;
                    border-color: #dbe3f7;
                }

                .subvention-create-card .input-group > .form-control,
                .subvention-create-card .input-group > .form-select {
                    min-height: 48px;
                }

                .beneficiary-picker {
                    border: 1px solid #dbe3f7;
                    border-radius: 14px;
                    overflow: hidden;
                    background: #ffffff;
                }

                .beneficiary-picker__search {
                    padding: 12px;
                    border-bottom: 1px solid #edf1fb;
                    background: #fbfcff;
                }

                .beneficiary-picker__search input {
                    width: 100%;
                    height: 46px;
                    border: 1px solid #dbe3f7;
                    border-radius: 10px;
                    padding: 0 14px;
                    color: #24335b;
                    outline: none;
                }

                .beneficiary-picker__list {
                    max-height: 285px;
                    overflow-y: auto;
                    padding: 8px;
                }

                .beneficiary-picker__row {
                    display: flex;
                    align-items: center;
                    gap: 12px;
                    padding: 12px 14px;
                    margin-bottom: 6px;
                    border: 1px solid transparent;
                    border-radius: 12px;
                    cursor: pointer;
                    transition: background 0.18s ease, border-color 0.18s ease, box-shadow 0.18s ease;
                }

                .beneficiary-picker__row:hover {
                    background: #f6f8ff;
                    border-color: #e2e8fb;
                }

                .beneficiary-picker__row.is-selected {
                    background: #fef1f1;
                    border-color: #f5b9b9;
                    box-shadow: inset 3px 0 0 #df2f2f;
                }

                .beneficiary-picker__checkbox {
                    width: 19px;
                    height: 19px;
                    accent-color: #df2f2f;
                    cursor: pointer;
                    flex-shrink: 0;
                }

                .beneficiary-picker__name {
                    flex: 1;
                    color: #24335b;
                    font-weight: 700;
                    text-align: right;
                }

                .beneficiary-picker__amount {
                    color: #7a88b6;
                    font-size: 12px;
                    white-space: nowrap;
                }

                .beneficiary-picker__empty {
                    display: none;
                    padding: 18px;
                    color: #7a88b6;
                    text-align: center;
                }

                .subvention-radio-group {
                    display: flex;
                    gap: 24px;
                    flex-wrap: wrap;
                    padding-top: 6px;
                }

                .subvention-radio-group .custom-control {
                    background: #f8faff;
                    border: 1px solid #e4eafa;
                    border-radius: 12px;
                    padding: 10px 14px 10px 36px;
                    min-width: 140px;
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

                @media (max-width: 767.98px) {
                    .subvention-create-card .card-header,
                    .subvention-create-card .card-body {
                        padding: 18px;
                    }

                    .subvention-section {
                        padding: 16px;
                    }

                    .subvention-actions {
                        flex-direction: column;
                    }

                    .subvention-actions .btn {
                        width: 100%;
                    }
                }
            </style>

            <div class="card subvention-create-card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h3 class="card-title mb-0">بيانات الإعانة</h3>
                    <span class="badge badge-primary-light">إعانة شهرية</span>
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

                    <form method="POST" action="{{ route('subventions.store') }}">
                        @csrf

                        <div class="subvention-section">
                            <div class="subvention-section__title">اختيار المستفيدين</div>
                            <div class="form-group mb-0">
                                <label class="form-label">المستفيدون المؤهلون</label>
                                @php
                                    $selectedUserIds = collect(old('user_ids', []))->map(fn ($id) => (string) $id);
                                @endphp
                                <div class="beneficiary-picker" id="beneficiaryPicker">
                                    <div class="beneficiary-picker__search">
                                        <input type="text" id="beneficiarySearch" placeholder="ابحث عن مستفيد">
                                    </div>
                                    <div class="beneficiary-picker__list">
                                        @foreach($users as $user)
                                            @php
                                                $userName = $user->husband_name ?: $user->wife_name;
                                                $isSelected = $selectedUserIds->contains((string) $user->id);
                                            @endphp
                                            <label class="beneficiary-picker__row {{ $isSelected ? 'is-selected' : '' }}"
                                                data-name="{{ $userName }}"
                                                data-amount="{{ $user->monthly_subvention_amount ?? 0 }}">
                                                <input type="checkbox"
                                                    class="beneficiary-picker__checkbox"
                                                    name="user_ids[]"
                                                    value="{{ $user->id }}"
                                                    data-amount="{{ $user->monthly_subvention_amount ?? 0 }}"
                                                    {{ $isSelected ? 'checked' : '' }}>
                                                <span class="beneficiary-picker__name">{{ $userName }}</span>
                                                <span class="beneficiary-picker__amount">{{ number_format((float) ($user->monthly_subvention_amount ?? 0), 0) }} EGP</span>
                                            </label>
                                        @endforeach
                                        <div class="beneficiary-picker__empty" id="beneficiaryEmptyState">لا يوجد مستفيد بهذا الاسم</div>
                                    </div>
                                </div>
                                <div class="subvention-helper">يتم عرض المستفيدين الذين لديهم إعانة شهرية ولم يتم الصرف لهم خلال الشهر الحالي فقط.</div>
                            </div>
                        </div>

                        <div class="subvention-section">
                            <div class="subvention-section__title">الخزنة ومبلغ الصرف</div>
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
                                        <label>إجمالي مبلغ الصرف</label>
                                        <input type="text" class="form-control" id="monthly_total_amount" value="0" readonly>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="subvention-helper">يتم حساب الإجمالي تلقائيًا من مبالغ الإعانة الشهرية المسجلة للمستفيدين المختارين.</div>
                                </div>
                            </div>
                        </div>

                        <div class="subvention-section">
                            <div class="subvention-section__title">نوعية الصرف والسبب</div>
                            <input type="hidden" name="type" value="monthly">

                            <div class="form-group mb-0">
                                <label>سبب الاعانه</label>
                                <input class="form-control" name="comment" value="{{ old('comment') }}" placeholder="اكتب سبب الإعانة">
                            </div>
                        </div>

                        <div class="subvention-actions">
                            <button type="submit" class="btn btn-primary">اضافة</button>
                            <a href="{{ route('subventions.index') }}" class="btn btn-secondary">رجوع</a>
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
            const $beneficiaryRows = $('.beneficiary-picker__row');
            const $beneficiaryCheckboxes = $('.beneficiary-picker__checkbox');
            const $beneficiarySearch = $('#beneficiarySearch');
            const $emptyState = $('#beneficiaryEmptyState');
            const formatAmount = new Intl.NumberFormat('en-US');

            function calculateMonthlyTotal() {
                let total = 0;

                $beneficiaryCheckboxes.filter(':checked').each(function() {
                    total += parseFloat($(this).data('amount') || 0);
                });

                $('#monthly_total_amount').val(formatAmount.format(total) + ' EGP');
            }

            function syncSelectedRows() {
                $beneficiaryRows.each(function() {
                    const $row = $(this);
                    $row.toggleClass('is-selected', $row.find('.beneficiary-picker__checkbox').is(':checked'));
                });
            }

            $beneficiaryCheckboxes.on('change', function() {
                syncSelectedRows();
                calculateMonthlyTotal();
            });

            $beneficiarySearch.on('input', function() {
                const searchTerm = $(this).val().trim().toLowerCase();
                let visibleRows = 0;

                $beneficiaryRows.each(function() {
                    const $row = $(this);
                    const name = String($row.data('name') || '').toLowerCase();
                    const shouldShow = !searchTerm || name.includes(searchTerm);

                    $row.toggle(shouldShow);
                    if (shouldShow) {
                        visibleRows++;
                    }
                });

                $emptyState.toggle(visibleRows === 0);
            });

            syncSelectedRows();
            calculateMonthlyTotal();
        });
    </script>
@endsection
