<?php $__env->startSection('title'); ?>
    <?php echo e(trans('Dashboard/main-sidebar_trans.sections')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
    <!-- Internal Data table css -->
    <link href="<?php echo e(URL::asset('Dashboard/plugins/select2/css/select2.min.css')); ?>" rel="stylesheet">
    <!--Internal   Notify -->
    <link href="<?php echo e(URL::asset('dashboard/plugins/notify/css/notifIt.css')); ?>" rel="stylesheet" />
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-header'); ?>
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto"><?php echo e(trans('patients.patient_operations')); ?></h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/
                    <?php echo e(trans('patients.invoices')); ?></span>
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
                <div class="card-header pb-0">
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table style="text-align: center" class="table text-md-nowrap" id="example1">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?php echo e(trans('patients.invoice_date_col')); ?></th>
                                    <th><?php echo e(trans('patients.doctor_name_col')); ?></th>
                                    <th><?php echo e(trans('patients.service_name_col')); ?></th>
                                    <th><?php echo e(trans('patients.total_col')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($loop->iteration); ?></td>
                                        <td><?php echo e($invoice->invoice_date); ?></td>
                                        <td><?php echo e($invoice->doctor->name ?? trans('patients.unspecified')); ?></td>
                                        <td>
                                            <?php if($invoice->Service_id && $invoice->service): ?>
                                                <?php echo e($invoice->service->name); ?>

                                            <?php elseif($invoice->Group_id && $invoice->group): ?>
                                                <?php echo e($invoice->group->name); ?> <?php echo e(trans('patients.group_suffix')); ?>

                                            <?php else: ?>
                                                <span class="text-muted"><?php echo e(trans('patients.unspecified')); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($invoice->total_with_tax); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div><!-- bd -->
            </div><!-- bd -->
        </div>
        <!--/div-->

        <?php echo $__env->make('Dashboard.Sections.add', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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

<?php echo $__env->make('Dashboard.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/dashboard_patient/invoices.blade.php ENDPATH**/ ?>