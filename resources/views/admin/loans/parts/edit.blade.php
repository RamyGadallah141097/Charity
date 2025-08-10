<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{route('Donations.update' , $donation->id)}}">
        @method("put")
        @csrf

{{--        <div class="form-group">--}}
{{--            <label for="donor_id" class="form-control-label">اسم المتبرع</label>--}}
{{--            <select name="donor_id" id="donor_name" class="form-control">--}}
{{--                <option value="{{$donation->donor_id}}">{{$donation->donor->name}}</option>--}}
{{--                @foreach($donors as $donor)--}}
{{--                    <option value="{{$donor->id}}">{{$donor->name}}</option>--}}
{{--                @endforeach--}}
{{--            </select>--}}
{{--        </div>--}}

        <div class="form-group">
            <label for="search_donor" class="form-control-label">بحث عن المتبرع</label>
            <input type="text" id="search_donor" class="form-control" placeholder="ابحث عن اسم المتبرع...">
        </div>

        <div class="form-group">
            <label for="donor_id" class="form-control-label">اسم المتبرع</label>
            <select name="donor_id" id="donor_name" class="form-control">
            <option value="{{$donation->donor_id}}">{{$donation->donor->name}}</option>
            @foreach($donors as $donor)
                    <option value="{{$donor->id}}">{{$donor->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="name" class="form-control-label" >رقم المتبرع</label>
            <input type="text" value="{{$donation->donor->phone}}"  class="form-control" disabled   id="donor_phone">
        </div>



        <div class="form-group">
            <label for="donation_type" class="form-control-label">نوع التبرع </label>
            <select name="donation_type" id="type" class="form-control">
                <option value="{{$donation->donation_type}}" selected>@php
                    switch ($donation->donation_type) {
                        case 0:
                            echo 'زكاة المال';
                            break;
                        case 1:
                            echo 'صدقات';
                            break;
                        case 2:
                            echo 'قرض حسن';
                            break;
                        default:
                            echo 'تبرع عيني';
                    }
                @endphp</option>
                <option value="0">زكاة المال </option>  // the first type 0
                <option value="1"> صدقات</option> // the second type 1
                <option value="2">قرض حسن </option>// the third type 2
                <option value="3">تبرع عيني  </option>// the forth type 3
            </select>
        </div>

        <div class="form-group">
            <label for="donation_amount" class="form-control-label">مبلغ التبرع</label>
            <input type="text" class="form-control" name="donation_amount" id="donation_amount" value="{{ $donation->donation_amount }}">
        </div>

        <div class="form-group">
            <label for="created_at" class="form-control-label">تاريخ الانشاء </label>
            <input type="date" class="form-control" name="created_at" id="created_at" value="{{ $donation->created_at->format('Y-m-d') }}">
        </div>


        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
            <button type="submit" class="btn btn-primary" id="addButton">تحديث</button>
        </div>
    </form>
</div>



<script>
    $(document).ready(function() {
        $('#search_donor').on('keyup', function() {
            let query = $(this).val();
            // console.log(query)
            $.ajax({
                url: "{{ route('search.donor') }}",
                method: 'GET',
                data: { donor_names: query },
                success: function(response) {
                    $('#donor_name').empty();
                    $('#donor_name').append('<option value="">اختر متبرع</option>');

                    // Append new options based on the response
                    $.each(response, function(key, donor) {
                        $('#donor_name').append('<option selected value="'+ donor.id +'">'+ donor.name +'</option>');
                    });
                }
            });
        });
    });
</script>


<script>
    $(document).ready(function () {
        $('select[id="donor_name"]').on('change', function () {
                var donor_name = $(this).val();
                $.ajax({
                    url: '{{route('get_donor_phone' , ':donor_name')}}'.replace(":donor_name", donor_name),
                    type: "GET",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        id: donor_name
                    },
                    dataType: "json",
                    success: function (response) {
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

    $('#borrower_id').on('change', function() {
        let selectedBorrowerId = $(this).val();

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
