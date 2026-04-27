@extends('admin/layouts/master')

@section('title')
    {{ isset($setting) ? $setting->title : '' }}
    | الخزنة
@endsection
@section('page_name')
    الخزنة
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row align-items-end">
                        <div class="col-md-4">
                            <label for="locker_type" class="form-control-label">نوع الخزنة</label>
                            <select name="locker_type" id="locker_type" class="form-control mt-2">
                                @foreach ($lockerTypes as $lockerType)
                                    <option value="{{ $lockerType->id }}"
                                        {{ (int) $selectedTypeId === (int) $lockerType->id ? 'selected' : '' }}>
                                        {{ $lockerType->display_name ?? $lockerType->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-8">
                            <form id="searchByDate">
                                @csrf
                                <div class="input-group">
                                    <label class="input-group-text">من تاريخ</label>
                                    <input style="margin-left:20px" type="date" name="from" class="form-control"
                                        placeholder="من تاريخ">
                                    <label class="input-group-text">الى تاريخ</label>
                                    <input style="margin-left:20px" type="date" name="to" class="form-control"
                                        placeholder="الى تاريخ">
                                    <button type="submit" class="btn btn-primary">بحث</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            @if ($isCashLocker)
                <div class="w-50 mt-4">
                    <label for="type">العمليات </label>
                    <select name="type" id="type" class="form-control mt-2">
                        <option value="all">الكل</option>
                        <option value="plus">داخل</option>
                        <option value="minus">خارج</option>
                    </select>
                </div>
            @endif

            @if ($isCashLocker)
                <div class="card-body w-100">
                    <div class="row w-100">
                        <div class="col-12">
                            <div class="card bg-secondary img-card box-secondary-shadow">
                                <div class="d-flex justify-content-between pr-3 pl-3 pt-3 w-100">
                                    <span class="text-white fs-30">المتوفر في خزنة {{ $title }}</span>
                                    <span class="text-white fs-30">{{ number_format($total, 0) }} EGP</span>
                                </div>
                                <div class="d-flex justify-content-between pr-3 pl-3 pt-3 w-100">
                                    <span class="text-white fs-30">مجموع الداخل</span>
                                    <span class="text-white fs-30">{{ number_format($totalPlus, 0) }} EGP <i
                                            class='fas fa-arrow-down'
                                            style='color: #63E6BE; font-size: 30px ;transform: rotate(45deg); margin-right: 20px;'></i></span>
                                </div>
                                <div class="d-flex justify-content-between pr-3 pl-3 pt-3 w-100">
                                    <span class="text-white fs-30">مجموع الخارج</span>
                                    <span class="text-white fs-30">{{ number_format($totalMinus, 0) }} EGP <i
                                            class='fas fa-arrow-up'
                                            style='color: #e42f2f; font-size: 30px ; transform: rotate(45deg);margin-right: 20px;'></i></span>
                                </div>

                                <div class="card-body">
                                    <div class="row text-white">
                                        <div class="col-4 text-end">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if (!$isCashLocker && $inKindCategorySummaries->isNotEmpty())
                <style>
                    .in-kind-summary-grid {
                        display: grid;
                        grid-template-columns: repeat(7, minmax(130px, 1fr));
                        gap: 10px;
                        margin: 16px 0;
                    }

                    .in-kind-summary-card {
                        position: relative;
                        overflow: hidden;
                        border: 0;
                        border-radius: 14px;
                        background:
                            radial-gradient(circle at 16% 12%, rgba(255, 255, 255, .28), transparent 28%),
                            linear-gradient(135deg, #243b6b 0%, #5967e8 62%, #7c4dff 100%);
                        box-shadow: 0 10px 22px rgba(48, 63, 159, .14);
                        color: #fff;
                        min-height: 135px;
                    }

                    .in-kind-summary-card::after {
                        content: "";
                        position: absolute;
                        width: 82px;
                        height: 82px;
                        border: 1px solid rgba(255, 255, 255, .18);
                        border-radius: 999px;
                        left: -24px;
                        bottom: -30px;
                    }

                    .in-kind-summary-card__body {
                        position: relative;
                        z-index: 1;
                        padding: 14px;
                    }

                    .in-kind-summary-card__title {
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        gap: 6px;
                        margin-bottom: 8px;
                        font-size: 14px;
                        font-weight: 800;
                        line-height: 1.35;
                    }

                    .in-kind-summary-card__badge {
                        padding: 3px 8px;
                        border-radius: 999px;
                        background: rgba(255, 255, 255, .18);
                        font-size: 10px;
                        font-weight: 700;
                        white-space: nowrap;
                    }

                    .in-kind-summary-row {
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        padding: 6px 0;
                        border-top: 1px solid rgba(255, 255, 255, .16);
                        font-size: 12px;
                    }

                    .in-kind-summary-row__value {
                        font-size: 13px;
                        font-weight: 800;
                    }

                    .in-kind-summary-row--remaining .in-kind-summary-row__value {
                        color: #dfffe9;
                    }

                    @media (max-width: 1399px) {
                        .in-kind-summary-grid {
                            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                        }
                    }

                    .locker-movement {
                        display: inline-flex;
                        align-items: center;
                        gap: 6px;
                        margin-right: 10px;
                        font-weight: 800;
                        white-space: nowrap;
                    }

                    .locker-movement i {
                        font-size: 22px;
                        transform: rotate(45deg);
                    }

                    .locker-movement--in {
                        color: #22c55e;
                    }

                    .locker-movement--out {
                        color: #ef4444;
                    }
                </style>

                <div class="in-kind-summary-grid">
                    @foreach ($inKindCategorySummaries as $summary)
                        @php
                            $unit = $summary['unit'] ? ' ' . $summary['unit'] : '';
                        @endphp
                        <div class="in-kind-summary-card">
                            <div class="in-kind-summary-card__body">
                                <div class="in-kind-summary-card__title">
                                    <span>خزنة {{ $summary['name'] }}</span>
                                    @if ($summary['unit'])
                                        <span class="in-kind-summary-card__badge">{{ $summary['unit'] }}</span>
                                    @endif
                                </div>
                                <div class="in-kind-summary-row">
                                    <span>الإجمالي</span>
                                    <span class="in-kind-summary-row__value">{{ number_format($summary['incoming'], 0) }}{{ $unit }}</span>
                                </div>
                                <div class="in-kind-summary-row">
                                    <span>المنصرف</span>
                                    <span class="in-kind-summary-row__value">{{ number_format($summary['spent'], 0) }}{{ $unit }}</span>
                                </div>
                                <div class="in-kind-summary-row in-kind-summary-row--remaining">
                                    <span>المتبقي</span>
                                    <span class="in-kind-summary-row__value">{{ number_format($summary['remaining'], 0) }}{{ $unit }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            @if (!empty($error))
                <div class="alert alert-danger">
                    {{ $error }}
                </div>
            @endif

            <div class="card">
                <div class="card-header row">
                    <h3 class="card-title">{{ $isCashLocker ? 'حركة خزنة ' . $title : 'تبرعات خزنة ' . $title }}</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered text-nowrap w-100" id="dataTable">
                            <thead>
                                <tr class="fw-bolder text-muted bg-light">
                                    <th class="min-w-25px">#</th>
                                    <th class="min-w-50px">الاسم</th>
                                    @if (!$isCashLocker)
                                        <th class="min-w-125px">صنف التبرع</th>
                                    @endif
                                    <th class="min-w-125px">القيمه</th>
                                    <th class="min-w-125px">ملاحظات</th>
                                    <th class="min-w-125px">التاريخ</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!--Delete MODAL -->
        <div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">حذف بيانات</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <form action="{{ route('delete_subventions') }}" method="post">
                        @csrf
                        @method('post')
                        <div class="modal-body">
                            <input id="delete_id" name="id" type="hidden">
                            <p>هل انت متأكد من حذف البيانات التالية <span id="title" class="text-danger"></span>؟</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal" id="dismiss_delete_modal">
                                اغلاق
                            </button>
                            <button type="submit" class="btn btn-danger" id="delete_btn">حذف !</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- MODAL CLOSED -->

        <!-- Create Or Edit Modal -->
        <div class="modal fade" id="editOrCreate" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="example-Modal3">بيانات الإعانة</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="modal-body">

                    </div>
                </div>
            </div>
        </div>
        <!-- Create Or Edit Modal -->
    </div>
    @include('admin/layouts/myAjaxHelper')
@endsection
@section('ajaxCalls')
    <script>
        $(document).ready(function() {
            const selectedTypeId = "{{ $selectedTypeId ?? '' }}";
            const isCashLocker = @json($isCashLocker);
            let dataTableUrl = "{{ route('lock') }}" + '?locker_type=' + selectedTypeId;

            var table = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: dataTableUrl,
                    data: function(d) {
                        d.from = $('input[name="from"]').val();
                        d.to = $('input[name="to"]').val();
                        d.locker_type = selectedTypeId;
                        d.type = isCashLocker ? $('#type').val() : 'all';
                    }
                },
                columns: [{
                        data: null,
                        name: 'index',
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        },
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    ...(!isCashLocker ? [{
                        data: 'category_name',
                        name: 'category_name'
                    }] : []),
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'comment',
                        name: 'comment'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                ]
            });

            $('#searchByDate').on('submit', function(e) {
                e.preventDefault();
                table.ajax.reload();
            });

            $('#type').on('change', function() {
                table.ajax.reload();
            });

            $('#locker_type').on('change', function() {
                const url = new URL("{{ route('lock') }}", window.location.origin);
                url.searchParams.set('locker_type', $(this).val());
                window.location.href = url.toString();
            });
        });
    </script>
@endsection
