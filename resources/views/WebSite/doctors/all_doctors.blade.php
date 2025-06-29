<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title>@yield('title', 'فريق أطبائنا المتميز') - {{ config('app.name') }}</title>

    <!-- Preconnects for faster loading -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">

    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&family=Cairo:wght@400;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/choices.js/public/assets/styles/choices.min.css" />

    <!-- Custom Styles -->
    <style>
        :root {
            --docpage-primary-color: #2c7be5;
            --docpage-primary-color-rgb: 44, 123, 229;
            --docpage-secondary-color: #00d97e;
            --docpage-accent-color: #f6c343;
            --docpage-text-color: #4a4a4a;
            --docpage-heading-color: #2d3748;
            --docpage-bg-color: #f8fafc;
            --docpage-card-bg: #ffffff;
            --docpage-border-color: #e2e8f0;
            --docpage-radius: 10px;
            --docpage-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --docpage-shadow-hover: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --docpage-font-primary: 'Tajawal', sans-serif;
            --docpage-font-secondary: 'Cairo', sans-serif;
            --docpage-transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        html,
        body {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: var(--docpage-font-primary);
            background-color: var(--docpage-bg-color);
            color: var(--docpage-text-color);
            line-height: 1.6;
            scroll-behavior: smooth;
        }

        *,
        *::before,
        *::after {
            box-sizing: inherit;
        }

        .doctors-standalone-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 30px 20px;
        }

        /* Header Styles */
        .standalone-header {
            background-color: var(--docpage-card-bg);
            padding: 15px 0;
            box-shadow: var(--docpage-shadow);
            margin-bottom: 30px;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .site-title {
            font-family: var(--docpage-font-secondary);
            font-size: 2rem;
            color: var(--docpage-primary-color);
            margin: 0;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .site-title i {
            color: var(--docpage-secondary-color);
        }

        .back-to-home {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            color: var(--docpage-text-color);
            text-decoration: none;
            font-weight: 500;
            transition: var(--docpage-transition);
        }

        .back-to-home:hover {
            color: var(--docpage-primary-color);
        }

        /* Page Title Section */
        .doctors-page-title-standalone {
            padding: 40px 0 30px;
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }

        .doctors-page-title-standalone h1 {
            font-family: var(--docpage-font-secondary);
            font-size: 2.8rem;
            color: var(--docpage-heading-color);
            font-weight: 700;
            margin-bottom: 15px;
            line-height: 1.3;
        }

        .title-description-standalone {
            font-size: 1.2rem;
            color: #64748b;
            max-width: 700px;
            margin: 0 auto 20px;
            line-height: 1.7;
        }

        .search-tags {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 8px;
            margin-top: 20px;
        }

        .search-tag {
            background-color: #e2e8f0;
            color: #4a5568;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .search-tag .remove-tag {
            cursor: pointer;
            color: #718096;
            transition: var(--docpage-transition);
        }

        .search-tag .remove-tag:hover {
            color: #e53e3e;
        }

        /* Advanced Filter Panel */
        .doctors-filter-panel-standalone {
            background-color: var(--docpage-card-bg);
            padding: 25px;
            border-radius: var(--docpage-radius);
            margin-bottom: 40px;
            box-shadow: var(--docpage-shadow);
            transition: var(--docpage-transition);
        }

        .filter-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .filter-title {
            font-family: var(--docpage-font-secondary);
            font-size: 1.4rem;
            color: var(--docpage-heading-color);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .filter-toggle {
            background: none;
            border: none;
            color: var(--docpage-primary-color);
            font-size: 0.9rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .advanced-filters {
            display: none;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid var(--docpage-border-color);
        }

        .advanced-filters.show {
            display: block;
            animation: fadeIn 0.3s ease-in-out;
        }

        .filter-group {
            margin-bottom: 15px;
        }

        .filter-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--docpage-heading-color);
        }

        .form-control,
        .form-select {
            border: 1px solid var(--docpage-border-color);
            border-radius: calc(var(--docpage-radius) - 2px);
            font-size: 1rem;
            padding: 0.7rem 1rem;
            height: auto;
            transition: var(--docpage-transition);
            width: 100%;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--docpage-primary-color);
            box-shadow: 0 0 0 0.2rem rgba(var(--docpage-primary-color-rgb), 0.15);
        }

        .choices__inner {
            border: 1px solid var(--docpage-border-color) !important;
            border-radius: calc(var(--docpage-radius) - 2px) !important;
            padding: 0.5rem 1rem !important;
            min-height: auto !important;
        }

        .choices[data-type*="select-one"] .choices__inner {
            padding-bottom: 0.5rem !important;
        }

        .choices__list--dropdown {
            border: 1px solid var(--docpage-border-color) !important;
            border-radius: calc(var(--docpage-radius) - 2px) !important;
            box-shadow: var(--docpage-shadow) !important;
        }

        .btn {
            font-size: 1rem;
            padding: 0.7rem 1.3rem;
            border-radius: calc(var(--docpage-radius) - 2px);
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: var(--docpage-transition);
        }

        .btn-primary {
            background-color: var(--docpage-primary-color);
            border-color: var(--docpage-primary-color);
        }

        .btn-primary:hover {
            background-color: color-mix(in srgb, var(--docpage-primary-color) 85%, black);
            border-color: color-mix(in srgb, var(--docpage-primary-color) 85%, black);
            transform: translateY(-1px);
        }

        .btn-outline-secondary {
            color: var(--docpage-text-color);
            border-color: var(--docpage-border-color);
        }

        .btn-outline-secondary:hover {
            background-color: var(--docpage-bg-color);
            color: var(--docpage-primary-color);
            border-color: var(--docpage-border-color);
        }

        .rating-filter {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .star-rating {
            display: flex;
            gap: 3px;
        }

        .star-rating i {
            color: #e2e8f0;
            cursor: pointer;
            transition: var(--docpage-transition);
        }

        .star-rating i.active {
            color: var(--docpage-accent-color);
        }

        .reset-filters {
            color: var(--docpage-primary-color);
            text-decoration: none;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-top: 10px;
        }

        .reset-filters:hover {
            text-decoration: underline;
        }

        /* Doctors Grid */
        .doctors-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        /*  *********************************************** */
        .doctor-card-standalone {
            background-color: var(--docpage-card-bg);
            border-radius: var(--docpage-radius);
            box-shadow: var(--docpage-shadow);
            transition: var(--docpage-transition);
            overflow: hidden;
            text-align: center;
            display: flex;
            flex-direction: column;
            border: 1px solid var(--docpage-border-color);
            position: relative;
        }

        .doctor-card-standalone:hover {
            transform: translateY(-5px);
            box-shadow: var(--docpage-shadow-hover);
        }

        .image-wrapper-standalone {
            position: relative;
            background-color: #f1f5f9;
            height: 250px;
            overflow: hidden;
        }

        .image-wrapper-standalone img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center top;
            display: block;
        }

        .card-badges {
            position: absolute;
            top: 10px;
            right: 10px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            align-items: flex-end;
        }

        .rtl .card-badges {
            right: auto;
            left: 10px;
            align-items: flex-start;
        }

        .availability-badge-standalone {
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.75rem;
            color: white;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .status-available {
            background-color: var(--docpage-secondary-color);
        }

        .status-unavailable {
            background-color: #94a3b8;
        }

        .featured-badge {
            background-color: var(--docpage-accent-color);
            color: #4a3500;
        }

        .info-content-standalone {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .doctor-name-standalone a {
            font-family: var(--docpage-font-secondary);
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--docpage-heading-color);
            margin-bottom: 8px;
            display: block;
            text-decoration: none;
            transition: var(--docpage-transition);
        }

        .doctor-name-standalone a:hover {
            color: var(--docpage-primary-color);
        }

        .doctor-title {
            color: #64748b;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }

        .doctor-department-standalone,
        .doctor-department-standalone a {
            font-size: 0.95rem;
            color: var(--docpage-primary-color);
            font-weight: 500;
            margin-bottom: 12px;
            display: block;
            text-decoration: none;
        }

        .doctor-department-standalone a:hover {
            text-decoration: underline;
        }

        .doctor-department-standalone i {
            margin-inline-end: 6px;
        }

        .doctor-meta {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 15px;
            color: #64748b;
            font-size: 0.85rem;
        }

        .doctor-meta-item {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .doctor-rating {
            display: flex;
            justify-content: center;
            gap: 3px;
            margin: 10px 0;
        }

        .doctor-rating i {
            color: var(--docpage-accent-color);
            font-size: 0.9rem;
        }

        .rating-count {
            font-size: 0.8rem;
            color: #64748b;
        }

        .action-footer-standalone {
            padding: 0 20px 20px;
            margin-top: auto;
        }

        .action-footer-standalone .btn {
            width: 100%;
            font-weight: 500;
        }

        .action-footer-standalone .btn-outline-primary {
            border-color: var(--docpage-primary-color);
            color: var(--docpage-primary-color);
        }

        .action-footer-standalone .btn-outline-primary:hover {
            background-color: var(--docpage-primary-color);
            color: var(--docpage-card-bg);
        }

        .view-profile-btn {
            position: relative;
            overflow: hidden;
        }

        .view-profile-btn::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: var(--docpage-transition);
        }

        .view-profile-btn:hover::after {
            left: 100%;
        }

        /* **************************************** */
        /* No Results */
        .no-results-standalone {
            padding: 60px 20px;
            text-align: center;
            background-color: var(--docpage-card-bg);
            border-radius: var(--docpage-radius);
            box-shadow: var(--docpage-shadow);
        }

        .no-results-standalone img {
            max-width: 180px;
            margin-bottom: 25px;
            opacity: 0.7;
        }

        .no-results-standalone h3 {
            font-family: var(--docpage-font-secondary);
            font-size: 1.8rem;
            color: var(--docpage-heading-color);
            margin-bottom: 10px;
        }

        .no-results-standalone p {
            font-size: 1.05rem;
            color: #64748b;
            max-width: 500px;
            margin: 0 auto 25px;
            line-height: 1.7;
        }

        .no-results-standalone .btn {
            padding: 0.8rem 1.8rem;
        }

        /* Pagination */
        .pagination-standalone {
            display: flex;
            justify-content: center;
            margin-top: 50px;
        }

        .page-item .page-link {
            color: var(--docpage-primary-color);
            border: 1px solid var(--docpage-border-color);
            margin: 0 4px;
            border-radius: calc(var(--docpage-radius) - 4px) !important;
            padding: 0.5rem 0.9rem;
            transition: var(--docpage-transition);
        }

        .page-item.active .page-link {
            background-color: var(--docpage-primary-color);
            border-color: var(--docpage-primary-color);
            color: white;
        }

        .page-item.disabled .page-link {
            color: #94a3b8;
        }

        .page-item:not(.active):not(.disabled) .page-link:hover {
            background-color: #f1f5f9;
        }

        /* Footer */
        .standalone-footer {
            text-align: center;
            padding: 30px 0;
            margin-top: 60px;
            background-color: var(--docpage-card-bg);
            border-top: 1px solid var(--docpage-border-color);
        }

        .footer-content {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            flex-wrap: wrap;
        }

        .footer-link {
            color: var(--docpage-text-color);
            text-decoration: none;
            transition: var(--docpage-transition);
        }

        .footer-link:hover {
            color: var(--docpage-primary-color);
        }

        .copyright {
            font-size: 0.9rem;
            color: #64748b;
        }

        .social-links {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 10px;
        }

        .social-link {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: #f1f5f9;
            color: var(--docpage-text-color);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--docpage-transition);
        }

        .social-link:hover {
            background-color: var(--docpage-primary-color);
            color: white;
            transform: translateY(-2px);
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        .pulse-animation {
            animation: pulse 2s infinite;
        }

        /* Loading State */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .loading-overlay.active {
            opacity: 1;
            pointer-events: all;
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #f1f5f9;
            border-top: 4px solid var(--docpage-primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .doctors-page-title-standalone h1 {
                font-size: 2.3rem;
            }

            .title-description-standalone {
                font-size: 1.1rem;
            }

            .doctors-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .doctors-page-title-standalone h1 {
                font-size: 2rem;
            }

            .filter-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .filter-toggle {
                align-self: flex-end;
            }

            .doctors-grid {
                grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
                gap: 20px;
            }

            .image-wrapper-standalone {
                height: 220px;
            }
        }

        @media (max-width: 576px) {
            .doctors-standalone-container {
                padding: 20px 15px;
            }

            .doctors-page-title-standalone h1 {
                font-size: 1.8rem;
            }

            .title-description-standalone {
                font-size: 1rem;
            }

            .doctors-grid {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .filter-group {
                margin-bottom: 20px;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>

    <header class="standalone-header">
        <div class="doctors-standalone-container">
            <div class="header-content">
                <h2 class="site-title animate__animated animate__fadeIn">
                    <i class="fas fa-user-md"></i> فريق الأطباء
                </h2>
                <a href="{{ route('home') }}" class="back-to-home animate__animated animate__fadeIn">
                    <i class="fas fa-arrow-left"></i> العودة للرئيسية
                </a>
            </div>
        </div>
    </header>

    <main class="doctors-standalone-container">
        <!-- Page Title Section -->
        <section class="doctors-page-title-standalone animate__animated animate__fadeIn">
            <h1>ابحث عن طبيبك المثالي</h1>
            <p class="title-description-standalone">
                استخدم الفلاتر المتقدمة للعثور على الأخصائي المناسب لاحتياجاتك الصحية من بين نخبة أطبائنا المعتمدين.
            </p>

            @if (request()->hasAny(['doctor_name', 'section_id', 'rating', 'gender', 'available_today']))
                <div class="search-tags animate__animated animate__fadeIn">
                    @if (request('doctor_name'))
                        <div class="search-tag">
                            <span>الاسم: {{ request('doctor_name') }}</span>
                            <a href="{{ remove_query_param('doctor_name') }}" class="remove-tag">
                                <i class="fas fa-times"></i>
                            </a>
                        </div>
                    @endif

                    @if (request('section_id') && ($selectedSection = $sectionsForFilter->where('id', request('section_id'))->first()))
                        <div class="search-tag">
                            <span>القسم: {{ $selectedSection->name }}</span>
                            <a href="{{ remove_query_param('section_id') }}" class="remove-tag">
                                <i class="fas fa-times"></i>
                            </a>
                        </div>
                    @endif

                    @if (request('rating'))
                        <div class="search-tag">
                            <span>التقييم: {{ request('rating') }} <i class="fas fa-star"></i></span>
                            <a href="{{ remove_query_param('rating') }}" class="remove-tag">
                                <i class="fas fa-times"></i>
                            </a>
                        </div>
                    @endif

                    @if (request('gender'))
                        <div class="search-tag">
                            <span>الجنس: {{ request('gender') == 'male' ? 'ذكر' : 'أنثى' }}</span>
                            <a href="{{ remove_query_param('gender') }}" class="remove-tag">
                                <i class="fas fa-times"></i>
                            </a>
                        </div>
                    @endif

                    @if (request('available_today'))
                        <div class="search-tag">
                            <span>متاح اليوم</span>
                            <a href="{{ remove_query_param('available_today') }}" class="remove-tag">
                                <i class="fas fa-times"></i>
                            </a>
                        </div>
                    @endif
                </div>
            @endif
        </section>

        <!-- Advanced Filter Panel -->
        <section class="doctors-filter-panel-standalone animate__animated animate__fadeIn">
            <form method="get" action="{{ route('website.doctors.all') }}" id="doctorsFilterForm">
                <div class="filter-header">
                    <h3 class="filter-title"><i class="fas fa-filter"></i> فلتر الأطباء</h3>
                    <button type="button" class="filter-toggle" id="toggleAdvancedFilters">
                        <i class="fas fa-sliders-h"></i> فلاتر متقدمة
                    </button>
                </div>

                <div class="row g-3 align-items-end">
                    <div class="col-lg-4 col-md-6">
                        <div class="filter-group">
                            <label for="docNameFilterStandalone" class="filter-label">اسم الطبيب</label>
                            <input type="text" class="form-control" id="docNameFilterStandalone" name="doctor_name"
                                value="{{ request('doctor_name') }}" placeholder="ابحث باسم الطبيب...">
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="filter-group">
                            <label for="sectionFilterStandalone" class="filter-label">القسم الطبي</label>
                            <select class="form-select" id="sectionFilterStandalone" name="section_id">
                                <option value="">كل الأقسام</option>
                                @foreach ($sectionsForFilter as $section)
                                    <option value="{{ $section->id }}"
                                        {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                        {{ $section->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="filter-group">
                            <label class="filter-label">التقييم</label>
                            <input type="hidden" id="ratingInput" name="rating" value="{{ request('rating') }}">
                            <div class="rating-filter">
                                <div class="star-rating" id="starRating">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <i class="fas fa-star {{ request('rating') == $i ? 'active' : '' }}"
                                            data-rating="{{ $i }}"></i>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-6 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> بحث
                        </button>
                    </div>
                </div>

                <!-- Advanced Filters -->
                <div class="advanced-filters {{ request()->hasAny(['gender', 'available_today', 'experience']) ? 'show' : '' }}"
                    id="advancedFilters">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="filter-group">
                                <label class="filter-label">الجنس</label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender" id="genderAll"
                                            value="" {{ !request('gender') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="genderAll">الكل</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender"
                                            id="genderMale" value="male"
                                            {{ request('gender') == 'male' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="genderMale">ذكر</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="gender"
                                            id="genderFemale" value="female"
                                            {{ request('gender') == 'female' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="genderFemale">أنثى</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="filter-group">
                                <label for="experienceFilter" class="filter-label">سنوات الخبرة</label>
                                <select class="form-select" id="experienceFilter" name="experience">
                                    <option value="">أي خبرة</option>
                                    <option value="5" {{ request('experience') == '5' ? 'selected' : '' }}>5+
                                        سنوات</option>
                                    <option value="10" {{ request('experience') == '10' ? 'selected' : '' }}>10+
                                        سنوات</option>
                                    <option value="15" {{ request('experience') == '15' ? 'selected' : '' }}>15+
                                        سنوات</option>
                                    <option value="20" {{ request('experience') == '20' ? 'selected' : '' }}>20+
                                        سنوات</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="filter-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="availableToday"
                                        name="available_today" value="1"
                                        {{ request('available_today') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="availableToday">متاح اليوم فقط</label>
                                </div>

                                <div class="form-check mt-2">
                                    <input class="form-check-input" type="checkbox" id="featuredDoctors"
                                        name="featured" value="1" {{ request('featured') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="featuredDoctors">الأطباء المميزين فقط</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('website.doctors.all') }}" class="reset-filters">
                            <i class="fas fa-undo-alt"></i> إعادة تعيين الفلاتر
                        </a>
                    </div>
                </div>
            </form>
        </section>

        <!-- Doctors Grid -->
        @if ($doctors->isNotEmpty())
            <div class="doctors-grid">
                @foreach ($doctors as $doctor)
                    <div class="doctor-card-standalone animate__animated animate__fadeInUp"
                        data-wow-delay="{{ ($loop->index % 6) * 0.1 + 0.2 }}s">
                        <div class="image-wrapper-standalone">
                            <a href="{{ route('website.doctor.details', $doctor->id) }}">
                                <img src="{{ $doctor->image_url ?? ($doctor->image && $doctor->image->filename ? asset('Dashboard/img/doctors/' . $doctor->image->filename) : asset('WebSite/images/resource/doctor-placeholder.png')) }}"
                                    alt="{{ $doctor->name }}"
                                    onerror="this.onerror=null; this.src='{{ asset('Dashboard/img/doctor_default.png') }}';">
                            </a>

                            <div class="card-badges">
                                <span
                                    class="availability-badge-standalone {{ $doctor->status ? 'status-available' : 'status-unavailable' }}">
                                    <i class="fas fa-{{ $doctor->status ? 'check-circle' : 'times-circle' }}"></i>
                                    {{ $doctor->status ? 'متاح' : 'غير متاح' }}
                                </span>

                                @if ($doctor->is_featured)
                                    <span class="availability-badge-standalone featured-badge">
                                        <i class="fas fa-star"></i> مميز
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="info-content-standalone">
                            <h4 class="doctor-name-standalone">
                                <a href="{{ route('website.doctor.details', $doctor->id) }}">{{ $doctor->name }}</a>
                            </h4>

                            @if ($doctor->title)
                                <p class="doctor-title">{{ $doctor->title }}</p>
                            @endif

                            @if ($doctor->section)
                                <p class="doctor-department-standalone">
                                    <a href="{{ route('website.department.details', $doctor->section->id) }}">
                                        <i class="fas fa-stethoscope"></i>
                                        {{ $doctor->section->name }}
                                    </a>
                                </p>
                            @endif
                        </div>

                        <div class="action-footer-standalone">
                            <a href="{{ route('website.doctor.details', $doctor->id) }}"
                                class="btn btn-outline-primary view-profile-btn">
                                <i class="fas fa-id-badge"></i> عرض الملف الشخصي
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($doctors->hasPages())
                <nav class="pagination-standalone" aria-label="Doctor list pagination">
                    {{ $doctors->appends(request()->query())->onEachSide(1)->links() }}
                </nav>
            @endif
        @else
            <div class="no-results-standalone animate__animated animate__fadeIn">
                <img src="{{ asset('WebSite/images/icons/search-not-found.svg') }}" alt="لا يوجد أطباء">
                <h3>لم يتم العثور على أطباء</h3>
                <p>عذرًا، لا يوجد أطباء يطابقون معايير البحث الحالية. حاول تعديل الفلاتر أو <a
                        href="{{ route('website.doctors.all') }}">عرض كل الأطباء</a>.</p>
                <a href="{{ route('website.doctors.all') }}" class="btn btn-primary">
                    <i class="fas fa-undo-alt"></i> إعادة تعيين الفلاتر
                </a>
            </div>
        @endif
    </main>

    <footer class="standalone-footer">
        <div class="doctors-standalone-container">
            <div class="footer-content">
                <div class="footer-links">
                    <a href="#" class="footer-link">من نحن</a>
                    <a href="#" class="footer-link">خدماتنا</a>
                    <a href="#" class="footer-link">اتصل بنا</a>
                    <a href="#" class="footer-link">سياسة الخصوصية</a>
                </div>

                <div class="social-links">
                    <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                </div>

                <p style="margin: 0; font-size: 14px;">© حقوق النشر {{ date('Y') }} ، جميع الحقوق محفوظة لـ <a
                        href="{{ route('home') }}" style="color: #00bcd4; text-decoration: none;">نظام إدارة
                        المستشفيات</a>.
                    <br class="d-sm-none"> <!-- فاصل سطر على الشاشات الصغيرة فقط -->
                    تصميم وتطوير <a href="https://www.facebook.com/Momensarsour" target="_blank"
                        style="color: #00bcd4; text-decoration: none;">Mo'men Sarsour & Yousef Rajab</a>
                </p>

            </div>
        </div>
    </footer>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/choices.js/public/assets/scripts/choices.min.js"></script>

    <!-- Custom JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Choices.js for better select elements
            const selectElements = document.querySelectorAll('select.form-select');
            selectElements.forEach(select => {
                new Choices(select, {
                    searchEnabled: false,
                    itemSelectText: '',
                    shouldSort: false,
                });
            });

            // Toggle advanced filters
            const toggleFiltersBtn = document.getElementById('toggleAdvancedFilters');
            const advancedFilters = document.getElementById('advancedFilters');

            toggleFiltersBtn.addEventListener('click', function() {
                advancedFilters.classList.toggle('show');
                const icon = this.querySelector('i');
                if (advancedFilters.classList.contains('show')) {
                    icon.classList.remove('fa-sliders-h');
                    icon.classList.add('fa-times');
                } else {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-sliders-h');
                }
            });

            // Star rating filter
            const stars = document.querySelectorAll('#starRating i');
            const ratingInput = document.getElementById('ratingInput');

            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const rating = this.getAttribute('data-rating');

                    // Toggle active class
                    stars.forEach(s => {
                        s.classList.remove('active');
                        if (s.getAttribute('data-rating') <= rating) {
                            s.classList.add('active');
                        }
                    });

                    // Set or clear the rating value
                    if (ratingInput.value === rating) {
                        ratingInput.value = '';
                        stars.forEach(s => s.classList.remove('active'));
                    } else {
                        ratingInput.value = rating;
                    }

                    // Submit the form
                    document.getElementById('doctorsFilterForm').submit();
                });
            });

            // Show loading overlay when form is submitted
            const filterForm = document.getElementById('doctorsFilterForm');
            const loadingOverlay = document.getElementById('loadingOverlay');

            filterForm.addEventListener('submit', function() {
                loadingOverlay.classList.add('active');
            });

            // Remove query parameter when clicking on tag remove button
            document.querySelectorAll('.remove-tag').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.location.href = this.getAttribute('href');
                });
            });

            // Animate elements when they come into view
            const animateOnScroll = function() {
                const elements = document.querySelectorAll('.animate__animated');

                elements.forEach(element => {
                    const elementPosition = element.getBoundingClientRect().top;
                    const windowHeight = window.innerHeight;

                    if (elementPosition < windowHeight - 100) {
                        const animationClass = element.classList.contains('animate__fadeInUp') ?
                            'animate__fadeInUp' : 'animate__fadeIn';
                        element.style.opacity = '1';
                        element.classList.add(animationClass);
                    }
                });
            };

            // Run once on load and then on scroll
            animateOnScroll();
            window.addEventListener('scroll', animateOnScroll);
        });

        // Helper function to remove a query parameter from URL
        function remove_query_param(param) {
            const url = new URL(window.location.href);
            url.searchParams.delete(param);
            return url.toString();
        }
    </script>
</body>

</html>
