{{-- doctor menu. Rendered inside the shared sidebar (Dashboard.layouts.main-sidebar). --}}
<ul class="side-menu">
            {{-- 1. الرئيسية --}}
            <li class="slide {{ request()->routeIs('dashboard.doctor') ? 'active' : '' }}">
                <a class="side-menu__item" href="{{ route('dashboard.doctor') }}">
                    <i class="side-menu__icon fas fa-tachometer-alt"></i>
                    <span class="side-menu__label">{{ trans('main-sidebar_trans.index') }}</span>
                </a>
            </li>

            {{-- 2. إدارة الوصفات --}}
            <li class="side-item side-item-category">{{ trans('doctors.prescriptions_management_category') }}</li>
            <li
                class="slide {{ request()->routeIs(['prescriptions.*', 'doctor.patients.search_for_prescription', 'doctor.prescriptions.*']) ? 'open active' : '' }}">
                <a class="side-menu__item" data-toggle="slide" href="#">
                    <i class="side-menu__icon fas fa-file-medical-alt"></i>
                    <span class="side-menu__label">{{ trans('doctors.prescriptions') }}</span>
                    <i class="angle fas fa-chevron-down"></i>
                </a>
                <ul class="slide-menu">
                    <li class="{{ request()->routeIs('doctor.patients.search_for_prescription') ? 'current' : '' }}">
                        <a class="slide-item" href="{{ route('doctor.patients.search_for_prescription') }}">
                            {{ trans('doctors.create_new_prescription') }}
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('prescriptions.index') ? 'current' : '' }}">
                        <a class="slide-item" href="{{ route('prescriptions.index') }}">
                            {{ trans('doctors.my_prescriptions_list') }}
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('doctor.prescriptions.approvalRequests') ? 'current' : '' }}">
                        <a class="slide-item" href="{{ route('doctor.prescriptions.approvalRequests') }}">
                            {{ trans('doctors.renewal_requests_for_approval') }}
                             {{-- مثال على عداد التنبيهات --}}
                            <span class="badge bg-warning-transparent text-warning float-end">3</span>
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('doctor.prescriptions.adherenceDashboard') ? 'current' : '' }}">
                        <a class="slide-item" href="{{ route('doctor.prescriptions.adherenceDashboard') }}">
                            {{ trans('doctors.patient_adherence_tracking') }}
                        </a>
                    </li>
                </ul>
            </li>

            {{-- 3. إدارة الخدمات --}}
            <li class="side-item side-item-category">{{ trans('doctors.services_management_category') }}</li>
            <li class="slide {{ request()->routeIs('doctor.services_management.*') ? 'open active' : '' }}">
                <a class="side-menu__item" data-toggle="slide" href="#">
                    <i class="side-menu__icon fas fa-concierge-bell"></i>
                    <span class="side-menu__label">{{ trans('doctors.my_services') }}</span>
                    <i class="angle fas fa-chevron-down"></i></a>
                <ul class="slide-menu">
                    <li class="{{ request()->routeIs('doctor.services_management.index') ? 'current' : '' }}">
                        <a class="slide-item" href="{{ route('doctor.services_management.index') }}">
                            {{ trans('doctors.view_and_manage_services') }}
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('doctor.services_management.create') ? 'current' : '' }}">
                        <a class="slide-item" href="{{ route('doctor.services_management.create') }}">
                            {{ trans('doctors.create_new_service') }}
                        </a>
                    </li>
                </ul>
            </li>

            {{-- 4. الإدارة الطبية --}}
            <li class="side-item side-item-category">{{ trans('doctors.medical_management_category') }}</li>
            <li class="slide {{ request()->routeIs(['doctor.invoices.*', 'doctor.completedInvoices', 'doctor.reviewInvoices']) ? 'open active' : '' }}">
                <a class="side-menu__item" data-toggle="slide" href="#">
                    <i class="side-menu__icon fas fa-notes-medical"></i>
                    <span class="side-menu__label">{{ trans('doctors.examinations') }}</span>
                    <i class="angle fas fa-chevron-down"></i></a>
                <ul class="slide-menu">
                    <li class="{{ request()->routeIs('doctor.invoices.index') ? 'current' : '' }}"><a class="slide-item"
                            href="{{ route('doctor.invoices.index') }}">{{ trans('doctors.examinations_list') }}</a></li>
                    <li class="{{ request()->routeIs('doctor.completedInvoices') ? 'current' : '' }}"><a class="slide-item"
                            href="{{ route('doctor.completedInvoices') }}">{{ trans('doctors.completed_examinations') }}</a></li>
                    <li class="{{ request()->routeIs('doctor.reviewInvoices') ? 'current' : '' }}"><a class="slide-item"
                            href="{{ route('doctor.reviewInvoices') }}">{{ trans('doctors.reviews') }}</a></li>
                </ul>
            </li>

            {{-- 5. التواصل والمواعيد --}}
            <li class="side-item side-item-category">{{ trans('doctors.appointments_and_communication_category') }}</li>
             <li class="slide {{ request()->routeIs('doctor.appointments') ? 'active' : '' }}">
                <a class="side-menu__item" href="{{ route('doctor.appointments') }}">
                    <i class="side-menu__icon fas fa-calendar-check"></i>
                    <span class="side-menu__label">{{ trans('doctors.my_appointments') }}</span>
                </a>
            </li>
            <li class="slide {{ request()->routeIs(['doctor.list.patients', 'doctor.chat.patients']) ? 'open active' : '' }}">
                <a class="side-menu__item" data-toggle="slide" href="#">
                    <i class="side-menu__icon fas fa-headset"></i>
                    <span class="side-menu__label">{{ trans('doctors.patient_communication_category') }}</span>
                    <i class="angle fas fa-chevron-down"></i></a>
                <ul class="slide-menu">
                    <li class="{{ request()->routeIs('doctor.list.patients') ? 'current' : '' }}"><a class="slide-item"
                            href="{{ route('doctor.list.patients') }}">{{ trans('doctors.patients_list') }}</a></li>
                    <li class="{{ request()->routeIs('doctor.chat.patients') ? 'current' : '' }}"><a class="slide-item"
                            href="{{ route('doctor.chat.patients') }}">{{ trans('doctors.inbox') }}</a></li>
                </ul>
            </li>


            {{-- 6. الإعدادات الشخصية --}}
            <li class="side-item side-item-category">{{ trans('doctors.personal_settings_category') }}</li>
            <li class="slide {{ request()->routeIs(['doctor.schedule.*','doctor.profile.*']) ? 'open active' : '' }}">
                 <a class="side-menu__item" data-toggle="slide" href="#">
                    <i class="side-menu__icon fas fa-user-cog"></i>
                    <span class="side-menu__label">{{ trans('doctors.my_account') }}</span>
                    <i class="angle fas fa-chevron-down"></i></a>
                 <ul class="slide-menu">
                    <li class="{{ request()->routeIs('doctor.profile.show') ? 'current' : '' }}">
                        <a class="slide-item" href="{{ route('doctor.profile.show') }}">
                            {{ trans('doctors.my_profile') }}
                        </a>
                    </li>
                     <li class="{{ request()->routeIs('doctor.schedule.show') ? 'current' : '' }}">
                        <a class="slide-item" href="{{ route('doctor.schedule.show') }}">
                           {{ trans('doctors.manage_my_schedule') }}
                        </a>
                    </li>
                 </ul>
            </li>

            {{-- 7. تسجيل الخروج --}}
            <li class="slide">
                @php $logoutRoute = route('logout.doctor'); @endphp
                <form method="POST" action="{{ $logoutRoute }}" id="logout-form-sidebar-pro">
                    @csrf
                    <a class="side-menu__item logout-link" href="{{ $logoutRoute }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form-sidebar-pro').submit();">
                        <i class="side-menu__icon fas fa-sign-out-alt"></i>
                        <span class="side-menu__label">{{ trans('doctors.Log out') }}</span>
                    </a>
                </form>
            </li>

        </ul>
