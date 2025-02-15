@extends('Admin/layouts/master')
@section('title')
    {{($setting->title) ?? ''}} | الصفحة الرئيسية
@endsection
@section('page_name')
    الرئـيسية
@endsection
@section('content')
    <link href="{{asset('assets/admin')}}/assets/plugins/morris/morris.css"
          rel="stylesheet"/>


    @if($total_donors >= 1000)
        <div class="row">
            <div class="col-md-12">
                <div class="card  banner">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-3 col-lg-2 text-center"><img
                                    src="{{asset('assets/admin')}}/assets/images/pngs/dash5.png"
                                    alt="img" class="w-95"></div>
                            <div class="col-xl-9 col-lg-10 pl-lg-0">
                                <div class="row">
                                    <div class="col-xl-7 col-lg-6">
                                        <div class="text-right text-white mt-xl-4"><h3 class="font-weight-semibold">
                                                تهانينا يا {{loggedAdmin('name')}}</h3> <h4 class="font-weight-normal">
                                                تخطت اجمالي التبرعات 1000 جنية
                                            </h4>
                                            <p class="mb-lg-0 text-white-50">
                                                لقد بلغت اجمالي التبرعات جنية {{$total_donors}}
                                                , نأمل لمواصلة التقدم وتحقيق الهدف
                                            </p></div>
                                    </div>
                                    <div class="col-xl-5 col-lg-6 text-lg-center mt-xl-4"><h5
                                            class="font-weight-semibold mb-1 text-white"> عدد المتبرعين </h5>
                                        <h2 class="display-2 mb-3 number-font text-white">{{$donors_count}}</h2>
                                        <div class="btn-list mb-xl-0"><a href="{{route('donors.index')}}"
                                                                         class="btn btn-dark mb-xl-0">عرض التفاصيل</a>
                                            <a href="#" class="btn btn-white mb-xl-0" id="skip">
                                                لاحقا</a></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
            <div class="card bg-primary img-card box-primary-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white"><h2 class="mb-0 number-font">{{$users_count}}</h2>
                            <p class="text-white mb-0">إجمالي المستفيدين </p></div>
                        <div class="mr-auto"><i class="fe fe-users text-white fs-30 ml-2 mt-2"></i></div>
                    </div>
                </div>
            </div>
        </div><!-- COL END -->
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
            <div class="card bg-secondary img-card box-secondary-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white"><h2 class="mb-0 number-font">  {{$totalMonthlySubventions}} </h2>
                            <p class="text-white mb-0">إجمالي الاعانات الشهرية</p></div>
                        <div class="mr-auto"><i class="fe fe-dollar-sign text-white fs-30 ml-2 mt-2"></i></div>
                    </div>
                </div>
            </div>
        </div><!-- COL END -->
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
            <div class="card  bg-success img-card box-success-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white"><h2 class="mb-0 number-font">{{$donors_count}}</h2>
                            <p class="text-white mb-0">اجمالي المتبرعين</p></div>
                        <div class="mr-auto"><i class="fe fe-shopping-bag text-white fs-30 ml-2 mt-2"></i></div>
                    </div>
                </div>
            </div>
        </div><!-- COL END -->
        <div class="col-sm-12 col-md-6 col-lg-6 col-xl-3">
            <div class="card bg-info img-card box-info-shadow">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="text-white"><h2 class="mb-0 number-font">{{$accepted_users}}</h2>
                            <p class="text-white mb-0">المتسفيدين المقبولين</p></div>
                        <div class="mr-auto"><i class="fe fe-user-check text-white fs-30 ml-2 mt-2"></i></div>
                    </div>
                </div>
            </div>
        </div><!-- COL END -->
    </div>


    <div class="row">
        <div class="col-xl-4 col-sm-12 p-l-0 p-r-0 col-md-12">
            <div class="card pb-5">
                <div class="card-header text-center"><h2 class="card-title">المستفيدين</h2></div>
                <div class="card-body">
                    <div class="mx-auto chart-circle chart-circle-md mt-3 mb-4 text-center" data-value="{{$diff}}"
                         data-thickness="8" data-color="#1cc5ef">
                        <canvas width="140" height="140" style="height: 112px; width: 112px;"></canvas>
                    </div>
                    <div class="text-center mt-3"><h3>اخر المستفيدين</h3>
                        <div class="col p-1 mt-2 pb-6">
                            <div class="float-left"><h3 class="ml-5 "><i
                                        class="fa fa-caret-{{($users_last_month <= $users_month) ? 'up' : 'down'}} fa-1x text-{{($users_last_month > $users_month) ? 'danger' : 'primary'}} ml-1"></i>{{$users_month}}
                                </h3> <h6
                                    class="mr-5 mt-0 mb-0">هذا الشهر</h6></div>
                            <div class="float-right"><h3 class="mr-5"><i
                                        class="fa fa-caret-{{($users_last_month > $users_month) ? 'up' : 'down'}} fa-1x text-{{($users_last_month > $users_month) ? 'primary' : 'danger'}} ml-1"></i>{{$users_last_month}}
                                </h3> <h6
                                    class="ml-5 pb-0 mb-0">الشهر الماضي</h6></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-xl-4">
            <div class="card">
                <div class="card-header border-bottom"><h5 class="card-title">أعلي التبرعات <i
                            class="fa fa-arrow-up text-success"></i></h5></div>
                <div class="card-body">
                    @foreach($donors as $donor)
                        <div class="clearfix row mb-4">
                            <div class="col">
                                <div class="float-right"><h5 class="mb-0"><strong>{{$donor->name}}</strong></h5> <small
                                        class="text-muted">{{$donor->phone}}</small></div>
                            </div>
                            <div class="col">
                                <div class="float-left"><h4
                                        class="font-weight-bold mb-0 mt-2 text-blue">{{$donor->price}}</h4></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class=""><p class="mb-1">أعلي مبلغ اعانة</p>
                        <h2 class="mb-1  number-font"> {{$subvention->price}} جنية </h2></div>
                    <div class="mt-5">
                        <p class="mb-1 d-flex"><span class=""><i
                                    class="fa fa-university ml-2 fs-16 text-muted"></i></span>
                            <span class="fs-13 font-weight-normal text-muted ml-2">اسم المستفيد </span> : <span
                                class="mr-auto fs-15">{{($subvention->user->husband_name) ?? 'لا يوجد'}}</span></p>

                        <p class="mb-1 d-flex"><span class=""><i
                                    class="fe fe-dollar-sign text-muted ml-2 mt-1 fs-16"></i></span> <span
                                class="fs-13 font-weight-normal text-muted ml-2">نوعية الصرف </span> : <span
                                class="mr-auto fs-14">
                                @if($subvention->type == 'once')
                                    مرة واحدة
                                @else
                                    إعانة شهرية
                                @endif
                            </span></p>
                        <p class="mb-1 d-flex"><span class=""><i
                                    class="fa fa-users ml-2 mt-1 fs-16 text-muted"></i></span> <span
                                class="fs-13 font-weight-normal text-muted ml-2">عدد الاولاد </span> : <span
                                class="mr-auto fs-14">
                                @if($subvention->user->childrens->count())
                                    {{$subvention->user->childrens->count()}} أولاد
                                @else
                                    لا يوجد
                                @endif
                            </span>
                        </p>

                        <p class="mb-1 d-flex"><span class=""><i
                                    class="fa fa-phone ml-2 mt-1 fs-16 text-muted"></i></span> <span
                                class="fs-13 font-weight-normal text-muted ml-2">أقرب هاتف </span> : <span
                                class="mr-auto fs-14">
                            {{($subvention->user->nearest_phone) ?? 'لا يوجد'}}
                            </span>
                        </p>
                        <p class="mb-1 d-flex"><span class=""><i
                                    class="fa fa-credit-card ml-2 mt-1 fs-16 text-muted"></i></span> <span
                                class="fs-13 font-weight-normal text-muted ml-2">هل لديه املاك</span> : <span
                                class="mr-auto fs-14">
                                @if($subvention->user->has_savings_book == '0')
                                    لا
                                @else
                                    نعم
                                @endif
                            </span>
                        </p>

                        <p class="d-flex"><span class=""><i
                                    class="fa fa-building ml-2 mt-1 fs-16 text-muted"></i></span> <span
                                class="fs-13 font-weight-normal text-muted ml-2">هل لديه دفتر توفير</span> : <span
                                class="mr-auto fs-14">
                                @if($subvention->user->has_property == '0')
                                    لا
                                @else
                                    نعم
                                @endif
                            </span>
                        </p>

                    </div>
                    <div class="row mt-6 pb-4">
                        <div class="col-4"><a class="btn btn-primary btn-block btn-rounded"
                                              href="tel:{{($subvention->user->nearest_phone) ?? ''}}">اتصال</a></div>
                        <div class="col-4"><a class="btn btn-info btn-rounded btn-block"
                                              href="{{route('research.receive')}}">تقرير استلام</a></div>
                        <div class="col-4"><a class="btn btn-secondary btn-rounded btn-block"
                                              href="{{route('social_research',$subvention->user->id)}}">تقرير بحث</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div class="card">
                <div class="card-header"><h3 class="card-title">
                        أحدث المستفدين
                    </h3></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover mb-0 text-nowrap">
                            <thead>
                            <tr>
                                <th>اسم الزوج</th>
                                <th>اسم الزوجة</th>
                                <th>الحالة الاجتماعية</th>
                                <th>اقرب هاتف</th>
                                <th>اجمالي الدخل</th>
                                <th>اجمالي النفقات</th>
                                <th>الحالة</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{$user->husband_name}}</td>
                                    <td>{{$user->wife_name}}</td>
                                    <td>
                                        @if($user->social_status == 'single')
                                            أعزب
                                        @elseif ($user->social_status == 'married')
                                            متزوج
                                        @elseif ($user->social_status == 'divorced')
                                            مطلق
                                        @else
                                            أرمل
                                        @endif
                                    </td>
                                    <td>{{$user->nearest_phone}}</td>
                                    <td class="font-weight-semibold fs-15">{{$user->gross_income}}</td>
                                    <td class="font-weight-semibold fs-15">{{$user->total_expenses}}</td>
                                    <td>
                                        @if($user->status == 'new')
                                            <span class="badge badge-primary">جديد</span>
                                        @elseif ($user->status == 'preparing')
                                            <span class="badge badge-warning">قيد التنفيذ</span>
                                        @elseif ($user->status == 'accepted')
                                            <span class="badge badge-success">مقبول</span>
                                        @else
                                            <span class="badge badge-danger">مرفوض</span>
                                        @endif

                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>




@endsection
@section('js')

    {{--    <!-- INTERNAL CHARTJS CHART JS -->--}}
    <script src="{{asset('assets/admin')}}/assets/plugins/chart/Chart.bundle.js"></script>
    <script src="{{asset('assets/admin')}}/assets/plugins/chart/utils.js"></script>

    <!-- INTERNAL PIETY CHART JS -->
    <script
        src="{{asset('assets/admin')}}/assets/plugins/peitychart/jquery.peity.min.js"></script>
    <script
        src="{{asset('assets/admin')}}/assets/plugins/peitychart/peitychart.init.js"></script>

    <!-- INTERNAL MORRIS CHART JS -->
    <script src="{{asset('assets/admin')}}/assets/plugins/morris/morris.js"></script>
    <script src="{{asset('assets/admin')}}/assets/plugins/morris/raphael-min.js"></script>
    {{--    <!-- INTERNAL APEXCHART JS -->--}}
    <script src="{{asset('assets/admin')}}/assets/js/apexcharts.js"></script>
    <!--INTERNAL INDEX JS-->
    <script src="{{asset('assets/admin')}}/assets/js/index4.js"></script>
@endsection
