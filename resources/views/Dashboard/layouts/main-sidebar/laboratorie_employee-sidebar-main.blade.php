<!-- main-sidebar -->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar sidebar-scroll">
    <div class="main-sidebar-header active">
        <a class="desktop-logo logo-light active" href="{{ url('/' . ($page = 'index')) }}"><img
                src="{{ URL::asset('Dashboard/img/brand/logo.png') }}" class="main-logo" alt="logo"></a>
        <a class="desktop-logo logo-dark active" href="{{ url('/' . ($page = 'index')) }}"><img
                src="{{ URL::asset('Dashboard/img/brand/logo-white.png') }}" class="main-logo dark-theme"
                alt="logo"></a>
        <a class="logo-icon mobile-logo icon-light active" href="{{ url('/' . ($page = 'index')) }}"><img
                src="{{ URL::asset('Dashboard/img/brand/favicon.png') }}" class="logo-icon" alt="logo"></a>
        <a class="logo-icon mobile-logo icon-dark active" href="{{ url('/' . ($page = 'index')) }}"><img
                src="{{ URL::asset('Dashboard/img/brand/favicon-white.png') }}" class="logo-icon dark-theme"
                alt="logo"></a>
    </div>
    <div class="main-sidemenu">
        <div class="app-sidebar__user clearfix">
            <div class="dropdown user-pro-body">
                <div class="">
                    <img alt="user-img" class="avatar avatar-lg rounded-circle user-avatar"
                        src="{{ Auth::guard('laboratorie_employee')->user()->image ? asset('Dashboard/img/laboratorieEmployees/' . Auth::guard('laboratorie_employee')->user()->image->filename) : asset('Dashboard/img/laboratorie_employee_default.png') }}">
                    <span class="avatar-status profile-status bg-green"></span>
                </div>
                <div class="user-info">
                    <h4 class="font-weight-semibold mt-3 mb-0">{{ auth()->user()->name }}</h4>
                    <span class="mb-0 text-muted">{{ auth()->user()->email }}</span>
                </div>
            </div>
        </div>
        <ul class="side-menu">
            <li class="side-item side-item-category">{{ trans('main-sidebar_trans.Main') }}</li>

            <li class="slide">
                <a class="side-menu__item" href="{{ route('dashboard.laboratorie_employee') }}"><svg
                        xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path d="M5 5h4v6H5zm10 8h4v6h-4zM5 17h4v2H5zM15 5h4v2h-4z" opacity=".3" />
                        <path
                            d="M3 13h8V3H3v10zm2-8h4v6H5V5zm8 16h8V11h-8v10zm2-8h4v6h-4v-6zM13 3v6h8V3h-8zm6 4h-4V5h4v2zM3 21h8v-6H3v6zm2-4h4v2H5v-2z" />
                    </svg><span class="side-menu__label">{{ trans('main-sidebar_trans.index') }}</a>
            </li>


            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="#">
                    <!-- أيقونة Font Awesome مصححة -->
                    <i class="side-menu__icon fa fa-user-cog"></i>
                    <span class="side-menu__label">الملف الشخصي</span>
                    <!-- أيقونة السهم بمحاذاة صحيحة -->
                    <i class="angle fa fa-chevron-down"></i>
                </a>

                <ul class="slide-menu">
                    <li>
                        <a class="slide-item" href="{{ route('laboratorie_employee.profile.show') }}">
                            <i class="fa fa-eye mr-2"></i> عرض الملف الشخصي
                        </a>
                    </li>
                    <li>
                        <a class="slide-item" href="{{ route('laboratorie_employee.profile.edit') }}">
                            <i class="fa fa-edit mr-2"></i> تعديل الملف الشخصي
                        </a>
                    </li>
                </ul>
            </li>


            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . ($page = '#')) }}"><svg
                        xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path d="M19 5H5v14h14V5zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z" opacity=".3" />
                        <path
                            d="M3 5v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2zm2 0h14v14H5V5zm2 5h2v7H7zm4-3h2v10h-2zm4 6h2v4h-2z" />
                    </svg><span class="side-menu__label">كشوفات المختبر </span><i
                        class="angle fe fe-chevron-down"></i></a>
                <ul class="slide-menu">
                    <li><a class="slide-item"
                            href="{{ route('laboratorie_employee.invoices_laboratorie_employee.index') }}">قائمة
                            الكشوفات</a></li>
                    <li><a class="slide-item" href="{{ route('laboratorie_employee.completed_invoicess') }}">قائمة
                            الكشوفات المكتملة</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</aside>
