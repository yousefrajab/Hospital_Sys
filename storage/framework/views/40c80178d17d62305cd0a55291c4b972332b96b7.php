 <?php $__env->startSection('title'); ?>
     الفواتير المكتملة
 <?php $__env->stopSection(); ?>
 <?php $__env->startSection('css'); ?>


     <link href="<?php echo e(URL::asset('dashboard/plugins/notify/css/notifIt.css')); ?>" rel="stylesheet"/>

 <?php $__env->startSection('page-header'); ?>
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto"><?php echo e(trans('lab.completed_invoices')); ?></h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                    <?php echo e(trans('lab.invoices')); ?></span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('Dashboard.messages_alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- row -->
    <!-- row opened -->
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table text-md-nowrap" id="example1">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?php echo e(trans('lab.invoice_date')); ?></th>
                                    <th><?php echo e(trans('lab.patient_name')); ?></th>
                                    <th><?php echo e(trans('lab.doctor_name')); ?></th>
                                    <th><?php echo e(trans('lab.required_test')); ?></th>
                                    <th><?php echo e(trans('lab.invoice_status')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($loop->iteration); ?></td>
                                        <td><?php echo e($invoice->created_at); ?></td>
                                        <td><?php echo e($invoice->Patient->name); ?></td>
                                        <td><?php echo e($invoice->doctor->name); ?></td>
                                        <td><?php echo e($invoice->description); ?></td>
                                        <td>
                                            <?php if($invoice->case == 0): ?>
                                                <span class="badge badge-danger"><?php echo e(trans('lab.status_in_progress')); ?></span>
                                            <?php else: ?>
                                                <span class="badge badge-success"><?php echo e(trans('lab.status_completed')); ?></span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div><!-- bd -->
            </div><!-- bd -->
        </div>
        <!--/div-->

        <!-- /row -->
    </div>
    <!-- row closed -->

    <!-- Container closed -->

    <!-- main-content closed -->
<?php $__env->stopSection(); ?>
 <?php $__env->startSection('js'); ?>

     <!--Internal  Notify js -->
     <script src="<?php echo e(URL::asset('dashboard/plugins/notify/js/notifIt.js')); ?>"></script>
     <script src="<?php echo e(URL::asset('/plugins/notify/js/notifit-custom.js')); ?>"></script>

 <?php $__env->stopSection(); ?>

<?php echo $__env->make('Dashboard.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/dashboard_LaboratorieEmployee/invoices/completed_invoices.blade.php ENDPATH**/ ?>