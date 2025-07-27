<?php $__env->startSection('title'); ?>
<?php echo e(isset($setting) ? $setting->title : ''); ?>

| التبرعات
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page_name'); ?>
التبرعات
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-12 col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"> قائمة تبرعات الصدقات والزكاة </h3>
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
                                <th class="min-w-50px"> الاسم المتبرع</th>
                                <th class="min-w-125px"> الهاتف المتبرع</th>
                                <th class="min-w-125px">تاريخ التبرع</th>
                                <th class="min-w-125px">نوع التبرع </th>
                                <th class="min-w-125px">قيمه التبرع </th>

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
                <form action="<?php echo e(route('donations_delete')); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('post'); ?>
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
            data: 'donor_name',
            name: 'donor_name'
        },
        {
            data: 'donor_phone',
            name: 'donor_phone'
        },
        {
            data: 'created_at',
            name: 'created_at'
        },
        {
            data: 'donation_type',
            name: 'donation_type'
        },
        {
            data: 'donation_amount',
            name: 'donation_amount'
        },
        // {
        //     data: 'action',
        //     name: 'action',
        //     orderable: false,
        //     searchable: false
        // },
    ]
    showData('<?php echo e(route('Donations.index')); ?>', columns);
    deleteScript('<?php echo e(route('donations_delete')); ?>');
    showAddModal('<?php echo e(route('Donations.create')); ?>');
    addScript();
    showEditModal('<?php echo e(route('Donations.edit', ':id')); ?>');
    editScript();
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin/layouts/master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\new-zakat\resources\views/admin/donations/index.blade.php ENDPATH**/ ?>