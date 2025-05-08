{{-- resources/views/Dashboard/Admin/users_roles/index.blade.php --}}
@extends('Dashboard.layouts.master')

@section('title', 'إدارة المستخدمين والأدوار')

{{-- ========================== CSS Section ========================== --}}
@section('css')
    {{-- Font Awesome إذا لم يكن مضمناً --}}
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" ... /> --}}
    {{-- Animate.css للتأثيرات --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <style>
        /* --- المتغيرات الأساسية (Globals & Dark Mode) --- */
        :root {
            --admin-primary: #4f46e5;
            --admin-primary-dark: #4338ca;
            --admin-secondary: #10b981;
            --admin-success: #22c55e;
            --admin-danger: #ef4444;
            --admin-warning: #f59e0b;
            --admin-info: #3b82f6;
            --admin-bg: #f8f9fc;
            /* لون خلفية الصفحة */
            --admin-card-bg: #ffffff;
            /* لون خلفية البطاقات والجداول */
            --admin-text: #111827;
            /* لون النص الأساسي */
            --admin-text-secondary: #6b7280;
            /* لون النص الثانوي */
            --admin-border-color: #e5e7eb;
            /* لون الحدود */
            --admin-radius-sm: 0.25rem;
            --admin-radius-md: 0.375rem;
            --admin-radius-lg: 0.75rem;
            /* زيادة الـ Radius */
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
                --admin-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
                --admin-shadow-md: 0 4px 10px -1px rgba(0, 0, 0, 0.2);
                --admin-primary: #6366f1;
                --admin-primary-dark: #4f46e5;
                --admin-secondary: #34d399;
                --admin-success: #4ade80;
                --admin-danger: #f87171;
                --admin-warning: #fcd34d;
                --admin-info: #60a5fa;
            }

            .filter-form .form-control,
            .filter-form .form-select {
                background-color: #4b5563;
                color: var(--admin-text);
                border-color: #6b7280;
            }

            .filter-form .form-control::placeholder {
                color: var(--admin-text-secondary);
            }

            .filter-form .btn-outline-light {
                color: var(--admin-text-secondary);
                border-color: var(--admin-border-color);
            }

            .filter-form .btn-outline-light:hover {
                background-color: #4b5563;
                color: var(--admin-text);
            }

            .table thead th {
                background-color: #4b5563;
                color: var(--admin-text-secondary);
            }

            .table tbody tr:hover {
                background-color: rgba(99, 102, 241, 0.08);
            }

            .user-avatar {
                border-color: #4b5563;
            }
        }

        body {
            background-color: var(--admin-bg);
            font-family: 'Tajawal', sans-serif;
        }

        /* --- حاوية الصفحة الرئيسية --- */
        .users-management-container {
            background-color: transparent;
            box-shadow: none;
            border: none;
            border-radius: 0;
        }

        /* --- بطاقات الإحصائيات --- */
        .stats-card {
            background-color: var(--admin-card-bg);
            border-radius: var(--admin-radius-lg);
            padding: 1.25rem;
            border: 1px solid var(--admin-border-color);
            box-shadow: var(--admin-shadow);
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: var(--admin-transition);
        }

        .stats-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--admin-shadow-md);
            border-color: var(--admin-primary);
        }

        .stats-card .icon {
            width: 50px;
            height: 50px;
            border-radius: var(--admin-radius-md);
            display: grid;
            place-items: center;
            font-size: 1.6rem;
            flex-shrink: 0;
        }

        .stats-card .info .count {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--admin-text);
            margin-bottom: 0.1rem;
            display: block;
        }

        .stats-card .info .label {
            font-size: 0.85rem;
            color: var(--admin-text-secondary);
        }

        .stats-card .icon.doctors {
            background: linear-gradient(135deg, #818cf8, var(--admin-primary));
            color: white;
        }

        .stats-card .icon.patients {
            background: linear-gradient(135deg, #34d399, var(--admin-secondary));
            color: white;
        }

        .stats-card .icon.employees {
            background: linear-gradient(135deg, #fbbf24, var(--admin-warning));
            color: white;
        }

        .stats-card .icon.total {
            background: linear-gradient(135deg, #a78bfa, #7c3aed);
            color: white;
        }

        /* بنفسجي */

        /* --- بطاقة الفلترة --- */
        .filter-card {
            background-color: var(--admin-card-bg);
            border-radius: var(--admin-radius-lg);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid var(--admin-border-color);
            box-shadow: var(--admin-shadow);
        }

        .filter-form .form-label {
            font-weight: 500;
            color: var(--admin-text-secondary);
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
        }

        .filter-form .form-control,
        .filter-form .form-select {
            border-radius: var(--admin-radius-md);
            border: 1px solid var(--admin-border-color);
            padding: 0.6rem 1rem;
            font-size: 0.9rem;
            transition: var(--admin-transition);
        }

        .filter-form .form-control:focus,
        .filter-form .form-select:focus {
            border-color: var(--admin-primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
        }

        .filter-form .btn {
            border-radius: var(--admin-radius-md);
            padding: 0.6rem 1.25rem;
            font-size: 0.9rem;
            transition: var(--admin-transition);
        }

        .filter-form .btn-primary {
            background-color: var(--admin-primary);
            border-color: var(--admin-primary);
        }

        .filter-form .btn-primary:hover {
            background-color: var(--admin-primary-dark);
            border-color: var(--admin-primary-dark);
            transform: translateY(-1px);
            box-shadow: var(--admin-shadow);
        }

        .filter-form .btn-outline-light {
            border-color: var(--admin-border-color);
            color: var(--admin-text-secondary);
        }

        .filter-form .btn-outline-light:hover {
            background-color: var(--admin-bg);
            color: var(--admin-text);
        }

        /* --- الجدول --- */
        .users-table-card {
            background-color: var(--admin-card-bg);
            border-radius: var(--admin-radius-lg);
            box-shadow: var(--admin-shadow-md);
            border: 1px solid var(--admin-border-color);
            overflow: hidden;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            margin-bottom: 0;
            color: var(--admin-text);
            border-collapse: collapse;
        }

        .table thead th {
            background-color: var(--admin-bg);
            color: var(--admin-text-secondary);
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            border-bottom: 2px solid var(--admin-border-color);
            /* خط أسمك قليلاً */
            white-space: nowrap;
            padding: 1rem 1.25rem;
            text-align: right;
            /* محاذاة لليمين */
            position: sticky;
            top: 0;
            z-index: 1;
        }

        @media (prefers-color-scheme: dark) {
            .table thead th {
                background-color: #2d3748;
                border-color: #4b5563;
            }
        }

        .table tbody tr {
            transition: background-color 0.15s ease-in-out;
        }

        .table tbody tr:hover {
            background-color: rgba(79, 70, 229, 0.04);
        }

        @media (prefers-color-scheme: dark) {
            .table tbody tr:hover {
                background-color: rgba(99, 102, 241, 0.08);
            }
        }

        .table td,
        .table th {
            vertical-align: middle;
            padding: 0.9rem 1.25rem;
            border-top: 1px solid var(--admin-border-color);
            text-align: right;
        }

        .table td:first-child,
        .table th:first-child {
            padding-right: 1.5rem;
        }

        /* زيادة padding للعمود الأول */
        .table td:last-child,
        .table th:last-child {
            padding-left: 1.5rem;
            text-align: center;
        }

        /* تنسيق عمود الإجراءات */

        .user-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--admin-card-bg);
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease;
        }

        .user-avatar:hover {
            transform: scale(1.15);
        }

        @media (prefers-color-scheme: dark) {
            .user-avatar {
                border-color: #4b5563;
            }
        }

        .user-info .name {
            font-weight: 600;
            color: var(--admin-text);
            font-size: 0.95rem;
            margin-bottom: 0.1rem;
            display: block;
        }

        .user-info .email,
        .user-info .phone {
            color: var(--admin-text-secondary);
            font-size: 0.8rem;
            display: block;
        }

        .user-info .phone i {
            margin-left: 4px;
            font-size: 0.9em;
        }

        /* --- شارات الدور والحالة --- */
        .role-badge,
        .status-badge {
            font-size: 0.75rem;
            padding: 0.4em 0.8em;
            border-radius: 50px;
            /* شكل حبة دواء */
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            white-space: nowrap;
        }

        .role-badge i,
        .status-badge i {
            margin-left: 6px;
            font-size: 0.9em;
        }

        /* (نفس ألوان role-badge و status-badge من الرد السابق) */
        .role-badge-doctor {
            /* ... */
        }

        .role-badge-patient {
            /* ... */
        }

        .role-badge-ray_employee {
            /* ... */
        }

        .role-badge-laboratorie_employee {
            /* ... */
        }

        .role-badge-admin {
            /* ... */
        }

        .status-badge-active {
            /* ... */
        }

        .status-badge-inactive {
            /* ... */
        }

        @media (prefers-color-scheme: dark) {
            /* ... (نفس ألوان الوضع الداكن) ... */
        }

        /* --- أزرار الإجراءات --- */
        .action-buttons .action-btn {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: var(--admin-transition);
            color: var(--admin-text-secondary);
            background-color: transparent;
            border: none;
            margin: 0 2px;
            font-size: 0.9rem;
        }

        .action-buttons .action-btn:hover {
            background-color: rgba(var(--button-hover-rgb, 107, 114, 128), 0.1);
            color: rgb(var(--button-hover-rgb, 107, 114, 128));
            transform: scale(1.1);
        }

        .action-buttons .action-btn.edit:hover {
            --button-hover-rgb: 79, 70, 229;
        }

        /* بنفسجي */
        .action-buttons .action-btn.view:hover {
            --button-hover-rgb: 59, 130, 246;
        }

        /* أزرق */
        .action-buttons .action-btn.delete:hover {
            --button-hover-rgb: 239, 68, 68;
        }

        /* أحمر */

        /* --- حالة عدم وجود نتائج --- */
        .empty-state {
            padding: 4rem 1rem;
            text-align: center;
            color: var(--admin-text-secondary);
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            color: var(--admin-border-color);
            opacity: 0.7;
        }

        .empty-state h4 {
            margin-bottom: 0.75rem;
            color: var(--admin-text);
            font-weight: 600;
        }

        .empty-state p {
            font-size: 0.9rem;
        }

        .empty-state .btn i {
            margin-right: 0.5rem;
        }

        /* --- الترقيم Pagination --- */
        .pagination-container {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--admin-border-color);
            background-color: var(--admin-bg);
            border-bottom-left-radius: var(--admin-radius-lg);
            border-bottom-right-radius: var(--admin-radius-lg);
        }

        .pagination {
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

        @media (prefers-color-scheme: dark) {
            .page-item .page-link {
                background-color: var(--admin-card-bg);
                border-color: var(--admin-border-color);
                color: var(--admin-text-secondary);
            }

            .page-item .page-link:hover {
                background-color: var(--admin-bg);
                border-color: #6b7280;
                color: var(--admin-text);
            }

            .page-item.active .page-link {
                background-color: var(--admin-primary);
                border-color: var(--admin-primary);
                color: white;
                box-shadow: none;
            }

            .page-item.disabled .page-link {
                background-color: #4b5563;
                border-color: #6b7280;
                color: #9ca3af;
            }
        }

        /* --- Responsive Table Improvements --- */
        @media (max-width: 991px) {
            .table thead {
                display: none;
            }

            .table,
            .table tbody,
            .table tr,
            .table td {
                display: block;
                width: 100% !important;
            }

            .table tr {
                margin-bottom: 1rem;
                border: 1px solid var(--admin-border-color);
                border-radius: var(--admin-radius-md);
                overflow: hidden;
                box-shadow: var(--admin-shadow);
            }

            .table td {
                padding: 0.75rem 1rem;
                text-align: right !important;
                /* لضمان المحاذاة */
                position: relative;
                border: none;
                border-bottom: 1px solid var(--admin-border-color);
                display: flex;
                align-items: center;
                justify-content: space-between;
                /* ترتيب المحتوى */
            }

            /* إخفاء label للعمود الأول (الصورة والاسم) */
            .table td[data-label="#"] {
                display: none;
            }

            .table td:last-child {
                border-bottom: none;
            }

            /* إضافة الـ Label قبل المحتوى */
            .table td::before {
                content: attr(data-label);
                /* استخدام data-label */
                font-weight: 600;
                color: var(--admin-text-secondary);
                font-size: 0.8rem;
                margin-left: 10px;
                /* مسافة بين الليبل والمحتوى */
            }

            /* تنسيق خاص للصورة والاسم في وضع الموبايل */
            .table td[data-label="المستخدم"] div.d-flex {
                width: 100%;
            }

            /* جعل المحتوى يأخذ العرض كامل */
            .table td[data-label="المستخدم"]::before {
                display: none;
            }

            /* إخفاء الليبل لهذا العمود */
            .table td[data-label="الإجراءات"]::before {
                display: none;
            }

            .table td[data-label="الإجراءات"] .action-buttons {
                width: 100%;
                justify-content: flex-end;
            }

            /* محاذاة الأزرار لليسار */
        }
    </style>
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                {{-- أيقونة أكبر وأوضح --}}
                <i class="fas fa-users-cog fa-lg text-primary me-2"></i>
                <div>
                    <h4 class="content-title mb-0 my-auto">إدارة المستخدمين</h4>
                    <span class="text-muted mt-0 tx-13">عرض وتصنيف المستخدمين حسب أدوارهم وحالاتهم</span>
                </div>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center gap-2">
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    @include('Dashboard.messages_alert')

    {{-- حاوية الصفحة الرئيسية --}}
    <div class="users-management-container">

        {{-- 1. بطاقات الإحصائيات --}}
        <div class="row g-3 mb-4 animate__animated animate__fadeIn">
            {{-- بطاقة الأطباء --}}
            <div class="col-sm-6 col-lg-3">
                <div class="stats-card">
                    <div class="icon doctors"><i class="fas fa-user-md"></i></div>
                    <div class="info">
                        <span class="count">{{ App\Models\Doctor::count() }}</span>
                        <span class="label">اطباء</span>
                    </div>
                </div>
            </div>
            {{-- بطاقة المرضى --}}
            <div class="col-sm-6 col-lg-3">
                <div class="stats-card">
                    <div class="icon patients"><i class="fas fa-user-injured"></i></div>
                    <div class="info">
                        <span class="count">{{ App\Models\Patient::count() }}</span>
                        <span class="label">مريض</span>
                    </div>
                </div>
            </div>
            {{-- بطاقة موظفي الأشعة --}}
            <div class="col-sm-6 col-lg-3">
                <div class="stats-card">
                    <div class="icon employees"><i class="fas fa-x-ray"></i></div>
                    <div class="info">
                        <span class="count">{{ App\Models\RayEmployee::count() }}</span>
                        <span class="label">موظف أشعة</span>
                    </div>
                </div>
            </div>
            {{-- بطاقة موظفي المختبر --}}
            <div class="col-sm-6 col-lg-3">
                <div class="stats-card">
                    {{-- استخدام أيقونة ولون المختبر --}}
                    <div class="icon"
                        style="background: linear-gradient(135deg, #60a5fa, var(--admin-info)); color: white;"><i
                            class="fas fa-flask"></i></div>
                    <div class="info">
                        <span class="count">{{ App\Models\LaboratorieEmployee::count() }}</span>
                        <span class="label">موظف مختبر</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. بطاقة الفلترة --}}
        <div class="filter-card animate__animated animate__fadeIn">
            <form action="{{ route('admin.users.roles.index') }}" method="GET" class="filter-form">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-4 col-md-6">
                        <label for="search" class="form-label">بحث سريع</label>
                        <input type="text" id="search" name="search" class="form-control"
                            placeholder="اسم، بريد إلكتروني..." value="{{ $request->input('search') }}">
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="role" class="form-label">تصنيف حسب الدور</label>
                        <select id="role" name="role" class="form-select">
                            <option value="">-- كل الأدوار --</option>
                            @foreach ($roles as $key => $value)
                                <option value="{{ $key }}" {{ $request->input('role') == $key ? 'selected' : '' }}>
                                    {{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="status" class="form-label">تصنيف حسب الحالة</label>
                        <select id="status" name="status" class="form-select">
                            <option value="">-- كل الحالات --</option>
                            {{-- استخدام === للتحقق الدقيق مع السلاسل النصية --}}
                            <option value="1" {{ $request->input('status') === '1' ? 'selected' : '' }}>نشط فقط
                            </option>
                            <option value="0" {{ $request->input('status') === '0' ? 'selected' : '' }}>غير نشط فقط
                            </option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-6">
                        {{-- زر تطبيق الفلتر --}}
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-1"></i> تطبيق
                        </button>
                        {{-- (اختياري) زر إعادة تعيين الفلاتر --}}
                        @if (request()->has('search') || request()->has('role') || request()->has('status'))
                            <a href="{{ route('admin.users.roles.index') }}" class="btn btn-outline-light w-100 mt-2">
                                <i class="fas fa-eraser me-1"></i> مسح الفلتر
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        {{-- 3. بطاقة جدول المستخدمين --}}
        <div class="users-table-card animate__animated animate__fadeInUp">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th data-label="#">#</th>
                            <th data-label="المستخدم">المستخدم</th>
                            <th data-label="الدور">الدور</th>
                            <th data-label="الحالة">الحالة</th>
                            <th data-label="تاريخ الانضمام">تاريخ الانضمام</th>
                            <th data-label="الإجراءات">إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $index => $user)
                            <tr>
                                {{-- الرقم التسلسلي (مع الترقيم) --}}
                                <td data-label="#">{{ $users->firstItem() + $index }}</td>

                                {{-- معلومات المستخدم (الصورة، الاسم، الإيميل، الهاتف) --}}
                                <td data-label="المستخدم">
                                    <div class="d-flex align-items-center">
                                        @php
                                            $userInstance = $user; // $user هو المتغير القادم من الحلقة @foreach
                                            $imageSrc = null;
                                            $defaultImage = URL::asset('Dashboard/img/default_avatar.png'); // صورة افتراضية عامة جدًا
                                            $objectName = $userInstance->name ?? ($userInstance->email ?? 'مستخدم'); // اسم للعرض و alt

                                            // تحديد مجلد الصورة والصورة الافتراضية بناءً على نوع الموديل
                                            $folderName = null;
                                            $specificDefaultImage = null;

                                            if ($userInstance instanceof \App\Models\Admin) {
                                                $folderName = 'admin_photos'; // يُفترض أنه داخل public/storage/
                                                $specificDefaultImage = URL::asset(
                                                    'Dashboard/img/default_admin_avatar.png',
                                                );
                                            } elseif ($userInstance instanceof \App\Models\Doctor) {
                                                $folderName = 'doctors'; // يُفترض أنه داخل public/Dashboard/img/doctors/
                                                $specificDefaultImage = URL::asset(
                                                    'Dashboard/img/faces/doctor_default.png',
                                                );
                                            } elseif ($userInstance instanceof \App\Models\RayEmployee) {
                                                $folderName = 'rayEmployees'; // يُفترض أنه داخل public/Dashboard/img/rayEmployees/
                                                $specificDefaultImage = URL::asset(
                                                    'Dashboard/img/default_ray_employee_avatar.png',
                                                );
                                            } elseif ($userInstance instanceof \App\Models\LaboratorieEmployee) {
                                                $folderName = 'laboratorieEmployees'; // يُفترض أنه داخل public/Dashboard/img/laboratorieEmployees/
                                                $specificDefaultImage = URL::asset(
                                                    'Dashboard/img/default_lab_employee_avatar.png',
                                                );
                                            } elseif ($userInstance instanceof \App\Models\Patient) {
                                                // افترض وجود موديل Patient
                                                $folderName = 'patients'; // يُفترض أنه داخل public/storage/patients/
                                                $specificDefaultImage = URL::asset(
                                                    'Dashboard/img/default_patient_avatar.png',
                                                );
                                            }
                                            // أضف المزيد من الشروط لأنواع المستخدمين الأخرى

                                            // الآن حدد مسار الصورة الفعلي
                                            if ($userInstance->image && $userInstance->image->filename && $folderName) {
                                                // إذا كنت تستخدم storage:link للمجلدات مثل admin_photos, patients
                                                if (
                                                    in_array($folderName, [
                                                        'admin_photos',
                                                        'patients' /*, أضف مجلدات أخرى في storage هنا */,
                                                    ])
                                                ) {
                                                    $imageSrc = asset(
                                                        'Dashboard/img/' .
                                                            $folderName .
                                                            '/' .
                                                            $userInstance->image->filename,
                                                    );
                                                } else {
                                                    // إذا كانت المجلدات الأخرى مباشرة داخل public/Dashboard/img/
                                                    $imageSrc = URL::asset(
                                                        'Dashboard/img/' .
                                                            $folderName .
                                                            '/' .
                                                            $userInstance->image->filename,
                                                    );
                                                }
                                            }

                                            // استخدم الصورة الافتراضية المحددة إذا لم توجد صورة للمستخدم أو إذا لم يتم تحديد مسار
                                            if (!$imageSrc && $specificDefaultImage) {
                                                $imageSrc = $specificDefaultImage;
                                            } elseif (!$imageSrc) {
                                                $imageSrc = $defaultImage; // fallback إلى الصورة الافتراضية العامة جدًا
                                            }
                                        @endphp

                                        <img src="{{ $imageSrc }}" alt="{{ $objectName }}"
                                            class="user-avatar me-3 flex-shrink-0"
                                            style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                                        <div class="user-info">
                                            <span class="name"
                                                style="font-weight: 500; display: block; color: #333;">{{ $objectName }}</span>
                                            <span class="email"
                                                style="font-size: 0.85em; color: #777; display: block;">{{ $userInstance->email ?? '-' }}</span>
                                            @php
                                                // محاولة الحصول على رقم الهاتف من عدة أسماء حقول محتملة
                                                $phone =
                                                    $userInstance->phone ??
                                                    ($userInstance->Phone ?? ($userInstance->mobile ?? null));
                                            @endphp
                                            @if ($phone)
                                                <span class="phone"
                                                    style="font-size: 0.85em; color: #555; display: block;">
                                                    <i class="fas fa-phone-alt fa-xs" style="margin-left: 3px;"></i>
                                                    {{ $phone }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                {{-- الدور --}}
                                <td data-label="الدور">
                                    <span class="badge role-badge role-badge-{{ $user->role_key ?? 'default' }}">
                                        {{-- أيقونة للدور --}}
                                        <i
                                            class="fas @switch($user->role_key) @case('doctor') fa-user-md @break @case('patient') fa-user-injured @break @case('ray_employee') fa-x-ray @break @case('laboratorie_employee') fa-flask @break @default fa-user @endswitch"></i>
                                        {{ $user->role_name ?? 'غير محدد' }}
                                    </span>
                                </td>

                                {{-- الحالة --}}
                                <td data-label="الحالة">
                                    @if (isset($user->status))
                                        <span
                                            class="badge status-badge {{ $user->status ? 'status-badge-active' : 'status-badge-inactive' }}">
                                            <i
                                                class="fas {{ $user->status ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                                            {{ $user->status_display ?? ($user->status ? 'نشط' : 'غير نشط') }}
                                        </span>
                                    @else
                                        <span class="badge status-badge status-badge-active">
                                            <i class="fas fa-check-circle"></i> نشط
                                        </span>
                                    @endif
                                </td>

                                {{-- تاريخ الانضمام --}}
                                <td data-label="تاريخ الانضمام">
                                    <div class="text-muted small">
                                        {{ optional($user->created_at)->format('d M Y') ?? '-' }}
                                    </div>
                                    <div class="text-muted" style="font-size: 0.75rem;">
                                        {{ optional($user->created_at)->diffForHumans() ?? '' }}
                                    </div>
                                </td>

                                {{-- إجراءات --}}
                                <td data-label="الإجراءات">
                                    <div class="action-buttons">

                                        @if ($user->role_key === 'laboratorie_employee')
                                            {{-- =========   التعديل هنا   ========= --}}
                                            {{-- رابط يوجه إلى صفحة تعديل موظف المختبر المنفصلة --}}
                                            <a href="{{ route('admin.laboratorie_employee.edit', $user->id) }}" {{-- ** استخدام route edit ** --}}
                                               class="action-btn edit" title="تعديل موظف المختبر">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                            {{-- ========= نهاية التعديل ========= --}}

                                        @elseif ($user->role_key === 'doctor')
                                            {{-- رابط إلى صفحة تعديل الطبيب الرسمية (يفترض أنها تعمل) --}}
                                            <a href="{{ route('admin.Doctors.edit', $user->id) }}"
                                               class="action-btn edit" title="تعديل الطبيب">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>

                                        @elseif ($user->role_key === 'ray_employee')
                                             {{-- رابط إلى صفحة تعديل موظف الأشعة (إذا أنشأتها) --}}
                                             <a href="{{ route('admin.ray_employee.edit', $user->id) }}" {{-- ** افترض وجود route edit هنا أيضاً ** --}}
                                               class="action-btn edit" title="تعديل موظف الأشعة">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>

                                        @elseif ($user->role_key === 'patient')
                                             {{-- رابط لصفحة تعديل المريض --}}
                                             <a href="{{ route('admin.Patients.edit', $user->id)}}" class="action-btn edit" title="تعديل المريض">
                                                 <i class="fas fa-pencil-alt"></i>
                                             </a>
                                        @else
                                            <button type="button" class="action-btn edit" title="التعديل غير متاح" disabled>
                                                <i class="fas fa-pencil-alt"></i>
                                            </button>
                                        @endif

                                        {{-- أزرار عرض التفاصيل والحذف (تبقى كما هي أو تعدل حسب الحاجة) --}}
                                        <button type="button" class="action-btn view" title="عرض التفاصيل" ... >
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="action-btn delete" title="حذف أو تعطيل" ...>
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </td>
                        @empty
                            <tr>
                                <td colspan="6"> {{-- التأكد من عدد الأعمدة الصحيح --}}
                                    <div class="empty-state">
                                        <i class="fas fa-users-slash"></i>
                                        <h4>لا يوجد مستخدمين</h4>
                                        <p>
                                            @if (request()->filled('search') || request()->filled('role') || request()->filled('status'))
                                                لا توجد نتائج تطابق معايير البحث أو الفلترة.
                                            @else
                                                ابدأ بإضافة مستخدمين جدد للنظام.
                                            @endif
                                        </p>
                                        {{-- <button type="button" class="btn btn-primary mt-2"
                                            onclick="alert('سيتم إضافة هذه الميزة قريباً');">
                                            <i class="fas fa-user-plus me-1"></i> إضافة مستخدم جديد
                                        </button> --}}
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- الترقيم Pagination --}}
            @if ($users->hasPages())
                <div class="pagination-container d-flex justify-content-center">
                    {{-- تمرير request للحفاظ على الفلاتر --}}
                    {!! $users->appends($request->query())->links('pagination::bootstrap-5') !!} {{-- استخدام عرض Bootstrap 5 --}}
                </div>
            @endif
        </div>
    </div>

    {{-- (اختياري) Modal لعرض التفاصيل (محتوى افتراضي) --}}
    <div class="modal fade" id="userDetailsModal" tabindex="-1" aria-labelledby="userDetailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="userDetailsModalLabel">تفاصيل المستخدم</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="userDetailsContent">
                    <p class="text-center py-5">جاري تحميل التفاصيل...</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- ====================== JavaScript Section ===================== --}}
