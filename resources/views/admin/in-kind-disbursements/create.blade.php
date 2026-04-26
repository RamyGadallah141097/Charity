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

                .beneficiary-picker__filters {
                    display: grid;
                    grid-template-columns: minmax(220px, 280px) 1fr auto;
                    gap: 12px;
                    align-items: center;
                }

                .beneficiary-picker__search input,
                .beneficiary-picker__search select {
                    width: 100%;
                    height: 46px;
                    border: 1px solid #dbe3f7;
                    border-radius: 10px;
                    padding: 0 14px;
                    color: #24335b;
                    background: #fff;
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

                .beneficiary-picker__meta {
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

                .beneficiary-picker__select-all {
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    white-space: nowrap;
                    font-weight: 700;
                    color: #24335b;
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
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>المستفيد</label>
                                        @php
                                            $selectedUserIds = collect(old('user_ids', []))->map(fn ($id) => (string) $id);
                                            $selectedCategoryId = old('beneficiary_category_filter');
                                        @endphp
                                        <div class="beneficiary-picker" id="beneficiaryPicker">
                                            <div class="beneficiary-picker__search">
                                                <div class="beneficiary-picker__filters">
                                                    <select id="beneficiaryCategoryFilter" name="beneficiary_category_filter" class="no-select2">
                                                        <option value="">كل التصنيفات</option>
                                                        @foreach($beneficiaryCategories as $category)
                                                            <option value="{{ $category->id }}" {{ (string) $selectedCategoryId === (string) $category->id ? 'selected' : '' }}>
                                                                {{ $category->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <input type="text" id="beneficiarySearch" placeholder="ابحث عن مستفيد">
                                                    <label class="beneficiary-picker__select-all">
                                                        <input type="checkbox" id="beneficiarySelectAll">
                                                        <span>تحديد الكل</span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="beneficiary-picker__list">
                                                @foreach ($users as $user)
                                                    @php
                                                        $userName = $user->husband_name ?: $user->wife_name;
                                                        $isSelected = $selectedUserIds->contains((string) $user->id);
                                                    @endphp
                                                    <label class="beneficiary-picker__row {{ $isSelected ? 'is-selected' : '' }}"
                                                        data-name="{{ $userName }}"
                                                        data-category-id="{{ $user->beneficiary_category_id }}">
                                                        <input type="checkbox"
                                                            class="beneficiary-picker__checkbox"
                                                            name="user_ids[]"
                                                            value="{{ $user->id }}"
                                                            {{ $isSelected ? 'checked' : '' }}>
                                                        <span class="beneficiary-picker__name">{{ $userName }}</span>
                                                        <span class="beneficiary-picker__meta">{{ $user->beneficiary_code ?: 'بدون كود' }}</span>
                                                    </label>
                                                @endforeach
                                                <div class="beneficiary-picker__empty" id="beneficiaryEmptyState">لا يوجد مستفيد بهذا الاسم أو التصنيف</div>
                                            </div>
                                        </div>
                                        <div class="in-kind-balance-note text-muted">يمكنك اختيار مستفيد واحد أو أكثر، وسيتم صرف نفس الكمية لكل مستفيد محدد.</div>
                                    </div>
                                </div>

                                <div class="col-md-12 mt-3">
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
            const $beneficiaryRows = $('.beneficiary-picker__row');
            const $beneficiaryCheckboxes = $('.beneficiary-picker__checkbox');
            const $beneficiarySearch = $('#beneficiarySearch');
            const $beneficiaryCategoryFilter = $('#beneficiaryCategoryFilter');
            const $beneficiarySelectAll = $('#beneficiarySelectAll');
            const $emptyState = $('#beneficiaryEmptyState');

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

            function syncSelectedRows() {
                $beneficiaryRows.each(function() {
                    const $row = $(this);
                    $row.toggleClass('is-selected', $row.find('.beneficiary-picker__checkbox').is(':checked'));
                });
            }

            function syncSelectAllState() {
                const $visibleRows = $beneficiaryRows.filter(function() {
                    return $(this).is(':visible');
                });

                if (!$visibleRows.length) {
                    $beneficiarySelectAll.prop('checked', false).prop('indeterminate', false);
                    return;
                }

                const visibleCheckedCount = $visibleRows.find('.beneficiary-picker__checkbox:checked').length;
                const allVisibleChecked = visibleCheckedCount === $visibleRows.length;
                const someVisibleChecked = visibleCheckedCount > 0 && !allVisibleChecked;

                $beneficiarySelectAll
                    .prop('checked', allVisibleChecked)
                    .prop('indeterminate', someVisibleChecked);
            }

            function applyBeneficiaryFilters() {
                const searchTerm = $beneficiarySearch.val().trim().toLowerCase();
                const categoryId = String($beneficiaryCategoryFilter.val() || '');
                let visibleRows = 0;

                $beneficiaryRows.each(function() {
                    const $row = $(this);
                    const name = String($row.data('name') || '').toLowerCase();
                    const rowCategoryId = String($row.attr('data-category-id') || '');
                    const matchesSearch = !searchTerm || name.includes(searchTerm);
                    const matchesCategory = !categoryId || rowCategoryId === categoryId;
                    const shouldShow = matchesSearch && matchesCategory;

                    $row.toggle(shouldShow);
                    if (shouldShow) {
                        visibleRows++;
                    }
                });

                $emptyState.toggle(visibleRows === 0);
                syncSelectAllState();
            }

            syncCategoryState();
            $category.on('change', syncCategoryState);
            $beneficiaryCheckboxes.on('change', function() {
                syncSelectedRows();
                syncSelectAllState();
            });
            $beneficiarySearch.on('input', applyBeneficiaryFilters);
            $beneficiaryCategoryFilter.on('change', applyBeneficiaryFilters);
            $beneficiarySelectAll.on('change', function() {
                const shouldCheck = $(this).is(':checked');

                $beneficiaryRows.filter(':visible').each(function() {
                    $(this).find('.beneficiary-picker__checkbox').prop('checked', shouldCheck);
                });

                syncSelectedRows();
                syncSelectAllState();
            });
            syncSelectedRows();
            applyBeneficiaryFilters();
        });
    </script>
@endsection
