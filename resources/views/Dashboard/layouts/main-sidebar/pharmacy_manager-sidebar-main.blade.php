<!-- main-sidebar -->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar sidebar-scroll">
    <div class="main-sidebar-header active">
        <a class="desktop-logo logo-light active" href="{{ route('dashboard.pharmacy_manager') }}"><img
                src="{{ URL::asset('Dashboard/img/brand/logo.png') }}" class="main-logo" alt="logo"></a>
        <a class="desktop-logo logo-dark active" href="{{ route('dashboard.pharmacy_manager') }}"><img
                src="{{ URL::asset('Dashboard/img/brand/logo-white.png') }}" class="main-logo dark-theme"
                alt="logo"></a>
        <a class="logo-icon mobile-logo icon-light active" href="{{ route('dashboard.pharmacy_manager') }}"><img
                src="{{ URL::asset('Dashboard/img/brand/favicon.png') }}" class="logo-icon" alt="logo"></a>
        <a class="logo-icon mobile-logo icon-dark active" href="{{ route('dashboard.pharmacy_manager') }}"><img
                src="{{ URL::asset('Dashboard/img/brand/favicon-white.png') }}" class="logo-icon dark-theme"
                alt="logo"></a>
    </div>
    <div class="main-sidemenu">
        <div class="app-sidebar__user clearfix">
            <div class="dropdown user-pro-body">
                <div class="text-center"> {{-- text-center to center the avatar if no other content beside it --}}
                    {{-- تأكد من أن المسار صحيح لصور المرضى --}}
                    @php
                        $pharmacy_managerImage = Auth::guard('pharmacy_manager')->user()->image;
                        $imagePath =
                            $pharmacy_managerImage && $pharmacy_managerImage->filename
                                ? asset('Dashboard/img/pharmacy_managers/' . $pharmacy_managerImage->filename)
                                : asset('Dashboard/img/default_pharmacy_manager_avatar.png');
                        // Fallback if image file does not exist, though asset() should handle this if path is wrong
                        if (
                            $pharmacy_managerImage &&
                            $pharmacy_managerImage->filename &&
                            !file_exists(
                                public_path('Dashboard/img/pharmacy_managers/' . $pharmacy_managerImage->filename),
                            )
                        ) {
                            $imagePath = asset('Dashboard/img/default_pharmacy_manager_avatar.png');
                        }
                    @endphp
                    <img alt="user-img" class="avatar avatar-xl rounded-circle user-avatar" src="{{ $imagePath }}">
                    {{-- <span class="avatar-status profile-status bg-green"></span> --}} {{-- Status can be removed if not needed --}}
                </div>
                <div class="user-info text-center mt-2"> {{-- text-center and mt-2 for better spacing --}}
                    <h4 class="font-weight-semibold mb-0">{{ Auth::guard('pharmacy_manager')->user()->name }}</h4>
                    <span class="mb-0 text-muted small">{{ Auth::guard('pharmacy_manager')->user()->email }}</span>
                </div>
            </div>
        </div>
        <ul class="side-menu">
            <li class="side-item side-item-category">{{ trans('main-sidebar_trans.Main') }}</li>

            <li class="slide {{ request()->routeIs('dashboard.pharmacy_manager') ? 'active' : '' }}">
                <a class="side-menu__item" href="{{ route('dashboard.pharmacy_manager') }}">
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
                        <a class="slide-item" href="{{ route('pharmacy_manager.profile.show') }}">
                            <i class="fas fa-eye me-2"></i> عرض الملف الشخصي
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                        <a class="slide-item" href="{{ route('pharmacy_manager.profile.edit') }}">
                            <i class="fas fa-edit me-2"></i> تعديل الملف الشخصي
                        </a>
                    </li>
                </ul>
            </li>


            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="#">
                    <i class="side-menu__icon fas fa-pills"></i> {{-- أيقونة صيدلية/أدوية --}}
                    <span class="side-menu__label">الصيدلية</span>
                    <i class="angle fe fe-chevron-down"></i>
                </a>
                <ul class="slide-menu">
                    <li>
                        <a class="slide-item" href="{{ route('pharmacy_manager.medications.index') }}">
                            {{-- {{ route('admin.pharmacy.medicines.index') }} --}}
                            <i class="fas fa-tablets me-2"></i> إدارة الأدوية
                        </a>
                    </li>

                    {{-- <li>
                        <a class="slide-item" href="{{ route('pharmacy_manager.medications.stocks.index') }}">
                            <i class="fas fa-boxes-stacked me-2"></i> نظرة عامة على المخزون
                        </a>
                    </li> --}}
                </ul>
            </li>



            {{-- زر تسجيل الخروج --}}
            <li class="slide">
                <a class="side-menu__item" href="javascript:void(0);"
                    onclick="event.preventDefault(); document.getElementById('logout-form-pharmacy_manager').submit();">
                    <i class="side-menu__icon typcn typcn-export-outline"></i>
                    <span class="side-menu__label">تسجيل الخروج</span>
                </a>
                <form id="logout-form-pharmacy_manager" action="{{ route('logout.pharmacy_manager') }}" method="POST"
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
