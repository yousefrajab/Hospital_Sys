<?php $__env->startSection('title'); ?>
    سندات القبض
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
                <h4 class="content-title mb-0 my-auto"><?php echo e(trans('AdminSb.accounts')); ?></h4><span
                    class="text-muted mt-1 tx-13 mr-2 mb-0">/ <?php echo e(trans('AdminSb.receipts')); ?></span>
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
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between">
                        <a href="<?php echo e(route('admin.Receipt.create')); ?>" class="btn btn-primary" role="button"
                            aria-pressed="true"><?php echo e(trans('AdminSb.add_new_receipt')); ?></a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table text-md-nowrap" id="example1">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?php echo e(trans('AdminSb.patient_name')); ?></th>
                                    <th><?php echo e(trans('AdminSb.service_package')); ?></th>
                                    <th><?php echo e(trans('AdminSb.amount')); ?></th>
                                    <th><?php echo e(trans('AdminSb.description')); ?></th>
                                    <th><?php echo e(trans('AdminSb.date_added')); ?></th>
                                    <th><?php echo e(trans('AdminSb.Processes')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $receipts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $receipt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($loop->iteration); ?></td>
                                        <td><?php echo e($receipt->patients->name); ?></td>
                                        <td>
                                            <?php if($receipt->service): ?>
                                                <?php echo e($receipt->service->name); ?> <span
                                                    class="badge badge-primary"><?php echo e(trans('AdminSb.service')); ?></span>
                                            <?php elseif($receipt->group): ?>
                                                <?php echo e($receipt->group->name); ?> <span
                                                    class="badge badge-success"><?php echo e(trans('AdminSb.package')); ?></span>
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e(number_format($receipt->amount, 2)); ?></td>
                                        <td><?php echo e(\Str::limit($receipt->description, 50)); ?></td>
                                        <td><?php echo e($receipt->created_at->diffForHumans()); ?></td>
                                        <td>
                                            <a href="<?php echo e(route('admin.Receipt.edit', $receipt->id)); ?>"
                                                class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>
                                            <a class="modal-effect btn btn-sm btn-danger" data-effect="effect-scale"
                                                data-toggle="modal" href="#delete<?php echo e($receipt->id); ?>"><i
                                                    class="las la-trash"></i></a>
                                            <a href="<?php echo e(route('admin.Receipt.show', $receipt->id)); ?>"
                                                class="btn btn-primary btn-sm" target="_blank"
                                                title="<?php echo e(trans('AdminSb.print_receipt')); ?>"><i
                                                    class="fas fa-print"></i></a>
                                        </td>
                                    </tr>
                                    <?php echo $__env->make('Dashboard.Receipt.delete', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div><!-- bd -->
            </div><!-- bd -->
        </div>
        <!--/div-->
    </div>
    <!-- row closed -->
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

<?php echo $__env->make('Dashboard.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/Receipt/index.blade.php ENDPATH**/ ?>