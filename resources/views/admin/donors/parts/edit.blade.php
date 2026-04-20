<div class="modal-body">
    <form id="updateForm" class="editForm" method="POST" enctype="multipart/form-data" action="{{ route('updateDonor', $donor->id) }}">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name" class="form-control-label">الاسم</label>
                    <input type="text" class="form-control" name="name" id="name" value="{{ $donor->name }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="phone" class="form-control-label">رقم التليفون 1</label>
                    <input type="text" class="form-control" name="phone" id="phone" value="{{ $donor->phone }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="phone_second" class="form-control-label">رقم التليفون 2</label>
                    <input type="text" class="form-control" name="phone_second" id="phone_second" value="{{ $donor->phone_second }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="relative_phone" class="form-control-label">رقم تليفون قريب</label>
                    <input type="text" class="form-control" name="relative_phone" id="relative_phone" value="{{ $donor->relative_phone }}">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="governorate_id" class="form-control-label">المحافظة</label>
                    <select class="form-control donor-governorate" name="governorate_id" id="governorate_id">
                        <option value="">اختر المحافظة</option>
                        @foreach ($governorates as $governorate)
                            <option value="{{ $governorate->id }}" {{ $donor->governorate_id == $governorate->id ? 'selected' : '' }}>{{ $governorate->name }}</option>
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
                            <option value="{{ $center->id }}" data-governorate-id="{{ $center->governorate_id }}" {{ $donor->center_id == $center->id ? 'selected' : '' }}>{{ $center->name }}</option>
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
                            <option value="{{ $village->id }}" data-center-id="{{ $village->center_id }}" {{ $donor->village_id == $village->id ? 'selected' : '' }}>{{ $village->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-8">
                <div class="form-group">
                    <label for="detailed_address" class="form-control-label">العنوان التفصيلي</label>
                    <textarea rows="2" class="form-control" name="detailed_address" id="detailed_address">{{ $donor->detailed_address }}</textarea>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="address" class="form-control-label">العنوان المختصر</label>
                    <input type="text" class="form-control" name="address" id="address" value="{{ $donor->address }}">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="preferred_donation_types" class="form-control-label">أنواع التبرع المفضلة</label>
                    <select multiple class="form-control" name="preferred_donation_types[]" id="preferred_donation_types">
                        @foreach ($donationTypes as $type)
                            <option value="{{ $type->id }}" {{ $donor->preferredDonationTypes->contains('id', $type->id) ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="burn_date" class="form-control-label">تاريخ الميلاد</label>
                    <input type="date" class="form-control" name="burn_date" id="burn_date" value="{{ $donor->burn_date }}">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="created_at" class="form-control-label">تاريخ الانشاء</label>
                    <input type="date" class="form-control" name="created_at" id="created_at" value="{{ optional($donor->created_at)->format('Y-m-d') }}">
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    <label for="notes" class="form-control-label">الملاحظات</label>
                    <textarea rows="3" class="form-control" name="notes" id="notes">{{ $donor->notes }}</textarea>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
            <button type="submit" class="btn btn-primary" id="updateButton">تحديث</button>
        </div>
    </form>
</div>

<script>
    (function() {
        const modal = $('#editOrCreate');
        const $governorate = modal.find('.donor-governorate');
        const $center = modal.find('.donor-center');
        const $village = modal.find('.donor-village');
        const selectedCenterId = $center.val();
        const selectedVillageId = $village.val();
        const centerOptions = $center.html();
        const villageOptions = $village.html();

        function refreshSelect2($select) {
            $select.trigger('change.select2');
        }

        function filterCenters(selectedId) {
            const governorateId = $governorate.val();
            const filteredCenters = $(centerOptions).filter(function() {
                return !this.value || $(this).data('governorate-id') == governorateId;
            });

            $center.html(filteredCenters);

            if (selectedId && $center.find('option[value="' + selectedId + '"]').length) {
                $center.val(selectedId);
            } else {
                $center.val('');
            }

            refreshSelect2($center);
        }

        function filterVillages(selectedId) {
            const centerId = $center.val();
            const filteredVillages = $(villageOptions).filter(function() {
                return !this.value || $(this).data('center-id') == centerId;
            });

            $village.html(filteredVillages);

            if (selectedId && $village.find('option[value="' + selectedId + '"]').length) {
                $village.val(selectedId);
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

        filterCenters(selectedCenterId);
        filterVillages(selectedVillageId);
    })();
</script>
