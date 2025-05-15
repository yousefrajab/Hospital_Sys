@extends('Dashboard.layouts.master')
@section('title', 'إدارة الأسرة')

@section('css')
    @parent {{-- استيراد CSS الأساسي إذا كان لديك واحدًا في الـ layout --}}
    {{-- NotifIt للإشعارات --}}
    <link href="{{ URL::asset('Dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    {{-- Select2 للـ dropdowns --}}
    <link href="{{ URL::asset('Dashboard/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
    {{-- ثيم Bootstrap 5 لـ Select2 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- Animate.css --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <style>
        :root {
            --admin-primary: #4f46e5;
            --admin-primary-dark: #4338ca;
            --admin-secondary: #10b981;
            --admin-success: #22c55e;
            --admin-danger: #ef4444;
            --admin-warning: #f59e0b;
            --admin-info: #3b82f6;
            --admin-bg: #f8f9fc;
            --admin-card-bg: #ffffff;
            --admin-text: #111827;
            --admin-text-secondary: #6b7280;
            --admin-border-color: #e5e7eb;
            --admin-radius-sm: 0.25rem;
            --admin-radius-md: 0.375rem;
            --admin-radius-lg: 0.75rem;
            --admin-shadow: 0 1px 3px rgba(0, 0, 0, 0.07);
            --admin-shadow-md: 0 4px 10px -1px rgba(0, 0, 0, 0.07);
            --admin-transition: all 0.3s ease-in-out;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --admin-bg: #1f2937; --admin-card-bg: #374151; --admin-text: #f9fafb;
                --admin-text-secondary: #9ca3af; --admin-border-color: #4b5563;
                --admin-primary: #6366f1; --admin-primary-dark: #4f46e5;
            }
            .table thead th { background-color: #2d3748; border-color: #4b5563; color: var(--admin-text-secondary); }
            .table tbody tr:hover { background-color: rgba(99, 102, 241, 0.08); }
            .card { border-color: var(--admin-border-color); background-color: var(--admin-card-bg); }
            .form-control, .form-select, .select2-container--bootstrap-5 .select2-selection {
                background-color: #2d3748 !important;
                border-color: var(--admin-border-color) !important;
                color: var(--admin-text) !important;
            }
            .select2-container--bootstrap-5 .select2-dropdown {
                background-color: #2d3748;
                border-color: var(--admin-border-color);
            }
            .select2-container--bootstrap-5 .select2-results__option { color: var(--admin-text); }
            .select2-container--bootstrap-5 .select2-results__option--highlighted { background-color: var(--admin-primary) !important; }
            .page-item .page-link {
                background-color: var(--admin-card-bg);
                border-color: var(--admin-border-color);
                color: var(--admin-text-secondary);
            }
            .page-item.active .page-link {
                background-color: var(--admin-primary);
                border-color: var(--admin-primary);
            }
            .page-item.disabled .page-link {
                 background-color: #4b5563;
                 border-color: #6b7280;
                 color: #9ca3af;
            }
        }

        body { background-color: var(--admin-bg); font-family: 'Tajawal', sans-serif; color: var(--admin-text); }
        .card {
            background-color: var(--admin-card-bg);
            border-radius: var(--admin-radius-lg);
            box-shadow: var(--admin-shadow);
            border: 1px solid var(--admin-border-color);
            margin-bottom: 1.5rem;
        }
        .card-header {
            background-color: transparent;
            border-bottom: 1px solid var(--admin-border-color);
            padding: 1rem 1.25rem;
        }
        .card-header .card-title { font-weight: 600; color: var(--admin-text); }
        .table-responsive { overflow-x: auto; }
        .table { width: 100%; margin-bottom: 0; color: var(--admin-text); }
        .table thead th {
            background-color: var(--admin-bg); /* لون أفتح قليلاً لخلفية الهيدر */
            color: var(--admin-text-secondary);
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid var(--admin-border-color);
            white-space: nowrap;
            padding: 0.75rem 1rem;
            text-align: right;
        }
        .table td, .table th { vertical-align: middle; padding: 0.75rem 1rem; border-top: 1px solid var(--admin-border-color); text-align: right; }
        .table tbody tr:hover { background-color: rgba(79, 70, 229, 0.04); }

        .badge { font-size: 0.75rem; padding: 0.35em 0.65em; border-radius: 50px; font-weight: 500; }
        .bg-success-soft { background-color: rgba(34,197,94,0.15); color: #166534; }
        .bg-danger-soft { background-color: rgba(239,68,68,0.15); color: #991b1b; }

        .action-buttons .btn { margin: 0 2px; padding: 0.3rem 0.6rem; font-size:0.8rem; }
        .action-buttons .btn i { font-size: 0.9rem; }

        .form-control, .form-select {
            border-radius: var(--admin-radius-md);
            border: 1px solid var(--admin-border-color);
            padding: 0.5rem 0.75rem;
            font-size: 0.9rem;
            transition: var(--admin-transition);
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--admin-primary);
            box-shadow: 0 0 0 0.2rem rgba(79, 70, 229, 0.15);
        }
        .select2-container--bootstrap-5 .select2-selection {
            border-radius: var(--admin-radius-md) !important;
            border: 1px solid var(--admin-border-color) !important;
            padding: 0.32rem 0.75rem !important;
            height: calc(1.5em + 1rem + 2px) !important;
        }
        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
            height: calc(1.5em + 1rem) !important;
        }
        .pagination-container .pagination { margin-bottom: 0; }
        .page-item .page-link {
            border-radius: var(--admin-radius-md) !important; margin: 0 3px;
            border-color: var(--admin-border-color); color: var(--admin-text-secondary);
            background-color: var(--admin-card-bg); transition: var(--admin-transition); font-size: 0.9rem;
        }
        .page-item .page-link:hover { background-color: var(--admin-bg); border-color: #cbd5e1; color: var(--admin-text); }
        .page-item.active .page-link { background-color: var(--admin-primary); border-color: var(--admin-primary); color: white; box-shadow: 0 2px 5px rgba(79, 70, 229, 0.3); }
        .page-item.disabled .page-link { background-color: var(--admin-bg); border-color: var(--admin-border-color); color: #cbd5e1; }

    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <i class="fas fa-bed fa-lg me-2" style="color: var(--admin-primary, #4f46e5);"></i>
                <div>
                    <h4 class="content-title mb-0 my-auto">إدارة المرافق</h4>
                    <span class="text-muted mt-0 tx-13">/ قائمة الأسرة</span>
                </div>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <a href="{{ route('admin.beds.create') }}" class="btn btn-primary ripple">
                <i class="fas fa-plus me-1"></i> إضافة سرير جديد
            </a>
        </div>
    </div>
@endsection

@section('content')
    @include('Dashboard.messages_alert')

    {{-- بطاقة الفلترة للأسرة --}}
    <div class="card mb-4 animate__animated animate__fadeIn">
        <div class="card-header pb-0">
            <h5 class="card-title mb-0"><i class="fas fa-filter me-2"></i>فلترة البحث عن الأسرة</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.beds.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-2"> {{-- تصغير حقل البحث --}}
                        <label for="search_bed" class="form-label">رقم السرير:</label>
                        <input type="text" name="search_bed" id="search_bed" class="form-control" placeholder="رقم..." value="{{ $request->search_bed }}">
                    </div>
                    <div class="col-md-3">
                        <label for="section_id_filter" class="form-label">القسم:</label>
                        <select name="section_id_filter" id="section_id_filter" class="form-select select2" data-placeholder="الكل">
                            <option value="">الكل</option>
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}" {{ $request->section_id_filter == $section->id ? 'selected' : '' }}>
                                    {{ $section->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="room_id_filter" class="form-label">الغرفة:</label>
                        <select name="room_id_filter" id="room_id_filter" class="form-select select2" data-placeholder="الكل">
                            <option value="">الكل</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}" {{ $request->room_id_filter == $room->id ? 'selected' : '' }}>
                                    {{ $room->room_number }} @if($room->section) ({{ $room->section->name ?? '' }}) @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="bed_type_filter" class="form-label">نوع السرير:</label>
                        <select name="bed_type_filter" id="bed_type_filter" class="form-select select2" data-placeholder="الكل">
                            <option value="">الكل</option>
                            @foreach($bedTypes as $key => $value)
                                <option value="{{ $key }}" {{ $request->bed_type_filter == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="bed_status_filter" class="form-label">حالة السرير:</label>
                        <select name="bed_status_filter" id="bed_status_filter" class="form-select select2" data-placeholder="الكل">
                            <option value="">الكل</option>
                             @foreach($bedStatuses as $key => $value)
                                <option value="{{ $key }}" {{ $request->bed_status_filter == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search me-1"></i> تطبيق الفلتر</button>
                        @if(request()->hasAny(['search_bed', 'section_id_filter', 'room_id_filter', 'bed_type_filter', 'bed_status_filter']))
                            <a href="{{ route('admin.beds.index') }}" class="btn btn-outline-secondary"><i class="fas fa-eraser me-1"></i> مسح الفلتر</a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>


    <div class="card animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0"><i class="fas fa-procedures me-2"></i>قائمة الأسرة المسجلة ({{ $beds->total() }})</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>رقم السرير</th>
                            <th>الغرفة (القسم)</th>
                            <th>نوع السرير</th>
                            <th>الحالة</th>
                            <th>المريض الحالي</th>
                            <th class="text-center">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($beds as $index => $bed)
                            <tr>
                                <td>{{ $beds->firstItem() + $index }}</td>
                                <td class="fw-bold">{{ $bed->bed_number }}</td>
                                <td>
                                    @if($bed->room)
                                        <a href="{{ route('admin.rooms.show', $bed->room_id) }}">{{ $bed->room->room_number }}</a>
                                        @if($bed->room->section)
                                            <small class="text-muted d-block">({{ $bed->room->section->name ?? 'قسم غير محدد' }})</small>
                                        @endif
                                    @else
                                        <span class="text-muted">غرفة غير محددة</span>
                                    @endif
                                </td>
                                <td>{{ $bedTypes[$bed->type] ?? $bed->type }}</td>
                                <td>
                                    <span class="badge {{ $bed->status === 'available' ? 'bg-success-soft' : 'bg-danger-soft' }}">
                                        <i class="fas {{ $bed->status === 'available' ? 'fa-check-circle' : 'fa-user-times' }} me-1"></i>
                                        {{ $bedStatuses[$bed->status] ?? $bed->status }}
                                    </span>
                                </td>
                                <td>
                                    @if($bed->currentAdmission && $bed->currentAdmission->patient)
                                        {{-- افترض أن لديك route لعرض المريض --}}
                                        <a href="#"> {{-- route('admin.patients.show', $bed->currentAdmission->patient->id) --}}
                                            {{ $bed->currentAdmission->patient->name }}
                                        </a>
                                        <small class="text-muted d-block">ID: {{ $bed->currentAdmission->patient->id }}</small>
                                    @else
                                        <span class="text-muted">- فارغ -</span>
                                    @endif
                                </td>
                                <td class="text-center action-buttons">
                                    <a href="{{ route('admin.beds.show',$bed->id) }}" class="btn btn-sm btn-outline-success" title="عرض التفاصيل"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('admin.beds.edit', $bed->id) }}" class="btn btn-sm btn-outline-primary" title="تعديل السرير"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin.beds.destroy', $bed->id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من رغبتك في حذف هذا السرير؟ هذا الإجراء لا يمكن التراجع عنه.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف السرير"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-bed-pulse fa-2x text-muted mb-2"></i><br>
                                    لا توجد أسرة مسجلة حاليًا.
                                    @if(!request()->hasAny(['search_bed', 'section_id_filter', 'room_id_filter', 'bed_type_filter', 'bed_status_filter']))
                                       <br> <a href="{{ url('/admin/beds/create') }}" class="btn btn-primary btn-sm mt-2">
                                        <i class="fas fa-plus"></i> أضف سريرًا جديدًا
                                    </a>
                                    @else
                                        <br> <span class="text-muted">حاول تعديل معايير الفلترة.</span>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($beds->hasPages())
                <div class="mt-3 d-flex justify-content-center pagination-container">
                    {{ $beds->links('pagination::bootstrap') }}
                </div>
            @endif
        </div>
    </div>
@endsection

@section('js')
    @parent
    <script src="{{ URL::asset('Dashboard/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/select2/js/i18n/ar.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifit-custom.js') }}"></script>

    <script>
        $(document).ready(function() {
            // تهيئة Select2
            $('.select2').select2({
                placeholder: $(this).data('placeholder') || "اختر...",
                width: '100%',
                dir: "rtl",
                theme: "bootstrap-5",
                allowClear: true,
                // (اختياري) لمنع إغلاق القائمة المنسدلة عند الاختيار إذا كانت داخل مودال أو مكان آخر
                // dropdownParent: $(this).closest('.card-body') // أو أي عنصر أب مناسب
            });

            // إظهار رسائل NotifIt للنجاح أو الخطأ
            @if (session('success'))
                notif({ msg: "<i class='fas fa-check-circle me-2'></i> {{ session('success') }}", type: "success", position: "bottom", autohide: true, timeout: 5000 });
            @endif
            @if (session('error'))
                notif({ msg: "<i class='fas fa-exclamation-triangle me-2'></i> {{ session('error') }}", type: "error", position: "bottom", autohide: true, timeout: 7000 });
            @endif
        });
    </script>
@endsection
