{{-- resources/views/Dashboard/patient_panel/profile/show.blade.php --}}
@extends('Dashboard.layouts.master') {{-- أو الـ layout الخاص بلوحة تحكم المريض --}}

@php
    // استخدام $patient مباشرة، حيث أن الـ Controller يمرره
    $patientName = $patient->getTranslation('name', app()->getLocale(), false) ?: $patient->name;
    $patientAddress = $patient->getTranslation('Address', app()->getLocale(), false) ?: $patient->Address ?? null; // إضافة ?? null للـ fallback
@endphp
@section('title', 'ملفي الشخصي | ' . $patientName)

@section('css')
    @parent
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
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
            --success-color: #28a745;
            --error-color: #dc3545;
            --admin-radius-xl: 1.25rem;
            /* زيادة طفيفة للحواف */
            --admin-shadow-lg: 0 12px 30px rgba(0, 0, 0, 0.08);
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --primary-color: #5a7bf0;
                --secondary-color: #5948d9;
                --accent-color: #67a7f0;
                --light-color: #2a3038;
                --dark-color: #e9ecef;
                --text-muted-custom: #adb5bd;
                --card-border-color: #454c55;
                --admin-bg: #1a1d24;
                --admin-card-bg: #242930;
            }

            .profile-card-view,
            .qr-code-section,
            .patient-info-section,
            .medical-history-item,
            .allergies-section {
                background-color: var(--admin-card-bg) !important;
                border-color: var(--admin-border-color) !important;
            }

            .profile-header-view::after {
                background: var(--admin-card-bg) !important;
            }

            .profile-avatar-view {
                background-color: var(--admin-card-bg) !important;
                border-color: var(--admin-card-bg) !important;
            }

            .patient-name-view,
            .detail-content .detail-value,
            .section-title-profile {
                color: var(--dark-color) !important;
            }

            .patient-email-view,
            .detail-content .detail-label {
                color: var(--text-muted-custom) !important;
            }

            .qr-code-image svg {
                background-color: white !important;
            }

            /* خلفية بيضاء دائمة للـ QR */
        }

        body {
            background-color: var(--admin-bg, #f4f7f9);
            font-family: 'Tajawal', sans-serif;
            color: var(--dark-color);
        }

        .patient-profile-view-container {
            padding: 2rem 1rem;
        }

        .profile-card-view {
            background: white;
            border-radius: var(--admin-radius-xl);
            box-shadow: var(--admin-shadow-lg);
            overflow: hidden;
            border: 1px solid var(--card-border-color);
            animation: fadeInUp 0.5s ease-out forwards;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .profile-header-view {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 50px 30px 70px;
            /* زيادة الحشو السفلي ليتداخل مع الصورة */
            text-align: center;
            position: relative;
        }

        .profile-header-view::after {
            content: '';
            position: absolute;
            bottom: -1px;
            /* ليغطي الخط الفاصل */
            left: 0;
            width: 100%;
            height: 50px;
            background: var(--admin-card-bg);
            border-radius: 100% / 0 0 50px 50px;
            /* تعديل بسيط لشكل الحافة */
            transform: scaleX(1.5);
            /* ليعطي انحناء أكبر */
        }

        .profile-avatar-view {
            width: 100px;
            height: 140px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid var(--admin-card-bg);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            margin: -10px auto 1rem;
            position: relative;
            z-index: 2;
            background-color: white;
        }

        .patient-name-view {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 0.25rem;
            text-align: center;
        }

        .patient-email-view {
            font-size: 1rem;
            color: var(--text-muted-custom);
            margin-bottom: 1.5rem;
            text-align: center;
            display: block;
        }

        .patient-email-view i {
            margin-left: 5px;
        }

        .profile-content-wrapper {
            padding: 0 30px 30px;
        }

        .section-title-profile {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--accent-color);
            display: inline-block;
            /* ليأخذ عرض المحتوى فقط */
        }

        .section-title-profile i {
            margin-left: 8px;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .detail-item-card {
            /* استخدام بطاقات للمعلومات */
            background-color: var(--admin-bg);
            /* لون خلفية مختلف قليلاً */
            padding: 1rem;
            border-radius: var(--admin-radius-lg);
            display: flex;
            align-items: flex-start;
            border: 1px solid var(--card-border-color);
            transition: var(--admin-transition);
        }

        .detail-item-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.07);
        }

        .detail-icon {
            font-size: 1.3rem;
            color: var(--primary-color);
            width: 35px;
            flex-shrink: 0;
            text-align: center;
            margin-left: 12px;
            padding-top: 3px;
        }

        .detail-content .detail-label {
            font-size: 0.8rem;
            color: var(--text-muted-custom);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
            display: block;
        }

        .detail-content .detail-value {
            font-size: 1rem;
            font-weight: 500;
            color: var(--dark-color);
            word-break: break-word;
        }

        .detail-content .detail-value.empty {
            color: var(--text-muted-custom);
            font-style: italic;
        }

        .qr-section-profile {
            text-align: center;
            margin-top: 2rem;
            padding: 1.5rem;
            background-color: var(--admin-bg);
            border-radius: var(--admin-radius-lg);
        }

        .qr-code-image-profile svg {
            display: block;
            margin: 0 auto 1rem auto;
            max-width: 180px;
            height: auto;
            border: 1px solid var(--card-border-color);
            padding: 6px;
            background-color: white;
            border-radius: var(--admin-radius-md);
        }

        .qr-download-buttons-profile .btn {
            font-size: 0.8rem;
            padding: 0.4rem 0.8rem;
            margin: 0 0.25rem;
        }

        .medical-history-item,
        .allergies-section {
            background-color: var(--admin-bg);
            padding: 1rem;
            border-radius: var(--admin-radius-lg);
            margin-bottom: 1rem;
            border: 1px solid var(--card-border-color);
        }

        .medical-history-item h6,
        .allergies-section h6 {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }

        .medical-history-item h6 i,
        .allergies-section h6 i {
            margin-left: 8px;
        }

        .medical-history-item p,
        .allergies-section p {
            font-size: 0.9rem;
            color: var(--dark-color);
            margin-bottom: 0.25rem;
        }

        .medical-history-item .text-muted,
        .allergies-section .text-muted {
            font-size: 0.8rem;
        }

        .edit-profile-action-button {
            /* زر التعديل في الهيدر */
            background: white;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
            padding: 8px 20px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s;
            border-radius: var(--admin-radius-full);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }

        .edit-profile-action-button:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(var(--primary-color-rgb, 67, 97, 238), 0.3);
        }

        .edit-profile-action-button i {
            margin-left: 8px;
        }

        @media print {

            /* ... (أنماط الطباعة كما هي، تأكد من استهداف الكلاسات الصحيحة) ... */
            body * {
                visibility: hidden;
            }

            .profile-card-view,
            .profile-card-view * {
                visibility: visible;
            }

            .profile-card-view {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                height: auto;
                border: none;
                box-shadow: none;
                margin: 0;
                border-radius: 0;
                animation: none !important;
            }

            .no-print,
            .main-header,
            .main-sidebar,
            .breadcrumb-header,
            #back-to-top,
            footer {
                display: none !important;
            }
        }

        /* ... (بقية @media (max-width: 768px) إذا أردت تعديلات إضافية) ... */
    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between no-print">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto"><i class="fas fa-id-card me-2"
                        style="color:var(--primary-color);"></i>ملفي الشخصي</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ {{ $patientName }}</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            {{-- افترض أن لديك route لتعديل ملف المريض الشخصي --}}
            @if (Route::has('patient.profile.edit'))
                <a href="{{ route('patient.profile.edit') }}" class="edit-profile-action-button">
                    <i class="fas fa-user-pen"></i> تعديل بياناتي
                </a>
            @endif
        </div>
    </div>
