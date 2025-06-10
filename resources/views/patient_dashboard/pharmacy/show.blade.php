@extends('Dashboard.layouts.master')
@section('title', 'تفاصيل الوصفة: ' . $prescription->prescription_number)

@section('css')
    @parent
    {{-- CSS files needed for this page (AOS, FontAwesome, NotifIt) --}}
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="{{ URL::asset('dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />

    <style>
        /* --- Root Variables & Basic Styling (Adopted from your previous clean styles) --- */
        :root {
            --bs-primary-rgb: 67, 97, 238;
            --bs-primary: rgb(var(--bs-primary-rgb));
            --bs-primary-dark: #3a58d8;
            --bs-secondary-rgb: 108, 117, 125;
            --bs-secondary: rgb(var(--bs-secondary-rgb));
            --bs-success-rgb: 25, 135, 84;
            --bs-success: rgb(var(--bs-success-rgb));
            --bs-info-rgb: 13, 202, 240;
            --bs-info: rgb(var(--bs-info-rgb));
            --bs-warning-rgb: 255, 193, 7;
            --bs-warning: rgb(var(--bs-warning-rgb));
            --bs-danger-rgb: 220, 53, 69;
            --bs-danger: rgb(var(--bs-danger-rgb));
            --bs-light-rgb: 248, 249, 250;
            --bs-light: rgb(var(--bs-light-rgb));
            --bs-dark-rgb: 33, 37, 41;
            --bs-dark: rgb(var(--bs-dark-rgb));
            --bs-body-bg: #f8f9fc;
            --bs-border-color: #e3e6f0;
            --bs-card-border-radius: 0.45rem;
            --bs-card-box-shadow: 0 0.15rem 1.75rem 0 rgba(0,0,0,0.05);
        }
        body { font-family: 'Tajawal', sans-serif; background-color: var(--bs-body-bg); color: #5a5c69; }
        .card { border-radius: var(--bs-card-border-radius); box-shadow: var(--bs-card-box-shadow); border: 1px solid var(--bs-border-color); margin-bottom: 1.5rem; }
        .card-header { background-color: #fff; border-bottom: 1px solid var(--bs-border-color); padding: 0.9rem 1.25rem; }
        .card-title { font-weight: 600; color: var(--bs-dark); }

        /* Status Badges (Your good styles are assumed here) */
        .status-badge { padding: 0.4em 0.8em; border-radius: 50px; font-size: 0.8rem; font-weight: 500; letter-spacing: 0.3px; min-width: 110px; text-align: center; display: inline-block; }
        .status-new { background-color: rgba(var(--bs-info-rgb), 0.15); color: rgb(var(--bs-info-rgb)); border: 1px solid rgba(var(--bs-info-rgb), 0.3); }
        .status-pending_review { background-color: rgba(var(--bs-warning-rgb), 0.2); color: #a17d06; border: 1px solid rgba(var(--bs-warning-rgb), 0.4); }
        .status-approved { background-color: rgba(var(--bs-primary-rgb), 0.15); color: var(--bs-primary); border: 1px solid rgba(var(--bs-primary-rgb), 0.3); }
        .status-ready_for_pickup { background-color: #e2f0d9; color: #548235; border: 1px solid #c5e0b4;}
        .status-processing { background-color: #fff3cd; color: #664d03; border: 1px solid #ffecb5;}
        .status-dispensed { background-color: rgba(var(--bs-success-rgb), 0.15); color: rgb(var(--bs-success-rgb)); border: 1px solid rgba(var(--bs-success-rgb), 0.3); }
        .status-partially_dispensed { background-color: rgba(255, 165, 0, 0.15); color: #cc8400; border: 1px solid rgba(255, 165, 0, 0.3); }
        .status-cancelled_by_doctor,
        .status-cancelled_by_pharmacist,
        .status-cancelled_by_patient { background-color: rgba(var(--bs-danger-rgb), 0.1); color: rgb(var(--bs-danger-rgb)); border: 1px solid rgba(var(--bs-danger-rgb), 0.2); }
        .status-on_hold { background-color: rgba(var(--bs-secondary-rgb), 0.15); color: rgb(var(--bs-secondary-rgb)); border: 1px solid rgba(var(--bs-secondary-rgb), 0.2); }
        .status-expired { background-color: #e9ecef; color: #495057; border: 1px solid #ced4da;}
        .status-refill_requested { background-color: #ffe6cc; color: #c45100; border: 1px solid #ffdab3; }
        .status-default { background-color: #f8f9fa; color: #212529; border: 1px solid #dee2e6; }


        /* --- Styles for the show page --- */
        .prescription-details-card { margin-top: 1rem; } /* Add some top margin */

        .prescription-main-header {
            background: linear-gradient(135deg, #4e73df 0%, #36b9cc 100%); /* Example gradient */
            color: #fff;
            padding: 1.5rem 1.75rem;
            border-radius: var(--bs-card-border-radius) var(--bs-card-border-radius) 0 0;
            margin: -1px -1px 0 -1px; /* To slightly overlap card border for seamless look */
        }
        .prescription-main-header h3 {
            margin-bottom: 0.25rem;
            font-size: 1.5rem; /* Slightly larger font for prescription number */
            font-weight: 600;
        }
        .prescription-main-header p {
            margin-bottom: 0;
            font-size: 0.9rem;
            opacity: 0.9;
        }
        .prescription-main-header .status-badge { /* Make status badge more prominent in header */
            font-size: 0.9rem;
            padding: 0.5em 1em;
            background-color: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.4);
            backdrop-filter: blur(4px);
            color: #fff;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); /* Adjust minmax for responsiveness */
            gap: 1.25rem; /* Increased gap */
            margin-bottom: 1.5rem;
        }
        .info-item p.info-label { /* Changed to p for label for better semantics */
            margin-bottom: 0.3rem;
            font-weight: 500; /* Medium weight for label */
            color: var(--bs-primary-dark); /* Darker primary for label */
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }
        .info-item .info-value {
            color: #36454F; /* Charcoal color for value for better readability */
            font-size: 0.95rem;
            font-weight: 500;
        }
        .info-item .info-value i {
            margin-right: 0.5rem; /* RTL: me- (margin-end) */
            opacity: 0.7;
            color: var(--bs-secondary);
        }

        .section-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--bs-dark);
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--bs-primary);
            display: inline-block; /* To make border-bottom only span the text width */
        }

        .medication-list { margin-top: 0.5rem; }
        .medication-list .list-group-item {
            border-color: var(--bs-border-color);
            padding: 1rem 1.25rem;
            margin-bottom: 0.75rem; /* Space between medication items */
            border-radius: var(--bs-card-border-radius) !important; /* Ensure border radius */
            border-width: 1px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.04);
            transition: transform 0.2s ease-out, box-shadow 0.2s ease-out;
        }
        .medication-list .list-group-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.08);
        }
        .medication-list .med-name {
            font-weight: 600;
            color: var(--bs-primary-dark);
            font-size: 1.1rem;
        }
        .medication-list .med-details {
            font-size: 0.85rem;
            color: #6c757d; display: block; margin-top:0.2rem;
        }
        .medication-list .med-instructions {
            background-color: #f0f5ff; /* Lighter blueish background */
            padding: 0.75rem 1rem;
            border-radius: 0.3rem;
            margin-top: 0.75rem;
            font-size: 0.9rem;
            color: #435D7D;  /* Darker blue text for instructions */
            border-left: 4px solid var(--bs-info);
            white-space: pre-wrap; /* To respect newlines in instructions */
        }
        .medication-list .med-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 0.5rem;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }
        .medication-list .med-info-grid > div > strong {
            color: var(--bs-secondary);
            font-weight: 500;
        }

        .notes-section p {
            white-space: pre-wrap;
            background-color: #fdfaf0;
            padding: 0.75rem 1rem;
            border-radius: 5px;
            border: 1px solid #fceec9;
            font-size:0.95rem;
            color: #5d533c;
        }

        .action-button-container {
            padding: 1.25rem;
            border-top: 1px solid var(--bs-border-color);
            background-color: #fcfdff;
            text-align: center; /* Center buttons or align as per your template */
        }
        .action-button-container .btn {
             padding: 0.6rem 1.5rem; /* Larger buttons */
             font-size: 0.95rem;
        }
    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto">
                    <a href="{{ route('patient.pharmacy.index') }}" class="text-primary-dark tx-16 hover-underline">
                        <i class="fas fa-arrow-left me-2 opacity-75"></i> وصفاتي الطبية
                    </a>
                </h4>
                <span class="text-muted mt-1 tx-13 mx-2">/</span>
                <span class="mt-1 tx-13"> تفاصيل الوصفة</span>
            </div>
        </div>
        {{-- Placeholder for any top-right actions if needed --}}
    </div>
@endsection

@section('content')
    @include('Dashboard.messages_alert')

    <div class="card prescription-details-card" data-aos="fade-up">
        {{-- Header with Prescription Number, Date, and Status --}}
        <div class="prescription-main-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3>وصفة طبية رقم: {{ $prescription->prescription_number }}</h3>
                    <p><i class="fas fa-calendar-alt me-1 opacity-75"></i>تاريخ الإصدار: {{ $prescription->prescription_date->translatedFormat('l، d F Y') }}</p>
                </div>
                <div>
                     <span class="status-badge {{ $prescription->status_badge_class }}">
                         {{ $prescription->status_display }}
                     </span>
                </div>
            </div>
        </div>

        <div class="card-body p-4">
            {{-- Section 1: Basic Prescription and Doctor Info --}}
            <h5 class="section-title mb-3"><i class="fas fa-file-invoice text-primary me-2"></i>معلومات الوصفة الأساسية</h5>
            <div class="info-grid">
                <div class="info-item">
                    <p class="info-label">الطبيب المُصدر:</p>
                    <span class="info-value"><i class="fas fa-user-md"></i>{{ $prescription->doctor ? $prescription->doctor->name : 'غير محدد' }}</span>
                </div>
                {{-- You can add patient name and ID too if desired, though it's the patient's page --}}
                {{-- <div class="info-item">
                    <p class="info-label">اسم المريض:</p>
                    <span class="info-value"><i class="fas fa-user"></i>{{ $prescription->patient->name ?? 'N/A' }}</span>
                </div> --}}
                 <div class="info-item">
                    <p class="info-label">وصفة مزمنة:</p>
                    <span class="info-value">
                        @if($prescription->is_chronic_prescription)
                            <i class="fas fa-check-circle text-success"></i> نعم
                        @else
                            <i class="fas fa-times-circle text-danger"></i> لا
                        @endif
                    </span>
                </div>
                @if ($prescription->admission)
                    <div class="info-item">
                        <p class="info-label">مرتبطة بتنويم رقم:</p>
                        <span class="info-value"><i class="fas fa-hospital-user"></i>{{ $prescription->admission->admission_uid ?? 'N/A' }}</span>
                    </div>
                @endif
                 @if ($prescription->next_refill_due_date)
                    <div class="info-item">
                        <p class="info-label">تاريخ إعادة الصرف القادم:</p>
                        <span class="info-value"><i class="fas fa-calendar-check"></i>{{ $prescription->next_refill_due_date->translatedFormat('d F Y') }}</span>
                    </div>
               @endif
            </div>

            {{-- Dispensing Information (if applicable) --}}
            @if ($prescription->dispensed_at && $prescription->dispensedByPharmacyEmployee)
                <hr class="my-4">
                <h5 class="section-title mb-3"><i class="fas fa-user-nurse text-success me-2"></i>معلومات الصرف</h5>
                <div class="info-grid">
                    <div class="info-item">
                        <p class="info-label">تم الصرف بواسطة:</p>
                        <span class="info-value"><i class="fas fa-user-tag"></i>{{ $prescription->dispensedByPharmacyEmployee->name }}</span>
                    </div>
                    <div class="info-item">
                        <p class="info-label">تاريخ ووقت الصرف:</p>
                        <span class="info-value"><i class="fas fa-clock"></i>{{ $prescription->dispensed_at->translatedFormat('d M Y, h:i A') }}</span>
                    </div>
                </div>
            @endif

            {{-- Notes --}}
            @if ($prescription->doctor_notes || $prescription->pharmacy_notes)
                <hr class="my-4">
            @endif
            @if ($prescription->doctor_notes)
            <div class="mb-3 notes-section" data-aos="fade-left" data-aos-delay="100">
                <h6 class="card-subtitle mb-2 text-muted fw-500"><i class="fas fa-user-doctor me-2 text-primary opacity-75"></i>ملاحظات الطبيب:</h6>
                <p>{{ $prescription->doctor_notes }}</p>
            </div>
            @endif

            @if ($prescription->pharmacy_notes)
             <div class="mb-3 notes-section" data-aos="fade-left" data-aos-delay="150">
                <h6 class="card-subtitle mb-2 text-muted fw-500"><i class="fas fa-clinic-medical me-2 text-success opacity-75"></i>ملاحظات الصيدلية:</h6>
                <p style="background-color: #f2f9f3; border-color: #d4ebd6;">{{ $prescription->pharmacy_notes }}</p>
            </div>
            @endif

            {{-- Section 2: Medication Items --}}
            <hr class="my-4">
            <h5 class="section-title mb-3"><i class="fas fa-pills text-primary me-2"></i>الأدوية الموصوفة ({{ $prescription->items->count() }})</h5>
            @if($prescription->items->isNotEmpty())
                <div class="list-group medication-list">
                    @foreach($prescription->items as $index => $item)
                        <div class="list-group-item flex-column align-items-start" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                            <div class="d-flex w-100 justify-content-between mb-2">
                                <h5 class="mb-1 med-name">
                                     <span class="badge bg-primary-transparent me-2">{{ $loop->iteration }}</span>
                                     {{ $item->medication->name ?? 'دواء غير متوفر' }}
                                     @if($item->is_prn)
                                         <span class="badge rounded-pill bg-warning-transparent text-warning ms-2" data-bs-toggle="tooltip" title="يُستخدم هذا الدواء فقط عند اللزوم حسب إرشادات الطبيب">
                                            <i class="fas fa-exclamation-circle"></i> عند اللزوم
                                         </span>
                                     @endif
                                </h5>
                                @if(isset($item->refills_allowed) && $item->refills_allowed > ($item->refills_done ?? 0))
                                    <small class="text-info fw-500" data-bs-toggle="tooltip" title="عدد مرات إعادة الصرف المتبقية لهذا الدواء">
                                        <i class="fas fa-sync-alt"></i> متبقي: {{ $item->refills_allowed - ($item->refills_done ?? 0) }} إعادة صرف
                                    </small>
                                 @endif
                            </div>

                            @if($item->medication)
                                <p class="mb-1 med-details">
                                    {{ $item->medication->generic_name ? $item->medication->generic_name . ' - ' : '' }}
                                    {{ $item->medication->strength ?? '' }}
                                    {{ $item->medication->dosage_form_display ? ' (' . $item->medication->dosage_form_display . ')' : '' }}
                                </p>
                            @endif

                            <div class="med-info-grid">
                                <div><strong>الجرعة:</strong> {{ $item->dosage ?? 'غير محدد' }}</div>
                                <div><strong>التكرار:</strong> {{ $item->frequency ?? 'غير محدد' }}</div>
                                <div><strong>المدة:</strong> {{ $item->duration ?? 'حسب الحاجة/تعليمات الطبيب' }}</div>
                                <div><strong>طريقة الإعطاء:</strong> {{ $item->route_of_administration ?? 'غير محدد' }}</div>
                                @if($item->quantity_prescribed)
                                <div><strong>الكمية الموصوفة:</strong> {{ $item->quantity_prescribed }} {{ $item->medication->unit_of_measure_display ?? '' }}</div>
                                @endif
                            </div>

                            @if($item->instructions_for_patient)
                            <div class="mt-2 med-instructions">
                                <i class="fas fa-info-circle me-1"></i> <strong>تعليمات خاصة:</strong> {{ $item->instructions_for_patient }}
                            </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-light text-center border p-4">
                    <i class="fas fa-capsules fa-2x text-muted mb-2"></i>
                    <p class="mb-0">لا توجد أدوية مدرجة في هذه الوصفة حالياً.</p>
                </div>
            @endif

             {{-- Action Buttons Area --}}
            @if ($prescription->can_request_refill)
                <div class="action-button-container mt-4">
                    <form action="{{ route('patient.pharmacy.request-refill', $prescription->id) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('هل أنت متأكد من رغبتك في طلب إعادة صرف لهذه الوصفة؟ سيتم إخطار الصيدلية.\n\nملاحظة: هذا الإجراء خاص بطلب إعادة صرف الأدوية المحددة في هذه الوصفة والتي تسمح بذلك.')">
                        @csrf
                        <button type="submit" class="btn btn-success btn-lg ripple shadow-sm">
                            <i class="fas fa-prescription-bottle me-2"></i> طلب إعادة صرف الوصفة
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('js')
    @parent
    {{-- AlpineJS (لا حاجة له إذا لم نستخدم توسيع/طي تفاعلي هنا) --}}
    {{-- <script defer src="https://unpkg.com/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="{{ URL::asset('dashboard/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('dashboard/plugins/notify/js/notifit-custom.js') }}"></script>

    <script>
        // document.addEventListener('alpine:init', () => {
        // });

        $(document).ready(function() {
            AOS.init({
                duration: 700,
                once: true,
                offset: 50
            });

            // تفعيل الـ Tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl, {
                    boundary: document.body,
                    fallbackPlacements: ['top', 'bottom']
                })
            });

            @if (session('success'))
                notif({
                    msg: "<div class='d-flex align-items-center p-2'><i class='fas fa-check-circle fa-lg me-2 text-success'></i><div style='font-size: 0.95rem;'>{{ session('success') }}</div></div>",
                    type: "success", position: "top-center", autohide: true, timeout: 5000
                });
            @endif
            @if (session('error'))
                notif({
                    msg: "<div class='d-flex align-items-center p-2'><i class='fas fa-exclamation-triangle fa-lg me-2 text-danger'></i><div style='font-size: 0.95rem;'>{{ session('error') }}</div></div>",
                    type: "error", position: "top-center", autohide: true, timeout: 7000
                });
            @endif
        });
    </script>
@endsection
