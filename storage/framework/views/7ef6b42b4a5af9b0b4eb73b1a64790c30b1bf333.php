<?php $__env->startSection('title', 'لوحة تحكم المشرف العام'); ?>

<?php $__env->startSection('css'); ?>
    <?php echo \Illuminate\View\Factory::parentPlaceholder('css'); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="<?php echo e(URL::asset('dashboard/plugins/notify/css/notifIt.css')); ?>" rel="stylesheet" />

    <style>
        /* --- المتغيرات الأساسية (Globals & Dark Mode) --- */
        :root {ض
            --admin-primary: #4f46e5; /* Indigo-600 */
            --admin-primary-dark: #4338ca; /* Indigo-700 */
            --admin-secondary: #10b981; /* Emerald-500 */
            --admin-success: #22c55e; /* Green-500 */
            --admin-danger: #ef4444; /* Red-500 */
            --admin-warning: #f59e0b; /* Amber-500 */
            --admin-info: #3b82f6; /* Blue-500 */
            --admin-purple: #8b5cf6; /* Violet-500 */
            --admin-teal: #14b8a6; /* Teal-500 */
            --admin-pink: #ec4899; /* Pink-500 */
            --admin-cyan: #06b6d4; /* Cyan-500 */


            --admin-bg: #f4f6f9;
            --admin-card-bg: #ffffff;
            --admin-text: #34495e;
            --admin-text-secondary: #7f8c8d;
            --admin-border-color: #e3e6f0;
            --admin-radius-md: 0.5rem;
            --admin-radius-lg: 0.75rem;
            --admin-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
            --admin-shadow-md: 0 6px 18px rgba(0, 0, 0, 0.08);
            --admin-transition: all 0.25s ease-in-out;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --admin-bg: #111827;
                --admin-card-bg: #1f2937;
                --admin-text: #f3f4f6;
                --admin-text-secondary: #9ca3af;
                --admin-border-color: #374151;
                --admin-primary: #818cf8;
                --admin-primary-dark: #6366f1;
                --admin-secondary: #34d399;
                /* ... (ألوان أخرى للوضع الداكن إذا أردت) ... */
            }
            /* ... (أنماط الوضع الداكن للـ table, pagination, filter-form من كودك السابق فهي جيدة) ... */
             .stats-card { background-color: var(--admin-card-bg); border-color:var(--admin-border-color); }
             .stats-card .info .count { color: var(--admin-text); }
             .stats-card .info .label { color: var(--admin-text-secondary); }
             .stats-card .stat-actions a { color: var(--admin-primary); }
             .stats-card .stat-actions a:hover { color: var(--admin-primary-dark); }
        }

        body { background-color: var(--admin-bg); font-family: 'Tajawal', sans-serif; color: var(--admin-text); }
        .main-content .container-fluid { padding-left: 20px; padding-right: 20px; }
        .breadcrumb-header { margin-bottom: 1.5rem; }
        .content-title { font-weight: 600; }


        /* -- بطاقات الإحصائيات -- */
        .stats-card {
            background-color: var(--admin-card-bg);
            border-radius: var(--admin-radius-lg);
            padding: 1.5rem;
            border: 1px solid var(--admin-border-color);
            box-shadow: var(--admin-shadow);
            display: flex;
            align-items: stretch;
            gap: 1rem;
            transition: var(--admin-transition);
            text-decoration: none !important; /* إزالة الخط من الرابط */
            color: inherit; /* يرث لون النص من body */
            height: 100%;
        }
        .stats-card:hover {
            transform: translateY(-5px) scale(1.015); /* تأثير hover أفضل */
            box-shadow: var(--admin-shadow-md);
            border-left: 4px solid var(--hover-color, var(--admin-primary)); /* استخدام متغير للون الحافة  */
        }
        .stats-card .icon {
            width: 55px; height: 55px;
            border-radius: var(--admin-radius-md);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.75rem; flex-shrink: 0; color: white;
            align-self: center;
        }
        .stats-card .info { display: flex; flex-direction: column; justify-content: center; flex-grow: 1; }
        .stats-card .info .count { font-size: 2rem; font-weight: 700; color: var(--admin-text); margin-bottom: 0.1rem; display: block; line-height: 1.1; }
        .stats-card .info .label { font-size: 0.75rem; color: var(--admin-text-secondary); margin-bottom: 0.5rem; text-transform: uppercase; font-weight: 500; letter-spacing: 0.5px;}
        .stats-card .stat-actions a { font-size: 0.75rem; font-weight: 500; color: var(--admin-primary-dark); display: inline-block; margin-top: auto; opacity: 0.8; letter-spacing: 0.3px;}
        .stats-card .stat-actions a:hover { opacity:1; text-decoration: underline; }

        /* ألوان أيقونات/خلفيات البطاقات بناءً على الكلاس */
        .stats-card .icon.doctors                      { background: var(--admin-primary); }
        .stats-card.doctors-card:hover                 { --hover-color: var(--admin-primary); }

        .stats-card .icon.patients                     { background: var(--admin-secondary); }
        .stats-card.patients-card:hover                { --hover-color: var(--admin-secondary); }

        .stats-card .icon.sections                     { background: var(--admin-success); }
        .stats-card.sections-card:hover                { --hover-color: var(--admin-success); }

        .stats-card .icon.rooms                        { background: var(--admin-info); }
        .stats-card.rooms-card:hover                   { --hover-color: var(--admin-info); }

        .stats-card .icon.beds                         { background: var(--admin-teal); }
        .stats-card.beds-card:hover                    { --hover-color: var(--admin-teal); }

        .stats-card .icon.admissions                   { background: var(--admin-purple); }
        .stats-card.admissions-card:hover              { --hover-color: var(--admin-purple); }

        .stats-card .icon.pending-appointments         { background: var(--admin-warning); }
        .stats-card.pending-appointments-card:hover    { --hover-color: var(--admin-warning); }

        /* === ألوان بطاقات الصيدلية والأشعة والمختبر === */
        .stats-card .icon.pharmacy_managers     { background: var(--admin-purple); opacity: 0.9; }
        .stats-card.pharmacy_managers-card:hover        { --hover-color: var(--admin-purple); }

        .stats-card .icon.pharmacy_employees    { background: var(--admin-teal); opacity: 0.9; }
        .stats-card.pharmacy_employees-card:hover       { --hover-color: var(--admin-teal); }

        .stats-card .icon.ray_employees { background-color: var(--admin-info); opacity:0.9; } /* Use solid color for variety */
        .stats-card.ray_employees-card:hover  { --hover-color: var(--admin-info); }

        .stats-card .icon.lab_employees { background-color: var(--admin-pink); opacity:0.9; } /*  استخدام لون جديد */
        .stats-card.lab_employees-card:hover { --hover-color: var(--admin-pink); }

        .stats-card .icon.system_users { background-color: var(--admin-cyan); opacity:0.9;}
        .stats-card.system_users-card:hover { --hover-color: var(--admin-cyan);}


        /* قسم التنبيهات الهامة للأدمن (من كودك السابق، فهو جيد) */
        .admin-alert-section { margin-top: 2rem; }
        .admin-alert-card { border-radius: var(--admin-radius-md); padding: 1.25rem; border: 1px solid; margin-bottom: 1rem; display: flex; align-items: center; background: var(--admin-card-bg); box-shadow: var(--admin-shadow); }
        .admin-alert-card .alert-icon { font-size: 1.5rem; margin-left: 1rem; }
        .admin-alert-card .alert-message strong { font-weight: 600; color:inherit; }
        .admin-alert-card .alert-link { font-size: 0.85rem; font-weight: 500; color:inherit; text-decoration: underline; }
        .alert-warning-custom { border-color: rgba(var(--admin-warning-rgb), 0.5); color: #856404; /* Bootstrap's default dark yellow */ }
        .alert-warning-custom .alert-icon { color: var(--admin-warning); }
        .alert-warning-custom .alert-message a { color: #664d03; }


        /* قوائم مختصرة (من كودك السابق، فهي جيدة) */
        .summary-list-card .card-header { background-color: #fbfdff; padding: 0.8rem 1.25rem; border-bottom: 1px solid var(--admin-border-color); }
        .summary-list-card .card-title-css {font-size: 1.05rem;}
        .summary-list-card .list-group-item { border-color: var(--admin-border-color); padding: 0.8rem 1.25rem; font-size:0.9rem; display:flex; justify-content: space-between; align-items:center; }
        .summary-list-card .list-group-item:hover { background-color: #f5f7fa; }
        .summary-list-card .item-main { font-weight: 500; color: var(--admin-text)}
        .summary-list-card .item-meta { font-size: 0.75rem; color: var(--admin-text-secondary); }
        .summary-list-card .item-action .btn {padding: 0.25rem 0.6rem; font-size:0.75rem;}
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header'); ?>
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <i class="fas fa-shield-alt fa-2x text-primary me-3"></i>
                <div>
                    <h4 class="content-title mb-0 my-auto"><?php echo e(trans('AdminSb.main_dashboard')); ?></h4>
                    <span class="text-muted mt-0 tx-13 op-8"><?php echo e(trans('AdminSb.dashboard_overview_subtitle')); ?></span>
                </div>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center gap-2">
            <span class="text-muted small"><i class="far fa-calendar-alt me-1"></i> <?php echo e(now()->translatedFormat('l, j F Y')); ?></span>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('Dashboard.messages_alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="users-management-container">
        <div class="row g-3 mb-4">
            
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12" data-aos="fade-up">
                <a href="<?php echo e(route('admin.appointments.index')); ?>" class="stats-card pending-appointments-card">
                    <div class="icon pending-appointments"><i class="fas fa-hourglass-half"></i></div>
                    <div class="info">
                        <span class="count"><?php echo e($pendingAppointmentsCount ?? 0); ?></span>
                        <span class="label"><?php echo e(trans('AdminSb.appointments_waiting_confirmation')); ?></span>
                    </div>
                    <div class="stat-actions align-self-end"><span class="stat-link"><?php echo e(trans('AdminSb.review_now')); ?></span></div>
                </a>
            </div>
            
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12" data-aos="fade-up" data-aos-delay="50">
                <a href="<?php echo e(route('admin.Doctors.index')); ?>" class="stats-card doctors-card">
                    <div class="icon doctors"><i class="fas fa-user-md"></i></div>
                    <div class="info">
                        <span class="count"><?php echo e($doctorsCount ?? 0); ?></span>
                        <span class="label"><?php echo e(trans('AdminSb.Doctors')); ?></span>
                    </div>
                     <div class="stat-actions align-self-end"><span class="stat-link"><?php echo e(trans('AdminSb.manage_doctors')); ?></span></div>
                </a>
            </div>
            
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12" data-aos="fade-up" data-aos-delay="100">
                 <a href="<?php echo e(route('admin.Patients.index')); ?>" class="stats-card patients-card">
                    <div class="icon patients"><i class="fas fa-procedures"></i></div>
                    <div class="info">
                        <span class="count"><?php echo e($patientsCount ?? 0); ?></span>
                        <span class="label"><?php echo e(trans('AdminSb.patients')); ?></span>
                    </div>
                    <div class="stat-actions align-self-end"><span class="stat-link"><?php echo e(trans('AdminSb.manage_patients')); ?></span></div>
                </a>
            </div>
            
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12" data-aos="fade-up" data-aos-delay="150">
                <a href="<?php echo e(route('admin.Sections.index')); ?>" class="stats-card sections-card">
                    <div class="icon sections"><i class="fas fa-hospital-symbol"></i></div>
                    <div class="info">
                        <span class="count"><?php echo e($sectionsCount ?? 0); ?></span>
                        <span class="label"><?php echo e(trans('AdminSb.medical_sections')); ?></span>
                    </div>
                    <div class="stat-actions align-self-end"><span class="stat-link"><?php echo e(trans('AdminSb.manage_sections')); ?></span></div>
                </a>
            </div>
            
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12" data-aos="fade-up" data-aos-delay="200">
                <a href="<?php echo e(route('admin.pharmacy_manager.index')); ?>" class="stats-card pharmacy_managers-card">
                    <div class="icon pharmacy_managers"><i class="fas fa-store-alt"></i></div>
                    <div class="info">
                        <span class="count"><?php echo e($pharmacyManagersCount ?? (\App\Models\PharmacyManager::count() ?? 0)); ?></span>
                        <span class="label"><?php echo e(trans('AdminSb.pharmacy_managers')); ?></span>
                    </div>
                    <div class="stat-actions align-self-end"><span class="stat-link"><?php echo e(trans('AdminSb.manage_pharmacies')); ?></span></div>
                </a>
            </div>
            
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12" data-aos="fade-up" data-aos-delay="250">
                <a href="<?php echo e(route('admin.pharmacy_employee.index')); ?>" class="stats-card pharmacy_employees-card">
                    <div class="icon pharmacy_employees"><i class="fas fa-pills"></i></div>
                    <div class="info">
                        <span class="count"><?php echo e($pharmacyEmployeesCount ?? (\App\Models\PharmacyEmployee::count() ?? 0)); ?></span>
                        <span class="label"><?php echo e(trans('AdminSb.pharmacy_employees')); ?></span>
                    </div>
                    <div class="stat-actions align-self-end"><span class="stat-link"><?php echo e(trans('AdminSb.employees')); ?></span></div>
                </a>
            </div>
            
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12" data-aos="fade-up" data-aos-delay="300">
                <a href="<?php echo e(route('admin.ray_employee.index')); ?>" class="stats-card ray_employees-card">
                    <div class="icon ray_employees"><i class="fas fa-x-ray"></i></div>
                    <div class="info">
                        <span class="count"><?php echo e($rayEmployeesCount ?? (\App\Models\RayEmployee::count() ?? 0)); ?></span>
                        <span class="label"><?php echo e(trans('AdminSb.Radiology staff')); ?></span>
                    </div>
                    <div class="stat-actions align-self-end"><span class="stat-link"><?php echo e(trans('AdminSb.radiology_department')); ?></span></div>
                </a>
            </div>
            
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12" data-aos="fade-up" data-aos-delay="350">
                <a href="<?php echo e(route('admin.laboratorie_employee.index')); ?>" class="stats-card lab_employees-card">
                    <div class="icon lab_employees"><i class="fas fa-flask"></i></div>
                    <div class="info">
                        <span class="count"><?php echo e($laboratorieEmployeesCount ?? (\App\Models\LaboratorieEmployee::count() ?? 0)); ?></span>
                        <span class="label"><?php echo e(trans('AdminSb.lab_staff')); ?></span>
                    </div>
                    <div class="stat-actions align-self-end"><span class="stat-link"><?php echo e(trans('AdminSb.lab_department')); ?></span></div>
                </a>
            </div>
        </div>

        <?php if(isset($hasAdminAlerts) && $hasAdminAlerts): ?>
        <div class="row mt-2 admin-alert-section">
            <div class="col-12 mb-2" data-aos="fade-up" data-aos-delay="50">
                <h5 class="section-heading" style="border-bottom-color: var(--admin-warning); color:var(--admin-warning);"><i class="fas fa-bell"></i><?php echo e(trans('AdminSb.urgent_actions_and_alerts')); ?></h5>
            </div>
            <?php if(isset($pendingAppointmentsCount) && $pendingAppointmentsCount > 0): ?>
            <div class="col-lg-6 col-md-12 mb-3" data-aos="fade-up" data-aos-delay="100">
                <div class="admin-alert-card alert-warning-custom">
                    <i class="fas fa-hourglass-half alert-icon"></i>
                    <div class="alert-message">
                        <strong class="d-block mb-1"><?php echo e(trans('AdminSb.new_appointment_requests_waiting')); ?></strong>
                        <?php echo e(trans('AdminSb.there_are_appointments_that_need_review', ['count' => $pendingAppointmentsCount])); ?>

                        <a href="<?php echo e(route('admin.appointments.index')); ?>" class="alert-link d-block mt-2"><?php echo e(trans('AdminSb.review_all_appointment_requests')); ?> <i class="fas fa-arrow-left fa-xs"></i></a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php else: ?>
         <div class="row mt-2" data-aos="fade-up" data-aos-delay="50">
             <div class="col-12">
                 <div class="alert alert-success text-center" role="alert">
                     <i class="fas fa-check-circle me-2"></i> <?php echo e(trans('AdminSb.no_urgent_alerts')); ?>

                 </div>
             </div>
         </div>
        <?php endif; ?>

        <div class="row row-deck mt-3">
             <div class="col-lg-7 col-md-12 mb-4" data-aos="fade-right" data-aos-delay="200">
                <div class="card h-100 shadow-sm summary-list-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title-css mb-0"><i class="far fa-calendaroplus-alt me-2 text-primary op-8"></i><?php echo e(trans('AdminSb.latest_pending_appointments_5')); ?></h4>
                         <?php if(isset($latestPendingAppointments) && $latestPendingAppointments->count() > 0): ?>
                            <a href="<?php echo e(route('admin.appointments.index')); ?>" class="btn btn-sm btn-outline-primary ripple"><?php echo e(trans('AdminSb.view_all_requests')); ?></a>
                         <?php endif; ?>
                    </div>
                    <div class="card-body pt-2 pb-2">
                         <?php if(isset($latestPendingAppointments) && $latestPendingAppointments->isNotEmpty()): ?>
                            <ul class="list-group list-group-flush">
                                <?php $__currentLoopData = $latestPendingAppointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="list-group-item">
                                        <div class="d-flex w-100 justify-content-between align-items-center">
                                            <div class="flex-grow-1">
                                                <span class="item-main"><?php echo e($appointment->patient->name ?? $appointment->name); ?></span>
                                                 <span class="text-muted mx-1 small"><?php echo e(trans('AdminSb.wants_an_appointment_with')); ?></span>
                                                 <span class="item-main text-primary-dark"><?php echo e($appointment->doctor->name ?? '-'); ?></span>
                                                <div class="item-meta">
                                                    <i class="far fa-clock fa-xs"></i> <?php echo e(trans('AdminSb.for_date')); ?> <?php echo e($appointment->appointment); ?>

                                                    <span class="mx-1">|</span>
                                                    <i class="fas fa-tag fa-xs"></i> <?php echo e(trans('AdminSb.requested_since')); ?> <?php echo e($appointment->created_at->diffForHumans()); ?>

                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        <?php else: ?>
                            <div class="text-center text-muted p-4">
                                <i class="fas fa-calendar-check fa-2x opacity-50 mb-2"></i>
                                <p class="mb-0"><?php echo e(trans('AdminSb.no_new_appointment_requests_pending')); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 col-md-12 mb-4" data-aos="fade-left" data-aos-delay="300">
                 <div class="card h-100 shadow-sm summary-list-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title-css mb-0"><i class="fas fa-users me-2 text-primary op-8"></i><?php echo e(trans('AdminSb.latest_registered_patients_5')); ?></h4>
                          <?php if(isset($recentPatients) && $recentPatients->count() > 0): ?>
                            <a href="<?php echo e(route('admin.Patients.index')); ?>" class="btn btn-sm btn-outline-primary ripple"><?php echo e(trans('AdminSb.all_patients')); ?></a>
                         <?php endif; ?>
                    </div>
                    <div class="card-body pt-2 pb-2">
                        <?php if(isset($recentPatients) && $recentPatients->isNotEmpty()): ?>
                             <ul class="list-group list-group-flush">
                                <?php $__currentLoopData = $recentPatients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $patientRec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="list-group-item">
                                    <div class="d-flex align-items-center">
                                         <img src="<?php echo e($patientRec->image ? asset('Dashboard/img/patients/' . $patientRec->image->filename) : asset('Dashboard/img/default_patient_avatar.png')); ?>" alt="<?php echo e($patientRec->name); ?>" style="width:35px; height:35px; border-radius:50%; object-fit:cover; margin-left:10px; border:2px solid var(--admin-border-color);">
                                        <div class="flex-grow-1">
                                            <a href="<?php echo e(route('admin.Patients.show', $patientRec->id)); ?>" class="item-main text-decoration-none hover-underline"><?php echo e($patientRec->name); ?></a>
                                            <div class="item-meta">
                                                <i class="far fa-calendar-plus fa-xs"></i> <?php echo e(trans('AdminSb.joined')); ?>: <?php echo e($patientRec->created_at->diffForHumans()); ?>

                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-center text-muted p-4"><?php echo e(trans('AdminSb.no_recently_registered_patients')); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <?php echo \Illuminate\View\Factory::parentPlaceholder('js'); ?>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="<?php echo e(URL::asset('dashboard/plugins/notify/js/notifIt.js')); ?>"></script>
    
    <script>
        $(document).ready(function() {
            AOS.init({ duration: 600, easing: 'ease-out-cubic', once: true, offset: 30 });

            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl, { boundary: document.body, container:'body'});
            });

            <?php if(session('success')): ?> notif({ msg: `<div class='d-flex align-items-center p-2'><i class='fas fa-check-circle fa-lg me-2 text-success'></i><div><?php echo addslashes(session('success')); ?></div></div>`, type: "success", position: "top-center", autohide: true, timeout: 6000 }); <?php endif; ?>
            <?php if(session('error')): ?> notif({ msg: `<div class='d-flex align-items-center p-2'><i class='fas fa-exclamation-triangle fa-lg me-2 text-danger'></i><div><?php echo addslashes(session('error')); ?></div></div>`, type: "error", position: "top-center", autohide: true, timeout: 8000 }); <?php endif; ?>
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Dashboard.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/Admin/dashboard.blade.php ENDPATH**/ ?>