@extends('Admin/layouts/master')
@section('title') {{$setting->title}} | اقرار استلام @endsection
@section('page_name') اقرار استلام @endsection
@section('content')
    <div class="row">
        <div class="col-md-12" id="printDiv">
            <div class="card" style="border: 2px double">
                <p class="text-right mt-4 mr-3" style="font-weight: bold">بنك ناصر الاجتماعي</p>
                <p class="text-right mt-1 mr-3" style="font-weight: bold">{{$setting->section}}</p>
                <p class="text-right mt-1 mr-3" style="font-weight: bold">{{$setting->branch}}</p>
                <div class="card-body" style="font-size: 20px;padding: 20px" >
                    <h3 class="mt-4 mb-1 text-center">اقرار استلام (نقدية/عينيه) من لجنة الزكاة</h3>
                    <hr style="width: 40%" class="mt-0 mb-1 text-dark"></hr>


                    <p class="mt-4">{{$setting->title}} المشهرة برقم {{$setting->vat_number}}</p>
                    <p>
                        وعنوانها {{$setting->address}}
                    </p>
                    <p>
                        {{$setting->sub_address}}
                    </p>

                    <p style="">
                        اقرا انا
                    </p>
                    <div style="align-content: space-between">
                        <p>بطاقة رقم / </p>
                        <p>المقيم في / </p>
                    </div>
                    <p>بانني استلمت من {{$setting->address}} مبلغ وقدره </p>
                    <p>وذلك لصرفها في /</p>
                    <p>
                        بتاريخ
                        &#160;&#160;&#160;&#160;&#160;/&#160;&#160;&#160;&#160;&#160;/{{date('Y')}}
                    </p>
                    <p class="mb-8">المقر بما فيه /</p>
                    <div class="text-center">
                        <h4 class="mb-5">
                            1-......................................................................2-..............................................................3-..................................................
                        </h4>
                        <h4 class="mb-5">
                            4-......................................................................5-..............................................................6-................................................
                        </h4>
                        <h4 class="mb-7">
                            عضو له حق التوقيع .................................................... أمين الصندوق .................................................. مقرر اللجنة............................................
                        </h4>
                    </div>
                </div>
                <div class="col-12 mb-4 text-center">
                    <button title="طباعة" class="btn btn-lg btn-outline-success mt-2 mb-2" id="printBtn">طباعة <i
                            class="fa fa-print"></i></button>
                </div>
            </div>
        </div>
    </div>


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