@section('js')
    {{-- Bootstrap JS (إذا لم يكن مضمنًا ومطلوب للـ Tooltips أو Modal) --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" ...></script> --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. تفعيل Tooltips باستخدام Bootstrap 5
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // 2. (اختياري) منطق فتح الـ Modal وعرض التفاصيل (يتطلب AJAX)
            const userDetailsModal = document.getElementById('userDetailsModal');
            if (userDetailsModal) {
                const modalBody = document.getElementById('userDetailsContent');
                const modalTitle = document.getElementById('userDetailsModalLabel');

                document.querySelectorAll('.action-btn.view').forEach(button => {
                    button.addEventListener('click', function() {
                        // استبدل هذا بمنطق AJAX الفعلي
                        modalTitle.innerHTML =
                            '<i class="fas fa-spinner fa-spin me-2"></i> جاري تحميل...';
                        modalBody.innerHTML =
                            '<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-3x text-primary"></i></div>';
                        const userName = this.closest('tr').querySelector('.user-info .name')
                            ?.textContent || 'المستخدم'; // الحصول على الاسم
                        const userRole = this.closest('tr').querySelector('.role-badge')
                            ?.textContent.trim() || 'الدور'; // الحصول على الدور

                        // عرض الـ Modal
                        var bsModal = new bootstrap.Modal(userDetailsModal);
                        bsModal.show();

                        // محاكاة AJAX
                        setTimeout(() => {
                            modalTitle.textContent = `تفاصيل: ${userName} (${userRole})`;
                            // هنا تضع محتوى الـ HTML الذي يتم جلبه من AJAX
                            modalBody.innerHTML = `
                                <p><strong>البريد الإلكتروني:</strong> ${this.closest('tr').querySelector('.user-info .email')?.textContent || '-'}</p>
                                <p><strong>الهاتف:</strong> ${this.closest('tr').querySelector('.user-info .phone')?.textContent || '-'}</p>
                                <p><strong>الدور:</strong> ${userRole}</p>
                                <p><strong>الحالة:</strong> ${this.closest('tr').querySelector('.status-badge')?.textContent.trim() || '-'}</p>
                                <p><strong>تاريخ الانضمام:</strong> ${this.closest('tr').querySelector('td[data-label="تاريخ الانضمام"] .small')?.textContent || '-'}</p>
                                <hr>
                                <p><em>محتوى إضافي يتم جلبه هنا...</em></p>
                            `;
                        }, 1200); // نهاية setTimeout (للمحاكاة)
                    });
                });
            }

            console.log('Admin User Roles page enhanced script loaded.');
        });
    </script>
@endsection
