<?php $__env->startSection('title', 'لوحة تحكم موظف الصيدلية'); ?>

<?php $__env->startSection('css'); ?>
    <?php echo \Illuminate\View\Factory::parentPlaceholder('css'); ?>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="<?php echo e(URL::asset('dashboard/plugins/notify/css/notifIt.css')); ?>" rel="stylesheet" />
    <!--  Owl-carousel css-->
    <link href="<?php echo e(URL::asset('Dashboard/plugins/owl-carousel/owl.carousel.css')); ?>" rel="stylesheet" />
    <!-- Maps css -->
    <link href="<?php echo e(URL::asset('Dashboard/plugins/jqvmap/jqvmap.min.css')); ?>" rel="stylesheet">
    <style>
        :root {
            --bs-primary-rgb: 67, 97, 238;
            --bs-primary: rgb(var(--bs-primary-rgb));
            --bs-success-rgb: 25, 135, 84;
            --bs-danger-rgb: 220, 53, 69;
            --bs-warning-rgb: 255, 193, 7;
            --bs-info-rgb: 13, 202, 240;
            --bs-light-rgb: 248, 249, 252;
            --bs-dark-rgb: 33, 37, 41;
            --bs-body-bg: #f4f6f9;
            --bs-border-color: #dee2e6;
            --bs-card-border-radius: 0.65rem;
            /* توحيد أكثر */
            --bs-card-box-shadow: 0 0.1rem 0.9rem rgba(58, 59, 69, 0.08);
            /* ظل أنعم */
        }

        .sales-card {
            /* استخدام نفس الكلاس من قالبك */
            color: #fff;
            border-radius: var(--bs-card-border-radius);
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease-in-out;
        }

        .sales-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 7px 20px rgba(0, 0, 0, 0.15);
        }

        .sales-card .card-icon {
            /* أيقونة كبيرة في الخلفية */
            position: absolute;
            top: 50%;
            left: 20px;
            /* RTL: right: 20px */
            transform: translateY(-50%) rotate(-15deg);
            font-size: 3.5rem;
            opacity: 0.15;
        }

        .sales-card h6 {
            font-weight: 500;
            opacity: 0.9;
        }

        .sales-card h4 {
            font-size: 1.75rem;
        }

        .sales-card .card-link {
            display: block;
            text-align: center;
            padding: 0.5rem 0;
            background-color: rgba(0, 0, 0, 0.1);
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-size: 0.8rem;
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            transition: background-color 0.2s ease;
        }

        .sales-card .card-link:hover {
            background-color: rgba(0, 0, 0, 0.2);
            color: #fff;
        }

        /* ألوان البطاقات (يمكنك الاحتفاظ بتدرجات قالبك أو استخدام هذه) */
        .bg-primary-gradient {
            background: linear-gradient(45deg, #4e73df 0%, #1a44aa 100%);
        }

        .bg-danger-gradient {
            background: linear-gradient(45deg, #e74a3b 0%, #b32d21 100%);
        }

        .bg-success-gradient {
            background: linear-gradient(45deg, #1cc88a 0%, #117a51 100%);
        }

        .bg-warning-gradient {
            background: linear-gradient(45deg, #f6c23e 0%, #c89d1e 100%);
        }

        .bg-info-gradient {
            background: linear-gradient(45deg, #36b9cc 0%, #25818e 100%);
        }


        .card-table-two .table th {
            font-size: 0.8rem;
            font-weight: 600;
            color: #6e7f8d;
            text-transform: uppercase;
        }

        .card-table-two .table td {
            font-size: 0.875rem;
        }

        .status-badge {
            padding: 0.3em 0.65em;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .status-new {
            background-color: rgba(var(--bs-info-rgb), 0.15);
            color: rgb(var(--bs-info-rgb));
        }

        .status-approved {
            background-color: rgba(var(--bs-primary-rgb), 0.15);
            color: rgb(var(--bs-primary));
        }

        .status-dispensed {
            background-color: rgba(var(--bs-success-rgb), 0.15);
            color: rgb(var(--bs-success-rgb));
        }

        .status-partially_dispensed {
            background-color: rgba(var(--bs-warning-rgb), 0.2);
            color: #a17d06;
        }

        .status-on_hold {
            background-color: #6c757d33;
            color: #6c757d;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header'); ?>
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="left-content">
            <div>
                <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1"><?php echo e(trans('phrE.dashboard_title')); ?></h2><br>
                <p class="mg-b-0">
                    <?php echo e(trans('phrE.welcome_back_message', ['name' => auth()->guard('pharmacy_employee')->user()->name])); ?>

                </p>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('Dashboard.messages_alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- row -->
    <div class="row row-sm">
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12" data-aos="zoom-in-up" data-aos-delay="100">
            <div class="card overflow-hidden sales-card bg-primary-gradient">
                <div class="card-icon"><i class="fas fa-receipt"></i></div>
                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                    <div class="">
                        <h6 class="mb-3 tx-12 text-white"><?php echo e(trans('phrE.new_approved_prescriptions')); ?></h6>
                    </div>
                    <div class="pb-0 mt-0">
                        <div class="d-flex">
                            <div class="">
                                <h4 class="tx-20 font-weight-bold mb-1 text-white">
                                    <?php echo e(\App\Models\Prescription::whereIn('status', [\App\Models\Prescription::STATUS_NEW, \App\Models\Prescription::STATUS_APPROVED])->count()); ?>

                                </h4>
                                <p class="mb-0 tx-12 text-white op-7"><?php echo e(trans('phrE.awaiting_dispensing')); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="<?php echo e(route('pharmacy_employee.prescriptions.index')); ?>"
                    class="card-link"><?php echo e(trans('phrE.view_list')); ?> <i class="fas fa-arrow-circle-left"></i></a>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12" data-aos="zoom-in-up" data-aos-delay="200">
            <div class="card overflow-hidden sales-card bg-warning-gradient">
                <div class="card-icon"><i class="fas fa-pause-circle"></i></div>
                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                    <div class="">
                        <h6 class="mb-3 tx-12 text-white"><?php echo e(trans('phrE.on_hold_prescriptions')); ?></h6>
                    </div>
                    <div class="pb-0 mt-0">
                        <div class="d-flex">
                            <div class="">
                                <h4 class="tx-20 font-weight-bold mb-1 text-white">
                                    <?php echo e(\App\Models\Prescription::where('status', \App\Models\Prescription::STATUS_ON_HOLD)->count()); ?>

                                </h4>
                                <p class="mb-0 tx-12 text-white op-7"><?php echo e(trans('phrE.needs_follow_up')); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="<?php echo e(route('pharmacy_employee.prescriptions.on_hold')); ?>"
                    class="card-link"><?php echo e(trans('phrE.view_list')); ?> <i class="fas fa-arrow-circle-left"></i></a>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12" data-aos="zoom-in-up" data-aos-delay="300">
            <div class="card overflow-hidden sales-card bg-danger-gradient">
                <div class="card-icon"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                    <div class="">
                        <h6 class="mb-3 tx-12 text-white"><?php echo e(trans('phrE.low_stock_medications')); ?></h6>
                    </div>
                    <div class="pb-0 mt-0">
                        <div class="d-flex">
                            <div class="">
                                <h4 class="tx-20 font-weight-bold mb-1 text-white">
                                    <?php echo e(\App\Models\Medication::whereRaw('id IN (SELECT medication_id FROM pharmacy_stocks GROUP BY medication_id HAVING SUM(quantity_on_hand) <= medications.minimum_stock_level)')->count()); ?>

                                </h4>
                                <p class="mb-0 tx-12 text-white op-7"><?php echo e(trans('phrE.need_reordering')); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="" class="card-link"><?php echo e(trans('phrE.view_details')); ?> <i
                        class="fas fa-arrow-circle-left"></i></a>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12" data-aos="zoom-in-up" data-aos-delay="400">
            <div class="card overflow-hidden sales-card bg-info-gradient">
                <div class="card-icon"><i class="fas fa-calendar-times"></i></div>
                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                    <div class="">
                        <h6 class="mb-3 tx-12 text-white"><?php echo e(trans('phrE.medications_nearing_expiry')); ?></h6>
                    </div>
                    <div class="pb-0 mt-0">
                        <div class="d-flex">
                            <div class="">
                                <?php
                                    $expiryWarningDays = config('pharmacy.stock_expiry_warning_days', 90);
                                    $soonToExpireCount = \App\Models\PharmacyStock::where('quantity_on_hand', '>', 0)
                                        ->whereDate('expiry_date', '>', now())
                                        ->whereDate('expiry_date', '<=', now()->addDays($expiryWarningDays))
                                        ->distinct('medication_id')
                                        ->count('medication_id');
                                ?>
                                <h4 class="tx-20 font-weight-bold mb-1 text-white"><?php echo e($soonToExpireCount); ?></h4>
                                <p class="mb-0 tx-12 text-white op-7">
                                    <?php echo e(trans('phrE.within_x_days', ['days' => $expiryWarningDays])); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <a href="" class="card-link"><?php echo e(trans('phrE.view_details')); ?> <i
                        class="fas fa-arrow-circle-left"></i></a>
            </div>
        </div>
    </div>
    <!-- row closed -->

    <div class="row row-sm row-deck">
        <div class="col-md-12 col-lg-12 col-xl-7">
            <div class="card card-custom" data-aos="fade-up" data-aos-delay="500">
                <div class="card-header bg-transparent pd-b-0 pd-t-20 bd-b-0">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-0"><?php echo e(trans('phrE.prescription_status_tracking')); ?></h4>
                        <i class="mdi mdi-dots-horizontal text-gray"></i>
                    </div>
                    <p class="tx-12 text-muted mb-0"><?php echo e(trans('phrE.overview_of_statuses')); ?></p>
                </div>
                <div class="card-body">
                    <?php
                        $newPrescriptionsForChart = \App\Models\Prescription::where(
                            'status',
                            \App\Models\Prescription::STATUS_NEW,
                        )->count();
                        $approvedPrescriptionsForChart = \App\Models\Prescription::where(
                            'status',
                            \App\Models\Prescription::STATUS_APPROVED,
                        )->count();
                        $dispensedPrescriptionsForChart = \App\Models\Prescription::where(
                            'status',
                            \App\Models\Prescription::STATUS_DISPENSED,
                        )->count();
                        $onHoldPrescriptionsForChart = \App\Models\Prescription::where(
                            'status',
                            \App\Models\Prescription::STATUS_ON_HOLD,
                        )->count();
                    ?>
                    <div class="total-revenue mb-3">
                        <div class="row text-center">
                            <div class="col">
                                <h4 class="text-info"><?php echo e($newPrescriptionsForChart); ?></h4>
                                <label><span
                                        style="background-color: rgb(var(--bs-info-rgb));"></span><?php echo e(trans('phrE.status_new')); ?></label>
                            </div>
                            <div class="col">
                                <h4 class="text-primary"><?php echo e($approvedPrescriptionsForChart); ?></h4>
                                <label><span
                                        style="background-color: rgb(var(--bs-primary-rgb));"></span><?php echo e(trans('phrE.status_approved')); ?></label>
                            </div>
                            <div class="col">
                                <h4 class="text-success"><?php echo e($dispensedPrescriptionsForChart); ?></h4>
                                <label><span
                                        style="background-color: rgb(var(--bs-success-rgb));"></span><?php echo e(trans('phrE.status_dispensed')); ?></label>
                            </div>
                            <div class="col">
                                <h4 class="text-warning"><?php echo e($onHoldPrescriptionsForChart); ?></h4>
                                <label><span
                                        style="background-color: rgb(var(--bs-warning-rgb));"></span><?php echo e(trans('phrE.status_on_hold')); ?></label>
                            </div>
                        </div>
                    </div>
                    <div id="prescriptionStatusBarChart" class="sales-bar mt-4" style="height: 280px;"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-12 col-xl-5">
            <div class="card card-custom card-dashboard-map-one" data-aos="fade-up" data-aos-delay="600">
                <div class="card-header bg-transparent pd-b-0 pd-t-20 bd-b-0">
                    <label class="main-content-label card-title"><i
                            class="fas fa-history me-2 text-primary"></i><?php echo e(trans('phrE.latest_5_incoming_prescriptions')); ?></label>
                    <span
                        class="d-block mg-b-10 text-muted tx-12"><?php echo e(trans('phrE.quick_view_of_prescriptions_to_process')); ?></span>
                </div>
                <div class="card-body p-0">
                    <?php
                        $recentPendingPrescriptions = \App\Models\Prescription::whereIn('status', [
                            \App\Models\Prescription::STATUS_NEW,
                            \App\Models\Prescription::STATUS_APPROVED,
                        ])
                            ->with('patient:id')
                            ->latest()
                            ->take(5)
                            ->get();
                    ?>
                    <?php if($recentPendingPrescriptions->isNotEmpty()): ?>
                        <div class="list-group list-group-flush">
                            <?php $__currentLoopData = $recentPendingPrescriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p_prescription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e(route('pharmacy_employee.prescriptions.dispense.form', $p_prescription->id)); ?>"
                                    class="list-group-item list-group-item-action-custom list-group-item-action d-flex justify-content-between align-items-center">
                                    <div>
                                        <span
                                            class="fw-bold"><?php echo e($p_prescription->patient->name ?? trans('phrE.unknown_patient')); ?></span>
                                        <small class="d-block text-muted"><?php echo e(trans('phrE.prescription_number')); ?>

                                            <?php echo e($p_prescription->prescription_number); ?></small>
                                    </div>
                                    <span
                                        class="status-badge status-<?php echo e(str_replace('_', '-', $p_prescription->status)); ?>"><?php echo e(\App\Models\Prescription::getStatusesForFilter()[$p_prescription->status] ?? ucfirst(str_replace('_', ' ', $p_prescription->status))); ?></span>
                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <div class="text-center p-2 border-top">
                            <a href="<?php echo e(route('pharmacy_employee.prescriptions.index')); ?>"
                                class="tx-12 text-primary"><?php echo e(trans('phrE.view_all_incoming_prescriptions')); ?> <i
                                    class="fas fa-arrow-alt-circle-left"></i></a>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center py-4 px-3">
                            <?php echo e(trans('phrE.no_incoming_prescriptions_to_process')); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <!-- /row -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <?php echo \Illuminate\View\Factory::parentPlaceholder('js'); ?>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="<?php echo e(URL::asset('dashboard/plugins/notify/js/notifIt.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('dashboard/plugins/notify/js/notifit-custom.js')); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.40.0/dist/apexcharts.min.js"></script>
    
    
    


    <script>
        $(document).ready(function() {
            AOS.init({
                duration: 800,
                easing: 'ease-out-cubic',
                once: true,
                offset: 50
            });

            // Prescription Status Bar Chart Data
            var newPrescriptions = <?php echo e($newPrescriptionsForChart ?? 0); ?>;
            var approvedPrescriptions = <?php echo e($approvedPrescriptionsForChart ?? 0); ?>;
            var dispensedPrescriptions = <?php echo e($dispensedPrescriptionsForChart ?? 0); ?>;
            var onHoldPrescriptions = <?php echo e($onHoldPrescriptionsForChart ?? 0); ?>;

            if (document.getElementById('prescriptionStatusBarChart')) {
                var barChartOptionsPresc = {
                    chart: {
                        type: 'bar',
                        height: 280,
                        fontFamily: 'Tajawal, sans-serif',
                        toolbar: {
                            show: false
                        },
                        parentHeightOffset: 0
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '55%',
                            borderRadius: 5,
                            dataLabels: {
                                position: 'top'
                            }
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        formatter: function(val) {
                            return val;
                        },
                        offsetY: -20,
                        style: {
                            fontSize: '12px',
                            colors: ["#304758"]
                        }
                    },
                    stroke: {
                        show: true,
                        width: 1,
                        colors: ['#fff']
                    },
                    series: [{
                        name: 'عدد الوصفات',
                        data: [newPrescriptions, approvedPrescriptions, dispensedPrescriptions,
                            onHoldPrescriptions
                        ]
                    }],
                    xaxis: {
                        categories: ['جديدة', 'معتمدة', 'مصروفة', 'قيد الانتظار'],
                        labels: {
                            style: {
                                colors: '#6e82a0',
                                fontSize: '13px',
                                fontWeight: 500
                            }
                        },
                        axisBorder: {
                            show: false
                        },
                        axisTicks: {
                            show: false
                        }
                    },
                    yaxis: {
                        show: true,
                        labels: {
                            style: {
                                colors: '#6e82a0',
                                fontSize: '12px'
                            },
                            formatter: function(val) {
                                return parseInt(val);
                            }
                        }
                    },
                    colors: ['rgb(var(--bs-info-rgb))', 'rgb(var(--bs-primary-rgb))',
                        'rgb(var(--bs-success-rgb))', 'rgb(var(--bs-warning-rgb))'
                    ],
                    fill: {
                        opacity: 1
                    },
                    grid: {
                        show: true,
                        borderColor: '#e3e6f0',
                        strokeDashArray: 3,
                        padding: {
                            top: 0,
                            right: 0,
                            bottom: 0,
                            left: 10
                        }
                    },
                    legend: {
                        show: false
                    },
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return val + " وصفة"
                            }
                        },
                        style: {
                            fontSize: '12px',
                            fontFamily: 'Tajawal, sans-serif'
                        },
                        marker: {
                            show: false
                        }
                    }
                };
                var chartPresc = new ApexCharts(document.querySelector("#prescriptionStatusBarChart"),
                    barChartOptionsPresc);
                chartPresc.render();
            }

            // NotifIt messages
            <?php if(session('success') || session('status_success')): ?>
                notif({
                    msg: `<div class="d-flex align-items-center p-2"><i class='fas fa-check-circle fa-lg me-2 text-success'></i><div style='font-size: 0.95rem;'><?php echo e(session('success') ?: session('status_success')); ?></div></div>`,
                    type: "success",
                    position: "top-center",
                    autohide: true,
                    timeout: 5000,
                    zindex: 99999
                });
            <?php endif; ?>
            <?php if(session('error') || session('error_message')): ?>
                notif({
                    msg: `<div class="d-flex align-items-center p-2"><i class='fas fa-exclamation-triangle fa-lg me-2 text-danger'></i><div style='font-size: 0.95rem;'><?php echo e(session('error') ?: session('error_message')); ?></div></div>`,
                    type: "error",
                    position: "top-center",
                    autohide: true,
                    timeout: 7000,
                    zindex: 99999
                });
            <?php endif; ?>

            // Placeholder for compositeline charts (if your template uses them)
            // You'll need to initialize them with actual data if you want them to be dynamic
            // For now, they will show the static data from the HTML
            // Example using peity (if you decide to include it)
            /*
            if (typeof $.fn.peity !== 'undefined') {
                $("span#compositeline").peity("line", {
                    fill: 'rgba(67, 97, 238, 0.1)', // Primary color with opacity
                    stroke: 'rgb(var(--bs-primary-rgb))',
                    width: '100%',
                    height: '40'
                });
                $("span#compositeline2").peity("line", {
                    fill: 'rgba(220, 53, 69, 0.1)', // Danger color
                    stroke: 'rgb(var(--bs-danger-rgb))',
                    width: '100%',
                    height: '40'
                });
                $("span#compositeline3").peity("line", {
                    fill: 'rgba(25, 135, 84, 0.1)', // Success color
                    stroke: 'rgb(var(--bs-success-rgb))',
                    width: '100%',
                    height: '40'
                });
                $("span#compositeline4").peity("line", {
                    fill: 'rgba(255, 193, 7, 0.1)', // Warning color
                    stroke: 'rgb(var(--bs-warning-rgb))',
                    width: '100%',
                    height: '40'
                });
            }
            */
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Dashboard.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/dashboard_PharmacyEmployee/dashboard.blade.php ENDPATH**/ ?>