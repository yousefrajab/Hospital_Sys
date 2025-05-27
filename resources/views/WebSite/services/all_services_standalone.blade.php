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
            /* Define your NEW theme for this standalone services page */
            --servicepage-primary-color: {{ $settings['servicepage_primary_color'] ?? '#673AB7' }}; /* Deep Purple */
            --servicepage-primary-color-rgb: {{ implode(',', sscanf($settings['servicepage_primary_color'] ?? '#673AB7', '#%02x%02x%02x')) ?? '103, 58, 183' }};
            --servicepage-secondary-color: {{ $settings['servicepage_secondary_color'] ?? '#FFC107' }}; /* Amber for accents */
            --servicepage-text-color: #424242; /* Slightly darker text */
            --servicepage-heading-color: #212121;
            --servicepage-light-text: #757575;
            --servicepage-bg-color: #f5f5f5; /* Even lighter gray */
            --servicepage-card-bg: #ffffff;
            --servicepage-border-color: #e0e0e0;
            --servicepage-radius: 12px;
            --servicepage-shadow: 0 8px 25px rgba(0, 0, 0, 0.07);
            --servicepage-font-primary: 'Cairo', sans-serif;
            --servicepage-font-secondary: 'Tajawal', sans-serif;
            --servicepage-gradient: linear-gradient(135deg, var(--servicepage-primary-color) 0%, color-mix(in srgb, var(--servicepage-primary-color) 70%, var(--servicepage-secondary-color)) 100%);
        }

        html, body { margin: 0; padding: 0; box-sizing: border-box; font-family: var(--servicepage-font-primary); background-color: var(--servicepage-bg-color); color: var(--servicepage-text-color); line-height: 1.75; font-size: 16px; }
        *, *::before, *::after { box-sizing: inherit; }

        .services-standalone-container { max-width: 1280px; margin: 0 auto; padding: 2.5rem 1rem; }

        /* Minimalist Header */
        .standalone-service-header { background-color: var(--servicepage-card-bg); padding: 1rem 0; box-shadow: 0 2px 8px rgba(0,0,0,0.05); margin-bottom: 2.5rem; }
        .standalone-service-header .container-fluid { display: flex; justify-content: space-between; align-items: center; }
        .standalone-service-header .site-branding .site-title { font-family: var(--servicepage-font-secondary); font-size: 1.7rem; color: var(--servicepage-primary-color); margin: 0; font-weight: 800; text-decoration: none; }
        .standalone-service-header .back-to-home a { color: var(--servicepage-primary-color); text-decoration: none; font-weight: 600; font-size: 0.95rem; }
        .standalone-service-header .back-to-home a i { margin-inline-end: 0.4rem; }
        .standalone-service-header .back-to-home a:hover { text-decoration: underline; }

        /* Page Title Section */
        .services-page-title-standalone {
            padding: 3.5rem 0 2.5rem; /* More padding */
            text-align: center;
            margin-bottom: 3rem;
            background: var(--servicepage-gradient);
            color: white;
            border-radius: var(--servicepage-radius);
            box-shadow: 0 10px 20px rgba(var(--servicepage-primary-color-rgb), 0.2);
        }
        .services-page-title-standalone h1 {
            font-family: var(--servicepage-font-secondary);
            font-size: 2.75rem;
            font-weight: 800;
            margin-bottom: 0.75rem;
            letter-spacing: -0.5px;
        }
        .services-page-title-standalone .title-description-standalone {
            font-size: 1.15rem;
            max-width: 750px;
            margin: 0 auto 1rem;
            opacity: 0.9;
        }
        .services-page-title-standalone .breadcrumb-custom-service { padding: 0; margin-top:1rem; list-style: none; display: flex; justify-content: center; font-size: 0.9rem; }
        .services-page-title-standalone .breadcrumb-custom-service li a { color: white; opacity:0.8; text-decoration: none; }
        .services-page-title-standalone .breadcrumb-custom-service li a:hover { opacity:1; text-decoration: underline; }
        .services-page-title-standalone .breadcrumb-custom-service li { color: white; opacity:0.6; }
        .services-page-title-standalone .breadcrumb-custom-service li+li::before { content: "/"; padding: 0 0.5rem; }


        /* Service Card Styling */
        .service-card-standalone {
            background-color: var(--servicepage-card-bg);
            border-radius: var(--servicepage-radius);
            box-shadow: var(--servicepage-shadow);
            transition: transform 0.3s ease-out, box-shadow 0.3s ease-out;
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
            border: 1px solid var(--servicepage-border-color);
            margin-bottom: 1.875rem; /* 30px */
        }
        .service-card-standalone:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(var(--servicepage-primary-color-rgb), 0.15);
        }
        .service-card-standalone .icon-wrapper-service { /* New section for icon */
            padding: 2rem 1.5rem 1.5rem;
            text-align: center;
            background-color: color-mix(in srgb, var(--servicepage-primary-color) 8%, transparent);
            border-bottom: 1px solid var(--servicepage-border-color);
        }
        .service-card-standalone .service-icon {
            font-size: 3rem;
            color: var(--servicepage-primary-color);
            margin-bottom: 0.5rem;
            line-height: 1;
            display: inline-block;
            transition: transform 0.3s ease;
        }
        .service-card-standalone:hover .service-icon {
            transform: scale(1.1);
        }

        .service-card-standalone .content-wrapper-service {
            padding: 1.75rem;
            text-align: center; /* Or right for RTL */
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        .service-card-standalone .service-name-standalone a, /* Name is a link to details if exists */
        .service-card-standalone .service-name-standalone { /* Fallback if not a link */
            font-family: var(--servicepage-font-secondary);
            font-size: 1.4rem; /* 22.4px */
            font-weight: 700;
            color: var(--servicepage-heading-color);
            margin-bottom: 0.75rem;
            display: block;
            text-decoration: none;
            transition: color 0.25s ease;
        }
        .service-card-standalone .service-name-standalone a:hover {
            color: var(--servicepage-primary-color);
        }
        .service-card-standalone .service-description-standalone {
            font-size: 0.95rem; /* 15.2px */
            color: var(--servicepage-text-color);
            line-height: 1.7;
            margin-bottom: 1.25rem;
            flex-grow: 1;
            display: -webkit-box; /* For text truncation */
            -webkit-line-clamp: 3; /* Limit to 3 lines */
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            min-height: calc(1.7em * 3);
        }
        .service-card-standalone .service-price-standalone {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--servicepage-secondary-color); /* Accent color for price */
            margin-bottom: 1.25rem;
            display: block;
        }
        .service-card-standalone .service-price-standalone small {
            font-size: 0.7em;
            color: var(--servicepage-light-text);
            font-weight: 500;
        }
        .service-card-standalone .btn-details-service {
            background: var(--servicepage-primary-color);
            color: var(--servicepage-card-bg);
            border: 2px solid var(--servicepage-primary-color);
            padding: 0.6rem 1.5rem;
            font-size: 0.9rem;
            font-weight: 600;
            border-radius: 50px; /* Pill button */
            text-decoration: none;
            display: inline-block;
            margin-top: auto; /* Pushes to bottom */
            transition: all 0.25s ease;
        }
        .service-card-standalone .btn-details-service:hover {
            background-color: var(--servicepage-card-bg);
            color: var(--servicepage-primary-color);
            transform: translateY(-2px);
        }
        .service-card-standalone .btn-details-service i { margin-inline-start: 0.5rem; }


        /* No Results Styling */
        .no-services-standalone { text-align: center; padding: 3rem 1rem; background-color: var(--servicepage-card-bg); border-radius: var(--servicepage-radius); box-shadow: var(--servicepage-shadow); }
        .no-services-standalone .no-serv-icon { font-size: 4rem; color: var(--servicepage-primary-color); opacity: 0.5; margin-bottom: 1rem; }
        .no-services-standalone h3 { font-family: var(--servicepage-font-secondary); font-size: 1.6rem; color: var(--servicepage-heading-color); margin-bottom: 0.75rem; }
        .no-services-standalone p { font-size: 1rem; color: var(--servicepage-light-text); }

        /* Pagination Styling */
        .pagination-standalone .page-item .page-link { color: var(--servicepage-primary-color); border-radius: var(--servicepage-radius); font-weight: 500; }
        .pagination-standalone .page-item.active .page-link { background-color: var(--servicepage-primary-color); border-color: var(--servicepage-primary-color); color: var(--servicepage-card-bg); }
        .pagination-standalone .page-item.disabled .page-link { color: var(--servicepage-light-text); }

        /* Footer Styling */
        .standalone-service-footer { text-align: center; padding: 1.5rem 0; margin-top: 3rem; background-color: var(--servicepage-card-bg); border-top: 1px solid var(--servicepage-border-color); font-size: 0.9rem; color: var(--servicepage-light-text); }
    </style>
