<?php $__env->startSection('title', 'حجز موعد جديد'); ?>

<?php $__env->startSection('css'); ?>
    <?php echo \Illuminate\View\Factory::parentPlaceholder('css'); ?>
    <link href="<?php echo e(URL::asset('Dashboard/plugins/select2/css/select2.min.css')); ?>" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/l10n/ar.css">
    <link href="<?php echo e(URL::asset('dashboard/plugins/notify/css/notifIt.css')); ?>" rel="stylesheet"/>

    <style>
        /* متغيرات أساسية مستوحاة من الكود الأول ومعدلة لتناسب */
        :root {
            --form-primary-color: #3498db; /* أزرق الكود الأول */
            --form-secondary-color: #2ecc71; /* أخضر الكود الأول */
            --form-text-color: #2c3e50;
            --form-border-color: #ddd;
            --form-focus-border-color: #3498db;
            --form-focus-shadow-color: rgba(52, 152, 219, 0.2);
            --form-background-light: #f9f9f9;
            --form-radius: 8px;
            /* Padding for LTR, will be overridden by RTL specific */
            --form-input-padding-left: 40px;
            --form-input-padding-right: 15px;
            --form-danger-color: #e74c3c;
        }
        [dir="rtl"] {
            --form-input-padding-left: 15px;
            --form-input-padding-right: 40px;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fc;
        }

        .appointment-form-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-top: 30px;
            margin-bottom: 30px;
        }

        .appointment-form-header {
            background: linear-gradient(135deg, var(--form-primary-color), var(--form-secondary-color));
            color: white;
            padding: 25px;
            text-align: center;
        }
        .appointment-form-header h5 {
            margin: 0;
            font-size: 1.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .form-section-fieldset {
            border: none;
            padding: 25px;
            margin-bottom: 0;
        }
        .form-section-fieldset.patient-info-section {
             background: var(--form-background-light);
        }

        .form-section-legend {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 25px;
            color: var(--form-text-color);
            font-size: 1.3rem;
            font-weight: 600;
            padding: 0;
            width: auto;
            border-bottom: none;
        }
        .form-section-legend i {
            font-size: 1.2rem;
            color: var(--form-primary-color);
        }

        .form-group {
            margin-bottom: 20px;
        }
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--form-text-color);
            font-size: 0.95rem;
        }

        .input-with-icon {
            position: relative;
        }
        .input-with-icon .form-control,
        .input-with-icon .form-select,
        .input-with-icon .select2-container--bootstrap-5 .select2-selection {
            padding-left: var(--form-input-padding-left) !important;
            padding-right: var(--form-input-padding-right) !important;
            border: 1px solid var(--form-border-color) !important;
            border-radius: var(--form-radius) !important;
            font-size: 14px !important;
            transition: all 0.3s;
            width: 100% !important;
            min-height: auto !important;
            height: calc(1.5em + 0.75rem + 2px + 10px); /* Approximate height */
        }

        .input-with-icon .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
            line-height: calc(1.5em + 0.75rem - 2px);
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        .input-with-icon .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
            height: calc(1.5em + 0.75rem + 2px + 10px - 2px) !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
        }
        [dir="rtl"] .input-with-icon .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
            left: 15px !important; right: auto !important;
        }
        [dir="ltr"] .input-with-icon .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
            right: 15px !important; left: auto !important;
        }

        .input-with-icon .form-control:focus,
        .input-with-icon .form-select:focus,
        .input-with-icon .select2-container--bootstrap-5.select2-container--open .select2-selection,
        .input-with-icon .select2-container--bootstrap-5.select2-container--focus .select2-selection {
            border-color: var(--form-focus-border-color) !important;
            box-shadow: 0 0 0 3px var(--form-focus-shadow-color) !important;
            outline: none !important;
        }
        .input-with-icon > i.fas, .input-with-icon > i.fa {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            color: #7f8c8d;
            z-index: 3;
        }
        [dir="rtl"] .input-with-icon > i.fas, [dir="rtl"] .input-with-icon > i.fa {
            right: 15px; left: auto;
        }
        [dir="ltr"] .input-with-icon > i.fas, [dir="ltr"] .input-with-icon > i.fa {
            left: 15px; right: auto;
        }

        textarea.form-control {
            padding: 12px 15px;
            border: 1px solid var(--form-border-color);
            border-radius: var(--form-radius);
            font-size: 14px;
            min-height: 100px;
            resize: vertical;
            transition: all 0.3s;
        }
        textarea.form-control:focus {
            border-color: var(--form-focus-border-color);
            box-shadow: 0 0 0 3px var(--form-focus-shadow-color);
            outline: none;
        }

        .flatpickr-input { background-color: #fff !important; }
        .flatpickr-input[disabled], .form-control.flatpickr-date[disabled] {
            background-color: #e9ecef !important;
            cursor: not-allowed !important;
            opacity: 0.6 !important;
        }

        .time-slots-container { margin-top: 10px; }
        #time_slots_wrapper { display: flex; flex-wrap: wrap; gap: 10px; }
        .time-slot-btn {
            background-color: #ecf0f1; border: 1px solid #bdc3c7; border-radius: 6px;
            padding: 8px 15px; cursor: pointer; transition: all 0.3s ease;
            font-size: 0.9rem; color: var(--form-text-color);
            line-height: normal; text-align: center; vertical-align: middle; user-select: none;
        }
        .time-slot-btn:hover { background-color: #d5dbdb; border-color: #aab1b5; }
        .time-slot-btn.selected, .time-slot-btn.active {
            background-color: var(--form-primary-color); color: white; border-color: #2980b9;
            font-weight: bold; box-shadow: 0 2px 5px rgba(52, 152, 219, 0.3);
        }
        .time-slot-btn:disabled {
            cursor: not-allowed !important; opacity: 0.6 !important;
            background-color: #ecf0f1 !important; border-color: #bdc3c7 !important; color: #7f8c8d !important;
        }

        #no_times_message {
            font-style: italic; margin-top: 10px; width: 100%;
            background-color: #fcf8e3; border: 1px solid #faebcc; color: #8a6d3b;
            padding: 10px 15px; border-radius: var(--form-radius);
        }
        #no_times_message.text-danger {
            background-color: #f2dede; border-color: #ebccd1; color: #a94442; font-style: normal;
        }

        .form-actions-container {
            text-align: center; padding: 25px; background: #f5f7fa;
            border-top: 1px solid #e9ecef;
        }
        .submit-btn-appointment {
            background: linear-gradient(135deg, var(--form-primary-color), var(--form-secondary-color));
            color: white; border: none; padding: 12px 30px; border-radius: 30px;
            font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.3s;
            display: inline-flex; align-items: center; gap: 10px;
        }
        .submit-btn-appointment:hover:not(:disabled) {
            transform: translateY(-3px); box-shadow: 0 10px 20px rgba(46, 204, 113, 0.3);
        }
        .submit-btn-appointment:disabled {
            opacity: 0.65; transform: none; box-shadow: none; cursor: not-allowed;
            background: var(--form-primary-color); /* Fallback */
        }

        .invalid-feedback {
            color: var(--form-danger-color) !important; font-size: 0.85em !important;
            display: block !important; margin-top: 4px !important;
        }
        .input-with-icon .form-control.is-invalid,
        .input-with-icon .was-validated .form-control:invalid,
        .input-with-icon .form-select.is-invalid,
        .input-with-icon .was-validated .form-select:invalid,
        .input-with-icon .select2-container .select2-selection.is-invalid,
        .input-with-icon input[type="text"].flatpickr-input.is-invalid {
            border-color: var(--form-danger-color) !important;
            background-image: none !important;
        }
        [dir="rtl"] .input-with-icon .form-control.is-invalid,
        /* ... (Rest of RTL invalid padding adjustments if needed, often not if icon padding is handled by variables) ... */

        .select2-container--bootstrap-5 .select2-dropdown {
            border-radius: var(--form-radius); border-color: var(--form-border-color);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
         .select2-container--bootstrap-5 .select2-results__option--selected { background-color: #e9ecef; }
        .select2-container--bootstrap-5 .select2-results__option--highlighted[aria-selected] {
            background-color: var(--form-primary-color) !important; color: white !important;
        }

        #doctors_loading, #times_loading, #dates_loading_indicator {
            color: var(--form-text-color); font-size: 0.9em;
        }

        input:disabled, select:disabled, textarea:disabled,
        .form-control:disabled, .form-select:disabled,
        .select2-container--disabled .select2-selection {
            cursor: not-allowed !important; opacity: 0.65 !important;
            background-color: #e9ecef !important;
        }
        .select2-container--bootstrap-5.select2-container--disabled .select2-selection {
             border-color: var(--form-border-color) !important;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header'); ?>
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto"><i class="fas fa-calendar-plus text-primary me-2"></i> <?php echo e(trans('patients.appointments')); ?></h4>
                <span class="text-muted mt-1 tx-13 ms-2 mb-0">/ <?php echo e(trans('patients.book_new_appointment')); ?></span>
            </div>
        </div>
         <div class="d-flex my-xl-auto right-content">
            <a href="<?php echo e(url()->previous()); ?>" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> <?php echo e(trans('patients.back')); ?>

            </a>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('Dashboard.messages_alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="row justify-content-center">
        <div class="col-lg-10 col-md-12">
            <div class="card appointment-form-card">
                <div class="appointment-form-header">
                    <h5 class="text-white"><i class="fas fa-calendar-plus"></i> <?php echo e(trans('patients.fill_appointment_details_title')); ?></h5>
                </div>
                <div class="card-body p-0">
                    <form action="<?php echo e(route('patient.appointments.store')); ?>" method="POST" class="needs-validation" novalidate autocomplete="off">
                        <?php echo csrf_field(); ?>
                        <fieldset class="form-section-fieldset patient-info-section">
                            <legend class="form-section-legend">
                                <i class="fas fa-user-injured"></i> <?php echo e(trans('patients.patient_information')); ?>

                            </legend>
                            <div class="row g-3">
                                <div class="col-md-4 form-group">
                                    <label for="patient_name" class="form-label"><?php echo e(trans('patients.full_name')); ?> <span class="text-danger">*</span></label>
                                    <div class="input-with-icon">
                                        <input type="text" name="patient_name" id="patient_name" class="form-control <?php $__errorArgs = ['patient_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('patient_name', $patientName ?? '')); ?>" required placeholder="<?php echo e(trans('patients.enter_your_full_name_placeholder')); ?>">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <?php $__errorArgs = ['patient_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="patient_email" class="form-label"><?php echo e(trans('patients.email')); ?> <span class="text-danger">*</span></label>
                                    <div class="input-with-icon">
                                        <input type="email" name="patient_email" id="patient_email" class="form-control <?php $__errorArgs = ['patient_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('patient_email', $patientEmail ?? '')); ?>" required placeholder="<?php echo e(trans('patients.email_placeholder')); ?>">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <?php $__errorArgs = ['patient_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="patient_phone" class="form-label"><?php echo e(trans('patients.phone_number')); ?> <span class="text-danger">*</span></label>
                                    <div class="input-with-icon">
                                        <input type="tel" name="patient_phone" id="patient_phone" class="form-control <?php $__errorArgs = ['patient_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('patient_phone', $patientPhone ?? '')); ?>" required placeholder="<?php echo e(trans('patients.phone_placeholder_sa')); ?>">
                                        <i class="fas fa-phone-alt"></i>
                                    </div>
                                    <?php $__errorArgs = ['patient_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="form-section-fieldset">
                            <legend class="form-section-legend">
                                <i class="fas fa-calendar-alt"></i> <?php echo e(trans('patients.appointment_details')); ?>

                            </legend>
                            <div class="row g-3">
                                <div class="col-md-6 form-group">
                                    <label for="section_id" class="form-label"><?php echo e(trans('patients.medical_section')); ?> <span class="text-danger">*</span></label>
                                    <div class="input-with-icon">
                                        <select name="section_id" id="section_id" class="form-select select2 <?php $__errorArgs = ['section_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required data-placeholder="<?php echo e(trans('patients.select_section_placeholder')); ?>">
                                            <option value=""></option>
                                            <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($section->id); ?>" <?php echo e(old('section_id', $selectedSectionId ?? '') == $section->id ? 'selected' : ''); ?>>
                                                    <?php echo e($section->name); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <i class="fas fa-stethoscope"></i>
                                    </div>
                                    <?php $__errorArgs = ['section_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="doctor_id" class="form-label"><?php echo e(trans('patients.attending_doctor')); ?> <span class="text-danger">*</span></label>
                                    <div class="input-with-icon">
                                        <select name="doctor_id" id="doctor_id" class="form-select select2 <?php $__errorArgs = ['doctor_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required data-placeholder="<?php echo e(trans('patients.select_doctor_placeholder')); ?>" disabled>
                                            <option value=""></option>
                                            <?php if(old('doctor_id') && isset($doctors) && $doctors->isNotEmpty() && $doctors->contains('id', old('doctor_id'))): ?>
                                                <option value="<?php echo e(old('doctor_id')); ?>" selected><?php echo e($doctors->firstWhere('id', old('doctor_id'))->name); ?></option>
                                            <?php elseif(isset($selectedDoctorId) && isset($doctors) && $doctors->isNotEmpty() && $doctors->contains('id', $selectedDoctorId)): ?>
                                                 <option value="<?php echo e($selectedDoctorId); ?>" selected><?php echo e($doctors->firstWhere('id', $selectedDoctorId)->name); ?></option>
                                            <?php endif; ?>
                                        </select>
                                        <i class="fas fa-user-md"></i>
                                    </div>
                                    <?php $__errorArgs = ['doctor_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <div id="doctors_loading" style="display: none;" class="mt-1"><small class="text-muted"><i class="fas fa-spinner fa-spin me-1"></i> <?php echo e(trans('patients.loading_doctors')); ?></small></div>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="selected_date_display" class="form-label"><?php echo e(trans('patients.appointment_date')); ?> <span class="text-danger">*</span></label>
                                    <div class="input-with-icon">
                                        <input type="text" id="selected_date_display" class="form-control flatpickr-date <?php $__errorArgs = ['selected_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                               value="<?php echo e(old('selected_date')); ?>" required placeholder="<?php echo e(trans('patients.select_doctor_first_placeholder')); ?>" disabled readonly="readonly">
                                        <i class="fas fa-calendar-day"></i>
                                    </div>
                                    <input type="hidden" name="selected_date" id="selected_date" value="<?php echo e(old('selected_date')); ?>">
                                    <div id="dates_loading_indicator" style="display: none;" class="mt-1"><small class="text-muted"><i class="fas fa-spinner fa-spin me-1"></i> <?php echo e(trans('patients.loading_doctor_work_days')); ?></small></div>
                                    <?php $__errorArgs = ['selected_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="col-md-12 form-group">
                                    <label class="form-label mt-2"><?php echo e(trans('patients.appointment_time')); ?> <span class="text-danger">*</span></label>
                                    <div id="times_loading" style="display: none;" class="mb-2"><small class="text-muted"><i class="fas fa-spinner fa-spin me-1"></i> <?php echo e(trans('patients.loading_times')); ?></small></div>
                                    <div id="no_times_message" style="display: none;"></div>

                                    <div id="available_time_slots_container" class="mt-1 time-slots-container" style="display: none;">
                                        <div class="d-flex flex-wrap" id="time_slots_wrapper">
                                            
                                        </div>
                                    </div>
                                    <input type="hidden" name="selected_time" id="selected_time" value="<?php echo e(old('selected_time')); ?>">
                                    <?php $__errorArgs = ['selected_time'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback d-block"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </fieldset>

                        <div class="p-4">
                            <div class="form-group">
                                <label for="notes" class="form-label"><?php echo e(trans('patients.additional_notes_optional')); ?></label>
                                <textarea name="notes" id="notes" class="form-control <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="3" placeholder="<?php echo e(trans('patients.additional_notes_placeholder')); ?>"><?php echo e(old('notes')); ?></textarea>
                                <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="form-actions-container">
                            <button type="submit" class="submit-btn-appointment" id="submitAppointmentBtn">
                                <i class="fas fa-calendar-check me-2"></i> <?php echo e(trans('patients.confirm_booking')); ?>

                            </button>
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
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/ar.js"></script>
    <script src="<?php echo e(URL::asset('Dashboard/plugins/notify/js/notifIt.js')); ?>"></script>

    <script>
        const initialData = {
            selectedSectionId: <?php echo json_encode(old('section_id', $selectedSectionId ?? null), 512) ?>,
            selectedDoctorId: <?php echo json_encode(old('doctor_id', $selectedDoctorId ?? null), 512) ?>,
            oldSelectedDate: <?php echo json_encode(old('selected_date', null), 512) ?>,
            oldSelectedTime: <?php echo json_encode(old('selected_time', null), 512) ?>,
            doctorsForOldSection: <?php echo json_encode(old('section_id') && isset($doctors) && $doctors->isNotEmpty() ? $doctors : collect(), 15, 512) ?>
        };

        $(document).ready(function() {
            $('#section_id, #doctor_id').select2({
                placeholder: $(this).data('placeholder') || "<?php echo e(trans('patients.js_select_placeholder')); ?>",
                width: '100%', dir: "rtl", theme: "bootstrap-5",
                allowClear: true, language: "ar"
            }).on('change', function() {
                const $select2Container = $(this).next('.select2-container').find('.select2-selection');
                const $errorFeedback = $(this).closest('.form-group').find('.invalid-feedback');
                if ($(this).val()) {
                    $select2Container.removeClass('is-invalid').addClass('is-valid');
                    if ($errorFeedback.length) $errorFeedback.hide();
                } else if ($(this).prop('required')) {
                    $select2Container.removeClass('is-valid').addClass('is-invalid');
                }
            });

            let flatpickrInstance = null;
            const dateDisplayInput = $('#selected_date_display');
            const dateHiddenInput = $('#selected_date');
            const datesLoadingIndicator = $('#dates_loading_indicator');
            const submitButton = $('#submitAppointmentBtn');

            function initializeFlatpickr() {
                if (dateDisplayInput.length === 0) return;
                flatpickrInstance = dateDisplayInput.flatpickr({
                    dateFormat: "Y-m-d", locale: "ar", minDate: "today",
                    disableMobile: true, enable: [], // Initially empty
                    onChange: function(selectedDates, dateStr, instance) {
                        dateHiddenInput.val(dateStr);
                        const $dateErrorFeedback = dateDisplayInput.closest('.form-group').find('.invalid-feedback');
                        if (dateStr) {
                            fetchAvailableTimes($('#doctor_id').val(), dateStr);
                            dateDisplayInput.removeClass('is-invalid').addClass('is-valid');
                            if($dateErrorFeedback.length) $dateErrorFeedback.hide();
                        } else {
                            resetTimeSelection();
                            if (dateDisplayInput.prop('required')) {
                               dateDisplayInput.removeClass('is-valid').addClass('is-invalid');
                            }
                        }
                        updateSubmitButtonState();
                    },
                });
                disableDateField();
            }
            initializeFlatpickr();

            function disableDateField() {
                if (flatpickrInstance) {
                    flatpickrInstance.clear();
                    flatpickrInstance.set('enable', []); // Clear enabled dates
                }
                dateDisplayInput.prop('disabled', true).attr('placeholder', "<?php echo e(trans('patients.js_select_doctor_first')); ?>").removeClass('is-valid is-invalid');
                dateHiddenInput.val('');
                resetTimeSelection();
            }

            function enableDateField() {
                 dateDisplayInput.prop('disabled', false); // Placeholder will be set after fetching dates
                 updateSubmitButtonState();
            }

            function resetTimeSelection() {
                $('#time_slots_wrapper').empty();
                $('#available_time_slots_container').hide();
                $('#no_times_message').hide().text('').removeClass('text-danger');
                $('#selected_time').val('');
                $('#selected_time').next('.invalid-feedback').hide();
                updateSubmitButtonState();
            }

            $('#section_id').on('change', function() {
                const sectionId = $(this).val();
                const doctorSelect = $('#doctor_id');
                const doctorsLoading = $('#doctors_loading');

                doctorSelect.html('<option value=""></option>').val(null).trigger('change.select2').prop('disabled', true);
                if (doctorSelect.prop('required')) {
                    doctorSelect.next('.select2-container').find('.select2-selection').removeClass('is-valid').addClass('is-invalid');
                }
                disableDateField(); // Reset date field

                if (sectionId) {
                    doctorsLoading.show();
                    $.ajax({
                        url: "<?php echo e(route('ajax.get_doctors_by_section')); ?>",
                        type: "GET", data: { section_id: sectionId }, dataType: 'json',
                        success: function(response) {
                            doctorsLoading.hide();
                            doctorSelect.html('<option value=""><?php echo e(trans("patients.select_doctor_placeholder")); ?></option>');
                            if (response && response.doctors && response.doctors.length > 0) {
                                response.doctors.forEach(doctor => doctorSelect.append(new Option(doctor.name, doctor.id)));
                                doctorSelect.prop('disabled', false);

                                if (initialData.selectedDoctorId && doctorSelect.find('option[value="' + initialData.selectedDoctorId + '"]').length > 0) {
                                     doctorSelect.val(initialData.selectedDoctorId).trigger('change');
                                } else if (doctorSelect.prop('required')) {
                                     doctorSelect.next('.select2-container').find('.select2-selection').addClass('is-invalid');
                                }
                            } else {
                                doctorSelect.append(new Option(response.error || "<?php echo e(trans('patients.js_no_doctors_in_section')); ?>", '', true, true));
                            }
                            doctorSelect.trigger('change.select2');
                        },
                        error: function(jqXHR) {
                            doctorsLoading.hide();
                            console.error("AJAX Error fetching doctors:", jqXHR.responseText);
                            notif({ msg: (jqXHR.responseJSON?.error || "<?php echo e(trans('patients.js_error_loading_doctors')); ?>"), type: "error", position: "bottom" });
                            doctorSelect.html('<option value=""><?php echo e(trans("patients.js_error_placeholder")); ?></option>').trigger('change.select2');
                        }
                    });
                }
            });

            $('#doctor_id').on('change', function() {
                const doctorId = $(this).val();
                disableDateField();

                if (doctorId) {
                    datesLoadingIndicator.show();
                    $.ajax({
                        url: "<?php echo e(route('ajax.get_doctor_available_dates')); ?>",
                        type: "GET", data: { doctor_id: doctorId }, dataType: 'json',
                        success: function(response) {
                            datesLoadingIndicator.hide();
                            if (flatpickrInstance && response.enabledDates) {
                                flatpickrInstance.set('enable', response.enabledDates);
                                if (response.enabledDates.length > 0) {
                                    enableDateField();
                                    dateDisplayInput.attr('placeholder', "<?php echo e(trans('patients.js_select_date_from_available')); ?>");
                                    if (initialData.oldSelectedDate && response.enabledDates.includes(initialData.oldSelectedDate)) {
                                        flatpickrInstance.setDate(initialData.oldSelectedDate, true);
                                    }
                                } else {
                                    disableDateField();
                                    dateDisplayInput.attr('placeholder', "<?php echo e(trans('patients.js_no_available_days')); ?>");
                                    $('#no_times_message').text(response.message || "<?php echo e(trans('patients.js_no_available_days')); ?>.").show();
                                }
                            } else {
                                disableDateField();
                                dateDisplayInput.attr('placeholder', response.message || "<?php echo e(trans('patients.js_error_loading_days')); ?>");
                            }
                        },
                        error: function(jqXHR) {
                            datesLoadingIndicator.hide();
                            console.error("AJAX Error fetching doctor available dates:", jqXHR.responseText);
                            disableDateField();
                            dateDisplayInput.attr('placeholder', "<?php echo e(trans('patients.js_error_loading_days')); ?>");
                            notif({ msg: "<?php echo e(trans('patients.js_error_loading_work_days')); ?>", type: "error", position: "bottom" });
                        }
                    });
                }
                const $select2Container = $(this).next('.select2-container').find('.select2-selection');
                 if ($(this).val()) {
                    $select2Container.removeClass('is-invalid').addClass('is-valid');
                } else if ($(this).prop('required')) {
                    $select2Container.removeClass('is-valid').addClass('is-invalid');
                }
            });

            function fetchAvailableTimes(doctorId, selectedDate) {
                const timeSlotsWrapper = $('#time_slots_wrapper');
                const availableTimeSlotsContainer = $('#available_time_slots_container');
                const timesLoading = $('#times_loading');
                const noTimesMessage = $('#no_times_message');
                const hiddenTimeInput = $('#selected_time');

                resetTimeSelection();

                if (doctorId && selectedDate) {
                    timesLoading.show();
                    $.ajax({
                        url: "<?php echo e(route('ajax.get_available_times')); ?>",
                        type: "GET", data: { doctor_id: doctorId, selected_date: selectedDate }, dataType: 'json',
                        success: function(response) {
                            timesLoading.hide();
                            if (response && response.times && response.times.length > 0) {
                                response.times.forEach(function(timeObj) {
                                    $('<button type="button" class="time-slot-btn"></button>')
                                        .text(timeObj.display).val(timeObj.value)
                                        .on('click', function() {
                                            timeSlotsWrapper.find('.time-slot-btn').removeClass('selected active');
                                            $(this).addClass('selected active');
                                            hiddenTimeInput.val(timeObj.value).trigger('change');
                                            $('#selected_time').next('.invalid-feedback').hide();
                                            noTimesMessage.hide().removeClass('text-danger');
                                            updateSubmitButtonState();
                                        }).appendTo(timeSlotsWrapper);
                                });
                                availableTimeSlotsContainer.show();
                                noTimesMessage.hide();
                                if (initialData.oldSelectedTime) {
                                    const matchingButton = timeSlotsWrapper.find('button[value="' + initialData.oldSelectedTime + '"]');
                                    if (matchingButton.length > 0) matchingButton.click();
                                    initialData.oldSelectedTime = null;
                                }
                            } else {
                                noTimesMessage.text(response.message || "<?php echo e(trans('patients.js_no_available_times')); ?>").show();
                            }
                        },
                        error: function(jqXHR) {
                            timesLoading.hide();
                            console.error("AJAX Error fetching times:", jqXHR.responseText);
                            noTimesMessage.text(jqXHR.responseJSON?.message || jqXHR.responseJSON?.error || "<?php echo e(trans('patients.js_error_loading_times')); ?>").show();
                        }
                    });
                }
            }

            function updateSubmitButtonState() {
                const doctorSelected = $('#doctor_id').val();
                const dateSelected = $('#selected_date').val();
                const timeSelected = $('#selected_time').val();
                submitButton.prop('disabled', !(doctorSelected && dateSelected && timeSelected));
            }
            updateSubmitButtonState();

            if (initialData.selectedSectionId) {
                $('#section_id').val(initialData.selectedSectionId).trigger('change');
            } else if (initialData.doctorsForOldSection && initialData.doctorsForOldSection.length > 0 && initialData.selectedDoctorId) {
                const doctorSelect = $('#doctor_id');
                doctorSelect.html('<option value="">--<?php echo e(trans("patients.select_doctor_placeholder")); ?>--</option>');
                initialData.doctorsForOldSection.forEach(doc => doctorSelect.append(new Option(doc.name, doc.id, false, doc.id == initialData.selectedDoctorId)));
                if (initialData.selectedDoctorId) doctorSelect.val(initialData.selectedDoctorId);
                doctorSelect.prop('disabled', false).trigger('change.select2');
                if (doctorSelect.val()) doctorSelect.trigger('change');
            }

            (function () {
                'use strict';
                var forms = document.querySelectorAll('.needs-validation');
                Array.prototype.slice.call(forms).forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        const hiddenTimeInput = form.querySelector('#selected_time');
                        const timeErrorFeedback = $(hiddenTimeInput).next('.invalid-feedback');

                        $(form).find('select.select2[required]').each(function() {
                            if (!$(this).val()) {
                                $(this).next('.select2-container').find('.select2-selection').addClass('is-invalid');
                            }
                        });
                        if ($(dateDisplayInput).prop('required') && !$('#selected_date').val()) {
                             $(dateDisplayInput).addClass('is-invalid');
                             $(dateDisplayInput).closest('.form-group').find('.invalid-feedback').show();
                        }

                        if ($('#doctor_id').val() && $('#selected_date').val() && !hiddenTimeInput.value) {
                             $('#no_times_message').text("<?php echo e(trans('patients.js_please_select_time')); ?>").addClass('text-danger').show();
                             if(timeErrorFeedback.length) timeErrorFeedback.text("<?php echo e(trans('patients.js_please_select_a_time')); ?>").show();
                             event.preventDefault();
                             event.stopPropagation();
                        } else if (hiddenTimeInput.value) {
                             $('#no_times_message').hide().removeClass('text-danger');
                             if(timeErrorFeedback.length) timeErrorFeedback.hide();
                        }

                        if (!form.checkValidity()) {
                            event.preventDefault();
                            event.stopPropagation();
                        } else {
                            submitButton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> <?php echo e(trans("patients.js_booking_in_progress")); ?>');
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            })();

            <?php if(session('success_message')): ?>
                notif({ msg: "<i class='fas fa-check-circle me-2'></i> <?php echo e(session('success_message')); ?>", type: "success", position: "bottom", autohide: true, timeout: 7000 });
            <?php endif; ?>
            <?php if(session('error')): ?>
                notif({ msg: "<i class='fas fa-exclamation-triangle me-2'></i> <?php echo e(session('error')); ?>", type: "error", position: "bottom", autohide: true, timeout: 7000 });
            <?php endif; ?>
            <?php if($errors->any()): ?>
                let errorMsg = "<strong class='d-block mb-1'><i class='fas fa-times-circle me-2'></i> <?php echo e(trans('patients.js_please_correct_errors')); ?></strong><ul class='list-unstyled mb-0 ps-3' style='text-align: right;'>";
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    errorMsg += "<li>- <?php echo e($error); ?></li>";
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                errorMsg += "</ul>";
                notif({ msg: errorMsg, type: "error", position: "bottom", multiline: true, autohide: false });
            <?php endif; ?>
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Dashboard.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/Patients/appointments/patient_create.blade.php ENDPATH**/ ?>