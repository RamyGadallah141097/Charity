<div class="modal-body">
    <form id="updateForm" class="management-form" method="POST" enctype="multipart/form-data" action="{{ route('admins.update', $admin->id) }}">
        @csrf
        @method('PUT')

        <div class="form-section">
            <h6 class="section-title">الملف الشخصي</h6>
            <div class="form-group mb-0">
                <label class="form-control-label">الصورة الشخصية</label>
                <input type="file" class="dropify" name="image" data-default-file="{{ get_user_file($admin->image) }}" />
            </div>
        </div>

        <div class="form-section">
            <h6 class="section-title">البيانات الأساسية</h6>
            <div class="form-group">
                <label class="form-control-label">الاسم</label>
                <input type="text" class="form-control" name="name" value="{{ $admin->name }}">
            </div>

            <div class="form-group">
                <label class="form-control-label">الصفة / المنصب</label>
                <input type="text" class="form-control" name="job_title" value="{{ $admin->job_title }}">
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label class="form-control-label">رقم الهاتف</label>
                    <input type="text" class="form-control" name="phone" value="{{ $admin->phone }}">
                </div>
                <div class="form-group col-md-6">
                    <label class="form-control-label">الرقم القومي</label>
                    <input type="text" class="form-control" name="national_id" maxlength="14" value="{{ $admin->national_id }}">
                </div>
            </div>
        </div>

        <div class="form-section">
            <h6 class="section-title">العنوان ومكان السكن</h6>
            <div class="form-group">
                <label class="form-control-label">العنوان</label>
                <textarea class="form-control" name="address" rows="2">{{ $admin->address }}</textarea>
            </div>

            <div class="form-row">
                <div class="form-group col-md-4">
                    <label class="form-control-label">المحافظة</label>
                    <select name="governorate_id" class="form-control select2">
                        <option value="">اختر المحافظة</option>
                        @foreach($governorates as $governorate)
                            <option value="{{ $governorate->id }}" {{ $admin->governorate_id == $governorate->id ? 'selected' : '' }}>{{ $governorate->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label class="form-control-label">المركز</label>
                    <select name="center_id" class="form-control select2">
                        <option value="">اختر المركز</option>
                        @foreach($centers as $center)
                            <option value="{{ $center->id }}" data-governorate-id="{{ $center->governorate_id }}" {{ $admin->center_id == $center->id ? 'selected' : '' }}>{{ $center->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label class="form-control-label">القرية</label>
                    <select name="village_id" class="form-control select2">
                        <option value="">اختر القرية</option>
                        @foreach($villages as $village)
                            <option value="{{ $village->id }}" data-center-id="{{ $village->center_id }}" {{ $admin->village_id == $village->id ? 'selected' : '' }}>{{ $village->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h6 class="section-title">المرفقات والملاحظات</h6>
            <div class="form-group">
                <label class="form-control-label">وثائق مهمة</label>
                @if(is_array($admin->documents) && count($admin->documents))
                    <div class="management-documents-grid mb-3">
                        @foreach($admin->documents as $document)
                            @php
                                $extension = strtolower(pathinfo($document, PATHINFO_EXTENSION));
                                $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                $documentUrl = asset($document);
                            @endphp

                            <a href="{{ $documentUrl }}" target="_blank" class="management-document-card">
                                @if($isImage)
                                    <img src="{{ $documentUrl }}" alt="document" class="management-document-image">
                                @else
                                    <div class="management-document-file">
                                        <i class="fe fe-file-text"></i>
                                        <span>{{ strtoupper($extension ?: 'FILE') }}</span>
                                    </div>
                                @endif
                            </a>
                        @endforeach
                    </div>
                    <small class="d-block text-muted mt-2">عدد المرفقات الحالية: {{ count($admin->documents) }}</small>
                @endif
                <input type="file" class="form-control" name="documents[]" multiple accept=".pdf,image/*">
            </div>

            <div class="form-group mb-0">
                <label class="form-control-label">ملاحظات</label>
                <textarea class="form-control" name="notes" rows="3">{{ $admin->notes }}</textarea>
            </div>
        </div>

        <div class="form-group mb-3">
            <label class="custom-switch">
                <input type="checkbox" class="custom-switch-input" id="is_system_user" name="is_system_user" value="1" {{ $admin->is_system_user ? 'checked' : '' }}>
                <span class="custom-switch-indicator"></span>
                <span class="custom-switch-description">مستخدم للنظام</span>
            </label>
        </div>

        <div id="system-user-fields" class="form-section">
            <h6 class="section-title">بيانات الدخول للنظام</h6>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label class="form-control-label">الايميل</label>
                    <input type="text" class="form-control" name="email" value="{{ $admin->email }}">
                </div>
                <div class="form-group col-md-6">
                    <label class="form-control-label">كلمة المرور</label>
                    <input type="password" class="form-control" name="password" placeholder="اتركها فارغة إذا لا تريد التغيير">
                </div>
            </div>
            <div class="form-group mb-0">
                <label>الصلاحية</label>
                <select name="adminRole" class="form-control select2">
                    <option value="">اختر الصلاحية</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ $admin->roles->pluck('name')->contains($role->name) ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
            <button type="submit" class="btn btn-success" id="updateButton">تحديث</button>
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

        filterCenters('{{ $admin->center_id }}');
        filterVillages('{{ $admin->village_id }}');

        function toggleSystemUserFields() {
            const isSystemUser = $('#is_system_user').is(':checked');
            $('#system-user-fields').toggle(isSystemUser);

            $('#system-user-fields').find('input, select').each(function () {
                if (isSystemUser) {
                    $(this).prop('disabled', false);
                } else {
                    $(this).prop('disabled', true);
                    if ($(this).attr('name') !== 'password') {
                        $(this).val(null).trigger('change');
                    }
                }
            });
        }

        $('#is_system_user').on('change', toggleSystemUserFields);
        toggleSystemUserFields();
    });
</script>
