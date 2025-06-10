<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title>خدماتنا الطبية - {{ config('app.name', 'اسم المستشفى/المنصة') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            /* تأكد أن $settings يتم تمريرها بشكل صحيح من الـ Controller */
            --servicepage-primary-color: {{ $settings['servicepage_primary_color'] ?? '#673AB7' }};
            --servicepage-primary-color-rgb: {{ isset($settings['servicepage_primary_color']) ? implode(',', sscanf($settings['servicepage_primary_color'], '#%02x%02x%02x')) : '103, 58, 183' }};
            --servicepage-secondary-color: {{ $settings['servicepage_secondary_color'] ?? '#FFC107' }};
            --servicepage-text-color: #424242;
            --servicepage-heading-color: #212121;
            --servicepage-light-text: #757575;
            --servicepage-bg-color: #f5f5f5;
            --servicepage-card-bg: #ffffff;
            --servicepage-border-color: #e0e0e0;
            --servicepage-radius: 12px;
            --servicepage-shadow: 0 8px 25px rgba(0, 0, 0, 0.07);
            --servicepage-font-primary: 'Cairo', sans-serif;
            --servicepage-font-secondary: 'Tajawal', sans-serif;
            --servicepage-gradient: linear-gradient(135deg, var(--servicepage-primary-color) 0%, color-mix(in srgb, var(--servicepage-primary-color) 70%, var(--servicepage-secondary-color)) 100%);
        }
        /* --- (بقية أنماط CSS كما هي في ملفك الأصلي) --- */
        html, body { margin: 0; padding: 0; box-sizing: border-box; font-family: var(--servicepage-font-primary); background-color: var(--servicepage-bg-color); color: var(--servicepage-text-color); line-height: 1.75; font-size: 16px; }
        *, *::before, *::after { box-sizing: inherit; }
        .services-standalone-container { max-width: 1280px; margin: 0 auto; padding: 2.5rem 1rem; }
        .standalone-service-header { background-color: var(--servicepage-card-bg); padding: 1rem 0; box-shadow: 0 2px 8px rgba(0,0,0,0.05); margin-bottom: 2.5rem; }
        .standalone-service-header .container-fluid { display: flex; justify-content: space-between; align-items: center; }
        .standalone-service-header .site-branding .site-title { font-family: var(--servicepage-font-secondary); font-size: 1.7rem; color: var(--servicepage-primary-color); margin: 0; font-weight: 800; text-decoration: none; }
        .standalone-service-header .back-to-home a { color: var(--servicepage-primary-color); text-decoration: none; font-weight: 600; font-size: 0.95rem; }
        .standalone-service-header .back-to-home a i { margin-inline-end: 0.4rem; }
        .standalone-service-header .back-to-home a:hover { text-decoration: underline; }
        .services-page-title-standalone { padding: 3.5rem 0 2.5rem; text-align: center; margin-bottom: 3rem; background: var(--servicepage-gradient); color: white; border-radius: var(--servicepage-radius); box-shadow: 0 10px 20px rgba(var(--servicepage-primary-color-rgb), 0.2); }
        .services-page-title-standalone h1 { font-family: var(--servicepage-font-secondary); font-size: 2.75rem; font-weight: 800; margin-bottom: 0.75rem; letter-spacing: -0.5px; }
        .services-page-title-standalone .title-description-standalone { font-size: 1.15rem; max-width: 750px; margin: 0 auto 1rem; opacity: 0.9; }
        .services-page-title-standalone .breadcrumb-custom-service { padding: 0; margin-top:1rem; list-style: none; display: flex; justify-content: center; font-size: 0.9rem; }
        .services-page-title-standalone .breadcrumb-custom-service li a { color: white; opacity:0.8; text-decoration: none; }
        .services-page-title-standalone .breadcrumb-custom-service li a:hover { opacity:1; text-decoration: underline; }
        .services-page-title-standalone .breadcrumb-custom-service li { color: white; opacity:0.6; }
        .services-page-title-standalone .breadcrumb-custom-service li+li::before { content: "/"; padding: 0 0.5rem; }

        .service-card-standalone { background-color: var(--servicepage-card-bg); border-radius: var(--servicepage-radius); box-shadow: var(--servicepage-shadow); transition: transform 0.3s ease-out, box-shadow 0.3s ease-out; overflow: hidden; height: 100%; display: flex; flex-direction: column; border: 1px solid var(--servicepage-border-color); margin-bottom: 1.875rem; }
        .service-card-standalone:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(var(--servicepage-primary-color-rgb), 0.15); }
        .service-card-standalone .icon-wrapper-service { padding: 2rem 1.5rem 1.5rem; text-align: center; background-color: color-mix(in srgb, var(--servicepage-primary-color) 8%, transparent); border-bottom: 1px solid var(--servicepage-border-color); }
        .service-card-standalone .service-icon { font-size: 3rem; color: var(--servicepage-primary-color); margin-bottom: 0.5rem; line-height: 1; display: inline-block; transition: transform 0.3s ease; }
        .service-card-standalone:hover .service-icon { transform: scale(1.1); }
        .service-card-standalone .content-wrapper-service { padding: 1.75rem; text-align: center; flex-grow: 1; display: flex; flex-direction: column; }
        .service-card-standalone .service-name-standalone a, .service-card-standalone .service-name-standalone { font-family: var(--servicepage-font-secondary); font-size: 1.4rem; font-weight: 700; color: var(--servicepage-heading-color); margin-bottom: 0.75rem; display: block; text-decoration: none; transition: color 0.25s ease; }
        .service-card-standalone .service-name-standalone a:hover { color: var(--servicepage-primary-color); }
        .service-card-standalone .service-description-standalone { font-size: 0.95rem; color: var(--servicepage-text-color); line-height: 1.7; margin-bottom: 1.25rem; flex-grow: 1; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; min-height: calc(1.7em * 3); }
        .service-card-standalone .service-price-standalone { font-size: 1.25rem; font-weight: 700; color: var(--servicepage-secondary-color); margin-bottom: 1.25rem; display: block; }
        .service-card-standalone .service-price-standalone small { font-size: 0.7em; color: var(--servicepage-light-text); font-weight: 500; }
        .service-card-standalone .btn-details-service { background: var(--servicepage-primary-color); color: var(--servicepage-card-bg); border: 2px solid var(--servicepage-primary-color); padding: 0.6rem 1.5rem; font-size: 0.9rem; font-weight: 600; border-radius: 50px; text-decoration: none; display: inline-block; margin-top: auto; transition: all 0.25s ease; }
        .service-card-standalone .btn-details-service:hover { background-color: var(--servicepage-card-bg); color: var(--servicepage-primary-color); transform: translateY(-2px); }
        .service-card-standalone .btn-details-service i { margin-inline-start: 0.5rem; }

        /* --- New Styles for Doctor/Section Info --- */
        .service-provider-info {
            font-size: 0.85rem;
            color: var(--servicepage-light-text);
            margin-bottom: 1rem;
            margin-top: -0.5rem; /* Pull up slightly below name */
            display: flex;
            flex-wrap: wrap;
            justify-content: center; /* Center items */
            gap: 0.3rem 0.8rem; /* Gap between items */
        }
        .service-provider-info .provider-item {
            display: inline-flex; /* Changed to inline-flex */
            align-items: center;
            background-color: color-mix(in srgb, var(--servicepage-primary-color) 5%, transparent);
            padding: 0.25rem 0.6rem;
            border-radius: 20px; /* Pill shape */
            border: 1px solid color-mix(in srgb, var(--servicepage-primary-color) 15%, transparent);
        }
        .service-provider-info .provider-item i {
            margin-inline-end: 0.4rem;
            color: var(--servicepage-primary-color);
            opacity: 0.8;
        }
         /* --- (بقية أنماط CSS) --- */
        .no-services-standalone { text-align: center; padding: 3rem 1rem; background-color: var(--servicepage-card-bg); border-radius: var(--servicepage-radius); box-shadow: var(--servicepage-shadow); }
        .no-services-standalone .no-serv-icon { font-size: 4rem; color: var(--servicepage-primary-color); opacity: 0.5; margin-bottom: 1rem; }
        .no-services-standalone h3 { font-family: var(--servicepage-font-secondary); font-size: 1.6rem; color: var(--servicepage-heading-color); margin-bottom: 0.75rem; }
        .no-services-standalone p { font-size: 1rem; color: var(--servicepage-light-text); }
        .pagination-standalone .page-item .page-link { color: var(--servicepage-primary-color); border-radius: var(--servicepage-radius); font-weight: 500; }
        .pagination-standalone .page-item.active .page-link { background-color: var(--servicepage-primary-color); border-color: var(--servicepage-primary-color); color: var(--servicepage-card-bg); }
        .pagination-standalone .page-item.disabled .page-link { color: var(--servicepage-light-text); }
        .standalone-service-footer { text-align: center; padding: 1.5rem 0; margin-top: 3rem; background-color: var(--servicepage-card-bg); border-top: 1px solid var(--servicepage-border-color); font-size: 0.9rem; color: var(--servicepage-light-text); }
    </style>
