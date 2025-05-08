{{-- resources/views/Dashboard/Doctors/profile/edit.blade.php --}}
@extends('Dashboard.layouts.master') {{-- أو اسم الـ Layout الصحيح --}}

{{-- ========================== CSS Section ========================== --}}
@section('css')
    @parent {{-- استيراد CSS الأساسي --}}
    {{-- Font Awesome & Animate.css --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    {{-- NotifIt (مكتبة الإشعارات) --}}
    <link href="{{ URL::asset('dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />

    {{-- أنماط CSS العصرية المخصصة --}}
    <style>
        /* --- المتغيرات الأساسية (Globals & Dark Mode) --- */
        :root {
            --profile-bg: #f8f9fc;
            --profile-card-bg: #ffffff;
            --profile-text-primary: #1e293b;
            --profile-text-secondary: #64748b;
            --profile-primary: #4f46e5;
            --profile-primary-hover: #4338ca;
            --profile-secondary: #10b981;
            --profile-border-color: #e5e7eb;
            --profile-success: #22c55e;
            --profile-danger: #ef4444;
            --profile-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --profile-radius-lg: 1rem;
            --profile-radius-md: 0.5rem;
            --profile-transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --profile-bg: #111827;
                --profile-card-bg: #1f2937;
                --profile-text-primary: #f3f4f6;
                --profile-text-secondary: #9ca3af;
                --profile-border-color: #374151;
                --profile-primary: #6366f1;
                --profile-primary-hover: #4f46e5;
                --profile-secondary: #34d399;
                --profile-success: #4ade80;
                --profile-danger: #f87171;
                --profile-shadow: 0 4px 6px -1px rgb(255 255 255 / 0.05), 0 2px 4px -2px rgb(255 255 255 / 0.05);
            }
        }

        body {
            background-color: var(--profile-bg);
            color: var(--profile-text-primary);
            font-family: 'Tajawal', sans-serif;
        }

        .profile-edit-container {
            padding: 2rem 1rem;
            max-width: 900px;
            margin: auto;
        }

        /* --- تصميم بطاقة التعديل --- */
        .edit-profile-card {
            background: var(--profile-card-bg);
            border-radius: var(--profile-radius-lg);
            padding: clamp(1.5rem, 5vw, 2.5rem);
            /* Padding متجاوب */
            box-shadow: var(--profile-shadow);
            border: 1px solid var(--profile-border-color);
            animation: fadeInUp 0.5s ease-out;
        }

        .edit-card-header {
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--profile-border-color);
        }

        .edit-card-title {
            font-size: clamp(1.25rem, 4vw, 1.5rem);
            /* Font-size متجاوب */
            font-weight: 700;
            color: var(--profile-text-primary);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .edit-card-title i {
            color: var(--profile-primary);
        }

        /* --- تصميم حقول النموذج --- */
        .form-group {
            margin-bottom: 1.75rem;
            position: relative;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--profile-text-secondary);
            font-size: 0.9rem;
        }

        .form-control {
            display: block;
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            font-weight: 500;
            line-height: 1.5;
            color: var(--profile-text-primary);
            background-color: var(--profile-card-bg);
            /* لون الخلفية الافتراضي */
            background-clip: padding-box;
            border: 1px solid var(--profile-border-color);
            appearance: none;
            border-radius: var(--profile-radius-md);
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.075);
            transition: var(--profile-transition);
        }

        /* تحسينات بسيطة للـ :focus */
        .form-control:focus {
            border-color: var(--profile-primary);
            outline: 0;
            background-color: var(--profile-card-bg);
            /* التأكد من بقاء لون الخلفية */
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.075), 0 0 0 0.2rem rgba(79, 70, 229, 0.2);
            /* تعديل طفيف للـ box-shadow */
        }

        /* تحسين الوضع الداكن للـ input */
        @media (prefers-color-scheme: dark) {
            .form-control {
                background-color: #374151;
                border-color: #4b5563;
            }

            .form-control:focus {
                background-color: #4b5563;
                border-color: var(--profile-primary);
                box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.075), 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
            }
        }

        /* أيقونة العين لكلمة المرور */
        .password-wrapper {
            position: relative;
        }

        .toggle-password-btn {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            /* تجعل الزر يأخذ ارتفاع الحقل */
            background: none;
            border: none;
            color: var(--profile-text-secondary);
            cursor: pointer;
            padding: 0 1rem;
            /* padding أفقي فقط */
            display: flex;
            align-items: center;
            /* محاذاة الأيقونة عمودياً */
            z-index: 3;
            /* للتأكد أنه فوق الحقل */
        }

        .toggle-password-btn:focus {
            outline: none;
            box-shadow: none;
        }

        /* إزالة التأثير عند التركيز */
        .form-control[type="password"],
        .form-control[type="text"] {
            padding-left: 3.5rem;
        }

        /* زيادة المساحة لزر العين */

        /* --- حقل رفع الصورة --- */
        .image-upload-wrapper {
            display: flex;
            flex-wrap: wrap;
            /* للسماح بالالتفاف */
            align-items: center;
            gap: 1.5rem;
        }

        .current-image-preview {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--profile-border-color);
            flex-shrink: 0;
        }

        .file-input-container {
            flex-grow: 1;
        }

        .file-input-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
        }

        .btn-upload {
            background-color: var(--profile-card-bg);
            color: var(--profile-primary);
            border: 1px dashed var(--profile-primary);
            padding: 0.5rem 1rem;
            border-radius: var(--profile-radius-md);
            cursor: pointer;
            transition: var(--profile-transition);
            font-size: 0.85rem;
        }

        .btn-upload:hover {
            background-color: rgba(79, 70, 229, 0.05);
        }

        .file-input-wrapper input[type=file] {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            cursor: pointer;
            height: 100%;
            width: 100%;
            font-size: 0;
            /* إخفاء نص الإدخال الافتراضي */
        }

        .file-name-display {
            margin-top: 0.5rem;
            font-size: 0.8rem;
            color: var(--profile-text-secondary);
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 250px;
            /* تحديد عرض أقصى لاسم الملف */
        }

        @media (prefers-color-scheme: dark) {
            .btn-upload {
                border-color: var(--profile-primary);
                color: var(--profile-primary);
                background-color: transparent;
            }

            .btn-upload:hover {
                background-color: rgba(99, 102, 241, 0.1);
            }
        }


        /* --- أزرار الحفظ والإلغاء --- */
        .form-actions {
            margin-top: 2.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--profile-border-color);
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
        }

        .btn-submit,
        .btn-cancel {
            padding: 0.65rem 1.5rem;
            /* تعديل طفيف */
            border-radius: var(--profile-radius-md);
            font-weight: 600;
            font-size: 0.9rem;
            transition: var(--profile-transition);
            border: 1px solid transparent;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
        }

        .btn-submit {
            background-color: var(--profile-primary);
            color: white;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
        }

        .btn-submit:hover {
            background-color: var(--profile-primary-hover);
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            transform: translateY(-1px);
        }

        .btn-cancel {
            background-color: transparent;
            color: var(--profile-text-secondary);
            border-color: var(--profile-border-color);
            text-decoration: none;
        }

        .btn-cancel:hover {
            background-color: #f9fafb;
            border-color: #d1d5db;
            color: var(--profile-text-primary);
        }

        .avatar-upload {
            position: relative;
            width: 100px;
            height: 180px;
            margin: 0 auto;
        }

        .avatar-upload img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .avatar-upload label {
            position: absolute;
            bottom: 10px;
            right: 10px;
            width: 40px;
            height: 40px;
            background: var(--secondary-gradient);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .avatar-upload label i {
            color: white;
            font-size: 18px;
        }

        .avatar-upload input[type="file"] {
            display: none;
        }

        @media (prefers-color-scheme: dark) {
            .btn-cancel {
                background-color: transparent;
                border-color: var(--profile-border-color);
                color: var(--profile-text-secondary);
            }

            .btn-cancel:hover {
                background-color: #374151;
                border-color: #4b5563;
                color: var(--profile-text-primary);
            }
        }

        /* أنماط التحقق (Validation) */
        /* إضافة أيقونات للتحقق */
        .form-control.is-invalid,
        .was-validated .form-control:invalid {
            border-color: var(--profile-danger);
            padding-right: calc(1.5em + .75rem);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23ef4444'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23ef4444' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(.375em + .1875rem) center;
            background-size: calc(.75em + .375rem) calc(.75em + .375rem);
        }

        .form-control.is-valid,
        .was-validated .form-control:valid {
            border-color: var(--profile-success);
            padding-right: calc(1.5em + .75rem);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3e%3cpath fill='%2322c55e' d='M2.3 6.73.6 4.53c-.4-1.04.46-1.4.84-1.4.38 0 .8.2.98.4L3.6 4.4l3.72-3.55c.17-.16.5-.4.86-.4.4 0 1.4.5.84 1.4L2.3 6.73z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(.375em + .1875rem) center;
            background-size: calc(.75em + .375rem) calc(.75em + .375rem);
        }

        .invalid-feedback {
            display: none;
            width: 100%;
            margin-top: .25rem;
            font-size: .875em;
            color: var(--profile-danger);
        }

        .was-validated .form-control:invalid~.invalid-feedback,
        .form-control.is-invalid~.invalid-feedback {
            display: block;
        }

        /* تعديل لزر العين مع أيقونات التحقق */
        .password-wrapper .form-control.is-valid,
        .password-wrapper .form-control.is-invalid {
            padding-left: 3.5rem;
            /* مساحة لزر العين */
            padding-right: calc(1.5em + .75rem);
            /* مساحة لأيقونة التحقق */
        }

        /* --- Responsive --- */
        @media (max-width: 576px) {
            .edit-profile-card {
                padding: 1.5rem;
            }

            .form-actions {
                flex-direction: column;
                align-items: stretch;
                /* تمدد الأزرار */
                gap: 0.5rem;
            }

            .profile-actions-modern {
            padding: 25px 30px;
            text-align: center;
            /* جعله في الوسط */
            border-top: 1px solid #eee;
        }

            .btn-submit,
            .btn-cancel {
                width: 100%;
                justify-content: center;
            }

            /* توسيط النص والأيقونة */
        }

        /* --- Animations --- */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(15px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection

@section('title')
    تعديل الملف الشخصي | د. {{ $doctor->name }}
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto">الملف الشخصي</h4>
                <span class="text-muted mt-1 tx-13 fw-light mx-2">/</span> {{-- استخدام فاصل أخف --}}
                <span class="text-muted tx-13">تعديل البيانات</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content align-items-center gap-2"> {{-- استخدام gap --}}
            {{-- رابط العودة لصفحة العرض --}}
            <a href="{{ route('doctor.profile.show') }}"
                class="btn btn-outline-light btn-sm d-inline-flex align-items-center">
                <i class="fas fa-arrow-left me-1 tx-11"></i> العودة للملف الشخصي
            </a>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

{{-- ====================== HTML Content Section ===================== --}}
@section('content')
    {{-- تضمين رسائل النجاح والخطأ --}}
    @include('Dashboard.messages_alert')

    <div class="profile-edit-container">
        <div class="edit-profile-card">
            {{-- رأس بطاقة التعديل --}}
            <div class="edit-card-header">
                <h3 class="edit-card-title">
                    <i class="fas fa-user-edit"></i> {{-- تغيير الأيقونة --}}
                    تعديل بيانات الملف الشخصي
                </h3>
            </div>

            {{-- الفورم الرئيسي --}}
            <form action="{{ route('doctor.profile.update') }}" method="POST" enctype="multipart/form-data"
                class="needs-validation" novalidate>
                @method('PUT')
                @csrf

                {{-- صف الحقول --}}
                <div class="row g-3"> {{-- استخدام g-3 لإضافة فجوات بين الأعمدة --}}

                    {{-- الاسم الكامل --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name" class="form-label">الاسم الكامل <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name"
                                class="form-control @error('name') is-invalid @enderror" value="{{ $doctor->name }}"
                                required aria-describedby="nameHelp">
                            @error('name')
                                <div class="invalid-feedback" id="nameError">{{ $message }}</div>
                            @else
                                <div class="invalid-feedback" id="nameError">حقل الاسم مطلوب.</div>
                            @enderror
                            <small id="nameHelp" class="form-text text-muted">الاسم كما سيظهر للمرضى.</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="floating-label">
                            <label>{{ trans('doctors.national_id') }}</label>
                            <input class="form-control" id="national_id" name="national_id"
                                placeholder="ادخل رقم الهوية (9 أرقام)" type="text" value="{{ $doctor->national_id }}"
                                pattern="[0-9]{9}" title="يجب أن يتكون رقم الهوية من 9 أرقام" maxlength="9" required
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            <div class="invalid-feedback">يجب أن يتكون رقم الهوية من 9 أرقام</div>
                            <div class="valid-feedback"><i class="fas fa-check"></i> صحيح</div>
                        </div>
                    </div>
                    {{-- البريد الإلكتروني --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email" class="form-label">البريد الإلكتروني <span
                                    class="text-danger">*</span></label>
                            <input type="email" id="email" name="email"
                                class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $doctor->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <div class="invalid-feedback">يرجى إدخال بريد إلكتروني صحيح.</div>
                            @enderror
                        </div>
                    </div>

                    {{-- رقم الهاتف --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone" class="form-label">رقم الهاتف <span class="text-danger">*</span></label>
                            <input type="tel" id="phone" name="phone"
                                class="form-control @error('phone') is-invalid @enderror"
                                value="{{ old('phone', $doctor->phone) }}" required placeholder="05xxxxxxxx"
                                pattern="^05\d{8}$" title="يجب أن يبدأ بـ 05 ويتكون من 10 أرقام">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <div class="invalid-feedback">صيغة رقم الهاتف غير صحيحة (مثال: 05xxxxxxxx).</div>
                            @enderror
                        </div>
                    </div>

                    {{-- رفع صورة الملف الشخصي --}}
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="image" class="form-label">الصورة الشخصية</label>
                            <div class="avatar-upload mb-4">
                                @if ($doctor->image)
                                    <img id="output"
                                        src="{{ URL::asset('Dashboard/img/doctors/' . $doctor->image->filename) }}"
                                        alt="{{ trans('doctors.img') }}">
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
                            @error('image')
                                <div class="invalid-feedback d-block mt-2">{{ $message }}</div> {{-- جعل الخطأ يظهر دائماً إذا وجد --}}
                            @enderror
                        </div>
                    </div>

                    {{-- فاصل قسم كلمة المرور --}}
                    <div class="col-12">
                        <hr class="my-4">
                        <p class="text-muted small mb-3">لتغيير كلمة المرور، أدخل كلمة المرور الحالية والجديدة (اترك الحقول
                            فارغة إذا لم تكن تريد التغيير):</p>
                    </div>

                    {{-- كلمة المرور الحالية --}}
                    <div class="col-md-6">
                        <div class="form-group password-wrapper">
                            <label for="current_password" class="form-label">كلمة المرور الحالية</label>
                            <input type="password" id="current_password" name="current_password"
                                class="form-control @error('current_password') is-invalid @enderror"
                                autocomplete="current-password" aria-describedby="currentPasswordHelp">
                            <button type="button" class="toggle-password-btn"
                                onclick="togglePasswordVisibility('current_password')" tabindex="-1"
                                aria-label="إظهار / إخفاء كلمة المرور الحالية">
                                <i class="fas fa-eye"></i>
                            </button>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small id="currentPasswordHelp" class="form-text text-muted">مطلوبة فقط إذا أردت تغيير كلمة
                                المرور.</small>
                        </div>
                    </div>
                    {{-- ترك عمود فارغ للمباعدة إن أردت --}}
                    {{-- <div class="col-md-6"></div> --}}

                    {{-- كلمة المرور الجديدة --}}
                    <div class="col-md-6">
                        <div class="form-group password-wrapper">
                            <label for="new_password" class="form-label">كلمة المرور الجديدة</label>
                            <input type="password" id="new_password" name="new_password"
                                class="form-control @error('new_password') is-invalid @enderror"
                                autocomplete="new-password" aria-describedby="newPasswordHelp">
                            <button type="button" class="toggle-password-btn"
                                onclick="togglePasswordVisibility('new_password')" tabindex="-1"
                                aria-label="إظهار / إخفاء كلمة المرور الجديدة">
                                <i class="fas fa-eye"></i>
                            </button>
                            @error('new_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small id="newPasswordHelp" class="form-text text-muted">8 أحرف على الأقل، تحتوي حروف
                                وأرقام.</small>
                        </div>
                    </div>

                    {{-- تأكيد كلمة المرور الجديدة --}}
                    <div class="col-md-6">
                        <div class="form-group password-wrapper">
                            <label for="new_password_confirmation" class="form-label">تأكيد كلمة المرور الجديدة</label>
                            <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                                class="form-control" {{-- لا نحتاج is-invalid هنا مباشرة --}} autocomplete="new-password"
                                aria-describedby="newPasswordConfirmationHelp">
                            <button type="button" class="toggle-password-btn"
                                onclick="togglePasswordVisibility('new_password_confirmation')" tabindex="-1"
                                aria-label="إظهار / إخفاء تأكيد كلمة المرور">
                                <i class="fas fa-eye"></i>
                            </button>
                            {{-- رسالة الخطأ لعدم التطابق تأتي من 'new_password.confirmed' وتعرض تحت حقل new_password --}}
                            <small id="newPasswordConfirmationHelp" class="form-text text-muted">أعد كتابة كلمة المرور
                                الجديدة للتأكيد.</small>
                        </div>
                    </div>

                </div> {{-- نهاية .row --}}

                {{-- أزرار الحفظ والإلغاء --}}
                <div class="form-actions">
                    <a href="{{ route('doctor.profile.show') }}" class="btn-cancel"> {{-- تأكد من اسم مسار عرض ملف الطبيب --}}
                        <i class="fas fa-times me-1"></i> إلغاء
                    </a>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save me-1"></i> حفظ التغييرات
                    </button>
                </div>

            </form> {{-- نهاية الفورم --}}
        </div> {{-- نهاية .edit-profile-card --}}
    </div> {{-- نهاية .doctor-profile-container --}}
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
            // البحث عن الزر بشكل أكثر دقة داخل نفس الحاوية
            const wrapper = passwordInput.closest('.password-wrapper');
            const button = wrapper ? wrapper.querySelector('.toggle-password-btn') : null;
            const icon = button ? button.querySelector('i') : null;

            if (passwordInput && icon) {
                const isPassword = passwordInput.type === "password";
                passwordInput.type = isPassword ? "text" : "password";
                icon.classList.toggle("fa-eye", !isPassword);
                icon.classList.toggle("fa-eye-slash", isPassword);
                // تحديث aria-label للزر (لإمكانية الوصول)
                button.setAttribute('aria-label', isPassword ? 'إخفاء كلمة المرور' : 'إظهار كلمة المرور');
            }
        }

        // --- عرض اسم الملف المختار ومعاينة الصورة ---
        function displayFileNameAndPreview(input) {
            const fileNameDisplay = document.getElementById('fileNameDisplay');
            const preview = document.getElementById('imagePreview');
            // الحصول على URL الصورة الأصلية في حال احتاج المستخدم لإلغاء الاختيار
            const originalImageUrl =
                "{{ $doctor->image ? Storage::url('doctors/' . $doctor->image->filename) : URL::asset('Dashboard/img/faces/doctor_default.png') }}";

            if (input.files && input.files.length > 0) {
                const file = input.files[0];
                fileNameDisplay.textContent = file.name; // عرض اسم الملف

                // قراءة وعرض معاينة الصورة
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (preview) {
                        preview.src = e.target.result; // تحديث مصدر الصورة للمعاينة
                    }
                }
                // التحقق من نوع الملف قبل قراءته (اختياري لكن جيد)
                if (file.type.startsWith('image/')) {
                    reader.readAsDataURL(file);
                } else {
                    // إذا لم يكن الملف صورة، عرض رسالة وإعادة الصورة الأصلية
                    fileNameDisplay.textContent = "الملف المختار ليس صورة.";
                    if (preview) preview.src = originalImageUrl;
                    input.value = ''; // مسح قيمة حقل الإدخال
                    input.classList.add('is-invalid'); // إضافة علامة خطأ
                }

            } else {
                // في حالة عدم اختيار ملف (أو إلغاء الاختيار)
                fileNameDisplay.textContent = "لم يتم اختيار ملف";
                if (preview) {
                    preview.src = originalImageUrl; // إعادة الصورة الأصلية
                }
                input.classList.remove('is-invalid'); // إزالة علامة الخطأ إن وجدت
            }
        }


        // --- تفعيل Bootstrap Validation (للتحقق من جانب العميل) ---
        (function() {
            'use strict';
            window.addEventListener('load', function() { // التأكد من تحميل كل شيء
                var forms = document.querySelectorAll('.needs-validation');
                Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault();
                            event.stopPropagation();
                            // التركيز على أول حقل غير صالح (اختياري)
                            const firstInvalidField = form.querySelector(':invalid');
                            if (firstInvalidField) {
                                firstInvalidField.focus();
                            }
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();

        // --- إظهار إشعارات NotifIt بناءً على رسائل الجلسة (Session Flash) ---
        @if (session('success'))
            if (typeof notif !== 'undefined') {
                notif({
                    msg: "{{ session('success') }}",
                    type: "success",
                    position: "center",
                    timeout: 5000
                });
            } else {
                console.log("Success: {{ session('success') }}"); // Log للمتصفح كبديل
            }
        @elseif (session('error'))
            if (typeof notif !== 'undefined') {
                notif({
                    msg: "{{ session('error') }}",
                    type: "error",
                    position: "center",
                    timeout: 7000
                });
            } else {
                console.error("Error: {{ session('error') }}"); // Log للمتصفح كبديل
            }
        @endif
    </script>
    <script>
        var loadFile = function(event) {
            var output = document.getElementById('output');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src);
            }
        };
    </script>
@endsection
