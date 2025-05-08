{{-- resources/views/Dashboard/ray_employee/edit.blade.php --}}
@extends('Dashboard.layouts.master')

@php
    $employeeName = $ray_employee->name ?? 'موظف أشعة غير مسمى';
@endphp
@section('title', 'تعديل بيانات موظف الأشعة: ' . $employeeName)

@section('css')
    @parent
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link href="{{ URL::asset('Dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #6366F1, #8B5CF6);
            --secondary-gradient: linear-gradient(135deg, #06B6D4, #0EA5E9);
            --dark-gradient: linear-gradient(135deg, #1e293b, #0f172a);
            --glass-effect: rgba(255, 255, 255, 0.15);
            --primary-color: #4f46e5;
            --secondary-color: #94a3b8;
            --accent-color: #10b981;
            --light-bg: #f8fafc;
            --card-bg: #ffffff;
            --text-dark: #1e293b;
            --text-light: #64748b;
            --border-color: #e2e8f0;
            --danger-color: #ef4444;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --radius-md: 0.5rem;
            --radius-lg: 1rem;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.12);
            --shadow-md: 0 4px 6px rgba(0,0,0,0.1);
            --shadow-lg: 0 10px 25px rgba(0,0,0,0.1);
        }

        body {
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            font-family: 'Tajawal', sans-serif;
            min-height: 100vh;
        }

        /* تنسيقات متحركة للأزرار */
        .btn-custom {
            position: relative;
            padding: 14px 28px;
            border: none;
            border-radius: var(--radius-md);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 160px;
            text-align: center;
            text-decoration: none;
            box-shadow: var(--shadow-sm);
            z-index: 1;
        }

        .btn-cancel {
            background: white;
            color: var(--text-dark);
            border: 1px solid var(--border-color);
        }

        .btn-save {
            background: var(--primary-gradient);
            color: white;
            box-shadow: 0 4px 6px rgba(79, 70, 229, 0.25);
        }

        .btn-cancel:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-save:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 12px rgba(79, 70, 229, 0.3);
            background: linear-gradient(135deg, #5b55e8, #7c3aed);
        }

        .btn-custom:active {
            transform: translateY(1px);
        }

        .btn-custom::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(255,255,255,0.3), rgba(255,255,255,0));
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: -1;
        }

        .btn-custom:hover::before {
            opacity: 1;
        }

        .btn-content {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .gap-4 {
            gap: 1rem;
        }

        /* تصميم البطاقة الزجاجية مع تأثير 3D */
        .card-glass {
            background: rgba(255, 255, 255, 0.8);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
            position: relative;
        }

        .card-glass::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                to bottom right,
                rgba(255, 255, 255, 0.1),
                rgba(255, 255, 255, 0)
            );
            transform: rotate(30deg);
            pointer-events: none;
        }

        .card-glass:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .section-title {
            font-size: 1.75rem;
            font-weight: 800;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            position: relative;
            padding-bottom: 12px;
            margin-bottom: 24px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            width: 60px;
            height: 4px;
            background: var(--primary-gradient);
            border-radius: 2px;
        }

        .section-icon {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 1.5em;
            margin-left: 10px;
        }

        /* تصميم حقول الإدخال الحديثة */
        .form-control-modern {
            border-radius: var(--radius-md);
            border: 1px solid var(--border-color);
            padding: 14px 16px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
            font-size: 15px;
            box-shadow: var(--shadow-sm);
        }

        .form-control-modern:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
            background: white;
            outline: none;
        }

        .floating-label {
            position: relative;
            margin-bottom: 28px;
        }

        .floating-label label {
            position: absolute;
            top: -10px;
            right: 16px;
            background: white;
            padding: 0 8px;
            font-size: 13px;
            color: var(--primary-color);
            font-weight: 600;
            transition: all 0.2s ease-out;
            pointer-events: none;
            z-index: 2;
            border-radius: 4px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }

        .floating-label input:focus ~ label,
        .floating-label input:not(:placeholder-shown):not(:focus) ~ label,
        .floating-label select:focus ~ label,
        .floating-label select:not([value=""]):not(:focus) ~ label {
            top: -10px;
            font-size: 13px;
            color: var(--primary-color);
            transform: translateY(0);
        }

        .form-text {
            font-size: 0.8rem;
            color: var(--text-light);
            margin-top: 4px;
        }

        /* تصميم رفع الصورة Avatar */
        .avatar-upload {
            position: relative;
            width: 160px;
            height: 160px;
            margin: 0 auto 24px;
            border-radius: 50%;
            background: white;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .avatar-upload:hover {
            transform: scale(1.05);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
        }

        .avatar-upload img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid white;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .avatar-upload label {
            position: absolute;
            bottom: 10px;
            right: 10px;
            width: 42px;
            height: 42px;
            background: var(--primary-gradient);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            z-index: 2;
        }

        .avatar-upload label:hover {
            transform: scale(1.1) rotate(15deg);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.25);
        }

        .avatar-upload label i {
            color: white;
            font-size: 18px;
        }

        .avatar-upload input[type="file"] {
            display: none;
        }

        .avatar-hint {
            font-size: 0.85rem;
            color: var(--text-light);
            text-align: center;
            margin-top: -16px;
            margin-bottom: 24px;
        }

        /* زر تبديل كلمة المرور */
        .toggle-password-eye {
            position: absolute;
            top: 50%;
            left: 16px;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            z-index: 10;
            color: var(--primary-color);
            transition: all 0.2s ease;
        }

        .toggle-password-eye:hover {
            color: var(--accent-color);
            transform: translateY(-50%) scale(1.1);
        }

        .toggle-password-eye i {
            font-size: 18px;
        }

        .form-control-modern[type="password"],
        .form-control-modern[type="text"].password-input {
            padding-left: 50px;
        }

        /* تصميم Select العادي بتنسيق modern */
        .form-select-modern {
            border-radius: var(--radius-md);
            border: 1px solid var(--border-color);
            padding: 14px 16px;
            background: rgba(255, 255, 255, 0.8);
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='%234f46e5' stroke='%234f46e5' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: left 0.75rem center;
            background-size: 16px 12px;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
        }

        .form-select-modern:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
            background-color: white;
            outline: none;
        }

        /* رسائل التحقق */
        .invalid-feedback {
            color: var(--danger-color);
            font-size: 0.85em;
            margin-top: 6px;
            display: block;
            animation: shake 0.5s ease-in-out;
        }

        .valid-feedback {
            color: var(--success-color);
            font-size: 0.85em;
            margin-top: 6px;
            display: block;
        }

        .form-control-modern.is-invalid,
        .needs-validation .form-control-modern:invalid {
            border-color: var(--danger-color);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23ef4444'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23ef4444' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
            padding-right: calc(1.5em + 0.75rem);
        }

        .form-control-modern.is-valid,
        .needs-validation .form-control-modern:valid {
            border-color: var(--success-color);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%2310b981' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
            padding-right: calc(1.5em + 0.75rem);
        }

        /* تأثيرات الحركة */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-5px); }
            40%, 80% { transform: translateX(5px); }
        }

        .animate-float {
            animation: float 6s ease-in-out infinite;
        }

        .animate-pulse {
            animation: pulse 3s ease infinite;
        }

        /* تأثيرات خاصة للبطاقة */
        .card-highlight {
            position: relative;
            overflow: hidden;
        }

        .card-highlight::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                to bottom right,
                rgba(255, 255, 255, 0.3),
                rgba(255, 255, 255, 0)
            );
            transform: rotate(30deg);
            animation: shine 3s infinite;
            pointer-events: none;
        }

        @keyframes shine {
            0% { transform: rotate(30deg) translate(-30%, -30%); }
            100% { transform: rotate(30deg) translate(30%, 30%); }
        }

        /* تنسيقات إضافية */
        .required-star {
            color: var(--danger-color);
            margin-right: 4px;
        }

        .form-section {
            margin-bottom: 32px;
            padding-bottom: 24px;
            border-bottom: 1px dashed var(--border-color);
        }

        .form-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .form-section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }

        .form-section-title i {
            margin-left: 10px;
            font-size: 1.1em;
        }
    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto">موظفو الأشعة</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تعديل بيانات / {{ $employeeName }}</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <a href="{{ route('admin.ray_employee.index') }}" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-arrow-left me-1"></i> عودة للقائمة
            </a>
        </div>
    </div>
