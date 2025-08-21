<?php $__env->startSection('css'); ?>

    <?php echo $__env->make('Style.Style', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header'); ?>
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto"><?php echo e(trans('Dashboard/main-sidebar_trans.sections')); ?></h4>
                <span class="text mt-1 tx-13 mr-2 mb-0">/ <?php echo e(trans('main-sidebar_trans.view_all')); ?></span>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('Dashboard.messages_alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card section-table-card">
                <div class="card-header card-header-gradient">
                    <div class="d-flex align-items-center">
                        <h3 class="card-title mb-0 text-white me-auto">
                            <i class="fas fa-list-alt mr-2"></i><?php echo e(trans('Dashboard/main-sidebar_trans.sections')); ?>

                        </h3>
                        <button type="button" class="btn btn-light" data-toggle="modal" data-target="#add">
                            <i class="fas fa-plus-circle mr-1"></i><?php echo e(trans('Dashboard/sections_trans.add_sections')); ?>

                        </button>
                    </div>
                </div>


                <div class="card-body">
                    <div class="table-responsive" style="min-height: 400px;">
                        <table class="table table-hover table-advanced" id="sections-table" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><?php echo e(trans('sections_trans.name_sections')); ?></th>
                                    <th><?php echo e(trans('sections_trans.description')); ?></th>
                                    <th><?php echo e(trans('sections_trans.created_at')); ?></th>
                                    <th><?php echo e(trans('sections_trans.Processes')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($loop->iteration); ?></td>
                                        <td>
                                            <a href="<?php echo e(route('admin.Sections.show', $section->id)); ?>" class="section-link">
                                                <?php echo e($section->name); ?>

                                            </a>
                                        </td>
                                        <td><?php echo e(\Str::limit($section->description, 50)); ?></td>
                                        <td class="creation-time-cell">
                                            <div class="time-display"
                                                title="<?php echo e($section->created_at->format('Y/m/d h:i A')); ?>">
                                                <small class="text-muted">
                                                    <?php echo e($section->created_at->format('Y/m/d')); ?>

                                                    <span
                                                        class="time-period <?php echo e($section->created_at->format('A') == 'AM' ? 'morning' : 'evening'); ?>">
                                                        <?php echo e($section->created_at->format('A') == 'AM' ? trans('doctors.AM') : trans('doctors.PM')); ?>

                                                    </span>
                                                </small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <a class="btn btn-sm btn-info mr-2" data-toggle="modal"
                                                    href="#edit<?php echo e($section->id); ?>">
                                                    <i class="las la-pen"></i>
                                                </a>
                                                <a class="btn btn-sm btn-danger" data-toggle="modal"
                                                    href="#delete<?php echo e($section->id); ?>">
                                                    <i class="las la-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>

                                    <?php echo $__env->make('Dashboard.Sections.edit', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    <?php echo $__env->make('Dashboard.Sections.delete', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <?php echo $__env->make('Dashboard.Sections.add', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <?php echo $__env->make('Script.Script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Dashboard.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/Sections/index.blade.php ENDPATH**/ ?>