@extends('Admin/layouts/master')

@section('title')
    {{ isset($setting) ? $setting->title : '' }}
    | المشرفين
@endsection
@section('page_name')
    المشرفين
@endsection
@section('content')
    <div class="modal-body">
        <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" {{--          action="{{route('changeRole')}}" --}}>
            @csrf
            <div class="form-group">
                <label for="name" class="form-control-label">الاسم</label>
                <input type="text" class="form-control" disabled value="{{ $admin->name }}">
                <input type="hidden" class="form-control" name="id" value="{{ $admin->id }}">

            </div>

            <div class="form-group">
                <label for="name" class="form-control-label w-100">السماحيات</label>


                <div class="row">
                    <select name="adminRole" class="form-control">
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>


                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
                <button type="submit" class="btn btn-primary" id="addButton">تغيير</button>
            </div>
        </form>
    </div>

    @include('admin/layouts/myAjaxHelper')
@endsection
