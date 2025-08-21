<?php $__env->startSection('title'); ?>
    الكشوفات المكتملة
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
    <!-- Internal Data table css -->
    <link href="<?php echo e(URL::asset('Dashboard/plugins/select2/css/select2.min.css')); ?>" rel="stylesheet">
    <!--Internal   Notify -->
    <link href="<?php echo e(URL::asset('dashboard/plugins/notify/css/notifIt.css')); ?>" rel="stylesheet" />

    <link href="<?php echo e(URL::asset('dashboard/plugins/select2/css/select2.min.css')); ?>" rel="stylesheet">
    <!--Internal  Datetimepicker-slider css -->
    <link href="<?php echo e(URL::asset('dashboard/plugins/amazeui-datetimepicker/css/amazeui.datetimepicker.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(URL::asset('dashboard/plugins/jquery-simple-datetimepicker/jquery.simple-dtpicker.css')); ?>"
        rel="stylesheet">
    <link href="<?php echo e(URL::asset('dashboard/plugins/pickerjs/picker.min.css')); ?>" rel="stylesheet">
    <!-- Internal Spectrum-colorpicker css -->
    <link href="<?php echo e(URL::asset('dashboard/plugins/spectrum-colorpicker/spectrum.css')); ?>" rel="stylesheet">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-header'); ?>
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto"><?php echo e(trans('doctors.completed_examinations')); ?></h4><span
                    class="text-muted mt-1 tx-13 mr-2 mb-0">/ <?php echo e(trans('doctors.invoices')); ?></span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('Dashboard.messages_alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- row -->
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table text-md-nowrap" id="example1">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?php echo e(trans('doctors.invoice_date')); ?></th>
                                    <th><?php echo e(trans('AdminSb.service_name')); ?></th>
                                    <th><?php echo e(trans('doctors.patient_name')); ?></th>
                                    <th><?php echo e(trans('doctors.service_price')); ?></th>
                                    <th><?php echo e(trans('doctors.discount_value')); ?></th>
                                    <th><?php echo e(trans('doctors.tax_rate')); ?></th>
                                    <th><?php echo e(trans('doctors.tax_value')); ?></th>
                                    <th><?php echo e(trans('doctors.total_with_tax')); ?></th>
                                    <th><?php echo e(trans('doctors.invoice_status')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($loop->iteration); ?></td>
                                        <td><?php echo e($invoice->invoice_date); ?></td>
                                        <td><?php echo e($invoice->Service->name ?? $invoice->Group->name); ?></td>
                                        <td><a
                                                href="<?php echo e(route('doctor.patient_details', $invoice->patient_id)); ?>"><?php echo e($invoice->Patient->name); ?></a>
                                        </td>
                                        <td><?php echo e(number_format($invoice->price, 2)); ?></td>
                                        <td><?php echo e(number_format($invoice->discount_value, 2)); ?></td>
                                        <td><?php echo e($invoice->tax_rate); ?>%</td>
                                        <td><?php echo e(number_format($invoice->tax_value, 2)); ?></td>
                                        <td><?php echo e(number_format($invoice->total_with_tax, 2)); ?></td>
                                        <td>
                                            <?php if($invoice->invoice_status == 1): ?>
                                                <span
                                                    class="badge badge-danger"><?php echo e(trans('doctors.under_procedure')); ?></span>
                                            <?php elseif($invoice->invoice_status == 2): ?>
                                                <span class="badge badge-warning"><?php echo e(trans('doctors.review')); ?></span>
                                            <?php else: ?>
                                                <span class="badge badge-success"><?php echo e(trans('doctors.completed')); ?></span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php echo $__env->make('Dashboard.doctor.invoices.add_diagnosis', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div><!-- bd -->
            </div><!-- bd -->
        </div>
        <!--/div-->
    </div>
    <!-- /row -->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>


    <!--Internal  Notify js -->
    <script src="<?php echo e(URL::asset('dashboard/plugins/notify/js/notifIt.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/plugins/notify/js/notifit-custom.js')); ?>"></script>


    <!--Internal  Datepicker js -->
    <script src="<?php echo e(URL::asset('dashboard/plugins/jquery-ui/ui/widgets/datepicker.js')); ?>"></script>
    <!--Internal  jquery.maskedinput js -->
    <script src="<?php echo e(URL::asset('dashboard/plugins/jquery.maskedinput/jquery.maskedinput.js')); ?>"></script>
    <!--Internal  spectrum-colorpicker js -->
    <script src="<?php echo e(URL::asset('dashboard/plugins/spectrum-colorpicker/spectrum.js')); ?>"></script>
    <!-- Internal Select2.min js -->
    <script src="<?php echo e(URL::asset('dashboard/plugins/select2/js/select2.min.js')); ?>"></script>
    <!--Internal Ion.rangeSlider.min js -->
    <script src="<?php echo e(URL::asset('dashboard/plugins/ion-rangeslider/js/ion.rangeSlider.min.js')); ?>"></script>
    <!--Internal  jquery-simple-datetimepicker js -->
    <script src="<?php echo e(URL::asset('dashboard/plugins/amazeui-datetimepicker/js/amazeui.datetimepicker.min.js')); ?>"></script>
    <!-- Ionicons js -->
    <script src="<?php echo e(URL::asset('dashboard/plugins/jquery-simple-datetimepicker/jquery.simple-dtpicker.js')); ?>"></script>
    <!--Internal  pickerjs js -->
    <script src="<?php echo e(URL::asset('dashboard/plugins/pickerjs/picker.min.js')); ?>"></script>
    <!-- Internal form-elements js -->
    <script src="<?php echo e(URL::asset('dashboard/js/form-elements.js')); ?>"></script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('Dashboard.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/doctor/invoices/completed_invoices.blade.php ENDPATH**/ ?>