@endsection

@section('content')
    @include('Dashboard.messages_alert')

    <div class="patient-profile-view-container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-11"> {{-- تعديل بسيط لعرض البطاقة --}}
                <div class="profile-card-view animate__animated animate__fadeIn">
                    <div class="profile-header-view">
                        {{-- يمكن ترك هذا فارغًا أو إضافة نص ترحيبي صغير --}}
                    </div>

                    {{-- الصورة والاسم والإيميل يتم وضعهم بعد الهيدر وقبل بقية التفاصيل --}}
                    <div class="text-center" style="margin-top: -65px; position: relative; z-index: 3;">
                        @if ($patient->image && $patient->image->filename)
                            <img src="{{ asset('Dashboard/img/patients/' . $patient->image->filename) }}"
                                {{-- استخدام storage path --}} class="profile-avatar-view" alt="{{ $patientName }}">
                        @else
                            <img src="{{ URL::asset('Dashboard/img/default_doctor.png') }}" class="profile-avatar-view"
                                alt="صورة افتراضية">
                        @endif
                        <h2 class="patient-name-view mt-2">{{ $patientName }}</h2>
                        @if ($patient->email)
                            <a href="mailto:{{ $patient->email }}" class="patient-email-view">
                                <i class="fas fa-envelope"></i> {{ $patient->email }}
                            </a>
                        @endif
                    </div>

                    <div class="profile-content-wrapper">
                        {{-- قسم معلومات QR --}}
                        <div class="qr-section-profile" data-aos="fade-up">
                            <h5 class="section-title-profile"><i class="fas fa-qrcode"></i> بطاقتك الرقمية (QR)</h5>
                            @if ($patient && $patient->id && isset($qrCodeSvg))
                                {{-- ** استخدام $qrCodeSvg ** --}}
                                <div class="qr-code-image-profile">
                                    {!! $qrCodeSvg !!}
                                </div>
                                <p class="qr-code-instructions mt-2">امسح الرمز للوصول السريع إلى معلوماتك الأساسية.</p>
                                <div class="qr-download-buttons-profile mt-2 no-print">
                                    <a href="#" class="btn btn-sm btn-outline-primary me-2" id="downloadPNGProfile">
                                        <i class="fas fa-download"></i> PNG
                                    </a>
                                    <a href="#" class="btn btn-sm btn-outline-success" id="downloadSVGProfile">
                                        <i class="fas fa-download"></i> SVG
                                    </a>
                                </div>
                            @else
                                <p class="text-muted my-3">تعذر إنشاء رمز QR حاليًا.</p>
                            @endif
                        </div>

                        <hr class="my-4">

                        <h5 class="section-title-profile"><i class="fas fa-info-circle"></i> المعلومات الشخصية</h5>
                        <div class="details-grid" data-aos="fade-up" data-aos-delay="100">
                            <div class="detail-item-card">
                                <div class="detail-icon"><i class="fas fa-id-badge"></i></div>
                                <div class="detail-content">
                                    <span class="detail-label">الرقم الوطني</span>
                                    <span class="detail-value">{{ $patient->national_id ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="detail-item-card">
                                <div class="detail-icon"><i class="fas fa-phone-alt"></i></div>
                                <div class="detail-content">
                                    <span class="detail-label">رقم الهاتف</span>
                                    <span class="detail-value">{{ $patient->Phone ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="detail-item-card">
                                <div class="detail-icon"><i class="fas fa-calendar-day"></i></div>
                                <div class="detail-content">
                                    <span class="detail-label">تاريخ الميلاد</span>
                                    <span
                                        class="detail-value">{{ $patient->Date_Birth ? \Carbon\Carbon::parse($patient->Date_Birth)->translatedFormat('d F Y') : '-' }}</span>
                                </div>
                            </div>
                            <div class="detail-item-card">
                                <div class="detail-icon"><i
                                        class="fas {{ $patient->Gender == 1 ? 'fa-mars' : 'fa-venus' }}"></i></div>
                                <div class="detail-content">
                                    <span class="detail-label">الجنس</span>
                                    <span
                                        class="detail-value">{{ $patient->Gender == 1 ? 'ذكر' : ($patient->Gender == 2 ? 'أنثى' : '-') }}</span>
                                </div>
                            </div>
                            @if ($patientAddress)
                                <div class="detail-item-card" style="grid-column: span 2 / span 2;"> {{-- لجعل العنوان يأخذ عمودين --}}
                                    <div class="detail-icon"><i class="fas fa-map-marker-alt"></i></div>
                                    <div class="detail-content">
                                        <span class="detail-label">العنوان</span>
                                        <span class="detail-value">{{ $patientAddress }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <hr class="my-4">
                        <h5 class="section-title-profile"><i class="fas fa-heartbeat"></i> المعلومات الطبية</h5>

                        @if ($patient->Blood_Group)
                            <div class="detail-item-card mb-3" data-aos="fade-up" data-aos-delay="200">
                                <div class="detail-icon" style="color: var(--error-color);"><i class="fas fa-tint"></i>
                                </div>
                                <div class="detail-content">
                                    <span class="detail-label">فصيلة الدم</span>
                                    <span class="detail-value"
                                        style="font-weight: 700;">{{ $patient->Blood_Group }}</span>
                                </div>
                            </div>
                        @endif

                        {{-- عرض الحساسيات (من الحقل النصي في جدول patients) --}}
                        @if ($patient->allergies)
                            <div class="allergies-section mt-3" data-aos="fade-up" data-aos-delay="300">
                                <h6><i class="fas fa-allergies text-warning"></i> الحساسيات المسجلة</h6>
                                <p class="mb-0">{{ $patient->allergies }}</p>
                            </div>
                        @endif

                        {{-- عرض الأمراض المزمنة (من الحقل النصي في جدول patients) --}}
                        @if ($patient->chronic_diseases && !$patient->diagnosedChronicDiseases->isNotEmpty())
                            {{-- اعرض فقط إذا لم تكن هناك أمراض منظمة --}}
                            <div class="medical-history-item mt-3" data-aos="fade-up" data-aos-delay="400">
                                <h6><i class="fas fa-notes-medical text-danger"></i> الأمراض المزمنة المسجلة (كنص)</h6>
                                <p class="mb-0">{{ $patient->chronic_diseases }}</p>
                            </div>
                        @endif

                        {{-- عرض الأمراض المزمنة المنظمة (من العلاقة) --}}
                        @if ($patient->relationLoaded('diagnosedChronicDiseases') && $patient->diagnosedChronicDiseases->isNotEmpty())
                            <div class="mt-4" data-aos="fade-up" data-aos-delay="400">
                                <h6 class="mb-3" style="font-weight:600; color:var(--primary-dark);"><i
                                        class="fas fa-laptop-medical me-2"></i> قائمة الأمراض المشخصة:</h6>
                                <div class="row g-3">
                                    @foreach ($patient->diagnosedChronicDiseases as $diagnosedDisease)
                                        <div class="col-md-6">
                                            <div class="medical-history-item p-3">
                                                <h6 class="d-flex justify-content-between align-items-center"
                                                    style="color:var(--dark-color);">
                                                    <span><i class="fas fa-disease me-2"
                                                            style="color:var(--error-color);"></i>{{ $diagnosedDisease->name }}</span>
                                                    @if ($diagnosedDisease->pivot->current_status)
                                                        <span
                                                            class="badge bg-light text-dark border">{{ \App\Models\PatientChronicDisease::getStatuses()[$diagnosedDisease->pivot->current_status] ?? $diagnosedDisease->pivot->current_status }}</span>
                                                    @endif
                                                </h6>
                                                @if ($diagnosedDisease->pivot->diagnosed_at)
                                                    <p class="mb-1"><small class="text-muted">تاريخ التشخيص:
                                                            {{ \Carbon\Carbon::parse($diagnosedDisease->pivot->diagnosed_at)->format('Y-m-d') }}</small>
                                                    </p>
                                                @endif
                                                @if ($diagnosedDisease->pivot->notes)
                                                    <p class="mb-0"><small class="text-muted">ملاحظات:
                                                            {{ Str::limit($diagnosedDisease->pivot->notes, 70) }}</small>
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- زر تعديل البيانات في الأسفل إذا لم يكن هناك زر عائم --}}
                        @if (Route::has('patient.profile.edit'))
                            <div class="text-center mt-5 no-print">
                                <a href="{{ route('patient.profile.edit') }}"
                                    class="btn edit-profile-action-button px-5 py-3">
                                    <i class="fas fa-user-pen"></i> تعديل بياناتي الشخصية والطبية
                                </a>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @parent
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifit-custom.js') }}"></script>
    <script>
        AOS.init({
            duration: 700,
            easing: 'ease-out-quad',
            once: true
        });

        document.addEventListener('DOMContentLoaded', function() {
            const patientId = "{{ $patient->id }}";
            const patientNameForFile = "{{ Str::slug($patientName, '_') }}";

            function setupDownloadButton(buttonId, format) {
                const downloadBtn = document.getElementById(buttonId);
                if (!downloadBtn) return;

                downloadBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const svgElement = document.querySelector('.qr-code-image-profile svg');
                    if (!svgElement) {
                        notif({
                            msg: "خطأ: لم يتم العثور على رمز QR.",
                            type: "error",
                            position: "center"
                        });
                        return;
                    }
                    const svgData = new XMLSerializer().serializeToString(svgElement);

                    if (format === 'png') {
                        const canvas = document.createElement('canvas');
                        const svgSize = svgElement.getBoundingClientRect();
                        // زيادة الدقة للـ PNG
                        canvas.width = (svgSize.width || 200) * 2;
                        canvas.height = (svgSize.height || 200) * 2;
                        const ctx = canvas.getContext('2d');
                        const img = new Image();
                        img.onload = function() {
                            ctx.fillStyle = "#FFFFFF"; // خلفية بيضاء
                            ctx.fillRect(0, 0, canvas.width, canvas.height);
                            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                            const pngUrl = canvas.toDataURL('image/png');
                            triggerDownload(pngUrl, `patient_qr_${patientNameForFile}.png`);
                        };
                        img.src = 'data:image/svg+xml;base64,' + btoa(unescape(encodeURIComponent(
                        svgData)));
                    } else if (format === 'svg') {
                        const blob = new Blob([svgData], {
                            type: 'image/svg+xml;charset=utf-8'
                        });
                        const url = URL.createObjectURL(blob);
                        triggerDownload(url, `patient_qr_${patientNameForFile}.svg`);
                        URL.revokeObjectURL(url); // تحرير الـ URL بعد التحميل
                    }
                });
            }

            function triggerDownload(href, filename) {
                const downloadLink = document.createElement('a');
                downloadLink.href = href;
                downloadLink.download = filename;
                document.body.appendChild(downloadLink);
                downloadLink.click();
                document.body.removeChild(downloadLink);
            }

            setupDownloadButton('downloadPNGProfile', 'png');
            setupDownloadButton('downloadSVGProfile', 'svg');

            @if (session('success'))
                notif({
                    msg: "<i class='fas fa-check-circle me-2'></i> {{ session('success') }}",
                    type: "success",
                    position: "bottom",
                    autohide: true,
                    timeout: 5000
                });
            @endif
            @if (session('error'))
                notif({
                    msg: "<i class='fas fa-exclamation-triangle me-2'></i> {{ session('error') }}",
                    type: "error",
                    position: "bottom",
                    autohide: true,
                    timeout: 7000
                });
            @endif
        });
    </script>
@endsection
