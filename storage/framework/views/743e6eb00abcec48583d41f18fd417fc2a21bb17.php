<?php $__env->startSection('css'); ?>
    <?php echo $__env->make('Style.Style', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<!-- Delete Doctor Modal - Enhanced Version -->
<div class="modal fade" id="delete<?php echo e($doctor->id); ?>" tabindex="-1" aria-labelledby="deleteDoctorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <!-- Modal Header -->
            <div class="modal-header bg-gradient-danger text-white">
                <div class="d-flex align-items-center">
                    <i class="fas fa-user-md fa-2x mr-3"></i>
                    <div>
                        <h5 class="modal-title font-weight-bold" id="deleteDoctorModalLabel"><?php echo e(trans('doctors.delete_doctor')); ?></h5>
                        <p class="mb-0 small"><?php echo e(trans('sections_trans.Warning')); ?></p>
                    </div>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body py-4">
                <form action="<?php echo e(route('admin.Doctors.destroy', 'test')); ?>" method="post" id="deleteDoctorForm<?php echo e($doctor->id); ?>">
                    <?php echo method_field('DELETE'); ?>
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="page_id" value="1">
                    <?php if($doctor->image): ?>
                        <input type="hidden" name="filename" value="<?php echo e($doctor->image->filename); ?>">
                    <?php endif; ?>
                    <input type="hidden" name="id" value="<?php echo e($doctor->id); ?>">

                    <div class="text-center mb-4">
                        <div class="delete-icon-container">
                            <i class="fas fa-user-md text-danger"></i>
                        </div>
                        <h4 class="text-danger font-weight-bold mt-3"><?php echo e(trans('sections_trans.warning')); ?> !!</h4>
                        <p class="text-muted"><?php echo e(trans('doctors.delete_doctor_warning')); ?> !!</p>
                    </div>

                    <div class="alert alert-light border-danger border text-center py-3">
                        <h5 class="font-weight-bold mb-1"><?php echo e($doctor->name); ?></h5>
                        <div class="d-flex justify-content-center align-items-center">
                            <?php if($doctor->image): ?>
                                <img src="<?php echo e(asset('Dashboard/img/doctors/'.$doctor->image->filename)); ?>"
                                     class="rounded-circle mr-2" width="40" height="40" alt="<?php echo e($doctor->name); ?>">
                            <?php else: ?>
                                <img src="<?php echo e(asset('Dashboard/img/doctor_default.png')); ?>"
                                     class="rounded-circle mr-2" width="40" height="40" alt="صورة افتراضية">
                            <?php endif; ?>
                            <div>
                                <p class="text-muted small mb-0"><?php echo e(trans('doctors.Doctor_id')); ?>: #<?php echo e($doctor->id); ?></p>
                                <p class="text-muted small mb-0"><?php echo e(trans('doctors.section')); ?>: <?php echo e($doctor->section->name); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="custom-control custom-checkbox my-4 text-center">
                        <input type="checkbox" class="custom-control-input" id="confirmDoctorCheck<?php echo e($doctor->id); ?>" required>
                        <label class="custom-control-label text-danger" for="confirmDoctorCheck<?php echo e($doctor->id); ?>">
                            <?php echo e(trans('sections_trans.I agree that this procedure cannot be undone')); ?>

                        </label>
                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light bg-gradient-light" data-dismiss="modal">
                            <i class="fas fa-times-circle mr-2"></i> <?php echo e(trans('Dashboard/sections_trans.Close')); ?>

                        </button>
                        <button type="submit" id="deleteDoctorBtn<?php echo e($doctor->id); ?>"
                                class="btn btn-danger bg-gradient-danger">
                            <i class="fas fa-trash-alt mr-2"></i> <?php echo e(trans('Dashboard/sections_trans.submit')); ?>

                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<?php $__env->startSection('js'); ?>
    <?php echo $__env->make('Script.Script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>


<?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/Doctors/delete.blade.php ENDPATH**/ ?>