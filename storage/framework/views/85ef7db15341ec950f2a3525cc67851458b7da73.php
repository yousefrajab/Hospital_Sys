 

<?php $__env->startSection('title', 'وصفات طبية قيد الانتظار'); ?>

<?php $__env->startSection('css'); ?>
    <?php echo \Illuminate\View\Factory::parentPlaceholder('css'); ?>
    <link href="<?php echo e(URL::asset('dashboard/plugins/select2/css/select2.min.css')); ?>" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="<?php echo e(URL::asset('dashboard/plugins/notify/css/notifIt.css')); ?>" rel="stylesheet" />
    <style>
        /* --- نفس أنماط CSS من dispensed_index.blade.php مع تعديلات طفيفة إذا لزم الأمر --- */
        :root {
            --bs-primary-rgb: 67, 97, 238;
            --bs-primary: rgb(var(--bs-primary-rgb));
            --bs-success-rgb: 25, 135, 84;
            --bs-info-rgb: 13, 202, 240;
            --bs-warning-rgb: 255, 193, 7;
            --bs-warning: rgb(var(--bs-warning-rgb)); /* للتأكيد على اللون */
            --bs-danger-rgb: 220, 53, 69;
            --bs-light-rgb: 248, 249, 252;
            --bs-dark-rgb: 33, 37, 41;
            --bs-body-bg: #f4f6f9;
            --bs-border-color: #dee2e6;
            --bs-card-border-radius: 0.75rem;
            --bs-card-box-shadow: 0 0.15rem 1.25rem rgba(58, 59, 69, 0.1);
            --bs-body-color: #525f7f;
        }
        body { font-family: 'Tajawal', sans-serif; background-color: var(--bs-body-bg); color: var(--bs-body-color); }
        .card-custom { border-radius: var(--bs-card-border-radius); box-shadow: var(--bs-card-box-shadow); border: 1px solid var(--bs-border-color); background-color: #fff; margin-bottom: 1.5rem; }
        .card-custom .card-header { background-color: rgb(var(--bs-light-rgb)); border-bottom: 1px solid var(--bs-border-color); padding: 1rem 1.25rem; }
        .card-custom .card-title { font-weight: 600; color: var(--bs-primary); margin-bottom: 0; }
        .card-custom .card-title i { margin-left: 0.5rem; }
        .table-prescriptions thead th { background-color: rgba(var(--bs-primary-rgb), 0.07); color: var(--bs-primary); font-weight: 600; font-size: 0.85rem; text-transform: uppercase; padding: 0.8rem 1rem; border-bottom-width: 2px !important; white-space: nowrap; }
        .table-prescriptions tbody td { vertical-align: middle; padding: 0.8rem 1rem; font-size: 0.9rem; border-top: 1px solid var(--bs-border-color); }
        .table-prescriptions tbody tr:hover { background-color: rgba(var(--bs-primary-rgb), 0.03); }
        .status-badge { padding: 0.4em 0.8em; border-radius: 50px; font-size: 0.75rem; font-weight: 500; }
        .status-on_hold { background-color: rgba(var(--bs-warning-rgb), 0.2); color: #b88100; border:1px solid rgba(var(--bs-warning-rgb),0.3); } /* لون مميز للحالة قيد الانتظار */
        .action-btn { font-weight: 500; padding: 0.45rem 0.9rem; font-size: 0.8rem; border-radius: 0.3rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .action-btn i { margin-left: 0.3rem; }
        .filter-form .form-control, .filter-form .form-select { font-size: 0.9rem; }
        .filter-form .btn { font-size: 0.9rem; }
        .empty-state { text-align: center; padding: 2.5rem 1rem; background-color:rgb(var(--bs-light-rgb)); border-radius: var(--bs-card-border-radius); border: 1px dashed var(--bs-border-color); }
        .empty-state i { font-size: 3rem; color: var(--bs-border-color); margin-bottom: 1rem; display: block; }
        .empty-state h5 { font-weight: 600; color: var(--bs-dark-rgb); margin-bottom: 0.5rem; }
        .empty-state p { color: var(--bs-body-color); font-size: 0.95rem; }
        .pagination .page-link { font-size:0.9rem; }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header'); ?>
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto"><i class="fas fa-pills text-primary me-2"></i><?php echo e(trans('phrE.pharmacist')); ?></h4>
                <span class="text-muted mt-1 tx-13 mx-2">/</span>
                <span class="text-muted mt-1 tx-13"><?php echo e(trans('phrE.on_hold_medical_prescriptions')); ?></span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <a href="<?php echo e(route('pharmacy_employee.prescriptions.index')); ?>" class="btn btn-outline-primary btn-sm ripple-effect">
                 <i class="fas fa-inbox me-1"></i> <?php echo e(trans('phrE.view_incoming_prescriptions_button')); ?>

            </a>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('Dashboard.messages_alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="card card-custom filter-card" data-aos="fade-down">
        <div class="card-header pb-0">
            <h5 class="card-title mb-0"><i class="fas fa-filter me-2"></i><?php echo e(trans('phrE.filter_on_hold_prescriptions')); ?></h5>
        </div>
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('pharmacy_employee.prescriptions.on_hold')); ?>">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-5 col-md-6">
                        <label for="search_on_hold" class="form-label"><?php echo e(trans('phrE.search_by_prescription_patient_doctor_onhold')); ?></label>
                        <input type="text" name="search_term" id="search_on_hold" class="form-control"
                               placeholder="<?php echo e(trans('phrE.search_term_placeholder')); ?>" value="<?php echo e($request->input('search_term')); ?>">
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <label for="date_filter_onhold_page" class="form-label"><?php echo e(trans('phrE.date_put_on_hold')); ?></label>
                        <input type="text" name="date_filter_onhold" id="date_filter_onhold_page" class="form-control flatpickr-date"
                               placeholder="YYYY-MM-DD" value="<?php echo e($request->input('date_filter_onhold')); ?>">
                    </div>
                    <div class="col-lg-3 col-md-12">
                        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> <?php echo e(trans('phrE.search')); ?></button>
                        <?php if(request()->hasAny(['search_term', 'date_filter_onhold'])): ?>
                            <a href="<?php echo e(route('pharmacy_employee.prescriptions.on_hold')); ?>" class="btn btn-outline-secondary w-100 mt-2 btn-sm">
                                <i class="fas fa-eraser me-1"></i> <?php echo e(trans('phrE.clear')); ?>

                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card card-custom mt-4" data-aos="fade-up" data-aos-delay="100">
        <div class="card-header">
            <h4 class="card-title"><?php echo e(trans('phrE.on_hold_prescriptions_list_title', ['count' => $onHoldPrescriptions->total()])); ?></h4>
        </div>
        <div class="card-body p-0">
            <?php if($onHoldPrescriptions->isNotEmpty()): ?>
                <div class="table-responsive">
                    <table class="table table-prescriptions table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th><?php echo e(trans('phrE.prescription_number_col')); ?></th>
                                <th><?php echo e(trans('phrE.patient_name_col')); ?></th>
                                <th><?php echo e(trans('phrE.attending_doctor_col')); ?></th>
                                <th><?php echo e(trans('phrE.prescription_date_col')); ?></th>
                                <th class="text-center"><?php echo e(trans('phrE.status_col')); ?></th>
                                <th><?php echo e(trans('phrE.last_update_reason_col')); ?></th>
                                <th class="text-center"><?php echo e(trans('phrE.action_col')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $onHoldPrescriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prescription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($loop->iteration + $onHoldPrescriptions->firstItem() - 1); ?></td>
                                    <td><strong><?php echo e($prescription->prescription_number); ?></strong></td>
                                    <td><?php echo e($prescription->patient->name ?? trans('phrE.unknown_patient')); ?></td>
                                    <td><?php echo e($prescription->doctor->name ?? trans('phrE.unspecified_doctor')); ?></td>
                                    <td><?php echo e($prescription->prescription_date->translatedFormat('d M Y')); ?></td>
                                    <td class="text-center">
                                        <span class="status-badge status-on_hold"><?php echo e(trans('phrE.status_on_hold')); ?></span>
                                    </td>
                                    <td>
                                        <?php echo e($prescription->updated_at->translatedFormat('d M Y H:i')); ?>

                                        <?php if($prescription->pharmacy_notes): ?>
                                            <small class="d-block text-muted" data-bs-toggle="tooltip" title="<?php echo e(trans('phrE.pharmacy_notes_tooltip', ['notes' => $prescription->pharmacy_notes])); ?>"><i class="fas fa-info-circle text-info me-1"></i><?php echo e(Str::limit($prescription->pharmacy_notes, 30)); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?php echo e(route('pharmacy_employee.prescriptions.dispense.form', $prescription->id)); ?>"
                                           class="btn btn-warning action-btn btn-sm">
                                            <i class="fas fa-play-circle"></i> <?php echo e(trans('phrE.resume_dispensing')); ?>

                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <?php if($onHoldPrescriptions->hasPages()): ?>
                    <div class="p-3 border-top">
                        <?php echo e($onHoldPrescriptions->links('pagination::bootstrap-5')); ?>

                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="empty-state p-5">
                    <i class="fas fa-pause-circle fa-3x text-warning"></i>
                    <h5 class="mt-3"><?php echo e(trans('phrE.no_on_hold_prescriptions')); ?></h5>
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
    <script src="<?php echo e(URL::asset('Dashboard/plugins/select2/js/select2.full.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('Dashboard/plugins/select2/js/i18n/ar.js')); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/ar.js"></script>

    <script>
        $(document).ready(function() {
            AOS.init({ duration: 700, once: true, offset: 50 });

            $('.select2').select2({ // استهدف الكلاس العام إذا كان لديك فلتر بالحالة هنا
                placeholder: $(this).data('placeholder') || "<?php echo e(trans('phrM.select_placeholder')); ?>",
                width: '100%',
                dir: "rtl",
                theme: "bootstrap-5",
                allowClear: true,
                dropdownParent: $(this).closest('.card-body')
            });

            flatpickr(".flatpickr-date", { dateFormat: "Y-m-d", locale: "ar", allowInput:true });

            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
              return new bootstrap.Tooltip(tooltipTriggerEl)
            })

            // NotifIt messages
            <?php if(session('success')): ?>
                notif({ msg: `<div class="d-flex align-items-center p-2"><i class='fas fa-check-circle fa-lg me-2 text-success'></i><div style='font-size: 0.95rem;'><?php echo e(session('success')); ?></div></div>`, type: "success", position: "top-center", autohide: true, timeout: 5000, zindex: 99999});
            <?php endif; ?>
            <?php if(session('error')): ?>
                notif({ msg: `<div class="d-flex align-items-center p-2"><i class='fas fa-exclamation-triangle fa-lg me-2 text-danger'></i><div style='font-size: 0.95rem;'><?php echo e(session('error')); ?></div></div>`, type: "error", position: "top-center", autohide: true, timeout: 7000, zindex: 99999});
            <?php endif; ?>
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Dashboard.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/pharmacy_employee/Prescriptions/on_hold_index.blade.php ENDPATH**/ ?>