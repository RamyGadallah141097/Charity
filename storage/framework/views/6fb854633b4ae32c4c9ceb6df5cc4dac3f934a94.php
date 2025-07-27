<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <?php echo $__env->make('Admin/layouts/head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</head>

<body class="app sidebar-mini">

<!-- Start Switcher -->

<!-- End Switcher -->

<!-- GLOBAL-LOADER -->
<?php echo $__env->make('Admin/layouts/loader', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<!-- /GLOBAL-LOADER -->

<!-- PAGE -->
<div class="page">
    <div class="page-main">
        <!--APP-SIDEBAR-->
    <?php echo $__env->make('Admin/layouts/main-sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!--/APP-SIDEBAR-->

        <!-- Header -->
    <?php echo $__env->make('Admin/layouts/main-header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- Header -->
        <!--Content-area open-->
        <div class="app-content">
            <div class="side-app">

                <!-- PAGE-HEADER -->
                <div class="page-header">
                    <div>
                        <h1 class="page-title">مرحبا بـك ! <i class="fas fa-heart text-danger"></i></h1>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('adminHome')); ?>">لوحة التحكم</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo $__env->yieldContent('page_name'); ?></li>
                        </ol>
                    </div>
                </div>
                <!-- PAGE-HEADER END -->
                <?php echo $__env->yieldContent('content'); ?>
            </div>
            <!-- End Page -->
        </div>
        <!-- CONTAINER END -->
    </div>
    <!-- SIDE-BAR -->

    <!-- FOOTER -->

<?php echo $__env->make('Admin/layouts/footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<!-- FOOTER END -->
</div>
<!-- BACK-TO-TOP -->
<a href="#top" id="back-to-top"><i class="fa fa-angle-up mt-4"></i></a>

<?php echo $__env->make('Admin/layouts/scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->yieldContent('ajaxCalls'); ?>
<?php echo toastr_js(); ?>
<?php echo app('toastr')->render(); ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\new-zakat\resources\views/Admin/layouts/master.blade.php ENDPATH**/ ?>