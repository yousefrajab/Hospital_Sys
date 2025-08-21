<!-- Modal -->
<div class="modal fade" id="add_diagnosis<?php echo e($invoice->id); ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?php echo e(trans('doctors.diagnose_patient_condition')); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?php echo e(trans('doctors.close')); ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?php echo e(route('doctor.Diagnostics.store')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="modal-body">

                <input type="hidden" name="invoice_id" value="<?php echo e($invoice->id); ?>">
                <input type="hidden" name="patient_id" value="<?php echo e($invoice->patient_id); ?>">
                <input type="hidden" name="doctor_id" value="<?php echo e($invoice->doctor_id); ?>">

                <div class="form-group">
                    <label for="exampleFormControlTextarea1"><?php echo e(trans('doctors.diagnosis')); ?></label>
                    <textarea class="form-control" name="diagnosis" rows="6"></textarea>
                </div>

                <div class="form-group">
                    <label for="exampleFormControlTextarea1"><?php echo e(trans('doctors.medicines')); ?></label>
                    <textarea class="form-control" name="medicine" rows="6"></textarea>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo e(trans('doctors.close')); ?></button>
                <button type="submit" class="btn btn-primary"><?php echo e(trans('doctors.save_data')); ?></button>
            </div>
            </form>
        </div>
    </div>
</div>```


<?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/doctor/invoices/add_diagnosis.blade.php ENDPATH**/ ?>