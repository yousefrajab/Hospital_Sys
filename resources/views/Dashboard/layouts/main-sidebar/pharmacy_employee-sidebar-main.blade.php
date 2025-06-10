<!-- main-sidebar -->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar sidebar-scroll">
    <div class="main-sidebar-header active">
        <a class="desktop-logo logo-light active" href="{{ route('dashboard.pharmacy_employee') }}"><img
                src="{{ URL::asset('Dashboard/img/brand/logo.png') }}" class="main-logo" alt="logo"></a>
        <a class="desktop-logo logo-dark active" href="{{ route('dashboard.pharmacy_employee') }}"><img
                src="{{ URL::asset('Dashboard/img/brand/logo-white.png') }}" class="main-logo dark-theme"
                alt="logo"></a>
        <a class="logo-icon mobile-logo icon-light active" href="{{ route('dashboard.pharmacy_employee') }}"><img
                src="{{ URL::asset('Dashboard/img/brand/favicon.png') }}" class="logo-icon" alt="logo"></a>
        <a class="logo-icon mobile-logo icon-dark active" href="{{ route('dashboard.pharmacy_employee') }}"><img
                src="{{ URL::asset('Dashboard/img/brand/favicon-white.png') }}" class="logo-icon dark-theme"
                alt="logo"></a>
    </div>
    <div class="main-sidemenu">
        <div class="app-sidebar__user clearfix">
            <div class="dropdown user-pro-body">
                <div class="text-center">
                    @php
                        $pharmacy_employeeImage = Auth::guard('pharmacy_employee')->user()->image;
                        $imagePath =
                            $pharmacy_employeeImage && $pharmacy_employeeImage->filename
                                ? asset('Dashboard/img/pharmacyEmployees/' . $pharmacy_employeeImage->filename)
                                : asset('Dashboard/img/default_pharmacy_employee_avatar.png');
                        if (
                            $pharmacy_employeeImage &&
                            $pharmacy_employeeImage->filename &&
                            !file_exists(public_path('Dashboard/img/pharmacyEmployees/' . $pharmacy_employeeImage->filename))
                        ) {
                            $imagePath = asset('Dashboard/img/default_pharmacy_employee_avatar.png');
                        }
                    @endphp
                    <img alt="user-img" class="avatar avatar-xl rounded-circle user-avatar" src="{{ $imagePath }}">
                </div>
                <div class="user-info text-center mt-2">
                    <h4 class="font-weight-semibold mb-0">{{ Auth::guard('pharmacy_employee')->user()->name }}</h4>
                    <span class="mb-0 text-muted small">{{ Auth::guard('pharmacy_employee')->user()->email }}</span>
                </div>
            </div>
        </div>
        <ul class="side-menu">
            <li class="side-item side-item-category">{{ trans('main-sidebar_trans.Main') }}</li>

            <li class="slide {{ request()->routeIs('dashboard.pharmacy_employee') ? 'active' : '' }}">
                <a class="side-menu__item" href="{{ route('dashboard.pharmacy_employee') }}">
                    <i class="side-menu__icon typcn typcn-home-outline"></i>
                    <span class="side-menu__label">{{ trans('main-sidebar_trans.index') }}</span>
                </a>
            </li>

            {{-- ================================================ --}}
            {{-- *** بداية قسم الوصفات الطبية لموظف الصيدلية *** --}}
            {{-- ================================================ --}}
            <li class="side-item side-item-category">إدارة الوصفات و المخزون</li>
            <li class="slide {{ request()->routeIs(['pharmacy_employee.prescriptions.*', 'pharmacy_employee.medications.search']) ? 'is-expanded active' : '' }}">
                <a class="side-menu__item" data-toggle="slide" href="javascript:void(0);">
                    <i class="side-menu__icon fas fa-pills"></i> {{-- أيقونة أدوية --}}
                    <span class="side-menu__label">الوصفات والأدوية</span>
                    <i class="angle fe fe-chevron-down"></i> {{-- السهم يشير للأسفل عند الفتح --}}
                </a>
                <ul class="slide-menu">
                    <li class="{{ request()->routeIs('pharmacy_employee.prescriptions.index') ? 'active' : '' }}">
                        <a class="slide-item" href="{{ route('pharmacy_employee.prescriptions.index') }}">
                            <i class="fas fa-inbox fa-xs me-2"></i> الوصفات الواردة
                        </a>
                    </li>
                    {{-- يمكنك إضافة رابط للوصفات المصروفة أو المعلقة هنا لاحقًا --}}

                    <li class="{{ request()->routeIs('pharmacy_employee.prescriptions.dispensed') ? 'active' : '' }}">
                        <a class="slide-item" href="{{ route('pharmacy_employee.prescriptions.dispensed') }}">
                            <i class="fas fa-check-circle fa-xs me-2"></i> الوصفات المصروفة
                        </a>
                    </li>
                     <li class="{{ request()->routeIs('pharmacy_employee.prescriptions.on_hold') ? 'active' : '' }}">
                        <a class="slide-item" href="{{ route('pharmacy_employee.prescriptions.on_hold') }}">
                            <i class="fas fa-pause-circle fa-xs me-2"></i> وصفات قيد الانتظار
                        </a>
                    </li>

                    <li class="{{ request()->routeIs('pharmacy_employee.medications.search') ? 'active' : '' }}">
                        <a class="slide-item" href="{{ route('pharmacy_employee.medications.search') }}">
                            <i class="fas fa-search-dollar fa-xs me-2"></i> البحث عن دواء/مخزون
                        </a>
                    </li>
                </ul>
            </li>
            {{-- ================================================ --}}
            {{-- *** نهاية قسم الوصفات الطبية لموظف الصيدلية *** --}}
            {{-- ================================================ --}}


            {{-- قسم الملف الشخصي --}}
            <li class="side-item side-item-category">الإعدادات</li>
            <li
                class="slide {{ request()->routeIs(['pharmacy_employee.profile.show', 'pharmacy_employee.profile.edit']) ? 'is-expanded active' : '' }}">
                <a class="side-menu__item" data-toggle="slide" href="javascript:void(0);">
                    <i class="side-menu__icon fas fa-user-cog"></i> {{-- أيقونة مناسبة أكثر --}}
                    <span class="side-menu__label">الملف الشخصي</span>
                    <i class="angle fe fe-chevron-down"></i>
                </a>
                <ul class="slide-menu">
                    <li class="{{ request()->routeIs('pharmacy_employee.profile.show') ? 'active' : '' }}">
                        <a class="slide-item" href="{{ route('pharmacy_employee.profile.show') }}">
                            <i class="fas fa-id-card fa-xs me-2"></i> عرض الملف الشخصي
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('pharmacy_employee.profile.edit') ? 'active' : '' }}">
                        <a class="slide-item" href="{{ route('pharmacy_employee.profile.edit') }}">
                            <i class="fas fa-user-edit fa-xs me-2"></i> تعديل الملف الشخصي
                        </a>
                    </li>
                </ul>
            </li>


            {{-- زر تسجيل الخروج --}}
            <li class="slide">
                <a class="side-menu__item" href="javascript:void(0);"
                    onclick="event.preventDefault(); document.getElementById('logout-form-pharmacy_employee').submit();">
                    <i class="side-menu__icon fas fa-sign-out-alt text-danger"></i> {{-- أيقونة تسجيل الخروج بلون مميز --}}
                    <span class="side-menu__label">تسجيل الخروج</span>
                </a>
                <form id="logout-form-pharmacy_employee" action="{{ route('logout.pharmacy_employee') }}" method="POST"
                    style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
