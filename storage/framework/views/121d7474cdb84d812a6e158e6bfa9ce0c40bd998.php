<?php $__env->startSection('title'); ?>
    <?php echo e(isset($setting) ? $setting->title : ''); ?>

    | مصروفات المشرفين
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page_name'); ?>
    مصروفات المشرفين
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">

        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body w-100">
                    <div class="row w-100"> <!-- Ensuring full width -->
                        <div class="col-12"> <!-- Making it take full width -->
                            <div class="card bg-secondary img-card box-secondary-shadow">
                                <div class="d-flex justify-content-between pr-3 pl-3 pt-3 w-100">
                                    <span class="text-white fs-30"> مصروفات المشرفين </span>
                                    <span class="text-white fs-30"> <?php echo e($total); ?> EGP</span>
                                    <!-- Changed dollar icon to EGP -->
                                </div>
                                <div class="card-body">
                                    <div class="row text-white">
                                        <div class="col-4 text-end"> <!-- Added text-end for right alignment -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- COL END -->
                    </div><!-- ROW END -->
                </div>
                <div class="card-header">
                    <h3 class="card-title"> مصروفات المشرفين <?php echo e(isset($setting) ? $setting->title : ''); ?>

                    </h3>
                    <div class="">
                        <button class="btn btn-secondary btn-icon text-white addBtn">
                            <span>
                                <i class="fe fe-plus"></i>
                            </span> اضافة مصروف
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
                                    <th class="min-w-50px">العنوان</th>
                                    <th class="min-w-125px"> القيمه</th>
                                    <th class="min-w-125px"> التاريخ</th>
                                    
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
                    <form action="<?php echo e(route('assetsDelete')); ?>" method="post">
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
                data: "title",
                name: "title"
            }, // This now returns admin name
            {
                data: "amount",
                name: "amount"
            },
            {
                data: "created_at",
                name: "created_at"
            },
            // {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
        showData('<?php echo e(route('SubscriptionFee.index')); ?>', columns);

        
        showAddModal('<?php echo e(route('SubscriptionFee.create')); ?>');
        addScript();
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin/layouts/master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/kariem/Desktop/Projects/new-zakat/resources/views/admin/Supscriptions/supscriptionFees/index.blade.php ENDPATH**/ ?>