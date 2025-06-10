{{-- resources/views/Dashboard/PatientAdmissions/partials/_form.blade.php --}}

@php
    // تحديد ما إذا كنا في وضع التعديل أو الإنشاء
    $isEditMode = isset($patientAdmission) && $patientAdmission->id;
    // تحديد ما إذا كنا في وضع تسجيل الخروج (بناءً على بارامتر في الـ URL أو حالة معينة)
    // $isDischargeMode = $isEditMode && request()->get('action') === 'discharge';
    $isDischargeMode = $isEditMode &&
                       (request()->get('action') === 'discharge' ||
                        (isset($prefillDischarge) && $prefillDischarge === true));

    // معرف فريد للـ error bag (مهم إذا كان هذا الفورم سيستخدم في مودال أو أكثر من مرة في الصفحة)
    $errorBagName = $isEditMode ? 'editAdmissionFormBag'.$patientAdmission->id : 'createAdmissionFormBag';
@endphp

<div class="row">
    <!-- قسم معلومات المريض والطبيب -->
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="fas fa-user-plus me-2"></i> معلومات المريض والطبيب</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="patient_id" class="form-label">المريض <span class="text-danger">*</span></label>
                    @if($isEditMode && !$isDischargeMode)
                        <input type="text" class="form-control" value="{{ $patientAdmission->patient->name ?? 'غير معروف' }}" readonly>
                        <input type="hidden" name="patient_id" value="{{ $patientAdmission->patient_id }}">
                    @else
                        <select name="patient_id" id="patient_id" class="form-select select2-patient @error('patient_id', $errorBagName) is-invalid @enderror" {{ $isEditMode ? 'disabled' : 'required' }}>
                            <option value="">-- اختر المريض --</option>
                            @if($isEditMode && $patientAdmission->patient) {{-- لعرض المريض الحالي في وضع التعديل --}}
                                <option value="{{ $patientAdmission->patient_id }}" selected>{{ $patientAdmission->patient->name }} ({{$patientAdmission->patient->national_id}})</option>
                            @endif
                            @foreach($patients as $patient)
                                @if(!($isEditMode && $patientAdmission->patient_id == $patient->id)) {{-- تجنب تكرار المريض الحالي --}}
                                    <option value="{{ $patient->id }}" {{ old('patient_id', $isEditMode ? $patientAdmission->patient_id : '') == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->name }} ({{$patient->national_id}})
                                    </option>
                                @endif
                            @endforeach
                        </select>
                        @error('patient_id', $errorBagName)
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if($isEditMode)
                             <small class="form-text text-muted">لا يمكن تغيير المريض لسجل دخول قائم.</small>
                        @endif
                    @endif
                </div>

                <div class="mb-3">
                    <label for="doctor_id" class="form-label">الطبيب المعالج</label>
                    <select name="doctor_id" id="doctor_id" class="form-select select2 @error('doctor_id', $errorBagName) is-invalid @enderror" {{ $isDischargeMode ? 'disabled' : '' }}>
                        <option value="">-- اختر الطبيب --</option>
                        @foreach($doctors as $doctor)
                            <option value="{{ $doctor->id }}" {{ old('doctor_id', $isEditMode ? $patientAdmission->doctor_id : '') == $doctor->id ? 'selected' : '' }}>
                                {{ $doctor->name }} {{ $doctor->section ? '('.$doctor->section->name.')' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('doctor_id', $errorBagName)
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                     @if($isDischargeMode)
                        <input type="hidden" name="doctor_id" value="{{ $patientAdmission->doctor_id }}">
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- قسم معلومات الدخول والسرير -->
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0"><i class="fas fa-procedures me-2"></i> تفاصيل الإقامة</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="admission_date" class="form-label">تاريخ ووقت الدخول <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="admission_date" id="admission_date"
                           class="form-control @error('admission_date', $errorBagName) is-invalid @enderror"
                           value="{{ old('admission_date', $isEditMode ? ($patientAdmission->admission_date ? $patientAdmission->admission_date->format('Y-m-d\TH:i') : '') : now()->format('Y-m-d\TH:i')) }}"
                           {{ $isDischargeMode ? 'readonly' : 'required' }}>
                    @error('admission_date', $errorBagName)
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="bed_id" class="form-label">السرير (الغرفة - القسم)</label>
                    <select name="bed_id" id="bed_id" class="form-select select2-beds @error('bed_id', $errorBagName) is-invalid @enderror" {{ $isDischargeMode ? 'disabled' : '' }}>
                        <option value="">-- اختر السرير (اختياري عند الدخول الأولي) --</option>
                        @if($isEditMode && $patientAdmission->bed_id && !collect($availableBeds)->pluck('id')->contains($patientAdmission->bed_id))
                            {{-- إضافة السرير الحالي للمريض إذا لم يكن ضمن قائمة الأسرة المتاحة (مثلاً أصبح غير متاح لسبب آخر) --}}
                            <option value="{{ $patientAdmission->bed_id }}" selected>
                                {{ $patientAdmission->bed->bed_number }}
                                @if($patientAdmission->bed->room)
                                    (غرفة: {{ $patientAdmission->bed->room->room_number }}
                                    @if($patientAdmission->bed->room->section)
                                        - قسم: {{ $patientAdmission->bed->room->section->name }}
                                    @endif
                                    ) (السرير الحالي)
                                @endif
                            </option>
                        @endif
                        @foreach($availableBeds as $bed)
                            <option value="{{ $bed->id }}" data-section-id="{{ $bed->room->section_id ?? '' }}" data-section-name="{{ $bed->room->section->name ?? '' }}"
                                {{ old('bed_id', $isEditMode ? $patientAdmission->bed_id : '') == $bed->id ? 'selected' : '' }}>
                                {{ $bed->display_name ?? $bed->bed_number }}
                            </option>
                        @endforeach
                    </select>
                    @error('bed_id', $errorBagName)
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @if($isDischargeMode)
                        <input type="hidden" name="bed_id" value="{{ $patientAdmission->bed_id }}">
                    @endif
                </div>

                <div class="mb-3">
                    <label for="section_id" class="form-label">القسم (سيتم تحديده تلقائياً عند اختيار سرير)</label>
                    <select name="section_id" id="section_id" class="form-select select2 @error('section_id', $errorBagName) is-invalid @enderror" {{ $isDischargeMode ? 'disabled' : '' }}>
                        <option value="">-- اختر القسم (إذا لم يتم اختيار سرير) --</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" {{ old('section_id', $isEditMode ? $patientAdmission->section_id : '') == $section->id ? 'selected' : '' }}>
                                {{ $section->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('section_id', $errorBagName)
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                     @if($isDischargeMode)
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
                <h5 class="card-title mb-0"><i class="fas fa-file-medical-alt me-2"></i> تفاصيل التشخيص وسبب الدخول</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="reason_for_admission" class="form-label">سبب الدخول</label>
                        <textarea name="reason_for_admission" id="reason_for_admission" rows="3"
                                  class="form-control @error('reason_for_admission', $errorBagName) is-invalid @enderror"
                                  {{ $isDischargeMode ? 'readonly' : '' }}>{{ old('reason_for_admission', $isEditMode ? $patientAdmission->reason_for_admission : '') }}</textarea>
                        @error('reason_for_admission', $errorBagName)
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="admitting_diagnosis" class="form-label">التشخيص المبدئي عند الدخول</label>
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


@if($isEditMode) {{-- حقول تظهر فقط في وضع التعديل --}}
    <hr>
    <div class="row">
        <div class="col-12">
             <h4><i class="fas fa-sign-out-alt me-2"></i> معلومات الخروج (إذا تم الخروج)</h4>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-md-4 mb-3">
            <label for="discharge_date" class="form-label">تاريخ ووقت الخروج</label>
            <input type="datetime-local" name="discharge_date" id="discharge_date"
                   class="form-control @error('discharge_date', $errorBagName) is-invalid @enderror"
                   value="{{ old('discharge_date', $patientAdmission->discharge_date ? $patientAdmission->discharge_date->format('Y-m-d\TH:i') : ($isDischargeMode ? now()->format('Y-m-d\TH:i') : '')) }}"
                   {{ $isDischargeMode ? 'required' : '' }}>
            @error('discharge_date', $errorBagName)
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4 mb-3">
            <label for="discharge_reason_select" class="form-label">سبب الخروج</label>
             <select class="form-select @error('discharge_reason', $errorBagName) is-invalid @enderror"
                    id="discharge_reason_select_form" name="discharge_reason_select">
                <option value="">-- اختر سبب الخروج --</option>
                @php
                    $commonDischargeReasons = ['تحسن الحالة', 'بناءً على طلب المريض', 'نقل إلى مستشفى آخر', 'وفاة'];
                    $currentDischargeReason = old('discharge_reason', $patientAdmission->discharge_reason);
                @endphp
                @foreach($commonDischargeReasons as $reason)
                    <option value="{{ $reason }}" {{ $currentDischargeReason == $reason ? 'selected' : '' }}>{{ $reason }}</option>
                @endforeach
                <option value="other" {{ !in_array($currentDischargeReason, $commonDischargeReasons) && !empty($currentDischargeReason) ? 'selected' : (old('discharge_reason_select') == 'other' ? 'selected' : '') }}>سبب آخر (يرجى التحديد)</option>
            </select>
            <textarea name="discharge_reason" id="discharge_reason_text_form" rows="2"
                      class="form-control mt-1 @error('discharge_reason', $errorBagName) is-invalid @enderror"
                      placeholder="إذا اخترت 'سبب آخر'، يرجى كتابة السبب هنا"
                      style="display: {{ !in_array($currentDischargeReason, $commonDischargeReasons) && !empty($currentDischargeReason) || old('discharge_reason_select') == 'other' ? 'block' : 'none' }};"
                      >{{ $currentDischargeReason }}</textarea>
            @error('discharge_reason', $errorBagName)
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4 mb-3">
            <label for="discharge_diagnosis" class="form-label">التشخيص عند الخروج</label>
            <textarea name="discharge_diagnosis" id="discharge_diagnosis_form" rows="3"
                      class="form-control @error('discharge_diagnosis', $errorBagName) is-invalid @enderror"
                      >{{ old('discharge_diagnosis', $patientAdmission->discharge_diagnosis) }}</textarea>
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
                 <h5 class="card-title mb-0"><i class="fas fa-sticky-note me-2"></i> الحالة والملاحظات</h5>
            </div>
            <div class="card-body">
                 <div class="mb-3">
                    <label for="status" class="form-label">حالة سجل الدخول <span class="text-danger">*</span></label>
                    <select name="status" id="status" class="form-select @error('status', $errorBagName) is-invalid @enderror" required {{ ($isEditMode && !$isDischargeMode && $patientAdmission->status === \App\Models\PatientAdmission::STATUS_DISCHARGED) ? 'disabled' : '' }}>
                        @if($isDischargeMode)
                             <option value="{{ \App\Models\PatientAdmission::STATUS_DISCHARGED }}" selected>خرج من المستشفى</option>
                        @else
                            @foreach($admissionStatuses as $statusKey => $statusValue)
                                @if($isEditMode)
                                    <option value="{{ $statusKey }}" {{ old('status', $patientAdmission->status) == $statusKey ? 'selected' : '' }}>{{ $statusValue }}</option>
                                @else {{-- وضع الإنشاء --}}
                                    @if(in_array($statusKey, [\App\Models\PatientAdmission::STATUS_ADMITTED])) {{--  , \App\Models\PatientAdmission::STATUS_RESERVED السماح فقط بتسجيل دخول أو حجز --}}
                                        <option value="{{ $statusKey }}" {{ old('status', \App\Models\PatientAdmission::STATUS_ADMITTED) == $statusKey ? 'selected' : '' }}>{{ $statusValue }}</option>
                                    @endif
                                @endif
                            @endforeach
                        @endif
                    </select>
                    @error('status', $errorBagName)
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @if($isEditMode && !$isDischargeMode && $patientAdmission->status === \App\Models\PatientAdmission::STATUS_DISCHARGED)
                         <small class="form-text text-muted">لا يمكن تغيير حالة سجل تم تسجيل خروجه من هنا.</small>
                         <input type="hidden" name="status" value="{{ \App\Models\PatientAdmission::STATUS_DISCHARGED }}">
                    @endif
                     @if($isDischargeMode)
                        <input type="hidden" name="status" value="{{ \App\Models\PatientAdmission::STATUS_DISCHARGED }}">
                    @endif
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">ملاحظات إضافية</label>
                    <textarea name="notes" id="notes" rows="3"
                              class="form-control @error('notes', $errorBagName) is-invalid @enderror"
                              >{{ old('notes', $isEditMode ? $patientAdmission->notes : '') }}</textarea>
                    @error('notes', $errorBagName)
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</div>

@push('js_after_form')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // تحديث القسم عند اختيار سرير
    const bedSelect = document.getElementById('bed_id');
    const sectionSelect = document.getElementById('section_id');
    if (bedSelect && sectionSelect) {
        bedSelect.addEventListener('change', function () {
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
                data: function (params) {
                    return {
                        q: params.term, // search term
                        page: params.page
                    };
                },
                processResults: function (data, params) {
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
