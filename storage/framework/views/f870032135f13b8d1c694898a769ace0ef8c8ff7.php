<?php $__env->startSection('title', 'لوحة متابعة الوصفات' . ($pageTitleSuffix ?? '') ); ?>

<?php $__env->startSection('css'); ?>
    
     <?php echo \Illuminate\View\Factory::parentPlaceholder('css'); ?>
     <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
     <link href="<?php echo e(URL::asset('dashboard/plugins/notify/css/notifIt.css')); ?>" rel="stylesheet" />
    <style>
     /* --- Root Variables & Basic Styling --- */
     :root {
         --bs-primary-rgb: 67, 97, 238; --bs-primary: rgb(var(--bs-primary-rgb));
         --bs-success-rgb: 25, 135, 84; --bs-success: rgb(var(--bs-success-rgb));
         --bs-warning-rgb: 255, 193, 7; --bs-warning: rgb(var(--bs-warning-rgb));
         --bs-danger-rgb: 220, 53, 69; --bs-danger: rgb(var(--bs-danger-rgb));
         --bs-info-rgb: 13, 202, 240; --bs-info: rgb(var(--bs-info-rgb));
         --bs-light: #f8f9fa; --bs-dark: #212529;
         --bs-body-bg: #f8f9fc; --bs-border-color: #e3e6f0;
         --bs-card-border-radius: 0.45rem;
         --bs-card-box-shadow: 0 0.15rem 1.75rem 0 rgba(0,0,0,0.05);
     }
     body { font-family: 'Tajawal', sans-serif; background-color: var(--bs-body-bg); color: #5a5c69; }
     .card { border-radius: var(--bs-card-border-radius); box-shadow: var(--bs-card-box-shadow); border: 1px solid var(--bs-border-color); margin-bottom: 1.5rem; }
     .card-header { background-color: #fff; border-bottom: 1px solid var(--bs-border-color); padding: 0.9rem 1.25rem; }
     .card-title-css { font-weight: 600; color: var(--bs-dark); font-size:1.1rem; }
     .table thead th { background-color: var(--bs-light) !important; color: #5a5c69; font-weight: 600; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; border-bottom-width: 2px !important; white-space: nowrap; padding: 0.75rem 1rem; }
     .table td, .table th { vertical-align: middle; padding: 0.75rem 1rem; }
     .table tbody tr:hover { background-color: rgba(var(--bs-primary-rgb), 0.04); }

      .status-badge { padding: 0.4em 0.8em; border-radius: 50px; font-size: 0.8rem; font-weight: 500; letter-spacing: 0.3px; min-width: 90px; text-align: center; display: inline-block; }
      .status-new { background-color: rgba(var(--bs-info-rgb), 0.15); color: rgb(var(--bs-info-rgb)); border: 1px solid rgba(var(--bs-info-rgb), 0.3); }
      .status-pending_review { background-color: rgba(var(--bs-warning-rgb), 0.2); color: #a17d06; border: 1px solid rgba(var(--bs-warning-rgb), 0.4); }
      .status-approved { background-color: rgba(var(--bs-primary-rgb), 0.15); color: var(--bs-primary); border: 1px solid rgba(var(--bs-primary-rgb), 0.3); }
      .status-ready_for_pickup { background-color: #e2f0d9; color: #548235; border: 1px solid #c5e0b4;}
      .status-processing { background-color: #fff3cd; color: #664d03; border: 1px solid #ffecb5;}
      .status-dispensed { background-color: rgba(var(--bs-success-rgb), 0.15); color: rgb(var(--bs-success-rgb)); border: 1px solid rgba(var(--bs-success-rgb), 0.3); }
      .status-partially_dispensed { background-color: rgba(255, 165, 0, 0.15); color: #cc8400; border: 1px solid rgba(255, 165, 0, 0.3); }
      .status-cancelled_by_doctor, .status-cancelled_by_pharmacist, .status-cancelled_by_patient        { background-color: rgba(var(--bs-danger-rgb), 0.1); color: rgb(var(--bs-danger-rgb)); border: 1px solid rgba(var(--bs-danger-rgb), 0.2); }
      .status-on_hold { background-color: rgba(var(--bs-secondary-rgb), 0.15); color: rgb(var(--bs-secondary-rgb)); border: 1px solid rgba(var(--bs-secondary-rgb), 0.2); }
      .status-expired { background-color: #e9ecef; color: #495057; border: 1px solid #ced4da;}
      .status-refill_requested { background-color: #ffe6cc; color: #c45100; border: 1px solid #ffdab3; }
      .status-default { background-color: var(--bs-light); color: var(--bs-dark); border: 1px solid var(--bs-border-color); }

     .stat-card { display: flex; flex-direction: column; justify-content: space-between; padding: 1.25rem 1.5rem; border-left: 4px solid var(--bs-primary); transition: all 0.2s ease-in-out; position: relative; overflow: hidden; }
     .stat-card:hover { transform: translateY(-3px); box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1); }
     .stat-card .stat-icon { font-size: 2.8rem; opacity: 0.15; position: absolute; right: 15px; top: 50%; transform: translateY(-50%) rotate(-10deg); transition: opacity 0.3s, transform 0.3s; }
     .stat-card:hover .stat-icon { opacity: 0.25; transform: translateY(-55%) rotate(-5deg) scale(1.1); }
     .stat-card .stat-content { z-index: 1; }
     .stat-card .stat-number { font-size: 2.2rem; font-weight: 700; display: block; margin-bottom: 0.1rem; }
     .stat-card .stat-label { font-size: 0.85rem; font-weight: 500; color: #6c757d; text-transform: uppercase; letter-spacing: 0.03em; }
     .stat-card .card-footer { background-color: rgba(0,0,0,0.02) !important; border-top: 1px solid rgba(0,0,0,0.05) !important; padding-top: 0.5rem !important; padding-bottom: 0.5rem !important;}
     .stat-card .stat-link { font-size: 0.85rem; font-weight: 500; text-decoration: none !important; display:flex; align-items:center; justify-content: flex-start; /* For RTL start */ }
     .stat-card .stat-link i { transition: transform 0.2s ease-in-out; font-size:0.8rem; }
     .stat-card:hover .stat-link i { transform: translateX(-3px); } /* For LTR, use translateX(+3px) for RTL */


     .stat-card.border-danger { border-left-color: var(--bs-danger); }
     .stat-card.border-danger .stat-number, .stat-card.border-danger .stat-icon { color: var(--bs-danger); }
     .stat-card.border-warning { border-left-color: var(--bs-warning); }
     .stat-card.border-warning .stat-number, .stat-card.border-warning .stat-icon { color: var(--bs-warning); }
     .stat-card.border-info { border-left-color: var(--bs-info); }
     .stat-card.border-info .stat-number, .stat-card.border-info .stat-icon { color: var(--bs-info); }
     .stat-card.border-primary .stat-number, .stat-card.border-primary .stat-icon { color: var(--bs-primary); }

     .patient-avatar-sm { width: 32px; height: 32px; border-radius: 50%; margin-right: 10px; object-fit: cover; border: 2px solid #fff; box-shadow: 0 0 5px rgba(0,0,0,0.1);}
     .table-hover tbody tr { cursor: pointer; }

     /* Styling for the "filtered by" message */
     .filtered-by-info {
         background-color: rgba(var(--bs-primary-rgb), 0.05);
         border: 1px solid rgba(var(--bs-primary-rgb), 0.2);
         border-radius: var(--bs-card-border-radius);
         padding: 0.75rem 1.25rem;
         margin-bottom: 1rem;
         font-size: 0.9rem;
     }
     .filtered-by-info strong { color: var(--bs-primary-dark); }
     .filtered-by-info a { text-decoration: none; font-weight: 500; }
     .filtered-by-info a:hover { text-decoration: underline; }

    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header'); ?>
    
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto"><i class="fas fa-tachometer-alt text-primary me-2"></i><?php echo e(trans('doctors.prescriptions_monitoring')); ?> <?php echo e($pageTitleSuffix ?? ''); ?></h4>
                
            </div>
        </div>
         <div class="d-flex my-xl-auto right-content align-items-center">
             <?php if($request->has('filter_type') || $request->has('patient_search_compliance') || $request->has('compliance_status_filter') ): ?>
                 <a href="<?php echo e(route('doctor.prescriptions.adherenceDashboard')); ?>" class="btn btn-outline-secondary btn-sm me-2 ripple">
                     <i class="fas fa-undo me-1"></i> <?php echo e(trans('doctors.view_all_follow_ups')); ?>

                 </a>
             <?php endif; ?>
             <a href="<?php echo e(route('prescriptions.create')); ?>" class="btn btn-primary-gradient ripple">
                 <i class="fas fa-plus-circle me-1"></i> <?php echo e(trans('doctors.new_prescription')); ?>

             </a>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('Dashboard.messages_alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    
    <div class="row row-sm">
         
        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3" data-aos="fade-up">
            <a href="<?php echo e(route('doctor.prescriptions.adherenceDashboard', ['filter_type' => 'needs_decision'])); ?>#compliance_monitoring_section" class="text-decoration-none">
                 <div class="card stat-card border-danger h-100"> 
                     <div class="card-body stat-content">
                         <i class="fas fa-user-md-medical stat-icon"></i> 
                         <span class="stat-number"><?php echo e($needsDecisionCount ?? 0); ?></span>
                         <span class="stat-label"><?php echo e(trans('doctors.stat_card_requests_awaiting_review')); ?></span>
                     </div>
                     <div class="card-footer text-start">
                         <span class="stat-link text-danger"><?php echo e(trans('doctors.stat_card_view_requests')); ?> <i class="fas fa-arrow-circle-left ms-1"></i></span>
                     </div>
                 </div>
            </a>
        </div>
         
        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3" data-aos="fade-up" data-aos-delay="100">
            <a href="<?php echo e(route('doctor.prescriptions.adherenceDashboard', ['filter_type' => 'non_compliant'])); ?>#compliance_monitoring_section" class="text-decoration-none">
                 <div class="card stat-card border-warning h-100">
                     <div class="card-body stat-content">
                         <i class="fas fa-user-times stat-icon"></i> 
                         <span class="stat-number"><?php echo e($nonCompliantPatientsCount ?? 0); ?></span>
                         <span class="stat-label"><?php echo e(trans('doctors.stat_card_patients_needing_follow_up')); ?></span>
                     </div>
                     <div class="card-footer text-start">
                         <span class="stat-link text-warning"><?php echo e(trans('doctors.stat_card_view_list')); ?> <i class="fas fa-arrow-circle-left ms-1"></i></span>
                     </div>
                 </div>
             </a>
        </div>
         
        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-3" data-aos="fade-up" data-aos-delay="200">
            <a href="<?php echo e(route('doctor.prescriptions.adherenceDashboard', ['filter_type' => 'upcoming_refills'])); ?>#compliance_monitoring_section" class="text-decoration-none">
                 <div class="card stat-card border-info h-100">
                     <div class="card-body stat-content">
                         <i class="fas fa-calendar-plus stat-icon"></i> 
                         <span class="stat-number"><?php echo e($upcomingRefillsCount ?? 0); ?></span>
                         <span class="stat-label"><?php echo e(trans('doctors.stat_card_upcoming_renewals')); ?></span>
                     </div>
                     <div class="card-footer text-start">
                         <span class="stat-link text-info"><?php echo e(trans('doctors.stat_card_view_list')); ?> <i class="fas fa-arrow-circle-left ms-1"></i></span>
                     </div>
                 </div>
             </a>
        </div>
    </div>

     
     <?php if($filter_type): ?>
         <div class="alert filtered-by-info" role="alert" data-aos="fade-in" data-aos-delay="100">
             <i class="fas fa-info-circle me-2"></i>
             <?php echo e(trans('doctors.filter_info_box_prefix')); ?>

             <?php if($filter_type === 'needs_decision'): ?>
                 <strong><?php echo e(trans('doctors.filter_info_box_needs_decision')); ?></strong>.
             <?php elseif($filter_type === 'non_compliant'): ?>
                 <strong><?php echo e(trans('doctors.filter_info_box_non_compliant')); ?></strong>.
             <?php elseif($filter_type === 'upcoming_refills'): ?>
                 <strong><?php echo e(trans('doctors.filter_info_box_upcoming_refills')); ?></strong>.
             <?php else: ?>
                 <strong><?php echo e(trans('doctors.filter_info_box_custom')); ?></strong>.
             <?php endif; ?>
             <a href="<?php echo e(route('doctor.prescriptions.adherenceDashboard')); ?>" class="ms-2"> <i class="fas fa-times-circle me-1"></i><?php echo e(trans('doctors.remove_this_filter')); ?></a>
         </div>
     <?php endif; ?>


    
    <div class="card mt-4" id="compliance_monitoring_section" data-aos="fade-up" data-aos-delay="300">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
            <h4 class="card-title-css mb-2 mb-md-0">
                <i class="fas fa-clipboard-check text-primary me-2"></i>
                <?php if($filter_type === 'needs_decision'): ?>
                    <?php echo e(trans('doctors.title_list_requests_awaiting_review')); ?>

                <?php elseif($filter_type === 'non_compliant'): ?>
                    <?php echo e(trans('doctors.title_list_patients_needing_follow_up')); ?>

                <?php elseif($filter_type === 'upcoming_refills'): ?>
                    <?php echo e(trans('doctors.title_list_upcoming_renewals')); ?>

                <?php else: ?>
                    <?php echo e(trans('doctors.title_monitoring_active_prescriptions')); ?>

                <?php endif; ?>
                <span class="badge bg-light text-dark border ms-2"><?php echo e($monitoredPrescriptions->total()); ?></span>
            </h4>
            
            <form method="GET" action="<?php echo e(route('doctor.prescriptions.adherenceDashboard')); ?>" class="d-flex align-items-center doctor-dashboard-filter-form ms-md-auto" style="min-width: 250px;">
                 <?php if($filter_type): ?>
                     <input type="hidden" name="filter_type" value="<?php echo e($filter_type); ?>">
                 <?php endif; ?>
                 <input type="text" name="patient_search_compliance" class="form-control form-control-sm me-2" placeholder="<?php echo e(trans('doctors.search_patient_placeholder')); ?>" value="<?php echo e($request->patient_search_compliance); ?>">
                 <button type="submit" class="btn btn-sm btn-outline-primary ripple"><i class="fas fa-search"></i></button>
            </form>
        </div>
        <div class="card-body p-0">
            <?php if($monitoredPrescriptions->isNotEmpty()): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th style="min-width: 200px;"><?php echo e(trans('doctors.table_header_patient')); ?></th>
                                <th style="min-width: 180px;"><?php echo e(trans('doctors.table_header_prescription_main_medication')); ?></th>
                                <th style="min-width: 130px;"><?php echo e(trans('doctors.table_header_last_dispense_request_date')); ?></th>
                                <th style="min-width: 130px;"><?php echo e(trans('doctors.table_header_next_expected_renewal')); ?></th>
                                <th style="min-width: 160px;"><?php echo e(trans('doctors.table_header_compliance_assessment')); ?></th>
                                <th class="text-center"><?php echo e(trans('doctors.table_header_refills_remaining')); ?></th>
                                <th class="text-center"><?php echo e(trans('doctors.table_header_actions')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $monitoredPrescriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $prescription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                                <tr>
                                    <td><?php echo e($idx + $monitoredPrescriptions->firstItem()); ?></td>
                                    <td>
                                        <?php if($prescription->patient): ?>
                                            <div class="d-flex align-items-center">
                                                 <img src="<?php echo e($prescription->patient->image ? asset('Dashboard/img/patients/' . $prescription->patient->image->filename) : URL::asset('Dashboard/img/default_patient_avatar.png')); ?>" alt="patient" class="patient-avatar-sm">
                                                 <div>
                                                     <a href="<?php echo e(route('doctor.patient.details', $prescription->patient->id)); ?>" class="fw-500 text-primary d-block" data-bs-toggle="tooltip" title="<?php echo e(trans('doctors.view_patient_profile_summary')); ?>">
                                                         <?php echo e($prescription->patient->name); ?>

                                                     </a>
                                                     <small class="text-muted">ID: <?php echo e($prescription->patient->id); ?></small>
                                                 </div>
                                             </div>
                                        <?php else: ?>
                                             <span class="text-muted"><?php echo e(trans('doctors.patient_not_specified')); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo e(route('prescriptions.show', $prescription->id)); ?>" class="fw-500 d-block" data-bs-toggle="tooltip" title="<?php echo e(trans('doctors.view_full_prescription_details')); ?>">
                                            <?php echo e($prescription->prescription_number); ?>

                                        </a>
                                        <small class="text-muted">
                                            <?php if($prescription->items->isNotEmpty()): ?>
                                                <i class="fas fa-pills me-1 opacity-50"></i>
                                                <?php echo e($prescription->items->first()->medication->name ?? trans('doctors.unspecified_medication_in_list')); ?>

                                                <?php if($prescription->items_count > 1): ?>
                                                    (+<?php echo e($prescription->items_count - 1); ?>)
                                                <?php endif; ?>
                                            <?php else: ?>
                                                 <?php echo e(trans('doctors.no_medications_in_prescription')); ?>

                                            <?php endif; ?>
                                        </small>
                                    </td>
                                    <td class="text-nowrap"> 
                                        <?php echo e($prescription->dispensed_at ? $prescription->dispensed_at->translatedFormat('d M Y') : ($prescription->status == \App\Models\Prescription::STATUS_REFILL_REQUESTED ? ($prescription->updated_at ? $prescription->updated_at->translatedFormat('d M Y') . ' ' . trans('doctors.date_suffix_request') : 'N/A') : trans('doctors.not_yet_dispensed'))); ?>

                                    </td>
                                    <td class="text-nowrap">
                                       <?php echo e($prescription->next_refill_due_date ? $prescription->next_refill_due_date->translatedFormat('d M Y') : trans('doctors.unspecified_medication')); ?>

                                       <?php if($prescription->next_refill_due_date && $prescription->next_refill_due_date->isPast() && !in_array($prescription->status, [\App\Models\Prescription::STATUS_DISPENSED, \App\Models\Prescription::STATUS_REFILL_REQUESTED, \App\Models\Prescription::STATUS_CANCELLED_BY_DOCTOR, \App\Models\Prescription::STATUS_EXPIRED]) ): ?>
                                         <span class="badge bg-danger-transparent ms-1 px-1 py-0" data-bs-toggle="tooltip" title="<?php echo e(trans('doctors.overdue_refill_tooltip')); ?>"><i class="fas fa-exclamation-triangle tx-10"></i></span>
                                       <?php elseif($prescription->next_refill_due_date && $prescription->next_refill_due_date->isBetween(now(), now()->addDays(config('your_config_file.upcoming_refill_window_days', 7)))): ?>
                                         <span class="badge bg-warning-transparent ms-1 px-1 py-0" data-bs-toggle="tooltip" title="<?php echo e(trans('doctors.upcoming_refill_tooltip')); ?>"><i class="fas fa-hourglass-half tx-10"></i></span>
                                       <?php endif; ?>
                                    </td>
                                    <td>
                                         <span class="status-badge <?php echo e($prescription->compliance_badge_class ?? 'status-default'); ?>" data-bs-toggle="tooltip" title="<?php echo e(trans('doctors.compliance_analysis_tooltip', ['status' => $prescription->compliance_status ?? trans('doctors.not_assessed')])); ?>">
                                             <?php echo e($prescription->compliance_status ?? trans('doctors.not_assessed')); ?>

                                         </span>
                                    </td>
                                    <td class="text-center">
                                        <?php if($prescription->items->sum('refills_allowed') > 0): ?>
                                             <?php
                                                 $totalAllowed = $prescription->items->sum('refills_allowed');
                                                 $totalDone = $prescription->items->sum('refills_done');
                                                 $remainingRefills = $totalAllowed - $totalDone;
                                             ?>
                                            <span class="<?php echo e($remainingRefills <= 0 ? 'text-danger fw-bold' : ($remainingRefills <= 2 ? 'text-warning fw-500' : 'text-success')); ?>">
                                                <?php echo e($remainingRefills > 0 ? $remainingRefills : trans('doctors.no_refills_remaining')); ?>

                                            </span>
                                            <small class="text-muted d-block">/ <?php echo e($totalAllowed); ?></small>
                                        <?php else: ?>
                                             <span class="text-muted small" data-bs-toggle="tooltip" title="<?php echo e(trans('doctors.no_refills_specified_tooltip')); ?>">---</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                         <div class="btn-group" role="group">
                                             <a href="<?php echo e(route('prescriptions.show', $prescription->id)); ?>" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="<?php echo e(trans('doctors.view_edit_prescription_tooltip')); ?>">
                                                 <i class="fas fa-eye"></i>
                                             </a>
                                             <a href="<?php echo e(route('doctor.patient.details', $prescription->patient_id)); ?>" class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip" title="<?php echo e(trans('doctors.patient_profile_tooltip')); ?>">
                                                 <i class="fas fa-user-alt"></i>
                                             </a>
                                         </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <?php if($monitoredPrescriptions->hasPages()): ?>
                    <div class="mt-3 px-3 pb-3 d-flex justify-content-center">
                        <?php echo e($monitoredPrescriptions->links('pagination::bootstrap-5')); ?>

                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="text-center p-5" data-aos="zoom-in">
                     <?php if($filter_type): ?>
                        <i class="fas fa-filter fa-3x text-muted mb-3"></i>
                        <h5 class="fw-bold"><?php echo e(trans('doctors.empty_state_filtered_title')); ?></h5>
                        <p class="text-muted"><?php echo e(trans('doctors.empty_state_filtered_message')); ?> <a href="<?php echo e(route('doctor.prescriptions.adherenceDashboard')); ?>"><?php echo e(trans('doctors.view_all_follow_ups')); ?></a>.</p>
                     <?php else: ?>
                        <i class="fas fa-clipboard-check fa-3x text-success mb-3"></i>
                        <h5 class="fw-bold"><?php echo e(trans('doctors.empty_state_general_title')); ?></h5>
                        <p class="text-muted"><?php echo e(trans('doctors.empty_state_general_message')); ?></p>
                     <?php endif; ?>
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
        $(document).ready(function() {
            AOS.init({
                duration: 600, // slightly faster
                once: true,
                offset: 60
            });

            // Initialize Tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl, {
                    boundary: document.body,
                    fallbackPlacements: ['top', 'bottom', 'right', 'left'],
                    container: 'body' // Important for elements within tables or complex layouts
                });
            });

            // Session messages
            <?php if(session('success')): ?>
                notif({msg: "<div class='d-flex align-items-center p-2'><i class='fas fa-check-circle fa-lg me-2 text-success'></i><div style='font-size: 0.95rem;'><?php echo e(session('success')); ?></div></div>", type: "success", position: "top-center", autohide: true, timeout: 5000});
            <?php endif; ?>
            <?php if(session('error')): ?>
                notif({msg: "<div class='d-flex align-items-center p-2'><i class='fas fa-exclamation-triangle fa-lg me-2 text-danger'></i><div style='font-size: 0.95rem;'><?php echo e(session('error')); ?></div></div>",type: "error",position: "top-center",autohide: true,timeout: 7000});
            <?php endif; ?>

             // Smooth scroll for anchor links from stat cards (if target is on the same page)
             $('a.stat-link[href^="#"], a.nav-link[href^="#"]').on('click', function(event) {
                 var target = $(this.getAttribute('href'));
                 if( target.length && window.location.pathname === this.pathname ) { // Check if target is on the same page
                     event.preventDefault();
                     $('html, body').stop().animate({
                         scrollTop: target.offset().top - 80 // Adjust offset for fixed navbar or other elements
                     }, 600, 'swing'); // 'swing' for a bit more natural scroll
                 }
             });

             // If a filter_type is active, scroll to the compliance_monitoring_section automatically
             // This ensures the doctor sees the filtered list when coming from a stat card click
             const urlParams = new URLSearchParams(window.location.search);
             const filterTypeFromUrl = urlParams.get('filter_type');
             if (filterTypeFromUrl) {
                 const targetSection = $('#compliance_monitoring_section');
                 if (targetSection.length) {
                      $('html, body').scrollTop(targetSection.offset().top - 80);
                 }
             }

        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Dashboard.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/Doctors/Prescriptions/adherence_dashboard.blade.php ENDPATH**/ ?>