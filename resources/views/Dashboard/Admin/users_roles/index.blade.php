{{-- resources/views/Dashboard/Admin/users_roles/index.blade.php --}}
@extends('Dashboard.layouts.master')

@section('title', 'إدارة المستخدمين والنظام')

@section('css')
    @parent
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    {{-- FontAwesome if not already included by master --}}
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" /> --}}
    <style>
        /* --- المتغيرات الأساسية (مأخوذة من كودك السابق) --- */
        :root {
            --admin-primary: #4f46e5; --admin-primary-dark: #4338ca;
            --admin-secondary: #10b981; --admin-success: #22c55e;
            --admin-danger: #ef4444; --admin-warning: #f59e0b;
            --admin-info: #3b82f6; --admin-purple: #8b5cf6;
            --admin-teal: #14b8a6;
            --admin-bg: #f8f9fc; --admin-card-bg: #ffffff;
            --admin-text: #1f2937; --admin-text-secondary: #6b7280;
            --admin-border-color: #e5e7eb; /* Gray-200 */
            --admin-radius-md: 0.375rem; --admin-radius-lg: 0.75rem;
            --admin-shadow: 0 1px 3px rgba(0,0,0,0.06); /* ظل أنعم */
            --admin-shadow-md: 0 4px 10px -1px rgba(0,0,0,0.06), 0 2px 4px -1px rgba(0,0,0,0.04); /* ظل أوضح */
            --admin-transition: all 0.2s ease-in-out;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --admin-bg: #111827; --admin-card-bg: #1f2937;
                --admin-text: #f3f4f6; --admin-text-secondary: #9ca3af;
                --admin-border-color: #374151;
                --admin-primary: #818cf8; --admin-primary-dark: #6366f1;
                --admin-secondary: #34d399;
            }
            /* ... (باقي أنماط الوضع الداكن من كودك) ... */
             .filter-form .form-control, .filter-form .form-select { background-color: #374151; color: var(--admin-text); border-color: #4b5563; }
            .table thead th { background-color: #374151 !important; color: var(--admin-text-secondary); border-color: #4b5563 !important;}
            .table tbody tr:hover { background-color: rgba(99, 102, 241, 0.08) !important; } /* !important للتأكيد */
            .pagination-container {background-color: #111827;}
            .page-item .page-link {background-color: #1f2937; border-color: #374151; color: #9ca3af;}
            .page-item.active .page-link {background-color: var(--admin-primary); border-color: var(--admin-primary);}
            .page-item.disabled .page-link {background-color: #1f2937; color: #4b5563;}
        }

        body { background-color: var(--admin-bg); font-family: 'Tajawal', sans-serif; color: var(--admin-text); }
        .users-management-container { padding-top: 1rem; }

        /* -- بطاقات الإحصائيات -- */
        .stats-card { background-color: var(--admin-card-bg); border-radius: var(--admin-radius-lg); padding: 1.25rem; border: 1px solid var(--admin-border-color); box-shadow: var(--admin-shadow); display: flex; align-items: center; gap: 1rem; transition: var(--admin-transition); text-decoration: none; color: inherit;}
        .stats-card:hover { transform: translateY(-5px); box-shadow: var(--admin-shadow-md); border-left: 4px solid var(--hover-color, var(--admin-primary)); }
        .stats-card .icon { width: 48px; height: 48px; border-radius: var(--admin-radius-md); display: grid; place-items: center; font-size: 1.5rem; flex-shrink: 0; color:white; }
        .stats-card .info .count { font-size: 1.75rem; font-weight: 700; color: var(--admin-text); margin-bottom: 0rem; display: block; }
        .stats-card .info .label { font-size: 0.8rem; color: var(--admin-text-secondary); text-transform: uppercase;}

        .stats-card.doctors-card:hover { --hover-color: var(--admin-primary); }
        .stats-card .icon.doctors { background: var(--admin-primary); } /* لون واحد للتبسيط */
        .stats-card.patients-card:hover { --hover-color: var(--admin-secondary); }
        .stats-card .icon.patients { background: var(--admin-secondary); }
        .stats-card.ray_employees-card:hover { --hover-color: var(--admin-warning); }
        .stats-card .icon.ray_employees { background: var(--admin-warning); }
        .stats-card.lab_employees-card:hover { --hover-color: var(--admin-info); }
        .stats-card .icon.lab_employees { background: var(--admin-info); }
        .stats-card.pharmacy_managers-card:hover { --hover-color: var(--admin-purple); }
        .stats-card .icon.pharmacy_managers { background: var(--admin-purple); }
        .stats-card.pharmacy_employees-card:hover { --hover-color: var(--admin-teal); }
        .stats-card .icon.pharmacy_employees { background: var(--admin-teal); }


        /* -- بطاقة الفلترة -- */
        .filter-card { background-color: var(--admin-card-bg); border-radius: var(--admin-radius-lg); padding: 1.5rem; margin-bottom: 1.5rem; border: 1px solid var(--admin-border-color); box-shadow: var(--admin-shadow); }
        .filter-form .form-label { font-weight: 500; color: var(--admin-text-secondary); font-size: 0.85rem; margin-bottom: 0.5rem; }
        .filter-form .form-control, .filter-form .form-select { border-radius: var(--admin-radius-md); border: 1px solid var(--admin-border-color); padding: 0.6rem 1rem; font-size: 0.9rem; transition: var(--admin-transition); background-color: var(--admin-card-bg); color: var(--admin-text); }
        .filter-form .form-control:focus, .filter-form .form-select:focus { border-color: var(--admin-primary); box-shadow: 0 0 0 3px rgba(var(--admin-primary-rgb), 0.15); }
        .filter-form .btn { border-radius: var(--admin-radius-md); padding: 0.6rem 1.25rem; font-size: 0.9rem; transition: var(--admin-transition); }
        .filter-form .btn-primary { background-color: var(--admin-primary); border-color: var(--admin-primary); color:white; }
        .filter-form .btn-primary:hover { background-color: var(--admin-primary-dark); border-color: var(--admin-primary-dark); transform: translateY(-1px); box-shadow: var(--admin-shadow); }
        .filter-form .btn-outline-secondary { border-color: var(--admin-border-color); color: var(--admin-text-secondary); } /* Changed from light to secondary */
        .filter-form .btn-outline-secondary:hover { background-color: var(--admin-bg); color: var(--admin-text); }

        /* -- الجدول -- */
        .users-table-card { background-color: var(--admin-card-bg); border-radius: var(--admin-radius-lg); box-shadow: var(--admin-shadow-md); border: 1px solid var(--admin-border-color); overflow: hidden; }
        .table { width: 100%; margin-bottom: 0; color: var(--admin-text); border-collapse: initial; border-spacing: 0; /* For rounded corners on table */ }
        .table thead th { background-color: var(--admin-bg) !important; color: var(--admin-text-secondary); font-weight: 600; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 2px solid var(--admin-border-color) !important; white-space: nowrap; padding: 0.85rem 1.25rem; text-align: right; position: sticky; top: 0; z-index: 10;}
        .table tbody tr { transition: background-color 0.15s ease-in-out, transform 0.15s ease-in-out; }
        .table tbody tr:hover { background-color: rgba(var(--admin-primary-rgb), 0.03) !important; transform: scale(1.005); box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
        .table td, .table th { vertical-align: middle; padding: 0.85rem 1.25rem; border-top: 1px solid var(--admin-border-color); text-align: right; }
        .table td:first-child, .table th:first-child { padding-right: 1.5rem; }
        .table td:last-child, .table th:last-child { padding-left: 1.5rem; text-align: center; }

        .user-avatar { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid var(--admin-card-bg); box-shadow: 0 1px 3px rgba(0,0,0,0.15); transition: transform 0.2s ease; margin-left: 12px; } /* RTL */
        .user-avatar:hover { transform: scale(1.2); }

        .user-info .name {display: block; font-weight: 600; color: var(--admin-primary-dark); font-size: 0.95rem; margin-bottom: 0.1rem; text-decoration: none; }
        .user-info .name:hover { text-decoration: underline; }
        .user-info .email, .user-info .phone { display: block; color: var(--admin-text-secondary); font-size: 0.8rem; }
        .user-info .phone i { margin-left: 5px; font-size: 0.9em; }

        .role-badge, .status-badge { font-size: 0.7rem; padding: 0.35em 0.75em; border-radius: var(--admin-radius-md); font-weight: 500; display: inline-flex; align-items: center; white-space: nowrap; border: 1px solid transparent; text-transform: uppercase; letter-spacing: 0.03em;}
        .role-badge i, .status-badge i { margin-left: 5px; font-size: 0.85em; }
        /* ... (الأنماط السابقة للـ badges جيدة) ... */
        .role-badge-default { background-color: rgba(107, 114, 128, 0.1); color: #4b5563; border-color: rgba(107, 114, 128, 0.2);}
        .role-badge-doctor { background-color: rgba(var(--admin-primary-rgb), 0.1); color: var(--admin-primary); border-color: rgba(var(--admin-primary-rgb), 0.2); }
        .role-badge-patient { background-color: rgba(var(--admin-secondary-rgb), 0.1); color: var(--admin-secondary); border-color: rgba(var(--admin-secondary-rgb), 0.2); }
        .role-badge-ray_employee { background-color: rgba(var(--admin-warning-rgb), 0.1); color: var(--admin-warning); border-color: rgba(var(--admin-warning-rgb), 0.2); }
        .role-badge-laboratorie_employee { background-color: rgba(var(--admin-info-rgb), 0.1); color: var(--admin-info); border-color: rgba(var(--admin-info-rgb), 0.2); }
        .role-badge-pharmacy_manager { background-color: rgba(var(--admin-purple), 0.1); color: var(--admin-purple); border-color: rgba(var(--admin-purple), 0.2); }
        .role-badge-pharmacy_employee { background-color: rgba(var(--admin-teal), 0.1); color: var(--admin-teal); border-color: rgba(var(--admin-teal), 0.2); }

        .status-badge-active { background-color: rgba(var(--admin-success-rgb), 0.1); color: var(--admin-success); border-color: rgba(var(--admin-success-rgb), 0.2); }
        .status-badge-inactive { background-color: rgba(var(--admin-danger-rgb), 0.08); color: var(--admin-danger); border-color: rgba(var(--admin-danger-rgb), 0.15); }


        .action-buttons .action-btn { width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; transition: var(--admin-transition); color: var(--admin-text-secondary); background-color: transparent; border: none; margin: 0 3px; font-size: 1rem; } /* حجم أكبر قليلاً */
        .action-buttons .action-btn:hover { background-color: rgba(var(--button-hover-rgb, var(--admin-primary-rgb)), 0.1); color: rgb(var(--button-hover-rgb, var(--admin-primary-rgb))); transform: translateY(-1px) scale(1.05); box-shadow: 0 2px 4px rgba(0,0,0,0.1);}
        .action-buttons .action-btn.edit { --button-hover-rgb: var(--admin-primary-rgb) ; }
        .action-buttons .action-btn.view { --button-hover-rgb: var(--admin-info-rgb) ; }
        .action-buttons .action-btn.delete { --button-hover-rgb: var(--admin-danger-rgb) ; }

        .empty-state { padding: 4rem 1rem; text-align: center; color: var(--admin-text-secondary); }
        .empty-state i { font-size: 3.5rem; margin-bottom: 1.5rem; color: var(--admin-border-color); opacity: 0.5; }
        .empty-state h4 { margin-bottom: 0.75rem; color: var(--admin-text); font-weight: 600; }
        .empty-state p { font-size: 0.95rem; }

        .pagination-container { padding: 1.25rem; border-top: 1px solid var(--admin-border-color); background-color: var(--admin-card-bg); border-bottom-left-radius: var(--admin-radius-lg); border-bottom-right-radius: var(--admin-radius-lg); display: flex; justify-content: center; }
         /* ... أنماط Pagination كما هي من كودك فهي جيدة ... */
         .page-item .page-link { border-radius: var(--admin-radius-md) !important; margin: 0 3px; border-color: var(--admin-border-color); color: var(--admin-text-secondary); background-color: var(--admin-card-bg); transition: var(--admin-transition); font-size: 0.9rem; }
        .page-item .page-link:hover { background-color: var(--admin-bg); border-color: #cbd5e1; color: var(--admin-text); }
        .page-item.active .page-link { background-color: var(--admin-primary); border-color: var(--admin-primary); color: white; box-shadow: 0 2px 5px rgba(var(--admin-primary-rgb), 0.3); }
        .page-item.disabled .page-link { background-color: var(--admin-bg); border-color: var(--admin-border-color); color: #cbd5e1; }


        /* Responsive Table - يبقى كما هو من كودك فهو جيد */
        @media (max-width: 991px) {
            .table thead { display: none; }
            .table, .table tbody, .table tr, .table td { display: block; width: 100% !important; }
            .table tr { margin-bottom: 1rem; border: 1px solid var(--admin-border-color) !important; border-radius: var(--admin-radius-md); overflow: hidden; box-shadow: var(--admin-shadow); }
            .table td { padding: 0.75rem 1rem; text-align: right !important; position: relative; border: none !important; border-bottom: 1px solid var(--admin-border-color) !important; display: flex; align-items: center; justify-content: space-between; }
            .table td[data-label="#"] {display:none;}
            .table td:last-child { border-bottom: none !important; }
            .table td::before { content: attr(data-label); font-weight: 600; color: var(--admin-text-secondary); font-size: 0.8rem; margin-left: 10px; }
            .table td[data-label="المستخدم"] div.d-flex { width: 100%; }
            .table td[data-label="المستخدم"]::before { display: none; }
            .table td[data-label="الإجراءات"]::before { display: none; }
            .table td[data-label="الإجراءات"] .action-buttons { width: 100%; justify-content: flex-start; }
        }
        /* Modal Dark Mode adjustment */
        @media (prefers-color-scheme: dark) {
            .modal-content { background-color: #1f2937; /* Gray-800 */ color: var(--admin-text); }
            .modal-header { border-bottom-color: #374151; /* Gray-700 */ }
            .modal-header .btn-close { filter: invert(1) grayscale(100%) brightness(200%); }
            .modal-footer { border-top-color: #374151; /* Gray-700 */ }
            #userDetailsModal .table th { color: var(--admin-text-secondary); }
            #userDetailsModal .table td { color: var(--admin-text); }
        }

    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <i class="fas fa-users-cog fa-2x text-primary me-3"></i>
                <div>
                    <h4 class="content-title mb-0 my-auto">إدارة المستخدمين والنظام</h4>
                    <span class="text-muted mt-0 tx-13 op-8">عرض، تصنيف، وتعديل جميع مستخدمي النظام وأدوارهم</span>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    @include('Dashboard.messages_alert')

    <div class="users-management-container">

        {{-- 1. Stats Cards --}}
        <div class="row g-3 mb-4 animate__animated animate__fadeIn">
            <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xl-2"> {{-- تعديل الأعمدة لتناسب المزيد من البطاقات --}}
                <a href="{{ route('admin.Doctors.index', ['role' => 'doctor']) }}" class="stats-card doctors-card">
                    <div class="icon doctors"><i class="fas fa-user-md"></i></div>
                    <div class="info">
                        <span class="count">{{ $users->where('role_key', 'doctor')->count() }}</span> {{-- حساب من المجموعة المفلترة أو جلبها من الكنترولر --}}
                        <span class="label">أطباء</span>
                    </div>
                </a>
            </div>
            <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xl-2">
                <a href="{{ route('admin.Patients.index', ['role' => 'patient']) }}" class="stats-card patients-card">
                    <div class="icon patients"><i class="fas fa-user-injured"></i></div>
                    <div class="info">
                        <span class="count">{{ $users->where('role_key', 'patient')->count() }}</span>
                        <span class="label">مرضى</span>
                    </div>
                </a>
            </div>
            <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xl-2">
                <a href="{{ route('admin.ray_employee.index', ['role' => 'ray_employee']) }}" class="stats-card ray_employees-card">
                    <div class="icon ray_employees"><i class="fas fa-x-ray"></i></div>
                    <div class="info">
                        <span class="count">{{ $users->where('role_key', 'ray_employee')->count() }}</span>
                        <span class="label">موظفو أشعة</span>
                    </div>
                </a>
            </div>
            <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xl-2">
                 <a href="{{ route('admin.laboratorie_employee.index', ['role' => 'laboratorie_employee']) }}" class="stats-card lab_employees-card">
                    <div class="icon lab_employees"><i class="fas fa-flask"></i></div>
                    <div class="info">
                        <span class="count">{{ $users->where('role_key', 'laboratorie_employee')->count() }}</span>
                        <span class="label">موظفو مختبر</span>
                    </div>
                </a>
            </div>
            <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xl-2">
                <a href="{{ route('admin.pharmacy_manager.index', ['role' => 'pharmacy_manager']) }}" class="stats-card pharmacy_managers-card">
                    <div class="icon pharmacy_managers"><i class="fas fa-store-alt"></i></div>
                    <div class="info">
                        <span class="count">{{ $users->where('role_key', 'pharmacy_manager')->count() }}</span>
                        <span class="label">مديرو صيدلية</span>
                    </div>
                </a>
            </div>
            <div class="col-6 col-sm-4 col-md-4 col-lg-3 col-xl-2">
                 <a href="{{ route('admin.pharmacy_employee.index', ['role' => 'pharmacy_employee']) }}" class="stats-card pharmacy_employees-card">
                    <div class="icon pharmacy_employees"><i class="fas fa-pills"></i></div>
                    <div class="info">
                        <span class="count">{{ $users->where('role_key', 'pharmacy_employee')->count() }}</span>
                        <span class="label">موظفو صيدلية</span>
                    </div>
                </a>
            </div>
        </div>

        {{-- 2. Filter Card --}}
        <div class="filter-card mb-4 animate__animated animate__fadeIn">
            {{-- كود الفلاتر كما هو، فهو جيد --}}
            <form action="{{ route('admin.users.roles.index') }}" method="GET" class="filter-form">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-4 col-md-12">
                        <label for="search" class="form-label"><i class="fas fa-search opacity-75 me-1"></i>بحث سريع</label>
                        <input type="text" id="search" name="search" class="form-control" placeholder="اسم المستخدم، البريد الإلكتروني، الهاتف..." value="{{ $request->input('search') }}">
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="role" class="form-label"><i class="fas fa-user-tag opacity-75 me-1"></i>تصنيف حسب الدور</label>
                        <select id="role" name="role" class="form-select">
                            <option value="">-- كل الأدوار --</option>
                            @foreach ($roles as $key => $value)
                                <option value="{{ $key }}" {{ $request->input('role') == $key ? 'selected' : '' }}>
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <label for="status" class="form-label"><i class="fas fa-toggle-on opacity-75 me-1"></i>تصنيف حسب الحالة</label>
                        <select id="status" name="status" class="form-select">
                            <option value="">-- كل الحالات --</option>
                            <option value="1" {{ $request->input('status') === '1' ? 'selected' : '' }}>نشط</option>
                            <option value="0" {{ $request->input('status') === '0' ? 'selected' : '' }}>غير نشط</option>
                        </select>
                    </div>
                    <div class="col-lg-2 col-md-12 d-flex align-items-end mt-3 mt-lg-0">
                        <button type="submit" class="btn btn-primary w-100 flex-grow-1">
                            <i class="fas fa-filter me-1"></i> تطبيق
                        </button>
                        @if (request()->has('search') || request()->filled('role') || request()->filled('status'))
                            <a href="{{ route('admin.users.roles.index') }}" class="btn btn-outline-secondary ms-2" data-bs-toggle="tooltip" title="إلغاء الفلاتر">
                                <i class="fas fa-undo-alt"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        {{-- 3. Users Table Card --}}
        <div class="users-table-card animate__animated animate__fadeInUp">
            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead>
                        <tr>
                            <th data-label="#">#</th>
                            <th data-label="المستخدم" style="min-width: 280px;">المستخدم</th>
                            <th data-label="الدور">الدور</th>
                            <th data-label="الحالة">الحالة</th>
                            <th data-label="القسم/معلومة" >القسم/معلومة</th>
                            <th data-label="تاريخ الانضمام" >تاريخ الانضمام</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $index => $user)
                            <tr>
                                <td data-label="#">{{ $users->firstItem() + $index }}</td>
                                <td data-label="المستخدم">
                                    <div class="d-flex align-items-center">
                                        {{-- PHP block for image remains the same, ensure logic is correct for your paths --}}
                                        @php
                                            $userInstance = $user;
                                            $imageSrc = URL::asset('Dashboard/img/default_avatar.png');
                                            $objectName = $userInstance->display_name ?? ($userInstance->email ?? 'مستخدم');
                                            $roleKey = $userInstance->role_key ?? 'default';
                                            // ... (نفس كود PHP لتحديد $imageSrc من الرد السابق - تأكد من صحته) ...
                                             $imagePaths = [
                                                // 'admin' => 'admin_photos/' . ($userInstance->image->filename ?? null),
                                                'doctor' => 'doctors/' . ($userInstance->image->filename ?? null),
                                                'patient' => 'patients/' . ($userInstance->image->filename ?? null),
                                                'ray_employee' => 'rayEmployees/' . ($userInstance->image->filename ?? null),
                                                'laboratorie_employee' => 'laboratorieEmployees/' . ($userInstance->image->filename ?? null),
                                                'pharmacy_manager'     => 'pharmacy_managers/' . ($userInstance->image->filename ?? null),
                                                'pharmacy_employee'    => 'pharmacyEmployees/' . ($userInstance->image->filename ?? null),
                                            ];
                                            $defaultAvatars = [
                                                'doctor'   => 'Dashboard/img/faces/doctor_default.png', // مثال
                                                'patient'  => 'Dashboard/img/default_patient_avatar.png', //  مثال
                                                'admin'    => 'Dashboard/img/default_admin_avatar.png',
                                                'pharmacy_manager' => 'Dashboard/img/default_avatar.png', // استخدام عام مؤقتا
                                                'pharmacy_employee' => 'Dashboard/img/default_avatar.png', // استخدام عام مؤقتا
                                                'ray_employee' => 'Dashboard/img/default_avatar.png',
                                                'laboratorie_employee' => 'Dashboard/img/default_avatar.png',
                                                'default'  => 'Dashboard/img/default_avatar.png'
                                            ];

                                            if ($userInstance->image && $userInstance->image->filename && isset($imagePaths[$roleKey]) && $imagePaths[$roleKey]) {
                                                $possiblePath = ($roleKey === 'admin' || $roleKey === 'patient' || $roleKey === 'pharmacy_manager' || $roleKey==='pharmacy_employee') ? 'Dashboard/img/' : 'Dashboard/img/';
                                                $fullPath = $possiblePath . $imagePaths[$roleKey];
                                                 if (file_exists(public_path($fullPath))) {
                                                    $imageSrc = asset($fullPath);
                                                 } elseif (isset($defaultAvatars[$roleKey])) {
                                                    $imageSrc = URL::asset($defaultAvatars[$roleKey]);
                                                }
                                            } elseif (isset($defaultAvatars[$roleKey])) {
                                                $imageSrc = URL::asset($defaultAvatars[$roleKey]);
                                            }
                                        @endphp
                                        <img src="{{ $imageSrc }}" alt="{{ $objectName }}" class="user-avatar me-3 flex-shrink-0">
                                        <div class="user-info">
                                            <span class="name">{{ $objectName }}</span>
                                            <span class="email">{{ $userInstance->email ?? '-' }}</span>
                                            @php $phone = $userInstance->phone ?? ($userInstance->Phone ?? null); @endphp
                                            @if ($phone)
                                                <span class="phone"><i class="fas fa-mobile-alt fa-xs"></i> {{ $phone }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td data-label="الدور">
                                    <span class="badge role-badge role-badge-{{ $user->role_key ?? 'default' }}">
                                        <i class="fas @switch($user->role_key)
                                            @case('doctor') fa-user-md @break
                                            @case('patient') fa-user-injured @break
                                            @case('ray_employee') fa-x-ray @break
                                            @case('laboratorie_employee') fa-flask @break
                                            @case('pharmacy_manager') fa-store-alt @break
                                            @case('pharmacy_employee') fa-pills @break
                                            @default fa-user-tag @endswitch me-1">
                                        </i>
                                        {{ $user->role_name ?? 'غير محدد' }}
                                    </span>
                                </td>
                                <td data-label="الحالة">
                                     <span class="badge status-badge {{ $user->actual_status ? 'status-badge-active' : 'status-badge-inactive' }}">
                                         <i class="fas {{ $user->actual_status ? 'fa-check-circle' : 'fa-ban' }} me-1"></i>
                                         {{ $user->status_display }}
                                     </span>
                                </td>
                                <td data-label="القسم/معلومة">
                                    @if ($user->role_key === 'doctor' && isset($user->section_name) && $user->section_name !== 'غير محدد')
                                        <span class="badge bg-light text-dark border"><i class="fas fa-hospital-symbol fa-xs me-1 opacity-75"></i>{{ Str::limit($user->section_name, 20) }}</span>
                                    @elseif($user->role_key === 'patient' && isset($user->Date_Birth))
                                         <span class="badge bg-light text-dark border"><i class="fas fa-birthday-cake fa-xs me-1 opacity-75"></i>{{ \Carbon\Carbon::parse($user->Date_Birth)->age }} سنة</span>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td data-label="تاريخ الانضمام">
                                    <div class="text-dark small fw-500">{{ optional($user->created_at)->translatedFormat('d M Y') ?? '-' }}</div>
                                    <div class="text-muted" style="font-size: 0.7rem;">{{ optional($user->created_at)->diffForHumans() ?? '' }}</div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <i class="fas fa-users-slash fa-3x"></i>
                                        <h4>لا يوجد مستخدمون لعرضهم</h4>
                                        <p>
                                            @if (request()->hasAny(['search', 'role', 'status']))
                                                لم يتم العثور على مستخدمين يطابقون معايير البحث أو الفلترة.
                                            @else
                                                لا يوجد مستخدمون في النظام حاليًا. يمكنك البدء بإضافة مستخدمين جدد.
                                            @endif
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($users->hasPages())
                <div class="pagination-container">
                    {!! $users->appends($request->query())->links('pagination::bootstrap-5') !!}
                </div>
            @endif
        </div>
    </div>

    {{-- Modal لعرض تفاصيل المستخدم --}}
    <div class="modal fade" id="userDetailsModal" tabindex="-1" aria-labelledby="userDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-light py-2">
                    <h5 class="modal-title" id="userDetailsModalLabel"><i class="fas fa-id-card me-2 text-primary"></i>تفاصيل المستخدم</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-2" id="userDetailsContent" style="min-height: 250px;">
                    <div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x text-primary"></i><p class="mt-2">جاري تحميل البيانات...</p></div>
                </div>
                <div class="modal-footer">
                    <a href="#" id="editUserFromModalBtn" class="btn btn-primary" style="display:none;"><i class="fas fa-edit me-1"></i>تعديل الملف الكامل</a>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"><i class="fas fa-times me-1"></i>إغلاق</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @parent
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    {{-- NotifIt --}}
    <script src="{{ URL::asset('dashboard/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('dashboard/plugins/notify/js/notifit-custom.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({ duration: 500, once: true, offset: 20 });

            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl, {boundary: document.body, container:'body', fallbackPlacements: ['top', 'bottom', 'right', 'left']})
            });

            const userDetailsModalEl = document.getElementById('userDetailsModal');
            const editUserFromModalBtn = document.getElementById('editUserFromModalBtn');

            if (userDetailsModalEl && editUserFromModalBtn) {
                const modalBody = document.getElementById('userDetailsContent');
                const modalTitle = document.getElementById('userDetailsModalLabel');

                userDetailsModalEl.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const userJson = button.getAttribute('data-user-details');
                    const editRouteFromButton = button.getAttribute('data-edit-route');

                    modalBody.innerHTML = '<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-2x text-primary"></i><p class="mt-2">جاري تحميل البيانات...</p></div>';
                    editUserFromModalBtn.style.display = 'none'; // إخفاء الزر افتراضيًا

                    if (!userJson) {
                        modalBody.innerHTML = '<p class="text-danger text-center p-3">خطأ: لم يتم العثور على بيانات المستخدم.</p>';
                        return;
                    }

                    try {
                        const user = JSON.parse(userJson);
                        modalTitle.innerHTML = `<i class="fas fa-id-card me-2 text-primary"></i>تفاصيل: ${user.display_name || 'المستخدم'}`;

                        let roleSpecificInfo = '';
                        if(user.role_key === 'doctor' && user.section_name && user.section_name !== 'غير محدد'){
                            roleSpecificInfo = `<tr><th>القسم:</th><td><span class="badge bg-light text-dark border">${user.section_name}</span></td></tr>`;
                        }
                        // Add more role-specific info here if needed

                        let htmlContent = `
                            <div class="row g-3">
                                <div class="col-lg-4 text-center">
                                    <img src="${button.closest('tr').querySelector('.user-avatar')?.src || '{{ URL::asset("Dashboard/img/default_avatar.png") }}'}"
                                         alt="${user.display_name || 'Avatar'}"
                                         class="img-thumbnail rounded-circle mb-2" style="width: 100px; height: 100px; object-fit: cover;">
                                    <h5 class="mb-0">${user.display_name || '-'}</h5>
                                    <p class="text-muted small">${user.role_name || '-'}</p>
                                </div>
                                <div class="col-lg-8">
                                    <table class="table table-sm table-borderless mb-0" style="font-size: 0.9rem;">
                                        <tr><td class="fw-500 text-muted" style="width:35%;">البريد الإلكتروني:</td><td>${user.email || '-'}</td></tr>
                                        <tr><td class="fw-500 text-muted">الهاتف:</td><td>${user.phone || '-'}</td></tr>
                                        <tr><td class="fw-500 text-muted">الحالة:</td><td><span class="badge status-badge ${user.actual_status ? 'status-badge-active' : 'status-badge-inactive'}">${user.status_display || '-'}</span></td></tr>
                                        ${roleSpecificInfo}
                                        <tr><td class="fw-500 text-muted">تاريخ الإنشاء:</td><td>${user.created_at ? new Date(user.created_at).toLocaleDateString('ar-EG-u-nu-latn', { day: 'numeric', month: 'short', year: 'numeric', hour:'numeric', minute:'2-digit' }) : '-'}</td></tr>
                                    </table>
                                </div>
                            </div>
                        `;
                        modalBody.innerHTML = htmlContent;

                        if (editRouteFromButton) {
                            editUserFromModalBtn.href = editRouteFromButton;
                            editUserFromModalBtn.style.display = 'inline-block';
                        }

                    } catch (e) {
                        modalBody.innerHTML = '<p class="text-danger text-center p-3">خطأ في عرض بيانات المستخدم.</p>';
                        console.error('Error parsing user details for modal:', e, userJson);
                    }
                });
            }

            // NotifIt (Alerts aلتي تأتي مع request session)
            @if (session('success'))
                notif({msg: "<div class='d-flex align-items-center p-2'><i class='fas fa-check-circle fa-lg me-2 text-success'></i><div style='font-size: 0.95rem;'>{{ session('success') }}</div></div>", type: "success", position: "top-center", autohide: true, timeout: 5000});
            @endif
            @if (session('error'))
                notif({msg: "<div class='d-flex align-items-center p-2'><i class='fas fa-exclamation-triangle fa-lg me-2 text-danger'></i><div style='font-size: 0.95rem;'>{{ session('error') }}</div></div>",type: "error",position: "top-center",autohide: true,timeout: 7000});
            @endif

        });
    </script>
@endsection
