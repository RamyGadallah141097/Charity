<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{ route('donors.store') }}">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">الاسم</label>
                    <input type="text" class="form-control" name="name" id="name" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="phone" class="form-control-label">رقم التليفون 1</label>
                    <input type="text" class="form-control" name="phone" id="phone" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="phone_second" class="form-control-label">رقم التليفون 2</label>
                    <input type="text" class="form-control" name="phone_second" id="phone_second">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="relative_phone" class="form-control-label">رقم تليفون قريب</label>
                    <input type="text" class="form-control" name="relative_phone" id="relative_phone">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="governorate_id" class="form-control-label">المحافظة</label>
                    <select class="form-control donor-governorate" name="governorate_id" id="governorate_id">
                        <option value="">اختر المحافظة</option>
                        @foreach ($governorates as $governorate)
                            <option value="{{ $governorate->id }}">{{ $governorate->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="center_id" class="form-control-label">المركز</label>
                    <select class="form-control donor-center" name="center_id" id="center_id">
                        <option value="">اختر المركز</option>
                        @foreach ($centers as $center)
                            <option value="{{ $center->id }}" data-governorate-id="{{ $center->governorate_id }}">{{ $center->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="village_id" class="form-control-label">القرية</label>
                    <select class="form-control donor-village" name="village_id" id="village_id">
                        <option value="">اختر القرية</option>
                        @foreach ($villages as $village)
                            <option value="{{ $village->id }}" data-center-id="{{ $village->center_id }}">{{ $village->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-8">
                <div class="form-group">
                    <label for="detailed_address" class="form-control-label">العنوان التفصيلي</label>
                    <textarea rows="2" class="form-control" name="detailed_address" id="detailed_address"></textarea>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="address" class="form-control-label">العنوان المختصر</label>
                    <input type="text" class="form-control" name="address" id="address">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="preferred_donation_types" class="form-control-label">أنواع التبرعات </label>
                    <select multiple class="form-control" name="preferred_donation_types[]" id="preferred_donation_types">
                        @foreach ($donationTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="burn_date" class="form-control-label">تاريخ الميلاد</label>
                    <input type="date" class="form-control" name="burn_date" id="burn_date">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="created_at" class="form-control-label">تاريخ الانشاء</label>
                    <input type="date" class="form-control" name="created_at" id="created_at" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label for="notes" class="form-control-label">الملاحظات</label>
                    <textarea rows="3" class="form-control" name="notes" id="notes"></textarea>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
            <button type="submit" class="btn btn-primary" id="addButton">اضافة</button>
        </div>
    </form>
</div>

<script>
    (function() {
        const modal = $('#editOrCreate');
        const $governorate = modal.find('.donor-governorate');
        const $center = modal.find('.donor-center');
        const $village = modal.find('.donor-village');
        const centerOptions = $center.html();
        const villageOptions = $village.html();

        function refreshSelect2($select) {
            $select.trigger('change.select2');
        }

        function filterCenters(selectedCenterId) {
            const governorateId = $governorate.val();
            const filteredCenters = $(centerOptions).filter(function() {
                return !this.value || $(this).data('governorate-id') == governorateId;
            });

            $center.html(filteredCenters);

            if (selectedCenterId && $center.find('option[value="' + selectedCenterId + '"]').length) {
                $center.val(selectedCenterId);
            } else {
                $center.val('');
            }

            refreshSelect2($center);
        }

        function filterVillages(selectedVillageId) {
            const centerId = $center.val();
            const filteredVillages = $(villageOptions).filter(function() {
                return !this.value || $(this).data('center-id') == centerId;
            });

            $village.html(filteredVillages);

            if (selectedVillageId && $village.find('option[value="' + selectedVillageId + '"]').length) {
                $village.val(selectedVillageId);
            } else {
                $village.val('');
            }

            refreshSelect2($village);
        }

        $governorate.on('change', function() {
            filterCenters('');
            filterVillages('');
        });

        $center.on('change', function() {
            filterVillages('');
        });

        filterCenters('');
        filterVillages('');
    })();
</script>
