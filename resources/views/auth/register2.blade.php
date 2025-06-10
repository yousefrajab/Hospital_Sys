<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام إدارة المرضى - تسجيل مريض جديد</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Tajawal Font -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;900&display=swap" rel="stylesheet">
    <!-- Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- Notify CSS (If you use it on this page) -->
    <link href="{{ URL::asset('dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />


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
            /* متغيرات إضافية لتوحيد المظهر مع لوحة التحكم */
            --admin-primary: var(--primary-color);
            --admin-border: #dee2e6;
            --admin-input-bg: #fdfdfd;
            --admin-text-secondary: #6c757d;
            --admin-radius-sm: 0.25rem;
            --admin-radius-md: 10px;
            --admin-danger-rgb: 255, 0, 110;
            --admin-light: var(--light-color);
            --admin-accent: var(--accent-color);
            --admin-success: var(--success-color);
            --admin-text: var(--dark-color);
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
            padding: 20px 0;
            /* إضافة padding للسماح بالـ scroll إذا كان النموذج طويلاً */
            display: flex;
            justify-content: center;
            align-items: flex-start;
            /* تغيير إلى flex-start للسماح بالـ scroll */
            min-height: 100vh;
            color: var(--dark-color);
            line-height: 1.6;
            overflow-x: hidden;
        }

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

        .form-header i.header-icon {
            /* كلاس جديد لأيقونة الهيدر */
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

        .form-section {
            padding: 30px;
            position: relative;
        }

        .section-title {
            /* هذا يستخدم كعنوان رئيسي للقسم */
            color: var(--primary-dark);
            font-weight: 700;
            margin-bottom: 25px;
            position: relative;
            padding-right: 20px;
            font-size: 1.5rem;
            border-bottom: 2px solid var(--accent-color);
            padding-bottom: 10px;
        }

        .section-title i {
            margin-left: 10px;
        }

        .section-title::after {
            content: none;
        }

        /* إزالة الخط العمودي إذا كان موجودًا */


        .password-toggle-group {
            position: relative;
        }

        .password-toggle-group input {
            padding-left: 3.5rem;
            /* مساحة لأيقونة العين في RTL */
        }

        .password-toggle-group .toggle-password {
            position: absolute;
            top: 50%;
            left: 0.75rem;
            /* لليسار في RTL */
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: #888;
            font-size: 1rem;
            cursor: pointer;
            z-index: 2;
            padding: 0.5rem;
        }

        .paired-fields {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-bottom: 25px;
        }

        /* تقليل gap و margin */
        .field-row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            width: 100%;
        }

        /* flex-wrap للسماح بالالتفاف */
        .field-group {
            flex: 1 1 calc(50% - 10px);
            /* أساس 50% مع خصم نصف الـ gap */
            min-width: 280px;
            /* حد أدنى للعرض قبل الالتفاف */
            position: relative;
            margin-bottom: 15px;
            /* تقليل الهامش */
        }

        .field-group.full-width {
            flex-basis: 100%;
            min-width: 100%;
        }

        /* كلاس لعرض كامل */


        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--dark-color);
            position: relative;
            font-size: 0.95rem;
        }

        .form-label.required::after {
            content: '*';
            color: var(--error-color);
            margin-right: 4px;
            font-size: 1em;
        }

        .form-control,
        .form-select {
            /* توحيد .form-select هنا */
            border: 2px solid #e9ecef;
            border-radius: var(--admin-radius-md);
            padding: 12px 15px;
            /* تعديل الحشو */
            width: 100%;
            font-size: 0.95rem;
            background-color: var(--admin-input-bg);
            /* استخدام متغير */
            transition: all var(--transition-speed);
            font-family: 'Tajawal', sans-serif;
            height: auto;
        }

        .form-control:focus,
        .form-select:focus,
        .select2-container--bootstrap-5.select2-container--focus .select2-selection--single,
        .select2-container--bootstrap-5.select2-container--open .select2-selection--single {
            border-color: var(--accent-color) !important;
            box-shadow: 0 0 0 3px rgba(0, 180, 216, 0.15) !important;
            /* تعديل الظل */
            background-color: white;
            outline: none;
        }

        .select2-container--default .select2-selection--single {
            height: calc(1.6em + (12px*2) + (2px*2));
            /* تعديل الارتفاع ليتناسب مع form-control */
            border-radius: var(--admin-radius-md);
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            font-size: 0.95rem;
            background-color: var(--admin-input-bg);
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 1.6;
            padding-left: 0;
            padding-right: 0;
            color: var(--dark-color);
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: calc(1.6em + (12px*2));
            top: 2px;
            left: 10px;
            right: auto;
        }

        /* RTL: right: 10px; left: auto; */
        .select2-container--bootstrap-5 .select2-dropdown {
            border: 1px solid var(--accent-color);
            border-radius: var(--admin-radius-md);
        }

        .select2-results__option--highlighted[aria-selected] {
            background-color: var(--primary-color);
            color: white;
        }


        .form-control.is-invalid {
            border-color: var(--error-color) !important;
            background-image: none;
        }

        .form-control.is-valid {
            border-color: var(--success-color) !important;
        }

        .avatar-upload-container {
            display: flex;
            justify-content: center;
            margin-bottom: 25px;
        }

        .avatar-upload {
            position: relative;
            width: 150px;
            height: 150px;
        }

        /* حجم أكبر للصورة */
        .avatar-upload img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid white;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
        }

        .avatar-upload label {
            position: absolute;
            bottom: 5px;
            right: 5px;
            width: 40px;
            height: 40px;
            background: linear-gradient(45deg, var(--primary-color), var(--accent-color));
            border-radius: 50%;
            display: flex;
            color: white;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.25);
            border: 2px solid white;
            transition: var(--transition-speed);
        }

        .avatar-upload label:hover {
            transform: scale(1.1);
        }

        .avatar-upload input[type="file"] {
            display: none;
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
            border-left: 0;
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        .input-group .toggle-password {
            border: 2px solid #e9ecef;
            border-right: none;
            border-radius: var(--admin-radius-md) 0 0 var(--admin-radius-md);
            background: var(--admin-input-bg);
            padding: 0 15px;
            color: var(--primary-color);
            cursor: pointer;
            transition: var(--transition-speed);
            z-index: 2;
        }

        .input-group .toggle-password:hover {
            background: #e0e0e0;
        }

        .invalid-feedback {
            color: var(--error-color);
            font-size: 0.875rem;
            margin-top: 0.3rem;
            display: none;
            animation: fadeIn 0.3s;
        }

        .valid-feedback {
            color: var(--success-color);
            font-size: 0.875rem;
            margin-top: 0.3rem;
            display: none;
            animation: fadeIn 0.3s;
        }

        .was-validated .form-control:invalid~.invalid-feedback,
        .form-control.is-invalid~.invalid-feedback,
        .was-validated .form-select:invalid~.invalid-feedback,
        .form-select.is-invalid~.invalid-feedback {
            display: block;
        }

        .was-validated .form-control:valid~.valid-feedback,
        .form-control.is-valid~.valid-feedback,
        .was-validated .form-select:valid~.valid-feedback,
        .form-select.is-valid~.valid-feedback {
            display: block;
        }


        .medical-info-wrapper {
            /* حاوية جديدة للمعلومات الطبية */
            background-color: var(--light-color);
            border-radius: 15px;
            padding: 25px;
            margin-top: 30px;
            border: 1px solid var(--admin-border);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.04);
        }

        .btn-submit {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            padding: 15px 30px;
            font-weight: 600;
            font-size: 1.05rem;
            /* تعديل طفيف */
            letter-spacing: 0.5px;
            border-radius: 10px;
            /* تعديل */
            color: white;
            cursor: pointer;
            width: auto;
            min-width: 280px;
            /* تعديل */
            margin: 30px auto 0;
            display: block;
            box-shadow: 0 5px 15px rgba(var(--primary-color), 0.25);
            /* استخدام متغير */
            transition: all var(--transition-speed);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .btn-submit::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(120deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: 0.5s;
            z-index: -1;
        }

        .btn-submit:hover {
            transform: translateY(-3px) scale(1.03);
            box-shadow: 0 8px 20px rgba(var(--primary-color), 0.3);
        }

        .btn-submit:hover::before {
            left: 100%;
        }

        .btn-submit:active {
            transform: translateY(0px) scale(0.98);
        }

        .btn-submit i {
            margin-left: 8px;
        }

        .btn-submit.loading .spinner-icon {
            display: inline-block !important;
            animation: spin 0.75s linear infinite;
            margin-left: 5px;
        }

        .spinner-icon {
            display: none;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }


        .alert-message {
            /* كما في الكود الأصلي */
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
            background-color: rgba(var(--success-color), 0.1);
            border-left-color: var(--success-color);
            color: var(--success-color);
        }

        .alert-error {
            background-color: rgba(var(--error-color), 0.1);
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

        .flatpickr-calendar.custom-calendar {
            /* كما في الكود الأصلي */
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

        .medical-text {
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 5px;
            line-height: 1.4;
        }

        /* تعديل طفيف */

        /* قسم الأمراض المزمنة (مأخوذ ومكيف من تصميم لوحة التحكم) */
        .chronic-diseases-section {
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px dashed var(--admin-border);
        }

        .chronic_disease_row {
            background-color: var(--admin-light);
            padding: 1rem;
            border-radius: var(--admin-radius-md);
            margin-bottom: 1rem;
            border: 1px solid #e9ecef;
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            align-items: flex-end;
            transition: var(--admin-transition);
        }

        .chronic_disease_row:hover {
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        }

        .chronic_disease_row .field-group {
            margin-bottom: 0;
        }

        /* إزالة الهامش من field-group داخل الصف */
        .chronic_disease_row .col-md-3-cd {
            flex: 0 0 calc(28% - 0.72rem);
            min-width: 180px;
        }

        /* كلاس جديد لتوزيع الأعمدة */
        .chronic_disease_row .col-md-2-cd {
            flex: 0 0 calc(20% - 0.8rem);
            min-width: 150px;
        }

        .chronic_disease_row .col-md-4-cd {
            flex: 0 0 calc(35% - 0.65rem);
            min-width: 200px;
        }

        .chronic_disease_row .col-md-1-cd {
            flex: 0 0 calc(7% - 0.93rem);
            text-align: center;
            min-width: 40px;
        }


        .chronic_disease_row .form-label {
            font-size: 0.85rem;
            margin-bottom: 0.3rem;
            color: var(--admin-text-secondary);
            font-weight: 500;
        }

        .chronic_disease_row .form-control,
        .chronic_disease_row .form-select,
        .chronic_disease_row .select2-container--bootstrap-5 .select2-selection {
            font-size: 0.9rem;
            padding: 0.6rem 0.9rem;
            background-color: white;
            height: auto;
            border-radius: var(--admin-radius-sm);
        }

        .remove_chronic_disease_row_btn {
            background-color: transparent;
            border: 1px solid rgba(var(--admin-danger-rgb), 0.5);
            color: var(--admin-danger);
            padding: 0;
            font-size: 0.9rem;
            border-radius: var(--admin-radius-sm);
            width: 36px;
            height: 36px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: var(--admin-transition);
            opacity: 0.8;
        }

        .remove_chronic_disease_row_btn:hover {
            background-color: var(--admin-danger);
            color: white;
            border-color: var(--admin-danger);
            transform: scale(1.05);
            opacity: 1;
        }

        #add_chronic_disease_btn_register {
            /* تمييز ID زر الإضافة لصفحة الإنشاء */
            background-color: var(--admin-success);
            border: none;
            color: white;
            font-weight: 500;
            padding: 0.6rem 1.2rem;
            font-size: 0.95rem;
            border-radius: var(--admin-radius-md);
            transition: var(--admin-transition);
            display: inline-flex;
            align-items: center;
            margin-top: 0.5rem;
        }

        #add_chronic_disease_btn_register:hover {
            background-color: #279b70;
            transform: translateY(-2px);
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
        }

        #add_chronic_disease_btn_register i {
            margin-left: 0.5rem;
        }

        /* RTL: margin-right */

        @media (max-width: 768px) {
            .registration-container {
                margin: 10px;
                border-radius: 15px;
            }

            .form-header {
                padding: 25px 20px;
            }

            .form-header h3 {
                font-size: 1.6rem;
            }

            .form-header i.header-icon {
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
                gap: 0px;
            }

            .field-group {
                width: 100%;
                margin-bottom: 1rem;
            }

            .btn-submit {
                padding: 14px 25px;
                font-size: 1rem;
                max-width: 100%;
            }

            .medical-info-wrapper {
                padding: 20px;
            }

            .chronic_disease_row>div[class*="col-md-"] {
                /* جعل أعمدة الأمراض تأخذ عرض كامل في الشاشات الصغيرة */
                flex: 0 0 100%;
                max-width: 100%;
                margin-bottom: 1rem;
            }

            .remove_chronic_disease_row_btn {
                margin-top: 0;
            }
        }

        @media (min-width: 1200px) {
            .registration-container {
                max-width: 1000px;
            }
        }
    </style>
