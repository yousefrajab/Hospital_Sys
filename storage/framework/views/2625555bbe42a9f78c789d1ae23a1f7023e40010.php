 

<?php $__env->startSection('title', 'إضافة تعليق جديد'); ?>


<?php $__env->startSection('page-header'); ?>
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        
    </div>
    <!-- breadcrumb -->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="row">
                        <div class="col">
                            <h4 class="page-title"><?php echo e(trans('patients.add_your_testimonial')); ?></h4>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a
                                        href="<?php echo e(route('dashboard.patient')); ?>"><?php echo e(trans('patients.dashboard')); ?></a></li>
                                <li class="breadcrumb-item active"><?php echo e(trans('patients.add_comment_breadcrumb')); ?></li>
                            </ol>
                        </div>
                    </div><!--end row-->
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->
        <!-- end page title end breadcrumb -->

        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"><?php echo e(trans('patients.share_your_opinion')); ?></h4>
                        <p class="text-muted mb-0"><?php echo e(trans('patients.we_value_your_feedback')); ?></p>
                    </div><!--end card-header-->
                    <div class="card-body">
                        <?php if(session('success')): ?>
                            <div class="alert alert-success">
                                <?php echo e(session('success')); ?>

                            </div>
                        <?php endif; ?>
                        <?php if(session('warning')): ?>
                            <div class="alert alert-warning">
                                <?php echo e(session('warning')); ?>

                            </div>
                        <?php endif; ?>

                        <form method="POST" action="<?php echo e(route('patient.testimonials.store')); ?>">
                            <?php echo csrf_field(); ?>

                            <div class="form-group mb-3">
                                <label for="patient_name"
                                    class="form-label"><?php echo e(trans('patients.your_name_in_comment')); ?></label>
                                <input type="text" class="form-control" id="patient_name"
                                    value="<?php echo e(Auth::guard('patient')->user()->name); ?>" readonly>
                                <small
                                    class="form-text text-muted"><?php echo e(trans('patients.your_current_name_will_be_used')); ?></small>
                            </div>

                            <div class="form-group mb-3">
                                <label for="comment" class="form-label"><?php echo e(trans('patients.your_comment')); ?> <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control <?php $__errorArgs = ['comment'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="comment" name="comment" rows="5"
                                    placeholder="<?php echo e(trans('patients.write_your_comment_here')); ?>" required><?php echo e(old('comment')); ?></textarea>
                                <?php $__errorArgs = ['comment'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="invalid-feedback">
                                        <?php echo e($message); ?>

                                    </div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="form-group mt-4 text-center">
                                <button type="submit"
                                    class="btn btn-primary"><?php echo e(trans('patients.submit_comment_for_review')); ?></button>
                                <a href="<?php echo e(route('dashboard.patient')); ?>"
                                    class="btn btn-outline-secondary ms-2"><?php echo e(trans('doctors.cancel')); ?></a>
                            </div>
                        </form>
                    </div><!--end card-body-->
                </div><!--end card-->
            </div><!--end col-->
        </div><!--end row-->
    </div><!-- container -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Dashboard.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/dashboard_patient/testimonials/create.blade.php ENDPATH**/ ?>