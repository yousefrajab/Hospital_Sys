{{-- resources/views/Dashboard/ray_employee/profile/show.blade.php --}}
@extends('Dashboard.layouts.master') {{-- استخدام نفس الـ layout --}}

@section('title')
    الملف الشخصي - {{ $employee->name }} {{-- استخدام $employee هنا --}}
@endsection

@section('css')
    @parent
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* --- نسخ نفس أنماط CSS الممتازة من ملف الطبيب --- */
        :root {
            --profile-primary-color: #007bff; /* تغيير الألوان قليلاً لتمييز موظف الأشعة */
            --profile-secondary-color: #17a2b8; /* Teal */
            --profile-gradient: linear-gradient(135deg, var(--profile-primary-color), var(--profile-secondary-color));
            --profile-text-light: #f8f9fa;
            --profile-text-dark: #495057;
            --profile-text-muted: #6c757d;
            --profile-bg-light: #f8f9fa;
            --profile-border-color: #dee2e6;
            --profile-success-color: #28a745;
            --profile-danger-color: #dc3545;
        }

        /* ... (انسخ جميع أنماط CSS من ملف show.blade.php للطبيب هنا) ... */
        .doctor-profile-page { /* يمكنك إعادة تسميته أو إبقائه إذا كانت الأنماط عامة */
            padding-top: 20px;
        }
        .profile-card-modern { /* ... */ }
        .profile-header-modern { /* ... */ }
        .profile-header-modern::before { /* ... */ }
        .profile-avatar-modern { /* ... */ }
        .profile-info-modern { /* ... */ }
        .profile-info-modern h3 { /* ... */ }
        .profile-info-modern .role-info { /* تغيير اسم الكلاس من specialty */
             display: block;
            font-size: 1rem;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 10px;
        }
        .profile-status-modern { /* ... */ }
        .profile-status-modern.active { /* ... */ }
        .profile-status-modern.inactive { /* ... */ }
        .profile-status-modern i { /* ... */ }
        .profile-body-modern { /* ... */ }
        .info-section { /* ... */ }
        .info-section-title { /* ... */ }
        .info-grid { /* ... */ }
        .info-card { /* ... */ }
        .info-card:hover { /* ... */ }
        .info-card .info-icon { /* ... */ }
        .info-card .info-content label { /* ... */ }
        .info-card .info-content p { /* ... */ }
        .profile-actions-modern { /* ... */ }
        .edit-profile-btn { /* ... */ }
        .edit-profile-btn:hover { /* ... */ }
        .edit-profile-btn i { /* ... */ }

    </style>
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                {{-- تغيير مسار التنقل --}}
                <h4 class="content-title mb-0 my-auto">لوحة تحكم موظف الأشعة</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ الملف الشخصي</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    @include('Dashboard.messages_alert') {{-- لعرض رسائل النجاح/الخطأ --}}

    <div class="doctor-profile-page"> {{-- يمكنك تغيير اسم الكلاس إذا أردت --}}
        <div class="profile-card-modern">
            {{-- رأس الملف الشخصي --}}
            <div class="profile-header-modern">
                 {{-- استخدام المتغير $employee لعرض الصورة --}}
                <img class="profile-avatar-modern" alt="{{ $employee->name }}"
                    src="{{ $employee->image ? asset('Dashboard/img/rayEmployees/' . $employee->image->filename) : asset('Dashboard/img/default_avatar.png') }}"> {{-- استخدام صورة افتراضية عامة أو خاصة بموظف الأشعة --}}
                <div class="profile-info-modern">
                    <h3>{{ $employee->name }}</h3>
                    {{-- عرض الدور الوظيفي --}}
                    <span class="role-info"><i class="fas fa-user-cog me-2"></i>موظف قسم الأشعة</span>
                    {{-- عرض الحالة --}}
                     @if(property_exists($employee, 'status')) {{-- التحقق من وجود الخاصية --}}
                        <span class="profile-status-modern {{ $employee->status ? 'active' : 'inactive' }}">
                            <i class="fas fa-circle"></i>
                            {{ $employee->status ? 'نشط' : 'غير نشط' }}
                        </span>
                    @endif
                </div>
            </div>

            {{-- جسم الملف الشخصي --}}
            <div class="profile-body-modern">

                {{-- قسم معلومات الاتصال والمعلومات الأساسية --}}
                <div class="info-section">
                    <h5 class="info-section-title">المعلومات الأساسية والشخصية</h5>
                    <div class="info-grid">
                        {{-- عرض بيانات $employee --}}
                        <div class="info-card">
                            <div class="info-icon"><i class="fas fa-envelope"></i></div>
                            <div class="info-content">
                                <label>البريد الإلكتروني</label>
                                <p>{{ $employee->email }}</p>
                            </div>
                        </div>
                         @if($employee->phone)
                            <div class="info-card">
                                <div class="info-icon" style="background-color: rgba(23, 162, 184, 0.1); color: #117a8b;"><i class="fas fa-phone-alt"></i></div>
                                <div class="info-content">
                                    <label>رقم الهاتف</label>
                                    <p dir="ltr">{{ $employee->phone }}</p>
                                </div>
                            </div>
                        @endif
                        @if($employee->national_id)
                            <div class="info-card">
                                <div class="info-icon" style="background-color: rgba(108, 99, 255, 0.1); color: #6c63ff;"><i class="fas fa-id-card"></i></div>
                                <div class="info-content">
                                    <label>رقم الهوية</label>
                                    <p>{{ $employee->national_id }}</p>
                                </div>
                            </div>
                        @endif
                        <div class="info-card">
                            <div class="info-icon"><i class="fas fa-calendar-alt"></i></div>
                            <div class="info-content">
                                <label>تاريخ الانضمام</label>
                                <p>{{ $employee->created_at ? $employee->created_at->translatedFormat('d M Y') : '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- يمكنك إضافة أقسام أخرى هنا خاصة بمهام موظف الأشعة --}}
                {{-- مثال: قسم طلبات الأشعة الأخيرة أو الإحصائيات --}}
                 {{--
                <div class="info-section">
                    <h5 class="info-section-title">مهام سريعة</h5>
                    <div class="info-grid">
                       <a href="#" class="info-card text-decoration-none">
                           <div class="info-icon" style="background-color: rgba(255, 193, 7, 0.1); color: #b58404;"><i class="fas fa-notes-medical"></i></div>
                           <div class="info-content">
                               <label>طلبات الأشعة</label>
                               <p>عرض الطلبات المعلقة</p>
                           </div>
                       </a>
                        <a href="#" class="info-card text-decoration-none">
                           <div class="info-icon" style="background-color: rgba(23, 162, 184, 0.1); color: #117a8b;"><i class="fas fa-history"></i></div>
                           <div class="info-content">
                               <label>سجل الأشعة</label>
                               <p>عرض الأشعة المنجزة</p>
                           </div>
                       </a>
                    </div>
                </div>
                 --}}

            </div>

             {{-- زر التعديل --}}
             <div class="profile-actions-modern">
                <a href="{{ route('ray_employee.profile.edit') }}" class="btn edit-profile-btn"> {{-- استخدام اسم الـ route الصحيح --}}
                    <i class="fas fa-edit"></i> تعديل الملف الشخصي
                </a>
            </div>

        </div>
    </div>
@endsection

@section('js')
    @parent
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifit-custom.js') }}"></script>
    <script>
        console.log("Ray Employee profile page loaded for: {{ $employee->name }}");

        // عرض رسالة النجاح عند وجودها في الجلسة
        @if (session('success'))
            notif({
                msg: "<i class='fas fa-check-circle me-2'></i> {{ session('success') }}",
                type: "success",
                position: "center",
                timeout: 5000
            });
        @endif
         @if (session('error'))
            notif({
                msg: "<i class='fas fa-exclamation-triangle me-2'></i> {{ session('error') }}",
                type: "error",
                position: "center",
                timeout: 7000
            });
        @endif
    </script>
@endsection
