<?php $__env->startSection('title', 'تفاصيل السرير: ' . $bed->bed_number . ' (الغرفة: ' . ($bed->room->room_number ?? 'N/A') . ')'); ?>

<?php $__env->startSection('css'); ?>
    <?php echo \Illuminate\View\Factory::parentPlaceholder('css'); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <style>
        /* ... (استخدم نفس متغيرات CSS الرائعة التي صممتها لتوحيد المظهر) ... */
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
            --admin-radius-lg: 0.75rem;
            --admin-radius-xl: 1rem;
            --admin-shadow-lg: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            --admin-transition: all 0.3s ease-in-out;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                /* ... (أنماط الوضع الداكن) ... */
            }

            .info-card-bed,
            .patient-info-card {
                background-color: #2d3748;
                border-color: var(--admin-border-color);
            }

            .info-card-bed strong,
            .patient-info-card .patient-name {
                color: var(--admin-text) !important;
            }
        }

        body {
            background-color: var(--admin-bg);
            font-family: 'Tajawal', sans-serif;
            color: var(--admin-text);
        }

        .bed-details-page {
            padding-top: 1.5rem;
            padding-bottom: 3rem;
        }

        .main-details-card {
            background-color: var(--admin-card-bg);
            border-radius: var(--admin-radius-xl);
            box-shadow: var(--admin-shadow-lg);
            border: 1px solid var(--admin-border-color);
            overflow: hidden;
        }

        .main-details-header {
            padding: 1.5rem 2rem;
            background: linear-gradient(135deg, var(--admin-primary), var(--admin-info));
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .main-details-header h3 {
            margin: 0;
            font-size: 1.6rem;
            font-weight: 700;
        }

        .main-details-header .status-indicator {
            font-size: 1rem;
            padding: 0.5em 1em;
            border-radius: 50px;
        }

        .main-details-body {
            padding: 2rem;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 1.5rem;
        }

        .info-card-bed {
            background-color: var(--admin-bg);
            padding: 1.25rem;
            border-radius: var(--admin-radius-md);
            border-left: 5px solid var(--admin-secondary);
            transition: var(--admin-transition);
        }

        .info-card-bed:hover {
            transform: translateY(-3px);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.07);
        }

        .info-card-bed .info-label {
            display: block;
            font-size: 0.85rem;
            color: var(--admin-text-secondary);
            margin-bottom: 0.4rem;
            font-weight: 500;
        }

        .info-card-bed .info-value {
            font-size: 1.1rem;
            color: var(--admin-text);
            font-weight: 600;
            word-break: break-word;
        }

        .info-card-bed .info-value i {
            margin-left: 0.5rem;
            color: var(--admin-secondary);
            width: 1.2em;
        }

        .info-card-bed .info-value a {
            color: var(--admin-primary);
            text-decoration: none;
        }

        .info-card-bed .info-value a:hover {
            text-decoration: underline;
            color: var(--admin-primary-dark);
        }


        .section-divider-page {
            margin: 2.5rem 0;
            border-top: 1px dashed var(--admin-border-color);
        }

        .sub-section-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--admin-primary);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
        }

        .sub-section-title i {
            margin-left: 0.75rem;
            font-size: 1.1em;
        }

        .patient-info-card {
            background-color: var(--admin-card-bg);
            border: 1px solid var(--admin-border-color);
            border-radius: var(--admin-radius-md);
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            box-shadow: var(--admin-shadow);
        }

        .patient-avatar {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--admin-primary);
        }

        .patient-details .patient-name {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--admin-text);
            margin-bottom: 0.25rem;
        }

        .patient-details .patient-meta {
            font-size: 0.9rem;
            color: var(--admin-text-secondary);
        }

        .patient-details .patient-meta span:not(:last-child)::after {
            content: ' | ';
            margin: 0 0.5rem;
        }

        .no-patient-info {
            text-align: center;
            padding: 2rem;
            background-color: var(--admin-bg);
            border-radius: var(--admin-radius-md);
            color: var(--admin-text-secondary);
        }

        .no-patient-info i {
            font-size: 2.5rem;
            display: block;
            margin-bottom: 1rem;
            opacity: 0.6;
        }

        .action-buttons-footer {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--admin-border-color);
            text-align: center;
        }

        .btn-action-footer {
            padding: 0.75rem 1.5rem;
            border-radius: var(--admin-radius-md);
            font-weight: 600;
            margin: 0 0.5rem;
            text-decoration: none;
        }

        .bg-success-soft {
            background-color: rgba(34, 197, 94, 0.15) !important;
            color: #166534 !important;
        }

        .bg-danger-soft {
            background-color: rgba(239, 68, 68, 0.15) !important;
            color: #991b1b !important;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header'); ?>
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <i class="fas fa-bed fa-lg me-2" style="color: var(--admin-primary);"></i>
                <div>
                    <h4 class="content-title mb-0 my-auto"><?php echo e(trans('AdminSb.beds_management')); ?></h4>
                    <span class="text-muted mt-0 tx-13">/ <?php echo e(trans('AdminSb.bed_details_colon')); ?>

                        <?php echo e($bed->bed_number); ?></span>
                </div>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <a href="<?php echo e(route('admin.beds.edit', $bed->id)); ?>" class="btn btn-outline-primary btn-sm me-2"
                style="border-radius: var(--admin-radius-md);">
                <i class="fas fa-edit me-1"></i> <?php echo e(trans('AdminSb.edit_bed')); ?>

            </a>
            <a href="<?php echo e(route('admin.beds.index')); ?>" class="btn btn-outline-secondary btn-sm"
                style="border-radius: var(--admin-radius-md);">
                <i class="fas fa-arrow-left me-1"></i> <?php echo e(trans('AdminSb.back_to_beds_list')); ?>

            </a>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('Dashboard.messages_alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="bed-details-page">
        <div class="main-details-card animate__animated animate__fadeInUp">
            <div class="main-details-header">
                <h3><i class="fas fa-bed"></i> <?php echo e(trans('AdminSb.bed_number_colon')); ?> <?php echo e($bed->bed_number); ?></h3>
                <?php
                    $statusClass = $bed->status === 'available' ? 'bg-success-soft' : 'bg-danger-soft';
                ?>
                <span class="badge status-indicator <?php echo e($statusClass); ?>"><?php echo e($bedStatusDisplay); ?></span>
            </div>

            <div class="main-details-body">
                <h5 class="sub-section-title"><i class="fas fa-info-circle"></i>
                    <?php echo e(trans('AdminSb.basic_bed_information')); ?></h5>
                <div class="details-grid">
                    <div class="info-card-bed">
                        <span class="info-label"><i class="fas fa-door-closed"></i><?php echo e(trans('AdminSb.room')); ?></span>
                        <strong class="info-value">
                            <?php if($bed->room): ?>
                                <a href="<?php echo e(route('admin.rooms.show', $bed->room_id)); ?>"><?php echo e($bed->room->room_number); ?></a>
                            <?php else: ?>
                                <?php echo e(trans('AdminSb.unspecified')); ?>

                            <?php endif; ?>
                        </strong>
                    </div>
                    <div class="info-card-bed">
                        <span class="info-label"><i
                                class="fas fa-hospital-symbol"></i><?php echo e(trans('AdminSb.department')); ?></span>
                        <strong class="info-value"><?php echo e($bed->room->section->name ?? trans('AdminSb.unspecified')); ?></strong>
                    </div>
                    <div class="info-card-bed">
                        <span class="info-label"><i class="fas fa-bed-pulse"></i><?php echo e(trans('AdminSb.bed_type')); ?></span>
                        <strong class="info-value"><?php echo e($bedTypeDisplay); ?></strong>
                    </div>
                    <div class="info-card-bed">
                        <span class="info-label"><i
                                class="fas fa-check-circle"></i><?php echo e(trans('AdminSb.current_bed_status')); ?></span>
                        <strong class="info-value"><?php echo e($bedStatusDisplay); ?></strong>
                    </div>
                    <div class="info-card-bed">
                        <span class="info-label"><i
                                class="far fa-calendar-plus"></i><?php echo e(trans('AdminSb.bed_creation_date')); ?></span>
                        <strong class="info-value"><?php echo e($bed->created_at->translatedFormat('d M Y, h:i A')); ?></strong>
                    </div>
                    <div class="info-card-bed">
                        <span class="info-label"><i
                                class="far fa-calendar-check"></i><?php echo e(trans('AdminSb.last_bed_update')); ?></span>
                        <strong class="info-value"><?php echo e($bed->updated_at->translatedFormat('d M Y, h:i A')); ?></strong>
                    </div>
                </div>

                <hr class="section-divider-page">

                <h5 class="sub-section-title"><i class="fas fa-user-injured"></i>
                    <?php echo e(trans('AdminSb.current_patient_information')); ?></h5>
                <?php if($bed->currentAdmission && $bed->currentAdmission->patient): ?>
                    <?php $currentPatient = $bed->currentAdmission->patient; ?>
                    <div class="patient-info-card animate__animated animate__fadeIn">
                        <img src="<?php echo e($currentPatient->image ? asset('Dashboard/img/patients/' . $currentPatient->image->filename) : asset('Dashboard/img/default_patient_avatar.png')); ?>"
                            alt="<?php echo e($currentPatient->name); ?>" class="patient-avatar">
                        <div class="patient-details">
                            <h4 class="patient-name"><?php echo e($currentPatient->name); ?></h4>
                            <div class="patient-meta">
                                <span><i class="fas fa-id-card"></i> <?php echo e($currentPatient->national_id); ?></span>
                                <span><i class="fas fa-envelope"></i> <?php echo e($currentPatient->email); ?></span>
                                <span><i class="fas fa-phone"></i> <?php echo e($currentPatient->Phone); ?></span>
                            </div>
                            <hr class="my-2">
                            <p class="mb-1"><strong><?php echo e(trans('AdminSb.admission_date_colon')); ?></strong>
                                <?php echo e($bed->currentAdmission->admission_date->translatedFormat('d M Y, h:i A')); ?></p>
                            <?php if($bed->currentAdmission->doctor): ?>
                                <p class="mb-0"><strong><?php echo e(trans('AdminSb.responsible_doctor_colon')); ?></strong>
                                    <?php echo e($bed->currentAdmission->doctor->name); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="no-patient-info">
                        <i class="fas fa-user-alt-slash"></i>
                        <p><?php echo e(trans('AdminSb.bed_not_currently_occupied')); ?></p>
                    </div>
                <?php endif; ?>

                <div class="action-buttons-footer">
                    <a href="<?php echo e(route('admin.beds.edit', $bed->id)); ?>"
                        class="btn btn-primary btn-action-footer ripple-effect">
                        <i class="fas fa-edit me-2"></i> <?php echo e(trans('AdminSb.edit_bed_data')); ?>

                    </a>
                    <a href="<?php echo e(route('admin.beds.index')); ?>" class="btn btn-secondary btn-action-footer ripple-effect">
                        <i class="fas fa-arrow-left me-2"></i> <?php echo e(trans('AdminSb.back_to_beds_list')); ?>

                    </a>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <?php echo \Illuminate\View\Factory::parentPlaceholder('js'); ?>
    
    <script src="<?php echo e(URL::asset('Dashboard/plugins/notify/js/notifIt.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('Dashboard/plugins/notify/js/notifit-custom.js')); ?>"></script>
    <script>
        $(document).ready(function() {
            console.log("Bed details page loaded for Bed ID: <?php echo e($bed->id); ?>");

            // عرض رسائل NotifIt (إذا تم إعادة التوجيه إليها مع رسالة)
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

<?php echo $__env->make('Dashboard.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/Beds/show.blade.php ENDPATH**/ ?>