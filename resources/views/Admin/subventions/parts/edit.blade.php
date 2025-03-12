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

        @if($subvention->price == 0 )
            <div class="row mt-4 mb-4" id="subvention">
                <label for="price" class="form-control-label">العينيه و قيمتها </label>
                <div class="input-group">
                    <select  class="form-select bx-outline" name="asset_id" id="asset">
                        <option selected value="{{$subvention->asset_id}}">{{$subvention->asset->name}}</option>
                        @foreach($assets as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                    <input type="number" min=1 value="{{$subvention->asset_count}}" class="form-control" name="asset_count" id="asset_count" placeholder="العدد">
                </div>

            </div>
        @else
            <div class="form-group mt-4 mb-4" id="money">
                <label for="price" class="form-control-label">العينيه و قيمتها </label>
                <div class="input-group">
                    <select  class="form-select bx-outline" name="moneyType" id="asset">
                        @if($Dtype == "zakat")
                            <option selected value="0">زكاة مال</option>
                        @else
                            <option selected value="1">صدقه</option>
                        @endif
                    </select>
                    <input type="number" min=1 value="{{$subvention->price}}" class="form-control"  name="price" id="price">
                </div>
            </div>
        @endif





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
