{{-- resources/views/Dashboard/PatientAdmissions/partials/_form.blade.php --}}

@php
    $isEditMode = isset($patientAdmission) && $patientAdmission->id;
    $isDischargeMode =
        $isEditMode &&
        (request()->get('action') === 'discharge' || (isset($prefillDischarge) && $prefillDischarge === true));
    $errorBagName = $isEditMode ? 'editAdmissionFormBag' . $patientAdmission->id : 'createAdmissionFormBag';
@endphp

<div class="row">
    <!-- Patient and Doctor Information Section -->
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="fas fa-user-plus me-2"></i>
                    {{ trans('AdminSb.patient_and_doctor_info') }}</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="patient_id" class="form-label">{{ trans('AdminSb.patient') }} <span
                            class="text-danger">*</span></label>
                    @if ($isEditMode && !$isDischargeMode)
                        <input type="text" class="form-control"
                            value="{{ $patientAdmission->patient->name ?? trans('AdminSb.unknown') }}" readonly>
                        <input type="hidden" name="patient_id" value="{{ $patientAdmission->patient_id }}">
                    @else
                        <select name="patient_id" id="patient_id"
                            class="form-select select2-patient @error('patient_id', $errorBagName) is-invalid @enderror"
                            {{ $isEditMode ? 'disabled' : 'required' }}>
                            <option value="">{{ trans('AdminSb.select_patient') }}</option>
                            @if ($isEditMode && $patientAdmission->patient)
                                <option value="{{ $patientAdmission->patient_id }}" selected>
                                    {{ $patientAdmission->patient->name }}
                                    ({{ $patientAdmission->patient->national_id }})</option>
                            @endif
                            @foreach ($patients as $patient)
                                @if (!($isEditMode && $patientAdmission->patient_id == $patient->id))
                                    <option value="{{ $patient->id }}"
                                        {{ old('patient_id', $isEditMode ? $patientAdmission->patient_id : '') == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->name }} ({{ $patient->national_id }})
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        @error('patient_id', $errorBagName)
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if ($isEditMode)
                            <small
                                class="form-text text-muted">{{ trans('AdminSb.cannot_change_patient_for_existing_admission') }}</small>
                        @endif
                    @endif
                </div>

                <div class="mb-3">
                    <label for="doctor_id" class="form-label">{{ trans('AdminSb.attending_physician') }}</label>
                    <select name="doctor_id" id="doctor_id"
                        class="form-select select2 @error('doctor_id', $errorBagName) is-invalid @enderror"
                        {{ $isDischargeMode ? 'disabled' : '' }}>
                        <option value="">{{ trans('AdminSb.select_doctor') }}</option>
                        @foreach ($doctors as $doctor)
                            <option value="{{ $doctor->id }}"
                                {{ old('doctor_id', $isEditMode ? $patientAdmission->doctor_id : '') == $doctor->id ? 'selected' : '' }}>
                                {{ $doctor->name }} {{ $doctor->section ? '(' . $doctor->section->name . ')' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('doctor_id', $errorBagName)
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @if ($isDischargeMode)
                        <input type="hidden" name="doctor_id" value="{{ $patientAdmission->doctor_id }}">
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Admission and Bed Information Section -->
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="fas fa-procedures me-2"></i>
                    {{ trans('AdminSb.admission_details') }}</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="admission_date" class="form-label">{{ trans('AdminSb.admission_datetime') }} <span
                            class="text-danger">*</span></label>
                    <input type="datetime-local" name="admission_date" id="admission_date"
                        class="form-control @error('admission_date', $errorBagName) is-invalid @enderror"
                        value="{{ old('admission_date', $isEditMode ? ($patientAdmission->admission_date ? $patientAdmission->admission_date->format('Y-m-d\TH:i') : '') : now()->format('Y-m-d\TH:i')) }}"
                        {{ $isDischargeMode ? 'readonly' : 'required' }}>
                    @error('admission_date', $errorBagName)
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="bed_id" class="form-label">{{ trans('AdminSb.bed_room_department') }}</label>
                    <select name="bed_id" id="bed_id"
                        class="form-select select2-beds @error('bed_id', $errorBagName) is-invalid @enderror"
                        {{ $isDischargeMode ? 'disabled' : '' }}>
                        <option value="">{{ trans('AdminSb.select_bed_optional') }}</option>
                        @if (
                            $isEditMode &&
                                $patientAdmission->bed_id &&
                                !collect($availableBeds)->pluck('id')->contains($patientAdmission->bed_id))
                            <option value="{{ $patientAdmission->bed_id }}" selected>
                                {{ $patientAdmission->bed->bed_number }}
                                @if ($patientAdmission->bed->room)
                                    ({{ trans('AdminSb.room_colon_short') }}
                                    {{ $patientAdmission->bed->room->room_number }}
                                    @if ($patientAdmission->bed->room->section)
                                        - {{ trans('AdminSb.department_colon_short') }}
                                        {{ $patientAdmission->bed->room->section->name }}
                                    @endif
                                    ) ({{ trans('AdminSb.current_bed_tag') }})
                                @endif
                            </option>
                        @endif
                        @foreach ($availableBeds as $bed)
                            <option value="{{ $bed->id }}" data-section-id="{{ $bed->room->section_id ?? '' }}"
                                data-section-name="{{ $bed->room->section->name ?? '' }}"
                                {{ old('bed_id', $isEditMode ? $patientAdmission->bed_id : '') == $bed->id ? 'selected' : '' }}>
                                {{ $bed->display_name ?? $bed->bed_number }}
                            </option>
                        @endforeach
                    </select>
                    @error('bed_id', $errorBagName)
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @if ($isDischargeMode)
                        <input type="hidden" name="bed_id" value="{{ $patientAdmission->bed_id }}">
                    @endif
                </div>

                <div class="mb-3">
                    <label for="section_id"
                        class="form-label">{{ trans('AdminSb.department_auto_select_note') }}</label>
                    <select name="section_id" id="section_id"
                        class="form-select select2 @error('section_id', $errorBagName) is-invalid @enderror"
                        {{ $isDischargeMode ? 'disabled' : '' }}>
                        <option value="">{{ trans('AdminSb.select_department_if_no_bed') }}</option>
                        @foreach ($sections as $section)
                            <option value="{{ $section->id }}"
                                {{ old('section_id', $isEditMode ? $patientAdmission->section_id : '') == $section->id ? 'selected' : '' }}>
                                {{ $section->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('section_id', $errorBagName)
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @if ($isDischargeMode)
                        <input type="hidden" name="section_id" value="{{ $patientAdmission->section_id }}">
                    @endif
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
                    {{ trans('AdminSb.diagnosis_and_admission_reason_details') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="reason_for_admission"
                            class="form-label">{{ trans('AdminSb.reason_for_admission') }}</label>
                        <textarea name="reason_for_admission" id="reason_for_admission" rows="3"
                            class="form-control @error('reason_for_admission', $errorBagName) is-invalid @enderror"
                            {{ $isDischargeMode ? 'readonly' : '' }}>{{ old('reason_for_admission', $isEditMode ? $patientAdmission->reason_for_admission : '') }}</textarea>
                        @error('reason_for_admission', $errorBagName)
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="admitting_diagnosis"
                            class="form-label">{{ trans('AdminSb.initial_diagnosis_on_admission') }}</label>
                        <textarea name="admitting_diagnosis" id="admitting_diagnosis" rows="3"
                            class="form-control @error('admitting_diagnosis', $errorBagName) is-invalid @enderror"
                            {{ $isDischargeMode ? 'readonly' : '' }}>{{ old('admitting_diagnosis', $isEditMode ? $patientAdmission->admitting_diagnosis : '') }}</textarea>
                        @error('admitting_diagnosis', $errorBagName)
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if ($isEditMode)
    <hr>
    <div class="row">
        <div class="col-12">
            <h4><i class="fas fa-sign-out-alt me-2"></i> {{ trans('AdminSb.discharge_information') }}</h4>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-md-4 mb-3">
            <label for="discharge_date" class="form-label">{{ trans('AdminSb.discharge_datetime') }}</label>
            <input type="datetime-local" name="discharge_date" id="discharge_date"
                class="form-control @error('discharge_date', $errorBagName) is-invalid @enderror"
                value="{{ old('discharge_date', $patientAdmission->discharge_date ? $patientAdmission->discharge_date->format('Y-m-d\TH:i') : ($isDischargeMode ? now()->format('Y-m-d\TH:i') : '')) }}"
                {{ $isDischargeMode ? 'required' : '' }}>
            @error('discharge_date', $errorBagName)
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4 mb-3">
            <label for="discharge_reason_select" class="form-label">{{ trans('AdminSb.discharge_reason') }}</label>
            <select class="form-select @error('discharge_reason', $errorBagName) is-invalid @enderror"
                id="discharge_reason_select_form" name="discharge_reason_select">
                <option value="">{{ trans('AdminSb.select_discharge_reason') }}</option>
                <option value="{{ trans('AdminSb.reason_improved_condition') }}"
                    {{ old('discharge_reason', $patientAdmission->discharge_reason) == trans('AdminSb.reason_improved_condition') ? 'selected' : '' }}>
                    {{ trans('AdminSb.reason_improved_condition') }}</option>
                <option value="{{ trans('AdminSb.reason_patient_request') }}"
                    {{ old('discharge_reason', $patientAdmission->discharge_reason) == trans('AdminSb.reason_patient_request') ? 'selected' : '' }}>
                    {{ trans('AdminSb.reason_patient_request') }}</option>
                <option value="{{ trans('AdminSb.reason_transfer_to_another_hospital') }}"
                    {{ old('discharge_reason', $patientAdmission->discharge_reason) == trans('AdminSb.reason_transfer_to_another_hospital') ? 'selected' : '' }}>
                    {{ trans('AdminSb.reason_transfer_to_another_hospital') }}</option>
                <option value="{{ trans('AdminSb.reason_death') }}"
                    {{ old('discharge_reason', $patientAdmission->discharge_reason) == trans('AdminSb.reason_death') ? 'selected' : '' }}>
                    {{ trans('AdminSb.reason_death') }}</option>
                <option value="other">{{ trans('AdminSb.reason_other') }}</option>
            </select>
            <textarea name="discharge_reason" id="discharge_reason_text_form" rows="2"
                class="form-control mt-1 @error('discharge_reason', $errorBagName) is-invalid @enderror"
                placeholder="{{ trans('AdminSb.other_reason_placeholder') }}" style="display: none;">{{ old('discharge_reason', $patientAdmission->discharge_reason) }}</textarea>
            @error('discharge_reason', $errorBagName)
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4 mb-3">
            <label for="discharge_diagnosis" class="form-label">{{ trans('AdminSb.diagnosis_on_discharge') }}</label>
            <textarea name="discharge_diagnosis" id="discharge_diagnosis_form" rows="3"
                class="form-control @error('discharge_diagnosis', $errorBagName) is-invalid @enderror">{{ old('discharge_diagnosis', $patientAdmission->discharge_diagnosis) }}</textarea>
            @error('discharge_diagnosis', $errorBagName)
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
@endif

<div class="row">
    <div class="col-12">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="fas fa-sticky-note me-2"></i>
                    {{ trans('AdminSb.status_and_notes') }}</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="status" class="form-label">{{ trans('AdminSb.admission_record_status') }} <span
                            class="text-danger">*</span></label>
                    <select name="status" id="status"
                        class="form-select @error('status', $errorBagName) is-invalid @enderror" required
                        {{ $isEditMode && !$isDischargeMode && $patientAdmission->status === \App\Models\PatientAdmission::STATUS_DISCHARGED ? 'disabled' : '' }}>
                        @if ($isDischargeMode)
                            <option value="{{ \App\Models\PatientAdmission::STATUS_DISCHARGED }}" selected>
                                {{ trans('AdminSb.discharged_from_hospital') }}</option>
                        @else
                            @foreach ($admissionStatuses as $statusKey => $statusValue)
                                @if ($isEditMode)
                                    <option value="{{ $statusKey }}"
                                        {{ old('status', $patientAdmission->status) == $statusKey ? 'selected' : '' }}>
                                        {{ $statusValue }}</option>
                                @else
                                    @if (in_array($statusKey, [\App\Models\PatientAdmission::STATUS_ADMITTED]))
                                        <option value="{{ $statusKey }}"
                                            {{ old('status', \App\Models\PatientAdmission::STATUS_ADMITTED) == $statusKey ? 'selected' : '' }}>
                                            {{ $statusValue }}</option>
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    </select>
                    @error('status', $errorBagName)
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @if ($isEditMode && !$isDischargeMode && $patientAdmission->status === \App\Models\PatientAdmission::STATUS_DISCHARGED)
                        <small
                            class="form-text text-muted">{{ trans('AdminSb.cannot_change_status_of_discharged_record') }}</small>
                        <input type="hidden" name="status"
                            value="{{ \App\Models\PatientAdmission::STATUS_DISCHARGED }}">
                    @endif
                    @if ($isDischargeMode)
                        <input type="hidden" name="status"
                            value="{{ \App\Models\PatientAdmission::STATUS_DISCHARGED }}">
                    @endif
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">{{ trans('AdminSb.additional_notes') }}</label>
                    <textarea name="notes" id="notes" rows="3"
                        class="form-control @error('notes', $errorBagName) is-invalid @enderror">{{ old('notes', $isEditMode ? $patientAdmission->notes : '') }}</textarea>
                    @error('notes', $errorBagName)
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</div>```


@push('js_after_form')
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
@endpush
