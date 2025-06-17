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
                <div class="text-center"> {{-- text-center to center the avatar if no other content beside it --}}
                    {{-- تأكد من أن المسار صحيح لصور المرضى --}}
                    @php
                        $patientImage = Auth::guard('patient')->user()->image;
                        $imagePath =
                            $patientImage && $patientImage->filename
                                ? asset('Dashboard/img/patients/' . $patientImage->filename)
                                : asset('Dashboard/img/default_patient_avatar.png');
                        // Fallback if image file does not exist, though asset() should handle this if path is wrong
                        if (
                            $patientImage &&
                            $patientImage->filename &&
                            !file_exists(public_path('Dashboard/img/patients/' . $patientImage->filename))
                        ) {
                            $imagePath = asset('Dashboard/img/default_patient_avatar.png');
                        }
                    @endphp
                    <img alt="user-img" class="avatar avatar-xl rounded-circle user-avatar" src="{{ $imagePath }}">
                    {{-- <span class="avatar-status profile-status bg-green"></span> --}} {{-- Status can be removed if not needed --}}
                </div>
                <div class="user-info text-center mt-2"> {{-- text-center and mt-2 for better spacing --}}
                    <h4 class="font-weight-semibold mb-0">{{ Auth::guard('patient')->user()->name }}</h4>
                    <span class="mb-0 text-muted small">{{ Auth::guard('patient')->user()->email }}</span>
                </div>
            </div>
        </div>
        <ul class="side-menu">
            <li class="side-item side-item-category">{{ trans('main-sidebar_trans.Main') }}</li>

            <li class="slide {{ request()->routeIs('dashboard.patient') ? 'active' : '' }}">
                <a class="side-menu__item" href="{{ route('dashboard.patient') }}">
                    <i class="side-menu__icon typcn typcn-home-outline"></i>
                    <span class="side-menu__label">{{ trans('main-sidebar_trans.index') }}</span>
                </a>
            </li>

            {{-- قسم الملف الشخصي --}}
            <li
                class="slide {{ request()->routeIs('profile.show') || request()->routeIs('profile.edit') ? 'is-expanded active' : '' }}">
                <a class="side-menu__item" data-toggle="slide" href="javascript:void(0);">
                    <i class="side-menu__icon fas fa-user-circle"></i>
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

            {{-- قسم المواعيد --}}
            {{-- Use request()->is('patient/appointments*') for broader match or specific routes --}}
            <li
                class="slide {{ Request::is('patient/appointments*') || request()->routeIs('appointments.upcoming') || request()->routeIs('appointments.past') || request()->routeIs('patient.appointments.create.form') ? 'is-expanded active' : '' }}">
                <a class="side-menu__item" data-toggle="slide" href="javascript:void(0);">
                    <i class="side-menu__icon fas fa-calendar-alt"></i>
                    <span class="side-menu__label">المواعيد</span>
                    <i class="angle fe fe-chevron-down"></i>
                </a>
                <ul class="slide-menu">
                    <li class="{{ request()->routeIs('appointments.upcoming') ? 'active' : '' }}">
                        <a class="slide-item" href="{{ route('appointments.upcoming') }}">
                            <i class="fas fa-calendar-day me-2 text-success"></i> المواعيد القادمة
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('appointments.past') ? 'active' : '' }}">
                        <a class="slide-item" href="{{ route('appointments.past') }}">
                            <i class="fas fa-history me-2 text-muted"></i> المواعيد السابقة
                        </a>
                    </li>
                    {{-- Changed route name here to match the one used in content section --}}
                    <li class="{{ request()->routeIs('patient.appointments.create.form') ? 'active' : '' }}">
                        <a class="slide-item" href="{{ route('patient.appointments.create.form') }}">
                            <i class="fas fa-plus-circle me-2 text-primary"></i> طلب موعد جديد
                        </a>
                    </li>
                </ul>
            </li>

            {{-- قسم الصيدلية --}}
            <li class="slide {{ request()->is('patient/pharmacy*') ? 'is-expanded active' : '' }}">
                <a class="side-menu__item" data-toggle="slide" href="javascript:void(0);">
                    <i class="side-menu__icon fas fa-pills"></i> {{-- أو fas fa-prescription-bottle-alt --}}
                    <span class="side-menu__label">وصفاتي الطبية</span>
                    <i class="angle fe fe-chevron-down"></i>
                </a>
                <ul class="slide-menu">
                    <li class="{{ request()->routeIs('patient.pharmacy.index') ? 'active' : '' }}">
                        <a class="slide-item" href="{{ route('patient.pharmacy.index') }}">
                            <i class="fas fa-list-alt me-2"></i> عرض وصفاتي
                        </a>
                    </li>
                    {{-- يمكنك إضافة "طلبات إعادة الصرف المعلقة" هنا لاحقًا إذا أردت --}}

                    <li class="{{ request()->routeIs('patient.pharmacy.refill-requests.pending') ? 'active' : '' }}">
                        <a class="slide-item" href="{{ route('patient.pharmacy.refill-requests.pending') }}">
                            <i class="fas fa-hourglass-half me-2"></i> طلبات إعادة الصرف
                        </a>
                    </li>

                </ul>
            </li>

            {{-- قسم عمليات المريض --}}
            <li
                class="slide {{ request()->is('patient/invoices*') || request()->is('patient/laboratories*') || request()->is('patient/rays*') ? 'is-expanded active' : '' }}">
                <a class="side-menu__item" data-toggle="slide" href="javascript:void(0);">
                    <i class="side-menu__icon fas fa-file-medical-alt"></i>
                    <span class="side-menu__label">سجلاتي الطبية</span>
                    <i class="angle fe fe-chevron-down"></i>
                </a>
                <ul class="slide-menu">
                    <li class="{{ request()->routeIs('invoices.patient') ? 'active' : '' }}">
                        <a class="slide-item" href="{{ route('invoices.patient') }}">
                            <i class="fas fa-file-invoice me-2"></i> قائمة الفواتير
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('laboratories.patient') ? 'active' : '' }}">
                        <a class="slide-item" href="{{ route('laboratories.patient') }}">
                            <i class="fas fa-vial me-2"></i> المختبر
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('rays.patient') ? 'active' : '' }}">
                        <a class="slide-item" href="{{ route('rays.patient') }}">
                            <i class="fas fa-x-ray me-2"></i> الاشعة
                        </a>
                    </li>
                </ul>
            </li>

            {{-- قسم المحادثات --}}
            <li
                class="slide {{ request()->is('patient/chat*') || request()->routeIs('list.doctors') || request()->routeIs('chat.doctors') ? 'is-expanded active' : '' }}">
                <a class="side-menu__item" data-toggle="slide" href="javascript:void(0);">
                    <i class="side-menu__icon fas fa-comments"></i>
                    <span class="side-menu__label">المحادثات</span>
                    <i class="angle fe fe-chevron-down"></i>
                </a>
                <ul class="slide-menu">
                    <li class="{{ request()->routeIs('list.doctors') ? 'active' : '' }}">
                        <a class="slide-item" href="{{ route('list.doctors') }}">
                            <i class="fas fa-users me-2"></i> قائمة الاطباء
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('chat.doctors') ? 'active' : '' }}">
                        <a class="slide-item" href="{{ route('chat.doctors') }}">
                            <i class="fas fa-comment-dots me-2"></i> المحادثات الاخيرة
                        </a>
                    </li>
                </ul>
            </li>


            <li class="slide {{ request()->routeIs('patient.testimonials.create') ? 'active' : '' }}">
                <a class="side-menu__item" href="{{ route('patient.testimonials.create') }}">
                    <i class="side-menu__icon fas fa-pencil-alt"></i> {{-- أيقونة قلم أو تعليق --}}
                    <span class="side-menu__label">إضافة رأيك/تعليقك</span>
                </a>
            </li>

            {{-- زر تسجيل الخروج --}}
            <li class="slide">
                <a class="side-menu__item" href="javascript:void(0);"
                    onclick="event.preventDefault(); document.getElementById('logout-form-patient').submit();">
                    <i class="side-menu__icon typcn typcn-export-outline"></i>
                    <span class="side-menu__label">تسجيل الخروج</span>
                </a>
                <form id="logout-form-patient" action="{{ route('logout.patient') }}" method="POST"
                    style="display: none;">
                    @csrf
                </form>
            </li>


        </ul>
    </div>
