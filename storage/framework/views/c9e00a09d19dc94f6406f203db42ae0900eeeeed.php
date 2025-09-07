

<div class="modal fade" id="dischargePatientModal<?php echo e($admission->id); ?>" tabindex="-1"
    aria-labelledby="dischargePatientModalLabel<?php echo e($admission->id); ?>" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dischargePatientModalLabel<?php echo e($admission->id); ?>">
                    <?php echo e(trans('AdminSb.discharge_patient_title')); ?>

                    <?php echo e($admission->patient->name ?? trans('AdminSb.unknown')); ?>

                    (<?php echo e(trans('AdminSb.admission_record_no')); ?> #<?php echo e($admission->id); ?>)
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo e(route('admin.patient_admissions.update', $admission->id)); ?>" method="POST"
                id="dischargeForm<?php echo e($admission->id); ?>">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="modal-body">
                    <p class="alert alert-info">
                        <?php echo e(trans('AdminSb.discharge_modal_info_message')); ?>

                    </p>

                    <input type="hidden" name="status" value="<?php echo e(\App\Models\PatientAdmission::STATUS_DISCHARGED); ?>">
                    <input type="hidden" name="patient_id" value="<?php echo e($admission->patient_id); ?>">
                    <input type="hidden" name="admission_date"
                        value="<?php echo e($admission->admission_date->format('Y-m-d\TH:i')); ?>">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="discharge_date<?php echo e($admission->id); ?>"
                                class="form-label"><?php echo e(trans('AdminSb.discharge_datetime')); ?> <span
                                    class="text-danger">*</span></label>
                            <input type="datetime-local"
                                class="form-control <?php $__errorArgs = ['discharge_date', 'dischargeFormBag' . $admission->id];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                id="discharge_date<?php echo e($admission->id); ?>" name="discharge_date"
                                value="<?php echo e(old('discharge_date', now()->format('Y-m-d\TH:i'))); ?>" required>
                            <?php $__errorArgs = ['discharge_date', 'dischargeFormBag' . $admission->id];
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
                            <label for="discharge_reason_select<?php echo e($admission->id); ?>"
                                class="form-label"><?php echo e(trans('AdminSb.discharge_reason')); ?></label>
                            <select
                                class="form-select <?php $__errorArgs = ['discharge_reason', 'dischargeFormBag' . $admission->id];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                id="discharge_reason_select<?php echo e($admission->id); ?>" name="discharge_reason_select">
                                <option value=""><?php echo e(trans('AdminSb.select_discharge_reason')); ?></option>
                                <option value="<?php echo e(trans('AdminSb.reason_improved_condition')); ?>"
                                    <?php echo e(old('discharge_reason_select', $admission->discharge_reason) == trans('AdminSb.reason_improved_condition') ? 'selected' : ''); ?>>
                                    <?php echo e(trans('AdminSb.reason_improved_condition')); ?></option>
                                <option value="<?php echo e(trans('AdminSb.reason_patient_request')); ?>"
                                    <?php echo e(old('discharge_reason_select', $admission->discharge_reason) == trans('AdminSb.reason_patient_request') ? 'selected' : ''); ?>>
                                    <?php echo e(trans('AdminSb.reason_patient_request')); ?></option>
                                <option value="<?php echo e(trans('AdminSb.reason_transfer_to_another_hospital')); ?>"
                                    <?php echo e(old('discharge_reason_select', $admission->discharge_reason) == trans('AdminSb.reason_transfer_to_another_hospital') ? 'selected' : ''); ?>>
                                    <?php echo e(trans('AdminSb.reason_transfer_to_another_hospital')); ?></option>
                                <option value="<?php echo e(trans('AdminSb.reason_death')); ?>"
                                    <?php echo e(old('discharge_reason_select', $admission->discharge_reason) == trans('AdminSb.reason_death') ? 'selected' : ''); ?>>
                                    <?php echo e(trans('AdminSb.reason_death')); ?></option>
                                <option value="other"><?php echo e(trans('AdminSb.reason_other')); ?></option>
                            </select>

                            <textarea class="form-control mt-2 <?php $__errorArgs = ['discharge_reason', 'dischargeFormBag' . $admission->id];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                id="discharge_reason_text<?php echo e($admission->id); ?>" name="discharge_reason" rows="2"
                                placeholder="<?php echo e(trans('AdminSb.other_reason_details_placeholder')); ?>" style="display: none;"><?php echo e(old('discharge_reason', $admission->discharge_reason)); ?></textarea>

                            <?php $__errorArgs = ['discharge_reason', 'dischargeFormBag' . $admission->id];
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

                    <div class="mb-3">
                        <label for="discharge_diagnosis<?php echo e($admission->id); ?>"
                            class="form-label"><?php echo e(trans('AdminSb.diagnosis_on_discharge')); ?></label>
                        <textarea class="form-control <?php $__errorArgs = ['discharge_diagnosis', 'dischargeFormBag' . $admission->id];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            id="discharge_diagnosis<?php echo e($admission->id); ?>" name="discharge_diagnosis" rows="3"
                            placeholder="<?php echo e(trans('AdminSb.final_diagnosis_placeholder')); ?>"><?php echo e(old('discharge_diagnosis', $admission->discharge_diagnosis)); ?></textarea>
                        <?php $__errorArgs = ['discharge_diagnosis', 'dischargeFormBag' . $admission->id];
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
                        <label for="notes<?php echo e($admission->id); ?>"
                            class="form-label"><?php echo e(trans('AdminSb.additional_discharge_notes')); ?></label>
                        <textarea class="form-control <?php $__errorArgs = ['notes', 'dischargeFormBag' . $admission->id];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            id="notes<?php echo e($admission->id); ?>" name="notes" rows="3"
                            placeholder="<?php echo e(trans('AdminSb.any_other_notes_placeholder')); ?>"><?php echo e(old('notes', $admission->notes)); ?></textarea>
                        <?php $__errorArgs = ['notes', 'dischargeFormBag' . $admission->id];
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-dismiss="modal"><?php echo e(trans('AdminSb.cancel')); ?></button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check-circle me-1"></i> <?php echo e(trans('AdminSb.confirm_patient_discharge')); ?>

                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->startSection('js'); ?>
    
    <script src="<?php echo e(asset('js/bootstrap.bundle.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/jquery.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/bootstrap-select.min.js')); ?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dischargeReasonSelect = document.getElementById('discharge_reason_select<?php echo e($admission->id); ?>');
            const dischargeReasonText = document.getElementById('discharge_reason_text<?php echo e($admission->id); ?>');

            if (dischargeReasonSelect && dischargeReasonText) {
                // Set initial state for textarea based on select value on load
                if (dischargeReasonSelect.value === 'other') {
                    dischargeReasonText.style.display = 'block';
                } else if (dischargeReasonSelect.value !== '') {
                    // If a predefined option is selected, and the text area is not empty (e.g. from old input or db)
                    // we don't want to clear it if it's not 'other'.
                    // If a predefined option is selected and text area is empty, it should remain hidden.
                    // This logic is now mostly handled by the `style` attribute in the textarea itself.
                } else {
                    // No option selected, check if text has value (from DB or old input)
                    if (dischargeReasonText.value.trim() !== '') {
                        dischargeReasonText.style.display = 'block';
                        // Optionally, select "other" if text is present and no option is selected
                        // This might be too aggressive. Let's keep it simple.
                        // dischargeReasonSelect.value = 'other';
                    } else {
                        dischargeReasonText.style.display = 'none';
                    }
                }


                dischargeReasonSelect.addEventListener('change', function() {
                    if (this.value === 'other') {
                        dischargeReasonText.style.display = 'block';
                        dischargeReasonText.focus();
                        // dischargeReasonText.value = ''; // Clear previous text if switching to "other"
                    } else {
                        dischargeReasonText.style.display = 'none';
                        // If a predefined option is selected, we want its value to be in the 'discharge_reason' field.
                        // The 'discharge_reason_text' textarea has name 'discharge_reason'
                        // So, we need to ensure the correct value is submitted.
                        // Option 1: change name of textarea, and use JS to populate hidden field
                        // Option 2: (Simpler for now) User manually types in text area if 'other'
                        // When 'other' is not selected, the textarea is hidden, but its value might still be submitted if not cleared.
                        // Let's clear it to be safe if a predefined option is chosen.
                        // dischargeReasonText.value = ''; // Clearing text when a predefined option is chosen.
                        // This might remove user's notes if they switch back and forth.
                        // A better approach would be to handle this on the server or have two separate fields.

                        // For submission, we will rely on the `name="discharge_reason"` on the textarea.
                        // If a select option is chosen (not 'other'), we can update the textarea's value or handle server-side.
                        // For simplicity, let's assume if 'other' is not selected, the select's value is what we want for `discharge_reason`.
                        // The current setup, the textarea value is always submitted as `discharge_reason`.
                        // Let's adjust this slightly.
                        if (this.value !== '' && this.value !== 'other') {
                            dischargeReasonText.value = this.value; // Populate textarea with selected value
                        } else if (this.value !== 'other') {
                            // dischargeReasonText.value = ''; // Clear if not 'other' and no specific selection
                        }
                    }
                });

                // If there are validation errors for this specific modal, open it.
                <?php if($errors->{'dischargeFormBag' . $admission->id}->any()): ?>
                    var dischargeModal = new bootstrap.Modal(document.getElementById(
                        'dischargePatientModal<?php echo e($admission->id); ?>'));
                    dischargeModal.show();
                <?php endif; ?>
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectElement = document.getElementById('discharge_reason_select<?php echo e($admission->id); ?>');
            const textElement = document.getElementById('discharge_reason_text<?php echo e($admission->id); ?>');

            // دالة لتحديث حالة حقل النص بناءً على القائمة المنسدلة
            function updateDischargeReasonState() {
                if (selectElement.value === 'other') {
                    textElement.style.display = 'block';
                    // إذا كان المستخدم يختار "سبب آخر" لأول مرة بعد تحميل الصفحة أو بعد اختيار سبب محدد،
                    // قد ترغب في مسح القيمة الموجودة في textElement إذا لم تكن قيمة مخصصة بالفعل.
                    // لكن الكود الحالي لـ old() يعالج هذا بشكل جيد.
                    // إذا كان هناك نص قديم من `old('discharge_reason')` وهو مخصص، سيظل موجوداً.
                    // إذا كان `old('discharge_reason_select')` هو 'other' ولم يكن هناك `old('discharge_reason')`، فسيكون فارغًا.
                    // textElement.value = ''; // قم بإلغاء التعليق إذا كنت تريد مسح النص دائمًا عند اختيار "سبب آخر"
                    textElement.focus();
                } else if (selectElement.value === '') { // في حالة اختيار "-- اختر سبب الخروج --"
                    textElement.style.display = 'none';
                    textElement.value = ''; // مسح القيمة لضمان عدم إرسال قيمة قديمة إذا لم يتم الاختيار
                } else {
                    textElement.style.display = 'none';
                    textElement.value = selectElement.value; // الأهم: نسخ قيمة الخيار المحدد إلى حقل النص
                }
            }

            // استدعاء الدالة عند تغيير القائمة المنسدلة
            selectElement.addEventListener('change', updateDischargeReasonState);

            // استدعاء الدالة عند تحميل الصفحة لضمان الحالة الأولية الصحيحة
            // (خاصة إذا كان هناك `old()` values وكان JavaScript ضروريًا لضبط `textarea.value`)
            // الكود الحالي في Blade لـ `value` و `style` للـ textarea جيد ويغطي معظم الحالات الأولية.
            // لكن, للتأكيد:
            if (selectElement.value !== 'other' && selectElement.value !== '') {
                textElement.value = selectElement.value;
            }
            // إذا كانت القيمة المحفوظة في الداتابيز هي قيمة مخصصة (ليست من الخيارات)
            // و old('discharge_reason_select') ليس 'other' (يعني لم يحدث خطأ في الـ validation أعاد اختيار 'other')
            // فإن selectElement.value سيكون 'other' (بفضل كود الـ Blade)
            // و textElement.value سيكون القيمة المخصصة (بفضل كود الـ Blade)
            // وحقل النص سيكون ظاهرًا (بفضل كود الـ Blade)
            // لذلك، في هذه الحالة، لا نحتاج لفعل شيء إضافي هنا عند التحميل.
        });
    </script>
<?php $__env->stopSection(); ?>
<?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/PatientAdmissions/modals/_discharge_modal.blade.php ENDPATH**/ ?>