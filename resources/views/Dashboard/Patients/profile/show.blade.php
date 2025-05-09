{{-- resources/views/Dashboard/patient_panel/profile/show.blade.php --}}
@extends('Dashboard.layouts.master') {{-- أو الـ layout الخاص بلوحة تحكم المريض --}}

@php
    $patientName = $patient->getTranslation('name', app()->getLocale(), false) ?: $patient->name;
    $patientAddress = $patient->getTranslation('Address', app()->getLocale(), false) ?: $patient->Address;
@endphp
@section('title', 'ملفي الشخصي | ' . $patientName)

@section('css')
    @parent
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link href="{{ URL::asset('Dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />

    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4895ef;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --text-muted-custom: #6c757d;
            --card-border-color: #e0e5ec;
        }

        body {
            background-color: #f4f7f9; /* خلفية أفتح قليلاً للصفحة */
            font-family: 'Tajawal', sans-serif;
            color: var(--dark-color);
        }

        .patient-profile-view-container {
            padding: 2rem 1rem;
        }

        .profile-card-view {
            background: white;
            border-radius: 20px; /* حواف أكثر دائرية */
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.07);
            overflow: hidden;
            border: 1px solid var(--card-border-color);
        }

        .profile-header-view {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 40px 30px;
            text-align: center;
            position: relative;
        }
        .profile-header-view::after { /* تأثير زخرفي */
            content: '';
            position: absolute;
            bottom: -30px;
            left: 50%;
            transform: translateX(-50%);
            width: 150%;
            height: 60px;
            background: white;
            border-radius: 50% / 100%;
            border-bottom-left-radius: 0;
            border-bottom-right-radius: 0;
        }


        .profile-avatar-view {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid white;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            margin: -65px auto 15px; /* لرفع الصورة فوق الهيدر */
            position: relative;
            z-index: 2;
            background-color: white; /* خلفية للصورة إذا كانت شفافة */
        }

        .patient-name-view {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 5px;
            text-align: center;
        }

        .patient-email-view {
            font-size: 1rem;
            color: var(--text-muted-custom);
            margin-bottom: 25px;
            text-align: center;
            display: block;
        }
        .patient-email-view i {
            margin-left: 5px;
        }

        .profile-details-section {
            padding: 20px 30px 30px;
        }

        .detail-item {
            display: flex;
            align-items: flex-start; /* محاذاة للأعلى إذا كان النص متعدد الأسطر */
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px dashed var(--card-border-color);
        }
        .detail-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .detail-icon {
            font-size: 1.2rem;
            color: var(--primary-color);
            width: 40px; /* عرض ثابت للأيقونة */
            flex-shrink: 0;
            text-align: center;
            margin-left: 15px; /* RTL: margin-right */
        }

        .detail-content .detail-label {
            font-size: 0.8rem;
            color: var(--text-muted-custom);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
            display: block;
        }

        .detail-content .detail-value {
            font-size: 1rem;
            font-weight: 500;
            color: var(--dark-color);
            word-break: break-word;
        }
        .detail-content .detail-value.empty { /* إذا كانت القيمة فارغة */
            color: var(--text-muted-custom);
            font-style: italic;
        }


        .edit-profile-fab { /* زر تعديل عائم (اختياري) */
            position: fixed;
            bottom: 30px;
            right: 30px; /* RTL: left: 30px; */
            background: linear-gradient(135deg, var(--accent-color), var(--primary-color));
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            text-decoration: none;
            transition: all 0.3s ease;
            z-index: 100;
        }
        .edit-profile-fab:hover {
            transform: scale(1.1) translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.25);
        }
        .edit-profile-action-button { /* زر تعديل تقليدي */
            background: linear-gradient(135deg, var(--accent-color), var(--primary-color));
            border: none;
            padding: 10px 25px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s;
            color: white;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }
        .edit-profile-action-button i { margin-left: 8px; /* RTL: margin-right */ }
        .edit-profile-action-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(var(--accent-color-rgb, 108, 99, 255), 0.3);
        }
        :root { /* لإضافة متغير RGB للون accent إذا لم يكن موجودًا */
            --accent-color-rgb: 108, 99, 255;
        }


        @media (max-width: 768px) {
            .profile-header-view { padding: 30px 20px; }
            .profile-avatar-view { width: 100px; height: 100px; margin-top: -50px; }
            .patient-name-view { font-size: 1.5rem; }
            .profile-details-section { padding: 15px 20px 20px; }
            .detail-item { flex-direction: column; align-items: flex-start; }
            .detail-icon { margin-left: 0; margin-bottom: 8px; width: auto; }
            .edit-profile-fab { width: 50px; height: 50px; font-size: 1.2rem; bottom: 20px; right: 20px; }
        }

    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto"><i class="fas fa-id-card me-2" style="color:var(--primary-color);"></i>ملفي الشخصي</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ عرض البيانات</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <a href="{{ route('patient.profile.edit') }}" class="edit-profile-action-button">
                <i class="fas fa-edit"></i> تعديل بياناتي
            </a>
        </div>
    </div>
@endsection

