@extends('Dashboard.layouts.master') {{-- أو الـ layout الخاص بلوحة تحكم المريض --}}

@section('title', 'مواعيـدي السابقة')

@section('css')
    @parent
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- Animate.css --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    {{-- NotifIt CSS --}}
    <link href="{{ URL::asset('Dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />

    <style>
        /* --- المتغيرات الأساسية (Globals & Dark Mode) --- */
        :root {
            --primary-color: #4A90E2;
            --primary-dark: #3A7BC8;
            --secondary-color: #4A4A4A;
            --accent-color: #50E3C2;
            --light-bg: #f9fbfd;
            --border-color: #e5e9f2;
            --white-color: #ffffff;
            --success-color: #2ecc71;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --info-color: #3498db;
            --text-dark: #34495e;
            --text-muted: #95a5a6;
            --text-completed: #6c757d; /* لون للحالة المنتهية */
            --text-cancelled: var(--danger-color); /* لون للحالة الملغاة */
            --card-shadow: 0 8px 25px rgba(140, 152, 164, 0.1);
            --admin-radius-md: 0.5rem; /* 8px */
            --admin-radius-lg: 0.75rem; /* 12px */
            --admin-transition: all 0.3s ease-in-out;
            --success-color-rgb: 46, 204, 113;
            --warning-color-rgb: 243, 156, 18;
            --danger-color-rgb: 231, 76, 60;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --light-bg: #1a1d24; --white-color: #242930; --border-color: #374151;
                --text-dark: #e9ecef; --text-muted: #adb5bd; --text-completed: #95a5a6;
                --primary-color: #5c9bff; --accent-color: #67e8f9;
            }
            .past-appointments-timeline::before { background-color: var(--border-color); }
            .timeline-icon-wrapper { background-color: var(--light-bg); border-color: var(--border-color); }
            .appointment-card-past { background-color: var(--white-color); border-color:var(--border-color); }
            .appointment-card-header-past { border-bottom-color: var(--border-color); }
        }
        .dark body { /* ... (نفس متغيرات prefers-color-scheme: dark) ... */ }

        body { background: var(--light-bg); font-family: 'Tajawal', sans-serif; color: var(--text-dark); }

        .appointments-page-container { padding: 1.5rem; max-width: 900px; /* عرض مناسب للخط الزمني */ margin: auto; }
        .page-header-flex { /* ... (نفس صفحة upcoming) ... */ }
        .page-title-flex { /* ... (نفس صفحة upcoming) ... */ }
        .page-actions .btn { /* ... (نفس صفحة upcoming) ... */ }

        /* --- تصميم الخط الزمني --- */
        .past-appointments-timeline {
            position: relative;
            padding: 1rem 0;
            margin-right: 20px; /* مساحة للخط والأيقونات في RTL */
        }
        [dir="ltr"] .past-appointments-timeline {
            margin-right: 0;
            margin-left: 20px;
        }

        .past-appointments-timeline::before {
            content: '';
            position: absolute;
            top: 0;
            right: 19px; /* (40px / 2) - (3px / 2) = 20px - 1.5px = 18.5px, rounded to 19px */
            /* left: 19px; for LTR */
            width: 3px;
            height: 100%;
            background-color: var(--border-color);
            border-radius: 2px;
            z-index: 0; /* خلف البطاقات */
        }
        [dir="ltr"] .past-appointments-timeline::before {
            right: auto;
            left: 19px;
        }


        .appointment-card-past {
            background: var(--white-color);
            border-radius: var(--admin-radius-lg);
            box-shadow: var(--card-shadow);
            margin-bottom: 2rem; /* مسافة أكبر بين عناصر الخط الزمني */
            padding: 1.25rem 1.5rem;
            position: relative; /* ضروري لـ z-index الأيقونة */
            border: 1px solid var(--border-color);
            opacity: 0.9;
            transition: var(--admin-transition);
            margin-right: 30px; /* مساحة إضافية لليمين (لـ RTL) لعدم التداخل مع أيقونة الخط */
             /* تم إزالة border-left من هنا، سيعتمد على حالة الأيقونة */
        }
        [dir="ltr"] .appointment-card-past {
            margin-right: 0;
            margin-left: 30px;
        }

        .appointment-card-past:hover {
            opacity: 1;
            transform: scale(1.015); /* تأثير hover أنعم */
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-color: var(--primary-color); /* تمييز عند التحويم */
        }

        .timeline-icon-wrapper {
            position: absolute;
            top: 1.5rem; /* ليتناسب مع بداية محتوى البطاقة */
            right: -20px; /* لـ RTL: (40px/2) = 20px يسار بداية البطاقة (التي هي 0) */
            /* left: -20px;  لـ LTR */
            width: 40px;
            height: 40px;
            background-color: var(--light-bg);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid var(--border-color); /* إطار يتناسب مع الخط */
            z-index: 2; /* فوق الخط العمودي */
            box-shadow: 0 0 0 4px var(--light-bg); /* لإخفاء الخط العمودي خلف الدائرة */
        }
        [dir="ltr"] .timeline-icon-wrapper {
             right: auto;
             left: -20px;
        }
        @media (prefers-color-scheme: dark) {
            .timeline-icon-wrapper { background-color: var(--admin-bg); border-color: var(--admin-border-color); box-shadow: 0 0 0 4px var(--admin-bg); }
        }


        .timeline-icon-wrapper i {
            font-size: 1.1rem;
            color: var(--text-muted); /* لون افتراضي */
        }
        /* تلوين أيقونة الخط الزمني بناءً على حالة الموعد */
        .appointment-card-past[data-status-timeline="completed"] .timeline-icon-wrapper { border-color: var(--success-color); }
        .appointment-card-past[data-status-timeline="completed"] .timeline-icon-wrapper i { color: var(--success-color); }

        .appointment-card-past[data-status-timeline="cancelled"] .timeline-icon-wrapper { border-color: var(--danger-color); }
        .appointment-card-past[data-status-timeline="cancelled"] .timeline-icon-wrapper i { color: var(--danger-color); }

        .appointment-card-past[data-status-timeline="no_show"] .timeline-icon-wrapper { border-color: var(--warning-color); }
        .appointment-card-past[data-status-timeline="no_show"] .timeline-icon-wrapper i { color: var(--warning-color); }


        .appointment-card-header-past {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.75rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px dashed #e0e0e0;
        }
        @media (prefers-color-scheme: dark) { .appointment-card-header-past { border-bottom-color: #4a4a4a; } }


        .appointment-title-past { font-size: 1.1rem; font-weight: 600; color: var(--text-dark); }
        .appointment-date-past { font-size: 0.9rem; color: var(--text-muted); }

        .appointment-details-list-past { list-style: none; padding: 0; margin: 0; }
        .appointment-details-list-past li { margin-bottom: 0.5rem; font-size: 0.9rem; color: var(--text-muted); display: flex; align-items: flex-start; }
        .appointment-details-list-past li i { width: 20px; text-align: center; margin-left: 8px; color: var(--primary-color); opacity: 0.8; padding-top: 2px;}
        .appointment-details-list-past li strong { font-weight: 500; color: var(--text-dark); }

        .appointment-status-past .badge { font-size: 0.8rem; padding: 0.4em 0.8em; }
        .badge-completed-past { background-color: rgba(var(--success-color-rgb), 0.1); color: var(--success-color); }
        .badge-cancelled-past { background-color: rgba(var(--danger-color-rgb), 0.1); color: var(--danger-color); }
        .badge-noshow-past { background-color: rgba(var(--warning-color-rgb), 0.1); color: var(--warning-color); }
        .badge-default-past { background-color: rgba(108, 117, 125, 0.1); color: #545b62; } /* رمادي للحالات الأخرى */


        .no-appointments-patient { /* ... (نفس صفحة upcoming) ... */ }
        .pagination-wrapper { /* ... (نفس صفحة upcoming) ... */ }
    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto"><i class="fas fa-history me-2 text-secondary"></i>مواعيـدي</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ المواعيد السابقة</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <a href="{{ route('appointments.upcoming') }}" class="btn btn-outline-success btn-sm me-2" style="border-radius:var(--admin-radius-md);">
                <i class="fas fa-calendar-check me-1"></i> المواعيد القادمة
            </a>
            @if(Route::has('appointments.book'))
            <a href="{{ route('appointments.book') }}" class="btn btn-primary btn-sm" style="border-radius:var(--admin-radius-md);">
                <i class="fas fa-plus-circle me-1"></i> طلب موعد جديد
            </a>
            @endif
        </div>
    </div>
@endsection

@section('content')
    @include('Dashboard.messages_alert')

    <div class="appointments-page-container">
        <div class="page-header-flex animate__animated animate__fadeInDown">
            <h1 class="page-title-flex"><i class="fas fa-archive"></i> سجل مواعيدك السابقة</h1>
            {{-- يمكنك إضافة فلاتر هنا (مثلاً، حسب السنة أو الشهر) --}}
        </div>

        @if(isset($appointments) && $appointments->isNotEmpty())
            <div class="past-appointments-timeline">
                @foreach ($appointments as $appointment)
                    @php
                        // تحديد حالة الخط الزمني لعكسها في data-attribute
                        $timelineStatus = match($appointment->type) {
                            \App\Models\Appointment::STATUS_COMPLETED => 'completed',
                            \App\Models\Appointment::STATUS_CANCELLED => 'cancelled',
                            // \App\Models\Appointment::STATUS_NO_SHOW => 'no_show', // إذا أضفت هذه الحالة
                            default => 'other'
                        };
                    @endphp
                    <div class="appointment-card-past animate__animated animate__fadeInRight"
                         data-status-timeline="{{ $timelineStatus }}"
                         style="animation-delay: {{ $loop->index * 0.1 }}s;">

                        <div class="timeline-icon-wrapper">
                            @php
                                $timelineIconClass = match($appointment->type) {
                                    \App\Models\Appointment::STATUS_COMPLETED => 'fas fa-check-double',
                                    \App\Models\Appointment::STATUS_CANCELLED => 'fas fa-ban',
                                    // \App\Models\Appointment::STATUS_NO_SHOW => 'fas fa-calendar-times',
                                    default => 'far fa-calendar-alt'
                                };
                            @endphp
                            <i class="{{ $timelineIconClass }}"></i>
                        </div>

                        <div class="appointment-card-header-past">
                            <div>
                                <h5 class="appointment-title-past">
                                    @if($appointment->section)
                                        {{ $appointment->section->name }}
                                    @else
                                        موعد عام
                                    @endif
                                    {{-- إذا كان لديك حقل 'type' لنوع الموعد غير حالته --}}
                                    {{-- {{ $appointment->appointment_type_description ? ' - ' . $appointment->appointment_type_description : '' }} --}}
                                </h5>
                                <span class="appointment-date-past">
                                    {{ $appointment->appointment ? $appointment->appointment->translatedFormat('l، j F Y - h:i A') : 'تاريخ غير محدد' }}
                                </span>
                            </div>
                            <div class="appointment-status-past">
                                @php
                                    $statusTextPast = $appointment->status_display ?? $appointment->type; // استخدام type مباشرة إذا لم يكن status_display
                                    $statusBadgeClassPast = match($appointment->type) {
                                        \App\Models\Appointment::STATUS_COMPLETED => 'badge-completed-past',
                                        \App\Models\Appointment::STATUS_CANCELLED => 'badge-cancelled-past',
                                        // \App\Models\Appointment::STATUS_NO_SHOW => 'badge-noshow-past',
                                        default => 'badge-default-past'
                                    };
                                @endphp
                                <span class="badge {{ $statusBadgeClassPast }}">{{ $statusTextPast }}</span>
                            </div>
                        </div>
                        <div class="appointment-card-body-past">
                            <ul class="appointment-details-list-past">
                                @if($appointment->doctor)
                                <li><i class="fas fa-user-md"></i> <span class="detail-label">الطبيب:</span> <strong>{{ $appointment->doctor->name }}</strong></li>
                                @endif
                                @if($appointment->notes_by_staff)
                                    <li><i class="fas fa-clinic-medical"></i> <span class="detail-label">ملاحظات العيادة/الطبيب:</span> <strong>{{ Str::limit($appointment->notes_by_staff, 150) }}</strong></li>
                                @endif
                                @if($appointment->notes_by_patient && $appointment->type != \App\Models\Appointment::STATUS_CANCELLED)
                                    <li><i class="fas fa-sticky-note"></i> <span class="detail-label">ملاحظاتك (عند الحجز):</span> <strong>{{ Str::limit($appointment->notes_by_patient, 150) }}</strong></li>
                                @endif
                                {{-- <li><i class="fas fa-clock"></i> <span class="detail-label">المدة:</span> <strong>{{ $appointment->duration_minutes ?? 'غير محددة' }} دقيقة</strong></li> --}}
                            </ul>
                        </div>
                        {{-- لا يوجد فوتر أزرار عادة هنا، لكن يمكن إضافة زر لـ "طلب نفس الموعد" مثلاً --}}
                    </div>
                @endforeach
            </div>

            @if ($appointments->hasPages())
                <div class="pagination-wrapper mt-4">
                    {{ $appointments->links('pagination::bootstrap-5') }}
                </div>
            @endif
        @else
            <div class="no-appointments-patient text-center py-5">
                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                <p>لا توجد لديك مواعيد سابقة مسجلة.</p>
            </div>
        @endif
    </div>
@endsection

@section('js')
    @parent
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifit-custom.js') }}"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 700, // مدة أطول قليلاً للأنيميشن
            easing: 'ease-out-quad',
            once: true,
            offset: 50 // بدء الأنيميشن عند ظهور 50px من العنصر
        });

        $(document).ready(function() {
            console.log("Patient past appointments page loaded.");
            @if (session('success'))
                notif({ msg: "<i class='fas fa-check-circle me-2'></i> {{ session('success') }}", type: "success", position: "bottom", autohide: true, timeout: 5000 });
            @endif
            @if (session('error'))
                notif({ msg: "<i class='fas fa-exclamation-triangle me-2'></i> {{ session('error') }}", type: "error", position: "bottom", autohide: true, timeout: 7000 });
            @endif
        });
    </script>
@endsection
