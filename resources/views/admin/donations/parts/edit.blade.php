<div class="modal-body">
    <form id="updateForm" class="editForm" method="POST" enctype="multipart/form-data" action="{{ route('Donations.update', $donation->id) }}">
        @method('put')
        @csrf

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="search_donor" class="form-control-label">بحث عن المتبرع</label>
                    <input type="text" id="search_donor" class="form-control" placeholder="ابحث عن اسم المتبرع...">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="donor_id" class="form-control-label">اسم المتبرع</label>
                    <select name="donor_id" id="donor_name" class="form-control">
                        <option value="{{ $donation->donor_id }}">{{ optional($donation->donor)->name }}</option>
                        @foreach ($donors as $donor)
                            <option value="{{ $donor->id }}">{{ $donor->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="donor_phone" class="form-control-label">رقم المتبرع</label>
                    <input type="text" value="{{ optional($donation->donor)->phone }}" class="form-control" disabled id="donor_phone">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="received_at" class="form-control-label">تاريخ الاستلام</label>
                    <input type="date" class="form-control" name="received_at" id="received_at" value="{{ optional($donation->received_at)->format('Y-m-d') }}">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="receipt_number" class="form-control-label">رقم وصل التبرع</label>
                    <input type="text" class="form-control" name="receipt_number" id="receipt_number" value="{{ $donation->receipt_number }}">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="received_by_admin_id" class="form-control-label">المسؤول عن الاستلام</label>
                    <select name="received_by_admin_id" id="received_by_admin_id" class="form-control">
                        <option value="">اختر المسؤول</option>
                        @foreach ($admins as $admin)
                            <option value="{{ $admin->id }}" {{ $donation->received_by_admin_id == $admin->id ? 'selected' : '' }}>{{ $admin->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="donation_month" class="form-control-label">شهر التبرع</label>
                    <input type="number" min="1" max="12" class="form-control" name="donation_month" id="donation_month" value="{{ $donation->donation_month }}" readonly>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="donation_type_id" class="form-control-label">تصنيف التبرع</label>
                    <select name="donation_type_id" id="donation_type_id" class="form-control">
                        <option value="">اختر التصنيف</option>
                        @foreach ($donationTypes as $type)
                            <option value="{{ $type->id }}" {{ $donation->donation_type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
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
                            <option value="{{ $occasion }}" {{ $donation->occasion == $occasion ? 'selected' : '' }}>{{ $occasion }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="amount_value" class="form-control-label">مبلغ/كمية التبرع</label>
                    <input type="number" step="0.01" class="form-control" name="amount_value" id="amount_value" value="{{ $donation->amount_value }}">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="donation_unit_id" class="form-control-label">وحدة التبرع</label>
                    <select name="donation_unit_id" id="donation_unit_id" class="form-control">
                        <option value="">اختر الوحدة</option>
                        @foreach ($donationUnits as $unit)
                            <option value="{{ $unit->id }}" {{ $donation->donation_unit_id == $unit->id ? 'selected' : '' }}>{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="donation_amount" class="form-control-label">وصف إضافي أو ملاحظات كمية</label>
                    <input type="text" class="form-control" name="donation_amount" id="donation_amount" value="{{ $donation->donation_amount }}">
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
    $(function() {
        const $receivedAt = $('#received_at');
        const $donationMonth = $('#donation_month');

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

        $('#search_donor').on('keyup', function() {
            let query = $(this).val();
            $.ajax({
                url: "{{ route('search.donor') }}",
                method: 'GET',
                data: { donor_names: query },
                success: function(response) {
                    $('#donor_name').empty();
                    $('#donor_name').append('<option value="">اختر متبرع</option>');
                    $.each(response, function(key, donor) {
                        $('#donor_name').append('<option selected value="' + donor.id + '">' + donor.name + '</option>');
                    });
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
