<?php
    $medicationNameForTitle = $medication->name ?? 'غير متوفر';
?>

<?php $__env->startSection('title', 'تفاصيل الدواء: ' . $medicationNameForTitle); ?>

<?php $__env->startSection('css'); ?>
    <?php echo \Illuminate\View\Factory::parentPlaceholder('css'); ?>
    
    
    <link href="<?php echo e(URL::asset('Dashboard/plugins/notify/css/notifIt.css')); ?>" rel="stylesheet" />
    <style>
        :root {
            --admin-primary: #0d6efd;
            --admin-secondary: #6c757d;
            --admin-success: #198754;
            --admin-info: #0dcaf0;
            --admin-warning: #ffc107;
            --admin-danger: #dc3545;
            --admin-light: #f8f9fa;
            --admin-dark: #212529;
            --admin-body-bg: #f4f6f9;
            /* لون خلفية الصفحة */
            --admin-card-bg: #ffffff;
            --admin-card-border-color: #dee2e6;
            --admin-text-muted: #6c757d;
            --admin-radius-sm: 0.25rem;
            --admin-radius-md: 0.375rem;
            --admin-radius-lg: 0.5rem;
            --admin-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            --admin-shadow-lg: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1);
            /* ظل أكثر بروزاً */
            --admin-transition: all 0.25s ease-in-out;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --admin-body-bg: #1a202c;
                --admin-card-bg: #2d3748;
                --admin-card-border-color: #4a5568;
                --admin-text-muted: #a0aec0;
                --admin-light: #2d3748;
                /* تعديل للداكن */
            }
        }

        body {
            background-color: var(--admin-body-bg);
        }

        .details-card {
            background-color: var(--admin-card-bg);
            border: 1px solid var(--admin-card-border-color);
            border-radius: var(--admin-radius-lg);
            box-shadow: var(--admin-shadow-lg);
            margin-bottom: 2rem;
            overflow: hidden;
            /* مهم للـ header */
        }

        .details-card-header {
            background: linear-gradient(135deg, var(--admin-primary) 0%, var(--admin-info) 100%);
            color: white;
            padding: 1.25rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid transparent;
            /* لا حاجة للحد */
        }

        .details-card-header h3 {
            margin-bottom: 0;
            font-weight: 600;
            font-size: 1.5rem;
            /* عنوان أكبر */
            display: flex;
            align-items: center;
        }

        .details-card-header h3 i {
            margin-inline-end: 0.75rem;
            font-size: 1.8rem;
            /* أيقونة أكبر للعنوان */
            opacity: 0.9;
        }

        .status-badge {
            font-size: 0.9rem;
            padding: 0.5em 0.9em;
            border-radius: var(--admin-radius-md);
            font-weight: 500;
        }

        .details-card-body {
            padding: 1.5rem;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--admin-dark);
            margin-bottom: 1.25rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--admin-primary);
            display: inline-block;
            /* ليأخذ الـ border-bottom عرض النص فقط */
        }

        .section-title i {
            margin-inline-end: 0.5rem;
            color: var(--admin-primary);
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            /* شبكة مرنة */
            gap: 1.25rem;
            /* مسافات أكبر */
            margin-bottom: 1.5rem;
        }

        .info-item {
            background-color: var(--admin-light);
            padding: 1rem;
            border-radius: var(--admin-radius-md);
            border: 1px solid var(--admin-card-border-color);
            transition: var(--admin-transition);
        }

        .info-item:hover {
            transform: translateY(-3px);
            box-shadow: var(--admin-shadow);
        }

        .info-item-label {
            display: flex;
            align-items: center;
            font-size: 0.85rem;
            color: var(--admin-text-muted);
            margin-bottom: 0.35rem;
            font-weight: 500;
        }

        .info-item-label i {
            margin-inline-end: 0.5rem;
            color: var(--admin-primary);
            width: 18px;
            /* تحديد عرض للأيقونة لتناسق المحاذاة */
            text-align: center;
        }

        .info-item-value {
            font-size: 1rem;
            font-weight: 600;
            color: var(--admin-dark);
            word-break: break-word;
            /* لكسر النصوص الطويلة */
        }

        .stock-table {
            margin-top: 0.5rem;
        }

        .stock-table th {
            background-color: var(--admin-light) !important;
            /* تعديل مهم */
            color: var(--admin-dark);
            font-weight: 600;
            font-size: 0.9rem;
            text-align: center;
            vertical-align: middle;
        }

        .stock-table td {
            font-size: 0.9rem;
            text-align: center;
            vertical-align: middle;
        }

        /* تلوين صفوف المخزون */
        .stock-table .table-danger,
        .stock-table .table-danger>th,
        .stock-table .table-danger>td {
            background-color: #f8d7da !important;
            color: #721c24 !important;
        }

        .stock-table .table-warning,
        .stock-table .table-warning>th,
        .stock-table .table-warning>td {
            background-color: #fff3cd !important;
            color: #856404 !important;
        }

        @media (prefers-color-scheme: dark) {
            .stock-table th {
                background-color: #374151 !important;
            }

            .stock-table .table-danger,
            .stock-table .table-danger>th,
            .stock-table .table-danger>td {
                background-color: #4b2d30 !important;
                color: #f8d7da !important;
            }

            .stock-table .table-warning,
            .stock-table .table-warning>th,
            .stock-table .table-warning>td {
                background-color: #4d4123 !important;
                color: #fff3cd !important;
            }
        }

        .btn-action-group {
            display: flex;
            gap: 0.75rem;
        }

        .btn-icon-text .fas,
        .btn-icon-text .far {
            margin-inline-end: 0.35rem;
        }

        .alert-custom-info {
            background-color: var(--admin-info-bg, #e0f7fa);
            /* لون أزرق فاتح جداً */
            color: var(--admin-info-text, #007bff);
            border-color: var(--admin-info-border, #b3e5fc);
            padding: 1rem;
            border-radius: var(--admin-radius-md);
        }

        @media (prefers-color-scheme: dark) {
            .alert-custom-info {
                background-color: #2c5282;
                color: #bee3f8;
                border-color: #4299e1;
            }
        }

        .no-stock-message i {
            font-size: 2.5rem;
            margin-bottom: 0.75rem;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header'); ?>
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <i class="fas fa-info-circle fa-lg me-2 text-primary"></i>
                <div>
                    <h4 class="content-title mb-0 my-auto"><?php echo e(trans('phrM.manage_medications')); ?></h4>
                    <span class="text-muted mt-0 tx-13">/ <?php echo e(trans('phrM.medication_details_title', ['name' => ''])); ?>

                        <strong><?php echo e($medicationNameForTitle); ?></strong></span>
                </div>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content btn-action-group">
            <a href="<?php echo e(route('pharmacy_manager.medications.edit', $medication->id)); ?>"
                class="btn btn-outline-warning btn-sm btn-icon-text">
                <i class="fas fa-edit"></i> <?php echo e(trans('phrM.edit')); ?>

            </a>
            <a href="<?php echo e(route('pharmacy_manager.medications.index')); ?>"
                class="btn btn-outline-secondary btn-sm btn-icon-text">
                <i class="fas fa-arrow-left"></i> <?php echo e(trans('phrM.list')); ?>

            </a>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('Dashboard.messages_alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="row">
        <div class="col-lg-12">
            <div class="details-card animate__animated animate__fadeInUp">
                <div class="details-card-header">
                    <h3><i class="fas fa-capsules"></i> <?php echo e($medicationNameForTitle); ?></h3>
                    <span class="status-badge bg-<?php echo e($medication->status ? 'success' : 'danger'); ?>">
                        <?php echo e($medication->status ? trans('phrM.active') : trans('phrM.inactive')); ?>

                    </span>
                </div>

                <div class="details-card-body">
                    <h4 class="section-title"><i class="fas fa-file-medical-alt"></i> <?php echo e(trans('phrM.basic_medication_info')); ?></h4>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-item-label"><i class="fas fa-tag"></i><?php echo e(trans('phrM.commercial_name')); ?></span>
                            <strong class="info-item-value"><?php echo e($medication->name); ?></strong>
                        </div>
                        <div class="info-item">
                            <span class="info-item-label"><i class="fas fa-flask-vial"></i><?php echo e(trans('phrM.scientific_name')); ?></span>
                            <strong class="info-item-value"><?php echo e($medication->generic_name ?? '-'); ?></strong>
                        </div>
                        <div class="info-item">
                            <span class="info-item-label"><i class="fas fa-tablets"></i><?php echo e(trans('phrM.pharmaceutical_form')); ?></span>
                            <strong class="info-item-value"><?php echo e($medication->dosage_form ?? '-'); ?></strong>
                        </div>
                        <div class="info-item">
                            <span class="info-item-label"><i class="fas fa-weight-scale"></i><?php echo e(trans('phrM.concentration_strength')); ?></span>
                            <strong class="info-item-value"><?php echo e($medication->strength ?? '-'); ?></strong>
                        </div>
                        <div class="info-item">
                            <span class="info-item-label"><i class="fas fa-bell"></i><?php echo e(trans('phrM.reorder_limit')); ?></span>
                            <strong class="info-item-value"><?php echo e($medication->minimum_stock_level); ?></strong>
                        </div>
                        <div class="info-item">
                            <span class="info-item-label"><i class="far fa-calendar-plus"></i><?php echo e(trans('phrM.date_added')); ?></span>
                            <strong
                                class="info-item-value"><?php echo e($medication->created_at->translatedFormat('d F Y, H:i')); ?></strong>
                        </div>
                        <?php if($medication->notes): ?>
                            <div class="info-item" style="grid-column: 1 / -1;">
                                <span class="info-item-label"><i class="far fa-sticky-note"></i><?php echo e(trans('phrM.additional_notes')); ?></span>
                                <p class="info-item-value" style="white-space: pre-wrap;"><?php echo e($medication->notes); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <hr class="my-4">

                    <h4 class="section-title"><i class="fas fa-boxes-stacked"></i> <?php echo e(trans('phrM.available_stock_active_batches')); ?></h4>
                    <?php if($medication->relationLoaded('stocks') && $medication->stocks->where('quantity_on_hand', '>', 0)->count() > 0): ?>
                        <div class="table-responsive shadow-sm"
                            style="border-radius: var(--admin-radius-md); overflow: hidden;">
                            <table class="table table-sm table-bordered stock-table mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th><?php echo e(trans('phrM.batch_number_col')); ?></th>
                                        <th><?php echo e(trans('phrM.expiry_date_col')); ?></th>
                                        <th><?php echo e(trans('phrM.current_quantity_col')); ?></th>
                                        <th><?php echo e(trans('phrM.added_on_col')); ?></th>
                                        <th><?php echo e(trans('phrM.batch_notes_col')); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $medication->stocks->where('quantity_on_hand', '>', 0)->sortBy('expiry_date'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $stock): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr
                                            class="<?php echo e($stock->expiry_date->isPast() ? 'table-danger' : ($stock->expiry_date->isBefore(now()->addMonths(config('pharmacy.expiry_warning_months', 3))) ? 'table-warning' : '')); ?>">
                                            <td><?php echo e($loop->iteration); ?></td>
                                            <td><?php echo e($stock->batch_number ?? 'N/A'); ?></td>
                                            <td>
                                                <?php echo e($stock->expiry_date->translatedFormat('M Y')); ?>

                                                <?php if($stock->expiry_date->isPast()): ?>
                                                    <i class="fas fa-exclamation-triangle text-danger ms-1"
                                                        title="<?php echo e(trans('phrM.expired_tooltip')); ?>"></i>
                                                <?php elseif($stock->expiry_date->isBefore(now()->addMonths(config('pharmacy.expiry_warning_months', 3)))): ?>
                                                    <i class="fas fa-exclamation-circle text-warning ms-1"
                                                        title="<?php echo e(trans('phrM.expiring_soon_tooltip')); ?>"></i>
                                                <?php endif; ?>
                                            </td>
                                            <td><strong><?php echo e($stock->quantity_on_hand); ?></strong></td>
                                            <td><?php echo e($stock->created_at->translatedFormat('d M Y')); ?></td>
                                            <td><?php echo e($stock->notes ?? '-'); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                        <small class="form-text text-muted mt-2 d-block">
                            <span class="badge bg-danger-soft me-1"> </span> <?php echo e(trans('phrM.legend_expired')); ?>

                            <span class="badge bg-warning-soft ms-2 me-1"> </span> <?php echo e(trans('phrM.legend_expiring_soon', ['months' => config('pharmacy.expiry_warning_months', 3)])); ?>

                        </small>
                    <?php else: ?>
                        <div class="alert alert-custom-info text-center no-stock-message">
                            <i class="fas fa-box-open d-block"></i>
                            <h5 class="mb-1"><?php echo e(trans('phrM.no_stock_message_title')); ?></h5>
                            <p class="mb-0"><?php echo e(trans('phrM.no_stock_message_body')); ?></p>
                        </div>
                    <?php endif; ?>
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
        $(function() {
            // Tooltips for expiry icons (if Bootstrap JS is loaded)
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // NotifIt messages
            <?php if(session('success')): ?>
                notif({
                    msg: "<i class='fas fa-check-circle me-2'></i> <?php echo e(session('success')); ?>",
                    type: "success",
                    position: "bottom-right",
                    autohide: true,
                    timeout: 5000
                });
            <?php endif; ?>
            <?php if(session('error')): ?>
                notif({
                    msg: "<i class='fas fa-exclamation-triangle me-2'></i> <?php echo e(session('error')); ?>",
                    type: "error",
                    position: "bottom-right",
                    autohide: true,
                    timeout: 7000
                });
            <?php endif; ?>
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Dashboard.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/PharmacyManager/Medications/show.blade.php ENDPATH**/ ?>