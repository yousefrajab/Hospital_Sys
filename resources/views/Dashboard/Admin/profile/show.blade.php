{{-- resources/views/Dashboard/Admin/profile/show.blade.php --}}
@extends('Dashboard.layouts.master')

@section('title')
    الملف الشخصي | {{ $admin->name }}
@endsection

{{-- ========================== CSS Section ========================== --}}
@section('css')
    @parent {{-- استيراد CSS الأساسي --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link href="{{ URL::asset('Dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />

    <style>
        /* --- تصميم عصري 2025 --- */
        :root {
            --admin-bg: #f8f9fc;
            --admin-card-bg: #ffffff;
            --admin-text-primary: #1e293b;
            --admin-text-secondary: #64748b;
            --admin-primary: #4f46e5;
            --admin-primary-hover: #4338ca;
            --admin-secondary: #10b981;
            --admin-border-color: #e5e7eb;
            --admin-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --admin-radius-lg: 1rem; /* 16px */
            --admin-radius-md: 0.5rem; /* 8px */
            --admin-transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --admin-bg: #111827;
                --admin-card-bg: #1f2937;
                --admin-text-primary: #f3f4f6;
                --admin-text-secondary: #9ca3af;
                --admin-border-color: #374151;
                --admin-primary: #6366f1;
                --admin-primary-hover: #4f46e5;
                --admin-secondary: #34d399;
                --admin-shadow: 0 4px 6px -1px rgb(255 255 255 / 0.05), 0 2px 4px -2px rgb(255 255 255 / 0.05);
            }
        }

        /* يمكنك أيضًا استخدام كلاس .dark على body لتفعيل الوضع الداكن */
        .dark body { /* Apply dark theme variables if body has .dark class */
            --admin-bg: #111827;
            --admin-card-bg: #1f2937;
            --admin-text-primary: #f3f4f6;
            --admin-text-secondary: #9ca3af;
            --admin-border-color: #374151;
            --admin-primary: #6366f1;
            --admin-primary-hover: #4f46e5;
            --admin-secondary: #34d399;
            --admin-shadow: 0 4px 6px -1px rgb(255 255 255 / 0.05), 0 2px 4px -2px rgb(255 255 255 / 0.05);
        }
        .dark .profile-header-card::before {
             background: radial-gradient(circle, rgba(99, 102, 241, 0.1), transparent 70%);
        }
        /* ... (أضف أي تجاوزات أخرى للوضع الداكن هنا إذا لزم الأمر) ... */


        body {
            background-color: var(--admin-bg);
            color: var(--admin-text-primary);
            font-family: 'Tajawal', sans-serif; /* تأكد من تحميل هذا الخط إذا لم يكن موجودًا */
        }

        .admin-profile-container {
            padding: 2rem 1rem;
            max-width: 1200px;
            margin: auto;
        }

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

        .profile-action-buttons { /* لتجميع أزرار الإجراءات في الهيدر */
            margin-left: auto; /* لدفع الأزرار إلى اليمين في LTR */
            display: flex;
            gap: 0.5rem;
            z-index: 2;
        }
        @media (max-width: 768px) { /* في الشاشات الصغيرة، الأزرار تحت التفاصيل */
            .profile-action-buttons {
                width: 100%;
                justify-content: center;
                margin-top: 1rem;
                margin-left: 0;
            }
        }


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
            content: '';
            position: absolute;
            bottom: 0;
            right: 0; /* للغة العربية */
            /* left: 0; للغة الإنجليزية */
            width: 40px;
            height: 3px;
            background: var(--admin-primary);
            border-radius: 2px;
        }

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

        .btn-profile-action { /* زر التعديل وغيره */
            background-color: var(--admin-primary);
            color: white;
            border: none;
            padding: 0.6rem 1rem;
            border-radius: var(--admin-radius-md);
            font-size: 0.85rem;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: var(--admin-transition);
            text-decoration: none !important;
        }
        .btn-profile-action:hover {
            background-color: var(--admin-primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .btn-profile-action i {
            font-size: 0.9em;
        }


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
            .section-grid {
                grid-template-columns: 1fr;
            }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto"><i class="fas fa-user-shield mr-2" style="color: var(--admin-primary);"></i>الملف الشخصي</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ عرض البيانات</span>
            </div>
        </div>
         {{-- زر التعديل في الهيدر العلوي لسهولة الوصول --}}
        <div class="d-flex my-xl-auto right-content align-items-center">
             <a href="{{ route('admin.profile.edit') }}" class="btn-profile-action">
                <i class="fas fa-edit"></i> تعديل الملف الشخصي
            </a>
        </div>
    </div>
@endsection

@section('content')
    @include('Dashboard.messages_alert') {{-- لعرض رسائل النجاح أو الخطأ بعد التحديث --}}

    <div class="admin-profile-container">

        {{-- 1. بطاقة رأس الملف الشخصي --}}
        <div class="profile-header-card animate__animated animate__fadeInUp">
            <div class="profile-avatar-wrapper">
                {{-- تعديل مسار الصورة ليعكس صور الأدمن --}}
                <img class="profile-avatar" alt="{{ $admin->name }}"
                    src="{{ $admin->image ? asset('Dashboard/img/admin_photos/' . $admin->image->filename) : asset('Dashboard/img/default_avatar.png') }}">
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
            {{-- تم نقل زر التعديل إلى الهيدر الرئيسي للصفحة ليكون أوضح وأكثر سهولة في الوصول إليه --}}
            <div class="profile-edit-action">
                <a href="{{ route('admin.profile.edit') }}" class="edit-profile-btn">
                    <i class="fas fa-pencil-alt"></i> تعديل
                </a>
            </div>
        </div>

        {{-- 2. قسم المعلومات الأساسية --}}
        <div class="mb-5 animate__animated animate__fadeInUp" style="animation-delay: 0.2s;">
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

                {{-- بطاقة رقم الهاتف --}}
                @if($admin->phone)
                <div class="info-card-2025">
                    <div class="info-icon" style="background: linear-gradient(135deg, var(--admin-secondary), #0d9488);"><i class="fas fa-phone-alt"></i></div> {{-- لون مختلف قليلاً --}}
                    <div class="info-content">
                        <label>رقم الهاتف</label>
                        <p>{{ $admin->phone }}</p>
                    </div>
                </div>
                @endif

                {{-- بطاقة تاريخ الانضمام --}}
                <div class="info-card-2025">
                    <div class="info-icon"><i class="fas fa-calendar-check"></i></div>
                    <div class="info-content">
                        <label>تاريخ الانضمام</label>
                        <p>{{ $admin->created_at ? $admin->created_at->translatedFormat('d M Y, H:i A') : ' ' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. قسم الإجراءات السريعة (يمكنك تخصيصه حسب الحاجة) --}}
        <div class="mb-4 animate__animated animate__fadeInUp" style="animation-delay: 0.4s;">
            <h4 class="section-title">إجراءات سريعة</h4>
            <div class="section-grid">
                @if(Route::has('admin.Doctors.index')) {{-- تحقق من وجود الراوت قبل عرضه --}}
                <a href="{{ route('admin.Doctors.index') }}" class="action-card">
                    <span class="action-icon"><i class="fas fa-user-md"></i></span>
                    <h5>إدارة الأطباء</h5>
                    <p>عرض، إضافة، تعديل، أو حذف بيانات الأطباء.</p>
                </a>
                @endif

                @if(Route::has('admin.Patients.index'))
                <a href="{{ route('admin.Patients.index') }}" class="action-card">
                    <span class="action-icon"><i class="fas fa-users"></i></span>
                    <h5>إدارة المرضى</h5>
                    <p>الوصول إلى سجلات المرضى وإدارتها.</p>
                </a>
                @endif

                @if(Route::has('admin.Sections.index'))
                <a href="{{ route('admin.Sections.index') }}" class="action-card">
                    <span class="action-icon"><i class="fas fa-hospital-symbol"></i></span> {{-- أيقونة مختلفة للأقسام --}}
                    <h5>إدارة الأقسام</h5>
                    <p>إدارة أقسام العيادات والخدمات.</p>
                </a>
                @endif

                @if(Route::has('admin.appointments.index'))
                <a href="{{ route('admin.appointments.index') }}" class="action-card">
                    <span class="action-icon"><i class="fas fa-calendar-alt"></i></span>
                    <h5>إدارة المواعيد</h5>
                    <p>عرض وإدارة جميع المواعيد المحجوزة.</p>
                </a>
                @endif

                 @if(Route::has('admin.rooms.index'))
                <a href="{{ route('admin.rooms.index') }}" class="action-card">
                    <span class="action-icon"><i class="side-menu__icon fas fa-door-open"></i></span>
                    <h5>إدارة الغرف</h5>
                    <p>عرض وإدارة جميع الغرف .</p>
                </a>
                @endif

                 @if(Route::has('admin.beds.index'))
                <a href="{{ route('admin.beds.index') }}" class="action-card">
                    <span class="action-icon"><i class="side-menu__icon fas fa-bed"></i></span>
                    <h5>إدارة الأسرة</h5>
                    <p>عرض وإدارة جميع الأسرة</p>
                </a>
                @endif

            </div>
        </div>

    </div> {{-- نهاية .admin-profile-container --}}
@endsection

@section('js')
    @parent
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifit-custom.js') }}"></script> {{-- تأكد من وجود هذا الملف أو إزالته إذا كان Notifit يعمل بدونه --}}

    <script>
        console.log("Admin profile page loaded for: {{ $admin->name }} (ID: {{ $admin->id }})");

        // عرض رسالة النجاح عند وجودها في الجلسة
        @if (session('success'))
            notif({
                msg: "{{ session('success') }}",
                type: "success",
                position: "center", // أو "bottom" أو "top"
                timeout: 5000 // 5 ثواني
            });
        @endif
        @if (session('error')) // لعرض رسائل الخطأ أيضًا إذا استخدمتها
             notif({
                msg: "{{ session('error') }}",
                type: "error",
                position: "center",
                timeout: 7000
            });
        @endif

        // يمكنك إضافة كلاس .dark إلى body هنا إذا أردت تفعيل الوضع الداكن يدويًا
        // document.body.classList.add('dark');
    </script>
@endsection
