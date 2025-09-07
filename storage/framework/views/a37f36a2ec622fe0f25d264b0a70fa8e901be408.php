<?php $__env->startSection('title'); ?>
    تسجيل دخول مريض جديد
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <!-- Select2 CSS -->
    <link href="<?php echo e(asset('dashboard/assets/plugins/select2/select2.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(asset('dashboard/assets/plugins/select2-bootstrap5-theme/select2-bootstrap-5-theme.min.css')); ?>" rel="stylesheet" type="text/css" />
    <style>
        /* يمكنك إضافة أي CSS مخصص هنا */
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="float-right">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard.admin')); ?>"><?php echo e(trans('AdminSb.dashboard')); ?></a></li>
                            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.patient_admissions.index')); ?>"><?php echo e(trans('AdminSb.patient_admission_records')); ?></a></li>
                            <li class="breadcrumb-item active"><?php echo e(trans('AdminSb.new_admission')); ?></li>
                        </ol>
                    </div>
                    <h4 class="page-title"><?php echo e(trans('AdminSb.admit_new_patient')); ?></h4>
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->
        <!-- end page title end breadcrumb -->

        <?php if($errors->createAdmissionFormBag->any()): ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php $__currentLoopData = $errors->createAdmissionFormBag->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>


        <form action="<?php echo e(route('admin.patient_admissions.store')); ?>" method="POST" id="createAdmissionForm">
            <?php echo csrf_field(); ?>

            <?php echo $__env->make('Dashboard.PatientAdmissions.partials._form', [
                'patients' => $patients,
                'doctors' => $doctors,
                'sections' => $sections,
                'availableBeds' => $availableBeds,
                'admissionStatuses' => $admissionStatuses,
            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <div class="row">
                <div class="col-12 text-center mt-3 mb-3">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> <?php echo e(trans('AdminSb.save_and_admit')); ?></button>
                    <a href="<?php echo e(route('admin.patient_admissions.index')); ?>" class="btn btn-secondary"><i class="fas fa-times me-1"></i> <?php echo e(trans('AdminSb.cancel')); ?></a>
                </div>
            </div>
        </form>

    </div><!-- container -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script src="<?php echo e(asset('dashboard/assets/plugins/select2/select2.full.min.js')); ?>"></script>
    <?php echo $__env->yieldPushContent('js_after_form'); ?>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });

        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Dashboard.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/PatientAdmissions/create.blade.php ENDPATH**/ ?>