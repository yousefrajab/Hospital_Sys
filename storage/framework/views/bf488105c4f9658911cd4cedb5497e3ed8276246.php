<?php $__env->startSection('title'); ?>
    ملف المريض: <?php echo e($patient->name ?? 'مريض غير محدد'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <?php echo \Illuminate\View\Factory::parentPlaceholder('css'); ?>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="<?php echo e(URL::asset('dashboard/plugins/notify/css/notifIt.css')); ?>" rel="stylesheet" />

    
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css" rel="stylesheet" />

    <style>
        :root {
            --bs-primary-rgb: 78, 115, 223;
            --bs-primary: rgb(var(--bs-primary-rgb));
            --bs-secondary-rgb: 108, 117, 125;
            --bs-success-rgb: 25, 135, 84;
            --bs-success: rgb(var(--bs-success-rgb));
            --bs-info-rgb: 13, 202, 240;
            --bs-info: rgb(var(--bs-info-rgb));
            --bs-warning-rgb: 246, 194, 62;
            --bs-warning: rgb(var(--bs-warning-rgb));
            --bs-danger-rgb: 220, 53, 69;
            --bs-danger: rgb(var(--bs-danger-rgb));
            --bs-light-rgb: 248, 249, 252;
            --bs-dark-rgb: 33, 37, 41;
            --bs-body-bg: #f4f6f9;
            --bs-border-color: #e3e6f0;
            --bs-card-border-radius: 0.75rem;
            --bs-card-box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            --bs-body-color: #5a5c69;
        }

        html[dir="rtl"] body {
            font-family: 'Tajawal', sans-serif;
        }

        body {
            background-color: var(--bs-body-bg);
            color: var(--bs-body-color);
            line-height: 1.7;
        }

        .profile-header-card {
            background: linear-gradient(45deg, rgba(var(--bs-primary-rgb), 0.9), rgba(var(--bs-primary-rgb), 0.7)), url('https://images.unsplash.com/photo-1579684385127-6c1793094790?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center center;
            color: white;
            border-radius: var(--bs-card-border-radius);
            box-shadow: var(--bs-card-box-shadow);
            padding: 2.5rem 2rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .profile-header-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(var(--bs-dark-rgb), 0.4);
            z-index: 0;
        }

        .profile-header-content {
            position: relative;
            z-index: 1;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 5px solid rgba(255, 255, 255, 0.85);
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.25);
            object-fit: cover;
        }

        html[dir="rtl"] .profile-avatar.me-md-4 {
            margin-right: 0 !important;
            margin-left: 1.5rem !important;
        }

        .profile-name {
            font-size: 2.1rem;
            font-weight: 700;
            margin-bottom: 0.2rem;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
        }

        .profile-id {
            font-size: 0.95rem;
            opacity: 0.85;
            margin-bottom: 1.25rem;
        }

        html[dir="rtl"] .profile-id i.ms-2 {
            margin-left: 0.5rem !important;
            margin-right: 0 !important;
        }

        .profile-actions .btn {
            border-radius: 50px;
            padding: 0.7rem 1.5rem;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.25s ease;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        html[dir="rtl"] .profile-actions .btn i.me-2 {
            margin-right: 0 !important;
            margin-left: 0.5rem !important;
        }

        .profile-actions .btn-success:hover {
            background-color: #128a53;
            border-color: #128a53;
            transform: translateY(-2px);
        }

        .info-card {
            border-radius: var(--bs-card-border-radius);
            box-shadow: var(--bs-card-box-shadow);
            margin-bottom: 1.5rem;
            border: 1px solid var(--bs-border-color);
            background-color: #fff;
        }

        .info-card .card-header {
            background-color: var(--bs-light-rgb);
            color: var(--bs-primary);
            font-weight: 600;
            padding: 0.9rem 1.25rem;
            border-bottom: 1px solid var(--bs-border-color);
            font-size: 1.05rem;
            border-top-left-radius: var(--bs-card-border-radius);
            border-top-right-radius: var(--bs-card-border-radius);
        }

        html[dir="rtl"] .info-card .card-header i {
            margin-left: 0.6rem;
            margin-right: 0;
        }

        .info-card .list-group-item {
            border: none;
            padding: 0.75rem 1.25rem;
            font-size: 0.9rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #fff;
        }

        .info-card .list-group-item:last-child {
            border-bottom-left-radius: var(--bs-card-border-radius);
            border-bottom-right-radius: var(--bs-card-border-radius);
        }

        .info-card .list-group-item strong {
            color: rgb(var(--bs-dark-rgb));
            min-width: 100px;
            display: inline-block;
        }

        html[dir="rtl"] .info-card .list-group-item .icon-label i {
            margin-left: 0.5rem;
            margin-right: 0;
            opacity: 0.8;
            width: 20px;
            text-align: center;
            color: var(--bs-primary);
        }

        .info-card .list-group-item .icon-label i {
            margin-right: 0.5rem;
            opacity: 0.8;
            width: 20px;
            text-align: center;
            color: var(--bs-primary);
        }

        .timeline-custom {
            padding: 0;
            list-style: none;
            margin-top: 1rem;
        }

        html[dir="rtl"] .timeline-custom .timeline-item {
            padding: 0 1.75rem 1.5rem 0;
            border-right: 2px dotted var(--bs-border-color);
            border-left: 0;
        }

        html[dir="rtl"] .timeline-custom .timeline-item:last-child {
            border-right: 0;
        }

        html[dir="rtl"] .timeline-custom .timeline-badge {
            right: -10px;
            left: auto;
        }

        html[dir="rtl"] .timeline-custom .timeline-footer i {
            margin-left: 0.3rem;
            margin-right: 0;
        }

        .timeline-custom .timeline-item {
            position: relative;
            padding: 0 0 1.5rem 1.75rem;
            border-left: 2px dotted var(--bs-border-color);
        }

        .timeline-custom .timeline-item:last-child {
            border-left: 0;
            padding-bottom: 0;
        }

        .timeline-custom .timeline-badge {
            position: absolute;
            top: 2px;
            left: -10px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: var(--bs-primary);
            border: 3px solid var(--bs-light-rgb);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .timeline-custom .timeline-badge i {
            color: white;
            font-size: 0.7rem;
        }

        .timeline-custom .timeline-panel {
            background-color: var(--bs-light-rgb);
            padding: 1rem;
            border-radius: var(--bs-card-border-radius);
            border: 1px solid var(--bs-border-color);
            position: relative;
        }

        .timeline-custom .timeline-heading h6.timeline-title {
            font-weight: 600;
            color: var(--bs-primary);
            margin-bottom: 0.2rem;
            font-size: 0.95rem;
        }

        .timeline-custom .timeline-body p {
            margin-bottom: 0.4rem;
            font-size: 0.9rem;
            color: #606975;
        }

        .timeline-custom .timeline-footer {
            font-size: 0.8rem;
            color: #888;
            margin-top: 0.5rem;
        }

        .timeline-custom .timeline-footer i {
            margin-right: 0.3rem;
        }

        .nav-pills-custom .nav-link {
            color: var(--bs-body-color);
            font-weight: 500;
            padding: 0.7rem 1.1rem;
            border-radius: var(--bs-card-border-radius);
            margin: 0 0.2rem 0.5rem 0.2rem;
            border: 1px solid transparent;
            transition: all 0.2s ease;
        }

        html[dir="rtl"] .nav-pills-custom .nav-link i.me-2 {
            margin-right: 0 !important;
            margin-left: 0.5rem !important;
        }

        .nav-pills-custom .nav-link.active,
        .nav-pills-custom .show>.nav-link {
            color: #fff;
            background-color: var(--bs-primary);
            box-shadow: 0 2px 8px rgba(var(--bs-primary-rgb), 0.3);
            border-color: var(--bs-primary);
        }

        .nav-pills-custom .nav-link:not(.active):hover {
            background-color: rgba(var(--bs-primary-rgb), 0.08);
            color: var(--bs-primary);
        }

        .tab-content-custom {
            background-color: #fff;
            padding: 1.5rem;
            border: 1px solid var(--bs-border-color);
            border-top: none;
            border-radius: 0 0 var(--bs-card-border-radius) var(--bs-card-border-radius);
        }

        .table-records th {
            background-color: rgba(var(--bs-primary-rgb), 0.05);
            color: var(--bs-primary);
            font-weight: 600;
            font-size: 0.85rem;
        }

        .table-records td {
            font-size: 0.9rem;
            vertical-align: middle;
        }

        .table-records .badge {
            font-size: 0.75rem;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.3em 0.7em;
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 1rem;
        }


        .status-badge-sm {
            padding: 0.3em 0.6em;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 500;
            display: inline-block;
        }

        .status-active {
            background-color: rgba(var(--bs-danger-rgb), 0.1);
            color: rgb(var(--bs-danger-rgb));
        }

        .status-controlled {
            background-color: rgba(var(--bs-warning-rgb), 0.15);
            color: rgb(var(--bs-warning-rgb));
        }

        .status-in_remission,
        .status-in-remission {
            background-color: rgba(var(--bs-info-rgb), 0.1);
            color: rgb(var(--bs-info-rgb));
        }

        .status-resolved {
            background-color: rgba(var(--bs-success-rgb), 0.1);
            color: rgb(var(--bs-success-rgb));
        }

        .status-admitted {
            background-color: rgba(var(--bs-danger-rgb), 0.1);
            color: rgb(var(--bs-danger-rgb));
        }

        .status-discharged {
            background-color: rgba(var(--bs-success-rgb), 0.1);
            color: rgb(var(--bs-success-rgb));
        }

        .status-new {
            background-color: rgba(var(--bs-info-rgb), 0.1);
            color: rgb(var(--bs-info-rgb));
        }

        .status-approved,
        .status-dispensed {
            background-color: rgba(var(--bs-success-rgb), 0.1);
            color: var(--bs-success);
        }

        .status-partially_dispensed,
        .status-partially-dispensed {
            background-color: rgba(var(--bs-warning-rgb), 0.15);
            color: rgb(var(--bs-warning-rgb));
        }

        .status-cancelled_by_doctor,
        .status-cancelled-by-doctor,
        .status-cancelled_by_pharmacist,
        .status-cancelled-by-pharmacist,
        .status-cancelled_by_patient,
        .status-cancelled-by-patient {
            background-color: rgba(var(--bs-danger-rgb), 0.1);
            color: rgb(var(--bs-danger-rgb));
        }

        .status-on_hold,
        .status-on-hold {
            background-color: rgba(var(--bs-secondary-rgb), 0.15);
            color: rgb(var(--bs-secondary-rgb));
        }

        .status-expired {
            background-color: #6c757d26;
            color: #6c757d;
        }

        .status-pending,
        .status-قيد-التنفيذ {
            background-color: rgba(var(--bs-warning-rgb), 0.15);
            color: rgb(var(--bs-warning-rgb));
        }

        .status-completed,
        .status-مكتملة {
            background-color: rgba(var(--bs-success-rgb), 0.1);
            color: rgb(var(--bs-success-rgb));
        }

        .empty-state-compact {
            text-align: center;
            padding: 1.5rem;
            background-color: #fcfdff;
            border-radius: var(--bs-card-border-radius);
            border: 1px dashed #eef2f7;
        }

        .empty-state-compact i {
            font-size: 2.5rem;
            color: #e0e6ed;
            margin-bottom: 0.75rem;
            display: block;
        }

        .empty-state-compact p {
            color: #7a828e;
            font-size: 0.9rem;
            margin-bottom: 0;
        }

        html[dir="rtl"] .breadcrumb-header .content-title i.me-2 {
            margin-right: 0 !important;
            margin-left: 0.5rem !important;
        }

        html[dir="rtl"] .btn i.me-1 {
            margin-right: 0 !important;
            margin-left: 0.25rem !important;
        }

        html[dir="rtl"] .nav-pills-custom .nav-link i.me-2 {
            margin-right: 0 !important;
            margin-left: 0.5rem !important;
        }

        .nav-pills-custom .nav-link i.me-2 {
            margin-right: 0.5rem !important;
        }

        /* Lightbox Customizations */
        .lb-data .lb-caption {
            font-size: 0.9rem;
            color: #f0f0f0;
        }

        .lb-data .lb-number {
            font-size: 0.8rem;
            color: #ccc;
        }

        .lb-nav a.lb-prev,
        .lb-nav a.lb-next {
            opacity: 0.7;
            transition: opacity 0.2s;
        }

        .lb-nav a.lb-prev:hover,
        .lb-nav a.lb-next:hover {
            opacity: 1;
        }

        @media  print {
            body * {
                visibility: hidden;
            }

            .print-container,
            .print-container * {
                visibility: visible;
            }

            .print-container {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 20px !important;
                margin: 0 !important;
            }

            .no-print {
                display: none !important;
            }

            .profile-header-card {
                background-image: none !important;
                background-color: #eee !important;
                color: #333 !important;
                box-shadow: none !important;
                border: 1px solid #ccc !important;
            }

            .profile-header-card::before {
                display: none;
            }

            .profile-avatar {
                border-color: #ccc !important;
            }

            .info-card,
            .info-section-card {
                box-shadow: none !important;
                border: 1px solid #ccc !important;
                margin-bottom: 1rem !important;
            }

            .table-responsive {
                overflow-x: hidden !important;
            }

            table {
                width: 100% !important;
                border-collapse: collapse !important;
            }

            th,
            td {
                border: 1px solid #ddd !important;
                padding: 5px !important;
                font-size: 0.8rem !important;
            }

            .badge {
                border: 1px solid #ccc !important;
                background-color: transparent !important;
                color: #333 !important;
            }

            a[href]:after {
                content: none !important;
            }

            /* Remove URL from links in print */
            .timeline-panel {
                background-color: #f9f9f9 !important;
                border: 1px solid #ddd !important;
            }

            .tab-content-custom {
                border: none !important;
                padding: 0 !important;
            }
        }
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page-header'); ?>
    <div class="breadcrumb-header justify-content-between no-print">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto"><i
                        class="fas fa-user-injured text-primary me-2"></i><?php echo e(trans('doctors.patient_profile')); ?></h4>
                <span class="text-muted mt-1 tx-13 mx-2">/</span>
                <span class="text-muted mt-1 tx-13"><?php echo e($patient->name ?? trans('doctors.details')); ?></span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center">
            <button onclick="printSelectedPatientSections()" class="btn btn-outline-primary btn-sm ripple-effect me-2">
                <i class="fas fa-print me-1"></i> <?php echo e(trans('doctors.print_selected')); ?>

            </button>
            <a href="<?php echo e(route('doctor.patients.search_for_prescription')); ?>"
                class="btn btn-outline-secondary btn-sm ripple-effect">
                <i class="fas fa-search me-1"></i> <?php echo e(trans('doctors.search_another_patient')); ?>

            </a>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('Dashboard.messages_alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php if($patient): ?>
        <div id="printable-patient-profile"> 
            
            <div class="profile-header-card" data-aos="fade-down" id="patientProfileHeaderSection">
                <div class="profile-header-content text-center text-md-start d-md-flex align-items-center">
                    <img src="<?php echo e($patient->image ? asset('Dashboard/img/patients/' . $patient->image->filename) : URL::asset('Dashboard/img/default_patient_avatar.png')); ?>"
                        alt="<?php echo e($patient->name); ?>" class="profile-avatar mb-3 mb-md-0 me-md-4"
                        onerror="this.onerror=null; this.src='<?php echo e(URL::asset('Dashboard/img/default_patient_avatar.png')); ?>';">
                    <div class="flex-grow-1">
                        <h1 class="profile-name"><?php echo e($patient->name); ?></h1>
                        <p class="profile-id">
                            <i class="fas fa-id-card opacity-75"></i> <?php echo e(trans('doctors.id_colon')); ?> <?php echo e($patient->id); ?>

                            |
                            <i class="fas fa-venus-mars opacity-75 ms-2"></i>
                            <?php echo e($patient->Gender == 1 ? trans('AdminSb.male') : trans('AdminSb.female')); ?> |
                            <i class="fas fa-birthday-cake opacity-75 ms-2"></i>
                            <?php echo e($patient->Date_Birth ? $patient->Date_Birth->age . ' ' . trans('doctors.years_old') : '-'); ?>

                            |
                            <?php if($patient->Blood_Group): ?>
                                <i class="fas fa-tint opacity-75 ms-2"></i> <?php echo e($patient->Blood_Group); ?> |
                            <?php endif; ?>
                        </p>
                        <div class="profile-actions mt-3 no-print">
                            <a href="<?php echo e(route('prescriptions.create', ['patient_id' => $patient->id])); ?>"
                                class="btn btn-success shadow-sm">
                                <i class="fas fa-file-medical-alt me-2"></i><?php echo e(trans('doctors.create_new_prescription')); ?>

                            </a>
                        </div>
                    </div>
                    <div class="qr-code-section">
                        <?php if($patient && $patient->id): ?>
                            <div class="qr-code-image">
                                <?php echo $patient->generateQrCodeSvg(200); ?>

                            </div>
                            <p class="qr-code-instructions"><?php echo e(trans('doctors.scan_qr_for_profile')); ?></p>
                            <div class="qr-download-buttons mt-3 no-print">
                                <a href="#" class="btn btn-sm btn-outline-primary" style="color: white"
                                    id="downloadPNG">
                                    <i class="fas fa-download"></i> <?php echo e(trans('doctors.download_png')); ?>

                                </a>
                                <a href="#" class="btn btn-sm btn-outline-success" style="color: white"
                                    id="downloadSVG">
                                    <i class="fas fa-download"></i> <?php echo e(trans('doctors.download_svg')); ?>

                                </a>
                            </div>
                        <?php else: ?>
                            <p class="text-danger my-4"><?php echo e(trans('doctors.qr_code_error')); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>


            <div class="row">
                
                <div class="col-lg-5 col-xl-4" id="patientInfoAndDiseasesSection">
                    <div class="info-card card" data-aos="fade-right" data-aos-delay="100">
                        <div class="card-header"><i
                                class="fas fa-user-circle me-2"></i><?php echo e(trans('doctors.personal_information')); ?></div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><span class="icon-label"><i
                                        class="fas fa-id-badge"></i><strong><?php echo e(trans('doctors.national_id_colon')); ?></strong></span>
                                <span><?php echo e($patient->national_id ?? '-'); ?></span>
                            </li>
                            <li class="list-group-item"><span class="icon-label"><i
                                        class="fas fa-envelope"></i><strong><?php echo e(trans('doctors.email_colon')); ?></strong></span>
                                <span class="text-truncate" style="max-width: 180px;"><?php echo e($patient->email ?? '-'); ?></span>
                            </li>
                            <li class="list-group-item"><span class="icon-label"><i
                                        class="fas fa-phone"></i><strong><?php echo e(trans('AdminSb.phone')); ?></strong></span>
                                <span><?php echo e($patient->Phone ?? '-'); ?></span>
                            </li>
                            <li class="list-group-item"><span class="icon-label"><i
                                        class="fas fa-map-marker-alt"></i><strong><?php echo e(trans('doctors.address_colon')); ?></strong></span>
                                <span class="text-truncate"
                                    style="max-width: 180px;"><?php echo e($patient->Address ?: trans('doctors.unspecified')); ?></span>
                            </li>
                            <li class="list-group-item"><span class="icon-label"><i
                                        class="fas fa-calendar-plus"></i><strong><?php echo e(trans('doctors.registration_date_colon')); ?></strong></span>
                                <span><?php echo e($patient->created_at ? $patient->created_at->translatedFormat('j M Y') : '-'); ?></span>
                            </li>
                        </ul>
                    </div>

                    <div class="info-card card" data-aos="fade-right" data-aos-delay="200">
                        <div class="card-header"><i
                                class="fas fa-notes-medical text-danger me-2"></i><?php echo e(trans('doctors.chronic_diseases')); ?>

                        </div>
                        <div class="card-body p-0">
                            <?php if($patient->diagnosedChronicDiseases && $patient->diagnosedChronicDiseases->isNotEmpty()): ?>
                                <div class="table-responsive">
                                    <table class="table table-custom table-sm table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th><?php echo e(trans('doctors.disease')); ?></th>
                                                <th><?php echo e(trans('doctors.status')); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $statuses = \App\Models\PatientChronicDisease::getStatuses(); ?>
                                            <?php $__currentLoopData = $patient->diagnosedChronicDiseases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td>
                                                        <?php echo e($cd->disease->name ?? ($cd->name ?? trans('doctors.unspecified'))); ?>

                                                        <?php if($cd->pivot && $cd->pivot->notes): ?>
                                                            <small class="d-block text-muted" data-toggle="tooltip"
                                                                title="<?php echo e($cd->pivot->notes); ?>"><i
                                                                    class="fas fa-info-circle fa-xs text-info me-1"></i><?php echo e(trans('doctors.note_short')); ?></small>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if($cd->pivot && $cd->pivot->current_status): ?>
                                                            <span
                                                                class="status-badge-sm status-<?php echo e(str_replace('_', '-', $cd->pivot->current_status)); ?>"><?php echo e($statuses[$cd->pivot->current_status] ?? ucfirst(str_replace('_', ' ', $cd->pivot->current_status))); ?></span>
                                                        <?php else: ?>
                                                            -
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="empty-state-compact p-3 m-3"><i class="fas fa-shield-virus"></i>
                                    <p><?php echo e(trans('doctors.no_chronic_diseases_registered')); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if(isset($patient->initial_allergies_text) && trim($patient->initial_allergies_text) !== ''): ?>
                        <div class="info-card card" data-aos="fade-right" data-aos-delay="300">
                            <div class="card-header"><i
                                    class="fas fa-allergies text-warning me-2"></i><?php echo e(trans('doctors.registered_allergies')); ?>

                            </div>
                            <div class="card-body">
                                <p class="text-muted" style="white-space: pre-wrap; font-size: 0.9rem;">
                                    <?php echo e($patient->initial_allergies_text); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                
                <div class="col-lg-7 col-xl-8">
                    <div data-aos="fade-left" data-aos-delay="150">
                        <ul class="nav nav-pills nav-pills-custom nav-fill mb-0" id="patientRecordTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="diagnostics-tab-link" data-toggle="tab"
                                    href="#diagnosticsContent" role="tab" aria-controls="diagnosticsContent"
                                    aria-selected="true">
                                    <i class="fas fa-stethoscope me-2"></i><?php echo e(trans('doctors.diagnostics')); ?>

                                    <?php if($patient_records): ?>
                                        <span
                                            class="badge badge-pill badge-light ms-1"><?php echo e($patient_records->count()); ?></span>
                                    <?php endif; ?>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="prescriptions-tab-link" data-toggle="tab"
                                    href="#prescriptionsContent" role="tab" aria-controls="prescriptionsContent"
                                    aria-selected="false">
                                    <i class="fas fa-file-prescription me-2"></i><?php echo e(trans('doctors.prescriptions')); ?>

                                    <?php if($patient->prescriptions): ?>
                                        <span
                                            class="badge badge-pill badge-light ms-1"><?php echo e($patient->prescriptions->count()); ?></span>
                                    <?php endif; ?>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="rays-tab-link" data-toggle="tab" href="#raysContent"
                                    role="tab" aria-controls="raysContent" aria-selected="false">
                                    <i class="fas fa-x-ray me-2"></i><?php echo e(trans('doctors.radiology')); ?>

                                    <?php if($patient_rays): ?>
                                        <span
                                            class="badge badge-pill badge-light ms-1"><?php echo e($patient_rays->count()); ?></span>
                                    <?php endif; ?>
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="labs-tab-link" data-toggle="tab" href="#labsContent"
                                    role="tab" aria-controls="labsContent" aria-selected="false">
                                    <i class="fas fa-flask me-2"></i><?php echo e(trans('doctors.laboratory')); ?>

                                    <?php if($patient_Laboratories): ?>
                                        <span
                                            class="badge badge-pill badge-light ms-1"><?php echo e($patient_Laboratories->count()); ?></span>
                                    <?php endif; ?>
                                </a>
                            </li>
                            <?php if($patient->admissions && $patient->admissions->isNotEmpty()): ?>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="admissionsLogTab-link" data-toggle="tab"
                                        href="#admissionsLogContent" role="tab" aria-controls="admissionsLogContent"
                                        aria-selected="false">
                                        <i class="fas fa-procedures me-2"></i><?php echo e(trans('doctors.admissions')); ?>

                                        <span
                                            class="badge badge-pill badge-light ms-1"><?php echo e($patient->admissions->count()); ?></span>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>

                        <div class="tab-content tab-content-custom" id="patientRecordTabsContent">
                            
                            <div class="tab-pane fade show active" id="diagnosticsContent" role="tabpanel"
                                aria-labelledby="diagnostics-tab-link">
                                <div class="info-section-card mb-0">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <span><i
                                                class="fas fa-notes-medical me-2"></i><?php echo e(trans('doctors.diagnostics_and_examinations_log')); ?></span>
                                        <button class="btn btn-sm btn-outline-secondary no-print"
                                            onclick="printPatientSection(['#patientProfileHeaderSection', '#diagnosticsContent'])"><i
                                                class="fas fa-print fa-sm"></i>
                                            <?php echo e(trans('doctors.print_this_section')); ?></button>
                                    </div>
                                    <div class="card-body">
                                        <?php if(isset($patient_records) && $patient_records->count() > 0): ?>
                                            <ul class="timeline-custom">
                                                <?php $__currentLoopData = $patient_records->sortByDesc('date'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $record): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li class="timeline-item">
                                                        <div class="timeline-badge"><i
                                                                class="fas <?php echo e($loop->first ? 'fa-star text-warning' : 'fa-check-double'); ?>"></i>
                                                        </div>
                                                        <div class="timeline-panel">
                                                            <div class="timeline-heading">
                                                                <h6 class="timeline-title">
                                                                    <?php echo e(trans('doctors.examination_on_date')); ?>

                                                                    <?php echo e($record->date ? \Carbon\Carbon::parse($record->date)->translatedFormat('d M Y') : trans('doctors.unspecified')); ?>

                                                                </h6>
                                                                <small class="text-muted"><?php echo e(trans('doctors.by_doctor')); ?>

                                                                    <?php echo e($record->Doctor->name ?? trans('doctors.unspecified_doctor')); ?></small>
                                                            </div>
                                                            <div class="timeline-body">
                                                                <p class="mb-1">
                                                                    <strong><?php echo e(trans('doctors.diagnosis_colon')); ?></strong>
                                                                    <?php echo e($record->diagnosis ?: '-'); ?></p>
                                                                <?php if($record->medicine): ?>
                                                                    <p class="mb-0">
                                                                        <strong><?php echo e(trans('doctors.treatment_from_examination')); ?></strong>
                                                                        <?php echo e($record->medicine); ?></p>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>
                                        <?php else: ?>
                                            <div class="empty-state-compact"><i class="fas fa-folder-open"></i>
                                                <p><?php echo e(trans('doctors.no_previous_diagnostic_records')); ?></p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="tab-pane fade" id="prescriptionsContent" role="tabpanel"
                                aria-labelledby="prescriptions-tab-link">
                                <div class="info-section-card mb-0">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <span><i
                                                class="fas fa-file-prescription me-2"></i><?php echo e(trans('doctors.previous_prescriptions')); ?></span>
                                        <button class="btn btn-sm btn-outline-secondary no-print"
                                            onclick="printPatientSection(['#patientProfileHeaderSection', '#prescriptionsContent'])"><i
                                                class="fas fa-print fa-sm"></i>
                                            <?php echo e(trans('doctors.print_this_section')); ?></button>
                                    </div>
                                    <div class="card-body p-0">
                                        <?php if($patient->prescriptions && $patient->prescriptions->isNotEmpty()): ?>
                                            <div class="table-responsive px-3 pt-3">
                                                <table id="prescriptionsTable"
                                                    class="table table-records table-sm table-hover table-striped w-100">
                                                    <thead>
                                                        <tr>
                                                            <th><?php echo e(trans('doctors.prescription_number')); ?></th>
                                                            <th><?php echo e(trans('doctors.date')); ?></th>
                                                            <th><?php echo e(trans('doctors.Doctor')); ?></th>
                                                            <th class="text-center"><?php echo e(trans('doctors.Status')); ?></th>
                                                            <th class="text-center no-print"><?php echo e(trans('doctors.action')); ?>

                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $__currentLoopData = $patient->prescriptions->sortByDesc('prescription_date'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <tr>
                                                                <td><a href="<?php echo e(route('prescriptions.show', $pr->id)); ?>"
                                                                        data-toggle="tooltip"
                                                                        title="<?php echo e(trans('doctors.view_prescription_details_for_number', ['number' => $pr->prescription_number])); ?>"><?php echo e($pr->prescription_number); ?></a>
                                                                </td>
                                                                <td><?php echo e($pr->prescription_date ? $pr->prescription_date->translatedFormat('d M Y') : '-'); ?>

                                                                </td>
                                                                <td><?php echo e($pr->doctor->name ?? '-'); ?></td>
                                                                <td class="text-center">
                                                                    <?php
                                                                        $sKey = $pr->status ?? 'unknown';
                                                                        $sText = '';
                                                                        $sClass = 'status-unknown';
                                                                        if (
                                                                            class_exists(
                                                                                \App\Models\Prescription::class,
                                                                            ) &&
                                                                            method_exists(
                                                                                \App\Models\Prescription::class,
                                                                                'getStatusesForFilter',
                                                                            )
                                                                        ) {
                                                                            $statusesArray = \App\Models\Prescription::getStatusesForFilter();
                                                                            $sText =
                                                                                $statusesArray[$sKey] ??
                                                                                ucfirst(str_replace('_', ' ', $sKey));
                                                                        } else {
                                                                            $sText = ucfirst(
                                                                                str_replace('_', ' ', $sKey),
                                                                            );
                                                                        }
                                                                        $sClass =
                                                                            'status-' . str_replace('_', '-', $sKey);
                                                                    ?>
                                                                    <span
                                                                        class="status-badge-sm <?php echo e($sClass); ?>"><?php echo e($sText); ?></span>
                                                                </td>
                                                                <td class="text-center no-print">
                                                                    <a href="<?php echo e(route('prescriptions.show', $pr->id)); ?>"
                                                                        class="btn btn-xs btn-outline-info px-2 py-1"
                                                                        data-toggle="tooltip"
                                                                        title="<?php echo e(trans('doctors.view_prescription')); ?>"><i
                                                                            class="fas fa-eye fa-fw"></i></a>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php else: ?>
                                            <div class="empty-state-compact p-3 m-3"><i class="fas fa-pills"></i>
                                                <p><?php echo e(trans('doctors.no_previous_prescriptions_for_patient')); ?></p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="tab-pane fade" id="raysContent" role="tabpanel" aria-labelledby="rays-tab-link">
                                <div class="info-section-card mb-0">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-x-ray me-2"></i><?php echo e(trans('doctors.rays_log')); ?></span>
                                        <button class="btn btn-sm btn-outline-secondary no-print"
                                            onclick="printPatientSection(['#patientProfileHeaderSection', '#raysContent'])"><i
                                                class="fas fa-print fa-sm"></i>
                                            <?php echo e(trans('doctors.print_this_section')); ?></button>
                                    </div>
                                    <div class="card-body p-0">
                                        <?php if(isset($patient_rays) && $patient_rays->count() > 0): ?>
                                            <div class="table-responsive px-3 pt-3">
                                                <table id="raysTable"
                                                    class="table table-records table-sm table-hover table-striped w-100">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th><?php echo e(trans('doctors.description_request')); ?></th>
                                                            <th><?php echo e(trans('doctors.requesting_doctor')); ?></th>
                                                            <th><?php echo e(trans('doctors.request_date')); ?></th>
                                                            <th class="text-center"><?php echo e(trans('doctors.Status')); ?></th>
                                                            <th class="text-center no-print"><?php echo e(trans('doctors.result')); ?>

                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $__currentLoopData = $patient_rays; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $ray): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <tr>
                                                                <td><?php echo e($loop->iteration); ?></td>
                                                                <td><?php echo e(Str::limit($ray->description, 35)); ?></td>
                                                                <td><?php echo e($ray->doctor->name ?? '-'); ?></td>
                                                                <td><?php echo e($ray->created_at ? $ray->created_at->translatedFormat('d M Y, H:i A') : '-'); ?>

                                                                </td>
                                                                <td class="text-center">
                                                                    <?php if($ray->case == 1): ?>
                                                                        <span
                                                                            class="status-badge-sm status-completed"><?php echo e(trans('doctors.status_completed')); ?></span>
                                                                    <?php else: ?>
                                                                        <span
                                                                            class="status-badge-sm status-pending"><?php echo e(trans('doctors.status_pending')); ?></span>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td class="text-center no-print">
                                                                    <?php if($ray->case == 1 && isset($ray->employee_id) && $ray->image && $ray->image->filename): ?>
                                                                        <a href="<?php echo e(asset('ads/rays/' . $ray->image->filename)); ?>"
                                                                            data-lightbox="ray-images-<?php echo e($patient->id); ?>"
                                                                            data-title="<?php echo e(trans('doctors.radiology')); ?>: <?php echo e($ray->description); ?> - <?php echo e(trans('doctors.date')); ?>: <?php echo e($ray->created_at ? $ray->created_at->translatedFormat('d M Y') : ''); ?>"
                                                                            class="btn btn-xs btn-outline-info px-2 py-1"
                                                                            data-toggle="tooltip"
                                                                            title="<?php echo e(trans('doctors.view_ray_result')); ?>">
                                                                            <i class="fas fa-file-image fa-fw"></i>
                                                                        </a>
                                                                    <?php else: ?>
                                                                        -
                                                                    <?php endif; ?>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php else: ?>
                                            <div class="empty-state-compact p-3 m-3"><i class="fas fa-radiation"></i>
                                                <p><?php echo e(trans('doctors.no_previous_ray_requests')); ?></p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="tab-pane fade" id="labsContent" role="tabpanel" aria-labelledby="labs-tab-link">
                                <div class="info-section-card mb-0">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-flask-vial me-2"></i><?php echo e(trans('doctors.lab_log')); ?></span>
                                        <button class="btn btn-sm btn-outline-secondary no-print"
                                            onclick="printPatientSection(['#patientProfileHeaderSection', '#labsContent'])"><i
                                                class="fas fa-print fa-sm"></i>
                                            <?php echo e(trans('doctors.print_this_section')); ?></button>
                                    </div>
                                    <div class="card-body p-0">
                                        <?php if(isset($patient_Laboratories) && $patient_Laboratories->count() > 0): ?>
                                            <div class="table-responsive px-3 pt-3">
                                                <table id="labsTable"
                                                    class="table table-records table-sm table-hover table-striped w-100">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th><?php echo e(trans('doctors.description_request')); ?></th>
                                                            <th><?php echo e(trans('doctors.requesting_doctor')); ?></th>
                                                            <th><?php echo e(trans('doctors.request_date')); ?></th>
                                                            <th class="text-center"><?php echo e(trans('doctors.Status')); ?></th>
                                                            <th class="text-center no-print">
                                                                <?php echo e(trans('doctors.result')); ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $__currentLoopData = $patient_Laboratories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lab): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <tr>
                                                                <td><?php echo e($loop->iteration); ?></td>
                                                                <td><?php echo e(Str::limit($lab->description, 35)); ?></td>
                                                                <td><?php echo e($lab->doctor->name ?? '-'); ?></td>
                                                                <td><?php echo e($lab->created_at ? $lab->created_at->translatedFormat('d M Y, H:i A') : '-'); ?>

                                                                </td>
                                                                <td class="text-center">
                                                                    <?php if($lab->case == 1): ?>
                                                                        <span
                                                                            class="status-badge-sm status-completed"><?php echo e(trans('doctors.status_completed')); ?></span>
                                                                    <?php else: ?>
                                                                        <span
                                                                            class="status-badge-sm status-pending"><?php echo e(trans('doctors.status_pending')); ?></span>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td class="text-center no-print">
                                                                    <?php if($lab->case == 1 && isset($lab->employee_id) && $lab->image && $lab->image->filename): ?>
                                                                        <a href="<?php echo e(asset('Dashboard/img/laboratories/' . $lab->image->filename)); ?>"
                                                                            data-lightbox="lab-images-<?php echo e($patient->id); ?>"
                                                                            data-title="<?php echo e(trans('doctors.lab_test_title')); ?>: <?php echo e($lab->description); ?> - <?php echo e(trans('doctors.date')); ?>: <?php echo e($lab->created_at ? $lab->created_at->translatedFormat('d M Y') : ''); ?>"
                                                                            class="btn btn-xs btn-outline-info px-2 py-1"
                                                                            data-toggle="tooltip"
                                                                            title="<?php echo e(trans('doctors.view_lab_result')); ?>">
                                                                            <i class="fas fa-file-alt fa-fw"></i>
                                                                        </a>
                                                                    <?php else: ?>
                                                                        -
                                                                    <?php endif; ?>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php else: ?>
                                            <div class="empty-state-compact p-3 m-3"><i class="fas fa-vial-virus"></i>
                                                <p><?php echo e(trans('doctors.no_previous_lab_requests')); ?></p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            
                            <?php if($patient->admissions && $patient->admissions->isNotEmpty()): ?>
                                <div class="tab-pane fade" id="admissionsLogContent" role="tabpanel"
                                    aria-labelledby="admissionsLogTab-link">
                                    <div class="info-section-card mb-0">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <span><i
                                                    class="fas fa-procedures me-2"></i><?php echo e(trans('doctors.admissions_log')); ?></span>
                                            <button class="btn btn-sm btn-outline-secondary no-print"
                                                onclick="printPatientSection(['#patientProfileHeaderSection', '#admissionsLogContent'])"><i
                                                    class="fas fa-print fa-sm"></i>
                                                <?php echo e(trans('doctors.print_this_section')); ?></button>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="table-responsive px-3 pt-3">
                                                <table id="admissionsTable"
                                                    class="table table-records table-sm table-hover w-100">
                                                    <tr>
                                                        <th>#</th>
                                                        <th><?php echo e(trans('doctors.admission_date')); ?></th>
                                                        <th><?php echo e(trans('doctors.discharge_date')); ?></th>
                                                        <th><?php echo e(trans('doctors.section')); ?></th>
                                                        <th><?php echo e(trans('doctors.room_bed')); ?></th>
                                                        <th><?php echo e(trans('doctors.admission_reason')); ?></th>
                                                        <th><?php echo e(trans('doctors.Status')); ?></th>
                                                    </tr>

                                                    <tbody>
                                                        <?php $__currentLoopData = $patient->admissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $admission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <tr>
                                                                <td><?php echo e($loop->iteration); ?></td>
                                                                <td><?php echo e($admission->admission_date ? \Carbon\Carbon::parse($admission->admission_date)->translatedFormat('d M Y, H:i A') : '-'); ?>

                                                                </td>
                                                                <td>
                                                                    <?php if($admission->discharge_date): ?>
                                                                        <?php echo e(\Carbon\Carbon::parse($admission->discharge_date)->translatedFormat('d M Y, H:i A')); ?>

                                                                    <?php else: ?>
                                                                        <span
                                                                            class="status-badge-sm status-admitted"><?php echo e(trans('doctors.currently_admitted')); ?></span>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td>
                                                                    <?php echo e($admission->section->name); ?>

                                                                </td>
                                                                <td>
                                                                    (<?php echo e(trans('doctors.room_short')); ?>:
                                                                    <?php echo e($admission->bed->room->room_number); ?> -
                                                                    <?php echo e(trans('doctors.bed_short')); ?>:
                                                                    <?php echo e($admission->bed->bed_number); ?>)
                                                                </td>
                                                                <td><?php echo e(Str::limit($admission->reason_for_admission, 40) ?? trans('doctors.unspecified')); ?>

                                                                </td>
                                                                <td>
                                                                    <span
                                                                        class="badge badge-pill <?php echo e($admission->status == \App\Models\PatientAdmission::STATUS_ADMITTED && !$admission->discharge_date ? 'badge-success-light' : 'badge-warning-light'); ?>">
                                                                        <?php echo e(\App\Models\PatientAdmission::getAdmissionStatusText($admission->status)); ?>

                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    <?php else: ?>
        <div class="alert alert-danger text-center" data-aos="fade-in">
            <h4 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>
                <?php echo e(trans('doctors.error_displaying_data')); ?></h4>
            <p><?php echo e(trans('doctors.patient_data_not_found_error')); ?></p>
            <hr>
            <a href="<?php echo e(route('doctor.patients.search_for_prescription')); ?>" class="btn btn-outline-danger"><i
                    class="fas fa-search me-1"></i> <?php echo e(trans('doctors.return_to_patient_search')); ?></a>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <?php echo \Illuminate\View\Factory::parentPlaceholder('js'); ?>
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> 
    
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="<?php echo e(URL::asset('dashboard/plugins/notify/js/notifIt.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('dashboard/plugins/notify/js/notifit-custom.js')); ?>"></script>

    
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js">
    </script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js">
    </script>
    
    


    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>

    <script>
        // للحصول على ملف اللغة العربية لـ DataTables
        var dataTablesArabicURL =
            "//cdn.datatables.net/plug-ins/1.13.6/i18n/ar.json"; // تأكد من صحة الرابط أو استخدم ملف محلي

        $(document).ready(function() {
            console.log("Document ready. jQuery version:", $.fn.jquery);
            console.log("Bootstrap version (check console for errors if any):", ($.fn.tooltip && $.fn.tooltip
                    .Constructor) ? $.fn.tooltip.Constructor.VERSION :
                "Not Bootstrap 4/jQuery based or not loaded");


            AOS.init({
                duration: 700,
                easing: 'ease-out-cubic',
                once: true,
                offset: 50
            });

            if (typeof $('[data-toggle="tooltip"]').tooltip === 'function') {
                $('[data-toggle="tooltip"]').tooltip({
                    boundary: 'window'
                });
            }

            // Initialize DataTables
            function initDataTable(tableId) {
                if (typeof $.fn.DataTable === 'function' && $(tableId).length && !$.fn.DataTable.isDataTable(
                        tableId)) {
                    $(tableId).DataTable({
                        "language": {
                            "url": dataTablesArabicURL
                        },
                        "responsive": true,
                        "pageLength": 5,
                        "lengthMenu": [
                            [5, 10, 20, -1],
                            [5, 10, 20, "الكل"]
                        ],
                        "columnDefs": [{
                            "orderable": false,
                            "targets": -1
                        }], // آخر عمود (الإجراءات) غير قابل للفرز
                        "processing": true, // إظهار مؤشر المعالجة
                    });
                    console.log(tableId + " DataTable initialized.");
                } else if ($.fn.DataTable.isDataTable(tableId)) {
                    console.log(tableId + " DataTable already initialized.");
                } else {
                    console.warn("DataTable function not found or table " + tableId + " does not exist.");
                }
            }

            initDataTable('#prescriptionsTable');
            initDataTable('#raysTable');
            initDataTable('#labsTable');
            initDataTable('#admissionsTable');


            if (typeof lightbox !== 'undefined') {
                lightbox.option({
                    'resizeDuration': 200,
                    'wrapAround': true,
                    'fadeDuration': 300,
                    'albumLabel': "صورة %1 من %2"
                });
            }

            var tabsContainer = $('#patientRecordTabs');
            tabsContainer.find('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                var newActiveTabHref = $(e.target).attr('href');
                localStorage.setItem('activeDoctorPatientDetailTab_BS4', newActiveTabHref);

                // إعادة تهيئة أو رسم DataTables إذا كانت داخل التبويب الذي تم عرضه للتو
                // لأن DataTables قد لا تحسب عرض الأعمدة بشكل صحيح إذا كان الجدول مخفيًا عند التهيئة الأولية
                var activeTable = $(newActiveTabHref).find('table.dataTable');
                if (activeTable.length && $.fn.DataTable.isDataTable(activeTable)) {
                    // activeTable.DataTable().columns.adjust().responsive.recalc();
                    // A more robust way to redraw if it was initialized while hidden
                    setTimeout(function() { // Use timeout to ensure tab is fully visible
                        activeTable.DataTable().columns.adjust().draw();
                        console.log("DataTable in " + newActiveTabHref + " redrawn/adjusted.");
                    }, 10);
                }
            });

            var lastActiveTabHref = localStorage.getItem('activeDoctorPatientDetailTab_BS4');
            var defaultActiveTab = tabsContainer.find('a[data-toggle="tab"].active').attr(
                'href'); // التبويب النشط في HTML

            if (lastActiveTabHref) {
                var tabToActivate = tabsContainer.find('a[href="' + lastActiveTabHref + '"]');
                if (tabToActivate.length) {
                    // لا تستدعي tab('show') هنا إذا كان هو نفسه التبويب النشط في HTML
                    // لتجنب إطلاق الحدث shown.bs.tab مرتين عند التحميل الأول
                    if (lastActiveTabHref !== defaultActiveTab) {
                        tabToActivate.tab('show');
                    } else {
                        // إذا كان هو نفسه، قم بتشغيل الكود الخاص بإعادة رسم الجدول يدوياً لأول مرة
                        var activeTableOnLoad = $(lastActiveTabHref).find('table.dataTable');
                        if (activeTableOnLoad.length && $.fn.DataTable.isDataTable(activeTableOnLoad)) {
                            setTimeout(function() {
                                activeTableOnLoad.DataTable().columns.adjust().draw();
                                console.log("Initial active DataTable in " + lastActiveTabHref +
                                    " redrawn/adjusted.");
                            }, 150); // تأخير أطول قليلاً عند التحميل الأول
                        }
                    }
                } else {
                    tabsContainer.find('a[data-toggle="tab"]:first').tab('show');
                }
            } else if (defaultActiveTab) {
                // إذا لم يكن هناك تبويب مخزن، شغل الكود الخاص بإعادة رسم الجدول للتبويب النشط في HTML
                var activeTableInHtml = $(defaultActiveTab).find('table.dataTable');
                if (activeTableInHtml.length && $.fn.DataTable.isDataTable(activeTableInHtml)) {
                    setTimeout(function() {
                        activeTableInHtml.DataTable().columns.adjust().draw();
                        console.log("Initial active DataTable (from HTML) in " + defaultActiveTab +
                            " redrawn/adjusted.");
                    }, 150);
                }
            } else {
                tabsContainer.find('a[data-toggle="tab"]:first').tab('show');
            }


            // Notifications
            <?php if(session('success')): ?>
                notif({
                    msg: `<div class="d-flex align-items-center p-2"><i class='fas fa-check-circle fa-lg me-2 text-success'></i><div style='font-size: 0.95rem;'><?php echo e(session('success')); ?></div></div>`,
                    type: "success",
                    position: "top-center",
                    autohide: true,
                    timeout: 5000,
                    zindex: 99999
                });
            <?php endif; ?>
            <?php if(session('error')): ?>
                notif({
                    msg: `<div class="d-flex align-items-center p-2"><i class='fas fa-exclamation-triangle fa-lg me-2 text-danger'></i><div style='font-size: 0.95rem;'><?php echo e(session('error')); ?></div></div>`,
                    type: "error",
                    position: "top-center",
                    autohide: true,
                    timeout: 7000,
                    zindex: 99999
                });
            <?php endif; ?>
            <?php if($errors->any()): ?>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    notif({
                        msg: `<div class="d-flex align-items-center p-2"><i class='fas fa-times-circle fa-lg me-2 text-danger'></i><div style='font-size: 0.95rem;'><?php echo e($error); ?></div></div>`,
                        type: "error",
                        position: "top-center",
                        autohide: false,
                        timeout: 7000,
                        zindex: 99999
                    });
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        });

        // Print function
        function printPatientSection(selectorsArray) {
            var printContents = '';
            selectorsArray.forEach(function(selector) {
                var element = document.querySelector(selector);
                if (element) {
                    printContents += '<div class="print-section-wrapper">' + element.innerHTML + '</div>';
                }
            });

            if (printContents === '') {
                alert('لم يتم تحديد أي قسم للطباعة أو الأقسام غير موجودة.');
                return;
            }

            var originalContents = document.body.innerHTML;
            var printPage = document.createElement('div');
            printPage.classList.add('print-container'); // Add class for print-specific styling if needed
            printPage.innerHTML = printContents;
            document.body.innerHTML = printPage.innerHTML;
            window.print();
            document.body.innerHTML = originalContents;
            // Re-initialize JS components that might have been affected
            // This is important because replacing innerHTML can break event listeners and component states
            $(document).ready(function() { // Re-run relevant parts of document.ready or specific init functions
                AOS.init({
                    duration: 700,
                    easing: 'ease-out-cubic',
                    once: true,
                    offset: 50
                });
                if (typeof $('[data-toggle="tooltip"]').tooltip === 'function') {
                    $('[data-toggle="tooltip"]').tooltip({
                        boundary: 'window'
                    });
                }
                // Re-initialize DataTables (can be complex, might need a more robust solution if issues arise)
                // initDataTable('#prescriptionsTable'); // Example, might re-initialize multiple times
                // initDataTable('#raysTable');
                // initDataTable('#labsTable');
                // initDataTable('#admissionsTable');
                // Re-attach tab event listeners or re-initialize tabs if necessary
                var tabsContainer = $('#patientRecordTabs');
                tabsContainer.find('a[data-toggle="tab"]').off('shown.bs.tab').on('shown.bs.tab', function(e) {
                    var newActiveTabHref = $(e.target).attr('href');
                    localStorage.setItem('activeDoctorPatientDetailTab_BS4', newActiveTabHref);
                    var activeTable = $(newActiveTabHref).find('table.dataTable');
                    if (activeTable.length && $.fn.DataTable.isDataTable(activeTable)) {
                        setTimeout(function() {
                            activeTable.DataTable().columns.adjust().draw();
                        }, 10);
                    }
                });
                var lastActiveTabHref = localStorage.getItem('activeDoctorPatientDetailTab_BS4');
                if (lastActiveTabHref) {
                    var tabToReactivate = tabsContainer.find('a[href="' + lastActiveTabHref + '"]');
                    if (tabToReactivate.length) tabToReactivate.tab('show');
                }
            });
        }

        function printSelectedPatientSections() {
            // يمكنك هنا فتح مودال صغير للمستخدم ليختار الأقسام التي يريد طباعتها
            // أو تحديد مجموعة افتراضية للطباعة
            var sectionsToPrint = ['#patientProfileHeaderSection', '#patientInfoAndDiseasesSection'];
            var activeTabContentId = $('#patientRecordTabs .nav-link.active').attr('href');
            if (activeTabContentId) {
                sectionsToPrint.push(activeTabContentId);
            }
            printPatientSection(sectionsToPrint);
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Dashboard.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/Doctors/Patients/details.blade.php ENDPATH**/ ?>