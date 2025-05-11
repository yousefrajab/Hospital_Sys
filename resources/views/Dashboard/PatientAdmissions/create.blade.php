@extends('Dashboard.layouts.master')
@section('title', 'تسجيل دخول مريض جديد')

@section('css')
    @parent
    <link href="{{ URL::asset('Dashboard/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"> {{-- Flatpickr للتاريخ والوقت --}}
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
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <i class="fas fa-user-plus fa-lg me-2" style="color: var(--admin-primary, #4f46e5);"></i>
                <div>
                    <h4 class="content-title mb-0 my-auto">إدارة إقامة المرضى</h4>
                    <span class="text-muted mt-0 tx-13">/ تسجيل دخول مريض جديد</span>
                </div>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <a href="{{ route('admin.patient_admissions.index') }}" class="btn btn-outline-secondary btn-sm"
                style="border-radius: var(--admin-radius-md);">
                <i class="fas fa-arrow-left me-1"></i> العودة لسجلات الدخول
            </a>
        </div>
    </div>
@endsection

@section('content')
    @include('Dashboard.messages_alert')

    <div class="row justify-content-center animate__animated animate__fadeInUp">
        <div class="col-lg-10 col-md-12"> {{-- عرض أكبر قليلاً للفورم --}}
            <div class="form-container-card">
                <div class="card-header card-header-custom">
                    <h3 class="card-title mb-0"><i class="fas fa-hospital-user"></i> بيانات تسجيل الدخول الجديدة</h3>
                </div>
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('admin.patient_admissions.store') }}" method="POST" class="needs-validation"
                        novalidate autocomplete="off">
                        @csrf
                        <div class="row g-3">
                            {{-- اختيار المريض --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="patient_id" class="form-label">المريض <span
                                            class="text-danger">*</span></label>
                                    <select name="patient_id" id="patient_id"
                                        class="form-select select2-searchable @error('patient_id') is-invalid @enderror"
                                        required data-placeholder="-- ابحث واختر المريض --">
                                        <option value=""></option>
                                        @foreach ($patients as $patient)
                                            <option value="{{ $patient->id }}"
                                                {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                                {{ $patient->name }} (ID: {{ $patient->id }}, هوية:
                                                {{ $patient->national_id }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('patient_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- الطبيب المسؤول (اختياري) --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="doctor_id" class="form-label">الطبيب المسؤول</label>
                                    <select name="doctor_id" id="doctor_id"
                                        class="form-select select2-searchable @error('doctor_id') is-invalid @enderror"
                                        data-placeholder="-- اختر الطبيب (اختياري) --">
                                        <option value=""></option>
                                        @foreach ($doctors as $doctor)
                                            <option value="{{ $doctor->id }}"
                                                {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                                {{ $doctor->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('doctor_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- تاريخ ووقت الدخول --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="admission_date" class="form-label">تاريخ ووقت الدخول <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="admission_date" id="admission_date"
                                        class="form-control flatpickr-datetime @error('admission_date') is-invalid @enderror"
                                        value="{{ old('admission_date', now()->format('Y-m-d H:i')) }}" required
                                        placeholder="اختر تاريخ ووقت">
                                    @error('admission_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- الحالة (عادةً 'admitted' عند الإنشاء) --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status_admission" class="form-label">حالة سجل الدخول <span
                                            class="text-danger">*</span></label>
                                    <select name="status" id="status_admission"
                                        class="form-select select2 @error('status') is-invalid @enderror" required>
                                        @foreach ($admissionStatuses as $key => $value)
                                            <option value="{{ $key }}"
                                                {{ old('status', \App\Models\PatientAdmission::STATUS_ADMITTED) == $key ? 'selected' : '' }}>
                                                {{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- القسم (اختياري، قد يُستنتج من الغرفة) --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="section_id_admission" class="form-label">القسم (اختياري)</label>
                                    <select name="section_id" id="section_id_admission"
                                        class="form-select select2-searchable @error('section_id') is-invalid @enderror"
                                        data-placeholder="-- اختر القسم --">
                                        <option value=""></option>
                                        @foreach ($sections as $section)
                                            <option value="{{ $section->id }}"
                                                {{ old('section_id') == $section->id ? 'selected' : '' }}>
                                                {{ $section->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('section_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- السرير (اختياري عند الدخول الأولي، يمكن تحديث هذه القائمة بـ AJAX بناءً على القسم/الغرفة) --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bed_id" class="form-label">السرير المخصص (اختياري)</label>
                                    <select name="bed_id" id="bed_id"
                                        class="form-select select2-searchable @error('bed_id') is-invalid @enderror"
                                        data-placeholder="-- اختر السرير المتاح (إذا لزم الأمر الآن) --">
                                        <option value=""></option>
                                        @foreach ($availableBeds as $bed)
                                            <option value="{{ $bed->id }}"
                                                {{ old('bed_id') == $bed->id ? 'selected' : '' }}>
                                                {{ $bed->display_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('bed_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">سيتم عرض الأسرة المتاحة فقط. يمكن تحديث هذا
                                        لاحقًا.</small>
                                </div>
                            </div>

                            {{-- سبب الدخول --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="reason_for_admission" class="form-label">سبب الدخول</label>
                                    <textarea name="reason_for_admission" id="reason_for_admission"
                                        class="form-control @error('reason_for_admission') is-invalid @enderror" rows="3"
                                        placeholder="اكتب سبب دخول المريض...">{{ old('reason_for_admission') }}</textarea>
                                    @error('reason_for_admission')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- التشخيص عند الدخول --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="admitting_diagnosis" class="form-label">التشخيص المبدئي عند الدخول</label>
                                    <input type="text" name="admitting_diagnosis" id="admitting_diagnosis"
                                        class="form-control @error('admitting_diagnosis') is-invalid @enderror"
                                        value="{{ old('admitting_diagnosis') }}" placeholder="التشخيص المبدئي...">
                                    @error('admitting_diagnosis')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- ملاحظات إضافية --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="notes_admission" class="form-label">ملاحظات إضافية</label>
                                    <textarea name="notes" id="notes_admission" class="form-control @error('notes') is-invalid @enderror"
                                        rows="3" placeholder="أي ملاحظات أخرى...">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4 pt-3 border-top">
                            <button type="submit" class="btn btn-submit-form">
                                <i class="fas fa-check-circle me-2"></i> تسجيل الدخول
                            </button>
                            <a href="{{ route('admin.patient_admissions.index') }}" class="btn btn-cancel-form ms-2">
                                <i class="fas fa-times me-2"></i> إلغاء
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @parent
    <script src="{{ URL::asset('Dashboard/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/select2/js/i18n/ar.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifit-custom.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/ar.js"></script>

    <script>
        $(document).ready(function() {
            // تهيئة Select2
            $('.select2').select2({
                placeholder: $(this).data('placeholder') || "اختر...",
                width: '100%',
                dir: "rtl",
                theme: "bootstrap-5",
                allowClear: true
            });
            $('.select2-searchable').select2({ // للسماح بالبحث
                placeholder: $(this).data('placeholder') || "ابحث واختر...",
                width: '100%',
                dir: "rtl",
                theme: "bootstrap-5",
                allowClear: true
            });


            // تهيئة Flatpickr لحقل تاريخ ووقت الدخول
            $("#admission_date").flatpickr({
                enableTime: true,
                dateFormat: "Y-m-d H:i", // تنسيق مع الوقت
                locale: "ar",
                allowInput: true,
                defaultDate: "today", // القيمة الافتراضية هي الآن
                minuteIncrement: 15, // زيادة الدقائق بمقدار 15
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

            @if (session('success'))
                notif({
                    msg: "<i class='fas fa-check-circle me-2'></i> {{ session('success') }}",
                    type: "success",
                    position: "bottom",
                    autohide: true,
                    timeout: 5000
                });
            @endif
            @if (session('error'))
                notif({
                    msg: "<i class='fas fa-exclamation-triangle me-2'></i> {{ session('error') }}",
                    type: "error",
                    position: "bottom",
                    autohide: true,
                    timeout: 7000
                });
            @endif
            @if ($errors->any())
                let errorMsg =
                    "<strong><i class='fas fa-times-circle me-2'></i> يرجى تصحيح الأخطاء التالية:</strong><ul class='mb-0 ps-3 mt-1' style='list-style-type: none; padding-right: 0;'>";
                @foreach ($errors->all() as $error)
                    errorMsg += "<li>- {{ $error }}</li>";
                @endforeach
                errorMsg += "</ul>";
                notif({
                    msg: errorMsg,
                    type: "error",
                    position: "bottom",
                    multiline: true,
                    autohide: false
                });
            @endif
        });
    </script>
@endsection
