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
                            <a href="{{ route('home') }}" class="theme-btn btn-style-one"><span class="txt">المزيد
                                    عنا</span></a>
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
                            <div class="icon fas fa-briefcase-medical fa-3x">
                                {{-- <i class="fas fa-briefcase-medical fa-3x text-warning"></i> <!-- أيقونة مناسبة للخدمات المجمعة/الباقات --> --}}
                            </div>
                            <h3><a href="#group_service">الخدمات المجمعة</a></h3>
                        </div>
                        <div class="text">
                            استفد من باقاتنا الصحية الشاملة التي توفر لك رعاية متكاملة بأسعار تنافسية.
                        </div>
                    </div>
                </div>

                <!-- Feature Block -->
                <div class="feature-block col-lg-3 col-md-6 col-sm-12">
                    <div class="inner-box wow fadeInLeft" data-wow-delay="250ms" data-wow-duration="1500ms">
                        <div class="upper-box">
                            <div class="icon fas fa-syringe fa-3x"></div>
                            <h3><a href="#single_service">الخدمات المفردة</a></h3>
                        </div>
                        <div class="text">احصل على خدمات طبية محددة مثل الفحوصات، التحاليل، أو استشارات متخصصة حسب حاجتك.
                        </div>
                    </div>
                </div>

                <!-- Feature Block -->
                <div class="feature-block col-lg-3 col-md-6 col-sm-12">
                    <div class="inner-box wow fadeInLeft" data-wow-delay="500ms" data-wow-duration="1500ms">
                        <div class="upper-box">
                            <div class="icon fas fa-user-md"></div>
                            <h3><a href="{{ route('website.doctors.all') }}">أطباء مؤهلين</a></h3>
                        </div>
                        <div class="text">
                            تعرّف على نخبة من الأطباء المؤهلين لتقديم أفضل استشارة ورعاية صحية.</div>
                    </div>
                </div>

                <!-- Feature Block -->
                <div class="feature-block col-lg-3 col-md-6 col-sm-12">
                    <div class="inner-box wow fadeInLeft" data-wow-delay="750ms" data-wow-duration="1500ms">
                        <div class="upper-box">
                            <div class="icon fas fa-hospital-alt fa-3x"></div>
                            <h3><a href="{{ route('website.departments.all') }}">الأقسام</a></h3>
                        </div>
                        <div class="text">
                            اكتشف أقسامنا الطبية المتنوعة والمجهزة بأحدث التقنيات لتقديم رعاية متخصصة لك.</div>
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

    <!-- Hospital Management System Banner Section - Bootstrap Enhanced -->
    <section class="hospital-management-banner-section py-5 bg-light">
        <!-- py-5 للتباعد العمودي, bg-light لخلفية خفيفة (اختياري) -->
        <div class="container">
            <div class="row align-items-center">
                <!-- align-items-center لمحاذاة العناصر عمودياً في حال اختلاف ارتفاع الأعمدة -->

                <!-- Content Column -->
                <div class="col-lg-7 col-md-12 order-lg-1 order-2 mb-4 mb-lg-0">
                    <!-- order للتحكم بترتيب الظهور على الشاشات المختلفة, mb للتباعد السفلي -->
                    <div class="content-inner p-lg-4"> <!-- p-lg-4 لإضافة padding داخلي على الشاشات الكبيرة -->
                        <!-- ممكن نضيف خط فاصل هنا باستخدام بوتستراب إذا أردت -->
                        <!-- <hr class="border-primary border-2 opacity-50" style="width: 50px;"> -->

                        <h2 class="display-5 fw-bold mb-3">
                            <!-- display-5 لكبر الخط, fw-bold للخط العريض, mb-3 للتباعد السفلي -->
                            نظام إدارة المستشفيات <br class="d-none d-md-block">
                            <!-- d-none d-md-block لإظهار الـ br فقط على شاشات متوسطة فأكبر -->
                            <span class="text-primary">نحو رعاية صحية متطورة</span>
                            <!-- text-primary لتلوين جزء من النص بلون البوتستراب الأساسي -->
                        </h2>
                        <hr class="w-25 my-4 border-2">
                        <!-- فاصل بوتستراب: w-25 لعرض 25%, my-4 للتباعد العمودي, border-2 لسمك الخط -->
                        <p class="lead mb-4"> <!-- lead لجعل النص أكبر قليلاً وأكثر بروزاً, mb-4 للتباعد السفلي -->
                            نقدم حلولاً برمجية متكاملة لإدارة المستشفيات والمراكز الطبية بكفاءة عالية. نظامنا يهدف إلى تبسيط
                            العمليات، تحسين تجربة المرضى، وتمكين الكوادر الطبية من تقديم أفضل رعاية ممكنة.
                        </p>
                        <a href="{{ route('home') }}" class="btn btn-primary btn-lg px-4">
                            <!-- btn, btn-primary, btn-lg كلاسات بوتستراب للزر, px-4 لتباعد أفقي داخلي -->
                            <span class="txt">اكتشف النظام</span>
                            <i class="fas fa-arrow-left ms-2"></i>
                            <!-- مثال لإضافة أيقونة (إذا كنت تستخدم Font Awesome), ms-2 للتباعد الأيسر -->
                        </a>
                    </div>
                </div>

                <!-- Image Column -->
                <div class="col-lg-5 col-md-12 order-lg-2 order-1"> <!-- order للتحكم بترتيب الظهور -->
                    <div class="image-inner wow fadeInRight" data-wow-delay="0ms" data-wow-duration="1500ms">
                        <!--  !!! مهم: غيّر مسار الصورة واسمها لتناسب مشروعك !!! -->
                        <img src="{{ URL::asset('Dashboard/img/media/hsptl.jpg') }}"
                            alt="نظام إدارة المستشفيات - واجهة حديثة" class="img-fluid rounded shadow-sm" />
                        <!--
                                    img-fluid: لجعل الصورة متجاوبة
                                    rounded: لحواف دائرية خفيفة
                                    shadow-sm: لظل خفيف
                                    اقترح صورة تعبر عن التكنولوجيا في المستشفى، مثل شاشة كمبيوتر تعرض واجهة نظام، أو طبيب يستخدم تابلت،
                                    أو رسم توضيحي يمثل تدفق البيانات في النظام.
                                    يفضل استخدام صيغة .webp للصور لكونها أخف وأفضل أداءً.
                                -->
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!-- End Hospital Management System Banner Section -->

    <br style="border: 1px solid #e0e0e0; margin: 40px 0;">


    @if (isset($latestServices) && $latestServices->count() > 0)
        <section class="grouped-services-section py-5 bg-white" id="single_service">
            <div class="container">
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

                <div class="row g-4 justify-content-center">
                    @foreach ($latestServices as $service)
                        <div class="col-md-6 col-lg-4">
                            <div class="package-card">
                                <div class="package-header">
                                    <h3 class="package-title">{{ $service->name }}</h3>
                                    @if (isset($service->price) && is_numeric($service->price))
                                        <div class="package-price">
                                            {{ number_format($service->price, 2) }}
                                            <span>{{ config('app.currency_symbol', 'ر.س') }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="package-body">
                                    <div class="service-excerpt mb-3">
                                        {{ Str::limit(strip_tags($service->description ?? 'وصف مختصر للخدمة...'), 100) }}
                                    </div>

                                    {{-- تصحيح: يجب استخدام $service->doctor هنا وليس $doctor --}}
                                    @if ($service->doctor)
                                        <div class="doctor-info mb-3 d-flex align-items-center"> {{-- Added d-flex and align-items-center --}}
                                            @if ($service->doctor->image)
                                                {{-- Corrected: $service->doctor --}}
                                                <img src="{{ asset('Dashboard/img/doctors/' . $service->doctor->image->filename) }}"
                                                    alt="{{ $service->doctor->name }}"
                                                    class="doctor-avatar img-fluid rounded-circle me-2"
                                                    {{-- Added me-2 for margin --}}
                                                    style="width: 60px; height: 60px; object-fit: cover;"
                                                    onerror="this.src='{{ asset('Dashboard/img/doctor_default.png') }}'">
                                            @else
                                                <img src="{{ asset('Dashboard/img/doctor_default.png') }}"
                                                    alt="صورة افتراضية"
                                                    class="doctor-avatar img-fluid rounded-circle me-2"
                                                    {{-- Added me-2 for margin --}}
                                                    style="width: 60px; height: 60px; object-fit: cover;">
                                            @endif
                                            <div class="ms-1"> {{-- Adjusted margin --}}
                                                <div class="doctor-name fw-bold">{{ $service->doctor->name }}</div>
                                                @if ($service->doctor->section)
                                                    <div class="doctor-specialty text-muted small">
                                                        {{ $service->doctor->section->name }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    <div class="package-features">
                                        <ul>
                                            <li><i class="fas fa-check text-success"></i> تشخيص دقيق</li>
                                            <li><i class="fas fa-check text-success"></i> أحدث التقنيات</li>
                                            <li><i class="fas fa-check text-success"></i> متابعة مستمرة</li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="package-footer">
                                    {{-- ======== بداية التعديل على الزر ======== --}}
                                    <button type="button" {{-- تغيير a إلى button لعدم الحاجة لـ href --}}
                                        class="btn btn-outline-primary btn-package-book book-service-trigger"
                                        {{-- إضافة كلاس book-service-trigger --}} data-service-name="{{ $service->name }}"
                                        @if ($service->doctor) data-doctor-id="{{ $service->doctor->id }}"
                                        @if ($service->doctor->section)
                                            data-section-id="{{ $service->doctor->section->id }}" @endif
                                        @endif
                                        >
                                        حجز الخدمة <i class="fas fa-calendar-alt ms-2"></i>
                                    </button>
                                    {{-- ======== نهاية التعديل على الزر ======== --}}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if (isset($latestGroupedServices) && $latestGroupedServices->count() > 0)
        <section class="grouped-services-section py-5 bg-white" id="group_service">
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
                        <div class="col-md-6 col-lg-4 d-flex"> {{-- Added d-flex --}}
                            <div class="package-card h-100 d-flex flex-column">
                                <div class="package-header">
                                    <h3 class="package-title">{{ $group->name }}</h3>
                                    @if (isset($group->Total_with_tax))
                                        <div class="package-price">
                                            {{ number_format($group->Total_with_tax, 2) }}
                                            <span>{{ config('app.currency_symbol', 'ر.س') }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="package-body flex-grow-1">
                                    @if ($group->notes)
                                        <p class="package-description">
                                            {{ Str::limit(strip_tags($group->notes), 100) }}
                                        </p>
                                    @endif

                                    {{-- عرض الطبيب الرئيسي للباقة إذا كان موجودًا --}}
                                    @if ($group->doctor)
                                        <div class="alert alert-soft-primary py-2 px-3 mb-2" style="font-size: 0.85em;">
                                            <i class="fas fa-user-shield me-1"></i>
                                            <small>
                                                <strong>مقدمة بواسطة:</strong> {{ $group->doctor->name }}
                                                @if ($group->doctor->section)
                                                    ({{ $group->doctor->section->name }})
                                                @endif
                                            </small>
                                        </div>
                                    @endif

                                    @if($group->service_group->isNotEmpty())
                                        <div class="package-features">
                                            <h6 class="mb-2" style="font-size: 0.9em;">تشمل الباقة:</h6>
                                            <ul class="list-unstyled" style="font-size: 0.85em;">
                                                @foreach ($group->service_group->take(3) as $service_item)
                                                    <li class="mb-1">
                                                        <i class="fas fa-check-circle text-success me-1"></i>
                                                        {{ Str::limit($service_item->name, 25) }}
                                                    </li>
                                                @endforeach
                                                @if ($group->service_group->count() > 3)
                                                    <li class="text-muted">
                                                        <i class="fas fa-plus-circle me-1"></i>
                                                        + {{ $group->service_group->count() - 3 }} خدمات أخرى
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                                <div class="package-footer mt-auto">
                                    {{-- ======== بداية التعديل على الزر ======== --}}
                                    <a href="{{ route('home') }}#appointment" {{-- رابط إلى صفحة الحجز --}}
                                        class="btn btn-primary btn-block btn-package-book book-service-trigger"
                                        data-service-name="{{ $group->name }}"
                                        data-service-type="package"
                                        @if ($group->doctor) {{-- إذا كانت الباقة مرتبطة بطبيب --}}
                                            data-doctor-id="{{ $group->doctor->id }}"
                                            @if ($group->doctor->section) {{-- وإذا كان للطبيب قسم --}}
                                                data-section-id="{{ $group->doctor->section->id }}"
                                            @endif
                                        @endif
                                        data-action="fillAppointmentForm" {{-- علامة للـ JavaScript في صفحة الحجز --}}
                                        >
                                        حجز الباقة <i class="fas fa-calendar-alt ms-2"></i>
                                    </a>
                                    {{-- ======== نهاية التعديل على الزر ======== --}}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

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
                <h2>ماذا يقول مرضانا</h2>
                <div class="separator"></div>
            </div>

            @if (isset($testimonials))
                <div class="testimonial-carousel owl-carousel owl-theme">
                    @foreach ($testimonials as $testimonial)
                        <div class="testimonial-block-two">
                            <div class="inner-box" style="height: 15rem;">
                                <div class="text">
                                    {{ $testimonial->comment }}
                                </div>
                                <div class="lower-box">
                                    <div class="clearfix">
                                        <div class="pull-left">
                                            <div class="quote-icon flaticon-quote"></div>
                                        </div>
                                        <div class="pull-right">
                                            <div class="author-info">
                                                <h3>{{ $testimonial->patient_name }}</h3>
                                                <br>

                                                <div class="author"> مرضانا الكرام</div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="no-testimonials text-center" style="padding: 20px 0;">
                    {{-- يمكنك وضع رسالة أو تصميم بديل هنا إذا لم تكن هناك آراء --}}
                    <p>لا توجد آراء من المرضى لعرضها حاليًا.</p>
                </div>
            @endif
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
                                    +<span class="count-text" data-speed="2500"
                                        data-stop="{{ $patientsCount ?? 0 }}">0</span>
                                    {{-- <span class="count-text" data-speed="2500" data-stop="2350">0</span> --}}
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
                                    +<span class="count-text" data-speed="3000"
                                        data-stop="{{ $doctorsCount ?? 0 }}">0</span>
                                    {{-- +<span class="count-text" data-speed="3000" data-stop="350">0</span> --}}
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
                                    +<span class="count-text" data-speed="3000"
                                        data-stop="{{ $appointmentCount ?? 0 }}">0</span>
                                    {{-- <span class="count-text" data-speed="3000" data-stop="2150">0</span> --}}
                                </div>
                                <h4 class="counter-title">الاستشارات </h4>
                            </div>
                        </div>
                    </div>

                    <!--Column-->
                    <div class="column counter-column col-lg-3 col-md-6 col-sm-12">
                        <div class="inner wow fadeInLeft" data-wow-delay="900ms" data-wow-duration="1500ms">
                            <div class="content">
                                <div class="icon flaticon-logout"></div>
                                <div class="count-outer count-box">
                                    +<span class="count-text" data-speed="2500"
                                        data-stop="{{ $successfulSurgeriesCount ?? 0 }}">0</span>
                                    {{-- +<span class="count-text" data-speed="2500" data-stop="225">0</span> --}}
                                </div>
                                <h4 class="counter-title">الاستشارات ناجحة</h4>
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
                            <div class="phone">
                                اتصل بنا ! <a href="tel:+970567077179"
                                    style="text-decoration: none; color: inherit;"><strong>+970567077179</strong></a></div>
                            {{-- <div class="phone">اتصل بنا ! <strong>+970567077179</strong></div> --}}
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
    {{-- <section class="news-section-two">
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
    </section> --}}

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
        document.addEventListener('DOMContentLoaded', function() {

            // --- المعالج الجديد لأزرار "حجز الخدمة" ---
            document.body.addEventListener('click', function(event) {
                // استخدام closest للتأكد من أننا نلتقط الزر حتى لو كان بداخله أيقونة أو نص
                const serviceButton = event.target.closest('.book-service-trigger');

                if (serviceButton) {
                    event.preventDefault(); // منع أي سلوك افتراضي للزر

                    const serviceName = serviceButton.dataset.serviceName;
                    const sectionId = serviceButton.dataset.sectionId ||
                        null; // سيكون null إذا لم يكن موجودًا
                    const doctorId = serviceButton.dataset.doctorId ||
                        null; // سيكون null إذا لم يكن موجودًا

                    console.log('Booking service:', serviceName, 'Section:', sectionId, 'Doctor:',
                        doctorId); // For debugging

                    if (serviceName && typeof Livewire !== 'undefined') {
                        // إرسال الحدث إلى مكون Livewire
                        // 'appointments.create' هو اسم مكون Livewire
                        Livewire.emitTo('appointments.create', 'setServiceDetails', serviceName, sectionId,
                            doctorId);

                        // التمرير إلى قسم فورم الحجز
                        // افترض أن فورم الحجز موجود داخل عنصر له ID 'appointment'
                        const appointmentFormSection = document.getElementById('appointment');
                        if (appointmentFormSection) {
                            appointmentFormSection.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                        } else {
                            console.warn(
                                'Appointment form section with ID "appointment" not found for scrolling.'
                            );
                        }
                    } else {
                        if (!serviceName) console.warn('Service name not found on button:', serviceButton);
                        if (typeof Livewire === 'undefined') console.error(
                            'Livewire is not defined. Cannot emit event.');
                    }
                }
            });

            // --- الكود الخاص بأزرار حجز الأطباء من قائمة الأقسام (book-doctor-btn) ---
            // إذا كان هذا الكود موجودًا لديك بالفعل ويعمل، يمكنك تركه كما هو.
            // أو إذا كنت تريد توحيد السلوك، يمكن تعديله ليشبه معالج book-service-trigger
            const doctorButtons = document.querySelectorAll('.book-doctor-btn'); // الكلاس من كود الأقسام لديك
            doctorButtons.forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    const doctorId = this.dataset.doctorId;
                    const sectionId = this.dataset.sectionId;
                    const doctorName = this.dataset.doctorName || 'المختار';
                    // const serviceNote = "استشارة مع الدكتور " + doctorName; // يمكنك إعداد ملاحظة هنا

                    if (typeof Livewire !== 'undefined') {
                        // الخيار أ: إذا كنت تريد أن يقوم هذا الزر بتعبئة الملاحظات أيضًا
                        // Livewire.emitTo('appointments.create', 'setServiceDetails', serviceNote, sectionId, doctorId);

                        // الخيار ب: إذا كان سيعتمد فقط على setSelectedDoctor (والتي قد تعبئ الملاحظات افتراضيًا إذا عدلتها)
                        Livewire.emitTo('appointments.create', 'setSelectedDoctor', sectionId,
                            doctorId);
                    }

                    const appointmentFormSection = document.getElementById('appointment');
                    if (appointmentFormSection) {
                        appointmentFormSection.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // ... (بقية كود JS الخاص بك لـ flatpickr وغيرها) ...
            // تأكد أن هذا الجزء من الكود لا يتعارض مع كود flatpickr الموجود بالفعل
            // الكود الخاص بـ flatpickr يجب أن يكون داخل document.addEventListener('alpine:init', () => { ... });
            // أو Livewire.hook إذا كان يعتمد على تحديثات Livewire.
        });

        document.addEventListener('DOMContentLoaded', function() {

            // --- المعالج الموحد لأزرار "حجز الخدمة" و "حجز الباقة" ---
            document.body.addEventListener('click', function(event) {
                const triggerButton = event.target.closest(
                    '.book-service-trigger'); // يستهدف أي زر بهذا الكلاس

                if (triggerButton) {
                    event.preventDefault();

                    const serviceName = triggerButton.dataset.serviceName;
                    const sectionId = triggerButton.dataset.sectionId || null;
                    const doctorId = triggerButton.dataset.doctorId || null;
                    const serviceType = triggerButton.dataset.serviceType || 'service'; // << قراءة النوع

                    console.log('Booking triggered:', serviceName, 'Type:', serviceType, 'Section:',
                        sectionId, 'Doctor:', doctorId);

                    if (serviceName && typeof Livewire !== 'undefined') {
                        Livewire.emitTo(
                            'appointments.create',
                            'setServiceDetails',
                            serviceName,
                            sectionId,
                            doctorId,
                            serviceType // << تمرير النوع
                        );

                        const appointmentFormSection = document.getElementById('appointment');
                        if (appointmentFormSection) {
                            appointmentFormSection.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            });
                        } else {
                            console.warn(
                                'Appointment form section with ID "appointment" not found for scrolling.'
                            );
                        }
                    } else {
                        if (!serviceName) console.warn('Service name not found on button:', triggerButton);
                        if (typeof Livewire === 'undefined') console.error(
                            'Livewire is not defined. Cannot emit event.');
                    }
                }
            });

            // --- الكود الخاص بأزرار حجز الأطباء من قائمة الأقسام (book-doctor-btn) ---
            // (اتركه كما هو إذا كان يعمل بشكل جيد، أو عدله ليتناسب مع setServiceDetails إذا أردت توحيد السلوك)
            const doctorButtons = document.querySelectorAll('.book-doctor-btn');
            doctorButtons.forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    const doctorId = this.dataset.doctorId;
                    const sectionId = this.dataset.sectionId;
                    // const doctorName = this.dataset.doctorName || 'المختار';
                    // const serviceNote = "استشارة مع الدكتور " + doctorName;

                    if (typeof Livewire !== 'undefined') {
                        // الخيار 1: إرسال كخدمة افتراضية مع تحديد الطبيب والقسم
                        // Livewire.emitTo('appointments.create', 'setServiceDetails', serviceNote, sectionId, doctorId, 'service');

                        // الخيار 2: استخدام setSelectedDoctor مباشرة (كما كان)
                        Livewire.emitTo('appointments.create', 'setSelectedDoctor', sectionId,
                            doctorId);
                    }

                    const appointmentFormSection = document.getElementById('appointment');
                    if (appointmentFormSection) {
                        appointmentFormSection.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // ... (بقية كود JS الخاص بك لـ flatpickr وغيرها) ...
        });
    </script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
@endsection
