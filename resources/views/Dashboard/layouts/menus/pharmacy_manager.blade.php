{{-- pharmacy_manager menu. Rendered inside the shared sidebar (Dashboard.layouts.main-sidebar). --}}
<ul class="side-menu">
            <li class="side-item side-item-category">{{ trans('main-sidebar_trans.Main') }}</li>

            <li class="slide {{ request()->routeIs('dashboard.pharmacy_manager') ? 'active' : '' }}">
                <a class="side-menu__item" href="{{ route('dashboard.pharmacy_manager') }}">
                    <i class="side-menu__icon fas fa-home"></i>
                    <span class="side-menu__label">{{ trans('main-sidebar_trans.index') }}</span>
                </a>
            </li>

            {{-- قسم الملف الشخصي --}}
            <li
                class="slide {{ request()->routeIs('pharmacy_manager.profile.show') || request()->routeIs('pharmacy_manager.profile.edit') ? 'is-expanded active' : '' }}">
                <a class="side-menu__item" data-toggle="slide" href="javascript:void(0);">
                    <i class="side-menu__icon fas fa-user-circle"></i>
                    <span class="side-menu__label">{{ trans('phrM.profile') }}</span>
                    <i class="angle fas fa-chevron-down"></i>
                </a>
                <ul class="slide-menu">
                    <li class="{{ request()->routeIs('pharmacy_manager.profile.show') ? 'active' : '' }}">
                        <a class="slide-item" href="{{ route('pharmacy_manager.profile.show') }}">
                            <i class="fas fa-eye me-2"></i> {{ trans('phrM.view_profile') }}
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('pharmacy_manager.profile.edit') ? 'active' : '' }}">
                        <a class="slide-item" href="{{ route('pharmacy_manager.profile.edit') }}">
                            <i class="fas fa-edit me-2"></i> {{ trans('phrM.edit_profile_menu') }}
                        </a>
                    </li>
                </ul>
            </li>


            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="#">
                    <i class="side-menu__icon fas fa-pills"></i>
                    <span class="side-menu__label">{{ trans('phrM.pharmacy') }}</span>
                    <i class="angle fas fa-chevron-down"></i>
                </a>
                <ul class="slide-menu">
                    <li>
                        <a class="slide-item" href="{{ route('pharmacy_manager.medications.index') }}">
                            <i class="fas fa-tablets me-2"></i> {{ trans('phrM.manage_medications') }}
                        </a>
                    </li>
                </ul>
            </li>

            {{-- زر تسجيل الخروج --}}
            <li class="slide">
                <a class="side-menu__item" href="javascript:void(0);"
                    onclick="event.preventDefault(); document.getElementById('logout-form-pharmacy_manager').submit();">
                    <i class="side-menu__icon fas fa-sign-out-alt"></i>
                    <span class="side-menu__label">{{ trans('phrM.logout') }}</span>
                </a>
                <form id="logout-form-pharmacy_manager" action="{{ route('logout.pharmacy_manager') }}" method="POST"
                    style="display: none;">
                    @csrf
                </form>
            </li>

        </ul>
