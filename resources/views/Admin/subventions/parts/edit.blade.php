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

        <div class="mt-4 ">
            <label>نوع الاعانه</label>
            <select id="sub_type" name="sub_type" class="form-control">
                <option value=0>ماديه</option>
                <option value=1>عينيه</option>
            </select>
        </div>


        <div class="form-group mt-4 mb-4" id="money">
            <label for="price" class="form-control-label">المبلغ</label>
            <input type="number" value=0 class="form-control"  name="price" id="price">
        </div>



        <div class="row mt-4 mb-4" id="subvention">
            <label for="price" class="form-control-label">العينيه و قيمتها </label>
            <div class="input-group">
                <select  class="form-select bx-outline" name="asset_id" id="asset">
                    {{--                        <option selected value=0>initially dummy option</option>--}}
                    @foreach($assets as $item)
                        <option value="{{$item->id}}">{{$item->name}}</option>
                    @endforeach
                </select>
                <input type="number" value=0 class="form-control" name="asset_count" id="asset_count" placeholder="العدد">
            </div>

        </div>





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


<script>
    $("document").ready(function(){
        $("#subvention").hide();
        $("select[id='sub_type']").on("change" , function(){
            let type = $(this).val();
            if(type == 1){
                $("#money").hide();
                $("#subvention").show();
            }else{
                $("#money").show();
                $("#subvention").hide();
            }
        })
    })
</script>