</aside>
<!-- main-sidebar -->

{{--
    CSS للشريط الجانبي الاحترافي (يفضل نقله إلى ملف CSS منفصل إذا لم يكن موجودًا بالفعل)
    سأفترض أنك قد قمت بتضمين الأنماط التي أرسلتها سابقًا للشريط الجانبي الاحترافي.
    إذا لم يكن كذلك، يمكنك إضافة هذا المقطع من CSS:
--}}
<style>
    /* :root {
        --sidebar-bg: #ffffff;
        --sidebar-text-color: #556a81;
        --sidebar-icon-color: #889cc0;
        --sidebar-active-bg: #e7f0ff;
        --sidebar-active-text: #4a7cfd;
        --sidebar-hover-bg: #f2f6ff;
        --sidebar-hover-text: #4a7cfd;
        --sidebar-category-text: #a0aec0;
        --sidebar-border-color: #e9edf4;
        --sidebar-user-header-bg: #f8f9fc;
        --sidebar-user-name: #343a40;
        --sidebar-user-email: #6c757d;
        --sidebar-online-indicator: #28a745;
        --sidebar-offline-indicator: #adb5bd;
        --sidebar-logout-hover-bg: #ffeef0;
        --sidebar-logout-hover-text: #f16d75;
    }

    .professional-sidebar {
        background-color: var(--sidebar-bg);
        border-left: 1px solid var(--sidebar-border-color);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
    .professional-sidebar .main-sidebar-header {
        border-bottom: 1px solid var(--sidebar-border-color);
        background-color: var(--sidebar-bg);
        padding: 12px 15px;
    }
    .professional-sidebar .main-logo { max-height: 40px; }

    .professional-sidebar .app-sidebar__user {
        padding: 15px; /* تقليل الحشو قليلاً */
        background-color: var(--sidebar-user-header-bg);
        border-bottom: 1px solid var(--sidebar-border-color);
        margin-bottom: 5px; /* تقليل الهامش */
    }
    .professional-sidebar .user-avatar { width: 50px; height: 50px; }
    .professional-sidebar .user-info { margin-top: 0.5rem; }
    .professional-sidebar .user-name { color: var(--sidebar-user-name); font-size: 0.95rem; font-weight: 600; }
    .professional-sidebar .user-email { color: var(--sidebar-user-email); font-size: 0.75rem; }

    .professional-sidebar .side-menu { padding-top: 5px; }
    .professional-sidebar .side-item-category {
        padding: 12px 20px 6px; /* تعديل الحشو */
        font-size: 0.65rem; /* تصغير الخط */
        font-weight: 700; color: var(--sidebar-category-text);
        text-transform: uppercase; letter-spacing: 0.8px;
    }
    .professional-sidebar .side-menu__item {
        display: flex; align-items: center; padding: 9px 18px; /* تعديل الحشو */
        margin: 1px 8px; /* تعديل الهوامش */
        border-radius: 6px; color: var(--sidebar-text-color);
        transition: all 0.2s ease-in-out; /* انتقال أسرع قليلاً */
        position: relative; font-weight: 500; font-size:0.875rem; /* حجم خط أصغر قليلاً */
    }
    .professional-sidebar .side-menu__item:hover {
        background-color: var(--sidebar-hover-bg); color: var(--sidebar-hover-text);
        transform: translateX(-2px);
    }
    .professional-sidebar .side-menu__item:hover .side-menu__icon { color: var(--sidebar-hover-text); }
    .professional-sidebar .side-menu__icon {
        font-size: 0.95rem; /* حجم أيقونة متناسق */
        width: 20px; text-align: center; margin-left: 10px; /* RTL */
        color: var(--sidebar-icon-color); transition: color 0.2s ease-in-out;
    }
    .professional-sidebar .slide.active > .side-menu__item,
    .professional-sidebar .slide.is-expanded > .side-menu__item { /* is-expanded هو الكلاس الذي يستخدمه القالب غالبًا */
        color: var(--sidebar-active-text);
        font-weight: 500; /* يمكن جعله 600 إذا أردت تمييزًا أكبر */
    }
    .professional-sidebar .slide.active > .side-menu__item::before,
    .professional-sidebar .slide.is-expanded > .side-menu__item::before { /* خط جانبي للنشط */
        content: ""; position: absolute; right: -8px; /* RTL */
        top: 5px; bottom: 5px; width: 3px; /* خط أنحف */
        background-color: var(--sidebar-active-text);
        border-radius: 3px;
    }
    .professional-sidebar .slide-menu {
        padding: 4px 0; margin-right: 25px; /* تعديل المسافة البادئة */
        margin-left: 8px; margin-top: 2px; /* تعديل الهوامش */
        border-right: 1px solid var(--sidebar-border-color);
        padding-right: 12px; background-color: transparent;
    }
    .professional-sidebar .slide-menu::before { display: none; }
    .professional-sidebar .slide-item {
        padding: 7px 10px; /* تعديل الحشو */
        font-size: 0.825rem; color: var(--sidebar-text-color);
        border-radius: 4px; margin: 1px 0;
    }
    .professional-sidebar .slide-item:hover { color: var(--sidebar-hover-text); background-color: var(--sidebar-hover-bg); }
    .professional-sidebar .slide-menu li.active > a.slide-item { /* تعديل لـ .active بدلاً من .current */
        color: var(--sidebar-active-text); font-weight: 500; /* يمكن جعله 600 */
        background-color: var(--sidebar-active-bg);
    }
    .professional-sidebar .slide-item .fa-xs { /* لتصغير أيقونات القائمة الفرعية */
        font-size: 0.7em;
        vertical-align: middle;
    }
    .professional-sidebar .angle { margin-right: auto; margin-left: 0; font-size: 0.75rem; transition: transform 0.25s ease-in-out; }
    .professional-sidebar .slide.is-expanded > .side-menu__item .angle { transform: rotate(90deg); } /* قالبك قد يستخدم is-expanded */

    .professional-sidebar .logout-link:hover { background-color: var(--sidebar-logout-hover-bg); color: var(--sidebar-logout-hover-text); }
    .professional-sidebar .logout-link:hover .side-menu__icon { color: var(--sidebar-logout-hover-text); } */
</style>