</aside>


@section('js')
    <!--Internal  Chart.bundle js -->
    <script src="{{ URL::asset('Dashboard/plugins/chart.js/Chart.bundle.min.js') }}"></script>
    <!-- Moment js -->
    <script src="{{ URL::asset('Dashboard/plugins/raphael/raphael.min.js') }}"></script>
    <!--Internal  Flot js-->
    {{-- <script src="{{ URL::asset('Dashboard/plugins/jquery.flot/jquery.flot.js') }}"></script> --}}
    {{-- <script src="{{ URL::asset('Dashboard/plugins/jquery.flot/jquery.flot.pie.js') }}"></script> --}}
    {{-- <script src="{{ URL::asset('Dashboard/plugins/jquery.flot/jquery.flot.resize.js') }}"></script> --}}
    {{-- <script src="{{ URL::asset('Dashboard/plugins/jquery.flot/jquery.flot.categories.js') }}"></script> --}}
    {{-- <script src="{{ URL::asset('Dashboard/js/dashboard.sampledata.js') }}"></script> --}}
    {{-- <script src="{{ URL::asset('Dashboard/js/chart.flot.sampledata.js') }}"></script> --}}
    <!--Internal Apexchart js-->
    <script src="{{ URL::asset('Dashboard/js/apexcharts.js') }}"></script>
    <!-- Internal Map -->
    {{-- <script src="{{ URL::asset('Dashboard/plugins/jqvmap/jquery.vmap.min.js') }}"></script> --}}
    {{-- <script src="{{ URL::asset('Dashboard/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script> --}}
    {{-- <script src="{{ URL::asset('Dashboard/js/modal-popup.js') }}"></script> --}}
    <!--Internal  index js -->
    <script src="{{ URL::asset('Dashboard/js/index.js') }}"></script> {{-- هذا قد يكون ضرورياً لعمل الشريط الجانبي --}}
    {{-- <script src="{{ URL::asset('Dashboard/js/jquery.vmap.sampledata.js') }}"></script> --}}

    <script>
        // يمكنك إضافة أي كود JavaScript خاص بهذه الصفحة هنا إذا احتجت
        // مثلاً، لتحديث بيانات العرض البياني إذا كانت ديناميكية
        $(function() {
            'use strict';
            // أي تهيئة خاصة بـ Charts أو Maps إذا أردت تفعيلها
        });
    </script>
@endsection