@section('content')
    @include('Dashboard.messages_alert') {{-- لعرض رسائل النجاح إذا تم التوجيه هنا بعد التحديث --}}

    <div class="patient-profile-view-container animate__animated animate__fadeIn">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="profile-card-view">
                    <div class="profile-header-view">
                        {{-- يمكن وضع أيقونة أو عنصر زخرفي هنا إذا أردت --}}
                    </div>

                    <img class="profile-avatar-view"
                         src="{{ $patient->image ? asset('Dashboard/img/patients/' . $patient->image->filename) : URL::asset('Dashboard/img/default_patient_avatar.png') }}"
                         alt="{{ $patientName }}">

                    <div class="pt-2 pb-4">
                        <h2 class="patient-name-view">{{ $patientName }}</h2>
                        @if($patient->email)
                            <a href="mailto:{{ $patient->email }}" class="patient-email-view">
                                <i class="fas fa-envelope"></i> {{ $patient->email }}
                            </a>
                        @endif
                    </div>


                    <div class="profile-details-section">
                        <h5 class="section-title mb-4" style="font-size: 1.2rem; padding-bottom: 8px;">
                            <i class="fas fa-info-circle me-2" style="color: var(--accent-color);"></i>المعلومات الشخصية والطبية
                        </h5>

                        <div class="row">
                            {{-- رقم الهاتف --}}
                            @if($patient->Phone)
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <div class="detail-icon"><i class="fas fa-phone-alt"></i></div>
                                    <div class="detail-content">
                                        <span class="detail-label">رقم الهاتف</span>
                                        <span class="detail-value">{{ $patient->Phone }}</span>
                                    </div>
                                </div>
                            </div>
                            @endif

                            {{-- تاريخ الميلاد --}}
                            @if($patient->Date_Birth)
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <div class="detail-icon"><i class="fas fa-calendar-day"></i></div>
                                    <div class="detail-content">
                                        <span class="detail-label">تاريخ الميلاد</span>
                                        <span class="detail-value">{{ \Carbon\Carbon::parse($patient->Date_Birth)->translatedFormat('d F Y') }}</span>
                                    </div>
                                </div>
                            </div>
                            @endif

                            {{-- الجنس --}}
                            @if($patient->Gender)
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <div class="detail-icon"><i class="fas {{ $patient->Gender == 1 ? 'fa-mars' : 'fa-venus' }}"></i></div>
                                    <div class="detail-content">
                                        <span class="detail-label">الجنس</span>
                                        <span class="detail-value">{{ $patient->Gender == 1 ? 'ذكر' : 'أنثى' }}</span>
                                    </div>
                                </div>
                            </div>
                            @endif

                            {{-- فصيلة الدم --}}
                            @if($patient->Blood_Group)
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <div class="detail-icon"><i class="fas fa-tint"></i></div>
                                    <div class="detail-content">
                                        <span class="detail-label">فصيلة الدم</span>
                                        <span class="detail-value">{{ $patient->Blood_Group }}</span>
                                    </div>
                                </div>
                            </div>
                            @endif

                             {{-- رقم الهوية --}}
                             @if($patient->national_id)
                             <div class="col-md-6">
                                 <div class="detail-item">
                                     <div class="detail-icon"><i class="fas fa-id-badge"></i></div>
                                     <div class="detail-content">
                                         <span class="detail-label">رقم الهوية</span>
                                         <span class="detail-value">{{ $patient->national_id }}</span>
                                     </div>
                                 </div>
                             </div>
                             @endif

                            {{-- تاريخ التسجيل --}}
                            @if($patient->created_at)
                            <div class="col-md-6">
                                <div class="detail-item">
                                    <div class="detail-icon"><i class="fas fa-calendar-plus"></i></div>
                                    <div class="detail-content">
                                        <span class="detail-label">تاريخ التسجيل</span>
                                        <span class="detail-value">{{ $patient->created_at->translatedFormat('d M Y') }} ({{ $patient->created_at->diffForHumans() }})</span>
                                    </div>
                                </div>
                            </div>
                            @endif

                            {{-- العنوان --}}
                            @if($patientAddress)
                            <div class="col-md-12">
                                <div class="detail-item">
                                    <div class="detail-icon"><i class="fas fa-map-marker-alt"></i></div>
                                    <div class="detail-content">
                                        <span class="detail-label">العنوان</span>
                                        <span class="detail-value">{{ $patientAddress }}</span>
                                    </div>
                                </div>
                            </div>
                            @else
                             <div class="col-md-12">
                                <div class="detail-item">
                                    <div class="detail-icon"><i class="fas fa-map-marker-alt"></i></div>
                                    <div class="detail-content">
                                        <span class="detail-label">العنوان</span>
                                        <span class="detail-value empty">لم يتم إدخال عنوان</span>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- زر التعديل العائم (اختياري إذا كان الزر في الهيدر كافيًا) --}}
    {{-- <a href="{{ route('patient.profile.edit') }}" class="edit-profile-fab" title="تعديل ملفي الشخصي">
        <i class="fas fa-pencil-alt"></i>
    </a> --}}
@endsection

@section('js')
    @parent
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifit-custom.js') }}"></script>
    <script>
        $(document).ready(function() { // استخدام jQuery إذا كنت تفضل
            console.log("Patient profile show page loaded for: {{ $patientName }}");

            // عرض رسائل التنبيه NotifIt (إذا تم التوجيه هنا بعد تحديث ناجح)
            @if (session('success'))
                notif({
                    msg: "<i class='fas fa-check-circle me-2'></i> {{ session('success') }}",
                    type: "success",
                    position: "bottom", // أو "center"
                    autohide: true,
                    timeout: 5000,
                    zindex: 9999
                });
            @endif
        });
    </script>
@endsection
