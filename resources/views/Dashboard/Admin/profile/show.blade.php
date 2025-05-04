{{-- resources/views/Dashboard/Admin/profile/show.blade.php --}}
@extends('Dashboard.layouts.master')

@section('title')
    الملف الشخصي للمدير | {{ $admin->name }}
@endsection

{{-- ========================== CSS Section ========================== --}}
@section('css')
    @parent {{-- استيراد CSS الأساسي --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    {{-- NotifIt إذا كانت ستستخدم للإشعارات --}}
    <link href="{{ URL::asset('dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />

    <style>
        /* --- تصميم عصري 2025 --- */
        :root {
            --admin-bg: #f8f9fc;
            /* خلفية الصفحة الرئيسية */
            --admin-card-bg: #ffffff;
            /* خلفية البطاقات */
            --admin-text-primary: #1e293b;
            /* لون النص الأساسي */
            --admin-text-secondary: #64748b;
            /* لون النص الثانوي */
            --admin-primary: #4f46e5;
            /* لون أساسي (بنفسجي) */
            --admin-primary-hover: #4338ca;
            --admin-secondary: #10b981;
            /* لون ثانوي (أخضر زمردي) */
            --admin-border-color: #e5e7eb;
            /* لون الحدود */
            --admin-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --admin-radius-lg: 1rem;
            /* 16px */
            --admin-radius-md: 0.5rem;
            /* 8px */
            --admin-transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* === Dark Mode Styles === */
        @media (prefers-color-scheme: dark) {
            :root {
                --admin-bg: #111827;
                /* خلفية داكنة */
                --admin-card-bg: #1f2937;
                /* خلفية بطاقة داكنة */
                --admin-text-primary: #f3f4f6;
                /* نص فاتح */
                --admin-text-secondary: #9ca3af;
                /* نص ثانوي أفتح */
                --admin-border-color: #374151;
                /* حدود أغمق */
                --admin-primary: #6366f1;
                /* لون أساسي أفتح قليلاً */
                --admin-primary-hover: #4f46e5;
                --admin-secondary: #34d399;
                /* لون ثانوي أفتح قليلاً */
                --admin-shadow: 0 4px 6px -1px rgb(255 255 255 / 0.05), 0 2px 4px -2px rgb(255 255 255 / 0.05);
            }
        }

        /* يمكنك أيضًا استخدام كلاس .dark على body لتفعيل الوضع الداكن */
        .dark {
            /* نفس متغيرات @media (prefers-color-scheme: dark) */
        }

        /* === Layout === */
        body {
            background-color: var(--admin-bg);
            color: var(--admin-text-primary);
            font-family: 'Tajawal', sans-serif;
        }

        .admin-profile-container {
            padding: 2rem 1rem;
            max-width: 1200px;
            margin: auto;
        }

        /* === Profile Header Card === */
        .profile-header-card {
            background: var(--admin-card-bg);
            border-radius: var(--admin-radius-lg);
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: var(--admin-shadow);
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 1.5rem;
            border: 1px solid var(--admin-border-color);
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.5s ease-out;
        }

        /* تأثير زخرفي بسيط في الخلفية */
        .profile-header-card::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 150px;
            height: 150px;
            background: radial-gradient(circle, rgba(79, 70, 229, 0.05), transparent 70%);
            opacity: 0.6;
            z-index: 0;
            pointer-events: none;
            transition: var(--admin-transition);
        }

        @media (prefers-color-scheme: dark) {
            .profile-header-card::before {
                background: radial-gradient(circle, rgba(99, 102, 241, 0.1), transparent 70%);
            }
        }

        .profile-avatar-wrapper {
            flex-shrink: 0;
            position: relative;
            z-index: 1;
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--admin-card-bg);
            /* فصل عن الخلفية */
            box-shadow: 0 0 0 4px var(--admin-primary), 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .profile-details {
            flex-grow: 1;
            position: relative;
            z-index: 1;
        }

        .profile-details h2 {
            font-size: 1.75rem;
            font-weight: 700;
            margin: 0 0 0.25rem;
            color: var(--admin-text-primary);
        }

        .profile-details .role-badge {
            display: inline-block;
            background-color: var(--admin-primary);
            color: white;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.2rem 0.6rem;
            border-radius: var(--admin-radius-md);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .profile-details .last-login {
            font-size: 0.8rem;
            color: var(--admin-text-secondary);
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .profile-details .last-login i {
            font-size: 0.9em;
        }

        .profile-edit-action {
            position: absolute;
            top: 1rem;
            right: 1rem;
            z-index: 2;
        }

        .edit-profile-btn {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
            /* تأثير زجاجي */
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: var(--admin-text-primary);
            padding: 0.5rem 0.8rem;
            border-radius: var(--admin-radius-md);
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            transition: var(--admin-transition);
        }

        .edit-profile-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            color: var(--admin-primary);
        }

        @media (prefers-color-scheme: dark) {
            .edit-profile-btn {
                background: rgba(0, 0, 0, 0.1);
                border: 1px solid rgba(255, 255, 255, 0.1);
                color: var(--admin-text-primary);
            }

            .edit-profile-btn:hover {
                background: rgba(0, 0, 0, 0.2);
                color: var(--admin-primary);
            }
        }

        /* === Sections (Info & Actions) === */
        .section-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: var(--admin-text-primary);
            position: relative;
            padding-bottom: 0.5rem;
        }

        .section-title::after {
            /* خط سفلي مميز */
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            width: 40px;
            height: 3px;
            background: var(--admin-primary);
            border-radius: 2px;
        }

        /* === Info Card === */
        .info-card-2025 {
            background: var(--admin-card-bg);
            border-radius: var(--admin-radius-md);
            padding: 1.25rem;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            border: 1px solid var(--admin-border-color);
            box-shadow: var(--admin-shadow);
            transition: var(--admin-transition);
        }

        .info-card-2025:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        }

        .info-card-2025 .info-icon {
            flex-shrink: 0;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, var(--admin-primary), var(--admin-secondary));
            color: white;
            font-size: 1.1rem;
        }

        .info-card-2025 .info-content label {
            display: block;
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--admin-text-secondary);
            margin-bottom: 0.2rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-card-2025 .info-content p {
            margin: 0;
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--admin-text-primary);
            word-break: break-word;
        }

        /* === Action Card === */
        .action-card {
            background: var(--admin-card-bg);
            border-radius: var(--admin-radius-md);
            padding: 1.5rem;
            text-align: center;
            border: 1px solid var(--admin-border-color);
            box-shadow: var(--admin-shadow);
            transition: var(--admin-transition);
            display: block;
            color: var(--admin-text-primary);
            text-decoration: none !important;
            /* إزالة أي خط تحت الرابط */
        }

        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px -5px rgb(0 0 0 / 0.1), 0 5px 8px -6px rgb(0 0 0 / 0.1);
            border-color: var(--admin-primary);
        }

        .action-card .action-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
            display: block;
            color: var(--admin-primary);
            transition: var(--admin-transition);
        }

        .action-card:hover .action-icon {
            transform: scale(1.1);
        }

        .action-card h5 {
            font-size: 1rem;
            font-weight: 600;
            margin: 0;
            color: var(--admin-text-primary);
        }

        .action-card p {
            font-size: 0.85rem;
            color: var(--admin-text-secondary);
            margin-top: 0.25rem;
            line-height: 1.4;
        }

        /* === Responsive === */
        @media (max-width: 768px) {
            .admin-profile-container {
                padding: 1rem;
            }

            .profile-header-card {
                padding: 1.5rem;
                flex-direction: column;
                text-align: center;
            }

            .profile-avatar {
                width: 70px;
                height: 70px;
            }

            .profile-details h2 {
                font-size: 1.5rem;
            }

            .profile-edit-action {
                top: 0.5rem;
                right: 0.5rem;
            }

            .edit-profile-btn {
                padding: 0.4rem 0.6rem;
                font-size: 0.75rem;
            }

            .section-grid {
                grid-template-columns: 1fr;
                /* عمود واحد */
            }
        }

        /* === Animations === */
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

        .animate__fadeInUp {
            animation-name: fadeInUp;
        }
    </style>
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto">لوحة التحكم</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ الملف الشخصي</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            {{-- أزرار للانتقال بين قوائم المواعيد --}}

            <a href="{{ route('admin.profile.edit') }}" class="btn btn-outline-success btn-sm mr-2"><i
                    class="fas fa-check me-1"></i>تعديل البيانات</a>

        </div>
        
    </div>
    <!-- breadcrumb -->
