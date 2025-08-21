 

<?php $__env->startSection('title'); ?>
    تفاصيل الوصفة الطبية رقم: <?php echo e($prescription->prescription_number ?? 'غير محدد'); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
    <?php echo \Illuminate\View\Factory::parentPlaceholder('css'); ?>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="<?php echo e(URL::asset('dashboard/plugins/notify/css/notifIt.css')); ?>" rel="stylesheet" />
    <style>
        :root {
            --bs-primary-rgb: 67, 97, 238;
            --bs-primary: rgb(var(--bs-primary-rgb));
            --bs-secondary-rgb: 108, 117, 125;
            --bs-success-rgb: 25, 135, 84;
            --bs-info-rgb: 13, 202, 240;
            --bs-warning-rgb: 255, 193, 7;
            --bs-danger-rgb: 220, 53, 69;
            --bs-light-rgb: 248, 249, 250;
            --bs-dark-rgb: 33, 37, 41;
            --bs-body-bg: #f4f6f9;
            --bs-border-color: #dee2e6;
            --bs-card-border-radius: 0.75rem;
            /* زوايا أكثر دائرية */
            --bs-card-box-shadow: 0 0.25rem 1rem rgba(0, 0, 0, 0.08);
            /* ظل أنعم */
            --bs-font-sans-serif: 'Tajawal', sans-serif;
            --bs-body-color: #495057;
            /* لون نص أغمق قليلاً */
        }

        body {
            font-family: var(--bs-font-sans-serif);
            background-color: var(--bs-body-bg);
            color: var(--bs-body-color);
            line-height: 1.7;
        }

        .prescription-details-container {
            max-width: 900px;
            /* عرض مناسب للتفاصيل */
            margin: 2rem auto;
        }

        .prescription-card {
            background-color: #fff;
            border-radius: var(--bs-card-border-radius);
            box-shadow: var(--bs-card-box-shadow);
            border: 1px solid var(--bs-border-color);
            margin-bottom: 2rem;
            overflow: hidden;
            /* لضمان تطبيق الزوايا على الهيدر */
        }

        .prescription-header {
            background: linear-gradient(135deg, rgba(var(--bs-primary-rgb), 0.95), rgba(var(--bs-primary-rgb), 0.8));
            color: white;
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .prescription-header h4 {
            margin-bottom: 0.25rem;
            font-size: 1.6rem;
            font-weight: 600;
        }

        .prescription-header p {
            margin-bottom: 0;
            opacity: 0.85;
            font-size: 0.9rem;
        }

        .prescription-header .status-badge-header {
            font-size: 0.9rem;
            padding: 0.5em 1em;
            border-radius: 50px;
            background-color: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .info-block {
            background-color: rgb(var(--bs-light-rgb));
            padding: 1.25rem;
            border-radius: var(--bs-card-border-radius);
            border: 1px solid var(--bs-border-color);
        }

        .info-block h6 {
            color: var(--bs-primary);
            margin-bottom: 0.75rem;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .info-block h6 i {
            margin-left: 0.5rem;
            /* RTL */
            font-size: 1.1em;
            opacity: 0.8;
        }

        .info-block p {
            margin-bottom: 0.4rem;
            font-size: 0.95rem;
        }

        .info-block strong {
            color: var(--bs-dark-rgb);
        }

        .info-block .patient-avatar-display {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            margin-left: 10px;
            border: 2px solid var(--bs-border-color);
        }

        .info-block .doctor-avatar-display {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            margin-left: 10px;
            border: 2px solid var(--bs-border-color);
        }

        .medications-table th {
            background-color: rgba(var(--bs-primary-rgb), 0.07);
            color: var(--bs-primary);
            font-weight: 600;
            font-size: 0.9rem;
        }

        .medications-table td {
            font-size: 0.9rem;
            vertical-align: top;
            /* لمحاذاة التعليمات بشكل أفضل */
        }

        .medications-table .dosage-details {
            font-weight: 500;
            color: var(--bs-dark-rgb);
        }

        .medications-table .instructions {
            font-size: 0.85rem;
            color: #6c757d;
            padding-left: 1.5rem;
            /* RTL */
        }

        .notes-section {
            margin-top: 1.5rem;
        }

        .notes-section h6 {
            font-weight: 600;
            color: var(--bs-primary);
        }

        .notes-section p {
            background-color: #f8f9fa;
            border: 1px solid #eee;
            padding: 0.75rem;
            border-radius: 0.4rem;
            font-size: 0.9rem;
            white-space: pre-wrap;
        }

        .actions-footer {
            padding: 1.5rem 2rem;
            background-color: var(--bs-card-cap-bg);
            border-top: 1px solid var(--bs-border-color);
            text-align: left;
            /* RTL */
        }

        .actions-footer .btn {
            font-weight: 500;
            padding: 0.6rem 1.2rem;
        }

        .status-badge-sm {
            /* لتناسق الشارات */
            padding: 0.35em 0.7em;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 500;
            letter-spacing: 0.3px;
        }

        .status-new {
            background-color: rgba(var(--bs-info-rgb), 0.15);
            color: rgb(var(--bs-info-rgb));
        }

        .status-approved {
            background-color: rgba(var(--bs-primary-rgb), 0.15);
            color: var(--bs-primary);
        }

        .status-dispensed {
            background-color: rgba(var(--bs-success-rgb), 0.15);
            color: rgb(var(--bs-success-rgb));
        }

        .status-partially_dispensed {
            background-color: rgba(var(--bs-warning-rgb), 0.2);
            color: #a17d06;
        }

        .status-cancelled_by_doctor,
        .status-cancelled_by_pharmacist,
        .status-cancelled_by_patient {
            background-color: rgba(var(--bs-danger-rgb), 0.1);
            color: rgb(var(--bs-danger-rgb));
        }

        .status-on_hold {
            background-color: rgba(var(--bs-secondary-rgb), 0.15);
            color: rgb(var(--bs-secondary-rgb));
        }

        .status-expired {
            background-color: #f8d7da;
            color: #721c24;
        }

        @media  print {

            /* أنماط للطباعة */
            body {
                background-color: #fff;
                font-size: 10pt;
            }

            .breadcrumb-header,
            .actions-footer,
            .main-header,
            .app-sidebar,
            .main-footer {
                display: none !important;
            }

            .prescription-form-container {
                margin: 0;
                box-shadow: none;
                border: none;
                max-width: 100%;
                padding: 0;
            }

            .prescription-card {
                box-shadow: none;
                border: 1px solid #ccc;
                margin-bottom: 1rem;
            }

            .prescription-header {
                background: #eee !important;
                color: #333 !important;
                padding: 1rem;
                border-bottom: 1px solid #ccc;
            }

            .prescription-header h4 {
                font-size: 1.3rem;
            }

            .prescription-header .status-badge-header {
                background-color: transparent;
                border: 1px solid #777;
                color: #333;
            }

            .info-block {
                background-color: #f9f9f9;
                border: 1px solid #ddd;
                margin-bottom: 1rem;
            }

            .info-block h6 {
                color: #333;
            }

            .medications-table th {
                background-color: #f0f0f0 !important;
                color: #333 !important;
            }

            .medications-table td,
            .medications-table th {
                font-size: 9pt;
                padding: 0.5rem;
            }

            .notes-section p {
                background-color: #f9f9f9;
                border: 1px solid #eee;
            }

            a[href]:after {
                content: none !important;
            }

            /* إخفاء الروابط عند الطباعة */
            .no-print {
                display: none !important;
            }
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header'); ?>
    <div class="breadcrumb-header justify-content-between no-print">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto"><i
                        class="fas fa-file-medical-alt text-primary me-2"></i><?php echo e(trans('doctors.prescriptions')); ?></h4>
                <span class="text-muted mt-1 tx-13 mx-2">/</span>
                <span
                    class="text-muted mt-1 tx-13"><?php echo e(trans('doctors.prescription_details_for_number', ['number' => $prescription->prescription_number])); ?></span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <a href="<?php echo e(route('prescriptions.index')); ?>" class="btn btn-outline-secondary btn-sm ripple-effect me-2">
                <i class="fas fa-list-ul me-1"></i> <?php echo e(trans('doctors.view_all_prescriptions')); ?>

            </a>
            <button onclick="window.print();" class="btn btn-info btn-sm ripple-effect me-2">
                <i class="fas fa-print me-1"></i> <?php echo e(trans('doctors.print_prescription')); ?>

            </button>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('Dashboard.messages_alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="prescription-details-container" data-aos="fade-in">
        <div class="prescription-card">
            <div class="prescription-header">
                <div>
                    <h4><?php echo e(trans('doctors.prescription_number_header', ['number' => $prescription->prescription_number])); ?>

                    </h4>
                    <p><?php echo e(trans('doctors.created_on')); ?>:
                        <?php echo e($prescription->prescription_date->translatedFormat('l، j F Y')); ?></p>
                </div>
                <div>
                    <?php
                        $statusKeyHeader = $prescription->status;
                        $statusTextHeader = '';
                        if (
                            class_exists(\App\Models\Prescription::class) &&
                            method_exists(\App\Models\Prescription::class, 'getStatusesForFilter')
                        ) {
                            $statusTextHeader =
                                \App\Models\Prescription::getStatusesForFilter()[$statusKeyHeader] ??
                                ucfirst(str_replace('_', ' ', $statusKeyHeader));
                        } else {
                            $statusTextHeader = ucfirst(str_replace('_', ' ', $statusKeyHeader));
                        }
                        $statusBadgeClassHeader = 'status-' . str_replace('_', '-', $statusKeyHeader);
                    ?>
                    <span class="status-badge-header <?php echo e($statusBadgeClassHeader); ?>"
                        style="color: white"><?php echo e($statusTextHeader); ?></span>
                </div>
            </div>

            <div class="card-body p-4">
                <div class="info-grid">
                    
                    <div class="info-block">
                        <h6><i class="fas fa-user-injured"></i><?php echo e(trans('doctors.patient_information')); ?></h6>
                        <?php if($prescription->patient): ?>
                            <div class="d-flex align-items-center mb-2">
                                <img src="<?php echo e($prescription->patient->image ? asset('Dashboard/img/patients/' . $prescription->patient->image->filename) : URL::asset('Dashboard/img/default_patient_avatar.png')); ?>"
                                    alt="<?php echo e($prescription->patient->name); ?>" class="patient-avatar-display">
                                <div>
                                    <p class="mb-0"><strong><?php echo e(trans('doctors.name_label')); ?></strong>
                                        <?php echo e($prescription->patient->name); ?></p>
                                    <p class="mb-0"><small class="text-muted"><?php echo e(trans('doctors.national_id_label')); ?>

                                            <?php echo e($prescription->patient->national_id); ?></small></p>
                                </div>
                            </div>
                            <p><i class="fas fa-phone fa-fw me-2 text-muted"></i><?php echo e($prescription->patient->Phone ?: '-'); ?>

                            </p>
                            <p><i
                                    class="fas fa-birthday-cake fa-fw me-2 text-muted"></i><?php echo e($prescription->patient->Date_Birth ? $prescription->patient->Date_Birth->translatedFormat('j M Y') . ' (' . $prescription->patient->Date_Birth->age . ' ' . trans('doctors.years_old') . ')' : '-'); ?>

                            </p>
                        <?php else: ?>
                            <p class="text-muted"><em><?php echo e(trans('doctors.patient_info_unavailable')); ?></em></p>
                        <?php endif; ?>
                    </div>

                    
                    <div class="info-block">
                        <h6><i class="fas fa-user-md"></i><?php echo e(trans('doctors.doctor_information')); ?></h6>
                        <?php if($prescription->doctor): ?>
                            <div class="d-flex align-items-center mb-2">
                                <img src="<?php echo e($prescription->doctor->image ? asset('Dashboard/img/doctors/' . $prescription->doctor->image->filename) : URL::asset('Dashboard/img/doctor_default.png')); ?>"
                                    alt="<?php echo e($prescription->doctor->name); ?>" class="doctor-avatar-display">
                                <div>
                                    <p class="mb-0"><strong><?php echo e(trans('doctors.name_label')); ?></strong>
                                        <?php echo e($prescription->doctor->name); ?></p>
                                    <p class="mb-0"><small class="text-muted"><?php echo e(trans('doctors.department_label')); ?>

                                            <?php echo e($prescription->doctor->section->name ?? '-'); ?></small></p>
                                </div>
                            </div>
                            <p><i
                                    class="fas fa-envelope fa-fw me-2 text-muted"></i><?php echo e($prescription->doctor->email ?: '-'); ?>

                            </p>
                        <?php else: ?>
                            <p class="text-muted"><em><?php echo e(trans('doctors.doctor_info_unavailable')); ?></em></p>
                        <?php endif; ?>
                    </div>
                </div>

                
                <?php if($prescription->is_chronic_prescription || $prescription->patient_admission_id): ?>
                    <div class="info-grid" style="margin-top: -0.5rem;"> 
                        <?php if($prescription->is_chronic_prescription): ?>
                            <div class="info-block bg-light border-warning">
                                <h6><i
                                        class="fas fa-history text-warning"></i><?php echo e(trans('doctors.chronic_prescription_title')); ?>

                                </h6>
                                <p class="mb-0"><?php echo e(trans('doctors.chronic_prescription_message')); ?></p>
                                <?php if($prescription->next_refill_due_date): ?>
                                    <p><strong><?php echo e(trans('doctors.next_refill_due_date_label')); ?></strong>
                                        <?php echo e(\Carbon\Carbon::parse($prescription->next_refill_due_date)->translatedFormat('j M Y')); ?>

                                    </p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <?php if($prescription->patientAdmission): ?>
                            <div class="info-block bg-light border-info">
                                <h6><i
                                        class="fas fa-procedures text-info"></i><?php echo e(trans('doctors.linked_to_admission_number', ['id' => $prescription->patientAdmission->id])); ?>

                                </h6>
                                <p><strong><?php echo e(trans('doctors.admission_reason_label')); ?></strong>
                                    <?php echo e(Str::limit($prescription->patientAdmission->reason_for_admission, 50) ?: '-'); ?></p>
                                <p><strong><?php echo e(trans('doctors.admission_date_label')); ?></strong>
                                    <?php echo e($prescription->patientAdmission->admission_date->translatedFormat('j M Y')); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>


                <h5 class="form-section-title mt-4"><i
                        class="fas fa-tablets"></i><?php echo e(trans('doctors.prescribed_medications_title')); ?></h5>
                <?php if($prescription->items->isNotEmpty()): ?>
                    <div class="table-responsive-custom">
                        <table class="table table-custom table-striped medications-table">
                            <thead>
                                <tr>
                                    <th><?php echo e(trans('doctors.medication_header')); ?></th>
                                    <th class="text-center"><?php echo e(trans('doctors.dose_header')); ?></th>
                                    <th class="text-center"><?php echo e(trans('doctors.frequency_header')); ?></th>
                                    <th class="text-center"><?php echo e(trans('doctors.duration_header')); ?></th>
                                    <th class="text-center"><?php echo e(trans('doctors.quantity_header')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $prescription->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo e($item->medication->name ?? trans('doctors.unknown_medication')); ?></strong>
                                            <small class="d-block text-muted">
                                                <?php echo e($item->medication->strength ? $item->medication->strength . ' - ' : ''); ?>

                                                <?php echo e($item->medication->dosage_form ? \App\Models\Medication::getCommonDosageForms()[$item->medication->dosage_form] ?? $item->medication->dosage_form : ''); ?>

                                            </small>
                                            <?php if($item->instructions_for_patient): ?>
                                                <p class="instructions mt-1 mb-0"><i
                                                        class="fas fa-info-circle fa-xs me-1 text-info"></i><?php echo e($item->instructions_for_patient); ?>

                                                </p>
                                            <?php endif; ?>
                                            <?php if($item->is_prn): ?>
                                                <span
                                                    class="badge bg-light text-dark border mt-1"><?php echo e(trans('doctors.as_needed_badge')); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center dosage-details"><?php echo e($item->dosage); ?></td>
                                        <td class="text-center dosage-details"><?php echo e($item->frequency); ?></td>
                                        <td class="text-center"><?php echo e($item->duration ?: '-'); ?></td>
                                        <td class="text-center"><?php echo e($item->quantity_prescribed ?: '-'); ?></td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center my-3"><?php echo e(trans('doctors.no_medications_added')); ?></p>
                <?php endif; ?>

                <?php if($prescription->doctor_notes): ?>
                    <div class="notes-section">
                        <h6><i class="fas fa-sticky-note"></i><?php echo e(trans('doctors.doctor_notes_for_pharmacist_label')); ?></h6>
                        <p><?php echo e($prescription->doctor_notes); ?></p>
                    </div>
                <?php endif; ?>

                <?php if($prescription->pharmacy_notes): ?>
                    <div class="notes-section">
                        <h6><i class="fas fa-prescription-bottle-alt"></i><?php echo e(trans('doctors.pharmacy_notes_label')); ?></h6>
                        <p><?php echo e($prescription->pharmacy_notes); ?></p>
                    </div>
                <?php endif; ?>

                <?php if($prescription->dispensed_at): ?>
                    <div class="notes-section">
                        <h6><i class="fas fa-check-double text-success"></i>
                            <?php echo e(trans('doctors.dispensing_information_title')); ?></h6>
                        <p class="mb-1"><strong><?php echo e(trans('doctors.dispensed_by_label')); ?></strong>
                            <?php if($prescription->dispensedByPharmacyEmployee): ?>
                                <?php echo e($prescription->dispensedByPharmacyEmployee->name); ?>

                            <?php else: ?>
                                <?php echo e(trans('doctors.unknown_employee_with_id', ['id' => $prescription->dispensed_by_pharmacy_employee_id])); ?>

                            <?php endif; ?>
                        </p>
                        <p><strong><?php echo e(trans('doctors.dispensing_date_time_label')); ?></strong>
                            <?php echo e($prescription->dispensed_at->translatedFormat('l، j F Y - H:i')); ?></p>
                    </div>
                <?php endif; ?>

            </div> 

            <div class="actions-footer no-print">
                <?php
                    $editableStatusesFooter = [
                        \App\Models\Prescription::STATUS_NEW,
                        \App\Models\Prescription::STATUS_APPROVED,
                    ];
                    $cancellableStatusesFooter = array_merge($editableStatusesFooter, [
                        \App\Models\Prescription::STATUS_ON_HOLD,
                        \App\Models\Prescription::STATUS_PARTIALLY_DISPENSED,
                    ]);
                ?>

                <?php if(in_array($prescription->status, $editableStatusesFooter) &&
                        Auth::guard('doctor')->id() === $prescription->doctor_id): ?>
                    <a href="<?php echo e(route('prescriptions.edit', $prescription->id)); ?>" class="btn btn-primary ripple">
                        <i class="fas fa-edit me-1"></i> <?php echo e(trans('doctors.edit_prescription_action')); ?>

                    </a>
                <?php endif; ?>

                <?php if(in_array($prescription->status, $cancellableStatusesFooter) &&
                        Auth::guard('doctor')->id() === $prescription->doctor_id): ?>
                    <form action="<?php echo e(route('prescriptions.destroy', $prescription->id)); ?>" method="POST" class="d-inline"
                        onsubmit="return confirm('<?php echo e(trans('doctors.confirm_cancellation_prompt')); ?>');">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-danger ripple">
                            <i class="fas fa-times-circle me-1"></i> <?php echo e(trans('doctors.cancel_prescription_action')); ?>

                        </button>
                    </form>
                <?php endif; ?>
            </div>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <?php echo \Illuminate\View\Factory::parentPlaceholder('js'); ?>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="<?php echo e(URL::asset('dashboard/plugins/notify/js/notifIt.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('dashboard/plugins/notify/js/notifit-custom.js')); ?>"></script>
    <script>
        $(document).ready(function() {
            AOS.init({
                duration: 700,
                easing: 'ease-out-cubic',
                once: true
            });

            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })

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
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Dashboard.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/Doctors/Prescriptions/show.blade.php ENDPATH**/ ?>