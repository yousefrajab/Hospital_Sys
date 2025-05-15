{{-- resources/views/Dashboard/Patients/showQR.blade.php --}}
@extends('Dashboard.layouts.master')

@php
    $patientNameForTitle = $patient->name ?? 'مريض غير محدد';
@endphp

@section('title')
    <i class="fas fa-qrcode"></i> بطاقة QR للمريض - {{ $patientNameForTitle }}
@endsection

@section('css')
    @parent
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="{{ URL::asset('dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <style>
        :root {
            --primary-color: #3a86ff; --primary-dark: #2667cc; --secondary-color: #8338ec;
            --accent-color: #00b4d8; --light-color: #f8f9fa; --dark-color: #212529;
            --success-color: #38b000; --error-color: #ff006e; --warning-color: #ffbe0b;
            --card-shadow: 0 10px 25px rgba(0, 0, 0, 0.08); --transition-speed: 0.3s;
            --admin-radius-lg: 0.75rem; --admin-radius-xl: 1rem; --admin-bg: #f0f4f8;
            --admin-card-bg: #ffffff; --admin-text: #2b2d42; --admin-text-secondary: #6c757d;
            --admin-border-color: #e9ecef;
        }
        @media (prefers-color-scheme: dark) {
            :root { /* ... (أنماط الوضع الداكن) ... */ }
            .qr-content-card { background-color: #2d3748; border-color: var(--admin-border-color); }
            .qr-content-card strong { color: var(--admin-text) !important; }
        }
        body { /* ... (كما هي) ... */ }
        .qr-page-wrapper { display: flex; align-items: flex-start; justify-content: center; min-height: 100vh; padding: 2rem 1rem; }
        .qr-main-card {
            width: 100%; max-width: 1000px; /* حجم مناسب لبطاقة QR */
            background: var(--admin-card-bg); border-radius: var(--admin-radius-xl);
            box-shadow: var(--card-shadow); overflow: hidden; animation: fadeInUp 0.6s ease-out;
        }
        .qr-header-patient {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white; padding: 1.25rem; text-align: center;
        }
        .patient-avatar-qr {
            width: 90px; height: 90px; object-fit: cover; border-radius: 50%;
            border: 4px solid white; box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            margin: -45px auto 10px auto; display: block; position: relative; z-index: 2;
            background-color: var(--admin-card-bg);
        }
        .qr-header-patient h3 { margin: 0; font-size: 1.3rem; font-weight: 700; }
        .qr-header-patient p { margin: 5px 0 0; opacity: 0.9; font-size: 0.85rem; }

        .qr-code-display-area { padding: 1.5rem; text-align: center; background-color: #fdfdfd; }
        .qr-code-image-wrapper svg {
            display: block; margin: 0 auto 1rem auto;
            width: 200px; height: 200px; /* حجم QR Code */
            border: 1px solid var(--admin-border-color); padding: 5px; background-color: white;
            border-radius: var(--admin-radius-md);
        }
        .qr-scan-instruction { font-size: 0.8rem; color: var(--admin-text-secondary); }

        .emergency-info-box {
            background-color: rgba(var(--error-color, 255, 0, 110), 0.07);
            border: 1px solid rgba(var(--error-color, 255, 0, 110), 0.2);
            border-left: 4px solid var(--error-color);
            padding: 1rem; margin: 1rem; border-radius: var(--admin-radius-md);
        }
        .emergency-info-box h5 { color: var(--error-color); font-weight: 700; font-size: 1rem; margin-bottom: 0.5rem; }
        .emergency-info-box p { margin-bottom: 0.25rem; font-size: 0.9rem; color: var(--admin-text); }
        .emergency-info-box p strong { color: var(--admin-text-secondary); }

        .medical-summary-box { padding: 0 1rem 1.5rem 1rem; }
        .medical-summary-box h5 {
            font-size: 1rem; font-weight: 600; color: var(--primary-dark);
            margin-bottom: 0.75rem; padding-bottom: 0.5rem;
            border-bottom: 1px dashed var(--admin-border-color);
        }
        .summary-list { list-style: none; padding: 0; margin: 0; }
        .summary-list li { display: flex; justify-content: space-between; padding: 0.5rem 0; font-size: 0.9rem; border-bottom: 1px dotted #f0f0f0;}
        .summary-list li:last-child { border-bottom: none; }
        .summary-list .label { color: var(--admin-text-secondary); }
        .summary-list .value { color: var(--admin-text); font-weight: 500; text-align: left; }

        .print-actions { text-align: center; padding: 1rem; border-top: 1px solid var(--admin-border-color); background-color: var(--admin-bg); }
        .btn-print-this-card { background-color: var(--admin-primary); color: white; border:none; }

        @media print { /* نفس أنماط الطباعة تقريبًا */
            body { background: white !important; padding:0; margin:0; display: block; align-items: initial; justify-content: initial;}
            .qr-page-wrapper { padding:0; }
            .qr-main-card { box-shadow: none !important; border: 1px solid #ccc !important; margin: 0 auto !important; border-radius: 0 !important; max-width: 100% !important; width: 210mm !important; /* حجم A4 تقريبي للطول، العرض سيتكيف */ height: auto !important; }
            .qr-header-patient { padding: 0.75rem !important; }
            .patient-avatar-qr { width: 70px !important; height: 70px !important; margin-top: 10px !important; }
            .qr-code-display-area { padding: 1rem !important; }
            .qr-code-image-wrapper svg { width: 180px !important; height: 180px !important; }
            .emergency-info-box, .medical-summary-box { padding: 0.75rem !important; margin: 0.5rem !important; }
            .no-print, .main-header, .main-sidebar, .breadcrumb-header, #back-to-top, footer, .page-title-header { display: none !important; }
            h3,h4,h5,p,li { font-size: 10pt !important; } /* تعديل حجم الخط للطباعة */
        }
    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between no-print page-title-header">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto"><i class="fas fa-qrcode text-primary"></i> بطاقة QR للمريض</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ {{ $patientNameForTitle }}</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <button onclick="window.print()" class="btn btn-success btn-sm">
                <i class="fas fa-print me-1"></i> طباعة البطاقة
            </button>
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm ms-2">
                <i class="fas fa-arrow-left me-1"></i> رجوع
            </a>
        </div>
    </div>
@endsection

@section('content')
    @include('Dashboard.messages_alert') {{-- هذا سيتم إخفاؤه عند الطباعة --}}

    <div class="qr-page-wrapper">
        <div class="qr-main-card animate__animated animate__fadeIn" data-aos="zoom-in-up">
            <div class="qr-header-patient m" style="margin-top: 30px" >
                @if ($patient->image && $patient->image->filename)
                    <img src="{{ asset('Dashboard/img/patients/' . $patient->image->filename) }}"
                        class="patient-avatar-qr" alt="{{ $patient->name }}">
                @else
                    <img src="{{ URL::asset('Dashboard/img/default_patient_avatar.png') }}" class="patient-avatar-qr"
                        alt="صورة افتراضية">
                @endif
                <h3>{{ $patientNameForTitle }}</h3>
                <p>بطاقة معلومات طبية سريعة</p>
            </div>

            <div class="qr-code-display-area">
                @if ($patient && $patient->id && isset($qrCodeSvg))
                    <div class="qr-code-image-wrapper">
                        {!! $qrCodeSvg !!}
                    </div>
                    <p class="qr-scan-instruction">امسح الرمز للوصول إلى ملف المريض الإلكتروني (يتطلب إنترنت).</p>
                    <div class="qr-download-buttons mt-2 no-print">
                        <a href="#" class="btn btn-sm btn-outline-primary" id="downloadPatientPNG">
                            <i class="fas fa-image me-1"></i> PNG
                        </a>
                        <a href="#" class="btn btn-sm btn-outline-success ms-2" id="downloadPatientSVG">
                            <i class="fas fa-vector-square me-1"></i> SVG
                        </a>
                    </div>
                @else
                    <p class="text-danger my-3 alert alert-warning"><i class="fas fa-exclamation-triangle me-1"></i> لا يمكن إنشاء QR Code.</p>
                @endif
            </div>

            {{-- عرض المعلومات المستخرجة من الـ QR Code (التي لا تحتاج لإنترنت) --}}
            @if(isset($emergencyInfo) && !empty($emergencyInfo))
                <div class="emergency-info-box">
                    <h5><i class="fas fa-first-aid me-2"></i> هام للطوارئ</h5>
                    @foreach($emergencyInfo as $label => $value)
                        @if(!empty($value) && $value !== '-')
                        <p><strong class="text-secondary">{{ $label }}:</strong> {{ $value }}</p>
                        @endif
                    @endforeach
                </div>
            @endif

            @if(isset($medicalSummary) && !empty($medicalSummary))
            <div class="medical-summary-box">
                <h5><i class="fas fa-notes-medical me-2"></i> ملخص المعلومات</h5>
                <ul class="summary-list">
                    @foreach($medicalSummary as $label => $value)
                        @if(!empty($value) && $value !== '-')
                        <li>
                            <span class="label">{{ $label }}:</span>
                            <span class="value">{{ $value }}</span>
                        </li>
                        @endif
                    @endforeach
                    {{-- الرابط المباشر الذي كان مشفرًا في الـ QR --}}
                    @if($patient && Route::has('admin.Patients.show'))
                        <li>
                            <span class="label"><i class="fas fa-link"></i> ملف كامل (إنترنت):</span>
                            <span class="value"><a href="{{ route('admin.Patients.show', $patient->id) }}" target="_blank" rel="noopener noreferrer">اضغط هنا</a></span>
                        </li>
                    @endif
                </ul>
            </div>
            @endif

            <div class="print-actions no-print">
                <button onclick="window.print()" class="btn btn-primary btn-print-card">
                    <i class="fas fa-print me-2"></i> طباعة هذه البطاقة
                </button>
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
        AOS.init({ duration: 700, easing: 'ease-out-quad', once: true });

        document.addEventListener('DOMContentLoaded', function() {
            const patientIdForFile = "{{ $patient->id }}";
            const patientNameForFile = "{{ Str::slug($patientNameForTitle, '_') }}";

            function setupQrDownload(buttonId, format) {
                const downloadBtn = document.getElementById(buttonId);
                if (!downloadBtn) return;

                downloadBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const svgElement = document.querySelector('.qr-code-image-wrapper svg');
                    if (!svgElement) {
                        alert('عنصر SVG الخاص بـ QR Code غير موجود.');
                        return;
                    }
                    const svgData = new XMLSerializer().serializeToString(svgElement);

                    if (format === 'png') {
                        const canvas = document.createElement('canvas');
                        const svgSize = svgElement.getBoundingClientRect(); // الحصول على الأبعاد الفعلية
                        canvas.width = svgSize.width * 2; // زيادة الدقة
                        canvas.height = svgSize.height * 2;
                        const ctx = canvas.getContext('2d');
                        const img = new Image();
                        img.onload = function() {
                            ctx.fillStyle = "#FFFFFF"; // خلفية بيضاء
                            ctx.fillRect(0, 0, canvas.width, canvas.height);
                            ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                            const pngUrl = canvas.toDataURL('image/png');
                            triggerDownload(pngUrl, `patient_qr_${patientNameForFile}.png`);
                        };
                        img.src = 'data:image/svg+xml;base64,' + btoa(unescape(encodeURIComponent(svgData)));
                    } else if (format === 'svg') {
                        const blob = new Blob([svgData], {type: 'image/svg+xml;charset=utf-8'});
                        const url = URL.createObjectURL(blob);
                        triggerDownload(url, `patient_qr_${patientNameForFile}.svg`);
                        URL.revokeObjectURL(url); // تحرير الذاكرة بعد فترة قصيرة
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

            setupQrDownload('downloadPatientPNG', 'png');
            setupQrDownload('downloadPatientSVG', 'svg');

            // ... (رسائل NotifIt كما هي)
            @if (session('success')) /* ... */ @endif
            @if (session('error')) /* ... */ @endif
        });
    </script>
@endsection
