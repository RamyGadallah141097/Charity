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

                .subvention-create-card .select2-container .select2-search--inline .select2-search__field {
                    margin-top: 7px;
                }

                .subvention-create-card .input-group > .form-control,
                .subvention-create-card .input-group > .form-select {
                    min-height: 48px;
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
                                <select name="user_ids[]" class="form-control select2" multiple required
                                    data-placeholder="اختيار المستفيدين">
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" data-amount="{{ $user->monthly_subvention_amount ?? 0 }}"
                                            {{ collect(old('user_ids', []))->contains($user->id) ? 'selected' : '' }}>
                                            {{ $user->wife_name ?: $user->husband_name }}
                                        </option>
                                    @endforeach
                                </select>
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
            const $beneficiaries = $('select[name="user_ids[]"]');
            const formatAmount = new Intl.NumberFormat('en-US');

            $beneficiaries.select2({
                placeholder: $beneficiaries.data('placeholder'),
                closeOnSelect: false,
                matcher: function(params, data) {
                    if (!data.id) {
                        return data;
                    }

                    if (data.element && data.element.selected) {
                        return null;
                    }

                    if ($.trim(params.term) === '') {
                        return data;
                    }

                    if (data.text && data.text.toLowerCase().indexOf(params.term.toLowerCase()) > -1) {
                        return data;
                    }

                    return null;
                }
            });

            function calculateMonthlyTotal() {
                let total = 0;

                $beneficiaries.find('option:selected').each(function() {
                    total += parseFloat($(this).data('amount') || 0);
                });

                $('#monthly_total_amount').val(formatAmount.format(total) + ' EGP');
            }

            $beneficiaries.on('select2:select select2:unselect change', function() {
                calculateMonthlyTotal();
                $(this).select2('close');
            });

            calculateMonthlyTotal();
        });
    </script>
@endsection
