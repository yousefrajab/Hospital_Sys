{{-- resources/views/Dashboard/Admin/profile/edit.blade.php --}}
@extends('Dashboard.layouts.master') {{-- تأكد من اسم الـ Layout --}}

{{-- ========================== CSS Section ========================== --}}
@section('css')
    @parent {{-- استيراد CSS الأساسي --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    {{-- NotifIt للإشعارات --}}
    <link href="{{ URL::asset('dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />

    {{-- نفس الأنماط الأساسية من صفحة العرض مع إضافة أنماط الفورم --}}
    <style>
        /* --- المتغيرات والتصميم الأساسي (نفس صفحة العرض) --- */
        :root {
            --admin-bg: #f8f9fc; --admin-card-bg: #ffffff; --admin-text-primary: #1e293b;
            --admin-text-secondary: #64748b; --admin-primary: #4f46e5; --admin-primary-hover: #4338ca;
            --admin-secondary: #10b981; --admin-border-color: #e5e7eb; --admin-success: #22c55e; --admin-danger: #ef4444;
            --admin-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --admin-radius-lg: 1rem; --admin-radius-md: 0.5rem; --admin-transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        @media (prefers-color-scheme: dark) { /* ... (أنماط الوضع الداكن كما هي) ... */ }
        body { background-color: var(--admin-bg); color: var(--admin-text-primary); font-family: 'Tajawal', sans-serif; }
        .admin-profile-container { padding: 2rem 1rem; max-width: 900px; margin: auto; } /* عرض أقل لصفحة التعديل */

        /* --- تصميم بطاقة التعديل --- */
        .edit-profile-card { background: var(--admin-card-bg); border-radius: var(--admin-radius-lg); padding: 2.5rem; box-shadow: var(--admin-shadow); border: 1px solid var(--admin-border-color); animation: fadeInUp 0.5s ease-out; }
        .edit-card-header { margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 1px solid var(--admin-border-color); }
        .edit-card-title { font-size: 1.5rem; font-weight: 700; color: var(--admin-text-primary); display: flex; align-items: center; gap: 0.75rem; }
        .edit-card-title i { color: var(--admin-primary); }

        /* --- تصميم حقول النموذج --- */
        .form-group { margin-bottom: 1.75rem; position: relative; }
        .form-label { display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--admin-text-secondary); font-size: 0.9rem; }
        .form-control {
            display: block; width: 100%; padding: 0.75rem 1rem; font-size: 0.95rem;
            font-weight: 500; line-height: 1.5; color: var(--admin-text-primary);
            background-color: var(--admin-card-bg); background-clip: padding-box;
            border: 1px solid var(--admin-border-color); appearance: none;
            border-radius: var(--admin-radius-md); box-shadow: inset 0 1px 2px rgba(0,0,0,0.075);
            transition: var(--admin-transition);
        }
        .form-control:focus { border-color: var(--admin-primary); outline: 0; box-shadow: inset 0 1px 2px rgba(0,0,0,0.075), 0 0 0 0.25rem rgba(79, 70, 229, 0.25); }
        /* أيقونة العين لكلمة المرور */
        .password-wrapper { position: relative; }
        .toggle-password-btn { position: absolute; top: 50%; left: 1rem; transform: translateY(-50%); background: none; border: none; color: var(--admin-text-secondary); cursor: pointer; padding: 0.5rem; }
        .form-control[type="password"] { padding-left: 3rem; } /* مساحة للزر */

        /* --- أزرار الحفظ والإلغاء --- */
        .form-actions { margin-top: 2.5rem; display: flex; justify-content: flex-end; gap: 0.75rem; }
        .btn-submit, .btn-cancel {
            padding: 0.6rem 1.25rem; border-radius: var(--admin-radius-md); font-weight: 600;
            font-size: 0.9rem; transition: var(--admin-transition); border: 1px solid transparent;
            display: inline-flex; align-items: center; gap: 0.4rem;
        }
        .btn-submit { background-color: var(--admin-primary); color: white; box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1); }
        .btn-submit:hover { background-color: var(--admin-primary-hover); box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1); transform: translateY(-1px); }
        .btn-cancel { background-color: var(--admin-card-bg); color: var(--admin-text-secondary); border-color: var(--admin-border-color); }
        .btn-cancel:hover { background-color: #f9fafb; border-color: #d1d5db; } /* تعديل بسيط لخلفية hover */
         @media (prefers-color-scheme: dark) { .btn-cancel:hover { background-color: #374151; border-color: #4b5563; } }

         /* أنماط التحقق */
        .was-validated .form-control:invalid, .form-control.is-invalid { border-color: var(--admin-danger) !important; }
        .was-validated .form-control:valid, .form-control.is-valid { border-color: var(--admin-success) !important; }
        .invalid-feedback { display: none; width: 100%; margin-top: 0.25rem; font-size: .875em; color: var(--admin-danger); }
        .was-validated .form-control:invalid ~ .invalid-feedback { display: block; }

         /* --- Responsive --- */
         @media (max-width: 576px) { .p-6 { padding: 1.5rem; } .form-actions { flex-direction: column; gap: 0.5rem; } .btn-submit, .btn-cancel { width: 100%; } }
    </style>
@endsection

@section('title')
    تعديل الملف الشخصي للمدير
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto">الملف الشخصي</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تعديل البيانات</span>
            </div>
        </div>
         <div class="d-flex my-xl-auto right-content">
             <a href="{{ route('admin.profile.show') }}" class="btn btn-outline-secondary btn-sm"> {{-- اسم مسار العرض الصحيح --}}
                 <i class="fas fa-user me-1"></i> عرض الملف الشخصي
            </a>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

{{-- ====================== HTML Content Section ===================== --}}
@section('content')
    @include('Dashboard.messages_alert')

    <div class="admin-profile-container">
        <div class="edit-profile-card">
            <div class="edit-card-header">
                <h3 class="edit-card-title">
                    <i class="fas fa-user-pen"></i>
                    تعديل بيانات الملف الشخصي
                </h3>
            </div>

            <form action="{{ route('admin.profile.update') }}" method="POST" class="needs-validation" novalidate> {{-- استخدام اسم المسار الصحيح --}}
                @method('PUT')
                @csrf

                <div class="row">
                    {{-- الاسم --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name" class="form-label">الاسم الكامل</label>
                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $admin->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- البريد الإلكتروني --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email" class="form-label">البريد الإلكتروني</label>
                            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $admin->email) }}" required>
                             @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- كلمة المرور الحالية (اختياري لكن موصى به للتغيير) --}}
                    <div class="col-md-12">
                         <hr class="my-4">
                         <p class="text-muted small mb-3">لتغيير كلمة المرور، أدخل كلمة المرور الحالية والجديدة:</p>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group password-wrapper">
                            <label for="current_password" class="form-label">كلمة المرور الحالية</label>
                            <input type="password" id="current_password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" autocomplete="current-password">
                             <button type="button" class="toggle-password-btn" onclick="togglePasswordVisibility('current_password')">
                                <i class="fas fa-eye"></i>
                            </button>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    {{-- كلمة المرور الجديدة --}}
                    <div class="col-md-6">
                        <div class="form-group password-wrapper">
                            <label for="new_password" class="form-label">كلمة المرور الجديدة</label>
                            <input type="password" id="new_password" name="new_password" class="form-control @error('new_password') is-invalid @enderror" autocomplete="new-password">
                             <button type="button" class="toggle-password-btn" onclick="togglePasswordVisibility('new_password')">
                                <i class="fas fa-eye"></i>
                            </button>
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    {{-- تأكيد كلمة المرور الجديدة --}}
                     <div class="col-md-6">
                        <div class="form-group password-wrapper">
                            <label for="new_password_confirmation" class="form-label">تأكيد كلمة المرور الجديدة</label>
                            <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-control" autocomplete="new-password">
                             <button type="button" class="toggle-password-btn" onclick="togglePasswordVisibility('new_password_confirmation')">
                                <i class="fas fa-eye"></i>
                            </button>
                             {{-- لا يوجد error مباشر هنا، خطأ عدم التطابق يأتي من قاعدة التحقق confirmed --}}
                        </div>
                    </div>

                </div> {{-- نهاية .row --}}

                {{-- أزرار الحفظ والإلغاء --}}
                <div class="form-actions">
                    <a href="{{ route('admin.profile.show') }}" class="btn-custom btn-cancel"> {{-- اسم مسار العرض الصحيح --}}
                         <span class="btn-content"><i class="fas fa-times me-1"></i> <span>إلغاء</span></span>
                    </a>
                    <button type="submit" class="btn-custom btn-submit">
                        <span class="btn-content"><i class="fas fa-save me-1"></i> <span>حفظ التغييرات</span></span>
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection

