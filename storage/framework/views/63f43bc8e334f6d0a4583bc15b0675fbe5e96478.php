<!-- main-sidebar -->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar sidebar-scroll">
    <div class="main-sidebar-header active">
        <a class="desktop-logo logo-light active" href="<?php echo e(url('/' . ($page = 'index'))); ?>"><img
                src="<?php echo e(URL::asset('Dashboard/img/brand/logo.png')); ?>" class="main-logo" alt="logo"></a>
        <a class="desktop-logo logo-dark active" href="<?php echo e(url('/' . ($page = 'index'))); ?>"><img
                src="<?php echo e(URL::asset('Dashboard/img/brand/logo-white.png')); ?>" class="main-logo dark-theme"
                alt="logo"></a>
        <a class="logo-icon mobile-logo icon-light active" href="<?php echo e(url('/' . ($page = 'index'))); ?>"><img
                src="<?php echo e(URL::asset('Dashboard/img/brand/favicon.png')); ?>" class="logo-icon" alt="logo"></a>
        <a class="logo-icon mobile-logo icon-dark active" href="<?php echo e(url('/' . ($page = 'index'))); ?>"><img
                src="<?php echo e(URL::asset('Dashboard/img/brand/favicon-white.png')); ?>" class="logo-icon dark-theme"
                alt="logo"></a>
    </div>

    <?php if(\Auth::guard('admin')->check()): ?>
        <?php echo $__env->make('Dashboard.layouts.main-sidebar.admin-sidebar-main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>

    <?php if(\Auth::guard('doctor')->check()): ?>
        <?php echo $__env->make('Dashboard.layouts.main-sidebar.doctor-sidebar-main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>

    <?php if(\Auth::guard('ray_employee')->check()): ?>
        <?php echo $__env->make('Dashboard.layouts.main-sidebar.ray_employee-sidebar-main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>

    <?php if(\Auth::guard('laboratorie_employee')->check()): ?>
        <?php echo $__env->make('Dashboard.layouts.main-sidebar.laboratorie_employee-sidebar-main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
    <?php if(\Auth::guard('patient')->check()): ?>
        <?php echo $__env->make('Dashboard.layouts.main-sidebar.patient-sidebar-main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>

    <?php if(\Auth::guard('pharmacy_employee')->check()): ?>
        <?php echo $__env->make('Dashboard.layouts.main-sidebar.pharmacy_employee-sidebar-main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>


    <?php if(\Auth::guard('pharmacy_manager')->check()): ?>
        <?php echo $__env->make('Dashboard.layouts.main-sidebar.pharmacy_manager-sidebar-main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>
</aside>
<!-- main-sidebar -->
<?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/layouts/main-sidebar.blade.php ENDPATH**/ ?>