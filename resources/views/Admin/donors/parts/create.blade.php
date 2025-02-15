<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{route('donors.store')}}">
        @csrf
        <div class="form-group">
            <label for="name" class="form-control-label">الاسم</label>
            <input type="text" class="form-control" name="name" id="name">
        </div>
        <div class="form-group">
            <label for="phone" class="form-control-label">الهاتف</label>
            <input type="number" min="0" class="form-control" name="phone" id="phone">
        </div>
        <div class="form-group">
            <label for="price" class="form-control-label">اجمالي التبرعات</label>
            <input type="number" min="0" class="form-control" name="price" id="price">
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
