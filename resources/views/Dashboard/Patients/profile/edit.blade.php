{{-- resources/views/Dashboard/patient_panel/profile/edit.blade.php --}}
@extends('Dashboard.layouts.master') {{-- أو الـ layout الخاص بلوحة تحكم المريض --}}

@php
    // استخدام $patient->name مباشرة لأنه يعمل معك لعرض الاسم
    $patientName = $patient->name ?? 'ملفي الشخصي';
@endphp
@section('title', 'تعديل الملف الشخصي | ' . $patientName)

@section('css')
    @parent
    <!-- Flatpickr CSS -->
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Notify CSS -->
    <link href="{{ URL::asset('dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    {{-- Font Awesome (إذا لم يكن مضمنًا بشكل عام) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />


    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4895ef;
            --light-color: #f8f9fa;
            --dark-color: #212529;
        }

        .patient-profile-form-container {
            /* تغيير اسم الكلاس ليكون خاصًا بهذه الصفحة */
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin-top: 20px;
            /* إضافة هامش علوي */
        }

        .form-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 20px;
            margin-bottom: 30px;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }

        .form-header h3 {
            margin-bottom: 5px;
        }

        .form-header p {
            font-size: 0.9rem;
            opacity: 0.9;
        }


        .form-section {
            padding: 0 30px 30px;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--dark-color);
        }

        .form-control,
        .form-select {
            border: 1px solid #e0e5ec;
            /* تعديل لون الحد قليلاً */
            border-radius: 8px;
            padding: 12px 15px;
            transition: all 0.3s;
            height: auto;
            /* ليتناسب مع Select2 */
            font-size: 0.95rem;
            background-color: #fdfdff;
            /* خلفية أفتح قليلاً */
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(72, 149, 239, 0.15);
            background-color: white;
        }

        .datepicker-input {
            position: relative;
        }

        .datepicker-input i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-color);
            pointer-events: none;
        }

        /* Select2 Styles */
        .select2-container--default .select2-selection--single {
            border: 1px solid #e0e5ec;
            /* نفس لون حدود الحقول الأخرى */
            border-radius: 8px;
            height: calc(1.5em + (12px * 2) + (1px * 2));
            /* حساب الارتفاع ليتناسب */
            padding: 10px 12px;
            /* تعديل الحشو */
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: normal;
            /* لضمان محاذاة النص بشكل صحيح */
            padding-left: 0;
            /* إزالة الحشو الافتراضي */
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: calc(1.5em + (12px * 2));
            /* تعديل ارتفاع السهم */
            right: 8px;
        }

        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(72, 149, 239, 0.15);
        }


        .btn-submit-profile {
            /* تغيير اسم الكلاس */
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s;
            color: white;
            border-radius: 8px;
        }

        .btn-submit-profile:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }

        .section-title-divider {
            /* خط فاصل بين الأقسام */
            border: 0;
            height: 1px;
            background-image: linear-gradient(to right, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.15), rgba(0, 0, 0, 0));
            margin: 30px 0;
        }

        /* تصميم قسم الصورة */
        .avatar-upload-profile {
            /* تغيير اسم الكلاس */
            position: relative;
            width: 100px;
            /* تعديل الحجم قليلاً */
            height: 140px;
            margin: 0 auto 25px;
            /* توسيط */
            border-radius: 50%;
            border: 4px solid white;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .avatar-upload-profile img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar-upload-profile label {
            position: absolute;
            bottom: 5px;
            right: 5px;
            width: 35px;
            height: 35px;
            background: var(--accent-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s;
        }

        .avatar-upload-profile label:hover {
            background: var(--primary-color);
        }

        .avatar-upload-profile label i {
            font-size: 1rem;
        }

        .avatar-upload-profile input[type="file"] {
            display: none;
        }

        /* تبديل كلمة المرور */
        .password-toggle-group {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            left: 10px;
            /* تعديل الموضع */
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: #6c757d;
            cursor: pointer;
            padding: 5px;
            z-index: 3;
        }

        .form-control[type="password"] {
            padding-left: 40px;
            /* مساحة للزر */
        }


        @media (max-width: 768px) {
            .form-section {
                padding: 0 15px 20px;
            }

            .form-header {
                text-align: center;
            }
        }
    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto"><i class="fas fa-user-circle me-2"
                        style="color:var(--primary-color);"></i>ملفي الشخصي</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تعديل بياناتي</span>
            </div>
        </div>
        {{-- يمكنك إضافة زر للعودة إلى لوحة تحكم المريض الرئيسية هنا إذا كانت مختلفة عن صفحة الملف الشخصي --}}
        {{-- <div class="d-flex my-xl-auto right-content">
            <a href="{{ route('patient.dashboard') }}" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-tachometer-alt me-1"></i> لوحة التحكم
            </a>
        </div> --}}
    </div>
@endsection

@section('content')
    @include('Dashboard.messages_alert')

    <div class="row justify-content-center">
        <div class="col-lg-10 col-md-12"> {{-- تعديل عرض العمود --}}
            <div class="patient-profile-form-container">
                <div class="form-header">
                    <h3><i class="fas fa-edit"></i> تعديل الملف الشخصي</h3>
                    <p class="mb-0">أهلاً بك {{ $patientName }}، قم بتحديث بياناتك هنا.</p>
                </div>
                <div class="form-section">
                    <form action="{{ route('profile.update') }}" method="post" autocomplete="off"
                        id="patientProfileForm" enctype="multipart/form-data" class="needs-validation" novalidate>
                        @method('PUT') {{-- أو PATCH --}}
                        @csrf
                        {{-- لا نحتاج حقل ID مخفي هنا لأننا نستخدم Auth::guard('patient')->id() في الـ Controller --}}

                        <div class="row mb-4">
                            <div class="col-12 text-center">
                                <div class="avatar-upload-profile">
                                    {{-- تأكد من مسار صور المرضى الصحيح --}}
                                    <img id="profile_photo_output"
                                        src="{{ $patient->image ? asset('Dashboard/img/patients/' . $patient->image->filename) : URL::asset('Dashboard/img/default_patient_avatar.png') }}"
                                        alt="الصورة الشخصية">
                                    <label for="profile_photo_input_field" title="تغيير الصورة">
                                        <i class="fas fa-camera"></i>
                                    </label>
                                    <input id="profile_photo_input_field" type="file" name="photo"
                                        onchange="previewPatientProfileImage(event, 'profile_photo_output')">
                                    {{-- اسم دالة JS مختلف --}}
                                </div>
                                @error('photo')
                                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>


                        <div class="row">
                            <!-- الاسم الكامل -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name_pat_profile" class="form-label">الاسم الكامل <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="name_pat_profile" name="name"
                                        value="{{ old('name', $patient->getTranslation('name', app()->getLocale(), false) ?: $patient->name) }}"
                                        class="form-control @error('name') is-invalid @enderror"
                                        placeholder="أدخل الاسم الكامل" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- البريد الإلكتروني -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email_pat_profile" class="form-label">البريد الإلكتروني <span
                                            class="text-danger">*</span></label>
                                    <input type="email" id="email_pat_profile" name="email"
                                        value="{{ old('email', $patient->email) }}"
                                        class="form-control @error('email') is-invalid @enderror"
                                        placeholder="example@domain.com" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- تاريخ الميلاد -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="Date_Birth_pat_profile">تاريخ الميلاد <span
                                            class="text-danger">*</span></label>
                                    <div class="datepicker-input">
                                        <input class="form-control @error('Date_Birth') is-invalid @enderror"
                                            id="Date_Birth_pat_profile" name="Date_Birth"
                                            value="{{ old('Date_Birth', $patient->Date_Birth) }}" type="text"
                                            autocomplete="off" required placeholder="اختر تاريخ ميلادك">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    @error('Date_Birth')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">رقم الهوية</label>
                                    <input type="text" name="national_id" title="يجب أن يتكون من 9 أرقام فقط"
                                        value="{{ $patient->national_id }}" pattern="[0-9]{9}"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')" maxlength="9"
                                        class="form-control @error('national_id') is-invalid @enderror"
                                        placeholder="أدخل رقم الهوية" required>
                                </div>
                            </div>

                            <!-- رقم الهاتف -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="Phone_pat_profile" class="form-label">رقم الهاتف <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        {{-- <span class="input-group-text">+966</span> --}}
                                        <input type="tel" id="Phone_pat_profile" name="Phone"
                                            value="{{ old('Phone', $patient->Phone) }}"
                                            class="form-control phone-input @error('Phone') is-invalid @enderror"
                                            placeholder="5xxxxxxxx" required>
                                    </div>
                                    <small class="form-text text-muted">أدخل 9 أرقام تبدأ بـ 5 (مثال: 501234567).</small>
                                    @error('Phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- الجنس -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="Gender_pat_profile" class="form-label">الجنس <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select @error('Gender') is-invalid @enderror" name="Gender"
                                        id="Gender_pat_profile" required>
                                        <option value="" disabled
                                            {{ old('Gender', $patient->Gender) ? '' : 'selected' }}>-- اختر --</option>
                                        <option value="1"
                                            {{ old('Gender', $patient->Gender) == 1 ? 'selected' : '' }}>ذكر</option>
                                        <option value="2"
                                            {{ old('Gender', $patient->Gender) == 2 ? 'selected' : '' }}>أنثى</option>
                                    </select>
                                    @error('Gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- فصيلة الدم -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="Blood_Group_pat_profile">فصيلة الدم</label>
                                    <select class="form-select @error('Blood_Group') is-invalid @enderror"
                                        name="Blood_Group" id="Blood_Group_pat_profile">
                                        <option value=""
                                            {{ old('Blood_Group', $patient->Blood_Group) ? '' : 'selected' }}>-- اختر --
                                        </option>
                                        @foreach (['O-', 'O+', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-'] as $bloodType)
                                            <option value="{{ $bloodType }}"
                                                {{ old('Blood_Group', $patient->Blood_Group) == $bloodType ? 'selected' : '' }}>
                                                {{ $bloodType }}</option>
                                        @endforeach
                                    </select>
                                    @error('Blood_Group')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- العنوان -->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label" for="Address_pat_profile">العنوان</label>
                                    <textarea rows="3" class="form-control @error('Address') is-invalid @enderror" name="Address"
                                        id="Address_pat_profile" placeholder="الحي، الشارع، المدينة">{{ old('Address', $patient->getTranslation('Address', app()->getLocale(), false) ?: $patient->Address) }}</textarea>
                                    @error('Address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr class="section-title-divider">

                        <!-- قسم كلمة المرور -->
                        <h5 class="section-title mb-4" style="font-size: 1.2rem;"><i class="fas fa-key"></i> تغيير كلمة
                            المرور</h5>
                        <div class="alert alert-info bg-light border-info text-info-emphasis" role="alert"
                            style="font-size: 0.9rem;">
                            <i class="fas fa-info-circle me-2"></i>
                            اترك حقول كلمة المرور فارغة إذا كنت لا تريد تغييرها.
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="current_password_pat_profile" class="form-label">كلمة المرور
                                        الحالية</label>
                                    <div class="password-toggle-group">
                                        <input type="password" id="current_password_pat_profile" name="current_password"
                                            class="form-control @error('current_password') is-invalid @enderror"
                                            placeholder="كلمة المرور الحالية (مطلوبة للتغيير)"
                                            autocomplete="current-password">
                                        <button class="btn toggle-password" type="button"
                                            data-target="current_password_pat_profile" tabindex="-1"><i
                                                class="fas fa-eye"></i></button>
                                    </div>
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_pat_profile" class="form-label">كلمة المرور الجديدة</label>
                                    <div class="password-toggle-group">
                                        <input type="password" id="password_pat_profile" name="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="8 أحرف على الأقل" autocomplete="new-password" minlength="8">
                                        <button class="btn toggle-password" type="button"
                                            data-target="password_pat_profile" tabindex="-1"><i
                                                class="fas fa-eye"></i></button>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation_pat_profile" class="form-label">تأكيد كلمة المرور
                                        الجديدة</label>
                                    <div class="password-toggle-group">
                                        <input type="password" id="password_confirmation_pat_profile"
                                            name="password_confirmation"
                                            class="form-control @error('password_confirmation') is-invalid @enderror"
                                            placeholder="أعد إدخال كلمة المرور" autocomplete="new-password"
                                            minlength="8">
                                        <button class="btn toggle-password" type="button"
                                            data-target="password_confirmation_pat_profile" tabindex="-1"><i
                                                class="fas fa-eye"></i></button>
                                    </div>
                                    @error('password_confirmation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4 pt-3 border-top">
                            <button type="submit" class="btn btn-submit-profile btn-lg">
                                <i class="fas fa-save me-2"></i> حفظ التغييرات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @parent
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ar.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Notify JS -->
    <script src="{{ URL::asset('dashboard/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('dashboard/plugins/notify/js/notifit-custom.js') }}"></script>

    <script>
        // معاينة الصورة
        if (typeof window.previewPatientProfileImage !== 'function') {
            window.previewPatientProfileImage = function(event, outputId) {
                const output = document.getElementById(outputId);
                const file = event.target.files[0];
                if (file) {
                    // (اختياري) التحقق من حجم ونوع الملف قبل المعاينة
                    if (file.size > 2 * 1024 * 1024) { // 2MB
                        notif({
                            msg: "حجم الصورة كبير جدًا (الحد الأقصى 2MB).",
                            type: "warning",
                            position: "center"
                        });
                        event.target.value = "";
                        return;
                    }
                    const validImageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml', 'image/webp'];
                    if (!validImageTypes.includes(file.type)) {
                        notif({
                            msg: "نوع الملف غير صالح. يرجى اختيار صورة.",
                            type: "warning",
                            position: "center"
                        });
                        event.target.value = "";
                        return;
                    }
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        output.src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            };
        }

        $(document).ready(function() { // استخدام jQuery لـ Select2 و Flatpickr
            // تهيئة Flatpickr للتاريخ
            $("#Date_Birth_pat_profile").flatpickr({ // استهداف الـ ID الصحيح
                dateFormat: "Y-m-d",
                locale: "ar",
                allowInput: true, // اسمح بالإدخال اليدوي إذا أردت
                clickOpens: true,
                disableMobile: false,
                maxDate: "today",
                monthSelectorType: "dropdown", // أسهل للتنقل
            });

            // تهيئة Select2 للجنس وفصيلة الدم
            $('#Gender_pat_profile, #Blood_Group_pat_profile').select2({
                placeholder: "-- اختر --",
                width: '100%',
                dropdownAutoWidth: true,
                dir: "rtl",
                minimumResultsForSearch: Infinity // لإخفاء حقل البحث إذا لم تكن هناك حاجة له
            });

            // إضافة زر التقويم بشكل برمجي (إذا كنت لا تزال تريد الأيقونة منفصلة)
            // $('.datepicker-input').click(function() {
            //     $('#Date_Birth_pat_profile').focus();
            // });

            // التحقق من النموذج قبل الإرسال
            $('#patientProfileForm').on('submit', function(e) {
                if (!this.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                    // رسالة خطأ عامة باستخدام NotifIt
                    notif({
                        msg: "يرجى ملء جميع الحقول المطلوبة بشكل صحيح.",
                        type: "warning",
                        position: "center",
                        timeout: 4000
                    });
                    // التركيز على أول حقل غير صالح
                    const firstInvalidField = $(this).find(':invalid:not(fieldset)').first();
                    if (firstInvalidField.length) {
                        $('html, body').animate({
                            scrollTop: firstInvalidField.offset().top - 100
                        }, 500, function() {
                            firstInvalidField.focus();
                        });
                    }
                }
                this.classList.add('was-validated');
            });

            // إظهار/إخفاء كلمة المرور
            $('.toggle-password').click(function() {
                const targetId = $(this).data('target');
                const input = $('#' + targetId);
                const icon = $(this).find('i');
                const type = input.attr('type') === 'password' ? 'text' : 'password';
                input.attr('type', type);
                icon.toggleClass('fa-eye fa-eye-slash');
                input.focus();
            });

            // عرض رسائل التنبيه NotifIt
            @if (session('success'))
                notif({
                    msg: "<i class='fas fa-check-circle me-2'></i> {{ session('success') }}",
                    type: "success",
                    position: "bottom",
                    autohide: true,
                    timeout: 5000,
                    zindex: 9999
                });
            @endif
            @if (session('error'))
                notif({
                    msg: "<i class='fas fa-exclamation-triangle me-2'></i> {{ session('error') }}",
                    type: "error",
                    position: "bottom",
                    autohide: true,
                    timeout: 7000,
                    zindex: 9999
                });
            @endif
            @if ($errors->any())
                let errorMsg =
                    "<strong><i class='fas fa-times-circle me-2'></i> يرجى تصحيح الأخطاء التالية:</strong><ul class='mb-0 ps-3 mt-2' style='list-style-type: none; padding-right: 0;'>";
                @foreach ($errors->all() as $error)
                    errorMsg += "<li>- {{ $error }}</li>";
                @endforeach
                errorMsg += "</ul>";
                notif({
                    msg: errorMsg,
                    type: "error",
                    position: "bottom",
                    multiline: true,
                    autohide: false,
                    zindex: 9999
                });
            @endif
        });
    </script>
@endsection
