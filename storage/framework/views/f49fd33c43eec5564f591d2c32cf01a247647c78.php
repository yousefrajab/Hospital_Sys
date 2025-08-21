<?php echo $__env->make('Style.Style', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div class="modal fade" id="delete_select" tabindex="-1" aria-labelledby="deleteSelectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <!-- Modal Header -->
            <div class="modal-header bg-gradient-danger text-white">
                <div class="d-flex align-items-center">
                    <i class="fas fa-users-slash fa-2x mr-3"></i>
                    <div>
                        <h5 class="modal-title font-weight-bold" id="deleteSelectModalLabel">
                            <?php echo e(trans('doctors.delete_select')); ?>

                        </h5>
                        <p class="mb-0 small"><?php echo e(trans('sections_trans.Warning')); ?></p>
                    </div>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body py-4">
                <form action="<?php echo e(route('admin.Doctors.destroy', 'test')); ?>" method="post" id="deleteSelectForm">
                    <?php echo method_field('DELETE'); ?>
                    <?php echo csrf_field(); ?>
                    <input type="hidden" id="delete_select_id" name="delete_select_id" value=''>

                    <div class="text-center mb-4">
                        <div class="delete-icon-container">
                            <i class="fas fa-users text-danger"></i>
                        </div>
                        <h4 class="text-danger font-weight-bold mt-3">
                            <?php echo e(trans('sections_trans.warning')); ?> !!
                        </h4>
                        <p class="text-muted"><?php echo e(trans('doctors.delete_doctor_warning')); ?></p>
                    </div>

                    <div class="alert alert-light border-danger border text-center py-3">
                        <h5 class="font-weight-bold mb-1"><?php echo e(trans('doctors.select')); ?></h5>
                        <p class="text-muted small mb-0">
                            <?php echo e(trans('doctors.delete_select_review') ?? 'تأكد من مراجعة جميع العناصر قبل التأكيد.'); ?>

                        </p>
                    </div>

                    <div class="custom-control custom-checkbox my-4 text-center">
                        <input type="checkbox" class="custom-control-input" id="confirmCheckSelect" required>
                        <label class="custom-control-label text-danger" for="confirmCheckSelect">
                            <?php echo e(trans('sections_trans.I agree that this procedure cannot be undone')); ?>

                        </label>
                    </div>

                    <!-- Modal Footer -->
                    <div class="modal-footer border-0 pt-0">
                        <button type="button" class="btn btn-light bg-gradient-light" data-dismiss="modal">
                            <i class="fas fa-times-circle mr-2"></i> <?php echo e(trans('Dashboard/sections_trans.Close')); ?>

                        </button>
                        <button type="submit" id="deleteSelectBtn" class="btn btn-danger bg-gradient-danger">
                            <i class="fas fa-trash-alt mr-2"></i> <?php echo e(trans('Dashboard/sections_trans.submit')); ?>

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
    <!-- Internal Data tables -->
    <script src="<?php echo e(URL::asset('Dashboard/plugins/datatable/js/jquery.dataTables.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('Dashboard/plugins/datatable/js/dataTables.dataTables.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('Dashboard/plugins/datatable/js/dataTables.responsive.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('Dashboard/plugins/datatable/js/responsive.dataTables.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('Dashboard/plugins/datatable/js/dataTables.bootstrap4.js')); ?>"></script>


    <script src="<?php echo e(URL::asset('Dashboard/plugins/select2/js/select2.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('Dashboard/plugins/neomorphic-ui/neomorphic.js')); ?>"></script>
    <?php echo $__env->make('Script.Script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/Doctors/delete_select.blade.php ENDPATH**/ ?>