@extends('Dashboard.layouts.master')

@section('title')
    لوحة تحكم موظف المختبر
@endsection

@section('css')
    <!--  Owl-carousel css-->
    <link href="{{ URL::asset('Dashboard/plugins/owl-carousel/owl.carousel.css') }}" rel="stylesheet" />
    <!-- Maps css -->
    <link href="{{ URL::asset('Dashboard/plugins/jqvmap/jqvmap.min.css') }}" rel="stylesheet">
    <!-- Custom Dashboard CSS -->
    <style>
        .stat-card {
            border-radius: 15px;
            box-shadow: 0 4px 20px 0 rgba(0, 0, 0, 0.1);
            transition: transform .3s ease, box-shadow .3s ease;
            color: #fff;
            overflow: hidden;
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
            /* حجم الأيقونة */
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

        .bg-custom-info {
            background-color: #17a2b8 !important;
        }

        /* لون مختلف للمختبر */
        .bg-custom-orange {
            background-color: #fd7e14 !important;
        }

        .bg-custom-teal {
            background-color: #20c997 !important;
        }

        .table-custom thead th {
            background-color: #f8f9fa;
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

        #monthlyLabsChart {
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
                <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">لوحة تحكم موظف المختبر</h2>
                <p class="mg-b-0">مرحباً بعودتك، {{ $employeeName }}!</p>
            </div>
        </div>
    </div>
    <!-- /breadcrumb -->
@endsection

@section('content')
    <!-- row -->
    <div class="row row-sm">
        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-4">
            <div class="card stat-card bg-custom-info"> {{-- لون مختلف --}}
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 style="color: black">إجمالي طلبات المختبر</h6>
                            <h4 style="color: black">{{ $totalLabs }}</h4>
                        </div>
                        <i class="fas fa-vials stat-icon"></i> {{-- أيقونة للمختبر --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-4">
            <div class="card stat-card bg-custom-orange"> {{-- لون مختلف --}}
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 style="color: black">طلبات تحت الإجراء</h6>
                            <h4 style="color: black">{{ $pendingLabs }}</h4>
                        </div>
                        <i class="fas fa-flask stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6 col-md-6 col-sm-12 mb-4">
            <div class="card stat-card bg-custom-teal"> {{-- لون مختلف --}}
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 style="color: black">طلبات مكتملة</h6>
                            <h4 style="color: black">{{ $completedLabs }}</h4>
                        </div>
                        <i class="fas fa-check-double stat-icon"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- row closed -->

    <!-- Row for Charts and Recent Activities -->
    <div class="row row-sm">
        <!-- Monthly Labs Chart -->
        <div class="col-xl-7 col-lg-12 mb-4">
            <div class="card">
                <div class="card-header pb-0">
                    <h5 class="card-title mb-0">طلبات المختبر الشهرية ({{ date('Y') }})</h5>
                </div>
                <div class="card-body">
                    <canvas id="monthlyLabsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Labs Table -->
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
                                @forelse($latestLabs as $lab)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $lab->patient->name ?? 'غير متوفر' }}</td>
                                        <td>{{ $lab->doctor->name ?? 'غير متوفر' }}</td>
                                        <td>
                                            @if ($lab->case == 0)
                                                <span class="badge badge-pill badge-warning">تحت الإجراء</span>
                                            @else
                                                <span class="badge badge-pill badge-success">مكتملة</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($lab->case == 0)
                                                <a href="{{ route('laboratorie_employee.invoices_laboratorie_employee.index', $lab->id) }}"
                                                    class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-microscope"></i>
                                                </a>
                                            @else
                                                <a href="{{ route('laboratorie_employee.completed_invoicess', $lab->id) }}"
                                                    class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-microscope"></i>
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
    {{-- <script src="https://kit.fontawesome.com/YOUR_KIT_ID.js" crossorigin="anonymous"></script> --}}

    <script>
        $(function() {
            'use strict';

            // Chart for Monthly Labs
            if ($('#monthlyLabsChart').length) {
                var ctxMonthlyLabs = document.getElementById('monthlyLabsChart').getContext('2d');
                new Chart(ctxMonthlyLabs, {
                    type: 'line', //  يمكنك استخدام 'bar' أو 'line'
                    data: {
                        labels: @json($monthLabels),
                        datasets: [{
                            label: 'عدد الطلبات',
                            data: @json($monthData),
                            backgroundColor: 'rgba(23, 162, 184, 0.2)', // لون أزرق مائي مع شفافية
                            borderColor: 'rgba(23, 162, 184, 1)',
                            borderWidth: 2,
                            pointBackgroundColor: 'rgba(23, 162, 184, 1)',
                            pointBorderColor: '#fff',
                            pointHoverBackgroundColor: '#fff',
                            pointHoverBorderColor: 'rgba(23, 162, 184, 1)',
                            tension: 0.3 // لجعل الخط منحنيًا قليلاً
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        legend: {
                            display: false
                        },
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    precision: 0
                                }
                            }],
                            xAxes: [{
                                ticks: {
                                    autoSkip: false
                                }
                            }]
                        },
                        tooltips: {
                            mode: 'index',
                            intersect: false,
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
                        },
                        hover: {
                            mode: 'nearest',
                            intersect: true
                        }
                    }
                });
            }

            // إذا كنت تستخدم الـ Sparkline charts في القالب الأصلي، يمكنك تهيئتها هنا ببيانات ديناميكية
            // مثال (مع بيانات ثابتة كما في القالب الأصلي، يجب استبدالها):
            // $("#compositeline").sparkline('html', { type: 'line', width: '100%', height: '40',lineColor: 'rgba(255,255,255,0.5)', fillColor: 'rgba(255,255,255,0.2)', spotColor:false, minSpotColor:false,maxSpotColor:false,highlightSpotColor: undefined,highlightLineColor: undefined});
            // $("#compositeline2").sparkline('html', { type: 'line', width: '100%', height: '40',lineColor: 'rgba(255,255,255,0.5)', fillColor: 'rgba(255,255,255,0.2)', spotColor:false, minSpotColor:false,maxSpotColor:false,highlightSpotColor: undefined,highlightLineColor: undefined});
            // $("#compositeline3").sparkline('html', { type: 'line', width: '100%', height: '40',lineColor: 'rgba(255,255,255,0.5)', fillColor: 'rgba(255,255,255,0.2)', spotColor:false, minSpotColor:false,maxSpotColor:false,highlightSpotColor: undefined,highlightLineColor: undefined});
        });
    </script>

    {{-- باقي ملفات JS من القالب إذا كنت تحتاجها --}}
    <script src="{{ URL::asset('Dashboard/plugins/raphael/raphael.min.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/jquery.flot/jquery.flot.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/jquery.flot/jquery.flot.pie.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/jquery.flot/jquery.flot.resize.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/jquery.flot/jquery.flot.categories.js') }}"></script>
    {{-- <script src="{{ URL::asset('Dashboard/js/dashboard.sampledata.js') }}"></script> --}}
    {{-- <script src="{{ URL::asset('Dashboard/js/chart.flot.sampledata.js') }}"></script> --}}
    <script src="{{ URL::asset('Dashboard/js/apexcharts.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/js/modal-popup.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/js/index.js') }}"></script>
    {{-- <script src="{{ URL::asset('Dashboard/js/jquery.vmap.sampledata.js') }}"></script> --}}
@endsection
