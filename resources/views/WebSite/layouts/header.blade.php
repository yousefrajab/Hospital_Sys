{{-- WebSite/layouts/header.blade.php --}}
<div class="nav-outer clearfix">

    <!--Mobile Navigation Toggler For Mobile-->
    <div class="mobile-nav-toggler"><span class="icon flaticon-menu"></span></div>

    <nav class="main-menu navbar-expand-md navbar-light">
        <div class="navbar-header">
            <!-- Toggle Button -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="icon flaticon-menu"></span>
            </button>
        </div>

        <div class="collapse navbar-collapse clearfix" id="navbarSupportedContent">
            <ul class="navigation clearfix">
                <li class="current"><a href="{{ url('/') }}">الرئيسية</a></li> {{-- افترض أن لديك route اسمه home --}}
                <li>
                    <a title="لوحة التحكم" href="{{ route('dashboard.patient') }}">
                        <span class="fas fa-user-cog"></span> لوحة التحكم
                    </a>
                </li>

                <li class="dropdown"><a href="{{ route('website.doctors.all') }}">الاطباء</a>
                    <ul>
                        <li><a href="{{ route('website.doctors.all') }}">Doctors</a></li>
                    </ul>
                </li>
                <li class="dropdown"><a href="#">الاقسام</a>
                    <ul>
                        <li><a href="{{ route('website.departments.all') }}">Sections</a></li>
                    </ul>
                </li>
                <li class="dropdown"><a href="#">الخدمات</a>
                    <ul>
                        <li><a href="{{ route('website.services.all') }}">الخدمات المفردة</a></li>

                        <li><a href="{{ route('website.group_services.all') }}">الخدمات المجمعة</a></li>
                    </ul>
                </li>
                <li class="dropdown"><a href="#">المواعيد</a>
                    <ul>
                        <li><a href="{{ route('website.my.appointments') }}">مواعيدي</a></li>
                    </ul>
                </li>
                <li class="dropdown"><a href="#">الفواتير</a>
                    <ul>
                        <li><a href="{{ route('website.my.invoices') }}">فواتيري</a></li>
                    </ul>
                </li>
                <li class="dropdown"><a href="#"> الحسابات </a>
                    <ul>
                        <li><a href="{{ route('website.my.account') }}">كشف حساباتي</a></li>
                    </ul>
                </li>
                <li class="dropdown"><a href="#">{{ LaravelLocalization::getCurrentLocaleNative() }}</a>
                    <ul>
                        @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                            <li>
                                <a rel="alternate" hreflang="{{ $localeCode }}"
                                    href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                                    {{ $properties['native'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
    <!-- Main Menu End-->

    <div class="outer-box clearfix">
        @php
            $authenticatedUser = null;
            $userGuard = null;
            $logoutRoute = null;
            $userImage = asset('Dashboard/img/patient_default.png'); // صورة افتراضية عامة

            if (Auth::guard('patient')->check()) {
                $authenticatedUser = Auth::guard('patient')->user();
                $userGuard = 'patient';
                $logoutRoute = route('logout.patient');
                if ($authenticatedUser->image && $authenticatedUser->image->filename) {
                    $userImage = asset('Dashboard/img/patients/' . $authenticatedUser->image->filename);
                }
            }

            // يمكنك إضافة المزيد من الـ else if لباقي الـ guards (ray_employee, laboratorie_employee) بنفس الطريقة
            // وتحديد مسارات صورهم ومسارات تسجيل الخروج الخاصة بهم.

        @endphp

        @if ($authenticatedUser)
            <div class="user-profile-card">
                <div class="user-avatar-container">
                    <img alt="{{ $authenticatedUser->name }}" class="user-avatar" src="{{ $userImage }}"
                        onerror="this.src='{{ asset('Dashboard/img/patient_default.png') }}';"> {{-- Fallback image --}}
                </div>
                <div class="user-info-dropdown">
                    <div class="user-name-email">
                        <h4 class="user-name">{{ $authenticatedUser->name }}</h4>
                        <span class="user-email">{{ $authenticatedUser->email }}</span>
                    </div>
                    @if ($logoutRoute)
                        <form method="POST" action="{{ $logoutRoute }}" class="logout-form">
                            @csrf
                            <button type="submit" class="btn-logout">
                                <i class="fas fa-sign-out-alt"></i>
                                تسجيل الخروج
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endif

        <!-- Nav Toggler -->
        <div class="nav-box">
            <button class="nav-toggler navSidebar-button"><span class="icon flaticon-menu-1"></span></button>
        </div>

        <!-- Social Box -->
        <ul class="header-social-icons clearfix">
            <li><a href="#" aria-label="Facebook"><span class="fab fa-facebook-f"></span></a></li>
            <li><a href="#" aria-label="Twitter"><span class="fab fa-twitter"></span></a></li>
            <li><a href="#" aria-label="LinkedIn"><span class="fab fa-linkedin-in"></span></a></li>
        </ul>

        <!-- Search Btn -->
        <div class="search-box-btn"><span class="icon flaticon-search"></span></div>
    </div>
</div>

<style>
    /* Styles for WebSite/layouts/header.blade.php */
    /* You can move these styles to an external CSS file later */

    .main-menu .navigation>li>a.header-login-link,
    .main-menu .navigation>li>a.header-dashboard-link {
        color: #ffffff;
        /* أو اللون الذي تفضله لروابط الهيدر */
        padding: 20px 15px;
        /* تعديل الحشوة حسب الحاجة */
    }

    .main-menu .navigation>li>a.header-login-link:hover,
    .main-menu .navigation>li>a.header-dashboard-link:hover {
        color: #f0f0f0;
        /* لون عند التحويم */
    }

    .main-menu .navigation>li>a .fas {
        margin-right: 8px;
        /* مسافة بين الأيقونة والنص */
    }

    .nav-outer .outer-box {
        display: flex;
        align-items: center;
        gap: 15px;
        /* مسافة بين العناصر في الـ outer-box */
    }

    /* User Profile Card Styles */
    .user-profile-card {
        position: relative;
        display: flex;
        align-items: center;
        cursor: pointer;
    }

    .user-avatar-container .user-avatar {
        width: 40px;
        /* حجم أصغر للأفاتار في الهيدر */
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #fff;
        /* إطار أبيض حول الصورة */
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        transition: transform 0.2s ease;
    }

    .user-profile-card:hover .user-avatar {
        transform: scale(1.1);
    }

    .user-info-dropdown {
        display: none;
        /* مخفية بشكل افتراضي */
        position: absolute;
        top: 100%;
        /* تظهر أسفل الأفاتار */
        left: 50%;
        transform: translateX(-50%);
        margin-top: 10px;
        /* مسافة بسيطة من الأعلى */
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        padding: 15px;
        min-width: 220px;
        z-index: 100;
        text-align: center;
    }

    /* في حالة RTL */
    .rtl .user-info-dropdown {
        left: auto;
        right: 50%;
        transform: translateX(50%);
    }


    .user-profile-card:hover .user-info-dropdown {
        display: block;
        /* تظهر عند التحويم على الكرت بالكامل */
    }

    .user-info-dropdown .user-name-email {
        margin-bottom: 12px;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
    }

    .user-info-dropdown .user-name {
        margin: 0 0 5px 0;
        font-size: 1.1em;
        font-weight: 600;
        color: #333;
    }

    .user-info-dropdown .user-email {
        font-size: 0.9em;
        color: #777;
        display: block;
        word-break: break-all;
    }

    .user-info-dropdown .btn-logout {
        background-color: #e74c3c;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 20px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        width: 100%;
        justify-content: center;
        font-size: 0.95em;
    }

    .user-info-dropdown .btn-logout:hover {
        background-color: #c0392b;
    }

    .user-info-dropdown .btn-logout .fas {
        font-size: 0.9em;
    }


    /* Social Icons in Header */
    .header-social-icons {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
        /* مسافة بين أيقونات التواصل */
    }

    .header-social-icons li a {
        color: #fff;
        /* لون أيقونات التواصل الاجتماعي */
        font-size: 16px;
        transition: color 0.3s ease;
    }

    .header-social-icons li a:hover {
        color: #ecf0f1;
        /* لون عند التحويم */
    }

    /* Nav Toggler & Search Btn - افترض أن هذه الستايلات موجودة في ملف الستايل الرئيسي للقالب */
    .nav-box .nav-toggler,
    .search-box-btn {
        color: #fff;
        /* تأكد أن لونها مناسب لخلفية الهيدر */
        /* قد تحتاج لتعديل الـ padding أو الـ margin إذا لزم الأمر */
    }

    /* لضمان أن القائمة المنسدلة للمستخدم لا تقطعها عناصر أخرى */
    .nav-outer {
        position: relative;
        z-index: 99;
        /* أو قيمة أعلى إذا لزم الأمر */
    }
</style>
