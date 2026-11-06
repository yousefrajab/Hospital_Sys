{{-- ray_employee menu. Rendered inside the shared sidebar (Dashboard.layouts.main-sidebar). --}}
<ul class="side-menu">
            <li class="side-item side-item-category">{{ trans('main-sidebar_trans.Main') }}</li>


            <li class="slide">
                <a class="side-menu__item" href="{{ route('dashboard.ray_employee') }}"><i class="side-menu__icon fas fa-home"></i><span class="side-menu__label">{{ trans('main-sidebar_trans.index') }}</a>
            </li>

            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="{{ route('ray_employee.profile.show') }}">
                    <i class="side-menu__icon fa fa-user-cog"></i>
                    <span class="side-menu__label">{{ trans('ray.profile') }}</span>
                    <i class="angle fas fa-chevron-down"></i>
                </a>

                <ul class="slide-menu">
                    <li>
                        <a class="slide-item" href="{{ route('ray_employee.profile.show') }}">
                            <i class="fa fa-eye mr-2"></i> {{ trans('ray.view_profile') }}
                        </a>
                    </li>
                    <li>
                        <a class="slide-item" href="{{ route('ray_employee.profile.edit') }}">
                            <i class="fa fa-edit mr-2"></i> {{ trans('ray.edit_profile_menu') }}
                        </a>
                    </li>
                </ul>
            </li>

            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . ($page = '#')) }}"><i class="side-menu__icon fas fa-x-ray"></i><span class="side-menu__label">{{ trans('ray.ray_scans') }} </span><i
                        class="angle fas fa-chevron-down"></i></a>
                <ul class="slide-menu">
                    <li><a class="slide-item" href="{{ route('ray_employee.invoices_ray_employee.index') }}">{{ trans('ray.scans_list') }}</a></li>
                    <li><a class="slide-item" href="{{ route('ray_employee.completed_invoices') }}">{{ trans('ray.completed_scans_list') }}</a></li>
                </ul>
            </li>
        </ul>
