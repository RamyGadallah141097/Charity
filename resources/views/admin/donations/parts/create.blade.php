<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{ route('Donations.store') }}">
        @csrf

        <div class="row">
            <div class="col-md-6">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="donor_search_bar">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                            </svg>
                        </span>
                    </div>
                    <input type="text" id="search_donor" class="form-control" placeholder="ابحث عن اسم المتبرع">
                </div>
            </div>
            <div class="col-md-6 d-flex align-items-end">
                <div id="create_donor"></div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="donor_name" class="form-control-label">اسم المتبرع</label>
                    <select name="donor_id" id="donor_name" class="form-control">
                        <option value="">اختر متبرع</option>
                        @foreach ($donors as $donor)
                            <option value="{{ $donor->id }}">{{ $donor->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="donor_phone" class="form-control-label">رقم المتبرع</label>
                    <input type="text" class="form-control" disabled id="donor_phone">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="received_at" class="form-control-label">تاريخ الاستلام</label>
                    <input type="date" class="form-control" name="received_at" id="received_at" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="receipt_number" class="form-control-label">رقم وصل التبرع</label>
                    <input type="text" class="form-control" name="receipt_number" id="receipt_number">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="received_by_admin_id" class="form-control-label">المسؤول عن الاستلام</label>
                    <select name="received_by_admin_id" id="received_by_admin_id" class="form-control">
                        <option value="">اختر المسؤول</option>
                        @foreach ($admins as $admin)
                            <option value="{{ $admin->id }}" {{ auth()->id() == $admin->id ? 'selected' : '' }}>{{ $admin->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="donation_type_id" class="form-control-label">نوع التبرع</label>
                    <select name="donation_type_id" id="donation_type_id" class="form-control">
                        <option value="">اختر التصنيف</option>
                        @foreach ($donationTypes as $type)
                            <option value="{{ $type->id }}" data-code="{{ $type->code }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="occasion" class="form-control-label">مناسبة التبرع</label>
                    <select name="occasion" id="occasion" class="form-control">
                        <option value="">اختر المناسبة</option>
                        @foreach ($occasions as $occasion)
                            <option value="{{ $occasion }}">{{ $occasion }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-4 d-none" id="donation-category-wrapper">
                <div class="form-group">
                    <label for="donation_category_id" class="form-control-label">صنف التبرع العيني</label>
                    <select name="donation_category_id" id="donation_category_id" class="form-control">
                        <option value="">اختر الصنف</option>
                        @foreach ($donationCategories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="amount_value" class="form-control-label">مبلغ/كمية التبرع</label>
                    <input type="number" step="0.01" class="form-control" name="amount_value" id="amount_value">
                </div>
            </div>

            <div class="col-md-4" id="donation-unit-wrapper">
                <div class="form-group">
                    <label for="donation_unit_id" class="form-control-label">وحدة التبرع</label>
                    <select name="donation_unit_id" id="donation_unit_id" class="form-control">
                        <option value="">اختر الوحدة</option>
                        @foreach ($donationUnits as $unit)
                            <option value="{{ $unit->id }}" data-category-ids="{{ $unit->categories->pluck('id')->implode(',') }}" data-code="{{ $unit->code }}">{{ $unit->name }}</option>
                        @endforeach
                    </select>
                    <small class="text-muted d-none" id="cash-unit-note">سيتم اعتماد وحدة "جنيه" تلقائيًا لهذا النوع.</small>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="donation_month" class="form-control-label">شهر التبرع</label>
                    <input type="number" min="1" max="12" class="form-control" name="donation_month" id="donation_month" readonly>
                </div>
            </div>

            <div class="col-12">
                <div class="form-group">
                    <label for="donation_amount" class="form-control-label">وصف إضافي أو ملاحظات كمية</label>
                    <input type="text" class="form-control" name="donation_amount" id="donation_amount">
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
    $(function() {
        const $receivedAt = $('#received_at');
        const $donationMonth = $('#donation_month');
        const $donationType = $('#donation_type_id');
        const $donationCategory = $('#donation_category_id');
        const $donationCategoryWrapper = $('#donation-category-wrapper');
        const $donationUnit = $('#donation_unit_id');
        const $donationUnitWrapper = $('#donation-unit-wrapper');
        const $cashUnitNote = $('#cash-unit-note');
        const cashUnitId = @json($cashDonationUnitId);
        const originalUnitOptions = $donationUnit.html();

        function syncDonationMonth() {
            const value = $receivedAt.val();
            if (!value) {
                $donationMonth.val('');
                return;
            }

            const month = new Date(value).getMonth() + 1;
            $donationMonth.val(month);
        }

        syncDonationMonth();
        $receivedAt.on('change', syncDonationMonth);

        function syncDonationUnits() {
            const selectedOption = $donationType.find('option:selected');
            const selectedTypeCode = selectedOption.data('code');
            const selectedCategoryId = $donationCategory.val();
            const isCashType = selectedTypeCode === 'cash' || selectedTypeCode === 'good_loan';
            const previousValue = $donationUnit.val();

            $donationUnit.html(originalUnitOptions);

            $donationUnit.find('option').each(function() {
                const $option = $(this);
                const optionCategoryIds = (($option.data('category-ids') || '') + '').split(',').filter(Boolean);
                const optionCode = $option.data('code');
                const shouldKeep = !$option.val()
                    || (isCashType && optionCode === 'egp')
                    || (!isCashType && selectedCategoryId && optionCategoryIds.includes(selectedCategoryId));

                if (!shouldKeep) {
                    $option.remove();
                }
            });

            if (isCashType) {
                $donationCategory.val('');
                $donationCategoryWrapper.addClass('d-none');
                $donationUnit.val(cashUnitId || '');
                $donationUnitWrapper.addClass('d-none');
                $cashUnitNote.removeClass('d-none');
            } else {
                $donationCategoryWrapper.removeClass('d-none');
                $donationUnitWrapper.removeClass('d-none');
                $cashUnitNote.addClass('d-none');

                if (!selectedCategoryId) {
                    $donationUnit.val('');
                    $donationUnit.prop('disabled', true);
                    return;
                }

                $donationUnit.prop('disabled', false);

                if (previousValue && $donationUnit.find('option[value="' + previousValue + '"]').length) {
                    $donationUnit.val(previousValue);
                } else {
                    $donationUnit.val('');
                }

            }

            $donationUnit.trigger('change');
        }

        syncDonationUnits();
        $donationType.on('change', syncDonationUnits);
        $donationCategory.on('change', syncDonationUnits);

        $('#search_donor').on('keyup', function() {
            let query = $(this).val();
            $.ajax({
                url: "{{ route('search.donor') }}",
                method: 'GET',
                data: { donor_names: query },
                success: function(response) {
                    $('#donor_name').empty();
                    $("#create_donor").empty();
                    $('#donor_name').append('<option value="">اختر متبرع</option>');
                    if (response.length > 0) {
                        $.each(response, function(key, donor) {
                            $('#donor_name').append('<option selected value="' + donor.id + '">' + donor.name + '</option>');
                            $('#donor_phone').val(donor.phone);
                        });
                    } else {
                        $("#create_donor").html('<a class="btn btn-secondary text-white" href="{{ route('donors.index') }}"><i class="fe fe-plus"></i> اضافة متبرع جديد</a>');
                        $('#donor_phone').val('');
                    }
                }
            });
        });

        $('#donor_name').on('change', function() {
            var donor_name = $(this).val();
            $.ajax({
                url: '{{ route('get_donor_phone', ':donor_name') }}'.replace(":donor_name", donor_name),
                type: "GET",
                data: { "_token": "{{ csrf_token() }}", id: donor_name },
                dataType: "json",
                success: function(response) {
                    $('#donor_phone').val(response && response.donor_phone ? response.donor_phone : 'غير متوفر');
                },
            });
        });
    });
</script>
