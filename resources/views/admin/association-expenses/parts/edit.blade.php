<div class="modal-body">
    <form id="updateForm" method="POST" action="{{ route('association-expenses.update', $expense->id) }}">
        @csrf
        @method('PUT')
        <div class="alert alert-info">
            الرصيد المتاح بعد احتساب قيمة العملية الحالية: <strong>{{ number_format($balance, 2) }}</strong>
        </div>
        <div class="form-group">
            <label>نوع المصروف</label>
            <select class="form-control" name="expense_type_id" required>
                <option value="">اختر النوع</option>
                @foreach($expenseTypes as $type)
                    <option value="{{ $type->id }}" {{ $expense->expense_type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>المبلغ</label>
            <input type="number" step="0.01" min="0.01" class="form-control" name="amount" value="{{ $expense->amount }}" required>
        </div>
        <div class="form-group">
            <label>تاريخ العملية</label>
            <input type="date" class="form-control" name="transaction_date" value="{{ optional($expense->transaction_date)->format('Y-m-d') }}" required>
        </div>
        <div class="form-group">
            <label>رقم المرجع</label>
            <input type="text" class="form-control" name="reference_number" value="{{ $expense->reference_number }}">
        </div>
        <div class="form-group">
            <label>ملاحظات</label>
            <textarea rows="3" class="form-control" name="notes">{{ $expense->notes }}</textarea>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
            <button type="submit" class="btn btn-success" id="updateButton">تحديث</button>
        </div>
    </form>
</div>
