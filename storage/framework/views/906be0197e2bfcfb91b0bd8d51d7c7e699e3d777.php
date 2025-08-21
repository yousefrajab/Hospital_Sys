<?php $__env->startSection('css'); ?>
    <?php echo $__env->make('Style.Style', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<!-- Delete Section Modal - Enhanced Style -->
<div class="modal fade" id="delete<?php echo e($section->id); ?>" tabindex="-1" aria-labelledby="deleteSectionModalLabel<?php echo e($section->id); ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <!-- Modal Header -->
            <div class="modal-header bg-gradient-danger text-white">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle fa-2x mr-3"></i>
                    <div>
                        <h5 class="modal-title font-weight-bold" id="deleteSectionModalLabel<?php echo e($section->id); ?>">
                            <?php echo e(trans('Dashboard/sections_trans.delete_sections')); ?>

                        </h5>
                        <p class="mb-0 small"><?php echo e(trans('sections_trans.delete_warning')); ?></p>
                    </div>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body py-4">
                <form action="<?php echo e(route('admin.Sections.destroy', 'test')); ?>" method="post" id="deleteSectionForm<?php echo e($section->id); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <input type="hidden" name="id" value="<?php echo e($section->id); ?>">

                    <div class="text-center mb-4">
                        <div class="delete-icon-container">
                            <i class="fas fa-layer-group text-danger"></i>
                        </div>
                        <h4 class="text-danger font-weight-bold mt-3"><?php echo e(trans('sections_trans.warning')); ?>!</h4>
                        <p class="text-muted"><?php echo e(trans('sections_trans.delete_section_warning')); ?></p>
                    </div>

                    <div class="alert alert-light border-danger border text-center py-3">
                        <h5 class="font-weight-bold mb-1"><?php echo e($section->name); ?></h5>
                        <p class="text-muted small mb-0"><?php echo e(trans('sections_trans.section_id')); ?>: #<?php echo e($section->id); ?></p>
                    </div>

                    <div class="custom-control custom-checkbox my-4 text-center">
                        <input type="checkbox" class="custom-control-input" id="confirmDeleteSection<?php echo e($section->id); ?>" required>
                        <label class="custom-control-label text-danger" for="confirmDeleteSection<?php echo e($section->id); ?>">
                            <?php echo e(trans('sections_trans.I agree that this procedure cannot be undone')); ?>

                        </label>
                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light bg-gradient-light" data-dismiss="modal">
                            <i class="fas fa-times-circle mr-2"></i> <?php echo e(trans('sections_trans.Close')); ?></button>
                        <button type="submit" id="deleteSectionBtn<?php echo e($section->id); ?>" class="btn btn-danger bg-gradient-danger">
                            <i class="fas fa-trash-alt mr-2"></i><?php echo e(trans('sections_trans.submit')); ?>

                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<?php $__env->startSection('js'); ?>
<script src="<?php echo e(URL::asset('dashboard/plugins/notify/js/notifIt.js')); ?>"></script>
<script src="<?php echo e(URL::asset('/plugins/notify/js/notifit-custom.js')); ?>"></script>
<?php echo $__env->make('Script.Script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteForm = document.getElementById('deleteSectionForm<?php echo e($section->id); ?>');
        if (deleteForm) {
            deleteForm.addEventListener('submit', function(e) {
                const deleteBtn = document.getElementById('deleteSectionBtn<?php echo e($section->id); ?>');
                if (deleteBtn) {
                    deleteBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> جاري الحذف...';
                    deleteBtn.disabled = true;
                }
            });
        }
    });
</script>
<?php $__env->stopSection(); ?>



<?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/Sections/delete.blade.php ENDPATH**/ ?>