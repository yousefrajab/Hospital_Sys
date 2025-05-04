<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام إدارة المرضى - مشروع تخرج 2025</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Tajawal Font -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;900&display=swap" rel="stylesheet">
    <!-- Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        :root {
            --primary-color: #3a86ff;
            --primary-dark: #2667cc;
            --secondary-color: #8338ec;
            --accent-color: #00b4d8;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --success-color: #38b000;
            --error-color: #ff006e;
            --warning-color: #ffbe0b;
            --card-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
            --transition-speed: 0.3s;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Tajawal', sans-serif;
            background: linear-gradient(135deg, #f0f4f8 0%, #dfe7f0 100%);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: var(--dark-color);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* تصميم الكارت الرئيسي */
        .registration-container {
            width: 100%;
            max-width: 950px;
            background: white;
            border-radius: 20px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            animation: fadeInUp 0.6s ease-out;
            margin: 20px;
            position: relative;
            transition: transform var(--transition-speed);
        }

        .registration-container:hover {
            transform: translateY(-5px);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* تصميم الهيدر */
        .form-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .form-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiPjxkZWZzPjxwYXR0ZXJuIGlkPSJwYXR0ZXJuIiB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHBhdHRlcm5Vbml0cz0idXNlclNwYWNlT25Vc2UiIHBhdHRlcm5UcmFuc2Zvcm09InJvdGF0ZSg0NSkiPjxyZWN0IHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCIgZmlsbD0icmdiYSgyNTUsMjU1LDI1NSwwLjA1KSIvPjwvcGF0dGVybj48L2RlZnM+PHJlY3QgZmlsbD0idXJsKCNwYXR0ZXJuKSIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIvPjwvc3ZnPg==');
            z-index: -1;
        }

        .form-header h3 {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-header p {
            margin: 10px 0 0;
            opacity: 0.9;
            font-size: 1rem;
            font-weight: 300;
        }

        .form-header i {
            font-size: 2.5rem;
            margin-bottom: 15px;
            display: inline-block;
            background: rgba(255, 255, 255, 0.2);
            width: 80px;
            height: 80px;
            line-height: 80px;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        /* تصميم قسم النموذج */
        .form-section {
            padding: 30px;
            position: relative;
        }

        .section-title {
            color: var(--primary-dark);
            font-weight: 700;
            margin-bottom: 20px;
            position: relative;
            padding-right: 15px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            right: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 5px;
            height: 25px;
            background: var(--accent-color);
            border-radius: 5px;
        }

        .password-toggle-group {
            position: relative;
        }

        .password-toggle-group input {
            padding-right: 4rem;
            /* مساحة لأيقونة العين */
        }

        .password-toggle-group .toggle-password {
            position: absolute;
            top: 50%;
            right: 0.75rem;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: #888;
            font-size: 1rem;
            cursor: pointer;
            z-index: 2;
        }


        /* تنسيقات الحقول */
        .paired-fields {
            display: flex;
            flex-direction: column;
            gap: 25px;
            margin-bottom: 30px;
        }

        .field-row {
            display: flex;
            gap: 25px;
            width: 100%;
        }

        .field-group {
            flex: 1;
            min-width: 0;
            position: relative;
        }

        .form-label {
            display: block;
            margin-bottom: 10px;
            font-weight: 600;
            color: var(--dark-color);
            position: relative;
        }

        .form-label.required::after {
            content: '*';
            color: var(--error-color);
            margin-right: 5px;
            font-size: 1.1em;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 14px 18px;
            width: 100%;
            font-size: 1rem;
            background-color: #f8f9fa;
            transition: all var(--transition-speed);
            font-family: 'Tajawal', sans-serif;
        }

        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 4px rgba(0, 180, 216, 0.2);
            background-color: white;
            outline: none;
        }


        .select2-container--default .select2-selection--single {
            height: 45px;
            border-radius: 12px;
            border: 1px solid #ced4da;
            padding: 8px 16px;
            font-size: 16px;
            transition: all 0.3s ease;
            background-color: #fdfdfd;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 10px;
            right: 10px;
        }

        .select2-results__option {
            padding: 10px 16px;
            font-size: 16px;
        }

        .select2-results__option--highlighted {
            background-color: #d2e3fc;
            color: #000;
        }


        .form-control.is-invalid {
            border-color: var(--error-color);
            background-image: none;
        }

        .form-control.is-valid {
            border-color: var(--success-color);
        }

        .input-group {
            position: relative;
            display: flex;
            align-items: stretch;
            width: 100%;
        }

        .input-group .form-control {
            flex: 1 1 auto;
            position: relative;
            z-index: 1;
        }

        .input-group .toggle-password {
            border: 2px solid #e9ecef;
            border-right: none;
            border-radius: 10px 0 0 10px;
            background: #f8f9fa;
            padding: 0 15px;
            color: var(--primary-color);
            cursor: pointer;
            transition: all var(--transition-speed);
        }

        .input-group .toggle-password:hover {
            background: #e9ecef;
        }

        .invalid-feedback {
            color: var(--error-color);
            font-size: 0.85rem;
            margin-top: 8px;
            display: none;
            animation: fadeIn 0.3s;
        }

        .valid-feedback {
            color: var(--success-color);
            font-size: 0.85rem;
            margin-top: 8px;
            display: none;
            animation: fadeIn 0.3s;
        }

        /* تصميم المعلومات الطبية */
        .medical-info {
            background-color: #f9fafc;
            border-radius: 15px;
            padding: 25px;
            margin-top: 25px;
            border-left: 5px solid var(--accent-color);
            position: relative;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.03);
        }

        .medical-info::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%2300b4d8" opacity="0.1"><path d="M19 8h-1V3H6v5H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zM8 5h8v3H8V5zm8 14H8v-4h8v4zm2-4v-2H6v2H4v-4c0-.55.45-1 1-1h14c.55 0 1 .45 1 1v4h-2z"/></svg>') no-repeat;
            background-size: contain;
        }

        /* تصميم زر الإرسال */
        .btn-submit {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            padding: 16px 32px;
            font-weight: 700;
            font-size: 1.1rem;
            letter-spacing: 0.5px;
            border-radius: 12px;
            color: white;
            cursor: pointer;
            width: 100%;
            max-width: 320px;
            margin: 30px auto 0;
            display: block;
            box-shadow: 0 6px 20px rgba(58, 134, 255, 0.3);
            transition: all var(--transition-speed);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .btn-submit::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--secondary-color), var(--primary-color));
            opacity: 0;
            z-index: -1;
            transition: opacity var(--transition-speed);
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(58, 134, 255, 0.4);
        }

        .btn-submit:hover::before {
            opacity: 1;
        }

        .btn-submit:active {
            transform: translateY(1px);
        }

        .btn-submit i {
            margin-left: 8px;
        }

        /* تصميم رسائل التنبيه */
        .alert-message {
            padding: 18px;
            border-radius: 12px;
            margin-bottom: 25px;
            display: none;
            position: relative;
            animation: fadeIn 0.5s;
            border-left: 5px solid;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .alert-success {
            background-color: rgba(56, 176, 0, 0.1);
            border-left-color: var(--success-color);
            color: var(--success-color);
        }

        .alert-error {
            background-color: rgba(255, 0, 110, 0.1);
            border-left-color: var(--error-color);
            color: var(--error-color);
        }

        .alert-message i {
            font-size: 1.2rem;
            margin-left: 10px;
            vertical-align: middle;
        }

        .countdown {
            position: absolute;
            left: 20px;
            font-weight: bold;
            font-size: 1rem;
        }

        /* تصميم التقويم المخصص */
        .flatpickr-calendar.custom-calendar {
            font-family: 'Tajawal', sans-serif;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            border: none;
        }

        .flatpickr-calendar.custom-calendar .flatpickr-month {
            height: 60px;
            background: var(--primary-color);
            color: white;
            border-radius: 12px 12px 0 0;
        }

        .flatpickr-calendar.custom-calendar .flatpickr-weekdays {
            background: #f8f9fa;
        }

        .flatpickr-calendar.custom-calendar .flatpickr-day.selected {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        /* تصميم Select2 المخصص
        .select2-container--default .select2-selection--single {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            height: auto;
            padding: 12px 15px;
            background-color: #f8f9fa;
        } */

        /* .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100%;
            left: 10px;
            right: auto;
        } */

        /* .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding-right: 0;
            color: var(--dark-color);
        }

        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 4px rgba(0, 180, 216, 0.2);
            background-color: white;
        } */

        /* تصميم النص الطبي */
        .medical-text {
            font-size: 0.9rem;
            color: #6c757d;
            margin-top: 5px;
            line-height: 1.5;
        }

        /* تأثيرات للهواتف */
        @media (max-width: 768px) {
            .registration-container {
                margin: 10px;
                border-radius: 15px;
                animation: fadeIn 0.6s ease-out;
            }

            .form-header {
                padding: 25px 20px;
            }

            .form-header h3 {
                font-size: 1.6rem;
            }

            .form-header i {
                font-size: 2rem;
                width: 70px;
                height: 70px;
                line-height: 70px;
            }

            .form-section {
                padding: 25px 20px;
            }

            .field-row {
                flex-direction: column;
                gap: 20px;
            }

            .field-group {
                width: 100%;
            }

            .btn-submit {
                padding: 14px 25px;
                font-size: 1rem;
                max-width: 100%;
            }

            .medical-info {
                padding: 20px;
            }
        }

        /* تأثيرات للشاشات الكبيرة */
        @media (min-width: 1200px) {
            .registration-container {
                max-width: 1000px;
            }
        }

        /* تأثيرات الحركة */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* تأثيرات الاهتزاز للخطأ */
        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            20%,
            60% {
                transform: translateX(-5px);
            }

            40%,
            80% {
                transform: translateX(5px);
            }
        }

        .shake {
            animation: shake 0.5s;
        }
    </style>
</head>

<body>
    <div class="registration-container">
        <div class="form-header">
            <i class="fas fa-user-plus"></i>
            <h3>نظام إدارة المرضى - 2025</h3>
            <p class="mb-0">نظام متكامل لإدارة سجلات المرضى وتقديم الرعاية الصحية الذكية</p>
        </div>

        <div class="form-section">
            <!-- رسائل التنبيه -->
            <div id="successAlert" class="alert-message alert-success animate__animated animate__fadeIn">
                <i class="fas fa-check-circle"></i> تم تسجيل المريض بنجاح في قاعدة البيانات!
                <span class="countdown">8</span>
            </div>

            <div id="errorAlert" class="alert-message alert-error animate__animated animate__fadeIn">
                <i class="fas fa-exclamation-circle"></i> حدث خطأ أثناء التسجيل، يرجى مراجعة البيانات المطلوبة.
                <span class="countdown">8</span>
            </div>

            <form action="{{ route('register.patient') }}" method="post" autocomplete="off" id="patientForm"
                enctype="multipart/form-data" novalidate>
                @csrf

                <!-- المعلومات الأساسية -->
                <h5 class="section-title">المعلومات الأساسية</h5>
                <div class="paired-fields">
                    <!-- الصف 1: الاسم والبريد -->
                    <div class="field-row">
                        <div class="field-group">
                            <label for="name" class="form-label required">الاسم الكامل للمريض</label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}"
                                class="form-control @error('name') is-invalid @enderror"
                                placeholder="الاسم الأول، الأب، الجد، العائلة" required
                                pattern="^([\u0600-\u06FF\s]{10,}|[A-Za-z\s\-']{10,})$">
                            <div class="invalid-feedback">يجب إدخال الاسم الكامل (على الأقل 10 أحرف)</div>
                            <div class="valid-feedback"><i class="fas fa-check"></i> صحيح</div>
                        </div>

                        <div class="field-group">
                            <label for="email" class="form-label required">البريد الإلكتروني</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}"
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="example@domain.com" required>
                            <div class="invalid-feedback">يرجى إدخال بريد إلكتروني صحيح</div>
                            <div class="valid-feedback"><i class="fas fa-check"></i> صحيح</div>
                        </div>
                    </div>

                    <!-- الصف 2: تاريخ الميلاد ورقم الهاتف -->
                    <div class="field-row">
                        <div class="field-group">
                            <label for="Date_Birth" class="form-label required">تاريخ الميلاد</label>
                            <div class="datepicker-input">
                                <input class="form-control" id="Date_Birth" name="Date_Birth" placeholder="يوم/شهر/سنة"
                                    type="text" autocomplete="off" required>
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div class="invalid-feedback">يرجى اختيار تاريخ الميلاد</div>
                        </div>

                        <div class="field-group mb-3">
                            <label for="Phone" class="form-label required">رقم الجوال</label>
                            <input type="tel" id="Phone" name="Phone" value="{{ old('Phone') }}"
                                class="form-control @error('Phone') is-invalid @enderror" placeholder="+9705XXXXXXXX"
                                maxlength="13" required pattern="^\+9705\d{8}$"
                                title="يجب أن يبدأ رقم الجوال بـ +9705 ويتكون من 13 خانة (مثال: +9705XXXXXXXX)">

                            <small class="form-text text-muted">
                                أدخل رقم الجوال بالتنسيق التالي: <strong>+9705XXXXXXXX</strong>
                            </small>

                            <div class="invalid-feedback">
                                يجب أن يبدأ رقم الجوال بـ +9705 ويتكون من 13 خانة (مثال: +9705XXXXXXXX)
                            </div>

                            <div class="valid-feedback">
                                <i class="fas fa-check-circle"></i> رقم صحيح
                            </div>
                        </div>

                    </div>

                    <!-- الصف 3: كلمة المرور وتأكيدها -->
                    <div class="field-row">
                        <div class="field-group">
                            <label for="password" class="form-label required">كلمة المرور</label>
                            <div class="input-group password-toggle-group">
                                <input type="password" id="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="أدخل كلمة المرور (8 أحرف على الأقل)" required minlength="8"
                                    pattern="^(?=.*[A-Za-z])(?=.*\d).{8,}$" title="يجب أن تحتوي على حروف وأرقام">
                                <button class="btn toggle-password" type="button" tabindex="-1">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback">يجب أن تحتوي كلمة المرور على 8 أحرف على الأقل وتشمل حروف
                                وأرقام</div>
                            <div class="valid-feedback"><i class="fas fa-check"></i> قوي</div>
                            <div class="medical-text">لأمان أفضل، استخدم مزيجًا من الحروف والأرقام</div>
                        </div>

                        <div class="field-group">
                            <label for="password_confirmation" class="form-label required">تأكيد كلمة المرور</label>
                            <div class="input-group password-toggle-group">
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="form-control" placeholder="أعد إدخال كلمة المرور" required>
                                <button class="btn toggle-password" type="button" tabindex="-1">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="invalid-feedback">كلمة المرور غير متطابقة</div>
                            <div class="valid-feedback"><i class="fas fa-check"></i> متطابق</div>
                        </div>
                    </div>

                </div>

                <!-- المعلومات الطبية -->
                <div class="paired-fields">
                    <div class="medical-info">
                        <h5 class="section-title"><i class="fas fa-heartbeat"></i> المعلومات الطبية</h5>

                        <div class="field-row">
                            <div class="field-group">
                                <label for="national_id" class="form-label required">رقم الهوية الوطنية</label>
                                <input class="form-control" id="national_id" name="national_id"
                                    placeholder="ادخل رقم الهوية (9 أرقام)" type="text" pattern="[0-9]{9}"
                                    title="يجب أن يتكون رقم الهوية من 9 أرقام" maxlength="9" required
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                <div class="invalid-feedback">يجب أن يتكون رقم الهوية من 9 أرقام</div>
                                <div class="valid-feedback"><i class="fas fa-check"></i> صحيح</div>
                            </div>

                            <div class="field-group">
                                <label for="Blood_Group" class="form-label required">فصيلة الدم</label>
                                <select class="form-control select2" id="Blood_Group" name="Blood_Group" required>
                                    <option value="" selected disabled>-- اختر فصيلة الدم --</option>
                                    <option value="O-">O-</option>
                                    <option value="O+">O+</option>
                                    <option value="A+">A+</option>
                                    <option value="A-">A-</option>
                                    <option value="B+">B+</option>
                                    <option value="B-">B-</option>
                                    <option value="AB+">AB+</option>
                                    <option value="AB-">AB-</option>
                                </select>
                                <div class="invalid-feedback">يرجى اختيار فصيلة الدم</div>
                                <div class="medical-text">مهم في حالات الطوارئ</div>
                            </div>
                        </div>

                        <div class="field-row">
                            <div class="field-group">
                                <label for="Gender" class="form-label required">الجنس</label>
                                <select class="form-control select2-gender" id="Gender" name="Gender" required>
                                    <option value="" selected disabled>-- اختر الجنس --</option>
                                    <option value="1" data-icon="♂️">ذكر</option>
                                    <option value="2" data-icon="♀️">أنثى</option>
                                </select>
                                <div class="invalid-feedback">يرجى اختيار الجنس</div>
                                <div class="valid-feedback"><i class="fas fa-check"></i> تم الاختيار</div>
                            </div>


                            <div class="field-group">
                                <label for="Address" class="form-label required">العنوان التفصيلي</label>
                                <textarea rows="3" class="form-control" id="Address" name="Address"
                                    placeholder="الحي، الشارع، المدينة، الرمز البريدي"></textarea>
                                <div class="invalid-feedback">يرجى إدخال العنوان التفصيلي</div>
                                <div class="medical-text">لأغراض التواصل والزيارات المنزلية</div>
                            </div>
                        </div>

                        <!-- الصف 4: الحساسيات والأمراض المزمنة -->
                        <div class="field-row">
                            <div class="field-group">
                                <label for="Allergies" class="form-label">الحساسيات المعروفة</label>
                                <textarea rows="2" class="form-control" id="Allergies" name="Allergies"
                                    placeholder="أدوية، أطعمة، مواد أخرى..."></textarea>
                                <div class="medical-text">اتركه فارغًا إذا لم يكن هناك حساسيات معروفة</div>
                            </div>

                            <div class="field-group">
                                <label for="Chronic_Diseases" class="form-label">الأمراض المزمنة</label>
                                <textarea rows="2" class="form-control" id="Chronic_Diseases" name="Chronic_Diseases"
                                    placeholder="السكري، الضغط، الربو..."></textarea>
                                <div class="medical-text">اتركه فارغًا إذا لم يكن هناك أمراض مزمنة</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- شروط وأحكام -->
                <div class="field-row mt-4">
                    <div class="field-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                            <label class="form-check-label" for="terms">
                                أوافق على <a href="#" style="color: var(--primary-color);">شروط الاستخدام</a> و
                                <a href="#" style="color: var(--primary-color);">سياسة الخصوصية</a>
                            </label>
                            <div class="invalid-feedback">يجب الموافقة على الشروط والأحكام</div>
                        </div>
                    </div>
                </div>

                <!-- زر الإرسال -->
                <div class="text-center mt-4">
                    <button type="submit"
                        class="btn-submit animate__animated animate__pulse animate__infinite animate__slower">
                        <i class="fas fa-user-plus"></i> تسجيل المريض
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Flatpickr -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ar.js"></script>
    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

    <script>
        $(document).ready(function() {
            // تهيئة Flatpickr للتاريخ
            flatpickr("#Date_Birth", {
                dateFormat: "Y-m-d",
                locale: "ar",
                allowInput: false,
                clickOpens: true,
                disableMobile: false,
                maxDate: "today",
                monthSelectorType: "static",
                position: "auto center",
                onOpen: function() {
                    document.querySelector('.flatpickr-calendar').classList.add('custom-calendar');
                }
            });

            // تهيئة Select2
            $('.select2').select2({
                placeholder: function() {
                    $(this).data('placeholder');
                },
                width: '100%',
                dropdownAutoWidth: true,
                dir: "rtl",
                dropdownParent: $('.registration-container')
            });

            // التحقق من النموذج في الوقت الحقيقي
            $('input, select, textarea').on('input change', function() {
                validateField($(this));
            });

            // التحقق من الحقل
            function validateField(field) {
                if (field.is(':invalid') || (field.is(':required') && !field.val())) {
                    field.addClass('is-invalid');
                    field.removeClass('is-valid');
                    field.nextAll('.invalid-feedback').show();
                    field.nextAll('.valid-feedback').hide();

                    if (field.is(':invalid') && field.val()) {
                        field.addClass('shake');
                        setTimeout(() => field.removeClass('shake'), 50);
                    }
                } else {
                    field.removeClass('is-invalid');
                    field.addClass('is-valid');
                    field.nextAll('.invalid-feedback').hide();
                    field.nextAll('.valid-feedback').show();
                }
            }

            // التحقق من النموذج قبل الإرسال
            $('#patientForm').on('submit', async function(e) {
                e.preventDefault(); // نمنع الإرسال التلقائي

                let isValid = true;
                $('input, select, textarea, #terms').each(function() {
                    validateField($(this));
                    if ($(this).is(':invalid') || ($(this).is(':required') && !$(this).val())) {
                        isValid = false;
                    }
                });

                if (!isValid) {
                    showErrorAlert();
                    $('html, body').animate({
                        scrollTop: $('.is-invalid').first().offset().top - 100
                    }, 500);
                    return;
                }

                const accepted = await showPrivacyPolicyModal();

                if (accepted) {
                    showSuccessAlert();

                    // نؤجل الإرسال 8 ثواني
                    setTimeout(() => {
                        const form = document.getElementById('patientForm');
                        form.submit(); // 👈 إرسال النموذج فعليًا (بدون jQuery off)
                    }, 8000);
                }
            });

            // عرض رسالة النجاح
            function showSuccessAlert() {
                $('#successAlert').fadeIn().addClass('animate__tada');
                $('#errorAlert').fadeOut();

                $('html, body').animate({
                    scrollTop: $('#successAlert').offset().top - 100
                }, 500);

                startCountdown(8, function() {
                    $('#successAlert').fadeOut();
                });
            }

            // عرض رسالة الخطأ
            function showErrorAlert() {
                $('#errorAlert').fadeIn().addClass('animate__headShake');
                $('#successAlert').fadeOut();

                startCountdown(8, function() {
                    $('#errorAlert').fadeOut();
                });
            }

            // عد تنازلي للإخطارات
            function startCountdown(seconds, callback) {
                let counter = seconds;
                const countdownElement = $('.countdown');
                countdownElement.text(counter);

                const interval = setInterval(function() {
                    counter--;
                    countdownElement.text(counter);

                    if (counter <= 0) {
                        clearInterval(interval);
                        if (typeof callback === 'function') {
                            callback();
                        }
                    }
                }, 1000);
            }

            // إظهار/إخفاء كلمة المرور
            $('.toggle-password').click(function() {
                const input = $(this).siblings('input');
                const icon = $(this).find('i');
                const type = input.attr('type') === 'password' ? 'text' : 'password';
                input.attr('type', type);
                icon.toggleClass('fa-eye fa-eye-slash');
            });

            function validatePasswordMatch() {
                const password = $('#password').val();
                const confirm = $('#password_confirmation').val();

                if (confirm !== password) {
                    $('#password_confirmation')[0].setCustomValidity('كلمة المرور غير متطابقة');
                } else {
                    $('#password_confirmation')[0].setCustomValidity('');
                }
            }

            // عند الكتابة في حقل التأكيد أو حقل كلمة المرور الأصلية
            $('#password, #password_confirmation').on('input', validatePasswordMatch);


            // تأثيرات عند التركيز على الحقول
            $('input, select, textarea').focus(function() {
                $(this).parent().css('transform', 'translateY(-2px)');
                $(this).parent().css('box-shadow', '0 5px 15px rgba(0, 0, 0, 0.1)');
            }).blur(function() {
                $(this).parent().css('transform', 'translateY(0)');
                $(this).parent().css('box-shadow', 'none');
            });

            // تحقق من قوة كلمة المرور
            $('#password').on('input', function() {
                const password = $(this).val();
                const strengthText = $(this).nextAll('.valid-feedback');

                if (password.length === 0) {
                    strengthText.html('<i class="fas fa-check"></i> صحيح');
                } else if (password.length < 8) {
                    strengthText.html('<i class="fas fa-exclamation-triangle"></i> ضعيفة');
                } else if (!/\d/.test(password) || !/[a-zA-Z]/.test(password)) {
                    strengthText.html('<i class="fas fa-check"></i> متوسطة');
                } else {
                    strengthText.html('<i class="fas fa-check"></i> قوية');
                }
            });
        });

        // إنشاء نافذة سياسة الخصوصية
        function showPrivacyPolicyModal() {
            return new Promise((resolve) => {
                const modalHTML = `
        <div id="privacyModal" style="
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 10000;
        ">
            <div style="
                background: white;
                padding: 30px;
                border-radius: 15px;
                max-width: 600px;
                width: 90%;
                max-height: 80vh;
                overflow-y: auto;
            ">
                <h3 style="color: var(--primary-color); margin-bottom: 20px;">
                    <i class="fas fa-shield-alt"></i> سياسة الخصوصية
                </h3>
                <div style="margin-bottom: 20px; line-height: 1.7;">
                    <p>باستخدامك لهذا النظام، فإنك توافق على شروط وسياسة الخصوصية الخاصة بنا:</p>
                    <ul style="padding-right: 20px; margin-top: 10px;">
                        <li>سيتم تخزين بياناتك الشخصية بشكل آمن</li>
                        <li>لا يتم مشاركة بياناتك مع أي جهات خارجية بدون موافقتك</li>
                        <li>يمكنك طلب حذف بياناتك في أي وقت</li>
                        <li>نستخدم أحدث تقنيات الحماية لحماية معلوماتك</li>
                    </ul>
                </div>
                <div style="display: flex; justify-content: space-between; margin-top: 30px;">
                    <button id="acceptPrivacy" style="
                        background: var(--primary-color);
                        color: white;
                        border: none;
                        padding: 10px 20px;
                        border-radius: 8px;
                        cursor: pointer;
                    ">
                        <i class="fas fa-check"></i> أوافق
                    </button>
                    <button id="cancelPrivacy" style="
                        background: var(--error-color);
                        color: white;
                        border: none;
                        padding: 10px 20px;
                        border-radius: 8px;
                        cursor: pointer;
                    ">
                        <i class="fas fa-times"></i> إلغاء
                    </button>
                </div>
            </div>
        </div>
        `;

                $('body').append(modalHTML);

                $('#acceptPrivacy').click(function() {
                    $('#privacyModal').remove();
                    resolve(true);
                });

                $('#cancelPrivacy').click(function() {
                    $('#privacyModal').remove();
                    resolve(false);
                });
            });
        }



        $(document).ready(function() {
            $('.select2-gender').select2({
                dir: "rtl",
                placeholder: "-- اختر الجنس --",
                minimumResultsForSearch: Infinity,
                templateResult: formatGenderOption,
                templateSelection: formatGenderOption
            });

            function formatGenderOption(state) {
                if (!state.id) return state.text;
                const icon = $(state.element).data('icon') || '';
                return $(`<span>${icon} ${state.text}</span>`);
            }
        });
    </script>




</body>

</html>
