<!-- Modal -->
<div class="modal fade" id="xray_conversion<?php echo e($invoice->id); ?>" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?php echo e(trans('doctors.convert_to_xray_department')); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?php echo e(route('doctor.rays.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <input type="hidden" name="invoice_id" value="<?php echo e($invoice->id); ?>">
                    <input type="hidden" name="patient_id" value="<?php echo e($invoice->patient_id); ?>">
                    <input type="hidden" name="doctor_id" value="<?php echo e($invoice->doctor_id); ?>">

                    <div class="form-group">
                        <label for="exampleFormControlTextarea1"><?php echo e(trans('doctors.request_description')); ?></label>
                        <textarea class="form-control" name="description" rows="6"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-dismiss="modal"><?php echo e(trans('doctors.close')); ?></button>
                    <button type="submit" class="btn btn-primary"><?php echo e(trans('doctors.save_data')); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/doctor/invoices/xray_conversion.blade.php ENDPATH**/ ?>