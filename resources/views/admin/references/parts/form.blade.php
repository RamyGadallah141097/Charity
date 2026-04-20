<form action="{{ $formAction }}" method="POST" id="{{ $formId }}">
    @csrf
    @if ($formMethod === 'PUT')
        @method('PUT')
    @endif

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="form-label">الاسم</label>
                <input type="text" class="form-control" name="name" value="{{ old('name', $item->name ?? '') }}">
            </div>
        </div>

        @if ($lookup['show_code'])
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">الكود</label>
                    <input type="text" class="form-control" name="code" value="{{ old('code', $item->code ?? '') }}">
                </div>
            </div>
        @endif

        @if ($type === 'centers')
            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label">المحافظة</label>
                    <select class="form-control" name="governorate_id">
                        <option value="">اختر المحافظة</option>
                        @foreach ($parents as $parent)
                            <option value="{{ $parent->id }}" {{ old('governorate_id', $item->governorate_id ?? '') == $parent->id ? 'selected' : '' }}>
                                {{ $parent->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endif

        @if ($type === 'villages')
            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label">المركز</label>
                    <select class="form-control" name="center_id">
                        <option value="">اختر المركز</option>
                        @foreach ($parents as $parent)
                            <option value="{{ $parent->id }}" {{ old('center_id', $item->center_id ?? '') == $parent->id ? 'selected' : '' }}>
                                {{ $parent->name }}{{ $parent->governorate ? ' - ' . $parent->governorate->name : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endif

        @if ($type === 'disbursement-frequencies')
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">الفاصل بالشهور</label>
                    <input type="number" class="form-control" name="months_interval" value="{{ old('months_interval', $item->months_interval ?? '') }}">
                    <small class="text-muted">يمكن تركه فارغًا في حالة "غير دوري".</small>
                </div>
            </div>
        @endif

        @if ($lookup['show_sort_order'])
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">الترتيب</label>
                    <input type="number" class="form-control" name="sort_order" value="{{ old('sort_order', $item->sort_order ?? 0) }}">
                </div>
            </div>
        @endif

        @if ($lookup['show_notes'])
            <div class="col-md-12">
                <div class="form-group">
                    <label class="form-label">ملاحظات</label>
                    <textarea class="form-control" rows="4" name="notes">{{ old('notes', $item->notes ?? '') }}</textarea>
                </div>
            </div>
        @endif
    </div>

    <div class="text-left">
        <button type="submit" class="btn btn-primary" id="{{ $formId === 'addForm' ? 'addButton' : 'updateButton' }}">{{ $submitLabel }}</button>
    </div>
</form>
