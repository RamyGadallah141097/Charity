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
                                        {{ $lockerType->name }}
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
