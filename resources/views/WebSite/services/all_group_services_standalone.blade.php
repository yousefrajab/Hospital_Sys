<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title>باقات الخدمات - {{ config('app.name', 'المنصة الطبية') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700&family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <style>
        :root {
            --page-primary-color: {{ $settings['servicepage_primary_color'] ?? '#2E7D32' }}; /* استخدام نفس المتغيرات لتوحيد المظهر */
            --page-primary-color-rgb: {{ isset($settings['servicepage_primary_color']) ? implode(',', sscanf($settings['servicepage_primary_color'], '#%02x%02x%02x')) : '46, 125, 50' }};
            --page-secondary-color: {{ $settings['servicepage_secondary_color'] ?? '#FFAB00' }};
            --page-text-color: #333;
            --page-heading-color: #111;
            --page-bg-color: #f4f6f8;
            --page-card-bg: #ffffff;
            --page-border-color: #e0e0e0;
            --page-radius: 8px;
            --page-shadow: 0 5px 15px rgba(0, 0, 0, 0.07);
            --page-font-main: 'Cairo', sans-serif;
            --page-font-heading: 'Tajawal', sans-serif;
        }

        html, body { margin: 0; padding: 0; box-sizing: border-box; font-family: var(--page-font-main); background-color: var(--page-bg-color); color: var(--page-text-color); line-height: 1.6; font-size: 16px; }
        *, *::before, *::after { box-sizing: inherit; }
        .page-container { max-width: 1200px; margin: 0 auto; padding: 2rem 1rem; }
        .page-header-minimal { background-color: var(--page-card-bg); padding: 1rem 0; box-shadow: 0 2px 5px rgba(0,0,0,0.05); margin-bottom: 2rem; }
        .page-header-minimal .container-fluid { display: flex; justify-content: space-between; align-items: center; max-width: 1200px; margin: 0 auto; padding: 0 1rem;}
        .page-header-minimal .site-title-minimal { font-family: var(--page-font-heading); font-size: 1.5rem; color: var(--page-primary-color); margin: 0; font-weight: 700; text-decoration: none; }
        .page-header-minimal .nav-links-minimal a { color: var(--page-primary-color); text-decoration: none; font-weight: 500; font-size: 0.9rem; margin-inline-start:1rem;}
        .page-header-minimal .nav-links-minimal a:hover { text-decoration: underline; }
        .page-main-title { text-align: center; margin-bottom: 2.5rem; }
        .page-main-title h1 { font-family: var(--page-font-heading); font-size: 2.2rem; color: var(--page-heading-color); font-weight: 700; margin-bottom: 0.5rem; }
        .page-main-title p { font-size: 1.05rem; color: #666; max-width: 600px; margin: 0 auto; }

        .group-service-card { background-color: var(--page-card-bg); border-radius: var(--page-radius); box-shadow: var(--page-shadow); transition: transform 0.25s ease, box-shadow 0.25s ease; overflow: hidden; height: 100%; display: flex; flex-direction: column; border: 1px solid var(--page-border-color); margin-bottom: 1.5rem; }
        .group-service-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .group-service-card .card-header-block { padding: 1.25rem 1.5rem; background-color: color-mix(in srgb, var(--page-primary-color) 8%, transparent); border-bottom: 1px solid var(--page-border-color); }
        .group-service-card .group-name { font-family: var(--page-font-heading); font-size: 1.35rem; font-weight: 700; color: var(--page-primary-color); margin-bottom: 0.25rem; }
        .group-service-card .group-price { font-size: 1.5rem; font-weight: 700; color: var(--page-secondary-color); display: block; }
        .group-service-card .group-price small { font-size: 0.65em; color: var(--page-text-color); font-weight: 500; }
        .group-service-card .card-body-block { padding: 1.5rem; flex-grow: 1; }
        .group-service-card .group-notes { font-size: 0.9rem; color: #555; margin-bottom: 1rem; line-height: 1.7; min-height: 3.4em; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; }
        .group-service-card .services-list-title { font-family: var(--page-font-heading); font-size: 1rem; font-weight: 600; color: var(--page-heading-color); margin-bottom: 0.75rem; margin-top: 1rem; padding-top: 0.75rem; border-top: 1px dashed var(--page-border-color); }
        .group-service-card .services-list { list-style: none; padding: 0; margin: 0; }
        .group-service-card .services-list li {
            padding: 0.4rem 0; color: #444;
            display: flex; flex-direction: column; /* Stack items vertically */
            align-items: flex-start; /* Align to start for RTL/LTR */
            margin-bottom: 0.5rem;
        }
        .group-service-card .services-list li .service-item-name {
            display: flex; align-items: center; font-size: 0.9rem; font-weight: 500;
        }
        .group-service-card .services-list li .fa-check { color: var(--page-secondary-color); margin-inline-end: 0.5rem; font-size:0.8em; }
        .group-service-card .services-list li .service-item-meta { /* For doctor/section */
            font-size: 0.75rem; color: #777;
            margin-inline-start: 1.5rem; /* Indent under service name */
            display: flex; flex-wrap: wrap; gap: 0.5rem; margin-top: 0.2rem;
        }
        .group-service-card .services-list li .service-item-meta span { display: inline-flex; align-items: center; }
        .group-service-card .services-list li .service-item-meta i { margin-inline-end: 0.3rem; opacity: 0.7; }

        .group-service-card .card-footer-block { padding: 1.25rem 1.5rem; background-color: #f9f9f9; border-top: 1px solid var(--page-border-color); text-align: center; }
        .group-service-card .btn-view-details { background-color: var(--page-primary-color); color: white; border: none; padding: 0.6rem 1.5rem; font-size: 0.9rem; font-weight: 600; border-radius: 50px; text-decoration: none; transition: background-color 0.2s ease; }
        .group-service-card .btn-view-details:hover { background-color: color-mix(in srgb, var(--page-primary-color) 80%, black); }
        .no-results-message { text-align: center; padding: 2rem; background-color: var(--page-card-bg); border-radius: var(--page-radius); box-shadow: var(--page-shadow); }
        .no-results-message i { font-size: 3rem; color: var(--page-primary-color); opacity:0.7; margin-bottom:1rem; }
        .no-results-message h4 { font-family: var(--page-font-heading); font-size: 1.4rem; color: var(--page-heading-color); }
        .no-results-message p { color: #666; }
        .pagination-block .page-item .page-link { color: var(--page-primary-color); border-radius: var(--page-radius); }
        .pagination-block .page-item.active .page-link { background-color: var(--page-primary-color); border-color: var(--page-primary-color); color: white; }
        .page-footer-minimal { text-align: center; padding: 1.5rem 0; margin-top: 2.5rem; background-color: var(--page-card-bg); border-top: 1px solid var(--page-border-color); font-size: 0.85rem; color: #777; }
    </style>
</head>
<body>
    <header class="page-header-minimal">
        {{-- ... (Header content remains the same) ... --}}
         <div class="container-fluid">
            <div class="site-branding">
                <a href="{{ route('home') }}" class="site-title-minimal">
                    {{ config('app.name', 'المنصة الطبية') }}
                </a>
            </div>
            <div class="nav-links-minimal">
                <a href="{{ route('website.services.all') }}" class="me-3"><i class="fas fa-medkit"></i> الخدمات الفردية</a>
                <a href="{{ route('home') }}"><i class="fas fa-home"></i> الرئيسية</a>
            </div>
        </div>
    </header>

    <main class="page-container">
        <section class="page-main-title">
             {{-- ... (Title section remains the same) ... --}}
            <h1>باقات الخدمات المتاحة</h1>
            <p>تصفح مجموعات الخدمات المتكاملة التي نقدمها لتلبية احتياجاتك الصحية الشاملة.</p>
        </section>

        <div class="row g-4">
            @forelse ($groupedServices as $group)
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="group-service-card">
                        <div class="card-header-block">
                            <h3 class="group-name">{{ $group->name }}</h3>
                            @if(isset($group->Total_with_tax))
                            <span class="group-price">
                                {{ number_format($group->Total_with_tax, 2) }}
                                <small>{{  config('app.currency', 'ر.س') }}</small>
                            </span>
                            @endif
                        </div>
                        <div class="card-body-block">
                            @if($group->notes)
                                <p class="group-notes">{{ Str::limit(strip_tags($group->notes), 100) }}</p>
                            @endif

                            @if($group->service_group && $group->service_group instanceof \Illuminate\Support\Collection && $group->service_group->isNotEmpty())
                                <h4 class="services-list-title">تشمل هذه الباقة:</h4>
                                <ul class="services-list">
                                    @foreach($group->service_group->take(4) as $service)
                                        <li>
                                            <div class="service-item-name">
                                                <i class="fas fa-check"></i> {{ $service->name }}
                                                @if(isset($service->pivot) && isset($service->pivot->quantity) && $service->pivot->quantity > 1)
                                                    <small class="text-muted ms-1">(الكمية: {{ $service->pivot->quantity }})</small>
                                                @endif
                                            </div>
                                            {{-- Display Doctor and Section for each service in the group --}}
                                            @if ($service->doctor)
                                                <div class="service-item-meta">
                                                    <span data-bs-toggle="tooltip" title="مقدم الخدمة"><i class="fas fa-user-md"></i> {{ $service->doctor->name }}</span>
                                                    @if ($service->doctor->section)
                                                        <span data-bs-toggle="tooltip" title="القسم الطبي"><i class="fas fa-clinic-medical"></i> {{ $service->doctor->section->name }}</span>
                                                    @endif
                                                </div>
                                            @endif
                                        </li>
                                    @endforeach
                                    @if($group->service_group->count() > 4)
                                        <li class="text-muted mt-1"><i class="fas fa-ellipsis-h"></i> وخدمات أخرى...</li>
                                    @endif
                                </ul>
                            @else
                                <p class="text-muted small mt-2">لا توجد تفاصيل للخدمات المتضمنة في هذه الباقة حالياً.</p>
                            @endif
                        </div>
                        <div class="card-footer-block">
                            <a href="{{-- route('website.group_service.details', $group->id) --}}" class="btn-view-details">
                                طلب الباقة أو معرفة المزيد
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                {{-- ... (No grouped services message remains the same) ... --}}
                <div class="col-12">
                    <div class="no-results-message">
                        <i class="fas fa-archive"></i>
                        <h4>لا توجد باقات خدمات متاحة حاليًا.</h4>
                        <p>يرجى التحقق مرة أخرى قريبًا، أو تصفح <a href="{{ route('website.services.all') }}">خدماتنا الفردية</a>.</p>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Pagination for Grouped Services --}}
        @if ($groupedServices->hasPages())
            <div class="d-flex justify-content-center mt-5 pagination-block">
                {{ $groupedServices->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </main>

    <footer class="page-footer-minimal">
        {{-- ... (Footer content remains the same) ... --}}
        <p>© {{ date('Y') }} جميع الحقوق محفوظة - {{ config('app.name', 'المنصة الطبية') }}.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Bootstrap tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
              return new bootstrap.Tooltip(tooltipTriggerEl, {
                container: 'body'
              })
            })
        });
    </script>
</body>
</html>
