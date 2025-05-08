{{-- resources/views/Dashboard/ray_employee/profile/edit.blade.php --}}
@extends('Dashboard.layouts.master')

@section('title')
    تعديل الملف الشخصي - {{ $employee->name }}
@endsection

@section('css')
    @parent
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="{{ URL::asset('Dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    <style>
        :root {
            --ray-primary: #5e72e4;
            --ray-secondary: #11cdef;
            --ray-gradient: linear-gradient(135deg, var(--ray-primary), var(--ray-secondary));
            --ray-dark: #32325d;
            --ray-light: #f7fafc;
            --ray-success: #2dce89;
            --ray-danger: #f5365c;
            --ray-warning: #fb6340;
            --ray-info: #11cdef;
            --ray-text: #525f7f;
            --ray-border: rgba(0, 0, 0, 0.1);
            --ray-shadow: 0 15px 35px rgba(50, 50, 93, 0.1), 0 5px 15px rgba(0, 0, 0, 0.07);
            --ray-radius: 0.375rem;
            --ray-radius-lg: 0.5rem;
            --ray-transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        .ray-edit-container {
            perspective: 1000px;
            padding: 2rem 0;
        }

        .ray-edit-card {
            background: white;
            border-radius: var(--ray-radius-lg);
            box-shadow: var(--ray-shadow);
            overflow: hidden;
            transition: var(--ray-transition);
            transform-style: preserve-3d;
            position: relative;
        }

        .ray-edit-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(50, 50, 93, 0.15), 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .ray-edit-header {
            background: var(--ray-gradient);
            padding: 2rem;
            text-align: center;
            position: relative;
            color: white;
            clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);
        }

        .ray-edit-header h2 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .ray-edit-body {
            padding: 2rem;
        }

        .ray-form-group {
            margin-bottom: 1.5rem;
        }

        .ray-form-label {
            display: block;
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--ray-text);
            margin-bottom: 0.5rem;
        }

        .ray-form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            border: 1px solid var(--ray-border);
            border-radius: var(--ray-radius);
            transition: var(--ray-transition);
            background-color: white;
        }

        .ray-form-control:focus {
            border-color: var(--ray-primary);
            box-shadow: 0 0 0 3px rgba(94, 114, 228, 0.2);
            outline: none;
        }

        .ray-avatar-edit {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .ray-avatar-preview {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--ray-border);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .ray-upload-btn {
            padding: 0.5rem 1rem;
            background: white;
            border: 1px dashed var(--ray-primary);
            color: var(--ray-primary);
            border-radius: var(--ray-radius);
            cursor: pointer;
            transition: var(--ray-transition);
            font-weight: 500;
        }

        .ray-upload-btn:hover {
            background: rgba(94, 114, 228, 0.05);
        }

        .ray-file-name {
            font-size: 0.8rem;
            color: var(--ray-text);
            margin-top: 0.5rem;
            display: block;
        }

        .ray-password-wrapper {
            position: relative;
        }

        .ray-toggle-password {
            position: absolute;
            top: 50%;
            left: 1rem;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--ray-text);
            cursor: pointer;
        }

        .ray-password-field {
            padding-left: 3rem;
        }

        .ray-form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid var(--ray-border);
        }

        .ray-btn {
            padding: 0.75rem 1.5rem;
            border-radius: var(--ray-radius);
            font-weight: 600;
            transition: var(--ray-transition);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .ray-btn-primary {
            background: var(--ray-gradient);
            color: white;
            border: none;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .ray-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(94, 114, 228, 0.2);
        }

        .ray-btn-outline {
            background: transparent;
            border: 1px solid var(--ray-border);
            color: var(--ray-text);
        }

        .ray-btn-outline:hover {
            background: var(--ray-light);
        }

        .is-invalid {
            border-color: var(--ray-danger) !important;
        }

        .invalid-feedback {
            color: var(--ray-danger);
            font-size: 0.8rem;
            margin-top: 0.5rem;
        }

        @media (max-width: 768px) {
            .ray-avatar-edit {
                flex-direction: column;
                align-items: flex-start;
            }

            .ray-form-actions {
                flex-direction: column;
            }

            .ray-btn {
                width: 100%;
                justify-content: center;
            }
        }

        /* تأثيرات الحركة */
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

        .ray-animate {
            animation: fadeInUp 0.6s ease forwards;
        }

        .ray-delay-1 { animation-delay: 0.1s; }
        .ray-delay-2 { animation-delay: 0.2s; }
        .ray-delay-3 { animation-delay: 0.3s; }
        .ray-delay-4 { animation-delay: 0.4s; }
        .ray-delay-5 { animation-delay: 0.5s; }
    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto">موظف الأشعة</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تعديل الملف الشخصي</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <a href="{{ route('ray_employee.profile.show') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-user me-1"></i> عرض الملف الشخصي
            </a>
        </div>
    </div>
@endsection

@section('content')
    @include('Dashboard.messages_alert')

    <div class="ray-edit-container">
        <div class="ray-edit-card ray-animate">
            <div class="ray-edit-header">
                <h2><i class="fas fa-user-edit me-2"></i> تعديل الملف الشخصي</h2>
            </div>

            <form action="{{ route('ray_employee.profile.update') }}" method="POST" enctype="multipart/form-data" class="ray-edit-body needs-validation" novalidate>
                @method('PUT')
                @csrf

                <!-- قسم الصورة -->
                <div class="ray-form-group">
                    <label class="ray-form-label">الصورة الشخصية</label>
                    <div class="ray-avatar-edit">
                        <img id="rayImagePreviewEdit" class="ray-avatar-preview"
                             src="{{ $employee->image ? asset('Dashboard/img/rayEmployees/' . $employee->image->filename) : asset('Dashboard/img/default_avatar.png') }}"
                             alt="{{ $employee->name }}">
                        <div>
                            <label for="rayPhotoInputEdit" class="ray-upload-btn">
                                <i class="fas fa-upload me-1"></i> اختيار صورة
                            </label>
                            <input type="file" name="photo" id="rayPhotoInputEdit" accept="image/*,image/webp"
                                   onchange="rayDisplayFileNameAndPreview(this)">
                            <span id="rayFileNameDisplayEdit" class="ray-file-name">لم يتم اختيار ملف</span>
                            @error('photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <small class="text-muted">اختياري. يفضل صورة مربعة (الحد الأقصى 2MB).</small>
                </div>

                <div class="row">
                    <!-- الاسم -->
                    <div class="col-md-6">
                        <div class="ray-form-group">
                            <label for="rayNameEdit" class="ray-form-label">الاسم الكامل <span class="text-danger">*</span></label>
                            <input type="text" id="rayNameEdit" name="name" class="ray-form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $employee->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- البريد الإلكتروني -->
                    <div class="col-md-6">
                        <div class="ray-form-group">
                            <label for="rayEmailEdit" class="ray-form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                            <input type="email" id="rayEmailEdit" name="email" class="ray-form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $employee->email) }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- رقم الهاتف -->
                    <div class="col-md-6">
                        <div class="ray-form-group">
                            <label for="rayPhoneEdit" class="ray-form-label">رقم الهاتف <span class="text-danger">*</span></label>
                            <input type="tel" id="rayPhoneEdit" name="phone" class="ray-form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone', $employee->phone) }}" required pattern="^05\d{8}$" placeholder="05xxxxxxxx">
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- رقم الهوية -->
                    <div class="col-md-6">
                        <div class="ray-form-group">
                            <label for="rayNationalIdEdit" class="ray-form-label">رقم الهوية</label>
                            <input type="text" id="rayNationalIdEdit" name="national_id" class="ray-form-control @error('national_id') is-invalid @enderror"
                                   value="{{ old('national_id', $employee->national_id) }}" pattern="[0-9]{9,10}" maxlength="10" placeholder="9 أو 10 أرقام">
                            @error('national_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <!-- قسم كلمة المرور -->
                    <div class="col-12">
                        <hr class="my-4">
                        <p class="text-muted small mb-3">لتغيير كلمة المرور، أدخل كلمة المرور الحالية ثم الجديدة وتأكيدها.</p>
                    </div>

                    <!-- كلمة المرور الحالية -->
                    <div class="col-md-6">
                        <div class="ray-form-group ray-password-wrapper">
                            <label for="rayCurrentPasswordEdit" class="ray-form-label">كلمة المرور الحالية</label>
                            <input type="password" id="rayCurrentPasswordEdit" name="current_password"
                                   class="ray-form-control ray-password-field @error('current_password') is-invalid @enderror"
                                   autocomplete="current-password">
                            <button type="button" class="ray-toggle-password" onclick="rayTogglePasswordVisibility('rayCurrentPasswordEdit')" tabindex="-1">
                                <i class="fas fa-eye"></i>
                            </button>
                            @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="col-md-6"></div> <!-- فراغ للمحاذاة -->

                    <!-- كلمة المرور الجديدة -->
                    <div class="col-md-6">
                        <div class="ray-form-group ray-password-wrapper">
                            <label for="rayPasswordEdit" class="ray-form-label">كلمة المرور الجديدة</label>
                            <input type="password" id="rayPasswordEdit" name="password"
                                   class="ray-form-control ray-password-field @error('password') is-invalid @enderror"
                                   autocomplete="new-password" minlength="8">
                            <button type="button" class="ray-toggle-password" onclick="rayTogglePasswordVisibility('rayPasswordEdit')" tabindex="-1">
                                <i class="fas fa-eye"></i>
                            </button>
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <small class="text-muted">8 أحرف على الأقل.</small>
                        </div>
                    </div>

                    <!-- تأكيد كلمة المرور الجديدة -->
                    <div class="col-md-6">
                        <div class="ray-form-group ray-password-wrapper">
                            <label for="rayPasswordConfirmEdit" class="ray-form-label">تأكيد كلمة المرور الجديدة</label>
                            <input type="password" id="rayPasswordConfirmEdit" name="password_confirmation"
                                   class="ray-form-control ray-password-field" autocomplete="new-password" minlength="8">
                            <button type="button" class="ray-toggle-password" onclick="rayTogglePasswordVisibility('rayPasswordConfirmEdit')" tabindex="-1">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- أزرار الإجراءات -->
                <div class="ray-form-actions">
                    <a href="{{ route('ray_employee.profile.show') }}" class="ray-btn ray-btn-outline">
                        <i class="fas fa-times me-1"></i> إلغاء
                    </a>
                    <button type="submit" class="ray-btn ray-btn-primary">
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
        // تبديل رؤية كلمة المرور
        function rayTogglePasswordVisibility(inputId) {
            const input = document.getElementById(inputId);
            const button = input.nextElementSibling;
            const icon = button.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // عرض اسم الملف ومعاينة الصورة
        function rayDisplayFileNameAndPreview(input) {
            const fileNameDisplay = document.getElementById('rayFileNameDisplayEdit');
            const preview = document.getElementById('rayImagePreviewEdit');
            const originalImage = "{{ $employee->image ? asset('Dashboard/img/rayEmployees/' . $employee->image->filename) : asset('Dashboard/img/default_avatar.png') }}";

            if (input.files && input.files[0]) {
                const file = input.files[0];

                // التحقق من نوع الملف
                if (!file.type.match('image.*')) {
                    notif({
                        msg: "<i class='fas fa-exclamation-circle me-2'></i> يرجى اختيار ملف صورة فقط",
                        type: "error",
                        position: "center"
                    });
                    input.value = '';
                    fileNameDisplay.textContent = 'ملف غير صالح';
                    return;
                }

                // التحقق من حجم الملف (2MB كحد أقصى)
                if (file.size > 2 * 1024 * 1024) {
                    notif({
                        msg: "<i class='fas fa-exclamation-circle me-2'></i> حجم الصورة يجب أن لا يتجاوز 2 ميجابايت",
                        type: "error",
                        position: "center"
                    });
                    input.value = '';
                    fileNameDisplay.textContent = 'حجم كبير جداً';
                    return;
                }

                fileNameDisplay.textContent = file.name;

                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                }
                reader.readAsDataURL(file);
            } else {
                fileNameDisplay.textContent = 'لم يتم اختيار ملف';
                preview.src = originalImage;
            }
        }

        // تفعيل التحقق من صحة النموذج
        (function () {
            'use strict';

            // استرجاع جميع النماذج التي نريد تطبيق التحقق من صحتها عليها
            var forms = document.querySelectorAll('.needs-validation');

            // التكرار عليها ومنع الإرسال
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault();
                            event.stopPropagation();

                            // إضافة تأثير اهتزاز للحقول غير الصالحة
                            const invalidFields = form.querySelectorAll(':invalid');
                            invalidFields.forEach(field => {
                                field.classList.add('animate__animated', 'animate__headShake');
                                setTimeout(() => {
                                    field.classList.remove('animate__animated', 'animate__headShake');
                                }, 1000);
                            });
                        }

                        form.classList.add('was-validated');
                    }, false);
                });
        })();

        // عرض رسائل التنبيه
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                notif({
                    msg: "<i class='fas fa-check-circle me-2'></i> {{ session('success') }}",
                    type: "success",
                    position: "center",
                    width: 350,
                    timeout: 5000,
                    animation: "slide"
                });
            @endif

            @if(session('error'))
                notif({
                    msg: "<i class='fas fa-exclamation-triangle me-2'></i> {{ session('error') }}",
                    type: "error",
                    position: "center",
                    width: 350,
                    timeout: 7000,
                    animation: "slide"
                });
            @endif
        });
    </script>
@endsection
