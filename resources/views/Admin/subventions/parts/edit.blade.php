<div class="modal-body">
    <form id="updateForm" method="POST" enctype="multipart/form-data" action="{{route('subventions.update',$subvention->id)}}" >
    @csrf
        @method('PUT')
        <div class="form-group">
            <label class="form-label">اختيار المستفيد</label>
            <select name="user_id" class="form-control select2" required
                    data-placeholder="اختيارالمستفيد">
                @foreach($users as $user)
                    <option value="{{$user->id}}" {{($user->id == $subvention->user_id) ? 'selected' : '' }}>{{$user->husband_name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="price" class="form-control-label">المبلغ</label>
            <input type="number" min="0" class="form-control" required name="price" id="price" value="{{$subvention->price}}">
        </div>

        <select class="form-control mb-3" name="asset_id" id="asset">
            <option value="{{$subvention->asset_id}}">
                {{ optional($subvention->asset)->name ?? 'Select an Asset' }}
            </option>
            @foreach($assets as $item)
                <option value="{{$item->id}}" {{ $item->id == $subvention->asset_id ? 'selected' : '' }}>
                    {{$item->name}}
                </option>
            @endforeach
        </select>

        <input type="number" class="form-control" name="asset_count" id="asset_count" value="{{$subvention->asset_count}}">




        <div>
            <div class="form-group form-elements">
                <div class="form-label">نوعية الصرف</div>
                <div class="custom-controls-stacked">
                    <label class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" name="type" value="once" {{($subvention->type == 'once') ? 'checked' : '' }}>
                        <span class="custom-control-label">مرة واحدة</span>
                    </label>
                    <label class="custom-control custom-radio">
                        <input type="radio" class="custom-control-input" name="type" value="monthly" {{($subvention->type == 'monthly') ? 'checked' : '' }}>
                        <span class="custom-control-label">شهري</span>
                    </label>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
            <button type="submit" class="btn btn-success" id="updateButton">تحديث</button>
        </div>
    </form>
</div>
