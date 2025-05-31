@extends('WebSite.layouts.master')
<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
@section('content')
    <!-- Main Slider Three -->
    <section class="main-slider-three">
        <div class="banner-carousel">
            <!-- Swiper -->
            <div class="swiper-wrapper">

                <div class="swiper-slide slide">
                    <div class="auto-container">
                        <div class="row clearfix">
                            <!-- Content Column -->
                            <div class="content-column col-lg-6 col-md-12 col-sm-12">
                                <div class="inner-column">
                                    <div class="sub-title" style="color: white">رعايتك هي أولويتنا</div>
                                    <h1 style="color: white">شريكك الصحي الموثوق، <br>لحياة مليئة بالعافية</h1>
                                    <div class="text">
                                        نلتزم بتقديم أعلى مستويات الرعاية الطبية بخبرات عالمية وتقنيات متطورة. صحتك معنا في
                                        أيدٍ أمينة.
                                    </div>
                                    <div class="btn-box">
                                        <a href="{{ route('website.departments.all') }}"
                                            class="theme-btn appointment-btn"><i class="fas fa-hospital-alt me-2"></i>
                                            <span> الأقسام </span></a>
                                        <a href="{{ route('website.doctors.all') }}" class="theme-btn services-btn"><i
                                                class="fas fa-user-md me-2"></i>
                                            الأطباء</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Image Column -->
                            <div class="image-column col-lg-6 col-md-12 col-sm-12">
                                <div class="inner-column">
                                    <div class="image">
                                        <img src="{{ asset('Dashboard/img/media/ps1.jpg') }}" alt="" />
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>


                <div class="swiper-slide slide">
                    <div class="auto-container">
                        <div class="row clearfix">

                            <!-- Content Column -->
                            <div class="content-column col-lg-6 col-md-12 col-sm-12">
                                <div class="inner-column">
                                    <div class="sub-title animated-text" style="color: #e0e0e0;">حلول صحية مبتكرة</div>
                                    <h1 class="animated-text" style="color: white;">خدمات طبية متكاملة،<br> تلبي كافة
                                        احتياجاتك</h1>
                                    <div class="text animated-text">
                                        نوفر باقة واسعة من الخدمات التشخيصية والعلاجية المتقدمة، مع فريق متخصص يسعى لراحتك
                                        وشفائك التام.
                                    </div>
                                    <div class="btn-box">
                                        <a href="{{ route('website.services.all') }}" class="theme-btn appointment-btn"> <i
                                                class="fas fa-medkit me-2"></i> <span>الخدمات المفردة</span></a>
                                        <a href="{{ route('website.group_services.all') }}" class="theme-btn services-btn">
                                            <i class="fas fa-users-medical me-2"></i> <span>الخدمات المجمعة</span></a>
                                    </div>

                                </div>
                            </div>

                            <!-- Image Column -->
                            <div class="image-column col-lg-6 col-md-12 col-sm-12">
                                <div class="inner-column">
                                    <div class="image">
                                        <img src="{{ asset('WebSite/images/main-slider/3.png') }}" alt="" />
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>


                <div class="swiper-slide slide">
                    <div class="auto-container">
                        <div class="row clearfix">

                            <!-- Content Column -->
                            <div class="content-column col-lg-6 col-md-12 col-sm-12">
                                <div class="inner-column">
                                    <div class="sub-title animated-text" style="color: #e0e0e0;">وصولك السهل للرعاية</div>
                                    <h1 class="animated-text" style="color: white;">إدارة صحتك أصبحت أسهل،<br> كل ما تحتاجه
                                        في مكان واحد</h1>
                                    <div class="text animated-text">
                                        نقدم لك أدوات رقمية متطورة لمتابعة فواتيرك، حجوزاتك، والتواصل المباشر مع فريقنا
                                        الطبي بكل يسر وأمان.
                                    </div>
                                    <div class="btn-box">
                                        <a href="{{ route('website.my.invoices') }}" class="theme-btn appointment-btn"><i
                                                class="fas fa-file-invoice-dollar me-2"></i> <span>الفواتير</span></a>
                                        <a href="{{ route('website.my.account') }}" class="theme-btn services-btn"><i
                                                class="fas fa-user-cog me-2"></i> <span>كشف الحساب</span></a>
                                        <div class="btn-box">
                                            <a href="{{ route('website.my.appointments') }}"
                                                class="theme-btn appointment-btn"><i class="fas fa-calendar-check me-2"></i>
                                                <span>المواعيد</span></a>
                                            {{-- <a href="{{ route('website.doctors.all') }}"
                                            class="theme-btn services-btn">الأطباء</a> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Image Column -->
                            <div class="image-column col-lg-6 col-md-12 col-sm-12">
                                <div class="inner-column">
                                    <div class="image">
                                        <img src="{{ asset('WebSite/images/main-slider/3.png') }}" alt="" />
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

            </div>
            <!-- Add Arrows -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </section>
    <!-- End Main Slider -->

    <!-- Health Section -->
    <section class="health-section">
        <div class="auto-container">
            <div class="inner-container">

                <div class="row clearfix">

                    <!-- Content Column -->
                    <div class="content-column col-lg-7 col-md-12 col-sm-12">
                        <div class="inner-column">
                            <div class="border-line"></div>
                            <!-- Sec Title -->
                            <div class="sec-title">
                                <h2>من نحن <br> الريادة في الصحة</h2>
                                <div class="separator"></div>
                            </div>
                            <div class="text">أين أنت في قلب مهمتنا. نأمل أن تعتبرنا منزلك الطبي - المكان الذي تشعر فيه
                                بالأمان والراحة والرعاية. كمجموعة طبية متعددة التخصصات
                            </div>
                            <a href="about.html" class="theme-btn btn-style-one"><span class="txt">المزيد عنا</span></a>
                        </div>
                    </div>

                    <!-- Image Column -->
                    <div class="image-column col-lg-5 col-md-12 col-sm-12">
                        <div class="inner-column wow fadeInRight" data-wow-delay="0ms" data-wow-duration="1500ms">
                            <div class="image">
                                <img src="{{ URL::asset('Dashboard/img/media/medical.jpg') }}" alt="" />
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>
    <!-- End Health Section -->

    <!-- Featured Section -->
    <section class="featured-section">
        <div class="auto-container">
            <div class="row clearfix">

                <!-- Feature Block -->
                <div class="feature-block col-lg-3 col-md-6 col-sm-12">
                    <div class="inner-box wow fadeInLeft" data-wow-delay="0ms" data-wow-duration="1500ms">
                        <div class="upper-box">
                            <div class="icon flaticon-doctor-stethoscope"></div>
                            <h3><a href="#">العلاج الطبي</a></h3>
                        </div>
                        <div class="text">سواء أكنت تتخذ خطواتك الأولى ، أو مجرد إيجاد خطوتك</div>
                    </div>
                </div>

                <!-- Feature Block -->
                <div class="feature-block col-lg-3 col-md-6 col-sm-12">
                    <div class="inner-box wow fadeInLeft" data-wow-delay="250ms" data-wow-duration="1500ms">
                        <div class="upper-box">
                            <div class="icon flaticon-ambulance-side-view"></div>
                            <h3><a href="#">مساعدة الطوارئ</a></h3>
                        </div>
                        <div class="text">سواء أكنت تتخذ خطواتك الأولى ، أو مجرد إيجاد خطوتك</div>
                    </div>
                </div>

                <!-- Feature Block -->
                <div class="feature-block col-lg-3 col-md-6 col-sm-12">
                    <div class="inner-box wow fadeInLeft" data-wow-delay="500ms" data-wow-duration="1500ms">
                        <div class="upper-box">
                            <div class="icon fas fa-user-md"></div>
                            <h3><a href="#">أطباء مؤهلين</a></h3>
                        </div>
                        <div class="text">سواء أكنت تتخذ خطواتك الأولى ، أو مجرد إيجاد خطوتك</div>
                    </div>
                </div>

                <!-- Feature Block -->
                <div class="feature-block col-lg-3 col-md-6 col-sm-12">
                    <div class="inner-box wow fadeInLeft" data-wow-delay="750ms" data-wow-duration="1500ms">
                        <div class="upper-box">
                            <div class="icon fas fa-briefcase-medical"></div>
                            <h3><a href="#">محترفين طبيا</a></h3>
                        </div>
                        <div class="text">سواء أكنت تتخذ خطواتك الأولى ، أو مجرد إيجاد خطوتك</div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- End Featured Section -->

    <!-- Department Section Three -->
    <section class="department-section-three">
        <div class="image-layer" style="background-image:url(images/background/6.jpg)"></div>
        <div class="auto-container">
            <!-- Department Tabs-->
            <div class="department-tabs tabs-box">
                <div class="row clearfix">
                    <!--Column-->
                    <div class="col-lg-4 col-md-12 col-sm-12">
                        <!-- Sec Title -->
                        <div class="sec-title light">
                            <h2>الاقسام</h2>
                            <div class="separator"></div>
                        </div>
                        <!--Tab Btns-->
                        <ul class="tab-btns tab-buttons clearfix">
                            @foreach ($sections as $index => $section)
                                <li data-tab="#tab-{{ $section->id }}"
                                    class="tab-btn {{ $index === 0 ? 'active-btn' : '' }}">
                                    {{ $section->name }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <!--Column-->
                    <div class="col-lg-8 col-md-12 col-sm-12">
                        <!--Tabs Container-->
                        <div class="tabs-content">
                            @foreach ($sections as $index => $section)
                                <div class="tab {{ $index === 0 ? 'active-tab' : '' }}" id="tab-{{ $section->id }}">
                                    <div class="content">
                                        <h2>{{ $section->name }}</h2>
                                        <div class="title">{{ $section->name_en ?? $section->name }}</div>
                                        <div class="text">
                                            <p>{{ $section->description ?? 'لا يوجد وصف متوفر لهذا القسم' }}</p>
                                        </div>

                                        <div class="two-column row clearfix">
                                            <div class="column col-lg-12 col-md-12 col-sm-12">
                                                <h3 class="section-title">الأطباء المتخصصين</h3>
                                                <div class="doctors-container">
                                                    @if ($section->doctors && count($section->doctors) > 0)
                                                        <div class="row">
                                                            @foreach ($section->doctors->take(10) as $doctor)
                                                                <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                                                                    <div class="doctor-profile-card d-flex">
                                                                        <div class="doctor-avatar-container me-3">
                                                                            @if ($doctor->image)
                                                                                <img src="{{ asset('Dashboard/img/doctors/' . $doctor->image->filename) }}"
                                                                                    alt="{{ $doctor->name }}"
                                                                                    class="doctor-avatar img-fluid rounded-circle"
                                                                                    style="width: 100px; height: 100px; object-fit: cover;"
                                                                                    onerror="this.src='{{ asset('Dashboard/img/doctor_default.png') }}'">
                                                                            @else
                                                                                <img src="{{ asset('Dashboard/img/doctor_default.png') }}"
                                                                                    alt="صورة افتراضية"
                                                                                    class="doctor-avatar img-fluid rounded-circle"
                                                                                    style="width: 100px; height: 100px; object-fit: cover;">
                                                                            @endif
                                                                            <div
                                                                                class="doctor-status-badge {{ $doctor->status ? 'active' : 'inactive' }}">
                                                                                {{ $doctor->status ? 'متاح' : 'غير متاح' }}
                                                                            </div>
                                                                        </div>
                                                                        <div class="doctor-details flex-grow-1">
                                                                            <h4 class="doctor-name">{{ $doctor->name }}
                                                                            </h4>
                                                                            <p class="doctor-specialty">
                                                                                {{ $doctor->specialization }}</p>
                                                                            <button type="button"
                                                                                class="btn btn-primary book-doctor-btn"
                                                                                data-doctor-id="{{ $doctor->id }}"
                                                                                data-doctor-name="{{ $doctor->name }}"
                                                                                data-section-id="{{ $section->id }}"
                                                                                data-section-name="{{ $section->name }}">
                                                                                حجز موعد <i
                                                                                    class="fas fa-calendar-check ms-1"></i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @else
                                                        <div class="no-doctors text-center py-5">
                                                            <img src="{{ asset('Dashboard/img/no-doctors.svg') }}"
                                                                alt="لا يوجد أطباء" class="img-fluid mb-3"
                                                                style="max-width: 200px;">
                                                            <p class="text-muted">لا يوجد أطباء مسجلين في هذا القسم</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        {{-- <a href="{{ route('department.details', $section->id) }}" class="theme-btn btn-style-two">
                                        <span class="txt">عرض المزيد</span>
                                    </a> --}}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Department Section -->

    @if (isset($latestServices) && $latestServices->count() > 0)
        <section class="services-slider-section py-5 bg-gradient-light">
            <div class="container position-relative">
                <div class="row justify-content-center mb-5">
                    <div class="col-lg-8 text-center">
                        <h2 class="display-5 fw-bold text-primary mb-3">أحدث خدماتنا الطبية المتميزة</h2>
                        <div class="animated-separator mx-auto">
                            <span class="animated-bar"></span>
                        </div>
                        <p class="text-muted mt-3">اكتشف مجموعة واسعة من الخدمات الطبية المتخصصة التي نقدمها بأعلى معايير
                            الجودة</p>
                    </div>
                </div>

                <!-- Swiper with 3D effect -->
                <div class="swiper-container services-swiper-3d">
                    <div class="swiper-wrapper">
                        @foreach ($latestServices as $service)
                            <div class="swiper-slide">
                                <div class="service-card-3d">
                                    <div class="card-face card-front">
                                        <div class="service-icon-wrapper">
                                            <i class="fas fa-heartbeat"></i>
                                        </div>
                                        <div class="card-body">
                                            <h5 class="service-title">{{ $service->name }}</h5>
                                            @if ($service->doctor)
                                                <div class="doctor-info">
                                                    @if ($doctor->image)
                                                        <img src="{{ asset('Dashboard/img/doctors/' . $doctor->image->filename) }}"
                                                            alt="{{ $doctor->name }}"
                                                            class="doctor-avatar img-fluid rounded-circle"
                                                            style="width: 100px; height: 100px; object-fit: cover;"
                                                            onerror="this.src='{{ asset('Dashboard/img/doctor_default.png') }}'">
                                                    @else
                                                        <img src="{{ asset('Dashboard/img/doctor_default.png') }}"
                                                            alt="صورة افتراضية"
                                                            class="doctor-avatar img-fluid rounded-circle"
                                                            style="width: 100px; height: 100px; object-fit: cover;">
                                                    @endif
                                                    <div>
                                                        <span class="doctor-name">{{ $service->doctor->name }}</span>
                                                        @if ($service->doctor->section)
                                                            <span
                                                                class="doctor-specialty">{{ $service->doctor->section->name }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="service-excerpt">
                                                {{ Str::limit(strip_tags($service->description ?? 'وصف مختصر للخدمة...'), 100) }}
                                            </div>
                                            <div class="flip-button">
                                                <i class="fas fa-redo"></i> التفاصيل
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-face card-back">
                                        <div class="card-body">
                                            <h5 class="service-title">{{ $service->name }}</h5>
                                            <ul class="service-features">
                                                <li><i class="fas fa-check-circle"></i> تشخيص دقيق</li>
                                                <li><i class="fas fa-check-circle"></i> أحدث التقنيات</li>
                                                <li><i class="fas fa-check-circle"></i> متابعة مستمرة</li>
                                            </ul>
                                            @if (isset($service->price) && is_numeric($service->price))
                                                <div class="price-tag">
                                                    {{ number_format($service->price, 2) }}
                                                    <small>{{ config('app.currency_symbol', 'ر.س') }}</small>
                                                </div>
                                            @endif
                                            <a href="#" class="btn btn-primary btn-book">
                                                حجز الخدمة <i class="fas fa-calendar-check ms-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <!-- Add Pagination -->
                    <div class="swiper-pagination services-swiper-pagination"></div>
                </div>

                <!-- Custom Navigation -->
                <div class="swiper-nav-buttons">
                    <button class="swiper-button-prev-custom">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                    <button class="swiper-button-next-custom">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                </div>
            </div>
        </section>
    @endif

    @if (isset($latestGroupedServices) && $latestGroupedServices->count() > 0)
        <section class="grouped-services-section py-5 bg-white">
            <div class="container">
                <div class="row justify-content-center mb-5">
                    <div class="col-lg-8 text-center">
                        <h2 class="display-5 fw-bold text-primary mb-3">باقاتنا الطبية الشاملة</h2>
                        <div class="animated-separator mx-auto">
                            <span class="animated-bar"></span>
                        </div>
                        <p class="text-muted mt-3">وفر وقتك ومالك مع باقاتنا المتكاملة المصممة خصيصًا لراحتك</p>
                    </div>
                </div>

                <div class="row g-4 justify-content-center">
                    @foreach ($latestGroupedServices as $group)
                        <div class="col-md-6 col-lg-4">
                            <div class="package-card">
                                <div class="package-header">
                                    <h3 class="package-title">{{ $group->name }}</h3>
                                    @if (isset($group->Total_with_tax))
                                        <div class="package-price">
                                            {{ number_format($group->Total_with_tax, 2) }}
                                            <span>{{ config('app.currency_symbol', 'ر.س') }}</span>
                                        </div>
                                    @endif
                                    <div class="package-savings">
                                        متوفر حتى 25
                                    </div>
                                </div>
                                <div class="package-body">
                                    @if ($group->notes)
                                        <p class="package-description">
                                            {{ Str::limit(strip_tags($group->notes), 120) }}
                                        </p>
                                    @endif

                                    <div class="package-features">
                                        <h5>تشمل الباقة:</h5>
                                        <ul>
                                            @foreach ($group->service_group->take(4) as $service_item)
                                                <li>
                                                    <i class="fas fa-check"></i>
                                                    {{ $service_item->name }}
                                                    @if (isset($service_item->price))
                                                        <span class="feature-price">
                                                            {{ number_format($service_item->price, 2) }} ر.س
                                                        </span>
                                                    @endif
                                                </li>
                                            @endforeach
                                            @if ($group->service_group->count() > 4)
                                                <li class="more-features">
                                                    + {{ $group->service_group->count() - 4 }} خدمات إضافية
                                                </li>
                                            @endif
                                        </ul>
                                    </div>

                                    <div class="package-total">
                                        <span>القيمة الإجمالية:</span>
                                        <span class="total-value">
                                            @php
                                                $totalValue = $group->service_group->sum('price');
                                                echo number_format($totalValue, 2) .
                                                    ' ' .
                                                    config('app.currency_symbol', 'ر.س');
                                            @endphp
                                        </span>
                                    </div>
                                </div>
                                <div class="package-footer">
                                    {{-- <a href="#" class="btn btn-primary btn-package-details">
                                        تفاصيل الباقة <i class="fas fa-arrow-left ms-2"></i>
                                    </a> --}}
                                    <a href="#" class="btn btn-outline-primary btn-package-book">
                                        حجز الباقة <i class="fas fa-calendar-alt ms-2"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- <div class="text-center mt-5">
                    <a href="#" class="btn btn-outline-primary btn-lg rounded-pill px-4">
                        عرض جميع البackages <i class="fas fa-arrow-left ms-2"></i>
                    </a>
                </div> --}}
            </div>
        </section>
    @endif
    <!-- End Latest Single Services Section -->

    <!-- Team Section -->
    {{-- <section class="team-section">
        <div class="auto-container">

            <!-- Sec Title -->
            <div class="sec-title centered">
                <h2>الأخصائيون الطبيون</h2>
                <div class="separator"></div>
            </div>

            <div class="row clearfix">

                <!-- Team Block -->
                <div class="team-block col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="inner-box wow fadeInLeft" data-wow-delay="0ms" data-wow-duration="1500ms">
                        <div class="image">
                            <img src="images/resource/team-1.jpg" alt="" />
                            <div class="overlay-box">
                                <ul class="social-icons">
                                    <li><a href="#"><span class="fab fa-facebook-f"></span></a></li>
                                    <li><a href="#"><span class="fab fa-google"></span></a></li>
                                    <li><a href="#"><span class="fab fa-twitter"></span></a></li>
                                    <li><a href="#"><span class="fab fa-skype"></span></a></li>
                                    <li><a href="#"><span class="fab fa-linkedin-in"></span></a></li>
                                </ul>
                                <a href="#" class="appointment">Make Appointment</a>
                            </div>
                        </div>
                        <div class="lower-content">
                            <h3><a href="#">الدكتورة أندريا جونيا</a></h3>
                            <div class="designation">أخصائي السرطان</div>
                        </div>
                    </div>
                </div>

                <!-- Team Block -->
                <div class="team-block col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="inner-box wow fadeInLeft" data-wow-delay="250ms" data-wow-duration="1500ms">
                        <div class="image">
                            <img src="images/resource/team-2.jpg" alt="" />
                            <div class="overlay-box">
                                <ul class="social-icons">
                                    <li><a href="#"><span class="fab fa-facebook-f"></span></a></li>
                                    <li><a href="#"><span class="fab fa-google"></span></a></li>
                                    <li><a href="#"><span class="fab fa-twitter"></span></a></li>
                                    <li><a href="#"><span class="fab fa-skype"></span></a></li>
                                    <li><a href="#"><span class="fab fa-linkedin-in"></span></a></li>
                                </ul>
                                <a href="#" class="appointment">Make Appointment</a>
                            </div>
                        </div>
                        <div class="lower-content">
                            <h3><a href="#">د. روبت سميث</a></h3>
                            <div class="designation">جراح قلب</div>
                        </div>
                    </div>
                </div>

                <!-- Team Block -->
                <div class="team-block col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="inner-box wow fadeInLeft" data-wow-delay="500ms" data-wow-duration="1500ms">
                        <div class="image">
                            <img src="images/resource/team-3.jpg" alt="" />
                            <div class="overlay-box">
                                <ul class="social-icons">
                                    <li><a href="#"><span class="fab fa-facebook-f"></span></a></li>
                                    <li><a href="#"><span class="fab fa-google"></span></a></li>
                                    <li><a href="#"><span class="fab fa-twitter"></span></a></li>
                                    <li><a href="#"><span class="fab fa-skype"></span></a></li>
                                    <li><a href="#"><span class="fab fa-linkedin-in"></span></a></li>
                                </ul>
                                <a href="#" class="appointment">Make Appointment</a>
                            </div>
                        </div>
                        <div class="lower-content">
                            <h3><a href="#">دكتور ويل لورا</a></h3>
                            <div class="designation">طبيب الأسرة</div>
                        </div>
                    </div>
                </div>

                <!-- Team Block -->
                <div class="team-block col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="inner-box wow fadeInLeft" data-wow-delay="750ms" data-wow-duration="1500ms">
                        <div class="image">
                            <img src="images/resource/team-4.jpg" alt="" />
                            <div class="overlay-box">
                                <ul class="social-icons">
                                    <li><a href="#"><span class="fab fa-facebook-f"></span></a></li>
                                    <li><a href="#"><span class="fab fa-google"></span></a></li>
                                    <li><a href="#"><span class="fab fa-twitter"></span></a></li>
                                    <li><a href="#"><span class="fab fa-skype"></span></a></li>
                                    <li><a href="#"><span class="fab fa-linkedin-in"></span></a></li>
                                </ul>
                                <a href="#" class="appointment">Make Appointment</a>
                            </div>
                        </div>
                        <div class="lower-content">
                            <h3><a href="#">الدكتور أليكس فورغسين</a></h3>
                            <div class="designation">أخصائي تقويم العظام</div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section> --}}
    <!-- End Team Section -->
    <br>
    <!-- Video Section -->
    <section class="video-section" style="background-image:url(images/background/5.jpg)">
        <div class="auto-container">
            <div class="content">
                <a href="https://www.youtube.com/watch?v=kxPCFljwJws" class="lightbox-image play-box"><span
                        class="flaticon-play-button"><i class="ripple"></i></span></a>
                <div class="text">نحن نهتم بصحتك<h2>نحن نهتم بك</h2>
                </div>
            </div>
    </section>
    <!-- End Video Section -->

    <!-- Appointment Section Two -->
    <section class="appointment-section-two">
        <div class="auto-container">
            <div class="inner-container">
                <div class="row clearfix">

                    <!-- Image Column -->
                    <div class="image-column col-lg-6 col-md-12 col-sm-12">
                        <div class="inner-column wow slideInLeft" data-wow-delay="0ms" data-wow-duration="1500ms">
                            <div class="image">
                                <img src="images/resource/doctor-2.png" alt="" />
                            </div>
                        </div>
                    </div>

                    <div class="container my-5" id="appointment">
                        <div class="sec-title">
                            <h2>حجز موعد</h2>
                            <div class="separator"></div>
                        </div>
                        <div class="row">

                            <div class="col-12"> {{-- This will make the component span the full width of the row --}}
                                <livewire:appointments.create />
                            </div>
                        </div>
                    </div>


                    {{--  --}}

                </div>
            </div>
        </div>
    </section>

    <!-- Testimonial Section Two -->
    <section class="testimonial-section-two">
        <div class="auto-container">
            <!-- Sec Title -->
            <div class="sec-title centered">
                <h2>ماذا يقول المرضى</h2>
                <div class="separator"></div>
            </div>
            <div class="testimonial-carousel owl-carousel owl-theme">

                <!-- Tesimonial Block Two -->
                <div class="testimonial-block-two">
                    <div class="inner-box">
                        <div class="image">
                            <img src="images/resource/author-4.jpg" alt="" />
                        </div>
                        <div class="text">
                            يعد المركز الطبي مكانًا رائعًا للحصول على جميع احتياجاتك الطبية. دخلت
                            لإجراء فحص ولم تنتظر أكثر من 5 دقائق قبل رؤيتي. يمكنني أن أتصور
                            نوع الخدمة التي تحصل عليها في حالة المشكلات الأكثر خطورة. شكرًا!
                        </div>
                        <div class="lower-box">
                            <div class="clearfix">

                                <div class="pull-left">
                                    <div class="quote-icon flaticon-quote"></div>
                                </div>
                                <div class="pull-right">
                                    <div class="author-info">
                                        <h3>ماكس وينشستر</h3>
                                        <div class="author">مريض الكلى</div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tesimonial Block Two -->
                <div class="testimonial-block-two">
                    <div class="inner-box">
                        <div class="image">
                            <img src="images/resource/author-5.jpg" alt="" />
                        </div>
                        <div class="text">
                            يعد المركز الطبي مكانًا رائعًا للحصول على جميع احتياجاتك الطبية. دخلت
                            لإجراء فحص ولم تنتظر أكثر من 5 دقائق قبل رؤيتي. يمكنني أن أتصور
                            نوع الخدمة التي تحصل عليها في حالة المشكلات الأكثر خطورة. شكرًا!
                        </div>
                        <div class="lower-box">
                            <div class="clearfix">

                                <div class="pull-left">
                                    <div class="quote-icon flaticon-quote"></div>
                                </div>
                                <div class="pull-right">
                                    <div class="author-info">
                                        <h3>جاك مونيتا</h3>
                                        <div class="author">مريض الكلى</div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tesimonial Block Two -->
                <div class="testimonial-block-two">
                    <div class="inner-box">
                        <div class="image">
                            <img src="images/resource/author-4.jpg" alt="" />
                        </div>
                        <div class="text">Medical Centre is a great place to get all of your medical needs. I came in
                            for a check up and did not wait more than 5 minutes before I was seen. I can only imagine
                            the type of service you get for more serious issues. Thanks!
                        </div>
                        <div class="lower-box">
                            <div class="clearfix">

                                <div class="pull-left">
                                    <div class="quote-icon flaticon-quote"></div>
                                </div>
                                <div class="pull-right">
                                    <div class="author-info">
                                        <h3>Max Winchester</h3>
                                        <div class="author">Kidny Patient</div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tesimonial Block Two -->
                <div class="testimonial-block-two">
                    <div class="inner-box">
                        <div class="image">
                            <img src="images/resource/author-5.jpg" alt="" />
                        </div>
                        <div class="text">Medical Centre is a great place to get all of your medical needs. I came in
                            for a check up and did not wait more than 5 minutes before I was seen. I can only imagine
                            the type of service you get for more serious issues. Thanks!
                        </div>
                        <div class="lower-box">
                            <div class="clearfix">

                                <div class="pull-left">
                                    <div class="quote-icon flaticon-quote"></div>
                                </div>
                                <div class="pull-right">
                                    <div class="author-info">
                                        <h3>Jack Monita</h3>
                                        <div class="author">Kidny Patient</div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- End Testimonial Section Two -->

    <!-- Counter Section -->
    <section class="counter-section style-two" style="background-image: url(images/background/pattern-3.png)">
        <div class="auto-container">

            <!-- Fact Counter -->
            <div class="fact-counter style-two">
                <div class="row clearfix">

                    <!--Column-->
                    <div class="column counter-column col-lg-3 col-md-6 col-sm-12">
                        <div class="inner wow fadeInLeft" data-wow-delay="0ms" data-wow-duration="1500ms">
                            <div class="content">
                                <div class="icon flaticon-logout"></div>
                                <div class="count-outer count-box">
                                    <span class="count-text" data-speed="2500" data-stop="2350">0</span>
                                </div>
                                <h4 class="counter-title">مرضى راضون</h4>
                            </div>
                        </div>
                    </div>

                    <!--Column-->
                    <div class="column counter-column col-lg-3 col-md-6 col-sm-12">
                        <div class="inner wow fadeInLeft" data-wow-delay="300ms" data-wow-duration="1500ms">
                            <div class="content">
                                <div class="icon flaticon-logout"></div>
                                <div class="count-outer count-box alternate">
                                    +<span class="count-text" data-speed="3000" data-stop="350">0</span>
                                </div>
                                <h4 class="counter-title">فريق الطبيب</h4>
                            </div>
                        </div>
                    </div>

                    <!--Column-->
                    <div class="column counter-column col-lg-3 col-md-6 col-sm-12">
                        <div class="inner wow fadeInLeft" data-wow-delay="600ms" data-wow-duration="1500ms">
                            <div class="content">
                                <div class="icon flaticon-logout"></div>
                                <div class="count-outer count-box">
                                    <span class="count-text" data-speed="3000" data-stop="2150">0</span>
                                </div>
                                <h4 class="counter-title">مهمة النجاح</h4>
                            </div>
                        </div>
                    </div>

                    <!--Column-->
                    <div class="column counter-column col-lg-3 col-md-6 col-sm-12">
                        <div class="inner wow fadeInLeft" data-wow-delay="900ms" data-wow-duration="1500ms">
                            <div class="content">
                                <div class="icon flaticon-logout"></div>
                                <div class="count-outer count-box">
                                    +<span class="count-text" data-speed="2500" data-stop="225">0</span>
                                </div>
                                <h4 class="counter-title">جراحات ناجحة</h4>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </section>
    <!-- End Counter Section -->

    <!-- Doctor Info Section -->
    <section class="doctor-info-section">
        <div class="auto-container">
            <div class="inner-container">
                <div class="row clearfix">

                    <!-- Doctor Block -->
                    <div class="doctor-block col-lg-4 col-md-6 col-sm-12">
                        <div class="inner-box wow fadeInLeft" data-wow-delay="0ms" data-wow-duration="1500ms">
                            <h3>ساعات العمل</h3>
                            <ul class="doctor-time-list">
                                <li>من الإثنين إلى الجمعة<span>8:00am–7:00pm</span></li>
                                <li>السبت <span>9:00am–5:00pm</span></li>
                                <li>الأحد<span>9:00am–3:00pm</span></li>
                            </ul>
                            <h4>حالات الطوارئ</h4>
                            <div class="phone">اتصل بنا ! <strong>+898 68679 575 09</strong></div>
                        </div>
                    </div>

                    <!-- Doctor Block -->
                    <div class="doctor-block col-lg-4 col-md-6 col-sm-12">
                        <div class="inner-box wow fadeInUp" data-wow-delay="0ms" data-wow-duration="1500ms">
                            <h3>جدول الأطباء</h3>
                            <div class="text">
                                ما يلي هو للإرشاد فقط لمساعدتك في التخطيط لموعدك
                                طبيب أو ممرضة مفضلة. لا تضمن توافر الأطباء أو الممرضات
                                قد يكون في بعض الأحيان يحضر إلى واجبات أخرى
                            </div>
                            <a href="#" class="detail">تفاصيل اكثر</a>
                        </div>
                    </div>

                    <!-- Doctor Block -->
                    <div class="doctor-block col-lg-4 col-md-6 col-sm-12">
                        <div class="inner-box wow fadeInRight" data-wow-delay="0ms" data-wow-duration="1500ms">
                            <h3>العناية الصحية الاولية</h3>
                            <div class="text">عندما تعلم أنك تستخدم أفضل مواهبك من أجل شيء تحبه ، فأنت
                                لا تستطيع ذلك. التواصل الفعال هو الأساس لبناء علامات تجارية صلبة مثل
                                علاقة السفن بالبناء مع عملائنا
                            </div>
                            <a href="#" class="detail">اتصل الآن</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!-- End Doctor Info Section -->

    <!-- News Section Two -->
    <section class="news-section-two">
        <div class="auto-container">
            <!-- Sec Title -->
            <div class="sec-title centered">
                <h2>آخر الأخبار والمقالات</h2>
                <div class="separator style-three"></div>
            </div>
            <div class="row clearfix">

                <!-- News Block Two -->
                <div class="news-block-two col-lg-6 col-md-12 col-sm-12">
                    <div class="inner-box">
                        <div class="image">
                            <a href="blog-detail.html"><img src="images/resource/news-4.jpg" alt="" /></a>
                        </div>
                        <div class="lower-content">
                            <div class="content">
                                <ul class="post-info">
                                    <li><span class="icon flaticon-chat-comment-oval-speech-bubble-with-text-lines"></span>
                                        02
                                    </li>
                                    <li><span class="icon flaticon-heart"></span> 126</li>
                                </ul>
                                <ul class="post-meta">
                                    <li>21 يونيو 2018 الساعة 8:12 مساءً</li>
                                    <li>Post بواسطة: Admin</li>
                                </ul>
                                <h3><a href="blog-detail.html">خدمات التشخيص لانتقاء نتائج فعالة بشكل صحيح</a></h3>
                                <div class="text">
                                    هناك الكثير من النساء اللواتي يجهلن المخاطر العديدة
                                    المرتبطة بصحتهم وتجاهل في النهاية
                                </div>
                                <a href="blog-detail.html" class="theme-btn btn-style-five"><span class="txt">اقرا
                                        المزيد</span></a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- News Block Two -->
                <div class="news-block-two col-lg-6 col-md-12 col-sm-12">
                    <div class="inner-box">
                        <div class="image">
                            <a href="blog-detail.html"><img src="images/resource/news-5.jpg" alt="" /></a>
                        </div>
                        <div class="lower-content">
                            <div class="content">
                                <ul class="post-info">
                                    <li><span class="icon flaticon-chat-comment-oval-speech-bubble-with-text-lines"></span>
                                        02
                                    </li>
                                    <li><span class="icon flaticon-heart"></span> 126</li>
                                </ul>
                                <ul class="post-meta">
                                    <li>21 يونيو 2018 الساعة 8:12 مساءً</li>
                                    <li>Post بواسطة: Admin</li>
                                </ul>
                                <h3><a href="blog-detail.html">خدمات التشخيص لانتقاء نتائج فعالة بشكل صحيح</a></h3>
                                <div class="text">
                                    هناك الكثير من النساء اللواتي يجهلن المخاطر العديدة
                                    المرتبطة بصحتهم وتجاهل في النهاية
                                </div>
                                <a href="blog-detail.html" class="theme-btn btn-style-five"><span class="txt">اقرا
                                        المزيد</span></a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
    </section>

    <!--Clients Section-->
    <section class="clients-section">
        <div class="outer-container">

            <div class="sponsors-outer">
                <!--Sponsors Carousel-->
                <ul class="sponsors-carousel owl-carousel owl-theme">
                    <li class="slide-item">
                        <figure class="image-box"><a href="#"><img src="images/clients/1.png" alt=""></a>
                        </figure>
                    </li>
                    <li class="slide-item">
                        <figure class="image-box"><a href="#"><img src="images/clients/2.png" alt=""></a>
                        </figure>
                    </li>
                    <li class="slide-item">
                        <figure class="image-box"><a href="#"><img src="images/clients/3.png" alt=""></a>
                        </figure>
                    </li>
                    <li class="slide-item">
                        <figure class="image-box"><a href="#"><img src="images/clients/4.png" alt=""></a>
                        </figure>
                    </li>
                    <li class="slide-item">
                        <figure class="image-box"><a href="#"><img src="images/clients/5.png" alt=""></a>
                        </figure>
                    </li>
                    <li class="slide-item">
                        <figure class="image-box"><a href="#"><img src="images/clients/1.png" alt=""></a>
                        </figure>
                    </li>
                    <li class="slide-item">
                        <figure class="image-box"><a href="#"><img src="images/clients/2.png" alt=""></a>
                        </figure>
                    </li>
                </ul>
            </div>

        </div>
    </section>
    <!--End Clients Section-->


    <style>
        .service-list,
        .doctor-list {
            list-style: none;
            padding: 0;
        }


        .doctor-card {
            text-align: center;
            padding: 15px;
            transition: all 0.3s ease;
        }

        .doctor-card:hover {
            transform: translateY(-5px);
        }

        .doctor-image {
            width: 120px;
            height: 120px;
            object-fit: cover;
            margin-bottom: 10px;
            border: 3px solid #f0f0f0;
        }

        .doctor-info h5 {
            margin-top: 10px;
            color: #333;
            font-weight: 600;
        }

        .doctor-info p {
            color: #666;
            font-size: 14px;
        }

        .service-list li,
        .doctor-list li {
            padding: 8px 0;
            border-bottom: 1px dashed #eee;
            position: relative;
            padding-left: 20px;
        }

        .service-list li:before,
        .doctor-list li:before {
            content: "•";
            color: #5e72e4;
            position: absolute;
            left: 0;
            font-size: 20px;
            line-height: 1;
        }

        /* تخصيصات قسم الأطباء */
        .section-title {
            color: #2c3e50;
            font-size: 1.8rem;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 10px;
        }

        .section-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, #3498db, #2ecc71);
        }

        .doctors-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
        }

        .doctor-profile-card {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
        }

        .doctor-profile-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
        }

        .doctor-avatar-container {
            position: relative;
            padding: 20px;
            text-align: center;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        .doctor-avatar {
            width: 100px;
            height: 150px;
            border-radius: 30%;
            object-fit: cover;
            border: 5px solid white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .doctor-status-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
            color: white;
        }

        .doctor-status-badge.active {
            background-color: #2ecc71;
        }

        .doctor-status-badge.inactive {
            background-color: #e74c3c;
        }

        .doctor-details {
            padding: 20px;
            text-align: center;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .doctor-name {
            color: #2c3e50;
            margin-bottom: 5px;
            font-size: 1.2rem;
        }

        .doctor-specialty {
            color: #7f8c8d;
            margin-bottom: 15px;
            font-size: 0.9rem;
        }

        .doctor-meta {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 15px;
            color: #95a5a6;
            font-size: 0.85rem;
        }

        .btn-appointment {
            background: linear-gradient(90deg, #3498db, #2ecc71);
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 30px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: auto;
        }

        .btn-appointment:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(46, 204, 113, 0.4);
        }

        .no-doctors {
            text-align: center;
            padding: 40px 20px;
        }

        .no-doctors img {
            max-width: 200px;
            margin-bottom: 20px;
            opacity: 0.2;
        }

        .no-doctors p {
            color: #95a5a6;
            font-size: 1.1rem;
        }

        /* تأثيرات للجوال */
        @media (max-width: 768px) {
            .doctors-grid {
                grid-template-columns: 1fr;
            }

            .doctor-profile-card {
                flex-direction: row;
                text-align: left;
            }

            .doctor-avatar-container {
                padding: 15px;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }

            /* .doctor-avatar {
                                                                            width: 50px;
                                                                            height: 100px;
                                                                        } */

            .doctor-details {
                padding: 15px;
                text-align: left;
            }

            .doctor-meta {
                justify-content: flex-start;
            }
        }

        .tab-btns .tab-btn {
            cursor: pointer;
            transition: all 0.3s;
            padding: 12px 15px;
            margin-bottom: 5px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
            position: relative;
            overflow: hidden;
        }

        .tab-btns .tab-btn:hover,
        .tab-btns .tab-btn.active-btn {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            transform: translateX(5px);
        }

        .tab-btns .tab-btn:after {
            content: '';
            position: absolute;
            right: -20px;
            top: 50%;
            transform: translateY(-50%);
            width: 0;
            height: 0;
            border-top: 10px solid transparent;
            border-bottom: 10px solid transparent;
            border-right: 10px solid white;
            opacity: 0;
            transition: all 0.3s;
        }

        .tab-btns .tab-btn.active-btn:after {
            right: 10px;
            opacity: 1;
        }
    </style>

    <style>
        /* خدمات 3D بطاقات */
        .services-slider-section {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9f5ff 100%);
        }

        .service-card-3d {
            position: relative;
            width: 100%;
            height: 400px;
            transform-style: preserve-3d;
            transition: all 0.8s ease;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            cursor: pointer;
        }

        .service-card-3d:hover {
            transform: rotateY(180deg);
        }

        .card-face {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            border-radius: 15px;
            overflow: hidden;
            background: white;
            display: flex;
            flex-direction: column;
        }

        .card-front {
            padding: 25px;
            text-align: center;
        }

        .card-back {
            background: linear-gradient(135deg, #3a7bd5 0%, #00d2ff 100%);
            color: white;
            transform: rotateY(180deg);
            padding: 25px;
        }

        .service-icon-wrapper {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: rgba(58, 123, 213, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            color: #3a7bd5;
        }

        .service-title {
            font-weight: 700;
            margin-bottom: 15px;
            color: #2c3e50;
        }

        .doctor-info {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            background: rgba(0, 0, 0, 0.03);
            padding: 10px;
            border-radius: 10px;
        }

        .doctor-thumbnail {
            width: 40px;
            height: 40px;
            object-fit: cover;
            margin-left: 10px;
        }

        .doctor-name {
            display: block;
            font-weight: 600;
            font-size: 14px;
        }

        .doctor-specialty {
            display: block;
            font-size: 12px;
            color: #7f8c8d;
        }

        .service-excerpt {
            font-size: 14px;
            color: #7f8c8d;
            margin-bottom: 20px;
            flex-grow: 1;
        }

        .flip-button {
            background: rgba(58, 123, 213, 0.1);
            color: #3a7bd5;
            padding: 8px 15px;
            border-radius: 30px;
            font-size: 13px;
            display: inline-flex;
            align-items: center;
            transition: all 0.3s;
        }

        .flip-button i {
            margin-left: 5px;
            transition: all 0.3s;
        }

        .flip-button:hover {
            background: #3a7bd5;
            color: white;
        }

        /* Back card styles */
        .card-back .service-title {
            color: white;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding-bottom: 10px;
        }

        .service-features {
            list-style: none;
            padding: 0;
            margin: 20px 0;
        }

        .service-features li {
            margin-bottom: 10px;
            font-size: 14px;
            display: flex;
            align-items: center;
        }

        .service-features i {
            margin-left: 8px;
            font-size: 12px;
        }

        .price-tag {
            font-size: 28px;
            font-weight: 700;
            margin: 20px 0;
        }

        .price-tag small {
            font-size: 16px;
            font-weight: normal;
        }

        .btn-book {
            width: 100%;
            border-radius: 30px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        /* Swiper 3D modifications */
        .services-swiper-3d {
            padding: 30px 0;
        }

        .services-swiper-3d .swiper-slide {
            transition: all 0.3s;
            transform: scale(0.9);
            opacity: 0.8;
        }

        .services-swiper-3d .swiper-slide-active {
            transform: scale(1);
            opacity: 1;
        }

        /* باقات بطاقات */
        .package-card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
            transition: all 0.3s;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .package-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12);
        }

        .package-header {
            background: linear-gradient(135deg, #3a7bd5 0%, #00d2ff 100%);
            color: white;
            padding: 25px;
            text-align: center;
            position: relative;
        }

        .package-title {
            font-weight: 700;
            margin-bottom: 10px;
            font-size: 1.4rem;
        }

        .package-price {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .package-price span {
            font-size: 1rem;
            font-weight: normal;
        }

        .package-savings {
            position: absolute;
            top: 15px;
            left: 15px;
            background: rgba(255, 255, 255, 0.2);
            padding: 3px 10px;
            border-radius: 30px;
            font-size: 12px;
        }

        .package-body {
            padding: 25px;
            flex-grow: 1;
        }

        .package-description {
            color: #7f8c8d;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .package-features {
            margin-bottom: 20px;
        }

        .package-features h5 {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 1.1rem;
        }

        .package-features ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .package-features li {
            padding: 8px 0;
            border-bottom: 1px dashed #eee;
            display: flex;
            align-items: center;
            font-size: 14px;
        }

        .package-features li i {
            margin-left: 8px;
            color: #3a7bd5;
            font-size: 12px;
        }

        .feature-price {
            margin-right: auto;
            color: #3a7bd5;
            font-weight: 600;
        }

        .more-features {
            color: #7f8c8d;
            font-style: italic;
            font-size: 13px !important;
        }

        .package-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8f9fa;
            padding: 12px 15px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .package-total span:first-child {
            font-size: 14px;
            color: #7f8c8d;
        }

        .total-value {
            font-weight: 700;
            color: #2c3e50;
            font-size: 1.1rem;
        }

        .package-footer {
            display: flex;
            padding: 15px 25px;
            background: #f8f9fa;
            border-top: 1px solid #eee;
        }

        .btn-package-details {
            flex: 1;
            border-radius: 30px;
            font-weight: 600;
            margin-left: 10px;
        }

        .btn-package-book {
            flex: 1;
            border-radius: 30px;
            font-weight: 600;
        }

        /* عناصر عامة */
        .animated-separator {
            width: 80px;
            height: 3px;
            background: rgba(58, 123, 213, 0.2);
            position: relative;
            overflow: hidden;
        }

        .animated-bar {
            position: absolute;
            width: 40px;
            height: 100%;
            background: #3a7bd5;
            animation: separatorAnimation 3s infinite ease-in-out;
        }

        @keyframes separatorAnimation {
            0% {
                left: -40px;
            }

            50% {
                left: 80px;
            }

            100% {
                left: -40px;
            }
        }

        .swiper-nav-buttons {
            position: absolute;
            top: 50%;
            width: 100%;
            display: flex;
            justify-content: space-between;
            pointer-events: none;
            z-index: 10;
        }

        .swiper-button-prev-custom,
        .swiper-button-next-custom {
            pointer-events: all;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border: none;
            color: #3a7bd5;
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .swiper-button-prev-custom:hover,
        .swiper-button-next-custom:hover {
            background: #3a7bd5;
            color: white;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // تفعيل نظام التبويب
            const tabBtns = document.querySelectorAll('.tab-btns .tab-btn');

            tabBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    // إزالة التنشيط من جميع الأزرار
                    tabBtns.forEach(b => b.classList.remove('active-btn'));

                    // إضافة التنشيط للزر المحدد
                    this.classList.add('active-btn');

                    // إخفاء جميع المحتويات
                    document.querySelectorAll('.tabs-content .tab').forEach(tab => {
                        tab.classList.remove('active-tab');
                    });

                    // إظهار المحتوى المحدد
                    const tabId = this.getAttribute('data-tab');
                    document.querySelector(tabId).classList.add('active-tab');
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof Swiper !== 'undefined') {
                // Swiper for Single Services
                if (document.querySelector('.services-swiper')) {
                    var servicesSwiper = new Swiper('.services-swiper', {
                        slidesPerView: 1,
                        spaceBetween: 20,
                        loop: false, // أو true إذا أردت تكرار لا نهائي
                        grabCursor: true,
                        pagination: {
                            el: '.services-swiper-pagination',
                            clickable: true,
                        },
                        navigation: {
                            nextEl: '.services-swiper-button-next',
                            prevEl: '.services-swiper-button-prev',
                        },
                        breakpoints: {
                            // when window width is >= 576px
                            576: {
                                slidesPerView: 2,
                                spaceBetween: 20
                            },
                            // when window width is >= 768px
                            768: {
                                slidesPerView: 3,
                                spaceBetween: 25
                            },
                            // when window width is >= 992px
                            992: {
                                slidesPerView: 4, // يمكنك تعديل هذا الرقم
                                spaceBetween: 30
                            }
                        }
                    });
                }

                // Swiper for Grouped Services
                if (document.querySelector('.grouped-services-swiper')) {
                    var groupedServicesSwiper = new Swiper('.grouped-services-swiper', {
                        slidesPerView: 1,
                        spaceBetween: 20,
                        loop: false,
                        grabCursor: true,
                        pagination: {
                            el: '.grouped-services-swiper-pagination',
                            clickable: true,
                        },
                        navigation: {
                            nextEl: '.grouped-services-swiper-button-next',
                            prevEl: '.grouped-services-swiper-button-prev',
                        },
                        breakpoints: {
                            576: {
                                slidesPerView: 2,
                                spaceBetween: 20
                            },
                            768: {
                                slidesPerView: 2, // الباقات قد تكون أعرض
                                spaceBetween: 25
                            },
                            992: {
                                slidesPerView: 3, // يمكنك تعديل هذا الرقم
                                spaceBetween: 30
                            }
                        }
                    });
                }
            } else {
                console.error('SwiperJS is not loaded.');
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize 3D Services Swiper
            if (document.querySelector('.services-swiper-3d')) {
                var servicesSwiper3D = new Swiper('.services-swiper-3d', {
                    effect: 'coverflow',
                    grabCursor: true,
                    centeredSlides: true,
                    slidesPerView: 'auto',
                    coverflowEffect: {
                        rotate: 0,
                        stretch: 0,
                        depth: 200,
                        modifier: 1,
                        slideShadows: false,
                    },
                    pagination: {
                        el: '.services-swiper-pagination',
                        clickable: true,
                    },
                    navigation: {
                        nextEl: '.swiper-button-next-custom',
                        prevEl: '.swiper-button-prev-custom',
                    },
                    breakpoints: {
                        768: {
                            coverflowEffect: {
                                rotate: 0,
                                stretch: -50,
                                depth: 300,
                                modifier: 1,
                            }
                        },
                        992: {
                            coverflowEffect: {
                                rotate: 0,
                                stretch: -100,
                                depth: 400,
                                modifier: 1,
                            }
                        }
                    }
                });
            }

            // Manual flip for touch devices
            const serviceCards = document.querySelectorAll('.service-card-3d');
            serviceCards.forEach(card => {
                card.addEventListener('click', function() {
                    if (window.innerWidth < 992) {
                        this.classList.toggle('flipped');
                    }
                });
            });

            // Package cards hover effect
            const packageCards = document.querySelectorAll('.package-card');
            packageCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.querySelector('.package-header').style.background =
                        'linear-gradient(135deg, #3a7bd5 0%, #2980b9 100%)';
                });
                card.addEventListener('mouseleave', function() {
                    this.querySelector('.package-header').style.background =
                        'linear-gradient(135deg, #3a7bd5 0%, #00d2ff 100%)';
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // البحث عن حاوية الفورم لتمرير الشاشة إليها
            const appointmentFormContainer = document.getElementById('appointment'); // استخدم الـ ID المصحح

            // الحصول على جميع أزرار حجز الأطباء
            const bookButtons = document.querySelectorAll('.book-doctor-btn');

            bookButtons.forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault(); // منع أي سلوك افتراضي للزر

                    // قراءة البيانات من الزر المضغوط
                    const doctorId = this.dataset.doctorId;
                    const sectionId = this.dataset.sectionId;
                    // const doctorName = this.dataset.doctorName; // يمكنك استخدامها إذا أردت
                    // const sectionName = this.dataset.sectionName; // يمكنك استخدامها إذا أردت

                    // التحقق من وجود البيانات اللازمة
                    if (doctorId && sectionId) {

                        // *** إرسال الحدث إلى مكون Livewire ***
                        // 'setSelectedDoctor' هو اسم الحدث الذي يستمع له المكون
                        // نمرر sectionId أولاً ثم doctorId كما هو متوقع في ميثود PHP
                        Livewire.emit('setSelectedDoctor', sectionId, doctorId);

                        // تمرير الشاشة (scroll) إلى حاوية الفورم بسلاسة
                        if (appointmentFormContainer) {
                            appointmentFormContainer.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start' // حاول جعل بداية الحاوية في أعلى الشاشة
                            });
                        }

                    } else {
                        console.error(
                            'Doctor ID or Section ID is missing from the button data attributes.'
                        );
                    }
                });
            });

            // ----- كود التحقق من تاريخ الموعد (من الكود الأصلي، للتأكد من عمله) -----
            const dateInput = document.getElementById('appointment_patient');
            const dateError = document.getElementById('date-error');

            if (dateInput && dateError) {
                dateInput.addEventListener('change', function() {
                    const selectedDate = new Date(this.value);
                    const today = new Date();
                    // ضبط الوقت لليوم الحالي لبداية اليوم للمقارنة الصحيحة
                    today.setHours(0, 0, 0, 0);

                    if (selectedDate < today) {
                        dateError.classList.remove('d-none'); // إظهار رسالة الخطأ
                        this.value = ''; // مسح القيمة غير الصالحة (اختياري)
                    } else {
                        dateError.classList.add('d-none'); // إخفاء رسالة الخطأ
                    }
                });
            }
            // ----- نهاية كود التحقق من التاريخ -----

        }); // نهاية document.addEventListener
    </script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
@endsection
