<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(URL::asset('Dashboard/plugins/notify/css/notifIt.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(URL::asset('Dashboard/plugins/select2/css/select2.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(URL::asset('Dashboard/plugins/datatable/css/dataTables.bootstrap4.min.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(URL::asset('Dashboard/plugins/datatable/css/buttons.bootstrap4.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(URL::asset('Dashboard/plugins/datatable/css/responsive.bootstrap4.min.css')); ?>" rel="stylesheet" />
    <style>
        .working-hours-summary {
            font-size: 0.8rem;
            line-height: 1.5;
            min-width: 190px;
            vertical-align: middle;
        }

        .working-days-list {
            padding-right: 0;
        }

        .working-days-list li {
            margin-bottom: 4px;
            white-space: nowrap;
            display: flex;
            align-items: center;
        }

        .working-days-list li:last-child {
            margin-bottom: 0;
        }

        .working-days-list li i {
            opacity: 0.6;
            margin-left: 5px;
            font-size: 0.9em;
            width: 15px;
            text-align: center;
        }

        .working-days-list strong {
            color: #556a81;
            min-width: 40px;
            display: inline-block;
            font-weight: 600;
        }

        .working-days-list span.times {
            color: #333;
            font-weight: 500;
        }

        .badge-light {
            background-color: #f8f9fa;
            color: #6c757d;
            font-size: 0.8em;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('title'); ?>
    <?php echo e(trans('main-sidebar_trans.doctors')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header'); ?>
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto"><?php echo e(trans('main-sidebar_trans.doctors')); ?></h4>
                <span class="text mt-1 tx-13 mr-2 mb-0">/ <?php echo e(trans('main-sidebar_trans.view_all')); ?></span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('Dashboard.messages_alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- row opened -->
    <div class="row row-sm">
        <div class="col-xl-12">
            <div class="card doctors-card">
                <div class="doctors-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0 text-white">
                            <i class="fas fa-user-md mr-2"></i><?php echo e(trans('doctors.List of Doctors')); ?>

                        </h3>
                        <div>
                            <a href="<?php echo e(route('admin.Doctors.create')); ?>" class="btn btn-light mr-2">
                                <i class="fas fa-plus-circle mr-1"></i> <?php echo e(trans('doctors.add_doctor')); ?>

                            </a>
                            <button type="button" class="btn btn-light" id="btn_delete_all">
                                <i class="fas fa-trash-alt mr-1"></i> <?php echo e(trans('doctors.delete_select')); ?>

                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive" style="min-height: 400px; overflow: visible !important;">
                        <table class="table table-hover table-advanced" id="doctors-table" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th><input name="select_all" type="checkbox" id="example-select-all"></th>
                                    <th><?php echo e(trans('doctors.name')); ?></th>
                                    <th><?php echo e(trans('doctors.national_id')); ?></th>
                                    <th><?php echo e(trans('doctors.img')); ?></th>
                                    <th><?php echo e(trans('doctors.email')); ?></th>
                                    <th><?php echo e(trans('doctors.section')); ?></th>
                                    <th><?php echo e(trans('doctors.phone')); ?></th>
                                    <th><?php echo e(trans('doctors.number_of_statements')); ?></th>
                                    <th><?php echo e(trans('AdminSb.working hours')); ?></th>
                                    <th><?php echo e(trans('doctors.Status')); ?></th>
                                    
                                    <th><?php echo e(trans('doctors.Processes')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $doctors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doctor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($loop->iteration); ?></td>
                                        <td class="text-center align-middle">
                                            <div class="innovative-checkbox">
                                                <input type="checkbox" name="delete_select" value="<?php echo e($doctor->id); ?>"
                                                    id="doctor-check-<?php echo e($doctor->id); ?>" class="delete_select">
                                                <label for="doctor-check-<?php echo e($doctor->id); ?>" class="check-label">
                                                    <svg width="24" height="24" viewBox="0 0 24 24"
                                                        class="check-icon">
                                                        <circle cx="12" cy="12" r="10"
                                                            class="check-bg-circle"></circle>
                                                        <polyline points="6 12 10 16 18 8" class="check-mark"></polyline>
                                                        <circle cx="12" cy="12" r="12" class="pulse-effect">
                                                        </circle>
                                                    </svg>
                                                    <span class="check-text"><?php echo e(trans('doctors.select')); ?></span>
                                                </label>
                                            </div>
                                        </td>
                                        <td><strong><?php echo e($doctor->name); ?></strong></td>
                                        <td><?php echo e($doctor->national_id); ?></td>
                                        <td>
                                            <?php if($doctor->image): ?>
                                                <img src="<?php echo e(Url::asset('Dashboard/img/doctors/' . $doctor->image->filename)); ?>"
                                                    class="doctor-avatar" alt="<?php echo e(trans('doctors.img')); ?>">
                                            <?php else: ?>
                                                <img src="<?php echo e(Url::asset('Dashboard/img/doctor_default.png')); ?>"
                                                    class="doctor-avatar" alt="صورة افتراضية">
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($doctor->email); ?></td>
                                        <td>
                                            <?php if($doctor->section): ?>
                                                <a href="<?php echo e(route('admin.Sections.show', $doctor->section->id)); ?>"
                                                    class="inline-block">
                                                    <span
                                                        class="badge bg-primary-light text-primary p-2"><?php echo e($doctor->section->name); ?></span>
                                                </a>
                                            <?php else: ?>
                                                <span class="badge badge-light"><?php echo e(trans('AdminSb.indefinite')); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($doctor->phone); ?></td>
                                        <td>
                                            <div class="statements-count" data-count="<?php echo e($doctor->number_of_statements); ?>">
                                                <div class="count-circle"> <span
                                                        class="count-number"><?php echo e($doctor->number_of_statements); ?></span>
                                                    <svg class="count-circle-bg" viewBox="0 0 36 36">
                                                        <path
                                                            d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                                            fill="none" stroke="#e0e0e0" stroke-width="3" />
                                                        <path class="count-circle-fill"
                                                            d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                                            fill="none" stroke="#4361ee" stroke-width="3"
                                                            stroke-dasharray="0, 100" />
                                                    </svg>
                                                </div> <span class="count-label"><?php echo e(trans('doctors.Reports')); ?></span>
                                            </div>
                                        </td>

                                        <td class="working-hours-summary">
                                            <?php if($doctor->relationLoaded('workingDays') && $doctor->workingDays->where('active', true)->isNotEmpty()): ?>
                                                <ul class="list-unstyled mb-0 working-days-list">
                                                    <?php
                                                        $daysOrder = [
                                                            'Saturday',
                                                            'Sunday',
                                                            'Monday',
                                                            'Tuesday',
                                                            'Wednesday',
                                                            'Thursday',
                                                            'Friday',
                                                        ];
                                                        $activeWorkingDays = $doctor->workingDays
                                                            ->where('active', true)
                                                            ->sortBy(function ($day) use ($daysOrder) {
                                                                $dayNameNormalized = ucfirst(
                                                                    strtolower(trim($day->day)),
                                                                );
                                                                return array_search($dayNameNormalized, $daysOrder) ??
                                                                    99;
                                                            });
                                                    ?>
                                                    <?php $__currentLoopData = $activeWorkingDays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <li>
                                                            <i class="far fa-calendar-check text-success"></i>
                                                            <strong><?php echo e(trans('doctors.' . $day->day) ?? ucfirst(strtolower(trim($day->day)))); ?>:</strong>
                                                            <span class="times text-nowrap mr-1"
                                                                dir="ltr"><?php echo e(\Carbon\Carbon::parse($day->start_time)->format('h:i A')); ?>

                                                                -
                                                                <?php echo e(\Carbon\Carbon::parse($day->end_time)->format('h:i A')); ?></span>
                                                        </li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </ul>
                                            <?php else: ?>
                                                <span class="badge badge-light"><?php echo e(trans('AdminSb.indefinite')); ?></span>
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <span
                                                class="badge-status <?php echo e($doctor->status == 1 ? 'active-status' : 'inactive-status'); ?>">
                                                <i class="fas fa-circle status-icon pulse mr-1"
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
                                                <div class="dropdown-menu dropdown-action"
                                                    aria-labelledby="dropdownMenu<?php echo e($doctor->id); ?>">
                                                    <a class="dropdown-item"
                                                        href="<?php echo e(route('admin.Doctors.edit', $doctor->id)); ?>"> <i
                                                            class="fas fa-edit text-success action-icon"></i>
                                                        <?php echo e(trans('doctors.modify data')); ?> </a>
                                                    <a class="dropdown-item" href="#" data-toggle="modal"
                                                        data-target="#update_password<?php echo e($doctor->id); ?>"> <i
                                                            class="fas fa-key text-primary action-icon"></i><?php echo e(trans('doctors.update_password')); ?>

                                                    </a>
                                                    <a class="dropdown-item" href="#" data-toggle="modal"
                                                        data-target="#update_status<?php echo e($doctor->id); ?>"> <i
                                                            class="fas fa-power-off text-warning action-icon"></i>
                                                        <?php echo e(trans('doctors.Status_change')); ?> </a>

                                                    <a href="<?php echo e(route('admin.doctors.schedule.edit', $doctor->id)); ?>"
                                                        class="dropdown-item">
                                                        <span class="btn-content"><i class="fas fa-calendar-alt me-2"></i>
                                                            <span><?php echo e(trans('AdminSb.Modify the work schedule')); ?></span></span>
                                                        <span class="btn-wave"></span>
                                                    </a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item text-danger" href="#"
                                                        data-toggle="modal" data-target="#delete<?php echo e($doctor->id); ?>"> <i
                                                            class="fas fa-trash-alt action-icon"></i>
                                                        <?php echo e(trans('doctors.delete_doctor')); ?> </a>
                                                </div>
                                            </div>

                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="12" class="text-center text-muted py-4">
                                            <?php echo e(trans('AdminSb.There are no doctors to show them currently')); ?></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /row -->

    <?php if(isset($doctors) && $doctors->isNotEmpty()): ?>
        <?php echo $__env->make('Dashboard.Doctors.delete_select', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php $__currentLoopData = $doctors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doctor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo $__env->make('Dashboard.Doctors.delete', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php echo $__env->make('Dashboard.Doctors.update_password', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php echo $__env->make('Dashboard.Doctors.update_status', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <?php echo $__env->make('Script.Script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Dashboard.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/Doctors/index.blade.php ENDPATH**/ ?>