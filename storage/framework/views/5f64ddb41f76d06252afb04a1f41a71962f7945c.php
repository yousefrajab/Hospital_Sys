<?php $__env->startSection('title', 'إضافة سرير جديد'); ?>

<?php $__env->startSection('css'); ?>
    <?php echo \Illuminate\View\Factory::parentPlaceholder('css'); ?>
    <link href="<?php echo e(URL::asset('Dashboard/plugins/select2/css/select2.min.css')); ?>" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <style>
        /* استخدام نفس متغيرات التصميم لتوحيد المظهر */
        :root {
            --admin-primary: #4f46e5;
            --admin-primary-dark: #4338ca;
            --admin-secondary: #10b981;
            --admin-success: #22c55e;
            --admin-danger: #ef4444;
            --admin-bg: #f8f9fc;
            --admin-card-bg: #ffffff;
            --admin-text: #111827;
            --admin-text-secondary: #6b7280;
            --admin-border-color: #e5e7eb;
            --admin-radius-md: 0.375rem;
            --admin-radius-lg: 0.75rem;
            --admin-shadow: 0 1px 3px rgba(0, 0, 0, 0.07);
            --admin-transition: all 0.3s ease-in-out;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --admin-bg: #1f2937;
                --admin-card-bg: #374151;
                --admin-text: #f9fafb;
                --admin-text-secondary: #9ca3af;
                --admin-border-color: #4b5563;
                --admin-primary: #6366f1;
                --admin-primary-dark: #4f46e5;
            }

            .form-control,
            .form-select,
            .select2-container--bootstrap-5 .select2-selection {
                background-color: #2d3748 !important;
                border-color: var(--admin-border-color) !important;
                color: var(--admin-text) !important;
            }

            .select2-container--bootstrap-5 .select2-dropdown {
                background-color: #2d3748;
                border-color: var(--admin-border-color);
            }

            .select2-container--bootstrap-5 .select2-results__option {
                color: var(--admin-text);
            }

            .select2-container--bootstrap-5 .select2-results__option--highlighted {
                background-color: var(--admin-primary) !important;
            }

            .form-label {
                color: var(--admin-text-secondary);
            }
        }

        body {
            background-color: var(--admin-bg);
            font-family: 'Tajawal', sans-serif;
            color: var(--admin-text);
        }

        .form-container-card {
            background-color: var(--admin-card-bg);
            border-radius: var(--admin-radius-lg);
            box-shadow: var(--admin-shadow);
            border: 1px solid var(--admin-border-color);
            transition: var(--admin-transition);
        }

        .form-container-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transform: translateY(-3px);
        }

        .card-header-custom {
            background: linear-gradient(135deg, var(--admin-primary), var(--admin-primary-dark));
            color: white;
            padding: 1.25rem 1.5rem;
            border-bottom: none;
            border-radius: var(--admin-radius-lg) var(--admin-radius-lg) 0 0;
        }

        .card-header-custom .card-title {
            font-weight: 600;
            font-size: 1.2rem;
        }

        .card-header-custom i {
            margin-left: 0.5rem;
        }

        /* RTL: margin-right */

        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            color: var(--admin-text-secondary);
        }

        .form-control,
        .form-select {
            border-radius: var(--admin-radius-md);
            border: 1px solid var(--admin-border-color);
            padding: 0.65rem 1rem;
            font-size: 0.95rem;
            transition: var(--admin-transition);
            background-color: var(--admin-card-bg);
            /* للخلفية في الوضع الفاتح */
            color: var(--admin-text);
            /* لون النص في الوضع الفاتح */
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--admin-primary);
            box-shadow: 0 0 0 0.2rem rgba(79, 70, 229, 0.15);
        }

        .select2-container--bootstrap-5 .select2-selection {
            border-radius: var(--admin-radius-md) !important;
            border: 1px solid var(--admin-border-color) !important;
            padding: 0.47rem 0.75rem !important;
            height: calc(1.5em + 1.3rem + 2px) !important;
            /* ليتناسب مع ارتفاع الحقول الأخرى */
            background-color: var(--admin-card-bg);
        }

        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
            color: var(--admin-text) !important;
        }

        .select2-container--bootstrap-5.select2-container--focus .select2-selection,
        .select2-container--bootstrap-5.select2-container--open .select2-selection {
            border-color: var(--admin-primary) !important;
            box-shadow: 0 0 0 0.2rem rgba(79, 70, 229, 0.15) !important;
        }

        .btn-submit-form,
        .btn-cancel-form {
            padding: 0.75rem 1.5rem;
            border-radius: var(--admin-radius-md);
            font-weight: 600;
            transition: var(--admin-transition);
            border: none;
            font-size: 0.95rem;
        }

        .btn-submit-form {
            background-color: var(--admin-primary);
            color: white;
        }

        .btn-submit-form:hover {
            background-color: var(--admin-primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--admin-shadow);
        }

        .btn-cancel-form {
            background-color: #6c757d;
            color: white;
        }

        /* رمادي داكن للإلغاء */
        .btn-cancel-form:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
            box-shadow: var(--admin-shadow);
        }

        .is-invalid {
            border-color: var(--admin-danger) !important;
        }

        .invalid-feedback {
            color: var(--admin-danger);
            font-size: 0.85em;
            display: block;
            margin-top: 0.25rem;
        }

        .was-validated .form-control:valid,
        .was-validated .form-select:valid {
            border-color: var(--admin-success) !important;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header'); ?>
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <i class="fas fa-bed-pulse fa-lg me-2" style="color: var(--admin-primary, #4f46e5);"></i>
                <div>
                    <h4 class="content-title mb-0 my-auto"><?php echo e(trans('AdminSb.beds_management')); ?></h4>
                    <span class="text-muted mt-0 tx-13">/ <?php echo e(trans('AdminSb.add_new_bed')); ?></span>
                </div>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <a href="<?php echo e(route('admin.beds.index')); ?>" class="btn btn-outline-secondary btn-sm"
                style="border-radius: var(--admin-radius-md);">
                <i class="fas fa-arrow-left me-1"></i> <?php echo e(trans('AdminSb.back_to_beds_list')); ?>

            </a>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('Dashboard.messages_alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="row justify-content-center animate__animated animate__fadeInUp">
        <div class="col-lg-8 col-md-10">
            <div class="form-container-card">
                <div class="card-header card-header-custom">
                    <h3 class="card-title mb-0" style="color: white"><i class="fas fa-plus-circle" style="color: white"></i>
                        <?php echo e(trans('AdminSb.new_bed_data')); ?></h3>
                </div>
                <div class="card-body p-4 p-md-5">
                    <form action="<?php echo e(route('admin.beds.store')); ?>" method="POST" class="needs-validation" novalidate
                        autocomplete="off">
                        <?php echo csrf_field(); ?>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="room_id" class="form-label"><?php echo e(trans('AdminSb.room')); ?> <span
                                            class="text-danger">*</span></label>
                                    <select name="room_id" id="room_id"
                                        class="form-select select2 <?php $__errorArgs = ['room_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required
                                        data-placeholder="<?php echo e(trans('AdminSb.select_room_placeholder')); ?>">
                                        <option value=""></option>
                                        <?php $__currentLoopData = $rooms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $room): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($room->id); ?>"
                                                <?php echo e(old('room_id') == $room->id ? 'selected' : ''); ?>>
                                                <?php echo e($room->display_name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['room_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bed_number" class="form-label"><?php echo e(trans('AdminSb.bed_number_code')); ?> <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="bed_number" id="bed_number"
                                        class="form-control <?php $__errorArgs = ['bed_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        value="<?php echo e(old('bed_number')); ?>"
                                        placeholder="<?php echo e(trans('AdminSb.bed_number_placeholder_example')); ?>" required>
                                    <?php $__errorArgs = ['bed_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type" class="form-label"><?php echo e(trans('AdminSb.bed_type')); ?> <span
                                            class="text-danger">*</span></label>
                                    <select name="type" id="type"
                                        class="form-select select2 <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required
                                        data-placeholder="<?php echo e(trans('AdminSb.select_type_placeholder')); ?>">
                                        <option value=""></option>
                                        <?php $__currentLoopData = $bedTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($key); ?>"
                                                <?php echo e(old('type', 'standard') == $key ? 'selected' : ''); ?>>
                                                <?php echo e($value); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status" class="form-label"><?php echo e(trans('AdminSb.initial_status')); ?> <span
                                            class="text-danger">*</span></label>
                                    <select name="status" id="status"
                                        class="form-select select2 <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required
                                        data-placeholder="<?php echo e(trans('AdminSb.select_status_placeholder')); ?>">
                                        <option value=""></option>
                                        <?php $__currentLoopData = $initialBedStatuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($key); ?>"
                                                <?php echo e(old('status', 'available') == $key ? 'selected' : ''); ?>>
                                                <?php echo e($value); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4 pt-3 border-top">
                            <button type="submit" class="btn btn-submit-form">
                                <i class="fas fa-save me-2"></i> <?php echo e(trans('AdminSb.save_bed')); ?>

                            </button>
                            <a href="<?php echo e(route('admin.beds.index')); ?>" class="btn btn-cancel-form ms-2">
                                <i class="fas fa-times me-2"></i> <?php echo e(trans('AdminSb.cancel')); ?>

                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <?php echo \Illuminate\View\Factory::parentPlaceholder('js'); ?>
    <script src="<?php echo e(URL::asset('Dashboard/plugins/select2/js/select2.full.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('Dashboard/plugins/select2/js/i18n/ar.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('Dashboard/plugins/notify/js/notifIt.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('Dashboard/plugins/notify/js/notifit-custom.js')); ?>"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: $(this).data('placeholder') || "اختر...",
                width: '100%',
                dir: "rtl",
                theme: "bootstrap-5",
                allowClear: true
            });

            (function() {
                'use strict'
                var forms = document.querySelectorAll('.needs-validation')
                Array.prototype.slice.call(forms)
                    .forEach(function(form) {
                        form.addEventListener('submit', function(event) {
                            if (!form.checkValidity()) {
                                event.preventDefault()
                                event.stopPropagation()
                            }
                            form.classList.add('was-validated')
                        }, false)
                    })
            })();

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
            <?php if($errors->any()): ?>
                let errorMsg =
                    "<strong><i class='fas fa-times-circle me-2'></i> يرجى تصحيح الأخطاء التالية:</strong><ul class='mb-0 ps-3 mt-1' style='list-style-type: none; padding-right: 0;'>";
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    errorMsg += "<li>- <?php echo e($error); ?></li>";
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                errorMsg += "</ul>";
                notif({
                    msg: errorMsg,
                    type: "error",
                    position: "bottom",
                    multiline: true,
                    autohide: false
                });
            <?php endif; ?>
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Dashboard.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/Beds/create.blade.php ENDPATH**/ ?>