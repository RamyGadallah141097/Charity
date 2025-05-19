
<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <!-- bootstrap -->
    <link rel="stylesheet" href="{{asset("oneInvoices/css/all.min.css")}}">
    <link rel="stylesheet" href="{{asset("oneInvoices/css/bootstrap.min.css")}}">

<style>
  body{
    font-weight: bold;
    direction: rtl;
}
.header{
    display: flex;
    justify-content: flex-end;
}
.logo{
    width: 300px;
    height: 80px;
}
table, td, th{
    border: 1px solid black !important;
}
.scroll{
    overflow-y: auto;
    margin-bottom: 10px;
}
.blue-color{
    background-color: gray;
    color: white;
}
.border-color{
    border: 1px solid white !important;
}
.border-shape{
  border: 2px solid black;
}


</style>
</head>
<body>
   <div class="border-shape p-1">
    <div class="pt-5 pb-5 border-shape">
      <div class="container">
        <!-- header -->
       <p>بنك ناصر الاجتماعى</p>
       <p>قطاع التكافل / الادارة العامة للزكاة</p>
       <p>فرع شبين الكوم</p>
        <!-- content -->
          <h4 class="text-center mt-1 mb-3">اقرار استلام (نقدية) من لجنة الزكاة</h4>
          <h4 class="text-center mt-1 mb-3">لجنة زكاة كفر طنبدى المشهورة برقم 3/4859</h4>
          <h5 class="text-center mt-1 mb-3"> وعنوانها كفر طنبدى -شارع البحر بعد صيدلية ناصف بجوار الاستاذ على داود المحامى</h5>
          <h5 class="text-center mt-1 mb-3"> شبين الكوم - محافظة المنوفية</h5>
          <div>
            <h5 class="text-center mt-1 mb-3"> اقر انا / {{$subventions ? $subventions->user->wife_name : "---" }}</h5>
          </div>
          <h5 class="text-center mt-1 mb-3"> المقيم / المنوفية - قرية كفر طنبدى</h5>
            <p> الرقم القومى / <span>{{$subventions ? $subventions->user->wife_national_id : "---"}}</span></p>
            <p> رقم الموبايل / <span>{{$subventions?->user->nearest_phone}}</span></p>
            <p> باننى استلمت من لجنة الزكاة بكفر طنبدى مبلغ  <span> {{$subventions?->price}} </span> جنيها فقط لاغير وذلك / <span> {{$subventions?->comment}}</span></p>
            <div class="d-flex justify-content-end">
                <p>بتاريخ {{\Carbon\Carbon::now()->format("Y-m-d")}}</p>
            </div>
            {{-- <p>المقر بما فيه / <span>{{auth()->user()->name}}</span></p> --}}
            <p>المقر بما فيه / <span>{{$subventions ? $subventions->user->wife_name}}</span></p>

            <!-- footer -->
            <div class="d-flex justify-content-center fw-normal pt-5 pb-5">
              <p>
                عضو له حق التوقيع
              </p>
              <p>-----------</p>
              <p>امين الصندوق</p>
              <p>-----------</p>
              <p>مقر اللجنة</p>
              <p>-----------</p>
            </div>
      </div>
     </div>
   </div>


<script src="{{asset("oneInvoices/js/bootstrap.bundle.min.js")}}"></script>
<script src="{{asset("oneInvoices/js/all.min.js")}}"></script>
<script>
  function myfunction(){
    window.print();
  }
  window.addEventListener('DOMContentLoaded', (event) => {
      myfunction();
  });
</script>
</body>
</html>
