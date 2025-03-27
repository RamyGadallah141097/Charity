<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{route('tasks.store')}}">
        @csrf

        <div class="form-group">
            <label for="title" class="form-control-label" > العنوان</label>
            <input type="text"  class="form-control" name="title"   id="title">
        </div>
        <div class="form-group">
            <label for="description" class="form-control-label" > الوصف</label>
            <textarea type="text"  class="form-control" name="description"   id="description"></textarea>
        </div>
        <div class="form-group">
            <label for="from_date" class="form-control-label" > وقت البدأ</label>
            <input type="date"  value="{{ \Carbon\Carbon::now()->format("Y-m-d") }}"  class="form-control" name="from_date"   id="from_date">
        </div>
        <div class="form-group">
            <label for="to_date" class="form-control-label" > وقت الانتهاء</label>
            <input type="date"   class="form-control" name="to_date"   id="to_date">
        </div>



{{--        <div class="form-group">--}}
{{--            <label for="donation_type" class="form-control-label">نوع التبرع </label>--}}
{{--            <select name="donation_type" id="type" class="form-control">--}}
{{--                <option value="0">زكاة المال </option>  // the first type 0--}}
{{--                <option value="1"> صدقات</option> // the second type 1--}}
{{--                <option value="2">قرض حسن </option>// the third type 2--}}
{{--                <option value="3">تبرع عيني  </option>// the forth type 3--}}
{{--            </select>--}}
{{--        </div>--}}




        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
            <button type="submit" class="btn btn-primary" id="addButton">اضافة</button>
        </div>
    </form>
</div>
