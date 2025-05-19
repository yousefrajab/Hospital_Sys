{{-- resources/views/Dashboard/pharmacy_employee/edit.blade.php --}}
@extends('Dashboard.layouts.master')

@php
    $employeeName = $pharmacy_employee->name ?? 'موظف غير مسمى';
@endphp
@section('title', 'تعديل بيانات موظف المختبر: ' . $employeeName)

@section('css')
    @parent
    {{-- نفس ملفات CSS التي استخدمتها للطبيب --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" ... />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link href="{{ URL::asset('Dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    {{-- <link href="{{ URL::asset('Dashboard/plugins/select2/css/select2.min.css') }}" rel="stylesheet"> --}} {{-- لا نحتاج Select2 هنا --}}
    {{-- <link href="{{ URL::asset('Dashboard/plugins/neomorphic-ui/neomorphic.css') }}" rel="stylesheet"> --}} {{-- Neomorphic UI إذا كنت تستخدمه --}}

    {{-- نسخ الأنماط الممتازة من تصميم الطبيب --}}
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #6366F1, #8B5CF6);
            --secondary-gradient: linear-gradient(135deg, #06B6D4, #0EA5E9);
            --glass-effect: rgba(255, 255, 255, 0.25);
            --primary-color: #4e73ff; /* ألوان إضافية من تصميم المختبر الأصلي إذا احتجتها */
            --secondary-color: #858796;
            --accent-color: #1cc88a;
            --light-bg: #f8f9fc;
            --card-bg: #ffffff;
            --text-dark: #5a5c69;
            --text-light: #858796;
            --border-color: #e3e6f0;
            --danger-color: #e74a3b;
            --success-color: #1cc88a;
            --radius-md: 0.35rem;
            --radius-lg: 0.75rem;
            --shadow-sm: 0 .125rem .25rem rgba(0,0,0,.075);
            --shadow-md: 0 .5rem 1rem rgba(0,0,0,.15);
        }
        body { background: #F8FAFC; font-family: 'Tajawal', sans-serif; /* استخدام خط Tajawal */ }

        /* تنسيقات أساسية للأزرار (من تصميم الطبيب) */
        .btn-custom { position: relative; padding: 12px 24px; border: none; border-radius: 8px; font-size: 16px; font-weight: 600; cursor: pointer; overflow: hidden; transition: all 0.3s ease; display: inline-flex; align-items: center; justify-content: center; min-width: 150px; text-align: center; text-decoration: none; }
        .btn-cancel { background: linear-gradient(135deg, #f5f7fa 0%, #e4e8eb 100%); color: #6c757d; box-shadow: 0 4px 6px rgba(108, 117, 125, 0.1); }
        .btn-save { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; box-shadow: 0 4px 6px rgba(0, 178, 255, 0.2); }
        .btn-cancel:hover { transform: translateY(-2px); box-shadow: 0 6px 12px rgba(108, 117, 125, 0.15); color: #5a6268; }
        .btn-save:hover { transform: translateY(-2px); box-shadow: 0 6px 12px rgba(0, 178, 255, 0.3); background: linear-gradient(135deg, #3a9ffd 0%, #00d9e9 100%); }
        .btn-custom:active { transform: translateY(1px); }
        .btn-wave { position: absolute; top: 50%; left: 50%; width: 5px; height: 5px; background: rgba(255, 255, 255, 0.5); opacity: 0; border-radius: 100%; transform: scale(1, 1) translate(-50%, -50%); transform-origin: 50% 50%; transition: all 0.5s ease-out; }
        .btn-custom:hover .btn-wave { opacity: 1; transform: scale(50, 50) translate(-50%, -50%); }
        .btn-save .btn-wave { transition-delay: 0.1s; }
        .btn-content { position: relative; z-index: 2; display: flex; align-items: center; }
        .gap-4 { gap: 1rem; }

        /* تصميم البطاقة 3D (من تصميم الطبيب) */
        .card-3d { background: white; border-radius: 24px; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); backdrop-filter: blur(16px); border: 1px solid rgba(255, 255, 255, 0.3); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .card-3d:hover { transform: translateY(-4px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
        .section-title { font-size: 1.5rem; font-weight: 700; background: linear-gradient(135deg, #6366F1, #8B5CF6); -webkit-background-clip: text; -webkit-text-fill-color: transparent; position: relative; padding-bottom: 12px; }
        .section-title:after { content: ''; position: absolute; bottom: 0; right: 0; width: 50px; height: 4px; background: linear-gradient(135deg, #6366F1, #8B5CF6); border-radius: 2px; }

        /* تصميم حقول الإدخال Modern (من تصميم الطبيب) */
        .form-control-modern { border-radius: 12px; border: 1px solid #E2E8F0; padding: 12px 16px; transition: all 0.3s; background: rgba(255, 255, 255, 0.7); }
        .form-control-modern:focus { border-color: #8B5CF6; box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.2); background: white; }
        .floating-label { position: relative; margin-bottom: 24px; }
        .floating-label label { position: absolute; top: -10px; right: 16px; background: white; padding: 0 8px; font-size: 13px; color: #6366F1; font-weight: 600; transition: all 0.2s ease-out; pointer-events: none; z-index: 1;}
        .floating-label input:focus ~ label, .floating-label input:not(:placeholder-shown) ~ label { top: -10px; font-size: 13px; color: #6366F1; }
        /* إزالة padding-top من input إذا استخدمت floating label */
        /* .floating-label input { padding-top: 20px; } */

        /* تصميم رفع الصورة Avatar (من تصميم الطبيب) */
        .avatar-upload { position: relative; width: 150px; /* تعديل الحجم حسب الرغبة */ height: 150px; margin: 0 auto 24px; /* إضافة هامش سفلي */ }
        .avatar-upload img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; border: 4px solid white; box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15); }
        .avatar-upload label { position: absolute; bottom: 5px; right: 5px; width: 40px; height: 40px; background: linear-gradient(135deg, #06B6D4, #0EA5E9); /* استخدام تدرج ثانوي */ border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15); transition: all 0.3s ease; }
        .avatar-upload label:hover { transform: scale(1.1); box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2); }
        .avatar-upload label i { color: white; font-size: 16px; }
        .avatar-upload input[type="file"] { display: none; }

        /* زر تبديل كلمة المرور (من تصميم الطبيب) */
        .toggle-password-eye { position: absolute; top: 50%; left: 16px; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 0; z-index: 10; }
        .toggle-password-eye i { font-size: 16px; color: #8B5CF6; } /* لون مطابق للتدرج الأساسي */
        /* إضافة padding لليسار لحقل كلمة المرور */
        .form-control-modern[type="password"], .form-control-modern[type="text"].password-input { padding-left: 45px; }

        /* تصميم Select العادي بتنسيق modern */
        .form-select-modern { border-radius: 12px; border: 1px solid #E2E8F0; padding: 12px 16px; background: rgba(255, 255, 255, 0.7); -webkit-appearance: none; -moz-appearance: none; appearance: none; background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e"); background-repeat: no-repeat; background-position: left .75rem center; background-size: 16px 12px; }
        .form-select-modern:focus { border-color: #8B5CF6; box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.2); background-color: white; }

        /* رسائل التحقق */
        .invalid-feedback { color: var(--danger-color, #e74a3b); font-size: 0.85em; margin-top: 4px; display: block;}
        .valid-feedback { color: var(--success-color, #1cc88a); font-size: 0.85em; margin-top: 4px; display: block;}
        .form-control-modern.is-invalid, .needs-validation .form-control-modern:invalid { border-color: var(--danger-color); }
        .form-control-modern.is-valid, .needs-validation .form-control-modern:valid { border-color: var(--success-color); }

    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto">موظفو المختبر</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تعديل بيانات / {{ $employeeName }}</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <a href="{{ route('admin.pharmacy_employee.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> عودة للقائمة
            </a>
        </div>
    </div>
@endsection

@section('content')
    @include('Dashboard.messages_alert')

    <div class="row justify-content-center mt-4">
        <div class="col-lg-10 col-md-12">
            <div class="card-3d p-lg-6 p-4"> {{-- استخدام بطاقة 3D مع padding --}}
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <h3 class="section-title mb-0">
                        <i class="fas fa-flask me-2"></i> تعديل بيانات موظف المختبر
                    </h3>
                </div>

                <form action="{{ route('admin.pharmacy_employee.update', $pharmacy_employee->id) }}" method="POST" enctype="multipart/form-data"
                    class="needs-validation" novalidate autocomplete="off">
                    @method('PATCH') {{-- أو PUT --}}
                    @csrf

                    <div class="row">
                        {{-- قسم الصورة --}}
                        <div class="col-md-4 d-flex flex-column align-items-center">
                             <div class="avatar-upload mb-4">
                                {{-- استخدام ID فريد لمعاينة الصورة --}}
                                <img id="lab_photo_preview_{{ $pharmacy_employee->id }}"
                                     src="{{ $pharmacy_employee->image ? URL::asset('Dashboard/img/pharmacyEmployees/' . $pharmacy_employee->image->filename) : URL::asset('Dashboard/img/default_avatar.png') }}"
                                     alt="صورة الموظف">
                                <label for="lab_photo_input_{{ $pharmacy_employee->id }}">
                                    <i class="fas fa-camera"></i>
                                </label>
                                <input id="lab_photo_input_{{ $pharmacy_employee->id }}" type="file" accept="image/*,image/webp" name="photo"
                                       onchange="loadFile(event, 'lab_photo_preview_{{ $pharmacy_employee->id }}')"> {{-- استخدام دالة loadFile --}}
                            </div>
                             @error('photo') <small class="text-danger text-center d-block mb-3">{{ $message }}</small> @enderror
                        </div>

                        {{-- بقية الحقول --}}
                        <div class="col-md-8">
                            <div class="row">
                                <!-- الاسم الكامل -->
                                <div class="col-md-6">
                                    <div class="floating-label">
                                        <input type="text" id="name_edit_{{ $pharmacy_employee->id }}" name="name" class="form-control-modern @error('name') is-invalid @enderror" value="{{ old('name', $pharmacy_employee->name) }}" required placeholder=" "> {{-- placeholder مهم لـ floating label --}}
                                        <label for="name_edit_{{ $pharmacy_employee->id }}">الاسم الكامل <span class="text-danger">*</span></label>
                                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <!-- رقم الهوية -->
                                <div class="col-md-6">
                                    <div class="floating-label">
                                        <input type="text" id="national_id_edit_{{ $pharmacy_employee->id }}" name="national_id" class="form-control-modern @error('national_id') is-invalid @enderror" value="{{ old('national_id', $pharmacy_employee->national_id) }}" pattern="[0-9]{9,10}" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required placeholder=" ">
                                        <label for="national_id_edit_{{ $pharmacy_employee->id }}">رقم الهوية <span class="text-danger">*</span></label>
                                        <small class="form-text text-muted">9 أو 10 أرقام.</small>
                                        @error('national_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <!-- البريد الإلكتروني -->
                                <div class="col-md-6">
                                    <div class="floating-label">
                                        <input type="email" id="email_edit_{{ $pharmacy_employee->id }}" name="email" class="form-control-modern @error('email') is-invalid @enderror" value="{{ old('email', $pharmacy_employee->email) }}" required placeholder=" ">
                                        <label for="email_edit_{{ $pharmacy_employee->id }}">البريد الإلكتروني <span class="text-danger">*</span></label>
                                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <!-- رقم الهاتف -->
                                <div class="col-md-6">
                                    <div class="floating-label">
                                        <input type="tel" id="phone_edit_{{ $pharmacy_employee->id }}" name="phone" class="form-control-modern @error('phone') is-invalid @enderror" value="{{ old('phone', $pharmacy_employee->phone) }}" pattern="^05\d{8}$" maxlength="10" required placeholder=" ">
                                        <label for="phone_edit_{{ $pharmacy_employee->id }}">رقم الهاتف <span class="text-danger">*</span></label>
                                        <small class="form-text text-muted">مثال: 05xxxxxxxx.</small>
                                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <!-- الحالة -->
                                <div class="col-md-6">
                                    <div class="floating-label">
                                        <select name="status" id="status_edit_{{ $pharmacy_employee->id }}" class="form-select-modern w-100 @error('status') is-invalid @enderror" required>
                                            <option value="1" {{ old('status', $pharmacy_employee->status) == 1 ? 'selected' : '' }}>نشط</option>
                                            <option value="0" {{ old('status', $pharmacy_employee->status) == 0 ? 'selected' : '' }}>غير نشط</option>
                                        </select>
                                        <label for="status_edit_{{ $pharmacy_employee->id }}">الحالة <span class="text-danger">*</span></label>
                                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>

                                <!-- كلمة المرور الجديدة -->
                                <div class="col-md-6">
                                    <div class="floating-label position-relative">
                                        <input type="password" id="password_edit_{{ $pharmacy_employee->id }}" name="password" class="form-control-modern password-input @error('password') is-invalid @enderror" autocomplete="new-password" minlength="8" placeholder=" ">
                                        <label for="password_edit_{{ $pharmacy_employee->id }}">كلمة المرور الجديدة</label>
                                        <button type="button" class="toggle-password-eye" onclick="togglePasswordVisibility('password_edit_{{ $pharmacy_employee->id }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <small class="form-text text-muted">اتركه فارغاً لعدم التغيير (8 أحرف على الأقل).</small>
                                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                                {{-- أضف تأكيد كلمة المرور إذا كان مطلوباً --}}
                                {{-- <div class="col-md-6"> ... password_confirmation ... </div> --}}
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-5 gap-4"> {{-- استخدام d-flex و gap --}}
                        <a href="{{ route('admin.pharmacy_employee.index') }}" class="btn-custom btn-cancel">
                            <span class="btn-content"><i class="fas fa-times me-2"></i><span>إلغاء</span></span>
                            <span class="btn-wave"></span>
                        </a>
                        <button type="submit" class="btn-custom btn-save">
                            <span class="btn-content"><i class="fas fa-save me-2"></i><span>حفظ التغييرات</span></span>
                            <span class="btn-wave"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @parent
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifit-custom.js') }}"></script>
    {{-- <script src="{{ URL::asset('Dashboard/plugins/select2/js/select2.min.js') }}"></script> --}}
    {{-- <script src="{{ URL::asset('Dashboard/plugins/neomorphic-ui/neomorphic.js') }}"></script> --}}

    <script>
        // دالة معاينة الصورة (يجب أن تكون متاحة، تأكد من اسمها loadFile)
        if (typeof window.loadFile !== 'function') {
            window.loadFile = function(event, outputId) {
                var output = document.getElementById(outputId);
                 if (event.target.files && event.target.files[0]) {
                    // يمكنك إضافة التحقق من الحجم والنوع هنا كما في الرد السابق
                    output.src = URL.createObjectURL(event.target.files[0]);
                    output.onload = function() { URL.revokeObjectURL(output.src); }
                 }
            };
        }

        // دالة تبديل كلمة المرور (مأخوذة من كود الطبيب)
        function togglePasswordVisibility(inputId) {
            const passwordInput = document.getElementById(inputId);
            // البحث عن الزر المرتبط بالحقل (بافتراض أنه العنصر التالي أو لديه كلاس معين)
            const button = passwordInput.closest('.floating-label').querySelector('.toggle-password-eye');
            const icon = button ? button.querySelector('i') : null;

            if (passwordInput && icon) {
                if (passwordInput.type === "password") {
                    passwordInput.type = "text";
                    icon.classList.remove("fa-eye"); icon.classList.add("fa-eye-slash");
                } else {
                    passwordInput.type = "password";
                    icon.classList.remove("fa-eye-slash"); icon.classList.add("fa-eye");
                }
            }
        }

        // التحقق من صحة Bootstrap
        (function () {
            'use strict';
            window.addEventListener('load', function () {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function (form) {
                    form.addEventListener('submit', function (event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                             // عرض رسالة خطأ عامة باستخدام NotifIt
                            if (typeof notif !== 'undefined') {
                                notif({ msg: "يرجى ملء جميع الحقول المطلوبة بشكل صحيح.", type: "warning", position: "center", timeout: 4000 });
                            }
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();

        // عرض رسائل NotifIt عند النجاح أو الخطأ
         document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                notif({ msg: "<i class='fas fa-check-circle me-2'></i> {{ session('success') }}", type: "success", position: "bottom", autohide: true, timeout: 5000});
            @endif
            @if (session('error'))
                notif({ msg: "<i class='fas fa-exclamation-triangle me-2'></i> {{ session('error') }}", type: "error", position: "bottom", autohide: true, timeout: 7000});
            @endif
            @if ($errors->any())
                 let errorMsg = "<strong><i class='fas fa-times-circle me-2'></i> يرجى تصحيح الأخطاء التالية:</strong><ul class='mb-0 ps-3 mt-2' style='list-style-type: none; padding-right: 0;'>";
                @foreach ($errors->all() as $error)
                    errorMsg += "<li>- {{ $error }}</li>";
                @endforeach
                errorMsg += "</ul>";
                notif({ msg: errorMsg, type: "error", position: "bottom", multiline: true, autohide: false });
            @endif
         });

    </script>
@endsection
