@extends('Dashboard.layouts.master')

@php
    // تحضير بعض البيانات للعرض بشكل أفضل
    $patientName = $patientAdmission->patient->name ?? 'مريض غير محدد';
    $pageTitle = 'تفاصيل إقامة المريض: ' . $patientName;
@endphp
@section('title', $pageTitle)

@section('css')
    @parent
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <style>
        /* ... (استخدم نفس متغيرات CSS الرائعة التي صممتها مع بعض الإضافات) ... */
        :root {
            --admin-primary: #4f46e5; --admin-primary-dark: #4338ca; --admin-secondary: #10b981;
            --admin-success: #22c55e; --admin-danger: #ef4444; --admin-warning: #f59e0b;
            --admin-info: #3b82f6; --admin-bg: #f8f9fc; --admin-card-bg: #ffffff;
            --admin-text: #111827; --admin-text-secondary: #6b7280; --admin-border-color: #e5e7eb;
            --admin-radius-lg: 0.75rem; --admin-radius-xl: 1rem;
            --admin-shadow-lg: 0 10px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.1);
            --admin-transition: all 0.3s ease-in-out;
            --admin-success-rgb: 34,197,94; --admin-danger-rgb: 239,68,68; --admin-secondary-rgb: 108,117,125;
        }
         @media (prefers-color-scheme: dark) {
            :root { /* ... (أنماط الوضع الداكن) ... */ }
            .details-card-section, .patient-summary-card { background-color: #2d3748; border-color: var(--admin-border-color); }
            .details-card-section strong, .patient-summary-card .patient-name { color: var(--admin-text) !important; }
            .timeline-item::before { background-color: var(--admin-border-color); }
            .timeline-icon { border-color: var(--admin-card-bg); }
        }
        body { background-color: var(--admin-bg); font-family: 'Tajawal', sans-serif; color: var(--admin-text); }

        .admission-details-page { padding-top: 1.5rem; padding-bottom: 3rem; }
        .main-admission-card {
            background-color: var(--admin-card-bg);
            border-radius: var(--admin-radius-xl);
            box-shadow: var(--admin-shadow-lg);
            border: 1px solid var(--admin-border-color);
        }
        .admission-card-header {
            padding: 1.5rem 2rem;
            background: linear-gradient(135deg, var(--admin-primary), var(--admin-info));
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid transparent; /* لإزالة الخط إذا كان موجودًا */
            border-radius: var(--admin-radius-xl) var(--admin-radius-xl) 0 0;
        }
        .admission-card-header h3 { margin: 0; font-size: 1.5rem; font-weight: 700; }
        .admission-card-header .status-badge-admission { font-size: 0.9rem; padding: 0.5em 1em; border-radius: 50px; }

        .admission-card-body { padding: 2rem; }
        .details-grid-admission { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
        .details-card-section {
            background-color: var(--admin-bg);
            padding: 1.25rem;
            border-radius: var(--admin-radius-md);
            border-left: 4px solid var(--admin-secondary);
        }
        .details-card-section .section-label { display: block; font-size: 0.85rem; color: var(--admin-text-secondary); margin-bottom: 0.4rem; font-weight: 500;}
        .details-card-section .section-value { font-size: 1.05rem; color: var(--admin-text); font-weight: 600; word-break: break-word; }
        .details-card-section .section-value i { margin-left: 0.5rem; color: var(--admin-secondary); width: 1.2em; }
        .details-card-section .section-value a { color: var(--admin-primary); text-decoration: none; }
        .details-card-section .section-value a:hover { text-decoration: underline; color: var(--admin-primary-dark); }

        .patient-summary-card {
            background-color: var(--admin-card-bg);
            border: 1px solid var(--admin-border-color);
            border-radius: var(--admin-radius-lg);
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
            box-shadow: var(--admin-shadow);
        }
        .patient-avatar-show { width: 60px; height: 60px; border-radius: 50%; object-fit: cover; border: 3px solid var(--admin-primary-light); }
        .patient-summary-details .patient-name { font-size: 1.25rem; font-weight: 700; color: var(--admin-text); margin-bottom: 0.1rem; }
        .patient-summary-details .patient-meta { font-size: 0.85rem; color: var(--admin-text-secondary); }
        .patient-summary-details .patient-meta span:not(:last-child)::after { content: '•'; margin: 0 0.4rem; }

        .section-title-page { font-size: 1.3rem; font-weight: 600; color: var(--admin-primary); margin-bottom: 1.5rem; display: flex; align-items: center; padding-bottom: 0.5rem; border-bottom: 2px solid var(--admin-primary-light); }
        .section-title-page i { margin-left: 0.75rem; font-size: 1.1em; }

        .action-buttons-footer-show { margin-top: 2.5rem; padding-top: 1.5rem; border-top: 1px solid var(--admin-border-color); text-align: center; }
        .btn-action-footer-show { padding: 0.75rem 1.5rem; border-radius: var(--admin-radius-md); font-weight: 600; margin: 0 0.5rem; text-decoration: none; }
        .btn-primary-custom { background-color: var(--admin-primary); color:white; border:1px solid var(--admin-primary); }
        .btn-primary-custom:hover { background-color: var(--admin-primary-dark); border-color:var(--admin-primary-dark); }
        .btn-secondary-custom { background-color: var(--admin-text-secondary); color:white; border:1px solid var(--admin-text-secondary); }
        .btn-secondary-custom:hover { background-color: #5a6268; border-color:#545b62; }

        /* لتمييز حالة سجل الدخول */
        .status-admitted { background-color: rgba(var(--admin-success-rgb), 0.15); color: #166534; }
        .status-discharged { background-color: rgba(var(--admin-text-secondary-rgb, 108, 117, 125), 0.15); color: var(--admin-text-secondary); }
        .status-cancelled { background-color: rgba(var(--admin-danger-rgb), 0.15); color: #721c24; }
        /* ... (أضف حالات أخرى إذا لزم الأمر) ... */

    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <i class="fas fa-clipboard-user fa-lg me-2" style="color: var(--admin-primary);"></i>
                <div>
                    <h4 class="content-title mb-0 my-auto">سجلات الدخول</h4>
                    <span class="text-muted mt-0 tx-13">/ تفاصيل إقامة المريض: {{ $patientName }}</span>
                </div>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            @if($patientAdmission->status == \App\Models\PatientAdmission::STATUS_ADMITTED && !$patientAdmission->discharge_date)
                <a href="{{ route('admin.patient_admissions.edit', $patientAdmission->id) }}" class="btn btn-outline-primary btn-sm me-2" style="border-radius: var(--admin-radius-md);">
                    <i class="fas fa-edit me-1"></i> تعديل بيانات الدخول
                </a>
                {{-- زر تسجيل الخروج يمكن أن يفتح مودال أو يوجه لصفحة تسجيل خروج --}}
                <button type="button" class="btn btn-outline-warning btn-sm me-2" data-bs-toggle="modal" data-bs-target="#dischargePatientModal{{$patientAdmission->id}}">
                    <i class="fas fa-user-minus me-1"></i> تسجيل خروج المريض
                </button>
            @endif
            <a href="{{ route('admin.patient_admissions.index') }}" class="btn btn-outline-secondary btn-sm" style="border-radius: var(--admin-radius-md);">
                <i class="fas fa-arrow-left me-1"></i> العودة للقائمة
            </a>
        </div>
    </div>
@endsection

@section('content')
    @include('Dashboard.messages_alert')

    <div class="admission-details-page">
        <div class="main-admission-card animate__animated animate__fadeInUp">
            <div class="admission-card-header">
                <h3><i class="fas fa-file-medical-alt"></i> سجل الإقامة رقم: #{{ $patientAdmission->id }}</h3>
                @php
                    $statusBadgeClass = 'bg-info-soft'; // افتراضي
                    if($patientAdmission->status == \App\Models\PatientAdmission::STATUS_ADMITTED) $statusBadgeClass = 'status-admitted';
                    elseif($patientAdmission->status == \App\Models\PatientAdmission::STATUS_DISCHARGED) $statusBadgeClass = 'status-discharged';
                    elseif($patientAdmission->status == \App\Models\PatientAdmission::STATUS_CANCELLED) $statusBadgeClass = 'status-cancelled';
                @endphp
                <span class="badge status-badge-admission {{ $statusBadgeClass }}">{{ $statusDisplay }}</span>
            </div>

            <div class="admission-card-body">
                {{-- 1. ملخص معلومات المريض --}}
                @if($patientAdmission->patient)
                    @php $patient = $patientAdmission->patient; @endphp
                    <div class="patient-summary-card animate__animated animate__fadeIn" style="animation-delay: 0.1s;">
                        <img src="{{ $patient->image ? asset('Dashboard/img/patients/' . $patient->image->filename) : asset('Dashboard/img/doctor_default.png') }}"
                             alt="{{ $patient->name }}" class="patient-avatar-show">
                        <div class="patient-summary-details">
                            <h4 class="patient-name">{{ $patient->name }}</h4>
                            <div class="patient-meta">
                                <span>الهوية: {{ $patient->national_id }}</span>
                                <span>البريد: {{ $patient->email }}</span>
                                <span>الهاتف: {{ $patient->Phone }}</span>
                            </div>
                            {{-- رابط لملف المريض الكامل --}}
                             @if(Route::has('admin.Patients.show'))
                                <a href="{{ route('admin.Patients.show', $patient->id) }}" class="btn btn-sm btn-link p-0 mt-1">عرض الملف الكامل للمريض <i class="fas fa-arrow-alt-circle-left"></i></a>
                             @endif
                        </div>
                    </div>
                @endif

                <hr class="section-divider-page">

                {{-- 2. تفاصيل الإقامة --}}
                <h5 class="section-title-page"><i class="fas fa-calendar-alt"></i> تفاصيل الإقامة</h5>
                <div class="details-grid-admission">
                    <div class="details-card-section">
                        <span class="section-label"><i class="fas fa-sign-in-alt"></i>تاريخ ووقت الدخول</span>
                        <strong class="section-value">{{ $patientAdmission->admission_date->translatedFormat('l, d M Y - H:i A') }}</strong>
                    </div>
                    <div class="details-card-section">
                        <span class="section-label"><i class="fas fa-sign-out-alt"></i>تاريخ ووقت الخروج</span>
                        <strong class="section-value">{{ $patientAdmission->discharge_date ? $patientAdmission->discharge_date->translatedFormat('l, d M Y - H:i A') : 'لم يتم تسجيل الخروج بعد' }}</strong>
                    </div>
                    <div class="details-card-section">
                        <span class="section-label"><i class="fas fa-user-md"></i>الطبيب المسؤول</span>
                        <strong class="section-value">
                            @if($patientAdmission->doctor)
                                {{ $patientAdmission->doctor->name }}
                                @if($patientAdmission->doctor->section) <small class="text-muted">({{ $patientAdmission->doctor->section->name }})</small> @endif
                            @else - @endif
                        </strong>
                    </div>
                     <div class="details-card-section">
                        <span class="section-label"><i class="fas fa-hospital-symbol"></i>القسم</span>
                        <strong class="section-value">{{ $patientAdmission->section->name ?? ($patientAdmission->bed->room->section->name ?? 'غير محدد') }}</strong>
                    </div>
                    <div class="details-card-section">
                        <span class="section-label"><i class="fas fa-door-open"></i>الغرفة</span>
                        <strong class="section-value">{{ $patientAdmission->bed->room->room_number ?? 'لم تحدد غرفة' }}</strong>
                    </div>
                    <div class="details-card-section">
                        <span class="section-label"><i class="fas fa-bed"></i>السرير</span>
                        <strong class="section-value">{{ $patientAdmission->bed->bed_number ?? 'لم يخصص سرير' }}</strong>
                    </div>
                </div>

                @if($patientAdmission->reason_for_admission)
                <div class="mt-4">
                    <h6 class="font-weight-bold" style="color:var(--admin-primary);"><i class="fas fa-notes-medical me-2"></i>سبب الدخول:</h6>
                    <p class="text-muted" style="white-space: pre-wrap;">{{ $patientAdmission->reason_for_admission }}</p>
                </div>
                @endif

                @if($patientAdmission->admitting_diagnosis)
                <div class="mt-3">
                    <h6 class="font-weight-bold" style="color:var(--admin-primary);"><i class="fas fa-stethoscope me-2"></i>التشخيص المبدئي عند الدخول:</h6>
                    <p class="text-muted" style="white-space: pre-wrap;">{{ $patientAdmission->admitting_diagnosis }}</p>
                </div>
                @endif

                @if($patientAdmission->discharge_reason)
                <div class="mt-3">
                    <h6 class="font-weight-bold" style="color:var(--admin-primary);"><i class="fas fa-comment-medical me-2"></i>سبب/ملاحظات الخروج:</h6>
                    <p class="text-muted" style="white-space: pre-wrap;">{{ $patientAdmission->discharge_reason }}</p>
                </div>
                @endif

                @if($patientAdmission->discharge_diagnosis)
                <div class="mt-3">
                    <h6 class="font-weight-bold" style="color:var(--admin-primary);"><i class="fas fa-file-medical me-2"></i>التشخيص عند الخروج:</h6>
                    <p class="text-muted" style="white-space: pre-wrap;">{{ $patientAdmission->discharge_diagnosis }}</p>
                </div>
                @endif

                @if($patientAdmission->notes)
                <hr class="section-divider-page">
                <div>
                    <h5 class="section-title-page"><i class="far fa-sticky-note"></i> ملاحظات إضافية على سجل الإقامة</h5>
                    <p class="text-muted" style="white-space: pre-wrap;">{{ $patientAdmission->notes }}</p>
                </div>
                @endif

                <div class="action-buttons-footer-show">
                    <a href="{{ route('admin.patient_admissions.edit', $patientAdmission->id) }}" class="btn btn-primary-custom btn-action-footer-show ripple-effect">
                        <i class="fas fa-edit me-2"></i> تعديل بيانات الإقامة
                    </a>
                    <a href="{{ route('admin.patient_admissions.index') }}" class="btn btn-secondary-custom btn-action-footer-show ripple-effect">
                        <i class="fas fa-list-alt me-2"></i> عرض كل سجلات الإقامة
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @parent
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifit-custom.js') }}"></script>
    <script>
        $(document).ready(function() {
            console.log("Patient admission details page loaded for Admission ID: {{ $patientAdmission->id }}");
            @if (session('success'))
                notif({ msg: "<i class='fas fa-check-circle me-2'></i> {{ session('success') }}", type: "success", position: "bottom", autohide: true, timeout: 5000 });
            @endif
            @if (session('error'))
                notif({ msg: "<i class='fas fa-exclamation-triangle me-2'></i> {{ session('error') }}", type: "error", position: "bottom", autohide: true, timeout: 7000 });
            @endif
        });
    </script>
@endsection
