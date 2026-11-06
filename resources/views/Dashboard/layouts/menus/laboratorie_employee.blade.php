{{-- laboratorie_employee menu. Rendered inside the shared sidebar (Dashboard.layouts.main-sidebar). --}}
<ul class="side-menu">
            <li class="side-item side-item-category">{{ trans('main-sidebar_trans.Main') }}</li>

            <li class="slide">
                <a class="side-menu__item" href="{{ route('dashboard.laboratorie_employee') }}"><i class="side-menu__icon fas fa-home"></i><span class="side-menu__label">{{ trans('main-sidebar_trans.index') }}</a>
            </li>


            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="#">
                    <i class="side-menu__icon fa fa-user-cog"></i>
                    <span class="side-menu__label">{{ trans('lab.profile') }}</span>
                    <i class="angle fas fa-chevron-down"></i>
                </a>

                <ul class="slide-menu">
                    <li>
                        <a class="slide-item" href="{{ route('laboratorie_employee.profile.show') }}">
                            <i class="fa fa-eye mr-2"></i> {{ trans('lab.view_profile') }}
                        </a>
                    </li>
                    <li>
                        <a class="slide-item" href="{{ route('laboratorie_employee.profile.edit') }}">
                            <i class="fa fa-edit mr-2"></i> {{ trans('lab.edit_profile_menu') }}
                        </a>
                    </li>
                </ul>
            </li>


            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . ($page = '#')) }}"><i class="side-menu__icon fas fa-vials"></i><span class="side-menu__label">{{ trans('lab.lab_tests') }} </span><i
                        class="angle fas fa-chevron-down"></i></a>
                <ul class="slide-menu">
                    <li><a class="slide-item"
                            href="{{ route('laboratorie_employee.invoices_laboratorie_employee.index') }}">{{ trans('lab.tests_list') }}</a></li>
                    <li><a class="slide-item" href="{{ route('laboratorie_employee.completed_invoicess') }}">{{ trans('lab.completed_tests_list') }}</a>
                    </li>
                </ul>
            </li>
        </ul>
