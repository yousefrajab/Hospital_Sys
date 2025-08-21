<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/l10n/ar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <style>
        :root {
            --primary-color: #4A90E2;
            --secondary-color: #4A4A4A;
            --accent-color: #50E3C2;
            --light-bg: #f9fbfd;
            --border-color: #e5e9f2;
            --danger-color: #e74c3c;
            --success-color: #2ecc71;
            --white-color: #ffffff;
            --card-shadow: 0 8px 25px rgba(140, 152, 164, 0.15);
            --input-bg: #ffffff;
            --input-border: #d1d9e6;
            --input-focus-border: var(--primary-color);
            --input-focus-shadow: rgba(74, 144, 226, 0.2);
            --primary-gradient: linear-gradient(135deg, #4A90E2, #7FDBFF);
        }

        body {
            background: var(--light-bg);
            font-family: 'Cairo', sans-serif;
        }

        .schedule-edit-container {
            background: var(--white-color);
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            padding: 2rem 2.5rem;
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
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--secondary-color);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .schedule-title i {
            color: var(--primary-color);
        }

        .doctor-badge {
            background: #eef2ff;
            color: var(--primary-color);
            padding: 0.4rem 0.8rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .doctor-badge i {
            opacity: 0.8;
        }

        .day-schedule-container {
            display: grid;
            gap: 0.75rem;
        }

        .day-row {
            display: grid;
            grid-template-columns: 150px auto auto 1fr;
            align-items: center;
            gap: 1.25rem;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            border: 1px solid transparent;
            transition: all 0.3s ease-in-out;
        }

        .day-row.active {
            background-color: #f5f9ff;
            border-color: #dbe7ff;
        }

        .day-row.inactive {
            opacity: 0.6;
            background-color: #fafafa;
        }

        .day-row:hover {
            border-color: var(--accent-color);
        }

        .day-label {
            font-weight: 600;
            color: var(--secondary-color);
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-size: 0.95rem;
        }

        .day-icon {
            width: 20px;
            text-align: center;
            color: var(--primary-color);
            opacity: 0.9;
        }

        .time-inputs {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .time-separator {
            color: #adb5bd;
            font-size: 0.9rem;
            padding: 0 0.25rem;
        }

        .time-input,
        .duration-input input[type="number"] {
            border: 1px solid var(--input-border);
            border-radius: 6px;
            padding: 6px 10px;
            font-size: 0.9rem;
            text-align: center;
            background-color: var(--input-bg);
            transition: all 0.2s ease;
        }

        .time-input:focus,
        .duration-input input[type="number"]:focus {
            border-color: var(--input-focus-border);
            box-shadow: 0 0 0 3px var(--input-focus-shadow);
            outline: none;
        }

        .duration-input .input-group-text {
            background-color: transparent;
            border: none;
            font-size: 0.85rem;
            color: var(--dark-gray);
        }

        /* تصميم زر التفعيل */
        .day-toggle {
            justify-self: end;
        }

        .form-switch .form-check-input {
            width: 2.8em;
            height: 1.4em;
            cursor: pointer;
            background-color: #e9ecef;
            border-color: #dee2e6;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='rgba(0,0,0,.25)'/%3e%3c/svg%3e");
            transition: background-color .15s ease-in-out, border-color .15s ease-in-out, background-position .15s ease-in-out;
        }

        .form-switch .form-check-input:checked {
            background-color: var(--success-color);
            border-color: var(--success-color);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e");
        }

        .form-switch .form-check-label {
            font-size: 0.8rem;
            color: var(--dark-gray);
        }

        .inputs-disabled input,
        .inputs-disabled .input-group-text {
            opacity: 0.5;
            cursor: not-allowed !important;
        }

        .btn-save-schedule {
            background: var(--primary-gradient);
            color: white;
            border: none;
            padding: 0.8rem 2rem;
            border-radius: 50px;
            /* أكثر استدارة */
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(74, 144, 226, 0.3);
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
        }

        .btn-save-schedule:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(74, 144, 226, 0.4);
        }

        .btn-save-schedule:active {
            transform: translateY(-1px);
        }

        .alert-custom {
            border-radius: 10px;
            border-left-width: 5px;
            padding: 1rem 1.25rem;
        }

        .alert-info.alert-custom {
            background-color: #e7f3fe;
            border-color: #85c5fe;
            color: #0c5464;
        }

        .alert-danger.alert-custom {
            background-color: #f8d7da;
            border-color: #f1aeb5;
            color: #721c24;
        }

        .alert-custom i {
            font-size: 1.2em;
        }

        .alert-custom strong {
            font-weight: 600;
        }

        .alert-custom ul {
            padding-right: 1.5rem;
            margin-top: 0.5rem;
            font-size: 0.9em;
        }

        @media (max-width: 991px) {
            .day-row {
                grid-template-columns: 1fr auto;
                grid-template-areas: "label toggle" "times times" "duration duration";
            }

            .day-label {
                grid-area: label;
            }

            .time-inputs {
                grid-area: times;
                flex-direction: column;
                align-items: stretch;
                gap: 0.5rem;
            }

            .time-separator {
                display: none;
            }

            .duration-input {
                grid-area: duration;
            }

            .day-toggle {
                grid-area: toggle;
                justify-self: end;
            }

            .time-input,
            .duration-input input[type="number"] {
                max-width: none;
            }
        }

        @media (max-width: 575px) {
            .p-6 {
                padding: 1.5rem;
            }

            .schedule-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }

            .day-row {
                gap: 0.75rem;
            }
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('title'); ?>
    <?php echo e(trans('AdminSb.Managing the doctor\'s work schedule')); ?>- <?php echo e($doctor->name); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header'); ?>
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto"><?php echo e(trans('main-sidebar_trans.doctors')); ?></h4> <span
                    class="text-muted mt-1 tx-13 mr-2 mb-0"><a
                        href="<?php echo e(route('admin.Doctors.edit', $doctor->id)); ?>"><?php echo e($doctor->name); ?></a></span> <span
                    class="text-muted mt-1 tx-13 mr-2 mb-0">/ <?php echo e(trans('AdminSb.Modify the work schedule')); ?></span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <a href="<?php echo e(route('admin.Doctors.edit', $doctor->id)); ?>" class="btn btn-outline-secondary btn-sm rounded-pill">
                <i class="fas fa-user-edit me-1"></i><?php echo e(trans('AdminSb.basic data')); ?></a>
            <a href="<?php echo e(route('admin.Doctors.index')); ?>" class="btn btn-outline-primary btn-sm rounded-pill mr-2"> <i
                    class="fas fa-list me-1"></i> <?php echo e(trans('AdminSb.list of doctors')); ?></a>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('Dashboard.messages_alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="schedule-edit-container animate__animated animate__fadeIn">
                <div class="schedule-header">
                    <h2 class="schedule-title">
                        <i class="fas fa-calendar-check"></i>
                        <?php echo e(trans('AdminSb.Manage the working hours schedule')); ?>

                    </h2>
                    <span class="doctor-badge">
                        <i class="fas fa-user-md"></i>
                        <?php echo e($doctor->name); ?>

                    </span>
                </div>

                <form action="<?php echo e(route('admin.doctors.schedule.update', $doctor->id)); ?>" method="POST" id="schedule-form"
                    class="needs-validation" novalidate>
                    <?php echo method_field('PUT'); ?>
                    <?php echo csrf_field(); ?>

                    <div class="alert alert-info alert-custom mb-4 animate__animated animate__fadeIn" data-wow-delay="0.2s">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle fa-lg me-2"></i>
                            <span><?php echo e(trans('AdminSb.Select active days and enter their data. Unactivated days will be considered rest days.')); ?></span>
                        </div>
                    </div>

                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger alert-custom mb-4 animate__animated animate__headShake"
                            role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle fa-lg me-2"></i>
                                <div>
                                    <strong><?php echo e(trans('AdminSb.Input error!')); ?></strong>
                                    <ul class="mb-0 mt-1 small">
                                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><?php echo e($error); ?></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="day-schedule-container">
                        <?php $__currentLoopData = $daysOfWeek; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $currentDayData = $workingHoursData[$day] ?? [
                                    'active' => false,
                                    'start_time' => '',
                                    'end_time' => '',
                                    'appointment_duration' => 30,
                                ];
                                $isActive = $errors->any()
                                    ? old('days.' . $day . '.active') === 'on'
                                    : $currentDayData['active'];
                                $startTime = old('days.' . $day . '.start_time', $currentDayData['start_time']);
                                $endTime = old('days.' . $day . '.end_time', $currentDayData['end_time']);
                                $duration = old(
                                    'days.' . $day . '.appointment_duration',
                                    $currentDayData['appointment_duration'],
                                );
                                $dayIcons = [
                                    'saturday' => 'fa-star-of-david',
                                    'sunday' => 'fa-sun',
                                    'monday' => 'fa-moon',
                                    'tuesday' => 'fa-calendar-day',
                                    'wednesday' => 'fa-calendar-day',
                                    'thursday' => 'fa-calendar-week',
                                    'friday' => 'fa-mosque',
                                ];
                            ?>

                            <div class="day-row <?php echo e($isActive ? 'active' : 'inactive'); ?> animate__animated animate__fadeInUp"
                                style="animation-delay: <?php echo e($loop->index * 0.05); ?>s;" id="day-<?php echo e($day); ?>">
                                <div class="day-label">
                                    <span class="day-icon"><i
                                            class="fas <?php echo e($dayIcons[strtolower($day)] ?? 'fa-calendar'); ?>"></i></span>
                                    <span><?php echo e(trans('doctors.' . $day) ?? $day); ?></span>
                                </div>

                                <div class="time-inputs">
                                    <input type="text" class="form-control time-input flatpickr-time"
                                        name="days[<?php echo e($day); ?>][start_time]" value="<?php echo e($startTime); ?>"
                                        placeholder="00:00" <?php echo e(!$isActive ? 'disabled' : ''); ?> required>
                                    <span class="time-separator">-</span>
                                    <input type="text" class="form-control time-input flatpickr-time"
                                        name="days[<?php echo e($day); ?>][end_time]" value="<?php echo e($endTime); ?>"
                                        placeholder="00:00" <?php echo e(!$isActive ? 'disabled' : ''); ?> required>
                                </div>

                                <div class="duration-input">
                                    <div class="input-group input-group-sm">
                                        <input type="number" class="form-control duration-input-field"
                                            name="days[<?php echo e($day); ?>][appointment_duration]"
                                            value="<?php echo e($duration); ?>" min="5" max="120" step="5"
                                            <?php echo e(!$isActive ? 'disabled' : ''); ?> required>
                                        <span class="input-group-text"><?php echo e(trans('AdminSb.minute')); ?></span>
                                    </div>
                                </div>

                                <div class="day-toggle">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input day-switch" type="checkbox" role="switch"
                                            id="toggle-<?php echo e($day); ?>" name="days[<?php echo e($day); ?>][active]"
                                            value="on" <?php echo e($isActive ? 'checked' : ''); ?>>
                                        <label class="form-check-label small" for="toggle-<?php echo e($day); ?>"></label>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <div class="d-flex justify-content-center mt-5">
                        <button type="submit" class="btn-save-schedule">
                            <i class="fas fa-save"></i>
                            <?php echo e(trans('AdminSb.Save work schedule')); ?> </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <?php echo \Illuminate\View\Factory::parentPlaceholder('js'); ?>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/ar.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            flatpickr('.flatpickr-time', {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: false,
                locale: "ar",
                minuteIncrement: 5,
                defaultHour: 9,

                altInput: true,
                altFormat: "h:i K",
                onReady: function(selectedDates, dateStr, instance) {
                    if (instance.input.disabled) {
                        instance.input.style.backgroundColor = "#e9ecef";
                        if (instance.altInput) instance.altInput.disabled = true;
                    }
                },


            });

            document.querySelectorAll('.day-switch').forEach(switchEl => {
                const dayRow = switchEl.closest('.day-row');
                const relatedInputs = dayRow.querySelectorAll(
                    '.flatpickr-time, input[name$="[appointment_duration]"]');

                function toggleInputs(isActive) {
                    relatedInputs.forEach(input => {
                        input.disabled = !isActive;
                        input.required = isActive;

                        if (input.classList.contains('flatpickr-time')) {
                            const fpInstance = input._flatpickr;
                            const altInput = fpInstance ? fpInstance.altInput :
                                null;

                            if (fpInstance) {
                                if (!isActive) {
                                    fpInstance.clear();
                                    input.style.backgroundColor = "#e9ecef";
                                    if (altInput) altInput.disabled = true;
                                } else {
                                    input.style.backgroundColor = '';
                                    if (altInput) altInput.disabled = false;
                                }
                            }
                        }
                        input.closest('.time-inputs, .duration-input')?.classList.toggle(
                            'inputs-disabled', !isActive);
                    });
                    dayRow.classList.toggle('active', isActive);
                    dayRow.classList.toggle('inactive', !isActive);
                    dayRow.style.opacity = isActive ? '1' : '0.6';
                }

                switchEl.addEventListener('change', function() {
                    toggleInputs(this.checked);
                });
                toggleInputs(switchEl.checked);
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Dashboard.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/Doctors/edit_Days.blade.php ENDPATH**/ ?>