<?php $__env->startSection('title', 'إدارة الأسرة'); ?>

<?php $__env->startSection('css'); ?>
    <?php echo \Illuminate\View\Factory::parentPlaceholder('css'); ?> 
    
    <link href="<?php echo e(URL::asset('Dashboard/plugins/notify/css/notifIt.css')); ?>" rel="stylesheet" />
    
    <link href="<?php echo e(URL::asset('Dashboard/plugins/select2/css/select2.min.css')); ?>" rel="stylesheet" />
    
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <style>
        :root {
            --admin-primary: #4f46e5;
            --admin-primary-dark: #4338ca;
            --admin-secondary: #10b981;
            --admin-success: #22c55e;
            --admin-danger: #ef4444;
            --admin-warning: #f59e0b;
            --admin-info: #3b82f6;
            --admin-bg: #f8f9fc;
            --admin-card-bg: #ffffff;
            --admin-text: #111827;
            --admin-text-secondary: #6b7280;
            --admin-border-color: #e5e7eb;
            --admin-radius-sm: 0.25rem;
            --admin-radius-md: 0.375rem;
            --admin-radius-lg: 0.75rem;
            --admin-shadow: 0 1px 3px rgba(0, 0, 0, 0.07);
            --admin-shadow-md: 0 4px 10px -1px rgba(0, 0, 0, 0.07);
            --admin-transition: all 0.3s ease-in-out;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --admin-bg: #1f2937;
                --admin-card-bg: #374151;
                --admin-text: #f9fafb;
                --admin-text-secondary: #9ca3af;
                --admin-border-color: #4b5563;
                --admin-primary: #6366f1;
                --admin-primary-dark: #4f46e5;
            }

            .table thead th {
                background-color: #2d3748;
                border-color: #4b5563;
                color: var(--admin-text-secondary);
            }

            .table tbody tr:hover {
                background-color: rgba(99, 102, 241, 0.08);
            }

            .card {
                border-color: var(--admin-border-color);
                background-color: var(--admin-card-bg);
            }

            .form-control,
            .form-select,
            .select2-container--bootstrap-5 .select2-selection {
                background-color: #2d3748 !important;
                border-color: var(--admin-border-color) !important;
                color: var(--admin-text) !important;
            }

            .select2-container--bootstrap-5 .select2-dropdown {
                background-color: #2d3748;
                border-color: var(--admin-border-color);
            }

            .select2-container--bootstrap-5 .select2-results__option {
                color: var(--admin-text);
            }

            .select2-container--bootstrap-5 .select2-results__option--highlighted {
                background-color: var(--admin-primary) !important;
            }

            .page-item .page-link {
                background-color: var(--admin-card-bg);
                border-color: var(--admin-border-color);
                color: var(--admin-text-secondary);
            }

            .page-item.active .page-link {
                background-color: var(--admin-primary);
                border-color: var(--admin-primary);
            }

            .page-item.disabled .page-link {
                background-color: #4b5563;
                border-color: #6b7280;
                color: #9ca3af;
            }
        }

        body {
            background-color: var(--admin-bg);
            font-family: 'Tajawal', sans-serif;
            color: var(--admin-text);
        }

        .card {
            background-color: var(--admin-card-bg);
            border-radius: var(--admin-radius-lg);
            box-shadow: var(--admin-shadow);
            border: 1px solid var(--admin-border-color);
            margin-bottom: 1.5rem;
        }

        .card-header {
            background-color: transparent;
            border-bottom: 1px solid var(--admin-border-color);
            padding: 1rem 1.25rem;
        }

        .card-header .card-title {
            font-weight: 600;
            color: var(--admin-text);
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            margin-bottom: 0;
            color: var(--admin-text);
        }

        .table thead th {
            background-color: var(--admin-bg);
            /* لون أفتح قليلاً لخلفية الهيدر */
            color: var(--admin-text-secondary);
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid var(--admin-border-color);
            white-space: nowrap;
            padding: 0.75rem 1rem;
            text-align: right;
        }

        .table td,
        .table th {
            vertical-align: middle;
            padding: 0.75rem 1rem;
            border-top: 1px solid var(--admin-border-color);
            text-align: right;
        }

        .table tbody tr:hover {
            background-color: rgba(79, 70, 229, 0.04);
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.35em 0.65em;
            border-radius: 50px;
            font-weight: 500;
        }

        .bg-success-soft {
            background-color: rgba(34, 197, 94, 0.15);
            color: #166534;
        }

        .bg-danger-soft {
            background-color: rgba(239, 68, 68, 0.15);
            color: #991b1b;
        }

        .action-buttons .btn {
            margin: 0 2px;
            padding: 0.3rem 0.6rem;
            font-size: 0.8rem;
        }

        .action-buttons .btn i {
            font-size: 0.9rem;
        }

        .form-control,
        .form-select {
            border-radius: var(--admin-radius-md);
            border: 1px solid var(--admin-border-color);
            padding: 0.5rem 0.75rem;
            font-size: 0.9rem;
            transition: var(--admin-transition);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--admin-primary);
            box-shadow: 0 0 0 0.2rem rgba(79, 70, 229, 0.15);
        }

        .select2-container--bootstrap-5 .select2-selection {
            border-radius: var(--admin-radius-md) !important;
            border: 1px solid var(--admin-border-color) !important;
            padding: 0.32rem 0.75rem !important;
            height: calc(1.5em + 1rem + 2px) !important;
        }

        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
            height: calc(1.5em + 1rem) !important;
        }

        .pagination-container .pagination {
            margin-bottom: 0;
        }

        .page-item .page-link {
            border-radius: var(--admin-radius-md) !important;
            margin: 0 3px;
            border-color: var(--admin-border-color);
            color: var(--admin-text-secondary);
            background-color: var(--admin-card-bg);
            transition: var(--admin-transition);
            font-size: 0.9rem;
        }

        .page-item .page-link:hover {
            background-color: var(--admin-bg);
            border-color: #cbd5e1;
            color: var(--admin-text);
        }

        .page-item.active .page-link {
            background-color: var(--admin-primary);
            border-color: var(--admin-primary);
            color: white;
            box-shadow: 0 2px 5px rgba(79, 70, 229, 0.3);
        }

        .page-item.disabled .page-link {
            background-color: var(--admin-bg);
            border-color: var(--admin-border-color);
            color: #cbd5e1;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header'); ?>
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <i class="fas fa-bed fa-lg me-2" style="color: var(--admin-primary, #4f46e5);"></i>
                <div>
                    <h4 class="content-title mb-0 my-auto"><?php echo e(trans('AdminSb.facilities_management')); ?></h4>
                    <span class="text-muted mt-0 tx-13">/ <?php echo e(trans('AdminSb.beds_list')); ?></span>
                </div>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <a href="<?php echo e(route('admin.beds.create')); ?>" class="btn btn-primary ripple">
                <i class="fas fa-plus me-1"></i> <?php echo e(trans('AdminSb.add_new_bed')); ?>

            </a>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('Dashboard.messages_alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="card mb-4 animate__animated animate__fadeIn">
        <div class="card-header pb-0">
            <h5 class="card-title mb-0"><i class="fas fa-filter me-2"></i><?php echo e(trans('AdminSb.filter_beds_search')); ?></h5>
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('admin.beds.index')); ?>" method="GET">
                <div class="row g-3">
                    <div class="col-md-2">
                        <label for="search_bed" class="form-label"><?php echo e(trans('AdminSb.bed_number')); ?></label>
                        <input type="text" name="search_bed" id="search_bed" class="form-control"
                            placeholder="<?php echo e(trans('AdminSb.number_placeholder')); ?>" value="<?php echo e($request->search_bed); ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="section_id_filter" class="form-label"><?php echo e(trans('AdminSb.department')); ?></label>
                        <select name="section_id_filter" id="section_id_filter" class="form-select select2"
                            data-placeholder="<?php echo e(trans('AdminSb.all')); ?>">
                            <option value=""><?php echo e(trans('AdminSb.all')); ?></option>
                            <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($section->id); ?>"
                                    <?php echo e($request->section_id_filter == $section->id ? 'selected' : ''); ?>>
                                    <?php echo e($section->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="room_id_filter" class="form-label"><?php echo e(trans('AdminSb.room')); ?></label>
                        <select name="room_id_filter" id="room_id_filter" class="form-select select2"
                            data-placeholder="<?php echo e(trans('AdminSb.all')); ?>">
                            <option value=""><?php echo e(trans('AdminSb.all')); ?></option>
                            <?php $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($room->id); ?>"
                                    <?php echo e($request->room_id_filter == $room->id ? 'selected' : ''); ?>><?php echo e($room->room_number); ?>

                                    <?php if($room->section): ?>
                                        (<?php echo e($room->section->name ?? ''); ?>)
                                    <?php endif; ?>
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="bed_type_filter" class="form-label"><?php echo e(trans('AdminSb.bed_type')); ?></label>
                        <select name="bed_type_filter" id="bed_type_filter" class="form-select select2"
                            data-placeholder="<?php echo e(trans('AdminSb.all')); ?>">
                            <option value=""><?php echo e(trans('AdminSb.all')); ?></option>
                            <?php $__currentLoopData = $bedTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($key); ?>"
                                    <?php echo e($request->bed_type_filter == $key ? 'selected' : ''); ?>><?php echo e($value); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="bed_status_filter" class="form-label"><?php echo e(trans('AdminSb.bed_status')); ?></label>
                        <select name="bed_status_filter" id="bed_status_filter" class="form-select select2"
                            data-placeholder="<?php echo e(trans('AdminSb.all')); ?>">
                            <option value=""><?php echo e(trans('AdminSb.all')); ?></option>
                            <?php $__currentLoopData = $bedStatuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($key); ?>"
                                    <?php echo e($request->bed_status_filter == $key ? 'selected' : ''); ?>><?php echo e($value); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search me-1"></i>
                            <?php echo e(trans('AdminSb.apply_filter')); ?></button>
                        <?php if(request()->hasAny(['search_bed', 'section_id_filter', 'room_id_filter', 'bed_type_filter', 'bed_status_filter'])): ?>
                            <a href="<?php echo e(route('admin.beds.index')); ?>" class="btn btn-outline-secondary"><i
                                    class="fas fa-eraser me-1"></i> <?php echo e(trans('AdminSb.clear_filter')); ?></a>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0"><i class="fas fa-procedures me-2"></i><?php echo e(trans('AdminSb.registered_beds_list')); ?>

                (<?php echo e($beds->total()); ?>)</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th><?php echo e(trans('AdminSb.bed_number')); ?></th>
                            <th><?php echo e(trans('AdminSb.room_department')); ?></th>
                            <th><?php echo e(trans('AdminSb.bed_type')); ?></th>
                            <th><?php echo e(trans('AdminSb.status')); ?></th>
                            <th><?php echo e(trans('AdminSb.current_patient')); ?></th>
                            <th class="text-center"><?php echo e(trans('AdminSb.actions')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $beds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $bed): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($beds->firstItem() + $index); ?></td>
                                <td class="fw-bold"><?php echo e($bed->bed_number); ?></td>
                                <td>
                                    <?php if($bed->room): ?>
                                        <a
                                            href="<?php echo e(route('admin.rooms.show', $bed->room_id)); ?>"><?php echo e($bed->room->room_number); ?></a>
                                        <?php if($bed->room->section): ?>
                                            <small
                                                class="text-muted d-block">(<?php echo e($bed->room->section->name ?? trans('AdminSb.unspecified_department')); ?>)</small>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted"><?php echo e(trans('AdminSb.unspecified_room')); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($bedTypes[$bed->type] ?? $bed->type); ?></td>
                                <td>
                                    <span
                                        class="badge <?php echo e($bed->status === 'available' ? 'bg-success-soft' : 'bg-danger-soft'); ?>">
                                        <i
                                            class="fas <?php echo e($bed->status === 'available' ? 'fa-check-circle' : 'fa-user-times'); ?> me-1"></i>
                                        <?php echo e($bedStatuses[$bed->status] ?? $bed->status); ?>

                                    </span>
                                </td>
                                <td>
                                    <?php if($bed->currentAdmission && $bed->currentAdmission->patient): ?>
                                        <a
                                            href="<?php echo e(route('admin.Patients.show', $bed->currentAdmission->patient->id)); ?>"><?php echo e($bed->currentAdmission->patient->name); ?></a>
                                        <small class="text-muted d-block">ID:
                                            <?php echo e($bed->currentAdmission->patient->id); ?></small>
                                    <?php else: ?>
                                        <span class="text-muted"><?php echo e(trans('AdminSb.empty_bed')); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center action-buttons">
                                    <a href="<?php echo e(route('admin.beds.show', $bed->id)); ?>"
                                        class="btn btn-sm btn-outline-success"
                                        title="<?php echo e(trans('AdminSb.view_details')); ?>"><i class="fas fa-eye"></i></a>
                                    <a href="<?php echo e(route('admin.beds.edit', $bed->id)); ?>"
                                        class="btn btn-sm btn-outline-primary" title="<?php echo e(trans('AdminSb.edit_bed')); ?>"><i
                                            class="fas fa-edit"></i></a>
                                    <form action="<?php echo e(route('admin.beds.destroy', $bed->id)); ?>" method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('<?php echo e(trans('AdminSb.confirm_delete_bed')); ?>');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                            title="<?php echo e(trans('AdminSb.delete_bed')); ?>"><i
                                                class="fas fa-trash-alt"></i></button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-bed-pulse fa-2x text-muted mb-2"></i><br>
                                    <?php echo e(trans('AdminSb.no_beds_registered')); ?>

                                    <?php if(!request()->hasAny(['search_bed', 'section_id_filter', 'room_id_filter', 'bed_type_filter', 'bed_status_filter'])): ?>
                                        <br> <a href="<?php echo e(url('/admin/beds/create')); ?>"
                                            class="btn btn-primary btn-sm mt-2"><i class="fas fa-plus"></i>
                                            <?php echo e(trans('AdminSb.add_new_bed_now')); ?></a>
                                    <?php else: ?>
                                        <br> <span class="text-muted"><?php echo e(trans('AdminSb.try_adjusting_filters')); ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if($beds->hasPages()): ?>
                <div class="mt-3 d-flex justify-content-center pagination-container">
                    <?php echo e($beds->links('pagination::bootstrap')); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <?php echo \Illuminate\View\Factory::parentPlaceholder('js'); ?>
    <script src="<?php echo e(URL::asset('Dashboard/plugins/select2/js/select2.full.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('Dashboard/plugins/select2/js/i18n/ar.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('Dashboard/plugins/notify/js/notifIt.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('Dashboard/plugins/notify/js/notifit-custom.js')); ?>"></script>

    <script>
        $(document).ready(function() {
            // تهيئة Select2
            $('.select2').select2({
                placeholder: $(this).data('placeholder') || "اختر...",
                width: '100%',
                dir: "rtl",
                theme: "bootstrap-5",
                allowClear: true,
                // (اختياري) لمنع إغلاق القائمة المنسدلة عند الاختيار إذا كانت داخل مودال أو مكان آخر
                // dropdownParent: $(this).closest('.card-body') // أو أي عنصر أب مناسب
            });

            // إظهار رسائل NotifIt للنجاح أو الخطأ
            <?php if(session('success')): ?>
                notif({
                    msg: "<i class='fas fa-check-circle me-2'></i> <?php echo e(session('success')); ?>",
                    type: "success",
                    position: "bottom",
                    autohide: true,
                    timeout: 5000
                });
            <?php endif; ?>
            <?php if(session('error')): ?>
                notif({
                    msg: "<i class='fas fa-exclamation-triangle me-2'></i> <?php echo e(session('error')); ?>",
                    type: "error",
                    position: "bottom",
                    autohide: true,
                    timeout: 7000
                });
            <?php endif; ?>
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Dashboard.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/Beds/index.blade.php ENDPATH**/ ?>