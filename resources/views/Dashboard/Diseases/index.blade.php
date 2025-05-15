@extends('Dashboard.layouts.master')
@section('title', 'إدارة الأمراض')

@section('css')
    @parent
    {{-- أضف أي CSS خاص بالجدول أو الفلاتر إذا لزم الأمر --}}
    <link href="{{ URL::asset('Dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
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
                --admin-bg: #1f2937;
                --admin-card-bg: #374151;
                --admin-text: #f9fafb;
                --admin-text-secondary: #9ca3af;
                --admin-border-color: #4b5563;
                --admin-primary: #6366f1;
                --admin-primary-dark: #4f46e5;
            }

            .table thead th {
                background-color: #2d3748;
                border-color: #4b5563;
                color: var(--admin-text-secondary);
            }

            .table tbody tr:hover {
                background-color: rgba(99, 102, 241, 0.08);
            }

            .card {
                border-color: var(--admin-border-color);
                background-color: var(--admin-card-bg);
            }

            .form-control,
            .form-select,
            .select2-container--bootstrap-5 .select2-selection {
                background-color: #2d3748 !important;
                border-color: var(--admin-border-color) !important;
                color: var(--admin-text) !important;
            }

            .select2-container--bootstrap-5 .select2-dropdown {
                background-color: #2d3748;
                border-color: var(--admin-border-color);
            }

            .select2-container--bootstrap-5 .select2-results__option {
                color: var(--admin-text);
            }

            .select2-container--bootstrap-5 .select2-results__option--highlighted {
                background-color: var(--admin-primary) !important;
            }

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

        body {
            background-color: var(--admin-bg);
            font-family: 'Tajawal', sans-serif;
            color: var(--admin-text);
        }

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

        .card-header .card-title {
            font-weight: 600;
            color: var(--admin-text);
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            margin-bottom: 0;
            color: var(--admin-text);
        }

        .table thead th {
            background-color: var(--admin-bg);
            /* لون أفتح قليلاً لخلفية الهيدر */
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

        .table td,
        .table th {
            vertical-align: middle;
            padding: 0.75rem 1rem;
            border-top: 1px solid var(--admin-border-color);
            text-align: right;
        }

        .table tbody tr:hover {
            background-color: rgba(79, 70, 229, 0.04);
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.35em 0.65em;
            border-radius: 50px;
            font-weight: 500;
        }

        .bg-success-soft {
            background-color: rgba(34, 197, 94, 0.15);
            color: #166534;
        }

        .bg-danger-soft {
            background-color: rgba(239, 68, 68, 0.15);
            color: #991b1b;
        }

        .action-buttons .btn {
            margin: 0 2px;
            padding: 0.3rem 0.6rem;
            font-size: 0.8rem;
        }

        .action-buttons .btn i {
            font-size: 0.9rem;
        }

        .form-control,
        .form-select {
            border-radius: var(--admin-radius-md);
            border: 1px solid var(--admin-border-color);
            padding: 0.5rem 0.75rem;
            font-size: 0.9rem;
            transition: var(--admin-transition);
        }

        .form-control:focus,
        .form-select:focus {
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

        .pagination-container .pagination {
            margin-bottom: 0;
        }

        .page-item .page-link {
            border-radius: var(--admin-radius-md) !important;
            margin: 0 3px;
            border-color: var(--admin-border-color);
            color: var(--admin-text-secondary);
            background-color: var(--admin-card-bg);
            transition: var(--admin-transition);
            font-size: 0.9rem;
        }

        .page-item .page-link:hover {
            background-color: var(--admin-bg);
            border-color: #cbd5e1;
            color: var(--admin-text);
        }

        .page-item.active .page-link {
            background-color: var(--admin-primary);
            border-color: var(--admin-primary);
            color: white;
            box-shadow: 0 2px 5px rgba(79, 70, 229, 0.3);
        }

        .page-item.disabled .page-link {
            background-color: var(--admin-bg);
            border-color: var(--admin-border-color);
            color: #cbd5e1;
        }


        .badge-chronic {
            background-color: rgba(var(--admin-warning-rgb), 0.15);
            color: #856404;
        }

        .badge-not-chronic {
            background-color: rgba(var(--admin-info-rgb), 0.15);
            color: var(--admin-info);
        }
    </style>

@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <i class="fas fa-viruses fa-lg me-2" style="color: var(--admin-primary);"></i>
                <div>
                    <h4 class="content-title mb-0 my-auto">قائمة الأمراض</h4>
                    <span class="text-muted mt-0 tx-13">عرض وإدارة الأمراض في النظام</span>
                </div>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <a href="{{ route('admin.diseases.create') }}" class="btn btn-primary ripple">
                <i class="fas fa-plus me-1"></i> إضافة مرض جديد
            </a>
        </div>
    </div>
@endsection

@section('content')
    @include('Dashboard.messages_alert')

    {{-- بطاقة الفلترة --}}
    <div class="card mb-4 animate__animated animate__fadeIn">
        <div class="card-header pb-0">
            <h5 class="card-title mb-0"><i class="fas fa-filter me-2"></i>فلترة البحث</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.diseases.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="search_disease" class="form-label">بحث (اسم/وصف المرض):</label>
                        <input type="text" name="search_disease" id="search_disease" class="form-control"
                            placeholder="ابحث..." value="{{ $request->search_disease }}">
                    </div>
                    <div class="col-md-4">
                        <label for="is_chronic_filter" class="form-label">نوع المرض:</label>
                        <select name="is_chronic_filter" id="is_chronic_filter" class="form-select">
                            <option value="all"
                                {{ $request->is_chronic_filter == 'all' || !$request->filled('is_chronic_filter') ? 'selected' : '' }}>
                                الكل</option>
                            <option value="1" {{ $request->is_chronic_filter === '1' ? 'selected' : '' }}>مرض مزمن
                            </option>
                            <option value="0" {{ $request->is_chronic_filter === '0' ? 'selected' : '' }}>مرض غير مزمن
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> بحث</button>
                        @if (request()->hasAny(['search_disease', 'is_chronic_filter']))
                            <a href="{{ route('admin.diseases.index') }}" class="btn btn-outline-secondary ms-2"
                                title="مسح الفلتر"><i class="fas fa-eraser"></i></a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card animate__animated animate__fadeInUp">
        <div class="card-header">
            <h5 class="card-title mb-0"><i class="fas fa-list-ul me-2"></i>قائمة الأمراض ({{ $diseases->total() }})</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>اسم المرض</th>
                            <th>الوصف</th>
                            <th>النوع</th>
                            <th>تاريخ الإضافة</th>
                            <th class="text-center">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($diseases as $index => $disease)
                            <tr>
                                <td>{{ $diseases->firstItem() + $index }}</td>
                                <td class="fw-bold">{{ $disease->name }}</td>
                                <td>{{ Str::limit($disease->description, 70) }}</td>
                                <td>
                                    <span class="badge {{ $disease->is_chronic ? 'badge-chronic' : 'badge-not-chronic' }}">
                                        {{ $disease->is_chronic ? 'مزمن' : 'غير مزمن' }}
                                    </span>
                                </td>
                                <td>{{ $disease->created_at->translatedFormat('Y/m/d') }}</td>
                                <td class="text-center action-buttons">
                                    <a href="{{ route('admin.diseases.show', $disease->id) }}"
                                        class="btn btn-sm btn-outline-success" title="عرض"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('admin.diseases.edit', $disease->id) }}"
                                        class="btn btn-sm btn-outline-primary" title="تعديل"><i
                                            class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin.diseases.destroy', $disease->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا المرض؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف"><i
                                                class="fas fa-trash-alt"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    لا توجد أمراض مسجلة. <a href="{{ route('admin.diseases.create') }}">أضف مرضًا
                                        جديدًا</a>.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($diseases->hasPages())
                <div class="mt-3 d-flex justify-content-center">
                    {{ $diseases->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
@endsection

@section('js')
    @parent
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
