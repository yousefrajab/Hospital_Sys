@extends('Dashboard.layouts.master')

@section('title')
    تفاصيل سجل الدخول للمريض: {{ $patientAdmission->patient->name ?? 'غير معروف' }} (رقم: {{ $patientAdmission->id }})
@endsection

@section('css')
    <!-- يمكنك إضافة أي CSS مخصص هنا إذا احتجت -->
    <style>
        .info-label {
            font-weight: bold;
            color: #555;
        }

        .info-value {
            color: #333;
        }

        .patient-img-container,
        .doctor-img-container {
            width: 100px;
            height: 100px;
            overflow: hidden;
            border-radius: 50%;
            margin-bottom: 15px;
            border: 2px solid #eee;
        }

        .patient-img-container img,
        .doctor-img-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .card-custom-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            padding: 0.75rem 1.25rem;
            font-weight: bold;
        }
    </style>
@endsection
@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <i class="fas fa-clipboard-user fa-lg me-2" style="color: var(--admin-primary);"></i>
                <div>
                    <h4 class="content-title mb-0 my-auto">سجلات الدخول</h4>
                    <span class="text-muted mt-0 tx-13">/ تفاصيل إقامة المريض: {{ $patientAdmission->patient->name }}</span>
                </div>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            @if ($patientAdmission->status == \App\Models\PatientAdmission::STATUS_ADMITTED && !$patientAdmission->discharge_date)
                <a href="{{ route('admin.patient_admissions.edit', $patientAdmission->id) }}"
                    class="btn btn-outline-primary btn-sm me-2" style="border-radius: var(--admin-radius-md);">
                    <i class="fas fa-edit me-1"></i> تعديل بيانات الدخول
                </a>
                {{-- زر تسجيل الخروج يمكن أن يفتح مودال أو يوجه لصفحة تسجيل خروج --}}
                <button type="button" class="btn btn-lg btn-warning"
                    data-toggle="modal"
                    data-target="#dischargePatientModal{{ $patientAdmission->id }}">
                <i class="fas fa-user-check me-1"></i> تسجيل خروج المريض
            </button>
            @endif
            <a href="{{ route('admin.patient_admissions.index') }}" class="btn btn-outline-secondary btn-sm"
                style="border-radius: var(--admin-radius-md);">
                <i class="fas fa-arrow-left me-1"></i> العودة للقائمة
            </a>
        </div>
    </div>
    @include('Dashboard.PatientAdmissions.modals._discharge_modal', ['admission' => $patientAdmission])

@endsection

