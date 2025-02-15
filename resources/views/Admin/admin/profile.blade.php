@extends('Admin/layouts/master')
@section('title') {{$setting->title}} | حسابي @endsection

@section('page_name')
    حسابي
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="wideget-user text-center">
                        <div class="wideget-user-desc">
                            <div class="wideget-user-img">
                                <img class="" src="{{get_user_file($admin->photo)}}" alt="img">
                            </div>
                            <div class="user-wrap">
                                <h4 class="mb-1 text-capitalize">{{$admin->name}}</h4>
{{--                                <h6 class="text-muted mb-4"> {{$admin->created_at->diffForHumans()}}</h6>--}}
                                <h6 class="text-muted mb-4"> {{$admin->email}}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="wideget-user-tab">
                    <div class="tab-menu-heading">
                        <div class="tabs-menu1">
                            <ul class="nav">
                                <li class=""><a href="#tab-51" class="active show" data-toggle="tab">بيانات الحساب</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-content">
                <div class="tab-pane active show" id="tab-51">
                    <div class="card">
                        <div class="card-body">
                            <div id="profile-log-switch">
                                <div class="media-heading">
                                    <h5><strong>المعلومات الشخصية</strong></h5>
                                </div>
                                <div class="table-responsive ">
                                    <table class="table row table-borderless">
                                        <tbody class="col-lg-12 col-xl-4 p-0">
                                        <tr>
                                            <td class="text-capitalize"><strong>الاسم :</strong> {{$admin->name}}</td>
                                        </tr>
                                        </tbody>
                                        <tbody class="col-lg-12 col-xl-4 p-0">
                                        <tr>
                                            <td><strong>البريد الالكتروني :</strong> {{$admin->email}}</td>
                                        </tr>
                                        </tbody>
                                        <tbody class="col-lg-12 col-xl-4 p-0">
                                        <tr>
                                            <td><strong>وقت التسجيل :</strong> {{$admin->created_at->diffForHumans()}}</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- COL-END -->
    </div>

@endsection


