<?php $__env->startSection('title', 'لوحة تحكم مدير الصيدلية'); ?>

<?php $__env->startSection('css'); ?>
    <?php echo \Illuminate\View\Factory::parentPlaceholder('css'); ?>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="<?php echo e(asset('dashboard/plugins/notify/css/notifIt.css')); ?>" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/apexcharts@3.40.0/dist/apexcharts.css" rel="stylesheet">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #6c757d;
            --success: #28a745;
            --danger: #dc3545;
            --warning: #ffc107;
            --info: #17a2b8;
            --light: #f8f9fa;
            --dark: #343a40;
            --purple: #6f42c1;
            --cyan: #0dcaf0;
            --card-radius: 12px;
            --card-shadow: 0 4px 20px rgba(0,0,0,0.08);
            --transition: all 0.3s ease;
        }

        /* بطاقات الإحصائيات */
        .stat-card {
            border-radius: var(--card-radius);
            overflow: hidden;
            transition: var(--transition);
            box-shadow: var(--card-shadow);
            position: relative;
            color: white;
            margin-bottom: 1.5rem;
            border: none;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }

        .stat-card .card-icon {
            position: absolute;
            right: 20px;
            top: 20px;
            font-size: 2.5rem;
            opacity: 0.2;
            transition: var(--transition);
        }

        .stat-card:hover .card-icon {
            opacity: 0.3;
            transform: scale(1.1);
        }

        .stat-card .card-body {
            position: relative;
            z-index: 1;
            padding: 1.5rem;
        }

        .stat-card .stat-title {
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.9;
        }

        .stat-card .stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .stat-card .stat-desc {
            font-size: 0.8rem;
            opacity: 0.8;
            margin-bottom: 0;
        }

        .stat-card .card-footer {
            background: rgba(0,0,0,0.1);
            border-top: 1px solid rgba(255,255,255,0.1);
            padding: 0.75rem;
            text-align: center;
        }

        .stat-card .card-footer a {
            color: rgba(255,255,255,0.8);
            font-size: 0.8rem;
            text-decoration: none;
            transition: var(--transition);
        }

        .stat-card .card-footer a:hover {
            color: white;
            text-decoration: underline;
        }

        /* ألوان البطاقات */
        .bg-primary { background: linear-gradient(135deg, var(--primary) 0%, #3a56e6 100%); }
        .bg-danger { background: linear-gradient(135deg, var(--danger) 0%, #c82333 100%); }
        .bg-warning { background: linear-gradient(135deg, var(--warning) 0%, #e0a800 100%); }
        .bg-info { background: linear-gradient(135deg, var(--info) 0%, #138496 100%); }
        .bg-success { background: linear-gradient(135deg, var(--success) 0%, #218838 100%); }
        .bg-purple { background: linear-gradient(135deg, var(--purple) 0%, #5a32a3 100%); }

        /* بطاقات المحتوى */
        .content-card {
            border-radius: var(--card-radius);
            box-shadow: var(--card-shadow);
            border: none;
            margin-bottom: 1.5rem;
            transition: var(--transition);
        }

        .content-card:hover {
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }

        .content-card .card-header {
            background: white;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 1rem 1.5rem;
            border-radius: var(--card-radius) var(--card-radius) 0 0 !important;
        }

        .content-card .card-title {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0;
            display: flex;
            align-items: center;
        }

        .content-card .card-title i {
            margin-left: 0.5rem;
            color: var(--primary);
        }

        /* قائمة الأدوية الحديثة */
        .recent-list {
            border-radius: 0 0 var(--card-radius) var(--card-radius);
        }

        .recent-list .list-group-item {
            border-left: 3px solid transparent;
            transition: var(--transition);
            padding: 1rem 1.5rem;
            border-color: rgba(0,0,0,0.05);
        }

        .recent-list .list-group-item:hover {
            border-left-color: var(--primary);
            background-color: rgba(var(--primary), 0.03);
        }

        .recent-list .list-group-item .med-name {
            font-weight: 600;
            color: var(--dark);
        }

        .recent-list .list-group-item .med-details {
            font-size: 0.8rem;
            color: var(--secondary);
        }

        .recent-list .list-group-item .med-date {
            font-size: 0.75rem;
            color: var(--secondary);
        }

        /* الرسم البياني */
        .chart-container {
            padding: 1rem;
            height: 300px;
        }

        /* الصفحة الرئيسية */
        .welcome-header h2 {
            font-weight: 700;
            color: var(--dark);
        }

        .welcome-header p {
            color: var(--secondary);
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page-header'); ?>
    <div class="breadcrumb-header justify-content-between">
        <div class="left-content">
            <div class="welcome-header">
                <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1"><?php echo e(trans('phrM.dashboard_title')); ?></h2>
                <p class="mg-b-0"><?php echo e(trans('phrM.welcome_back_message', ['name' => Auth::guard('pharmacy_manager')->user()->name ?? 'مدير الصيدلية'])); ?></p>
            </div>
        </div>
        <div class="right-content">
            <div class="d-flex align-items-center">
                <span class="me-2"><?php echo e(trans('phrM.today')); ?></span>
                <span class="badge bg-light text-dark"><?php echo e(now()->format('d F Y')); ?></span>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('Dashboard.messages_alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="row">
        <!-- بطاقة إجمالي الأدوية -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-card bg-primary">
                <div class="card-icon"><i class="fas fa-pills"></i></div>
                <div class="card-body">
                    <h6 class="stat-title"><?php echo e(trans('phrM.total_medication_items')); ?></h6>
                    <h3 class="stat-value"><?php echo e(number_format($activeMedications->count())); ?></h3>
                    <p class="stat-desc"><?php echo e(trans('phrM.medications_registered')); ?></p>
                </div>
                <div class="card-footer">
                    <a href="<?php echo e(route('admin.medications.index')); ?>"><?php echo e(trans('phrM.manage_medications')); ?> <i class="fas fa-arrow-left ms-1"></i></a>
                </div>
            </div>
        </div>

        <!-- بطاقة الأدوية منخفضة المخزون -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-card bg-danger">
                <div class="card-icon"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="card-body">
                    <h6 class="stat-title"><?php echo e(trans('phrM.low_stock_medications')); ?></h6>
                    <h3 class="stat-value"><?php echo e($lowStockMedicationsCount ?? 0); ?></h3>
                    <p class="stat-desc"><?php echo e(trans('phrM.need_reordering')); ?></p>
                </div>
                <div class="card-footer">
                    <a href="<?php echo e(route('pharmacy_employee.medications.search', ['stock_status_filter' => 'low_stock'])); ?>"><?php echo e(trans('phrM.view_list')); ?> <i class="fas fa-arrow-left ms-1"></i></a>
                </div>
            </div>
        </div>

        <!-- بطاقة الأدوية قريبة الانتهاء -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12" data-aos="fade-up" data-aos-delay="300">
            <div class="stat-card bg-warning">
                <div class="card-icon"><i class="fas fa-calendar-alt"></i></div>
                <div class="card-body">
                    <h6 class="stat-title"><?php echo e(trans('phrM.medications_nearing_expiry')); ?></h6>
                    <h3 class="stat-value"><?php echo e($soonToExpireMedicationsCount ?? 0); ?></h3>
                    <p class="stat-desc"><?php echo e(trans('phrM.within_x_days', ['days' => $expiryWarningDays ?? 90])); ?></p>
                </div>
                <div class="card-footer">
                    <a href="<?php echo e(route('pharmacy_employee.medications.search', ['expiry_status_filter' => 'expired_soon'])); ?>"><?php echo e(trans('phrM.view_list')); ?> <i class="fas fa-arrow-left ms-1"></i></a>
                </div>
            </div>
        </div>

        <!-- بطاقة الوصفات المعلقة -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12" data-aos="fade-up" data-aos-delay="400">
            <div class="stat-card bg-info">
                <div class="card-icon"><i class="fas fa-receipt"></i></div>
                <div class="card-body">
                    <h6 class="stat-title"><?php echo e(trans('phrM.prescriptions_awaiting_processing')); ?></h6>
                    <h3 class="stat-value"><?php echo e($pendingPrescriptionsCountForManager ?? 0); ?></h3>
                    <p class="stat-desc"><?php echo e(trans('phrM.new_approved_or_on_hold')); ?></p>
                </div>
                <div class="card-footer">
                    <a href="<?php echo e(route('pharmacy_employee.prescriptions.index')); ?>"><?php echo e(trans('phrM.review_prescriptions')); ?> <i class="fas fa-arrow-left ms-1"></i></a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- الرسم البياني -->
        <div class="col-lg-8" data-aos="fade-up" data-aos-delay="500">
            <div class="content-card">
                <div class="card-header">
                    <h5 class="card-title"><i class="fas fa-chart-bar me-2"></i><?php echo e(trans('phrM.stock_status')); ?></h5>
                </div>
                <div class="card-body">
                    <div class="row text-center mb-4">
                        <div class="col-md-4">
                            <div class="border p-3 rounded">
                                <h4 class="text-success"><?php echo e($inStockForChart ?? 0); ?></h4>
                                <small class="text-muted"><?php echo e(trans('phrM.medications_in_stock')); ?></small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border p-3 rounded">
                                <h4 class="text-warning"><?php echo e($lowStockMedicationsCount ?? 0); ?></h4>
                                <small class="text-muted"><?php echo e(trans('phrM.low_stock')); ?></small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border p-3 rounded">
                                <h4 class="text-danger"><?php echo e($outOfStockForChart ?? 0); ?></h4>
                                <small class="text-muted"><?php echo e(trans('phrM.out_of_stock')); ?></small>
                            </div>
                        </div>
                    </div>
                    <div id="stockChart" class="chart-container"></div>
                </div>
            </div>
        </div>

        <!-- آخر الدفعات -->
        <div class="col-lg-4" data-aos="fade-up" data-aos-delay="600">
            <div class="content-card">
                <div class="card-header">
                    <h5 class="card-title"><i class="fas fa-history me-2"></i><?php echo e(trans('phrM.latest_medication_batches')); ?></h5>
                </div>
                <div class="card-body p-0">
                    <?php if($recentStockEntries->isNotEmpty()): ?>
                        <div class="list-group recent-list">
                            <?php $__currentLoopData = $recentStockEntries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stock): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route('pharmacy_manager.medications.stocks.index', $stock->medication_id)); ?>"
                               class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="med-name mb-1"><?php echo e($stock->medication->name ?? trans('phrM.unknown_medication')); ?></h6>
                                        <small class="med-details d-block">
                                            <?php echo e(trans('phrM.batch_number')); ?> <?php echo e($stock->batch_number ?: 'N/A'); ?> |
                                            <?php echo e(trans('phrM.quantity')); ?> <?php echo e($stock->initial_quantity); ?>

                                        </small>
                                    </div>
                                    <small class="med-date">
                                        <?php echo e($stock->received_date ? \Carbon\Carbon::parse($stock->received_date)->diffForHumans() : ''); ?>

                                    </small>
                                </div>
                            </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <div class="card-footer text-center">
                            <a href="<?php echo e(route('admin.medications.index')); ?>" class="text-primary">
                                <?php echo e(trans('phrM.view_all_batches')); ?> <i class="fas fa-arrow-left ms-1"></i>
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-box-open fa-2x text-muted mb-3"></i>
                            <p class="text-muted"><?php echo e(trans('phrM.no_recent_batches')); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <?php echo \Illuminate\View\Factory::parentPlaceholder('js'); ?>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="<?php echo e(asset('dashboard/plugins/notify/js/notifIt.js')); ?>"></script>
    <script src="<?php echo e(asset('dashboard/plugins/notify/js/notifit-custom.js')); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        $(document).ready(function() {
            // Initialize animations
            AOS.init({
                duration: 800,
                easing: 'ease-out-quad',
                once: true
            });

            // Stock Status Chart
            if ($('#stockChart').length) {
                var options = {
                    series: [{
                        name: 'عدد الأدوية',
                        data: [
                            <?php echo e($inStockForChart ?? 0); ?>,
                            <?php echo e($lowStockMedicationsCount ?? 0); ?>,
                            <?php echo e($outOfStockForChart ?? 0); ?>

                        ]
                    }],
                    chart: {
                        type: 'bar',
                        height: 300,
                        fontFamily: 'Tajawal, sans-serif',
                        toolbar: { show: false }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '55%',
                            endingShape: 'rounded',
                            borderRadius: 8
                        },
                    },
                    dataLabels: {
                        enabled: false
                    },
                    colors: ['#28a745', '#ffc107', '#dc3545'],
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ['transparent']
                    },
                    xaxis: {
                        categories: ['متوفرة', 'منخفضة', 'نفذت'],
                        labels: {
                            style: {
                                fontSize: '13px',
                                fontWeight: 500
                            }
                        }
                    },
                    yaxis: {
                        title: { text: 'عدد الأدوية' },
                        labels: {
                            formatter: function(val) {
                                return parseInt(val);
                            }
                        }
                    },
                    fill: {
                        opacity: 1
                    },
                    tooltip: {
                        y: {
                            formatter: function (val) {
                                return val + " صنف دواء"
                            }
                        }
                    }
                };

                var chart = new ApexCharts(document.querySelector("#stockChart"), options);
                chart.render();
            }

            // Show notifications
            <?php if(session('success')): ?>
                notif({
                    msg: `<i class="fas fa-check-circle me-2"></i><?php echo e(session('success')); ?>`,
                    type: "success",
                    position: "top",
                    width: 350,
                    height: 60,
                    autohide: true,
                    timeout: 5000
                });
            <?php endif; ?>

            <?php if(session('error')): ?>
                notif({
                    msg: `<i class="fas fa-exclamation-circle me-2"></i><?php echo e(session('error')); ?>`,
                    type: "error",
                    position: "top",
                    width: 350,
                    height: 60,
                    autohide: true,
                    timeout: 7000
                });
            <?php endif; ?>
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Dashboard.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH H:\Hospital_Sys\resources\views/Dashboard/dashboard_PharmacyManager/dashboard.blade.php ENDPATH**/ ?>