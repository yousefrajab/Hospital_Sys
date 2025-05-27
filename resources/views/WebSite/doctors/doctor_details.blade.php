<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title>{{ $doctor->name ?? 'ملف طبيب' }} - {{ config('app.name', 'اسم المستشفى/المنصة') }}</title>
    <link href="{{ URL::asset('Dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('Dashboard/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('Dashboard/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('Dashboard/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('Dashboard/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&family=Cairo:wght@400;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    {{-- Bootstrap 5 for Grid and Utilities (Optional, but helps with layout) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --docprofile-primary-color: {{ $settings['docprofile_primary_color'] ?? '#2979ff' }};
            --docprofile-primary-color-rgb: {{ implode(',', sscanf($settings['docprofile_primary_color'] ?? '#2979ff', '#%02x%02x%02x')) ?? '41, 121, 255' }};
            --docprofile-secondary-color: {{ $settings['docprofile_secondary_color'] ?? '#4caf50' }};
            --docprofile-text-color: #424753;
            --docprofile-heading-color: #222933;
            --docprofile-light-text: #757E8B;
            --docprofile-bg-color: #f7f9fc;
            --docprofile-card-bg: #ffffff;
            --docprofile-border-color: #e8eef3;
            --docprofile-radius: 10px;
            --docprofile-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
            --docprofile-font-primary: 'Tajawal', sans-serif;
            --docprofile-font-secondary: 'Cairo', sans-serif;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: var(--docprofile-font-primary);
            background-color: var(--docprofile-bg-color);
            color: var(--docprofile-text-color);
            line-height: 1.75;
            font-size: 16px;
        }

        *,
        *::before,
        *::after {
            box-sizing: inherit;
        }

        .doctor-profile-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2.5rem 1rem;
        }

        /* Minimalist Header for Standalone Page */
        .standalone-profile-header {
            background-color: var(--docprofile-card-bg);
            padding: 1rem 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 2.5rem;
        }

        .standalone-profile-header .container-fluid {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .standalone-profile-header .site-branding .site-title {
            font-family: var(--docprofile-font-secondary);
            font-size: 1.6rem;
            color: var(--docprofile-primary-color);
            margin: 0;
            font-weight: 700;
            text-decoration: none;
        }

        .standalone-profile-header .back-to-doctors a {
            color: var(--docprofile-primary-color);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
        }

        .standalone-profile-header .back-to-doctors a i {
            margin-inline-end: 0.3rem;
        }

        .standalone-profile-header .back-to-doctors a:hover {
            text-decoration: underline;
        }


        /* Doctor Profile Hero Section */
        .doctor-hero-section {
            background-color: var(--docprofile-card-bg);
            border-radius: var(--docprofile-radius);
            padding: 2.5rem;
            margin-bottom: 2.5rem;
            box-shadow: var(--docprofile-shadow);
            display: flex;
            flex-wrap: wrap;
            /* Allow wrapping on smaller screens */
            gap: 2rem;
            align-items: flex-start;
            /* Align items to the top */
        }

        .doctor-hero-section .profile-image-wrapper {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            overflow: hidden;
            border: 6px solid var(--docprofile-bg-color);
            /* Border matches page background */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            flex-shrink: 0;
            margin: 0 auto 1rem;
            /* Center on mobile */
        }

        @media (min-width: 768px) {
            .doctor-hero-section .profile-image-wrapper {
                margin: 0;
            }
        }

        .doctor-hero-section .profile-image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .doctor-hero-section .profile-info-details {
            flex-grow: 1;
        }

        .doctor-hero-section .profile-info-details h1 {
            font-family: var(--docprofile-font-secondary);
            font-size: 2.25rem;
            /* Larger name */
            color: var(--docprofile-heading-color);
            font-weight: 800;
            /* Bolder */
            margin-bottom: 0.5rem;
            line-height: 1.2;
        }

        .doctor-hero-section .profile-info-details .department-link,
        .doctor-hero-section .profile-info-details .department-link a {
            font-size: 1.1rem;
            color: var(--docprofile-primary-color);
            font-weight: 600;
            margin-bottom: 1rem;
            display: block;
            text-decoration: none;
        }

        .doctor-hero-section .profile-info-details .department-link a:hover {
            text-decoration: underline;
        }

        .doctor-hero-section .profile-info-details .department-link i {
            margin-inline-end: 0.5rem;
        }

        .doctor-hero-section .profile-actions-hero {
            margin-top: 1rem;
            width: 100%;
            /* Full width on small screens */
        }

        @media (min-width: 992px) {
            .doctor-hero-section .profile-actions-hero {
                margin-top: 0;
                margin-inline-start: auto;
                width: auto;
                flex-shrink: 0;
            }
        }

        .doctor-hero-section .btn-book-appointment-hero {
            background-color: var(--docprofile-secondary-color);
            color: white;
            border: none;
            padding: 0.8rem 1.75rem;
            font-size: 1rem;
            font-weight: 700;
            border-radius: var(--docprofile-radius);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            transition: background-color 0.25s ease, transform 0.2s ease;
        }

        .doctor-hero-section .btn-book-appointment-hero:hover {
            background-color: color-mix(in srgb, var(--docprofile-secondary-color) 85%, black);
            transform: translateY(-2px);
        }

        .doctor-hero-section .btn-book-appointment-hero i {
            font-size: 1.1em;
        }

        /* Custom Breadcrumb for this page */
        .profile-breadcrumb {
            padding: 0;
            margin: 1.5rem 0 0 0;
            list-style: none;
            display: flex;
            font-size: 0.9rem;
        }

        .profile-breadcrumb li a {
            color: var(--docprofile-primary-color);
            text-decoration: none;
        }

        .profile-breadcrumb li a:hover {
            text-decoration: underline;
        }

        .profile-breadcrumb li {
            color: var(--docprofile-light-text);
        }

        .profile-breadcrumb li+li::before {
            content: "/";
            padding: 0 0.5rem;
            color: var(--docprofile-light-text);
        }


        /* Main Content Area (Tabs & Sidebar) */
        .card-styled {
            background-color: var(--docprofile-card-bg);
            border-radius: var(--docprofile-radius);
            padding: 2rem;
            box-shadow: var(--docprofile-shadow);
            margin-bottom: 2rem;
            border: 1px solid var(--docprofile-border-color);
        }

        /* Modern Tabs for Profile Content */
        .doctor-profile-tabs .nav-tabs {
            border-bottom: 2px solid var(--docprofile-border-color);
            margin-bottom: 2rem;
        }

        .doctor-profile-tabs .nav-tabs .nav-item .nav-link {
            border: none;
            border-bottom: 3px solid transparent;
            padding: 0.8rem 1.5rem;
            font-size: 1.05rem;
            font-weight: 600;
            color: var(--docprofile-light-text);
            transition: all 0.25s ease;
            margin-bottom: -2px;
            /* Align with bottom border */
        }

        .doctor-profile-tabs .nav-tabs .nav-item .nav-link.active,
        .doctor-profile-tabs .nav-tabs .nav-item .nav-link:hover {
            color: var(--docprofile-primary-color);
            border-bottom-color: var(--docprofile-primary-color);
            background-color: transparent;
        }

        .doctor-profile-tabs .nav-tabs .nav-item .nav-link i {
            margin-inline-end: 0.6rem;
        }

        .doctor-profile-tabs .tab-content .tab-pane {
            animation: fadeInSmooth 0.5s;
        }

        @keyframes fadeInSmooth {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .tab-content-section h4.section-title {
            font-family: var(--docprofile-font-secondary);
            font-size: 1.5rem;
            /* 24px */
            font-weight: 700;
            color: var(--docprofile-heading-color);
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid var(--docprofile-border-color);
            display: flex;
            align-items: center;
        }

        .tab-content-section h4.section-title i {
            color: var(--docprofile-primary-color);
            margin-inline-end: 0.75rem;
            font-size: 1.2em;
        }

        .doctor-bio-fulltext {
            font-size: 1rem;
            line-height: 1.8;
            color: var(--docprofile-text-color);
        }

        .contact-info-list-profile {
            list-style: none;
            padding: 0;
            margin-top: 1.5rem;
        }

        .contact-info-list-profile li {
            margin-bottom: 0.8rem;
            font-size: 1rem;
            display: flex;
            align-items: center;
        }

        .contact-info-list-profile li i {
            width: 22px;
            color: var(--docprofile-primary-color);
            margin-inline-end: 0.8rem;
            text-align: center;
        }

        .contact-info-list-profile li strong {
            font-weight: 600;
            color: var(--docprofile-heading-color);
            margin-inline-end: 0.4rem;
        }

        .contact-info-list-profile li a {
            color: var(--docprofile-primary-color);
            text-decoration: none;
        }

        .contact-info-list-profile li a:hover {
            text-decoration: underline;
        }

        .schedule-table-profile {
            border: 1px solid var(--docprofile-border-color);
            font-size: 0.95rem;
        }

        .schedule-table-profile th {
            background-color: #f3f6f9;
            color: var(--docprofile-heading-color);
            font-weight: 600;
            padding: 0.75rem 1rem;
        }

        .schedule-table-profile td {
            padding: 0.75rem 1rem;
            vertical-align: middle;
        }

        .schedule-table-profile .break-list-profile {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .schedule-table-profile .break-list-profile li {
            font-size: 0.85em;
            margin-bottom: 0.2rem;
            color: var(--docprofile-light-text);
        }

        .schedule-table-profile .break-list-profile i {
            margin-inline-end: 0.3rem;
        }

        .schedule-notice-profile {
            color: var(--docprofile-light-text);
            font-size: 0.85rem;
            margin-top: 1rem;
        }

        .schedule-notice-profile i {
            margin-inline-end: 0.3rem;
        }

        .empty-state-profile {
            text-align: center;
            padding: 1.5rem;
            background-color: #f9fbfd;
            border-radius: var(--docprofile-radius);
            color: var(--docprofile-light-text);
            border: 1px dashed var(--docprofile-border-color);
        }

        .empty-state-profile i {
            display: block;
            font-size: 2rem;
            margin-bottom: 0.75rem;
            color: var(--docprofile-primary-color);
            opacity: 0.7;
        }

        /* Sidebar Widgets */
        .doctor-profile-sidebar .sidebar-widget {
            margin-bottom: 2rem;
        }

        .doctor-profile-sidebar .widget-title-sidebar {
            font-family: var(--docprofile-font-secondary);
            font-size: 1.25rem;
            /* 20px */
            font-weight: 700;
            color: var(--docprofile-heading-color);
            margin-bottom: 1.25rem;
            padding-bottom: 0.6rem;
            border-bottom: 2px solid var(--docprofile-primary-color);
            display: inline-block;
            /* To make border only under text */
        }

        .doctor-profile-sidebar .widget-title-sidebar i {
            margin-inline-end: 0.6rem;
        }

        .working-hours-summary-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .working-hours-summary-list li {
            display: flex;
            justify-content: space-between;
            padding: 0.6rem 0;
            font-size: 0.95rem;
            border-bottom: 1px dashed var(--docprofile-border-color);
        }

        .working-hours-summary-list li:last-child {
            border-bottom: none;
        }

        .working-hours-summary-list li .day-name {
            font-weight: 600;
            color: var(--docprofile-heading-color);
        }

        .working-hours-summary-list li .day-time {
            color: var(--docprofile-text-color);
        }

        .working-hours-summary-list li.is-today .day-name,
        .working-hours-summary-list li.is-today .day-time {
            color: var(--docprofile-secondary-color);
            font-weight: bold;
        }

        .department-info-widget-profile h5 a {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--docprofile-heading-color);
            margin-bottom: 0.5rem;
            display: block;
            text-decoration: none;
        }

        .department-info-widget-profile h5 a:hover {
            color: var(--docprofile-primary-color);
        }

        .department-info-widget-profile p {
            font-size: 0.9rem;
            line-height: 1.7;
            color: var(--docprofile-light-text);
            margin-bottom: 1rem;
        }

        .department-info-widget-profile .read-more-profile-link {
            color: var(--docprofile-primary-color);
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
        }

        .department-info-widget-profile .read-more-profile-link i {
            margin-inline-start: 0.3rem;
        }

        /* For RTL arrow */
        .department-info-widget-profile .read-more-profile-link:hover {
            text-decoration: underline;
        }

        /* Related Doctors Section */
        .related-doctors-section-profile {
            margin-top: 3rem;
            padding-top: 2.5rem;
            border-top: 1px solid var(--docprofile-border-color);
        }

        .related-doctors-section-profile .section-title-centered {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .related-doctors-section-profile .section-title-centered h2 {
            font-family: var(--docprofile-font-secondary);
            font-size: 1.8rem;
            color: var(--docprofile-heading-color);
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .related-doctors-section-profile .section-title-centered .separator-line {
            display: block;
            width: 70px;
            height: 3px;
            background-color: var(--docprofile-primary-color);
            margin: 0 auto;
        }

        /* Re-use doctor card style from previous example, just ensure class names match if copied */
        /* .doctor-card-standalone (styles for related doctors can be same as main list) */


        /* Minimalist Footer for Standalone Page */
        .standalone-profile-footer {
            text-align: center;
            padding: 1.5rem 0;
            margin-top: 3rem;
            background-color: var(--docprofile-card-bg);
            border-top: 1px solid var(--docprofile-border-color);
            font-size: 0.9rem;
            color: var(--docprofile-light-text);
        }
    </style>
</head>

<body>

    <header class="standalone-profile-header">
        <div class="container-fluid">
            <div class="site-branding">
                <a href="{{ route('home') }}" class="site-title" title="العودة إلى الرئيسية {{ config('app.name') }}">
                    {{-- <img src="{{ asset('path/to/your/logo.png') }}" alt="Logo"> --}}
                    {{ config('app.name', 'المنصة الطبية') }}
                </a>
            </div>
            <div class="back-to-doctors">
                <a href="{{ route('website.doctors.all') }}"><i class="fas fa-users"></i> عرض كل الأطباء</a>
            </div>
        </div>
    </header>

    <main class="doctor-profile-container">
        <!-- Doctor Hero Section -->
        <section class="doctor-hero-section animate__animated animate__fadeIn">
            <div class="profile-image-wrapper">
                @if ($doctor->image)
                    <img src="{{ Url::asset('Dashboard/img/doctors/' . $doctor->image->filename) }}"
                        class="doctor-avatar" alt="{{ trans('doctors.img') }}">
                @else
                    <img src="{{ Url::asset('Dashboard/img/doctor_default.png') }}" class="doctor-avatar"
                        alt="صورة افتراضية">
                @endif
            </div>
            <div class="profile-info-details">
                <h1>{{ $doctor->name }}</h1>
                @if ($doctor->section)
                    <p class="department-link">
                        <i class="fas fa-hospital-symbol"></i>
                        <a href="{{ route('website.department.details', $doctor->section->id) }}"
                            title="المزيد عن قسم {{ $doctor->section->name }}">
                            {{ $doctor->section->name }}
                        </a>
                    </p>
                @endif
                {{-- Add Qualifications/Title if available, e.g., $doctor->title or $doctor->qualifications --}}
                {{-- <p class="doctor-title-qualifications text-muted">{{ $doctor->title_or_qualifications ?? 'أخصائي/استشاري' }}</p> --}}

                <ul class="profile-breadcrumb">
                    <li><a href="{{ route('home') }}">الرئيسية</a></li>
                    <li><a href="{{ route('website.doctors.all') }}">الأطباء</a></li>
                    <li>{{ $doctor->name }}</li>
                </ul>
            </div>

        </section>

        <!-- Main Content (Tabs and Sidebar) -->
        <div class="row g-4">
            <!-- Content Side -->
            <div class="col-lg-8">
                <div class="doctor-profile-tabs card-styled animate__animated animate__fadeInUp" data-wow-delay="0.2s">
                    <ul class="nav nav-tabs" id="doctorProfileTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="bio-tab" data-bs-toggle="tab"
                                data-bs-target="#tab-bio-content" type="button" role="tab"
                                aria-controls="tab-bio-content" aria-selected="true">
                                <i class="fas fa-user-tie"></i> السيرة الذاتية
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="schedule-tab" data-bs-toggle="tab"
                                data-bs-target="#tab-schedule-content" type="button" role="tab"
                                aria-controls="tab-schedule-content" aria-selected="false">
                                <i class="fas fa-calendar-alt"></i> جدول العمل
                            </button>
                        </li>
                        {{-- Add more tabs if needed: Education, Reviews, etc. --}}
                    </ul>
                    <div class="tab-content" id="doctorProfileTabContent">
                        <!-- Tab: Bio -->
                        <div class="tab-pane fade show active" id="tab-bio-content" role="tabpanel"
                            aria-labelledby="bio-tab">
                            <div class="tab-content-section">
                                <h4 class="section-title"><i class="fas fa-info-circle"></i> نبذة تعريفية عن الطبيب</h4>
                                <div class="doctor-bio-fulltext">
                                    @if ($doctor->bio)
                                        {!! nl2br(e($doctor->bio)) !!}
                                    @else
                                        <p class="empty-state-profile"><i class="fas fa-feather-alt"></i> لا توجد نبذة
                                            تعريفية متاحة حاليًا لهذا الطبيب.</p>
                                    @endif
                                </div>

                                @if ($doctor->phone || $doctor->email)
                                    <h4 class="section-title mt-4"><i class="fas fa-address-book"></i> معلومات الاتصال
                                    </h4>
                                    <ul class="contact-info-list-profile">
                                        @if ($doctor->phone)
                                            <li><i class="fas fa-phone-alt"></i> <strong>الهاتف:</strong> <a
                                                    href="tel:{{ $doctor->phone }}">{{ $doctor->phone }}</a></li>
                                        @endif
                                        @if ($doctor->email)
                                            <li><i class="fas fa-envelope"></i> <strong>البريد الإلكتروني:</strong> <a
                                                    href="mailto:{{ $doctor->email }}">{{ $doctor->email }}</a></li>
                                        @endif
                                    </ul>
                                @endif
                            </div>
                        </div>
                        <!-- Tab: Schedule -->
                        <div class="tab-pane fade" id="tab-schedule-content" role="tabpanel"
                            aria-labelledby="schedule-tab">
                            <div class="tab-content-section">
                                <h4 class="section-title"><i class="fas fa-user-clock"></i> جدول عمل الطبيب</h4>
                                @if (collect($scheduleData)->where('active', true)->isNotEmpty())
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover schedule-table-profile">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>اليوم</th>
                                                    <th>وقت البدء</th>
                                                    <th>وقت الانتهاء</th>
                                                    <th>مدة الكشف (دقيقة)</th>
                                                    <th>الاستراحات</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($scheduleData as $day => $data)
                                                    @if ($data['active'])
                                                        <tr>
                                                            <td>{{ trans('doctors.' . $day) ?? $day }}
                                                            </td>
                                                            <td>{{ $data['start_time'] }}</td>
                                                            <td>{{ $data['end_time'] }}</td>
                                                            <td>{{ $data['appointment_duration'] ?? '-' }}</td>
                                                            <td>
                                                                @if ($data['breaks']->isNotEmpty())
                                                                    <ul class="list-unstyled mb-0 break-list-profile">
                                                                        @foreach ($data['breaks'] as $break)
                                                                            <li><i class="far fa-pause-circle"></i>
                                                                                {{ $break['start_time'] }} -
                                                                                {{ $break['end_time'] }}
                                                                                @if ($break['reason'])
                                                                                    <small>({{ $break['reason'] }})</small>
                                                                                @endif
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                @else
                                                                    <span>لا توجد</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <p class="schedule-notice-profile mt-3"><i class="fas fa-exclamation-circle"></i>
                                        المواعيد المذكورة هي حسب التوقيت المحلي وقد تخضع للتغيير. يفضل التأكيد المسبق.
                                    </p>
                                @else
                                    <p class="empty-state-profile"><i class="fas fa-calendar-times"></i> لا يوجد جدول
                                        عمل مفصل متاح حاليًا لهذا الطبيب.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- End Content Side -->

            <!-- Sidebar Side -->
            <div class="col-lg-4">
                <aside class="doctor-profile-sidebar">
                    <div class="sidebar-widget card-styled working-hours-widget animate__animated animate__fadeInUp"
                        data-wow-delay="0.3s">
                        <h5 class="widget-title-sidebar"><i class="far fa-clock"></i> ساعات العمل المتاحة</h5>
                        <ul class="working-hours-summary-list">
                            @php $activeDaysFound = false; @endphp
                            @forelse ($scheduleData as $day => $data)
                                @if ($data['active'])
                                    @php $activeDaysFound = true; @endphp
                                    <li
                                        class="{{ \Carbon\Carbon::now()->englishDayOfWeek == $day ? 'is-today' : '' }}">
                                        <span
                                            class="day-name">{{ trans('doctors.'.$day  ) ?? $day }}</span>
                                        <span class="day-time">{{ $data['start_time'] }} -
                                            {{ $data['end_time'] }}</span>
                                    </li>
                                @endif
                            @empty
                                <li>لا توجد أيام عمل مجدولة حاليًا.</li>
                            @endforelse
                            @if (!$activeDaysFound && !empty($scheduleData))
                                <li><span class="text-warning fw-bold">الطبيب في إجازة حاليًا أو لا توجد أيام عمل
                                        محددة.</span></li>
                            @endif
                        </ul>
                    </div>

                    @if ($doctor->section)
                        <div class="sidebar-widget card-styled department-info-widget-profile animate__animated animate__fadeInUp"
                            data-wow-delay="0.4s">
                            <h5 class="widget-title-sidebar"><i class="fas fa-building"></i> عن القسم</h5>
                            <h5><a
                                    href="{{ route('website.department.details', $doctor->section->id) }}">{{ $doctor->section->name }}</a>
                            </h5>
                            <p>{{ Str::limit(strip_tags($doctor->section->description ?? 'معلومات إضافية عن هذا القسم ستكون متاحة قريباً.'), 150) }}
                            </p>
                            <a href="{{ route('website.department.details', $doctor->section->id) }}"
                                class="read-more-profile-link">اقرأ المزيد عن القسم <i
                                    class="fas fa-arrow-left"></i></a>
                        </div>
                    @endif

                    {{-- Add more widgets: Map, Contact Form, etc. --}}
                </aside>
            </div> <!-- End Sidebar Side -->
        </div> <!-- End Row -->

        @if ($relatedDoctors->isNotEmpty())
            <section class="related-doctors-section-profile animate__animated animate__fadeInUp"
                data-wow-delay="0.5s">
                <div class="section-title-centered">
                    <h2>أطباء مشابهون في نفس التخصص</h2>
                    <div class="separator-line"></div>
                </div>
                <div class="row g-4">
                    @foreach ($relatedDoctors as $rdoctor)
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            {{-- Re-use the doctor card structure from the all_doctors_standalone page, slightly simplified --}}
                            <div class="doctor-card-standalone">
                                <div class="image-wrapper-standalone" style="height: 200px;"> {{-- Slightly smaller image for related --}}
                                    <a href="{{ route('website.doctor.details', $rdoctor->id) }}"
                                        title="{{ $rdoctor->name }}">
                                        <img src="{{ $rdoctor->image_url_or_placeholder }}"
                                            alt="{{ $rdoctor->name }}"
                                            onerror="this.onerror=null; this.src='{{ asset('WebSite/images/resource/doctor-placeholder.png') }}';">
                                    </a>
                                    @if ($rdoctor->status)
                                        <span class="availability-badge-standalone status-available"><i
                                                class="fas fa-check-circle"></i> متاح</span>
                                    @endif
                                </div>
                                <div class="info-content-standalone pt-3">
                                    <h5 class="doctor-name-standalone mb-1"><a
                                            href="{{ route('website.doctor.details', $rdoctor->id) }}">{{ $rdoctor->name }}</a>
                                    </h5>
                                    @if ($rdoctor->section)
                                        <p class="doctor-department-standalone small mb-2"><a
                                                href="{{ route('website.department.details', $rdoctor->section->id) }}"><i
                                                    class="fas fa-briefcase-medical"></i>
                                                {{ $rdoctor->section->name }}</a></p>
                                    @endif
                                </div>
                                <div class="action-footer-standalone pb-3">
                                    <a href="{{ route('website.doctor.details', $rdoctor->id) }}"
                                        class="btn btn-sm btn-outline-primary"><i class="fas fa-user-tag"></i> عرض
                                        الملف</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

    </main>

    <footer class="standalone-profile-footer">
        <p>© {{ date('Y') }} جميع الحقوق محفوظة - {{ config('app.name', 'منصتنا الطبية') }}.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    {{-- Add WOW.js or other JS if needed and initialized --}}
</body>

</html>