@endsection

@section('content')
    @include('Dashboard.messages_alert')

    <div class="row justify-content-center mt-4">
        <div class="col-lg-10 col-md-12">
            <div class="card-glass card-highlight p-lg-6 p-4 animate__animated animate__fadeInUp">
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <h3 class="section-title mb-0">
                        <i class="fas fa-user-edit section-icon"></i> تعديل بيانات موظف الأشعة
                    </h3>
                    <div class="badge bg-gradient-primary rounded-pill p-2 animate-pulse">
                        <i class="fas fa-id-card me-1"></i> ID: {{ $ray_employee->id }}
                    </div>
                </div>

                <form action="{{ route('admin.ray_employee.update', $ray_employee->id) }}" method="POST" enctype="multipart/form-data"
                    class="needs-validation" novalidate autocomplete="off">
                    @method('PUT')
                    @csrf

                    <div class="row">
                        {{-- قسم الصورة --}}
                        <div class="col-md-4 d-flex flex-column align-items-center">
                            {{-- <div class="avatar-upload mb-3 animate-float">
                                <img id="ray_photo_preview_{{ $ray_employee->id }}"
                                     src="{{ $ray_employee->image ? URL::asset('Dashboard/img/rayEmployees/' . $ray_employee->image->filename) : URL::asset('Dashboard/img/default_avatar.png') }}"
                                     alt="صورة الموظف" class="animate__animated animate__fadeIn">
                                <label for="ray_photo_input_{{ $ray_employee->id }}">
                                    <i class="fas fa-camera"></i>
                                </label>
                                <input id="ray_photo_input_{{ $ray_employee->id }}" type="file" accept="image/*,image/webp" name="photo"
                                       onchange="loadFile(event, 'ray_photo_preview_{{ $ray_employee->id }}')">
                            </div> --}}


                            <div class="avatar-upload mb-3 animate-float">
                                @if ($ray_employee->image)
                                    <img id="output"
                                        src="{{ URL::asset('Dashboard/img/rayEmployees/' . $ray_employee->image->filename) }}"
                                        alt="{{ trans('rayEmployees.img') }}">
                                @else
                                    <img id="output" src="{{ URL::asset('Dashboard/img/doctor_default.png') }}"
                                        alt="صورة الطبيب">
                                @endif
                                <label for="avatar-upload">
                                    <i class="fas fa-camera"></i>
                                </label>
                                <input id="avatar-upload" type="file" accept="image/*" name="photo"
                                    onchange="loadFile(event)">
                            </div>
                            @error('photo') <small class="text-danger text-center d-block mb-2">{{ $message }}</small> @enderror
                            <small class="avatar-hint">الصورة الشخصية (اختياري - JPG, PNG, WEBP)</small>
                        </div>

                        {{-- بقية الحقول --}}
                        <div class="col-md-8">
                            <div class="form-section">
                                <h5 class="form-section-title"><i class="fas fa-user-circle"></i> المعلومات الشخصية</h5>
                                <div class="row">
                                    <!-- الاسم الكامل -->
                                    <div class="col-md-6">
                                        <div class="floating-label">
                                            <input type="text" id="name_edit_{{ $ray_employee->id }}" name="name"
                                                   class="form-control-modern @error('name') is-invalid @enderror"
                                                   value="{{ old('name', $ray_employee->name) }}" required placeholder=" ">
                                            <label for="name_edit_{{ $ray_employee->id }}">الاسم الكامل <span class="required-star">*</span></label>
                                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    <!-- رقم الهوية -->
                                    <div class="col-md-6">
                                        <div class="floating-label">
                                            <input type="text" id="national_id_edit_{{ $ray_employee->id }}" name="national_id"
                                                   class="form-control-modern @error('national_id') is-invalid @enderror"
                                                   value="{{ old('national_id', $ray_employee->national_id) }}"
                                                   pattern="[0-9]{9,10}" maxlength="10"
                                                   oninput="this.value = this.value.replace(/[^0-9]/g, '')" required placeholder=" ">
                                            <label for="national_id_edit_{{ $ray_employee->id }}">رقم الهوية <span class="required-star">*</span></label>
                                            <small class="form-text">يجب أن يتكون من 9 أو 10 أرقام فقط</small>
                                            @error('national_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-section">
                                <h5 class="form-section-title"><i class="fas fa-address-card"></i> معلومات التواصل</h5>
                                <div class="row">
                                    <!-- البريد الإلكتروني -->
                                    <div class="col-md-6">
                                        <div class="floating-label">
                                            <input type="email" id="email_edit_{{ $ray_employee->id }}" name="email"
                                                   class="form-control-modern @error('email') is-invalid @enderror"
                                                   value="{{ old('email', $ray_employee->email) }}" required placeholder=" ">
                                            <label for="email_edit_{{ $ray_employee->id }}">البريد الإلكتروني <span class="required-star">*</span></label>
                                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    <!-- رقم الهاتف -->
                                    <div class="col-md-6">
                                        <div class="floating-label">
                                            <input type="tel" id="phone_edit_{{ $ray_employee->id }}" name="phone"
                                                   class="form-control-modern @error('phone') is-invalid @enderror"
                                                   value="{{ old('phone', $ray_employee->phone) }}"
                                                   pattern="^05\d{8}$" maxlength="10" required placeholder=" ">
                                            <label for="phone_edit_{{ $ray_employee->id }}">رقم الهاتف <span class="required-star">*</span></label>
                                            <small class="form-text">يجب أن يبدأ بـ 05 ويتكون من 10 أرقام</small>
                                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-section">
                                <h5 class="form-section-title"><i class="fas fa-lock"></i> الإعدادات الأمنية</h5>
                                <div class="row">
                                    <!-- الحالة -->
                                    <div class="col-md-6">
                                        <div class="floating-label">
                                            <select name="status" id="status_edit_{{ $ray_employee->id }}"
                                                    class="form-select-modern w-100 @error('status') is-invalid @enderror" required>
                                                <option value="1" {{ old('status', $ray_employee->status) == 1 ? 'selected' : '' }}>نشط</option>
                                                <option value="0" {{ old('status', $ray_employee->status) == 0 ? 'selected' : '' }}>غير نشط</option>
                                            </select>
                                            <label for="status_edit_{{ $ray_employee->id }}">الحالة <span class="required-star">*</span></label>
                                            @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>

                                    <!-- كلمة المرور الجديدة -->
                                    <div class="col-md-6">
                                        <div class="floating-label position-relative">
                                            <input type="password" id="password_edit_{{ $ray_employee->id }}" name="password"
                                                   class="form-control-modern password-input @error('password') is-invalid @enderror"
                                                   autocomplete="new-password" minlength="8" placeholder=" ">
                                            <label for="password_edit_{{ $ray_employee->id }}">كلمة المرور الجديدة</label>
                                            <button type="button" class="toggle-password-eye"
                                                    onclick="togglePasswordVisibility('password_edit_{{ $ray_employee->id }}')">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <small class="form-text">اتركه فارغاً لعدم التغيير (8 أحرف على الأقل)</small>
                                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-5 gap-4">
                        <a href="{{ route('admin.ray_employee.index') }}" class="btn-custom btn-cancel">
                            <span class="btn-content"><i class="fas fa-times me-2"></i><span>إلغاء</span></span>
                        </a>
                        <button type="submit" class="btn-custom btn-save">
                            <span class="btn-content"><i class="fas fa-save me-2"></i><span>حفظ التغييرات</span></span>
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

    <script>
          var loadFile = function(event) {
            var output = document.getElementById('output');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src);
            }
        };
        // دالة تبديل رؤية كلمة المرور
        function togglePasswordVisibility(inputId) {
            const passwordInput = document.getElementById(inputId);
            const button = passwordInput.nextElementSibling;
            const icon = button.querySelector('i');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
                passwordInput.classList.add('animate__animated', 'animate__pulse');
                setTimeout(() => {
                    passwordInput.classList.remove('animate__animated', 'animate__pulse');
                }, 1000);
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // التحقق من صحة Bootstrap مع تأثيرات
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

        // عرض رسائل NotifIt مع تأثيرات
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                notif({
                    type: "success",
                    msg: "{{ session('success') }}",
                    position: "right",
                    width: 300,
                    animation: "slide",
                    duration: 3
                });
                // إضافة تأثير للبطاقة عند النجاح
                const card = document.querySelector('.card-glass');
                if (card) {
                    card.classList.add('animate__animated', 'animate__tada');
                    setTimeout(() => {
                        card.classList.remove('animate__animated', 'animate__tada');
                    }, 1000);
                }
            @endif

            @if (session('error'))
                notif({
                    type: "error",
                    msg: "{{ session('error') }}",
                    position: "right",
                    width: 300,
                    animation: "slide",
                    duration: 3
                });
            @endif

            @if ($errors->any())
                notif({
                    type: "error",
                    msg: "يوجد أخطاء في البيانات المدخلة، يرجى مراجعتها",
                    position: "right",
                    width: 300,
                    animation: "slide",
                    duration: 3
                });
            @endif
        });
    </script>
@endsection
