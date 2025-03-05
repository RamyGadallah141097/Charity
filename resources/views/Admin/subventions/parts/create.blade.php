<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data"
          action="{{route('subventions.store')}}">
        @csrf
        <div class="form-group">
            <label class="form-label">اختيار المستفيد</label>
            <select name="user_id" class="form-control select2" required
                    data-placeholder="اختيارالمستفيد">
                @foreach($users as $user)
                    <option value="{{$user->id}}">{{$user->husband_name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="price" class="form-control-label">المبلغ</label>
            <input type="number" value=0 class="form-control"  name="price" id="price">
        </div>


            <select class="form-control mb-3" name="asset_id" id="asset">
                @foreach($assets as $item)
                    <option value="{{$item->id}}">{{$item->name}}</option>
                @endforeach
                <input type="number" class="form-control" name="asset_count" id="asset_count">
            </select>


        <div>
            <div class="form-group form-elements">
                <div class="form-label">نوعية الصرف</div>
                <div class="custom-controls-stacked">
                    <label class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" name="type" value="once" checked>
                        <span class="custom-control-label">مرة واحدة</span>
                    </label>
                    <label class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" name="type" value="monthly">
                        <span class="custom-control-label">شهري</span>
                    </label>
                </div>
            </div>
            </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
            <button type="submit" class="btn btn-primary" id="addButton">اضافة</button>
        </div>
    </form>
</div>
