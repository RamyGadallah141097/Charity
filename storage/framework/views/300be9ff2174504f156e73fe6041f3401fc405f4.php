<?php $__env->startSection('title'); ?>
<?php echo e(isset($setting) ? $setting->title : ''); ?> | القروض الحسنة
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page_name'); ?>
القروض الحسنة
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

<style>
    /* Image Gallery Styles */
    .gallery-image {
        cursor: pointer;
        transition: 0.3s;
        margin: 5px;
        max-height: 200px;
        object-fit: cover;
    }

    .gallery-image:hover {
        opacity: 0.7;
    }

    /* Modal Styles */
    .image-modal {
        display: none;
        position: fixed;
        z-index: 2000;
        padding-top: 50px;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.9);
    }

    .modal-content {
        margin: auto;
        display: block;
        max-width: 90%;
        /*max-height: 80vh;*/
        animation: zoom 0.6s;
    }

    @keyframes zoom {
        from {
            transform: scale(0.1)
        }

        to {
            transform: scale(1)
        }
    }

    .close {
        position: absolute;
        top: 15px;
        right: 35px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        transition: 0.3s;
    }

    .close:hover,
    .close:focus {
        color: #bbb;
        text-decoration: none;
        cursor: pointer;
    }

    /* Table Styles */
    #dataTable {
        width: 100% !important;
    }

    /* Modal Dialog Adjustments */
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

    /* Responsive Adjustments */
    @media (max-width: 992px) {
        .modal-dialog {
            max-width: 95vw !important;
        }

        .modal-content {
            max-width: 95%;
        }

        #dataTable {
            width: 100%;
            display: block;
            overflow-x: auto;
        }
    }

    /* Review Form Styles */
    #borrowerReviewForm textarea {
        min-height: 150px;
    }
</style>



