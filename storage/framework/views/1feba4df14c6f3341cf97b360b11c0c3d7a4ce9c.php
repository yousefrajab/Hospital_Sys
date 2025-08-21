<?php $__env->startSection('title', 'قائمة المرضى'); ?>

<?php $__env->startSection('css'); ?>
    <?php echo \Illuminate\View\Factory::parentPlaceholder('css'); ?>
    <link href="<?php echo e(URL::asset('dashboard/plugins/notify/css/notifIt.css')); ?>" rel="stylesheet" />
    
    
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    
    <link href="<?php echo e(URL::asset('Dashboard/plugins/select2/css/select2.min.css')); ?>" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

    <style>
        /* --- استخدام نفس المتغيرات والأنماط العامة من الردود السابقة --- */
        :root {
            --admin-primary: #4f46e5;
            /* بنفسجي جذاب */
            --admin-primary-dark: #4338ca;
            --admin-secondary: #10b981;
            /* أخضر مائل للأزرق */
            --admin-success: #22c55e;
            /* أخضر زاهي */
            --admin-danger: #ef4444;
            /* أحمر واضح */
            --admin-warning: #f59e0b;
            /* برتقالي دافئ */
            --admin-info: #3b82f6;
            /* أزرق سماوي */
            --admin-light: #f8f9fa;
            /* أبيض مائل للرمادي فاتح جدا */
            --admin-dark: #212529;
            /* أسود فحمي */

            --admin-bg: #f4f6f9;
            /* خلفية رمادية فاتحة جداً */
            --admin-card-bg: #ffffff;
            --admin-text: #343a40;
            /* نص أسود مائل للرمادي */
            --admin-text-secondary: #6c757d;
            /* نص رمادي ثانوي */
            --admin-border-color: #dee2e6;
            /* لون الحدود */

            --admin-radius-sm: 0.25rem;
            --admin-radius-md: 0.5rem;
            /* زوايا دائرية متوسطة */
            --admin-radius-lg: 0.75rem;
            --admin-radius-xl: 1rem;
            --admin-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            /* ظل ناعم */
            --admin-shadow-md: 0 6px 12px rgba(0, 0, 0, 0.08);
            --admin-transition: all 0.25s ease-in-out;
            /* انتقال سلس وسريع */

            --admin-primary-rgb: 79, 70, 229;
            --admin-secondary-rgb: 16, 185, 129;
            --admin-success-rgb: 34, 197, 94;
            --admin-danger-rgb: 239, 68, 68;
            --admin-warning-rgb: 245, 158, 11;
            --admin-info-rgb: 59, 130, 246;
        }

        /* يمكنك إضافة أنماط للوضع الداكن هنا إذا أردت */
        /* @media (prefers-color-scheme: dark) { ... } */

        body {
            background-color: var(--admin-bg);
            font-family: 'Tajawal', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            /* خطوط متناسقة */
            color: var(--admin-text);
            font-size: 0.95rem;
            /* حجم خط أساسي مريح */
        }

        .card {
            background-color: var(--admin-card-bg);
            border-radius: var(--admin-radius-lg);
            box-shadow: var(--admin-shadow);
            border: 1px solid var(--admin-border-color);
            margin-bottom: 1.75rem;
            /* مسافة أكبر قليلاً بين البطاقات */
        }

        .card-header {
            background-color: transparent;
            /* أو var(--admin-light) لخلفية فاتحة جدا */
            border-bottom: 1px solid var(--admin-border-color);
            padding: 1rem 1.5rem;
            /* مساحة داخلية جيدة */
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header .card-title {
            font-weight: 600;
            color: var(--admin-text);
            margin-bottom: 0;
            font-size: 1.15rem;
            /* عنوان بطاقة أوضح */
        }

        .card-header .card-title i {
            margin-left: 0.6rem;
            /* تعديل للغة العربية: margin-right */
            margin-right: 0.6rem;
            color: var(--admin-primary);
        }

        /* بطاقات الإحصائيات */
        .stats-card-patient {
            background: var(--admin-card-bg);
            border-radius: var(--admin-radius-lg);
            padding: 1.5rem;
            /* مساحة أكبر قليلاً */
            box-shadow: var(--admin-shadow-md);
            display: flex;
            align-items: center;
            gap: 1.25rem;
            /* مسافة بين الأيقونة والنص */
            transition: var(--admin-transition);
            border-left: 6px solid transparent;
            /* حدود جانبية أوضح */
        }

        .stats-card-patient:hover {
            transform: translateY(-5px) scale(1.02);
            /* تأثير تحريك وتكبير بسيط */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .stats-card-patient .icon {
            width: 55px;
            /* أيقونة أكبر قليلاً */
            height: 55px;
            border-radius: var(--admin-radius-md);
            /* شكل مربع بحواف دائرية للأيقونة */
            display: grid;
            place-items: center;
            font-size: 1.8rem;
            color: white;
            flex-shrink: 0;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
        }

        .stats-card-patient .info .count {
            font-size: 2rem;
            /* رقم أكبر للإحصائية */
            font-weight: 700;
            color: var(--admin-text);
            margin-bottom: 0.2rem;
            display: block;
            line-height: 1.2;
        }

        .stats-card-patient .info .label {
            font-size: 0.9rem;
            /* تسمية أوضح */
            color: var(--admin-text-secondary);
        }

        .stats-card-patient.primary {
            border-left-color: var(--admin-primary);
        }

        .stats-card-patient.primary .icon {
            background: linear-gradient(45deg, var(--admin-primary), var(--admin-primary-dark));
        }

        .stats-card-patient.success {
            border-left-color: var(--admin-success);
        }

        .stats-card-patient.success .icon {
            background: linear-gradient(45deg, var(--admin-success), #16a34a);
        }

        .stats-card-patient.info {
            border-left-color: var(--admin-info);
        }

        .stats-card-patient.info .icon {
            background: linear-gradient(45deg, var(--admin-info), #2563eb);
        }

        /* الجدول */
        .table-responsive {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            margin-bottom: 0;
            color: var(--admin-text);
            border-collapse: separate;
            /* للسماح بـ border-spacing */
            border-spacing: 0 0.5rem;
            /* مسافة بين الصفوف إذا أردت، تحتاج table لـ border-collapse: separate; */
        }

        .table thead th {
            background-color: var(--admin-light);
            /* خلفية أفتح لرأس الجدول */
            color: var(--admin-text-secondary);
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid var(--admin-border-color);
            white-space: nowrap;
            padding: 1rem 1.25rem;
            /* مساحة داخلية أكبر للخلايا */
            text-align: right;
        }

        .table td,
        .table th {
            vertical-align: middle;
            padding: 1rem 1.25rem;
            border-top: 1px solid var(--admin-border-color);
            /* خط فاصل أنعم */
            text-align: right;
            font-size: 0.9rem;
        }

        .table tbody tr {
            background-color: var(--admin-card-bg);
            /* خلفية الصفوف */
            transition: background-color 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: #f1f5f9;
            /* لون خلفية عند التحويم أفتح */
        }

        .patient-avatar-table {
            width: 60px;
            /* صورة أكبر قليلاً */
            height: 70px;
            border-radius: var(--admin-radius-md);
            /* زوايا دائرية لصورة المريض */
            object-fit: cover;
            border: 2px solid var(--admin-border-color);
            margin-left: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .patient-name-link {
            color: var(--admin-primary);
            font-weight: 600;
            text-decoration: none;
            transition: var(--admin-transition);
        }

        .patient-name-link:hover {
            color: var(--admin-primary-dark);
            text-decoration: underline;
        }

        .patient-name-link+.text-muted {
            font-size: 0.8rem;
        }

        .badge {
            font-size: 0.8rem;
            /* حجم Badge متناسق */
            padding: 0.45em 0.85em;
            /* مساحة داخلية أفضل للـ Badge */
            border-radius: var(--admin-radius-sm);
            /* حواف دائرية أقل للـ Badge */
            font-weight: 500;
            letter-spacing: 0.3px;
        }

        .badge-gender-male {
            background-color: rgba(var(--admin-info-rgb), 0.12);
            color: var(--admin-info);
            border: 1px solid rgba(var(--admin-info-rgb), 0.2);
        }

        .badge-gender-female {
            background-color: rgba(236, 72, 153, 0.12);
            color: #d946ef;
            /* بنفسجي/وردي للنساء */
            border: 1px solid rgba(236, 72, 153, 0.2);
        }

        .badge-blood {
            background-color: rgba(var(--admin-danger-rgb), 0.12);
            color: var(--admin-danger);
            border: 1px solid rgba(var(--admin-danger-rgb), 0.2);
        }

        .badge-blood i {
            transform: rotate(10deg);
            /* لمسة بسيطة لأيقونة فصيلة الدم */
        }

        .status-badge-table {
            font-weight: 500;
        }

        .status-badge-table.admitted {
            color: var(--admin-success);
            /* background-color: rgba(var(--admin-success-rgb), 0.1); */
            /* padding: 0.3em 0.6em; */
            /* border-radius: var(--admin-radius-sm); */
        }

        .status-badge-table.not-admitted {
            color: var(--admin-text-secondary);
            /* background-color: rgba(108, 117, 125, 0.1); */
            /* padding: 0.3em 0.6em; */
            /* border-radius: var(--admin-radius-sm); */
        }

        .status-badge-table i {
            font-size: 0.9em;
        }

        .action-buttons .btn {
            margin: 0 3px;
            /* مسافة أكبر بين أزرار العمليات */
            padding: 0.4rem 0.7rem;
            font-size: 0.85rem;
            border-radius: var(--admin-radius-md);
            transition: var(--admin-transition);
        }

        .action-buttons .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .action-buttons .btn i {
            font-size: 1rem;
            /* أيقونات أكبر قليلاً في الأزرار */
            vertical-align: middle;
        }

        .btn-outline-success:hover {
            color: #fff;
            background-color: var(--admin-success);
            border-color: var(--admin-success);
        }

        .btn-outline-primary:hover {
            color: #fff;
            background-color: var(--admin-primary);
            border-color: var(--admin-primary);
        }

        .btn-outline-info:hover {
            color: #fff;
            background-color: var(--admin-info);
            border-color: var(--admin-info);
        }

        .btn-outline-warning:hover {
            color: #212529;
            background-color: var(--admin-warning);
            border-color: var(--admin-warning);
        }

        .btn-outline-danger:hover {
            color: #fff;
            background-color: var(--admin-danger);
            border-color: var(--admin-danger);
        }


        /* الفلاتر */
        .filter-card {
            margin-bottom: 1.75rem;
        }

        .filter-card .form-label {
            font-weight: 500;
            font-size: 0.85rem;
            margin-bottom: 0.3rem;
            color: var(--admin-text-secondary);
        }

        .form-control,
        .form-select {
            border-radius: var(--admin-radius-md);
            border: 1px solid var(--admin-border-color);
            /* لون الحدود الأساسي */
            padding: 0.55rem 0.85rem;
            /* مساحة داخلية متناسقة */
            font-size: 0.9rem;
            transition: var(--admin-transition);
            background-color: var(--admin-card-bg);
            /* ليتناسق مع الوضع الداكن لو وجد */
            color: var(--admin-text);
        }

        .form-control::placeholder {
            color: #adb5bd;
            /* لون أفتح للنص المؤقت */
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--admin-primary);
            box-shadow: 0 0 0 0.2rem rgba(var(--admin-primary-rgb), 0.1);
            /* ظل تركيز أنعم */
        }

        .filter-card .btn-primary {
            background-color: var(--admin-primary);
            border-color: var(--admin-primary);
            transition: var(--admin-transition);
        }

        .filter-card .btn-primary:hover {
            background-color: var(--admin-primary-dark);
            border-color: var(--admin-primary-dark);
        }

        .filter-card .btn-outline-secondary {
            color: var(--admin-text-secondary);
            border-color: var(--admin-border-color);
        }

        .filter-card .btn-outline-secondary:hover {
            background-color: var(--admin-text-secondary);
            color: var(--admin-card-bg);
        }


        /* تنسيق خاص لقائمة الأمراض المزمنة في الجدول */
        .diseases-list-inline {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-wrap: wrap;
            /* للسماح بالالتفاف إذا زاد عدد الأمراض */
            gap: 0.3rem;
            /* مسافة بين عناصر القائمة */
            font-size: 0.8rem;
        }

        .diseases-list-inline li {
            background-color: rgba(var(--admin-warning-rgb), 0.1);
            color: var(--admin-warning);
            padding: 0.2em 0.5em;
            border-radius: var(--admin-radius-sm);
            border: 1px solid rgba(var(--admin-warning-rgb), 0.2);
            white-space: nowrap;
            /* لمنع كسر اسم المرض الطويل */
        }

        .diseases-list-inline li.more-diseases a {
            color: var(--admin-primary);
            text-decoration: none;
            font-weight: 500;
        }

        .diseases-list-inline li.more-diseases a:hover {
            text-decoration: underline;
        }

        .no-chronic-diseases {
            font-size: 0.85rem;
            color: var(--admin-text-secondary);
            font-style: italic;
        }

        /* تنسيق ترقيم الصفحات */
        .pagination-container .pagination .page-item .page-link {
            color: var(--admin-primary);
            border-radius: var(--admin-radius-sm);
            margin: 0 2px;
            transition: var(--admin-transition);
        }

        .pagination-container .pagination .page-item.active .page-link {
            background-color: var(--admin-primary);
            border-color: var(--admin-primary);
            color: #fff;
            box-shadow: 0 2px 5px rgba(var(--admin-primary-rgb), 0.3);
        }

        .pagination-container .pagination .page-item .page-link:hover {
            background-color: rgba(var(--admin-primary-rgb), 0.1);
            border-color: rgba(var(--admin-primary-rgb), 0.3);
        }

        .pagination-container .pagination .page-item.disabled .page-link {
            color: var(--admin-text-secondary);
            background-color: var(--admin-light);
            border-color: var(--admin-border-color);
        }

        /* Select2 */
        .select2-container--bootstrap-5 .select2-selection {
            border-radius: var(--admin-radius-md);
            border: 1px solid var(--admin-border-color);
            padding: 0.41rem 0.75rem;
            /* تعديل بسيط ليتناسب مع ارتفاع الـ input */
            min-height: calc(1.5em + 1.1rem + 2px);
            /* لضمان نفس ارتفاع الـ input */
            background-color: var(--admin-card-bg);
        }

        .select2-container--bootstrap-5.select2-container--focus .select2-selection,
        .select2-container--bootstrap-5.select2-container--open .select2-selection {
            border-color: var(--admin-primary);
            box-shadow: 0 0 0 0.2rem rgba(var(--admin-primary-rgb), 0.1);
        }

        .select2-container--bootstrap-5 .select2-dropdown {
            border-radius: var(--admin-radius-md);
            border-color: var(--admin-border-color);
            box-shadow: var(--admin-shadow);
        }

        .select2-container--bootstrap-5 .select2-results__option--highlighted {
            background-color: var(--admin-primary);
            color: #fff;
        }

        .select2-container--bootstrap-5 .select2-search--dropdown .select2-search__field {
            border-radius: var(--admin-radius-sm);
            border-color: var(--admin-border-color);
        }

        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
            color: var(--admin-text);
        }

        .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice {
            background-color: var(--admin-primary);
            color: #fff;
            border: none;
            border-radius: var(--admin-radius-sm);
        }

        .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove {
            color: rgba(255, 255, 255, 0.7);
        }

        .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__remove:hover {
            color: #fff;
        }

        /* زر إضافة مريض في أعلى الصفحة */
        .btn-primary.ripple {
            background-color: var(--admin-primary);
            border-color: var(--admin-primary);
            padding: 0.6rem 1.2rem;
            /* زر أكبر قليلاً */
            font-size: 0.95rem;
            font-weight: 500;
            border-radius: var(--admin-radius-md);
            transition: var(--admin-transition);
            box-shadow: 0 2px 5px rgba(var(--admin-primary-rgb), 0.2);
        }

        .btn-primary.ripple:hover {
            background-color: var(--admin-primary-dark);
            border-color: var(--admin-primary-dark);
            box-shadow: 0 4px 8px rgba(var(--admin-primary-rgb), 0.3);
            transform: translateY(-1px);
        }

        .btn-primary.ripple i {
            margin-right: 0.4rem;
            /* تعديل للغة العربية */
        }

        /* تنسيق بسيط لرسالة "لا يوجد مرضى" */
        .no-patients-message {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            text-align: center;
            color: var(--admin-text-secondary);
        }

        .no-patients-message i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: var(--admin-info);
        }

        .no-patients-message p {
            font-size: 1.1rem;
            margin-bottom: 1rem;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header'); ?>
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <i class="fas fa-users-cog fa-lg me-2" style="color: var(--admin-primary);"></i>
                <div>
                    <h4 class="content-title mb-0 my-auto" style="font-weight: 600;">
                        <?php echo e(trans('AdminSb.Patients Management')); ?></h4>
                    <span class="text-muted mt-0 tx-13">/ <?php echo e(trans('AdminSb.view_and_filter_patients')); ?></span>
                </div>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <a href="<?php echo e(route('admin.Patients.create')); ?>" class="btn btn-primary ripple">
                <i class="fas fa-user-plus"></i> <?php echo e(trans('AdminSb.Add a new patient')); ?>

            </a>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('Dashboard.messages_alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="row g-4 mb-4 animate__animated animate__fadeInDown">
        <div class="col-sm-6 col-lg-4">
            <div class="stats-card-patient primary">
                <div class="icon"><i class="fas fa-users"></i></div>
                <div class="info">
                    <span class="count"><?php echo e($totalPatients ?? 0); ?></span>
                    <span class="label"><?php echo e(trans('AdminSb.total_patients')); ?></span>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4">
            <div class="stats-card-patient success">
                <div class="icon"><i class="fas fa-hospital-user"></i></div>
                <div class="info">
                    <span class="count"><?php echo e($admittedPatientsCount ?? 0); ?></span>
                    <span class="label"><?php echo e(trans('AdminSb.currently_admitted_patients')); ?></span>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4">
            <div class="stats-card-patient info">
                <div class="icon"><i class="fas fa-user-clock"></i></div>
                <div class="info">
                    <span class="count"><?php echo e($newPatientsThisMonth ?? 0); ?></span>
                    <span class="label"><?php echo e(trans('AdminSb.new_patients_this_month')); ?></span>
                </div>
            </div>
        </div>
    </div>

    
    <div class="card filter-card animate__animated animate__fadeIn">
        <div class="card-header">
            <h5 class="card-title mb-0"><i class="fas fa-filter me-2"
                    style="color: var(--admin-secondary);"></i><?php echo e(trans('AdminSb.patient_filter_options')); ?></h5>
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('admin.Patients.index')); ?>" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-3 col-md-6">
                        <label for="search_patient" class="form-label"><?php echo e(trans('AdminSb.comprehensive_search')); ?></label>
                        <input type="text" name="search_patient" id="search_patient" class="form-control"
                            placeholder="<?php echo e(trans('AdminSb.search_placeholder_patient')); ?>"
                            value="<?php echo e($request->input('search_patient')); ?>">
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label for="gender_filter" class="form-label"><?php echo e(trans('AdminSb.gender')); ?></label>
                        <select name="gender_filter" id="gender_filter" class="form-select select2"
                            data-placeholder="<?php echo e(trans('AdminSb.all')); ?>">
                            <option value=""><?php echo e(trans('AdminSb.all')); ?></option>
                            <?php $__currentLoopData = $genders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($key); ?>"
                                    <?php echo e($request->input('gender_filter') == $key ? 'selected' : ''); ?>><?php echo e($value); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label for="blood_group_filter" class="form-label"><?php echo e(trans('AdminSb.blood_type')); ?></label>
                        <select name="blood_group_filter" id="blood_group_filter" class="form-select select2"
                            data-placeholder="<?php echo e(trans('AdminSb.all')); ?>">
                            <option value=""><?php echo e(trans('AdminSb.all')); ?></option>
                            <?php $__currentLoopData = $bloodGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($group); ?>"
                                    <?php echo e($request->input('blood_group_filter') == $group ? 'selected' : ''); ?>>
                                    <?php echo e($group); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        <label for="admission_status_filter"
                            class="form-label"><?php echo e(trans('AdminSb.admission_status')); ?></label>
                        <select name="admission_status_filter" id="admission_status_filter" class="form-select select2"
                            data-placeholder="<?php echo e(trans('AdminSb.all')); ?>">
                            <option value=""><?php echo e(trans('AdminSb.all')); ?></option>
                            <?php $__currentLoopData = $admissionStatusesFilter; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($key); ?>"
                                    <?php echo e($request->input('admission_status_filter') == $key ? 'selected' : ''); ?>>
                                    <?php echo e($value); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-12 d-flex mt-md-0 mt-3">
                        <button type="submit" class="btn btn-primary w-100 me-2"><i class="fas fa-search me-1"></i>
                            <?php echo e(trans('AdminSb.apply_filter')); ?></button>
                        <?php if(request()->hasAny(['search_patient', 'gender_filter', 'blood_group_filter', 'admission_status_filter'])): ?>
                            <a href="<?php echo e(route('admin.Patients.index')); ?>" class="btn btn-outline-secondary"
                                data-bs-toggle="tooltip" title="<?php echo e(trans('AdminSb.clear_all_filters')); ?>"><i
                                    class="fas fa-eraser"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>

    
    <div class="card patient-table-card animate__animated animate__fadeInUp" style="animation-delay: 0.1s;">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-clipboard-list me-2"
                    style="color: var(--admin-info);"></i><?php echo e(trans('AdminSb.registered_patients_list')); ?>

                <?php if($Patients->total() > 0): ?>
                    <span class="badge bg-primary-light text-primary ms-2"><?php echo e($Patients->total()); ?>

                        <?php echo e(trans('AdminSb.patient')); ?></span>
                <?php endif; ?>
            </h3>
        </div>
        <div class="card-body p-0">
            <?php if($Patients->isNotEmpty()): ?>
                <div class="table-responsive">
                    <table class="table table-hover text-md-nowrap" id="patients-table-datatable-custom">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th><?php echo e(trans('AdminSb.patient_image')); ?></th>
                                <th><?php echo e(trans('AdminSb.patient_name_and_email')); ?></th>
                                <th><?php echo e(trans('AdminSb.national_id')); ?></th>
                                <th><?php echo e(trans('AdminSb.age_and_dob')); ?></th>
                                <th><?php echo e(trans('AdminSb.phone')); ?></th>
                                <th><?php echo e(trans('AdminSb.gender')); ?></th>
                                <th><?php echo e(trans('AdminSb.blood_type')); ?></th>
                                <th><?php echo e(trans('AdminSb.admission_status_and_details')); ?></th>
                                <th><?php echo e(trans('AdminSb.chronic_diseases')); ?></th>
                                <th class="text-center"><?php echo e(trans('AdminSb.operations')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $Patients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $Patient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="animate__animated animate__fadeIn"
                                    style="animation-delay: <?php echo e($index * 0.05); ?>s;">
                                    <td><?php echo e($Patients->firstItem() + $index); ?></td>
                                    <td>
                                        <?php if($Patient->image && $Patient->image->filename): ?>
                                            <img src="<?php echo e(asset('Dashboard/img/patients/' . $Patient->image->filename)); ?>"
                                                class="patient-avatar-table" alt="<?php echo e($Patient->name); ?>"
                                                onerror="this.onerror=null; this.src='<?php echo e(URL::asset('Dashboard/img/doctor_default.png')); ?>';">
                                        <?php else: ?>
                                            <img src="<?php echo e(URL::asset('Dashboard/img/doctor_default.png')); ?>"
                                                class="patient-avatar-table" alt="<?php echo e(trans('AdminSb.default_image')); ?>">
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo e(route('admin.Patients.show', $Patient->id)); ?>"
                                            class="patient-name-link"><?php echo e($Patient->name); ?></a>
                                        <small class="text-muted d-block"><?php echo e($Patient->email); ?></small>
                                    </td>
                                    <td><?php echo e($Patient->national_id); ?></td>
                                    <td>
                                        <?php if($Patient->Date_Birth): ?>
                                            <?php echo e(\Carbon\Carbon::parse($Patient->Date_Birth)->age); ?>

                                            <?php echo e(trans('AdminSb.years_old')); ?>

                                            <small
                                                class="text-muted d-block">(<?php echo e(\Carbon\Carbon::parse($Patient->Date_Birth)->translatedFormat('j M Y')); ?>)</small>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($Patient->Phone); ?></td>
                                    <td>
                                        <?php if($Patient->Gender): ?>
                                            <span
                                                class="badge <?php echo e($Patient->Gender == 1 ? 'badge-gender-male' : 'badge-gender-female'); ?>">
                                                <i
                                                    class="fas <?php echo e($Patient->Gender == 1 ? 'fa-mars' : 'fa-venus'); ?> me-1"></i>
                                                <?php echo e($Patient->Gender == 1 ? trans('AdminSb.male') : trans('AdminSb.female')); ?>

                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($Patient->Blood_Group): ?>
                                            <span class="badge badge-blood"><i class="fas fa-tint me-1"></i>
                                                <?php echo e($Patient->Blood_Group); ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td
                                        class="status-badge-table <?php echo e($Patient->currentAdmission ? 'admitted' : 'not-admitted'); ?>">
                                        <?php if($Patient->currentAdmission): ?>
                                            <i class="fas fa-procedures fa-fw me-1"></i>
                                            <?php echo e(trans('AdminSb.currently_admitted')); ?>

                                            <?php if($Patient->currentAdmission->bed && $Patient->currentAdmission->bed->room): ?>
                                                <small class="d-block text-muted" style="font-size: 0.75rem;">
                                                    (<?php echo e($Patient->currentAdmission->bed->room->section->name ?? trans('AdminSb.department')); ?>

                                                    -
                                                    <?php echo e(trans('AdminSb.room_short')); ?>:
                                                    <?php echo e($Patient->currentAdmission->bed->room->room_number); ?> -
                                                    <?php echo e(trans('AdminSb.bed_short')); ?>:
                                                    <?php echo e($Patient->currentAdmission->bed->bed_number); ?>)
                                                </small>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <i class="fas fa-user-check fa-fw me-1"></i>
                                            <?php echo e(trans('AdminSb.not_admitted')); ?>

                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($Patient->diagnosedChronicDiseases && $Patient->diagnosedChronicDiseases->count() > 0): ?>
                                            <ul class="diseases-list-inline">
                                                <?php $__currentLoopData = $Patient->diagnosedChronicDiseases->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $diagnosedDisease): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="<?php echo e($diagnosedDisease->name); ?> - <?php echo e(trans('AdminSb.diagnosis_date')); ?>: <?php echo e($diagnosedDisease->pivot->diagnosed_at ? \Carbon\Carbon::parse($diagnosedDisease->pivot->diagnosed_at)->format('Y-m-d') : trans('AdminSb.undefined')); ?> - <?php echo e(trans('AdminSb.status')); ?>: <?php echo e(\App\Models\PatientChronicDisease::getStatuses()[$diagnosedDisease->pivot->current_status] ?? $diagnosedDisease->pivot->current_status); ?>">
                                                        <i class="fas fa-viruses fa-xs"></i>
                                                        <?php echo e(Str::limit($diagnosedDisease->name, 15)); ?>

                                                    </li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($Patient->diagnosedChronicDiseases->count() > 2): ?>
                                                    <li class="more-diseases">
                                                        <a href="<?php echo e(route('admin.Patients.show', $Patient->id)); ?>#chronic-diseases-section"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="<?php echo e(trans('AdminSb.view_all_chronic_diseases')); ?> (+<?php echo e($Patient->diagnosedChronicDiseases->count() - 2); ?>)">
                                                            <i class="fas fa-ellipsis-h"></i>
                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                            </ul>
                                        <?php else: ?>
                                            <span
                                                class="no-chronic-diseases"><?php echo e(trans('AdminSb.no_chronic_diseases_found')); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center action-buttons">
                                        <a href="<?php echo e(route('admin.showQR', $Patient->id)); ?>"
                                            class="btn btn-sm btn-outline-success" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="<?php echo e(trans('AdminSb.view_patient_file')); ?>"><i
                                                class="fas fa-eye"></i></a>
                                        <a href="<?php echo e(route('admin.Patients.edit', $Patient->id)); ?>"
                                            class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="<?php echo e(trans('AdminSb.edit_patient_data')); ?>"><i
                                                class="fas fa-edit"></i></a>
                                        <?php if(!$Patient->currentAdmission): ?>
                                            <a href="<?php echo e(route('admin.patient_admissions.create', ['patient_id' => $Patient->id])); ?>"
                                                class="btn btn-sm btn-outline-info" data-toggle="tooltip"
                                                data-bs-placement="top" title="<?php echo e(trans('AdminSb.admit_patient')); ?>"><i
                                                    class="fas fa-procedures"></i></a>
                                        <?php else: ?>
                                            <a href="<?php echo e(route('admin.patient_admissions.edit', $Patient->currentAdmission->id)); ?>"
                                                class="btn btn-sm btn-outline-warning" data-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="<?php echo e(trans('AdminSb.edit_admission_or_discharge')); ?>"><i
                                                    class="fas fa-file-medical-alt"></i></a>
                                            <a href="<?php echo e(route('admin.patient_admissions.vital_signs_sheet', $Patient->currentAdmission->id)); ?>"
                                                class="btn btn-sm btn-outline-danger" data-toggle="tooltip"
                                                title="<?php echo e(trans('AdminSb.vital_signs_sheet')); ?>"><i
                                                    class="fas fa-heartbeat"></i></a>
                                        <?php endif; ?>
                                        <form action="<?php echo e(route('admin.Patients.destroy', $Patient->id)); ?>" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('<?php echo e(trans('AdminSb.confirm_delete_warning')); ?>\n<?php echo e(trans('AdminSb.confirm_delete_message_part1')); ?> <?php echo e($Patient->name); ?>.\n<?php echo e(trans('AdminSb.confirm_delete_message_part2')); ?>\n<?php echo e(trans('AdminSb.confirm_delete_message_part3')); ?>');">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                data-toggle="tooltip" data-placement="top"
                                                title="<?php echo e(trans('AdminSb.delete_patient')); ?>"><i
                                                    class="fas fa-trash-alt"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <?php if($Patients instanceof \Illuminate\Pagination\LengthAwarePaginator && $Patients->hasPages()): ?>
                    <div class="mt-3 p-3 d-flex justify-content-center pagination-container border-top">
                        <?php echo e($Patients->appends(request()->query())->links('pagination::bootstrap')); ?>

                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="no-patients-message">
                    <i class="fas fa-user-injured"></i>
                    <p><?php echo e(trans('AdminSb.no_patients_found_message')); ?></p>
                    <?php if(!request()->hasAny(['search_patient', 'gender_filter', 'blood_group_filter', 'admission_status_filter'])): ?>
                        <a href="<?php echo e(route('admin.Patients.create')); ?>" class="btn btn-primary"><i
                                class="fas fa-user-plus me-2"></i> <?php echo e(trans('AdminSb.add_new_patient_now')); ?></a>
                    <?php else: ?>
                        <a href="<?php echo e(route('admin.Patients.index')); ?>" class="btn btn-outline-secondary"><i
                                class="fas fa-eraser me-2"></i> <?php echo e(trans('AdminSb.clear_filters_and_view_all')); ?></a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <?php echo \Illuminate\View\Factory::parentPlaceholder('js'); ?>
    <script src="<?php echo e(URL::asset('dashboard/plugins/notify/js/notifIt.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('dashboard/plugins/notify/js/notifit-custom.js')); ?>"></script>
    
    
    
    
    
    <script src="<?php echo e(URL::asset('Dashboard/plugins/select2/js/select2.full.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('Dashboard/plugins/select2/js/i18n/ar.js')); ?>"></script> 

    <script>
        $(document).ready(function() {

            // تهيئة Select2
            $('.select2').select2({
                placeholder: $(this).data('placeholder') || trans('AdminSb.select'),
                width: '100%',
                dir: "rtl",
                theme: "bootstrap-5",
                allowClear: true,
                // dropdownParent: $(this).closest('.filter-card') // لضمان ظهور القائمة بشكل صحيح داخل بطاقة الفلتر
            });

            // تهيئة Tooltips من Bootstrap
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })


            // إظهار رسائل NotifIt
            const showNotif = (msg, type, position = 'top-right', autohide = true, timeout = 5000, multiline =
                false) => {
                notif({
                    msg,
                    type,
                    position,
                    autohide,
                    timeout,
                    multiline
                });
            };

            <?php if(session('add')): ?>
                showNotif("<i class='fas fa-check-circle me-2'></i> تمت إضافة المريض بنجاح!", "success");
            <?php endif; ?>
            <?php if(session('edit')): ?>
                showNotif("<i class='fas fa-check-circle me-2'></i> تم تعديل بيانات المريض بنجاح!", "success");
            <?php endif; ?>
            // <?php if(session('delete')): ?>
            //     showNotif("<i class='fas fa-check-circle me-2'></i> تم حذف المريض بنجاح.", "success",
            //         'bottom-center');
            // <?php endif; ?>
            <?php if(session('success')): ?>
                showNotif("<i class='fas fa-thumbs-up me-2'></i> <?php echo e(session('success')); ?>", "success");
            <?php endif; ?>
            <?php if(session('error')): ?>
                showNotif("<i class='fas fa-exclamation-triangle me-2'></i> <?php echo e(session('error')); ?>", "error",
                    'top-center', false);
            <?php endif; ?>
            <?php if(session('info')): ?>
                showNotif("<i class='fas fa-info-circle me-2'></i> <?php echo e(session('info')); ?>", "info");
            <?php endif; ?>
            <?php if(session('warning')): ?>
                showNotif("<i class='fas fa-exclamation-circle me-2'></i> <?php echo e(session('warning')); ?>", "warning",
                    'top-center', true, 7000);
            <?php endif; ?>

            <?php if($errors->any()): ?>
                let errorMsg =
                    "<strong><i class='fas fa-times-circle me-2'></i> حدث خطأ! يرجى مراجعة البيانات:</strong><ul class='mb-0 ps-3 mt-1' style='list-style-type: disc; padding-right: 15px;'>";
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    errorMsg += "<li><?php echo e($error); ?></li>";
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                errorMsg += "</ul>";
                showNotif(errorMsg, "error", 'top-center', false, 0,
                    true); // autohide false, timeout 0 for persistent
            <?php endif; ?>
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Dashboard.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/Patients/index.blade.php ENDPATH**/ ?>