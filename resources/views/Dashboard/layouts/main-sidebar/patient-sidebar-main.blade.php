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
                        if (
                            $patientImage &&
                            $patientImage->filename &&
                            !file_exists(public_path('Dashboard/img/patients/' . $patientImage->filename))
                        ) {
                            $imagePath = asset('Dashboard/img/default_patient_avatar.png');
                        }
                    @endphp
                    <img alt="user-img" class="avatar avatar-xl rounded-circle user-avatar" src="{{ $imagePath }}">
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
                    <span class="side-menu__label">{{ trans('patients.profile') }}</span>
                    <i class="angle fe fe-chevron-down"></i>
                </a>
                <ul class="slide-menu">
                    <li class="{{ request()->routeIs('profile.show') ? 'active' : '' }}">
                        <a class="slide-item" href="{{ route('profile.show') }}">
                            <i class="fas fa-eye me-2"></i> {{ trans('patients.view_profile') }}
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                        <a class="slide-item" href="{{ route('profile.edit') }}">
                            <i class="fas fa-edit me-2"></i> {{ trans('patients.edit_profile') }}
                        </a>
                    </li>
                </ul>
            </li>

            {{-- قسم المواعيد --}}
            <li
                class="slide {{ Request::is('patient/appointments*') || request()->routeIs('appointments.upcoming') || request()->routeIs('appointments.past') || request()->routeIs('patient.appointments.create.form') ? 'is-expanded active' : '' }}">
                <a class="side-menu__item" data-toggle="slide" href="javascript:void(0);">
                    <i class="side-menu__icon fas fa-calendar-alt"></i>
                    <span class="side-menu__label">{{ trans('patients.appointments') }}</span>
                    <i class="angle fe fe-chevron-down"></i>
                </a>
                <ul class="slide-menu">
                    <li class="{{ request()->routeIs('appointments.upcoming') ? 'active' : '' }}">
                        <a class="slide-item" href="{{ route('appointments.upcoming') }}">
                            <i class="fas fa-calendar-day me-2 text-success"></i> {{ trans('patients.upcoming_appointments') }}
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('appointments.past') ? 'active' : '' }}">
                        <a class="slide-item" href="{{ route('appointments.past') }}">
                            <i class="fas fa-history me-2 text-muted"></i> {{ trans('patients.past_appointments') }}
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('patient.appointments.create.form') ? 'active' : '' }}">
                        <a class="slide-item" href="{{ route('patient.appointments.create.form') }}">
                            <i class="fas fa-plus-circle me-2 text-primary"></i> {{ trans('patients.request_new_appointment') }}
                        </a>
                    </li>
                </ul>
            </li>

            {{-- قسم الصيدلية --}}
            <li class="slide {{ request()->is('patient/pharmacy*') ? 'is-expanded active' : '' }}">
                <a class="side-menu__item" data-toggle="slide" href="javascript:void(0);">
                    <i class="side-menu__icon fas fa-pills"></i>
                    <span class="side-menu__label">{{ trans('patients.my_prescriptions') }}</span>
                    <i class="angle fe fe-chevron-down"></i>
                </a>
                <ul class="slide-menu">
                    <li class="{{ request()->routeIs('patient.pharmacy.index') ? 'active' : '' }}">
                        <a class="slide-item" href="{{ route('patient.pharmacy.index') }}">
                            <i class="fas fa-list-alt me-2"></i> {{ trans('patients.view_my_prescriptions') }}
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('patient.pharmacy.refill-requests.pending') ? 'active' : '' }}">
                        <a class="slide-item" href="{{ route('patient.pharmacy.refill-requests.pending') }}">
                            <i class="fas fa-hourglass-half me-2"></i> {{ trans('patients.refill_requests') }}
                        </a>
                    </li>
                </ul>
            </li>

            {{-- قسم عمليات المريض --}}
            <li
                class="slide {{ request()->is('patient/invoices*') || request()->is('patient/laboratories*') || request()->is('patient/rays*') ? 'is-expanded active' : '' }}">
                <a class="side-menu__item" data-toggle="slide" href="javascript:void(0);">
                    <i class="side-menu__icon fas fa-file-medical-alt"></i>
                    <span class="side-menu__label">{{ trans('patients.my_medical_records') }}</span>
                    <i class="angle fe fe-chevron-down"></i>
                </a>
                <ul class="slide-menu">
                    <li class="{{ request()->routeIs('invoices.patient') ? 'active' : '' }}">
                        <a class="slide-item" href="{{ route('invoices.patient') }}">
                            <i class="fas fa-file-invoice me-2"></i> {{ trans('patients.invoices_list') }}
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('laboratories.patient') ? 'active' : '' }}">
                        <a class="slide-item" href="{{ route('laboratories.patient') }}">
                            <i class="fas fa-vial me-2"></i> {{ trans('patients.laboratory') }}
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('rays.patient') ? 'active' : '' }}">
                        <a class="slide-item" href="{{ route('rays.patient') }}">
                            <i class="fas fa-x-ray me-2"></i> {{ trans('patients.radiology') }}
                        </a>
                    </li>
                </ul>
            </li>

            {{-- قسم المحادثات --}}
            <li
                class="slide {{ request()->is('patient/chat*') || request()->routeIs('list.doctors') || request()->routeIs('chat.doctors') ? 'is-expanded active' : '' }}">
                <a class="side-menu__item" data-toggle="slide" href="javascript:void(0);">
                    <i class="side-menu__icon fas fa-comments"></i>
                    <span class="side-menu__label">{{ trans('patients.conversations') }}</span>
                    <i class="angle fe fe-chevron-down"></i>
                </a>
                <ul class="slide-menu">
                    <li class="{{ request()->routeIs('list.doctors') ? 'active' : '' }}">
                        <a class="slide-item" href="{{ route('list.doctors') }}">
                            <i class="fas fa-users me-2"></i> {{ trans('patients.doctors_list') }}
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('chat.doctors') ? 'active' : '' }}">
                        <a class="slide-item" href="{{ route('chat.doctors') }}">
                            <i class="fas fa-comment-dots me-2"></i> {{ trans('patients.recent_conversations') }}
                        </a>
                    </li>
                </ul>
            </li>

            <li class="slide {{ request()->routeIs('patient.testimonials.create') ? 'active' : '' }}">
                <a class="side-menu__item" href="{{ route('patient.testimonials.create') }}">
                    <i class="side-menu__icon fas fa-pencil-alt"></i>
                    <span class="side-menu__label">{{ trans('patients.add_your_testimonial') }}</span>
                </a>
            </li>

            {{-- زر تسجيل الخروج --}}
            <li class="slide">
                <a class="side-menu__item" href="javascript:void(0);"
                    onclick="event.preventDefault(); document.getElementById('logout-form-patient').submit();">
                    <i class="side-menu__icon typcn typcn-export-outline"></i>
                    <span class="side-menu__label">{{ trans('patients.logout') }}</span>
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
