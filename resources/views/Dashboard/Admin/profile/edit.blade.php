{{-- resources/views/Dashboard/Admin/profile/edit.blade.php --}}
@extends('Dashboard.layouts.master') {{-- تأكد من اسم الـ Layout --}}

@section('title')
    تعديل الملف الشخصي
@endsection

{{-- ========================== CSS Section ========================== --}}
@section('css')
    @parent {{-- استيراد CSS الأساسي --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link href="{{ URL::asset('Dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />

    <style>
        :root {
            --admin-bg: #f8f9fc; --admin-card-bg: #ffffff; --admin-text-primary: #1e293b;
            --admin-text-secondary: #64748b; --admin-primary: #4f46e5; --admin-primary-hover: #4338ca;
            --admin-secondary: #10b981; --admin-border-color: #e5e7eb; --admin-success: #22c55e; --admin-danger: #ef4444;
            --admin-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --admin-radius-lg: 1rem; --admin-radius-md: 0.5rem; --admin-transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        @media (prefers-color-scheme: dark) {
            :root {
                --admin-bg: #111827; --admin-card-bg: #1f2937; --admin-text-primary: #f3f4f6;
                --admin-text-secondary: #9ca3af; --admin-border-color: #374151; --admin-primary: #6366f1;
                --admin-primary-hover: #4f46e5; --admin-secondary: #34d399;
                --admin-shadow: 0 4px 6px -1px rgb(255 255 255 / 0.05), 0 2px 4px -2px rgb(255 255 255 / 0.05);
            }
        }
        /* ... (بقية أنماط الوضع الداكن والأساسي كما هي) ... */
        body { background-color: var(--admin-bg); color: var(--admin-text-primary); font-family: 'Tajawal', sans-serif; }
        .admin-profile-container { padding: 2rem 1rem; max-width: 900px; margin: auto; }

        .edit-profile-card { background: var(--admin-card-bg); border-radius: var(--admin-radius-lg); padding: 2.5rem; box-shadow: var(--admin-shadow); border: 1px solid var(--admin-border-color); animation: fadeInUp 0.5s ease-out; }
        .edit-card-header { margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 1px solid var(--admin-border-color); }
        .edit-card-title { font-size: 1.5rem; font-weight: 700; color: var(--admin-text-primary); display: flex; align-items: center; gap: 0.75rem; }
        .edit-card-title i { color: var(--admin-primary); }

        /* تصميم رفع الصورة */
        .profile-avatar-edit-container { position: relative; width: 130px; height: 130px; margin: 0 auto 2rem auto; }
        .profile-avatar-edit-container img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; border: 3px solid var(--admin-card-bg); box-shadow: 0 0 0 4px var(--admin-primary), 0 4px 12px rgba(0,0,0,0.15); }
        .profile-avatar-edit-container .upload-btn-edit {
            position: absolute; bottom: 0px; right: 0px; width: 35px; height: 35px; background-color: var(--admin-secondary);
            color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center;
            cursor: pointer; border: 2px solid var(--admin-card-bg); box-shadow: 0 2px 6px rgba(0,0,0,0.2); transition: var(--admin-transition);
        }
        .profile-avatar-edit-container .upload-btn-edit:hover { background-color: #0d9488; transform: scale(1.1); }
        .profile-avatar-edit-container input[type="file"] { display: none; }


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
        .password-wrapper { position: relative; }
        .toggle-password-btn { position: absolute; top: 50%; left: 1rem; transform: translateY(-50%); background: none; border: none; color: var(--admin-text-secondary); cursor: pointer; padding: 0.5rem; z-index: 3; }
        .form-control[type="password"] { padding-left: 3.5rem; }

        .form-actions { margin-top: 2.5rem; display: flex; justify-content: flex-end; gap: 0.75rem; }
        .btn-submit, .btn-cancel {
            padding: 0.6rem 1.25rem; border-radius: var(--admin-radius-md); font-weight: 600;
            font-size: 0.9rem; transition: var(--admin-transition); border: 1px solid transparent;
            display: inline-flex; align-items: center; gap: 0.4rem; text-decoration: none;
        }
        .btn-submit { background-color: var(--admin-primary); color: white; box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1); }
        .btn-submit:hover { background-color: var(--admin-primary-hover); box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1); transform: translateY(-1px); }
        .btn-cancel { background-color: transparent; color: var(--admin-text-secondary); border-color: var(--admin-border-color); }
        .btn-cancel:hover { background-color: #f9fafb; border-color: #d1d5db; }
         @media (prefers-color-scheme: dark) { .btn-cancel:hover { background-color: #374151; border-color: #4b5563; } }

        .was-validated .form-control:invalid, .form-control.is-invalid { border-color: var(--admin-danger) !important; background-image: none !important; /* لإزالة أيقونة Bootstrap الافتراضية */ }
        .was-validated .form-control:valid, .form-control.is-valid { border-color: var(--admin-success) !important; background-image: none !important; }
        .invalid-feedback { display: none; width: 100%; margin-top: 0.25rem; font-size: .875em; color: var(--admin-danger); }
        .is-invalid ~ .invalid-feedback, .was-validated :invalid ~ .invalid-feedback { display: block; } /* لعرض الخطأ مع is-invalid */


         @media (max-width: 576px) { .edit-profile-card { padding: 1.5rem; } .form-actions { flex-direction: column; gap: 0.5rem; } .btn-submit, .btn-cancel { width: 100%; justify-content: center;} }
    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto"><i class="fas fa-user-cog mr-2" style="color:var(--admin-primary);"></i>الملف الشخصي</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تعديل البيانات</span>
            </div>
        </div>
         <div class="d-flex my-xl-auto right-content">
             <a href="{{ route('admin.profile.show') }}" class="btn btn-outline-secondary btn-sm" style="border-radius: var(--admin-radius-md);">
                 <i class="fas fa-eye me-1"></i> عرض الملف الشخصي
            </a>
        </div>
    </div>
@endsection

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

            <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate autocomplete="off">
                @method('PATCH') {{-- أو PUT، يجب أن يتطابق مع الـ Route definition --}}
                @csrf

                <!-- Profile Image Upload -->
                <div class="profile-avatar-edit-container">
                    <img id="profile_photo_preview_edit"
                         src="{{ $admin->image ? asset('Dashboard/img/admin_photos/' . $admin->image->filename) : asset('Dashboard/img/default_avatar.png') }}"
                         alt="الصورة الشخصية">
                    <label for="profile_photo_input_edit" class="upload-btn-edit" title="تغيير الصورة">
                        <i class="fas fa-camera"></i>
                    </label>
                    <input type="file" name="photo" id="profile_photo_input_edit" accept="image/*"
                           onchange="previewAdminImage(event, 'profile_photo_preview_edit')">
                </div>
                @error('photo')
                    <div class="text-center mb-3"><span class="text-danger small">{{ $message }}</span></div>
                @enderror


                <div class="row">
                    {{-- الاسم --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name_edit" class="form-label">الاسم الكامل <span class="text-danger">*</span></label>
                            <input type="text" id="name_edit" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $admin->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- البريد الإلكتروني --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email_edit" class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                            <input type="email" id="email_edit" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $admin->email) }}" required>
                             @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- رقم الهاتف --}}
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="phone_edit" class="form-label">رقم الهاتف</label>
                            <input type="tel" id="phone_edit" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone', $admin->phone) }}" pattern="^05\d{8}$" title="يجب أن يبدأ رقم الجوال بـ 05 ويتكون من 10 أرقام" maxlength="10" placeholder="05xxxxxxxx">
                            <small class="form-text text-muted">اختياري. مثال: 0512345678</small>
                             @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>


                    {{-- تغيير كلمة المرور --}}
                    <div class="col-md-12">
                         <hr class="my-4">
                         <p class="text-muted small mb-3">لتغيير كلمة المرور، أدخل كلمة المرور الحالية ثم الجديدة وتأكيدها. اتركه فارغًا لعدم التغيير.</p>
                    </div>
                    <div class="col-md-12"> {{-- كلمة المرور الحالية --}}
                        <div class="form-group password-wrapper">
                            <label for="current_password_edit" class="form-label">كلمة المرور الحالية</label>
                            <input type="password" id="current_password_edit" name="current_password" class="form-control @error('current_password') is-invalid @enderror" autocomplete="current-password">
                             <button type="button" class="toggle-password-btn" data-target="current_password_edit">
                                <i class="fas fa-eye"></i>
                            </button>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6"> {{-- كلمة المرور الجديدة --}}
                        <div class="form-group password-wrapper">
                            <label for="password_edit" class="form-label">كلمة المرور الجديدة</label> {{-- تم تغيير الاسم إلى password ليتطابق مع FormRequest --}}
                            <input type="password" id="password_edit" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password">
                             <button type="button" class="toggle-password-btn" data-target="password_edit">
                                <i class="fas fa-eye"></i>
                            </button>
                            @error('password') {{-- كان new_password، تم تغييره إلى password --}}
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                     <div class="col-md-6"> {{-- تأكيد كلمة المرور الجديدة --}}
                        <div class="form-group password-wrapper">
                            <label for="password_confirmation_edit" class="form-label">تأكيد كلمة المرور الجديدة</label>
                            <input type="password" id="password_confirmation_edit" name="password_confirmation" class="form-control" autocomplete="new-password">
                             <button type="button" class="toggle-password-btn" data-target="password_confirmation_edit">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                </div> {{-- نهاية .row --}}

                <div class="form-actions">
                    <a href="{{ route('admin.profile.show') }}" class="btn btn-cancel">
                         <i class="fas fa-times me-1"></i> إلغاء
                    </a>
                    <button type="submit" class="btn btn-submit">
                        <i class="fas fa-save me-1"></i> حفظ التغييرات
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection

@section('js')
    @parent
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifit-custom.js') }}"></script>

    <script>
        // Image Preview Function
        if (typeof window.previewAdminImage !== 'function') {
            window.previewAdminImage = function(event, outputId) {
                const output = document.getElementById(outputId);
                if (event.target.files && event.target.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        output.src = e.target.result;
                    }
                    reader.readAsDataURL(event.target.files[0]);
                }
            };
        }

        // Password Toggle Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const togglePasswordButtons = document.querySelectorAll('.toggle-password-btn');
            togglePasswordButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    const targetInputId = this.dataset.target; // استخدام data-target بدلاً من data-target-input
                    const passwordInput = document.getElementById(targetInputId);
                    const icon = this.querySelector('i');

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
                });
            });

            // Bootstrap Validation
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

            // Notify on success/error message from session
            @if (session('success'))
                notif({ msg: "{{ session('success') }}", type: "success", position: "center", timeout: 5000 });
            @endif
            @if (session('error')) // لعرض رسائل الخطأ العامة من الـ controller
                notif({ msg: "{{ session('error') }}", type: "error", position: "center", timeout: 7000 });
            @endif
        });
    </script>
@endsection
