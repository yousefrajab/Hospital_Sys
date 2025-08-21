<?php $__env->startSection('title', 'طلبات تجديد الوصفات للموافقة'); ?>

<?php $__env->startSection('css'); ?>
    <?php echo \Illuminate\View\Factory::parentPlaceholder('css'); ?>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="<?php echo e(URL::asset('dashboard/plugins/notify/css/notifIt.css')); ?>" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet"> 
    <style>
        /* --- Root Variables & Basic Styling --- */
        :root {
            --bs-primary-rgb: 67, 97, 238; --bs-primary: rgb(var(--bs-primary-rgb));
            --bs-success-rgb: 25, 135, 84; --bs-success: rgb(var(--bs-success-rgb));
            --bs-warning-rgb: 255, 193, 7; --bs-warning: rgb(var(--bs-warning-rgb));
            --bs-danger-rgb: 220, 53, 69; --bs-danger: rgb(var(--bs-danger-rgb));
            --bs-danger-rgb-light: 253, 237, 239; /* Added for danger-transparent */
            --bs-secondary-rgb: 108, 117, 125;
            --bs-body-bg: #f8f9fc;
            --bs-border-color: #e3e6f0;
            --bs-card-border-radius: 0.45rem;
            --bs-card-box-shadow: 0 0.15rem 1.75rem 0 rgba(0,0,0,0.05);
        }
        body { font-family: 'Tajawal', sans-serif; background-color: var(--bs-body-bg); color: #5a5c69; }
        .card { border-radius: var(--bs-card-border-radius); box-shadow: var(--bs-card-box-shadow); border: 1px solid var(--bs-border-color); margin-bottom: 1.5rem; }
        .card-header { background-color: #fff; border-bottom: 1px solid var(--bs-border-color); padding: 0.9rem 1.25rem; }
        .card-title-css { font-weight: 600; color: #212529; font-size:1.1rem; }
        .table thead th { background-color: #f8f9fa !important; color: #5a5c69; font-weight: 600; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; border-bottom-width: 2px !important; white-space: nowrap; padding: 0.75rem 1rem; }
        .table td, .table th { vertical-align: middle; padding: 0.75rem 1rem; }
        .table tbody tr:hover { background-color: rgba(var(--bs-primary-rgb), 0.04); }
        .patient-avatar-sm { width: 35px; height: 35px; border-radius: 50%; margin-right: 10px; object-fit: cover; border: 2px solid #fff; box-shadow: 0 0 5px rgba(0,0,0,0.1);}
        .fw-500 {font-weight: 500!important;}
        .tx-primary {color: var(--bs-primary);}
        .text-warning-dark {color: #c77c03 !important;} /* Darker warning text for better contrast on light backgrounds */
        .text-danger-dark{color:var(--bs-danger)!important}
        .action-buttons .btn { min-width: 40px; padding: 0.3rem 0.6rem; }
        .action-buttons .btn-label { display: none; }
        @media (min-width: 768px) { .action-buttons .btn-label { display: inline; } }


        .status-badge { padding: 0.4em 0.8em; border-radius: 50px; font-size: 0.8rem; font-weight: 500; letter-spacing: 0.3px; min-width: 90px; text-align: center; display: inline-block; }
        .status-refill_requested { background-color: #ffe6cc; color: #c45100; border: 1px solid #ffdab3; }
        .request-reason-badge { font-size: 0.8rem; padding: 0.35em 0.7em; font-weight:500;}

        .empty-state-container { text-align: center; padding: 2.5rem 1rem; background-color: #f8f9fa; border-radius: 0.45rem; border: 1px dashed #e3e6f0; }
        .empty-state-container i.empty-icon {  font-size: 3.5rem; color: var(--bs-primary); opacity: 0.5; margin-bottom: 1rem; display: block; }
        .empty-state-container h5 { font-weight: 600; color: #212529; margin-bottom: 0.5rem; }
        .empty-state-container p { color: #6c757d; font-size: 0.95rem; }

        /* Modal Specific Styles */
        .modal-header .btn-close-white { filter: invert(1) grayscale(100%) brightness(200%); } /* Make close button more visible on dark headers */
        .modal-body .item-for-approval {
            padding: 0.75rem;
            border: 1px solid var(--bs-border-color);
            border-radius: var(--bs-card-border-radius);
            margin-bottom: 0.75rem;
            background-color: #fcfdff;
        }
        .modal-body .item-for-approval:last-child { margin-bottom: 0; }
        .modal-body .item-for-approval .form-check-input { margin-top: 0.4em; }
        .modal-body .form-label.sub-label { font-size: 0.85rem; margin-bottom: 0.2rem; color: #555; }
        .modal-body .form-control.form-control-sm-custom { font-size:0.85rem; padding: .25rem .5rem; max-width: 80px; display:inline-block;}
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header'); ?>
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto"><i class="fas fa-user-shield text-warning me-2"></i><?php echo e(trans('doctors.requests_needing_decision')); ?></h4>
                <span class="text-muted mt-1 tx-13 mx-2">/</span>
                <span class="mt-1 tx-13"><?php echo e(trans('doctors.review_renewal_requests')); ?></span>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('Dashboard.messages_alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="card" data-aos="fade-up">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
             <h4 class="card-title-css mb-2 mb-md-0">
                 <i class="fas fa-list-check me-2 text-warning-dark"></i> <?php echo e(trans('doctors.renewal_requests_list_title')); ?>

                 <?php if($approvalRequests->total() > 0): ?>
                     <span class="badge bg-warning-transparent text-warning-dark rounded-pill ms-2"><?php echo e($approvalRequests->total()); ?> <?php echo e(trans('doctors.request_unit')); ?></span>
                 <?php endif; ?>
             </h4>
             <form method="GET" action="<?php echo e(route('doctor.prescriptions.approvalRequests')); ?>" class="d-flex align-items-center" style="min-width: 300px;">
                 <input type="text" name="search_patient_approval" class="form-control form-control-sm me-2" placeholder="<?php echo e(trans('doctors.search_patient_placeholder')); ?>" value="<?php echo e($request->search_patient_approval ?? ''); ?>">
                 <button type="submit" class="btn btn-sm btn-outline-primary ripple"><i class="fas fa-search"></i> <?php echo e(trans('doctors.search_button')); ?></button>
                 <?php if(request('search_patient_approval')): ?>
                    <a href="<?php echo e(route('doctor.prescriptions.approvalRequests')); ?>" class="btn btn-sm btn-link text-danger ms-2" data-bs-toggle="tooltip" title="<?php echo e(trans('doctors.cancel_search_tooltip')); ?>"><i class="fas fa-times"></i></a>
                 <?php endif; ?>
            </form>
        </div>
        <div class="card-body p-0">
            <?php if($approvalRequests->isNotEmpty()): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th style="min-width: 200px;"><?php echo e(trans('doctors.table_header_patient')); ?></th>
                                <th style="min-width: 150px;"><?php echo e(trans('doctors.table_header_original_prescription')); ?></th>
                                <th style="min-width: 160px;"><?php echo e(trans('doctors.table_header_medication_summary')); ?></th>
                                <th style="min-width: 130px;"><?php echo e(trans('doctors.table_header_request_date')); ?></th>
                                <th style="min-width: 180px;"><?php echo e(trans('doctors.table_header_reason_for_approval')); ?></th>
                                <th class="text-center" style="min-width: 180px;"><?php echo e(trans('doctors.table_header_action')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $approvalRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $prescription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($idx + $approvalRequests->firstItem()); ?></td>
                                    <td>
                                        <?php if($prescription->patient): ?>
                                            <div class="d-flex align-items-center">
                                                 <img src="<?php echo e($prescription->patient->image ? asset('Dashboard/img/patients/' . $prescription->patient->image->filename) : URL::asset('Dashboard/img/default_patient_avatar.png')); ?>" alt="patient" class="patient-avatar-sm">
                                                 <div>
                                                     <a href="<?php echo e(route('doctor.patient.details', $prescription->patient->id)); ?>" class="fw-500 text-primary d-block" data-bs-toggle="tooltip" title="<?php echo e(trans('doctors.view_full_patient_profile')); ?>">
                                                         <?php echo e($prescription->patient->name); ?>

                                                     </a>
                                                     <small class="text-muted d-block">
                                                         <?php echo e($prescription->patient->gender == 1 ? trans('doctors.gender_male') : ($prescription->patient->gender == 2 ? trans('doctors.gender_female') : '')); ?>

                                                         <?php echo e($prescription->patient->Date_Birth ? '، ' . $prescription->patient->Date_Birth->age . ' ' . trans('doctors.years_old') : ''); ?>

                                                     </small>
                                                 </div>
                                             </div>
                                        <?php else: ?>
                                             <span class="text-muted"><?php echo e(trans('doctors.patient_not_specified')); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo e(route('prescriptions.show', $prescription->id)); ?>" class="fw-500" data-bs-toggle="tooltip" title="<?php echo e(trans('doctors.view_original_prescription_details')); ?>">
                                            <?php echo e($prescription->prescription_number); ?>

                                        </a>
                                        <small class="text-muted d-block"><?php echo e(trans('doctors.created_at_prefix')); ?> <?php echo e($prescription->prescription_date->translatedFormat('d M Y')); ?></small>
                                    </td>
                                    <td>
                                        <?php if($prescription->items->isNotEmpty()): ?>
                                            <ul class="list-unstyled mb-0 small">
                                                <?php $__currentLoopData = $prescription->items->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                                                    <li data-bs-toggle="tooltip" title="<?php echo e($item->medication->name ?? ''); ?> <?php echo e($item->medication->strength ?? ''); ?>"><i class="fas fa-capsule fa-xs text-muted me-1"></i><?php echo e(Str::limit($item->medication->name ?? trans('doctors.unspecified_medication'), 25)); ?></li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($prescription->items_count > 2): ?>
                                                    <li class="text-muted"><?php echo e(trans('doctors.and_x_more_medications', ['count' => $prescription->items_count - 2])); ?></li>
                                                <?php endif; ?>
                                            </ul>
                                        <?php else: ?>
                                             <span class="text-muted small"><?php echo e(trans('doctors.no_medications_in_prescription')); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-nowrap">
                                        <span data-bs-toggle="tooltip" title="<?php echo e($prescription->updated_at->translatedFormat('h:i A')); ?>">
                                            <?php echo e($prescription->updated_at->translatedFormat('d M Y')); ?>

                                        </span>
                                    </td>
                                    <td>
                                         <?php
                                             $reasonText = trans('doctors.reason_patient_request');
                                             $allOriginalRefillsDone = true;
                                             $hadRefillableItems = false;
                                             foreach($prescription->items as $pItem){
                                                 if($pItem->refills_allowed > 0) $hadRefillableItems = true;
                                                 if($pItem->refills_allowed > $pItem->refills_done) $allOriginalRefillsDone = false;
                                             }
                                             if($hadRefillableItems && $allOriginalRefillsDone){
                                                $reasonText = trans('doctors.reason_refills_exhausted');
                                             } elseif ($prescription->is_chronic_prescription && !$hadRefillableItems) {
                                                $reasonText = trans('doctors.reason_chronic_prescription_renewal');
                                             }
                                         ?>
                                         <span class="badge bg-light border text-primary-dark request-reason-badge" data-bs-toggle="tooltip" title="<?php echo e(trans('doctors.reason_tooltip')); ?>">
                                             <i class="fas fa-info-circle me-1"></i><?php echo e($reasonText); ?>

                                         </span>
                                    </td>
                                    <td class="text-center action-buttons">
                                        <button type="button" class="btn btn-sm btn-success-gradient" data-toggle="modal" data-target="#approveRefillModal-<?php echo e($prescription->id); ?>" title="<?php echo e(trans('doctors.action_approve_renewal')); ?>">
                                            <i class="fas fa-check-circle"></i> <span class="btn-label"><?php echo e(trans('doctors.approve_button')); ?></span>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger-gradient" data-toggle="modal" data-target="#denyRefillModal-<?php echo e($prescription->id); ?>" title="<?php echo e(trans('doctors.action_deny_renewal')); ?>">
                                            <i class="fas fa-times-circle"></i> <span class="btn-label"><?php echo e(trans('doctors.deny_button')); ?></span>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <?php if($approvalRequests->hasPages()): ?>
                    <div class="mt-3 px-3 pb-3 d-flex justify-content-center">
                        <?php echo e($approvalRequests->appends(request()->query())->links('pagination::bootstrap-5')); ?>

                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="empty-state-container p-5" data-aos="zoom-in-up">
                    <i class="fas fa-clipboard-check empty-icon text-success opacity-75"></i>
                    <h5 class="mt-3 fw-bold"><?php echo e(trans('doctors.empty_state_title')); ?></h5>
                    <p class="text-muted col-md-8 mx-auto mt-2">
                        <?php echo e(trans('doctors.empty_state_message')); ?>

                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    
    <?php $__currentLoopData = $approvalRequests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prescription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        
        <div class="modal fade" id="approveRefillModal-<?php echo e($prescription->id); ?>" tabindex="-1" aria-labelledby="approveRefillModalLabel-<?php echo e($prescription->id); ?>" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered"> 
                <form action="<?php echo e(route('doctor.prescriptions.approveRefill', $prescription->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-content">
                        <div class="modal-header bg-success text-white">
                            <h5 class="modal-title" id="approveRefillModalLabel-<?php echo e($prescription->id); ?>">
                                <i class="fas fa-check-double me-2"></i><?php echo e(trans('doctors.approve_modal_title')); ?> <strong class="font-monospace"><?php echo e($prescription->prescription_number); ?></strong>
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                            <p><strong><?php echo e(trans('doctors.table_header_patient')); ?>:</strong> <?php echo e($prescription->patient->name ?? trans('doctors.patient_not_specified')); ?>

                                <a href="<?php echo e(route('doctor.patient.details', $prescription->patient_id)); ?>" target="_blank" class="ms-2 small"><?php echo e(trans('doctors.view_profile_link')); ?></a>
                            </p>
                            <hr>
                            <h6><i class="fas fa-pills me-2 text-primary"></i><?php echo e(trans('doctors.medications_to_renew_label')); ?></h6>
                            <?php if($prescription->items->isNotEmpty()): ?>
                                <?php $__currentLoopData = $prescription->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="item-for-approval" data-aos="fade-in">
                                        <div class="d-flex align-items-center mb-2">
                                            <input class="form-check-input me-2" type="checkbox"
                                                   name="items[<?php echo e($item->id); ?>][renew]"
                                                   id="renew_item_<?php echo e($item->id); ?>_for_pres_<?php echo e($prescription->id); ?>"
                                                   checked
                                                   onchange="toggleRefillFields(this, <?php echo e($item->id); ?>, <?php echo e($prescription->id); ?>)">
                                            <label class="form-check-label fw-500" for="renew_item_<?php echo e($item->id); ?>_for_pres_<?php echo e($prescription->id); ?>">
                                                <?php echo e($item->medication->name ?? trans('doctors.unknown_medication')); ?>

                                                <small class="text-muted">(<?php echo e($item->medication->strength ?? ''); ?> <?php echo e($item->medication->dosage_form_display ?? ''); ?>)</small>
                                            </label>
                                        </div>
                                        <div class="ms-4 ps-2 row gx-2 item-renewal-fields" id="fields_item_<?php echo e($item->id); ?>_pres_<?php echo e($prescription->id); ?>">
                                            <div class="col-md-5 mb-2">
                                                <label for="refills_allowed_new_<?php echo e($item->id); ?>" class="form-label sub-label"><?php echo e(trans('doctors.new_refills_count_label')); ?></label>
                                                <input type="number" class="form-control form-control-sm-custom"
                                                       id="refills_allowed_new_<?php echo e($item->id); ?>"
                                                       name="items[<?php echo e($item->id); ?>][refills_allowed_new]"
                                                       min="0" max="12"
                                                       value="<?php echo e($item->refills_allowed > 0 ? $item->refills_allowed : ($prescription->is_chronic_prescription ? 1 : 0)); ?>"
                                                       required>
                                            </div>
                                            <div class="col-md-7 mb-2">
                                                <label for="item_notes_new_<?php echo e($item->id); ?>" class="form-label sub-label"><?php echo e(trans('doctors.item_note_label')); ?></label>
                                                <input type="text" class="form-control form-control-sm"
                                                       id="item_notes_new_<?php echo e($item->id); ?>"
                                                       name="items[<?php echo e($item->id); ?>][item_notes_new]"
                                                       placeholder="<?php echo e(trans('doctors.item_note_placeholder')); ?>">
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <p class="text-danger"><?php echo e(trans('doctors.no_medication_items_in_prescription_error')); ?></p>
                            <?php endif; ?>
                            <hr class="my-3">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                     <label for="new_next_refill_due_date-<?php echo e($prescription->id); ?>" class="form-label"><?php echo e(trans('doctors.next_renewal_due_date_label')); ?></label>
                                     <input type="text" class="form-control flatpickr-modal" id="new_next_refill_due_date-<?php echo e($prescription->id); ?>" name="new_next_refill_due_date" placeholder="YYYY-MM-DD"
                                            value="<?php echo e($prescription->next_refill_due_date ? $prescription->next_refill_due_date->addMonth()->toDateString() : now()->addMonth()->toDateString()); ?>">
                                     <small class="form-text text-muted"><?php echo e(trans('doctors.next_renewal_due_date_help')); ?></small>
                                 </div>
                            </div>
                            <div class="mb-2">
                                <label for="general_approval_notes-<?php echo e($prescription->id); ?>" class="form-label"><?php echo e(trans('doctors.general_approval_notes_label')); ?></label>
                                <textarea class="form-control" id="general_approval_notes-<?php echo e($prescription->id); ?>" name="general_approval_notes" rows="2" placeholder="<?php echo e(trans('doctors.general_approval_notes_placeholder')); ?>"></textarea>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="notify_patient_approval-<?php echo e($prescription->id); ?>" name="notify_patient_on_approval" checked>
                                <label class="form-check-label" for="notify_patient_approval-<?php echo e($prescription->id); ?>">
                                    <?php echo e(trans('doctors.notify_patient_on_approval_checkbox')); ?>

                                </label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><?php echo e(trans('doctors.close_button')); ?></button>
                            <button type="submit" class="btn btn-success"><i class="fas fa-check-circle me-1"></i> <?php echo e(trans('doctors.confirm_approval_button')); ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        
        <div class="modal fade" id="denyRefillModal-<?php echo e($prescription->id); ?>" tabindex="-1" aria-labelledby="denyRefillModalLabel-<?php echo e($prescription->id); ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                 <form action="<?php echo e(route('doctor.prescriptions.denyRefill', $prescription->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title" id="denyRefillModalLabel-<?php echo e($prescription->id); ?>">
                                <i class="fas fa-times-circle me-2"></i><?php echo e(trans('doctors.deny_modal_title')); ?> <strong class="font-monospace"><?php echo e($prescription->prescription_number); ?></strong>
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong><?php echo e(trans('doctors.table_header_patient')); ?>:</strong> <?php echo e($prescription->patient->name ?? trans('doctors.patient_not_specified')); ?></p>
                            <div class="mb-3">
                                <label for="denial_reason-<?php echo e($prescription->id); ?>" class="form-label"><?php echo e(trans('doctors.denial_reason_label')); ?></label>
                                <textarea class="form-control" id="denial_reason-<?php echo e($prescription->id); ?>" name="denial_reason" rows="4" placeholder="<?php echo e(trans('doctors.denial_reason_placeholder')); ?>" required minlength="10"></textarea>
                            </div>
                             <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="notify_patient_denial-<?php echo e($prescription->id); ?>" name="notify_patient_on_denial" checked>
                                <label class="form-check-label" for="notify_patient_denial-<?php echo e($prescription->id); ?>">
                                    <?php echo e(trans('doctors.notify_patient_on_denial_checkbox')); ?>

                                </label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><?php echo e(trans('doctors.close_button')); ?></button>
                            <button type="submit" class="btn btn-danger"><i class="fas fa-ban me-1"></i> <?php echo e(trans('doctors.confirm_denial_button')); ?></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    

<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <?php echo \Illuminate\View\Factory::parentPlaceholder('js'); ?>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="<?php echo e(URL::asset('dashboard/plugins/notify/js/notifIt.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('dashboard/plugins/notify/js/notifit-custom.js')); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/ar.js"></script>

    <script>
        function toggleRefillFields(checkbox, itemId, prescriptionId) {
            const fieldsDiv = document.getElementById(`fields_item_${itemId}_pres_${prescriptionId}`);
            const refillsInput = document.getElementById(`refills_allowed_new_${itemId}`);
            if (checkbox.checked) {
                fieldsDiv.style.display = 'flex'; // Or 'block' or 'grid' depending on your row layout
                if(refillsInput) refillsInput.required = true;
            } else {
                fieldsDiv.style.display = 'none';
                if(refillsInput) refillsInput.required = false;
            }
        }

        $(document).ready(function() {
            AOS.init({
                duration: 600,
                once: true,
                offset: 60
            });

            flatpickr(".flatpickr-modal", {
                 dateFormat: "Y-m-d",
                 locale: "ar",
                 allowInput: true,
                 minDate: "today"
             });

            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl, { boundary: document.body, container: 'body' });
            });

            <?php if(session('success')): ?>
                notif({msg: "<div class='d-flex align-items-center p-2'><i class='fas fa-check-circle fa-lg me-2 text-success'></i><div style='font-size: 0.95rem;'><?php echo e(session('success')); ?></div></div>", type: "success", position: "top-center", autohide: true, timeout: 6000});
            <?php endif; ?>
            <?php if(session('error')): ?>
                notif({msg: "<div class='d-flex align-items-center p-2'><i class='fas fa-exclamation-triangle fa-lg me-2 text-danger'></i><div style='font-size: 0.95rem;'><?php echo e(session('error')); ?></div></div>",type: "error",position: "top-center",autohide: true,timeout: 8000});
            <?php endif; ?>

            // عند فتح modal الموافقة، تأكد من أن حقول الدواء التي ليست checked تكون مخفية
             $('.modal[id^="approveRefillModal-"]').on('show.bs.modal', function (event) {
                $(this).find('.item-for-approval').each(function() {
                    var checkbox = $(this).find('input[type="checkbox"][name*="[renew]"]');
                    var fieldsDiv = $(this).find('.item-renewal-fields');
                    var refillsInput = fieldsDiv.find('input[name*="[refills_allowed_new]"]');

                    if (checkbox.is(':checked')) {
                        fieldsDiv.show();
                        if(refillsInput.length) refillsInput.prop('required', true);
                    } else {
                        fieldsDiv.hide();
                         if(refillsInput.length) refillsInput.prop('required', false);
                    }
                });
             });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Dashboard.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/Doctors/Prescriptions/approval_requests.blade.php ENDPATH**/ ?>