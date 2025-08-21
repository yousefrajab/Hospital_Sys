<div class="main-sidemenu">
    <div class="app-sidebar__user clearfix">
        <div class="dropdown user-pro-body">
            <div class="">
                <img alt="user-img" class="avatar avatar-lg rounded-circle user-avatar"
                    src="<?php echo e(Auth::guard('admin')->user()->image ? asset('Dashboard/img/admin_photos/' . Auth::guard('admin')->user()->image->filename) : asset('Dashboard/img/doctor_default.png')); ?>">
                <span class="avatar-status profile-status bg-green"></span>
            </div>
            <div class="user-info">
                <h4 class="font-weight-semibold mt-3 mb-0"><?php echo e(Auth::user()->name); ?></h4>
                <span class="mb-0 text-muted"><?php echo e(Auth::user()->email); ?></span>
            </div>
        </div>
    </div>
    <ul class="side-menu">
        <li class="side-item side-item-category"><?php echo e(trans('main-sidebar_trans.Main')); ?></li>
        <li class="slide">
            <a class="side-menu__item" href="<?php echo e(route('dashboard.admin')); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24">
                    <path d="M0 0h24v24H0V0z" fill="none" />
                    <path d="M5 5h4v6H5zm10 8h4v6h-4zM5 17h4v2H5zM15 5h4v2h-4z" opacity=".3" />
                    <path
                        d="M3 13h8V3H3v10zm2-8h4v6H5V5zm8 16h8V11h-8v10zm2-8h4v6h-4v-6zM13 3v6h8V3h-8zm6 4h-4V5h4v2zM3 21h8v-6H3v6zm2-4h4v2H5v-2z" />
                </svg>
                <span class="side-menu__label"><?php echo e(trans('main-sidebar_trans.index')); ?></span>
            </a>
        </li>
        <li class="side-item side-item-category"><?php echo e(trans('AdminSb.General')); ?></li>

        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#">
                <i class="side-menu__icon fas fa-user-cog"></i>
                <span class="side-menu__label"><?php echo e(trans('AdminSb.Profile')); ?></span>
                <i class="angle fa fa-chevron-down"></i>
            </a>
            <ul class="slide-menu">
                <li>
                    <a class="slide-item" href="<?php echo e(route('admin.profile.show')); ?>">
                        <i class="fas fa-eye me-2"></i> <?php echo e(trans('AdminSb.ViewProfile')); ?></a>
                </li>
                <li>
                    <a class="slide-item" href="<?php echo e(route('admin.profile.edit')); ?>">
                        <i class="fas fa-edit me-2"></i> <?php echo e(trans('AdminSb.EditProfile')); ?>

                    </a>
                </li>
            </ul>
        </li>

        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#">
                <svg xmlns="http://www.w3.org/2000/svg" class="side-menu__icon" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                </svg>
                <span class="side-menu__label"><?php echo e(trans('AdminSb.UsersAndRoles')); ?></span>
                <i class="angle fe fe-chevron-down"></i>
            </a>
            <ul class="slide-menu">
                <li>
                    <a class="slide-item" href="<?php echo e(route('admin.users.roles.index')); ?>">
                        <i class="fas fa-list-ul me-2"></i><?php echo e(trans('AdminSb.ViewAll')); ?>

                    </a>
                </li>
            </ul>
        </li>

        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#">
                <i class="side-menu__icon fas fa-sitemap"></i>
                <span class="side-menu__label"><?php echo e(trans('main-sidebar_trans.sections')); ?></span>
                <i class="angle fe fe-chevron-down"></i>
            </a>
            <ul class="slide-menu">
                <li>
                    <a class="slide-item" href="<?php echo e(route('admin.Sections.index')); ?>">
                        <i class="fas fa-stream me-2"></i> <?php echo e(trans('main-sidebar_trans.view_all')); ?>

                    </a>
                </li>
            </ul>
        </li>


        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#">
                <i class="side-menu__icon fas fa-user-md"></i>
                <span class="side-menu__label"><?php echo e(trans('main-sidebar_trans.doctors')); ?></span>
                <i class="angle fe fe-chevron-down"></i>
            </a>
            <ul class="slide-menu">
                <li>
                    <a class="slide-item" href="<?php echo e(route('admin.Doctors.index')); ?>">
                        <i class="fas fa-list-alt me-2"></i> <?php echo e(trans('main-sidebar_trans.view_all')); ?>

                    </a>
                </li>
            </ul>
        </li>
        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#">
                <i class="side-menu__icon fas fa-pills"></i>
                <span class="side-menu__label"><?php echo e(trans('AdminSb.Pharmacy')); ?></span>
                <i class="angle fe fe-chevron-down"></i>
            </a>
            <ul class="slide-menu">
                <li>
                    <a class="slide-item" href="<?php echo e(route('admin.pharmacy_manager.index')); ?>">
                        <i class="fas fa-users-cog me-2"></i>
                        <?php echo e(trans('AdminSb.PharmacyManager')); ?>

                    </a>
                </li>
                <li>
                    <a class="slide-item" href="<?php echo e(route('admin.pharmacy_employee.index')); ?>">
                        <i class="fas fa-users-cog me-2"></i>
                        <?php echo e(trans('AdminSb.PharmacyEmployees')); ?>

                    </a>
                </li>

            </ul>
        </li>


        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#">
                <i class="side-menu__icon fas fa-x-ray"></i>
                <span class="side-menu__label"><?php echo e(trans('AdminSb.Radiology staff')); ?></span>
                <i class="angle fe fe-chevron-down"></i>
            </a>
            <ul class="slide-menu">
                <li>
                    <a class="slide-item" href="<?php echo e(route('admin.ray_employee.index')); ?>">
                        <i class="fas fa-users me-2"></i> <?php echo e(trans('AdminSb.Staff List')); ?>

                    </a>
                </li>
            </ul>
        </li>

        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#">
                <i class="side-menu__icon fas fa-microscope"></i>
                <span class="side-menu__label"><?php echo e(trans('AdminSb.laboratory staff')); ?></span>
                <i class="angle fe fe-chevron-down"></i>
            </a>
            <ul class="slide-menu">
                <li>
                    <a class="slide-item" href="<?php echo e(route('admin.laboratorie_employee.index')); ?>">
                        <i class="fas fa-users me-2"></i> <?php echo e(trans('AdminSb.Staff List')); ?></a>
                </li>
            </ul>
        </li>

        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#">
                <i class="side-menu__icon fas fa-users"></i>
                <span class="side-menu__label"><?php echo e(trans('AdminSb.patients')); ?></span>
                <i class="angle fe fe-chevron-down"></i>
            </a>
            <ul class="slide-menu">
                <li>
                    <a class="slide-item" href="<?php echo e(route('admin.Patients.index')); ?>">
                        <i class="fas fa-list-ul me-2 text-primary opacity-75"></i>
                        <?php echo e(trans('AdminSb.patients list')); ?>

                    </a>
                </li>
                <li>
                    <a class="slide-item" href="<?php echo e(route('admin.Patients.create')); ?>">
                        <i class="fas fa-user-plus me-2 text-success opacity-75"></i>
                        <?php echo e(trans('AdminSb.Add a new patient')); ?>

                    </a>
                </li>
                <li>
                    <hr class="text-muted my-1">
                </li>
                <li>
                    <a class="slide-item" href="<?php echo e(route('admin.patient_admissions.index')); ?>">
                        <i class="fas fa-notes-medical me-2 text-info opacity-75"></i>
                        <?php echo e(trans('AdminSb.Patient admission and discharge records')); ?>

                    </a>
                </li>
                <li>
                    <a class="slide-item" href="<?php echo e(route('admin.patient_admissions.create')); ?>">
                        <i class="fas fa-procedures me-2 text-warning opacity-75"></i>
                        <?php echo e(trans('AdminSb.New patient login')); ?>

                    </a>
                </li>
            </ul>
        </li>
        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#">
                <i class="side-menu__icon fas fa-calendar-alt"></i> 
                <span class="side-menu__label"><?php echo e(trans('AdminSb.appointments')); ?></span>
                <i class="angle fe fe-chevron-down"></i>
            </a>
            <ul class="slide-menu">
                <li>
                    <a class="slide-item" href="<?php echo e(route('admin.appointments.index')); ?>">
                        <i class="fas fa-calendar-day me-2"></i> <?php echo e(trans('AdminSb.Unconfirmed appointment list')); ?>

                    </a>
                </li>
                <li>
                    <a class="slide-item" href="<?php echo e(route('admin.appointments.index2')); ?>">
                        <i class="fas fa-calendar-check me-2"></i> <?php echo e(trans('AdminSb.confirmed appointment list')); ?>

                    </a>
                </li>
                <li>
                    <a class="slide-item" href="<?php echo e(route('admin.completed')); ?>">
                        <i class="fas fa-calendar-times me-2"></i> <?php echo e(trans('AdminSb.expired appointment list')); ?>

                    </a>
                </li>
                <li>
                    <a class="slide-item" href="<?php echo e(route('admin.appointments.lapsed')); ?>">
                        <i class="fas fa-calendar-day me-2"></i> <?php echo e(trans('AdminSb.missed appointment list')); ?>

                    </a>
                </li>
                <li>
                    <a class="slide-item" href="<?php echo e(route('admin.cancelled')); ?>">
                        <i class="fas fa-ban me-2"></i> <?php echo e(trans('AdminSb.canceled appointment list')); ?>

                    </a>
                </li>
            </ul>
        </li>

        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#">
                <i class="side-menu__icon fas fa-heartbeat"></i>
                <span class="side-menu__label"><?php echo e(trans('AdminSb.disease management')); ?></span>
                <i class="angle fe fe-chevron-down"></i>
            </a>
            <ul class="slide-menu">
                <li>
                    <a class="slide-item" href="<?php echo e(route('admin.diseases.index')); ?>">
                        <i class="fas fa-notes-medical me-2 text-primary opacity-75"></i>
                        <?php echo e(trans('AdminSb.list of diseases')); ?>

                    </a>
                </li>
                <li>
                    <a class="slide-item" href="<?php echo e(route('admin.diseases.create')); ?>">
                        <i class="fas fa-file-medical me-2 text-success opacity-75"></i>
                        <?php echo e(trans('AdminSb.Add a new disease')); ?>

                    </a>
                </li>
            </ul>
        </li>

        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#">
                <i class="side-menu__icon fas fa-door-open"></i> 
                <span class="side-menu__label"><?php echo e(trans('AdminSb.rooms_management')); ?></span>
                <i class="angle fe fe-chevron-down"></i>
            </a>
            <ul class="slide-menu">
                <li>
                    <a class="slide-item" href="<?php echo e(route('admin.rooms.index')); ?>">
                        <i class="fas fa-th-list me-2"></i> <?php echo e(trans('AdminSb.view_all_rooms')); ?>

                    </a>
                </li>
                <li>
                    <a class="slide-item" href="<?php echo e(route('admin.rooms.create')); ?>">
                        <i class="fas fa-plus-square me-2"></i> <?php echo e(trans('AdminSb.add_new_room')); ?>

                    </a>
                </li>
            </ul>
        </li>

        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#">
                <i class="side-menu__icon fas fa-bed"></i> 
                <span class="side-menu__label"><?php echo e(trans('AdminSb.beds_management')); ?></span>
                <i class="angle fe fe-chevron-down"></i>
            </a>
            <ul class="slide-menu">
                <li>
                    <a class="slide-item" href="<?php echo e(route('admin.beds.index')); ?>">
                        <i class="fas fa-bed me-2"></i> <?php echo e(trans('AdminSb.view_all_beds')); ?>

                    </a>
                </li>
                <li>
                    <a class="slide-item" href="<?php echo e(route('admin.beds.create')); ?>">
                        <i class="fas fa-plus-circle me-2"></i> <?php echo e(trans('AdminSb.add_new_bed')); ?>

                    </a>
                </li>
            </ul>
        </li>

        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#">
                <i class="side-menu__icon fas fa-concierge-bell"></i> 
                <span class="side-menu__label"><?php echo e(trans('main-sidebar_trans.Services')); ?></span>
                <i class="angle fe fe-chevron-down"></i>
            </a>
            <ul class="slide-menu">
                <li>
                    <a class="slide-item" href="<?php echo e(route('admin.Service.index')); ?>">
                        <i class="fas fa-briefcase-medical me-2"></i> <?php echo e(trans('main-sidebar_trans.Single_service')); ?>

                    </a>
                </li>
                <li>
                    <a class="slide-item" href="<?php echo e(route('admin.Add_GroupServices')); ?>">
                        <i class="fas fa-layer-group me-2"></i> <?php echo e(trans('main-sidebar_trans.group_services')); ?>

                    </a>
                </li>
            </ul>
        </li>

        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#">
                <i class="side-menu__icon fas fa-file-invoice-dollar"></i>
                <span class="side-menu__label"><?php echo e(trans('AdminSb.invoices')); ?></span>
                <i class="angle fe fe-chevron-down"></i>
            </a>
            <ul class="slide-menu">
                <li>
                    <a class="slide-item" href="<?php echo e(route('admin.single_invoices')); ?>">
                        <i class="fas fa-file-invoice me-2"></i> <?php echo e(trans('AdminSb.Single service invoice')); ?>

                    </a>
                </li>
                <li>
                    <a class="slide-item" href="<?php echo e(route('admin.group_invoices')); ?>">
                        <i class="fas fa-file-alt me-2"></i> <?php echo e(trans('AdminSb.Group service invoice')); ?>

                    </a>
                </li>
            </ul>
        </li>



        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#">
                <i class="side-menu__icon fas fa-calculator"></i>
                <span class="side-menu__label"><?php echo e(trans('AdminSb.accounts')); ?></span>
                <i class="angle fe fe-chevron-down"></i>
            </a>
            <ul class="slide-menu">
                <li>
                    <a class="slide-item" href="<?php echo e(route('admin.Receipt.index')); ?>">
                        <i class="fas fa-file-import me-2"></i> <?php echo e(trans('AdminSb.a receipt')); ?>

                    </a>
                </li>
                
            </ul>
        </li>

        <li class="slide">
            <a class="side-menu__item" data-toggle="slide" href="#">
                <i class="side-menu__icon far fa-comments"></i>
                <span class="side-menu__label"><?php echo e(trans('AdminSb.reviews of patients')); ?></span>
                <i class="angle fe fe-chevron-down"></i>
            </a>
            <ul class="slide-menu">
                <li>
                    <a class="slide-item <?php echo e(request()->routeIs('admin.testimonials.*') ? 'active' : ''); ?>"
                        href="<?php echo e(route('admin.testimonials.index')); ?>">
                        <i class="fas fa-comment-alt me-2"></i> <?php echo e(trans('AdminSb.View All')); ?>


                        <?php
                            $pendingCount = \App\Models\Testimonial::where('status', 'pending')->count();
                        ?>

                        <?php if($pendingCount > 0): ?>
                            <span class="badge bg-warning text-dark ms-2"><?php echo e($pendingCount); ?></span>
                        <?php endif; ?>
                    </a>
                </li>
            </ul>
        </li>




    </ul>
</div>
<?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/layouts/main-sidebar/admin-sidebar-main.blade.php ENDPATH**/ ?>