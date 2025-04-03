<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{ route('SubscriptionFee.store') }}">
        @csrf


        <div class="form-group">
            <label for="title" class="form-control-label">العنوان</label>
            <input type="text" min="0"  class="form-control" name="title" id="title" required>
        </div>


        <div class="form-group">
            <label for="amount" class="form-control-label">القيمة</label>
            <input type="number" min="0"  class="form-control" name="amount" id="amount" required>
        </div>


        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
            <button type="submit" class="btn btn-primary" id="addButton">إضافة</button>
        </div>
    </form>
</div>
