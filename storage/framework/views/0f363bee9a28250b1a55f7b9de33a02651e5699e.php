 

<?php $__env->startSection('title', 'مواعيدي القادمة'); ?>

<?php $__env->startSection('css'); ?>
    <?php echo \Illuminate\View\Factory::parentPlaceholder('css'); ?>
    
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link href="<?php echo e(URL::asset('Dashboard/plugins/notify/css/notifIt.css')); ?>" rel="stylesheet" />
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">


    <style>
        :root {
            --patient-primary: #007bff; /* Primary Blue */
            --patient-primary-rgb: 0, 123, 255;
            --patient-primary-dark: #0056b3;
            --patient-primary-light: #cfe2ff;
            --patient-secondary: #6c757d; /* Secondary Gray */
            --patient-success: #198754;  /* Success Green */
            --patient-success-rgb: 25, 135, 84;
            --patient-warning: #ffc107;  /* Warning Yellow */
            --patient-warning-rgb: 255, 193, 7;
            --patient-danger: #dc3545;   /* Danger Red */
            --patient-danger-rgb: 220, 53, 69;
            --patient-info: #0dcaf0;     /* Info Cyan */
            --patient-text-dark: #212529;
            --patient-text-light: #6c757d;
            --patient-bg: #f8f9fa;
            --patient-card-bg: #ffffff;
            --patient-border-color: #dee2e6;
            --patient-card-radius: 0.75rem; /* 12px */
            --patient-card-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.075);
            --patient-transition: all 0.3s ease-in-out;
        }

        body {
            background-color: var(--patient-bg);
            font-family: 'Tajawal', sans-serif; /* Ensure Tajawal is loaded */
            color: var(--patient-text-dark);
        }

        .appointments-page-container {
            padding: 2rem 1.5rem;
            max-width: 1320px; /* Slightly wider for better grid */
            margin: auto;
        }

        .page-header-custom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--patient-border-color);
        }

        .page-title-custom {
            font-size: 2rem;
            font-weight: 700;
            color: var(--patient-primary);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .page-title-custom i { font-size: 1.1em; }

        .page-actions .btn {
            font-size: 0.95rem;
            padding: 0.6rem 1.2rem;
            border-radius: var(--patient-card-radius);
            font-weight: 500;
        }
        .btn-primary-patient { background-color: var(--patient-primary); border-color: var(--patient-primary); color: white; }
        .btn-primary-patient:hover { background-color: var(--patient-primary-dark); border-color: var(--patient-primary-dark); }
        .btn-outline-secondary-patient { color: var(--patient-secondary); border-color: var(--patient-secondary); }
        .btn-outline-secondary-patient:hover { background-color: var(--patient-secondary); color: white; }

        .appointments-grid-patient {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
        }

        .appointment-card-wrapper {
            transition: var(--patient-transition);
        }
        .appointment-card-wrapper:hover {
            transform: translateY(-6px);
        }

        .appointment-card {
            background: var(--patient-card-bg);
            border-radius: var(--patient-card-radius);
            box-shadow: var(--patient-card-shadow);
            border: 1px solid var(--patient-border-color);
            display: flex;
            flex-direction: column;
            height: 100%; /* Ensure cards in a row have same height */
            overflow: hidden;
            position: relative;
        }
        .appointment-card::before { /* Status indicator strip */
            content: '';
            position: absolute;
            top: 0;
            right: 0; /* RTL */
            bottom: 0;
            width: 7px;
            background-color: var(--patient-info); /* Default */
            border-radius: 0 var(--patient-card-radius) var(--patient-card-radius) 0; /* RTL */
            transition: background-color 0.3s;
        }
        .appointment-card[data-status="مؤكد"]::before,
        .appointment-card[data-status-key="confirmed"]::before { background-color: var(--patient-success); }
        .appointment-card[data-status="غير مؤكد"]::before,
        .appointment-card[data-status-key="pending"]::before { background-color: var(--patient-warning); }
        .appointment-card[data-status^="ملغي"]::before, /* Starts with "ملغي" */
        .appointment-card[data-status-key="cancelled"]::before { background-color: var(--patient-danger); }


        .appointment-card-header-patient {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--patient-border-color);
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .appointment-title-patient {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--patient-text-dark);
            margin-bottom: 0.3rem;
        }

        .appointment-datetime-patient {
            font-size: 0.9rem;
            color: var(--patient-text-light);
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }
        .appointment-datetime-patient i { color: var(--patient-secondary); }

        .appointment-status-badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.4em 0.9em;
            border-radius: 50px;
            text-transform: capitalize;
            white-space: nowrap;
        }
        .badge-status-confirmed { background-color: rgba(var(--patient-success-rgb), 0.1); color: var(--patient-success); border: 1px solid rgba(var(--patient-success-rgb),0.2); }
        .badge-status-pending { background-color: rgba(var(--patient-warning-rgb), 0.15); color: #a17400; border: 1px solid rgba(var(--patient-warning-rgb),0.3); } /* Darker yellow text */
        .badge-status-cancelled { background-color: rgba(var(--patient-danger-rgb), 0.1); color: var(--patient-danger); border: 1px solid rgba(var(--patient-danger-rgb),0.2); }


        .appointment-card-body-patient {
            padding: 1.25rem 1.5rem;
            flex-grow: 1;
        }

        .appointment-details-list-patient {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .appointment-details-list-patient li {
            display: flex;
            align-items: flex-start;
            margin-bottom: 0.85rem;
            font-size: 0.95rem;
            color: var(--patient-text-dark);
        }
        .appointment-details-list-patient li i {
            width: 24px; /* For icon alignment */
            text-align: center;
            margin-left: 12px; /* RTL */
            color: var(--patient-primary);
            font-size: 1.1em;
            padding-top: 1px;
        }
        .appointment-details-list-patient li .detail-value { font-weight: 500; }
        .appointment-details-list-patient li .detail-label { color: var(--patient-text-light); margin-right: 5px;} /* For RTL */


        .appointment-card-footer-patient {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--patient-border-color);
            background-color: #fcfdff; /* Slightly off-white */
            text-align: left; /* RTL: buttons to the left */
        }
        .btn-appointment-action {
            font-size: 0.85rem;
            padding: 0.5rem 1.1rem;
            border-radius: var(--patient-card-radius);
            font-weight: 500;
        }
        .btn-outline-danger-patient { color: var(--patient-danger); border-color: var(--patient-danger); }
        .btn-outline-danger-patient:hover { background-color: var(--patient-danger); color: white; }
        .btn-disabled-custom { opacity: 0.65; cursor: not-allowed !important; }

        .no-appointments-patient {
            text-align: center;
            padding: 4rem 2rem;
            background: var(--patient-card-bg);
            border-radius: var(--patient-card-radius);
            box-shadow: var(--patient-card-shadow);
            color: var(--patient-text-light);
            border: 1px dashed var(--patient-border-color);
        }
        .no-appointments-patient i {
            font-size: 4rem;
            display: block;
            margin-bottom: 1.5rem;
            color: var(--patient-primary-light);
        }
        .no-appointments-patient h4 { font-size: 1.5rem; color: var(--patient-text-dark); margin-bottom: 0.5rem; }
        .no-appointments-patient p { font-size: 1rem; margin-bottom: 1.5rem;}

        .pagination-wrapper { margin-top: 2.5rem; }
        .pagination .page-item .page-link {
            color: var(--patient-primary);
            border-radius: 0.3rem;
            margin: 0 0.2rem;
        }
        .pagination .page-item.active .page-link {
            background-color: var(--patient-primary);
            border-color: var(--patient-primary);
            color: white;
        }
        .pagination .page-item.disabled .page-link {
            color: var(--patient-text-light);
        }

        /* Animation */
        .animate__faster { animation-duration: 0.6s !important; }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header'); ?>
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto"><i class="fas fa-calendar-alt me-2 text-primary"></i><?php echo e(trans('patients.my_appointments')); ?></h4>
                <span class="text-muted mt-1 tx-13 ms-2 mb-0">/ <?php echo e(trans('patients.upcoming')); ?></span> 
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center">
            <?php if(Route::has('patient.appointments.past')): ?>
                <a href="<?php echo e(route('patient.appointments.past')); ?>" class="btn btn-outline-secondary-patient btn-sm me-2">
                    <i class="fas fa-history me-1"></i> <?php echo e(trans('patients.past_appointments')); ?>

                </a>
            <?php endif; ?>
            <a href="<?php echo e(route('patient.appointments.create.form')); ?>" class="btn btn-primary-patient btn-sm">
                <i class="fas fa-plus-circle me-1"></i> <?php echo e(trans('patients.request_new_appointment')); ?>

            </a>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('Dashboard.messages_alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="appointments-page-container">
        <div class="page-header-custom animate__animated animate__fadeInDown">
            <h1 class="page-title-custom"><i class="fas fa-business-time"></i> <?php echo e(trans('patients.your_upcoming_appointments_title')); ?></h1>
        </div>

        <?php if(isset($appointments) && $appointments->isNotEmpty()): ?>
            <div class="appointments-grid-patient">
                <?php $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $appointmentDateTime = \Carbon\Carbon::parse($appointment->appointment);
                        $statusKey = 'pending';
                        $statusText = $appointment->type;
                        $statusBadgeClass = 'badge-status-pending';

                        if ($appointment->type == (\App\Models\Appointment::STATUS_CONFIRMED ?? 'مؤكد')) {
                            $statusKey = 'confirmed';
                            $statusBadgeClass = 'badge-status-confirmed';
                        } elseif (str_starts_with($appointment->type, 'ملغي')) {
                             $statusKey = 'cancelled';
                             $statusBadgeClass = 'badge-status-cancelled';
                        }

                        $cancellationWindowHours = config('app.appointment_cancellation_window_hours', 24);
                        $isCancellable = in_array($appointment->type, [\App\Models\Appointment::STATUS_PENDING ?? 'غير مؤكد', \App\Models\Appointment::STATUS_CONFIRMED ?? 'مؤكد']) &&
                                         $appointmentDateTime->isFuture() &&
                                         $appointmentDateTime->diffInHours(now()) >= $cancellationWindowHours;
                    ?>
                    <div class="appointment-card-wrapper animate__animated animate__fadeInUp" style="animation-delay: <?php echo e($loop->index * 0.1); ?>s;">
                        <div class="appointment-card" data-appointment-id="<?php echo e($appointment->id); ?>" data-status-key="<?php echo e($statusKey); ?>" data-status="<?php echo e($appointment->type); ?>">
                            <div class="appointment-card-header-patient">
                                <div>
                                    <h5 class="appointment-title-patient">
                                        <?php echo e($appointment->section->name ?? trans('patients.general_appointment')); ?>

                                    </h5>
                                    <div class="appointment-datetime-patient">
                                        <span><i class="fas fa-calendar-alt"></i> <?php echo e($appointmentDateTime->translatedFormat('l، j F Y')); ?></span>
                                        <span><i class="far fa-clock"></i> <?php echo e($appointmentDateTime->translatedFormat('h:i A')); ?></span>
                                    </div>
                                </div>
                                <span class="badge appointment-status-badge <?php echo e($statusBadgeClass); ?>">
                                    <?php if($statusKey == 'confirmed'): ?> <i class="fas fa-check-circle me-1"></i> <?php endif; ?>
                                    <?php if($statusKey == 'pending'): ?> <i class="fas fa-hourglass-half me-1"></i> <?php endif; ?>
                                    <?php if($statusKey == 'cancelled'): ?> <i class="fas fa-ban me-1"></i> <?php endif; ?>
                                    <?php echo e($statusText); ?>

                                </span>
                            </div>
                            <div class="appointment-card-body-patient">
                                <ul class="appointment-details-list-patient">
                                    <?php if($appointment->doctor): ?>
                                        <li><i class="fas fa-user-md"></i>
                                            <span class="detail-value"><?php echo e($appointment->doctor->name ?? trans('doctors.unspecified')); ?></span>
                                        </li>
                                    <?php endif; ?>
                                     <li><i class="fas fa-user"></i>
                                        <span class="detail-value"><?php echo e($appointment->name); ?></span>
                                    </li>
                                    <?php if($appointment->notes): ?>
                                        <li><i class="fas fa-sticky-note"></i>
                                            <span class="detail-label"><?php echo e(trans('patients.your_notes_label')); ?></span>
                                            <span class="detail-value"><?php echo e(Str::limit($appointment->notes, 100)); ?></span>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                            <div class="appointment-card-footer-patient">
                                <?php if($isCancellable): ?>
                                    <button type="button"
                                        class="btn btn-appointment-action btn-outline-danger-patient cancel-appointment-btn"
                                        data-appointment-id="<?php echo e($appointment->id); ?>"
                                        data-appointment-doctor="<?php echo e($appointment->doctor->name ?? trans('patients.the_doctor')); ?>"
                                        data-appointment-datetime="<?php echo e($appointmentDateTime->translatedFormat('l، j F Y - h:i A')); ?>"
                                        title="<?php echo e(trans('patients.cancel_this_appointment_tooltip')); ?>">
                                        <i class="fas fa-times-circle me-1"></i> <?php echo e(trans('patients.cancel_appointment_button')); ?>

                                    </button>
                                <?php elseif(in_array($statusKey, ['pending', 'confirmed'])): ?>
                                     <span class="text-muted small"><i class="fas fa-info-circle me-1"></i> <?php echo e(trans('patients.cancellation_not_allowed_msg')); ?></span>
                                <?php else: ?>
                                    <span class="text-muted small"><i class="fas fa-ban me-1"></i> <?php echo e(trans('patients.no_actions_available_msg')); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <?php if($appointments->hasPages()): ?>
                <div class="pagination-wrapper d-flex justify-content-center">
                    <?php echo e($appointments->links()); ?>

                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="no-appointments-patient animate__animated animate__fadeIn">
                <i class="fas fa-calendar-check"></i>
                <h4><?php echo e(trans('patients.no_upcoming_appointments_currently')); ?></h4>
                <p><?php echo e(trans('patients.new_appointments_will_appear_here')); ?></p>
                <a href="<?php echo e(route('patient.appointments.create.form')); ?>" class="btn btn-lg btn-primary-patient mt-2">
                    <i class="fas fa-plus-circle me-2"></i> <?php echo e(trans('patients.request_new_appointment_now')); ?>

                </a>
            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
    <?php echo \Illuminate\View\Factory::parentPlaceholder('js'); ?>
    <script src="<?php echo e(URL::asset('Dashboard/plugins/notify/js/notifIt.js')); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
 <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script> AOS.init(); </script>
    <script>
        $(document).ready(function() {
            // Initialize AOS if not done globally
            if (typeof AOS !== 'undefined') {
                AOS.init({
                    duration: 600,
                    easing: 'ease-out-cubic',
                    once: true
                });
            }

            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            function showNotification(message, type = 'info', options = {}) {
                const defaultOptions = {
                    position: "top-right", // Changed default position
                    autohide: true,
                    timeout: 5000,
                    zindex: 999999,
                    bgcolor: '', // Will be set based on type
                    color: "#ffffff",
                    multiline: true,
                    width: 'auto'
                };

                let iconClass = 'fas fa-info-circle';
                switch (type) {
                    case 'success':
                        iconClass = 'fas fa-check-circle';
                        defaultOptions.bgcolor = 'var(--patient-success)';
                        break;
                    case 'error':
                        iconClass = 'fas fa-times-circle';
                        defaultOptions.bgcolor = 'var(--patient-danger)';
                        break;
                    case 'warning':
                        iconClass = 'fas fa-exclamation-triangle';
                        defaultOptions.bgcolor = 'var(--patient-warning)';
                        break;
                    default: // info
                        defaultOptions.bgcolor = 'var(--patient-info)';
                        break;
                }

                const finalOptions = { ...defaultOptions, ...options };
                finalOptions.msg = `<div class='d-flex align-items-center p-1'><i class='${iconClass} fa-lg me-2'></i><div style='font-size: 0.9rem;'>${message}</div></div>`;
                notif(finalOptions);
            }

            // Handle session messages passed from controller (non-AJAX redirects)
            <?php if(session('success')): ?>
                showNotification("<?php echo e(session('success')); ?>", "success");
            <?php endif; ?>
            <?php if(session('error')): ?>
                showNotification("<?php echo e(session('error')); ?>", "error", { timeout: 7000 });
            <?php endif; ?>
            <?php if(session('info')): ?>
                showNotification("<?php echo e(session('info')); ?>", "info");
            <?php endif; ?>


            $('.appointments-grid-patient').on('click', '.cancel-appointment-btn', function() {
                const appointmentId = $(this).data('appointment-id');
                const doctorName = $(this).data('appointment-doctor');
                const appointmentDatetime = $(this).data('appointment-datetime');
                const $button = $(this);
                const $card = $button.closest('.appointment-card');

                if (!appointmentId) {
                    console.error('Appointment ID not found for cancellation.');
                    showNotification('خطأ: معرف الموعد غير موجود.', 'error');
                    return;
                }

                Swal.fire({
                    title: 'تأكيد إلغاء الموعد',
                    html: `هل أنت متأكد من رغبتك في إلغاء موعدك مع:<br><strong>د. ${doctorName}</strong><br>يوم ${appointmentDatetime}؟`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'var(--patient-danger)',
                    cancelButtonColor: 'var(--patient-secondary)',
                    confirmButtonText: '<i class="fas fa-trash-alt me-1"></i> نعم، إلغاء',
                    cancelButtonText: '<i class="fas fa-times me-1"></i> تراجع',
                    customClass: {
                        popup: 'animate__animated animate__fadeInDown animate__faster',
                        confirmButton: 'btn btn-danger mx-1',
                        cancelButton: 'btn btn-secondary mx-1'
                    },
                    buttonsStyling: false, // Use custom Bootstrap classes
                    showLoaderOnConfirm: true,
                    preConfirm: async () => {
                        try {
                            // Route model binding will handle {appointment}
                            const cancelUrl = `<?php echo e(url('patient/appointments')); ?>/${appointmentId}/cancel-by-patient`;
                            const response = await fetch(cancelUrl, {
                                method: 'PATCH', // Or POST if your route is POST and you use _method
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json'
                                }
                                // No body needed if reason is default, or pass { reason_patient: '...' }
                            });

                            const responseData = await response.json();
                            if (!response.ok) {
                                throw new Error(responseData.message || `فشل الإلغاء (HTTP ${response.status})`);
                            }
                            return responseData;
                        } catch (error) {
                            Swal.showValidationMessage(`فشل الطلب: ${error.message}`);
                            // Log detailed error to console for debugging
                            console.error("Cancellation preConfirm error:", error);
                        }
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed && result.value) {
                        const apiResult = result.value;
                        showNotification(apiResult.message || 'تم إلغاء الموعد بنجاح.', 'success');

                        // Update card UI
                        $card.attr('data-status-key', 'cancelled');
                        $card.attr('data-status', apiResult.new_status_display || apiResult.new_status); // Update display status

                        const $statusBadge = $card.find('.appointment-status-badge');
                        if ($statusBadge.length) {
                            $statusBadge.removeClass('badge-status-confirmed badge-status-pending')
                                .addClass('badge-status-cancelled')
                                .html(`<i class="fas fa-ban me-1"></i> ${apiResult.new_status_display || apiResult.new_status}`);
                        }

                        $button.closest('.appointment-card-footer-patient')
                            .html('<span class="text-danger small fw-bold p-2"><i class="fas fa-check-circle me-1"></i> تم إلغاء هذا الموعد.</span>');

                        // Optional: Add a class to the card for further styling or filtering
                        $card.addClass('appointment-cancelled');

                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        // User cancelled
                    } else if (result.isDenied) {
                        // This won't happen with current button setup
                    } else if (result.value === undefined && result.isConfirmed === false) {
                        // Error from preConfirm, Swal.showValidationMessage was shown
                        showNotification('لم يتم إلغاء الموعد بسبب خطأ في الطلب.', 'error');
                    }
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Dashboard.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/Patients/appointments/upcoming.blade.php ENDPATH**/ ?>