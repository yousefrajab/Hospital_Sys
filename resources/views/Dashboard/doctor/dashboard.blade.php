@extends('Dashboard.layouts.master') {{-- افترض أن هذا هو الـ master layout الصحيح للطبيب --}}

@section('title')
    لوحة تحكم الطبيب - د. {{ $doctor->name ?? auth()->user()->name }}
@endsection

@section('css')
    @parent
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="{{ URL::asset('dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    <style>
        /* استخدام متغيرات مشابهة لواجهة المريض مع تعديل ألوان الطبيب إذا أردت */
        :root {
            --doctor-primary: #007bff;
            /* أزرق Bootstrap القياسي */
            --doctor-primary-rgb: 0, 123, 255;
            --doctor-primary-dark: #0056b3;
            --doctor-success: #28a745;
            --doctor-warning: #ffc107;
            --doctor-danger: #dc3545;
            --doctor-info: #17a2b8;
            --doctor-text-dark: #343a40;
            --doctor-text-light: #6c757d;
            --doctor-bg: #f4f7f9;
            --doctor-card-bg: #ffffff;
            --doctor-border-color: #dee2e6;
            --doctor-card-radius: 0.6rem;
            --doctor-card-shadow: 0 4px 15px rgba(0, 0, 0, 0.07);
            --patient-gradient-chat: linear-gradient(135deg, #805ad5 0%, #a46ef4 100%);
            /*  للون بطاقة الرسائل  */
            --doctor-gradient-teal: linear-gradient(135deg, #20c997 0%, #52d8b8 100%);
            /* لون إضافي */
        }

        /* ... (يمكنك نسخ وتكييف أنماط البطاقات والقوائم من واجهة المريض إذا أعجبتك) ... */
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: var(--doctor-bg);
            color: var(--doctor-text-dark);
        }

        .main-content .container-fluid {
            padding-left: 20px;
            padding-right: 20px;
        }

        .breadcrumb-header {
            margin-bottom: 1.5rem;
        }

        .main-content-title {
            color: var(--doctor-primary-dark);
            font-size: 1.75rem;
            font-weight: 600;
        }

        .sub-text-breadcrumb {
            color: var(--doctor-text-light);
            font-size: 0.9rem;
        }

        .stat-card-doctor {
            /* تمييز اسم الكلاس */
            background-color: var(--doctor-card-bg);
            border-radius: var(--doctor-card-radius);
            padding: 1.5rem;
            box-shadow: var(--doctor-card-shadow);
            display: flex;
            align-items: center;
            transition: all 0.3s ease-out;
            border: 1px solid var(--doctor-border-color);
            position: relative;
            overflow: hidden;
            text-decoration: none !important;
            color: inherit;
            margin-bottom: 1.5rem;
        }

        .stat-card-doctor:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .stat-card-doctor .stat-icon-bg {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: #fff;
            margin-left: 1rem;
            flex-shrink: 0;
        }

        .stat-card-doctor .stat-info h6 {
            font-size: 0.85rem;
            color: var(--doctor-text-light);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.2rem;
        }

        .stat-card-doctor .stat-info .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--doctor-text-dark);
            display: block;
            line-height: 1.2;
        }

        .stat-card-doctor .stat-info .stat-number small {
            font-size: 0.6em;
            font-weight: 500;
            color: var(--doctor-text-light);
        }

        .stat-card-doctor .stat-info .stat-link {
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--doctor-primary);
            margin-top: 0.3rem;
            display: inline-block;
        }

        .stat-card-doctor .stat-info .stat-link:hover {
            color: var(--doctor-primary-dark);
        }

        .stat-card-doctor .stat-icon-bg.bg-blue-gradient {
            background: linear-gradient(135deg, #007bff 0%, #3699ff 100%);
        }

        .stat-card-doctor .stat-icon-bg.bg-green-gradient {
            background: linear-gradient(135deg, #28a745 0%, #34d399 100%);
        }

        .stat-card-doctor .stat-icon-bg.bg-orange-gradient {
            background: linear-gradient(135deg, #fd7e14 0%, #ffc107 100%);
        }

        .stat-card-doctor .stat-icon-bg.bg-cyan-gradient {
            background: linear-gradient(135deg, #17a2b8 0%, #4dd0e1 100%);
        }

        .stat-card-doctor .stat-icon-bg.bg-chat-gradient {
            background: var(--patient-gradient-chat);
        }

        /* استخدام نفس تدرج الرسائل للمريض */


        .section-heading {
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--doctor-primary-dark);
            margin-bottom: 1.25rem;
            padding-bottom: 0.6rem;
            border-bottom: 3px solid var(--doctor-primary);
            display: inline-block;
        }

        .section-heading i {
            margin-left: 0.5rem;
            opacity: 0.8;
        }

        .alert-card-doctor {
            /* تمييز اسم الكلاس */
            background-color: var(--doctor-card-bg);
            border-radius: var(--doctor-card-radius);
            border-left: 5px solid var(--doctor-warning);
            padding: 1.25rem;
            box-shadow: var(--doctor-card-shadow);
            display: flex;
            align-items: center;
            /*  أو flex-start */
            margin-bottom: 1rem;
            transition: all 0.3s ease;
        }

        .alert-card-doctor:hover {
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .alert-card-doctor.alert-info {
            border-left-color: var(--doctor-info);
        }

        .alert-card-doctor.alert-chat {
            border-left-color: #805ad5;
            /* بنفسجي */
        }

        .alert-card-doctor .alert-icon-box {
            font-size: 1.75rem;
            margin-left: 1.25rem;
            flex-shrink: 0;
        }

        .alert-card-doctor .alert-icon-box .fa-comments {
            color: #805ad5;
        }

        .alert-card-doctor .alert-content .alert-title {
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 0.25rem;
            color: var(--doctor-text-dark);
        }

        .alert-card-doctor .alert-content .alert-text {
            font-size: 0.9rem;
            color: var(--doctor-text-light);
            margin-bottom: 0.75rem;
        }

        .alert-card-doctor .alert-content .btn {
            font-size: 0.85rem;
            padding: 0.3rem 0.8rem;
        }

        .list-group-custom .list-group-item {
            border-color: var(--doctor-border-color);
            padding: 0.85rem 1rem;
        }

        .list-group-custom .list-group-item:hover {
            background-color: #f8f9fa;
        }

        .list-group-custom .item-title {
            font-weight: 500;
            color: var(--doctor-text-dark);
        }

        .list-group-custom .item-meta {
            font-size: 0.8rem;
            color: var(--doctor-text-light);
        }

        .list-group-custom .item-action .btn {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
        }
    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="left-content">
            <div>
                <h2 class="main-content-title tx-24 mg-b-1 mg-b-lg-1">لوحة تحكم الطبيب</h2>
                <p class="mg-b-0 sub-text-breadcrumb">مرحباً د. <strong
                        class="text-primary">{{ $doctor->name ?? auth()->user()->name }}</strong>، إليك ملخص نشاطك اليوم.
                </p>
            </div>
        </div>
        <div class="right-content d-flex my-auto ms-auto">
            <a href="{{ route('doctor.prescriptions.adherenceDashboard') }}" class="btn btn-outline-primary ripple me-2">
                <i class="fas fa-chart-line me-1"></i> متابعة التزام المرضى
            </a>
            <a href="{{ route('doctor.prescriptions.create') }}" class="btn btn-primary-gradient ripple">
                <i class="fas fa-plus-circle me-1"></i> إنشاء وصفة جديدة
            </a>
        </div>
    </div>
@endsection

@section('content')
    @include('Dashboard.messages_alert')

    <!-- Row: Quick Stats Cards -->
    <div class="row row-sm">
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12" data-aos="fade-up" data-aos-delay="100">
            <a href="{{ route('doctor.appointments') }}" class="stat-card-doctor">
                <div class="stat-icon-bg bg-blue-gradient"><i class="fas fa-calendar-day"></i></div>
                <div class="stat-info">
                    <h6>مواعيد اليوم المؤكدة</h6>
                    <span class="stat-number">{{ $todayConfirmedAppointmentsCount ?? 0 }}</span>
                    <span class="stat-link">عرض جدول المواعيد <i class="fas fa-arrow-left tx-10"></i></span>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12" data-aos="fade-up" data-aos-delay="150">
            <a href="{{ route('doctor.appointments') }}" class="stat-card-doctor"> {{-- رابط لصفحة كل مواعيد الطبيب --}}
                <div class="stat-icon-bg bg-success-gradient"><i class="fas fa-calendar-alt"></i></div>
                <div class="stat-info">
                    <h6>كل مواعيدي المؤكدة</h6>
                    {{-- الآن سيتم استخدام المتغير الصحيح --}}
                    <span class="stat-number">{{ $allUpcomingConfirmedAppointmentsTotalCount ?? 0 }}</span>
                    <span class="stat-link">عرض كل المواعيد <i class="fas fa-arrow-left tx-10"></i></span>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12" data-aos="fade-up" data-aos-delay="200">
            <a href="{{ route('doctor.prescriptions.approvalRequests') }}" class="stat-card-doctor">
                <div class="stat-icon-bg bg-orange-gradient"><i class="fas fa-file-import"></i></div>
                <div class="stat-info">
                    <h6>طلبات تحتاج قرارك</h6> {{-- مثل طلبات تجديد الوصفات --}}
                    <span class="stat-number">{{ $prescriptionApprovalRequestsCount ?? 0 }}</span>
                    <span class="stat-link">مراجعة الطلبات <i class="fas fa-arrow-left tx-10"></i></span>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12" data-aos="fade-up" data-aos-delay="300">
            <a href="{{ route('doctor.prescriptions.index') }}" class="stat-card-doctor">
                <div class="stat-icon-bg bg-cyan-gradient"><i class="fas fa-prescription"></i></div>
                <div class="stat-info">
                    <h6>وصفات هذا الشهر</h6>
                    <span class="stat-number">{{ $prescriptionsThisMonthCount ?? 0 }}</span>
                    <span class="stat-link">عرض كل الوصفات <i class="fas fa-arrow-left tx-10"></i></span>
                </div>
            </a>
        </div>
        {{-- *** بطاقة جديدة لعدد الرسائل غير المقروءة للطبيب *** --}}
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12" data-aos="fade-up" data-aos-delay="400">
            <a href="{{ route('doctor.chat.patients') }}" class="stat-card-doctor"> {{-- رابط لصفحة محادثات الطبيب --}}
                <div class="stat-icon-bg bg-chat-gradient"><i class="fas fa-comments"></i></div>
                <div class="stat-info">
                    <h6>رسائل جديدة من المرضى</h6>
                    <span class="stat-number">{{ $unreadMessagesFromPatientsCount ?? 0 }}</span>
                    <span class="stat-link">الذهاب للمحادثات <i class="fas fa-arrow-left tx-10"></i></span>
                </div>
            </a>
        </div>
    </div>

    <!-- Row: Important Alerts for Doctor -->
    @if (isset($hasImportantDoctorAlerts) && $hasImportantDoctorAlerts)
        <div class="row row-sm mt-2">
            <div class="col-12 mb-3" data-aos="fade-up" data-aos-delay="50">
                <h5 class="section-heading"><i class="fas fa-exclamation-triangle text-danger"></i>إجراءات وتنبيهات عاجلة
                </h5>
            </div>

            @if (isset($unreadMessagesFromPatientsCount) && $unreadMessagesFromPatientsCount > 0)
                <div class="col-lg-6 col-md-12 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="alert-card-doctor alert-chat">
                        <div class="alert-icon-box"><i class="fas fa-envelope-open-text fa-fw"></i></div>
                        <div class="alert-content">
                            <h6 class="alert-title">لديك رسائل جديدة من المرضى!</h6>
                            <p class="alert-text mb-1">
                                لديك <strong style="color: #805ad5;">{{ $unreadMessagesFromPatientsCount }}</strong>
                                {{ $unreadMessagesFromPatientsCount == 1 ? 'رسالة جديدة' : ($unreadMessagesFromPatientsCount == 2 ? 'رسالتان' : 'رسائل') }}
                                تنتظر ردك.
                            </p>
                            <a href="{{ route('doctor.chat.patients') }}"
                                class="btn btn-sm btn-outline-primary shadow-sm mt-2"
                                style="border-color: #805ad5; color: #805ad5;">
                                <i class="fas fa-reply-all me-1"></i> الرد على الرسائل
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            @if (isset($prescriptionApprovalRequestsCount) && $prescriptionApprovalRequestsCount > 0)
                <div class="col-lg-6 col-md-12 mb-4" data-aos="fade-up" data-aos-delay="150">
                    <div class="alert-card-doctor alert-warning"> {{-- تم استخدام alert-warning --}}
                        <div class="alert-icon-box text-warning"><i class="fas fa-clipboard-list-check fa-fw"></i></div>
                        <div class="alert-content">
                            <h6 class="alert-title">طلبات تجديد وصفات للموافقة</h6>
                            <p class="alert-text mb-1">
                                هناك <strong class="text-warning">{{ $prescriptionApprovalRequestsCount }}</strong>
                                طلب/طلبات تجديد وصفة تنتظر مراجعتك وقرارك.
                            </p>
                            <a href="{{ route('doctor.prescriptions.approvalRequests') }}"
                                class="btn btn-sm btn-warning text-dark shadow-sm mt-2">
                                <i class="fas fa-user-check me-1"></i> مراجعة الطلبات الآن
                            </a>
                        </div>
                    </div>
                </div>
            @endif
            {{-- يمكنك إضافة تنبيهات أخرى هنا، مثل مواعيد مؤكدة جداً قريبة أو نتائج فحوصات حرجة --}}
        </div>
    @endif
    <!-- End Alerts Section -->


    <!-- Row: Upcoming Appointments & Recent Prescriptions (Example) -->
    <div class="row row-sm row-deck mt-3 dashboard-section">
        <div class="col-lg-7 col-md-12 mb-4" data-aos="fade-right" data-aos-delay="200">
            <div class="card h-100 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title-main mb-0"><i class="far fa-calendar-alt me-2 text-primary"></i>مواعيد اليوم
                        والغد المؤكدة (أحدث 5)</h4>
                    <a href="{{-- route('doctor.appointments.all') --}}" class="btn btn-sm btn-outline-primary ripple disabled"
                        aria-disabled="true">عرض كل المواعيد</a>
                </div>
                <div class="card-body pt-2 pb-2">
                    @if ($allUpcomingConfirmedAppointments->isNotEmpty())
                        <ul class="list-group list-group-flush list-group-custom">
                            @foreach ($allUpcomingConfirmedAppointments as $appointment)
                                <li class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                        <div class="flex-grow-1">
                                            <span
                                                class="time-badge mb-1 d-inline-block">{{ $appointment->appointment->translatedFormat('D, j M Y - g:i A') }}</span>
                                            <div class="mt-1 item-title">
                                                <i class="fas fa-user-injured text-muted me-1 op-7"></i> مريض:
                                                <a href="{{ route('doctor.patient.details', $appointment->patient_id) }}"
                                                    class="text-primary-dark hover-underline">
                                                    {{ $appointment->patient->name ?? 'غير محدد' }}
                                                </a>
                                            </div>
                                        </div>
                                        <div class="item-action">
                                            <a href="{{-- route('doctor.appointments.show', $appointment->id) --}}" class="btn btn-sm btn-outline-info disabled"
                                                aria-disabled="true" title="تفاصيل الموعد"><i class="fas fa-eye"></i></a>
                                        </div>
                                    </div>
                                    @if ($appointment->notes)
                                        <p class="mb-0 mt-2 text-muted small border-top pt-2"
                                            style="font-size: 0.88rem_}"><i class="fas fa-info-circle me-1 text-info"></i>
                                            ملاحظات: {{ Str::limit($appointment->notes, 100) }}</p>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center text-muted p-4">
                            <i class="fas fa-calendar-check fa-2x opacity-50 mb-2"></i>
                            <p class="mb-0">لا توجد مواعيد مؤكدة لليوم أو الغد.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-5 col-md-12 mb-4" data-aos="fade-left" data-aos-delay="300">
            <div class="card h-100 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title-main mb-0"><i class="fas fa-file-prescription me-2 text-primary"></i>أحدث
                        الوصفات المُنشأة/المُعدلة</h4>
                    <a href="{{ route('doctor.prescriptions.index') }}" class="btn btn-sm btn-outline-primary ripple">كل
                        الوصفات</a>
                </div>
                <div class="card-body pt-2 pb-2">
                    @if ($recentPrescriptionsList->isNotEmpty())
                        <ul class="list-group list-group-flush list-group-custom">
                            @foreach ($recentPrescriptionsList as $rx)
                                <li class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <div>
                                            <a href="{{ route('doctor.prescriptions.show', $rx->id) }}"
                                                class="item-title text-primary-dark hover-underline">{{ $rx->prescription_number }}</a>
                                            <div class="item-meta">للمريض: {{ $rx->patient->name ?? 'غير محدد' }}</div>
                                        </div>
                                        <div class="text-end">
                                            <span
                                                class="badge status-badge {{ $rx->status_badge_class ?? 'status-default' }}"
                                                style="min-width:90px;">{{ $rx->status_display ?? $rx->status }}</span>
                                            <small
                                                class="d-block text-muted mt-_1">{{ $rx->updated_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-center text-muted p-4">لم تقم بإنشاء أو تعديل أي وصفات مؤخراً.</p>
                    @endif
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

    <script>
        $(document).ready(function() {
            AOS.init({
                duration: 750,
                easing: 'ease-out-back',
                once: true,
                offset: 40
            });

            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl, {
                    boundary: document.body,
                    container: 'body',
                    trigger: 'hover'
                });
            });

            @if (session('success'))
                notif({
                    msg: "<div class='d-flex align-items-center p-2'><i class='fas fa-check-circle fa-lg me-2 text-success'></i><div style='font-size: 0.95rem;'>{!! addslashes(session('success')) !!}</div></div>",
                    type: "success",
                    position: "top-center",
                    autohide: true,
                    timeout: 6000
                });
            @endif
            @if (session('success') || session('status_success'))
                notif({
                    msg: `<div class="d-flex align-items-center p-2"><i class='fas fa-check-circle fa-lg me-2 text-success'></i><div style='font-size: 0.95rem;'>{{ session('success') ?: session('status_success') }}</div></div>`,
                    type: "success",
                    position: "top-center",
                    autohide: true,
                    timeout: 5000,
                    zindex: 99999
                });
            @endif
            @if (session('error') || session('error_message'))
                notif({
                    msg: `<div class="d-flex align-items-center p-2"><i class='fas fa-exclamation-triangle fa-lg me-2 text-danger'></i><div style='font-size: 0.95rem;'>{{ session('error') ?: session('error_message') }}</div></div>`,
                    type: "error",
                    position: "top-center",
                    autohide: true,
                    timeout: 7000,
                    zindex: 99999
                });
            @endif
        });
    </script>
@endsection
