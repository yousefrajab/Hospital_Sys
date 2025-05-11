@extends('Dashboard.layouts.master')
@section('title', 'تفاصيل الغرفة: ' . $room->room_number)

@section('css')
    @parent
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <style>
        :root {
            --admin-primary: #4f46e5; --admin-primary-dark: #4338ca; --admin-secondary: #10b981;
            --admin-success: #22c55e; --admin-danger: #ef4444; --admin-warning: #f59e0b;
            --admin-info: #3b82f6; --admin-bg: #f8f9fc; --admin-card-bg: #ffffff;
            --admin-text: #111827; --admin-text-secondary: #6b7280; --admin-border-color: #e5e7eb;
            --admin-radius-lg: 0.75rem; --admin-radius-xl: 1rem; --admin-shadow-lg: 0 10px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.1);
            --admin-transition: all 0.3s ease-in-out;
        }
        @media (prefers-color-scheme: dark) {
            :root {
                --admin-bg: #1f2937; --admin-card-bg: #374151; --admin-text: #f9fafb;
                --admin-text-secondary: #9ca3af; --admin-border-color: #4b5563;
                --admin-primary: #6366f1; --admin-primary-dark: #4f46e5;
            }
            .info-item strong { color: var(--admin-text) !important; }
            .bed-card { background-color: #2d3748; border-color: var(--admin-border-color); }
            .bed-card.available { border-left-color: var(--admin-success) !important; }
            .bed-card.occupied { border-left-color: var(--admin-danger) !important; }
        }
        body { background-color: var(--admin-bg); font-family: 'Tajawal', sans-serif; color: var(--admin-text); }

        .room-details-container { padding-top: 1.5rem; padding-bottom: 3rem; }
        .details-card {
            background-color: var(--admin-card-bg);
            border-radius: var(--admin-radius-xl);
            box-shadow: var(--admin-shadow-lg);
            border: 1px solid var(--admin-border-color);
            overflow: hidden; /* لضمان احتواء الهيدر */
        }
        .details-card-header {
            background: linear-gradient(135deg, var(--admin-primary), var(--admin-secondary));
            color: white;
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .details-card-header h3 { margin: 0; font-size: 1.5rem; font-weight: 700; }
        .details-card-header .room-status-badge { font-size: 0.9rem; padding: 0.5em 1em; border-radius: 50px; }

        .details-card-body { padding: 2rem; }
        .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
        .info-item {
            background-color: var(--admin-bg); /* لون خلفية أفتح قليلاً من البطاقة */
            padding: 1rem;
            border-radius: var(--admin-radius-md);
            border-left: 4px solid var(--admin-primary);
        }
        @media (prefers-color-scheme: dark) { .info-item { background-color: #2d3748; border-left-color: var(--admin-primary); } }

        .info-item label { display: block; font-size: 0.85rem; color: var(--admin-text-secondary); margin-bottom: 0.3rem; font-weight: 500;}
        .info-item strong { font-size: 1.1rem; color: var(--admin-text); font-weight: 600; word-break: break-word; }
        .info-item i { margin-left: 0.5rem; color: var(--admin-primary); width: 1.2em; /* لمحاذاة الأيقونات */ }

        .section-divider {
            margin-top: 2.5rem; margin-bottom: 2rem; border: 0;
            border-top: 1px solid var(--admin-border-color);
        }
        .section-subtitle { font-size: 1.25rem; font-weight: 600; color: var(--admin-primary); margin-bottom: 1.5rem; display: flex; align-items: center;}
        .section-subtitle i { margin-left: 0.75rem; font-size: 1.1em; }

        /* تصميم بطاقات الأسرة */
        .beds-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 1.5rem; }
        .bed-card {
            background-color: var(--admin-card-bg);
            border: 1px solid var(--admin-border-color);
            border-radius: var(--admin-radius-md);
            padding: 1rem;
            text-align: center;
            transition: var(--admin-transition);
            position: relative;
            overflow: hidden;
            border-left-width: 5px; /* لإظهار حالة السرير */
        }
        .bed-card:hover { transform: translateY(-5px); box-shadow: var(--admin-shadow); }

        .bed-card.available { border-left-color: var(--admin-success); }
        .bed-card.occupied { border-left-color: var(--admin-danger); }
        /* أضف حالات أخرى للأسرة هنا إذا أردت */

        .bed-card .bed-icon { font-size: 2.5rem; margin-bottom: 0.75rem; display: block; }
        .bed-card.available .bed-icon { color: var(--admin-success); }
        .bed-card.occupied .bed-icon { color: var(--admin-danger); }

        .bed-card h5 { font-size: 1.1rem; font-weight: 600; margin-bottom: 0.25rem; color: var(--admin-text); }
        .bed-card .bed-type { font-size: 0.8rem; color: var(--admin-text-secondary); margin-bottom: 0.75rem; }
        .bed-card .patient-name { font-size: 0.9rem; font-weight: 500; }
        .bed-card .patient-name i { margin-left: 0.3rem; }
        .bed-card .no-patient { font-style: italic; color: var(--admin-text-secondary); }

        .no-beds-message { text-align: center; padding: 2rem; color: var(--admin-text-secondary); }
        .no-beds-message i { font-size: 3rem; display: block; margin-bottom: 1rem; opacity: 0.5; }

        /* لتحسين عرض حالة الغرفة */
        .status-badge-show { font-size: 0.9rem !important; padding: 0.5em 1em !important; border-radius: 50px !important; }
        .bg-success-soft { background-color: rgba(34,197,94,0.15) !important; color: #166534 !important; }
        .bg-warning-soft { background-color: rgba(245,158,11,0.15) !important; color: #78350f !important; }
        .bg-danger-soft { background-color: rgba(239,68,68,0.15) !important; color: #991b1b !important; }
        .text-white { color: white !important; }

    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <i class="fas fa-door-open fa-lg me-2" style="color: var(--admin-primary);"></i>
                <div>
                    <h4 class="content-title mb-0 my-auto">إدارة الغرف</h4>
                    <span class="text-muted mt-0 tx-13">/ تفاصيل الغرفة: {{ $room->room_number }}</span>
                </div>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <a href="{{ route('admin.rooms.edit', $room->id) }}" class="btn btn-outline-primary btn-sm me-2" style="border-radius: var(--admin-radius-md);">
                <i class="fas fa-edit me-1"></i> تعديل الغرفة
            </a>
            <a href="{{ route('admin.rooms.index') }}" class="btn btn-outline-secondary btn-sm" style="border-radius: var(--admin-radius-md);">
                <i class="fas fa-arrow-left me-1"></i> العودة للقائمة
            </a>
        </div>
    </div>
@endsection

@section('content')
    @include('Dashboard.messages_alert')

    <div class="room-details-container">
        <div class="details-card animate__animated animate__fadeInUp">
            <div class="details-card-header">
                <h3><i class="fas fa-info-circle"></i> تفاصيل الغرفة: {{ $room->room_number }}</h3>
                @php
                    $statusClass = match($room->status) {
                        'available' => 'bg-success-soft',
                        'partially_occupied' => 'bg-warning-soft',
                        'fully_occupied' => 'bg-danger-soft',
                        'out_of_service' => 'bg-secondary text-white',
                        default => 'bg-light text-dark'
                    };
                @endphp
                <span class="badge status-badge-show {{ $statusClass }}">{{ $statusDisplay }}</span>
            </div>

            <div class="details-card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <label><i class="fas fa-tag"></i>رقم/اسم الغرفة</label>
                        <strong>{{ $room->room_number }}</strong>
                    </div>
                    <div class="info-item">
                        <label><i class="fas fa-hospital-symbol"></i>القسم</label>
                        <strong>{{ $room->section->name ?? 'غير محدد' }}</strong> {{-- افترض أن name عمود عادي --}}
                    </div>
                    <div class="info-item">
                        <label><i class="fas fa-door-closed"></i>نوع الغرفة</label>
                        <strong>{{ $roomTypeDisplay }}</strong>
                    </div>
                    <div class="info-item">
                        <label><i class="fas fa-venus-mars"></i>تخصيص الجنس</label>
                        <strong>{{ $genderTypeDisplay }}</strong>
                    </div>
                    <div class="info-item">
                        <label><i class="fas fa-layer-group"></i>الطابق</label>
                        <strong>{{ $room->floor ?? '-' }}</strong>
                    </div>
                    <div class="info-item">
                        <label><i class="far fa-calendar-alt"></i>تاريخ الإنشاء</label>
                        <strong>{{ $room->created_at->translatedFormat('d M Y, h:i A') }}</strong>
                    </div>
                </div>

                @if($room->notes)
                <hr class="section-divider">
                <div>
                    <h5 class="section-subtitle"><i class="far fa-sticky-note"></i> ملاحظات إضافية عن الغرفة</h5>
                    <p class="text-muted" style="white-space: pre-wrap;">{{ $room->notes }}</p>
                </div>
                @endif

                {{-- عرض الأسرة الموجودة في الغرفة --}}
                @if(in_array($room->type, ['patient_room', 'private_room', 'semi_private_room', 'icu_room']))
                    <hr class="section-divider">
                    <div>
                        <h5 class="section-subtitle"><i class="fas fa-bed"></i> الأسرة في هذه الغرفة ({{ $room->beds->count() }})</h5>
                        @if($room->beds->count() > 0)
                            <div class="beds-grid">
                                @foreach($room->beds as $bed)
                                    <div class="bed-card {{ $bed->status }} animate__animated animate__zoomIn" style="animation-delay: {{ $loop->index * 0.1 }}s;">
                                        <span class="bed-icon">
                                            @if($bed->status == 'occupied')
                                                <i class="fas fa-procedures"></i> {{-- أيقونة سرير مشغول --}}
                                            @else
                                                <i class="fas fa-bed"></i>
                                            @endif
                                        </span>
                                        <h5>سرير رقم: {{ $bed->bed_number }}</h5>
                                        <p class="bed-type">{{ \App\Models\Bed::getBedTypes()[$bed->type] ?? $bed->type }}</p>
                                        @if($bed->currentAdmission && $bed->currentAdmission->patient)
                                            <p class="patient-name">
                                                <i class="fas fa-user-injured"></i>
                                                المريض: <strong>{{ $bed->currentAdmission->patient->name }}</strong>
                                            </p>
                                        @elseif($bed->status == 'occupied')
                                            <p class="patient-name text-danger">مشغول (بيانات المريض غير متاحة)</p>
                                        @else
                                            <p class="no-patient text-success">متاح</p>
                                        @endif
                                        {{-- يمكنك إضافة زر لعرض تفاصيل السرير أو المريض --}}
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="no-beds-message">
                                <i class="fas fa-bed-pulse"></i> {{-- أيقونة مختلفة لعدم وجود أسرة --}}
                                <p>لا توجد أسرة معرفة لهذه الغرفة حاليًا.</p>
                                <a href="#" class="btn btn-sm btn-outline-primary"> {{-- رابط لإضافة أسرة (لاحقًا) --}}
                                    <i class="fas fa-plus-circle me-1"></i> إضافة أسرة لهذه الغرفة
                                </a>
                            </div>
                        @endif
                    </div>
                @endif

                <div class="mt-4 pt-3 border-top text-center">
                    <a href="{{ route('admin.rooms.edit', $room->id) }}" class="btn btn-primary ripple-effect">
                        <i class="fas fa-edit me-2"></i> تعديل بيانات الغرفة
                    </a>
                    {{-- زر لإدارة أسرة الغرفة --}}
                    @if(in_array($room->type, ['patient_room', 'private_room', 'semi_private_room', 'icu_room']))
                    <a href="{{ route('admin.beds.index', ['room_id_filter' => $room->id]) }}" class="btn btn-secondary ripple-effect ms-2">
                        <i class="fas fa-bed me-2"></i> إدارة أسرة هذه الغرفة
                    </a>
                    @endif
                </div>

            </div>
        </div>
    </div>
@endsection

@section('js')
    @parent
    {{-- يمكنك إضافة أي JS خاص بهذه الصفحة إذا احتجت (مثل تفعيل Tooltips إذا أضفتها) --}}
    <script>
        console.log("Room details page loaded for Room ID: {{ $room->id }}");
        // مثال لتفعيل tooltips إذا استخدمت data-bs-toggle="tooltip"
        // var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        // var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        //   return new bootstrap.Tooltip(tooltipTriggerEl)
        // })
    </script>
@endsection
