<div class="modal-body">
    <form id="updateForm" method="POST" action="{{ route('association-revenues.update', $revenue->id) }}">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label>نوع الإيراد</label>
            <select class="form-control" name="revenue_type_id" required>
                <option value="">اختر النوع</option>
                @foreach($revenueTypes as $type)
                    <option value="{{ $type->id }}" {{ $revenue->revenue_type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>المبلغ</label>
            <input type="number" step="0.01" min="0.01" class="form-control" name="amount" value="{{ $revenue->amount }}" required>
        </div>
        <div class="form-group">
            <label>تاريخ العملية</label>
            <input type="date" class="form-control" name="transaction_date" value="{{ optional($revenue->transaction_date)->format('Y-m-d') }}" required>
        </div>
        <div class="form-group">
            <label>رقم المرجع</label>
            <input type="text" class="form-control" name="reference_number" value="{{ $revenue->reference_number }}">
        </div>
        <div class="form-group">
            <label>ملاحظات</label>
            <textarea rows="3" class="form-control" name="notes">{{ $revenue->notes }}</textarea>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
            <button type="submit" class="btn btn-success" id="updateButton">تحديث</button>
        </div>
    </form>
</div>
