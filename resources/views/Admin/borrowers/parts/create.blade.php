
<style>
    .modal-dialog {
        max-width: calc(80vw - 80px) !important;
        margin: auto;
    }
</style>

<div class="modal-body ">
    <h4 class="text-primary">معلومات المقترض</h4>
    <form id="addBorrowerForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{ route('borrowers.store') }}">
        @csrf


        <div class="form-group row">
            <!-- Borrower Fields -->

            <div class="form-group col-6">
                <label for="name" class="form-control-label">الاسم</label>
                <input type="text" class="form-control" name="name" id="name" required>
            </div>

            <div class="form-group col-6">
                <label for="phone" class="form-control-label">الهاتف</label>
                <input type="number" class="form-control" name="phone" maxlength="12"  minlength="11" id="phone" required>
            </div>

            <div class="form-group col-6">
                <label for="nationalID" class="form-control-label">الرقم القومي</label>
                <input type="number" class="form-control" name="nationalID" maxlength="15" minlength="14" id="nationalID" required>
            </div>

            <div class="form-group col-6">
                <label for="address" class="form-control-label">العنوان</label>
                <input type="text" class="form-control" name="address" id="address" required>
            </div>

            <div class="form-group col-6">
                <label for="job" class="form-control-label">المهنة</label>
                <input type="text" class="form-control" name="job" id="job" required>
            </div>

        </div>

        <h4 class="text-primary mt-4"> معلومات الضامنين</h4>
        <div id="guarantorsContainer">

        </div>


        <button type="button" class="btn btn-success mb-3" id="addGuarantor">إضافة ضامن</button>


        <hr>

        <div class="row form-group">
            <div class="col-6">
                <label>ملفات المقترض</label>
                <input class="form-control dropify" accept="image/*"  type="file"  name="borrowerMedia[]" multiple />
            </div>
            <div class="col-6">
                <label>ملفات الضامن</label>
                <input class="form-control dropify" accept="image/*" type="file" name="guarantorMedia[]" multiple />
            </div>
        </div>


        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
            <button type="submit" class="btn btn-primary" id="addBorrowerButton">إضافة المقترض</button>
        </div>
    </form>
</div>

<!-- JavaScript to Handle Dynamic Form -->
<script>
    $(document).ready(function () {
        let guarantorIndex = 0;

        //اضافه ضامن جديد في الفورم
        $('#addGuarantor').click(function () {
            guarantorIndex++;

            $('#guarantorsContainer').append(`
            <div class="guarantor-item border p-3 mb-2" id="guarantor_${guarantorIndex}">
                <h5 class="text-secondary"> ${guarantorIndex} ضامن  ${guarantorIndex}</h5>

                <div class="row">
                    <div class="form-group col-6">
                        <label for="guarantors[${guarantorIndex}][name]" class="form-control-label">اسم الضامن</label>
                        <input type="text" class="form-control" name="guarantors[${guarantorIndex}][name]" required>
                    </div>

                    <div class="form-group col-6">
                        <label for="guarantors[${guarantorIndex}][phone]" class="form-control-label">هاتف الضامن</label>
                        <input type="number" class="form-control" name="guarantors[${guarantorIndex}][phone]" required>
                    </div>

                    <div class="form-group col-6">
                        <label for="guarantors[${guarantorIndex}][nationalID]" class="form-control-label">الرقم القومي</label>
                        <input type="number" class="form-control" name="guarantors[${guarantorIndex}][nationalID]" required>
                    </div>

                    <div class="form-group col-6">
                        <label for="guarantors[${guarantorIndex}][address]" class="form-control-label">العنوان</label>
                        <input type="text" class="form-control" name="guarantors[${guarantorIndex}][address]" required>
                    </div>

                    <div class="form-group col-6">
                        <label for="guarantors[${guarantorIndex}][job]" class="form-control-label">المهنة</label>
                        <input type="text" class="form-control" name="guarantors[${guarantorIndex}][job]" required>
                    </div>
                </div>

                <button type="button" class="btn btn-danger removeGuarantor" data-id="${guarantorIndex}">حذف الضامن</button>

            </div>


        `);
        });

        //حذف الضامن من الفورم
        $(document).on('click', '.removeGuarantor', function () {
            let id = $(this).data('id');
            $('#guarantor_' + id).remove();
        });
    });
</script>

<script>
    $(document).ready(function () {
        $("#addBorrowerForm").on("submit", function (e) {
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
                    if (response.status === 200) {
                        toastr.success("تمت الإضافة بنجاح!");

                        setTimeout(function () {
                            location.reload();
                        }, 500);
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

<script>
    $('.dropify').dropify()
</script>


