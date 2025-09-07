<?php $__env->startSection('title', 'تعديل المرض: ' . $disease->name); ?>

<?php $__env->startSection('css'); ?>
    <?php echo \Illuminate\View\Factory::parentPlaceholder('css'); ?>
    <link href="<?php echo e(URL::asset('Dashboard/plugins/notify/css/notifIt.css')); ?>" rel="stylesheet" />
    
    <style>
        :root {
            --admin-primary: #4f46e5;
            --admin-primary-dark: #4338ca;
            --admin-secondary: #10b981;
            --admin-success: #22c55e;
            --admin-danger: #ef4444;
            --admin-warning: #f59e0b;
            --admin-info: #3b82f6;
            --admin-bg: #f8f9fc;
            --admin-card-bg: #ffffff;
            --admin-text: #111827;
            --admin-text-secondary: #6b7280;
            --admin-border-color: #e5e7eb;
            --admin-radius-md: 0.375rem;
            --admin-radius-lg: 0.75rem;
            --admin-radius-xl: 1rem;
            --admin-shadow: 0 1px 3px rgba(0, 0, 0, 0.07);
            --admin-shadow-md: 0 4px 10px -1px rgba(0, 0, 0, 0.07);
            --admin-transition: all 0.3s ease-in-out;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                /* ... (أنماط الوضع الداكن) ... */
            }

            .form-control,
            .form-select {
                background-color: #2d3748 !important;
                border-color: var(--admin-border-color) !important;
                color: var(--admin-text) !important;
            }

            .card {
                border-color: var(--admin-border-color);
                background-color: var(--admin-card-bg);
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
            box-shadow: var(--admin-shadow-md);
            border: 1px solid var(--admin-border-color);
            transition: var(--admin-transition);
        }

        .form-container-card:hover {
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
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
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--admin-primary);
            box-shadow: 0 0 0 0.2rem rgba(79, 70, 229, 0.15);
        }

        .btn-submit-form,
        .btn-cancel-form,
        .btn-delete-form-page {
            padding: 0.75rem 1.5rem;
            border-radius: var(--admin-radius-md);
            font-weight: 600;
            transition: var(--admin-transition);
            border: none;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
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

        .btn-cancel-form:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
        }

        .btn-delete-form-page {
            background-color: var(--admin-danger);
            color: white;
        }

        .btn-delete-form-page:hover {
            background-color: #c82333;
            transform: translateY(-2px);
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
                <i class="fas fa-viruses fa-lg me-2" style="color: var(--admin-primary);"></i>
                <div>
                    <h4 class="content-title mb-0 my-auto"><?php echo e(trans('AdminSb.disease_management')); ?></h4>
                    <span class="text-muted mt-0 tx-13">/ <?php echo e(trans('AdminSb.edit_disease_colon')); ?>

                        <?php echo e($disease->name); ?></span>
                </div>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <a href="<?php echo e(route('admin.diseases.index')); ?>" class="btn btn-outline-secondary btn-sm"
                style="border-radius: var(--admin-radius-md);">
                <i class="fas fa-arrow-left me-1"></i> <?php echo e(trans('AdminSb.back_to_diseases_list')); ?>

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
                    <h3 class="card-title mb-0"><i class="fas fa-edit"></i> <?php echo e(trans('AdminSb.edit_disease_data_colon')); ?>

                        <?php echo e($disease->name); ?></h3>
                </div>
                <div class="card-body p-4 p-md-5">
                    <form action="<?php echo e(route('admin.diseases.update', $disease->id)); ?>" method="POST"
                        class="needs-validation" novalidate autocomplete="off">
                        <?php echo method_field('PUT'); ?>
                        <?php echo csrf_field(); ?>
                        <div class="form-group mb-3">
                            <label for="name" class="form-label"><?php echo e(trans('AdminSb.disease_name')); ?> <span
                                    class="text-danger">*</span></label>
                            <input type="text" name="name" id="name"
                                class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                value="<?php echo e(old('name', $disease->name)); ?>" required>
                            <?php $__errorArgs = ['name'];
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

                        <div class="form-group mb-3">
                            <label for="description" class="form-label"><?php echo e(trans('AdminSb.disease_description')); ?></label>
                            <textarea name="description" id="description" class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                rows="4"><?php echo e(old('description', $disease->description)); ?></textarea>
                            <?php $__errorArgs = ['description'];
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

                        <div class="form-group mb-4">
                            <label class="form-label"><?php echo e(trans('AdminSb.is_the_disease_chronic')); ?> <span
                                    class="text-danger">*</span></label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_chronic"
                                        id="is_chronic_yes_edit" value="1"
                                        <?php echo e(old('is_chronic', $disease->is_chronic) == 1 ? 'checked' : ''); ?> required>
                                    <label class="form-check-label"
                                        for="is_chronic_yes_edit"><?php echo e(trans('AdminSb.yes')); ?></label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_chronic" id="is_chronic_no_edit"
                                        value="0" <?php echo e(old('is_chronic', $disease->is_chronic) == 0 ? 'checked' : ''); ?>

                                        required>
                                    <label class="form-check-label"
                                        for="is_chronic_no_edit"><?php echo e(trans('AdminSb.no')); ?></label>
                                </div>
                            </div>
                            <?php $__errorArgs = ['is_chronic'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-actions mt-4 pt-3 border-top d-flex justify-content-between align-items-center">
                            <div>
                                <button type="button" class="btn btn-delete-form-page" data-toggle="modal"
                                    data-target="#deleteDiseaseModal<?php echo e($disease->id); ?>">
                                    <i class="fas fa-trash-alt me-2"></i> <?php echo e(trans('AdminSb.delete_disease')); ?>

                                </button>
                            </div>
                            <div>
                                <a href="<?php echo e(route('admin.diseases.index')); ?>" class="btn btn-cancel-form" type="submit">
                                    <i class="fas fa-times me-2"></i> <?php echo e(trans('AdminSb.cancel')); ?>

                                </a>
                                <button type="submit" class="btn btn-submit-form ms-2">
                                    <i class="fas fa-save me-2"></i> <?php echo e(trans('AdminSb.save_changes')); ?>

                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php if(isset($disease)): ?>
        <div class="modal fade" id="deleteDiseaseModal<?php echo e($disease->id); ?>" tabindex="-1"
            aria-labelledby="deleteDiseaseModalLabel<?php echo e($disease->id); ?>" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header"
                        style="background: linear-gradient(135deg, var(--admin-danger), #c82333); color:white; border-bottom:none;">
                        <h5 class="modal-title" id="deleteDiseaseModalLabel<?php echo e($disease->id); ?>"><i
                                class="fas fa-exclamation-triangle me-2"></i> <?php echo e(trans('AdminSb.confirm_deletion')); ?></h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <form action="<?php echo e(route('admin.diseases.destroy', $disease->id)); ?>" method="POST"
                            id="confirmDeleteDiseaseForm<?php echo e($disease->id); ?>">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <p class="lead mb-2"><?php echo e(trans('AdminSb.are_you_sure_delete_disease_permanently_q')); ?></p>
                            <h4 class="text-danger mb-3"><strong><?php echo e($disease->name); ?></strong></h4>
                            <p class="small text-muted"><?php echo e(trans('AdminSb.delete_warning_details')); ?></p>
                        </form>
                    </div>
                    <div class="modal-footer justify-content-center border-top-0">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            style="min-width: 100px;"><i class="fas fa-times me-1"></i>
                            <?php echo e(trans('AdminSb.cancel')); ?></button>
                        <button type="submit" form="confirmDeleteDiseaseForm<?php echo e($disease->id); ?>" class="btn btn-danger"
                            style="min-width: 100px;"><i class="fas fa-trash-alt me-1"></i>
                            <?php echo e(trans('AdminSb.yes_delete')); ?></button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <?php echo \Illuminate\View\Factory::parentPlaceholder('js'); ?>
    <script src="<?php echo e(URL::asset('Dashboard/plugins/notify/js/notifIt.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('Dashboard/plugins/notify/js/notifit-custom.js')); ?>"></script>
    
    <script>
        $(document).ready(function() {
            // Bootstrap validation
            (function() {
                'use strict'
                var forms = document.querySelectorAll('.needs-validation')
                Array.prototype.slice.call(forms)
                    .forEach(function(form) {
                        // استثناء فورم الحذف داخل المودال من هذا التحقق إذا أردت
                        if (!form.id || !form.id.startsWith('confirmDeleteDiseaseForm')) {
                            form.addEventListener('submit', function(event) {
                                if (!form.checkValidity()) {
                                    event.preventDefault()
                                    event.stopPropagation()
                                }
                                form.classList.add('was-validated')
                            }, false)
                        }
                    })
            })();

            // NotifIt messages
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

            // (اختياري) تأثير التحميل لزر الحذف في المودال
            $('form[id^="confirmDeleteDiseaseForm"]').on('submit', function() {
                $(this).find('button[type="submit"]').html(
                    '<i class="fas fa-spinner fa-spin me-2"></i> جاري الحذف...').prop('disabled', true);
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Dashboard.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/Diseases/edit.blade.php ENDPATH**/ ?>