<div class="modal-body">
    <h4 class="text-primary">معلومات المقترض</h4>
    <form id="addBorrowerForm" class="addForm" method="POST" enctype="multipart/form-data" action="<?php echo e(route('borrowers.store')); ?>">
        <?php echo csrf_field(); ?>
        <div class="form-group row">
            <div class="form-group col-6">
                <label for="name" class="form-control-label">الاسم</label>
                <input type="text" class="form-control" name="name" id="name" required>
            </div>

            <div class="form-group col-6">
                <label for="phone" class="form-control-label">الهاتف</label>
                <input type="number" class="form-control" name="phone" maxlength="12" minlength="11" id="phone" required>
            </div>

            <div class="form-group col-6">
                <label for="BnationalID" class="form-control-label">الرقم القومي</label>
                <input type="number" class="form-control" name="nationalID" maxlength="15" minlength="14" id="BnationalID" required>
            </div>

            <div class="form-group col-6">
                <label for="borrower_age" class="form-control-label">السن</label>
                <input type="text" class="form-control" readonly value="1" name="borrower_age" id="borrower_age">
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
        <div id="guarantorsContainer"></div>
        <button type="button" class="btn btn-success mb-3" id="addGuarantor">إضافة ضامن</button>

        <hr>

        <div class="row form-group">
            <div class="col-6">
                <label>ملفات المقترض</label>
                <input class="form-control dropify" accept="image/*" type="file" name="borrowerMedia[]" multiple />
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

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dropify/dist/js/dropify.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/toastr@2.1.4/toastr.min.js"></script>

<script>
    $(document).ready(function () {
        let guarantorIndex = 0;

        function calculateAgeFromID(nationalID) {
            if (nationalID.length < 14) return '';

            const centuryCode = nationalID[0];
            const year = parseInt(nationalID.substr(1, 2));
            const month = parseInt(nationalID.substr(3, 2));
            const day = parseInt(nationalID.substr(5, 2));
            let fullYear;

            if (centuryCode === '2') {
                fullYear = 1900 + year;
            } else if (centuryCode === '3') {
                fullYear = 2000 + year;
            } else {
                return '';
            }

            const birthDate = new Date(fullYear, month - 1, day);
            const today = new Date();
            let years = today.getFullYear() - birthDate.getFullYear();
            let months = today.getMonth() - birthDate.getMonth();

            if (months < 0 || (months === 0 && today.getDate() < birthDate.getDate())) {
                years--;
                months += 12;
            }

            return `${years} سنة و ${months} شهر`;
        }

        $('#BnationalID').on('input', function () {
            const age = calculateAgeFromID(this.value);
            $('#borrower_age').val(age);
        });

        $('#addGuarantor').click(function () {
            guarantorIndex++;

            $('#guarantorsContainer').append(`
                <div class="guarantor-item border p-3 mb-2" id="guarantor_${guarantorIndex}">
                    <h5 class="text-secondary">${guarantorIndex} ضامن</h5>
                    <div class="row">
                        <div class="form-group col-6">
                            <label>اسم الضامن</label>
                            <input type="text" class="form-control" name="guarantors[${guarantorIndex}][name]" required>
                        </div>
                        <div class="form-group col-6">
                            <label>هاتف الضامن</label>
                            <input type="number" class="form-control" name="guarantors[${guarantorIndex}][phone]" required>
                        </div>
                        <div class="form-group col-6">
                            <label>الرقم القومي</label>
                            <input type="number" class="form-control guarantor-nid" data-index="${guarantorIndex}" name="guarantors[${guarantorIndex}][nationalID]" required>
                        </div>
                        <div class="form-group col-6">
                            <label>سن الضامن</label>
                            <input type="text" class="form-control" name="guarantors[${guarantorIndex}][guarantorAge]" id="guarantor_age_${guarantorIndex}" readonly>
                        </div>
                        <div class="form-group col-6">
                            <label>العنوان</label>
                            <input type="text" class="form-control" name="guarantors[${guarantorIndex}][address]" required>
                        </div>
                        <div class="form-group col-6">
                            <label>المهنة</label>
                            <input type="text" class="form-control" name="guarantors[${guarantorIndex}][job]" required>
                        </div>
                    </div>
                    <button type="button" class="btn btn-danger removeGuarantor" data-id="${guarantorIndex}">حذف الضامن</button>
                </div>
            `);
        });

        $(document).on('input', '.guarantor-nid', function () {
            const index = $(this).data('index');
            const nationalID = $(this).val();
            const age = calculateAgeFromID(nationalID);
            $(`#guarantor_age_${index}`).val(age);
        });

        $(document).on('click', '.removeGuarantor', function () {
            let id = $(this).data('id');
            $('#guarantor_' + id).remove();
        });

        $('#addBorrowerForm').on('submit', function (e) {
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
                        setTimeout(() => location.reload(), 500);
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function (key, messages) {
                            let field = $(`[name="${key}"]`);
                            if (field.length) {
                                field.addClass("is-invalid");
                                field.after(`<span class="text-danger">${messages[0]}</span>`);
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

        $('.dropify').dropify();
    });
</script>
<?php /**PATH /home/kariem/Desktop/Projects/new-zakat/resources/views/admin/borrowers/parts/create.blade.php ENDPATH**/ ?>