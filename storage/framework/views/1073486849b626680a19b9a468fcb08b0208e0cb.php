<?php if(isset($medication_for_modal) && $medication_for_modal instanceof \App\Models\Medication): ?>
<div class="modal fade" id="deleteMedicationModal<?php echo e($medication_for_modal->id); ?>" tabindex="-1" aria-labelledby="deleteMedicationModalLabel<?php echo e($medication_for_modal->id); ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteMedicationModalLabel<?php echo e($medication_for_modal->id); ?>">
                    <i class="fas fa-exclamation-triangle me-2"></i> <?php echo e(trans('phrM.confirm_medication_deletion_title')); ?>

                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4 text-center">
                <form action="<?php echo e(route('pharmacy_manager.medications.destroy', $medication_for_modal->id)); ?>" method="post" id="confirmDeleteMedicationForm<?php echo e($medication_for_modal->id); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <div style="font-size: 3rem; color: var(--admin-danger, #ef4444); margin-bottom: 1rem;">
                        <i class="fas fa-pills"></i>
                    </div>
                    <h4><?php echo e(trans('phrM.are_you_sure_you_want_to_delete_medication')); ?></h4>
                    <p class="text-muted">
                        <?php echo e(trans('phrM.medication_label')); ?> <strong><?php echo e($medication_for_modal->name); ?></strong>
                        <?php if($medication_for_modal->generic_name): ?>
                            <br><small><?php echo e(trans('phrM.generic_name_label_in_modal')); ?> <?php echo e($medication_for_modal->generic_name); ?>)</small>
                        <?php endif; ?>
                    </p>
                    <p class="text-danger small mt-2">
                        <i class="fas fa-info-circle"></i> <?php echo e(trans('phrM.action_cannot_be_undone')); ?>

                        <br><?php echo e(trans('phrM.usage_check_before_deletion_note')); ?>

                    </p>
                </form>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="min-width: 100px;">
                    <i class="fas fa-times me-1"></i> <?php echo e(trans('phrM.cancel')); ?>

                </button>
                <button type="submit" form="confirmDeleteMedicationForm<?php echo e($medication_for_modal->id); ?>" class="btn btn-danger" style="min-width: 100px;">
                    <i class="fas fa-trash-alt me-1"></i> <?php echo e(trans('phrM.yes_delete_button')); ?>

                </button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/PharmacyManager/Medications/delete_confirm_modal.blade.php ENDPATH**/ ?>