<style>
    .star-rating {
        direction: rtl;
        display: inline-block;
        cursor: pointer;
    }

    .star-rating input {
        display: none;
    }

    .star-rating label {
        color: #ddd;
        font-size: 24px;
        padding: 0 2px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .star-rating label:hover,
    .star-rating label:hover~label,
    .star-rating input:checked~label {
        color: #ffc107;
    }
</style>

<script>
    document.querySelectorAll('.star-rating:not(.readonly) label').forEach(star => {
        star.addEventListener('click', function() {
            this.style.transform = 'scale(1.2)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 200);
        });
    });
</script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<?php if ($errors->any()): ?>
    <div class="alert alert-danger">
        <ul>
            <?php $__currentLoopData = $errors->all();
            $__env->addLoop($__currentLoopData);
            foreach ($__currentLoopData as $error): $__env->incrementLoopIndices();
                $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
            <?php endforeach;
            $__env->popLoop();
            $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-md-12 col-lg-12">
        <div class="card">
            <div class="p-3">
                <div class="card-header">
                    <h3 class="card-title">المقترضين من القروض الحسنة <?php echo e(isset($setting) ? $setting->title : ''); ?></h3>
                    <div class="">
                        <button class="btn btn-secondary btn-icon text-white addBtn">
                            <span><i class="fe fe-plus"></i></span> اضافة جديد
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered text-nowrap w-75" id="dataTable">
                        <thead>
                            <tr class="fw-bolder text-muted bg-light">
                                <th class="min-w-25px">#</th>
                                <th class="min-w-50px">الاسم المقترض</th>
                                <th class="min-w-125px">رقم المقترض</th>
                                <th class="min-w-125px">الرقم القومي للمقترض</th>
                                <th class="min-w-125px">عمر للمقترض</th>
                                <th class="min-w-125px">عنوان المقترض</th>
                                <th class="min-w-125px"> 5 /التقييم</th>
                                <th class="min-w-125px">عمل المقترض</th>
                                <th class="min-w-125px">العمليات</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete MODAL -->
    <div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">حذف بيانات</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="<?php echo e(route("delete_borrowers")); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field("post"); ?>
                    <div class="modal-body">
                        <input id="delete_id" name="id" type="hidden">
                        <p>هل انت متأكد من حذف البيانات التالية <span id="title" class="text-danger"></span>؟</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal" id="dismiss_delete_modal">اغلاق</button>
                        <button type="submit" class="btn btn-danger" id="delete_btn">حذف !</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Create Or Edit Modal -->
    <div class="modal fade" id="editOrCreate" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="example-Modal3">بيانات المتبرع</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modal-body"></div>
            </div>
        </div>
    </div>

    <!-- Guarantor Details Modal -->
    <div class="modal fade" id="guarantorModal" tabindex="-1" aria-labelledby="guarantorModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="guarantorModalLabel">تفاصيل الكفلاء</h5>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">
                        <i class="fa fa-window-close"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>الاسم</th>
                                <th>الهاتف</th>
                                <th>الرقم القومي</th>
                                <th>السن</th>
                                <th>العنوان</th>
                                <th>العمل</th>
                            </tr>
                        </thead>
                        <tbody id="guarantorModalBodyTable"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Media Modal -->
    <div class="modal fade" id="mediaModal" tabindex="-1" role="dialog" aria-labelledby="mediaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">صور المقترض والضامن</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <!-- Borrower Images -->
                    <h5 class="text-primary">صور المقترض</h5>
                    <div class="row" id="borrowerMedia"></div>

                    <hr>

                    <!-- Guarantor Images -->
                    <h5 class="text-secondary">صور الضامن</h5>
                    <div class="row" id="guarantorMedia"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Zoom Modal -->
    <div id="imageZoomModal" class="image-modal">
        <span class="close" onclick="closeZoomModal()">&times;</span>
        <img class="modal-content" id="zoomedImage">
    </div>

    <!-- borrowerReviewModal Modal -->
    <div class="modal fade" id="borrowerReviewModal" tabindex="-1" role="dialog" aria-labelledby="borrowerReviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تقييم المقترض</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo e(route('BorrowerReview')); ?>" method="POST" id="borrowerReviewForm">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="borrower_id" id="borrower_idReview">
                        <div class="form-group">
                            <label for="review">التقييم</label>
                            <textarea class="form-control" name="review" id="review" rows="4" placeholder="اكتب التقييم هنا..."></textarea>
                        </div>



                        <div class="col-md-6">
                            <div class="rating-card p-4">
                                <div class="star-rating animated-stars">
                                    <input type="radio" id="star5" name="rating" value="5">
                                    <label for="star5" class="bi bi-star-fill"></label>
                                    <input type="radio" id="star4" name="rating" value="4">
                                    <label for="star4" class="bi bi-star-fill"></label>
                                    <input type="radio" id="star3" name="rating" value="3">
                                    <label for="star3" class="bi bi-star-fill"></label>
                                    <input type="radio" id="star2" name="rating" value="2">
                                    <label for="star2" class="bi bi-star-fill"></label>
                                    <input type="radio" id="star1" name="rating" value="1">
                                    <label for="star1" class="bi bi-star-fill"></label>
                                </div>
                            </div>
                        </div>


                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">إرسال التقييم</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $__env->make('admin/layouts/myAjaxHelper', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('ajaxCalls'); ?>
<script>
    var columns = [{
            data: null,
            name: 'index',
            render: function(data, type, row, meta) {
                return meta.row + 1;
            },
            orderable: false,
            searchable: false
        },
        {
            data: 'name',
            name: 'name'
        },
        {
            data: 'phone',
            name: 'phone'
        },
        {
            data: 'nationalID',
            name: 'nationalID'
        },
        {
            data: 'borrower_age',
            name: 'borrower_age'
        },
        {
            data: 'address',
            name: 'address'
        },
        {
            data: 'rate',
            name: 'rate'
        },
        {
            data: 'job',
            name: 'job'
        },
        {
            data: 'action',
            name: 'action'
        },
    ]
    showData('<?php echo e(route('borrowers.index')); ?>', columns);
    deleteScript('<?php echo e(route('delete_borrowers')); ?>');
    showAddModal('<?php echo e(route('borrowers.create')); ?>');
    addScript();
    showEditModal('<?php echo e(route('borrowers.edit', ':id')); ?>');
    editScript();

    $(document).ready(function() {
        $(document).on('click', '.view-guarantors', function() {
            let borrower_id = $(this).data('id');
            $.ajax({
                url: "<?php echo e(route('getGuarantor')); ?>",
                type: "GET",
                data: {
                    borrower_id: borrower_id
                },
                success: function(response) {
                    let modalBody = $('#guarantorModalBodyTable');
                    modalBody.empty();

                    if (response.guarantors.length > 0) {
                        response.guarantors.forEach(function(guarantor, index) {
                            modalBody.append(
                                `<tr>
                                        <th scope="row">${index + 1}</th>
                                        <td>${guarantor.name}</td>
                                        <td>${guarantor.phone}</td>
                                        <td>${guarantor.nationalID}</td>
                                        <td>${guarantor.guarantorAge}</td>
                                        <td>${guarantor.address}</td>
                                        <td>${guarantor.job}</td>
                                    </tr>`
                            );
                        });
                    } else {
                        modalBody.append('<p class="text-center text-danger">لا يوجد كفلاء</p>');
                    }
                    $('#guarantorModal').modal('show');
                },
                error: function() {
                    alert('حدث خطأ أثناء جلب بيانات الكفيل');
                }
            });
        });
    });

    // Show media
    $(document).on("click", ".viewMedia", function() {
        let borrowerId = $(this).data("id");
        loadMedia(borrowerId);
        $("#mediaModal").modal("show");
    });

    // Show review modal
    $(document).on("click", ".borrowerReview", function() {
        let borrowerId = $(this).data("id");
        let review = $(this).data("review");
        $("#borrower_idReview").val(borrowerId);
        $("#review").val(review);
        $("#borrowerReviewModal").modal("show");
    });

    function loadMedia(borrowerId) {
        let basePath = "<?php echo e(asset('')); ?>";
        $.ajax({
            url: `/admin/borrowers/${borrowerId}/media`,
            type: "GET",
            success: function(response) {
                $("#borrowerMedia").empty();
                $("#guarantorMedia").empty();
                let borrowerHtml = "";
                let guarantorHtml = "";

                response.media.forEach((media) => {
                    let imageUrl = basePath + media.path;
                    let mediaHtml = `
                            <div class="col-lg-4 col-md-6 col-12 mb-3">
                                <img src="${imageUrl}"  class="img-fluid img-thumbnail gallery-image"
                                     onclick="openZoomModal('${imageUrl}')"
                                     style="height: 150px; width: 100%; object-fit: cover;">
                            </div>
                        `;
                    if (media.type == 1) {
                        guarantorHtml += mediaHtml;
                    } else {
                        borrowerHtml += mediaHtml;
                    }
                });

                $("#borrowerMedia").html(borrowerHtml);
                $("#guarantorMedia").html(guarantorHtml);
            },
            error: function() {
                toastr.error("فشل في تحميل الصور");
            },
        });
    }

    // Image zoom functionality
    function openZoomModal(imageSrc) {
        document.getElementById('zoomedImage').src = imageSrc;
        document.getElementById('imageZoomModal').style.display = "block";
    }

    function closeZoomModal() {
        document.getElementById('imageZoomModal').style.display = "none";
    }

    window.onclick = function(event) {
        const modal = document.getElementById('imageZoomModal');
        if (event.target == modal) {
            closeZoomModal();
        }
    }
</script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('Admin/layouts/master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\new-zakat\resources\views/admin/borrowers/index.blade.php ENDPATH**/ ?>