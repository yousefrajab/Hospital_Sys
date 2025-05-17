{{-- resources/views/Dashboard/patients/showQR.blade.php --}}
@extends('Dashboard.layouts.master')

@php
    $patientNameForTitle = $patient->name ?? __('patients.unknown_patient');
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
            --primary-color: #3a86ff;
            --primary-dark: #2667cc;
            --secondary-color: #8338ec;
            --accent-color: #00b4d8;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --success-color: #38b000;
            --error-color: #ff006e;
            --warning-color: #ffbe0b;
            --card-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
            --transition-speed: 0.3s;
            --admin-radius-lg: 0.75rem;
            --admin-radius-xl: 1rem;
            --admin-bg: #f0f4f8;
            --admin-card-bg: #ffffff;
            --admin-text: #2b2d42;
            --admin-text-secondary: #6c757d;
            --admin-border-color: #e9ecef;
        }

        body {
            background-color: var(--admin-bg);
            font-family: 'Tajawal', sans-serif;
            color: var(--admin-text);
            display: flex;
            align-items: flex-start;
            justify-content: center;
            min-height: 100vh;
            padding-top: 2rem;
            padding-bottom: 2rem;
        }

        .qr-card-container {
            width: 1700px;
            max-width: 5000px;
            background: var(--admin-card-bg);
            border-radius: var(--admin-radius-xl);
            box-shadow: var(--card-shadow);
            overflow: hidden;
            animation: fadeInUp 0.6s ease-out;
            margin: 1rem;
        }

        .qr-card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 1.5rem;
            text-align: center;
        }

        .qr-card-header h3 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .qr-card-header p {
            margin: 5px 0 0;
            opacity: 0.9;
            font-size: 0.9rem;
        }

        .qr-code-section {
            padding: 2rem;
            text-align: center;
            border-bottom: 1px solid var(--admin-border-color);
        }

        .qr-code-image svg {
            display: block;
            margin: 0 auto 1rem auto;
            max-width: 300px;
            height: auto;
            border: 1px solid var(--admin-border-color);
            padding: 8px;
            background-color: white;
            border-radius: var(--admin-radius-lg);
        }

        .qr-code-instructions {
            font-size: 0.9rem;
            color: var(--admin-text-secondary);
        }

        .patient-info-section {
            padding: 1.5rem;
        }

        .info-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .info-list li {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
            font-size: 0.95rem;
        }

        .info-list li:last-child {
            border-bottom: none;
        }

        .info-list li .label {
            color: var(--admin-text-secondary);
            font-weight: 500;
        }

        .info-list li .value {
            color: var(--admin-text);
            font-weight: 600;
            text-align: left;
        }

        .emergency-section {
            background-color: #fff8f8;
            border-left: 4px solid var(--error-color);
            margin: 1rem;
            padding: 1rem;
            border-radius: 0 var(--admin-radius-lg) var(--admin-radius-lg) 0;
        }

        .chronic-diseases-section {
            padding: 1rem;
        }

        .disease-card {
            transition: all 0.3s ease;
        }

        .disease-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .patient-profile-avatar {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
            margin-bottom: 1rem;
        }

        @media print {
            body * {
                visibility: hidden;
            }
            .qr-card-container, .qr-card-container * {
                visibility: visible;
            }
            .qr-card-container {
                position: absolute;
                left: 0;
                top: 0;
                /* width: 100%; */
                height: auto;
                border: none;
                box-shadow: none;
            }
            .no-print {
                display: none !important;
            }
        }

        @media (max-width: 768px) {
            .qr-card-container {
                margin: 0.5rem;
            }
        }
    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto"><i class="fas fa-qrcode text-primary"></i> بطاقة QR للمريض</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ {{ $patientNameForTitle }}</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <button onclick="window.print()" class="btn btn-success btn-sm no-print">
                <i class="fas fa-print me-1"></i> طباعة البطاقة
            </button>
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm ms-2 no-print">
                <i class="fas fa-arrow-left me-1"></i> رجوع
            </a>
        </div>
    </div>
