<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>مواعيــدي - {{ $patient->name }} - {{ config('app.name', 'المنصة الطبية') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&family=Cairo:wght@400;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
        integrity="sha512-c42qTSw/wPZ3/5LBzD+Bw5f7bSF2oxou6wEb+I/lqeaKV5FDIfMvvRp772y4jcJLKuGUOpbJMdg/BTl50fJYAw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="{{ URL::asset('Dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    {{-- نفس الـ CSS من الرد السابق --}}
    <style>
        :root {
            --patient-primary: {{ $settings['patient_primary_color'] ?? '#007bff' }};
            --patient-primary-rgb: {{ $settings['patient_primary_color_rgb'] ?? '0, 123, 255' }};
            --patient-primary-dark: {{ $settings['patient_primary_dark_color'] ?? '#0056b3' }};
            --patient-primary-light: {{ $settings['patient_primary_light_color'] ?? '#cfe2ff' }};
            --patient-secondary: {{ $settings['patient_secondary_color'] ?? '#6c757d' }};
            --patient-success: #198754;
            --patient-warning: #ffc107;
            --patient-danger: #dc3545;
            --patient-info: #0dcaf0;
            --patient-text-dark: #212529;
            --patient-text-light: #6c757d;
            --patient-bg: #f8f9fa;
            --patient-card-bg: #ffffff;
            --patient-border-color: #dee2e6;
            --patient-card-radius: 0.75rem;
            --patient-card-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.075);
            --patient-transition: all 0.3s ease-in-out;
            --patient-font-family: 'Tajawal', sans-serif;
        }
        html, body { margin: 0; padding: 0; box-sizing: border-box; font-family: var(--patient-font-family); background-color: var(--patient-bg); color: var(--patient-text-dark); line-height: 1.7; font-size: 1rem; }
        *, *::before, *::after { box-sizing: inherit; }
        .standalone-page-header { background-color: var(--patient-card-bg); padding: 1rem 0; box-shadow: 0 2px 8px rgba(0,0,0,0.05); margin-bottom: 2.5rem; }
        .standalone-page-header .container-fluid { max-width: 1200px; margin: 0 auto; display: flex; justify-content: space-between; align-items: center; padding-left:1.5rem; padding-right:1.5rem;}
        .standalone-page-header .site-branding .site-title { font-family: var(--patient-font-family); font-size: 1.6rem; color: var(--patient-primary); margin: 0; font-weight: 700; text-decoration:none; }
        .standalone-page-header .user-actions { display:flex; align-items:center; gap:1rem;}
        .standalone-page-header .user-actions .welcome-text { color: var(--patient-text-light); font-size: 0.9rem;}
        .standalone-page-header .user-actions a { color: var(--patient-primary); text-decoration: none; font-weight: 600; font-size: 0.95rem; transition: color 0.2s ease; }
        .standalone-page-header .user-actions a i { margin-inline-end: 0.4rem; }
        .standalone-page-header .user-actions a:hover { color: var(--patient-primary-dark); }
        .appointments-page-container { padding: 0 1.5rem 2.5rem; max-width: 1320px; margin: auto; }
        .page-header-custom { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid var(--patient-border-color); }
        .page-title-custom { font-size: 2rem; font-weight: 700; color: var(--patient-primary); display: flex; align-items: center; gap: 0.75rem; margin:0; }
        .page-title-custom i { font-size: 1.1em; }
        .page-actions .btn { font-size: 0.95rem; padding: 0.6rem 1.2rem; border-radius: var(--patient-card-radius); font-weight: 500; }
        .btn-primary-patient { background-color: var(--patient-primary); border-color: var(--patient-primary); color: white; }
        .btn-primary-patient:hover { background-color: var(--patient-primary-dark); border-color: var(--patient-primary-dark); }
        .appointment-tabs .nav-tabs { border-bottom: 2px solid var(--patient-border-color); margin-bottom: 2.5rem; }
        .appointment-tabs .nav-tabs .nav-item .nav-link { border: none; border-bottom: 3px solid transparent; padding: 0.8rem 1.75rem; font-size: 1.15rem; font-weight: 700; color: var(--patient-text-light); transition: all 0.25s ease; margin-bottom: -2px; border-top-left-radius: var(--patient-card-radius); border-top-right-radius: var(--patient-card-radius); }
        .appointment-tabs .nav-tabs .nav-item .nav-link.active,
        .appointment-tabs .nav-tabs .nav-item .nav-link:hover { color: var(--patient-primary); border-bottom-color: var(--patient-primary); background-color: color-mix(in srgb, var(--patient-primary) 5%, transparent); }
        .appointment-tabs .nav-tabs .nav-item .nav-link i { margin-inline-end: 0.6rem; font-size: 1em; }
        .appointment-tabs .nav-tabs .nav-item .nav-link .badge { font-size: 0.75em; vertical-align: middle; background-color: var(--patient-primary); color:white;}
        .appointment-tabs .nav-tabs .nav-item .nav-link.active .badge { background-color: var(--patient-secondary); color:white; }
        .appointment-tabs .tab-content .tab-pane { animation: fadeInSmooth 0.4s; }
        @keyframes fadeInSmooth { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .appointments-grid-patient { display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 2rem; }
        .appointment-card-wrapper { transition: var(--patient-transition); }
        .appointment-card-wrapper:hover { transform: translateY(-6px); }
        .appointment-card { background: var(--patient-card-bg); border-radius: var(--patient-card-radius); box-shadow: var(--patient-card-shadow); border: 1px solid var(--patient-border-color); display: flex; flex-direction: column; height: 100%; overflow: hidden; position: relative; }
        .appointment-card::before { content: ''; position: absolute; top: 0; right: 0; bottom: 0; width: 7px; background-color: var(--patient-info); border-radius: 0 var(--patient-card-radius) var(--patient-card-radius) 0; transition: background-color 0.3s; }
        .rtl .appointment-card::before { right:auto; left:0; border-radius: var(--patient-card-radius) 0 0 var(--patient-card-radius); }
        .appointment-card[data-status-key="confirmed"]::before { background-color: var(--patient-success); }
        .appointment-card[data-status-key="pending"]::before { background-color: var(--patient-warning); }
        .appointment-card[data-status-key^="cancel"]::before { background-color: var(--patient-danger); }
        .appointment-card[data-status-key="completed"]::before { background-color: var(--patient-secondary); }
        .appointment-card[data-status-key="lapsed"]::before { background-color: var(--patient-text-light); }
        .appointment-card-header-patient { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--patient-border-color); display: flex; justify-content: space-between; align-items: flex-start; }
        .appointment-datetime-patient { font-size: 1.25rem; font-weight:700; color: var(--patient-text-dark); display:block; margin-bottom:0.3rem;}
        .appointment-datetime-patient .time-part { font-size: 1rem; color: var(--patient-primary); font-weight: 600; display:block; }
        .appointment-datetime-patient .time-part i { opacity:0.8; margin-inline-end:0.3rem;}
        .appointment-status-badge { font-size: 0.75rem; font-weight: 600; padding: 0.4em 0.9em; border-radius: 50px; text-transform: capitalize; white-space: nowrap; }
        .status-badge.pending    { background-color: rgba(var(--patient-warning-rgb, 255, 193, 7), 0.15); color: #a17400; border: 1px solid rgba(var(--patient-warning-rgb, 255, 193, 7),0.3); }
        .status-badge.confirmed  { background-color: rgba(var(--patient-success-rgb, 25, 135, 84), 0.1); color: var(--patient-success); border: 1px solid rgba(var(--patient-success-rgb, 25, 135, 84),0.2); }
        .status-badge.completed  { background-color: #e2e3e5; color: #41464b; border: 1px solid #d3d6d8;}
        .status-badge.cancelled,
        .status-badge.cancelled-patient,
        .status-badge.cancelled-doctor { background-color: rgba(var(--patient-danger-rgb, 220, 53, 69), 0.1); color: var(--patient-danger); border: 1px solid rgba(var(--patient-danger-rgb, 220, 53, 69),0.2); }
        .status-badge.lapsed     { background-color: #fde8d7; color: #6c4a26; border: 1px solid #fbd9b0;}
        .appointment-card-body-patient { padding: 1.25rem 1.5rem; flex-grow: 1; }
        .appointment-details-list-patient { list-style: none; padding: 0; margin: 0; }
        .appointment-details-list-patient li { display: flex; align-items: flex-start; margin-bottom: 0.85rem; font-size: 0.95rem; color: var(--patient-text-dark); }
        .appointment-details-list-patient li i { width: 22px; min-width:22px; text-align: center; margin-inline-end: 0.85rem; color: var(--patient-primary); font-size: 1.05em; padding-top: 2px; }
        .appointment-details-list-patient li .detail-label { font-weight: 500; color: var(--patient-text-light); min-width:80px; }
        .appointment-details-list-patient li .detail-value { font-weight: 600; color: var(--patient-text-dark); }
        .appointment-details-list-patient li .detail-value.text-muted { color: var(--patient-text-light) !important; font-weight:normal;}
        .appointment-details-list-patient li.cancellation-reason-item .detail-value { font-size:0.9em; }
        .appointment-card-footer-patient { padding: 1rem 1.5rem; border-top: 1px solid var(--patient-border-color); background-color: #fdfdfd; text-align: left; }
        .btn-appointment-action { font-size: 0.85rem; padding: 0.5rem 1.1rem; border-radius: var(--patient-card-radius); font-weight: 500; }
        .btn-outline-danger-patient { color: var(--patient-danger); border-color: var(--patient-danger); } /* هذا الكلاس غير مستخدم الآن في الفورم */
        .btn-outline-danger-patient:hover { background-color: var(--patient-danger); color: white; }
        .appointment-card-footer-patient .form-control-sm { font-size:0.85rem; max-height: 70px; }
        .appointment-card-footer-patient .cancellation-info { font-size: 0.85rem; color: var(--patient-text-light); }
        .appointment-card-footer-patient .cancellation-info i { opacity: 0.8; }
        .appointment-card-footer-patient .btn-cancel-appointment-ajax { /* كلاس جديد لزر AJAX */
            background-color: var(--patient-danger); color: white;
            border: none; font-size: 0.875rem; padding: 0.5rem 1.1rem;
            border-radius: var(--patient-card-radius); transition: background-color 0.2s ease;
        }
        .appointment-card-footer-patient .btn-cancel-appointment-ajax:hover { background-color: color-mix(in srgb, var(--patient-danger) 80%, black); }


        .no-appointments-patient { text-align: center; padding: 4rem 2rem; background: var(--patient-card-bg); border-radius: var(--patient-card-radius); box-shadow: var(--patient-card-shadow); color: var(--patient-text-light); border: 1px dashed var(--patient-border-color); }
        .no-appointments-patient .empty-icon-display { font-size: 4rem; display: block; margin-bottom: 1.5rem; color: var(--patient-primary-light); }
        .no-appointments-patient h4 { font-size: 1.5rem; color: var(--patient-text-dark); margin-bottom: 0.5rem; }
        .no-appointments-patient p { font-size: 1rem; margin-bottom: 1.5rem;}
        .no-appointments-patient .btn-book-new-appointment { background-color: var(--patient-primary); color:white; padding: 0.7rem 1.75rem; font-size: 1.05rem; font-weight: 600; border-radius: var(--patient-card-radius); text-decoration: none; transition: background-color 0.2s ease; }
        .no-appointments-patient .btn-book-new-appointment:hover { background-color: var(--patient-primary-dark); }
        .no-appointments-patient .btn-book-new-appointment i { margin-inline-end: 0.5rem; }
        .pagination-wrapper { margin-top: 2.5rem; }
        .pagination .page-item .page-link { color: var(--patient-primary); border-radius: var(--patient-card-radius); margin: 0 0.2rem; padding: 0.55rem 0.9rem; }
        .pagination .page-item.active .page-link { background-color: var(--patient-primary); border-color: var(--patient-primary); color: white; box-shadow: 0 2px 5px rgba(var(--patient-primary-rgb),0.2); }
        .pagination .page-item.disabled .page-link { color: var(--patient-text-light); }
        .pagination .page-item:not(.active) .page-link:hover { background-color:color-mix(in srgb, var(--patient-primary) 10%, transparent); }
        .standalone-page-footer { text-align: center; padding: 1.75rem 0; margin-top: 3rem; background-color: var(--patient-card-bg); border-top: 1px solid var(--patient-border-color); font-size: 0.9rem; color: var(--patient-text-light); }
        .notifit_container { font-family: var(--patient-font-family) !important; z-index: 99999 !important; }
        .notifit_notification { border-radius: var(--patient-card-radius) !important; box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important; padding: 1rem !important; }
        .notifit_notification .media-body { font-size: 1rem !important; }
        .notifit_notification i.fa-lg { font-size: 1.5rem !important; }
    </style>
</head>

<body>
    <header class="standalone-page-header">
        {{-- ... نفس الهيدر من الرد السابق ... --}}
        <div class="container-fluid">
            <div class="site-branding">
                <a href="{{ route('home') }}" class="site-title" title="العودة إلى الرئيسية ">
                    {{  'Hospital Management System' }}
                </a>
            </div>
            <div class="user-actions">
                @auth('patient')
                    <span class="welcome-text">مرحباً بك، <strong>{{ $patient->name }}</strong></span>
                    <a href="{{ route('logout.patient') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form-header-standalone').submit();"
                        title="تسجيل الخروج">
                        <i class="fas fa-sign-out-alt"></i> تسجيل الخروج
                    </a>
                    <form id="logout-form-header-standalone" action="{{ route('logout.patient') }}" method="POST" style="display: none;">@csrf</form>
                @else
                    <a href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> تسجيل الدخول</a>
                @endauth
                <a href="{{ route('home') }}" title="العودة للصفحة الرئيسية"><i class="fas fa-home"></i> الرئيسية</a>
            </div>
        </div>
    </header>

    <main class="appointments-page-container">
        <div class="page-header-custom animate__animated animate__fadeInDown">
            <h1 class="page-title-custom"><i class="fas fa-calendar-check text-primary"></i> مواعيــدي</h1>
            <a href="{{ route('patient.appointments.create.form') }}" class="btn btn-primary-patient btn-sm">
                <i class="fas fa-plus-circle me-1"></i> طلب موعد جديد
            </a>
        </div>

        {{-- Session Messages (for redirects from non-AJAX forms - not used by current AJAX cancel) --}}
        @if (session('success_notify') || session('error_notify') || session('warning_notify') || session('info_notify'))
            {{-- ... نفس كود عرض رسائل السيشون من الرد السابق ... --}}
        @endif

        <div class="appointment-tabs animate__animated animate__fadeInUp" data-wow-delay="0.2s">
            <ul class="nav nav-tabs nav-fill mb-4" id="myAppointmentsTab" role="tablist">
                {{-- ... نفس كود التبويبات من الرد السابق ... --}}
                 <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="upcoming-tab" data-bs-toggle="tab"
                        data-bs-target="#upcoming-appointments-content" type="button" role="tab"
                        aria-controls="upcoming-appointments-content" aria-selected="true">
                        <i class="fas fa-calendar-day"></i> المواعيد القادمة
                        @if ($upcomingAppointments->total() > 0)
                            <span class="badge ms-2">{{ $upcomingAppointments->total() }}</span>
                        @endif
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="past-tab" data-bs-toggle="tab"
                        data-bs-target="#past-appointments-content" type="button" role="tab"
                        aria-controls="past-appointments-content" aria-selected="false">
                        <i class="fas fa-history"></i> المواعيد السابقة
                        @if ($pastAppointments->total() > 0)
                            <span class="badge ms-2">{{ $pastAppointments->total() }}</span>
                        @endif
                    </button>
                </li>
            </ul>
            <div class="tab-content" id="myAppointmentsTabContent">
                <!-- Upcoming Appointments Tab -->
                <div class="tab-pane fade show active" id="upcoming-appointments-content" role="tabpanel" aria-labelledby="upcoming-tab">
                    @if ($upcomingAppointments->isNotEmpty())
                        <div class="appointments-grid-patient">
                            @foreach ($upcomingAppointments as $appointment)
                                @php
                                    $appointmentDateTime = $appointment->appointment;
                                    $cancellationWindowHours = config('settings.appointments.patient_cancellation_window_hours', 24);
                                    $isCancellable = $appointmentDateTime->isFuture() && ($appointmentDateTime->diffInHours(now()) >= $cancellationWindowHours) && in_array($appointment->type, [App\Models\Appointment::STATUS_PENDING, App\Models\Appointment::STATUS_CONFIRMED]);
                                @endphp
                                <div class="appointment-card-wrapper">
                                    <div class="appointment-card" id="appointmentCard_{{ $appointment->id }}" data-appointment-id="{{ $appointment->id }}" data-status-key="{{ $appointment->type_key }}">
                                        <div class="appointment-card-header-patient">
                                            <div>
                                                <span class="appointment-datetime-patient">
                                                    {{ $appointmentDateTime->translatedFormat('l، j F Y') }}
                                                    <span class="time-part"><i class="far fa-clock"></i> {{ $appointmentDateTime->translatedFormat('h:i A') }}</span>
                                                </span>
                                            </div>
                                            <span class="badge appointment-status-badge status-badge {{ $appointment->type_key }}">
                                                {{ $appointment->status_display }}
                                            </span>
                                        </div>
                                        <div class="appointment-card-body-patient">
                                            {{-- ... نفس تفاصيل الموعد ... --}}
                                            @if ($appointment->doctor)
                                                <li><i class="fas fa-user-md"></i>
                                                    <span class="detail-label">الطبيب:</span>
                                                    <span class="detail-value">{{ $appointment->doctor->name ?? 'غير محدد' }}</span>
                                                </li>
                                            @endif
                                            @if ($appointment->section)
                                                <li><i class="fas fa-clinic-medical"></i>
                                                    <span class="detail-label">القسم:</span>
                                                    <span class="detail-value">{{ $appointment->section->name ?? 'غير محدد' }}</span>
                                                </li>
                                            @endif
                                             <li><i class="fas fa-user"></i>
                                                <span class="detail-label">باسم:</span>
                                                <span class="detail-value">{{ $appointment->name }}</span>
                                            </li>
                                            @if ($appointment->notes)
                                                <li><i class="fas fa-sticky-note"></i>
                                                    <span class="detail-label">ملاحظاتك:</span>
                                                    <span class="detail-value text-muted">{{ Str::limit($appointment->notes, 70) }}</span>
                                                </li>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            @endforeach
                        </div>
                        {{-- ... نفس كود الترقيم ... --}}
                         @if ($upcomingAppointments->hasPages())
                            <div class="pagination-wrapper d-flex justify-content-center mt-4">
                                {{ $upcomingAppointments->appends(['past_page' => $pastAppointments->currentPage()])->links('vendor.pagination.bootstrap-5') }}
                            </div>
                        @endif
                    @else
                        {{-- ... نفس كود "لا توجد مواعيد قادمة" ... --}}
                         <div class="no-appointments-patient">
                            <div class="empty-icon-display"><i class="fas fa-calendar-check"></i></div>
                            <h4>لا توجد لديك مواعيد قادمة حاليًا.</h4>
                            <p>عندما تقوم بحجز موعد جديد، سيظهر هنا بكل تأكيد.</p>
                            <a href="{{ route('patient.appointments.create.form') }}" class="btn btn-lg btn-book-new-appointment mt-2">
                                <i class="fas fa-plus-circle"></i> اطلب موعدًا جديدًا الآن
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Past Appointments Tab -->
                <div class="tab-pane fade" id="past-appointments-content" role="tabpanel" aria-labelledby="past-tab">
                   {{-- ... نفس كود عرض المواعيد السابقة من الرد السابق ... --}}
                   @if ($pastAppointments->isNotEmpty())
                        <div class="appointments-grid-patient">
                            @foreach ($pastAppointments as $appointment)
                                @php $appointmentDateTime = $appointment->appointment; @endphp
                                 <div class="appointment-card-wrapper">
                                    <div class="appointment-card" data-status-key="{{ $appointment->type_key }}">
                                        <div class="appointment-card-header-patient">
                                             <div>
                                                <span class="appointment-datetime-patient">
                                                    {{ $appointmentDateTime->translatedFormat('l، j F Y') }}
                                                    <span class="time-part"><i class="far fa-clock"></i> {{ $appointmentDateTime->translatedFormat('h:i A') }}</span>
                                                </span>
                                            </div>
                                            <span class="badge appointment-status-badge status-badge {{ $appointment->type_key }}">
                                                {{ $appointment->status_display }}
                                            </span>
                                        </div>
                                        <div class="appointment-card-body-patient">
                                            <ul class="appointment-details-list-patient">
                                                @if ($appointment->doctor)
                                                    <li><i class="fas fa-user-md"></i>
                                                        <span class="detail-label">الطبيب:</span>
                                                        <span class="detail-value">{{ $appointment->doctor->name ?? 'غير محدد' }}</span>
                                                    </li>
                                                @endif
                                                @if ($appointment->section)
                                                    <li><i class="fas fa-clinic-medical"></i>
                                                         <span class="detail-label">القسم:</span>
                                                        <span class="detail-value">{{ $appointment->section->name ?? 'غير محدد' }}</span>
                                                    </li>
                                                @endif
                                                 <li><i class="fas fa-user"></i>
                                                    <span class="detail-label">باسم:</span>
                                                    <span class="detail-value">{{ $appointment->name }}</span>
                                                </li>
                                                @if ( ($appointment->type == App\Models\Appointment::STATUS_CANCELLED_BY_PATIENT || $appointment->type == App\Models\Appointment::STATUS_CANCELLED) && $appointment->cancellation_reason && (empty($appointment->cancelled_by_user_type) || $appointment->cancelled_by_user_type == App\Models\Patient::class) )
                                                    <li class="cancellation-reason-item text-danger"><i class="fas fa-comment-slash"></i>
                                                        <span class="detail-label">سبب الإلغاء (من طرفك):</span>
                                                        <span class="detail-value">{{ Str::limit($appointment->cancellation_reason, 60) }}</span>
                                                    </li>
                                                @elseif ( ($appointment->type == App\Models\Appointment::STATUS_CANCELLED_BY_DOCTOR || $appointment->type == App\Models\Appointment::STATUS_CANCELLED) && $appointment->cancellation_reason && $appointment->cancelled_by_user_type == App\Models\Doctor::class )
                                                    <li class="cancellation-reason-item text-warning"><i class="fas fa-comment-slash"></i>
                                                        <span class="detail-label">سبب الإلغاء (من العيادة):</span>
                                                        <span class="detail-value">{{ Str::limit($appointment->cancellation_reason, 60) }}</span>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if ($pastAppointments->hasPages())
                            <div class="pagination-wrapper d-flex justify-content-center mt-4">
                                {{ $pastAppointments->appends(['upcoming_page' => $upcomingAppointments->currentPage()])->links('vendor.pagination.bootstrap-5') }}
                            </div>
                        @endif
                    @else
                        <div class="no-appointments-patient">
                             <div class="empty-icon-display"><i class="fas fa-folder-open"></i></div>
                            <h4>لا يوجد لديك سجل مواعيد سابقة.</h4>
                            <p>ستظهر مواعيدك المكتملة أو الملغاة هنا بعد مرور تاريخها.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <footer class="standalone-page-footer">
        <p>© {{ date('Y') }} جميع الحقوق محفوظة - {{ config('app.name', 'المنصة الطبية') }}.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifIt.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <script>
        // NotifIt messages (for redirects, if any other action uses them)
        @if (session('success_notify'))
            notif({ msg: "<div class='d-flex align-items-center'><i class='fas fa-check-circle fa-lg me-2'></i><div>{!! addslashes(session('success_notify')) !!}</div></div>", type: "success", position: "center", autohide: true, timeout: 5500, zindex: 999999 });
        @endif
        // ... (other session messages for error, warning, info) ...
         @if (session('error_notify'))
             notif({ msg: "<div class='d-flex align-items-center'><i class='fas fa-times-circle fa-lg me-2'></i><div>{!! addslashes(session('error_notify')) !!}</div></div>", type: "error", position: "center", autohide: true, timeout: 7500, zindex: 999999 });
        @endif
        @if (session('warning_notify'))
             notif({ msg: "<div class='d-flex align-items-center'><i class='fas fa-exclamation-triangle fa-lg me-2'></i><div>{!! addslashes(session('warning_notify')) !!}</div></div>", type: "warning", position: "center", autohide: true, timeout: 6500, bgcolor: "var(--patient-warning)", color: "#333", zindex: 999999 });
        @endif
        @if (session('info_notify'))
             notif({ msg: "<div class='d-flex align-items-center'><i class='fas fa-info-circle fa-lg me-2'></i><div>{!! addslashes(session('info_notify')) !!}</div></div>", type: "info", position: "center", autohide: true, timeout: 5500, zindex: 999999 });
        @endif


        // AJAX Cancellation
        document.addEventListener('DOMContentLoaded', function () {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            document.querySelectorAll('.btn-cancel-appointment-ajax').forEach(button => {
                button.addEventListener('click', function () {
                    const appointmentId = this.dataset.appointmentId;
                    const doctorName = this.dataset.appointmentDoctor || 'الطبيب';
                    const appointmentDatetime = this.dataset.appointmentDatetime || 'المحدد';
                    const $card = document.getElementById(`appointmentCard_${appointmentId}`); // Get the card element
                    const $reasonTextarea = document.querySelector(`textarea[name="cancellation_reason_for_ajax_${appointmentId}"]`);

                    Swal.fire({
                        title: 'تأكيد إلغاء الموعد',
                        html: `هل أنت متأكد من رغبتك في إلغاء موعدك مع:<br><strong>د. ${doctorName}</strong><br>يوم ${appointmentDatetime}؟` +
                              `<br><br><textarea id="swal_cancellation_reason" class="form-control mt-2" placeholder="سبب الإلغاء (اختياري)" rows="2"></textarea>`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: 'var(--patient-danger)',
                        cancelButtonColor: 'var(--patient-secondary)',
                        confirmButtonText: '<i class="fas fa-trash-alt me-1"></i> نعم، قم بالإلغاء',
                        cancelButtonText: '<i class="fas fa-times me-1"></i> لا، تراجع',
                        customClass: { popup: 'animate__animated animate__fadeInDown animate__faster', confirmButton: 'btn btn-danger mx-1 px-3', cancelButton: 'btn btn-secondary mx-1 px-3' },
                        buttonsStyling: false,
                        focusCancel: true,
                        preConfirm: () => {
                            return document.getElementById('swal_cancellation_reason').value;
                        },
                        showLoaderOnConfirm: true,
                        allowOutsideClick: () => !Swal.isLoading()
                    }).then(async (result) => {
                        if (result.isConfirmed) {
                            const cancellationReason = result.value; // السبب من SweetAlert
                            const cancelUrl = `{{ url('/my-appointments') }}/${appointmentId}/cancel`; // تأكد أن المسار صحيح

                            try {
                                const response = await fetch(cancelUrl, {
                                    method: 'POST', // أو PATCH إذا كان مسارك كذلك
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': csrfToken,
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        _method: 'POST', // أو 'PATCH'
                                        cancellation_reason_website: cancellationReason // مطابقة اسم الحقل في الكنترولر
                                    })
                                });

                                const responseData = await response.json();

                                if (!response.ok) {
                                    throw new Error(responseData.message || `فشل الإلغاء (HTTP ${response.status})`);
                                }

                                // نجاح الإلغاء
                                notif({ msg: `<div class='d-flex align-items-center'><i class='fas fa-check-circle fa-lg me-2'></i><div>${responseData.message}</div></div>`, type: "success", position: "center", autohide: true, timeout: 5500, zindex: 999999 });

                                // تحديث واجهة المستخدم للبطاقة الملغاة
                                if ($card) {
                                    $card.dataset.statusKey = 'cancelled';
                                    const $statusBadge = $card.querySelector('.appointment-status-badge');
                                    if ($statusBadge) {
                                        $statusBadge.className = 'badge appointment-status-badge status-badge cancelled'; // تحديث الكلاس
                                        $statusBadge.innerHTML = `<i class="fas fa-ban me-1"></i> ${responseData.status_display || responseData.new_status}`;
                                    }
                                    const $footer = $card.querySelector('.appointment-card-footer-patient');
                                    if ($footer) {
                                        $footer.innerHTML = '<small class="cancellation-info ms-auto text-center w-100 text-danger fw-bold"><i class="fas fa-check-circle me-1"></i> تم إلغاء هذا الموعد.</small>';
                                    }
                                    // يمكنك إضافة تأثير بصري إضافي للبطاقة
                                    $card.style.opacity = '0.7';
                                    $card.style.borderLeftColor = 'var(--patient-danger)'; // Or using ::before
                                }

                            } catch (error) {
                                console.error("Cancellation AJAX error:", error);
                                Swal.fire({ title: 'خطأ!', text: error.message || 'فشل طلب الإلغاء.', icon: 'error', confirmButtonColor: 'var(--patient-danger)' });
                            }
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>
