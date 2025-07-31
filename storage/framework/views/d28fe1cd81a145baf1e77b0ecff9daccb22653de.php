<?php $__env->startSection('title'); ?>
    <?php echo e(isset($setting) ? $setting->title : ''); ?>

    | القروض الشخصية
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page_name'); ?>
    القروض الشخصية
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <!-- Modal -->
        <div class="modal fade" id="payModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <form id="payForm">
                    <?php echo csrf_field(); ?>
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">دفع القرض</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <div class="modal-body">
                            <input type="hidden" name="loan_id" id="modal-loan-id">

                            <div class="form-group">
                                <label>قيمة الدفع</label>
                                <input type="number" name="amount" id="pay-amount" class="form-control" required>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">تأكيد الدفع</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-12 col-lg-12">
            <div class="card-body w-100">
                <div class="row w-100"> <!-- Ensuring full width -->
                    <div class="col-12"> <!-- Making it take full width -->
                        <div class="card bg-secondary img-card box-secondary-shadow">
                            <div class="d-flex justify-content-between pr-3 pl-3 pt-3 w-100">
                                <span class="text-white fs-30">قيمه القرض </span>
                                <span class="text-white fs-30"> <?php echo e($total); ?> EGP</span>
                                <!-- Changed dollar icon to EGP -->
                            </div>
                            <div class="d-flex justify-content-between pr-3 pl-3 pt-3 w-100">
                                <span class="text-white fs-30"> المدفوع </span>
                                <span class="text-white fs-30"> <?php echo e($totalIn); ?> EGP <i class='fas fa-arrow-down'
                                        style='color: #63E6BE; font-size: 30px ;transform: rotate(45deg); margin-right: 20px;'></i></span>
                                <!-- Changed dollar icon to EGP -->
                            </div>
                            <div class="d-flex justify-content-between pr-3 pl-3 pt-3 w-100">
                                <span class="text-white fs-30"> المتبقي </span>
                                <span class="text-white fs-30"> <?php echo e($totalOut); ?> EGP <i class='fas fa-arrow-up'
                                        style='color: #e42f2f; font-size: 30px ; transform: rotate(45deg);margin-right: 20px;'></i></span>
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
            <div class="col-md-12 col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"> القروض الشخصية </h3>
                        <?php if($pay == 1): ?>
                            <button class="btn btn-success btn-icon text-white loan-btn" data-id="<?php echo e($id); ?>"> صرف
                                القرض
                            </button>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered text-nowrap w-75" id="dataTable">
                                <thead>
                                    <tr class="fw-bolder text-muted bg-light">
                                        <th>#</th>
                                        <th>اسم المقترض</th>
                                        <th>رقم الهاتف</th>
                                        <th>قيمة القسط</th>
                                        <th>تاريخ القرض</th>
                                        <th>الحالة</th>
                                        <th>العمليات</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php echo $__env->make('admin.layouts.myAjaxHelper', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php $__env->stopSection(); ?>

    <?php $__env->startSection('ajaxCalls'); ?>
        <script>
            $(document).ready(function() {
                var borrowerId = "<?php echo e($id); ?>";

                var table = $('#dataTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "<?php echo e(route('person.loans', ':id')); ?>".replace(':id', borrowerId),
                    columns: [{
                            data: null,
                            name: 'index',
                            render: function(data, type, row, meta) {
                                return meta.row + 1;
                            },
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'borrower_name',
                            name: 'borrower_name'
                        },
                        {
                            data: 'borrower_phone',
                            name: 'borrower_phone'
                        },
                        {
                            data: 'amount',
                            name: 'amount'
                        },
                        {
                            data: 'month',
                            name: 'month'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ]
                });

                $(document).on('click', '.loan-btn', function() {
                    let loanId = $(this).data('id');

                    if (confirm("هل أنت متأكد من صرف هذا القرض؟")) {
                        $.ajax({
                            url: "<?php echo e(route('loan.checkout', ':id')); ?>".replace(':id', loanId),
                            type: 'get',
                            data: {
                                _token: '<?php echo e(csrf_token()); ?>'
                            },
                            success: function(response) {
                                window.location.reload();
                                alert(response.message);
                                table.ajax.reload();
                            },
                            error: function(response) {
                                alert(response.message);
                                alert(response.responseJSON.error);
                            }
                        });
                    }
                });

                
                
                

                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                

            });
        </script>

        <script>
            $(document).on('click', '.pay-btn', function() {
                let loanId = $(this).data('id');
                let payStatus = $(this).data('status');
                let amount = $(this).data('amount');

                if (payStatus == 0) {
                    $('#modal-loan-id').val(loanId);
                    $('#pay-amount').val(amount);
                    $('#payModal').modal('show');
                }
            });

            $('#payForm').submit(function(e) {
                e.preventDefault();

                let loanId = $('#modal-loan-id').val();
                let amount = $('#pay-amount').val();

                $.ajax({
                    url: "<?php echo e(route('loan.pay', ':id')); ?>".replace(':id', loanId),
                    type: 'POST',
                    data: {
                        _token: '<?php echo e(csrf_token()); ?>',
                        amount: amount
                    },
                    success: function(response) {
                        $('#payModal').modal('hide');
                        alert(response.message);
                        window.location.reload();
                    },
                    error: function(response) {
                        alert(response.responseJSON.error || "حدث خطأ أثناء الدفع");
                    }
                });
            });
        </script>
    <?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/kariem/Desktop/Projects/new-zakat/resources/views/admin/loans/indexloan.blade.php ENDPATH**/ ?>