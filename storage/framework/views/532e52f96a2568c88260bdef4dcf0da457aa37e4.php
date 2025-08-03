<style>
    /* Modal Styles */
    .modal-dialog {
        max-width: calc(90vw - 100px) !important;
        margin: auto;
        overflow: clip !important;
    }

    .modal-xl {
        max-width: 90% !important;
    }

    .modal-body {
        /*max-height: 80vh;*/
        overflow-y: auto;
    }

    /* Form Styles */
    .borrowerForm .form-group {
        margin-bottom: 1rem;
    }

    .borrowerForm .text-primary {
        margin-top: 1.5rem;
        margin-bottom: 1rem;
    }

    .guarantor-item {
        background-color: #f8f9fa;
        border-radius: 5px;
        margin-bottom: 1rem;
    }

    .guarantor-item h5 {
        margin-bottom: 1rem;
    }

    /* Image Preview Styles */
    .img-thumbnail {
        padding: 0.25rem;
        background-color: #fff;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        max-width: 100%;
        height: auto;
    }

    /* Button Styles */
    .removeGuarantor, .removeExistingGuarantor {
        margin-top: 0.5rem;
    }

    #addGuarantor {
        margin-top: 1rem;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .modal-dialog {
            max-width: 95vw !important;
        }

        .modal-xl {
            max-width: 95% !important;
        }

        .form-group.col-6 {
            width: 100%;
        }
    }
