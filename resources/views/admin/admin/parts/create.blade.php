<div class="modal-body">
    <form id="addForm" class="addForm management-form" method="POST" enctype="multipart/form-data" action="{{ route('admins.store') }}">
        @csrf

        <div class="form-section">
            <h6 class="section-title">الملف الشخصي</h6>
            <div class="form-group mb-0">
                <label class="form-control-label">الصورة الشخصية</label>
                <input type="file" class="dropify" name="image" data-default-file="{{ asset('assets/uploads/avatar.png') }}" accept="image/png, image/gif, image/jpeg, image/jpg" />
                <span class="form-text text-danger text-center">مسموح فقط: png, gif, jpeg, jpg</span>
            </div>
        </div>

        <div class="form-section">
            <h6 class="section-title">البيانات الأساسية</h6>
            <div class="form-group">
                <label class="form-control-label">الاسم</label>
                <input type="text" class="form-control" name="name" id="name">
            </div>

            <div class="form-group">
                <label class="form-control-label">الصفة / المنصب</label>
                <select name="job_title" class="form-control select2">
                    <option value="">اختر الصفة / المنصب</option>
                    @foreach($job_titles as $job)
                        <option value="{{ $job->name }}">{{ $job->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label class="form-control-label">رقم الهاتف</label>
                    <input type="text" class="form-control" name="phone">
                </div>
                <div class="form-group col-md-6">
                    <label class="form-control-label">الرقم القومي</label>
                    <input type="text" class="form-control" name="national_id" maxlength="14">
                </div>
            </div>
        </div>

        <div class="form-section">
            <h6 class="section-title">العنوان ومكان السكن</h6>
            <div class="form-group">
                <label class="form-control-label">العنوان</label>
                <textarea class="form-control" name="address" rows="2"></textarea>
            </div>

            <div class="form-row">
                <div class="form-group col-md-4">
                    <label class="form-control-label">المحافظة</label>
                    <select name="governorate_id" class="form-control select2">
                        <option value="">اختر المحافظة</option>
                        @foreach($governorates as $governorate)
                            <option value="{{ $governorate->id }}">{{ $governorate->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label class="form-control-label">المركز</label>
                    <select name="center_id" class="form-control select2">
                        <option value="">اختر المركز</option>
                        @foreach($centers as $center)
                            <option value="{{ $center->id }}" data-governorate-id="{{ $center->governorate_id }}">{{ $center->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label class="form-control-label">القرية</label>
                    <select name="village_id" class="form-control select2">
                        <option value="">اختر القرية</option>
                        @foreach($villages as $village)
                            <option value="{{ $village->id }}" data-center-id="{{ $village->center_id }}">{{ $village->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h6 class="section-title">المرفقات والملاحظات</h6>
            <div class="form-group">
                <label class="form-control-label">وثائق مهمة</label>
                <input type="file" class="form-control" name="documents[]" multiple accept=".pdf,image/*">
                <small class="text-muted">يمكن رفع أكثر من ملف</small>
            </div>

            <div class="form-group mb-0">
                <label class="form-control-label">ملاحظات</label>
                <textarea class="form-control" name="notes" rows="3"></textarea>
            </div>
        </div>

        <div class="form-group mb-3">
            <label class="custom-switch">
                <input type="checkbox" class="custom-switch-input" id="is_system_user" name="is_system_user" value="1" checked>
                <span class="custom-switch-indicator"></span>
                <span class="custom-switch-description">مستخدم للنظام</span>
            </label>
        </div>

        <div id="system-user-fields" class="form-section">
            <h6 class="section-title">بيانات الدخول للنظام</h6>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="email" class="form-control-label">الايميل</label>
                    <input type="text" class="form-control" name="email" id="email">
                </div>
                <div class="form-group col-md-6">
                    <label for="password" class="form-control-label">كلمة المرور</label>
                    <input type="password" class="form-control" name="password" id="password">
                </div>
            </div>
            <div class="form-group mb-0">
                <label>الصلاحية</label>
                <select name="adminRole" class="form-control select2">
                    <option value="">اختر الصلاحية</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
            <button type="submit" class="btn btn-primary" id="addButton">اضافة</button>
        </div>
    </form>
</div>

<script>
    $(function () {
        $('.dropify').dropify();
        $('.select2').select2({ width: '100%' });

        const $governorate = $('select[name="governorate_id"]');
        const $center = $('select[name="center_id"]');
        const $village = $('select[name="village_id"]');
        const centerOptions = $center.html();
        const villageOptions = $village.html();

        function filterCenters(selectedCenterId) {
            const governorateId = $governorate.val();
            const filteredCenters = $(centerOptions).filter(function () {
                return !this.value || $(this).data('governorate-id') == governorateId;
            });
            $center.html(filteredCenters).val(selectedCenterId).trigger('change.select2');
        }

        function filterVillages(selectedVillageId) {
            const centerId = $center.val();
            const filteredVillages = $(villageOptions).filter(function () {
                return !this.value || $(this).data('center-id') == centerId;
            });
            $village.html(filteredVillages).val(selectedVillageId).trigger('change.select2');
        }

        $governorate.on('change', function () {
            filterCenters('');
            filterVillages('');
        });

        $center.on('change', function () {
            filterVillages('');
        });

        function toggleSystemUserFields() {
            const isSystemUser = $('#is_system_user').is(':checked');
            $('#system-user-fields').toggle(isSystemUser);

            $('#system-user-fields').find('input, select').each(function () {
                if (isSystemUser) {
                    $(this).prop('disabled', false);
                } else {
                    $(this).prop('disabled', true).val(null).trigger('change');
                }
            });
        }

        $('#is_system_user').on('change', toggleSystemUserFields);
        toggleSystemUserFields();
    });
</script>
