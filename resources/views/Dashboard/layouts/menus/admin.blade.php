{{-- admin menu. Rendered inside the shared sidebar (Dashboard.layouts.main-sidebar). --}}
<ul class="side-menu">
        <li class="side-item side-item-category">{{ trans('main-sidebar_trans.Main') }}</li>
        <li class="slide">
            <a class="side-menu__item" href="{{ route('dashboard.admin') }}">
                <i class="side-menu__icon fas fa-home"></i>
                <span class="side-menu__label">{{ trans('main-sidebar_trans.index') }}</span>
            </a>
        </li>
        <li class="side-item side-item-category">{{ trans('AdminSb.General') }}</li>

        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#">
                <i class="side-menu__icon fas fa-user-cog"></i>
                <span class="side-menu__label">{{ trans('AdminSb.Profile') }}</span>
                <i class="angle fas fa-chevron-down"></i>
            </a>
            <ul class="slide-menu">
                <li>
                    <a class="slide-item" href="{{ route('admin.profile.show') }}">
                        <i class="fas fa-eye me-2"></i> {{ trans('AdminSb.ViewProfile') }}</a>
                </li>
                <li>
                    <a class="slide-item" href="{{ route('admin.profile.edit') }}">
                        <i class="fas fa-edit me-2"></i> {{ trans('AdminSb.EditProfile') }}
                    </a>
                </li>
            </ul>
        </li>

        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#">
                <i class="side-menu__icon fas fa-user-shield"></i>
                <span class="side-menu__label">{{ trans('AdminSb.UsersAndRoles') }}</span>
                <i class="angle fas fa-chevron-down"></i>
            </a>
            <ul class="slide-menu">
                <li>
                    <a class="slide-item" href="{{ route('admin.users.roles.index') }}">
                        <i class="fas fa-list-ul me-2"></i>{{ trans('AdminSb.ViewAll') }}
                    </a>
                </li>
            </ul>
        </li>

        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#">
                <i class="side-menu__icon fas fa-sitemap"></i>
                <span class="side-menu__label">{{ trans('main-sidebar_trans.sections') }}</span>
                <i class="angle fas fa-chevron-down"></i>
            </a>
            <ul class="slide-menu">
                <li>
                    <a class="slide-item" href="{{ route('admin.Sections.index') }}">
                        <i class="fas fa-stream me-2"></i> {{ trans('main-sidebar_trans.view_all') }}
                    </a>
                </li>
            </ul>
        </li>


        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#">
                <i class="side-menu__icon fas fa-user-md"></i>
                <span class="side-menu__label">{{ trans('main-sidebar_trans.doctors') }}</span>
                <i class="angle fas fa-chevron-down"></i>
            </a>
            <ul class="slide-menu">
                <li>
                    <a class="slide-item" href="{{ route('admin.Doctors.index') }}">
                        <i class="fas fa-list-alt me-2"></i> {{ trans('main-sidebar_trans.view_all') }}
                    </a>
                </li>
            </ul>
        </li>
        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#">
                <i class="side-menu__icon fas fa-pills"></i>
                <span class="side-menu__label">{{ trans('AdminSb.Pharmacy') }}</span>
                <i class="angle fas fa-chevron-down"></i>
            </a>
            <ul class="slide-menu">
                <li>
                    <a class="slide-item" href="{{ route('admin.pharmacy_manager.index') }}">
                        <i class="fas fa-users-cog me-2"></i>
                        {{ trans('AdminSb.PharmacyManager') }}
                    </a>
                </li>
                <li>
                    <a class="slide-item" href="{{ route('admin.pharmacy_employee.index') }}">
                        <i class="fas fa-users-cog me-2"></i>
                        {{ trans('AdminSb.PharmacyEmployees') }}
                    </a>
                </li>

            </ul>
        </li>


        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#">
                <i class="side-menu__icon fas fa-x-ray"></i>
                <span class="side-menu__label">{{ trans('AdminSb.Radiology staff') }}</span>
                <i class="angle fas fa-chevron-down"></i>
            </a>
            <ul class="slide-menu">
                <li>
                    <a class="slide-item" href="{{ route('admin.ray_employee.index') }}">
                        <i class="fas fa-users me-2"></i> {{ trans('AdminSb.Staff List') }}
                    </a>
                </li>
            </ul>
        </li>

        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#">
                <i class="side-menu__icon fas fa-microscope"></i>
                <span class="side-menu__label">{{ trans('AdminSb.laboratory staff') }}</span>
                <i class="angle fas fa-chevron-down"></i>
            </a>
            <ul class="slide-menu">
                <li>
                    <a class="slide-item" href="{{ route('admin.laboratorie_employee.index') }}">
                        <i class="fas fa-users me-2"></i> {{ trans('AdminSb.Staff List') }}</a>
                </li>
            </ul>
        </li>

        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#">
                <i class="side-menu__icon fas fa-users"></i>
                <span class="side-menu__label">{{ trans('AdminSb.patients') }}</span>
                <i class="angle fas fa-chevron-down"></i>
            </a>
            <ul class="slide-menu">
                <li>
                    <a class="slide-item" href="{{ route('admin.Patients.index') }}">
                        <i class="fas fa-list-ul me-2 text-primary opacity-75"></i>
                        {{ trans('AdminSb.patients list') }}
                    </a>
                </li>
                <li>
                    <a class="slide-item" href="{{ route('admin.Patients.create') }}">
                        <i class="fas fa-user-plus me-2 text-success opacity-75"></i>
                        {{ trans('AdminSb.Add a new patient') }}
                    </a>
                </li>
                <li>
                    <hr class="text-muted my-1">
                </li>
                <li>
                    <a class="slide-item" href="{{ route('admin.patient_admissions.index') }}">
                        <i class="fas fa-notes-medical me-2 text-info opacity-75"></i>
                        {{ trans('AdminSb.Patient admission and discharge records') }}
                    </a>
                </li>
                <li>
                    <a class="slide-item" href="{{ route('admin.patient_admissions.create') }}">
                        <i class="fas fa-procedures me-2 text-warning opacity-75"></i>
                        {{ trans('AdminSb.New patient login') }}
                    </a>
                </li>
            </ul>
        </li>
        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#">
                <i class="side-menu__icon fas fa-calendar-alt"></i> {{-- أيقونة تقويم/مواعيد --}}
                <span class="side-menu__label">{{ trans('AdminSb.appointments') }}</span>
                <i class="angle fas fa-chevron-down"></i>
            </a>
            <ul class="slide-menu">
                <li>
                    <a class="slide-item" href="{{ route('admin.appointments.index') }}">
                        <i class="fas fa-calendar-day me-2"></i> {{ trans('AdminSb.Unconfirmed appointment list') }}
                    </a>
                </li>
                <li>
                    <a class="slide-item" href="{{ route('admin.appointments.index2') }}">
                        <i class="fas fa-calendar-check me-2"></i> {{ trans('AdminSb.confirmed appointment list') }}
                    </a>
                </li>
                <li>
                    <a class="slide-item" href="{{ route('admin.completed') }}">
                        <i class="fas fa-calendar-times me-2"></i> {{ trans('AdminSb.expired appointment list') }}
                    </a>
                </li>
                <li>
                    <a class="slide-item" href="{{ route('admin.appointments.lapsed') }}">
                        <i class="fas fa-calendar-day me-2"></i> {{ trans('AdminSb.missed appointment list') }}
                    </a>
                </li>
                <li>
                    <a class="slide-item" href="{{ route('admin.cancelled') }}">
                        <i class="fas fa-ban me-2"></i> {{ trans('AdminSb.canceled appointment list') }}
                    </a>
                </li>
            </ul>
        </li>

        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#">
                <i class="side-menu__icon fas fa-heartbeat"></i>
                <span class="side-menu__label">{{ trans('AdminSb.disease management') }}</span>
                <i class="angle fas fa-chevron-down"></i>
            </a>
            <ul class="slide-menu">
                <li>
                    <a class="slide-item" href="{{ route('admin.diseases.index') }}">
                        <i class="fas fa-notes-medical me-2 text-primary opacity-75"></i>
                        {{ trans('AdminSb.list of diseases') }}
                    </a>
                </li>
                <li>
                    <a class="slide-item" href="{{ route('admin.diseases.create') }}">
                        <i class="fas fa-file-medical me-2 text-success opacity-75"></i>
                        {{ trans('AdminSb.Add a new disease') }}
                    </a>
                </li>
            </ul>
        </li>

        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#">
                <i class="side-menu__icon fas fa-door-open"></i> {{-- أيقونة غرفة --}}
                <span class="side-menu__label">{{ trans('AdminSb.rooms_management') }}</span>
                <i class="angle fas fa-chevron-down"></i>
            </a>
            <ul class="slide-menu">
                <li>
                    <a class="slide-item" href="{{ route('admin.rooms.index') }}">
                        <i class="fas fa-th-list me-2"></i> {{ trans('AdminSb.view_all_rooms') }}
                    </a>
                </li>
                <li>
                    <a class="slide-item" href="{{ route('admin.rooms.create') }}">
                        <i class="fas fa-plus-square me-2"></i> {{ trans('AdminSb.add_new_room') }}
                    </a>
                </li>
            </ul>
        </li>

        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#">
                <i class="side-menu__icon fas fa-bed"></i> {{-- أيقونة سرير --}}
                <span class="side-menu__label">{{ trans('AdminSb.beds_management') }}</span>
                <i class="angle fas fa-chevron-down"></i>
            </a>
            <ul class="slide-menu">
                <li>
                    <a class="slide-item" href="{{ route('admin.beds.index') }}">
                        <i class="fas fa-bed me-2"></i> {{ trans('AdminSb.view_all_beds') }}
                    </a>
                </li>
                <li>
                    <a class="slide-item" href="{{ route('admin.beds.create') }}">
                        <i class="fas fa-plus-circle me-2"></i> {{ trans('AdminSb.add_new_bed') }}
                    </a>
                </li>
            </ul>
        </li>

        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#">
                <i class="side-menu__icon fas fa-concierge-bell"></i> {{-- أيقونة خدمات --}}
                <span class="side-menu__label">{{ trans('main-sidebar_trans.Services') }}</span>
                <i class="angle fas fa-chevron-down"></i>
            </a>
            <ul class="slide-menu">
                <li>
                    <a class="slide-item" href="{{ route('admin.Service.index') }}">
                        <i class="fas fa-briefcase-medical me-2"></i> {{ trans('main-sidebar_trans.Single_service') }}
                    </a>
                </li>
                <li>
                    <a class="slide-item" href="{{ route('admin.Add_GroupServices') }}">
                        <i class="fas fa-layer-group me-2"></i> {{ trans('main-sidebar_trans.group_services') }}
                    </a>
                </li>
            </ul>
        </li>

        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#">
                <i class="side-menu__icon fas fa-file-invoice-dollar"></i>
                <span class="side-menu__label">{{ trans('AdminSb.invoices') }}</span>
                <i class="angle fas fa-chevron-down"></i>
            </a>
            <ul class="slide-menu">
                <li>
                    <a class="slide-item" href="{{ route('admin.single_invoices') }}">
                        <i class="fas fa-file-invoice me-2"></i> {{ trans('AdminSb.Single service invoice') }}
                    </a>
                </li>
                <li>
                    <a class="slide-item" href="{{ route('admin.group_invoices') }}">
                        <i class="fas fa-file-alt me-2"></i> {{ trans('AdminSb.Group service invoice') }}
                    </a>
                </li>
            </ul>
        </li>



        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#">
                <i class="side-menu__icon fas fa-calculator"></i>
                <span class="side-menu__label">{{ trans('AdminSb.accounts') }}</span>
                <i class="angle fas fa-chevron-down"></i>
            </a>
            <ul class="slide-menu">
                <li>
                    <a class="slide-item" href="{{ route('admin.Receipt.index') }}">
                        <i class="fas fa-file-import me-2"></i> {{ trans('AdminSb.a receipt') }}
                    </a>
                </li>
                {{-- <li>
                    <a class="slide-item" href="{{ route('admin.Payment.index') }}">
                        <i class="fas fa-file-export me-2"></i> سند صرف
                    </a>
                </li> --}}
            </ul>
        </li>

        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#">
                <i class="side-menu__icon far fa-comments"></i>
                <span class="side-menu__label">{{ trans('AdminSb.reviews of patients') }}</span>
                <i class="angle fas fa-chevron-down"></i>
            </a>
            <ul class="slide-menu">
                <li>
                    <a class="slide-item {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}"
                        href="{{ route('admin.testimonials.index') }}">
                        <i class="fas fa-comment-alt me-2"></i> {{ trans('AdminSb.View All') }}

                        @php
                            $pendingCount = \App\Models\Testimonial::where('status', 'pending')->count();
                        @endphp

                        @if ($pendingCount > 0)
                            <span class="badge bg-warning text-dark ms-2">{{ $pendingCount }}</span>
                        @endif
                    </a>
                </li>
            </ul>
        </li>




    </ul>
