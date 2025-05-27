<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title>تفاصيل قسم {{ $section->name }} - {{ config('app.name', 'اسم المستشفى/المنصة') }}</title>
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
    {{-- Bootstrap 5 for Grid and Utilities --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --deptdetail-primary-color: {{ $settings['deptdetail_primary_color'] ?? '#00796B' }};
            /* A sophisticated Teal */
            --deptdetail-primary-color-rgb: {{ implode(',', sscanf($settings['deptdetail_primary_color'] ?? '#00796B', '#%02x%02x%02x')) ?? '0, 121, 107' }};
            --deptdetail-secondary-color: {{ $settings['deptdetail_secondary_color'] ?? '#FFB300' }};
            /* A warm Amber for accents */
            --deptdetail-text-color: #37474F;
            --deptdetail-heading-color: #212121;
            /* Very dark gray for headings */
            --deptdetail-light-text: #607D8B;
            --deptdetail-bg-color: #F5F5F5;
            /* Off-white, almost gray */
            --deptdetail-card-bg: #ffffff;
            --deptdetail-border-color: #E0E0E0;
            --deptdetail-radius: 10px;
            --deptdetail-shadow: 0 6px 20px rgba(0, 0, 0, 0.07);
            --deptdetail-font-primary: 'Cairo', sans-serif;
            --deptdetail-font-secondary: 'Tajawal', sans-serif;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: var(--deptdetail-font-primary);
            background-color: var(--deptdetail-bg-color);
            color: var(--deptdetail-text-color);
            line-height: 1.8;
            font-size: 16px;
        }

        *,
        *::before,
        *::after {
            box-sizing: inherit;
        }

        .department-detail-standalone-container {
            max-width: 1140px;
            margin: 0 auto;
            padding: 2.5rem 1rem;
        }

        /* Minimalist Header */
        .standalone-detail-header {
            background-color: var(--deptdetail-card-bg);
            padding: 1rem 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 2.5rem;
        }

        .standalone-detail-header .container-fluid {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .standalone-detail-header .site-branding .site-title {
            font-family: var(--deptdetail-font-secondary);
            font-size: 1.6rem;
            color: var(--deptdetail-primary-color);
            margin: 0;
            font-weight: 700;
            text-decoration: none;
        }

        .standalone-detail-header .back-to-links a {
            color: var(--deptdetail-primary-color);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            margin-inline-start: 1rem;
        }

        .standalone-detail-header .back-to-links a i {
            margin-inline-end: 0.4rem;
        }

        .standalone-detail-header .back-to-links a:hover {
            text-decoration: underline;
        }

        /* Page Title / Hero Section for Department */
        .department-hero-title {
            padding: 3rem 0 2rem;
            text-align: center;
            margin-bottom: 2.5rem;
            border-radius: var(--deptdetail-radius);
            background-color: color-mix(in srgb, var(--deptdetail-primary-color) 7%, white);
            border: 1px solid color-mix(in srgb, var(--deptdetail-primary-color) 20%, white);
        }

        .department-hero-title .department-icon-hero {
            font-size: 3rem;
            color: var(--deptdetail-primary-color);
            margin-bottom: 1rem;
            display: inline-block;
        }

        .department-hero-title h1 {
            font-family: var(--deptdetail-font-secondary);
            font-size: 2.75rem;
            color: var(--deptdetail-heading-color);
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .department-hero-title .breadcrumb-custom-detail {
            padding: 0;
            margin: 0;
            list-style: none;
            display: flex;
            justify-content: center;
            font-size: 0.9rem;
        }

        .department-hero-title .breadcrumb-custom-detail li a {
            color: var(--deptdetail-primary-color);
            text-decoration: none;
        }

        .department-hero-title .breadcrumb-custom-detail li a:hover {
            text-decoration: underline;
        }

        .department-hero-title .breadcrumb-custom-detail li {
            color: var(--deptdetail-light-text);
        }

        .department-hero-title .breadcrumb-custom-detail li+li::before {
            content: "/";
            padding: 0 0.5rem;
            color: var(--deptdetail-light-text);
        }

        /* Main Content & Sidebar Layout */
        .card-styled-detail {
            background-color: var(--deptdetail-card-bg);
            border-radius: var(--deptdetail-radius);
            padding: 2rem;
            box-shadow: var(--deptdetail-shadow);
            margin-bottom: 2rem;
            border: 1px solid var(--deptdetail-border-color);
        }

        .department-main-content .section-heading-detail {
            font-family: var(--deptdetail-font-secondary);
            font-size: 1.75rem;
            /* 28px */
            font-weight: 700;
            color: var(--deptdetail-heading-color);
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--deptdetail-primary-color);
            display: inline-block;
            /* Border only under text */
        }

        .department-main-content .department-description-full {
            font-size: 1.05rem;
            line-height: 1.9;
            margin-bottom: 2rem;
        }

        .department-main-content .services-list-detail {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .department-main-content .services-list-detail li {
            position: relative;
            padding-inline-start: 2rem;
            /* For icon */
            margin-bottom: 0.75rem;
            font-size: 1rem;
        }

        .department-main-content .services-list-detail li::before {
            content: "\f00c";
            /* FontAwesome check */
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            position: absolute;
            inset-inline-start: 0;
            /* Handles LTR/RTL */
            top: 5px;
            color: var(--deptdetail-secondary-color);
            /* Accent color for checkmark */
            font-size: 0.9em;
        }

        /* Doctors in Department */
        .department-doctors-grid .doctor-card-item-small {
            background-color: var(--deptdetail-card-bg);
            border-radius: var(--deptdetail-radius);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            overflow: hidden;
            text-align: center;
            height: 100%;
            display: flex;
            flex-direction: column;
            border: 1px solid var(--deptdetail-border-color);
        }

        .department-doctors-grid .doctor-card-item-small:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.07);
        }

        .department-doctors-grid .doctor-image-small {
            height: 160px;
            overflow: hidden;
            background-color: #f0f4f7;
        }

        .department-doctors-grid .doctor-image-small img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center top;
        }

        .department-doctors-grid .doctor-info-small {
            padding: 1rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .department-doctors-grid .doctor-info-small h5 a {
            font-family: var(--deptdetail-font-secondary);
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--deptdetail-heading-color);
            text-decoration: none;
            margin-bottom: 0.25rem;
            display: block;
        }

        .department-doctors-grid .doctor-info-small h5 a:hover {
            color: var(--deptdetail-primary-color);
        }

        .department-doctors-grid .doctor-specialization-small {
            font-size: 0.8rem;
            color: var(--deptdetail-light-text);
            margin-bottom: 0.75rem;
            display: block;
            min-height: 2.4em;
            /* Space for 2 lines */
        }

        .department-doctors-grid .btn-view-profile-small {
            background-color: transparent;
            color: var(--deptdetail-primary-color);
            border: 1px solid var(--deptdetail-primary-color);
            padding: 0.4rem 1rem;
            font-size: 0.8rem;
            font-weight: 600;
            border-radius: 50px;
            text-decoration: none;
            display: inline-block;
            margin-top: auto;
            transition: all 0.25s ease;
        }

        .department-doctors-grid .btn-view-profile-small:hover {
            background-color: var(--deptdetail-primary-color);
            color: white;
        }

        /* Sidebar Styling */
        .department-sidebar .sidebar-widget-detail {
            margin-bottom: 2rem;
        }

        .department-sidebar .widget-title-detail {
            font-family: var(--deptdetail-font-secondary);
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--deptdetail-heading-color);
            margin-bottom: 1.25rem;
            padding-bottom: 0.6rem;
            border-bottom: 2px solid var(--deptdetail-primary-color);
            display: inline-block;
        }

        .department-sidebar .other-departments-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .department-sidebar .other-departments-list li a {
            display: block;
            padding: 0.7rem 1rem;
            background-color: var(--deptdetail-card-bg);
            color: var(--deptdetail-text-color);
            font-size: 0.95rem;
            border: 1px solid var(--deptdetail-border-color);
            border-radius: calc(var(--deptdetail-radius) - 4px);
            transition: all 0.25s ease;
            margin-bottom: 0.5rem;
            text-decoration: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .department-sidebar .other-departments-list li a:hover,
        .department-sidebar .other-departments-list li.current-dept a {
            background-color: var(--deptdetail-primary-color);
            color: white;
            border-color: var(--deptdetail-primary-color);
            transform: translateX(-4px);
            box-shadow: 0 4px 10px rgba(var(--deptdetail-primary-color-rgb), 0.2);
        }

        .rtl .department-sidebar .other-departments-list li a:hover,
        .rtl .department-sidebar .other-departments-list li.current-dept a {
            transform: translateX(4px);
        }

        .department-sidebar .other-departments-list li a .arrow-icon {
            opacity: 0.6;
        }

        .department-sidebar .other-departments-list li a:hover .arrow-icon,
        .department-sidebar .other-departments-list li.current-dept a .arrow-icon {
            opacity: 1;
        }


        .contact-widget-detail .widget-content-styled {
            padding: 1.75rem;
            border-radius: var(--deptdetail-radius);
            color: white;
            background: var(--deptdetail-secondary-color);
            /* Use secondary color for contact CTA */
            /* Or a gradient: linear-gradient(135deg, var(--deptdetail-secondary-color) 0%, color-mix(in srgb, var(--deptdetail-secondary-color) 70%, black) 100%); */
            text-align: center;
        }

        .contact-widget-detail .contact-icon-top {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            opacity: 0.8;
        }

        .contact-widget-detail .text-contact {
            margin-bottom: 1rem;
            font-size: 0.95rem;
            opacity: 0.9;
        }

        .contact-widget-detail .contact-info-item a {
            color: white;
            font-size: 1.1rem;
            font-weight: 600;
            display: block;
            margin-bottom: 0.5rem;
            text-decoration: none;
        }

        .contact-widget-detail .contact-info-item a i {
            margin-inline-end: 0.5rem;
            opacity: 0.8;
        }

        .contact-widget-detail .contact-info-item a:hover {
            opacity: 0.85;
        }

        .empty-state-alert {
            background-color: color-mix(in srgb, var(--deptdetail-secondary-color) 10%, white);
            border-color: color-mix(in srgb, var(--deptdetail-secondary-color) 30%, white);
            color: color-mix(in srgb, var(--deptdetail-secondary-color) 80%, black);
        }

        /* Footer Styling */
        .standalone-detail-footer {
            text-align: center;
            padding: 1.5rem 0;
            margin-top: 3rem;
            background-color: var(--deptdetail-card-bg);
            border-top: 1px solid var(--deptdetail-border-color);
            font-size: 0.9rem;
            color: var(--deptdetail-light-text);
        }
    </style>
</head>

<body>

    <header class="standalone-detail-header">
        <div class="container-fluid">
            <div class="site-branding">
                <a href="{{ route('home') }}" class="site-title" title="العودة إلى الرئيسية {{ config('app.name') }}">
                    {{ config('app.name', 'المنصة الطبية') }}
                </a>
            </div>
            <div class="back-to-links">
                <a href="{{ route('website.departments.all') }}"><i class="fas fa-th-large"></i> كل الأقسام</a>
                <a href="{{ route('home') }}"><i class="fas fa-home"></i> الرئيسية</a>
            </div>
        </div>
    </header>

    <main class="department-detail-standalone-container">

        <section class="department-hero-title animate__animated animate__fadeInDown">
            @if ($section->icon_class)
                <div class="department-icon-hero"><i class="{{ $section->icon_class }}"></i></div>
            @else
                <div class="department-icon-hero"><i class="fas fa-clinic-medical"></i></div> {{-- Default icon --}}
            @endif
            <h1>{{ $section->name }}</h1>
            <ul class="breadcrumb-custom-detail">
                <li><a href="{{ route('home') }}">الرئيسية</a></li>
                <li><a href="{{ route('website.departments.all') }}">الأقسام الطبية</a></li>
                <li>{{ $section->name }}</li>
            </ul>
        </section>

        <div class="row g-4 g-lg-5"> {{-- Bootstrap 5 row with gutters --}}
            <!-- Content Side -->
            <div class="col-lg-8">
                <div class="department-main-content card-styled-detail animate__animated animate__fadeInUp"
                    data-wow-delay="0.2s">
                    {{-- Optional: Main Image for the department
                    @if ($section->main_image_url)
                        <div class="department-main-image mb-4 rounded overflow-hidden shadow-sm">
                            <img src="{{ $section->main_image_url }}" alt="صورة قسم {{ $section->name }}" class="img-fluid">
                        </div>
                    @endif
                    --}}
                    <h2 class="section-heading-detail">عن قسم {{ $section->name }}</h2>
                    <div class="department-description-full">
                        {!! nl2br(e($section->description ?? 'لا يوجد وصف تفصيلي متاح لهذا القسم حاليًا.')) !!}
                    </div>

                    @if ($section->Service && $section->Service->isNotEmpty())
                        <div class="mt-4 pt-4 border-top">
                            <h3 class="section-heading-detail">الخدمات الأساسية المقدمة</h3>
                            <ul class="services-list-detail row">
                                @foreach ($section->Service as $service)
                                    <li class="col-md-6">{{ $service->name }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                @if ($section->doctors && $section->doctors->isNotEmpty())
                    <div class="department-doctors-section card-styled-detail mt-4 animate__animated animate__fadeInUp"
                        data-wow-delay="0.3s">
                        <h2 class="section-heading-detail">أطباؤنا في قسم {{ $section->name }}</h2>
                        <div class="row g-3 department-doctors-grid">
                            @foreach ($section->doctors as $doctor)
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="doctor-card-item-small">
                                        <div class="doctor-image-small">
                                            <a href="{{ route('website.doctor.details', $doctor->id) }}"
                                                title="{{ $doctor->name }}">
                                                @if ($doctor->image)
                                                    <img src="{{ Url::asset('Dashboard/img/doctors/' . $doctor->image->filename) }}"
                                                        class="doctor-avatar" alt="{{ trans('doctors.img') }}">
                                                @else
                                                    <img src="{{ Url::asset('Dashboard/img/doctor_default.png') }}"
                                                        class="doctor-avatar" alt="صورة افتراضية">
                                                @endif
                                            </a>
                                        </div>
                                        <div class="doctor-info-small">
                                            <h5><a
                                                    href="{{ route('website.doctor.details', $doctor->id) }}">{{ $doctor->name }}</a>
                                            </h5>
                                            <span class="doctor-specialization-small">
                                                {{-- Assuming specializations is a collection; adjust if it's a string --}}
                                                {{ $doctor->specializations_display ?? ($doctor->specializations && $doctor->specializations->isNotEmpty() ? $doctor->specializations->pluck('name')->take(2)->implode(', ') : 'أخصائي بالقسم') }}
                                            </span>
                                            <a href="{{ route('website.doctor.details', $doctor->id) }}"
                                                class="btn-view-profile-small">
                                                <i class="fas fa-user-circle"></i> الملف الشخصي
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="alert empty-state-alert mt-4 animate__animated animate__fadeInUp" data-wow-delay="0.3s">
                        <i class="fas fa-user-md fa-2x mb-2"></i><br>
                        لا يوجد أطباء مسجلون في هذا القسم حاليًا.
                    </div>
                @endif
            </div>

            <!-- Sidebar Side -->
            <div class="col-lg-4">
                <aside class="department-sidebar">
                    <div class="sidebar-widget-detail card-styled-detail animate__animated animate__fadeInUp"
                        data-wow-delay="0.4s">
                        <h4 class="widget-title-detail">الأقسام الأخرى</h4>
                        @if ($otherSections->isNotEmpty())
                            <ul class="other-departments-list">
                                @foreach ($otherSections as $otherSection)
                                    <li class="{{ $otherSection->id == $section->id ? 'current-dept' : '' }}">
                                        <a href="{{ route('website.department.details', $otherSection->id) }}">
                                            {{ $otherSection->name }}
                                            <i
                                                class="fas {{ LaravelLocalization::getCurrentLocaleDirection() == 'rtl' ? 'fa-chevron-left' : 'fa-chevron-right' }} arrow-icon"></i>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted small">لا توجد أقسام أخرى لعرضها حاليًا.</p>
                        @endif
                        <a href="{{ route('website.departments.all') }}"
                            class="btn btn-sm btn-outline-primary mt-3 d-block text-center">
                            <i class="fas fa-th-list"></i> عرض جميع الأقسام
                        </a>
                    </div>

                    <div class="sidebar-widget-detail contact-widget-detail card-styled-detail animate__animated animate__fadeInUp"
                        data-wow-delay="0.5s">
                        <div class="widget-content-styled">
                            <div class="contact-icon-top"><i class="fas fa-headset"></i></div>
                            <h4 class="widget-title-detail text-white border-white mb-3">تحتاج مساعدة أو لديك استفسار؟
                            </h4>
                            <p class="text-contact opacity-90">فريقنا جاهز لخدمتك. يمكنك التواصل معنا عبر المعلومات
                                التالية أو زيارتنا مباشرة.</p>
                            <div class="contact-info-item">
                                <a href="tel:{{ $settings['hospital_phone'] ?? '+1234567890' }}"><i
                                        class="fas fa-phone-alt"></i>
                                    {{ $settings['hospital_phone'] ?? '(123) 456-7890' }}</a>
                            </div>
                            <div class="contact-info-item">
                                <a href="mailto:{{ $settings['hospital_email'] ?? 'info@example.com' }}"><i
                                        class="fas fa-envelope"></i>
                                    {{ $settings['hospital_email'] ?? 'info@example.com' }}</a>
                            </div>
                            {{-- Add address or link to contact page if available --}}
                            {{-- <a href="{{route('contact')}}" class="btn btn-light btn-sm mt-3">صفحة التواصل الكاملة</a> --}}
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </main>

    <footer class="standalone-detail-footer">
        <p>© {{ date('Y') }} جميع الحقوق محفوظة - {{ config('app.name', 'منصتنا الطبية') }}.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    {{--
    <script src="https://cdnjs.cloudflare.com/ajax/libs/wow/1.1.2/wow.min.js"></script>
    <script>
        // document.addEventListener('DOMContentLoaded', function () {
        //   if (typeof WOW !== 'undefined') { new WOW({ boxClass: 'animate__animated', offset: 50, mobile: true, live: true }).init(); }
        // });
    </script>
    --}}
</body>

</html>
