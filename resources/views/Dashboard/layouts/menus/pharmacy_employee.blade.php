{{-- pharmacy_employee menu. Rendered inside the shared sidebar (Dashboard.layouts.main-sidebar). --}}
<ul class="side-menu">
            <li class="side-item side-item-category">{{ trans('main-sidebar_trans.Main') }}</li>

            <li class="slide {{ request()->routeIs('dashboard.pharmacy_employee') ? 'active' : '' }}">
                <a class="side-menu__item" href="{{ route('dashboard.pharmacy_employee') }}">
                    <i class="side-menu__icon fas fa-home"></i>
                    <span class="side-menu__label">{{ trans('main-sidebar_trans.index') }}</span>
                </a>
            </li>

            <li class="side-item side-item-category">{{ trans('phrE.prescriptions_and_stock_management') }}</li>
            <li class="slide {{ request()->routeIs(['pharmacy_employee.prescriptions.*', 'pharmacy_employee.medications.search']) ? 'is-expanded active' : '' }}">
                <a class="side-menu__item" data-toggle="slide" href="javascript:void(0);">
                    <i class="side-menu__icon fas fa-pills"></i>
                    <span class="side-menu__label">{{ trans('phrE.prescriptions_and_medications') }}</span>
                    <i class="angle fas fa-chevron-down"></i>
                </a>
                <ul class="slide-menu">
                    <li class="{{ request()->routeIs('pharmacy_employee.prescriptions.index') ? 'active' : '' }}">
                        <a class="slide-item" href="{{ route('pharmacy_employee.prescriptions.index') }}">
                            <i class="fas fa-inbox fa-xs me-2"></i> {{ trans('phrE.incoming_prescriptions') }}
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('pharmacy_employee.prescriptions.dispensed') ? 'active' : '' }}">
                        <a class="slide-item" href="{{ route('pharmacy_employee.prescriptions.dispensed') }}">
                            <i class="fas fa-check-circle fa-xs me-2"></i> {{ trans('phrE.dispensed_prescriptions') }}
                        </a>
                    </li>
                     <li class="{{ request()->routeIs('pharmacy_employee.prescriptions.on_hold') ? 'active' : '' }}">
                        <a class="slide-item" href="{{ route('pharmacy_employee.prescriptions.on_hold') }}">
                            <i class="fas fa-pause-circle fa-xs me-2"></i> {{ trans('phrE.on_hold_prescriptions') }}
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('pharmacy_employee.medications.search') ? 'active' : '' }}">
                        <a class="slide-item" href="{{ route('pharmacy_employee.medications.search') }}">
                            <i class="fas fa-search-dollar fa-xs me-2"></i> {{ trans('phrE.search_for_medication_stock') }}
                        </a>
                    </li>
                </ul>
            </li>

            <li class="side-item side-item-category">{{ trans('phrE.settings') }}</li>
            <li
                class="slide {{ request()->routeIs(['pharmacy_employee.profile.show', 'pharmacy_employee.profile.edit']) ? 'is-expanded active' : '' }}">
                <a class="side-menu__item" data-toggle="slide" href="javascript:void(0);">
                    <i class="side-menu__icon fas fa-user-cog"></i>
                    <span class="side-menu__label">{{ trans('phrE.profile') }}</span>
                    <i class="angle fas fa-chevron-down"></i>
                </a>
                <ul class="slide-menu">
                    <li class="{{ request()->routeIs('pharmacy_employee.profile.show') ? 'active' : '' }}">
                        <a class="slide-item" href="{{ route('pharmacy_employee.profile.show') }}">
                            <i class="fas fa-id-card fa-xs me-2"></i> {{ trans('phrE.view_profile') }}
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('pharmacy_employee.profile.edit') ? 'active' : '' }}">
                        <a class="slide-item" href="{{ route('pharmacy_employee.profile.edit') }}">
                            <i class="fas fa-user-edit fa-xs me-2"></i> {{ trans('phrE.edit_profile') }}
                        </a>
                    </li>
                </ul>
            </li>

            <li class="slide">
                <a class="side-menu__item" href="javascript:void(0);"
                    onclick="event.preventDefault(); document.getElementById('logout-form-pharmacy_employee').submit();">
                    <i class="side-menu__icon fas fa-sign-out-alt text-danger"></i>
                    <span class="side-menu__label">{{ trans('phrE.logout') }}</span>
                </a>
                <form id="logout-form-pharmacy_employee" action="{{ route('logout.pharmacy_employee') }}" method="POST"
                    style="display: none;">
                    @csrf
                </form>
            </li>
        </ul>
