<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" action="{{ route('association-revenues.store') }}">
        @csrf
        <div class="form-group">
            <label>نوع الإيراد</label>
            <select class="form-control" name="revenue_type_id" required>
                <option value="">اختر النوع</option>
                @foreach($revenueTypes as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>المبلغ</label>
            <input type="number" step="0.01" min="0.01" class="form-control" name="amount" required>
        </div>
        <div class="form-group">
            <label>تاريخ العملية</label>
            <input type="date" class="form-control" name="transaction_date" value="{{ now()->format('Y-m-d') }}" required>
        </div>
        <div class="form-group">
            <label>رقم المرجع</label>
            <input type="text" class="form-control" name="reference_number">
        </div>
        <div class="form-group">
            <label>ملاحظات</label>
            <textarea rows="3" class="form-control" name="notes"></textarea>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
            <button type="submit" class="btn btn-primary" id="addButton">إضافة</button>
        </div>
    </form>
</div>
