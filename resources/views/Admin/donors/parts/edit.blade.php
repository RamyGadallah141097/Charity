<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{route('roles.update')}}">
        @csrf
        <div class="form-group">
            <label for="name" class="form-control-label">الاسم</label>
            <input type="text" class="form-control" value="{{$role->name}}" name="name" id="name">
        </div>

        <div class="form-group">
            <label for="name" class="form-control-label w-100">الصلاحيات</label>

            @foreach($permissions as $permission)
                <label for="permission_{{$permission->id}}">{{$permission->name}}</label>
                <input type="checkbox"
                       id="permission_{{$permission->id}}"
                       name="permissions[]"
                       value="{{$permission->id}}"
                    {{ $role->hasPermission($permission) ? 'checked' : '' }}>
            @endforeach


        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
            <button type="submit" class="btn btn-primary" id="addButton">اضافة</button>
        </div>
    </form>
</div>
