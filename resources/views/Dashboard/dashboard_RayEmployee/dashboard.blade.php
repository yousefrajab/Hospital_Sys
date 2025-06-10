@extends('Dashboard.layouts.master')

@section('title')
    لوحة تحكم موظف الأشعة
@endsection

@section('css')
    <!--  Owl-carousel css-->
    <link href="{{ URL::asset('Dashboard/plugins/owl-carousel/owl.carousel.css') }}" rel="stylesheet" />
    <!-- Maps css -->
    <link href="{{ URL::asset('Dashboard/plugins/jqvmap/jqvmap.min.css') }}" rel="stylesheet">
    <!-- Custom Dashboard CSS (يمكنك وضع التنسيقات المخصصة هنا أو في ملف منفصل) -->
    <style>
        .stat-card {
            border-radius: 15px;
            box-shadow: 0 4px 20px 0 rgba(0, 0, 0, 0.1);
            transition: transform .3s ease, box-shadow .3s ease;
            color: #fff;
            overflow: hidden;
            /* لإخفاء الـ span الخاص بالـ sparkline إذا لم يتم استخدامه بشكل جيد */
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px 0 rgba(0, 0, 0, 0.15);
        }

        .stat-card .card-body {
            padding: 20px;
            position: relative;
            z-index: 1;
        }

        .stat-card h6 {
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 8px;
            opacity: 0.9;
        }

        .stat-card h4 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0;
        }

        .stat-icon {
            font-size: 3rem;
            opacity: 0.2;
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            transition: opacity 0.3s ease;
        }

        .stat-card:hover .stat-icon {
            opacity: 0.3;
        }

        .bg-custom-primary {
            background-color: #007bff !important;
        }

        /* استبدل بألوانك المفضلة */
        .bg-custom-danger {
            background-color: #dc3545 !important;
        }

        .bg-custom-success {
            background-color: #28a745 !important;
        }

        .bg-custom-warning {
            background-color: #ffc107 !important;
            color: #212529 !important;
        }


        .table-custom thead th {
            background-color: #f8f9fa;
            /* لون أفتح لرأس الجدول */
            color: #495057;
            font-weight: 600;
            border-bottom-width: 1px;
            text-align: center;
        }

        .table-custom tbody td {
            vertical-align: middle;
            text-align: center;
        }

        .table-custom .badge {
            font-size: 0.85em;
            padding: 0.5em 0.75em;
        }

        .card-table-two {
            border-radius: 15px;
            box-shadow: 0 4px 20px 0 rgba(0, 0, 0, 0.08);
        }

        .card-table-two .card-header {
            background-color: transparent;
            border-bottom: 1px solid #e9ecef;
            padding: 1rem 1.25rem;
        }

        .card-table-two .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #333;
        }

        /* تخصيص بسيط للـ chart */
        #monthlyRaysChart {
            border-radius: 10px;
            padding: 15px;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.07);
        }
    </style>
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="left-content">
            <div>
                <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">لوحة تحكم موظف الأشعة</h2>
                <p class="mg-b-0">مرحباً بعودتك، {{ Auth::guard('ray_employee')->user()->name }}!</p>
            </div>
        </div>
        {{-- يمكنك إضافة عناصر هنا مثل زر "إضافة طلب جديد" إذا كان منطقياً --}}
    </div>
    <!-- /breadcrumb -->
@endsection

@section('content')
    <!-- row -->
    <div class="row row-sm">
        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-4">
            <div class="card stat-card bg-custom-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 style="color: black">إجمالي طلبات الأشعة</h6>
                            <h4 style="color: black">{{ $totalRays }}</h4>
                        </div>
                        <i class="fas fa-receipt stat-icon"></i> {{-- استبدل بأيقونة مناسبة --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-4">
            <div class="card stat-card bg-custom-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 style="color: black">طلبات تحت الإجراء</h6>
                            <h4 style="color: black">{{ $pendingRays }}</h4>
                        </div>
                        <i class="fas fa-hourglass-half stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-4">
            <div class="card stat-card bg-custom-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 style="color: black">طلبات مكتملة</h6>
                            <h4 style="color: black">{{ $completedRays }}</h4>
                        </div>
                        <i class="fas fa-check-circle stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- row closed -->

    <!-- Row for Charts and Recent Activities -->
    <div class="row row-sm">
        <!-- Monthly Rays Chart -->
        <div class="col-xl-7 col-lg-12 mb-4">
            <div class="card">
                <div class="card-header pb-0">
                    <h5 class="card-title mb-0">طلبات الأشعة الشهرية ({{ date('Y') }})</h5>
                </div>
                <div class="card-body">
                    <canvas id="monthlyRaysChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Rays Table -->
        <div class="col-xl-5 col-lg-12 mb-4">
            <div class="card card-table-two">
                <div class="card-header">
                    <h5 class="card-title mb-0">أحدث 5 طلبات</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-custom table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>المريض</th>
                                    <th>الطبيب</th>
                                    <th>الحالة</th>
                                    <th>عرض</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($latestRays as $ray)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $ray->patient->name ?? 'غير متوفر' }}</td>
                                        <td>{{ $ray->doctor->name ?? 'غير متوفر' }}</td>
                                        <td>
                                            @if ($ray->case == 0)
                                                <span class="badge badge-pill badge-warning">تحت الإجراء</span>
                                            @else
                                                <span class="badge badge-pill badge-success">مكتملة</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{-- افترض أن لديك مسار لعرض تفاصيل طلب الأشعة لموظف الأشعة --}}



                                            @if ($ray->case == 0)
                                                <a href="{{ route('ray_employee.invoices_ray_employee.index', $ray->id) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('ray_employee.completed_invoices', $ray->id) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center p-3">لا توجد طلبات حديثة.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /row -->