</head>
<body>

    <header class="standalone-service-header">
        {{-- ... (Header content remains the same) ... --}}
        <div class="container-fluid">
            <div class="site-branding">
                <a href="{{ route('home') }}" class="site-title" title="العودة إلى الرئيسية {{ config('app.name') }}">
                    {{ config('app.name', 'المنصة الطبية') }}
                </a>
            </div>
            <div class="back-to-home">
                <a href="{{ route('website.group_services.all') }}" class="me-3"><i class="fas fa-layer-group"></i> الخدمات المجمعة</a>
                <a href="{{ route('home') }}"><i class="fas fa-home"></i> العودة للرئيسية</a>
            </div>
        </div>
    </header>

    <main class="services-standalone-container">
        <section class="services-page-title-standalone">
            {{-- ... (Title section remains the same) ... --}}
            <h1 class="animate__animated animate__fadeInDown">اكتشف خدماتنا الطبية المتكاملة</h1>
            <p class="title-description-standalone animate__animated animate__fadeInUp" data-wow-delay="0.2s">
                نقدم مجموعة شاملة من الخدمات الطبية عالية الجودة، المصممة خصيصًا لتلبية احتياجاتك الصحية المتنوعة ودعم رحلتك نحو العافية.
            </p>
             <ul class="breadcrumb-custom-service">
                <li><a href="{{ route('home') }}">الرئيسية</a></li>
                <li>الخدمات الطبية</li>
            </ul>
        </section>

        <div class="row g-4">
            @forelse ($services as $service)
                <div class="col-lg-4 col-md-6 col-sm-12 animate__animated animate__fadeInUp" data-wow-delay="{{ ($loop->index % 3) * 0.1 + 0.3 }}s">
                    <div class="service-card-standalone">
                        <div class="icon-wrapper-service">
                            <span class="service-icon {{ $service->icon_class ?? 'fas fa-briefcase-medical' }}"></span>
                        </div>
                        <div class="content-wrapper-service">
                            <h3 class="service-name-standalone">
                                {{ $service->name }}
                            </h3>

                            {{-- Display Doctor and Section Info --}}
                            @if ($service->doctor)
                                <div class="service-provider-info">
                                    <span class="provider-item" data-bs-toggle="tooltip" title="مقدم الخدمة">
                                        <i class="fas fa-user-md"></i> {{ $service->doctor->name }}
                                    </span>
                                    @if ($service->doctor->section)
                                    <span class="provider-item" data-bs-toggle="tooltip" title="القسم الطبي">
                                        <i class="fas fa-clinic-medical"></i> {{ $service->doctor->section->name }}
                                    </span>
                                    @endif
                                </div>
                            @endif

                            <p class="service-description-standalone">
                                {{ Str::limit(strip_tags($service->description ?? 'وصف تفصيلي لهذه الخدمة سيكون متاحًا قريبًا.'), 100) }}
                            </p>
                            @if(isset($service->price) && is_numeric($service->price))
                                <span class="service-price-standalone">
                                    {{ number_format($service->price, 2) }} <small>{{ config('app.currency', 'ر.س') }}</small>
                                </span>
                            @endif
                             <a href="{{-- route('website.appointments.book') --}}?service_id={{$service->id}}" class="btn-details-service">
                                طلب الخدمة <i class="fas fa-calendar-check"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                {{-- ... (No services message remains the same) ... --}}
                <div class="col-12">
                    <div class="no-services-standalone animate__animated animate__fadeInUp" data-wow-delay="0.3s">
                        <div class="no-serv-icon"><i class="fas fa-notes-medical"></i></div>
                        <h3>لا توجد خدمات متاحة حاليًا</h3>
                        <p>نعمل على تحديث قائمة خدماتنا. يرجى التحقق مرة أخرى قريبًا أو التواصل معنا للاستفسار.</p>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if ($services->hasPages())
            <div class="d-flex justify-content-center mt-5 pagination-standalone">
                {{ $services->links('pagination::bootstrap-5') }}
            </div>
        @endif

    </main>

    <footer class="standalone-service-footer">
        {{-- ... (Footer content remains the same) ... --}}
        <p>© {{ date('Y') }} جميع الحقوق محفوظة - {{ config('app.name', 'منصتنا الطبية') }}.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Bootstrap tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
              return new bootstrap.Tooltip(tooltipTriggerEl, {
                container: 'body' // Recommended for better positioning
              })
            })
        });
    </script>
</body>
</html>
