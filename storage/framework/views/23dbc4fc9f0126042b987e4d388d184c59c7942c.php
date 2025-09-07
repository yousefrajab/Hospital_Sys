<?php $__env->startSection('title', 'إدارة آراء المرضى'); ?>
<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">
                <div class="page-title-box">
                    <div class="row">
                        <div class="col">
                            <h4 class="page-title"><?php echo e(trans('AdminSb.patient_reviews_and_comments')); ?></h4>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a
                                        href="<?php echo e(route('dashboard.admin')); ?>"><?php echo e(trans('AdminSb.dashboard')); ?></a></li>
                                <li class="breadcrumb-item active"><?php echo e(trans('AdminSb.comments_management')); ?></li>
                            </ol>
                        </div><!--end col-->
                    </div><!--end row-->
                </div><!--end page-title-box-->
            </div><!--end col-->
        </div><!--end row-->
        <!-- end page title end breadcrumb -->

        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo e(session('success')); ?>

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"><?php echo e(trans('AdminSb.comments_list')); ?></h4>
                    </div><!--end card-header-->
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th><?php echo e(trans('AdminSb.patient_name')); ?></th>
                                        <th><?php echo e(trans('AdminSb.comment')); ?></th>
                                        <th><?php echo e(trans('AdminSb.status')); ?></th>
                                        <th><?php echo e(trans('AdminSb.creation_date')); ?></th>
                                        <th><?php echo e(trans('AdminSb.approval_rejection_date')); ?></th>
                                        <th><?php echo e(trans('AdminSb.actions')); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $testimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $testimonial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                        <tr>
                                            <td><?php echo e($loop->iteration); ?></td>
                                            <td><?php echo e($testimonial->patient_name); ?></td>
                                            <td><?php echo e(Str::limit($testimonial->comment, 100)); ?></td>
                                            <td>
                                                <?php if($testimonial->status == 'approved'): ?>
                                                    <span class="badge bg-success"><?php echo e(trans('AdminSb.approved')); ?></span>
                                                <?php elseif($testimonial->status == 'pending'): ?>
                                                    <span
                                                        class="badge bg-warning text-dark"><?php echo e(trans('AdminSb.pending_review')); ?></span>
                                                <?php elseif($testimonial->status == 'rejected'): ?>
                                                    <span class="badge bg-danger"><?php echo e(trans('AdminSb.rejected')); ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo e($testimonial->created_at->translatedFormat('d M Y, h:i A')); ?></td>
                                            <td>
                                                <?php if($testimonial->status == 'approved' && $testimonial->approved_at): ?>
                                                    <?php echo e($testimonial->approved_at->translatedFormat('d M Y, h:i A')); ?>

                                                <?php elseif($testimonial->status == 'rejected' && $testimonial->updated_at): ?>
                                                    <?php echo e($testimonial->updated_at->translatedFormat('d M Y, h:i A')); ?>

                                                    (<?php echo e(trans('AdminSb.rejected_short')); ?>)
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($testimonial->status == 'pending'): ?>
                                                    <form
                                                        action="<?php echo e(route('admin.testimonials.approve', $testimonial->id)); ?>"
                                                        method="POST" class="d-inline">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('PATCH'); ?>
                                                        <button type="submit" class="btn btn-sm btn-soft-success"
                                                            title="<?php echo e(trans('AdminSb.approve')); ?>"><i
                                                                class="fas fa-check"></i></button>
                                                    </form>
                                                    <form
                                                        action="<?php echo e(route('admin.testimonials.reject', $testimonial->id)); ?>"
                                                        method="POST" class="d-inline">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('PATCH'); ?>
                                                        <button type="submit" class="btn btn-sm btn-soft-danger"
                                                            title="<?php echo e(trans('AdminSb.reject')); ?>"><i
                                                                class="fas fa-times"></i></button>
                                                    </form>
                                                <?php elseif($testimonial->status == 'approved'): ?>
                                                    <form
                                                        action="<?php echo e(route('admin.testimonials.reject', $testimonial->id)); ?>"
                                                        method="POST" class="d-inline">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('PATCH'); ?>
                                                        <button type="submit" class="btn btn-sm btn-soft-warning"
                                                            title="<?php echo e(trans('AdminSb.change_to_rejected')); ?>"><i
                                                                class="fas fa-undo"></i>
                                                            <?php echo e(trans('AdminSb.reject')); ?></button>
                                                    </form>
                                                <?php elseif($testimonial->status == 'rejected'): ?>
                                                    <form
                                                        action="<?php echo e(route('admin.testimonials.approve', $testimonial->id)); ?>"
                                                        method="POST" class="d-inline">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('PATCH'); ?>
                                                        <button type="submit" class="btn btn-sm btn-soft-info"
                                                            title="<?php echo e(trans('AdminSb.change_to_approved')); ?>"><i
                                                                class="fas fa-undo"></i>
                                                            <?php echo e(trans('AdminSb.approve')); ?></button>
                                                    </form>
                                                <?php endif; ?>

                                                <form action="<?php echo e(route('admin.testimonials.destroy', $testimonial->id)); ?>"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('<?php echo e(trans('AdminSb.confirm_delete_comment')); ?>');">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-sm btn-soft-danger"
                                                        title="<?php echo e(trans('AdminSb.delete')); ?>"><i
                                                            class="fas fa-trash-alt"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <tr>
                                            <td colspan="7" class="text-center">
                                                <?php echo e(trans('AdminSb.no_comments_to_display')); ?></td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div><!--end /table-responsive-->
                        <div class="mt-3">
                            <?php echo e($testimonials->links()); ?>

                        </div>
                    </div><!--end card-body-->
                </div><!--end card-->
            </div><!--end col-->
        </div><!--end row-->
    </div><!-- container -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    
    <style>
        .btn-soft-success {
            background-color: rgba(40, 199, 111, 0.1);
            color: #28c76f !important;
            border-color: transparent;
        }

        .btn-soft-success:hover {
            background-color: #28c76f;
            color: #fff !important;
        }

        .btn-soft-danger {
            background-color: rgba(234, 84, 85, 0.1);
            color: #ea5455 !important;
            border-color: transparent;
        }

        .btn-soft-danger:hover {
            background-color: #ea5455;
            color: #fff !important;
        }

        .btn-soft-primary {
            background-color: rgba(0, 123, 255, 0.1);
            color: #007bff !important;
            border-color: transparent;
        }

        .btn-soft-primary:hover {
            background-color: #007bff;
            color: #fff !important;
        }

        .btn-soft-warning {
            background-color: rgba(255, 159, 67, 0.1);
            color: #ff9f43 !important;
            border-color: transparent;
        }

        .btn-soft-warning:hover {
            background-color: #ff9f43;
            color: #fff !important;
        }

        .btn-soft-info {
            background-color: rgba(0, 207, 232, 0.1);
            color: #00cfe8 !important;
            border-color: transparent;
        }

        .btn-soft-info:hover {
            background-color: #00cfe8;
            color: #fff !important;
        }

        .badge.bg-success {
            background-color: #28c76f !important;
        }

        .badge.bg-warning {
            background-color: #ff9f43 !important;
        }

        .badge.bg-danger {
            background-color: #ea5455 !important;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Dashboard.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\Hospital_Sys\resources\views/admin/testimonials/index.blade.php ENDPATH**/ ?>