@endsection
@section('content')
    @include('Dashboard.messages_alert')

    <div class="admin-profile-container">

        {{-- 1. بطاقة رأس الملف الشخصي --}}
        <div class="profile-header-card animate__animated animate__fadeInUp">
            <div class="profile-avatar-wrapper">
                <img class="profile-avatar" alt="{{ $admin->name }}"
                    src="{{ Auth::user()->image ? asset('Dashboard/img/doctors/' . Auth::user()->image->filename) : asset('Dashboard/img/faces/doctor_default.png') }}">
            </div>
            <div class="profile-details">
                <h2>{{ $admin->name }}</h2>
                <span class="role-badge">مدير النظام</span>
                {{-- (اختياري) إضافة آخر تسجيل دخول --}}
                @if ($admin->last_login_at)
                    <p class="last-login">
                        <i class="fas fa-clock"></i> آخر تسجيل دخول: {{ $admin->last_login_at->diffForHumans() }}
                    </p>
                @endif
            </div>
            <div class="profile-edit-action">
                <a href="#" class="edit-profile-btn">
                </a>
            </div>
        </div>

        {{-- 2. قسم المعلومات الأساسية --}}
        <div class="mb-5">
            <h4 class="section-title">المعلومات الأساسية</h4>
            <div class="section-grid">
                {{-- بطاقة البريد الإلكتروني --}}
                <div class="info-card-2025">
                    <div class="info-icon"><i class="fas fa-envelope"></i></div>
                    <div class="info-content">
                        <label>البريد الإلكتروني</label>
                        <p>{{ $admin->email }}</p>
                    </div>
                </div>
                {{-- بطاقة تاريخ الانضمام --}}
                <div class="info-card-2025">
                    <div class="info-icon"><i class="fas fa-calendar-check"></i></div>
                    <div class="info-content">
                        <label>تاريخ الانضمام</label>
                        <p>{{ $admin->created_at ? $admin->created_at->translatedFormat('d M Y') : '-' }}</p>
                    </div>
                </div>
                {{-- (اختياري) بطاقة حالة الحساب --}}
                {{-- <div class="info-card-2025">
                     <div class="info-icon" style="background: var(--admin-secondary);"><i class="fas fa-check-circle"></i></div>
                     <div class="info-content">
                         <label>حالة الحساب</label>
                         <p style="color: var(--admin-secondary);">نشط</p>
                     </div>
                 </div> --}}
            </div>
        </div>

        {{-- 3. قسم الإجراءات السريعة (الأهم للأدمن) --}}
        <div class="mb-4">
            <h4 class="section-title">إجراءات سريعة</h4>
            <div class="section-grid">
                {{-- مثال: بطاقة إدارة الأطباء --}}
                <a href="{{ route('admin.Doctors.index') }}" class="action-card"> {{-- تأكد من اسم الراوت --}}
                    <span class="action-icon"><i class="fas fa-user-md"></i></span>
                    <h5>إدارة الأطباء</h5>
                    <p>عرض، إضافة، تعديل، أو حذف بيانات الأطباء.</p>
                </a>
                {{-- مثال: بطاقة إدارة المرضى --}}
                <a href="{{ route('admin.Patients.index') }}" class="action-card"> {{-- تأكد من اسم الراوت --}}
                    <span class="action-icon"><i class="fas fa-users"></i></span>
                    <h5>إدارة المرضى</h5>
                    <p>الوصول إلى سجلات المرضى وإدارتها.</p>
                </a>
                {{-- مثال: بطاقة إدارة الأقسام --}}
                <a href="{{ route('admin.Sections.index') }}" class="action-card"> {{-- تأكد من اسم الراوت --}}
                    <span class="action-icon"><i class="fas fa-building"></i></span>
                    <h5>إدارة الأقسام</h5>
                    <p>إدارة أقسام المستشفى المختلفة.</p>
                </a>
                {{-- مثال: بطاقة إدارة المواعيد --}}
                <a href="{{ route('admin.appointments.index') }}" class="action-card"> {{-- تأكد من اسم الراوت --}}
                    <span class="action-icon"><i class="fas fa-calendar-alt"></i></span>
                    <h5>إدارة المواعيد</h5>
                    <p>عرض وإدارة جميع المواعيد المحجوزة.</p>
                </a>
                {{-- مثال: بطاقة الإعدادات (عامة) --}}
                {{-- <a href="#" class="action-card">
                     <span class="action-icon"><i class="fas fa-cogs"></i></span>
                     <h5>إعدادات النظام</h5>
                     <p>الوصول إلى إعدادات التطبيق العامة.</p>
                 </a> --}}
            </div>
        </div>

    </div> {{-- نهاية .admin-profile-container --}}
@endsection

{{-- ====================== JavaScript Section ===================== --}}
@section('js')
    @parent {{-- استيراد JS الأساسي --}}
    {{-- NotifIt إذا كانت ستستخدم للإشعارات --}}
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifIt.js') }}"></script>

    <script>
        console.log("Modern Admin profile page loaded for: {{ $admin->name }}");

        // (اختياري) تفعيل Tooltips إذا استخدمت عناصر تحتاجها
        // var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        // var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        //   return new bootstrap.Tooltip(tooltipTriggerEl)
        // })

        // (اختياري) أي تفاعلات JS أخرى
        // مثال: زر التحرير يعرض رسالة "قريباً" باستخدام NotifIt
        document.querySelector('.edit-profile-btn')?.addEventListener('click', function(e) {
            e.preventDefault(); // منع الانتقال إذا كان رابطاً
            if (typeof notif !== 'undefined') {
                notif({
                    msg: "ميزة تعديل الملف الشخصي ستتوفر قريباً!",
                    type: "info",
                    position: "center",
                    timeout: 3000
                });
            } else {
                alert('ميزة تعديل الملف الشخصي ستتوفر قريباً!');
            }
        });
    </script>
@endsection
