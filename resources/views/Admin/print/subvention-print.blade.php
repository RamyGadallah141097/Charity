@extends('Admin/layouts/master')
@section('title') {{$setting->title}} | الإعانات @endsection
@section('page_name') الإعانات @endsection
<style>
    .td, th {
        border: 1px solid #dddddd;
    }
</style>
@section('content')
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="card" id="printDiv">
                <div id="printDiv">
                    <div class="text-center">
                        <h4 class="mb-0 mt-5">{{$setting->section}} ({{$setting->branch}})</h4>
                    </div>
                    <div class="text-center">
                        <h4 class="mt-4 mb-1">بيان باسماء الحالات المطلوب تقرير / صرف / زكاة لهم</h4>
                        <hr style="width: 40%" class="mt-0 mb-1 text-dark"></hr>
                        <h4 class="mt-4 mb-2">{{$setting->title}}</h4>
                        <h4 class="mt-4 mb-2">{{$setting->address}}</h4>
                    </div>
                    <div class="card-header mt-4 mb-2" style="justify-content:space-between">
                        <div class="fw-bold" style="font-size: 1.125rem">
                            شبين الكوم - محافطة المنوفية
                        </div>
                        <div class="fw-bold" style="font-size: 1.125rem">
                            بتاريخ
                            {{date('Y/m/d')}}
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive" style="font-size: 1rem !important;">
                            <table class="table card-table table-vcenter text-nowrap mb-0  align-items-center mb-0">
                                <thead class="thead-light">
                                <tr>
                                    <th>م</th>
                                    <th>المستفيد</th>
                                    <th>المبلغ</th>
{{--                                    <th>نوع الإعانة</th>--}}
                                    <th>التوقيع</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($subventions as $subvention)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td class="text-sm font-weight-600">{{$subvention->user->husband_name}}</td>
                                        <td>{{$subvention->price}}</td>
{{--                                        <td>{{($subvention->type == 'once') ? 'مرة واحدة' : 'شهرية'}}</td>--}}
                                        <td></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="text-center">
                        <h4 class="mb-2">
                            1-...............................................2-............................................3-.......................................
                        </h4>
                        <h4 class="mb-2">
                            4-...............................................5-............................................6-.....................................
                        </h4>
                        <h4 class="mb-2">
                            عضو له حق التوقيع ...................................... أمين الصندوق .............................................. مقرر اللجنة..............................................
                        </h4>
                    </div>
                </div>
                <div class="col-12 text-center">
                    <button title="طباعة" class="btn btn-lg btn-outline-success mt-2 mb-2" id="printBtn">طباعة <i
                            class="fa fa-print"></i></button>
                </div>
            </div>
        </div>
    </div>
    <!-- COL END -->

@endsection
@section('js')
    <script>
        $("#printBtn").click(function () {
            //Hide all other elements other than printarea.
            $(this).hide();
            var printContents = document.getElementById('printDiv').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            $(".app-header .header").css("display", "none");
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        });
    </script>
@endsection
