<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(URL::asset('dashboard/plugins/notify/css/notifIt.css')); ?>" rel="stylesheet" />
<?php $__env->stopSection(); ?>
<?php $__env->startSection('title'); ?>
    معلومات المريض
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-header'); ?>
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto"><?php echo e(trans('doctors.patient_data')); ?></h4><span
                    class="text-muted mt-1 tx-13 mr-2 mb-0">/ <?php echo e($patient->name); ?></span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('Dashboard.messages_alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- row opened -->
    <div class="row row-sm">
        <div class="col-lg-12 col-md-12">
            <div class="card" id="basic-alert">
                <div class="card-body">
                    <div class="text-wrap">
                        <div class="example">
                            <div class="panel panel-primary tabs-style-1">
                                <div class=" tab-menu-heading">
                                    <div class="tabs-menu1">
                                        <!-- Tabs -->
                                        <ul class="nav panel-tabs main-nav-line">
                                            <li class="nav-item"><a href="#tab1" class="nav-link active"
                                                    data-toggle="tab"><?php echo e(trans('doctors.patient_record')); ?></a></li>
                                            <li class="nav-item"><a href="#tab2" class="nav-link"
                                                    data-toggle="tab"><?php echo e(trans('doctors.radiology')); ?></a></li>
                                            <li class="nav-item"><a href="#tab3" class="nav-link"
                                                    data-toggle="tab"><?php echo e(trans('doctors.laboratory')); ?></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="panel-body tabs-menu-body main-content-body-right border-top-0 border">
                                    <div class="tab-content">

                                        
                                        <div class="tab-pane active" id="tab1">
                                            <br>
                                            <div class="vtimeline">
                                                <?php $__currentLoopData = $patient_records; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patient_record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div
                                                        class="timeline-wrapper <?php echo e($loop->iteration % 2 == 0 ? 'timeline-inverted' : ''); ?> timeline-wrapper-primary">
                                                        <div class="timeline-badge"><i class="las la-check-circle"></i>
                                                        </div>
                                                        <div class="timeline-panel">
                                                            <div class="timeline-heading">
                                                                <h6 class="timeline-title">
                                                                    <?php echo e(trans('doctors.status_update')); ?></h6>
                                                            </div>
                                                            <div class="timeline-body">
                                                                <p><?php echo e($patient_record->diagnosis); ?></p>
                                                            </div>
                                                            <div
                                                                class="timeline-footer d-flex align-items-center flex-wrap">
                                                                <i class="fas fa-user-md"></i>&nbsp;
                                                                <span><?php echo e($patient_record->Doctor->name); ?></span>
                                                                <span class="mr-auto"><i
                                                                        class="fe fe-calendar text-muted mr-1"></i><?php echo e($patient_record->date); ?></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>

                                        
                                        <div class="tab-pane" id="tab2">
                                            <div class="table-responsive">
                                                <table class="table table-hover text-md-nowrap text-center">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th><?php echo e(trans('doctors.service_name')); ?></th>
                                                            <th><?php echo e(trans('doctors.doctor_name')); ?></th>
                                                            <th><?php echo e(trans('doctors.radiology_technician')); ?></th>
                                                            <th><?php echo e(trans('doctors.examination_status')); ?></th>
                                                            <th><?php echo e(trans('doctors.Processes')); ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $__currentLoopData = $patient_rays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patient_ray): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <tr>
                                                                <td><?php echo e($loop->iteration); ?></td>
                                                                <td><?php echo e($patient_ray->description); ?></td>
                                                                <td><?php echo e($patient_ray->doctor->name); ?></td>
                                                                <td><?php echo e($patient_ray->employee->name); ?></td>
                                                                <?php if($patient_ray->case == 0): ?>
                                                                    <td class="text-danger">
                                                                        <?php echo e(trans('doctors.not_completed')); ?></td>
                                                                <?php else: ?>
                                                                    <td class="text-success">
                                                                        <?php echo e(trans('doctors.completed')); ?></td>
                                                                <?php endif; ?>
                                                                <?php if($patient_ray->doctor_id == auth()->user()->id): ?>
                                                                    <?php if($patient_ray->case == 0): ?>
                                                                        <td>
                                                                            <a class="modal-effect btn btn-sm btn-primary"
                                                                                data-effect="effect-scale"
                                                                                data-toggle="modal"
                                                                                href="#edit_xray_conversion<?php echo e($patient_ray->id); ?>"><i
                                                                                    class="fas fa-edit"></i></a>
                                                                            <a class="modal-effect btn btn-sm btn-danger"
                                                                                data-effect="effect-scale"
                                                                                data-toggle="modal"
                                                                                href="#delete<?php echo e($patient_ray->id); ?>"><i
                                                                                    class="las la-trash"></i></a>
                                                                        </td>
                                                                    <?php else: ?>
                                                                        <td><a class="modal-effect btn btn-sm btn-warning"
                                                                                href="<?php echo e(route('doctor.invoices.show', $patient_ray->id)); ?>"><i
                                                                                    class="fas fa-binoculars"></i></a></td>
                                                                    <?php endif; ?>
                                                                <?php endif; ?>
                                                            </tr>
                                                            <?php echo $__env->make('Dashboard.doctor.invoices.edit_xray_conversion', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                            <?php echo $__env->make('Dashboard.doctor.invoices.deleted', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        
                                        <div class="tab-pane" id="tab3">
                                            <div class="table-responsive">
                                                <table class="table table-hover text-md-nowrap text-center">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th><?php echo e(trans('doctors.service_name')); ?></th>
                                                            <th><?php echo e(trans('doctors.doctor_name')); ?></th>
                                                            <th><?php echo e(trans('doctors.lab_technician')); ?></th>
                                                            <th><?php echo e(trans('doctors.Processes')); ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $__currentLoopData = $patient_Laboratories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patient_Laboratorie): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <tr>
                                                                <td><?php echo e($loop->iteration); ?></td>
                                                                <td><?php echo e($patient_Laboratorie->description); ?></td>
                                                                <td><?php echo e($patient_Laboratorie->doctor->name); ?></td>
                                                                <td><?php echo e($patient_Laboratorie->employee->name ?? trans('doctors.no_employee')); ?>

                                                                </td>
                                                                <?php if($patient_Laboratorie->doctor_id == auth()->user()->id): ?>
                                                                    <?php if($patient_Laboratorie->case == 0): ?>
                                                                        <td>
                                                                            <a class="modal-effect btn btn-sm btn-primary"
                                                                                data-effect="effect-scale"
                                                                                data-toggle="modal"
                                                                                href="#edit_laboratorie_conversion<?php echo e($patient_Laboratorie->id); ?>"><i
                                                                                    class="fas fa-edit"></i></a>
                                                                            <a class="modal-effect btn btn-sm btn-danger"
                                                                                data-effect="effect-scale"
                                                                                data-toggle="modal"
                                                                                href="#deleted_laboratorie<?php echo e($patient_Laboratorie->id); ?>"><i
                                                                                    class="las la-trash"></i></a>
                                                                        </td>
                                                                    <?php else: ?>
                                                                        <td><a class="modal-effect btn btn-sm btn-warning"
                                                                                href="<?php echo e(route('doctor.show.laboratorie', $patient_Laboratorie->id)); ?>"><i
                                                                                    class="fas fa-binoculars"></i></a></td>
                                                                    <?php endif; ?>
                                                                <?php endif; ?>
                                                            </tr>
                                                            <?php echo $__env->make('Dashboard.doctor.invoices.edit_laboratorie_conversion', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                            <?php echo $__env->make('Dashboard.doctor.invoices.deleted_laboratorie', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
    <script src="<?php echo e(URL::asset('dashboard/plugins/notify/js/notifIt.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('/plugins/notify/js/notifit-custom.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Dashboard.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/doctor/invoices/patient_details.blade.php ENDPATH**/ ?>