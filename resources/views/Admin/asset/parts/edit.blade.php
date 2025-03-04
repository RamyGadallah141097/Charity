<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{ route('assets.update' , $asset->id) }}">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name" class="form-control-label">الاسم</label>
            <input type="text" value="{{ $asset->name ?? '' }}" class="form-control" name="name" id="name" required>
        </div>
        <div class="form-group">
            <label for="description" class="form-control-label">الوصف</label>
            <textarea rows="3" class="form-control" name="description" id="description">{{ $asset->description ?? '' }}</textarea>
        </div>
        <div class="form-group">
            <label for="counter" class="form-control-label">العدد</label>
            <input type="number" value="{{ $asset->counter ?? '' }}" min="0" class="form-control" name="counter" id="counter" required>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
            <button type="submit" class="btn btn-primary" id="addButton">إضافة</button>
        </div>
    </form>
</div>
