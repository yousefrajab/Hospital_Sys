<!-- Deleted insurance -->
<div class="modal fade" id="deleted_laboratorie<?php echo e($patient_Laboratorie->id); ?>" tabindex="-1" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?php echo e(trans('doctors.delete_lab_details')); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?php echo e(trans('doctors.close')); ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?php echo e(route('doctor.Laboratories.destroy', $patient_Laboratorie->id)); ?>" method="post">
                    <?php echo method_field('DELETE'); ?>
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="col">
                            <p class="h5 text-danger"><?php echo e(trans('doctors.confirm_lab_delete_message')); ?></p>
                            <input type="text" class="form-control" readonly value="<?php echo e($patient_Laboratorie->description); ?>">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal"><?php echo e(trans('doctors.close')); ?></button>
                        <button class="btn btn-danger"><?php echo e(trans('doctors.confirm_delete')); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/doctor/invoices/deleted_laboratorie.blade.php ENDPATH**/ ?>