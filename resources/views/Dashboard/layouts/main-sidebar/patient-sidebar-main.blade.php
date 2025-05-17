<!-- main-sidebar -->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar sidebar-scroll">
    <div class="main-sidebar-header active">
        <a class="desktop-logo logo-light active" href="{{ route('dashboard.patient') }}"><img
                src="{{ URL::asset('Dashboard/img/brand/logo.png') }}" class="main-logo" alt="logo"></a>
        <a class="desktop-logo logo-dark active" href="{{ route('dashboard.patient') }}"><img
                src="{{ URL::asset('Dashboard/img/brand/logo-white.png') }}" class="main-logo dark-theme"
                alt="logo"></a>
        <a class="logo-icon mobile-logo icon-light active" href="{{ route('dashboard.patient') }}"><img
                src="{{ URL::asset('Dashboard/img/brand/favicon.png') }}" class="logo-icon" alt="logo"></a>
        <a class="logo-icon mobile-logo icon-dark active" href="{{ route('dashboard.patient') }}"><img
                src="{{ URL::asset('Dashboard/img/brand/favicon-white.png') }}" class="logo-icon dark-theme"
                alt="logo"></a>
    </div>
    <div class="main-sidemenu">
        <div class="app-sidebar__user clearfix">
            <div class="dropdown user-pro-body">
                <div class="">
                    {{-- التأكد من أن المسار صحيح لصور المرضى --}}
                    <img alt="user-img" class="avatar avatar-lg rounded-circle user-avatar"
                        src="{{ Auth::guard('patient')->user()->image ? asset('Dashboard/img/patients/' . Auth::guard('patient')->user()->image->filename) : asset('Dashboard/img/default_patient_avatar.png') }}">
                    <span class="avatar-status profile-status bg-green"></span>
                </div>
                <div class="user-info">
                    {{-- التأكد من أن auth()->user() هنا يعود ببيانات المريض بشكل صحيح --}}
                    <h4 class="font-weight-semibold mt-3 mb-0">{{ Auth::guard('patient')->user()->name }}</h4>
                    <span class="mb-0 text-muted">{{ Auth::guard('patient')->user()->email }}</span>
                </div>
            </div>
        </div>
        <ul class="side-menu">
            <li class="side-item side-item-category">{{ trans('main-sidebar_trans.Main') }}</li>

            <li class="slide {{ request()->routeIs('dashboard.patient') ? 'active' : '' }}">
                <a class="side-menu__item" href="{{ route('dashboard.patient') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path d="M5 5h4v6H5zm10 8h4v6h-4zM5 17h4v2H5zM15 5h4v2h-4z" opacity=".3" />
                        <path
                            d="M3 13h8V3H3v10zm2-8h4v6H5V5zm8 16h8V11h-8v10zm2-8h4v6h-4v-6zM13 3v6h8V3h-8zm6 4h-4V5h4v2zM3 21h8v-6H3v6zm2-4h4v2H5v-2z" />
                    </svg>
                    <span class="side-menu__label">{{ trans('main-sidebar_trans.index') }}</span>
                </a>
            </li>

            {{-- قسم الملف الشخصي --}}
            <li
                class="slide {{ request()->routeIs('profile.show') || request()->routeIs('profile.edit') ? 'is-expanded active' : '' }}">
                <a class="side-menu__item" data-toggle="slide" href="#"> {{-- href="#" للقائمة المنسدلة --}}
                    <i class="side-menu__icon fas fa-user-circle"></i> {{-- أيقونة أفضل للملف الشخصي --}}
                    <span class="side-menu__label">الملف الشخصي</span>
                    <i class="angle fe fe-chevron-down"></i>
                </a>
                <ul class="slide-menu">
                    <li class="{{ request()->routeIs('profile.show') ? 'active' : '' }}">
                        <a class="slide-item" href="{{ route('profile.show') }}">
                            <i class="fas fa-eye me-2"></i> عرض الملف الشخصي
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                        <a class="slide-item" href="{{ route('profile.edit') }}">
                            <i class="fas fa-edit me-2"></i> تعديل الملف الشخصي
                        </a>
                    </li>
                </ul>
            </li>

            {{-- ***** قسم المواعيد (جديد) ***** --}}
            <li class="slide {{ request()->is('patient/appointments*') ? 'is-expanded active' : '' }}">
                {{-- لتفعيل القائمة إذا كان المسار يبدأ بـ patient/appointments --}}
                <a class="side-menu__item" data-toggle="slide" href="#">
                    <i class="side-menu__icon fas fa-calendar-alt"></i> {{-- أيقونة التقويم للمواعيد --}}
                    <span class="side-menu__label">المواعيد</span>
                    <i class="angle fe fe-chevron-down"></i>
                </a>
                <ul class="slide-menu">
                    {{-- افترض أن لديك هذه الـ routes لاحقًا --}}
                    <li class="{{ request()->routeIs('appointments.upcoming') ? 'active' : '' }}">
                        <a class="slide-item" href="{{ route('appointments.upcoming') }}"> {{-- route('appointments.upcoming') --}}
                            <i class="fas fa-calendar-check me-2 text-success"></i> المواعيد القادمة
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('appointments.past') ? 'active' : '' }}">
                        <a class="slide-item" href="{{route('appointments.past') }}"> {{-- route('appointments.past') --}}
                            <i class="fas fa-history me-2 text-muted"></i> المواعيد السابقة
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('appointments.create') ? 'active' : '' }}">
                        <a class="slide-item" href="{{ route('patient.appointments.create.form') }}"> {{-- هذا الـ route قد يكون عامًا أو خاصًا بالمريض --}}
                            <i class="fas fa-plus-circle me-2 text-primary"></i> طلب موعد جديد
                        </a>
                    </li>
                </ul>
            </li>


            <li class="slide {{ request()->is('patient/operations*') ? 'is-expanded active' : '' }}">
                <a class="side-menu__item" data-toggle="slide" href="#"><svg xmlns="http://www.w3.org/2000/svg"
                        class="side-menu__icon" viewBox="0 0 24 24">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path d="M19 5H5v14h14V5zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z" opacity=".3" />
                        <path
                            d="M3 5v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2zm2 0h14v14H5V5zm2 5h2v7H7zm4-3h2v10h-2zm4 6h2v4h-2z" />
                    </svg><span class="side-menu__label">عمليات المريض</span><i
                        class="angle fe fe-chevron-down"></i></a>
                <ul class="slide-menu">
                    <li><a class="slide-item" href="{{ route('invoices.patient') }}">قائمة الفواتير</a></li>
                    <li><a class="slide-item" href="{{ route('laboratories.patient') }}">المختبر</a></li>
                    <li><a class="slide-item" href="{{ route('rays.patient') }}">الاشعة</a></li>
                </ul>
            </li>

            <li class="slide {{ request()->is('patient/chat*') ? 'is-expanded active' : '' }}">
                <a class="side-menu__item" data-toggle="slide" href="#"><svg
                        xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
                        <path d="M0 0h24v24H0V0z" fill="none" />
                        <path d="M19 5H5v14h14V5zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z" opacity=".3" />
                        <path
                            d="M3 5v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2zm2 0h14v14H5V5zm2 5h2v7H7zm4-3h2v10h-2zm4 6h2v4h-2z" />
                    </svg><span class="side-menu__label">المحادثات</span><i class="angle fe fe-chevron-down"></i></a>
                <ul class="slide-menu">
                    <li><a class="slide-item" href="{{ route('list.doctors') }}">قائمة الاطباء</a></li>
                    <li><a class="slide-item" href="{{ route('chat.doctors') }}">المحادثات الاخيرة</a></li>
                </ul>
            </li>
        </ul>
    </div>
</aside>
<!-- main-sidebar -->