</head>
<body>

    <header class="standalone-service-header">
        <div class="container-fluid">
            <div class="site-branding">
                <a href="{{ route('home') }}" class="site-title" title="العودة إلى الرئيسية {{ config('app.name') }}">
                    {{ config('app.name', 'المنصة الطبية') }}
                </a>
            </div>
            <div class="back-to-home">
                                <a href="{{ route('website.group_services.all') }}"><i class="fas fa-medkit"></i> الخدمات المجمعة</a>
                <a href="{{ route('home') }}"><i class="fas fa-home"></i> العودة للرئيسية</a>
            </div>
        </div>
    </header>

    <main class="services-standalone-container">
        <section class="services-page-title-standalone">
            <h1 class="animate__animated animate__fadeInDown">اكتشف خدماتنا الطبية المتكاملة</h1>
            <p class="title-description-standalone animate__animated animate__fadeInUp" data-wow-delay="0.2s">
                نقدم مجموعة شاملة من الخدمات الطبية عالية الجودة، المصممة خصيصًا لتلبية احتياجاتك الصحية المتنوعة ودعم رحلتك نحو العافية.
            </p>
             <ul class="breadcrumb-custom-service">
                <li><a href="{{ route('home') }}">الرئيسية</a></li>
                <li>الخدمات الطبية</li>
            </ul>
        </section>

        {{-- Add filter section here if needed in the future --}}

        <div class="row g-4"> {{-- Bootstrap 5 row with gutters --}}
            @forelse ($services as $service)
                <div class="col-lg-4 col-md-6 col-sm-12 animate__animated animate__fadeInUp" data-wow-delay="{{ ($loop->index % 3) * 0.1 + 0.3 }}s">
                    <div class="service-card-standalone">
                        <div class="icon-wrapper-service">
                            {{-- يمكنك إضافة أيقونة مميزة للخدمة هنا إذا كان لديك حقل لها في الموديل --}}
                            {{-- أو استخدام أيقونة عامة بناءً على نوع الخدمة --}}
                            <span class="service-icon {{ $service->icon_class ?? 'fas fa-briefcase-medical' }}"></span>
                        </div>
                        <div class="content-wrapper-service">
                            <h3 class="service-name-standalone">
                                {{-- إذا كان هناك صفحة تفاصيل للخدمة --}}
                                {{-- <a href="{{ route('website.service.details', $service->id) }}" title="تفاصيل خدمة {{ $service->name }}"> --}}
                                    {{ $service->name }}
                                {{-- </a> --}}
                            </h3>
                            <p class="service-description-standalone">
                                {{ Str::limit(strip_tags($service->description ?? 'وصف تفصيلي لهذه الخدمة سيكون متاحًا قريبًا.'), 120) }}
                            </p>
                            @if(isset($service->price) && is_numeric($service->price))
                                <span class="service-price-standalone">
                                    {{ number_format($service->price, 2) }} <small>{{ config('app.currency', 'ر.س') }}</small>
                                </span>
                            @endif
                            {{-- زر "اقرأ المزيد" أو "اطلب الخدمة" --}}
                            {{-- <a href="{{ route('website.service.details', $service->id) }}" class="btn-details-service">
                                تفاصيل الخدمة <i class="fas {{ LaravelLocalization::getCurrentLocaleDirection() == 'rtl' ? 'fa-arrow-left' : 'fa-arrow-right' }}"></i>
                            </a> --}}
                             <a href="{{-- route('website.appointments.book') --}}?service_id={{$service->id}}" class="btn-details-service">
                                طلب الخدمة  <i class="fas fa-calendar-alt"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="no-services-standalone animate__animated animate__fadeInUp" data-wow-delay="0.3s">
                        <div class="no-serv-icon"><i class="fas fa-notes-medical"></i></div>
                        <h3>لا توجد خدمات متاحة حاليًا</h3>
                        <p>نعمل على تحديث قائمة خدماتنا. يرجى التحقق مرة أخرى قريبًا أو التواصل معنا للاستفسار.</p>
                    </div>
                </div>
            @endforelse
        </div>


    </main>

    <footer class="standalone-service-footer">
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
