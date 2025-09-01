<?php $__env->startSection('css'); ?>

    <?php echo $__env->make('Style.Style', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header'); ?>
    <div class="breadcrumb-header justify-content-between bg-gradient-primary">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto" style="color: white"><?php echo e($section->name); ?></h4>
                <span class="mt-1 tx-13 mr-2 mb-0" style="color: white"> / <?php echo e(trans('sections_trans.section_doctors')); ?></span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <a href="<?php echo e(route('admin.Sections.index')); ?>" class="btn btn-outline-light btn-with-icon" style="color: white">
                <i class="fas fa-arrow-left"></i> <?php echo e(trans('sections_trans.Back to Sections')); ?>

            </a>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('Dashboard.messages_alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card card-custom">
                <div class="card-header border-bottom-0">
                    <h3 class="card-title" style="color: white"><?php echo e(trans('doctors.List of Doctors')); ?> / <?php echo e($section->name); ?></h3>
                    <div class="card-toolbar">
                        <span class="badge bg-primary fs-4 p-3 d-flex align-items-center"
                            style="
                            box-shadow: 10px 10px 10px rgba(0, 0, 0, 0.1);
                            border-radius: 20px;
                            line-height: 1.5;
                        ">
                            <i class="fas fa-user-md me-2"></i>
                            <span>
                               <?php echo e(trans('sections_trans.total number')); ?> :
                                <strong class="fs-3"><?php echo e($doctors->count()); ?></strong>
                            </span>
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive" style="overflow: visible;">
                        <table class="table text-center border-0 rounded-3 shadow table-hover"
                            style="transition: all 0.3s ease-in-out;">

                            <thead class="thead-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th><?php echo e(trans('doctors.name')); ?></th>
                                    <th><?php echo e(trans('doctors.national_id')); ?></th>
                                    <th><?php echo e(trans('doctors.img')); ?></th>
                                    <th><?php echo e(trans('doctors.email')); ?></th>
                                    <th><?php echo e(trans('doctors.section')); ?></th>
                                    <th><?php echo e(trans('doctors.phone')); ?></th>
                                    <th><?php echo e(trans('doctors.appointments')); ?></th>
                                    <th><?php echo e(trans('doctors.number_of_statements')); ?></th>
                                    <th width="10%"><?php echo e(trans('doctors.Status')); ?></th>
                                    <th width="15%"><?php echo e(trans('doctors.Processes')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $doctors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doctor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <th scope="row"><?php echo e($loop->iteration); ?></th>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="symbol symbol-40 symbol-light-primary mr-3">
                                                    <span class="symbol-label">
                                                        <span class="svg-icon svg-icon-md svg-icon-primary">
                                                            <i class="fas fa-user-md"></i>
                                                        </span>
                                                    </span>
                                                </div>
                                                <div class="d-flex flex-column">
                                                    <span class="text-dark-75 font-weight-bold"><?php echo e($doctor->name); ?></span>
                                                </div>
                                            </div>
                                        </td>
                                        <!-- العمود الجديد: رقم الهوية -->
                                        <td class="font-weight-bold text-primary">
                                            <?php echo e($doctor->national_id ?? 'غير محدد'); ?>

                                        </td>
                                        <td>
                                            <div class="doctor-avatar-container">
                                                <?php if($doctor->image): ?>
                                                    <img src="<?php echo e(asset('Dashboard/img/doctors/' . $doctor->image->filename)); ?>"
                                                        class="doctor-avatar" alt="<?php echo e(trans('doctors.img')); ?>"
                                                        onerror="this.src='<?php echo e(asset('Dashboard/img/doctor_default.png')); ?>'">
                                                <?php else: ?>
                                                    <img src="<?php echo e(asset('Dashboard/img/doctor_default.png')); ?>"
                                                        class="doctor-avatar" alt="صورة افتراضية">
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td><?php echo e($doctor->email); ?></td>
                                        <td>
                                            <span
                                                class="inline-block text-2xl font-bold text-primary bg-primary/10 rounded-xl shadow-sm">
                                                <?php echo e($doctor->section->name); ?>

                                            </span>
                                        </td>

                                        <td dir="ltr"><?php echo e($doctor->phone); ?></td>
                                        <td>
                                            <?php $__currentLoopData = $doctor->doctorappointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="badge badge-pill badge-light-info mb-1">
                                                    <?php echo e($appointment->name); ?>

                                                </span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>
                                        <td>
                                            <div class="statements-count" data-count="<?php echo e($doctor->number_of_statements); ?>">
                                                <div class="count-circle">
                                                    <span class="count-number"><?php echo e($doctor->number_of_statements); ?></span>
                                                    <svg class="count-circle-bg" viewBox="0 0 36 36">
                                                        <path d="M18 2.0845
                                                                                    a 15.9155 15.9155 0 0 1 0 31.831
                                                                                    a 15.9155 15.9155 0 0 1 0 -31.831"
                                                            fill="none" stroke="#e0e0e0" stroke-width="3" />
                                                        <path class="count-circle-fill" d="M18 2.0845
                                                                                    a 15.9155 15.9155 0 0 1 0 31.831
                                                                                    a 15.9155 15.9155 0 0 1 0 -31.831"
                                                            fill="none" stroke="#4361ee" stroke-width="3"
                                                            stroke-dasharray="0, 100" />
                                                    </svg>
                                                </div>
                                                <span class="count-label"><?php echo e(trans('doctors.Reports')); ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <span
                                                class="badge-status <?php echo e($doctor->status == 1 ? 'active-status' : 'inactive-status'); ?>">
                                                <i class="fas fa-circle status-icon pulse me-1"
                                                    style="color: <?php echo e($doctor->status == 1 ? '#28a745' : '#dc3545'); ?>"></i>
                                                <?php echo e($doctor->status == 1 ? trans('doctors.Enabled') : trans('doctors.Not_enabled')); ?>

                                            </span>
                                        </td>

                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-primary dropdown-toggle"
                                                    type="button" id="dropdownMenu<?php echo e($doctor->id); ?>"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-cog"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-down"
                                                    aria-labelledby="dropdownMenu<?php echo e($doctor->id); ?>">
                                                    <a class="dropdown-item"
                                                        href="<?php echo e(route('admin.Doctors.edit', $doctor->id)); ?>">
                                                        <i class="fas fa-edit text-success"></i><?php echo e(trans('doctors.modify data')); ?>

                                                    </a>
                                                    <a class="dropdown-item" href="#" data-toggle="modal"
                                                        data-target="#update_password<?php echo e($doctor->id); ?>">
                                                        <i class="fas fa-key text-primary"></i> <?php echo e(trans('doctors.update_password')); ?>

                                                    </a>
                                                    <a class="dropdown-item" href="#" data-toggle="modal"
                                                        data-target="#update_status<?php echo e($doctor->id); ?>">
                                                        <i class="fas fa-power-off text-warning"></i> <?php echo e(trans('doctors.Status_change')); ?>

                                                    </a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item text-danger" href="#" data-toggle="modal"
                                                        data-target="#delete<?php echo e($doctor->id); ?>">
                                                        <i class="fas fa-trash-alt"></i> <?php echo e(trans('doctors.delete_doctor')); ?>

                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php echo $__env->make('Dashboard.Doctors.delete', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    <?php echo $__env->make('Dashboard.Doctors.delete_select', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    <?php echo $__env->make('Dashboard.Doctors.update_password', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    <?php echo $__env->make('Dashboard.Doctors.update_status', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <?php echo $__env->make('Script.Script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Dashboard.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/Sections/show_doctors.blade.php ENDPATH**/ ?>