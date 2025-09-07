

<?php
    $isEditMode = isset($patientAdmission) && $patientAdmission->id;
    $isDischargeMode =
        $isEditMode &&
        (request()->get('action') === 'discharge' || (isset($prefillDischarge) && $prefillDischarge === true));
    $errorBagName = $isEditMode ? 'editAdmissionFormBag' . $patientAdmission->id : 'createAdmissionFormBag';
?>

<div class="row">
    <!-- Patient and Doctor Information Section -->
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="fas fa-user-plus me-2"></i>
                    <?php echo e(trans('AdminSb.patient_and_doctor_info')); ?></h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="patient_id" class="form-label"><?php echo e(trans('AdminSb.patient')); ?> <span
                            class="text-danger">*</span></label>
                    <?php if($isEditMode && !$isDischargeMode): ?>
                        <input type="text" class="form-control"
                            value="<?php echo e($patientAdmission->patient->name ?? trans('AdminSb.unknown')); ?>" readonly>
                        <input type="hidden" name="patient_id" value="<?php echo e($patientAdmission->patient_id); ?>">
                    <?php else: ?>
                        <select name="patient_id" id="patient_id"
                            class="form-select select2-patient <?php $__errorArgs = ['patient_id', $errorBagName];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            <?php echo e($isEditMode ? 'disabled' : 'required'); ?>>
                            <option value=""><?php echo e(trans('AdminSb.select_patient')); ?></option>
                            <?php if($isEditMode && $patientAdmission->patient): ?>
                                <option value="<?php echo e($patientAdmission->patient_id); ?>" selected>
                                    <?php echo e($patientAdmission->patient->name); ?>

                                    (<?php echo e($patientAdmission->patient->national_id); ?>)</option>
                            <?php endif; ?>
                            <?php $__currentLoopData = $patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(!($isEditMode && $patientAdmission->patient_id == $patient->id)): ?>
                                    <option value="<?php echo e($patient->id); ?>"
                                        <?php echo e(old('patient_id', $isEditMode ? $patientAdmission->patient_id : '') == $patient->id ? 'selected' : ''); ?>>
                                        <?php echo e($patient->name); ?> (<?php echo e($patient->national_id); ?>)
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['patient_id', $errorBagName];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <?php if($isEditMode): ?>
                            <small
                                class="form-text text-muted"><?php echo e(trans('AdminSb.cannot_change_patient_for_existing_admission')); ?></small>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="doctor_id" class="form-label"><?php echo e(trans('AdminSb.attending_physician')); ?></label>
                    <select name="doctor_id" id="doctor_id"
                        class="form-select select2 <?php $__errorArgs = ['doctor_id', $errorBagName];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        <?php echo e($isDischargeMode ? 'disabled' : ''); ?>>
                        <option value=""><?php echo e(trans('AdminSb.select_doctor')); ?></option>
                        <?php $__currentLoopData = $doctors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $doctor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($doctor->id); ?>"
                                <?php echo e(old('doctor_id', $isEditMode ? $patientAdmission->doctor_id : '') == $doctor->id ? 'selected' : ''); ?>>
                                <?php echo e($doctor->name); ?> <?php echo e($doctor->section ? '(' . $doctor->section->name . ')' : ''); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['doctor_id', $errorBagName];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <?php if($isDischargeMode): ?>
                        <input type="hidden" name="doctor_id" value="<?php echo e($patientAdmission->doctor_id); ?>">
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Admission and Bed Information Section -->
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="fas fa-procedures me-2"></i>
                    <?php echo e(trans('AdminSb.admission_details')); ?></h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="admission_date" class="form-label"><?php echo e(trans('AdminSb.admission_datetime')); ?> <span
                            class="text-danger">*</span></label>
                    <input type="datetime-local" name="admission_date" id="admission_date"
                        class="form-control <?php $__errorArgs = ['admission_date', $errorBagName];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        value="<?php echo e(old('admission_date', $isEditMode ? ($patientAdmission->admission_date ? $patientAdmission->admission_date->format('Y-m-d\TH:i') : '') : now()->format('Y-m-d\TH:i'))); ?>"
                        <?php echo e($isDischargeMode ? 'readonly' : 'required'); ?>>
                    <?php $__errorArgs = ['admission_date', $errorBagName];
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

                <div class="mb-3">
                    <label for="bed_id" class="form-label"><?php echo e(trans('AdminSb.bed_room_department')); ?></label>
                    <select name="bed_id" id="bed_id"
                        class="form-select select2-beds <?php $__errorArgs = ['bed_id', $errorBagName];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        <?php echo e($isDischargeMode ? 'disabled' : ''); ?>>
                        <option value=""><?php echo e(trans('AdminSb.select_bed_optional')); ?></option>
                        <?php if(
                            $isEditMode &&
                                $patientAdmission->bed_id &&
                                !collect($availableBeds)->pluck('id')->contains($patientAdmission->bed_id)): ?>
                            <option value="<?php echo e($patientAdmission->bed_id); ?>" selected>
                                <?php echo e($patientAdmission->bed->bed_number); ?>

                                <?php if($patientAdmission->bed->room): ?>
                                    (<?php echo e(trans('AdminSb.room_colon_short')); ?>

                                    <?php echo e($patientAdmission->bed->room->room_number); ?>

                                    <?php if($patientAdmission->bed->room->section): ?>
                                        - <?php echo e(trans('AdminSb.department_colon_short')); ?>

                                        <?php echo e($patientAdmission->bed->room->section->name); ?>

                                    <?php endif; ?>
                                    ) (<?php echo e(trans('AdminSb.current_bed_tag')); ?>)
                                <?php endif; ?>
                            </option>
                        <?php endif; ?>
                        <?php $__currentLoopData = $availableBeds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bed): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($bed->id); ?>" data-section-id="<?php echo e($bed->room->section_id ?? ''); ?>"
                                data-section-name="<?php echo e($bed->room->section->name ?? ''); ?>"
                                <?php echo e(old('bed_id', $isEditMode ? $patientAdmission->bed_id : '') == $bed->id ? 'selected' : ''); ?>>
                                <?php echo e($bed->display_name ?? $bed->bed_number); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['bed_id', $errorBagName];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <?php if($isDischargeMode): ?>
                        <input type="hidden" name="bed_id" value="<?php echo e($patientAdmission->bed_id); ?>">
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="section_id"
                        class="form-label"><?php echo e(trans('AdminSb.department_auto_select_note')); ?></label>
                    <select name="section_id" id="section_id"
                        class="form-select select2 <?php $__errorArgs = ['section_id', $errorBagName];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        <?php echo e($isDischargeMode ? 'disabled' : ''); ?>>
                        <option value=""><?php echo e(trans('AdminSb.select_department_if_no_bed')); ?></option>
                        <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($section->id); ?>"
                                <?php echo e(old('section_id', $isEditMode ? $patientAdmission->section_id : '') == $section->id ? 'selected' : ''); ?>>
                                <?php echo e($section->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['section_id', $errorBagName];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <?php if($isDischargeMode): ?>
                        <input type="hidden" name="section_id" value="<?php echo e($patientAdmission->section_id); ?>">
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="fas fa-file-medical-alt me-2"></i>
                    <?php echo e(trans('AdminSb.diagnosis_and_admission_reason_details')); ?></h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="reason_for_admission"
                            class="form-label"><?php echo e(trans('AdminSb.reason_for_admission')); ?></label>
                        <textarea name="reason_for_admission" id="reason_for_admission" rows="3"
                            class="form-control <?php $__errorArgs = ['reason_for_admission', $errorBagName];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            <?php echo e($isDischargeMode ? 'readonly' : ''); ?>><?php echo e(old('reason_for_admission', $isEditMode ? $patientAdmission->reason_for_admission : '')); ?></textarea>
                        <?php $__errorArgs = ['reason_for_admission', $errorBagName];
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
                    <div class="col-md-6 mb-3">
                        <label for="admitting_diagnosis"
                            class="form-label"><?php echo e(trans('AdminSb.initial_diagnosis_on_admission')); ?></label>
                        <textarea name="admitting_diagnosis" id="admitting_diagnosis" rows="3"
                            class="form-control <?php $__errorArgs = ['admitting_diagnosis', $errorBagName];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            <?php echo e($isDischargeMode ? 'readonly' : ''); ?>><?php echo e(old('admitting_diagnosis', $isEditMode ? $patientAdmission->admitting_diagnosis : '')); ?></textarea>
                        <?php $__errorArgs = ['admitting_diagnosis', $errorBagName];
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
        </div>
    </div>
</div>

<?php if($isEditMode): ?>
    <hr>
    <div class="row">
        <div class="col-12">
            <h4><i class="fas fa-sign-out-alt me-2"></i> <?php echo e(trans('AdminSb.discharge_information')); ?></h4>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-md-4 mb-3">
            <label for="discharge_date" class="form-label"><?php echo e(trans('AdminSb.discharge_datetime')); ?></label>
            <input type="datetime-local" name="discharge_date" id="discharge_date"
                class="form-control <?php $__errorArgs = ['discharge_date', $errorBagName];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                value="<?php echo e(old('discharge_date', $patientAdmission->discharge_date ? $patientAdmission->discharge_date->format('Y-m-d\TH:i') : ($isDischargeMode ? now()->format('Y-m-d\TH:i') : ''))); ?>"
                <?php echo e($isDischargeMode ? 'required' : ''); ?>>
            <?php $__errorArgs = ['discharge_date', $errorBagName];
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
        <div class="col-md-4 mb-3">
            <label for="discharge_reason_select" class="form-label"><?php echo e(trans('AdminSb.discharge_reason')); ?></label>
            <select class="form-select <?php $__errorArgs = ['discharge_reason', $errorBagName];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                id="discharge_reason_select_form" name="discharge_reason_select">
                <option value=""><?php echo e(trans('AdminSb.select_discharge_reason')); ?></option>
                <option value="<?php echo e(trans('AdminSb.reason_improved_condition')); ?>"
                    <?php echo e(old('discharge_reason', $patientAdmission->discharge_reason) == trans('AdminSb.reason_improved_condition') ? 'selected' : ''); ?>>
                    <?php echo e(trans('AdminSb.reason_improved_condition')); ?></option>
                <option value="<?php echo e(trans('AdminSb.reason_patient_request')); ?>"
                    <?php echo e(old('discharge_reason', $patientAdmission->discharge_reason) == trans('AdminSb.reason_patient_request') ? 'selected' : ''); ?>>
                    <?php echo e(trans('AdminSb.reason_patient_request')); ?></option>
                <option value="<?php echo e(trans('AdminSb.reason_transfer_to_another_hospital')); ?>"
                    <?php echo e(old('discharge_reason', $patientAdmission->discharge_reason) == trans('AdminSb.reason_transfer_to_another_hospital') ? 'selected' : ''); ?>>
                    <?php echo e(trans('AdminSb.reason_transfer_to_another_hospital')); ?></option>
                <option value="<?php echo e(trans('AdminSb.reason_death')); ?>"
                    <?php echo e(old('discharge_reason', $patientAdmission->discharge_reason) == trans('AdminSb.reason_death') ? 'selected' : ''); ?>>
                    <?php echo e(trans('AdminSb.reason_death')); ?></option>
                <option value="other"><?php echo e(trans('AdminSb.reason_other')); ?></option>
            </select>
            <textarea name="discharge_reason" id="discharge_reason_text_form" rows="2"
                class="form-control mt-1 <?php $__errorArgs = ['discharge_reason', $errorBagName];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                placeholder="<?php echo e(trans('AdminSb.other_reason_placeholder')); ?>" style="display: none;"><?php echo e(old('discharge_reason', $patientAdmission->discharge_reason)); ?></textarea>
            <?php $__errorArgs = ['discharge_reason', $errorBagName];
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
        <div class="col-md-4 mb-3">
            <label for="discharge_diagnosis" class="form-label"><?php echo e(trans('AdminSb.diagnosis_on_discharge')); ?></label>
            <textarea name="discharge_diagnosis" id="discharge_diagnosis_form" rows="3"
                class="form-control <?php $__errorArgs = ['discharge_diagnosis', $errorBagName];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('discharge_diagnosis', $patientAdmission->discharge_diagnosis)); ?></textarea>
            <?php $__errorArgs = ['discharge_diagnosis', $errorBagName];
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
<?php endif; ?>

<div class="row">
    <div class="col-12">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="fas fa-sticky-note me-2"></i>
                    <?php echo e(trans('AdminSb.status_and_notes')); ?></h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="status" class="form-label"><?php echo e(trans('AdminSb.admission_record_status')); ?> <span
                            class="text-danger">*</span></label>
                    <select name="status" id="status"
                        class="form-select <?php $__errorArgs = ['status', $errorBagName];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required
                        <?php echo e($isEditMode && !$isDischargeMode && $patientAdmission->status === \App\Models\PatientAdmission::STATUS_DISCHARGED ? 'disabled' : ''); ?>>
                        <?php if($isDischargeMode): ?>
                            <option value="<?php echo e(\App\Models\PatientAdmission::STATUS_DISCHARGED); ?>" selected>
                                <?php echo e(trans('AdminSb.discharged_from_hospital')); ?></option>
                        <?php else: ?>
                            <?php $__currentLoopData = $admissionStatuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $statusKey => $statusValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($isEditMode): ?>
                                    <option value="<?php echo e($statusKey); ?>"
                                        <?php echo e(old('status', $patientAdmission->status) == $statusKey ? 'selected' : ''); ?>>
                                        <?php echo e($statusValue); ?></option>
                                <?php else: ?>
                                    <?php if(in_array($statusKey, [\App\Models\PatientAdmission::STATUS_ADMITTED])): ?>
                                        <option value="<?php echo e($statusKey); ?>"
                                            <?php echo e(old('status', \App\Models\PatientAdmission::STATUS_ADMITTED) == $statusKey ? 'selected' : ''); ?>>
                                            <?php echo e($statusValue); ?></option>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </select>
                    <?php $__errorArgs = ['status', $errorBagName];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <?php if($isEditMode && !$isDischargeMode && $patientAdmission->status === \App\Models\PatientAdmission::STATUS_DISCHARGED): ?>
                        <small
                            class="form-text text-muted"><?php echo e(trans('AdminSb.cannot_change_status_of_discharged_record')); ?></small>
                        <input type="hidden" name="status"
                            value="<?php echo e(\App\Models\PatientAdmission::STATUS_DISCHARGED); ?>">
                    <?php endif; ?>
                    <?php if($isDischargeMode): ?>
                        <input type="hidden" name="status"
                            value="<?php echo e(\App\Models\PatientAdmission::STATUS_DISCHARGED); ?>">
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label"><?php echo e(trans('AdminSb.additional_notes')); ?></label>
                    <textarea name="notes" id="notes" rows="3"
                        class="form-control <?php $__errorArgs = ['notes', $errorBagName];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"><?php echo e(old('notes', $isEditMode ? $patientAdmission->notes : '')); ?></textarea>
                    <?php $__errorArgs = ['notes', $errorBagName];
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
    </div>
</div>```


<?php $__env->startPush('js_after_form'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // تحديث القسم عند اختيار سرير
            const bedSelect = document.getElementById('bed_id');
            const sectionSelect = document.getElementById('section_id');
            if (bedSelect && sectionSelect) {
                bedSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const sectionId = selectedOption.getAttribute('data-section-id');
                    if (sectionId) {
                        sectionSelect.value = sectionId;
                        // تحديث Select2 إذا كنت تستخدمه للقسم
                        if (typeof $(sectionSelect).select2 === 'function') {
                            $(sectionSelect).trigger('change');
                        }
                    } else {
                        sectionSelect.value = ""; // مسح القسم إذا لم يكن للسرير قسم محدد
                        if (typeof $(sectionSelect).select2 === 'function') {
                            $(sectionSelect).trigger('change');
                        }
                    }
                });
            }

            // التعامل مع سبب الخروج (في نموذج التعديل الرئيسي)
            const dischargeReasonSelectForm = document.getElementById('discharge_reason_select_form');
            const dischargeReasonTextForm = document.getElementById('discharge_reason_text_form');

            if (dischargeReasonSelectForm && dischargeReasonTextForm) {
                function toggleDischargeReasonText() {
                    if (dischargeReasonSelectForm.value === 'other') {
                        dischargeReasonTextForm.style.display = 'block';
                    } else {
                        dischargeReasonTextForm.style.display = 'none';
                        if (dischargeReasonSelectForm.value !== '') { // إذا اختار المستخدم سبباً محدداً
                            dischargeReasonTextForm.value = dischargeReasonSelectForm.value;
                        } else { // إذا لم يختر سبباً (الخيار الافتراضي "-- اختر ...")
                            //  لا تمسح القيمة إذا كانت هناك قيمة قديمة (مثلاً من قاعدة البيانات)
                            // dischargeReasonTextForm.value = '';
                        }
                    }
                }
                toggleDischargeReasonText(); // Call on load
                dischargeReasonSelectForm.addEventListener('change', toggleDischargeReasonText);

                // للتأكد من أن قيمة الـ textarea هي التي تُرسل كـ discharge_reason
                // إذا لم يكن "other" مختارًا، ننسخ قيمة الـ select إلى الـ textarea.
                // هذا يحدث الآن داخل toggleDischargeReasonText
            }

            // تهيئة Select2 (تأكد من أن لديك jQuery و Select2)
            if (typeof $ === 'function' && typeof $.fn.select2 === 'function') {
                $('.select2').select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    dropdownParent: $(this).closest('.modal') // إذا كان الفورم داخل مودال
                });
                $('.select2-patient').select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: '-- ابحث عن مريض بالاسم أو الهوية --',
                    // dropdownParent: $(this).closest('.modal'), // إذا كان الفورم داخل مودال
                    ajax: {
                        url: "#", // تأكد من وجود هذا المسار
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                q: params.term, // search term
                                page: params.page
                            };
                        },
                        processResults: function(data, params) {
                            params.page = params.page || 1;
                            return {
                                results: data.items,
                                pagination: {
                                    more: (params.page * 15) < data.total_count
                                }
                            };
                        },
                        cache: true
                    },
                    minimumInputLength: 1, // ابدأ البحث بعد حرف واحد
                });

                $('.select2-beds').select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: '-- ابحث عن سرير --',
                    // يمكنك إضافة AJAX هنا إذا كانت قائمة الأسرة كبيرة جداً
                });
            }
        });
    </script>
<?php $__env->stopPush(); ?>
<?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/PatientAdmissions/partials/_form.blade.php ENDPATH**/ ?>