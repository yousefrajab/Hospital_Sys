<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title>الأقسام الطبية - {{ config('app.name', 'اسم المستشفى/المنصة') }}</title>

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
            /* يمكنك استبدال هذه القيم بالألوان المفضلة لديك لهذه الصفحة المستقلة */
            --deptpage-primary-color: {{ $settings['deptpage_primary_color'] ?? '#1E88E5' }};
            /* A strong, professional blue */
            --deptpage-primary-color-rgb: {{ implode(',', sscanf($settings['deptpage_primary_color'] ?? '#1E88E5', '#%02x%02x%02x')) ?? '30, 136, 229' }};
            --deptpage-secondary-color: {{ $settings['deptpage_secondary_color'] ?? '#43A047' }};
            /* A complementary green */
            --deptpage-text-color: #37474F;
            /* Darker text for readability */
            --deptpage-heading-color: #263238;
            /* Even darker for headings */
            --deptpage-light-text: #78909C;
            --deptpage-bg-color: #ECEFF1;
            /* Very light blue-gray background */
            --deptpage-card-bg: #ffffff;
            --deptpage-border-color: #CFD8DC;
            /* Softer border */
            --deptpage-radius: 12px;
            /* More rounded corners */
            --deptpage-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            --deptpage-font-primary: 'Cairo', sans-serif;
            /* Cairo for main text for a softer feel */
            --deptpage-font-secondary: 'Tajawal', sans-serif;
            /* Tajawal for headings for sharpness */
        }

        html,
        body {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: var(--deptpage-font-primary);
            background-color: var(--deptpage-bg-color);
            color: var(--deptpage-text-color);
            line-height: 1.7;
            font-size: 16px;
        }

        *,
        *::before,
        *::after {
            box-sizing: inherit;
        }

        .departments-standalone-container {
            max-width: 1240px;
            margin: 0 auto;
            padding: 2.5rem 1rem;
        }

        /* Minimalist Header for Standalone Page */
        .standalone-dept-header {
            background-color: var(--deptpage-card-bg);
            padding: 1rem 0;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 2.5rem;
        }

        .standalone-dept-header .container-fluid {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .standalone-dept-header .site-branding .site-title {
            font-family: var(--deptpage-font-secondary);
            font-size: 1.7rem;
            color: var(--deptpage-primary-color);
            margin: 0;
            font-weight: 800;
            text-decoration: none;
        }

        .standalone-dept-header .back-to-home a {
            color: var(--deptpage-primary-color);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
        }

        .standalone-dept-header .back-to-home a i {
            margin-inline-end: 0.4rem;
        }

        .standalone-dept-header .back-to-home a:hover {
            text-decoration: underline;
        }


        /* Page Title Section */
        .departments-page-title-standalone {
            padding: 3rem 0 2rem;
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .departments-page-title-standalone .page-icon {
            font-size: 3.5rem;
            color: var(--deptpage-primary-color);
            margin-bottom: 1rem;
            display: inline-block;
            padding: 1rem;
            background-color: color-mix(in srgb, var(--deptpage-primary-color) 10%, transparent);
            border-radius: 50%;
            line-height: 1;
            box-shadow: 0 0 0 8px color-mix(in srgb, var(--deptpage-primary-color) 20%, transparent);
        }

        .departments-page-title-standalone h1 {
            font-family: var(--deptpage-font-secondary);
            font-size: 2.5rem;
            /* 40px */
            color: var(--deptpage-heading-color);
            font-weight: 800;
            margin-bottom: 0.75rem;
            letter-spacing: -0.5px;
        }

        .departments-page-title-standalone .title-description-standalone {
            font-size: 1.1rem;
            /* 17.6px */
            color: var(--deptpage-light-text);
            max-width: 700px;
            margin: 0 auto 1rem;
        }

        /* Department Card Styling */
        .department-card-standalone {
            background-color: var(--deptpage-card-bg);
            border-radius: var(--deptpage-radius);
            box-shadow: var(--deptpage-shadow);
            transition: transform 0.3s ease-out, box-shadow 0.3s ease-out;
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
            border: 1px solid var(--deptpage-border-color);
            margin-bottom: 1.875rem;
            /* 30px */
        }

        .department-card-standalone:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 15px 35px rgba(var(--deptpage-primary-color-rgb), 0.12);
        }

        .department-card-standalone .icon-wrapper-standalone {
            padding: 2.5rem 1.5rem 1.5rem;
            /* More padding for icon */
            text-align: center;
            background: linear-gradient(145deg, color-mix(in srgb, var(--deptpage-primary-color) 95%, white) 0%, var(--deptpage-primary-color) 100%);
        }

        .department-card-standalone .icon-wrapper-standalone .department-icon {
            font-size: 3.5rem;
            /* Larger icon */
            color: var(--deptpage-card-bg);
            /* Icon color white */
            margin-bottom: 0.5rem;
            /* Space below icon if text is added */
            line-height: 1;
            display: inline-block;
            transition: transform 0.3s ease;
        }

        .department-card-standalone:hover .icon-wrapper-standalone .department-icon {
            transform: scale(1.1) rotate(-5deg);
        }

        .department-card-standalone .content-wrapper-standalone {
            padding: 1.75rem;
            text-align: center;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .department-card-standalone .department-name-standalone a {
            font-family: var(--deptpage-font-secondary);
            font-size: 1.5rem;
            /* 24px, larger name */
            font-weight: 700;
            color: var(--deptpage-heading-color);
            margin-bottom: 0.75rem;
            display: block;
            text-decoration: none;
            transition: color 0.25s ease;
        }

        .department-card-standalone .department-name-standalone a:hover {
            color: var(--deptpage-primary-color);
        }

        .department-card-standalone .department-description-standalone {
            font-size: 0.95rem;
            /* 15.2px */
            color: var(--deptpage-text-color);
            line-height: 1.7;
            margin-bottom: 1.25rem;
            flex-grow: 1;
            /* Pushes button down */
            display: -webkit-box;
            /* For text truncation */
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            min-height: calc(1.7em * 3);
            /* Ensure space for 3 lines */
        }

        .department-card-standalone .btn-details-standalone {
            background-color: transparent;
            color: var(--deptpage-primary-color);
            border: 2px solid var(--deptpage-primary-color);
            padding: 0.6rem 1.5rem;
            font-size: 0.9rem;
            font-weight: 700;
            border-radius: 50px;
            /* Pill button */
            text-decoration: none;
            display: inline-block;
            margin-top: auto;
            /* Pushes to bottom */
            transition: background-color 0.25s ease, color 0.25s ease, transform 0.2s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .department-card-standalone .btn-details-standalone:hover {
            background-color: var(--deptpage-primary-color);
            color: var(--deptpage-card-bg);
            transform: translateY(-2px);
        }

        .department-card-standalone .btn-details-standalone i {
            margin-inline-start: 0.5rem;
            /* Arrow after text */
            transition: transform 0.25s ease;
        }

        .department-card-standalone .btn-details-standalone:hover i {
            transform: translateX(3px);
        }

        .rtl .department-card-standalone .btn-details-standalone:hover i {
            transform: translateX(-3px);
        }

        .department-card-standalone .doctors-count-badge-standalone {
            margin-top: 1rem;
            font-size: 0.85rem;
            color: var(--deptpage-light-text);
            background-color: color-mix(in srgb, var(--deptpage-secondary-color) 10%, transparent);
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            display: inline-block;
        }

        .department-card-standalone .doctors-count-badge-standalone i {
            color: var(--deptpage-secondary-color);
            margin-inline-end: 0.3rem;
        }

        /* No Results Styling */
        .no-departments-standalone {
            text-align: center;
            padding: 3rem 1rem;
            background-color: var(--deptpage-card-bg);
            border-radius: var(--deptpage-radius);
            box-shadow: var(--deptpage-shadow);
        }

        .no-departments-standalone .no-dept-icon {
            font-size: 4rem;
            color: var(--deptpage-primary-color);
            opacity: 0.6;
            margin-bottom: 1rem;
        }

        .no-departments-standalone h3 {
            font-family: var(--deptpage-font-secondary);
            font-size: 1.6rem;
            color: var(--deptpage-heading-color);
            margin-bottom: 0.75rem;
        }

        .no-departments-standalone p {
            font-size: 1rem;
            color: var(--deptpage-light-text);
        }

        /* Pagination Styling */
        .pagination-standalone .page-item .page-link {
            color: var(--deptpage-primary-color);
            border-radius: var(--deptpage-radius);
            font-weight: 500;
        }

        .pagination-standalone .page-item.active .page-link {
            background-color: var(--deptpage-primary-color);
            border-color: var(--deptpage-primary-color);
            color: var(--deptpage-card-bg);
        }

        .pagination-standalone .page-item.disabled .page-link {
            color: var(--deptpage-light-text);
        }

        /* Footer Styling */
        .standalone-dept-footer {
            text-align: center;
            padding: 1.5rem 0;
            margin-top: 3rem;
            background-color: var(--deptpage-card-bg);
            border-top: 1px solid var(--deptpage-border-color);
            font-size: 0.9rem;
            color: var(--deptpage-light-text);
        }
    </style>
</head>

<body>

    <header class="standalone-dept-header">
        <div class="container-fluid">
            <div class="site-branding">
                <a href="{{ route('home') }}" class="site-title" title="العودة إلى الرئيسية {{ config('app.name') }}">
                    {{-- {{ config('app.name', 'المنصة الطبية') }} --}}
                    الأقسام الطبية
                </a>
            </div>
            <div class="back-to-home">
                <a href="{{ route('home') }}"><i class="fas fa-home"></i> العودة للرئيسية</a>
            </div>
        </div>
    </header>

    <main class="departments-standalone-container">
        <section class="departments-page-title-standalone">
            <div class="page-icon animate__animated animate__zoomIn" data-wow-delay="0.1s">
                <i class="fas fa-sitemap"></i> {{-- Or a more medical-specific icon for departments --}}
            </div>
            <h1 class="animate__animated animate__fadeInDown" data-wow-delay="0.2s">الأقسام الطبية المتخصصة</h1>
            <p class="title-description-standalone animate__animated animate__fadeInUp" data-wow-delay="0.3s">
                اكتشف مجموعة واسعة من الأقسام الطبية التي نقدمها، والمصممة لتلبية كافة احتياجاتك الصحية بأعلى معايير
                الجودة.
            </p>
        </section>

        <div class="row g-4"> {{-- Bootstrap 5 row with gutters --}}
            @forelse ($sections as $section)
                <div class="col-lg-4 col-md-6 col-sm-12 animate__animated animate__fadeInUp"
                    data-wow-delay="{{ ($loop->index % 3) * 0.1 + 0.4 }}s">
                    <div class="department-card-standalone">
                        <div class="icon-wrapper-standalone">
                            <span class="department-icon {{ $section->icon_class ?? 'fas fa-clinic-medical' }}"></span>
                            {{-- Default icon if not provided --}}
                        </div>
                        <div class="content-wrapper-standalone">
                            <h3 class="department-name-standalone">
                                <a href="{{ route('website.department.details', $section->id) }}"
                                    title="تفاصيل قسم {{ $section->name }}">
                                    {{ $section->name }}
                                </a>
                            </h3>
                            <p class="department-description-standalone">
                                {{ Str::limit(strip_tags($section->description ?? 'معلومات عن هذا القسم ستكون متاحة قريباً.'), 100) }}
                            </p>
                            @if ($section->doctors_count > 0)
                                <div class="doctors-count-badge-standalone">
                                    <i class="fas fa-user-md"></i>
                                    {{ $section->doctors_count }}
                                    {{ trans_choice('messages.doctors_count', $section->doctors_count, ['value' => $section->doctors_count]) }}
                                    {{-- Assuming you have a translation file like resources/lang/ar/messages.php
                                         'doctors_count' => '{0} لا يوجد أطباء|{1} طبيب واحد|[2,*] أطباء',
                                    --}}
                                </div>
                            @endif
                            <a href="{{ route('website.department.details', $section->id) }}"
                                class="btn-details-standalone">
                                اقرأ المزيد <i
                                    class="fas {{ LaravelLocalization::getCurrentLocaleDirection() == 'rtl' ? 'fa-arrow-left' : 'fa-arrow-right' }}"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="no-departments-standalone animate__animated animate__fadeInUp" data-wow-delay="0.4s">
                        <div class="no-dept-icon"><i class="fas fa-hospital-slash"></i></div>
                        <h3>لا توجد أقسام متاحة حاليًا</h3>
                        <p>نعمل باستمرار على إضافة وتحديث أقسامنا. يرجى التحقق مرة أخرى قريبًا.</p>
                    </div>
                </div>
            @endforelse
        </div>

        @if ($sections->hasPages())
            <nav class="pagination-standalone mt-5 d-flex justify-content-center" aria-label="قائمة الأقسام">
                {{ $sections->links() }} {{-- Default Bootstrap pagination --}}
            </nav>
        @endif
    </main>

    <footer class="standalone-dept-footer">
        <p style="margin: 0; font-size: 14px;">© حقوق النشر {{ date('Y') }} ، جميع الحقوق محفوظة لـ <a
                href="{{ route('home') }}" style="color: #00bcd4; text-decoration: none;">نظام إدارة
                المستشفيات</a>.
            <br class="d-sm-none"> <!-- فاصل سطر على الشاشات الصغيرة فقط -->
            تصميم وتطوير <a href="https://www.facebook.com/Momensarsour" target="_blank"
                style="color: #00bcd4; text-decoration: none;">Mo'men Sarsour & Yousef Rajab</a>
        </p>
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
