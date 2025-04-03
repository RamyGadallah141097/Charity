<div class="modal-body">
    <form id="editForm" class="editForm" method="POST" enctype="multipart/form-data" action="{{ route('updateDonor', $donor->id) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name" class="form-control-label">الاسم</label>
            <input type="text" class="form-control" name="name" id="name" value="{{ $donor->name }}">
        </div>

        <div class="form-group">
            <label for="phone" class="form-control-label">الهاتف</label>
            <input type="text" min="0" class="form-control" name="phone" id="phone" value="{{ $donor->phone }}">
        </div>

        <div class="form-group">
            <label for="address" class="form-control-label">العنوان </label>
            <input type="text" min="0" class="form-control" name="address" id="address" value="{{ $donor->address }}">
        </div>

        <div class="form-group">
            <label for="burn_date" class="form-control-label">تاريخ الميلاد </label>
            <input type="date" class="form-control" name="burn_date" id="burn_date" value="{{ $donor->burn_date }}">
        </div>

        <div class="form-group">
            <label for="created_at" class="form-control-label">تاريخ الانشاء </label>
            <input type="date" class="form-control" name="created_at" id="created_at" value="{{ $donor->created_at->format('Y-m-d') }}">
        </div>

        <div class="form-group">
            <label for="notes" class="form-control-label">الملاحظات</label>
            <textarea rows="3" class="form-control" name="notes" id="notes">{{ $donor->notes }}</textarea>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
            <button type="submit" class="btn btn-primary" id="editButton">تحديث</button>
        </div>
    </form>
</div>





















































{{--<div class="modal-body">--}}
{{--    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{route('roles.update')}}">--}}
{{--        @csrf--}}
{{--        <div class="form-group">--}}
{{--            <label for="name" class="form-control-label">الاسم</label>--}}
{{--            <input type="text" class="form-control" value="{{$role->name}}" name="name" id="name">--}}
{{--        </div>--}}

{{--        <div class="form-group">--}}
{{--            <label for="name" class="form-control-label w-100">الصلاحيات</label>--}}

{{--            @foreach($permissions as $permission)--}}
{{--                <label for="permission_{{$permission->id}}">{{$permission->name}}</label>--}}
{{--                <input type="checkbox"--}}
{{--                       id="permission_{{$permission->id}}"--}}
{{--                       name="permissions[]"--}}
{{--                       value="{{$permission->id}}"--}}
{{--                    {{ $role->hasPermission($permission) ? 'checked' : '' }}>--}}
{{--            @endforeach--}}


{{--        </div>--}}

{{--        <div class="modal-footer">--}}
{{--            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>--}}
{{--            <button type="submit" class="btn btn-primary" id="addButton">تحديث</button>--}}
{{--        </div>--}}
{{--    </form>--}}
{{--</div>--}}