@section('content')
    <div class="container-fluid">
        <!-- Page-Title -->
        <div class="row">
            <div class="col-sm-12">


                <h2 class="page-title">تفاصيل سجل الدخول للمريض: {{ $patientAdmission->patient->name ?? 'غير معروف' }}
                </h2>

            </div><!--end col-->

        </div><!--end row-->
        <!-- end page title end breadcrumb -->

        <div class="row">

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h4 class="card-title">معلومات سجل الدخول (ID: {{ $patientAdmission->id }})</h4>

                            </div><!--end col-->
                            @if (
                                $patientAdmission->status === \App\Models\PatientAdmission::STATUS_ADMITTED &&
                                    is_null($patientAdmission->discharge_date))
                                <div class="row mt-4">
                                    <div class="col-12 text-center">
                                        <a href="{{ route('admin.patient_admissions.edit', $patientAdmission->id) }}?action=discharge"
                                            class="btn btn-lg btn-warning">
                                            <i class="fas fa-user-check"></i> الانتقال لتسجيل خروج المريض
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div> <!--end row-->

                    </div><!--end card-header-->

                    <div class="card-body">
                        <div class="row">
                            <!-- معلومات المريض -->
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100">
                                    <div class="card-custom-header">
                                        <i class="fas fa-user-injured mr-2"></i>معلومات المريض
                                    </div>
                                    <div class="card-body text-center">
                                        @if ($patientAdmission->patient)
                                            <div class="patient-img-container mx-auto">
                                                <img src="{{ $patientAdmission->patient->image ? asset('Dashboard/img/patients/' . $patientAdmission->patient->image->filename) : asset('Dashboard/img/doctor_default.png') }}"
                                                    alt="{{ $patientAdmission->patient->name }}" class="patient-avatar-sm">
                                            </div>
                                            <h5 class="card-title mt-0">{{ $patientAdmission->patient->name }}</h5>
                                            <p class="card-text mb-1"><span class="info-label">رقم الهوية:</span>
                                                {{ $patientAdmission->patient->national_id ?? '-' }}</p>
                                            <p class="card-text mb-1"><span class="info-label">تاريخ الميلاد:</span>
                                                {{ $patientAdmission->patient->Date_Birth ? $patientAdmission->patient->Date_Birth->format('Y-m-d') : '-' }}
                                            </p>
                                            <p class="card-text mb-1"><span class="info-label">الجنس:</span>
                                                @if ($patientAdmission->patient->Gender == 1)
                                                    ذكر
                                                @elseif($patientAdmission->patient->Gender == 2)
                                                    أنثى
                                                @else
                                                    -
                                                @endif
                                            </p>
                                            <p class="card-text mb-1"><span class="info-label">الهاتف:</span>
                                                {{ $patientAdmission->patient->Phone ?? '-' }}</p>
                                            <a href="{{ route('admin.Patients.show', $patientAdmission->patient_id) }}"
                                                class="btn btn-sm btn-info mt-2">عرض ملف المريض الكامل</a>
                                        @else
                                            <p class="text-muted">لا توجد بيانات للمريض.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- معلومات الدخول -->
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100">
                                    <div class="card-custom-header">
                                        <i class="fas fa-procedures mr-2"></i> معلومات الدخول
                                    </div>
                                    <div class="card-body">
                                        <p><span class="info-label">تاريخ ووقت الدخول:</span> <br><span
                                                class="info-value">{{ $patientAdmission->admission_date->format('Y-m-d H:i A') }}</span>
                                        </p>
                                        <p><span class="info-label">سبب الدخول:</span> <br><span
                                                class="info-value">{{ $patientAdmission->reason_for_admission ?? '-' }}</span>
                                        </p>
                                        <p><span class="info-label">التشخيص عند الدخول:</span> <br><span
                                                class="info-value">{{ $patientAdmission->admitting_diagnosis ?? '-' }}</span>
                                        </p>
                                        <p><span class="info-label">الحالة الحالية للسجل:</span> <br><span
                                                class="badge badge-info p-2">{{ $statusDisplay }}</span></p>
                                    </div>
                                </div>
                            </div>

                            <!-- معلومات الطبيب المعالج -->
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card h-100">
                                    <div class="card-custom-header">
                                        <i class="fas fa-user-md mr-2"></i> الطبيب المعالج
                                    </div>
                                    <div class="card-body text-center">
                                        @if ($patientAdmission->doctor)
                                            <div class="doctor-img-container mx-auto">
                                                @if ($patientAdmission->doctor->image)
                                                    <img src="{{ $patientAdmission->doctor->image ? asset('Dashboard/img/doctors/' . $patientAdmission->doctor->image->filename) : asset('Dashboard/img/doctor_default.png') }}"
                                                        alt="{{ $patientAdmission->doctor->name }}"
                                                        class="patient-avatar-sm">
                                                @else
                                                    <img src="{{ asset('dashboard/assets/images/users/default-avatar.png') }}"
                                                        alt="صورة افتراضية" class="rounded-circle">
                                                @endif
                                            </div>
                                            <h5 class="card-title mt-0">{{ $patientAdmission->doctor->name }}</h5>
                                            <p class="card-text mb-1"><span class="info-label">القسم:</span>
                                                {{ $patientAdmission->doctor->section->name ?? '-' }}</p>
                                            {{-- يمكنك إضافة المزيد من تفاصيل الطبيب إذا أردت --}}
                                        @else
                                            <p class="text-muted">لم يتم تحديد طبيب.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <!-- معلومات السرير والغرفة والقسم -->
                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-custom-header">
                                        <i class="fas fa-bed mr-2"></i> السرير والغرفة والقسم
                                    </div>
                                    <div class="card-body">
                                        @if ($patientAdmission->bed)
                                            <p><span class="info-label">السرير:</span> <span
                                                    class="info-value">{{ $patientAdmission->bed->bed_number }}</span></p>
                                            @if ($patientAdmission->bed->room)
                                                <p><span class="info-label">الغرفة:</span> <span
                                                        class="info-value">{{ $patientAdmission->bed->room->room_number }}</span>
                                                </p>
                                                @if ($patientAdmission->bed->room->section)
                                                    <p><span class="info-label">القسم (للغرفة):</span> <span
                                                            class="info-value">{{ $patientAdmission->bed->room->section->name }}</span>
                                                    </p>
                                                @else
                                                    <p><span class="info-label">القسم (للغرفة):</span> <span
                                                            class="info-value">-</span></p>
                                                @endif
                                            @else
                                                <p><span class="info-label">الغرفة:</span> <span class="info-value">غير
                                                        محددة للسرير</span></p>
                                            @endif
                                        @else
                                            <p class="text-muted">لم يتم تخصيص سرير لهذا الدخول بعد.</p>
                                        @endif

                                        @if ($patientAdmission->section && !$patientAdmission->bed?->room?->section)
                                            <p><span class="info-label">القسم (للتسجيل المبدئي):</span> <span
                                                    class="info-value">{{ $patientAdmission->section->name }}</span></p>
                                        @elseif(!$patientAdmission->bed && !$patientAdmission->section)
                                            <p><span class="info-label">القسم:</span> <span class="info-value">غير
                                                    محدد</span></p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- معلومات الخروج (إذا تمت) -->
                            @if ($patientAdmission->discharge_date || $patientAdmission->status === \App\Models\PatientAdmission::STATUS_DISCHARGED)
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100 bg-light">
                                        <div class="card-custom-header">
                                            <i class="fas fa-sign-out-alt mr-2"></i> معلومات الخروج
                                        </div>
                                        <div class="card-body">
                                            @if ($patientAdmission->discharge_date)
                                                <p><span class="info-label">تاريخ ووقت الخروج:</span> <br><span
                                                        class="info-value">{{ $patientAdmission->discharge_date->format('Y-m-d H:i A') }}</span>
                                                </p>
                                            @endif
                                            <p><span class="info-label">سبب الخروج:</span> <br><span
                                                    class="info-value">{{ $patientAdmission->discharge_reason ?? '-' }}</span>
                                            </p>
                                            <p><span class="info-label">التشخيص عند الخروج:</span> <br><span
                                                    class="info-value">{{ $patientAdmission->discharge_diagnosis ?? '-' }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <hr>
                        <!-- ملاحظات إضافية -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-custom-header">
                                        <i class="fas fa-sticky-note mr-2"></i> ملاحظات إضافية
                                    </div>
                                    <div class="card-body">
                                        @if ($patientAdmission->notes)
                                            <p class="info-value">{{ $patientAdmission->notes }}</p>
                                        @else
                                            <p class="text-muted">لا توجد ملاحظات مسجلة.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>



                    </div><!--end card-body-->
                </div><!--end card-->
            </div><!--end col-->
        </div><!--end row-->

    </div><!-- container -->

    {{-- يمكنك إضافة أي مودالات هنا، مثل مودال تسجيل الخروج --}}
    {{-- @include('Dashboard.PatientAdmissions.modals.discharge_modal') --}}

@endsection

@section('js')
    <!-- يمكنك إضافة أي JS مخصص هنا إذا احتجت -->
@endsection
