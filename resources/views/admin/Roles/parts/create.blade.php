<div class="modal-body ">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{route('roles.store')}}">
        @csrf
        <div class="form-group">
            <label for="name" class="form-control-label">الاسم</label>
            <input type="text" class="form-control" name="name" id="name">
        </div>

        <div class="form-group">
            <label for="name" class="form-control-label w-100">الصلاحيات</label>

            <div class="col-12">
                <label>
                    <input type="checkbox" id="select_all" /> حدد الكل
                </label>
            </div>
            <br>

            <div class="row">
                @foreach($permissions as $permission)
                    <div class="col-md-6 mb-3" >
                        <div  class="p-3 border rounded d-flex align-items-center">
                            <input type="checkbox" style="cursor: pointer"
                                   class="form-check-input"
                                   id="permission_{{$permission->id}}"
                                   name="permissions[]"
                                   value="{{$permission->id}}" />

                            <label  for="permission_{{$permission->id}}" class="form-check-label me-3" style="margin-right: 16px;cursor: pointer" >
                                {{ __('permissions.' . $permission->name) }}
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>



        </div>



        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
            <button type="submit" class="btn btn-primary" id="addButton">اضافة</button>
        </div>
    </form>
</div>


<!-- JavaScript to Handle Select All Logic -->
<script>
    document.getElementById('select_all').addEventListener('change', function() {
        let checkboxes = document.querySelectorAll('.permission-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
</script>
