<!-- main-sidebar -->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar sidebar-scroll">
    <div class="main-sidebar-header active">
        <a class="desktop-logo logo-light active" href="<?php echo e(route('dashboard.pharmacy_manager')); ?>"><img
                src="<?php echo e(URL::asset('Dashboard/img/brand/logo.png')); ?>" class="main-logo" alt="logo"></a>
        <a class="desktop-logo logo-dark active" href="<?php echo e(route('dashboard.pharmacy_manager')); ?>"><img
                src="<?php echo e(URL::asset('Dashboard/img/brand/logo-white.png')); ?>" class="main-logo dark-theme"
                alt="logo"></a>
        <a class="logo-icon mobile-logo icon-light active" href="<?php echo e(route('dashboard.pharmacy_manager')); ?>"><img
                src="<?php echo e(URL::asset('Dashboard/img/brand/favicon.png')); ?>" class="logo-icon" alt="logo"></a>
        <a class="logo-icon mobile-logo icon-dark active" href="<?php echo e(route('dashboard.pharmacy_manager')); ?>"><img
                src="<?php echo e(URL::asset('Dashboard/img/brand/favicon-white.png')); ?>" class="logo-icon dark-theme"
                alt="logo"></a>
    </div>
    <div class="main-sidemenu">
        <div class="app-sidebar__user clearfix">
            <div class="dropdown user-pro-body">
                <div class="text-center">
                    <?php
                        $pharmacy_managerImage = Auth::guard('pharmacy_manager')->user()->image;
                        $imagePath =
                            $pharmacy_managerImage && $pharmacy_managerImage->filename
                                ? asset('Dashboard/img/pharmacy_managers/' . $pharmacy_managerImage->filename)
                                : asset('Dashboard/img/default_pharmacy_manager_avatar.png');
                        if (
                            $pharmacy_managerImage &&
                            $pharmacy_managerImage->filename &&
                            !file_exists(
                                public_path('Dashboard/img/pharmacy_managers/' . $pharmacy_managerImage->filename),
                            )
                        ) {
                            $imagePath = asset('Dashboard/img/default_pharmacy_manager_avatar.png');
                        }
                    ?>
                    <img alt="user-img" class="avatar avatar-xl rounded-circle user-avatar" src="<?php echo e($imagePath); ?>">
                </div>
                <div class="user-info text-center mt-2">
                    <h4 class="font-weight-semibold mb-0"><?php echo e(Auth::guard('pharmacy_manager')->user()->name); ?></h4>
                    <span class="mb-0 text-muted small"><?php echo e(Auth::guard('pharmacy_manager')->user()->email); ?></span>
                </div>
            </div>
        </div>
        <ul class="side-menu">
            <li class="side-item side-item-category"><?php echo e(trans('main-sidebar_trans.Main')); ?></li>

            <li class="slide <?php echo e(request()->routeIs('dashboard.pharmacy_manager') ? 'active' : ''); ?>">
                <a class="side-menu__item" href="<?php echo e(route('dashboard.pharmacy_manager')); ?>">
                    <i class="side-menu__icon typcn typcn-home-outline"></i>
                    <span class="side-menu__label"><?php echo e(trans('main-sidebar_trans.index')); ?></span>
                </a>
            </li>

            
            <li
                class="slide <?php echo e(request()->routeIs('pharmacy_manager.profile.show') || request()->routeIs('pharmacy_manager.profile.edit') ? 'is-expanded active' : ''); ?>">
                <a class="side-menu__item" data-toggle="slide" href="javascript:void(0);">
                    <i class="side-menu__icon fas fa-user-circle"></i>
                    <span class="side-menu__label"><?php echo e(trans('phrM.profile')); ?></span>
                    <i class="angle fe fe-chevron-down"></i>
                </a>
                <ul class="slide-menu">
                    <li class="<?php echo e(request()->routeIs('pharmacy_manager.profile.show') ? 'active' : ''); ?>">
                        <a class="slide-item" href="<?php echo e(route('pharmacy_manager.profile.show')); ?>">
                            <i class="fas fa-eye me-2"></i> <?php echo e(trans('phrM.view_profile')); ?>

                        </a>
                    </li>
                    <li class="<?php echo e(request()->routeIs('pharmacy_manager.profile.edit') ? 'active' : ''); ?>">
                        <a class="slide-item" href="<?php echo e(route('pharmacy_manager.profile.edit')); ?>">
                            <i class="fas fa-edit me-2"></i> <?php echo e(trans('phrM.edit_profile_menu')); ?>

                        </a>
                    </li>
                </ul>
            </li>


            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="#">
                    <i class="side-menu__icon fas fa-pills"></i>
                    <span class="side-menu__label"><?php echo e(trans('phrM.pharmacy')); ?></span>
                    <i class="angle fe fe-chevron-down"></i>
                </a>
                <ul class="slide-menu">
                    <li>
                        <a class="slide-item" href="<?php echo e(route('pharmacy_manager.medications.index')); ?>">
                            <i class="fas fa-tablets me-2"></i> <?php echo e(trans('phrM.manage_medications')); ?>

                        </a>
                    </li>
                </ul>
            </li>

            
            <li class="slide">
                <a class="side-menu__item" href="javascript:void(0);"
                    onclick="event.preventDefault(); document.getElementById('logout-form-pharmacy_manager').submit();">
                    <i class="side-menu__icon typcn typcn-export-outline"></i>
                    <span class="side-menu__label"><?php echo e(trans('phrM.logout')); ?></span>
                </a>
                <form id="logout-form-pharmacy_manager" action="<?php echo e(route('logout.pharmacy_manager')); ?>" method="POST"
                    style="display: none;">
                    <?php echo csrf_field(); ?>
                </form>
            </li>

        </ul>
    </div>
</aside>


<?php $__env->startSection('js'); ?>
    <!--Internal  Chart.bundle js -->
    <script src="<?php echo e(URL::asset('Dashboard/plugins/chart.js/Chart.bundle.min.js')); ?>"></script>
    <!-- Moment js -->
    <script src="<?php echo e(URL::asset('Dashboard/plugins/raphael/raphael.min.js')); ?>"></script>
    <!--Internal  Flot js-->
    
    
    
    
    
    
    <!--Internal Apexchart js-->
    <script src="<?php echo e(URL::asset('Dashboard/js/apexcharts.js')); ?>"></script>
    <!-- Internal Map -->
    
    
    
    <!--Internal  index js -->
    <script src="<?php echo e(URL::asset('Dashboard/js/index.js')); ?>"></script> 
    

    <script>
        // يمكنك إضافة أي كود JavaScript خاص بهذه الصفحة هنا إذا احتجت
        // مثلاً، لتحديث بيانات العرض البياني إذا كانت ديناميكية
        $(function() {
            'use strict';
            // أي تهيئة خاصة بـ Charts أو Maps إذا أردت تفعيلها
        });
    </script>
<?php $__env->stopSection(); ?>
<?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/layouts/main-sidebar/pharmacy_manager-sidebar-main.blade.php ENDPATH**/ ?>