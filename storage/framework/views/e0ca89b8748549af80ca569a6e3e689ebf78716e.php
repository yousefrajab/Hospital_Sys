 

<?php $__env->startSection('title', 'الوصفات الطبية الواردة'); ?>

<?php $__env->startSection('css'); ?>
    <?php echo \Illuminate\View\Factory::parentPlaceholder('css'); ?>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="<?php echo e(URL::asset('dashboard/plugins/notify/css/notifIt.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(URL::asset('Dashboard/plugins/select2/css/select2.min.css')); ?>" rel="stylesheet" /> 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet"> 

    <style>
        :root {
            --bs-primary-rgb: 67, 97, 238;
            --bs-primary: rgb(var(--bs-primary-rgb));
            --bs-success-rgb: 25, 135, 84;
            --bs-info-rgb: 13, 202, 240;
            --bs-warning-rgb: 255, 193, 7;
            --bs-danger-rgb: 220, 53, 69;
            --bs-light-rgb: 248, 249, 252;
            --bs-dark-rgb: 33, 37, 41;
            --bs-body-bg: #f4f6f9;
            --bs-border-color: #e3e6f0;
            --bs-card-border-radius: 0.75rem;
            --bs-card-box-shadow: 0 0.15rem 1.25rem rgba(58, 59, 69, 0.1);
            --bs-body-color: #525f7f;
            --admin-transition: all 0.25s ease-in-out;
        }
        body { font-family: 'Tajawal', sans-serif; background-color: var(--bs-body-bg); color: var(--bs-body-color); }

        .card-custom { border-radius: var(--bs-card-border-radius); box-shadow: var(--bs-card-box-shadow); border: 1px solid var(--bs-border-color); background-color: #fff; }
        .card-custom .card-header { background-color: rgb(var(--bs-light-rgb)); border-bottom: 1px solid var(--bs-border-color); padding: 1rem 1.25rem; }
        .card-custom .card-title { font-weight: 600; color: var(--bs-primary); margin-bottom: 0; }
        .card-custom .card-title i { margin-left: 0.5rem; /* RTL */ }

        .table-prescriptions thead th {
            background-color: rgba(var(--bs-primary-rgb), 0.07); color: var(--bs-primary);
            font-weight: 600; font-size: 0.85rem; text-transform: uppercase;
            padding: 0.8rem 1rem; border-bottom-width: 2px !important; white-space: nowrap;
        }
        .table-prescriptions tbody td {
            vertical-align: middle; padding: 0.8rem 1rem; font-size: 0.9rem;
            border-top: 1px solid var(--bs-border-color);
        }
        .table-prescriptions tbody tr:hover { background-color: rgba(var(--bs-primary-rgb), 0.03); }

        .patient-name-link { color: var(--bs-primary); font-weight: 500; text-decoration: none; }
        .patient-name-link:hover { text-decoration: underline; color: rgb(var(--bs-dark-rgb)); }

        .status-badge { padding: 0.4em 0.8em; border-radius: 50px; font-size: 0.75rem; font-weight: 500; letter-spacing:0.3px; }
        .status-new { background-color: rgba(var(--bs-info-rgb), 0.15); color: rgb(var(--bs-info-rgb)); border:1px solid rgba(var(--bs-info-rgb),0.2); }
        .status-approved { background-color: rgba(var(--bs-primary-rgb), 0.15); color: var(--bs-primary); border:1px solid rgba(var(--bs-primary-rgb),0.2); }
        .status-partially_dispensed { background-color: rgba(var(--bs-warning-rgb), 0.2); color: #a17d06; border:1px solid rgba(var(--bs-warning-rgb),0.3); }
        /* ... (أضف المزيد من كلاسات الحالة إذا لزم الأمر) ... */

        .action-btn {
            font-weight: 500; padding: 0.45rem 0.9rem; font-size: 0.8rem;
            border-radius: 0.3rem; box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }
        .action-btn i { margin-left: 0.3rem; /* RTL */ }

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
                <span class="text-muted mt-1 tx-13"><?php echo e(trans('phrE.incoming_prescriptions')); ?></span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <span class="text-muted small me-3"><?php echo e(trans('phrE.new_prescriptions_today')); ?> <strong class="text-primary"><?php echo e($newPrescriptionsCount ?? 0); ?></strong></span>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('Dashboard.messages_alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="card card-custom filter-card" data-aos="fade-down">
        <div class="card-header pb-0">
            <h5 class="card-title mb-0"><i class="fas fa-filter me-2"></i><?php echo e(trans('phrE.filter_prescriptions')); ?></h5>
        </div>
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('pharmacy_employee.prescriptions.index')); ?>">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-5 col-md-6">
                        <label for="search_term_pharma" class="form-label"><?php echo e(trans('phrE.search_by_prescription_patient_doctor')); ?></label>
                        <input type="text" name="search_term" id="search_term_pharma" class="form-control"
                               placeholder="<?php echo e(trans('phrE.search_term_placeholder')); ?>" value="<?php echo e($request->input('search_term')); ?>">
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="date_filter_pharma" class="form-label"><?php echo e(trans('phrE.prescription_date')); ?></label>
                        <input type="text" name="date_filter" id="date_filter_pharma" class="form-control flatpickr-date"
                               placeholder="YYYY-MM-DD" value="<?php echo e($request->input('date_filter')); ?>">
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> <?php echo e(trans('phrE.search')); ?></button>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <?php if(request()->hasAny(['search_term', 'date_filter', 'status_filter'])): ?>
                            <a href="<?php echo e(route('pharmacy_employee.prescriptions.index')); ?>" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-eraser me-1"></i> <?php echo e(trans('phrE.clear')); ?>

                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card card-custom mt-4" data-aos="fade-up" data-aos-delay="100">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0"><i class="fas fa-list-alt me-2"></i><?php echo e(trans('phrE.incoming_prescriptions_list_title', ['count' => $pendingPrescriptions->total()])); ?></h4>
            
        </div>
        <div class="card-body p-0">
            <?php if($pendingPrescriptions->isNotEmpty()): ?>
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
                                <th class="text-center"><?php echo e(trans('phrE.action_col')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $pendingPrescriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prescription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="animate__animated animate__fadeIn" style="animation-delay: <?php echo e(($loop->index % 10) * 0.05); ?>s;">
                                    <td><?php echo e($loop->iteration + $pendingPrescriptions->firstItem() - 1); ?></td>
                                    <td><strong><?php echo e($prescription->prescription_number); ?></strong></td>
                                    <td class="patient-name-link">
                                        <?php echo e($prescription->patient->name ?? trans('phrE.unknown_patient')); ?>

                                    </td>
                                    <td><?php echo e($prescription->doctor->name ?? trans('phrE.unspecified_doctor')); ?></td>
                                    <td><?php echo e($prescription->prescription_date->translatedFormat('d M Y')); ?></td>
                                    <td class="text-center">
                                        <?php
                                            $statusKey = $prescription->status;
                                            $allStatuses = class_exists(\App\Models\Prescription::class) && method_exists(\App\Models\Prescription::class, 'getStatusesForFilter')
                                                           ? \App\Models\Prescription::getStatusesForFilter()
                                                           : [];
                                            $statusText = $allStatuses[$statusKey] ?? ucfirst(str_replace('_', ' ', $statusKey));
                                            $statusBadgeClass = 'status-' . str_replace('_', '-', $statusKey);
                                        ?>
                                        <span class="status-badge <?php echo e($statusBadgeClass); ?>">
                                            <?php echo e($statusText); ?>

                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="<?php echo e(route('pharmacy_employee.prescriptions.dispense.form', $prescription->id)); ?>"
                                           class="btn btn-success action-btn btn-sm">
                                            <i class="fas fa-prescription-bottle"></i> <?php echo e(trans('phrE.start_dispensing')); ?>

                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <?php if($pendingPrescriptions->hasPages()): ?>
                    <div class="p-3 border-top">
                        <?php echo e($pendingPrescriptions->links('pagination::bootstrap-5')); ?>

                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="empty-state p-5">
                    <i class="fas fa-folder-open"></i>
                    <h5 class="mt-3"><?php echo e(trans('phrE.no_prescriptions_awaiting_processing')); ?></h5>
                    <p class="text-muted"><?php echo e(trans('phrE.new_prescriptions_will_appear_here')); ?></p>
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
            AOS.init({
                duration: 700,
                easing: 'ease-out-cubic',
                once: true,
                offset: 50
            });

            $('.select2').select2({ // تم تغيير الكلاس إلى .select2
                placeholder: $(this).data('placeholder') || "اختر...",
                width: '100%',
                dir: "rtl",
                theme: "bootstrap-5",
                allowClear: true,
                dropdownParent: $(this).closest('.card-body') // تأكد أن هذا هو الحاوي الصحيح
            });

            flatpickr(".flatpickr-date", {
                dateFormat: "Y-m-d",
                locale: "ar",
                allowInput: true // اسمح بالكتابة اليدوية إذا أراد المستخدم
            });

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

<?php echo $__env->make('Dashboard.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/pharmacy_employee/Prescriptions/index.blade.php ENDPATH**/ ?>