@endsection

@section('js')
    <!--Internal  Chart.bundle js -->
    <script src="{{ URL::asset('Dashboard/plugins/chart.js/Chart.bundle.min.js') }}"></script>
    {{-- تأكد من تضمين FontAwesome إذا كنت ستستخدم الأيقونات المقترحة --}}
    {{-- <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script> --}}


    <script>
        $(function() {
            'use strict';

            // Chart for Monthly Rays
            if ($('#monthlyRaysChart').length) {
                var ctxMonthly = document.getElementById('monthlyRaysChart').getContext('2d');
                new Chart(ctxMonthly, {
                    type: 'bar', // أو 'line'
                    data: {
                        labels: @json($monthLabels), // ['يناير', 'فبراير', ... ]
                        datasets: [{
                            label: 'عدد الطلبات',
                            data: @json($monthData), // [10, 20, ...]
                            backgroundColor: 'rgba(0, 123, 255, 0.5)', // لون أساسي مع شفافية
                            borderColor: 'rgba(0, 123, 255, 1)',
                            borderWidth: 1,
                            hoverBackgroundColor: 'rgba(0, 123, 255, 0.7)',
                            hoverBorderColor: 'rgba(0, 123, 255, 1)',
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false, // مهم لجعل الرسم البياني يأخذ ارتفاع الحاوية
                        legend: {
                            display: false // يمكن إظهارها إذا كان هناك أكثر من dataset
                        },
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    precision: 0 // لعرض أعداد صحيحة فقط
                                }
                            }],
                            xAxes: [{
                                ticks: {
                                    autoSkip: false // لعرض كل الشهور إذا كانت كثيرة
                                }
                            }]
                        },
                        tooltips: {
                            callbacks: {
                                label: function(tooltipItem, data) {
                                    var label = data.datasets[tooltipItem.datasetIndex].label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    label += tooltipItem.yLabel;
                                    return label;
                                }
                            }
                        }
                    }
                });
            }

            // يمكنك إبقاء الـ Sparkline charts إذا كنت تستخدمها وتعرف كيف تهيئها بالبيانات
            // أو إزالتها إذا كانت الأرقام الثابتة في الـ span هي مجرد placeholders
            // $("#compositeline").sparkline('html', { type: 'line', width: '100%', height: '40',lineColor: 'rgba(255,255,255,0.5)', fillColor: 'rgba(255,255,255,0.2)', });
            // $("#compositeline2").sparkline('html', { type: 'line', width: '100%', height: '40',lineColor: 'rgba(255,255,255,0.5)', fillColor: 'rgba(255,255,255,0.2)', });
            // $("#compositeline3").sparkline('html', { type: 'line', width: '100%', height: '40',lineColor: 'rgba(255,255,255,0.5)', fillColor: 'rgba(255,255,255,0.2)', });
        });
    </script>

    {{-- إذا كنت ستستخدم باقي ملفات JS من القالب الأصلي، أبقها --}}
    <script src="{{ URL::asset('Dashboard/plugins/raphael/raphael.min.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/jquery.flot/jquery.flot.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/jquery.flot/jquery.flot.pie.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/jquery.flot/jquery.flot.resize.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/jquery.flot/jquery.flot.categories.js') }}"></script>
    {{-- <script src="{{ URL::asset('Dashboard/js/dashboard.sampledata.js') }}"></script> --}} {{-- قد لا تحتاج هذا إذا كانت بياناتك ديناميكية --}}
    {{-- <script src="{{ URL::asset('Dashboard/js/chart.flot.sampledata.js') }}"></script> --}}
    <script src="{{ URL::asset('Dashboard/js/apexcharts.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/js/modal-popup.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/js/index.js') }}"></script> {{-- كن حذرًا، هذا قد يعيد تهيئة بعض الأشياء --}}
    {{-- <script src="{{ URL::asset('Dashboard/js/jquery.vmap.sampledata.js') }}"></script> --}}
@endsection