{{-- ====================== JavaScript Section ===================== --}}
@section('js')
    @parent {{-- استيراد JS الأساسي --}}
    {{-- NotifIt للإشعارات --}}
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifIt.js') }}"></script>

    <script>
        // --- تبديل رؤية كلمة المرور ---
        function togglePasswordVisibility(inputId) {
            const passwordInput = document.getElementById(inputId);
            const button = passwordInput.nextElementSibling; // افتراض أن الزر يأتي مباشرة بعد الحقل
            const icon = button ? button.querySelector('i') : null;

            if (passwordInput && icon) {
                if (passwordInput.type === "password") {
                    passwordInput.type = "text";
                    icon.classList.remove("fa-eye");
                    icon.classList.add("fa-eye-slash");
                } else {
                    passwordInput.type = "password";
                    icon.classList.remove("fa-eye-slash");
                    icon.classList.add("fa-eye");
                }
            }
        }

        // --- تفعيل Bootstrap Validation ---
        (function () {
            'use strict';
            var forms = document.querySelectorAll('.needs-validation');
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();

        // --- (اختياري) إظهار إشعار NotifIt عند النجاح ---
        @if(session('success'))
            if (typeof notif !== 'undefined') {
                notif({ msg: "{{ session('success') }}", type: "success", position: "center", timeout: 5000 });
            } else {
                alert("{{ session('success') }}"); // حل بديل
            }
        @endif

    </script>
@endsection
