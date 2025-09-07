 

<?php $__env->startSection('title', 'إدارة الغرف'); ?>

<?php $__env->startSection('css'); ?>
    <?php echo \Illuminate\View\Factory::parentPlaceholder('css'); ?> 
    
    <link href="<?php echo e(URL::asset('Dashboard/plugins/notify/css/notifIt.css')); ?>" rel="stylesheet" />
    
    <link href="<?php echo e(URL::asset('Dashboard/plugins/select2/css/select2.min.css')); ?>" rel="stylesheet" />
    
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <style>
        /* يمكنك استخدام نفس متغيرات CSS التي استخدمتها في صفحات المستخدمين لتوحيد المظهر */
        :root {
            --admin-primary: #4f46e5;
            /* بنفسجي جذاب */
            --admin-secondary: #10b981;
            /* أخضر زمردي */
            --admin-bg: #f8f9fc;
            --admin-card-bg: #ffffff;
            --admin-text: #111827;
            --admin-text-secondary: #6b7280;
            --admin-border-color: #e5e7eb;
            --admin-success: #22c55e;
            --admin-danger: #ef4444;
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
                --admin-secondary: #34d399;
            }

            .table thead th {
                background-color: #2d3748;
                border-color: #4b5563;
            }

            .card {
                border-color: var(--admin-border-color);
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
            /* أو var(--admin-card-bg) */
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
        }

        .bg-success-soft {
            background-color: rgba(34, 197, 94, 0.1);
            color: #166534;
        }

        /* أخضر ناعم */
        .bg-warning-soft {
            background-color: rgba(245, 158, 11, 0.1);
            color: #92400e;
        }

        /* أصفر ناعم */
        .bg-danger-soft {
            background-color: rgba(239, 68, 68, 0.1);
            color: #991b1b;
        }

        /* أحمر ناعم */
        .bg-info-soft {
            background-color: rgba(59, 130, 246, 0.1);
            color: #1d4ed8;
        }

        /* أزرق معلومات ناعم */

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
            /* تعديل padding ليناسب حجم الحقل */
            height: calc(1.5em + 1rem + 2px) !important;
            /* تعديل الارتفاع */
        }

        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
            height: calc(1.5em + 1rem) !important;
            /* تعديل ارتفاع السهم */
        }

        /* ... (يمكنك إضافة المزيد من الأنماط لتحسين شكل الفلاتر والجدول) ... */
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header'); ?>
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <i class="fas fa-hospital-user fa-lg me-2" style="color: var(--admin-primary);"></i>
                <div>
                    <h4 class="content-title mb-0 my-auto"><?php echo e(trans('AdminSb.facilities_management')); ?></h4>
                    <span class="text-muted mt-0 tx-13">/ <?php echo e(trans('AdminSb.rooms_list')); ?></span>
                </div>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <a href="<?php echo e(route('admin.rooms.create')); ?>" class="btn btn-primary ripple">
                <i class="fas fa-plus me-1"></i> <?php echo e(trans('AdminSb.add_new_room')); ?>

            </a>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('Dashboard.messages_alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="card animate__animated animate__fadeIn">
        <div class="card-header pb-0">
            <h5 class="card-title mb-0"><i class="fas fa-filter me-2"></i><?php echo e(trans('AdminSb.filter_rooms_search')); ?></h5>
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('admin.rooms.index')); ?>" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="search_room" class="form-label"><?php echo e(trans('AdminSb.room_number_name')); ?></label>
                        <input type="text" name="search_room" id="search_room" class="form-control"
                            placeholder="<?php echo e(trans('AdminSb.enter_room_number_placeholder')); ?>"
                            value="<?php echo e($request->search_room); ?>">
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
                        <label for="room_type_filter" class="form-label"><?php echo e(trans('AdminSb.room_type')); ?></label>
                        <select name="room_type_filter" id="room_type_filter" class="form-select select2"
                            data-placeholder="<?php echo e(trans('AdminSb.all')); ?>">
                            <option value=""><?php echo e(trans('AdminSb.all')); ?></option>
                            <?php $__currentLoopData = $roomTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($key); ?>"
                                    <?php echo e($request->room_type_filter == $key ? 'selected' : ''); ?>><?php echo e($value); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="room_status_filter" class="form-label"><?php echo e(trans('AdminSb.room_status')); ?></label>
                        <select name="room_status_filter" id="room_status_filter" class="form-select select2"
                            data-placeholder="<?php echo e(trans('AdminSb.all')); ?>">
                            <option value=""><?php echo e(trans('AdminSb.all')); ?></option>
                            <?php $__currentLoopData = $roomStatuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($key); ?>"
                                    <?php echo e($request->room_status_filter == $key ? 'selected' : ''); ?>><?php echo e($value); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search me-1"></i>
                            <?php echo e(trans('AdminSb.apply_filter')); ?></button>
                        <?php if(request()->hasAny(['search_room', 'section_id_filter', 'room_type_filter', 'room_status_filter'])): ?>
                            <a href="<?php echo e(route('admin.rooms.index')); ?>" class="btn btn-outline-secondary"><i
                                    class="fas fa-eraser me-1"></i> <?php echo e(trans('AdminSb.clear_filter')); ?></a>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0"><i class="fas fa-door-open me-2"></i><?php echo e(trans('AdminSb.registered_rooms_list')); ?>

                (<?php echo e($rooms->total()); ?>)</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th><?php echo e(trans('AdminSb.room_number')); ?></th>
                            <th><?php echo e(trans('AdminSb.department')); ?></th>
                            <th><?php echo e(trans('AdminSb.type')); ?></th>
                            <th><?php echo e(trans('AdminSb.gender_allocation')); ?></th>
                            <th><?php echo e(trans('AdminSb.floor')); ?></th>
                            <th><?php echo e(trans('AdminSb.status')); ?></th>
                            <th><?php echo e(trans('AdminSb.beds_count')); ?></th>
                            <th><?php echo e(trans('AdminSb.actions')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($rooms->firstItem() + $index); ?></td>
                                <td class="fw-bold"><?php echo e($room->room_number); ?></td>
                                <td><?php echo e($room->section->name ?? 'N/A'); ?></td>
                                <td><?php echo e($roomTypes[$room->type] ?? $room->type); ?></td>
                                <td>
                                    <?php
                                        $genderTypeDisplay = match ($room->gender_type) {
                                            'male' => trans('AdminSb.males_only'),
                                            'female' => trans('AdminSb.females_only'),
                                            'mixed' => trans('AdminSb.mixed'),
                                            'any' => trans('AdminSb.any_gender'),
                                            default => $room->gender_type,
                                        };
                                    ?>
                                    <?php echo e($genderTypeDisplay); ?>

                                </td>
                                <td><?php echo e($room->floor ?? '-'); ?></td>
                                <td>
                                    <?php
                                        $statusClass = match ($room->status) {
                                            'available' => 'bg-success-soft',
                                            'partially_occupied' => 'bg-warning-soft',
                                            'fully_occupied' => 'bg-danger-soft',
                                            'out_of_service' => 'bg-secondary text-white',
                                            default => 'bg-light text-dark',
                                        };
                                    ?>
                                    <span
                                        class="badge <?php echo e($statusClass); ?>"><?php echo e($roomStatuses[$room->status] ?? $room->status); ?></span>
                                </td>
                                <td><?php echo e($room->beds_count ?? $room->beds->count()); ?></td>
                                <td class="action-buttons">
                                    <a href="<?php echo e(route('admin.rooms.show', $room->id)); ?>"
                                        class="btn btn-sm btn-outline-success"
                                        title="<?php echo e(trans('AdminSb.view_details')); ?>"><i class="fas fa-eye"></i></a>
                                    <a href="<?php echo e(route('admin.rooms.edit', $room->id)); ?>"
                                        class="btn btn-sm btn-outline-primary" title="<?php echo e(trans('AdminSb.edit_room')); ?>"><i
                                            class="fas fa-edit"></i></a>
                                    <a href="<?php echo e(route('admin.beds.index', ['room_id_filter' => $room->id])); ?>"
                                        class="btn btn-sm btn-outline-secondary"
                                        title="<?php echo e(trans('AdminSb.manage_room_beds')); ?>"><i class="fas fa-bed"></i></a>
                                    <form action="<?php echo e(route('admin.rooms.destroy', $room->id)); ?>" method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('<?php echo e(trans('AdminSb.confirm_delete_room')); ?>');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                            title="<?php echo e(trans('AdminSb.delete_room')); ?>"><i
                                                class="fas fa-trash-alt"></i></button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <i class="fas fa-info-circle fa-2x text-muted mb-2"></i><br>
                                    <?php echo e(trans('AdminSb.no_rooms_registered')); ?>

                                    <?php if(!request()->hasAny(['search_room', 'section_id_filter', 'room_type_filter', 'room_status_filter'])): ?>
                                        <br> <a href="<?php echo e(route('admin.rooms.create')); ?>"
                                            class="btn btn-primary btn-sm mt-2"><i class="fas fa-plus"></i>
                                            <?php echo e(trans('AdminSb.add_new_room')); ?></a>
                                    <?php else: ?>
                                        <br> <span class="text-muted"><?php echo e(trans('AdminSb.try_adjusting_filters')); ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if($rooms->hasPages()): ?>
                <div class="mt-3 d-flex justify-content-center">
                    <?php echo e($rooms->links('pagination::bootstrap-5')); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <?php echo \Illuminate\View\Factory::parentPlaceholder('js'); ?>
    <script src="<?php echo e(URL::asset('Dashboard/plugins/notify/js/notifIt.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('Dashboard/plugins/notify/js/notifit-custom.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('Dashboard/plugins/select2/js/select2.full.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('Dashboard/plugins/select2/js/i18n/ar.js')); ?>"></script> 

    <script>
        $(document).ready(function() {
            // تهيئة Select2
            $('.select2').select2({
                placeholder: $(this).data('placeholder') || "اختر...",
                width: '100%',
                dir: "rtl",
                theme: "bootstrap-5", // تطبيق ثيم Bootstrap 5
                allowClear: true // للسماح بإلغاء الاختيار
            });

            // عرض رسائل NotifIt
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

<?php echo $__env->make('Dashboard.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/Rooms/index.blade.php ENDPATH**/ ?>