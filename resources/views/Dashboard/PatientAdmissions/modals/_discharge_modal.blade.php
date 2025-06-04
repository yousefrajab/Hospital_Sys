{{-- resources/views/Dashboard/PatientAdmissions/modals/_discharge_modal.blade.php --}}

<div class="modal fade" id="dischargePatientModal{{ $admission->id }}" tabindex="-1"
    aria-labelledby="dischargePatientModalLabel{{ $admission->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dischargePatientModalLabel{{ $admission->id }}">
                    تسجيل خروج المريض: {{ $admission->patient->name ?? 'غير معروف' }}
                    (سجل دخول رقم: #{{ $admission->id }})
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.patient_admissions.update', $admission->id) }}" method="POST"
                id="dischargeForm{{ $admission->id }}">
                @csrf
                @method('PUT') {{-- أو PATCH --}}

                <div class="modal-body">
                    <p class="alert alert-info">
                        سيتم تحديث حالة هذا السجل إلى "خرج من المستشفى" وتحديث حالة السرير ليصبح "متاح".
                    </p>

                    <input type="hidden" name="status" value="{{ \App\Models\PatientAdmission::STATUS_DISCHARGED }}">
                    {{-- الحقول المخفية الأخرى التي لا نريد أن يغيرها المستخدم من هذا المودال --}}
                    <input type="hidden" name="patient_id" value="{{ $admission->patient_id }}">
                    <input type="hidden" name="admission_date"
                        value="{{ $admission->admission_date->format('Y-m-d\TH:i') }}">
                    {{-- إذا كان لديك حقول أخرى تريد تمريرها بدون تغيير من هذا الفورم --}}
                    {{-- <input type="hidden" name="doctor_id" value="{{ $admission->doctor_id }}"> --}}
                    {{-- <input type="hidden" name="bed_id" value="{{ $admission->bed_id }}"> --}}
                    {{-- <input type="hidden" name="section_id" value="{{ $admission->section_id }}"> --}}
                    {{-- <input type="hidden" name="reason_for_admission" value="{{ $admission->reason_for_admission }}"> --}}
                    {{-- <input type="hidden" name="admitting_diagnosis" value="{{ $admission->admitting_diagnosis }}"> --}}


                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="discharge_date{{ $admission->id }}" class="form-label">تاريخ ووقت الخروج <span
                                    class="text-danger">*</span></label>
                            <input type="datetime-local"
                                class="form-control @error('discharge_date', 'dischargeFormBag' . $admission->id) is-invalid @enderror"
                                id="discharge_date{{ $admission->id }}" name="discharge_date"
                                value="{{ old('discharge_date', now()->format('Y-m-d\TH:i')) }}" required>
                            @error('discharge_date', 'dischargeFormBag' . $admission->id)
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="discharge_reason_select{{ $admission->id }}" class="form-label">سبب
                                الخروج</label>
                            <select
                                class="form-select @error('discharge_reason', 'dischargeFormBag' . $admission->id) is-invalid @enderror"
                                id="discharge_reason_select{{ $admission->id }}" name="discharge_reason_select">
                                {{-- اسم مؤقت للـ select، لن نعتمد عليه مباشرة في الحفظ --}}
                                <option value="">-- اختر سبب الخروج --</option>
                                <option value="تحسن الحالة"
                                    {{ old('discharge_reason_select', $admission->discharge_reason) == 'تحسن الحالة' && !(old('discharge_reason_select') == 'other' || (!old('discharge_reason_select') && !in_array($admission->discharge_reason, ['تحسن الحالة', 'بناءً على طلب المريض', 'نقل إلى مستشفى آخر', 'وفاة']) && !empty($admission->discharge_reason))) ? 'selected' : '' }}>
                                    تحسن الحالة
                                </option>
                                <option value="بناءً على طلب المريض"
                                    {{ old('discharge_reason_select', $admission->discharge_reason) == 'بناءً على طلب المريض' && !(old('discharge_reason_select') == 'other' || (!old('discharge_reason_select') && !in_array($admission->discharge_reason, ['تحسن الحالة', 'بناءً على طلب المريض', 'نقل إلى مستشفى آخر', 'وفاة']) && !empty($admission->discharge_reason))) ? 'selected' : '' }}>
                                    بناءً على طلب المريض
                                </option>
                                <option value="نقل إلى مستشفى آخر"
                                    {{ old('discharge_reason_select', $admission->discharge_reason) == 'نقل إلى مستشفى آخر' && !(old('discharge_reason_select') == 'other' || (!old('discharge_reason_select') && !in_array($admission->discharge_reason, ['تحسن الحالة', 'بناءً على طلب المريض', 'نقل إلى مستشفى آخر', 'وفاة']) && !empty($admission->discharge_reason))) ? 'selected' : '' }}>
                                    نقل إلى مستشفى آخر
                                </option>
                                <option value="وفاة"
                                    {{ old('discharge_reason_select', $admission->discharge_reason) == 'وفاة' && !(old('discharge_reason_select') == 'other' || (!old('discharge_reason_select') && !in_array($admission->discharge_reason, ['تحسن الحالة', 'بناءً على طلب المريض', 'نقل إلى مستشفى آخر', 'وفاة']) && !empty($admission->discharge_reason))) ? 'selected' : '' }}>
                                    وفاة
                                </option>
                                <option value="other"
                                    {{ old('discharge_reason_select') == 'other' || (!old('discharge_reason_select') && !in_array($admission->discharge_reason, ['تحسن الحالة', 'بناءً على طلب المريض', 'نقل إلى مستشفى آخر', 'وفاة']) && !empty($admission->discharge_reason)) ? 'selected' : '' }}>
                                    سبب آخر (يرجى التحديد)
                                </option>
                            </select>

                            <textarea class="form-control mt-2 @error('discharge_reason', 'dischargeFormBag' . $admission->id) is-invalid @enderror"
                                id="discharge_reason_text{{ $admission->id }}" name="discharge_reason" {{-- هذا الحقل هو الذي سيُحفظ في الداتا بيز --}} rows="2"
                                placeholder="إذا اخترت 'سبب آخر'، يرجى كتابة السبب هنا، أو أي تفاصيل إضافية"
                                style="display: {{ old('discharge_reason_select') == 'other' || (!old('discharge_reason_select') && !in_array($admission->discharge_reason, ['تحسن الحالة', 'بناءً على طلب المريض', 'نقل إلى مستشفى آخر', 'وفاة']) && !empty($admission->discharge_reason)) ? 'block' : 'none' }};">{{ old('discharge_reason', $admission->discharge_reason) }}</textarea>

                            @error('discharge_reason', 'dischargeFormBag' . $admission->id)
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="discharge_diagnosis{{ $admission->id }}" class="form-label">التشخيص عند
                            الخروج</label>
                        <textarea class="form-control @error('discharge_diagnosis', 'dischargeFormBag' . $admission->id) is-invalid @enderror"
                            id="discharge_diagnosis{{ $admission->id }}" name="discharge_diagnosis" rows="3"
                            placeholder="التشخيص النهائي أو الحالة عند الخروج">{{ old('discharge_diagnosis', $admission->discharge_diagnosis) }}</textarea>
                        @error('discharge_diagnosis', 'dischargeFormBag' . $admission->id)
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="notes{{ $admission->id }}" class="form-label">ملاحظات إضافية على عملية
                            الخروج</label>
                        <textarea class="form-control @error('notes', 'dischargeFormBag' . $admission->id) is-invalid @enderror"
                            id="notes{{ $admission->id }}" name="notes" rows="3" placeholder="أي ملاحظات أخرى متعلقة بعملية الخروج">{{ old('notes', $admission->notes) }}</textarea>
                        @error('notes', 'dischargeFormBag' . $admission->id)
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check-circle me-1"></i> تأكيد خروج المريض
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('js')
    {{-- إذا كان لديك أي سكربتات إضافية تحتاجها في هذا المودال --}}
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap-select.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dischargeReasonSelect = document.getElementById('discharge_reason_select{{ $admission->id }}');
            const dischargeReasonText = document.getElementById('discharge_reason_text{{ $admission->id }}');

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
                @if ($errors->{'dischargeFormBag' . $admission->id}->any())
                    var dischargeModal = new bootstrap.Modal(document.getElementById(
                        'dischargePatientModal{{ $admission->id }}'));
                    dischargeModal.show();
                @endif
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectElement = document.getElementById('discharge_reason_select{{ $admission->id }}');
            const textElement = document.getElementById('discharge_reason_text{{ $admission->id }}');

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
@endsection
