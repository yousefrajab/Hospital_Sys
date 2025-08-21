<?php $__env->startSection('title', 'تفاصيل الوصفة: ' . $prescription->prescription_number); ?>

<?php $__env->startSection('css'); ?>
    <?php echo \Illuminate\View\Factory::parentPlaceholder('css'); ?>
    
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="<?php echo e(URL::asset('dashboard/plugins/notify/css/notifIt.css')); ?>" rel="stylesheet" />

    <style>
        /* --- Root Variables & Basic Styling (Adopted from your previous clean styles) --- */
        :root {
            --bs-primary-rgb: 67, 97, 238;
            --bs-primary: rgb(var(--bs-primary-rgb));
            --bs-primary-dark: #3a58d8;
            --bs-secondary-rgb: 108, 117, 125;
            --bs-secondary: rgb(var(--bs-secondary-rgb));
            --bs-success-rgb: 25, 135, 84;
            --bs-success: rgb(var(--bs-success-rgb));
            --bs-info-rgb: 13, 202, 240;
            --bs-info: rgb(var(--bs-info-rgb));
            --bs-warning-rgb: 255, 193, 7;
            --bs-warning: rgb(var(--bs-warning-rgb));
            --bs-danger-rgb: 220, 53, 69;
            --bs-danger: rgb(var(--bs-danger-rgb));
            --bs-light-rgb: 248, 249, 250;
            --bs-light: rgb(var(--bs-light-rgb));
            --bs-dark-rgb: 33, 37, 41;
            --bs-dark: rgb(var(--bs-dark-rgb));
            --bs-body-bg: #f8f9fc;
            --bs-border-color: #e3e6f0;
            --bs-card-border-radius: 0.45rem;
            --bs-card-box-shadow: 0 0.15rem 1.75rem 0 rgba(0,0,0,0.05);
        }
        body { font-family: 'Tajawal', sans-serif; background-color: var(--bs-body-bg); color: #5a5c69; }
        .card { border-radius: var(--bs-card-border-radius); box-shadow: var(--bs-card-box-shadow); border: 1px solid var(--bs-border-color); margin-bottom: 1.5rem; }
        .card-header { background-color: #fff; border-bottom: 1px solid var(--bs-border-color); padding: 0.9rem 1.25rem; }
        .card-title { font-weight: 600; color: var(--bs-dark); }

        /* Status Badges (Your good styles are assumed here) */
        .status-badge { padding: 0.4em 0.8em; border-radius: 50px; font-size: 0.8rem; font-weight: 500; letter-spacing: 0.3px; min-width: 110px; text-align: center; display: inline-block; }
        .status-new { background-color: rgba(var(--bs-info-rgb), 0.15); color: rgb(var(--bs-info-rgb)); border: 1px solid rgba(var(--bs-info-rgb), 0.3); }
        .status-pending_review { background-color: rgba(var(--bs-warning-rgb), 0.2); color: #a17d06; border: 1px solid rgba(var(--bs-warning-rgb), 0.4); }
        .status-approved { background-color: rgba(var(--bs-primary-rgb), 0.15); color: var(--bs-primary); border: 1px solid rgba(var(--bs-primary-rgb), 0.3); }
        .status-ready_for_pickup { background-color: #e2f0d9; color: #548235; border: 1px solid #c5e0b4;}
        .status-processing { background-color: #fff3cd; color: #664d03; border: 1px solid #ffecb5;}
        .status-dispensed { background-color: rgba(var(--bs-success-rgb), 0.15); color: rgb(var(--bs-success-rgb)); border: 1px solid rgba(var(--bs-success-rgb), 0.3); }
        .status-partially_dispensed { background-color: rgba(255, 165, 0, 0.15); color: #cc8400; border: 1px solid rgba(255, 165, 0, 0.3); }
        .status-cancelled_by_doctor,
        .status-cancelled_by_pharmacist,
        .status-cancelled_by_patient { background-color: rgba(var(--bs-danger-rgb), 0.1); color: rgb(var(--bs-danger-rgb)); border: 1px solid rgba(var(--bs-danger-rgb), 0.2); }
        .status-on_hold { background-color: rgba(var(--bs-secondary-rgb), 0.15); color: rgb(var(--bs-secondary-rgb)); border: 1px solid rgba(var(--bs-secondary-rgb), 0.2); }
        .status-expired { background-color: #e9ecef; color: #495057; border: 1px solid #ced4da;}
        .status-refill_requested { background-color: #ffe6cc; color: #c45100; border: 1px solid #ffdab3; }
        .status-default { background-color: #f8f9fa; color: #212529; border: 1px solid #dee2e6; }


        /* --- Styles for the show page --- */
        .prescription-details-card { margin-top: 1rem; } /* Add some top margin */

        .prescription-main-header {
            background: linear-gradient(135deg, #4e73df 0%, #36b9cc 100%); /* Example gradient */
            color: #fff;
            padding: 1.5rem 1.75rem;
            border-radius: var(--bs-card-border-radius) var(--bs-card-border-radius) 0 0;
            margin: -1px -1px 0 -1px; /* To slightly overlap card border for seamless look */
        }
        .prescription-main-header h3 {
            margin-bottom: 0.25rem;
            font-size: 1.5rem; /* Slightly larger font for prescription number */
            font-weight: 600;
        }
        .prescription-main-header p {
            margin-bottom: 0;
            font-size: 0.9rem;
            opacity: 0.9;
        }
        .prescription-main-header .status-badge { /* Make status badge more prominent in header */
            font-size: 0.9rem;
            padding: 0.5em 1em;
            background-color: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.4);
            backdrop-filter: blur(4px);
            color: #fff;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); /* Adjust minmax for responsiveness */
            gap: 1.25rem; /* Increased gap */
            margin-bottom: 1.5rem;
        }
        .info-item p.info-label { /* Changed to p for label for better semantics */
            margin-bottom: 0.3rem;
            font-weight: 500; /* Medium weight for label */
            color: var(--bs-primary-dark); /* Darker primary for label */
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }
        .info-item .info-value {
            color: #36454F; /* Charcoal color for value for better readability */
            font-size: 0.95rem;
            font-weight: 500;
        }
        .info-item .info-value i {
            margin-right: 0.5rem; /* RTL: me- (margin-end) */
            opacity: 0.7;
            color: var(--bs-secondary);
        }

        .section-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--bs-dark);
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--bs-primary);
            display: inline-block; /* To make border-bottom only span the text width */
        }

        .medication-list { margin-top: 0.5rem; }
        .medication-list .list-group-item {
            border-color: var(--bs-border-color);
            padding: 1rem 1.25rem;
            margin-bottom: 0.75rem; /* Space between medication items */
            border-radius: var(--bs-card-border-radius) !important; /* Ensure border radius */
            border-width: 1px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.04);
            transition: transform 0.2s ease-out, box-shadow 0.2s ease-out;
        }
        .medication-list .list-group-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.08);
        }
        .medication-list .med-name {
            font-weight: 600;
            color: var(--bs-primary-dark);
            font-size: 1.1rem;
        }
        .medication-list .med-details {
            font-size: 0.85rem;
            color: #6c757d; display: block; margin-top:0.2rem;
        }
        .medication-list .med-instructions {
            background-color: #f0f5ff; /* Lighter blueish background */
            padding: 0.75rem 1rem;
            border-radius: 0.3rem;
            margin-top: 0.75rem;
            font-size: 0.9rem;
            color: #435D7D;  /* Darker blue text for instructions */
            border-left: 4px solid var(--bs-info);
            white-space: pre-wrap; /* To respect newlines in instructions */
        }
        .medication-list .med-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 0.5rem;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }
        .medication-list .med-info-grid > div > strong {
            color: var(--bs-secondary);
            font-weight: 500;
        }

        .notes-section p {
            white-space: pre-wrap;
            background-color: #fdfaf0;
            padding: 0.75rem 1rem;
            border-radius: 5px;
            border: 1px solid #fceec9;
            font-size:0.95rem;
            color: #5d533c;
        }

        .action-button-container {
            padding: 1.25rem;
            border-top: 1px solid var(--bs-border-color);
            background-color: #fcfdff;
            text-align: center; /* Center buttons or align as per your template */
        }
        .action-button-container .btn {
             padding: 0.6rem 1.5rem; /* Larger buttons */
             font-size: 0.95rem;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header'); ?>
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto">
                    <a href="<?php echo e(route('patient.pharmacy.index')); ?>" class="text-primary-dark tx-16 hover-underline">
                        <i class="fas fa-arrow-left me-2 opacity-75"></i> <?php echo e(trans('patients.my_prescriptions')); ?>

                    </a>
                </h4>
                <span class="text-muted mt-1 tx-13 mx-2">/</span>
                <span class="mt-1 tx-13"> <?php echo e(trans('patients.prescription_details')); ?></span>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('Dashboard.messages_alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="card prescription-details-card" data-aos="fade-up">
        <div class="prescription-main-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3><?php echo e(trans('patients.prescription_number_header')); ?> <?php echo e($prescription->prescription_number); ?></h3>
                    <p><i class="fas fa-calendar-alt me-1 opacity-75"></i><?php echo e(trans('patients.issue_date')); ?> <?php echo e($prescription->prescription_date->translatedFormat('l، d F Y')); ?></p>
                </div>
                <div>
                     <span class="status-badge <?php echo e($prescription->status_badge_class); ?>">
                         <?php echo e($prescription->status_display); ?>

                     </span>
                </div>
            </div>
        </div>

        <div class="card-body p-4">
            <h5 class="section-title mb-3"><i class="fas fa-file-invoice text-primary me-2"></i><?php echo e(trans('patients.basic_prescription_info')); ?></h5>
            <div class="info-grid">
                <div class="info-item">
                    <p class="info-label"><?php echo e(trans('patients.issuing_doctor')); ?></p>
                    <span class="info-value"><i class="fas fa-user-md"></i><?php echo e($prescription->doctor ? $prescription->doctor->name : trans('patients.unspecified')); ?></span>
                </div>
                 <div class="info-item">
                    <p class="info-label"><?php echo e(trans('patients.chronic_prescription')); ?></p>
                    <span class="info-value">
                        <?php if($prescription->is_chronic_prescription): ?>
                            <i class="fas fa-check-circle text-success"></i> <?php echo e(trans('patients.yes')); ?>

                        <?php else: ?>
                            <i class="fas fa-times-circle text-danger"></i> <?php echo e(trans('patients.no')); ?>

                        <?php endif; ?>
                    </span>
                </div>
                <?php if($prescription->admission): ?>
                    <div class="info-item">
                        <p class="info-label"><?php echo e(trans('patients.linked_to_admission_num')); ?></p>
                        <span class="info-value"><i class="fas fa-hospital-user"></i><?php echo e($prescription->admission->admission_uid ?? 'N/A'); ?></span>
                    </div>
                <?php endif; ?>
                 <?php if($prescription->next_refill_due_date): ?>
                    <div class="info-item">
                        <p class="info-label"><?php echo e(trans('patients.next_refill_date')); ?></p>
                        <span class="info-value"><i class="fas fa-calendar-check"></i><?php echo e($prescription->next_refill_due_date->translatedFormat('d F Y')); ?></span>
                    </div>
               <?php endif; ?>
            </div>

            <?php if($prescription->dispensed_at && $prescription->dispensedByPharmacyEmployee): ?>
                <hr class="my-4">
                <h5 class="section-title mb-3"><i class="fas fa-user-nurse text-success me-2"></i><?php echo e(trans('patients.dispensing_information')); ?></h5>
                <div class="info-grid">
                    <div class="info-item">
                        <p class="info-label"><?php echo e(trans('patients.dispensed_by')); ?></p>
                        <span class="info-value"><i class="fas fa-user-tag"></i><?php echo e($prescription->dispensedByPharmacyEmployee->name); ?></span>
                    </div>
                    <div class="info-item">
                        <p class="info-label"><?php echo e(trans('patients.dispensing_date_time')); ?></p>
                        <span class="info-value"><i class="fas fa-clock"></i><?php echo e($prescription->dispensed_at->translatedFormat('d M Y, h:i A')); ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <?php if($prescription->doctor_notes || $prescription->pharmacy_notes): ?>
                <hr class="my-4">
            <?php endif; ?>
            <?php if($prescription->doctor_notes): ?>
            <div class="mb-3 notes-section" data-aos="fade-left" data-aos-delay="100">
                <h6 class="card-subtitle mb-2 text-muted fw-500"><i class="fas fa-user-doctor me-2 text-primary opacity-75"></i><?php echo e(trans('patients.doctor_notes')); ?></h6>
                <p><?php echo e($prescription->doctor_notes); ?></p>
            </div>
            <?php endif; ?>

            <?php if($prescription->pharmacy_notes): ?>
             <div class="mb-3 notes-section" data-aos="fade-left" data-aos-delay="150">
                <h6 class="card-subtitle mb-2 text-muted fw-500"><i class="fas fa-clinic-medical me-2 text-success opacity-75"></i><?php echo e(trans('patients.pharmacy_notes')); ?></h6>
                <p style="background-color: #f2f9f3; border-color: #d4ebd6;"><?php echo e($prescription->pharmacy_notes); ?></p>
            </div>
            <?php endif; ?>

            <hr class="my-4">
            <h5 class="section-title mb-3"><i class="fas fa-pills text-primary me-2"></i><?php echo e(trans('patients.prescribed_medications_count', ['count' => $prescription->items->count()])); ?></h5>
            <?php if($prescription->items->isNotEmpty()): ?>
                <div class="list-group medication-list">
                    <?php $__currentLoopData = $prescription->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="list-group-item flex-column align-items-start" data-aos="fade-up" data-aos-delay="<?php echo e($index * 100); ?>">
                            <div class="d-flex w-100 justify-content-between mb-2">
                                <h5 class="mb-1 med-name">
                                     <span class="badge bg-primary-transparent me-2"><?php echo e($loop->iteration); ?></span>
                                     <?php echo e($item->medication->name ?? trans('patients.medication_not_available')); ?>

                                     <?php if($item->is_prn): ?>
                                         <span class="badge rounded-pill bg-warning-transparent text-warning ms-2" data-bs-toggle="tooltip" title="<?php echo e(trans('patients.prn_tooltip')); ?>">
                                            <i class="fas fa-exclamation-circle"></i> <?php echo e(trans('doctors.as_needed')); ?>

                                         </span>
                                     <?php endif; ?>
                                </h5>
                                <?php if(isset($item->refills_allowed) && $item->refills_allowed > ($item->refills_done ?? 0)): ?>
                                    <small class="text-info fw-500" data-bs-toggle="tooltip" title="<?php echo e(trans('patients.remaining_refills_tooltip')); ?>">
                                        <i class="fas fa-sync-alt"></i> <?php echo e(trans('patients.remaining_refills_text', ['count' => $item->refills_allowed - ($item->refills_done ?? 0)])); ?>

                                    </small>
                                 <?php endif; ?>
                            </div>

                            <?php if($item->medication): ?>
                                <p class="mb-1 med-details">
                                    <?php echo e($item->medication->generic_name ? $item->medication->generic_name . ' - ' : ''); ?>

                                    <?php echo e($item->medication->strength ?? ''); ?>

                                    <?php echo e($item->medication->dosage_form_display ? ' (' . $item->medication->dosage_form_display . ')' : ''); ?>

                                </p>
                            <?php endif; ?>

                            <div class="med-info-grid">
                                <div><strong><?php echo e(trans('patients.dosage_label')); ?></strong> <?php echo e($item->dosage ?? trans('patients.unspecified')); ?></div>
                                <div><strong><?php echo e(trans('patients.frequency_label')); ?></strong> <?php echo e($item->frequency ?? trans('patients.unspecified')); ?></div>
                                <div><strong><?php echo e(trans('patients.duration_label')); ?></strong> <?php echo e($item->duration ?? trans('patients.as_needed_or_instructed')); ?></div>
                                <div><strong><?php echo e(trans('patients.route_of_administration_label')); ?></strong> <?php echo e($item->route_of_administration ?? trans('patients.unspecified')); ?></div>
                                <?php if($item->quantity_prescribed): ?>
                                <div><strong><?php echo e(trans('patients.prescribed_quantity')); ?></strong> <?php echo e($item->quantity_prescribed); ?> <?php echo e($item->medication->unit_of_measure_display ?? ''); ?></div>
                                <?php endif; ?>
                            </div>

                            <?php if($item->instructions_for_patient): ?>
                            <div class="mt-2 med-instructions">
                                <i class="fas fa-info-circle me-1"></i> <strong><?php echo e(trans('patients.special_instructions')); ?></strong> <?php echo e($item->instructions_for_patient); ?>

                            </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="alert alert-light text-center border p-4">
                    <i class="fas fa-capsules fa-2x text-muted mb-2"></i>
                    <p class="mb-0"><?php echo e(trans('patients.no_medications_in_prescription')); ?></p>
                </div>
            <?php endif; ?>

            <?php if($prescription->can_request_refill): ?>
                <div class="action-button-container mt-4">
                    <form action="<?php echo e(route('patient.pharmacy.request-refill', $prescription->id)); ?>" method="POST" class="d-inline"
                          onsubmit="return confirm('<?php echo e(trans('patients.confirm_refill_request_message')); ?>')">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-success btn-lg ripple shadow-sm">
                            <i class="fas fa-prescription-bottle me-2"></i> <?php echo e(trans('patients.request_prescription_refill')); ?>

                        </button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
    <?php echo \Illuminate\View\Factory::parentPlaceholder('js'); ?>
    
    

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="<?php echo e(URL::asset('dashboard/plugins/notify/js/notifIt.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('dashboard/plugins/notify/js/notifit-custom.js')); ?>"></script>

    <script>
        // document.addEventListener('alpine:init', () => {
        // });

        $(document).ready(function() {
            AOS.init({
                duration: 700,
                once: true,
                offset: 50
            });

            // تفعيل الـ Tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl, {
                    boundary: document.body,
                    fallbackPlacements: ['top', 'bottom']
                })
            });

            <?php if(session('success')): ?>
                notif({
                    msg: "<div class='d-flex align-items-center p-2'><i class='fas fa-check-circle fa-lg me-2 text-success'></i><div style='font-size: 0.95rem;'><?php echo e(session('success')); ?></div></div>",
                    type: "success", position: "top-center", autohide: true, timeout: 5000
                });
            <?php endif; ?>
            <?php if(session('error')): ?>
                notif({
                    msg: "<div class='d-flex align-items-center p-2'><i class='fas fa-exclamation-triangle fa-lg me-2 text-danger'></i><div style='font-size: 0.95rem;'><?php echo e(session('error')); ?></div></div>",
                    type: "error", position: "top-center", autohide: true, timeout: 7000
                });
            <?php endif; ?>
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Dashboard.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\Hospital_Sys\resources\views/patient_dashboard/pharmacy/show.blade.php ENDPATH**/ ?>