<!-- Modal -->


<div class="modal fade" id="update_password<?php echo e($doctor->id); ?>" tabindex="-1" role="dialog"
    aria-labelledby="updatePasswordLabel<?php echo e($doctor->id); ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-gradient-primary text-white">
                <h5 class="modal-title" id="updatePasswordLabel<?php echo e($doctor->id); ?>">
                    <i class="fas fa-key mr-2"></i><?php echo e(trans('doctors.update_password')); ?> - <?php echo e($doctor->name); ?>

                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="<?php echo e(route('admin.update_password')); ?>" method="post" autocomplete="off" class="ajax-form">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="form-group position-relative">
                        <label class="font-weight-bold"><?php echo e(trans('doctors.new_password')); ?></label>
                        <input type="password" name="password" class="form-control rounded-lg shadow-sm pr-5" required
                            id="new-password-<?php echo e($doctor->id); ?>">
                        <button type="button" class="toggle-password-eye"
                            onclick="togglePassword('new-password-<?php echo e($doctor->id); ?>', this)"
                            style="position:absolute; top:38px; right:15px; background:none; border:none;">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>

                    <div class="form-group position-relative">
                        <label class="font-weight-bold"><?php echo e(trans('doctors.confirm_password')); ?></label>
                        <input type="password" name="password_confirmation"
                            class="form-control rounded-lg shadow-sm pr-5" required
                            id="confirm-password-<?php echo e($doctor->id); ?>">
                        <button type="button" class="toggle-password-eye"
                            onclick="togglePassword('confirm-password-<?php echo e($doctor->id); ?>', this)"
                            style="position:absolute; top:38px; right:15px; background:none; border:none;">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>

                    <input type="hidden" name="id" value="<?php echo e($doctor->id); ?>">
                </div>

                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-dismiss="modal">
                        <?php echo e(trans('Dashboard/sections_trans.Close')); ?>

                    </button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="fas fa-save mr-2"></i><?php echo e(trans('Dashboard/sections_trans.submit')); ?>

                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php $__env->startSection('js'); ?>
    <?php echo $__env->make('Script.Script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/Doctors/update_password.blade.php ENDPATH**/ ?>