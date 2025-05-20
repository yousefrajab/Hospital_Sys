@extends('Dashboard.layouts.master') {{-- أو التخطيط الخاص بالطبيب --}}

@section('title')
    تعديل الوصفة الطبية رقم: {{ $prescription->prescription_number }} (للمريض: {{ $patient->name ?? 'غير محدد' }})
@endsection

@section('css')
    @parent
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link href="{{ URL::asset('dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    {{-- استخدام نفس أنماط CSS من صفحة إنشاء الوصفة --}}
    <style>
        :root {
            --bs-primary-rgb: 78, 115, 223;
            --bs-primary: rgb(var(--bs-primary-rgb));
            --bs-success-rgb: 28, 200, 138;
            --bs-success: rgb(var(--bs-success-rgb));
            --bs-danger-rgb: 231, 74, 59;
            --bs-danger: rgb(var(--bs-danger-rgb));
            --bs-info-rgb: 54, 185, 204;
            --bs-warning-rgb: 246, 194, 62;
            --bs-light-rgb: 248, 249, 252;
            --bs-body-color: #5a5c69;
            --bs-body-bg: #f0f2f5;
            --bs-border-color: #e3e6f0;
            --bs-card-cap-bg: #f8f9fc;
            --bs-card-border-radius: 0.6rem;
            --bs-card-box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            --admin-transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        body { font-family: 'Tajawal', sans-serif; background-color: var(--bs-body-bg); color: var(--bs-body-color); }
        .prescription-form-container { background-color: #fff; padding: 2rem; border-radius: var(--bs-card-border-radius); box-shadow: var(--bs-card-box-shadow); border: 1px solid var(--bs-border-color); max-width: 1100px; margin: 2rem auto; }
        .form-section-title { font-size: 1.3rem; font-weight: 600; color: var(--bs-primary); margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 2px solid rgba(var(--bs-primary-rgb), 0.2); display: flex; align-items: center; }
        .form-section-title i { margin-right: 0.75rem; }
        .patient-info-display { background-color: rgba(var(--bs-primary-rgb), 0.05); border-left: 4px solid var(--bs-primary); padding: 1rem 1.25rem; border-radius: var(--bs-card-border-radius); margin-bottom: 2rem; }
        .patient-info-display h5 { color: var(--bs-primary); margin-bottom: 0.5rem; }
        .patient-info-display p { margin-bottom: 0.3rem; font-size: 0.95rem; }
        .patient-info-display strong { color: #333; }
        .patient-info-display .allergies-list span.badge { font-size: 0.8rem; margin-left: 5px; background-color: rgba(var(--bs-danger-rgb), 0.1); color: rgb(var(--bs-danger-rgb)); }
        .patient-info-display .chronic-list span.badge { font-size: 0.8rem; margin-left: 5px; background-color: rgba(var(--bs-warning-rgb), 0.15); color: rgb(var(--bs-warning-rgb));}

        .medication-item-row { background-color: #fdfdff; padding: 1.25rem; border-radius: var(--bs-card-border-radius); margin-bottom: 1.25rem; border: 1px solid #f0f3f7; box-shadow: 0 3px 7px rgba(0,0,0,0.04); position: relative; transition: var(--admin-transition); }
        .medication-item-row:hover { border-color: rgba(var(--bs-primary-rgb), 0.3); box-shadow: 0 4px 10px rgba(0,0,0,0.06); }
        .medication-item-row .form-label { font-weight: 500; font-size: 0.875rem; margin-bottom: 0.4rem; color: #5a5c69; }
        .medication-item-row .form-label.required::after { content: '*'; color: rgb(var(--bs-danger-rgb)); margin-right: 3px;}
        .medication-item-row .form-control, .medication-item-row .form-select, .medication-item-row .select2-container--bootstrap-5 .select2-selection--single { font-size: 0.9rem; padding: 0.6rem 0.9rem; border-radius: 0.3rem; border: 1px solid #ced4da; background-color: #fff; }
        .medication-item-row .select2-container--default .select2-selection--single { height: calc(1.5em + (0.6rem*2) + 2px); }
        .medication-item-row .select2-container--default .select2-selection--single .select2-selection__arrow{ height: calc(1.5em + (0.6rem*2) - 2px); }

        .remove-medication-item-btn { position: absolute; top: 0.75rem; left: 0.75rem; background-color: transparent; color: rgb(var(--bs-danger-rgb)); border: 1px solid rgba(var(--bs-danger-rgb), 0.3); width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; transition: all 0.2s ease; opacity: 0.7; }
        .remove-medication-item-btn:hover { background-color: rgb(var(--bs-danger-rgb)); color: white; opacity: 1; transform: scale(1.1); }
        #add_medication_item_btn { background-color: rgb(var(--bs-success-rgb)); border-color: rgb(var(--bs-success-rgb)); color: white; font-weight: 500; padding: 0.6rem 1.2rem; font-size: 0.95rem; border-radius: 0.35rem; transition: all 0.25s ease; display: inline-flex; align-items: center; box-shadow: 0 2px 5px rgba(var(--bs-success-rgb), 0.2); }
        #add_medication_item_btn:hover { background-color: #17a77a; transform: translateY(-1px); box-shadow: 0 4px 8px rgba(var(--bs-success-rgb), 0.3); }
        #add_medication_item_btn i { margin-left: 0.5rem; }

        .form-actions-footer { padding-top: 1.5rem; margin-top: 2rem; border-top: 1px solid var(--bs-border-color); text-align: left; }
        .btn-submit-prescription { font-weight: 600; padding: 0.75rem 2rem; font-size: 1rem; background-color: var(--bs-primary); border-color: var(--bs-primary); }
        .btn-submit-prescription:hover { background-color: #2e59d9; border-color: #2e59d9; }
        .btn-submit-prescription .spinner-icon { display: none; }
        .btn-submit-prescription.loading .spinner-icon { display: inline-block; animation: spin 0.75s linear infinite; }
        .btn-submit-prescription.loading .btn-text { display: none; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

        .select2-results__option--medication .medication-name { font-weight: 600; display: block; color: var(--bs-primary); }
        .select2-results__option--medication .medication-details { font-size: 0.8rem; color: #6c757d; display: block; }

        .form-control.is-invalid, .form-select.is-invalid, .was-validated .form-control:invalid, .was-validated .form-select:invalid { border-color: rgb(var(--bs-danger-rgb)) !important; }
        .select2-container--bootstrap-5 .select2-selection.is-invalid { border-color: rgb(var(--bs-danger-rgb)) !important; box-shadow: 0 0 0 0.2rem rgba(var(--bs-danger-rgb),0.15) !important; }
        .invalid-feedback { color: rgb(var(--bs-danger-rgb)); font-size: 0.875rem; }
        .form-check-input.is-invalid~.form-check-label { color: rgb(var(--bs-danger-rgb));}
        .form-check-input.is-invalid~.invalid-feedback { display: block !important;}
        .input-group-custom-select .select2-container--bootstrap-5 .select2-selection--single { border-top-right-radius: 0 !important; border-bottom-right-radius: 0 !important; }
        .input-group-custom-select .input-group-text { border-top-left-radius: var(--bs-card-border-radius) !important; border-bottom-left-radius: var(--bs-card-border-radius) !important; border-right: 0; }
    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto"><i class="fas fa-file-medical-alt text-primary me-2"></i>الوصفات الطبية</h4>
                <span class="text-muted mt-1 tx-13 mx-2">/</span>
                <span class="text-muted mt-1 tx-13">تعديل الوصفة رقم: {{ $prescription->prescription_number }}</span>
            </div>
        </div>
         <div class="d-flex my-xl-auto right-content">
            <a href="{{ route('doctor.prescriptions.show', $prescription->id) }}" class="btn btn-outline-info btn-sm ripple-effect me-2">
                 <i class="fas fa-eye me-1"></i> عرض الوصفة
            </a>
            <a href="{{ route('doctor.prescriptions.index') }}" class="btn btn-outline-secondary btn-sm ripple-effect">
                 <i class="fas fa-list-ul me-1"></i> كل الوصفات
            </a>
        </div>
    </div>
@endsection

@section('content')
    @include('Dashboard.messages_alert')

    <div class="prescription-form-container" data-aos="fade-up">
        @if($patient)
            <div class="patient-info-display">
                {{-- ... (نفس كود عرض معلومات المريض من صفحة الإنشاء) ... --}}
                <div class="row align-items-center">
                    <div class="col-auto">
                        <img src="{{ $patient->image ? asset('Dashboard/img/patients/' . $patient->image->filename) : URL::asset('Dashboard/img/default_patient_avatar.png') }}"
                             alt="{{ $patient->name }}" class="rounded-circle me-3" style="width: 60px; height: 60px; object-fit: cover; border: 2px solid var(--bs-primary);">
                    </div>
                    <div class="col">
                        <h5 class="mb-1">تعديل وصفة للمريض: <strong class="text-dark">{{ $patient->name }}</strong></h5>
                        <p class="mb-0"><strong>الهوية:</strong> {{ $patient->national_id }} | <strong>العمر:</strong> {{ $patient->Date_Birth ? $patient->Date_Birth->age : '-' }} سنة</p>
                        @if($patient->diagnosedChronicDiseases && $patient->diagnosedChronicDiseases->isNotEmpty())
                            <div class="mt-2 allergies-section">
                                <strong class="text-danger"><i class="fas fa-biohazard me-1"></i>أمراض مزمنة:</strong>
                                @foreach($patient->diagnosedChronicDiseases->take(3) as $cd)
                                    <span class="badge rounded-pill bg-danger-light text-danger-emphasis">{{ $cd->disease->name ?? $cd->name }}</span>
                                @endforeach
                                @if($patient->diagnosedChronicDiseases->count() > 3)
                                <a href="{{ route('admin.Patients.show', $patient->id) }}#chronic-diseases-section" target="_blank" class="small text-primary">(المزيد)</a>
                                @endif
                            </div>
                        @endif
                        @if($patient->initial_allergies_text)
                             <div class="mt-1 allergies-section">
                                <strong class="text-warning"><i class="fas fa-allergies me-1"></i>حساسيات:</strong>
                                <span class="badge rounded-pill bg-warning-light text-warning-emphasis border">{{ Str::limit($patient->initial_allergies_text, 50) }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        @if($patient && $prescription)
            <form action="{{ route('doctor.prescriptions.update', $prescription->id) }}" method="POST" id="prescriptionEditForm" novalidate class="needs-validation">
                @csrf
                @method('PUT') {{-- مهم لعملية التحديث --}}
                <input type="hidden" name="patient_id" value="{{ $patient->id }}">

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="prescription_date_edit" class="form-label required">تاريخ الوصفة</label>
                            <input type="text" name="prescription_date" id="prescription_date_edit"
                                   value="{{ old('prescription_date', $prescription->prescription_date->format('Y-m-d')) }}"
                                   class="form-control flatpickr-date @error('prescription_date') is-invalid @enderror" required
                                   placeholder="YYYY-MM-DD">
                            @error('prescription_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">نوع الوصفة</label>
                            <div class="form-check form-switch mt-2 pt-1">
                                <input class="form-check-input" type="checkbox" name="is_chronic_prescription" value="1" id="is_chronic_prescription_check_edit" {{ old('is_chronic_prescription', $prescription->is_chronic_prescription) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_chronic_prescription_check_edit">
                                    وصفة لمرض مزمن (تسمح بإعادة الصرف)
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <h5 class="form-section-title"><i class="fas fa-pills"></i>الأدوية الموصوفة</h5>
                <p class="text-muted small mb-3">قم بتعديل الأدوية المطلوبة للوصفة. يجب إضافة دواء واحد على الأقل.</p>
                @error('items') <div class="alert alert-danger py-2 px-3 small">{{ $message }}</div> @enderror
                @error('items.*.medication_id') <div class="alert alert-danger py-2 px-3 small">خطأ في أحد الأدوية: {{ $message }}</div> @enderror


                <div id="medication_items_wrapper_edit">
                    @php $itemsData = old('items', $prescription->items->map(function($item) {
                        return [
                            'medication_id' => $item->medication_id,
                            'dosage' => $item->dosage,
                            'frequency' => $item->frequency,
                            'duration' => $item->duration,
                            'route_of_administration' => $item->route_of_administration,
                            'quantity_prescribed' => $item->quantity_prescribed,
                            'refills_allowed' => $item->refills_allowed,
                            'is_prn' => $item->is_prn,
                            'instructions_for_patient' => $item->instructions_for_patient,
                        ];
                    })->all() ); @endphp

                    @if(is_array($itemsData) && count($itemsData) > 0)
                        @foreach($itemsData as $index => $itemData)
                            <div class="medication-item-row animate__animated animate__fadeIn">
                                <button type="button" class="remove-medication-item-btn" title="إزالة الدواء"><i class="fas fa-times"></i></button>
                                <input type="hidden" name="items[{{$index}}][medication_id]" value="{{ $itemData['medication_id'] ?? '' }}" class="selected-medication-id">
                                <div class="row g-3">
                                    <div class="col-md-12 mb-2">
                                         <label class="form-label required">الدواء</label>
                                        <select class="form-select select2-medications @error('items.'.$index.'.medication_id') is-invalid @enderror" data-item-index="{{$index}}" data-placeholder="ابحث واختر الدواء..." required>
                                            {{-- سيتم ملء هذا بواسطة JavaScript --}}
                                        </select>
                                         @error('items.'.$index.'.medication_id') <div class="invalid-feedback d-block">{{ $message }}</div> @else <div class="valid-feedback"></div> @enderror
                                         <div class="invalid-feedback custom-invalid-feedback-select2" style="display: none;">يرجى اختيار دواء.</div>
                                    </div>
                                    {{-- الجرعة --}}
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label required">الجرعة</label>
                                            <div class="input-group input-group-custom-select">
                                                <select class="form-select select2-common-options dosage-select" name="items[{{$index}}][dosage_select]" data-placeholder="اختر أو اكتب...">
                                                    <option></option><option value="قرص واحد">قرص واحد</option><option value="قرصان">قرصان</option><option value="نصف قرص">نصف قرص</option><option value="5 مل">5 مل</option><option value="10 مل">10 مل</option><option value="حقنة واحدة">حقنة واحدة</option><option value="other">أخرى</option>
                                                </select>
                                                <input type="text" name="items[{{$index}}][dosage_text]" value="{{ $itemData['dosage'] ?? '' }}" class="form-control dosage-text-input @error('items.'.$index.'.dosage') is-invalid @enderror" placeholder="أو اكتب هنا" style="display: none;">
                                                <input type="hidden" name="items[{{$index}}][dosage]" class="final-dosage-value" value="{{ $itemData['dosage'] ?? '' }}">
                                            </div>
                                             @error('items.'.$index.'.dosage') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                     {{-- التكرار --}}
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label required">التكرار</label>
                                             <div class="input-group input-group-custom-select">
                                                <select class="form-select select2-common-options frequency-select" name="items[{{$index}}][frequency_select]" data-placeholder="اختر أو اكتب...">
                                                    <option></option><option value="مرة واحدة يوميًا">مرة واحدة يوميًا</option><option value="مرتين يوميًا">مرتين يوميًا</option><option value="3 مرات يوميًا">3 مرات يوميًا</option><option value="كل 6 ساعات">كل 6 ساعات</option><option value="كل 8 ساعات">كل 8 ساعات</option><option value="كل 12 ساعة">كل 12 ساعة</option><option value="عند اللزوم">عند اللزوم</option><option value="other">أخرى</option>
                                                </select>
                                                <input type="text" name="items[{{$index}}][frequency_text]" value="{{ $itemData['frequency'] ?? '' }}" class="form-control frequency-text-input @error('items.'.$index.'.frequency') is-invalid @enderror" placeholder="أو اكتب هنا" style="display: none;">
                                                <input type="hidden" name="items[{{$index}}][frequency]" class="final-frequency-value" value="{{ $itemData['frequency'] ?? '' }}">
                                            </div>
                                            @error('items.'.$index.'.frequency') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                    {{-- المدة --}}
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">المدة</label>
                                            <div class="input-group input-group-custom-select">
                                                <select class="form-select select2-common-options duration-select" name="items[{{$index}}][duration_select]" data-placeholder="اختر أو اكتب...">
                                                     <option></option><option value="3 أيام">3 أيام</option><option value="5 أيام">5 أيام</option><option value="7 أيام">7 أيام</option><option value="10 أيام">10 أيام</option><option value="أسبوعان">أسبوعان</option><option value="شهر واحد">شهر واحد</option><option value="حتى إشعار آخر">حتى إشعار آخر</option><option value="other">أخرى</option>
                                                </select>
                                                <input type="text" name="items[{{$index}}][duration_text]" value="{{ $itemData['duration'] ?? '' }}" class="form-control duration-text-input @error('items.'.$index.'.duration') is-invalid @enderror" placeholder="أو اكتب هنا" style="display: none;">
                                                <input type="hidden" name="items[{{$index}}][duration]" class="final-duration-value" value="{{ $itemData['duration'] ?? '' }}">
                                            </div>
                                            @error('items.'.$index.'.duration') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3"><div class="form-group"><label class="form-label">طريقة الإعطاء</label><input type="text" name="items[{{$index}}][route_of_administration]" value="{{ $itemData['route_of_administration'] ?? ''}}" class="form-control @error('items.'.$index.'.route_of_administration') is-invalid @enderror" placeholder="فموي، حقن..."></div></div>
                                    <div class="col-md-2"><div class="form-group"><label class="form-label">الكمية</label><input type="number" name="items[{{$index}}][quantity_prescribed]" value="{{ $itemData['quantity_prescribed'] ?? ''}}" class="form-control @error('items.'.$index.'.quantity_prescribed') is-invalid @enderror" min="1" placeholder="وحدات"></div></div>
                                    <div class="col-md-2"><div class="form-group"><label class="form-label">إعادة صرف</label><input type="number" name="items[{{$index}}][refills_allowed]" value="{{ $itemData['refills_allowed'] ?? 0 }}" class="form-control @error('items.'.$index.'.refills_allowed') is-invalid @enderror" min="0" placeholder="0"></div></div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="form-label">عند اللزوم؟</label>
                                            <div class="form-check form-switch mt-1">
                                                <input class="form-check-input" type="checkbox" name="items[{{$index}}][is_prn]" value="1" id="is_prn_edit_{{$index}}" {{ ($itemData['is_prn'] ?? false) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_prn_edit_{{$index}}">نعم</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12"><div class="form-group mb-0"><label class="form-label">تعليمات للمريض</label><textarea name="items[{{$index}}][instructions_for_patient]" class="form-control @error('items.'.$index.'.instructions_for_patient') is-invalid @enderror" rows="2" placeholder="تعليمات خاصة بالدواء...">{{ $itemData['instructions_for_patient'] ?? ''}}</textarea></div></div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <button type="button" class="btn" id="add_medication_item_btn_edit"> {{-- معرف فريد --}}
                    <i class="fas fa-plus-circle me-1"></i> إضافة دواء آخر للوصفة
                </button>

                <hr class="my-4">
                <div class="form-group">
                    <label for="doctor_notes_edit" class="form-label">ملاحظات الطبيب للصيدلي (اختياري)</label>
                    <textarea name="doctor_notes" id="doctor_notes_edit" class="form-control @error('doctor_notes') is-invalid @enderror" rows="3" placeholder="أي تعليمات أو ملاحظات إضافية للصيدلي...">{{ old('doctor_notes', $prescription->doctor_notes) }}</textarea>
                    @error('doctor_notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-actions-footer mt-4">
                    <a href="{{ route('doctor.prescriptions.show', $prescription->id) }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-times me-1"></i> إلغاء التعديل
                    </a>
                    <button type="submit" class="btn btn-primary btn-submit-prescription" id="submitPrescriptionEditBtn">
                        <span class="btn-text"><i class="fas fa-save me-2"></i> حفظ التعديلات</span>
                        <i class="fas fa-spinner fa-spin spinner-icon"></i>
                    </button>
                </div>
            </form>
        @endif
    </div>

    <template id="medication_item_template_edit">
        {{-- نفس القالب من صفحة الإنشاء مع تغيير المعرفات إذا لزم الأمر --}}
        <div class="medication-item-row animate__animated animate__fadeIn">
            <button type="button" class="remove-medication-item-btn" title="إزالة الدواء"><i class="fas fa-times"></i></button>
            <input type="hidden" name="items[__INDEX__][medication_id]" class="selected-medication-id">
            <div class="row g-3">
                <div class="col-md-12 mb-2">
                    <label class="form-label required">الدواء</label>
                    <select class="form-select select2-medications-dynamic" name="items[__INDEX__][medication_id_select]" data-item-index="__INDEX__" data-placeholder="ابحث واختر الدواء..." required></select>
                    <div class="invalid-feedback custom-invalid-feedback-select2" style="display: none;">يرجى اختيار دواء.</div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label required">الجرعة</label>
                        <div class="input-group input-group-custom-select">
                            <select class="form-select select2-common-options dosage-select" name="items[__INDEX__][dosage_select]" data-placeholder="اختر أو اكتب...">
                                <option></option><option value="قرص واحد">قرص واحد</option><option value="قرصان">قرصان</option><option value="نصف قرص">نصف قرص</option><option value="5 مل">5 مل</option><option value="10 مل">10 مل</option><option value="حقنة واحدة">حقنة واحدة</option><option value="other">أخرى</option>
                            </select>
                            <input type="text" name="items[__INDEX__][dosage_text]" class="form-control dosage-text-input" placeholder="أو اكتب هنا" style="display: none;">
                            <input type="hidden" name="items[__INDEX__][dosage]" class="final-dosage-value">
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label required">التكرار</label>
                         <div class="input-group input-group-custom-select">
                            <select class="form-select select2-common-options frequency-select" name="items[__INDEX__][frequency_select]" data-placeholder="اختر أو اكتب...">
                                <option></option><option value="مرة واحدة يوميًا">مرة واحدة يوميًا</option><option value="مرتين يوميًا">مرتين يوميًا</option><option value="3 مرات يوميًا">3 مرات يوميًا</option><option value="كل 6 ساعات">كل 6 ساعات</option><option value="كل 8 ساعات">كل 8 ساعات</option><option value="كل 12 ساعة">كل 12 ساعة</option><option value="عند اللزوم">عند اللزوم</option><option value="other">أخرى</option>
                            </select>
                            <input type="text" name="items[__INDEX__][frequency_text]" class="form-control frequency-text-input" placeholder="أو اكتب هنا" style="display: none;">
                            <input type="hidden" name="items[__INDEX__][frequency]" class="final-frequency-value">
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="form-label">المدة</label>
                        <div class="input-group input-group-custom-select">
                            <select class="form-select select2-common-options duration-select" name="items[__INDEX__][duration_select]" data-placeholder="اختر أو اكتب...">
                                <option></option><option value="3 أيام">3 أيام</option><option value="5 أيام">5 أيام</option><option value="7 أيام">7 أيام</option><option value="10 أيام">10 أيام</option><option value="أسبوعان">أسبوعان</option><option value="شهر واحد">شهر واحد</option><option value="حتى إشعار آخر">حتى إشعار آخر</option><option value="other">أخرى</option>
                            </select>
                            <input type="text" name="items[__INDEX__][duration_text]" class="form-control duration-text-input" placeholder="أو اكتب هنا" style="display: none;">
                            <input type="hidden" name="items[__INDEX__][duration]" class="final-duration-value">
                        </div>
                    </div>
                </div>
                <div class="col-md-3"><div class="form-group"><label class="form-label">طريقة الإعطاء</label><input type="text" name="items[__INDEX__][route_of_administration]" class="form-control" placeholder="فموي، حقن..."></div></div>
                <div class="col-md-2"><div class="form-group"><label class="form-label">الكمية</label><input type="number" name="items[__INDEX__][quantity_prescribed]" class="form-control" min="1" placeholder="عدد"></div></div>
                <div class="col-md-2"><div class="form-group"><label class="form-label">إعادة صرف</label><input type="number" name="items[__INDEX__][refills_allowed]" class="form-control" value="0" min="0" placeholder="0"></div></div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-label">عند اللزوم؟</label>
                        <div class="form-check form-switch mt-1">
                            <input class="form-check-input" type="checkbox" name="items[__INDEX__][is_prn]" value="1" id="is_prn_edit___INDEX__">
                            <label class="form-check-label" for="is_prn_edit___INDEX__">نعم</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-12"><div class="form-group mb-0"><label class="form-label">تعليمات للمريض</label><textarea name="items[__INDEX__][instructions_for_patient]" class="form-control" rows="2" placeholder="تعليمات خاصة بالدواء..."></textarea></div></div>
            </div>
        </div>
    </template>
@endsection

@section('js')
    @parent
    <script src="{{ URL::asset('dashboard/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('dashboard/plugins/notify/js/notifit-custom.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/ar.js"></script>
    <script src="{{ URL::asset('Dashboard/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/select2/js/i18n/ar.js') }}"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        function showNotification(message, type = 'info', position = 'top-center', autohide = true, timeout = 4000) {
            let iconClass = 'fas fa-info-circle';
            if (type === 'success') iconClass = 'fas fa-check-circle';
            else if (type === 'error') iconClass = 'fas fa-times-circle';
            else if (type === 'warning') iconClass = 'fas fa-exclamation-triangle';
            notif({
                msg: `<div class="d-flex align-items-center p-2"><i class='${iconClass} fa-lg me-2'></i><div style="font-size: 0.95rem;">${message}</div></div>`,
                type: type, position: position, autohide: autohide, timeout: timeout,
                multiline: true, zindex: 99999, width: 'auto', padding: '15px'
            });
        }

        $(document).ready(function() {
            AOS.init({ duration: 600, once: true, offset: 50 });

            const flatpickrConfigGlobal = { dateFormat: "Y-m-d", locale: "ar", allowInput: true, disableMobile: "true" };
            flatpickr("#prescription_date_edit", { ...flatpickrConfigGlobal, defaultDate: "{{ old('prescription_date', $prescription->prescription_date->format('Y-m-d')) }}", maxDate: "today" });

            const medicationsData = @json(
                (isset($medications) && $medications instanceof \Illuminate\Support\Collection && $medications->isNotEmpty())
                    ? $medications->map(function ($med) {
                        return [
                            'id' => $med->id,
                            'text' => $med->display_text_for_select2 ?? $med->name,
                            'details' => $med->details_for_select2_dropdown ?? ''
                        ];
                    })->values()->all()
                    : []
            );

            function formatMedicationResult(medication) { /* ... نفس الدالة ... */
                if (!medication.id) { return medication.text; }
                return $(
                    '<div class="select2-results__option--medication">' +
                    '<span class="medication-name">' + medication.text + '</span>' +
                    (medication.details ? '<span class="medication-details">' + medication.details + '</span>' : '') +
                    '</div>'
                );
            }
            function formatMedicationSelection(medication) { /* ... نفس الدالة ... */ return medication.text || medication.id; }

            function initializeMedicationSelect2(selector, itemIndex) {
                const $selectElement = $(selector);
                if ($selectElement.data('select2')) { $selectElement.select2('destroy'); }
                $selectElement.empty();
                $selectElement.select2({
                    placeholder: "ابحث واختر الدواء...", width: '100%', dir: "rtl", theme: "bootstrap-5",
                    allowClear: false, data: medicationsData,
                    escapeMarkup: function(markup) { return markup; },
                    templateResult: formatMedicationResult, templateSelection: formatMedicationSelection,
                    dropdownParent: $selectElement.closest('.medication-item-row')
                }).on('select2:select', function(e) {
                    var data = e.params.data;
                    const $row = $(this).closest('.medication-item-row');
                    $row.find('.selected-medication-id').val(data.id).trigger('change');
                    $(this).next('.select2-container').find('.select2-selection').removeClass('is-invalid');
                    $row.find('.custom-invalid-feedback-select2').hide();
                });
                const $hiddenInput = $selectElement.closest('.medication-item-row').find('.selected-medication-id');
                const currentMedicationId = $hiddenInput.val();
                if (currentMedicationId) {
                    $selectElement.val(currentMedicationId).trigger('change.select2');
                } else {
                    $hiddenInput.val('');
                }
            }

            function initializeCommonOptionsSelect2(selector, placeholder, parent) {
                const $select = $(selector);
                if ($select.data('select2')) { $select.select2('destroy'); } // تدمير القديم إذا وجد
                $select.select2({
                    placeholder: placeholder, width: '100%', dir: "rtl", theme: "bootstrap-5",
                    allowClear: true, tags: false, dropdownParent: parent
                }).on('change', function() {
                    const $this = $(this);
                    const $textInput = $this.closest('.input-group').find('input[type="text"]');
                    const $finalValueInput = $this.closest('.input-group').find('input[type="hidden"]');
                    const isRequiredOriginal = $this.prop('required'); // تذكر الحالة الأصلية للمطلوب

                    if ($this.val() === 'other') {
                        $textInput.show().val($finalValueInput.val()).focus().prop('required', true); // اجعل النص مطلوبًا
                    } else {
                        $textInput.hide().val('').prop('required', false);
                        $finalValueInput.val($this.val());
                    }
                }).trigger('change'); // شغل الحدث عند التحميل لضبط الحقول النصية

                $(selector).closest('.input-group').find('input[type="text"]').on('input', function() {
                    $(this).closest('.input-group').find('input[type="hidden"]').val($(this).val());
                });
            }

            let medicationItemCounterEdit = {{ is_array($itemsData) ? count($itemsData) : 0 }};

            function addMedicationRowEdit(focusNew = true, itemData = null) {
                const template = document.getElementById('medication_item_template_edit').innerHTML;
                const uniqueIndex = Date.now() + '_' + medicationItemCounterEdit;
                const newRowHtml = template.replace(/__INDEX__/g, uniqueIndex);
                const $newRow = $(newRowHtml).appendTo('#medication_items_wrapper_edit');

                initializeMedicationSelect2($newRow.find('.select2-medications-dynamic'), uniqueIndex);
                initializeCommonOptionsSelect2($newRow.find('.dosage-select'), "اختر الجرعة أو اكتب", $newRow.find('.dosage-select').closest('.form-group'));
                initializeCommonOptionsSelect2($newRow.find('.frequency-select'), "اختر التكرار أو اكتب", $newRow.find('.frequency-select').closest('.form-group'));
                initializeCommonOptionsSelect2($newRow.find('.duration-select'), "اختر المدة أو اكتب", $newRow.find('.duration-select').closest('.form-group'));

                if (itemData) {
                    $newRow.find('.selected-medication-id').val(itemData.medication_id || '');
                    initializeMedicationSelect2($newRow.find('.select2-medications-dynamic'), uniqueIndex); // Re-init

                    function setComboValue(type, value) {
                        const $select = $newRow.find('.' + type + '-select');
                        const $text = $newRow.find('.' + type + '-text-input');
                        const $hidden = $newRow.find('.final-' + type + '-value');
                        $hidden.val(value || '');
                        if ($select.find('option[value="' + value + '"]').length > 0) {
                            $select.val(value).trigger('change');
                        } else if (value) {
                            $select.val('other').trigger('change');
                            $text.val(value); // لا تقم بإظهاره هنا، حدث change سيفعل ذلك
                        } else {
                            $select.val(null).trigger('change');
                        }
                    }
                    setComboValue('dosage', itemData.dosage);
                    setComboValue('frequency', itemData.frequency);
                    setComboValue('duration', itemData.duration);

                    $newRow.find('input[name$="[route_of_administration]"]').val(itemData.route_of_administration || '');
                    $newRow.find('input[name$="[quantity_prescribed]"]').val(itemData.quantity_prescribed || '');
                    $newRow.find('input[name$="[refills_allowed]"]').val(itemData.refills_allowed || 0);
                    $newRow.find('input[name$="[is_prn]"]').prop('checked', itemData.is_prn == '1' || itemData.is_prn === true);
                    $newRow.find('textarea[name$="[instructions_for_patient]"]').val(itemData.instructions_for_patient || '');
                }

                if (focusNew && !itemData) { $newRow.find('.select2-medications-dynamic').first().select2('open'); }

                $newRow.find('input[type="checkbox"]').each(function() {
                    const originalId = $(this).attr('id');
                    if (originalId && originalId.includes('__INDEX__')) {
                        const newId = originalId.replace('__INDEX__', uniqueIndex);
                        $(this).attr('id', newId); $(this).next('label').attr('for', newId);
                    }
                });
                medicationItemCounterEdit++;
            }

            // تهيئة الصفوف الموجودة (من $prescription->items أو old('items'))
            $('#medication_items_wrapper_edit .medication-item-row').each(function() {
                const $row = $(this);
                const itemIndex = $row.find('input[name^="items["]').attr('name').match(/items\[(\d+)\]/)[1];

                initializeMedicationSelect2($row.find('.select2-medications'), itemIndex);
                initializeCommonOptionsSelect2($row.find('.dosage-select'), "اختر الجرعة أو اكتب", $row.find('.dosage-select').closest('.form-group'));
                initializeCommonOptionsSelect2($row.find('.frequency-select'), "اختر التكرار أو اكتب", $row.find('.frequency-select').closest('.form-group'));
                initializeCommonOptionsSelect2($row.find('.duration-select'), "اختر المدة أو اكتب", $row.find('.duration-select').closest('.form-group'));

                // استدعاء trigger change يدويًا لضبط الحقول النصية بناءً على القيم المحددة في select
                $row.find('.dosage-select, .frequency-select, .duration-select').trigger('change');
            });


            $('#add_medication_item_btn_edit').click(function() { addMedicationRowEdit(true); });
            $('#medication_items_wrapper_edit').on('click', '.remove-medication-item-btn', function() {
                $(this).closest('.medication-item-row').addClass('animate__animated animate__zoomOutLeft').one('animationend', function() {
                    $(this).remove();
                    if ($('#medication_items_wrapper_edit .medication-item-row').length === 0) { addMedicationRowEdit(false); }
                });
            });

            const prescriptionFormEdit = document.getElementById('prescriptionEditForm');
            const submitPrescriptionEditBtn = document.getElementById('submitPrescriptionEditBtn');

            if (prescriptionFormEdit) {
                prescriptionFormEdit.addEventListener('submit', function(event) {
                    let formIsValid = true;
                    prescriptionFormEdit.querySelectorAll('.form-control, .form-select, .select2-selection').forEach(el => { el.classList.remove('is-invalid'); });
                    prescriptionFormEdit.querySelectorAll('.custom-invalid-feedback-select2').forEach(el => { el.style.display = 'none'; });

                    // Update hidden fields before validation
                    $('#medication_items_wrapper_edit .medication-item-row').each(function() {
                        const $row = $(this);
                        ['dosage', 'frequency', 'duration'].forEach(type => {
                            const $select = $row.find('.' + type + '-select');
                            const $text = $row.find('.' + type + '-text-input');
                            const $hidden = $row.find('.final-' + type + '-value');
                            if ($select.val() === 'other') { $hidden.val($text.val()); }
                            else { $hidden.val($select.val()); }
                        });
                    });

                    if (!prescriptionFormEdit.checkValidity()) { formIsValid = false; }

                    let firstInvalidMedicationSelect = null;
                    $('#medication_items_wrapper_edit .medication-item-row').each(function() {
                        const $row = $(this);
                        const medicationIdHiddenField = $row.find('.selected-medication-id');
                        const medicationSelectElement = $row.find('.select2-medications-dynamic'); // استهدف .select2-medications-dynamic
                        const errorFeedbackDiv = $row.find('.custom-invalid-feedback-select2');
                        if (!medicationIdHiddenField.val()) {
                            formIsValid = false;
                            medicationSelectElement.next('.select2-container').find('.select2-selection').addClass('is-invalid');
                            errorFeedbackDiv.show();
                            if (!firstInvalidMedicationSelect) { firstInvalidMedicationSelect = medicationSelectElement; }
                        }
                    });

                    if (!formIsValid) {
                        event.preventDefault(); event.stopPropagation();
                        const firstGeneralInvalidElement = prescriptionFormEdit.querySelector(':invalid:not(fieldset), .is-invalid');
                        if (firstInvalidMedicationSelect && firstInvalidMedicationSelect.length) {
                            $('html, body').animate({ scrollTop: firstInvalidMedicationSelect.offset().top - 120 }, 300, () => firstInvalidMedicationSelect.select2('open'));
                            showNotification("يرجى اختيار دواء لجميع البنود.", "warning");
                        } else if (firstGeneralInvalidElement) {
                            firstGeneralInvalidElement.focus({ preventScroll: true });
                            $('html, body').animate({ scrollTop: $(firstGeneralInvalidElement).offset().top - 120 }, 300);
                            showNotification("يرجى ملء الحقول المطلوبة.", "warning");
                        } else { showNotification("يرجى تصحيح الأخطاء.", "warning"); }
                    } else {
                        if (submitPrescriptionEditBtn) {
                            submitPrescriptionEditBtn.classList.add('loading');
                            submitPrescriptionEditBtn.disabled = true;
                            $(submitPrescriptionEditBtn).find('.btn-text').text('جاري حفظ التعديلات...');
                        }
                    }
                    prescriptionFormEdit.classList.add('was-validated');
                }, false);
            }

            @if (session('edit')) showNotification("{{ session('edit') }}", "success", "top-center", true, 6000); @endif
            @if (session('error')) showNotification("{{ session('error') }}", "error", "top-center", false); @endif
            @if ($errors->any())
                let errorList = "<strong><i class='fas fa-exclamation-triangle me-2'></i> حدثت الأخطاء التالية:</strong><ul class='mb-0 mt-2' style='list-style-type:none; padding-right:0;'>";
                @foreach ($errors->all() as $error) errorList += "<li class='mb-1'>- {{ $error }}</li>"; @endforeach
                errorList += "</ul>";
                showNotification(errorList, "error", "top-center", false);
            @endif
        });
    </script>
@endsection