<!-- main-sidebar -->
<style>
    :root {
        --sidebar-bg: #ffffff;
        /* خلفية بيضاء */
        --sidebar-text-color: #556a81;
        /* لون نص رمادي مزرق */
        --sidebar-icon-color: #889cc0;
        /* لون أيقونة أفتح */
        --sidebar-active-bg: #e7f0ff;
        /* خلفية العنصر النشط (أزرق فاتح جداً) */
        --sidebar-active-text: #4a7cfd;
        /* لون نص ورسم العنصر النشط (أزرق متوسط) */
        --sidebar-hover-bg: #f2f6ff;
        /* خلفية عند المرور */
        --sidebar-hover-text: #4a7cfd;
        --sidebar-category-text: #a0aec0;
        /* لون نصوص الفئات */
        --sidebar-border-color: #e9edf4;
        /* لون الخطوط الفاصلة */
        --sidebar-user-header-bg: #f8f9fc;
        /* خلفية رأس المستخدم */
        --sidebar-user-name: #343a40;
        /* لون اسم المستخدم */
        --sidebar-user-email: #6c757d;
        --sidebar-online-indicator: #28a745;
        --sidebar-offline-indicator: #adb5bd;
        --sidebar-logout-hover-bg: #ffeef0;
        --sidebar-logout-hover-text: #f16d75;
    }

    /* الوضع الداكن (إذا أردت دعمه) */
    /* body.dark-theme :root { ... تعريف ألوان مختلفة ... } */

    .professional-sidebar {
        background-color: var(--sidebar-bg);
        border-left: 1px solid var(--sidebar-border-color);
        /* خط فاصل يساري */
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .professional-sidebar .main-sidebar-header {
        border-bottom: 1px solid var(--sidebar-border-color);
        box-shadow: none;
        background-color: var(--sidebar-bg);
        /* التأكد من الخلفية */
        padding: 12px 15px;
        /* تعديل الحشوة */
    }

    .professional-sidebar .main-logo {
        max-height: 40px;
    }

    .professional-sidebar .pro-user-header {
        padding: 20px 15px;
        background-color: var(--sidebar-user-header-bg);
        border-bottom: 1px solid var(--sidebar-border-color);
        margin-bottom: 10px;
    }

    .professional-sidebar .pro-user-header .user-link {
        display: flex;
        align-items: center;
        gap: 15px;
        text-decoration: none;
        transition: background-color 0.2s ease;
        padding: 10px;
        border-radius: 8px;
    }

    .professional-sidebar .pro-user-header .user-link:hover {
        background-color: rgba(0, 0, 0, 0.03);
    }

    .professional-sidebar .user-avatar-wrapper {
        position: relative;
        flex-shrink: 0;
    }

    .professional-sidebar .user-avatar {
        width: 50px;
        /* حجم أصغر للصورة */
        height: 50px;
        border: 2px solid transparent;
        /* إطار شفاف */
    }

    .professional-sidebar .avatar-status-indicator {
        position: absolute;
        bottom: 0px;
        right: 0px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 2px solid var(--sidebar-bg);
        /* استخدام لون خلفية الشريط */
    }

    .professional-sidebar .avatar-status-indicator.online {
        background-color: var(--sidebar-online-indicator);
    }

    .professional-sidebar .avatar-status-indicator.offline {
        background-color: var(--sidebar-offline-indicator);
    }

    .professional-sidebar .user-info {
        text-align: right;
        flex-grow: 1;
    }

    .professional-sidebar .user-name {
        color: var(--sidebar-user-name);
        font-size: 0.95rem;
        font-weight: 600;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        /* لمنع تجاوز النص */
        max-width: 150px;
        /* تحديد عرض أقصى */
    }

    .professional-sidebar .user-email {
        color: var(--sidebar-user-email);
        font-size: 0.75rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 150px;
    }

    .professional-sidebar .side-menu {
        padding-top: 10px;
    }

    .professional-sidebar .side-item-category {
        padding: 15px 20px 8px;
        font-size: 0.7rem;
        font-weight: 700;
        color: var(--sidebar-category-text);
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }

    .professional-sidebar .side-menu__item {
        display: flex;
        align-items: center;
        padding: 10px 20px;
        /* تعديل الحشوة */
        margin: 1px 10px;
        /* تعديل الهوامش */
        border-radius: 6px;
        /* حواف دائرية أقل */
        color: var(--sidebar-text-color);
        transition: all 0.25s ease-in-out;
        position: relative;
        font-weight: 500;
    }

    .professional-sidebar .side-menu__item:hover {
        background-color: var(--sidebar-hover-bg);
        color: var(--sidebar-hover-text);
        transform: translateX(-2px);
        /* تحريك بسيط لليمين عند المرور (عدله لـ +2px للعربية) */
    }

    .professional-sidebar .side-menu__item:hover .side-menu__icon {
        color: var(--sidebar-hover-text);
    }

    .professional-sidebar .side-menu__icon {
        font-size: 1rem;
        /* حجم الأيقونة */
        width: 22px;
        /* لضمان محاذاة النص */
        text-align: center;
        margin-left: 12px;
        /* مسافة بعد الأيقونة (للعربية) */
        margin-right: 0;
        color: var(--sidebar-icon-color);
        transition: color 0.25s ease-in-out;
    }

    /* تمييز العنصر النشط */
    .professional-sidebar .slide.active>.side-menu__item,
    .professional-sidebar .slide.open>.side-menu__item {
        color: var(--sidebar-active-text);
        font-weight: 600;
        /* خط أعرض للنشط */
        /* background-color: var(--sidebar-active-bg); */
        /* يمكن إضافة خلفية للنشط أيضاً */
    }

    /* الخط الجانبي للعنصر النشط */
    .professional-sidebar .slide.active>.side-menu__item::before {
        content: "";
        position: absolute;
        right: -10px;
        /* لليمين */
        top: 0;
        bottom: 0;
        width: 4px;
        background-color: var(--sidebar-active-text);
        border-radius: 0 5px 5px 0;
        /* حواف للخط */
    }

    /* القوائم الفرعية */
    .professional-sidebar .slide-menu {
        padding: 5px 0;
        margin-right: 35px;
        /* مسافة بادئة أكبر لليمين */
        margin-left: 10px;
        margin-top: 0;
        /* إزالة الهامش العلوي */
        border-right: 1px solid var(--sidebar-border-color);
        /* خط يميني للقائمة الفرعية */
        padding-right: 15px;
        padding-left: 0;
        background-color: transparent;
        /* جعل الخلفية شفافة */
    }

    .professional-sidebar .slide-menu::before {
        display: none;
    }

    /* إخفاء الخط الافتراضي للقالب */

    .professional-sidebar .slide-item {
        padding: 8px 10px;
        font-size: 0.85rem;
        color: var(--sidebar-text-color);
        border-radius: 4px;
        margin: 1px 0;
    }

    .professional-sidebar .slide-item:hover {
        color: var(--sidebar-hover-text);
        background-color: var(--sidebar-hover-bg);
    }

    /* تمييز العنصر الفرعي النشط */
    .professional-sidebar .slide-menu li.current>a.slide-item {
        color: var(--sidebar-active-text);
        font-weight: 600;
        background-color: var(--sidebar-active-bg);
    }


    /* زاوية الفتح/الإغلاق */
    .professional-sidebar .angle {
        margin-right: auto;
        /* لنقل السهم لليسار */
        margin-left: 0;
        font-size: 0.8rem;
        transition: transform 0.3s cubic-bezier(0.25, 0.1, 0.25, 1);
    }

    .professional-sidebar .slide.open>.side-menu__item .angle {
        transform: rotate(0deg);
        /* تغيير الزاوية للعربية */
    }

    /* ملاحظة: قد تحتاج لتعديل JavaScript الذي يتحكم في كلاس 'open' ليعكس السهم بشكل صحيح */

    /* رابط تسجيل الخروج */
    .professional-sidebar .logout-link:hover {
        background-color: var(--sidebar-logout-hover-bg);
        color: var(--sidebar-logout-hover-text);
    }

    .professional-sidebar .logout-link:hover .side-menu__icon {
        color: var(--sidebar-logout-hover-text);
    }
</style>
