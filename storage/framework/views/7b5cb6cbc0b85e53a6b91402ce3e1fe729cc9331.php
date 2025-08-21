<!-- Modal -->
<div class="modal fade" id="edit_xray_conversion<?php echo e($patient_ray->id); ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?php echo e(trans('doctors.convert_to_xray_department')); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?php echo e(trans('doctors.close')); ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?php echo e(route('doctor.rays.update',$patient_ray->id)); ?>" method="POST">
                <?php echo method_field('PUT'); ?>
            <?php echo csrf_field(); ?>
            <div class="modal-body">
                <div class="form-group">
                    <label for="exampleFormControlTextarea1"><?php echo e(trans('doctors.request_description')); ?></label>
                    <textarea class="form-control" name="description" rows="6"><?php echo e($patient_ray->description); ?></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo e(trans('doctors.close')); ?></button>
                <button type="submit" class="btn btn-primary"><?php echo e(trans('doctors.save_data')); ?></button>
            </div>
            </form>
        </div>
    </div>
</div>
<?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/doctor/invoices/edit_xray_conversion.blade.php ENDPATH**/ ?>