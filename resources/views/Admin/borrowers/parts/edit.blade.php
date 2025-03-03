<style>
    .modal-dialog {
        max-width: calc(100vw - 100px) !important;
        margin: auto;
    }
</style>

<div class="modal-body">
    <form id="borrowerForm" class="borrowerForm" method="POST" enctype="multipart/form-data"
          action="{{ isset($borrower) ? route('borrowers.update', $borrower->id) : route('borrowers.store') }}">
        @csrf
        @method('PUT')

{{--        @if(isset($borrower))--}}
{{--            @method('PUT')--}}
{{--            <input type="hidden" name="id" value="{{ $borrower->id }}">--}}
{{--        @else--}}
{{--            @method('POST')--}}
{{--        @endif--}}

        <!-- Borrower Fields -->
        <h4 class="text-primary">معلومات المقترض</h4>
        <div class="form-group">
            <label for="name" class="form-control-label">الاسم</label>
            <input type="text" class="form-control" name="name" id="name"
                   value="{{ $borrower->name ?? '' }}" required>
        </div>

        <div class="form-group">
            <label for="phone" class="form-control-label">الهاتف</label>
            <input type="text" class="form-control" name="phone" id="phone"
                   value="{{ $borrower->phone ?? '' }}" required>
        </div>

        <div class="form-group">
            <label for="nationalID" class="form-control-label">الرقم القومي</label>
            <input type="text" class="form-control" name="nationalID" id="nationalID"
                   value="{{ $borrower->nationalID ?? '' }}" required>
        </div>

        <div class="form-group">
            <label for="address" class="form-control-label">العنوان</label>
            <input type="text" class="form-control" name="address" id="address"
                   value="{{ $borrower->address ?? '' }}" required>
        </div>

        <div class="form-group">
            <label for="job" class="form-control-label">المهنة</label>
            <input type="text" class="form-control" name="job" id="job"
                   value="{{ $borrower->job ?? '' }}" required>
        </div>

        <!-- Guarantors Section -->
        <h4 class="text-primary mt-4">الضامنين</h4>
        <div id="guarantorsContainer">
            @if(isset($borrower) && $borrower->guarantors->count() > 0)
                @foreach($borrower->guarantors as $index => $guarantor)
                    <div class="guarantor-item border p-3 mb-2" id="guarantor_{{ $guarantor->id }}">
                        <h5 class="text-secondary">كفيل {{ $index + 1 }}</h5>

                        <input type="hidden" name="guarantors[{{ $index }}][id]" value="{{ $guarantor->id }}">

                        <div class="form-group">
                            <label class="form-control-label">اسم الضامن</label>
                            <input type="text" class="form-control" name="guarantors[{{ $index }}][name]"
                                   value="{{ $guarantor->name }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">هاتف الضامن</label>
                            <input type="text" class="form-control" name="guarantors[{{ $index }}][phone]"
                                   value="{{ $guarantor->phone }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">الرقم القومي</label>
                            <input type="text" class="form-control" name="guarantors[{{ $index }}][nationalID]"
                                   value="{{ $guarantor->nationalID }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">العنوان</label>
                            <input type="text" class="form-control" name="guarantors[{{ $index }}][address]"
                                   value="{{ $guarantor->address }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">المهنة</label>
                            <input type="text" class="form-control" name="guarantors[{{ $index }}][job]"
                                   value="{{ $guarantor->job }}" required>
                        </div>

                        <button type="button" class="btn btn-danger removeExistingGuarantor" data-id="{{ $guarantor->id }}">
                            حذف الضامن
                        </button>
                    </div>


                @endforeach
            @endif
        </div>

        <!-- Button to Add More Guarantors -->
        <button type="button" class="btn btn-success mb-3" id="addGuarantor">إضافة كفيل</button>

        <hr>

        <div class="row form-group">
            <div class="col-6">
                <label>ملفات المقترض</label>
                <input class="form-control" accept="image/*"  type="file"  name="borrowerMedia[]" multiple />
            </div>
            <div class="col-6">
                <label>ملفات الضامن</label>
                <input class="form-control" accept="image/*" type="file" name="guarantorMedia[]" multiple />
            </div>
        </div>


        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
            <button type="submit" class="btn btn-primary" id="saveBorrowerButton">
                {{ isset($borrower) ? 'تحديث البيانات' : 'إضافة المقترض' }}
            </button>
        </div>
    </form>
</div>

<!-- JavaScript to Handle Dynamic Form -->
<script>
    $(document).ready(function () {
        let guarantorIndex = {{ isset($borrower) ? $borrower->guarantors->count() : 0 }};

        // Function to add a new guarantor form
        $('#addGuarantor').click(function () {
            guarantorIndex++;

            $('#guarantorsContainer').append(`
            <div class="guarantor-item border p-3 mb-2" id="guarantor_${guarantorIndex}">
                <h5 class="text-secondary">كفيل ${guarantorIndex}</h5>

                <div class="form-group">
                    <label class="form-control-label">اسم الضامن</label>
                    <input type="text" class="form-control" name="guarantors[${guarantorIndex}][name]" required>
                </div>

                <div class="form-group">
                    <label class="form-control-label">هاتف الضامن</label>
                    <input type="text" class="form-control" name="guarantors[${guarantorIndex}][phone]" required>
                </div>

                <div class="form-group">
                    <label class="form-control-label">الرقم القومي</label>
                    <input type="text" class="form-control" name="guarantors[${guarantorIndex}][nationalID]" required>
                </div>

                <div class="form-group">
                    <label class="form-control-label">العنوان</label>
                    <input type="text" class="form-control" name="guarantors[${guarantorIndex}][address]" required>
                </div>

                <div class="form-group">
                    <label class="form-control-label">المهنة</label>
                    <input type="text" class="form-control" name="guarantors[${guarantorIndex}][job]" required>
                </div>

                <button type="button" class="btn btn-danger removeGuarantor" data-id="${guarantorIndex}">حذف الضامن</button>
            </div>
        `);
        });

        // Function to remove a newly added guarantor form
        $(document).on('click', '.removeGuarantor', function () {
            $(this).parent().remove();
        });

        // Function to remove existing guarantor from database
        $(document).on('click', '.removeExistingGuarantor', function () {
            let id = $(this).data('id');
            $('#guarantor_' + id).remove();
            $('<input>').attr({ type: 'hidden', name: 'remove_guarantors[]', value: id }).appendTo('#borrowerForm');
        });
    });
</script>


<script>
    $(document).ready(function () {
        $("#borrowerForm").on("submit", function (e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: $(this).attr("action"),
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function () {
                    $(".text-danger").remove();
                    $("input, select, textarea").removeClass("is-invalid");
                },
                success: function (response) {

                    if(response.status == 200 ){
                        toastr.success("added successfully");
                        location.reload();
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function (key, messages) {
                            let field = $(`[name="${key}"]`);

                            if (field.length) {
                                field.addClass("is-invalid");
                                field.after(
                                    `<span class="text-danger">${messages[0]}</span>`
                                );
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "خطأ!",
                            text: "حدث خطأ غير متوقع. يرجى المحاولة مرة أخرى.",
                            confirmButtonText: "موافق",
                        });
                    }
                },
            });
        });
    });


</script>