@endsection

@section('content')
    @include('Dashboard.messages_alert')

    <div class="qr-card-container" data-aos="zoom-in">
        <div class="qr-card-header">
            @if ($patient->image)
                <img src="{{ Url::asset('Dashboard/img/patients/' . $patient->image->filename) }}"
                    class="patient-profile-avatar" alt="{{ $patient->name }}">
            @else
                <img src="{{ Url::asset('Dashboard/img/doctor_default.png') }}" class="patient-profile-avatar"
                    alt="صورة افتراضية">
            @endif

            <h3>{{ $patientNameForTitle }}</h3>
            <p>بطاقة تعريف المريض السريعة</p>
        </div>

        @if($patient->emergency_contact || $patient->Blood_Group)
        <div class="emergency-section">
            <h5 class="text-danger"><i class="fas fa-exclamation-triangle"></i> معلومات الطوارئ</h5>
            <div class="row">
                @if($patient->emergency_contact)
                <div class="col-md-6">
                    <p><strong>جهة اتصال الطوارئ:</strong> {{ $patient->emergency_contact }}</p>
                </div>
                @endif
                @if($patient->Blood_Group)
                <div class="col-md-6">
                    <p><strong>فصيلة الدم:</strong> <span class="badge bg-danger" style="color: white" >{{ $patient->Blood_Group }}</span></p>
                </div>
                @endif
            </div>
        </div>
        @endif

        <div class="qr-code-section">
            @if ($patient && $patient->id)
                <div class="qr-code-image">
                    {!! $patient->generateQrCodeSvg(200) !!}
                </div>
                <p class="qr-code-instructions">امسح الرمز لعرض ملف المريض الكامل</p>
                <div class="qr-download-buttons mt-3 no-print">
                    <a href="#" class="btn btn-sm btn-outline-primary" id="downloadPNG">
                        <i class="fas fa-download"></i> تحميل PNG
                    </a>
                    <a href="#" class="btn btn-sm btn-outline-success" id="downloadSVG">
                        <i class="fas fa-download"></i> تحميل SVG
                    </a>
                </div>
            @else
                <p class="text-danger my-4">خطأ: لا يمكن إنشاء رمز QR لهذا المريض.</p>
            @endif
        </div>

        <div class="patient-info-section">
            <h5 class="mb-3" style="color:var(--primary-dark); font-weight:600;">
                <i class="fas fa-user-shield me-2"></i>المعلومات الشخصية
            </h5>
            <ul class="info-list">
                <li>
                    <span class="label"><i class="fas fa-id-card"></i> الرقم الوطني:</span>
                    <span class="value">{{ $patient->national_id ?? '-' }}</span>
                </li>
                <li>
                    <span class="label"><i class="fas fa-envelope"></i> البريد الإلكتروني:</span>
                    <span class="value">{{ $patient->email ?? '-' }}</span>
                </li>
                <li>
                    <span class="label"><i class="fas fa-phone"></i> الهاتف:</span>
                    <span class="value">{{ $patient->Phone ?? '-' }}</span>
                </li>
                <li>
                    <span class="label"><i class="fas fa-calendar-alt"></i> تاريخ الميلاد:</span>
                    <span class="value">
                        {{ $patient->Date_Birth ? \Carbon\Carbon::parse($patient->Date_Birth)->translatedFormat('d M Y') : '-' }}
                    </span>
                </li>
                <li>
                    <span class="label"><i class="fas fa-venus-mars"></i> الجنس:</span>
                    <span class="value badge {{ $patient->Gender == 1 ? 'bg-primary' : 'bg-pink' }}" style="color: white">
                        {{ $patient->Gender == 1 ? __('patients.male') : ($patient->Gender == 2 ? __('patients.female') : '-') }}
                    </span>
                </li>
                @if ($patient->Address)
                <li>
                    <span class="label"><i class="fas fa-map-marker-alt"></i> العنوان:</span>
                    <span class="value">{{ $patient->Address }}</span>
                </li>
                @endif
            </ul>
        </div>

        @if($patient->diagnosedChronicDiseases->isNotEmpty())
        <div class="chronic-diseases-section">
            <h5 class="text-primary"><i class="fas fa-heartbeat"></i> الأمراض المزمنة</h5>
            <div class="row">
                @foreach($patient->diagnosedChronicDiseases as $disease)
                <div class="col-md-6 mb-2">
                    <div class="disease-card p-3 bg-light rounded">
                        <h6 class="d-flex justify-content-between align-items-center">
                            <span><i class="fas fa-disease text-danger me-2"></i>{{ $disease->name }}</span>
                            <span class="badge bg-{{ $disease->pivot->severity == 'high' ? 'danger' : ($disease->pivot->severity == 'medium' ? 'warning' : 'info') }}">
                                {{ $disease->pivot->severity }}
                            </span>
                        </h6>
                        @if($disease->pivot->diagnosis_date)
                        <p class="mb-1"><small>تاريخ التشخيص: {{ $disease->pivot->diagnosis_date->format('Y-m-d') }}</small></p>
                        @endif
                        @if($disease->pivot->current_status)
                        <p class="mb-0"><small>الحالة الحالية: {{ $disease->pivot->current_status }}</small></p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if ($patient->allergies)
        <div class="medical-notes-section p-3 bg-warning bg-opacity-10 mx-3 mb-3 rounded">
            <h5 class="text-warning"><i class="fas fa-allergies"></i> الحساسيات</h5>
            <p>{{ $patient->allergies }}</p>
        </div>
        @endif

        <div class="print-button-container no-print text-center p-3">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print me-2"></i> طباعة هذه البطاقة
            </button>
        </div>
    </div>
