<?php $__env->startSection('css'); ?>
    <?php echo $__env->make('Style.Style', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <!-- Delete Patient Modal - Simplified Version -->
    <div class="modal fade" id="delete<?php echo e($service->id); ?>" tabindex="-1" aria-labelledby="deletePatientModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <!-- Modal Header -->
                <div class="modal-header bg-gradient-danger text-white">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle fa-2x mr-3"></i>
                        <div>
                            <h5 class="modal-title font-weight-bold" id="deletePatientModalLabel">
                                <?php echo e(trans('AdminSb.confirm_service_deletion_title')); ?></h5>
                            <p class="mb-0 small"><?php echo e(trans('AdminSb.All associated data will be deleted')); ?></p>
                        </div>
                    </div>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body py-4">
                    <form action="<?php echo e(route('admin.Service.destroy', $service->id)); ?>" method="post"
                        id="deleteForm<?php echo e($service->id); ?>">
                        <?php echo method_field('DELETE'); ?>
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="id" value="<?php echo e($service->id); ?>">

                        <div class="text-center mb-4">
                            <div class="delete-icon-container">
                                <i class="fas fa-user-slash text-danger"></i>
                            </div>
                            <h4 class="text-danger font-weight-bold mt-3"><?php echo e(trans('AdminSb.warning')); ?></h4>
                            <p class="text-muted"><?php echo e(trans('AdminSb.You are about to delete the following record:')); ?>

                            </p>
                        </div>

                        <div class="alert alert-light border-danger border text-center py-3">
                            <h5 class="font-weight-bold mb-1"><?php echo e($service->name); ?></h5>
                            <p class="text-muted small mb-0"><?php echo e(trans('AdminSb.service_id_colon')); ?>

                                #<?php echo e($service->id); ?></p>
                        </div>

                        <div class="custom-control custom-checkbox my-4 text-center">
                            <input type="checkbox" class="custom-control-input" id="confirmCheck<?php echo e($service->id); ?>"
                                required>
                            <label class="custom-control-label text-danger" for="confirmCheck<?php echo e($service->id); ?>">
                                <?php echo e(trans('AdminSb.I realize that this action cannot be undone')); ?>

                            </label>
                        </div>

                        <!-- Modal Footer -->
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-light bg-gradient-light" data-dismiss="modal">
                                <i class="fas fa-times-circle mr-2"></i> <?php echo e(trans('AdminSb.cancel')); ?>

                            </button>
                            <button type="submit" id="deleteBtn<?php echo e($service->id); ?>"
                                class="btn btn-danger bg-gradient-danger">
                                <i class="fas fa-trash-alt mr-2"></i> <?php echo e(trans('AdminSb.confirm_deletion')); ?>

                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php $__env->startSection('js'); ?>
    <!-- 5. ملفات JS المخصصة (إذا وجدت) -->
    <?php echo $__env->make('Script.Script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/Services/Single Service/delete.blade.php ENDPATH**/ ?>