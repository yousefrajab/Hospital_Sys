<?php $__env->startSection('title', 'البحث عن الأدوية والمخزون'); ?>

<?php $__env->startSection('css'); ?>
    <?php echo \Illuminate\View\Factory::parentPlaceholder('css'); ?>
    <link href="<?php echo e(URL::asset('dashboard/plugins/select2/css/select2.min.css')); ?>" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="<?php echo e(URL::asset('dashboard/plugins/notify/css/notifIt.css')); ?>" rel="stylesheet" />
    <style>
        :root {
            --bs-primary-rgb: 67, 97, 238;
            /* ... (نفس متغيرات CSS من الرد السابق) ... */
            --bs-primary: rgb(var(--bs-primary-rgb));
            --bs-success-rgb: 25, 135, 84;
            --bs-success: rgb(var(--bs-success-rgb));
            --bs-danger-rgb: 220, 53, 69;
            --bs-danger: rgb(var(--bs-danger-rgb));
            --bs-warning-rgb: 255, 193, 7;
            --bs-warning: rgb(var(--bs-warning-rgb));
            --bs-info-rgb: 13, 202, 240;
            --bs-light-rgb: 248, 249, 252;
            --bs-dark-rgb: 33, 37, 41;
            --bs-body-bg: #f4f6f9;
            --bs-border-color: #dee2e6;
            --bs-card-border-radius: 0.65rem;
            --bs-card-box-shadow: 0 0.1rem 0.9rem rgba(58, 59, 69, 0.08);
        }

        body {
            font-family: 'Tajawal', sans-serif;
            background-color: var(--bs-body-bg);
            color: var(--bs-body-color);
        }

        .card-custom {
            border-radius: var(--bs-card-border-radius);
            box-shadow: var(--bs-card-box-shadow);
            border: 1px solid var(--bs-border-color);
            background-color: #fff;
            margin-bottom: 1.5rem;
        }

        .card-custom .card-header {
            background-color: rgb(var(--bs-light-rgb));
            border-bottom: 1px solid var(--bs-border-color);
            padding: 1rem 1.25rem;
        }

        .card-custom .card-title {
            font-weight: 600;
            color: var(--bs-primary);
            margin-bottom: 0;
        }

        .card-custom .card-title i {
            margin-left: 0.5rem;
        }

        .table-medications thead th {
            background-color: rgba(var(--bs-primary-rgb), 0.07);
            color: var(--bs-primary);
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            padding: 0.75rem 1rem;
            border-bottom-width: 2px !important;
            white-space: nowrap;
            text-align: right;
        }

        .table-medications tbody td {
            vertical-align: middle;
            padding: 0.7rem 1rem;
            font-size: 0.875rem;
            border-top: 1px solid var(--bs-border-color);
            text-align: right;
        }

        .table-medications tbody tr:hover {
            background-color: rgba(var(--bs-primary-rgb), 0.03);
        }

        .stock-status-badge {
            padding: 0.3em 0.7em;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 500;
            display: inline-block;
            min-width: 80px;
            text-align: center;
        }

        .stock-status-danger {
            background-color: rgba(var(--bs-danger-rgb), 0.1);
            color: rgb(var(--bs-danger-rgb));
            border: 1px solid rgba(var(--bs-danger-rgb), 0.2);
        }

        .stock-status-warning {
            background-color: rgba(var(--bs-warning-rgb), 0.15);
            color: #a17d06;
            border: 1px solid rgba(var(--bs-warning-rgb), 0.25);
        }

        .stock-status-success {
            background-color: rgba(var(--bs-success-rgb), 0.1);
            color: rgb(var(--bs-success-rgb));
            border: 1px solid rgba(var(--bs-success-rgb), 0.2);
        }

        .stock-status-secondary {
            background-color: rgba(var(--bs-secondary-rgb), 0.1);
            color: rgb(var(--bs-secondary-rgb));
            border: 1px solid rgba(var(--bs-secondary-rgb), 0.2);
        }

        .stock-status-dark {
            background-color: rgba(var(--bs-dark-rgb), 0.1);
            color: rgb(var(--bs-dark-rgb));
            border: 1px solid rgba(var(--bs-dark-rgb), 0.2);
        }


        .filter-form .form-label {
            font-size: 0.8rem;
            margin-bottom: 0.2rem;
            color: #6c757d;
            font-weight: 500;
        }

        .filter-form .form-control,
        .filter-form .form-select {
            font-size: 0.85rem;
            padding: 0.45rem 0.8rem;
        }

        .filter-form .btn {
            font-size: 0.85rem;
            padding: 0.45rem 1rem;
        }

        .filter-form .select2-container--bootstrap-5 .select2-selection--single {
            min-height: calc(1.5em + (0.45rem * 2) + (1px * 2) + 2px);
            padding-top: 0.45rem;
            padding-bottom: 0.45rem;
        }


        .empty-state {
            text-align: center;
            padding: 2rem;
            background-color: rgb(var(--bs-light-rgb));
            border-radius: var(--bs-card-border-radius);
            border: 1px dashed var(--bs-border-color);
        }

        .empty-state i {
            font-size: 2.5rem;
            color: var(--bs-border-color);
            margin-bottom: 1rem;
            display: block;
        }

        .pagination .page-link {
            font-size: 0.85rem;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header'); ?>
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto"><i class="fas fa-capsules text-primary me-2"></i><?php echo e(trans('phrE.page_header_title')); ?>

                </h4>
                <span class="text-muted mt-1 tx-13 mx-2">/</span>
                <span class="text-muted mt-1 tx-13"><?php echo e(trans('phrE.breadcrumb_sub')); ?></span>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('Dashboard.messages_alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="card card-custom filter-card" data-aos="fade-down">
        <div class="card-header pb-0">
            <h5 class="card-title mb-0"><i class="fas fa-filter me-2"></i><?php echo e(trans('phrE.filter_card_title')); ?></h5>
        </div>
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('pharmacy_employee.medications.search')); ?>">
                <div class="row g-2 align-items-end">
                    <div class="col-lg-3 col-md-6">
                        <label for="search_med_term_pharma" class="form-label"><?php echo e(trans('phrE.filter_search_label')); ?></label>
                        <input type="text" name="search_medication_term" id="search_med_term_pharma" class="form-control"
                            placeholder="<?php echo e(trans('phrE.filter_search_placeholder')); ?>" value="<?php echo e($request->input('search_medication_term')); ?>">
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label for="category_filter_pharma_search" class="form-label"><?php echo e(trans('phrE.filter_category_label')); ?></label>
                        <select name="category_filter" id="category_filter_pharma_search" class="form-select select2-filter"
                            data-placeholder="<?php echo e(trans('phrE.filter_select_all')); ?>">
                            <option value=""><?php echo e(trans('phrE.filter_select_all')); ?></option>
                            <?php if(isset($categories)): ?>
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($key); ?>"
                                        <?php echo e($request->category_filter == $key ? 'selected' : ''); ?>><?php echo e($value); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label for="stock_status_filter_search" class="form-label"><?php echo e(trans('phrE.filter_stock_status_label')); ?></label>
                        <select name="stock_status_filter" id="stock_status_filter_search"
                            class="form-select select2-filter" data-placeholder="<?php echo e(trans('phrE.filter_select_all')); ?>">
                            <?php if(isset($stockStatusOptions)): ?>
                                <?php $__currentLoopData = $stockStatusOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($key); ?>"
                                        <?php echo e($request->stock_status_filter == $key ? 'selected' : ''); ?>><?php echo e($value); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="expiry_status_filter_search" class="form-label"><?php echo e(trans('phrE.filter_expiry_status_label')); ?></label>
                        <select name="expiry_status_filter" id="expiry_status_filter_search"
                            class="form-select select2-filter" data-placeholder="<?php echo e(trans('phrE.filter_select_all')); ?>">
                            <?php if(isset($expiryStatusOptions)): ?>
                                <?php $__currentLoopData = $expiryStatusOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($key); ?>"
                                        <?php echo e($request->expiry_status_filter == $key ? 'selected' : ''); ?>><?php echo e($value); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-12">
                        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> <?php echo e(trans('phrE.filter_button_search')); ?></button>
                        <?php if(request()->hasAny(['search_medication_term', 'category_filter', 'stock_status_filter', 'expiry_status_filter'])): ?>
                            <a href="<?php echo e(route('pharmacy_employee.medications.search')); ?>"
                                class="btn btn-outline-secondary w-100 mt-2 btn-sm">
                                <i class="fas fa-eraser me-1"></i> <?php echo e(trans('phrE.filter_button_clear')); ?>

                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card card-custom mt-4" data-aos="fade-up" data-aos-delay="100">
        <div class="card-header">
            <h4 class="card-title"><?php echo e(trans('phrE.medications_list_title')); ?> (<?php if(isset($medications) && $medications->total() > 0): ?>
                    <?php echo e($medications->total()); ?>

                <?php else: ?>
                    0
                <?php endif; ?>)</h4>
        </div>
        <div class="card-body p-0">
            <?php if(isset($medications) && $medications->isNotEmpty()): ?>
                <div class="table-responsive">
                    <table class="table table-medications table-hover">
                        <thead>
                            <tr>
                                <th><?php echo e(trans('phrE.table_header_number')); ?></th>
                                <th><?php echo e(trans('phrE.table_header_medication_name')); ?></th>
                                <th><?php echo e(trans('phrE.table_header_scientific_name')); ?></th>
                                <th><?php echo e(trans('phrE.table_header_barcode')); ?></th>
                                <th class="text-center"><?php echo e(trans('phrE.table_header_available_quantity')); ?></th>
                                <th class="text-center"><?php echo e(trans('phrE.table_header_nearest_expiry')); ?></th>
                                <th class="text-center"><?php echo e(trans('phrE.table_header_stock_status')); ?></th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $medications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $medication): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($loop->iteration + $medications->firstItem() - 1); ?></td>
                                    <td>
                                        <strong><?php echo e($medication->name); ?></strong>
                                        <small class="d-block text-muted"><?php echo e($medication->strength ?: ''); ?>

                                            <?php echo e($medication->dosage_form ? (isset(\App\Models\Medication::getCommonDosageForms()[$medication->dosage_form]) ? \App\Models\Medication::getCommonDosageForms()[$medication->dosage_form] : $medication->dosage_form) : ''); ?></small>
                                    </td>
                                    <td><?php echo e($medication->generic_name ?: '-'); ?></td>
                                    <td><?php echo e($medication->barcode ?: '-'); ?></td>
                                    <td class="text-center">
                                        <strong
                                            class="text-primary"><?php echo e($medication->total_available_quantity ?? 0); ?></strong>
                                        <small
                                            class="text-muted d-block">(<?php echo e($medication->unit_of_measure ? (isset(\App\Models\Medication::getCommonUnitsOfMeasure()[$medication->unit_of_measure]) ? \App\Models\Medication::getCommonUnitsOfMeasure()[$medication->unit_of_measure] : $medication->unit_of_measure) : ''); ?>)</small>
                                    </td>
                                    <td class="text-center">
                                        <?php if($medication->nearest_expiry_date): ?>
                                            <span
                                                class="stock-status-badge stock-status-<?php echo e($medication->expiry_status_class ?? 'secondary'); ?>"
                                                data-bs-toggle="tooltip" title="<?php echo e(trans('phrE.tooltip_nearest_expiry')); ?>">
                                                <?php echo e(\Carbon\Carbon::parse($medication->nearest_expiry_date)->translatedFormat('M Y')); ?>

                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="stock-status-badge stock-status-<?php echo e($medication->stock_status_class ?? 'secondary'); ?>">
                                            <?php echo e($medication->stock_status_text ?? trans('phrE.status_unknown')); ?>

                                        </span>
                                    </td>
                                    
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <?php if($medications->hasPages()): ?>
                    <div class="p-3 border-top d-flex justify-content-center">
                        <?php echo e($medications->links('pagination::bootstrap-5')); ?>

                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="empty-state p-5">
                    <i class="fas fa-tablets fa-3x"></i>
                    <h5 class="mt-3"><?php echo e(trans('phrE.empty_state_title')); ?></h5>
                    <p class="text-muted"><?php echo e(trans('phrE.empty_state_description')); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
    <?php echo \Illuminate\View\Factory::parentPlaceholder('js'); ?>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="<?php echo e(URL::asset('Dashboard/plugins/select2/js/select2.full.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('Dashboard/plugins/select2/js/i18n/ar.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('dashboard/plugins/notify/js/notifIt.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('dashboard/plugins/notify/js/notifit-custom.js')); ?>"></script>
    <script>
        $(document).ready(function() {
            AOS.init({
                duration: 700,
                once: true,
                offset: 30
            });

            $('.select2').select2({
                placeholder: $(this).data('placeholder') || "الكل",
                width: '100%',
                dir: "rtl",
                theme: "bootstrap-5",
                allowClear: true,
                dropdownParent: $(this).closest('.card-body') // أو الحاوية المناسبة
            });

            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // NotifIt messages
            <?php if(session('success')): ?>
                notif({
                    msg: `<div class="d-flex align-items-center p-2"><i class='fas fa-check-circle fa-lg me-2 text-success'></i><div style='font-size: 0.95rem;'><?php echo e(session('success')); ?></div></div>`,
                    type: "success",
                    position: "top-center",
                    autohide: true,
                    timeout: 5000
                });
            <?php endif; ?>
            <?php if(session('error')): ?>
                notif({
                    msg: `<div class="d-flex align-items-center p-2"><i class='fas fa-exclamation-triangle fa-lg me-2 text-danger'></i><div style='font-size: 0.95rem;'><?php echo e(session('error')); ?></div></div>`,
                    type: "error",
                    position: "top-center",
                    autohide: true,
                    timeout: 7000
                });
            <?php endif; ?>
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Dashboard.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/pharmacy_employee/Medications/search.blade.php ENDPATH**/ ?>