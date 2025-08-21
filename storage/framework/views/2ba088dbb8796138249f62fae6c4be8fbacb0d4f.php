<?php $__env->startSection('css'); ?>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <!-- Flatpickr for time handling -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />

    <style>
        :root {
            --primary-color: #4A90E2;
            --primary-dark: #3a7bd5;
            --secondary-color: #4A4A4A;
            --accent-color: #50E3C2;
            --light-bg: #f9fbfd;
            --border-color: #e5e9f2;
            --white-color: #ffffff;
            --success-color: #2ecc71;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --info-color: #3498db;
            --text-dark: #34495e;
            --text-muted: #95a5a6;
            --card-shadow: 0 8px 25px rgba(140, 152, 164, 0.1);
            --primary-gradient: linear-gradient(135deg, #4A90E2 0%, #3a7bd5 100%);
            --success-gradient: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
        }

        body {
            background: var(--light-bg);
            font-family: 'Cairo', sans-serif;
        }

        .schedule-view-container {
            background: var(--white-color);
            border-radius: 16px;
            box-shadow: var(--card-shadow);
            padding: 2rem 2.5rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
        }

        .schedule-view-container::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 5px;
            background: var(--primary-gradient);
        }

        .schedule-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        .schedule-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--secondary-color);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            position: relative;
        }

        .schedule-title::after {
            content: '';
            position: absolute;
            bottom: -15px;
            right: 0;
            width: 50px;
            height: 3px;
            background: var(--primary-color);
        }

        .schedule-title i {
            color: var(--primary-color);
            font-size: 1.5rem;
        }

        .doctor-info-card {
            background: rgba(74, 144, 226, 0.1);
            border-radius: 12px;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            border: 1px solid rgba(74, 144, 226, 0.2);
        }

        .doctor-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--white-color);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .doctor-details h5 {
            margin: 0;
            font-weight: 700;
            color: var(--secondary-color);
        }

        .doctor-specialty {
            color: var(--primary-color);
            font-size: 0.85rem;
            font-weight: 600;
        }

        /* Weekly Schedule Table */
        .weekly-schedule-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 12px;
            margin-top: 1.5rem;
        }

        .weekly-schedule-table thead th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            font-size: 0.95rem;
            border: none;
            padding: 1rem 1.25rem;
            position: relative;
            text-align: center;
        }

        .weekly-schedule-table thead th:first-child {
            border-radius: 0 12px 12px 0;
            text-align: right;
        }

        .weekly-schedule-table thead th:last-child {
            border-radius: 12px 0 0 12px;
        }

        .weekly-schedule-table tbody tr {
            background-color: var(--white-color);
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            position: relative;
        }

        .weekly-schedule-table tbody tr:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            z-index: 10;
        }

        .weekly-schedule-table tbody td {
            border: 1px solid #f0f3f8;
            border-width: 1px 0;
            color: var(--text-dark);
            font-size: 0.95rem;
            padding: 1.25rem 1.5rem;
            vertical-align: middle;
        }

        .weekly-schedule-table tbody td:first-child {
            border-right: 1px solid #f0f3f8;
            border-radius: 0 12px 12px 0;
        }

        .weekly-schedule-table tbody td:last-child {
            border-left: 1px solid #f0f3f8;
            border-radius: 12px 0 0 12px;
        }

        .day-cell {
            display: flex;
            align-items: center;
            gap: 1rem;
            font-weight: 600;
            color: var(--secondary-color);
        }

        .day-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(74, 144, 226, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-size: 1.1rem;
        }

        .time-cell {
            font-weight: 500;
            direction: ltr;
            text-align: center;
        }

        .duration-cell {
            text-align: center;
        }

        .status-cell {
            text-align: center;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .status-active {
            background: rgba(46, 204, 113, 0.1);
            color: #1d643b;
        }

        .status-inactive {
            background: rgba(108, 117, 125, 0.1);
            color: #5a6268;
        }

        .breaks-container {
            max-height: 120px;
            overflow-y: auto;
            padding-right: 10px;
        }

        .breaks-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .break-item {
            background: rgba(231, 76, 60, 0.05);
            border-radius: 6px;
            padding: 0.5rem 0.75rem;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-left: 3px solid var(--danger-color);
        }

        .break-time {
            font-size: 0.85rem;
            font-weight: 500;
            color: var(--danger-color);
        }

        .break-reason {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-top: 0.2rem;
        }

        .no-breaks {
            color: var(--text-muted);
            font-size: 0.85rem;
            text-align: center;
            padding: 0.5rem;
        }

        .day-off {
            background: rgba(241, 243, 245, 0.5);
            color: var(--text-muted);
        }

        .day-off .day-cell {
            color: var(--text-muted);
        }

        .day-off .day-icon {
            background: rgba(149, 165, 166, 0.1);
            color: var(--text-muted);
        }

        /* Summary Card */
        .schedule-summary-card {
            background: var(--white-color);
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            padding: 1.5rem;
            margin-bottom: 2rem;
            border-top: 4px solid var(--primary-color);
        }

        .summary-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .summary-title i {
            color: var(--primary-color);
        }

        .summary-stats {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .stat-item {
            flex: 1;
            min-width: 150px;
            background: rgba(74, 144, 226, 0.05);
            border-radius: 8px;
            padding: 1rem;
            text-align: center;
            border: 1px solid rgba(74, 144, 226, 0.1);
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 2.5rem;
            flex-wrap: wrap;
        }

        .btn-schedule-action {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .btn-edit {
            background: var(--primary-gradient);
            color: white;
        }

        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(74, 144, 226, 0.3);
            background: var(--primary-dark);
        }

        .btn-print {
            background: var(--white-color);
            color: var(--secondary-color);
            border: 1px solid var(--border-color);
        }

        .btn-print:hover {
            background: #f8f9fa;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-share {
            background: var(--success-gradient);
            color: white;
        }

        .btn-share:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(46, 204, 113, 0.3);
        }

        /* Responsive Adjustments */
        @media (max-width: 992px) {
            .schedule-view-container {
                padding: 1.5rem;
            }

            .weekly-schedule-table thead {
                display: none;
            }

            .weekly-schedule-table tbody tr {
                display: block;
                margin-bottom: 1.5rem;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            }

            .weekly-schedule-table tbody td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                text-align: left !important;
                padding: 0.75rem 1rem;
                border: none;
                border-bottom: 1px solid #f0f3f8;
            }

            .weekly-schedule-table tbody td::before {
                content: attr(data-label);
                font-weight: 600;
                color: var(--primary-color);
                margin-left: 1rem;
            }

            .weekly-schedule-table tbody td:first-child,
            .weekly-schedule-table tbody td:last-child {
                border-radius: 0;
            }

            .day-cell {
                justify-content: space-between;
                width: 100%;
            }

            .stat-item {
                min-width: 120px;
            }
        }

        /* Animation for empty state */
        @keyframes  pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        .empty-state {
            animation: pulse 2s infinite;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('title'); ?>
    جدول عمل الطبيب - <?php echo e($doctor->name); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header'); ?>
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto"><?php echo e(trans('doctors.doctor_dashboard')); ?></h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ <a href="<?php echo e(route('doctor.profile.show')); ?>"><?php echo e(trans('doctors.my_profile')); ?></a></span>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ <?php echo e(trans('doctors.view_work_schedule')); ?></span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <a href="<?php echo e(route('doctor.schedule.edit', $doctor->id)); ?>" class="btn btn-primary-gradient btn-sm">
                <i class="fas fa-edit me-1"></i> <?php echo e(trans('doctors.edit_work_schedule')); ?>

            </a>
            <a href="<?php echo e(route('doctor.profile.show')); ?>" class="btn btn-outline-secondary btn-sm mr-2">
                <i class="fas fa-user me-1"></i> <?php echo e(trans('doctors.return_to_profile')); ?>

            </a>
        </div>
    </div>
    <!-- breadcrumb -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('Dashboard.messages_alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="row justify-content-center">
        <div class="col-lg-10 col-md-12">
            <!-- Doctor Info Card -->
            <div class="doctor-info-card mb-4 animate__animated animate__fadeIn">
                <div class="user-avatar-wrapper">
                    <img alt="user-img" class="avatar avatar-lg rounded-circle user-avatar" src="<?php echo e(Auth::guard('doctor')->user()->image ? asset('Dashboard/img/doctors/' . Auth::guard('doctor')->user()->image->filename) : asset('Dashboard/img/doctor_default.png')); ?>">
                    <span class="avatar-status-indicator <?php echo e(Auth::guard('doctor')->user()->status ? 'online' : 'offline'); ?>"></span>
                </div>
                <div class="doctor-details">
                    <h5><?php echo e(trans('doctors.dr_prefix')); ?> <?php echo e($doctor->name); ?></h5>
                    <p class="doctor-specialty mb-0"><i class="fas fa-stethoscope me-1"></i><?php echo e($doctor->section->name ?? trans('doctors.unspecified_specialty')); ?></p>
                </div>
            </div>

            <!-- Schedule Summary -->
            <?php if($workingDaysCollection && $workingDaysCollection->isNotEmpty()): ?>
                <?php
                    $workingDaysCount = $workingDaysCollection->where('active', true)->count();
                    $totalBreaks = $workingDaysCollection->sum(fn($day) => $day->breaks->count());
                    $averageDuration = $workingDaysCollection->where('active', true)->avg('appointment_duration');
                ?>
                <div class="schedule-summary-card animate__animated animate__fadeIn">
                    <h4 class="summary-title"><i class="fas fa-chart-pie"></i><?php echo e(trans('doctors.work_schedule_summary')); ?></h4>
                    <div class="summary-stats">
                        <div class="stat-item">
                            <div class="stat-value"><?php echo e($workingDaysCount); ?></div>
                            <div class="stat-label"><?php echo e(trans('doctors.working_days_per_week')); ?></div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value"><?php echo e($totalBreaks); ?></div>
                            <div class="stat-label"><?php echo e(trans('doctors.break_periods')); ?></div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value"><?php echo e(round($averageDuration)); ?> <?php echo e(trans('doctors.minute_short')); ?></div>
                            <div class="stat-label"><?php echo e(trans('doctors.average_appointment_duration')); ?></div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Main Schedule Table -->
            <div class="schedule-view-container animate__animated animate__fadeIn">
                <div class="schedule-header">
                    <h2 class="schedule-title"><i class="fas fa-calendar-week"></i><?php echo e(trans('doctors.weekly_working_hours_schedule')); ?></h2>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-primary me-2" style="color: white"><?php echo e(trans('doctors.current')); ?></span>
                        <small class="text-muted"><?php echo e(trans('doctors.last_updated_colon')); ?> <?php echo e(now()->format('Y-m-d')); ?></small>
                    </div>
                </div>

                <?php if($workingDaysCollection && $workingDaysCollection->isNotEmpty()): ?>
                    <div class="table-responsive">
                        <table class="table weekly-schedule-table">
                            <thead>
                                <tr>
                                    <th class="text-center" style="color: white"><?php echo e(trans('doctors.day')); ?></th>
                                    <th class="text-center" style="color: white"><?php echo e(trans('doctors.start_time')); ?></th>
                                    <th class="text-center" style="color: white"><?php echo e(trans('doctors.end_time')); ?></th>
                                    <th class="text-center" style="color: white"><?php echo e(trans('doctors.appointment_duration')); ?></th>
                                    <th class="text-center" style="color: white"><?php echo e(trans('doctors.status')); ?></th>
                                    <th class="text-center" style="color: white"><?php echo e(trans('doctors.break_periods')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $daysOrder = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
                                    $dayIcons = ['saturday' => 'fa-star-of-david', 'sunday' => 'fa-sun', 'monday' => 'fa-moon', 'tuesday' => 'fa-calendar-day', 'wednesday' => 'fa-calendar-day', 'thursday' => 'fa-calendar-week', 'friday' => 'fa-mosque'];
                                ?>
                                <?php $__currentLoopData = $daysOrder; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dayName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $workingDay = $workingDaysCollection->first(fn($item) => strcasecmp($item->day, $dayName) === 0);
                                        $isToday = strtolower($dayName) === strtolower(now()->englishDayOfWeek);
                                    ?>
                                    <tr class="animate__animated animate__fadeInUp <?php if(!$workingDay || !$workingDay->active): ?> day-off <?php endif; ?> <?php if($isToday): ?> border-start border-3 border-primary <?php endif; ?>" style="animation-delay: <?php echo e($loop->index * 0.05); ?>s;">
                                        <td class="day-cell" data-label="<?php echo e(trans('doctors.day')); ?>">
                                            <div class="day-icon"><i class="fas <?php echo e($dayIcons[strtolower($dayName)] ?? 'fa-calendar'); ?>"></i></div>
                                            <div>
                                                <div><?php echo e(trans('doctors.days.' . strtolower($dayName)) ?? $dayName); ?></div>
                                                <?php if($isToday): ?> <small class="text-primary">(<?php echo e(trans('doctors.today')); ?>)</small> <?php endif; ?>
                                            </div>
                                        </td>
                                        <?php if($workingDay && $workingDay->active): ?>
                                            <td class="time-cell" data-label="<?php echo e(trans('doctors.start_time')); ?>"><span class="badge bg-light text-dark"><i class="fas fa-clock me-1"></i><?php echo e(\Carbon\Carbon::parse($workingDay->start_time)->format('h:i A')); ?></span></td>
                                            <td class="time-cell" data-label="<?php echo e(trans('doctors.end_time')); ?>"><span class="badge bg-light text-dark"><i class="fas fa-clock me-1"></i><?php echo e(\Carbon\Carbon::parse($workingDay->end_time)->format('h:i A')); ?></span></td>
                                            <td class="duration-cell" data-label="<?php echo e(trans('doctors.appointment_duration')); ?>"><span class="badge bg-primary-light text-primary"><?php echo e($workingDay->appointment_duration); ?> <?php echo e(trans('doctors.minute_short')); ?></span></td>
                                            <td class="status-cell" data-label="<?php echo e(trans('doctors.status')); ?>"><span class="status-badge status-active"><i class="fas fa-check-circle"></i><?php echo e(trans('doctors.active')); ?></span></td>
                                            <td data-label="<?php echo e(trans('doctors.break_periods')); ?>">
                                                <?php if($workingDay->breaks->isNotEmpty()): ?>
                                                    <div class="breaks-container">
                                                        <?php $__currentLoopData = $workingDay->breaks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $break): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <div class="break-item">
                                                                <div>
                                                                    <div class="break-time"><i class="fas fa-coffee me-1"></i><?php echo e(\Carbon\Carbon::parse($break->start_time)->format('h:i A')); ?> - <?php echo e(\Carbon\Carbon::parse($break->end_time)->format('h:i A')); ?></div>
                                                                    <?php if($break->reason): ?> <div class="break-reason"><i class="fas fa-info-circle me-1"></i><?php echo e($break->reason); ?></div> <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="no-breaks"><i class="fas fa-check text-success me-1"></i><?php echo e(trans('doctors.no_breaks')); ?></div>
                                                <?php endif; ?>
                                            </td>
                                        <?php else: ?>
                                            <td colspan="5" class="text-center text-muted" data-label="<?php echo e(trans('doctors.status')); ?>"><span class="status-badge status-inactive"><i class="fas fa-times-circle"></i><?php echo e(trans('doctors.unavailable_day_off')); ?></span></td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="action-buttons">
                        <a href="<?php echo e(route('doctor.schedule.edit', $doctor->id)); ?>" class="btn btn-schedule-action btn-edit"><i class="fas fa-edit"></i> <?php echo e(trans('doctors.edit_schedule')); ?></a>
                        <button class="btn btn-schedule-action btn-print" onclick="window.print()"><i class="fas fa-print"></i> <?php echo e(trans('doctors.print_schedule')); ?></button>
                    </div>
                <?php else: ?>
                    <div class="empty-state text-center py-5 animate__animated animate__pulse">
                        <div class="mb-4"><i class="fas fa-calendar-times fa-4x text-muted"></i></div>
                        <h4 class="text-muted mb-3"><?php echo e(trans('doctors.schedule_not_set_yet')); ?></h4>
                        <p class="text-muted mb-4"><?php echo e(trans('doctors.schedule_not_set_yet_message')); ?></p>
                        <a href="<?php echo e(route('doctor.schedule.edit', $doctor->id)); ?>" class="btn btn-primary btn-lg"><i class="fas fa-plus-circle me-2"></i> <?php echo e(trans('doctors.create_work_schedule')); ?></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="modal fade" id="shareScheduleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-share-alt me-2"></i><?php echo e(trans('doctors.share_work_schedule')); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label"><?php echo e(trans('doctors.shareable_link')); ?></label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="scheduleLink" value="#" readonly>
                            <button class="btn btn-primary" onclick="copyScheduleLink()"><i class="fas fa-copy"></i> <?php echo e(trans('doctors.copy')); ?></button>
                        </div>
                    </div>
                    <div class="social-share-buttons d-flex justify-content-center gap-2 mt-4">
                        <a href="#" class="btn btn-outline-primary btn-sm rounded-circle" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                        <a href="#" class="btn btn-outline-info btn-sm rounded-circle" title="Telegram"><i class="fab fa-telegram"></i></a>
                        <a href="#" class="btn btn-outline-secondary btn-sm rounded-circle" title="<?php echo e(trans('doctors.copy_link')); ?>"><i class="fas fa-link"></i></a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo e(trans('doctors.close')); ?></button>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <?php echo \Illuminate\View\Factory::parentPlaceholder('js'); ?>
    <!-- Flatpickr -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ar.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize time pickers if any
            flatpickr(".time-picker", {
                enableTime: true,
                noCalendar: true,
                dateFormat: "h:i K",
                locale: "ar"
            });

            console.log('Doctor schedule view page loaded.');
        });

        function copyScheduleLink() {
            const copyText = document.getElementById("scheduleLink");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            document.execCommand("copy");

            // Show tooltip or alert
            alert("تم نسخ الرابط إلى الحافظة: " + copyText.value);
        }

        // Function to handle print
        function printSchedule() {
            window.print();
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Dashboard.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/Doctors/schedule/show_schedule.blade.php ENDPATH**/ ?>