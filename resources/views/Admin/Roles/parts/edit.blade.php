
<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{route('roles.update' , $role->id)}}">
        @method("put")
        @csrf
        <div class="form-group">
            <label for="name" class="form-control-label">الاسم</label>
            <input type="text" class="form-control" value="{{$role->name}}" name="name" id="name">
        </div>

        <div class="form-group">
            <label for="name" class="form-control-label w-100">الصلاحيات</label>


            <div class="row">
                <!-- Select All Checkbox -->
                <div class="col-12 mb-2">
                    <label>
                        <input type="checkbox" id="select_all" /> Select All Permissions
                    </label>
                </div>

                @foreach($permissions as $permission)
                    <div class="col-4 align-content-end">
                        <label for="permission_{{$permission->id}}">{{$permission->name}}</label>
                        <input type="checkbox"
                               {{$role->hasPermissionTo($permission) ? "checked" : ""}}
                               class="permission-checkbox"
                               id="permission_{{$permission->id}}"
                               name="permissions[]"
                               value="{{$permission->id}}" />
                    </div>
                    <br>
                @endforeach




            </div>

        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
            <button type="submit" class="btn btn-primary" id="addButton">اضافة</button>
        </div>
    </form>
</div>



<script>
    document.getElementById('select_all').addEventListener('change', function() {
        let checkboxes = document.querySelectorAll('.permission-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
</script>
