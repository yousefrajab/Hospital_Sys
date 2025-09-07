<?php $__env->startSection('title'); ?>
    <?php echo e(trans('main-sidebar_trans.Single_service')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <?php echo $__env->make('Style.Style', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <style>
        .doctors-list .badge {
            margin-right: 5px;
            margin-bottom: 5px;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header'); ?>
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto"><?php echo e(trans('AdminSb.Services')); ?></h4>
                <span class="text-muted mt-1 tx-13 mx-2">/</span>
                <span class="text-muted mt-1 tx-13"><?php echo e(trans('AdminSb.Single_service')); ?></span>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('Dashboard.messages_alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card service-card mg-b-20">
                <div class="card-header">
                    <h4 class="card-title mb-0" style="color: white"><?php echo e(trans('AdminSb.Single_service')); ?></h4>
                    <button type="button" class="btn btn-modern" data-toggle="modal" data-target="#add"
                        style="color: white">
                        <i class="fas fa-plus mr-1"></i> <?php echo e(trans('AdminSb.add_Service')); ?>

                    </button>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="services-table" class="table table-hover text-md-nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?php echo e(trans('AdminSb.name')); ?></th>
                                    <th><?php echo e(trans('AdminSb.price')); ?></th>
                                    <th><?php echo e(trans('AdminSb.Doctor')); ?></th>
                                    <th><?php echo e(trans('AdminSb.Status')); ?></th>
                                    <th><?php echo e(trans('AdminSb.description')); ?></th>
                                    <th><?php echo e(trans('AdminSb.created_at')); ?></th>
                                    <th><?php echo e(trans('AdminSb.Processes')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(isset($services) && $services->count() > 0): ?>
                                    <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($loop->iteration); ?></td>
                                            <td><?php echo e($service->name); ?></td>
                                            <td><?php echo e(number_format($service->price, 2)); ?></td>
                                            <td class="doctors-list">
                                                <?php if($service->doctor): ?>
                                                    <span><?php echo e($service->doctor->name); ?></span>
                                                <?php else: ?>
                                                    <span
                                                        class="badge bg-secondary"><?php echo e(trans('AdminSb.not_assigned')); ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge-status <?php echo e($service->status == 1 ? 'active-status' : 'inactive-status'); ?>">
                                                    <i class="fas fa-circle status-icon pulse me-1"
                                                        style="color: <?php echo e($service->status == 1 ? '#28a745' : '#dc3545'); ?>"></i>
                                                    <?php echo e($service->status == 1 ? trans('AdminSb.Enabled') : trans('AdminSb.Not_enabled')); ?>

                                                </span>
                                            </td>
                                            <td><?php echo e(Str::limit($service->description, 50)); ?></td>
                                            <td class="creation-time-cell">
                                                <div class="time-display"
                                                    title="<?php echo e($service->created_at->format('Y/m/d h:i A')); ?>">
                                                    <small class="text-muted">
                                                        <?php echo e($service->created_at->format('Y/m/d')); ?>

                                                        <span
                                                            class="time-period <?php echo e($service->created_at->format('A') == 'AM' ? 'morning' : 'evening'); ?>">
                                                            <?php echo e($service->created_at->format('A') == 'AM' ? trans('AdminSb.am') : trans('AdminSb.pm')); ?>

                                                        </span>
                                                    </small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="action-btns">
                                                    <a class="btn btn-primary btn-sm" data-effect="effect-scale"
                                                        data-toggle="modal" href="#edit<?php echo e($service->id); ?>">
                                                        <i class="las la-pen"></i>
                                                    </a>
                                                    <a class="btn btn-danger btn-sm" data-effect="effect-scale"
                                                        data-toggle="modal" href="#delete<?php echo e($service->id); ?>">
                                                        <i class="las la-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php echo $__env->make('Dashboard.Services.Single Service.edit', [
                                            'service' => $service,
                                            'doctors' => $doctors ?? [],
                                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                        <?php echo $__env->make('Dashboard.Services.Single Service.delete', [
                                            'service' => $service,
                                        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center"><?php echo e(trans('AdminSb.no_data_available')); ?>

                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo $__env->make('Dashboard.Services.Single Service.add', ['doctors' => $doctors ?? []], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <?php echo $__env->make('Script.Script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Dashboard.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/Services/Single Service/index.blade.php ENDPATH**/ ?>