</style>
<div class="modal-body">
    <form id="borrowerForm" class="borrowerForm" method="POST" enctype="multipart/form-data"
          action="<?php echo e(isset($borrower) ? route('borrowers.update', $borrower->id) : route('borrowers.store')); ?>">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <!-- Borrower Fields -->
        <h4 class="text-primary">معلومات المقترض</h4>
        <div class="row">
            <div class="form-group col-6">
                <label for="name" class="form-control-label">الاسم</label>
                <input type="text" class="form-control" name="name" id="name"
                       value="<?php echo e($borrower->name ?? ''); ?>" required>
            </div>

            <div class="form-group col-6">
                <label for="phone" class="form-control-label">الهاتف</label>
                <input type="text" class="form-control" name="phone" id="phone"
                       value="<?php echo e($borrower->phone ?? ''); ?>" required>
            </div>

            <div class="form-group col-6">
                <label for="nationalID" class="form-control-label">الرقم القومي</label>
                <input type="text" class="form-control" name="nationalID" id="nationalID"
                       value="<?php echo e($borrower->nationalID ?? ''); ?>" required>
            </div>

            <div class="form-group col-6">
                <label for="borrower_age" class="form-control-label">السن</label>
                <input type="text" class="form-control" readonly
                       value="<?php echo e($borrower->borrower_age ?? ''); ?>" name="borrower_age" id="borrower_age">
            </div>


            <div class="form-group col-6">
                <label for="address" class="form-control-label">العنوان</label>
                <input type="text" class="form-control" name="address" id="address"
                       value="<?php echo e($borrower->address ?? ''); ?>" required>
            </div>

            <div class="form-group col-6">
                <label for="job" class="form-control-label">المهنة</label>
                <input type="text" class="form-control" name="job" id="job"
                       value="<?php echo e($borrower->job ?? ''); ?>" required>
            </div>
        </div>

        <!-- Guarantors Section -->
        <h4 class="text-primary mt-4">الضامنين</h4>
        <div id="guarantorsContainer">
            <?php if(isset($borrower) && $borrower->guarantors->count() > 0): ?>
                <?php $__currentLoopData = $borrower->guarantors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $guarantor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="guarantor-item border p-3 mb-2" id="guarantor_<?php echo e($guarantor->id); ?>">
                        <h5 class="text-secondary">كفيل <?php echo e($index + 1); ?></h5>

                        <input type="hidden" name="guarantors[<?php echo e($index); ?>][id]" value="<?php echo e($guarantor->id); ?>">

                        <div class="form-group">
                            <label class="form-control-label">اسم الضامن</label>
                            <input type="text" class="form-control" name="guarantors[<?php echo e($index); ?>][name]"
                                   value="<?php echo e($guarantor->name); ?>" required>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">هاتف الضامن</label>
                            <input type="text" class="form-control" name="guarantors[<?php echo e($index); ?>][phone]"
                                   value="<?php echo e($guarantor->phone); ?>" required>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">الرقم القومي</label>
                            <input type="text" class="form-control" name="guarantors[<?php echo e($index); ?>][nationalID]"
                                   value="<?php echo e($guarantor->nationalID); ?>" required>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">السن</label>
                            <input type="text" class="form-control" name="guarantors[<?php echo e($index); ?>][guarantorAge]"
                                   value="<?php echo e($guarantor->guarantorAge); ?>" required>
                        </div>



                        <div class="form-group">
                            <label class="form-control-label">العنوان</label>
                            <input type="text" class="form-control" name="guarantors[<?php echo e($index); ?>][address]"
                                   value="<?php echo e($guarantor->address); ?>" required>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">المهنة</label>
                            <input type="text" class="form-control" name="guarantors[<?php echo e($index); ?>][job]"
                                   value="<?php echo e($guarantor->job); ?>" required>
                        </div>

                        <button type="button" class="btn btn-danger removeExistingGuarantor" data-id="<?php echo e($guarantor->id); ?>">
                            حذف الضامن
                        </button>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>

        <!-- Button to Add More Guarantors -->
        <button type="button" class="btn btn-success mb-3" id="addGuarantor">إضافة كفيل</button>

        <hr>

        <div class="row form-group">
            <div class="col-12 row">
                <div class="col-6 ">
                    <label>ملفات المقترض</label>
                    <input class="form-control dropify" accept="image/*" type="file" name="borrowerMedia[]" multiple />
                </div>
                <div class="col-6">
                    <label>ملفات الضامن</label>
                    <input class="form-control dropify" accept="image/*" type="file" name="guarantorMedia[]" multiple />
                </div>
            </div>
            <div class="col-12 row">
                <div class="modal-body">
                    <!-- Borrower Images -->
                    <h5 class="text-primary">صور المقترض</h5>
                    <div class="modal-dialog modal-xl" style="width: 100%">
                        <div class="row" id="borrowerMediaContainer">
                            <!-- Borrower images will load here -->
                        </div>
                    </div>

                    <hr>

                    <!-- Guarantor Images -->
                    <h5 class="text-secondary">صور الضامن</h5>
                    <div class="modal-dialog modal-xl" style="width: 100%">
                        <div class="row" id="guarantorMediaContainer">
                            <!-- Guarantor images will load here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
            <button type="submit" class="btn btn-primary" id="saveBorrowerButton">
                <?php echo e(isset($borrower) ? 'تحديث البيانات' : 'إضافة المقترض'); ?>

            </button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function () {
        let guarantorIndex = <?php echo e(isset($borrower) ? $borrower->guarantors->count() : 0); ?>;

        // Load images when modal opens
        <?php if(isset($borrower)): ?>
        loadMedia(<?php echo e($borrower->id); ?>);
        <?php endif; ?>

        // Function to add a new guarantor form
        $('#addGuarantor').click(function () {
            guarantorIndex++;

            $('#guarantorsContainer').append(`
            <div class="guarantor-item border p-3 mb-2" id="guarantor_${guarantorIndex}">
                <h5 class="text-secondary">كفيل ${guarantorIndex}</h5>

                <div class="row">
                    <div class="form-group col-6">
                        <label class="form-control-label">اسم الضامن</label>
                        <input type="text" class="form-control" name="guarantors[${guarantorIndex}][name]" required>
                    </div>

                    <div class="form-group col-6">
                        <label class="form-control-label">هاتف الضامن</label>
                        <input type="text" class="form-control" name="guarantors[${guarantorIndex}][phone]" required>
                    </div>

                    <div class="form-group col-6">
                        <label class="form-control-label">الرقم القومي</label>
                        <input type="text" class="form-control" name="guarantors[${guarantorIndex}][nationalID]" required>
                    </div>

                    <div class="form-group col-6">
                        <label class="form-control-label">السن</label>
                        <input type="text" class="form-control" name="guarantors[${guarantorIndex}][guarantorAge]" readonly>
                    </div>

                    <div class="form-group col-6">
                        <label class="form-control-label">العنوان</label>
                        <input type="text" class="form-control" name="guarantors[${guarantorIndex}][address]" required>
                    </div>

                    <div class="form-group col-6">
                        <label class="form-control-label">المهنة</label>
                        <input type="text" class="form-control" name="guarantors[${guarantorIndex}][job]" required>
                    </div>
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

        // Form submission handler
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
                        toastr.success("تم الحفظ بنجاح");
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

    function loadMedia(borrowerId) {
        let basePath = "<?php echo e(asset('')); ?>";
        $.ajax({
            url: `/admin/borrowers/${borrowerId}/media`,
            type: "GET",
            success: function(response) {
                $("#borrowerMediaContainer").empty();
                $("#guarantorMediaContainer").empty();

                let borrowerHtml = "";
                let guarantorHtml = "";

                response.media.forEach((media) => {
                    let imageUrl = basePath + media.path;
                    let mediaHtml = `
                        <div class="col-lg-4 col-md-6 col-12 mb-3">
                            <img src="${imageUrl}" style="height: 300px; width: 100%; object-fit: contain;" class="img-fluid img-thumbnail">
                        </div>
                    `;
                    if (media.type == 1) {
                        guarantorHtml += mediaHtml;
                    } else {
                        borrowerHtml += mediaHtml;
                    }
                });

                $("#borrowerMediaContainer").html(borrowerHtml);
                $("#guarantorMediaContainer").html(guarantorHtml);
            },
            error: function() {
                toastr.error("فشل في تحميل الصور");
            }
        });
    }

    // Initialize dropify
    $('.dropify').dropify();
</script>
<script>
    document.getElementById('nationalID').addEventListener('input', function () {
        const id = this.value;

        if (id.length === 14) {
            const centuryCode = id[0];
            const year = parseInt(id.substr(1, 2), 10);
            const month = parseInt(id.substr(3, 2), 10);
            const day = parseInt(id.substr(5, 2), 10);

            let fullYear;
            if (centuryCode === '2') {
                fullYear = 1900 + year;
            } else if (centuryCode === '3') {
                fullYear = 2000 + year;
            } else {
                document.getElementById('borrower_age').value = '';
                return;
            }

            const birthDate = new Date(fullYear, month - 1, day);
            const today = new Date();

            let ageYears = today.getFullYear() - birthDate.getFullYear();
            let ageMonths = today.getMonth() - birthDate.getMonth();

            if (today.getDate() < birthDate.getDate()) {
                ageMonths--;
            }

            if (ageMonths < 0) {
                ageYears--;
                ageMonths += 12;
            }

            document.getElementById('borrower_age').value = `${ageYears} سنة و ${ageMonths} شهر`;
        } else {
            document.getElementById('borrower_age').value = '';
        }
    });
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Function to calculate age in format "XX سنة و YY شهر"
    function calculateAgeFromNationalID(nationalID) {
        if (!/^\d{14}$/.test(nationalID)) return null;

        const centuryCode = nationalID[0];
        let year = parseInt(nationalID.slice(1, 3));
        const month = parseInt(nationalID.slice(3, 5)) - 1; // JS months 0-11
        const day = parseInt(nationalID.slice(5, 7));

        if (centuryCode === '2') {
            year += 1900;
        } else if (centuryCode === '3') {
            year += 2000;
        } else {
            return null;
        }

        const birthDate = new Date(year, month, day);
        const now = new Date();

        let ageYears = now.getFullYear() - birthDate.getFullYear();
        let ageMonths = now.getMonth() - birthDate.getMonth();

        if (now.getDate() < birthDate.getDate()) {
            ageMonths--;
        }
        if (ageMonths < 0) {
            ageYears--;
            ageMonths += 12;
        }

        return `${ageYears} سنة و ${ageMonths} شهر`;
    }

    // Attach handler to all National ID inputs
    function attachAgeCalculator() {
        $('input[name^="guarantors"][name$="[nationalID]"]').off('input').on('input', function () {
            const nationalID = $(this).val();
            const age = calculateAgeFromNationalID(nationalID);
            if (age) {
                const ageInputName = $(this).attr('name').replace('[nationalID]', '[guarantorAge]');
                $(`input[name="${ageInputName}"]`).val(age);
            }
        });
    }

    // Initialize and bind when new row added
    $(document).ready(function () {
        attachAgeCalculator();

        $('#addGuarantor').click(function () {
            setTimeout(attachAgeCalculator, 100); // Ensure input is in DOM
        });
    });
</script>
<?php /**PATH /home/kariem/Desktop/Projects/new-zakat/resources/views/admin/borrowers/parts/edit.blade.php ENDPATH**/ ?>