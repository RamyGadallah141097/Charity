@extends('Admin/layouts/master')
@section('title') {{$setting->title}} | بيانات المؤسسة @endsection
@section('page_name') بيانات المؤسسة @endsection
@section('content')
    @if(count($errors) > 0 )
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <ul class="p-0 m-0" style="list-style: none;">
                @foreach($errors->all() as $error)
                    <li><i class="fa fa-times-circle"></i> {{$error}}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <form action="{{route('settingUpdate')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0 card-title"> بيانات {{($setting->title) ?? ''}}</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">اسم المؤسسة</label>
                                    <input type="text" class="form-control" name="title"
                                           placeholder="اسم الشركة" value="{{($setting->title) ?? ''}}">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">اسم الفرع</label>
                                    <input type="text" class="form-control" name="branch"
                                           placeholder="اسم الفرع" value="{{($setting->branch) ?? ''}}">
                                </div>
                            </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">المشهرة برقم</label>
                                        <input type="text" class="form-control" name="vat_number"
                                               placeholder=""
                                               value="{{($setting->vat_number) ?? ''}}">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">اسم القطاع</label>
                                        <input type="text" class="form-control" name="section"
                                               placeholder="اسم القطاع مثلا : قطاع التكافل" value="{{($setting->section) ?? ''}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">العنوان</label>
                                    <input type="text" class="form-control" name="address"
                                           placeholder="عنوان ومكان الشركة" value="{{($setting->address) ?? ''}}">
                                </div>
                            </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">العنوان الفرعي</label>
                                <input type="text" class="form-control" name="sub_address"
                                       placeholder="عنوان ومكان الشركة" value="{{($setting->sub_address) ?? ''}}">
                            </div>
                        </div>
                            <div class="col-md-12 ">
                                <div class="form-group">
                                    <label class="form-label">اللوجو</label>
                                    <input type="file" class="dropify" name="logo"
                                           data-default-file="{{asset($setting->logo)}}"
                                           accept="image/png, image/gif, image/jpeg,image/jpg"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-left">
                        <button type="submit" class="btn btn-primary">تحديث</button>
                    </div>
                </div>
            </form>
        </div>
    </div>




@endsection