</head>

<body>
    <div class="registration-container">
        <div class="form-header">
            <i class="fas fa-user-plus header-icon"></i>
            <h3>نظام إدارة المرضى - تسجيل جديد</h3>
            <p class="mb-0">نظام متكامل لإدارة سجلات المرضى وتقديم الرعاية الصحية الذكية.</p>
            <p class="mt-2"><small>لديك حساب بالفعل؟ <a href="#"
                        style="color: black; font-weight:bold;">تسجيل الدخول</a></small></p>
        </div>

        <div class="form-section">
            <!-- رسائل التنبيه -->
            <div id="successAlert" class="alert-message alert-success"></div>
            <div id="errorAlert" class="alert-message alert-error"></div>
            @if (session('status_success'))
                <div class="alert-message alert-success" style="display: block;">
                    <i class="fas fa-check-circle"></i> {{ session('status_success') }}
                </div>
            @endif
            @if ($errors->has('error_message'))
                <div class="alert-message alert-error" style="display: block;">
                    <i class="fas fa-exclamation-circle"></i> {{ $errors->first('error_message') }}
                </div>
            @endif


            <form action="{{ route('register.patient') }}" method="post" autocomplete="off" id="patientForm"
                enctype="multipart/form-data" novalidate class="needs-validation">
                @csrf

                <div class="avatar-upload-container">
                    <div class="avatar-upload">
                        <img id="output_register" src="{{ URL::asset('Dashboard/img/default_patient_avatar.png') }}"
                            alt="الصورة الشخصية">
                        <label for="avatar_upload_input_register"><i class="fas fa-camera"></i></label>
                        <input id="avatar_upload_input_register" type="file" accept="image/*" name="photo"
                            onchange="loadFile(event, 'output_register')">
                    </div>
                </div>
                @error('photo')
                    <div class="text-center text-danger mb-3">{{ $message }}</div>
                @enderror


                <h5 class="section-title"><i class="fas fa-user-circle"></i> المعلومات الأساسية</h5>
                <div class="paired-fields">
                    <div class="field-row">
                        <div class="field-group">
                            <label for="name_register" class="form-label required">الاسم الكامل للمريض</label>
                            <input type="text" id="name_register" name="name" value="{{ old('name') }}"
                                class="form-control @error('name') is-invalid @enderror" placeholder="الاسم رباعي"
                                required pattern="^([\u0600-\u06FF\s]{10,}|[A-Za-z\s\-']{10,})$">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <div class="valid-feedback"><i class="fas fa-check"></i></div>
                            @enderror
                        </div>
                        <div class="field-group">
                            <label for="email_register" class="form-label required">البريد الإلكتروني</label>
                            <input type="email" id="email_register" name="email" value="{{ old('email') }}"
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="example@domain.com" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <div class="valid-feedback"><i class="fas fa-check"></i></div>
                            @enderror
                        </div>
                    </div>
                    <div class="field-row">
                        <div class="field-group">
                            <label for="Date_Birth_register" class="form-label required">تاريخ الميلاد</label>
                            <input class="form-control flatpickr-date @error('Date_Birth') is-invalid @enderror"
                                id="Date_Birth_register" name="Date_Birth" value="{{ old('Date_Birth') }}"
                                placeholder="YYYY-MM-DD" type="text" autocomplete="off" required>
                            @error('Date_Birth')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <div class="valid-feedback"><i class="fas fa-check"></i></div>
                            @enderror
                        </div>
                        <div class="field-group">
                            <label for="Phone_register" class="form-label required">رقم الجوال</label>
                            <input type="tel" id="Phone_register" name="Phone" value="{{ old('Phone') }}"
                                class="form-control @error('Phone') is-invalid @enderror" placeholder="05xxxxxxxx"
                                maxlength="10" required pattern="^(05\d{8})$"
                                title="يجب أن يكون رقم جوال فلسطيني (مثال: 059xxxxxxx أو 056xxxxxxx)">
                            @error('Phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <div class="valid-feedback"><i class="fas fa-check"></i></div>
                            @enderror
                        </div>
                    </div>
                    <div class="field-row">
                        <div class="field-group">
                            <label for="password_register" class="form-label required">كلمة المرور</label>
                            <div class="input-group password-toggle-group"> {{-- استخدام input-group لتنسيق أفضل --}}
                                <input type="password" id="password_register" name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="8 أحرف (حروف وأرقام)" required minlength="8"
                                    pattern="^(?=.*[A-Za-z])(?=.*\d).{8,}$" title="يجب أن تحتوي على حروف وأرقام">
                                <button class="btn toggle-password" type="button" data-target="password_register"
                                    tabindex="-1"><i class="fas fa-eye"></i></button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <div class="valid-feedback"><i class="fas fa-check"></i></div>
                            @enderror
                        </div>
                        <div class="field-group">
                            <label for="password_confirmation_register" class="form-label required">تأكيد كلمة
                                المرور</label>
                            <div class="input-group password-toggle-group">
                                <input type="password" id="password_confirmation_register"
                                    name="password_confirmation" class="form-control"
                                    placeholder="أعد إدخال كلمة المرور" required>
                                <button class="btn toggle-password" type="button"
                                    data-target="password_confirmation_register" tabindex="-1"><i
                                        class="fas fa-eye"></i></button>
                            </div>
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @else
                                <div class="valid-feedback"><i class="fas fa-check"></i></div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="medical-info-wrapper">
                    <h5 class="section-title"><i class="fas fa-notes-medical"></i> المعلومات الطبية</h5>
                    <div class="paired-fields">
                        <div class="field-row">
                            <div class="field-group">
                                <label for="national_id_register" class="form-label required">رقم الهوية
                                    الوطنية</label>
                                <input class="form-control @error('national_id') is-invalid @enderror"
                                    id="national_id_register" name="national_id" placeholder="9 أرقام"
                                    type="text" pattern="[0-9]{9}" value="{{ old('national_id') }}"
                                    title="يجب أن يتكون رقم الهوية من 9 أرقام" maxlength="9" required
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                @error('national_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @else
                                    <div class="valid-feedback"><i class="fas fa-check"></i></div>
                                @enderror
                            </div>
                            <div class="field-group">
                                <label for="Blood_Group_register" class="form-label required">فصيلة الدم</label>
                                <select class="form-control select2 @error('Blood_Group') is-invalid @enderror"
                                    id="Blood_Group_register" name="Blood_Group" required
                                    data-placeholder="اختر فصيلة الدم">
                                    <option value=""></option>
                                    @php $bloodGroupsArray = ['O-', 'O+', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-']; @endphp
                                    @foreach ($bloodGroupsArray as $group)
                                        <option value="{{ $group }}"
                                            {{ old('Blood_Group') == $group ? 'selected' : '' }}>{{ $group }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('Blood_Group')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="field-row">
                            <div class="field-group">
                                <label for="Gender_register" class="form-label required">الجنس</label>
                                <select class="form-control select2 @error('Gender') is-invalid @enderror"
                                    id="Gender_register" name="Gender" required data-placeholder="اختر الجنس">
                                    <option value=""></option>
                                    <option value="1" {{ old('Gender') == 1 ? 'selected' : '' }}>ذكر</option>
                                    <option value="2" {{ old('Gender') == 2 ? 'selected' : '' }}>أنثى</option>
                                </select>
                                @error('Gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="field-group full-width"> {{-- استخدام full-width للعنوان --}}
                                <label for="Address_register" class="form-label required">العنوان التفصيلي</label>
                                <textarea rows="2" class="form-control @error('Address') is-invalid @enderror" id="Address_register"
                                    name="Address" placeholder="الحي، الشارع، المدينة">{{ old('Address') }}</textarea>
                                @error('Address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- حقل الحساسيات كنص حر --}}
                        <div class="field-row">
                            <div class="field-group full-width">
                                <label for="Allergies_register" class="form-label">الحساسيات المعروفة
                                    (اختياري)</label>
                                <textarea rows="2" class="form-control @error('Allergies') is-invalid @enderror" id="Allergies_register"
                                    name="Allergies" placeholder="مثال: حساسية من البنسلين، حساسية من الفول السوداني...">{{ old('Allergies') }}</textarea>
                                @error('Allergies')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="medical-text">إذا كنت تعاني من أي حساسيات، يرجى ذكرها هنا. افصل بينها
                                    بفاصلة.</div>
                            </div>
                        </div>
                    </div>

                    {{-- قسم الأمراض المزمنة المنظم --}}
                    {{-- داخل <form id="patientForm" ... > --}}

                    <div class="chronic-diseases-section mt-3">
                        <h6 class="section-title-sub"
                            style="font-size: 1.1rem; border-bottom:none; margin-bottom: 1rem;">
                            <i class="fas fa-viruses text-danger"></i> الأمراض المزمنة (إن وجدت)
                        </h6>
                        <div id="chronic_diseases_wrapper_register"> {{-- <<<--- تأكد من هذا المعرف --}}
                            @if (is_array(old('chronic_diseases')))
                                @php $current_index_for_old = 0; @endphp {{-- عداد للصفوف القديمة --}}
                                @foreach (old('chronic_diseases') as $oldDiseaseData)
                                    <div class="chronic_disease_row">
                                        <div class="col-md-3-cd field-group">
                                            <label class="form-label">المرض</label>
                                            <select name="chronic_diseases[{{ $current_index_for_old }}][disease_id]"
                                                class="form-select select2-diseases-dynamic @error('chronic_diseases.' . $current_index_for_old . '.disease_id') is-invalid @enderror"
                                                data-placeholder="اختر مرض">
                                                <option value=""></option>
                                                @if (isset($diseases_list))
                                                    @foreach ($diseases_list as $id => $name)
                                                        <option value="{{ $id }}"
                                                            {{ old('chronic_diseases.' . $current_index_for_old . '.disease_id', $oldDiseaseData['disease_id'] ?? null) == $id ? 'selected' : '' }}>
                                                            {{ $name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error('chronic_diseases.' . $current_index_for_old . '.disease_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-2-cd field-group">
                                            <label class="form-label">تاريخ التشخيص</label>
                                            <input type="text"
                                                name="chronic_diseases[{{ $current_index_for_old }}][diagnosed_at]"
                                                value="{{ old('chronic_diseases.' . $current_index_for_old . '.diagnosed_at', $oldDiseaseData['diagnosed_at'] ?? '') }}"
                                                class="form-control flatpickr-date-chronic @error('chronic_diseases.' . $current_index_for_old . '.diagnosed_at') is-invalid @enderror"
                                                placeholder="YYYY-MM-DD">
                                            @error('chronic_diseases.' . $current_index_for_old . '.diagnosed_at')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-2-cd field-group">
                                            <label class="form-label">الحالة الحالية</label>
                                            <select
                                                name="chronic_diseases[{{ $current_index_for_old }}][current_status]"
                                                class="form-select select2-statuses-dynamic @error('chronic_diseases.' . $current_index_for_old . '.current_status') is-invalid @enderror"
                                                data-placeholder="الحالة">
                                                <option value=""></option>
                                                @if (isset($chronic_disease_statuses))
                                                    @foreach ($chronic_disease_statuses as $key => $value)
                                                        <option value="{{ $key }}"
                                                            {{ old('chronic_diseases.' . $current_index_for_old . '.current_status', $oldDiseaseData['current_status'] ?? null) == $key ? 'selected' : '' }}>
                                                            {{ $value }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error('chronic_diseases.' . $current_index_for_old . '.current_status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-4-cd field-group">
                                            <label class="form-label">ملاحظات (اختياري)</label>
                                            <input type="text"
                                                name="chronic_diseases[{{ $current_index_for_old }}][notes]"
                                                value="{{ old('chronic_diseases.' . $current_index_for_old . '.notes', $oldDiseaseData['notes'] ?? '') }}"
                                                class="form-control @error('chronic_diseases.' . $current_index_for_old . '.notes') is-invalid @enderror"
                                                placeholder="أي ملاحظات إضافية">
                                            @error('chronic_diseases.' . $current_index_for_old . '.notes')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-1-cd field-group">
                                            <button type="button" class="remove_chronic_disease_row_btn"
                                                title="حذف هذا المرض"><i class="fas fa-times-circle"></i></button>
                                        </div>
                                    </div>
                                    @php $current_index_for_old++; @endphp
                                @endforeach
                            @endif
                        </div>
                        <button type="button" class="btn" id="add_chronic_disease_btn_register">
                            {{-- <<<--- تأكد من هذا المعرف --}}
                            <i class="fas fa-plus-circle"></i> إضافة مرض مزمن آخر (إذا كنت تعاني من أكثر من مرض)
                        </button>
                        <div class="medical-text mt-2">إذا كنت لا تعاني من أي أمراض مزمنة، يمكنك ترك هذا القسم فارغًا.
                        </div>
                    </div>

                    {{-- قالب JavaScript --}}
                    <template id="chronic_disease_template_register"> {{-- <<<--- تأكد من هذا المعرف --}}
                        <div class="chronic_disease_row">
                            <div class="col-md-3-cd field-group">
                                <label class="form-label">المرض</label>
                                <select name="chronic_diseases[__INDEX__][disease_id]"
                                    class="form-select select2-diseases-dynamic" data-placeholder="اختر مرض">
                                    <option value=""></option>
                                    @if (isset($diseases_list))
                                        @foreach ($diseases_list as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            {{-- <div class="col-md-2-cd field-group">
                                <label class="form-label">تاريخ التشخيص</label>
                                <input type="text" name="chronic_diseases[__INDEX__][diagnosed_at]"
                                    class="form-control flatpickr-date-chronic-dynamic" placeholder="YYYY-MM-DD">
                            </div> --}}
                            {{-- <div class="col-md-2-cd field-group">
                                <label class="form-label">الحالة الحالية</label>
                                <select name="chronic_diseases[__INDEX__][current_status]"
                                    class="form-select select2-statuses-dynamic" data-placeholder="الحالة">
                                    <option value=""></option>
                                    @if (isset($chronic_disease_statuses))
                                        @foreach ($chronic_disease_statuses as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div> --}}
                            <div class="col-md-4-cd field-group">
                                <label class="form-label">ملاحظات (اختياري)</label>
                                <input type="text" name="chronic_diseases[__INDEX__][notes]" class="form-control"
                                    placeholder="أي ملاحظات إضافية">
                            </div>
                            <div class="col-md-1-cd field-group">
                                <button type="button" class="remove_chronic_disease_row_btn"
                                    title="حذف هذا المرض"><i class="fas fa-times-circle"></i></button>
                            </div>
                        </div>
                    </template>
                </div>


                <!-- شروط وأحكام -->
                <div class="field-row mt-4">
                    <div class="field-group full-width">
                        <div class="form-check">
                            <input class="form-check-input @error('terms') is-invalid @enderror" type="checkbox"
                                id="terms_register" name="terms" required>
                            <label class="form-check-label" for="terms_register">
                                أقر بأن جميع المعلومات المقدمة صحيحة وأوافق على <a href="#" target="_blank"
                                    style="color: var(--primary-color);">شروط الاستخدام</a> و
                                <a href="#" target="_blank" style="color: var(--primary-color);">سياسة
                                    الخصوصية</a> الخاصة بالنظام.
                            </label>
                            @error('terms')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- زر الإرسال -->
                <div class="text-center mt-4">
                    <button type="submit" class="btn-submit" id="submitPatientRegisterForm">
                        <span class="btn-text"><i class="fas fa-user-plus"></i> إنشاء حساب جديد</span>
                        <i class="fas fa-spinner fa-spin spinner-icon"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Flatpickr -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/ar.js"></script>
    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ URL::asset('Dashboard/plugins/select2/js/i18n/ar.js') }}"></script>
    <!-- Notify JS (If you use it on this page) -->
    <script src="{{ URL::asset('dashboard/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('dashboard/plugins/notify/js/notifit-custom.js') }}"></script>


    <script>
        // الدالة تبقى في النطاق العام إذا كنت ستستخدمها من HTML مباشرة (onclick)
        var loadFile = function(event, outputId = 'output_register') {
            var output = document.getElementById(outputId);
            var fileInput = event.target;
            if (fileInput.files && fileInput.files[0]) {
                const file = fileInput.files[0];
                if (file.size > 2 * 1024 * 1024) { // 2MB limit
                    showNotification(
                        "<b><i class='fas fa-exclamation-triangle me-1'></i> تنبيه:</b> حجم الصورة كبير جدًا. الحد الأقصى 2MB.",
                        "warning");
                    fileInput.value = "";
                    return;
                }
                const validImageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml', 'image/webp'];
                if (!validImageTypes.includes(file.type)) {
                    showNotification("<b><i class='fas fa-times-circle me-1'></i> خطأ:</b> نوع الملف غير مدعوم.",
                        "error");
                    fileInput.value = "";
                    return;
                }
                output.src = URL.createObjectURL(file);
                output.onload = function() {
                    URL.revokeObjectURL(output.src);
                }
            }
        };

        // دالة عامة لإظهار الإشعارات
        function showNotification(message, type = 'info', position = 'top-center', autohide = true, timeout = 4000) {
            let iconClass = 'fas fa-info-circle';
            if (type === 'success') iconClass = 'fas fa-check-circle';
            else if (type === 'error') iconClass = 'fas fa-times-circle';
            else if (type === 'warning') iconClass = 'fas fa-exclamation-triangle';
            notif({
                msg: `<div class="d-flex align-items-center p-2"><i class='${iconClass} fa-lg me-2'></i><div style="font-size: 0.95rem;">${message}</div></div>`,
                type: type,
                position: position,
                autohide: autohide,
                timeout: timeout,
                multiline: true,
                zindex: 99999,
                width: 'auto',
                padding: '15px'
            });
        }


        $(document).ready(function() {
            // تهيئة Flatpickr للتاريخ
            const flatpickrConfig = {
                dateFormat: "Y-m-d",
                locale: "ar",
                allowInput: true,
                disableMobile: "true"
            };
            flatpickr("#Date_Birth_register", {
                ...flatpickrConfig,
                maxDate: "today",
                defaultDate: "{{ old('Date_Birth') }}"
            });

            function initializeFlatpickrOnRow(selector) {
                flatpickr(selector, {
                    ...flatpickrConfig,
                    maxDate: "today"
                });
            }

            // تهيئة Select2
            function initializeSelect2(selector, placeholder, parent = $(document.body), customOptions = {}) {
                $(selector).select2({
                    placeholder: placeholder || "-- اختر --",
                    width: '100%',
                    dir: "rtl",
                    theme: "bootstrap-5",
                    allowClear: !$(selector).prop('required'), // اسمح بالمسح إذا لم يكن الحقل مطلوبًا
                    dropdownParent: parent,
                    ...customOptions
                }).on('select2:open', function(e) {
                    const evt = "scroll.select2";
                    $(e.target).parents().off(evt);
                    $(window).off(evt);
                });
            }
            initializeSelect2('.select2', null, $('#patientForm')); // لـ Blood_Group و Gender

            // --- Chronic Diseases Section for Patient REGISTRATION page ---
            // ... (بداية $(document).ready() ودوال أخرى) ...

            // --- Chronic Diseases Section for Patient REGISTRATION page ---
            // تأكد أن هذا العداد يتم تهيئته بشكل صحيح إذا كان هناك بيانات قديمة عند فشل التحقق
            let chronicDiseaseCounterRegister =
                {{ is_array(old('chronic_diseases')) ? count(old('chronic_diseases')) : 0 }};

            // دالة لتهيئة الإضافات (Select2, Flatpickr) للصفوف الجديدة أو القديمة
            function initializeChronicDiseaseRowPluginsRegister(rowElement) {
                const $row = $(rowElement); // jQuery object of the row

                // تهيئة Select2 لاختيار المرض
                const $diseaseSelect = $row.find('.select2-diseases-dynamic');
                if ($diseaseSelect.length && !$diseaseSelect.data('select2')) { // تحقق إذا لم يتم تهيئته من قبل
                    // 'parent' يجب أن يكون العنصر الذي يحتوي على القائمة المنسدلة لتعمل بشكل صحيح
                    // في حالتك، قد يكون $row.find('.col-md-3-cd') أو $row نفسها إذا كان تصميم CSS يسمح بذلك
                    initializeSelect2($diseaseSelect, "اختر المرض من القائمة", $row.find('.col-md-3-cd'));
                }

                // تهيئة Select2 لاختيار الحالة
                const $statusSelect = $row.find('.select2-statuses-dynamic');
                if ($statusSelect.length && !$statusSelect.data('select2')) {
                    initializeSelect2($statusSelect, "اختر الحالة", $row.find('.col-md-2-cd').eq(
                        1)); // .eq(1) لأن هناك حقل تاريخ قبله بنفس الكلاس col-md-2
                }

                // إعادة تهيئة Flatpickr
                $row.find('.flatpickr-date-chronic-dynamic, .flatpickr-date-chronic').each(function() {
                    if (this._flatpickr) { // إذا كان flatpickr مهيأ مسبقًا
                        this._flatpickr.destroy(); // قم بتدميره أولاً
                    }
                    initializeFlatpickrOnRow(
                        this); // ثم قم بتهيئته (تأكد أن initializeFlatpickrOnRow موجودة)
                });
            }

            // تهيئة الإضافات للصفوف القديمة (إذا وجدت عند فشل التحقق من الصحة)
            // تأكد من أن الحاوية لها المعرف الصحيح
            $('#chronic_diseases_wrapper_register .chronic_disease_row').each(function() {
                initializeChronicDiseaseRowPluginsRegister(this);
            });

            // *** هذا هو الجزء الخاص بحدث النقر على الزر ***
            $('#add_chronic_disease_btn_register').click(function() { // <<<--- تأكد أن هذا المعرف صحيح
                console.log('Add chronic disease button clicked!'); // <<<--- أضف هذا السطر

                const template = document.getElementById(
                    'chronic_disease_template_register'); // <<<--- تأكد أن هذا المعرف صحيح
                if (!template) {
                    console.error('Error: chronic_disease_template_register not found!');
                    showNotification("خطأ في تهيئة النموذج: القالب غير موجود.", "error");
                    return;
                }

                const newRowHtml = template.innerHTML.replace(/__INDEX__/g, chronicDiseaseCounterRegister);
                chronicDiseaseCounterRegister++; // زيادة العداد للصف التالي

                const $newRow = $(newRowHtml).appendTo(
                    '#chronic_diseases_wrapper_register'); // <<<--- تأكد أن هذا المعرف صحيح

                // إزالة أي كلاس d-none من form-label داخل الصف الجديد (إذا كنت تخفيها في القالب)
                $newRow.find('.form-label').removeClass('d-none');

                // تهيئة الإضافات للصف الجديد
                initializeChronicDiseaseRowPluginsRegister($newRow[0]); // $newRow[0] هو عنصر DOM النقي

                // اختياري: فتح أول قائمة منسدلة في الصف الجديد للتركيز
                $newRow.find('select.select2-diseases-dynamic').first().select2('open');
            });

            // حدث حذف صف المرض المزمن
            // تأكد من أن الحاوية لها المعرف الصحيح
            $('#chronic_diseases_wrapper_register').on('click', '.remove_chronic_disease_row_btn', function() {
                $(this).closest('.chronic_disease_row').addClass('animate__animated animate__zoomOutRight')
                    .one('animationend', function() {
                        $(this).remove();
                        // لا حاجة لتقليل العداد هنا، لأننا نستخدمه فقط للفهارس الجديدة
                    });
            });

            // ... (بقية كود $(document).ready()) ...


            // --- Form Validation & Submission ---
            const patientForm = document.getElementById('patientForm');
            const submitButton = document.getElementById('submitPatientRegisterForm');

            if (patientForm) {
                patientForm.addEventListener('submit', async function(event) {
                    event.preventDefault(); // منع الإرسال الافتراضي دائمًا للتحكم الكامل
                    event.stopPropagation();

                    let formIsValid = patientForm.checkValidity(); // استخدام التحقق المدمج أولاً

                    // إضافة تحقق مخصص لكلمة المرور إذا لزم الأمر
                    const password = $('#password_register').val();
                    const confirmPassword = $('#password_confirmation_register').val();
                    if (password && confirmPassword && password !== confirmPassword) {
                        $('#password_confirmation_register').get(0).setCustomValidity(
                            'كلمات المرور غير متطابقة.');
                        formIsValid = false;
                    } else {
                        $('#password_confirmation_register').get(0).setCustomValidity('');
                    }

                    patientForm.classList.add('was-validated'); // أضف الكلاس لعرض أنماط التحقق

                    if (!formIsValid) {
                        const firstInvalidField = patientForm.querySelector(':invalid:not(fieldset)');
                        if (firstInvalidField) {
                            firstInvalidField.focus({
                                preventScroll: true
                            });
                            $('html, body').animate({
                                scrollTop: $(firstInvalidField).offset().top - 120
                            }, 500);
                            showNotification("يرجى تصحيح الأخطاء المميزة في النموذج.", "warning");
                        }
                        return; // إيقاف التنفيذ إذا كان النموذج غير صالح
                    }

                    // إظهار نافذة سياسة الخصوصية
                    const acceptedPrivacy = await showPrivacyPolicyModal();
                    if (!acceptedPrivacy) {
                        showNotification("يجب الموافقة على سياسة الخصوصية لإكمال التسجيل.", "warning");
                        $('#terms_register').focus(); // ركز على مربع الشروط
                        return; // إيقاف التنفيذ
                    }


                    // إذا كان كل شيء صالحًا وتم قبول السياسة
                    if (submitButton) {
                        submitButton.classList.add('loading');
                        submitButton.disabled = true;
                        $(submitButton).find('.btn-text').text('جاري التسجيل...');
                    }
                    // إرسال النموذج بعد كل التحققات
                    // setTimeout(() => { form.submit(); }, 1000); // يمكنك إضافة تأخير بسيط إذا أردت
                    patientForm.submit(); // إرسال النموذج
                }, false);
            }

            // إظهار رسائل الخطأ من Laravel بعد إعادة التوجيه
            @if ($errors->any())
                let errorList =
                    "<strong><i class='fas fa-exclamation-triangle me-2'></i> حدثت الأخطاء التالية عند محاولة الحفظ:</strong><ul class='mb-0 mt-2' style='list-style-type:none; padding-right:0;'>";
                @foreach ($errors->all() as $error)
                    errorList += "<li class='mb-1'>- {{ $error }}</li>";
                @endforeach
                errorList += "</ul>";
                showNotification(errorList, "error", "top-center", false); // autohide: false
            @endif

            // رسالة نجاح التسجيل (إذا تم توجيه المستخدم هنا مع رسالة نجاح)
            @if (session('status_success'))
                showNotification("{{ session('status_success') }}", "success", "top-center", true, 7000);
            @endif
            @if (session('add')) // من PatientRepository
                showNotification("{{ session('add') }}", "success", "top-center", true, 7000);
            @endif


            $('.toggle-password').click(function() {
                const targetInputId = $(this).data('target');
                const passwordInput = $('#' + targetInputId);
                const icon = $(this).find('i');
                if (passwordInput.length) {
                    const type = passwordInput.attr('type') === 'password' ? 'text' : 'password';
                    passwordInput.attr('type', type);
                    icon.toggleClass('fa-eye fa-eye-slash');
                }
            });

            // لتحسين تجربة المستخدم مع الحقول (اختياري)
            $('.form-control').on('blur input', function() {
                if ($(this).val()) {
                    $(this).addClass('is-filled');
                } else {
                    $(this).removeClass('is-filled');
                }
            }).trigger('blur'); // لتطبيقها عند تحميل الصفحة للحقول المملوءة مسبقًا (مثل old data)
        });

        // إنشاء نافذة سياسة الخصوصية (تبقى كما هي)
        function showPrivacyPolicyModal() {
            return new Promise((resolve) => {
                const modalHTML = `
                <div id="privacyModal" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.6); display: flex; justify-content: center; align-items: center; z-index: 10000; padding: 15px;">
                    <div style="background: white; padding: 25px; border-radius: 15px; max-width: 550px; width: 100%; max-height: 90vh; overflow-y: auto; box-shadow: 0 5px 20px rgba(0,0,0,0.2); animation: fadeInModal 0.3s ease-out;">
                        <h3 style="color: var(--primary-color); margin-bottom: 20px; text-align:center; border-bottom: 1px solid #eee; padding-bottom:10px;">
                            <i class="fas fa-shield-alt me-2"></i> سياسة الخصوصية وشروط الاستخدام
                        </h3>
                        <div style="margin-bottom: 25px; line-height: 1.75; font-size:0.95rem; color: #555;">
                            <p class="mb-2">باستمرارك في عملية التسجيل، فإنك تقر بموافقتك على شروط الاستخدام وسياسة الخصوصية الخاصة بنظام إدارة المرضى:</p>
                            <ul style="padding-right: 20px; margin-top: 15px; list-style-type: decimal;">
                                <li class="mb-1"><strong>أمان البيانات:</strong> نتعهد بتخزين بياناتك الشخصية والصحية بشكل آمن ومشفر وفقًا لأفضل الممارسات الأمنية.</li>
                                <li class="mb-1"><strong>سرية المعلومات:</strong> لا يتم مشاركة بياناتك مع أي جهات خارجية غير مصرح لها بدون موافقتك الصريحة، إلا في الحالات التي يقتضيها القانون.</li>
                                <li class="mb-1"><strong>حقوق المستخدم:</strong> يحق لك الوصول إلى بياناتك، تعديلها، أو طلب حذفها في أي وقت وفقًا للإجراءات المتبعة.</li>
                                <li class="mb-1"><strong>استخدام المعلومات:</strong> تستخدم المعلومات المقدمة لأغراض تحسين جودة الرعاية الصحية المقدمة لك وتسهيل إدارة ملفك الطبي.</li>
                                <li class="mb-1"><strong>ملفات تعريف الارتباط (Cookies):</strong> قد نستخدم ملفات تعريف الارتباط لتحسين تجربتك على النظام.</li>
                            </ul>
                            <p class="mt-3">نشكرك على ثقتك بنا ونتعهد بحماية خصوصيتك.</p>
                        </div>
                        <div style="display: flex; justify-content: space-around; margin-top: 20px; gap:15px;">
                            <button id="acceptPrivacyBtn" style="background: var(--primary-color); color: white; border: none; padding: 12px 25px; border-radius: 8px; cursor: pointer; font-weight:bold; flex-grow:1; transition: background 0.2s ease;">
                                <i class="fas fa-check-circle me-1"></i> أوافق وأستمر
                            </button>
                            <button id="cancelPrivacyBtn" style="background: #6c757d; color: white; border: none; padding: 12px 25px; border-radius: 8px; cursor: pointer; font-weight:bold; flex-grow:1; transition: background 0.2s ease;">
                                <i class="fas fa-times-circle me-1"></i> إلغاء التسجيل
                            </button>
                        </div>
                    </div>
                </div>
                <style> @keyframes fadeInModal{0%{opacity:0;transform:scale(0.95)}100%{opacity:1;transform:scale(1)}} </style>
                `;
                $('body').append(modalHTML);
                $('#acceptPrivacyBtn').click(function() {
                    $('#privacyModal').remove();
                    resolve(true);
                });
                $('#cancelPrivacyBtn').click(function() {
                    $('#privacyModal').remove();
                    resolve(false);
                });
            });
        }
    </script>
</body>

</html>
