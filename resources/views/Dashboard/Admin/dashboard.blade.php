@extends('Dashboard.layouts.master')
@section('css')
    <!--  Owl-carousel css-->
    <link href="{{ URL::asset('Dashboard/plugins/owl-carousel/owl.carousel.css') }}" rel="stylesheet" />
    <!-- Maps css -->
    <link href="{{ URL::asset('Dashboard/plugins/jqvmap/jqvmap.min.css') }}" rel="stylesheet">
@endsection
@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="left-content">
            <div>
                <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">{{ trans('Admin.Welcome Admin') }}</h2>
            </div>
        </div>

    </div>
    <!-- /breadcrumb -->
@endsection
@section('content')
    <!-- row -->
    <div class="row row-sm">
        <!-- بطاقة الأطباء -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12 mb-3">
            <div class="card overflow-hidden sales-card bg-primary-gradient animate__animated animate__fadeInLeft">
                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 tx-12 text-white">الأطباء</h6>
                        <div class="dropdown">
                            <button class="btn btn-xs btn-outline-light dropdown-toggle" type="button" id="doctorDropdown"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="doctorDropdown">
                                <a class="dropdown-item" href="{{ route('admin.Doctors.index') }}">عرض الكل</a>
                                <a class="dropdown-item" href="{{ route('admin.Doctors.create') }}">إضافة جديد</a>
                            </div>
                        </div>
                    </div>
                    <div class="pb-0 mt-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="tx-20 font-weight-bold mb-1 text-white">
                                    {{ number_format(App\Models\Doctor::count()) }}
                                </h4>
                                <p class="mb-0 tx-12 text-white op-7">
                                    <i class="fas fa-arrow-up tx-11"></i>
                                    @php
                                        $lastMonth = App\Models\Doctor::where(
                                            'created_at',
                                            '>=',
                                            now()->subDays(30),
                                        )->count();
                                        $change =
                                            $lastMonth > 0
                                                ? round(((App\Models\Doctor::count() - $lastMonth) / $lastMonth) * 100)
                                                : 100;
                                    @endphp
                                    {{ $change }}% عن الشهر الماضي
                                </p>
                            </div>
                            <span class="float-right my-auto">
                                <i class="fas fa-user-md text-white" style="font-size: 2.5rem; opacity: 0.7;"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-primary-dark py-2">
                    <small class="text-white">
                        <i class="far fa-calendar-alt mr-1"></i>
                        آخر تحديث: {{ now()->format('H:i') }}
                    </small>
                </div>
            </div>
        </div>

        <!-- بطاقة المرضى -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12 mb-3">
            <div class="card overflow-hidden sales-card bg-danger-gradient animate__animated animate__fadeInLeft"
                style="animation-delay: 0.1s">
                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 tx-12 text-white">المرضى</h6>
                        <div class="dropdown">
                            <button class="btn btn-xs btn-outline-light dropdown-toggle" type="button" id="patientDropdown"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="patientDropdown">
                                <a class="dropdown-item" href="{{ route('admin.Patients.index') }}">عرض الكل</a>
                                <a class="dropdown-item" href="{{ route('admin.Patients.create') }}">إضافة جديد</a>
                            </div>
                        </div>
                    </div>
                    <div class="pb-0 mt-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="tx-20 font-weight-bold mb-1 text-white">
                                    {{ number_format(App\Models\Patient::count()) }}
                                </h4>
                                <p class="mb-0 tx-12 text-white op-7">
                                    <i class="fas fa-arrow-up tx-11"></i>
                                    @php
                                        $lastMonth = App\Models\Patient::where(
                                            'created_at',
                                            '>=',
                                            now()->subDays(30),
                                        )->count();
                                        $change =
                                            $lastMonth > 0
                                                ? round(((App\Models\Patient::count() - $lastMonth) / $lastMonth) * 100)
                                                : 100;
                                    @endphp
                                    {{ $change }}% عن الشهر الماضي
                                </p>
                            </div>
                            <span class="float-right my-auto">
                                <i class="fas fa-procedures text-white" style="font-size: 2.5rem; opacity: 0.7;"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-danger-dark py-2">
                    <small class="text-white">
                        <i class="fas fa-user-injured mr-1"></i>
                        {{ App\Models\PatientAdmission::whereNull('discharge_date')->count() }} حالات دخول حالية
                    </small>
                </div>
            </div>
        </div>

        <!-- بطاقة الأقسام -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12 mb-3">
            <div class="card overflow-hidden sales-card bg-success-gradient animate__animated animate__fadeInLeft"
                style="animation-delay: 0.2s">
                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 tx-12 text-white">الأقسام</h6>
                        <div class="dropdown">
                            <button class="btn btn-xs btn-outline-light dropdown-toggle" type="button" id="sectionDropdown"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="sectionDropdown">
                                <a class="dropdown-item" href="{{ route('admin.Sections.index') }}">عرض الكل</a>
                                <a class="dropdown-item" href="{{ route('admin.Sections.create') }}">إضافة جديد</a>
                            </div>
                        </div>
                        
                    </div>
                    <div class="pb-0 mt-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="tx-20 font-weight-bold mb-1 text-white">
                                    {{ number_format(App\Models\Section::count()) }}
                                </h4>
                                <p class="mb-0 tx-12 text-white op-7">
                                    <i class="fas fa-door-open mr-1"></i>
                                    {{ App\Models\Room::count() }} غرفة متاحة
                                </p>
                            </div>
                            <span class="float-right my-auto">
                                <i class="fas fa-hospital-alt text-white" style="font-size: 2.5rem; opacity: 0.7;"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-success-dark py-2">
                    <small class="text-white">
                        <i class="fas fa-bed mr-1"></i>
                        {{ App\Models\Bed::where('status', 'available')->count() }} سرير متاح
                    </small>
                </div>
            </div>
        </div>

        <!-- بطاقة الغرف -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12 mb-3">
            <div class="card overflow-hidden sales-card bg-info-gradient animate__animated animate__fadeInLeft"
                style="animation-delay: 0.3s">
                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 tx-12 text-white">الغرف</h6>
                        <div class="dropdown">
                            <button class="btn btn-xs btn-outline-light dropdown-toggle" type="button" id="roomDropdown"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="roomDropdown">
                                <a class="dropdown-item" href="{{ route('admin.rooms.index') }}">عرض الكل</a>
                                <a class="dropdown-item" href="{{ route('admin.rooms.create') }}">إضافة جديد</a>
                            </div>
                        </div>
                    </div>
                    <div class="pb-0 mt-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="tx-20 font-weight-bold mb-1 text-white">
                                    {{ number_format(App\Models\Room::count()) }}
                                </h4>
                                <p class="mb-0 tx-12 text-white op-7">
                                    <span class="badge bg-white text-success mr-1">
                                        {{ App\Models\Room::where('status', 'available')->count() }} متاحة
                                    </span>
                                    <span class="badge bg-white text-warning">
                                        {{ App\Models\Room::where('status', '!=', 'available')->count() }} مشغولة
                                    </span>
                                </p>
                            </div>
                            <span class="float-right my-auto">
                                <i class="fas fa-door-closed text-white" style="font-size: 2.5rem; opacity: 0.7;"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-info-dark py-2">
                    <small class="text-white">
                        <i class="fas fa-chart-pie mr-1"></i>
                        {{ round((App\Models\Room::where('status', 'available')->count() / max(App\Models\Room::count(), 1)) * 100) }}%
                        نسبة الإشغال
                    </small>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                // تأثير تحريك البطاقات عند التمرير إليها
                function animateCards() {
                    $('.sales-card').each(function() {
                        var cardTop = $(this).offset().top;
                        var scrollTop = $(window).scrollTop();
                        var windowHeight = $(window).height();

                        if (scrollTop + windowHeight > cardTop + 100) {
                            $(this).addClass('animate__fadeInUp');
                        }
                    });
                }

                // تشغيل عند التحميل وعند التمرير
                animateCards();
                $(window).scroll(animateCards);

                // تحديث الإحصاءات كل دقيقة (اختياري)
                setInterval(function() {
                    $.get('{{ route('dashboard.admin') }}', function(data) {
                        // يمكنك هنا تحديث الأرقام دون إعادة تحميل الصفحة
                        // هذا يتطلب إنشاء route وcontroller خاص بالإحصاءات
                    });
                }, 60000);
            });
        </script>
    @endpush

    <style>
        .sales-card {
            border-radius: 12px;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .sales-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .bg-primary-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .bg-danger-gradient {
            background: linear-gradient(135deg, #f54ea2 0%, #ff7676 100%);
        }

        .bg-success-gradient {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
        }

        .bg-info-gradient {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }

        .bg-primary-dark {
            background-color: rgba(0, 0, 0, 0.1);
        }

        .bg-danger-dark {
            background-color: rgba(0, 0, 0, 0.1);
        }

        .bg-success-dark {
            background-color: rgba(0, 0, 0, 0.1);
        }

        .bg-info-dark {
            background-color: rgba(0, 0, 0, 0.1);
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .dropdown-item {
            padding: 0.5rem 1.5rem;
            font-size: 0.85rem;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
            color: #333;
        }

        .badge {
            font-weight: 500;
            padding: 0.35em 0.65em;
            font-size: 0.75em;
        }
    </style>
    <!-- صف جديد للبطاقات الإضافية -->
    <div class="row row-sm">
        <!-- بطاقة الأسرة المتاحة -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12 mb-3">
            <div class="card overflow-hidden sales-card bg-teal-gradient animate__animated animate__fadeInUp">
                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 tx-12 text-white">الأسرة المتاحة</h6>
                        <div class="dropdown">
                            <button class="btn btn-xs btn-outline-light dropdown-toggle" type="button" id="bedDropdown"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="bedDropdown">
                                <a class="dropdown-item"
                                    href="{{ route('admin.beds.index', ['bed_status_filter' => 'available']) }}">
                                    <i class="fas fa-filter mr-1"></i> تصفية حسب المتاحة
                                </a>
                                <a class="dropdown-item" href="{{ route('admin.beds.create') }}">
                                    <i class="fas fa-plus mr-1"></i> إضافة سرير جديد
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="pb-0 mt-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="tx-20 font-weight-bold mb-1 text-white">
                                    {{ number_format(App\Models\Bed::where('status', 'available')->count()) }}
                                </h4>
                                <p class="mb-0 tx-12 text-white op-7">
                                    <span class="badge bg-white text-teal mr-1">
                                        {{ round((App\Models\Bed::where('status', 'available')->count() / max(App\Models\Bed::count(), 1)) * 100) }}%
                                        من الإجمالي
                                    </span>
                                </p>
                            </div>
                            <span class="float-right my-auto">
                                <i class="fas fa-bed text-white" style="font-size: 2.5rem; opacity: 0.7;"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-teal-dark py-2 d-flex justify-content-between">
                    <small class="text-white">
                        <i class="fas fa-arrow-up mr-1"></i>
                        @php
                            $occupiedBeds = App\Models\Bed::where('status', 'occupied')->count();
                            $totalBeds = App\Models\Bed::count();
                            $occupancyRate = $totalBeds > 0 ? round(($occupiedBeds / $totalBeds) * 100) : 0;
                        @endphp
                        نسبة الإشغال: {{ $occupancyRate }}%
                    </small>
                    <small>
                        <a href="{{ route('admin.beds.index') }}" class="text-white">
                            <i class="fas fa-external-link-alt mr-1"></i> عرض الكل
                        </a>
                    </small>
                </div>
            </div>
        </div>

        <!-- بطاقة المرضى المقيمين -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12 mb-3">
            <div class="card overflow-hidden sales-card bg-purple-gradient animate__animated animate__fadeInUp"
                style="animation-delay: 0.1s">
                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 tx-12 text-white">المرضى المقيمون</h6>
                        <div class="dropdown">
                            <button class="btn btn-xs btn-outline-light dropdown-toggle" type="button"
                                id="admissionDropdown" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="admissionDropdown">
                                <a class="dropdown-item" href="{{ route('admin.patient_admissions.index') }}">
                                    <i class="fas fa-list mr-1"></i> سجل الدخول
                                </a>
                                <a class="dropdown-item" href="{{ route('admin.patient_admissions.create') }}">
                                    <i class="fas fa-plus-circle mr-1"></i> تسجيل دخول جديد
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="pb-0 mt-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="tx-20 font-weight-bold mb-1 text-white">
                                    {{ number_format(App\Models\PatientAdmission::where('status', 'admitted')->whereNull('discharge_date')->count()) }}
                                </h4>
                                <p class="mb-0 tx-12 text-white op-7">
                                    <span class="badge bg-white text-purple mr-1">
                                        @php
                                            $icuPatients = App\Models\PatientAdmission::whereHas('bed.room', function (
                                                $q,
                                            ) {
                                                $q->where('type', 'icu_room');
                                            })
                                                ->whereNull('discharge_date')
                                                ->count();
                                        @endphp
                                        {{ $icuPatients }} في العناية المركزة
                                    </span>
                                </p>
                            </div>
                            <span class="float-right my-auto">
                                <i class="fas fa-hospital-user text-white" style="font-size: 2.5rem; opacity: 0.7;"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-purple-dark py-2 d-flex justify-content-between">
                    <small class="text-white">
                        <i class="fas fa-clock mr-1"></i>
                        @php
                            $avgStay = App\Models\PatientAdmission::whereNotNull('discharge_date')->avg(
                                \DB::raw('TIMESTAMPDIFF(DAY, admission_date, discharge_date)'),
                            );
                        @endphp
                        متوسط الإقامة: {{ round($avgStay) }} يوم
                    </small>
                    <small>
                        <a href="#" class="text-white">
                            <i class="fas fa-clipboard-list mr-1"></i> التفاصيل
                        </a>
                    </small>
                </div>
            </div>
        </div>

        <!-- بطاقة إجمالي الأسرة -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-xm-12 mb-3">
            <div class="card overflow-hidden sales-card bg-secondary-gradient animate__animated animate__fadeInUp"
                style="animation-delay: 0.2s">
                <div class="pl-3 pt-3 pr-3 pb-2 pt-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 tx-12 text-white">إجمالي الأسرة</h6>
                        <div class="dropdown">
                            <button class="btn btn-xs btn-outline-light dropdown-toggle" type="button"
                                id="totalBedsDropdown" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="totalBedsDropdown">
                                <a class="dropdown-item" href="{{ route('admin.beds.index') }}">
                                    <i class="fas fa-list mr-1"></i> عرض الكل
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-chart-bar mr-1"></i> تقرير الإشغال
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="pb-0 mt-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="tx-20 font-weight-bold mb-1 text-white">
                                    {{ number_format(App\Models\Bed::count()) }}
                                </h4>
                                <p class="mb-0 tx-12 text-white op-7">
                                    <span class="badge bg-white text-secondary mr-1">
                                        {{ App\Models\Bed::where('type', 'icu_bed')->count() }} سرير عناية مركزة
                                    </span>
                                </p>
                            </div>
                            <span class="float-right my-auto">
                                <i class="fas fa-procedures text-white" style="font-size: 2.5rem; opacity: 0.7;"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-secondary-dark py-2 d-flex justify-content-between">
                    <small class="text-white">
                        <i class="fas fa-map-marker-alt mr-1"></i>
                        موزعة على {{ App\Models\Room::count() }} غرفة
                    </small>
                    <small>
                        <a href="{{ route('admin.rooms.index') }}" class="text-white">
                            <i class="fas fa-door-open mr-1"></i> عرض الغرف
                        </a>
                    </small>
                </div>
            </div>
        </div>


    </div>

    @push('styles')
        <style>
            .bg-teal-gradient {
                background: linear-gradient(135deg, #0cebeb 0%, #20e3b2 50%, #29ffc6 100%);
            }

            .bg-purple-gradient {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }

            .bg-orange-gradient {
                background: linear-gradient(135deg, #f46b45 0%, #eea849 100%);
            }

            .bg-secondary-gradient {
                background: linear-gradient(135deg, #868f96 0%, #596164 100%);
            }

            .bg-teal-dark {
                background-color: rgba(0, 131, 143, 0.2);
            }

            .bg-purple-dark {
                background-color: rgba(102, 126, 234, 0.2);
            }

            .bg-orange-dark {
                background-color: rgba(244, 107, 69, 0.2);
            }

            .bg-secondary-dark {
                background-color: rgba(134, 143, 150, 0.2);
            }

            .occupancy-gauge {
                width: 100%;
                height: 6px;
                background: rgba(255, 255, 255, 0.2);
                border-radius: 3px;
                margin-top: 8px;
                overflow: hidden;
            }

            .occupancy-progress {
                height: 100%;
                background: white;
                border-radius: 3px;
                transition: width 1s ease;
            }

            .card-footer small a:hover {
                text-decoration: underline;
                opacity: 0.9;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            $(document).ready(function() {
                // رسم مؤشرات الإشغال
                function drawOccupancyIndicators() {
                    $('.occupancy-progress').each(function() {
                        var percent = $(this).data('percent');
                        $(this).css('width', percent + '%');
                    });
                }

                // تحديث البطاقات كل دقيقة (اختياري)
                function updateStatsCards() {
                    $.get('{{ route('dashboard.admin') }}', function(data) {
                        $('#available-beds-count').text(data.available_beds);
                        $('#total-beds-count').text(data.total_beds);
                        $('#current-patients-count').text(data.current_patients);
                        $('#occupancy-rate').text(data.occupancy_rate + '%');

                        // تحديث مؤشرات الإشغال
                        $('.occupancy-progress').data('percent', data.occupancy_rate);
                        drawOccupancyIndicators();
                    });
                }

                // التشغيل الأولي
                drawOccupancyIndicators();

                // التحديث التلقائي (اختياري)
                setInterval(updateStatsCards, 60000);
            });
        </script>
    @endpush

    <!-- row opened -->
    <div class="row row-sm">
        <div class="col-md-12 col-lg-12 col-xl-7">
            <div class="card">
                <div class="card-header bg-transparent pd-b-0 pd-t-20 bd-b-0">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-0">Order status</h4>
                        <i class="mdi mdi-dots-horizontal text-gray"></i>
                    </div>
                    <p class="tx-12 text-muted mb-0">Order Status and Tracking. Track your order from ship date to arrival.
                        To begin, enter your order number.</p>
                </div>
                <div class="card-body">
                    <div class="total-revenue">
                        <div>
                            <h4>120,750</h4>
                            <label><span class="bg-primary"></span>success</label>
                        </div>
                        <div>
                            <h4>56,108</h4>
                            <label><span class="bg-danger"></span>Pending</label>
                        </div>
                        <div>
                            <h4>32,895</h4>
                            <label><span class="bg-warning"></span>Failed</label>
                        </div>
                    </div>
                    <div id="bar" class="sales-bar mt-4"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 col-xl-5">
            <div class="card card-dashboard-map-one">
                <label class="main-content-label">Sales Revenue by Customers in USA</label>
                <span class="d-block mg-b-20 text-muted tx-12">Sales Performance of all states in the United States</span>
                <div class="">
                    <div class="vmap-wrapper ht-180" id="vmap2"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- row closed -->

    <!-- row opened -->
    <div class="row row-sm">
        <div class="col-xl-4 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header pb-1">
                    <h3 class="card-title mb-2">Recent Customers</h3>
                    <p class="tx-12 mb-0 text-muted">A customer is an individual or business that purchases the goods
                        service has evolved to include real-time</p>
                </div>
                <div class="card-body p-0 customers mt-1">
                    <div class="list-group list-lg-group list-group-flush">
                        <div class="list-group-item list-group-item-action" href="#">
                            <div class="media mt-0">
                                <img class="avatar-lg rounded-circle ml-3 my-auto"
                                    src="{{ URL::asset('Dashboard/img/faces/3.jpg') }}" alt="Image description">
                                <div class="media-body">
                                    <div class="d-flex align-items-center">
                                        <div class="mt-0">
                                            <h5 class="mb-1 tx-15">Samantha Melon</h5>
                                            <p class="mb-0 tx-13 text-muted">User ID: #1234 <span
                                                    class="text-success ml-2">Paid</span></p>
                                        </div>
                                        <span class="mr-auto wd-45p fs-16 mt-2">
                                            <div id="spark1" class="wd-100p"></div>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item list-group-item-action" href="#">
                            <div class="media mt-0">
                                <img class="avatar-lg rounded-circle ml-3 my-auto"
                                    src="{{ URL::asset('Dashboard/img/faces/11.jpg') }}" alt="Image description">
                                <div class="media-body">
                                    <div class="d-flex align-items-center">
                                        <div class="mt-1">
                                            <h5 class="mb-1 tx-15">Jimmy Changa</h5>
                                            <p class="mb-0 tx-13 text-muted">User ID: #1234 <span
                                                    class="text-danger ml-2">Pending</span></p>
                                        </div>
                                        <span class="mr-auto wd-45p fs-16 mt-2">
                                            <div id="spark2" class="wd-100p"></div>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item list-group-item-action" href="#">
                            <div class="media mt-0">
                                <img class="avatar-lg rounded-circle ml-3 my-auto"
                                    src="{{ URL::asset('Dashboard/img/faces/17.jpg') }}" alt="Image description">
                                <div class="media-body">
                                    <div class="d-flex align-items-center">
                                        <div class="mt-1">
                                            <h5 class="mb-1 tx-15">Gabe Lackmen</h5>
                                            <p class="mb-0 tx-13 text-muted">User ID: #1234<span
                                                    class="text-danger ml-2">Pending</span></p>
                                        </div>
                                        <span class="mr-auto wd-45p fs-16 mt-2">
                                            <div id="spark3" class="wd-100p"></div>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item list-group-item-action" href="#">
                            <div class="media mt-0">
                                <img class="avatar-lg rounded-circle ml-3 my-auto"
                                    src="{{ URL::asset('Dashboard/img/faces/15.jpg') }}" alt="Image description">
                                <div class="media-body">
                                    <div class="d-flex align-items-center">
                                        <div class="mt-1">
                                            <h5 class="mb-1 tx-15">Manuel Labor</h5>
                                            <p class="mb-0 tx-13 text-muted">User ID: #1234<span
                                                    class="text-success ml-2">Paid</span></p>
                                        </div>
                                        <span class="mr-auto wd-45p fs-16 mt-2">
                                            <div id="spark4" class="wd-100p"></div>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item list-group-item-action br-br-7 br-bl-7" href="#">
                            <div class="media mt-0">
                                <img class="avatar-lg rounded-circle ml-3 my-auto"
                                    src="{{ URL::asset('Dashboard/img/faces/6.jpg') }}" alt="Image description">
                                <div class="media-body">
                                    <div class="d-flex align-items-center">
                                        <div class="mt-1">
                                            <h5 class="mb-1 tx-15">Sharon Needles</h5>
                                            <p class="b-0 tx-13 text-muted mb-0">User ID: #1234<span
                                                    class="text-success ml-2">Paid</span></p>
                                        </div>
                                        <span class="mr-auto wd-45p fs-16 mt-2">
                                            <div id="spark5" class="wd-100p"></div>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-12 col-lg-6">
            <div class="card">
                <div class="card-header pb-1">
                    <h3 class="card-title mb-2">Sales Activity</h3>
                    <p class="tx-12 mb-0 text-muted">Sales activities are the tactics that salespeople use to achieve their
                        goals and objective</p>
                </div>
                <div class="product-timeline card-body pt-2 mt-1">
                    <ul class="timeline-1 mb-0">
                        <li class="mt-0"> <i class="ti-pie-chart bg-primary-gradient text-white product-icon"></i> <span
                                class="font-weight-semibold mb-4 tx-14 ">Total Products</span> <a href="#"
                                class="float-left tx-11 text-muted">3 days ago</a>
                            <p class="mb-0 text-muted tx-12">1.3k New Products</p>
                        </li>
                        <li class="mt-0"> <i
                                class="mdi mdi-cart-outline bg-danger-gradient text-white product-icon"></i> <span
                                class="font-weight-semibold mb-4 tx-14 ">Total Sales</span> <a href="#"
                                class="float-left tx-11 text-muted">35 mins ago</a>
                            <p class="mb-0 text-muted tx-12">1k New Sales</p>
                        </li>
                        <li class="mt-0"> <i class="ti-bar-chart-alt bg-success-gradient text-white product-icon"></i>
                            <span class="font-weight-semibold mb-4 tx-14 ">Toatal Revenue</span> <a href="#"
                                class="float-left tx-11 text-muted">50 mins ago</a>
                            <p class="mb-0 text-muted tx-12">23.5K New Revenue</p>
                        </li>
                        <li class="mt-0"> <i class="ti-wallet bg-warning-gradient text-white product-icon"></i> <span
                                class="font-weight-semibold mb-4 tx-14 ">Toatal Profit</span> <a href="#"
                                class="float-left tx-11 text-muted">1 hour ago</a>
                            <p class="mb-0 text-muted tx-12">3k New profit</p>
                        </li>
                        <li class="mt-0"> <i class="si si-eye bg-purple-gradient text-white product-icon"></i> <span
                                class="font-weight-semibold mb-4 tx-14 ">Customer Visits</span> <a href="#"
                                class="float-left tx-11 text-muted">1 day ago</a>
                            <p class="mb-0 text-muted tx-12">15% increased</p>
                        </li>
                        <li class="mt-0 mb-0"> <i class="icon-note icons bg-primary-gradient text-white product-icon"></i>
                            <span class="font-weight-semibold mb-4 tx-14 ">Customer Reviews</span> <a href="#"
                                class="float-left tx-11 text-muted">1 day ago</a>
                            <p class="mb-0 text-muted tx-12">1.5k reviews</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-12 col-lg-6">
            <div class="card">
                <div class="card-header pb-0">
                    <h3 class="card-title mb-2">Recent Orders</h3>
                    <p class="tx-12 mb-0 text-muted">An order is an investor's instructions to a broker or brokerage firm
                        to purchase or sell</p>
                </div>
                <div class="card-body sales-info ot-0 pt-0 pb-0">
                    <div id="chart" class="ht-150"></div>
                    <div class="row sales-infomation pb-0 mb-0 mx-auto wd-100p">
                        <div class="col-md-6 col">
                            <p class="mb-0 d-flex"><span class="legend bg-primary brround"></span>Delivered</p>
                            <h3 class="mb-1">5238</h3>
                            <div class="d-flex">
                                <p class="text-muted ">Last 6 months</p>
                            </div>
                        </div>
                        <div class="col-md-6 col">
                            <p class="mb-0 d-flex"><span class="legend bg-info brround"></span>Cancelled</p>
                            <h3 class="mb-1">3467</h3>
                            <div class="d-flex">
                                <p class="text-muted">Last 6 months</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card ">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center pb-2">
                                <p class="mb-0">Total Sales</p>
                            </div>
                            <h4 class="font-weight-bold mb-2">$7,590</h4>
                            <div class="progress progress-style progress-sm">
                                <div class="progress-bar bg-primary-gradient wd-80p" role="progressbar"
                                    aria-valuenow="78" aria-valuemin="0" aria-valuemax="78"></div>
                            </div>
                        </div>
                        <div class="col-md-6 mt-4 mt-md-0">
                            <div class="d-flex align-items-center pb-2">
                                <p class="mb-0">Active Users</p>
                            </div>
                            <h4 class="font-weight-bold mb-2">$5,460</h4>
                            <div class="progress progress-style progress-sm">
                                <div class="progress-bar bg-danger-gradient wd-75" role="progressbar" aria-valuenow="45"
                                    aria-valuemin="0" aria-valuemax="45"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- row close -->

    <!-- row opened -->
    <div class="row row-sm row-deck">
        <div class="col-md-12 col-lg-4 col-xl-4">
            <div class="card card-dashboard-eight pb-2">
                <h6 class="card-title">Your Top Countries</h6><span class="d-block mg-b-10 text-muted tx-12">Sales
                    performance revenue based by country</span>
                <div class="list-group">
                    <div class="list-group-item border-top-0">
                        <i class="flag-icon flag-icon-us flag-icon-squared"></i>
                        <p>United States</p><span>$1,671.10</span>
                    </div>
                    <div class="list-group-item">
                        <i class="flag-icon flag-icon-nl flag-icon-squared"></i>
                        <p>Netherlands</p><span>$1,064.75</span>
                    </div>
                    <div class="list-group-item">
                        <i class="flag-icon flag-icon-gb flag-icon-squared"></i>
                        <p>United Kingdom</p><span>$1,055.98</span>
                    </div>
                    <div class="list-group-item">
                        <i class="flag-icon flag-icon-ca flag-icon-squared"></i>
                        <p>Canada</p><span>$1,045.49</span>
                    </div>
                    <div class="list-group-item">
                        <i class="flag-icon flag-icon-in flag-icon-squared"></i>
                        <p>India</p><span>$1,930.12</span>
                    </div>
                    <div class="list-group-item border-bottom-0 mb-0">
                        <i class="flag-icon flag-icon-au flag-icon-squared"></i>
                        <p>Australia</p><span>$1,042.00</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-lg-8 col-xl-8">
            <div class="card card-table-two">
                <div class="d-flex justify-content-between">
                    <h4 class="card-title mb-1">Your Most Recent Earnings</h4>
                    <i class="mdi mdi-dots-horizontal text-gray"></i>
                </div>
                <span class="tx-12 tx-muted mb-3 ">This is your most recent earnings for today's date.</span>
                <div class="table-responsive country-table">
                    <table class="table table-striped table-bordered mb-0 text-sm-nowrap text-lg-nowrap text-xl-nowrap">
                        <thead>
                            <tr>
                                <th class="wd-lg-25p">Date</th>
                                <th class="wd-lg-25p tx-right">Sales Count</th>
                                <th class="wd-lg-25p tx-right">Earnings</th>
                                <th class="wd-lg-25p tx-right">Tax Witheld</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>05 Dec 2019</td>
                                <td class="tx-right tx-medium tx-inverse">34</td>
                                <td class="tx-right tx-medium tx-inverse">$658.20</td>
                                <td class="tx-right tx-medium tx-danger">-$45.10</td>
                            </tr>
                            <tr>
                                <td>06 Dec 2019</td>
                                <td class="tx-right tx-medium tx-inverse">26</td>
                                <td class="tx-right tx-medium tx-inverse">$453.25</td>
                                <td class="tx-right tx-medium tx-danger">-$15.02</td>
                            </tr>
                            <tr>
                                <td>07 Dec 2019</td>
                                <td class="tx-right tx-medium tx-inverse">34</td>
                                <td class="tx-right tx-medium tx-inverse">$653.12</td>
                                <td class="tx-right tx-medium tx-danger">-$13.45</td>
                            </tr>
                            <tr>
                                <td>08 Dec 2019</td>
                                <td class="tx-right tx-medium tx-inverse">45</td>
                                <td class="tx-right tx-medium tx-inverse">$546.47</td>
                                <td class="tx-right tx-medium tx-danger">-$24.22</td>
                            </tr>
                            <tr>
                                <td>09 Dec 2019</td>
                                <td class="tx-right tx-medium tx-inverse">31</td>
                                <td class="tx-right tx-medium tx-inverse">$425.72</td>
                                <td class="tx-right tx-medium tx-danger">-$25.01</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /row -->
    </div>
    </div>
    <!-- Container closed -->
@endsection
@section('js')
    <!--Internal  Chart.bundle js -->
    <script src="{{ URL::asset('Dashboard/plugins/chart.js/Chart.bundle.min.js') }}"></script>
    <!-- Moment js -->
    <script src="{{ URL::asset('Dashboard/plugins/raphael/raphael.min.js') }}"></script>
    <!--Internal  Flot js-->
    <script src="{{ URL::asset('Dashboard/plugins/jquery.flot/jquery.flot.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/jquery.flot/jquery.flot.pie.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/jquery.flot/jquery.flot.resize.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/jquery.flot/jquery.flot.categories.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/js/dashboard.sampledata.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/js/chart.flot.sampledata.js') }}"></script>
    <!--Internal Apexchart js-->
    <script src="{{ URL::asset('Dashboard/js/apexcharts.js') }}"></script>
    <!-- Internal Map -->
    <script src="{{ URL::asset('Dashboard/plugins/jqvmap/jquery.vmap.min.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/js/modal-popup.js') }}"></script>
    <!--Internal  index js -->
    <script src="{{ URL::asset('Dashboard/js/index.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/js/jquery.vmap.sampledata.js') }}"></script>
@endsection
