{{-- patient menu. Rendered inside the shared sidebar (Dashboard.layouts.main-sidebar). --}}
<ul class="side-menu">
            <li class="side-item side-item-category">{{ trans('main-sidebar_trans.Main') }}</li>

            <li class="slide {{ request()->routeIs('dashboard.patient') ? 'active' : '' }}">
                <a class="side-menu__item" href="{{ route('dashboard.patient') }}">
                    <i class="side-menu__icon fas fa-home"></i>
                    <span class="side-menu__label">{{ trans('main-sidebar_trans.index') }}</span>
                </a>
            </li>

            {{-- قسم الملف الشخصي --}}
            <li
                class="slide {{ request()->routeIs('profile.show') || request()->routeIs('profile.edit') ? 'is-expanded active' : '' }}">
                <a class="side-menu__item" data-toggle="slide" href="javascript:void(0);">
                    <i class="side-menu__icon fas fa-user-circle"></i>
                    <span class="side-menu__label">{{ trans('patients.profile') }}</span>
                    <i class="angle fas fa-chevron-down"></i>
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
                    <i class="angle fas fa-chevron-down"></i>
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
                    <i class="angle fas fa-chevron-down"></i>
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
                    <i class="angle fas fa-chevron-down"></i>
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
                    <i class="angle fas fa-chevron-down"></i>
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
                    <i class="side-menu__icon fas fa-sign-out-alt"></i>
                    <span class="side-menu__label">{{ trans('patients.logout') }}</span>
                </a>
                <form id="logout-form-patient" action="{{ route('logout.patient') }}" method="POST"
                    style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
