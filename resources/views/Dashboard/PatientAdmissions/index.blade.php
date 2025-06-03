@extends('Dashboard.layouts.master')
@section('title', 'إدارة سجلات دخول وخروج المرضى')

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <i class="fas fa-procedures fa-lg me-2" style="color: var(--admin-primary, #4f46e5);"></i>
                <div>
                    <h4 class="content-title mb-0 my-auto">إدارة المرضى</h4>
                    <span class="text-muted mt-0 tx-13">/ سجلات الدخول والخروج</span>
                </div>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            {{-- زر تسجيل دخول مريض جديد --}}
            <a href="{{ route('admin.patient_admissions.create') }}" class="btn btn-primary ripple">
                <i class="fas fa-user-plus me-1"></i> تسجيل دخول مريض جديد
            </a>
        </div>
    </div>
@endsection

@section('content')
    @include('Dashboard.messages_alert')

    {{-- بطاقة الفلترة --}}
    <div class="card mb-4 animate__animated animate__fadeIn">
        <div class="card-header pb-0">
            <h5 class="card-title mb-0"><i class="fas fa-filter me-2"></i>فلترة السجلات</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.patient_admissions.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="search_patient" class="form-label">بحث (اسم/هوية/إيميل المريض):</label>
                        <input type="text" name="search_patient" id="search_patient" class="form-control"
                            placeholder="ابحث..." value="{{ $request->search_patient }}">
                    </div>
                    <div class="col-md-3">
                        <label for="current_status_filter" class="form-label">حالة الإقامة:</label>
                        <select name="current_status" id="current_status_filter" class="form-select select2"
                            data-placeholder="الكل">
                            <option value="">الكل</option>
                            @foreach ($admissionStatuses as $key => $value)
                                <option value="{{ $key }}"
                                    {{ $request->current_status == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="section_id_filter" class="form-label">القسم:</label>
                        <select name="section_id_filter" id="section_id_filter" class="form-select select2"
                            data-placeholder="الكل">
                            <option value="">الكل</option>
                            @foreach ($sections as $section)
                                <option value="{{ $section->id }}"
                                    {{ $request->section_id_filter == $section->id ? 'selected' : '' }}>{{ $section->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="doctor_id_filter" class="form-label">الطبيب المسؤول:</label>
                        <select name="doctor_id_filter" id="doctor_id_filter" class="form-select select2"
                            data-placeholder="الكل">
                            <option value="">الكل</option>
                            @foreach ($doctors as $doctor)
                                <option value="{{ $doctor->id }}"
                                    {{ $request->doctor_id_filter == $doctor->id ? 'selected' : '' }}>{{ $doctor->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mt-3">
                        <label for="admission_date_from" class="form-label">تاريخ الدخول (من):</label>
                        <input type="date" name="admission_date_from" id="admission_date_from"
                            class="form-control flatpickr-date" value="{{ $request->admission_date_from }}">
                    </div>
                    <div class="col-md-3 mt-3">
                        <label for="admission_date_to" class="form-label">تاريخ الدخول (إلى):</label>
                        <input type="date" name="admission_date_to" id="admission_date_to"
                            class="form-control flatpickr-date" value="{{ $request->admission_date_to }}">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12 text-center">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search me-1"></i> تطبيق
                            الفلتر</button>
                        @if (request()->hasAny([
                                'search_patient',
                                'current_status',
                                'section_id_filter',
                                'doctor_id_filter',
                                'admission_date_from',
                                'admission_date_to',
                            ]))
                            <a href="{{ route('admin.patient_admissions.index') }}" class="btn btn-outline-secondary"><i
                                    class="fas fa-eraser me-1"></i> مسح الفلتر</a>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- بطاقة جدول سجلات الدخول --}}
    <div class="card animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
        <div class="card-header pb-0">
            <h5 class="card-title mb-0"><i class="fas fa-clipboard-list me-2"></i>سجلات دخول وخروج المرضى
                ({{ $admissions->total() }})</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>المريض</th>
                            <th>القسم</th>
                            <th>الغرفة - السرير</th>
                            <th>الطبيب المسؤول</th>
                            <th>تاريخ الدخول</th>
                            <th>تاريخ الخروج</th>
                            <th>الحالة</th>
                            <th class="text-center">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($admissions as $index => $admission)
                            <tr>
                                <td>{{ $admissions->firstItem() + $index }}</td>
                                <td>
                                    @if ($admission->patient)
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $admission->patient->image ? asset('Dashboard/img/patients/' . $admission->patient->image->filename) : asset('Dashboard/img/doctor_default.png') }}"
                                                alt="{{ $admission->patient->name }}" class="patient-avatar-sm">
                                            <div>
                                                {{-- ***** تعديل هنا ***** --}}
                                                <a
                                                    href="{{ route('admin.Patients.show', ['Patient' => $admission->patient->id]) }}">

                                                    {{ $admission->patient->name }}
                                                </a>
                                                <small class="text-muted d-block">هوية:
                                                    {{ $admission->patient->national_id }}</small>
                                            </div>

                                        </div>
                                    @else
                                        <span class="text-muted">غير محدد</span>
                                    @endif
                                </td>
                                <td>{{ $admission->section->name ?? ($admission->bed->room->section->name ?? 'N/A') }}</td>
                                <td>
                                    @if ($admission->bed)
                                        {{ $admission->bed->room->room_number ?? 'N/A' }} -
                                        {{ $admission->bed->bed_number ?? 'N/A' }}
                                    @else
                                        <span class="text-muted">لم يخصص سرير</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($admission->doctor)
                                        <a href="#">{{-- رابط لملف الطبيب --}}
                                            {{ $admission->doctor->name }}
                                        </a>
                                    @else
                                        <span class="text-muted">غير محدد</span>
                                    @endif
                                </td>
                                <td>{{ $admission->admission_date->translatedFormat('Y/m/d H:i A') }}</td>
                                <td>{{ $admission->discharge_date ? $admission->discharge_date->translatedFormat('Y/m/d H:i A') : '-' }}
                                </td>
                                <td>
                                    @php
                                        $statusText = $admissionStatuses[$admission->status] ?? $admission->status;
                                        $statusBadgeClass = 'bg-info-soft'; // افتراضي
                                        if ($admission->status == \App\Models\PatientAdmission::STATUS_ADMITTED) {
                                            $statusBadgeClass = 'status-admitted';
                                        } elseif (
                                            $admission->status == \App\Models\PatientAdmission::STATUS_DISCHARGED
                                        ) {
                                            $statusBadgeClass = 'status-discharged';
                                        } elseif (
                                            $admission->status == \App\Models\PatientAdmission::STATUS_CANCELLED
                                        ) {
                                            $statusBadgeClass = 'status-cancelled';
                                        }
                                    @endphp
                                    <span class="badge {{ $statusBadgeClass }}">{{ $statusText }}</span>
                                </td>
                                <td class="text-center action-buttons">
                                    <a href="{{ route('admin.patient_admissions.show', $admission->id) }}"
                                        class="btn btn-sm btn-outline-success" title="عرض التفاصيل"><i
                                            class="fas fa-eye"></i></a>
                                    @if ($admission->status == \App\Models\PatientAdmission::STATUS_ADMITTED && !$admission->discharge_date)
                                        {{-- زر تعديل بيانات الدخول (مثل تغيير السرير، الطبيب) --}}
                                        <a href="{{ route('admin.patient_admissions.edit', $admission->id) }}"
                                            class="btn btn-sm btn-outline-primary" title="تعديل بيانات الدخول"><i
                                                class="fas fa-edit"></i></a>

                                        {{-- زر لتسجيل الخروج (يفتح مودال أو صفحة) --}}
                                        {{-- <button type="button" class="btn btn-sm btn-outline-warning" title="تسجيل خروج المريض"> <i class="fas fa-procedures"></i></button> --}}
                                    @endif

                                    <a href="{{ route('admin.patient_admissions.vital_signs_sheet', $admission->id) }}" class="btn btn-sm btn-outline-success" title="مراقبة العلامات الحيوية"><i class="fas fa-heartbeat"></i></a>
                                    {{-- زر حذف سجل الدخول (بحذر شديد!) --}}
                                    <form action="{{ route('admin.patient_admissions.destroy', $admission->id) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا السجل؟');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="حذف السجل"><i class="fas fa-trash-alt"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <i class="fas fa-folder-open fa-2x text-muted mb-2"></i><br>
                                    لا توجد سجلات دخول وخروج حاليًا.
                                    @if (
                                        !request()->hasAny([
                                            'search_patient',
                                            'current_status',
                                            'section_id_filter',
                                            'doctor_id_filter',
                                            'admission_date_from',
                                            'admission_date_to',
                                        ]))
                                        <br> <a href="{{ route('admin.patient_admissions.create') }}"
                                            class="btn btn-primary btn-sm mt-2"><i class="fas fa-user-plus"></i> تسجيل
                                            دخول مريض جديد</a>
                                    @else
                                        <br> <span class="text-muted">حاول تعديل معايير الفلترة.</span>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($admissions->hasPages())
                <div class="mt-3 d-flex justify-content-center pagination-container">
                    {{ $admissions->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
@endsection

@section('css')
    @parent
    <link href="{{ URL::asset('Dashboard/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    {{-- Flatpickr إذا كنت ستستخدمه للتواريخ في الفلتر --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        :root {
            --admin-primary: #4f46e5;
            /* بنفسجي جذاب (Indigo) */
            --admin-primary-dark: #4338ca;
            /* أغمق قليلاً للـ hover */
            --admin-primary-light: #c7d2fe;
            /* أفتح للتلميحات أو الخلفيات الناعمة */

            --admin-secondary: #10b981;
            /* أخضر زمردي (Emerald) */
            --admin-secondary-dark: #059669;
            --admin-secondary-light: #a7f3d0;

            --admin-accent: #f59e0b;
            /* برتقالي/أصفر (Amber) - للتحذيرات أو التمييز */
            --admin-accent-dark: #d97706;

            --admin-success: #22c55e;
            /* أخضر للنجاح (Green) */
            --admin-danger: #ef4444;
            /* أحمر للخطر (Red) */
            --admin-warning: #facc15;
            /* أصفر للتحذيرات (Yellow) */
            --admin-info: #3b82f6;
            /* أزرق للمعلومات (Blue) */

            --admin-bg: #f8f9fc;
            /* خلفية الصفحة الرئيسية (فاتح جداً) */
            --admin-card-bg: #ffffff;
            /* خلفية البطاقات، الجداول، المودالات */
            --admin-sidebar-bg: #2d3748;
            /* مثال لخلفية الشريط الجانبي (داكن) */

            --admin-text: #111827;
            /* لون النص الأساسي (أسود ناعم) */
            --admin-text-light: #f9fafb;
            /* لون النص على خلفيات داكنة */
            --admin-text-secondary: #6b7280;
            /* لون النص الثانوي (رمادي) */
            --admin-text-muted: #9ca3af;
            /* نص باهت أو تلميحات */

            --admin-border-color: #e5e7eb;
            /* لون الحدود العام */
            --admin-input-border: #d1d5db;
            /* لون حدود حقول الإدخال */

            --admin-radius-sm: 0.25rem;
            /* 4px */
            --admin-radius-md: 0.375rem;
            /* 6px */
            --admin-radius-lg: 0.5rem;
            /* 8px - تم توحيده قليلاً */
            --admin-radius-xl: 0.75rem;
            /* 12px - للبطاقات الكبيرة أو المودالات */
            --admin-radius-full: 9999px;
            /* للحواف الدائرية بالكامل (الأزرار البيضاوية) */

            --admin-shadow-xs: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --admin-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
            --admin-shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
            --admin-shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
            --admin-shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);

            --admin-transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);

            /* متغيرات الألوان بصيغة RGB (مفيدة لـ rgba) */
            --admin-primary-rgb: 79, 70, 229;
            --admin-success-rgb: 34, 197, 94;
            --admin-danger-rgb: 239, 68, 68;
            --admin-secondary-rgb: 16, 185, 129;
        }

        /* === Dark Mode Styles === */
        @media (prefers-color-scheme: dark) {
            :root {
                --admin-bg: #111827;
                /* خلفية داكنة جداً */
                --admin-card-bg: #1f2937;
                /* خلفية بطاقة أفتح قليلاً */
                --admin-text: #f3f4f6;
                /* نص فاتح */
                --admin-text-secondary: #9ca3af;
                /* نص ثانوي أفتح */
                --admin-text-muted: #6b7280;
                --admin-border-color: #374151;
                /* حدود أغمق */
                --admin-input-border: #4b5563;

                --admin-primary: #6366f1;
                /* لون أساسي أفتح قليلاً */
                --admin-primary-dark: #4f46e5;
                --admin-primary-light: #a5b4fc;

                --admin-secondary: #34d399;
                /* لون ثانوي أفتح قليلاً */
                --admin-secondary-dark: #10b981;

                --admin-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.3), 0 1px 2px -1px rgba(0, 0, 0, 0.3);
                --admin-shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -2px rgba(0, 0, 0, 0.3);
            }
        }

        /* يمكنك أيضًا إضافة كلاس .dark على <body> لتفعيل الوضع الداكن يدويًا */
        .dark body,
        body.dark {
            /* Apply dark theme variables if body has .dark class */
            --admin-bg: #111827;
            --admin-card-bg: #1f2937;
            --admin-text: #f3f4f6;
            --admin-text-secondary: #9ca3af;
            --admin-text-muted: #6b7280;
            --admin-border-color: #374151;
            --admin-input-border: #4b5563;
            --admin-primary: #6366f1;
            --admin-primary-dark: #4f46e5;
            --admin-primary-light: #a5b4fc;
            --admin-secondary: #34d399;
            --admin-secondary-dark: #10b981;
            --admin-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.3), 0 1px 2px -1px rgba(0, 0, 0, 0.3);
            --admin-shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -2px rgba(0, 0, 0, 0.3);
        }

        .dark .table thead th,
        body.dark .table thead th {
            background-color: #2d3748;
            border-color: var(--admin-border-color);
            color: var(--admin-text-secondary);
        }

        .dark .table tbody tr:hover,
        body.dark .table tbody tr:hover {
            background-color: rgba(99, 102, 241, 0.08);
        }

        .dark .card,
        body.dark .card {
            border-color: var(--admin-border-color);
            background-color: var(--admin-card-bg);
        }

        .dark .form-control,
        .dark .form-select,
        .dark .select2-container--bootstrap-5 .select2-selection,
        body.dark .form-control,
        body.dark .form-select,
        body.dark .select2-container--bootstrap-5 .select2-selection {
            background-color: #2d3748 !important;
            border-color: var(--admin-input-border) !important;
            color: var(--admin-text) !important;
        }

        .dark .select2-container--bootstrap-5 .select2-dropdown,
        body.dark .select2-container--bootstrap-5 .select2-dropdown {
            background-color: #2d3748;
            border-color: var(--admin-input-border);
        }

        .dark .select2-container--bootstrap-5 .select2-results__option,
        body.dark .select2-container--bootstrap-5 .select2-results__option {
            color: var(--admin-text);
        }

        .dark .select2-container--bootstrap-5 .select2-results__option--highlighted,
        body.dark .select2-container--bootstrap-5 .select2-results__option--highlighted {
            background-color: var(--admin-primary) !important;
        }

        /* ... (أضف أي تجاوزات أخرى للوضع الداكن هنا) ... */


        /* === Base Styles === */
        body {
            background-color: var(--admin-bg);
            font-family: 'Tajawal', sans-serif;
            /* تأكد من تحميل هذا الخط */
            color: var(--admin-text);
            line-height: 1.6;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* === Card Styles === */
        .card {
            background-color: var(--admin-card-bg);
            border-radius: var(--admin-radius-lg);
            box-shadow: var(--admin-shadow);
            border: 1px solid var(--admin-border-color);
            margin-bottom: 1.5rem;
            transition: var(--admin-transition);
        }

        .card:hover {
            box-shadow: var(--admin-shadow-md);
        }

        .card-header {
            background-color: transparent;
            border-bottom: 1px solid var(--admin-border-color);
            padding: 1rem 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header .card-title {
            font-weight: 600;
            color: var(--admin-text);
            margin-bottom: 0;
            font-size: 1.1rem;
        }

        .card-header .card-title i {
            margin-left: 0.5rem;
            /* RTL: margin-right */
            color: var(--admin-primary);
        }

        .card-body {
            padding: 1.25rem;
        }

        /* === Table Styles === */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            /* لتحسين التمرير على iOS */
        }

        .table {
            width: 100%;
            margin-bottom: 0;
            color: var(--admin-text);
            border-collapse: collapse;
            /* أو separate مع border-spacing */
        }

        .table thead th {
            background-color: var(--admin-bg);
            color: var(--admin-text-secondary);
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid var(--admin-border-color);
            white-space: nowrap;
            padding: 0.85rem 1rem;
            text-align: right;
        }

        .table td,
        .table th {
            vertical-align: middle;
            padding: 0.85rem 1rem;
            border-top: 1px solid var(--admin-border-color);
            text-align: right;
            font-size: 0.9rem;
        }

        .table tbody tr {
            transition: background-color 0.15s ease-in-out;
        }

        .table tbody tr:hover {
            background-color: rgba(var(--admin-primary-rgb), 0.04);
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(var(--admin-primary-rgb), 0.02);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(var(--admin-primary-rgb), 0.06);
        }

        /* === Form Elements === */
        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            color: var(--admin-text-secondary);
        }

        .form-control,
        .form-select {
            display: block;
            width: 100%;
            padding: 0.65rem 1rem;
            /* حجم موحد */
            font-size: 0.9rem;
            font-weight: 400;
            line-height: 1.5;
            color: var(--admin-text);
            background-color: var(--admin-card-bg);
            background-clip: padding-box;
            border: 1px solid var(--admin-input-border);
            appearance: none;
            /* إزالة المظهر الافتراضي للمتصفح */
            border-radius: var(--admin-radius-md);
            box-shadow: var(--admin-shadow-xs);
            transition: border-color var(--admin-transition), box-shadow var(--admin-transition);
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--admin-primary);
            outline: 0;
            box-shadow: var(--admin-shadow-xs), 0 0 0 0.25rem rgba(var(--admin-primary-rgb), 0.25);
        }

        .form-control::placeholder {
            color: var(--admin-text-muted);
            opacity: 1;
        }

        .form-control:disabled,
        .form-control[readonly] {
            background-color: var(--admin-bg);
            opacity: 0.7;
        }

        /* Select2 Styles */
        .select2-container--bootstrap-5 .select2-selection--single {
            border: 1px solid var(--admin-input-border) !important;
            border-radius: var(--admin-radius-md) !important;
            padding: 0.47rem 0.75rem !important;
            /* تعديل ليتناسب مع ارتفاع الحقل */
            height: calc(1.5em + 1.3rem + 2px) !important;
            /* 0.65rem padding + font-size line-height + border */
            background-color: var(--admin-card-bg) !important;
            color: var(--admin-text) !important;
            box-shadow: var(--admin-shadow-xs) !important;
        }

        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
            color: var(--admin-text) !important;
            line-height: calc(1.5em + 0.36rem) !important;
            /* تعديل ليتناسب مع الـ padding */
        }

        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
            height: calc(1.5em + 1.3rem) !important;
        }

        .select2-container--bootstrap-5.select2-container--focus .select2-selection,
        .select2-container--bootstrap-5.select2-container--open .select2-selection {
            border-color: var(--admin-primary) !important;
            box-shadow: var(--admin-shadow-xs), 0 0 0 0.25rem rgba(var(--admin-primary-rgb), 0.25) !important;
        }

        .select2-container--bootstrap-5 .select2-dropdown {
            border-color: var(--admin-input-border);
            border-radius: var(--admin-radius-md);
            box-shadow: var(--admin-shadow-md);
            background-color: var(--admin-card-bg);
        }

        .select2-container--bootstrap-5 .select2-search--dropdown .select2-search__field {
            border-color: var(--admin-input-border);
            background-color: var(--admin-card-bg);
            color: var(--admin-text);
        }

        .select2-container--bootstrap-5 .select2-results__option {
            color: var(--admin-text);
        }

        .select2-container--bootstrap-5 .select2-results__option--highlighted {
            background-color: var(--admin-primary) !important;
            color: white !important;
        }

        .select2-container--bootstrap-5 .select2-results__option[aria-selected=true] {
            background-color: var(--admin-primary-light) !important;
            color: var(--admin-primary-dark) !important;
        }


        /* === Badges === */
        .badge {
            display: inline-block;
            padding: 0.4em 0.75em;
            /* حجم موحد للبادجات */
            font-size: 0.75rem;
            font-weight: 600;
            /* أثقل قليلاً */
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: var(--admin-radius-full);
            /* لجعلها دائرية أكثر */
            transition: var(--admin-transition);
        }

        .badge i {
            margin-left: 0.3em;
            /* RTL: margin-right */
        }

        /* Status Badges (Generic) */
        .status-badge-active,
        .bg-success-soft {
            background-color: rgba(var(--admin-success-rgb), 0.1);
            color: var(--admin-success) !important;
            /* استخدام !important بحذر */
            border: 1px solid rgba(var(--admin-success-rgb), 0.2);
        }

        .status-badge-inactive,
        .bg-danger-soft {
            background-color: rgba(var(--admin-danger-rgb), 0.1);
            color: var(--admin-danger) !important;
            border: 1px solid rgba(var(--admin-danger-rgb), 0.2);
        }

        .status-admitted {
            /* لسجلات الدخول */
            background-color: rgba(var(--admin-success-rgb), 0.15);
            color: #166534;
            /* لون أغمق قليلاً */
        }

        .status-discharged {
            background-color: rgba(var(--admin-text-secondary-rgb, 107, 114, 128), 0.15);
            /* استخدام متغير RGB إذا كان موجودًا */
            color: var(--admin-text-secondary);
        }

        .status-cancelled {
            background-color: rgba(var(--admin-danger-rgb), 0.15);
            color: #721c24;
        }

        /* === Buttons === */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 500;
            line-height: 1.5;
            text-align: center;
            text-decoration: none;
            vertical-align: middle;
            cursor: pointer;
            user-select: none;
            background-color: transparent;
            border: 1px solid transparent;
            padding: 0.5rem 1rem;
            /* حجم موحد للأزرار العادية */
            font-size: 0.9rem;
            border-radius: var(--admin-radius-md);
            transition: var(--admin-transition);
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: var(--admin-shadow-xs);
        }

        .btn:active {
            transform: translateY(0px);
            box-shadow: none;
        }

        .btn i {
            margin-left: 0.5rem;
            /* RTL: margin-right */
        }

        .btn-primary {
            color: #fff;
            background-color: var(--admin-primary);
            border-color: var(--admin-primary);
        }

        .btn-primary:hover {
            background-color: var(--admin-primary-dark);
            border-color: var(--admin-primary-dark);
        }

        .btn-outline-primary {
            color: var(--admin-primary);
            border-color: var(--admin-primary);
        }

        .btn-outline-primary:hover {
            color: #fff;
            background-color: var(--admin-primary);
            border-color: var(--admin-primary);
        }

        /* ... (أضف أنماط الأزرار الأخرى: secondary, success, danger, warning, info, light, dark) ... */
        .btn-sm {
            padding: 0.3rem 0.6rem;
            font-size: 0.8rem;
            border-radius: var(--admin-radius-sm);
        }

        .btn-lg {
            padding: 0.75rem 1.5rem;
            font-size: 1.1rem;
            border-radius: var(--admin-radius-lg);
        }

        /* Action Buttons in Tables */
        .action-buttons .btn {
            margin: 0 2px;
            padding: 0.3rem 0.6rem;
            /* حجم أصغر لأزرار الإجراءات */
            font-size: 0.8rem;
            line-height: 1.2;
            /* لضمان ظهور الأيقونة بشكل جيد */
        }

        .action-buttons .btn i {
            font-size: 0.9rem;
            margin-left: 0;
            /* لا حاجة لمسافة إذا كانت أيقونة فقط */
        }

        .btn-outline-success i {
            color: var(--admin-success);
        }

        .btn-outline-primary i {
            color: var(--admin-primary);
        }

        .btn-outline-secondary i {
            color: var(--admin-text-secondary);
        }

        .btn-outline-danger i {
            color: var(--admin-danger);
        }


        /* === Pagination === */
        .pagination-container {
            padding: 1rem 0;
            border-top: 1px solid var(--admin-border-color);
        }

        .pagination {
            margin-bottom: 0;
            justify-content: center;
        }

        .page-item .page-link {
            position: relative;
            display: block;
            padding: 0.5rem 0.75rem;
            margin-left: -1px;
            /* RTL: margin-right */
            line-height: 1.25;
            color: var(--admin-primary);
            background-color: var(--admin-card-bg);
            border: 1px solid var(--admin-border-color);
            transition: var(--admin-transition);
            border-radius: var(--admin-radius-md);
            margin: 0 2px;
            /* إضافة radius ومسافة */
        }

        .page-item .page-link:hover {
            z-index: 2;
            color: var(--admin-primary-dark);
            background-color: var(--admin-bg);
            border-color: var(--admin-border-color);
        }

        .page-item.active .page-link {
            z-index: 3;
            color: #fff;
            background-color: var(--admin-primary);
            border-color: var(--admin-primary);
            box-shadow: 0 2px 5px rgba(var(--admin-primary-rgb), 0.3);
        }

        .page-item.disabled .page-link {
            color: var(--admin-text-muted);
            pointer-events: none;
            background-color: var(--admin-card-bg);
            border-color: var(--admin-border-color);
        }


        /* === Avatar Styles === */
        .patient-avatar-sm {
            /* مثال لصورة مستخدم صغيرة في الجدول */
            width: 35px;
            height: 35px;
            border-radius: 50%;
            object-fit: cover;
            margin-left: 8px;
            /* RTL: margin-right */
            border: 2px solid var(--admin-border-color);
        }

        /* === Animation Utilities === */
        .animate__animated {
            animation-duration: 0.5s;
            /* تقليل مدة الأنيميشن قليلاً */
        }

        /* === Breadcrumb (إذا كنت تستخدم breadcrumb Bootstrap) === */
        .breadcrumb-header {
            margin-bottom: 1.5rem;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--admin-border-color);
        }

        .breadcrumb-header .content-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--admin-text);
        }

        .breadcrumb-header .text-muted {
            color: var(--admin-text-secondary) !important;
            /* لتجاوز ألوان القالب */
        }

        .breadcrumb-header .right-content .btn {
            box-shadow: var(--admin-shadow-xs);
        }

        .patient-avatar-sm {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            object-fit: cover;
            margin-left: 8px;
        }

        .status-admitted {
            background-color: rgba(var(--admin-success-rgb, 34, 197, 94), 0.15);
            color: #166534;
        }

        .status-discharged {
            background-color: rgba(var(--admin-secondary-rgb, 108, 117, 125), 0.15);
            color: #383d41;
        }

        .status-cancelled {
            background-color: rgba(var(--admin-danger-rgb, 239, 68, 68), 0.15);
            color: #721c24;
        }
    </style>
@endsection

@section('js')
    @parent
    <script src="{{ URL::asset('Dashboard/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/select2/js/i18n/ar.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifit-custom.js') }}"></script>
    {{-- Flatpickr JS --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/ar.js"></script> {{-- لغة عربية لـ Flatpickr --}}

    <script>
        $(document).ready(function() {
            // تهيئة Select2
            $('.select2').select2({
                placeholder: $(this).data('placeholder') || "اختر...",
                width: '100%',
                dir: "rtl",
                theme: "bootstrap-5",
                allowClear: true
            });

            // تهيئة Flatpickr لحقول التاريخ
            $(".flatpickr-date").flatpickr({
                dateFormat: "Y-m-d",
                locale: "ar", // تفعيل اللغة العربية
                allowInput: true, // السماح بالإدخال اليدوي
                // يمكنك إضافة خيارات أخرى مثل minDate, maxDate
            });


            // إظهار رسائل NotifIt
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
