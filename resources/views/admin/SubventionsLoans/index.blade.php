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
                    background: linear-gradient(135deg, #0f766e 0%, #0ea5a4 55%, #2dd4bf 100%);
                    box-shadow: 0 18px 45px rgba(13, 148, 136, 0.18);
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
            </style>

            <div class="card subventions-summary-card mb-4">
                <div class="subventions-summary-card__body">
                    <div class="subventions-summary-card__eyebrow">ملخص الإعانات الفردية</div>
                    <div class="subventions-summary-card__title">{{ $periodLabel }}</div>
                    <div class="subventions-summary-tile">
                        <div class="subventions-summary-tile__label">إجمالي المنصرف</div>
                        <div class="subventions-summary-tile__value">{{ number_format($totalSpent, 0) }} EGP</div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">الإعانات الفردية</h3>
                    <div class="">
                        <a href="{{ route('SubventionsLoans.create') }}" class="btn btn-secondary btn-icon text-white">
                            <span>
                                <i class="fe fe-plus"></i>
                            </span> اضافة جديد
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form class="mb-5" method="GET" action="{{ route('SubventionsLoans.index') }}">
                        <div class="input-group">
                            <label class="input-group-text">من تاريخ</label>
                            <input style="margin-left:20px" type="date" name="from" class="form-control"
                                value="{{ $fromDate?->format('Y-m-d') }}" placeholder="من تاريخ">
                            <label class="input-group-text">الى تاريخ</label>
                            <input style="margin-left:20px" type="date" name="to" class="form-control"
                                value="{{ $toDate?->format('Y-m-d') }}" placeholder="الى تاريخ">
                            <button type="submit" class="btn btn-primary">بحث</button>
                        </div>
                    </form>
                    <div class="table-responsive">
                        <!--begin::Table-->
                        <table class="table table-striped table-bordered text-nowrap w-100" id="dataTable">
                            <thead>
                                <tr class="fw-bolder text-muted bg-light">
                                    <th class="min-w-25px">#</th>
                                    <th class="min-w-50px">كود الحالة</th>
                                    <th class="min-w-50px">المستفيد</th>
                                    <th class="min-w-125px">المبلغ</th>
                                    <th class="min-w-125px">النوع</th>
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
                data: null,
                name: 'index',
                render: function(data, type, row, meta) {
                    return meta.row + 1;
                },
                orderable: false,
                searchable: false
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
        showData("{{ route('SubventionsLoans.index', ['from' => $fromDate->format('Y-m-d'), 'to' => $toDate->format('Y-m-d')]) }}", columns);
    </script>
@endsection
