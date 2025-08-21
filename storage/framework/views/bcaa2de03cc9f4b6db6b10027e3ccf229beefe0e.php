<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" dir="<?php echo e(LaravelLocalization::getCurrentLocaleDirection()); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<title><?php echo e($doctor->name ?? trans('patients.doctor_profile_title')); ?> - <?php echo e(config('app.name', trans('patients.medical_platform'))); ?></title>    <link href="<?php echo e(URL::asset('Dashboard/plugins/notify/css/notifIt.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(URL::asset('Dashboard/plugins/select2/css/select2.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(URL::asset('Dashboard/plugins/datatable/css/dataTables.bootstrap4.min.css')); ?>" rel="stylesheet" />
    <link href="<?php echo e(URL::asset('Dashboard/plugins/datatable/css/buttons.bootstrap4.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(URL::asset('Dashboard/plugins/datatable/css/responsive.bootstrap4.min.css')); ?>" rel="stylesheet" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&family=Cairo:wght@400;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --docprofile-primary-color: <?php echo e($settings['docprofile_primary_color'] ?? '#2979ff'); ?>;
            --docprofile-primary-color-rgb: <?php echo e(implode(',', sscanf($settings['docprofile_primary_color'] ?? '#2979ff', '#%02x%02x%02x')) ?? '41, 121, 255'); ?>;
            --docprofile-secondary-color: <?php echo e($settings['docprofile_secondary_color'] ?? '#4caf50'); ?>;
            --docprofile-text-color: #424753;
            --docprofile-heading-color: #222933;
            --docprofile-light-text: #757E8B;
            --docprofile-bg-color: #f7f9fc;
            --docprofile-card-bg: #ffffff;
            --docprofile-border-color: #e8eef3;
            --docprofile-radius: 10px;
            --docprofile-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
            --docprofile-font-primary: 'Tajawal', sans-serif;
            --docprofile-font-secondary: 'Cairo', sans-serif;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: var(--docprofile-font-primary);
            background-color: var(--docprofile-bg-color);
            color: var(--docprofile-text-color);
            line-height: 1.75;
            font-size: 16px;
        }

        *,
        *::before,
        *::after {
            box-sizing: inherit;
        }

        .doctor-profile-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2.5rem 1rem;
        }

        /* Minimalist Header for Standalone Page */
        .standalone-profile-header {
            background-color: var(--docprofile-card-bg);
            padding: 1rem 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 2.5rem;
        }

        .standalone-profile-header .container-fluid {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .standalone-profile-header .site-branding .site-title {
            font-family: var(--docprofile-font-secondary);
            font-size: 1.6rem;
            color: var(--docprofile-primary-color);
            margin: 0;
            font-weight: 700;
            text-decoration: none;
        }

        .standalone-profile-header .back-to-doctors a {
            color: var(--docprofile-primary-color);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
        }

        .standalone-profile-header .back-to-doctors a i {
            margin-inline-end: 0.3rem;
        }

        .standalone-profile-header .back-to-doctors a:hover {
            text-decoration: underline;
        }


        /* Doctor Profile Hero Section */
        .doctor-hero-section {
            background-color: var(--docprofile-card-bg);
            border-radius: var(--docprofile-radius);
            padding: 2.5rem;
            margin-bottom: 2.5rem;
            box-shadow: var(--docprofile-shadow);
            display: flex;
            flex-wrap: wrap;
            /* Allow wrapping on smaller screens */
            gap: 2rem;
            align-items: flex-start;
            /* Align items to the top */
        }

        .doctor-hero-section .profile-image-wrapper {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            overflow: hidden;
            border: 6px solid var(--docprofile-bg-color);
            /* Border matches page background */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            flex-shrink: 0;
            margin: 0 auto 1rem;
            /* Center on mobile */
        }

        @media (min-width: 768px) {
            .doctor-hero-section .profile-image-wrapper {
                margin: 0;
            }
        }

        .doctor-hero-section .profile-image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .doctor-hero-section .profile-info-details {
            flex-grow: 1;
        }

        .doctor-hero-section .profile-info-details h1 {
            font-family: var(--docprofile-font-secondary);
            font-size: 2.25rem;
            /* Larger name */
            color: var(--docprofile-heading-color);
            font-weight: 800;
            /* Bolder */
            margin-bottom: 0.5rem;
            line-height: 1.2;
        }

        .doctor-hero-section .profile-info-details .department-link,
        .doctor-hero-section .profile-info-details .department-link a {
            font-size: 1.1rem;
            color: var(--docprofile-primary-color);
            font-weight: 600;
            margin-bottom: 1rem;
            display: block;
            text-decoration: none;
        }

        .doctor-hero-section .profile-info-details .department-link a:hover {
            text-decoration: underline;
        }

        .doctor-hero-section .profile-info-details .department-link i {
            margin-inline-end: 0.5rem;
        }

        .doctor-hero-section .profile-actions-hero {
            margin-top: 1rem;
            width: 100%;
            /* Full width on small screens */
        }

        @media (min-width: 992px) {
            .doctor-hero-section .profile-actions-hero {
                margin-top: 0;
                margin-inline-start: auto;
                width: auto;
                flex-shrink: 0;
            }
        }

        .doctor-hero-section .btn-book-appointment-hero {
            background-color: var(--docprofile-secondary-color);
            color: white;
            border: none;
            padding: 0.8rem 1.75rem;
            font-size: 1rem;
            font-weight: 700;
            border-radius: var(--docprofile-radius);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.6rem;
            transition: background-color 0.25s ease, transform 0.2s ease;
        }

        .doctor-hero-section .btn-book-appointment-hero:hover {
            background-color: color-mix(in srgb, var(--docprofile-secondary-color) 85%, black);
            transform: translateY(-2px);
        }

        .doctor-hero-section .btn-book-appointment-hero i {
            font-size: 1.1em;
        }

        /* Custom Breadcrumb for this page */
        .profile-breadcrumb {
            padding: 0;
            margin: 1.5rem 0 0 0;
            list-style: none;
            display: flex;
            font-size: 0.9rem;
        }

        .profile-breadcrumb li a {
            color: var(--docprofile-primary-color);
            text-decoration: none;
        }

        .profile-breadcrumb li a:hover {
            text-decoration: underline;
        }

        .profile-breadcrumb li {
            color: var(--docprofile-light-text);
        }

        .profile-breadcrumb li+li::before {
            content: "/";
            padding: 0 0.5rem;
            color: var(--docprofile-light-text);
        }


        /* Main Content Area (Tabs & Sidebar) */
        .card-styled {
            background-color: var(--docprofile-card-bg);
            border-radius: var(--docprofile-radius);
            padding: 2rem;
            box-shadow: var(--docprofile-shadow);
            margin-bottom: 2rem;
            border: 1px solid var(--docprofile-border-color);
        }

        /* Modern Tabs for Profile Content */
        .doctor-profile-tabs .nav-tabs {
            border-bottom: 2px solid var(--docprofile-border-color);
            margin-bottom: 2rem;
        }

        .doctor-profile-tabs .nav-tabs .nav-item .nav-link {
            border: none;
            border-bottom: 3px solid transparent;
            padding: 0.8rem 1.5rem;
            font-size: 1.05rem;
            font-weight: 600;
            color: var(--docprofile-light-text);
            transition: all 0.25s ease;
            margin-bottom: -2px;
            /* Align with bottom border */
        }

        .doctor-profile-tabs .nav-tabs .nav-item .nav-link.active,
        .doctor-profile-tabs .nav-tabs .nav-item .nav-link:hover {
            color: var(--docprofile-primary-color);
            border-bottom-color: var(--docprofile-primary-color);
            background-color: transparent;
        }

        .doctor-profile-tabs .nav-tabs .nav-item .nav-link i {
            margin-inline-end: 0.6rem;
        }

        .doctor-profile-tabs .tab-content .tab-pane {
            animation: fadeInSmooth 0.5s;
        }

        @keyframes  fadeInSmooth {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .tab-content-section h4.section-title {
            font-family: var(--docprofile-font-secondary);
            font-size: 1.5rem;
            /* 24px */
            font-weight: 700;
            color: var(--docprofile-heading-color);
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid var(--docprofile-border-color);
            display: flex;
            align-items: center;
        }

        .tab-content-section h4.section-title i {
            color: var(--docprofile-primary-color);
            margin-inline-end: 0.75rem;
            font-size: 1.2em;
        }

        .doctor-bio-fulltext {
            font-size: 1rem;
            line-height: 1.8;
            color: var(--docprofile-text-color);
        }

        .contact-info-list-profile {
            list-style: none;
            padding: 0;
            margin-top: 1.5rem;
        }

        .contact-info-list-profile li {
            margin-bottom: 0.8rem;
            font-size: 1rem;
            display: flex;
            align-items: center;
        }

        .contact-info-list-profile li i {
            width: 22px;
            color: var(--docprofile-primary-color);
            margin-inline-end: 0.8rem;
            text-align: center;
        }

        .contact-info-list-profile li strong {
            font-weight: 600;
            color: var(--docprofile-heading-color);
            margin-inline-end: 0.4rem;
        }

        .contact-info-list-profile li a {
            color: var(--docprofile-primary-color);
            text-decoration: none;
        }

        .contact-info-list-profile li a:hover {
            text-decoration: underline;
        }

        .schedule-table-profile {
            border: 1px solid var(--docprofile-border-color);
            font-size: 0.95rem;
        }

        .schedule-table-profile th {
            background-color: #f3f6f9;
            color: var(--docprofile-heading-color);
            font-weight: 600;
            padding: 0.75rem 1rem;
        }

        .schedule-table-profile td {
            padding: 0.75rem 1rem;
            vertical-align: middle;
        }

        .schedule-table-profile .break-list-profile {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .schedule-table-profile .break-list-profile li {
            font-size: 0.85em;
            margin-bottom: 0.2rem;
            color: var(--docprofile-light-text);
        }

        .schedule-table-profile .break-list-profile i {
            margin-inline-end: 0.3rem;
        }

        .schedule-notice-profile {
            color: var(--docprofile-light-text);
            font-size: 0.85rem;
            margin-top: 1rem;
        }

        .schedule-notice-profile i {
            margin-inline-end: 0.3rem;
        }

        .empty-state-profile {
            text-align: center;
            padding: 1.5rem;
            background-color: #f9fbfd;
            border-radius: var(--docprofile-radius);
            color: var(--docprofile-light-text);
            border: 1px dashed var(--docprofile-border-color);
        }

        .empty-state-profile i {
            display: block;
            font-size: 2rem;
            margin-bottom: 0.75rem;
            color: var(--docprofile-primary-color);
            opacity: 0.7;
        }

        /* Sidebar Widgets */
        .doctor-profile-sidebar .sidebar-widget {
            margin-bottom: 2rem;
        }

        .doctor-profile-sidebar .widget-title-sidebar {
            font-family: var(--docprofile-font-secondary);
            font-size: 1.25rem;
            /* 20px */
            font-weight: 700;
            color: var(--docprofile-heading-color);
            margin-bottom: 1.25rem;
            padding-bottom: 0.6rem;
            border-bottom: 2px solid var(--docprofile-primary-color);
            display: inline-block;
            /* To make border only under text */
        }

        .doctor-profile-sidebar .widget-title-sidebar i {
            margin-inline-end: 0.6rem;
        }

        .working-hours-summary-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .working-hours-summary-list li {
            display: flex;
            justify-content: space-between;
            padding: 0.6rem 0;
            font-size: 0.95rem;
            border-bottom: 1px dashed var(--docprofile-border-color);
        }

        .working-hours-summary-list li:last-child {
            border-bottom: none;
        }

        .working-hours-summary-list li .day-name {
            font-weight: 600;
            color: var(--docprofile-heading-color);
        }

        .working-hours-summary-list li .day-time {
            color: var(--docprofile-text-color);
        }

        .working-hours-summary-list li.is-today .day-name,
        .working-hours-summary-list li.is-today .day-time {
            color: var(--docprofile-secondary-color);
            font-weight: bold;
        }

        .department-info-widget-profile h5 a {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--docprofile-heading-color);
            margin-bottom: 0.5rem;
            display: block;
            text-decoration: none;
        }

        .department-info-widget-profile h5 a:hover {
            color: var(--docprofile-primary-color);
        }

        .department-info-widget-profile p {
            font-size: 0.9rem;
            line-height: 1.7;
            color: var(--docprofile-light-text);
            margin-bottom: 1rem;
        }

        .department-info-widget-profile .read-more-profile-link {
            color: var(--docprofile-primary-color);
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
        }

        .department-info-widget-profile .read-more-profile-link i {
            margin-inline-start: 0.3rem;
        }

        /* For RTL arrow */
        .department-info-widget-profile .read-more-profile-link:hover {
            text-decoration: underline;
        }

        /* Related Doctors Section */
        .related-doctors-section-profile {
            margin-top: 3rem;
            padding-top: 2.5rem;
            border-top: 1px solid var(--docprofile-border-color);
        }

        .related-doctors-section-profile .section-title-centered {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .related-doctors-section-profile .section-title-centered h2 {
            font-family: var(--docprofile-font-secondary);
            font-size: 1.8rem;
            color: var(--docprofile-heading-color);
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .related-doctors-section-profile .section-title-centered .separator-line {
            display: block;
            width: 70px;
            height: 3px;
            background-color: var(--docprofile-primary-color);
            margin: 0 auto;
        }

        /* Re-use doctor card style from previous example, just ensure class names match if copied */
        /* .doctor-card-standalone (styles for related doctors can be same as main list) */


        /* Minimalist Footer for Standalone Page */
        .standalone-profile-footer {
            text-align: center;
            padding: 1.5rem 0;
            margin-top: 3rem;
            background-color: var(--docprofile-card-bg);
            border-top: 1px solid var(--docprofile-border-color);
            font-size: 0.9rem;
            color: var(--docprofile-light-text);
        }

        .doctor-card-standalone {
            background-color: var(--docpage-card-bg);
            border-radius: var(--docpage-radius);
            box-shadow: var(--docpage-shadow);
            transition: var(--docpage-transition);
            overflow: hidden;
            text-align: center;
            display: flex;
            flex-direction: column;
            border: 1px solid var(--docpage-border-color);
            position: relative;
        }

        .doctor-card-standalone:hover {
            transform: translateY(-5px);
            box-shadow: var(--docpage-shadow-hover);
        }

        .image-wrapper-standalone {
            position: relative;
            background-color: #f1f5f9;
            height: 250px;
            overflow: hidden;
        }

        .image-wrapper-standalone img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center top;
            display: block;
        }

        .card-badges {
            position: absolute;
            top: 10px;
            right: 10px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            align-items: flex-end;
        }

        .rtl .card-badges {
            right: auto;
            left: 10px;
            align-items: flex-start;
        }

        .availability-badge-standalone {
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.75rem;
            color: white;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .status-available {
            background-color: var(--docpage-secondary-color);
        }

        .status-unavailable {
            background-color: #94a3b8;
        }

        .featured-badge {
            background-color: var(--docpage-accent-color);
            color: #4a3500;
        }

        .info-content-standalone {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .doctor-name-standalone a {
            font-family: var(--docpage-font-secondary);
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--docpage-heading-color);
            margin-bottom: 8px;
            display: block;
            text-decoration: none;
            transition: var(--docpage-transition);
        }

        .doctor-name-standalone a:hover {
            color: var(--docpage-primary-color);
        }

        .doctor-title {
            color: #64748b;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }

        .doctor-department-standalone,
        .doctor-department-standalone a {
            font-size: 0.95rem;
            color: var(--docpage-primary-color);
            font-weight: 500;
            margin-bottom: 12px;
            display: block;
            text-decoration: none;
        }

        .doctor-department-standalone a:hover {
            text-decoration: underline;
        }

        .doctor-department-standalone i {
            margin-inline-end: 6px;
        }

        .doctor-meta {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 15px;
            color: #64748b;
            font-size: 0.85rem;
        }

        .doctor-meta-item {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .doctor-rating {
            display: flex;
            justify-content: center;
            gap: 3px;
            margin: 10px 0;
        }

        .doctor-rating i {
            color: var(--docpage-accent-color);
            font-size: 0.9rem;
        }

        .rating-count {
            font-size: 0.8rem;
            color: #64748b;
        }

        .action-footer-standalone {
            padding: 0 20px 20px;
            margin-top: auto;
        }

        .action-footer-standalone .btn {
            width: 100%;
            font-weight: 500;
        }

        .action-footer-standalone .btn-outline-primary {
            border-color: var(--docpage-primary-color);
            color: var(--docpage-primary-color);
        }

        .action-footer-standalone .btn-outline-primary:hover {
            background-color: var(--docpage-primary-color);
            color: var(--docpage-card-bg);
        }

        .view-profile-btn {
            position: relative;
            overflow: hidden;
        }

        .view-profile-btn::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: var(--docpage-transition);
        }

        .view-profile-btn:hover::after {
            left: 100%;
        }

        .rating-filter {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .star-rating {
            display: flex;
            gap: 3px;
        }

        .star-rating i {
            color: #e2e8f0;
            cursor: pointer;
            transition: var(--docpage-transition);
        }

        .star-rating i.active {
            color: var(--docpage-accent-color);
        }

        .reset-filters {
            color: var(--docpage-primary-color);
            text-decoration: none;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-top: 10px;
        }

        .reset-filters:hover {
            text-decoration: underline;
        }

        /* Doctors Grid */
        .doctors-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
    </style>
</head>

<body>

    <header class="standalone-profile-header">
        <div class="container-fluid">
            <div class="site-branding">
                <a href="<?php echo e(route('home')); ?>" class="site-title" title="<?php echo e(trans('patients.back_to_home_title', ['appName' => config('app.name')])); ?>">
                    <?php echo e(config('app.name', trans('patients.medical_platform'))); ?>

                </a>
            </div>
            <div class="back-to-doctors">
                <a href="<?php echo e(route('website.doctors.all')); ?>"><i class="fas fa-users"></i> <?php echo e(trans('patients.view_all_doctors')); ?></a>
            </div>
        </div>
    </header>

    <main class="doctor-profile-container">
        <!-- Doctor Hero Section -->
        <section class="doctor-hero-section animate__animated animate__fadeIn">
            <div class="profile-image-wrapper">
                <?php if($doctor->image): ?>
                    <img src="<?php echo e(Url::asset('Dashboard/img/doctors/' . $doctor->image->filename)); ?>"
                        class="doctor-avatar" alt="<?php echo e(trans('doctors.img')); ?>">
                <?php else: ?>
                    <img src="<?php echo e(Url::asset('Dashboard/img/doctor_default.png')); ?>" class="doctor-avatar"
                        alt="<?php echo e(trans('patients.default_image_alt')); ?>">
                <?php endif; ?>
            </div>
            <div class="profile-info-details">
                <h1><?php echo e($doctor->name); ?></h1>
                <?php if($doctor->section): ?>
                    <p class="department-link">
                        <i class="fas fa-hospital-symbol"></i>
                        <a href="<?php echo e(route('website.department.details', $doctor->section->id)); ?>"
                            title="<?php echo e(trans('patients.more_about_department', ['name' => $doctor->section->name])); ?>">
                            <?php echo e($doctor->section->name); ?>

                        </a>
                    </p>
                <?php endif; ?>
                

                <ul class="profile-breadcrumb">
                    <li><a href="<?php echo e(route('home')); ?>"><?php echo e(trans('patients.nav_home')); ?></a></li>
                    <li><a href="<?php echo e(route('website.doctors.all')); ?>"><?php echo e(trans('patients.nav_doctors')); ?></a></li>
                    <li><?php echo e($doctor->name); ?></li>
                </ul>
            </div>

        </section>

        <!-- Main Content (Tabs and Sidebar) -->
        <div class="row g-4">
            <!-- Content Side -->
            <div class="col-lg-8">
                <div class="doctor-profile-tabs card-styled animate__animated animate__fadeInUp" data-wow-delay="0.2s">
                    <ul class="nav nav-tabs" id="doctorProfileTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="bio-tab" data-bs-toggle="tab"
                                data-bs-target="#tab-bio-content" type="button" role="tab"
                                aria-controls="tab-bio-content" aria-selected="true">
                                <i class="fas fa-user-tie"></i> <?php echo e(trans('patients.biography')); ?>

                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="schedule-tab" data-bs-toggle="tab"
                                data-bs-target="#tab-schedule-content" type="button" role="tab"
                                aria-controls="tab-schedule-content" aria-selected="false">
                                <i class="fas fa-calendar-alt"></i> <?php echo e(trans('patients.work_schedule')); ?>

                            </button>
                        </li>
                    </ul>
                    <div class="tab-content" id="doctorProfileTabContent">
                        <!-- Tab: Bio -->
                        <div class="tab-pane fade show active" id="tab-bio-content" role="tabpanel"
                            aria-labelledby="bio-tab">
                            <div class="tab-content-section">
                                <h4 class="section-title"><i class="fas fa-info-circle"></i> <?php echo e(trans('patients.about_the_doctor')); ?></h4>
                                <div class="doctor-bio-fulltext">
                                    <?php if($doctor->bio): ?>
                                        <?php echo nl2br(e($doctor->bio)); ?>

                                    <?php else: ?>
                                        <p class="empty-state-profile"><i class="fas fa-feather-alt"></i> <?php echo e(trans('patients.no_biography_available')); ?></p>
                                    <?php endif; ?>
                                </div>

                                <?php if($doctor->phone || $doctor->email): ?>
                                    <h4 class="section-title mt-4"><i class="fas fa-address-book"></i> <?php echo e(trans('patients.contact_information')); ?>

                                    </h4>
                                    <ul class="contact-info-list-profile">
                                        <?php if($doctor->phone): ?>
                                            <li><i class="fas fa-phone-alt"></i> <strong><?php echo e(trans('patients.phone_label')); ?></strong> <a
                                                    href="tel:<?php echo e($doctor->phone); ?>"><?php echo e($doctor->phone); ?></a></li>
                                        <?php endif; ?>
                                        <?php if($doctor->email): ?>
                                            <li><i class="fas fa-envelope"></i> <strong><?php echo e(trans('patients.email_label')); ?></strong> <a
                                                    href="mailto:<?php echo e($doctor->email); ?>"><?php echo e($doctor->email); ?></a></li>
                                        <?php endif; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                        <!-- Tab: Schedule -->
                        <div class="tab-pane fade" id="tab-schedule-content" role="tabpanel"
                            aria-labelledby="schedule-tab">
                            <div class="tab-content-section">
                                <h4 class="section-title"><i class="fas fa-user-clock"></i> <?php echo e(trans('patients.doctor_work_schedule')); ?></h4>
                                <?php if(collect($scheduleData)->where('active', true)->isNotEmpty()): ?>
                                    <div class="table-responsive">
                                        <table class="table table-striped table-hover schedule-table-profile">
                                            <thead class="table-light">
                                                <tr>
                                                    <th><?php echo e(trans('patients.day_col')); ?></th>
                                                    <th><?php echo e(trans('patients.start_time_col')); ?></th>
                                                    <th><?php echo e(trans('patients.end_time_col')); ?></th>
                                                    <th><?php echo e(trans('patients.consultation_duration_min')); ?></th>
                                                    <th><?php echo e(trans('patients.breaks_col')); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $__currentLoopData = $scheduleData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($data['active']): ?>
                                                        <tr>
                                                            <td><?php echo e(trans('doctors.days.' . strtolower($day)) ?? $day); ?>

                                                            </td>
                                                            <td><?php echo e($data['start_time']); ?></td>
                                                            <td><?php echo e($data['end_time']); ?></td>
                                                            <td><?php echo e($data['appointment_duration'] ?? '-'); ?></td>
                                                            <td>
                                                                <?php if($data['breaks']->isNotEmpty()): ?>
                                                                    <ul class="list-unstyled mb-0 break-list-profile">
                                                                        <?php $__currentLoopData = $data['breaks']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $break): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <li><i class="far fa-pause-circle"></i>
                                                                                <?php echo e($break['start_time']); ?> -
                                                                                <?php echo e($break['end_time']); ?>

                                                                                <?php if($break['reason']): ?>
                                                                                    <small>(<?php echo e($break['reason']); ?>)</small>
                                                                                <?php endif; ?>
                                                                            </li>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    </ul>
                                                                <?php else: ?>
                                                                    <span><?php echo e(trans('patients.no_breaks')); ?></span>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <p class="schedule-notice-profile mt-3"><i class="fas fa-exclamation-circle"></i>
                                        <?php echo e(trans('patients.schedule_notice')); ?>

                                    </p>
                                <?php else: ?>
                                    <p class="empty-state-profile"><i class="fas fa-calendar-times"></i> <?php echo e(trans('patients.no_detailed_schedule_available')); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- End Content Side -->

            <!-- Sidebar Side -->
            <div class="col-lg-4">
                <aside class="doctor-profile-sidebar">
                    <div class="sidebar-widget card-styled working-hours-widget animate__animated animate__fadeInUp"
                        data-wow-delay="0.3s">
                        <h5 class="widget-title-sidebar"><i class="far fa-clock"></i> <?php echo e(trans('patients.available_working_hours')); ?></h5>
                        <ul class="working-hours-summary-list">
                            <?php $activeDaysFound = false; ?>
                            <?php $__empty_1 = true; $__currentLoopData = $scheduleData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php if($data['active']): ?>
                                    <?php $activeDaysFound = true; ?>
                                    <li
                                        class="<?php echo e(\Carbon\Carbon::now()->englishDayOfWeek == $day ? 'is-today' : ''); ?>">
                                        <span class="day-name"><?php echo e(trans('doctors.days.' . strtolower($day)) ?? $day); ?></span>
                                        <span class="day-time"><?php echo e($data['start_time']); ?> -
                                            <?php echo e($data['end_time']); ?></span>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <li><?php echo e(trans('patients.no_scheduled_work_days')); ?></li>
                            <?php endif; ?>
                            <?php if(!$activeDaysFound && !empty($scheduleData)): ?>
                                <li><span class="text-warning fw-bold"><?php echo e(trans('patients.doctor_on_leave')); ?></span></li>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <?php if($doctor->section): ?>
                        <div class="sidebar-widget card-styled department-info-widget-profile animate__animated animate__fadeInUp"
                            data-wow-delay="0.4s">
                            <h5 class="widget-title-sidebar"><i class="fas fa-building"></i> <?php echo e(trans('patients.about_the_department')); ?></h5>
                            <h5><a
                                    href="<?php echo e(route('website.department.details', $doctor->section->id)); ?>"><?php echo e($doctor->section->name); ?></a>
                            </h5>
                            <p><?php echo e(Str::limit(strip_tags($doctor->section->description ?? trans('patients.more_info_about_department_soon')), 150)); ?>

                            </p>
                            <a href="<?php echo e(route('website.department.details', $doctor->section->id)); ?>"
                                class="read-more-profile-link"><?php echo e(trans('patients.read_more_about_department')); ?> <i
                                    class="fas fa-arrow-left"></i></a>
                        </div>
                    <?php endif; ?>
                </aside>
            </div> <!-- End Sidebar Side -->
        </div> <!-- End Row -->

        <?php if($relatedDoctors->isNotEmpty()): ?>
            <section class="related-doctors-section-profile animate__animated animate__fadeInUp"
                data-wow-delay="0.5s">
                <div class="section-title-centered">
                    <h2><?php echo e(trans('patients.similar_doctors_in_specialty')); ?></h2>
                    <div class="separator-line"></div>
                </div>
                <div class="doctors-grid">
                    <?php $__currentLoopData = $relatedDoctors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rdoctor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="doctor-card-standalone animate__animated animate__fadeInUp"
                            data-wow-delay="<?php echo e(($loop->index % 6) * 0.1 + 0.2); ?>s">
                            <div class="image-wrapper-standalone">
                                <a href="<?php echo e(route('website.doctor.details', $rdoctor->id)); ?>">
                                    <img src="<?php echo e($rdoctor->image_url ?? ($rdoctor->image && $rdoctor->image->filename ? asset('Dashboard/img/doctors/' . $rdoctor->image->filename) : asset('WebSite/images/resource/doctor-placeholder.png'))); ?>"
                                        alt="<?php echo e($rdoctor->name); ?>"
                                        onerror="this.onerror=null; this.src='<?php echo e(asset('Dashboard/img/doctor_default.png')); ?>';">
                                </a>

                                <div class="card-badges">
                                    <span
                                        class="availability-badge-standalone <?php echo e($rdoctor->status ? 'status-available' : 'status-unavailable'); ?>">
                                        <i
                                            class="fas fa-<?php echo e($rdoctor->status ? 'check-circle' : 'times-circle'); ?>"></i>
                                        <?php echo e($rdoctor->status ? trans('patients.available') : trans('patients.not_available')); ?>

                                    </span>

                                    <?php if($rdoctor->is_featured): ?>
                                        <span class="availability-badge-standalone featured-badge">
                                            <i class="fas fa-star"></i> <?php echo e(trans('patients.featured_badge')); ?>

                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="info-content-standalone">
                                <h4 class="doctor-name-standalone">
                                    <a
                                        href="<?php echo e(route('website.doctor.details', $rdoctor->id)); ?>"><?php echo e($rdoctor->name); ?></a>
                                </h4>

                                <?php if($rdoctor->title): ?>
                                    <p class="doctor-title"><?php echo e($rdoctor->title); ?></p>
                                <?php endif; ?>

                                <?php if($rdoctor->section): ?>
                                    <p class="doctor-department-standalone">
                                        <a href="<?php echo e(route('website.department.details', $rdoctor->section->id)); ?>">
                                            <i class="fas fa-stethoscope"></i>
                                            <?php echo e($rdoctor->section->name); ?>

                                        </a>
                                    </p>
                                <?php endif; ?>
                            </div>

                            <div class="action-footer-standalone">
                                <a href="<?php echo e(route('website.doctor.details', $rdoctor->id)); ?>"
                                    class="btn btn-outline-primary view-profile-btn">
                                    <i class="fas fa-id-badge"></i> <?php echo e(trans('patients.view_profile_button')); ?>

                                </a>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </section>
        <?php endif; ?>
    </main>

    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
</body>

</html>
<?php /**PATH H:\Hospital_Sys\resources\views/WebSite/doctors/doctor_details.blade.php ENDPATH**/ ?>