<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{route('donors.store')}}">
        @csrf
        <div class="form-group">
            <label for="name" class="form-control-label">الاسم</label>
            <input type="text" class="form-control" name="name" id="name">
        </div>
        <div class="form-group">
            <label for="phone" class="form-control-label">الهاتف</label>
            <input type="text" min="0" class="form-control" name="phone" id="phone">
        </div>
        <div class="form-group">
            <label for="address" class="form-control-label">العنوان </label>
            <input type="text" min="0" class="form-control" name="address" id="address">
        </div>
        <div class="form-group">
            <label for="burn_date" class="form-control-label">تاريخ الميلاد </label>
            <input type="date"  class="form-control" name="burn_date" id="burn_date">
        </div>
        <div class="form-group">
            <label for="created_at" class="form-control-label">تاريخ الانشاء </label>
            <input type="date" class="form-control" name="created_at" id="created_at" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">

        </div>
        <div class="form-group">
            <label for="notes" class="form-control-label">الملاحظات</label>
            <textarea rows="3" class="form-control" name="notes" id="notes"></textarea>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
            <button type="submit" class="btn btn-primary" id="addButton">اضافة</button>
        </div>
    </form>
</div>
