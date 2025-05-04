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
        max-height: 80vh;
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
          action="{{ isset($borrower) ? route('borrowers.update', $borrower->id) : route('borrowers.store') }}">
        @csrf
        @method('PUT')

        <!-- Borrower Fields -->
        <h4 class="text-primary">معلومات المقترض</h4>
        <div class="row">
            <div class="form-group col-6">
                <label for="name" class="form-control-label">الاسم</label>
                <input type="text" class="form-control" name="name" id="name"
                       value="{{ $borrower->name ?? '' }}" required>
            </div>

            <div class="form-group col-6">
                <label for="phone" class="form-control-label">الهاتف</label>
                <input type="text" class="form-control" name="phone" id="phone"
                       value="{{ $borrower->phone ?? '' }}" required>
            </div>

            <div class="form-group col-6">
                <label for="nationalID" class="form-control-label">الرقم القومي</label>
                <input type="text" class="form-control" name="nationalID" id="nationalID"
                       value="{{ $borrower->nationalID ?? '' }}" required>
            </div>

            <div class="form-group col-6">
                <label for="borrower_age" class="form-control-label">السن</label>
                <input type="text" class="form-control" readonly
                       value="{{ $borrower->borrower_age ?? '' }}" name="borrower_age" id="borrower_age">
            </div>


            <div class="form-group col-6">
                <label for="address" class="form-control-label">العنوان</label>
                <input type="text" class="form-control" name="address" id="address"
                       value="{{ $borrower->address ?? '' }}" required>
            </div>

            <div class="form-group col-6">
                <label for="job" class="form-control-label">المهنة</label>
                <input type="text" class="form-control" name="job" id="job"
                       value="{{ $borrower->job ?? '' }}" required>
            </div>
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
                {{ isset($borrower) ? 'تحديث البيانات' : 'إضافة المقترض' }}
            </button>
        </div>
    </form>
</div>

<script>
    $(document).ready(function () {
        let guarantorIndex = {{ isset($borrower) ? $borrower->guarantors->count() : 0 }};

        // Load images when modal opens
        @if(isset($borrower))
        loadMedia({{ $borrower->id }});
        @endif

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
        let basePath = "{{ asset('') }}";
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
