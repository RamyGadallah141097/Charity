<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data"
        action="{{ route('Donations.store') }}">
        @csrf


        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="donor_search_bar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-search" viewBox="0 0 16 16">
                        <path
                            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                    </svg></span>
            </div>
            <input type="text" id="search_donor" aria-label="Username" aria-describedby="basic-addon1"
                class="form-control" placeholder="ابحث عن اسم المتبرع...">
        </div>

        <div class="" id="create_donor">

        </div>

        <div class="form-group" id="select_donor_container">
            <label for="donor_id" class="form-control-label">اسم المتبرع</label>
            <select name="donor_id" id="donor_name" class="form-control">
                <option value="">اختر متبرع</option>
                @foreach ($donors as $donor)
                    <option value="{{ $donor->id }}">{{ $donor->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="name" class="form-control-label">رقم المتبرع</label>
            <input type="text" class="form-control" disabled id="donor_phone">


        </div>



        <div class="form-group">
            <label for="donation_type" class="form-control-label">نوع التبرع </label>
            <select name="donation_type" id="type" class="form-control">
                <option value="0">زكاة المال </option> // the first type 0
                <option value="1"> صدقات</option> // the second type 1
                <option value="2">قرض حسن </option>// the third type 2
                <option value="3">تبرع عيني </option>// the forth type 3
            </select>
        </div>

        <div class="form-group">
            <label for="donation_amount" class="form-control-label">مبلغ التبرع</label>
            <input type="text" class="form-control" name="donation_amount" id="donation_amount">
        </div>

        <div class="form-group">
            <label for="created_at" class="form-control-label">تاريخ الانشاء </label>
            <input type="date" class="form-control" name="created_at" id="created_at"
                value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
        </div>


        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
            <button type="submit" class="btn btn-primary" id="addButton">اضافة</button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#search_donor').on('keyup', function() {
            let query = $(this).val();

            $.ajax({
                url: "{{ route('search.donor') }}",
                method: 'GET',
                data: {
                    donor_names: query
                },
                success: function(response) {
                    $('#donor_name').empty();
                    $("#create_donor").empty();
                    $('#donor_name').append('<option value="">اختر متبرع</option>');
                    if (response.length > 0) {
                        $.each(response, function(key, donor) {
                            $('#donor_name').append('<option selected value="' +
                                donor.id + '">' + donor.name + '</option>');
                            $('input[id="donor_phone"]').val(donor.phone);

                        });
                    } else {
                        $("#create_donor").empty();
                        $("#create_donor").append(`
                            <button class="btn btn-secondary btn-icon text-white addBtn" >
                               <a class="text-white" href="{{ route('donors.index') }}"> <i class="fe fe-plus"></i> اضافة متبرع جديد</>
                            </button>
                        `);
                        $("input[id='donor_phone']").empty();
                    }
                },
                error: function() {
                    console.log("Error retrieving donors");
                }
            });
        });
    });
</script>


<script>
    $(document).ready(function() {
        $('select[id="donor_name"]').on('change', function() {
                var donor_name = $(this).val();
                $.ajax({
                    url: '{{ route('get_donor_phone', ':donor_name') }}'.replace(":donor_name",
                        donor_name),
                    type: "GET",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        id: donor_name
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response && response.donor_phone) {
                            $('input[id="donor_phone"]').val(response.donor_phone);
                        } else {
                            $('input[id="donor_phone"]').val('غير متوفر');
                        }
                    },
                });
            }

        );
    });
</script>
