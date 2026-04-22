@extends('admin/layouts/master')

@section('title')
    {{ isset($setting) ? $setting->title : '' }}
    | الاعانات
@endsection
@section('page_name')
    الاعانات
@endsection
@section('content')
    <div class="row">

        <div class="col-md-12 col-lg-12">
            <style>
                .subventions-summary-card {
                    border: 0;
                    border-radius: 22px;
                    overflow: hidden;
                    background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 55%, #a855f7 100%);
                    box-shadow: 0 18px 45px rgba(109, 40, 217, 0.20);
                }

                .subventions-summary-card__body {
                    padding: 26px 30px;
                    color: #fff;
                }

                .subventions-summary-card__eyebrow {
                    font-size: 14px;
                    opacity: 0.9;
                    margin-bottom: 10px;
                }

                .subventions-summary-card__title {
                    font-size: 28px;
                    font-weight: 800;
                    margin-bottom: 24px;
                }

                .subventions-summary-tile {
                    background: rgba(255, 255, 255, 0.12);
                    border: 1px solid rgba(255, 255, 255, 0.16);
                    border-radius: 18px;
                    padding: 18px 20px;
                    backdrop-filter: blur(4px);
                }

                .subventions-summary-tile__label {
                    font-size: 15px;
                    opacity: 0.92;
                    margin-bottom: 8px;
                }

                .subventions-summary-tile__value {
                    font-size: 34px;
                    font-weight: 800;
                    line-height: 1.1;
                }

                .subventions-summary-tile__currency {
                    font-size: 15px;
                    font-weight: 600;
                    opacity: 0.9;
                }

                @media (max-width: 767.98px) {
                    .subventions-summary-card__body {
                        padding: 20px;
                    }
                }
            </style>

            <div class="card subventions-summary-card mb-4">
                <div class="subventions-summary-card__body">
                    <div class="subventions-summary-card__eyebrow">ملخص الصرف</div>
                    <div class="subventions-summary-card__title">{{ $periodLabel }}</div>
                    <div class="subventions-summary-tile">
                        <div class="subventions-summary-tile__label">إجمالي المنصرف</div>
                        <div class="subventions-summary-tile__value">{{ number_format($totalSpent, 0) }}</div>
                        <div class="subventions-summary-tile__currency">EGP</div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">تخصيص الاعانات ل قائمة المقبولين</h3>

                    <div class="">
                        <a href="{{ route('showSubventions') }}" id="print-selected-subventions" title="طباعة" class="btn btn-success btn-icon text-white">
                            طباعة
                            <i class="fa fa-print"></i>
                        </a>

                        <a href="{{ route('subventions.create') }}" class="btn btn-secondary btn-icon text-white">
                            <span>
                                <i class="fe fe-plus"></i>
                            </span> اضافة جديد
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form class="mb-5" id="searchByDate" method="GET" action="{{ route('subventions.index') }}">
                        <div class="input-group">
                            <label class="input-group-text">من تاريخ</label>
                            <input style="margin-left:20px" type="date" name="from" class="form-control "
                                value="{{ $fromDate?->format('Y-m-d') }}"
                                placeholder="من تاريخ">
                            <label class="input-group-text">الى تاريخ</label>
                            <input style="margin-left:20px" type="date" name="to" class="form-control"
                                value="{{ $toDate?->format('Y-m-d') }}"
                                placeholder="الى تاريخ">
                            <button type="submit" class="btn btn-primary">بحث</button>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table table-striped table-bordered text-nowrap w-100" id="dataTable">
                            <thead>
                                <tr class="fw-bolder text-muted bg-light">
                                    <th class="min-w-25px text-center">
                                        <input type="checkbox" id="select_all_subventions">
                                    </th>
                                    <th class="min-w-25px">#</th>
                                    <th class="min-w-50px">كود الحالة</th>
                                    <th class="min-w-50px">المستفيد</th>
                                    <th class="min-w-125px">القيمه</th>
                                    <th class="min-w-125px">شهري/ مرة</th>
                                    <th class="min-w-125px">التاريخ</th>
                                    <th class="min-w-125px">السبب</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
    @include('admin/layouts/myAjaxHelper')
@endsection
@section('ajaxCalls')
    <script>
        var columns = [{
                data: 'row_checkbox',
                name: 'row_checkbox',
                orderable: false,
                searchable: false
            },
            {
                data: null,
                name: 'index',
                render: function(data, type, row, meta) {
                    return meta.row + 1;
                },
                orderable: false,
                searchable: true
                },
            {
                data: 'beneficiary_code',
                name: 'beneficiary_code'
            },
            {
                data: 'user_id',
                name: 'user_id'
            },
            {
                data: 'price',
                name: 'price'
            },
            {
                data: 'type',
                name: 'type'
            },
            {
                data: 'created_at',
                name: 'created_at'
            },
            {
                data: 'comment',
                name: 'comment'
            },
        ]
        showData("{{ route('subventions.index', ['from' => $fromDate->format('Y-m-d'), 'to' => $toDate->format('Y-m-d')]) }}", columns);
    </script>
    <script>
        $(document).ready(function() {
            var table = $('#dataTable').DataTable();

            $(document).on('change', '#select_all_subventions', function() {
                $('.subvention-row-checkbox').prop('checked', $(this).is(':checked'));
            });

            $(document).on('change', '.subvention-row-checkbox', function() {
                let total = $('.subvention-row-checkbox').length;
                let checked = $('.subvention-row-checkbox:checked').length;
                $('#select_all_subventions').prop('checked', total > 0 && total === checked);
            });

            $('#print-selected-subventions').on('click', function(e) {
                e.preventDefault();

                let ids = $('.subvention-row-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                if (ids.length === 0) {
                    toastr.error('يرجى تحديد مستفيد واحد على الأقل للطباعة');
                    return;
                }

                let url = new URL($(this).attr('href'), window.location.origin);
                url.searchParams.set('ids', ids.join(','));
                window.open(url.toString(), '_blank');
            });
        });
    </script>
@endsection