@endsection

@section('js')
    @parent
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="{{ URL::asset('dashboard/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('dashboard/plugins/notify/js/notifit-custom.js') }}"></script>
    <script>
        AOS.init({
            duration: 600,
            easing: 'ease-out-cubic',
            once: true
        });

        document.addEventListener('DOMContentLoaded', function() {
            // تحميل QR كصورة PNG
            document.getElementById('downloadPNG').addEventListener('click', function(e) {
                e.preventDefault();
                const svg = document.querySelector('.qr-code-image svg');
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                const data = new XMLSerializer().serializeToString(svg);
                const DOMURL = window.URL || window.webkitURL || window;

                const img = new Image();
                const svgBlob = new Blob([data], {type: 'image/svg+xml;charset=utf-8'});
                const url = DOMURL.createObjectURL(svgBlob);

                img.onload = function() {
                    canvas.width = img.width;
                    canvas.height = img.height;
                    ctx.drawImage(img, 0, 0);
                    DOMURL.revokeObjectURL(url);

                    const png = canvas.toDataURL('image/png');
                    const downloadLink = document.createElement('a');
                    downloadLink.href = png;
                    downloadLink.download = 'patient_qr_{{ $patient->id }}.png';
                    document.body.appendChild(downloadLink);
                    downloadLink.click();
                    document.body.removeChild(downloadLink);
                };

                img.src = url;
            });

            // تحميل QR كـ SVG
            document.getElementById('downloadSVG').addEventListener('click', function(e) {
                e.preventDefault();
                const svg = document.querySelector('.qr-code-image svg').outerHTML;
                const blob = new Blob([svg], {type: 'image/svg+xml'});
                const url = URL.createObjectURL(blob);
                const downloadLink = document.createElement('a');
                downloadLink.href = url;
                downloadLink.download = 'patient_qr_{{ $patient->id }}.svg';
                document.body.appendChild(downloadLink);
                downloadLink.click();
                document.body.removeChild(downloadLink);
                URL.revokeObjectURL(url);
            });

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
