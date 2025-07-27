<?php $__env->startSection('title'); ?>
<?php echo e(isset($setting) ? $setting->title : ''); ?>

| المتبرعين
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page_name'); ?> المتبرعين <?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

<div class="row">
    <div class="col-md-12 col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"> المتبرعين الي <?php echo e(isset($setting) ? $setting->title : ''); ?>

                </h3>
                <div class="">
                    <button class="btn btn-secondary btn-icon text-white addBtn">
                        <span>
                            <i class="fe fe-plus"></i>
                        </span> اضافة جديد
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <!--begin::Table-->
                    <table class="table table-striped table-bordered text-nowrap w-75" id="dataTable">
                        <thead>
                            <tr class="fw-bolder text-muted bg-light">
                                <th class="min-w-25px">#</th>
                                <th class="min-w-50px">الاسم</th>
                                <th class="min-w-125px">الهاتف</th>
                                <th class="min-w-125px">العنوان</th>
                                <th class="min-w-125px">تاريخ الميلاد</th>
                                <th class="min-w-125px">ملاحظات</th>
                                <th class="min-w-125px">وقت التسجيل</th>
                                <th class="min-w-50px rounded-end">العمليات</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!--Delete MODAL -->
    <div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">حذف بيانات</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="<?php echo e(route("delete_donors")); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field("post"); ?>
                    <div class="modal-body">
                        <input id="delete_id" name="id" type="hidden">
                        <p>هل انت متأكد من حذف البيانات التالية <span id="title" class="text-danger"></span>؟</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal" id="dismiss_delete_modal">
                            اغلاق
                        </button>
                        <button type="submit" class="btn btn-danger" id="delete_btn">حذف !</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- MODAL CLOSED -->


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
                <div class="modal-body" id="modal-body">

                </div>
            </div>
        </div>
    </div>
    <!-- Create Or Edit Modal -->


    <!-- donationReturnModal Modal -->
    <div class="modal fade" id="donationReturnModal" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        ارجاع القرض للمتبرع
                        <input type="text" disabled id="name">
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="donationReturnForm" action="<?php echo e(route("donor.returnDonationMoney")); ?>">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="donor_id" id="donor_id">
                        <div class="form-group">




                            <label>القيمه المسترده</label>
                            <input type="number" class="form-control" placeholder="القيمه المسترده" name="DonationReturnAmount" id="DonationReturnAmount" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" form="donationReturnForm" class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- donationReturnModal Modal -->
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
            data: 'address',
            name: 'address'
        },
        {
            data: 'burn_date',
            name: 'burn_date'
        },
        {
            data: 'notes',
            name: 'notes'
        },
        {
            data: 'created_at',
            name: 'created_at'
        },
        {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: false
        },
    ]
    showData('<?php echo e(route('donors.index')); ?>', columns);
    // Delete Using Ajax

    deleteScript('<?php echo e(route('delete_donors')); ?>');

    // Add Using Ajax
    showAddModal('<?php echo e(route('donors.create')); ?>');
    addScript();
    // Add Using Ajax
    showEditModal('<?php echo e(route('donors.edit', ':id')); ?>');
    editScript();
</script>


<script>
    $(document).on("click", ".donationReturnBtn", function() {
        let donorId = $(this).data("id");
        let name = $(this).data("title");
        let avalable = $(this).data("avalable");
        let amount = $(this).data("amount");

        console.log(amount);

        $("#donor_id").val(donorId);
        $("#avalable").val(avalable);
        $("#DonationReturnAmount").val(amount);
        $("#name").val(name);
        $("#donationReturnModal").modal("show");
    });
</script>

<script>
    $(document).ready(function() {
        // تأكيد ضبط التوكن بعد تحميل الصفحة
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on('submit', '#donationReturnForm', function(e) {
            e.preventDefault();

            let form = $(this);
            let url = form.attr('action');
            let formData = form.serialize();

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.status === 'success') {
                        toastr.success(response.message || 'تم إرجاع المبلغ بنجاح');
                        $('#donationReturnModal').modal('hide');
                        location.reload();
                        form.trigger("reset");
                    } else {
                        toastr.warning(response.message || 'حدث شيء غير متوقع');
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText); // لفحص الخطأ
                    let errors = xhr.responseJSON?.errors;
                    if (errors) {
                        $.each(errors, function(key, error) {
                            toastr.error(error);
                        });
                    } else {
                        toastr.error("فشل في الإرسال، حاول مرة أخرى.");
                    }
                }
            });
        });
    });
</script>



<?php $__env->stopSection(); ?>



<?php echo $__env->make('admin/layouts/master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\new-zakat\resources\views/admin/donors/index.blade.php ENDPATH**/ ?>