@extends('Dashboard.layouts.master')

@section('title')
    لوحة تحكم المريض - {{ $patient->name ?? auth()->user()->name }}
@endsection

@section('css')
    @parent {{-- Keep existing CSS from master layout --}}
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    {{-- Font Awesome is usually included in master, but ensure it's available --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="{{ URL::asset('dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />

    <style>
        :root {
            --patient-primary: #4A90E2;
            /* أزرق سماوي منعش */
            --patient-primary-rgb: 74, 144, 226;
            --patient-primary-dark: #357ABD;
            --patient-primary-light: #EAF2FB;
            /* خلفية فاتحة للبطاقات مع لمسة زرقاء */
            --patient-secondary: #50E3C2;
            /* أخضر مائي مشرق */
            --patient-secondary-rgb: 80, 227, 194;
            --patient-success: #7ED321;
            /* أخضر تفاحي */
            --patient-success-rgb: 126, 211, 33;
            --patient-info: #F8E71C;
            /* أصفر ليموني (للتنبيهات الخفيفة) */
            --patient-info-dark: #cda70c;
            --patient-warning: #F5A623;
            /* برتقالي */
            --patient-warning-rgb: 245, 166, 35;
            --patient-danger: #D0021B;
            /* أحمر واضح */
            --patient-text-dark: #4A4A4A;
            /* رمادي داكن للنصوص */
            --patient-text-light: #7F8C8D;
            /* رمادي أفتح للنصوص الثانوية */
            --patient-bg: #f4f7f9;
            /* خلفية رمادية فاتحة جداً */
            --patient-card-bg: #ffffff;
            --patient-border-color: #e8eef3;
            --patient-card-radius: 0.8rem;
            /* انحناء أكبر للبطاقات */
            --patient-card-shadow: 0 6px 22px rgba(var(--patient-primary-rgb), 0.09);
            /* ظل أنعم وأوسع */
            --patient-gradient-primary: linear-gradient(135deg, var(--patient-primary) 0%, #68B3F9 100%);
            --patient-gradient-success: linear-gradient(135deg, var(--patient-success) 0%, #A6E66D 100%);
            --patient-gradient-info: linear-gradient(135deg, #50C9C3 0%, var(--patient-secondary) 100%);
            --patient-gradient-warning: linear-gradient(135deg, var(--patient-warning) 0%, #FAC06B 100%);
            --patient-gradient-chat: linear-gradient(135deg, #805ad5 0%, #a46ef4 100%);
            /* بنفسجي للرسائل */
        }

        body {
            font-family: 'Tajawal', sans-serif;
            /* تأكد من تحميل الخط إذا لم يكن في Master */
            background-color: var(--patient-bg);
            color: var(--patient-text-dark);
        }

        .main-content .container-fluid {
            padding-left: 25px;
            padding-right: 25px;
        }

        .breadcrumb-header {
            margin-bottom: 2rem;
        }

        .main-content-title {
            color: var(--patient-primary-dark);
            font-size: 2rem;
            font-weight: 700;
        }

        .sub-text-breadcrumb {
            color: var(--patient-text-light);
            font-size: 1rem;
        }

        .stat-card-patient {
            background-color: var(--patient-card-bg);
            border-radius: var(--patient-card-radius);
            padding: 1.75rem;
            box-shadow: var(--patient-card-shadow);
            display: flex;
            align-items: center;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            border: 1px solid var(--patient-border-color);
            position: relative;
            overflow: hidden;
            text-decoration: none !important;
        }

        .stat-card-patient:hover {
            transform: translateY(-6px) scale(1.03);
            box-shadow: 0 10px 30px rgba(var(--patient-primary-rgb), 0.15);
        }

        .stat-card-patient .stat-icon-bg {
            width: 65px;
            height: 65px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: #fff;
            margin-left: 1.25rem;
            /* RTL */
            flex-shrink: 0;
        }

        .stat-card-patient .stat-info h6 {
            font-size: 0.9rem;
            color: var(--patient-text-light);
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin-bottom: 0.3rem;
        }

        .stat-card-patient .stat-info .stat-number {
            font-size: 2.25rem;
            font-weight: 700;
            color: var(--patient-text-dark);
            display: block;
            line-height: 1.2;
        }

        .stat-card-patient .stat-info .stat-number small {
            font-size: 0.6em;
            font-weight: 500;
            color: var(--patient-text-light);
        }

        .stat-card-patient .stat-info .stat-link {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--patient-primary);
            transition: color 0.2s ease;
            display: inline-block;
            margin-top: 0.3rem;
        }

        .stat-card-patient .stat-info .stat-link:hover {
            color: var(--patient-primary-dark);
        }

        .stat-card-patient .stat-icon-bg.bg-primary-gradient {
            background: var(--patient-gradient-primary);
        }

        .stat-card-patient .stat-icon-bg.bg-success-gradient {
            background: var(--patient-gradient-success);
        }

        .stat-card-patient .stat-icon-bg.bg-info-gradient {
            background: var(--patient-gradient-info);
        }

        .stat-card-patient .stat-icon-bg.bg-chat-gradient {
            background: var(--patient-gradient-chat);
        }


        .section-heading {
            font-size: 1.6rem;
            font-weight: 600;
            color: var(--patient-primary-dark);
            margin-bottom: 1.5rem;
            padding-bottom: 0.7rem;
            border-bottom: 4px solid var(--patient-primary);
            display: inline-block;
        }

        .section-heading i {
            margin-left: 0.6rem;
            opacity: 0.8;
        }

        .alert-card-patient {
            background-color: var(--patient-card-bg);
            border-radius: var(--patient-card-radius);
            border-left: 6px solid var(--patient-warning);
            padding: 1.5rem;
            box-shadow: var(--patient-card-shadow);
            display: flex;
            align-items: center;
            /* Center vertically */
            margin-bottom: 1.25rem;
            transition: all 0.3s ease;
        }

        .alert-card-patient:hover {
            box-shadow: 0 8px 25px rgba(var(--patient-primary-rgb), 0.12);
            transform: translateY(-3px);
        }

        .alert-card-patient.alert-success {
            border-left-color: var(--patient-success);
        }

        .alert-card-patient.alert-info {
            border-left-color: var(--patient-primary);
        }

        .alert-card-patient.alert-chat {
            border-left-color: #805ad5;
        }

        /* لون بنفسجي للرسائل */
        .alert-card-patient .alert-icon-box {
            font-size: 2rem;
            margin-left: 1.25rem;
            /* RTL */
            padding-top: 0.1rem;
            line-height: 1;
            flex-shrink: 0;
        }

        .alert-card-patient .alert-icon-box .fa-comments {
            color: #805ad5;
        }

        .alert-card-patient .alert-content .alert-title {
            font-weight: 700;
            font-size: 1.15rem;
            margin-bottom: 0.35rem;
            color: var(--patient-text-dark);
        }

        .alert-card-patient .alert-content .alert-text {
            font-size: 0.95rem;
            color: var(--patient-text-light);
            margin-bottom: 0.85rem;
            line-height: 1.6;
        }

        .alert-card-patient .alert-content .btn {
            font-size: 0.9rem;
            padding: 0.4rem 1rem;
        }

        .dashboard-section .card {
            border: 1px solid var(--patient-border-color);
            box-shadow: var(--patient-card-shadow);
        }

        .dashboard-section .card-header {
            background-color: var(--patient-primary-light);
            border-bottom: 1px solid var(--patient-border-color);
            padding: 0.85rem 1.25rem;
        }

        .dashboard-section .card-title-main {
            color: var(--patient-primary-dark);
            font-weight: 600;
            font-size: 1.25rem;
        }

        .dashboard-section .list-group-item {
            border: 0;
            padding: 1rem 0.5rem;
            border-bottom: 1px dashed #eaedf1;
            transition: background-color 0.2s ease;
        }

        .dashboard-section .list-group-item:last-child {
            border-bottom: 0;
        }

        .dashboard-section .list-group-item:hover {
            background-color: #f0f5fb;
        }

        .appointment-item .time-badge {
            background-color: rgba(var(--patient-primary-rgb), 0.12);
            color: var(--patient-primary-dark);
            padding: 0.5em 0.9em;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .appointment-item .doctor-name {
            font-weight: 600;
            color: var(--patient-text-dark);
            font-size: 1.05rem;
        }

        .appointment-item .section-name {
            font-size: 0.85rem;
            color: var(--patient-text-light);
        }

        .appointment-item .status-badge {
            font-size: 0.8rem;
            padding: 0.4em 0.8em;
            border-radius: 20px;
            font-weight: 500;
        }

        .status-badge.status-confirmed {
            background-color: rgba(var(--patient-success-rgb), 0.15) !important;
            color: #38761d !important;
            border: 1px solid rgba(var(--patient-success-rgb), 0.3) !important;
        }

        .status-badge.status-pending {
            background-color: rgba(var(--patient-warning-rgb), 0.15) !important;
            color: #b17d00 !important;
            border: 1px solid rgba(var(--patient-warning-rgb), 0.3) !important;
        }

        .quick-access-links .list-group-item a {
            color: var(--patient-text-dark);
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 0.8rem 0.2rem;
            font-weight: 500;
            font-size: 1.05rem;
            transition: color 0.2s ease, padding-right 0.2s ease, transform 0.2s ease;
        }

        .quick-access-links .list-group-item a:hover {
            color: var(--patient-primary);
            padding-right: 8px;
            transform: scale(1.02);
        }

        .quick-access-links .list-group-item i {
            width: 25px;
            text-align: center;
            margin-left: 1rem;
            color: var(--patient-primary);
            opacity: 0.9;
            font-size: 1.2rem;
        }

        .welcome-area .btn {
            padding: 0.7rem 1.5rem;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .welcome-area .btn i {
            margin-right: -3px;
            margin-left: 6px;
        }

        .no-alerts-card {
            background-color: #f0fdf4;
            /* أخضر فاتح جداً */
            border-left: 5px solid var(--patient-success);
        }

        .no-alerts-card i {
            color: var(--patient-success);
            font-size: 2rem;
        }

        .no-alerts-card p {
            font-size: 1.05rem;
            font-weight: 500;
            color: #306844;
        }

        .text-gradient-primary {
            background: var(--patient-gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-fill-color: transparent;
            font-weight: 700;
        }
    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="left-content welcome-area">
            <div>
                <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">أهلاً بك، <span
                        class="text-gradient-primary">{{ $patient->name ?? 'زائرنا الكريم' }}</span>!</h2>
                <p class="mg-b-0 sub-text-breadcrumb">نظرة سريعة على صحتك وخدماتك في منصتنا المتكاملة.</p>
            </div>
        </div>
        <div class="right-content d-flex my-auto ms-auto welcome-area">
            <a href="{{ route('patient.appointments.create.form') }}" class="btn btn-primary-gradient ripple shadow-sm me-2"
                data-aos="fade-left" data-aos-delay="100">
                <i class="fas fa-calendar-plus me-1"></i> حجز موعد جديد
            </a>
            <a href="{{ url('/home') }}" class="btn btn-outline-secondary ripple" target="_blank" data-aos="fade-left">
                <i class="fas fa-home me-1"></i> العودة للرئيسية
            </a>
        </div>
    </div>
@endsection

@section('content')
    @include('Dashboard.messages_alert')

    <div class="row row-sm">
        <div class="col-lg-3 col-md-6 col-sm-12 mb-4" data-aos="fade-up" data-aos-delay="50">
            {{-- الرابط يشير الآن إلى صفحة الفواتير الجديدة --}}
            <a href="{{ route('website.my.invoices') }}" class="stat-card-patient">
                <div class="stat-icon-bg bg-primary-gradient"><i class="fas fa-file-invoice-dollar"></i></div>
                <div class="stat-info">
                    <h6>فواتيري المستحقة</h6>
                    <span class="stat-number">{{ number_format($totalDueInvoices ?? 0, 2) }} <small>ر.س</small></span>
                    <span class="stat-link">عرض الفواتير <i class="fas fa-arrow-left tx-10"></i></span>
                </div>
            </a>
        </div>

        {{-- بطاقة كشف الحساب / إجمالي المدفوعات الجديدة --}}
        <div class="col-lg-3 col-md-6 col-sm-12 mb-4" data-aos="fade-up" data-aos-delay="100">
            <a href="{{ route('website.my.account') }}" class="stat-card-patient">
                <div class="stat-icon-bg" style="background: linear-gradient(135deg, #fd7e14 0%, #ff9a44 100%);"><i
                        class="fas fa-receipt"></i></div> {{-- برتقالي --}}
                <div class="stat-info">
                    <h6>إجمالي مدفوعاتي</h6>
                    <span class="stat-number">{{ number_format($totalPaidByPatient ?? 0, 2) }} <small>ر.س</small></span>
                    <span class="stat-link">عرض كشف الحساب <i class="fas fa-arrow-left tx-10"></i></span>
                </div>
            </a>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-12 mb-4" data-aos="fade-up" data-aos-delay="150">
            <a href="{{ route('appointments.upcoming') }}" class="stat-card-patient"> {{-- أو route('appointments.upcoming') إذا كان هذا هو الصحيح --}}
                <div class="stat-icon-bg bg-success-gradient"><i class="fas fa-calendar-check"></i></div>
                <div class="stat-info">
                    <h6>مواعيدي القادمة</h6>
                    <span class="stat-number">{{ $upcomingAppointmentsCount ?? 0 }} <small>موعد</small></span>
                    <span class="stat-link">إدارة المواعيد <i class="fas fa-arrow-left tx-10"></i></span>
                </div>
            </a>
        </div>

        {{-- تم تعديل الترتيب ليكون 4 بطاقات --}}
        <div class="col-lg-3 col-md-6 col-sm-12 mb-4" data-aos="fade-up" data-aos-delay="250">
            <a href="{{ route('patient.pharmacy.index', ['quick_filter' => \App\Models\Prescription::STATUS_READY_FOR_PICKUP]) }}" class="stat-card-patient">
                <div class="stat-icon-bg bg-info-gradient"><i class="fas fa-pills"></i></div>
                <div class="stat-info">
                    <h6>وصفات جاهزة للاستلام</h6>
                    <span class="stat-number">{{ $readyPrescriptionsCount ?? 0 }} <small>وصفة</small></span>
                    <span class="stat-link">متابعة الصيدلية <i class="fas fa-arrow-left tx-10"></i></span>
                </div>
            </a>
        </div>

        <div class="col-lg-3 col-md-6 col-sm-12 mb-4" data-aos="fade-up" data-aos-delay="200"> {{-- تعديل delay --}}
            <a href="{{ route('chat.doctors') }}" class="stat-card-patient">
                <div class="stat-icon-bg bg-chat-gradient"><i class="fas fa-comments"></i></div>
                <div class="stat-info">
                    <h6>رسائل جديدة</h6>
                    <span class="stat-number">{{ $unreadChatMessagesCount ?? 0 }} <small>رسالة</small></span>
                    <span class="stat-link">عرض المحادثات <i class="fas fa-arrow-left tx-10"></i></span>
                </div>
            </a>
        </div>
    </div>

    <!-- Row: Important Alerts -->
    <div class="row row-sm mt-2">
        <div class="col-12 mb-3" data-aos="fade-up" data-aos-delay="50">
            <h5 class="section-heading"><i class="fas fa-bell text-warning"></i>تنبيهات وإجراءات مهمة</h5>
        </div>

        @if (isset($unreadChatMessagesCount) && $unreadChatMessagesCount > 0)
            <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="alert-card-patient alert-chat">
                    <div class="alert-icon-box"><i class="fas fa-envelope-open-text fa-fw"></i></div>
                    <div class="alert-content">
                        <h6 class="alert-title">لديك رسائل جديدة!</h6>
                        <p class="alert-text mb-1">
                            لديك <strong style="color: #805ad5;">{{ $unreadChatMessagesCount }}</strong>
                            {{ $unreadChatMessagesCount == 1 ? 'رسالة جديدة' : ($unreadChatMessagesCount == 2 ? 'رسالتان' : ($unreadChatMessagesCount >= 3 && $unreadChatMessagesCount <= 10 ? 'رسائل جديدة' : 'رسالة جديدة')) }}
                            من فريقنا الطبي.
                        </p>
                        <a href="{{ route('chat.doctors') }}" class="btn btn-sm btn-outline-primary shadow-sm mt-2"
                            style="border-color: #805ad5; color: #805ad5;">
                            <i class="fas fa-comments me-1"></i> عرض المحادثات الآن
                        </a>
                    </div>
                </div>
            </div>
        @endif

        @if ($upcomingChronicRefill)
            <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="150">
                <div class="alert-card-patient alert-warning">
                    <div class="alert-icon-box text-warning"><i class="fas fa-history fa-fw"></i></div>
                    <div class="alert-content">
                        <h6 class="alert-title">تجديد وصفة مزمنة</h6>
                        <p class="alert-text">
                            الوصفة <a href="{{ route('patient.pharmacy.show', $upcomingChronicRefill->id) }}"
                                class="fw-bold text-dark hover-underline">{{ $upcomingChronicRefill->prescription_number }}</a>
                            تحتاج لإعادة صرف
                            {{ $upcomingChronicRefill->next_refill_due_date->locale(app()->getLocale())->diffForHumans() }}.
                        </p>
                        @if (method_exists($upcomingChronicRefill, 'can_request_refill') && $upcomingChronicRefill->can_request_refill)
                            <form action="{{ route('patient.pharmacy.request-refill', $upcomingChronicRefill->id) }}"
                                method="POST" class="mt-2">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-warning text-dark shadow-sm"><i
                                        class="fas fa-redo-alt me-1"></i> طلب إعادة الصرف</button>
                            </form>
                        @else
                            <small class="text-muted d-block mt-2">تم طلب التجديد أو راجع الصيدلية.</small>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        @if (isset($readyPrescriptionsCount) && $readyPrescriptionsCount > 0)
            {{--  لا يتم إخفاء هذا التنبيه حتى لو كان هناك تنبيهات أخرى --}}
            <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                <div class="alert-card-patient alert-success">
                    <div class="alert-icon-box text-success"><i class="fas fa-check-circle fa-fw"></i></div>
                    <div class="alert-content">
                        <h6 class="alert-title">وصفات جاهزة للاستلام</h6>
                        <p class="alert-text">
                            لديك <strong class="text-gradient-success">{{ $readyPrescriptionsCount }}</strong> وصفة/وصفات
                            جاهزة للاستلام من الصيدلية.
                        </p>
                        <a href="{{ route('patient.pharmacy.index', ['quick_filter' => \App\Models\Prescription::STATUS_READY_FOR_PICKUP]) }}"
                            class="btn btn-sm btn-success shadow-sm mt-2"><i class="fas fa-clinic-medical me-1"></i> عرض
                            الوصفات</a>
                    </div>
                </div>
            </div>
        @endif


        @if ($imminentAppointment)
            <div class="col-lg-4 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="250">
                <div class="alert-card-patient alert-info">
                    <div class="alert-icon-box text-primary"><i class="fas fa-calendar-day fa-fw"></i></div>
                    <div class="alert-content">
                        <h6 class="alert-title">تذكير بموعدك القادم!</h6>
                        <p class="alert-text">
                            موعدك مع د. <strong>{{ $imminentAppointment->doctor->name ?? 'الطبيب' }}</strong>
                            هو <span
                                class="text-danger fw-bold">{{ $imminentAppointment->appointment->isToday() ? 'اليوم' : ($imminentAppointment->appointment->isTomorrow() ? 'غداً' : $imminentAppointment->appointment->translatedFormat('l')) }}</span>
                            الساعة
                            {{ \Carbon\Carbon::parse($imminentAppointment->appointment)->translatedFormat('h:i A') }}.
                        </p>
                        <a href="{{ route('appointments.upcoming') }}"
                            class="btn btn-sm btn-primary shadow-sm mt-2"> {{-- أو route('appointments.upcoming') --}}
                            <i class="fas fa-eye me-1"></i> عرض مواعيدي
                        </a>
                    </div>
                </div>
            </div>
        @endif

        @if (!$hasImportantAlerts)
            {{-- تم تعديل الشرط ليشمل جميع التنبيهات --}}
            <div class="col-12" data-aos="fade-up" data-aos-delay="100">
                <div class="card no-alerts-card shadow-sm">
                    <div class="card-body text-center p-4 d-flex align-items-center justify-content-center">
                        <i class="fas fa-shield-alt fa-3x opacity-75 me-3"></i>
                        <div>
                            <h5 class="mb-1">لا توجد تنبيهات عاجلة!</h5>
                            <p class="mb-0 text-muted">كل أمورك الصحية تحت السيطرة في الوقت الحالي. يومك سعيد!</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>


    <!-- Row: Appointments and Quick Access -->
    <div class="row row-sm row-deck mt-3 dashboard-section">
        <div class="col-lg-7 col-md-12 mb-4" data-aos="fade-right" data-aos-delay="100">
            <div class="card h-100 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title-main mb-0"><i class="far fa-calendar-alt me-2"></i> مواعيدي القادمة (أحدث 5)
                    </h4>
                    <a href="{{ route('appointments.upcoming') }}" class="btn btn-sm btn-outline-primary ripple">عرض
                        الكل <i class="fas fa-angle-double-left"></i></a> {{-- أو route('appointments.upcoming') --}}
                </div>
                <div class="card-body pt-2 pb-2">
                    @if ($upcoming_appointments_list->isNotEmpty())
                        <ul class="list-group list-group-flush appointment-list">
                            @foreach ($upcoming_appointments_list as $appointment_item)
                                {{-- تغيير اسم المتغير لتجنب التعارض --}}
                                <li class="list-group-item appointment-item">
                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                        <div class="appointment-details flex-grow-1">
                                            <span
                                                class="time-badge mb-1 d-inline-block">{{ \Carbon\Carbon::parse($appointment_item->appointment)->translatedFormat('D، j M Y - g:i A') }}</span>
                                            <div class="mt-1"><i class="fas fa-user-md text-muted me-1 op-7"></i>د.
                                                <strong
                                                    class="doctor-name">{{ $appointment_item->doctor->name ?? 'غير محدد' }}</strong>
                                            </div>
                                            <div class="section-name"><i
                                                    class="fas fa-clinic-medical text-muted me-1 op-7"></i>القسم:
                                                {{ $appointment_item->section->name ?? 'غير محدد' }}</div>
                                        </div>
                                        <div class="text-center ms-2 appointment-actions">
                                            <span
                                                class="badge status-badge {{ $appointment_item->type == 'مؤكد' ? 'status-confirmed' : 'status-pending' }} mb-2">
                                                {{ $appointment_item->type }}
                                            </span>
                                        </div>
                                    </div>
                                    @if ($appointment_item->notes)
                                        <p class="mb-0 mt-2 text-muted small border-top pt-2" style="font-size: 0.9rem"><i
                                                class="fas fa-info-circle me-1 text-info"></i> ملاحظاتك:
                                            {{ Str::limit($appointment_item->notes, 100) }}</p>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center text-muted p-5">
                            <i class="fas fa-calendar-times fa-3x opacity-50 mb-3"></i>
                            <h5 class="mb-2">لا توجد لديك مواعيد قادمة.</h5>
                            <p class="mb-3">يمكنك حجز موعد جديد بسهولة من خلال الضغط على الزر في الأعلى.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-5 col-md-12 mb-4" data-aos="fade-left" data-aos-delay="200">
            <div class="card quick-access-links h-100 shadow-sm">
                <div class="card-header">
                    <h4 class="card-title-main mb-0"><i class="fas fa-paperclip me-2"></i> وصول سريع لخدماتك</h4>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        {{-- تعديل رابط الفواتير ليشير إلى الصفحة الجديدة --}}
                        <li class="list-group-item"><a href="{{ route('website.my.invoices') }}"><i
                                    class="fas fa-file-invoice-dollar fa-fw"></i> فواتيري</a></li>
                        {{-- إضافة رابط كشف الحساب --}}
                        <li class="list-group-item"><a href="{{ route('website.my.account') }}"><i
                                    class="fas fa-cash-register fa-fw"></i> كشف حسابي</a></li>
                        <li class="list-group-item"><a href="{{ route('patient.pharmacy.index') }}"><i
                                    class="fas fa-pills fa-fw"></i> وصفاتي الطبية والصيدلية</a></li>
                        <li class="list-group-item"><a href="{{ route('laboratories.patient') }}"><i
                                    class="fas fa-microscope fa-fw"></i> نتائج فحوصاتي المعملية</a></li>
                        <li class="list-group-item"><a href="{{ route('rays.patient') }}"><i
                                    class="fas fa-x-ray fa-fw"></i> تقارير الأشعة الخاصة بي</a></li>
                        <li class="list-group-item"><a href="{{ route('chat.doctors') ?? '#' }}"><i
                                    class="fas fa-comments fa-fw"></i> محادثاتي مع الأطباء</a></li>
                        <li class="list-group-item"><a href="{{ route('profile.edit') }}"><i
                                    class="fas fa-user-edit fa-fw"></i> تعديل بيانات ملفي الشخصي</a></li>
                    </ul>
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
    {{-- <script src="{{ URL::asset('Dashboard/js/index.js') }}"></script>  --}}

    <script>
        $(document).ready(function() {
            AOS.init({
                duration: 750, // مدة أطول قليلاً
                easing: 'ease-out-cubic', // حركة ألطف
                once: true,
                offset: 40 // تعديل طفيف
            });

            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl, {
                    boundary: document.body,
                    container: 'body',
                    trigger: 'hover' // إظهار الـ tooltip عند المرور فقط
                });
            });

            // رسائل NotifIt تبقى كما هي، فهي جيدة
            @if (session('success'))
                notif({
                    msg: "<div class='d-flex align-items-center p-2'><i class='fas fa-check-circle fa-lg me-2 text-success'></i><div style='font-size: 0.95rem;'>{!! addslashes(session('success')) !!}</div></div>",
                    type: "success",
                    position: "top-center",
                    autohide: true,
                    timeout: 6000
                });
            @endif
            @if (session('error'))
                notif({
                    msg: "<div class='d-flex align-items-center p-2'><i class='fas fa-exclamation-triangle fa-lg me-2 text-danger'></i><div style='font-size: 0.95rem;'>{!! addslashes(session('error')) !!}</div></div>",
                    type: "error",
                    position: "top-center",
                    autohide: true,
                    timeout: 8000
                });
            @endif
            @if (session('info'))
                notif({
                    msg: "<div class='d-flex align-items-center p-2'><i class='fas fa-info-circle fa-lg me-2 text-info'></i><div style='font-size: 0.95rem;'>{!! addslashes(session('info')) !!}</div></div>",
                    type: "info",
                    position: "top-center",
                    autohide: true,
                    timeout: 6000
                });
            @endif
            @if (session('warning'))
                notif({
                    msg: "<div class='d-flex align-items-center p-2'><i class='fas fa-exclamation-circle fa-lg me-2 text-warning'></i><div style='font-size: 0.95rem;'>{!! addslashes(session('warning')) !!}</div></div>",
                    type: "warning",
                    position: "top-center",
                    autohide: true,
                    timeout: 7000
                });
            @endif
        });
    </script>
@endsection
