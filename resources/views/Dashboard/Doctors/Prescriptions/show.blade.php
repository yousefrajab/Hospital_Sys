@extends('Dashboard.layouts.master') {{-- أو التخطيط الخاص بالطبيب --}}

@section('title')
    تفاصيل الوصفة الطبية رقم: {{ $prescription->prescription_number ?? 'غير محدد' }}
@endsection

@section('css')
    @parent
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="{{ URL::asset('dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    <style>
        :root {
            --bs-primary-rgb: 67, 97, 238;
            --bs-primary: rgb(var(--bs-primary-rgb));
            --bs-secondary-rgb: 108, 117, 125;
            --bs-success-rgb: 25, 135, 84;
            --bs-info-rgb: 13, 202, 240;
            --bs-warning-rgb: 255, 193, 7;
            --bs-danger-rgb: 220, 53, 69;
            --bs-light-rgb: 248, 249, 250;
            --bs-dark-rgb: 33, 37, 41;
            --bs-body-bg: #f4f6f9;
            --bs-border-color: #dee2e6;
            --bs-card-border-radius: 0.75rem;
            /* زوايا أكثر دائرية */
            --bs-card-box-shadow: 0 0.25rem 1rem rgba(0, 0, 0, 0.08);
            /* ظل أنعم */
            --bs-font-sans-serif: 'Tajawal', sans-serif;
            --bs-body-color: #495057;
            /* لون نص أغمق قليلاً */
        }

        body {
            font-family: var(--bs-font-sans-serif);
            background-color: var(--bs-body-bg);
            color: var(--bs-body-color);
            line-height: 1.7;
        }

        .prescription-details-container {
            max-width: 900px;
            /* عرض مناسب للتفاصيل */
            margin: 2rem auto;
        }

        .prescription-card {
            background-color: #fff;
            border-radius: var(--bs-card-border-radius);
            box-shadow: var(--bs-card-box-shadow);
            border: 1px solid var(--bs-border-color);
            margin-bottom: 2rem;
            overflow: hidden;
            /* لضمان تطبيق الزوايا على الهيدر */
        }

        .prescription-header {
            background: linear-gradient(135deg, rgba(var(--bs-primary-rgb), 0.95), rgba(var(--bs-primary-rgb), 0.8));
            color: white;
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .prescription-header h4 {
            margin-bottom: 0.25rem;
            font-size: 1.6rem;
            font-weight: 600;
        }

        .prescription-header p {
            margin-bottom: 0;
            opacity: 0.85;
            font-size: 0.9rem;
        }

        .prescription-header .status-badge-header {
            font-size: 0.9rem;
            padding: 0.5em 1em;
            border-radius: 50px;
            background-color: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .info-block {
            background-color: rgb(var(--bs-light-rgb));
            padding: 1.25rem;
            border-radius: var(--bs-card-border-radius);
            border: 1px solid var(--bs-border-color);
        }

        .info-block h6 {
            color: var(--bs-primary);
            margin-bottom: 0.75rem;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .info-block h6 i {
            margin-left: 0.5rem;
            /* RTL */
            font-size: 1.1em;
            opacity: 0.8;
        }

        .info-block p {
            margin-bottom: 0.4rem;
            font-size: 0.95rem;
        }

        .info-block strong {
            color: var(--bs-dark-rgb);
        }

        .info-block .patient-avatar-display {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            margin-left: 10px;
            border: 2px solid var(--bs-border-color);
        }

        .info-block .doctor-avatar-display {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            margin-left: 10px;
            border: 2px solid var(--bs-border-color);
        }

        .medications-table th {
            background-color: rgba(var(--bs-primary-rgb), 0.07);
            color: var(--bs-primary);
            font-weight: 600;
            font-size: 0.9rem;
        }

        .medications-table td {
            font-size: 0.9rem;
            vertical-align: top;
            /* لمحاذاة التعليمات بشكل أفضل */
        }

        .medications-table .dosage-details {
            font-weight: 500;
            color: var(--bs-dark-rgb);
        }

        .medications-table .instructions {
            font-size: 0.85rem;
            color: #6c757d;
            padding-left: 1.5rem;
            /* RTL */
        }

        .notes-section {
            margin-top: 1.5rem;
        }

        .notes-section h6 {
            font-weight: 600;
            color: var(--bs-primary);
        }

        .notes-section p {
            background-color: #f8f9fa;
            border: 1px solid #eee;
            padding: 0.75rem;
            border-radius: 0.4rem;
            font-size: 0.9rem;
            white-space: pre-wrap;
        }

        .actions-footer {
            padding: 1.5rem 2rem;
            background-color: var(--bs-card-cap-bg);
            border-top: 1px solid var(--bs-border-color);
            text-align: left;
            /* RTL */
        }

        .actions-footer .btn {
            font-weight: 500;
            padding: 0.6rem 1.2rem;
        }

        .status-badge-sm {
            /* لتناسق الشارات */
            padding: 0.35em 0.7em;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 500;
            letter-spacing: 0.3px;
        }

        .status-new {
            background-color: rgba(var(--bs-info-rgb), 0.15);
            color: rgb(var(--bs-info-rgb));
        }

        .status-approved {
            background-color: rgba(var(--bs-primary-rgb), 0.15);
            color: var(--bs-primary);
        }

        .status-dispensed {
            background-color: rgba(var(--bs-success-rgb), 0.15);
            color: rgb(var(--bs-success-rgb));
        }

        .status-partially_dispensed {
            background-color: rgba(var(--bs-warning-rgb), 0.2);
            color: #a17d06;
        }

        .status-cancelled_by_doctor,
        .status-cancelled_by_pharmacist,
        .status-cancelled_by_patient {
            background-color: rgba(var(--bs-danger-rgb), 0.1);
            color: rgb(var(--bs-danger-rgb));
        }

        .status-on_hold {
            background-color: rgba(var(--bs-secondary-rgb), 0.15);
            color: rgb(var(--bs-secondary-rgb));
        }

        .status-expired {
            background-color: #f8d7da;
            color: #721c24;
        }

        @media print {

            /* أنماط للطباعة */
            body {
                background-color: #fff;
                font-size: 10pt;
            }

            .breadcrumb-header,
            .actions-footer,
            .main-header,
            .app-sidebar,
            .main-footer {
                display: none !important;
            }

            .prescription-form-container {
                margin: 0;
                box-shadow: none;
                border: none;
                max-width: 100%;
                padding: 0;
            }

            .prescription-card {
                box-shadow: none;
                border: 1px solid #ccc;
                margin-bottom: 1rem;
            }

            .prescription-header {
                background: #eee !important;
                color: #333 !important;
                padding: 1rem;
                border-bottom: 1px solid #ccc;
            }

            .prescription-header h4 {
                font-size: 1.3rem;
            }

            .prescription-header .status-badge-header {
                background-color: transparent;
                border: 1px solid #777;
                color: #333;
            }

            .info-block {
                background-color: #f9f9f9;
                border: 1px solid #ddd;
                margin-bottom: 1rem;
            }

            .info-block h6 {
                color: #333;
            }

            .medications-table th {
                background-color: #f0f0f0 !important;
                color: #333 !important;
            }

            .medications-table td,
            .medications-table th {
                font-size: 9pt;
                padding: 0.5rem;
            }

            .notes-section p {
                background-color: #f9f9f9;
                border: 1px solid #eee;
            }

            a[href]:after {
                content: none !important;
            }

            /* إخفاء الروابط عند الطباعة */
            .no-print {
                display: none !important;
            }
        }
    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between no-print">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto"><i class="fas fa-file-medical-alt text-primary me-2"></i>الوصفات
                    الطبية</h4>
                <span class="text-muted mt-1 tx-13 mx-2">/</span>
                <span class="text-muted mt-1 tx-13">تفاصيل الوصفة رقم: {{ $prescription->prescription_number }}</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <a href="{{ route('doctor.prescriptions.index') }}" class="btn btn-outline-secondary btn-sm ripple-effect me-2">
                <i class="fas fa-list-ul me-1"></i> عرض كل الوصفات
            </a>
            <button onclick="window.print();" class="btn btn-info btn-sm ripple-effect me-2">
                <i class="fas fa-print me-1"></i> طباعة الوصفة
            </button>
        </div>
    </div>
@endsection

@section('content')
    @include('Dashboard.messages_alert')

    <div class="prescription-details-container" data-aos="fade-in">
        <div class="prescription-card">
            <div class="prescription-header">
                <div>
                    <h4>وصفة طبية رقم: {{ $prescription->prescription_number }}</h4>
                    <p>تاريخ الإنشاء: {{ $prescription->prescription_date->translatedFormat('l، j F Y') }}</p>
                </div>
                <div>
                    @php
                        $statusKeyHeader = $prescription->status;
                        $statusTextHeader = '';
                        if (
                            class_exists(\App\Models\Prescription::class) &&
                            method_exists(\App\Models\Prescription::class, 'getStatusesForFilter')
                        ) {
                            $statusTextHeader =
                                \App\Models\Prescription::getStatusesForFilter()[$statusKeyHeader] ??
                                ucfirst(str_replace('_', ' ', $statusKeyHeader));
                        } else {
                            $statusTextHeader = ucfirst(str_replace('_', ' ', $statusKeyHeader));
                        }
                        $statusBadgeClassHeader = 'status-' . str_replace('_', '-', $statusKeyHeader);
                    @endphp
                    <span class="status-badge-header {{ $statusBadgeClassHeader }}"
                        style="color: white">{{ $statusTextHeader }}</span>
                </div>
            </div>

            <div class="card-body p-4">
                <div class="info-grid">
                    {{-- معلومات المريض --}}
                    <div class="info-block">
                        <h6><i class="fas fa-user-injured"></i>معلومات المريض</h6>
                        @if ($prescription->patient)
                            <div class="d-flex align-items-center mb-2">
                                <img src="{{ $prescription->patient->image ? asset('Dashboard/img/patients/' . $prescription->patient->image->filename) : URL::asset('Dashboard/img/default_patient_avatar.png') }}"
                                    alt="{{ $prescription->patient->name }}" class="patient-avatar-display">
                                <div>
                                    <p class="mb-0"><strong>الاسم:</strong> {{ $prescription->patient->name }}</p>
                                    <p class="mb-0"><small class="text-muted">رقم الهوية:
                                            {{ $prescription->patient->national_id }}</small></p>
                                </div>
                            </div>
                            <p><i class="fas fa-phone fa-fw me-2 text-muted"></i>{{ $prescription->patient->Phone ?: '-' }}
                            </p>
                            <p><i
                                    class="fas fa-birthday-cake fa-fw me-2 text-muted"></i>{{ $prescription->patient->Date_Birth ? $prescription->patient->Date_Birth->translatedFormat('j M Y') . ' (' . $prescription->patient->Date_Birth->age . ' سنة)' : '-' }}
                            </p>
                        @else
                            <p class="text-muted"><em>معلومات المريض غير متوفرة أو تم حذفه.</em></p>
                        @endif
                    </div>

                    {{-- معلومات الطبيب --}}
                    <div class="info-block">
                        <h6><i class="fas fa-user-md"></i>معلومات الطبيب</h6>
                        @if ($prescription->doctor)
                            <div class="d-flex align-items-center mb-2">
                                <img src="{{ $prescription->doctor->image ? asset('Dashboard/img/doctors/' . $prescription->doctor->image->filename) : URL::asset('Dashboard/img/doctor_default.png') }}"
                                    alt="{{ $prescription->doctor->name }}" class="doctor-avatar-display">
                                <div>
                                    <p class="mb-0"><strong>الاسم:</strong> {{ $prescription->doctor->name }}</p>
                                    <p class="mb-0"><small class="text-muted">القسم:
                                            {{ $prescription->doctor->section->name ?? '-' }}</small></p>
                                </div>
                            </div>
                            <p><i
                                    class="fas fa-envelope fa-fw me-2 text-muted"></i>{{ $prescription->doctor->email ?: '-' }}
                            </p>
                        @else
                            <p class="text-muted"><em>معلومات الطبيب غير متوفرة.</em></p>
                        @endif
                    </div>
                </div>

                {{-- تفاصيل إضافية للوصفة --}}
                @if ($prescription->is_chronic_prescription || $prescription->patient_admission_id)
                    <div class="info-grid" style="margin-top: -0.5rem;"> {{-- تقليل الهامش العلوي إذا كانت هناك معلومات إضافية --}}
                        @if ($prescription->is_chronic_prescription)
                            <div class="info-block bg-light border-warning">
                                <h6><i class="fas fa-history text-warning"></i>وصفة مزمنة</h6>
                                <p class="mb-0">هذه الوصفة مخصصة لمرض مزمن.</p>
                                @if ($prescription->next_refill_due_date)
                                    <p><strong>تاريخ إعادة الصرف التالي:</strong>
                                        {{ \Carbon\Carbon::parse($prescription->next_refill_due_date)->translatedFormat('j M Y') }}
                                    </p>
                                @endif
                            </div>
                        @endif
                        @if ($prescription->patientAdmission)
                            <div class="info-block bg-light border-info">
                                <h6><i class="fas fa-procedures text-info"></i>مرتبطة بإقامة رقم:
                                    {{ $prescription->patientAdmission->id }}</h6>
                                <p><strong>سبب الدخول:</strong>
                                    {{ Str::limit($prescription->patientAdmission->reason_for_admission, 50) ?: '-' }}</p>
                                <p><strong>تاريخ الدخول:</strong>
                                    {{ $prescription->patientAdmission->admission_date->translatedFormat('j M Y') }}</p>
                            </div>
                        @endif
                    </div>
                @endif


                <h5 class="form-section-title mt-4"><i class="fas fa-tablets"></i>الأدوية الموصوفة</h5>
                @if ($prescription->items->isNotEmpty())
                    <div class="table-responsive-custom">
                        <table class="table table-custom table-striped medications-table">
                            <thead>
                                <tr>
                                    <th>الدواء</th>
                                    <th class="text-center">الجرعة</th>
                                    <th class="text-center">التكرار</th>
                                    <th class="text-center">المدة</th>
                                    <th class="text-center">الكمية</th>
                                    {{-- <th>تعليمات</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($prescription->items as $item)
                                    <tr>
                                        <td>
                                            <strong>{{ $item->medication->name ?? 'دواء غير معروف' }}</strong>
                                            <small class="d-block text-muted">
                                                {{ $item->medication->strength ? $item->medication->strength . ' - ' : '' }}
                                                {{ $item->medication->dosage_form ? \App\Models\Medication::getCommonDosageForms()[$item->medication->dosage_form] ?? $item->medication->dosage_form : '' }}
                                            </small>
                                            @if ($item->instructions_for_patient)
                                                <p class="instructions mt-1 mb-0"><i
                                                        class="fas fa-info-circle fa-xs me-1 text-info"></i>{{ $item->instructions_for_patient }}
                                                </p>
                                            @endif
                                            @if ($item->is_prn)
                                                <span class="badge bg-light text-dark border mt-1">عند اللزوم</span>
                                            @endif
                                        </td>
                                        <td class="text-center dosage-details">{{ $item->dosage }}</td>
                                        <td class="text-center dosage-details">{{ $item->frequency }}</td>
                                        <td class="text-center">{{ $item->duration ?: '-' }}</td>
                                        <td class="text-center">{{ $item->quantity_prescribed ?: '-' }}</td>
                                        {{-- <td>{{ $item->instructions_for_patient ?: '-' }}</td> --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center my-3">لم يتم إضافة أي أدوية لهذه الوصفة.</p>
                @endif

                @if ($prescription->doctor_notes)
                    <div class="notes-section">
                        <h6><i class="fas fa-sticky-note"></i>ملاحظات الطبيب للصيدلي:</h6>
                        <p>{{ $prescription->doctor_notes }}</p>
                    </div>
                @endif

                @if ($prescription->pharmacy_notes)
                    <div class="notes-section">
                        <h6><i class="fas fa-prescription-bottle-alt"></i>ملاحظات الصيدلية:</h6>
                        <p>{{ $prescription->pharmacy_notes }}</p>
                    </div>
                @endif

                @if ($prescription->dispensed_at)
                    <div class="notes-section">
                        <h6><i class="fas fa-check-double text-success"></i> معلومات الصرف:</h6>
                        <p class="mb-1"><strong>تم الصرف بواسطة:</strong> @if ($prescription->dispensedByPharmacyEmployee) {{ $prescription->dispensedByPharmacyEmployee->name }}
                            @else
                                موظف غير معروف (ID: {{ $prescription->dispensed_by_pharmacy_employee_id }})
                            @endif
                        </p>
                        <p><strong>تاريخ ووقت الصرف:</strong> {{ $prescription->dispensed_at->translatedFormat('l، j F Y - H:i') }}</p>
                    </div>
                @endif

            </div> {{-- card-body --}}

            <div class="actions-footer no-print">
                @php
                    $editableStatusesFooter = [
                        \App\Models\Prescription::STATUS_NEW,
                        \App\Models\Prescription::STATUS_APPROVED,
                    ];
                    $cancellableStatusesFooter = array_merge($editableStatusesFooter, [
                        \App\Models\Prescription::STATUS_ON_HOLD,
                        \App\Models\Prescription::STATUS_PARTIALLY_DISPENSED,
                    ]);
                @endphp

                @if (in_array($prescription->status, $editableStatusesFooter) &&
                        Auth::guard('doctor')->id() === $prescription->doctor_id)
                    <a href="{{ route('doctor.prescriptions.edit', $prescription->id) }}" class="btn btn-primary ripple">
                        <i class="fas fa-edit me-1"></i> تعديل الوصفة
                    </a>
                @endif

                @if (in_array($prescription->status, $cancellableStatusesFooter) &&
                        Auth::guard('doctor')->id() === $prescription->doctor_id)
                    <form action="{{ route('doctor.prescriptions.destroy', $prescription->id) }}" method="POST"
                        class="d-inline" onsubmit="return confirm('هل أنت متأكد من رغبتك في إلغاء هذه الوصفة؟');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger ripple">
                            <i class="fas fa-times-circle me-1"></i> إلغاء الوصفة
                        </button>
                    </form>
                @endif
                {{-- يمكنك إضافة زر للصيدلي لتأكيد الصرف هنا إذا كانت هذه الصفحة مشتركة --}}
            </div>

        </div>
    </div>
@endsection

@section('js')
    @parent
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="{{ URL::asset('dashboard/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('dashboard/plugins/notify/js/notifit-custom.js') }}"></script>
    <script>
        $(document).ready(function() {
            AOS.init({
                duration: 700,
                easing: 'ease-out-cubic',
                once: true
            });

            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })

            @if (session('success'))
                notif({
                    msg: `<div class="d-flex align-items-center p-2"><i class='fas fa-check-circle fa-lg me-2 text-success'></i><div style='font-size: 0.95rem;'>{{ session('success') }}</div></div>`,
                    type: "success",
                    position: "top-center",
                    autohide: true,
                    timeout: 5000,
                    zindex: 99999
                });
            @endif
            @if (session('error'))
                notif({
                    msg: `<div class="d-flex align-items-center p-2"><i class='fas fa-exclamation-triangle fa-lg me-2 text-danger'></i><div style='font-size: 0.95rem;'>{{ session('error') }}</div></div>`,
                    type: "error",
                    position: "top-center",
                    autohide: true,
                    timeout: 7000,
                    zindex: 99999
                });
            @endif
        });
    </script>
@endsection
