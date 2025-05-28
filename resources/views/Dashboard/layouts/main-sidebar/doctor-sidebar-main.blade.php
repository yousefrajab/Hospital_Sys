<!-- main-sidebar -->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar sidebar-scroll professional-sidebar"> {{-- كلاس جديد --}}
    <div class="main-sidebar-header active">
        {{-- اللوجو --}}
        <a class="desktop-logo" href="{{ route('dashboard.doctor') }}">
            <img src="{{ URL::asset('Dashboard/img/brand/hospital-logo.png') }}" class="main-logo" alt="logo">
        </a>
        <a class="logo-icon mobile-logo" href="{{ route('dashboard.doctor') }}">
            <img src="{{ URL::asset('Dashboard/img/brand/favicon-hospital.png') }}" class="logo-icon" alt="logo">
        </a>
    </div>
    <div class="main-sidemenu">
        {{-- رأس المستخدم --}}
        <div class="app-sidebar__user pro-user-header clearfix">
            <a class="user-link" href="{{ route('doctor.profile.show') }}"> {{-- جعل المنطقة قابلة للنقر --}}
                <div class="user-avatar-wrapper">
                    <img alt="user-img" class="avatar avatar-lg rounded-circle user-avatar"
                        src="{{ Auth::guard('doctor')->user()->image ? asset('Dashboard/img/doctors/' . Auth::guard('doctor')->user()->image->filename) : asset('Dashboard/img/doctor_default.png') }}">
                    <span
                        class="avatar-status-indicator {{ Auth::guard('doctor')->user()->status ? 'online' : 'offline' }}"></span><br>
                    <h6 class="font-weight-semibold user-name mb-1">{{ Auth::guard('doctor')->user()->name }}</h6>
                    <span class="text-muted user-email d-block">{{ Auth::guard('doctor')->user()->email }}</span><br>
                </div>

            </a>
        </div>

        <ul class="side-menu">
            {{-- 1. الرئيسية --}}
            <li class="slide {{ request()->routeIs('dashboard.doctor') ? 'active' : '' }}">
                <a class="side-menu__item" href="{{ route('dashboard.doctor') }}">
                    <i class="side-menu__icon fas fa-tachometer-alt"></i> {{-- أيقونة لوحة التحكم --}}
                    <span class="side-menu__label">{{ trans('main-sidebar_trans.index') }}</span>
                </a>
            </li>



            <li class="side-item side-item-category">إدارة الوصفات الطبية</li>
            <li
                class="slide {{ request()->routeIs(['doctor.prescriptions.*', 'doctor.patients.search_for_prescription']) ? 'open active' : '' }}">
                <a class="side-menu__item" data-toggle="slide" href="#">
                    <i class="side-menu__icon fas fa-file-medical-alt"></i> {{-- أيقونة للوصفات --}}
                    <span class="side-menu__label">الوصفات الطبية</span>
                    <i class="angle fe fe-chevron-left"></i>
                </a>
                <ul class="slide-menu">
                    {{-- الرابط لإنشاء وصفة جديدة (سيوجه لصفحة البحث عن مريض أولاً) --}}
                    <li class="{{ request()->routeIs('doctor.patients.search_for_prescription') ? 'current' : '' }}">
                        <a class="slide-item" href="{{ route('doctor.patients.search_for_prescription') }}">
                            <i class="fas fa-plus-circle fa-xs me-2"></i>إنشاء وصفة جديدة
                        </a>
                    </li>
                    {{-- الرابط لقائمة الوصفات التي أنشأها الطبيب --}}
                    <li class="{{ request()->routeIs('prescriptions.index') ? 'current' : '' }}">
                        <a class="slide-item" href="{{ route('prescriptions.index') }}">
                            <i class="fas fa-list-ul fa-xs me-2"></i>قائمة وصفاتي
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('doctor.prescriptions.approvalRequests') ? 'current' : '' }}">
                        <a class="slide-item" href="{{ route('doctor.prescriptions.approvalRequests') }}">
                            <i class="fas fa-user-nurse fa-xs me-2 text-warning"></i>طلبات تجديد للموافقة
                            {{-- يمكن إضافة عداد هنا إذا كان لديك طريقة سهلة لحساب عدد هذه الطلبات --}}
                            {{-- @php $pendingApprovalCount = \App\Models\Prescription::getPendingDoctorApprovalCount(Auth::id()); @endphp
                            @if ($pendingApprovalCount > 0)
                        <span class="badge bg-warning-transparent text-warning float-end">{{ $pendingApprovalCount }}</span>
                              @endif --}}
                        </a>
                    </li>
                    <li
                        class="{{ request()->routeIs('doctor.prescriptions.adherenceDashboard') ? 'active is-expanded' : '' }}">
                        <a class="slide-item" href="{{ route('doctor.prescriptions.adherenceDashboard') }}">
                            <i class="fas fa-chart-line fa-xs me-2"></i>متابعة التزام المرضى
                        </a>
                    </li>

                    {{-- (اختياري) يمكنك إضافة روابط أخرى هنا لاحقًا --}}
                    {{--
        <li class="{{ request()->routeIs('doctor.prescriptions.pending_review') ? 'current' : '' }}">
            <a class="slide-item" href="#">
                <i class="fas fa-hourglass-half fa-xs me-2"></i>وصفات تحتاج مراجعة
            </a>
        </li>
        --}}
                </ul>
            </li>

            {{-- 2. الكشوفات --}}
            <li class="side-item side-item-category">الإدارة الطبية</li>
            <li
                class="slide {{ request()->routeIs(['invoices.*', 'completedInvoices', 'reviewInvoices']) ? 'open active' : '' }}">
                {{-- 'open' للحفاظ على الفتح --}}
                <a class="side-menu__item" data-toggle="slide" href="#">
                    <i class="side-menu__icon fas fa-notes-medical"></i> {{-- أيقونة طبية --}}
                    <span class="side-menu__label">الكشوفات</span>
                    <i class="angle fe fe-chevron-left"></i></a> {{-- تغيير اتجاه السهم --}}
                <ul class="slide-menu">
                    <li class="{{ request()->routeIs('invoices.index') ? 'current' : '' }}"><a class="slide-item"
                            href="{{ route('invoices.index') }}">قائمة الكشوفات</a></li>
                    <li class="{{ request()->routeIs('completedInvoices') ? 'current' : '' }}"><a class="slide-item"
                            href="{{ route('doctor.completedInvoices') }}">الكشوفات المكتملة</a></li>
                    <li class="{{ request()->routeIs('reviewInvoices') ? 'current' : '' }}"><a class="slide-item"
                            href="{{ route('doctor.reviewInvoices') }}">المراجعات</a></li>
                </ul>
            </li>

            {{-- 3. المحادثات --}}
            <li class="slide {{ request()->routeIs(['list.patients', 'chat.patients']) ? 'open active' : '' }}">
                <a class="side-menu__item" data-toggle="slide" href="#">
                    <i class="side-menu__icon fas fa-headset"></i> {{-- أيقونة تواصل --}}
                    <span class="side-menu__label">التواصل مع المرضى</span>
                    <i class="angle fe fe-chevron-left"></i></a>
                <ul class="slide-menu">
                    <li class="{{ request()->routeIs('list.patients') ? 'current' : '' }}"><a class="slide-item"
                            href="{{ route('doctor.list.patients') }}">قائمة المرضى</a></li>
                    <li class="{{ request()->routeIs('chat.patients') ? 'current' : '' }}"><a class="slide-item"
                            href="{{ route('doctor.chat.patients') }}">صندوق الوارد</a></li>
                </ul>
            </li>
            <li class="side-item side-item-category">إدارة المواعيد</li> {{-- أو اسم فئة أنسب --}}
            <li class="slide {{ request()->routeIs('doctor.appointments') ? 'active' : '' }}"> {{-- التحقق من المسار النشط --}}
                <a class="side-menu__item" href="{{ route('doctor.appointments') }}"> {{-- استخدام اسم المسار الجديد --}}
                    <i class="side-menu__icon fas fa-calendar-check"></i> {{-- أيقونة مناسبة --}}
                    <span class="side-menu__label">مواعيـدي</span> {{-- اسم الرابط --}}
                </a>
            </li>

            {{-- 4. إدارة الجدول (مقترح) --}}
            <li class="side-item side-item-category">الإعدادات الشخصية</li>
            <li class="slide {{ request()->routeIs('doctor.schedule.show') ? 'open active' : '' }}">
                <a class="side-menu__item" data-toggle="slide" href="#">
                    <i class="side-menu__icon fas fa-calendar-check"></i> {{-- أيقونة جدول --}}
                    <span class="side-menu__label">إدارة جدولي</span>
                    <i class="angle fe fe-chevron-left"></i></a>
                <ul class="slide-menu">
                    <li><a class="slide-item" href="{{ route('doctor.schedule.show') }}">عرض الجدول الأسبوعي</a></li>
                    <li><a class="slide-item" href="#">إدارة الاستراحات</a></li>
                    <li><a class="slide-item" href="#">تحديد إجازة/عدم توفر</a></li>
                </ul>
            </li>
            {{-- 5. الملف الشخصي (رابط مباشر) --}}
            <li class="slide {{ request()->routeIs('doctor.profile.show') ? 'active' : '' }}">
                <a class="side-menu__item" href="{{ route('doctor.profile.show') }}">
                    <i class="side-menu__icon fas fa-user-cog"></i> {{-- أيقونة إعدادات المستخدم --}}
                    <span class="side-menu__label">الملف الشخصي</span>
                </a>
            </li>

            {{-- 6. تسجيل الخروج --}}
            <li class="side-item side-item-category">الحساب</li>
            <li class="slide">
                @php $logoutRoute = route('logout.doctor'); /* تبسيط للمثال */ @endphp
                <form method="POST" action="{{ $logoutRoute }}" id="logout-form-sidebar-pro">
                    @csrf
                    <a class="side-menu__item logout-link" href="{{ $logoutRoute }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form-sidebar-pro').submit();">
                        <i class="side-menu__icon fas fa-sign-out-alt"></i>
                        <span class="side-menu__label">تسجيل الخروج</span>
                    </a>
                </form>
            </li>

        </ul>
    </div>
</aside>
<!-- main-sidebar -->

{{-- ================================================ --}}
{{-- CSS مخصص للشريط الجانبي الاحترافي            --}}
{{-- ================================================ --}}
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
