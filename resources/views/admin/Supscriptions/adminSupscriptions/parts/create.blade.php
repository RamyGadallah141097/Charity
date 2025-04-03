<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{ route('adminSubscription.store') }}">
        @csrf

        <!-- admin Selection (Assuming Admins Table Exists) -->
        <div class="form-group">
            <label for="admin_id" class="form-control-label">المسؤول</label>
            <select class="form-control" name="admin_id" id="admin_id" required>
                <option value="" disabled selected>اختر المسؤول</option>
                @foreach ($admins as $admin)
                    <option value="{{ $admin->id }}">{{ $admin->name }}</option>
                @endforeach
            </select>
        </div>


        <div class="form-group">
            <label for="months_count" class="form-control-label">عدد الشهور</label>
            <select class="form-control" name="months_count" id="months_count">
                <option value=1 selected >شهر واحد</option>
                @php
                    $monthNames = [
                        2 => "شهران",         3 => "ثلاثة أشهر",   4 => "أربعة أشهر",
                        5 => "خمسة أشهر",     6 => "ستة أشهر",    7 => "سبعة أشهر",
                        8 => "ثمانية أشهر",   9 => "تسعة أشهر",   10 => "عشرة أشهر",
                        11 => "أحد عشر شهرًا", 12 => "اثنا عشر شهرًا"
                    ];
                @endphp
                @foreach($monthNames as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>

        <!-- Amount -->
        <div class="form-group">
            <label for="amount" class="form-control-label">القيمة</label>
            <input type="number" min="0" readonly class="form-control" name="amount" id="amount" required>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
            <button type="submit" class="btn btn-primary" id="addButton">إضافة</button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function(){
        let total = $('select[name="months_count"]').val();


        $.ajax({
            url: "{{ route('getSubscriptionPrice') }}",
            type: "GET",
            success: function(response) {
                if(response.success) {
                    $('#amount').val(total * response.price);
                } else {
                    alert("Error fetching price");
                }
            }
        });

        $('select[name="months_count"]').on("change", function(){
            let total = $(this).val();


            $.ajax({
                url: "{{ route('getSubscriptionPrice') }}",
                type: "GET",
                success: function(response) {
                    if(response.success) {
                        $('#amount').val(total * response.price);
                    } else {
                        alert("Error fetching price");
                    }
                }
            });
        });
    });


</script>
