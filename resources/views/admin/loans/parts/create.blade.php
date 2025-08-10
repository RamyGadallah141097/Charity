<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{ route('store.loans') }}">
        @csrf
        <!-- <div class="input-group mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="donor_search_bar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-search" viewBox="0 0 16 16">
                        <path
                            d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0" />
                    </svg></span>
            </div>
            <input type="text" id="search_borrower" aria-label="Username" aria-describedby="basic-addon1"
                class="form-control" placeholder="ابحث عن اسم المقترض.">
        </div> -->

        <div class="" id="create_donor">

        </div>

        <div class="form-group" id="select_donor_container">
            <label for="borrower_id" class="form-control-label">اسم المقترض</label>
            <select name="borrower_id" id="borrower_id" class="form-control">
                <option value="">اختر مقترض</option>
                @foreach ($borrowers as $borrower)
                    <option value="{{ $borrower->id }}">{{ $borrower->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="borrower_phone" class="form-control-label">رقم المقترض</label>
            <input type="text" class="form-control"  id="borrower_phone" readonly>
        </div>

        <div class="form-group">
            <label for="donation_amount" class="form-control-label">مبلغ القرض </label>
            <input type="text" class="form-control" name="loan_amount" id="loan_amount">
        </div>

        <input type="hidden" value="null" name="isStarted">

        <div class="form-group">
            <label for="loan_date" class="form-control-label">تاريخ القرض </label>
            <input type="date" class="form-control" name="loan_date" id="loan_date"
                value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
        </div>

        <div>
            <label for="type">نوع القرض</label>
            <select class="form-control" name="type" id="type">
                <option value="0" selected>قرض عادي</option>
                <option value="1">قرض جمعيه</option>
            </select>
        </div>



        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
            <button type="submit" class="btn btn-primary" id="addButton">اضافة</button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        let borrowersData = {}; // كائن لتخزين بيانات المقترضين

        $('#search_borrower').on('keyup', function() {
            let query = $(this).val();

            $.ajax({
                url: "{{ route('search.Borrowers') }}",
                method: 'GET',
                data: {
                    borrower_name: query
                },
                success: function(response) {
                    $('#borrower_id').empty();
                    $('#borrower_id').append('<option value="">اختر المقترض</option>');

                    // تفريغ بيانات المقترضين قبل التحديث
                    borrowersData = {};

                    if (response.length > 0) {
                        $.each(response, function(key, borrower) {
                            $('#borrower_id').append('<option value="' +
                                borrower.id + '">' + borrower.name + '</option>'
                            );

                            // تخزين رقم الهاتف لكل مقترض باستخدام ID كـ مفتاح
                            borrowersData[borrower.id] = borrower.phone;
                        });
                    } else {
                        $('#borrower_id').append(
                            '<option value="" disabled>لا يوجد نتائج</option>');
                        $("input[id='borrower_phone']").val('');
                    }
                },
                error: function() {
                    console.log("Error retrieving borrowers");
                }
            });
        });


        $('#borrower_id').on('change', function() {
            let selectedBorrowerId = $(this).val();
            console.log(selectedBorrowerId);

            $.ajax({
               url: "{{ route('search.BorrowerPhone') }}",
                method:'get',
                data: {
                    borrower_id: selectedBorrowerId
                },
                success: function(response) {
                    if (response.length > 0) {
                        let borrower = response[0];
                        $("input[id='borrower_phone']").val(borrower.phone);
                    } else {
                        $("input[id='borrower_phone']").val('');
                    }
                }
            });
        });
    });
</script>