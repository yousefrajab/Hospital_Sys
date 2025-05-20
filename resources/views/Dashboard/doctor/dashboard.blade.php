@extends('Dashboard.layouts.master')
@section('title', 'لوحة تحكم الطبيب')

@section('css')
    @parent
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    {{-- إذا كنت ستستخدم Sparklines --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/peity/3.3.0/jquery.peity.min.js"></script> --}}
    <style>
        :root {
            --bs-primary-rgb: 67, 97, 238;
            --bs-primary: rgb(var(--bs-primary-rgb));
            --bs-success-rgb: 25, 135, 84;
            --bs-success: rgb(var(--bs-success-rgb));
            --bs-danger-rgb: 220, 53, 69;
            --bs-danger: rgb(var(--bs-danger-rgb));
            --bs-warning-rgb: 255, 193, 7;
            --bs-warning: rgb(var(--bs-warning-rgb));
            --bs-info-rgb: 13, 202, 240;
            --bs-info: rgb(var(--bs-info-rgb));
            --bs-light-rgb: 248, 249, 252;
            --bs-dark-rgb: 33, 37, 41;
            --bs-body-bg: #f4f6f9;
            --bs-border-color: #dee2e6;
            --bs-card-border-radius: 0.75rem;
            --bs-card-box-shadow: 0 0.15rem 1.25rem rgba(58, 59, 69, 0.1);
            --admin-transition: all 0.3s ease-in-out;
        }
        .stat-card-enhanced {
            color: #fff;
            border-radius: var(--bs-card-border-radius);
            padding: 1.5rem 1.25rem;
            position: relative;
            overflow: hidden;
            transition: var(--admin-transition);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            margin-bottom: 1.5rem; /* للمسافة بين البطاقات */
        }
        .stat-card-enhanced:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }
        .stat-card-enhanced .stat-icon {
            position: absolute;
            top: 50%;
            left: 20px; /* RTL: right: 20px; left: auto; */
            transform: translateY(-50%) rotate(-15deg);
            font-size: 4rem;
            opacity: 0.15;
            transition: transform 0.4s ease-out;
        }
        .stat-card-enhanced:hover .stat-icon {
            transform: translateY(-50%) rotate(0deg) scale(1.1);
        }
        .stat-card-enhanced .stat-content {
            position: relative;
            z-index: 1;
        }
        .stat-card-enhanced .stat-title {
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            opacity: 0.9;
        }
        .stat-card-enhanced .stat-number {
            font-size: 2.25rem;
            font-weight: 700;
            line-height: 1.1;
            margin-bottom: 0.75rem;
        }
        .stat-card-enhanced .stat-description {
            font-size: 0.8rem;
            opacity: 0.8;
            margin-bottom: 0;
        }
        .stat-card-enhanced .stat-link {
            position: absolute;
            bottom: 10px;
            right: 15px; /* RTL: left: 15px; right: auto; */
            color: rgba(255,255,255,0.7);
            font-size: 0.75rem;
            text-decoration: none;
            transition: var(--admin-transition);
        }
        .stat-card-enhanced .stat-link:hover {
            color: #fff;
            transform: translateX(-3px); /* RTL: translateX(3px); */
        }
        .stat-card-enhanced .stat-link i { margin-right: 3px; /* RTL: margin-left */ }

        /* Background Gradients */
        .bg-primary-gradient-custom { background: linear-gradient(135deg, var(--bs-primary) 0%, #667eea 100%); }
        .bg-danger-gradient-custom { background: linear-gradient(135deg, rgb(var(--bs-danger-rgb)) 0%, #ff758c 100%); }
        .bg-success-gradient-custom { background: linear-gradient(135deg, rgb(var(--bs-success-rgb)) 0%, #2ad6a0 100%); }
        .bg-warning-gradient-custom { background: linear-gradient(135deg, rgb(var(--bs-warning-rgb)) 0%, #ffcd39 100%); }
        .bg-info-gradient-custom { background: linear-gradient(135deg, rgb(var(--bs-info-rgb)) 0%, #4dd0e1 100%); }
        .bg-purple-gradient-custom { background: linear-gradient(135deg, #6f42c1 0%, #9775fa 100%); }


        /* Sparkline (placeholder styling) */
        .sparkline-chart {
            position: absolute;
            bottom: 0;
            left: 0; /* RTL: right: 0; left: auto; */
            width: 100%;
            height: 40px; /* Adjust height as needed */
            opacity: 0.2;
            z-index: 0;
        }
    </style>
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto"><i class="fas fa-tachometer-alt text-primary me-2"></i>لوحة التحكم</h4>
                <span class="text-muted mt-1 tx-13 mx-2">/</span>
                <span class="text-muted mt-1 tx-13">نظرة عامة</span>
            </div>
        </div>
        {{-- يمكنك إضافة أي أزرار إجراءات سريعة هنا --}}
    </div>
    <!-- /breadcrumb -->
@endsection

@section('content')
    @include('Dashboard.messages_alert')

    <!-- Row-1: بطاقات الإحصائيات المحسنة -->
    <div class="row">
        {{-- بطاقة إجمالي الفواتير --}}
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12" data-aos="fade-up" data-aos-delay="100">
            <a href="{{ route('doctor.invoices.index') }}" class="text-decoration-none"> {{-- جعل البطاقة قابلة للنقر --}}
                <div class="stat-card-enhanced bg-primary-gradient-custom">
                    <i class="fas fa-file-invoice-dollar stat-icon"></i>
                    <div class="stat-content">
                        <p class="stat-title">إجمالي الفواتير</p>
                        <h2 class="stat-number">{{ App\Models\Invoice::where('doctor_id', auth()->user()->id)->count() }}</h2>
                        <p class="stat-description">كل الفواتير المسجلة باسمك.</p>
                    </div>
                    <span class="stat-link">عرض الكل <i class="fas fa-arrow-circle-left"></i></span> {{-- RTL: fa-arrow-circle-right --}}
                    {{-- <span class="sparkline-chart" data-sparkline="5,9,5,6,4,12,18,14,10,15"></span> --}}
                </div>
            </a>
        </div>

        {{-- بطاقة الفواتير تحت الإجراء --}}
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12" data-aos="fade-up" data-aos-delay="200">
             <a href="{{ route('doctor.reviewInvoices') }}" class="text-decoration-none">
                <div class="stat-card-enhanced bg-danger-gradient-custom">
                    <i class="fas fa-hourglass-half stat-icon"></i>
                    <div class="stat-content">
                        <p class="stat-title">فواتير تحت الإجراء</p>
                        <h2 class="stat-number">{{ App\Models\Invoice::where('doctor_id', auth()->user()->id)->where('invoice_status', 1)->count() }}</h2>
                        <p class="stat-description">الفواتير التي تحتاج لمراجعتك.</p>
                    </div>
                    <span class="stat-link">عرض المراجعات <i class="fas fa-arrow-circle-left"></i></span>
                    {{-- <span class="sparkline-chart" data-sparkline="3,2,4,6,12,14,8,7,14,16"></span> --}}
                </div>
            </a>
        </div>

        {{-- بطاقة الفواتير المكتملة --}}
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12" data-aos="fade-up" data-aos-delay="300">
            <a href="{{ route('doctor.completedInvoices') }}" class="text-decoration-none">
                <div class="stat-card-enhanced bg-success-gradient-custom">
                    <i class="fas fa-check-double stat-icon"></i>
                    <div class="stat-content">
                        <p class="stat-title">الفواتير المكتملة</p>
                        <h2 class="stat-number">{{ App\Models\Invoice::where('doctor_id', auth()->user()->id)->where('invoice_status', 3)->count() }}</h2>
                        <p class="stat-description">الكشوفات التي تم إكمالها بنجاح.</p>
                    </div>
                    <span class="stat-link">عرض المكتملة <i class="fas fa-arrow-circle-left"></i></span>
                    {{-- <span class="sparkline-chart" data-sparkline="5,10,5,20,22,12,15,18"></span> --}}
                </div>
            </a>
        </div>

        {{-- بطاقة فواتير المراجعات (الحالية) --}}
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12" data-aos="fade-up" data-aos-delay="400">
            <a href="{{ route('doctor.reviewInvoices') }}" class="text-decoration-none"> {{-- افترض أن هذا هو الرابط المناسب لفواتير المراجعات --}}
                <div class="stat-card-enhanced bg-warning-gradient-custom">
                    <i class="fas fa-redo-alt stat-icon"></i>
                    <div class="stat-content">
                        <p class="stat-title">فواتير المراجعات</p>
                        <h2 class="stat-number">{{ App\Models\Invoice::where('doctor_id',auth()->user()->id)->where('invoice_status',2)->count() }}</h2>
                        <p class="stat-description">الكشوفات التي تم تحديدها كمراجعة.</p>
                    </div>
                     <span class="stat-link">عرض المراجعات <i class="fas fa-arrow-circle-left"></i></span>
                    {{-- <span class="sparkline-chart" data-sparkline="5,9,5,6,4,12,18,14"></span> --}}
                </div>
            </a>
        </div>

        {{-- بطاقة مقترحة: عدد المواعيد لليوم --}}
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12" data-aos="fade-up" data-aos-delay="500">
            <a href="{{ route('doctor.appointments') }}" class="text-decoration-none">
                <div class="stat-card-enhanced bg-info-gradient-custom">
                    <i class="fas fa-calendar-day stat-icon"></i>
                    <div class="stat-content">
                        <p class="stat-title">مواعيد اليوم</p>
                        <h2 class="stat-number">{{ App\Models\Appointment::where('doctor_id', auth()->user()->id)->whereDate('appointment', today())->where('type','مؤكد')->count() }}</h2>
                        <p class="stat-description">المواعيد المؤكدة لليوم الحالي.</p>
                    </div>
                    <span class="stat-link">عرض المواعيد <i class="fas fa-arrow-circle-left"></i></span>
                </div>
            </a>
        </div>

        {{-- بطاقة مقترحة: عدد الوصفات هذا الشهر --}}
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12" data-aos="fade-up" data-aos-delay="600">
            <a href="{{ route('doctor.prescriptions.index') }}" class="text-decoration-none">
                <div class="stat-card-enhanced bg-purple-gradient-custom">
                    <i class="fas fa-prescription-bottle-alt stat-icon"></i>
                    <div class="stat-content">
                        <p class="stat-title">الوصفات هذا الشهر</p>
                        <h2 class="stat-number">{{ App\Models\Prescription::where('doctor_id', auth()->user()->id)->whereMonth('prescription_date', now()->month)->whereYear('prescription_date', now()->year)->count() }}</h2>
                        <p class="stat-description">الوصفات التي تم إنشاؤها هذا الشهر.</p>
                    </div>
                    <span class="stat-link">عرض الوصفات <i class="fas fa-arrow-circle-left"></i></span>
                </div>
            </a>
        </div>

    </div><!-- /Row -->

    {{-- يمكنك إضافة أقسام أخرى هنا مثل آخر الكشوفات، آخر المواعيد، إلخ --}}

    {{-- مثال: قسم آخر التنبيهات أو المهام --}}
    <div class="row mt-2">
        <div class="col-12" data-aos="fade-up" data-aos-delay="700">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title"><i class="fas fa-bell text-warning me-2"></i>تنبيهات هامة / مهام قادمة</h4>
                </div>
                <div class="card-body">
                    {{-- هنا يمكنك عرض قائمة بالتنبيهات، مثل الأدوية منخفضة المخزون إذا كان للطبيب وصول لهذه المعلومة، أو مواعيد تحتاج تأكيد --}}
                    <p class="text-muted">لا توجد تنبيهات جديدة في الوقت الحالي.</p>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    @parent
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="{{ URL::asset('dashboard/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('dashboard/plugins/notify/js/notifit-custom.js') }}"></script>
    {{-- إذا كنت ستستخدم Sparklines --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/peity/3.3.0/jquery.peity.min.js"></script> --}}

    <script>
        $(document).ready(function() {
            AOS.init({
                duration: 800,
                easing: 'ease-out-cubic',
                once: true,
                offset: 50 // لتبدأ الأنيميشن عند ظهور العنصر
            });

            // تهيئة Sparklines إذا كنت ستستخدمها (مثال)
            // $(".sparkline-chart").each(function() {
            //     var $this = $(this);
            //     var values = $this.data('sparkline').toString().split(',').map(Number);
            //     var type = $this.data('sparkline-type') || 'line'; // line, bar, pie
            //     var color = $this.data('sparkline-color') || 'rgba(255,255,255,0.5)';

            //     if(type === 'line') {
            //         $this.peity("line", {
            //             fill: 'rgba(255,255,255,0.1)',
            //             stroke: color,
            //             width: '100%',
            //             height: $this.height() || 40,
            //             strokeWidth: 2
            //         });
            //     } else if (type === 'bar') {
            //         $this.peity("bar", {
            //             fill: [color],
            //             width: '100%',
            //             height: $this.height() || 40,
            //             padding: 0.2
            //         });
            //     }
            // });


            // NotifIt messages
            @if(session('success') || session('status_success'))
                notif({ msg: `<div class="d-flex align-items-center p-2"><i class='fas fa-check-circle fa-lg me-2 text-success'></i><div style='font-size: 0.95rem;'>{{ session('success') ?: session('status_success') }}</div></div>`, type: "success", position: "top-center", autohide: true, timeout: 5000, zindex: 99999});
            @endif
            @if(session('error') || session('error_message'))
                notif({ msg: `<div class="d-flex align-items-center p-2"><i class='fas fa-exclamation-triangle fa-lg me-2 text-danger'></i><div style='font-size: 0.95rem;'>{{ session('error') ?: session('error_message') }}</div></div>`, type: "error", position: "top-center", autohide: true, timeout: 7000, zindex: 99999});
            @endif
        });
    </script>
@endsection
