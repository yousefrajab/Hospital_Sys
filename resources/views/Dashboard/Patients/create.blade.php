@extends('Dashboard.layouts.master')

@section('css')
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Tajawal Font -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;900&display=swap" rel="stylesheet">
    <!-- Flatpickr CSS -->
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Notify CSS -->
    <link href="{{ URL::asset('dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
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
            color: var(--dark-color);
            line-height: 1.6;
        }

        /* تصميم الكارت الرئيسي */
        .patient-form {
            width: 100%;
            max-width: 1300px;
            background: white;
            border-radius: 50px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            animation: fadeInUp 0.6s ease-out;
            margin: 20px auto;
            position: relative;
            transition: transform var(--transition-speed);
        }

        .patient-form:hover {
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
            padding-right: 3rem;
        }

        .password-toggle-group .toggle-password {
            position: flex;
            top: 20%;
            right: 0.75rem;
            transform: translateY(-4%);
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
            margin-bottom: 25px;
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
            /* border: 3px solid white; */
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .avatar-upload label {
            position: absolute;
            bottom: -20px;
            right: -20px;
            width: 40px;
            height: 40px;
            background:black;
            border-radius: 50%;
            display: flex;
            color: white;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 5 10px 10px rgba(0, 0, 0, 0.1);
        }

        /* .avatar-upload label i {
            color: white;
            font-size: 100px;
        } */

        .avatar-upload input[type="file"] {
            /* color: white; */
            display: none;
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
            top: 2%;
            left: 03%;
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

        /* تأثيرات للهواتف */
        @media (max-width: 768px) {
            .patient-form {
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

        /* breadcrumb styling */
        .breadcrumb-header {
            background: white;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .breadcrumb-header .btn-primary {
            background: var(--primary-color);
            border: none;
            border-radius: 8px;
            padding: 8px 15px;
            transition: all var(--transition-speed);
        }

        .breadcrumb-header .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(58, 134, 255, 0.3);
        }
    </style>
    {{-- @include('Style.Style')     --}}
@endsection

@section('title')
    اضافة مريض جديد
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto">المرضى</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ اضافة مريض جديد</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <a href="{{ route('admin.Patients.index') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> رجوع لقائمة المرضى
            </a>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    @include('Dashboard.messages_alert')

    <div class="row">

        <div class="col-lg-12">
            <div class="patient-form">
                <div class="form-header">
                    <i class="fas fa-user-plus"></i>
                    <h3>نظام إدارة المرضى</h3>
                    <p class="mb-0">املأ جميع الحقول المطلوبة لإضافة مريض جديد للنظام</p>
                </div>

                <div class="form-section">
                    <form action="{{ route('admin.Patients.store') }}" method="post" autocomplete="off" id="patientForm"
                        enctype="multipart/form-data">
                        @csrf

                        <!-- رسائل التنبيه -->
                        <div id="successAlert" class="alert-message alert-success animate__animated animate__fadeIn"
                            style="display: none;">
                            <i class="fas fa-check-circle"></i> تم تسجيل المريض بنجاح في قاعدة البيانات!
                        </div>

                        <div id="errorAlert" class="alert-message alert-error animate__animated animate__fadeIn"
                            style="display: none;">
                            <i class="fas fa-exclamation-circle"></i> حدث خطأ أثناء التسجيل، يرجى مراجعة البيانات المطلوبة.
                        </div>

                        <div class="paired-fields">
                            <!-- الصف 1: الاسم والبريد -->
                            <div class="field-row">
                                <div class="col-md-4">
                                    <div class="avatar-upload mb-4"   >
                                        <img id="output" src="{{ URL::asset('Dashboard/img/doctorr_default.png') }}"
                                            alt="{{ trans('doctors.img') }}">
                                        <label for="avatar-upload">
                                            <i class="fas fa-camera"></i>
                                        </label>
                                        <input id="avatar-upload" type="file" accept="image/*" name="photo"
                                            onchange="loadFile(event)">
                                    </div>
                                </div>
                                <div class="field-group">
                                    <label for="name" class="form-label required">الاسم الكامل للمريض</label>
                                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                                        class="form-control @error('name') is-invalid @enderror"
                                        placeholder="الاسم الأول، الأب، الجد، العائلة" required
                                        pattern="^([\u0600-\u06FF\s]{10,}|[A-Za-z\s\-']{10,})$">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="valid-feedback"><i class="fas fa-check"></i> صحيح</div>
                                </div>

                                <div class="field-group">
                                    <label for="email" class="form-label required">البريد الإلكتروني</label>
                                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                                        class="form-control @error('email') is-invalid @enderror"
                                        placeholder="example@domain.com" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="valid-feedback"><i class="fas fa-check"></i> صحيح</div>
                                </div>
                            </div>

                            <!-- الصف 2: تاريخ الميلاد ورقم الهاتف -->
                            <div class="field-row">
                                <div class="field-group">
                                    <label for="Date_Birth" class="form-label required">تاريخ الميلاد</label>
                                    <div class="datepicker-input">
                                        <input class="form-control" id="Date_Birth" name="Date_Birth"
                                            placeholder="يوم/شهر/سنة" type="text" autocomplete="off" required>
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    @error('Date_Birth')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="field-group">
                                    <label for="Phone" class="form-label required">رقم الجوال</label>
                                    <input type="tel" id="Phone" name="Phone" value="{{ old('Phone') }}"
                                        class="form-control @error('Phone') is-invalid @enderror"
                                        placeholder="+9705XXXXXXXX" maxlength="13" required pattern="^\+9705\d{8}$"
                                        title="يجب أن يبدأ رقم الجوال بـ +9705 ويتكون من 13 خانة (مثال: +9705XXXXXXXX)">
                                    <small class="form-text text-muted">
                                        أدخل رقم الجوال بالتنسيق التالي: <strong>+9705XXXXXXXX</strong>
                                    </small>
                                    @error('Phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="valid-feedback"><i class="fas fa-check-circle"></i> رقم صحيح</div>
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
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="valid-feedback"><i class="fas fa-check"></i> قوي</div>
                                    <div class="medical-text">لأمان أفضل، استخدم مزيجًا من الحروف والأرقام</div>
                                </div>

                                <div class="field-group">
                                    <label for="password_confirmation" class="form-label required">تأكيد كلمة
                                        المرور</label>
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
                                        @error('national_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="valid-feedback"><i class="fas fa-check"></i> صحيح</div>
                                    </div>

                                    <div class="field-group">
                                        <label for="Blood_Group" class="form-label required">فصيلة الدم</label>
                                        <select class="form-control select2" id="Blood_Group" name="Blood_Group"
                                            required>
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
                                        @error('Blood_Group')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="medical-text">مهم في حالات الطوارئ</div>
                                    </div>
                                </div>

                                <div class="field-row">
                                    <div class="field-group">
                                        <label for="Gender" class="form-label required">الجنس</label>
                                        <select class="form-control select2-gender" id="Gender" name="Gender"
                                            required>
                                            <option value="" selected disabled>-- اختر الجنس --</option>
                                            <option value="1" data-icon="♂️">ذكر</option>
                                            <option value="2" data-icon="♀️">أنثى</option>
                                        </select>
                                        @error('Gender')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="valid-feedback"><i class="fas fa-check"></i> تم الاختيار</div>
                                    </div>

                                    <div class="field-group">
                                        <label for="Address" class="form-label required">العنوان التفصيلي</label>
                                        <textarea rows="3" class="form-control" id="Address" name="Address"
                                            placeholder="الحي، الشارع، المدينة، الرمز البريدي"></textarea>
                                        @error('Address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="medical-text">لأغراض التواصل والزيارات المنزلية</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- زر الإرسال -->
                        <div class="text-center mt-4">
                            <button type="submit"
                                class="btn-submit animate__animated animate__pulse animate__infinite animate__slower">
                                <i class="fas fa-save"></i> حفظ البيانات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ar.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Notify JS -->
    <script src="{{ URL::asset('dashboard/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('/plugins/notify/js/notifit-custom.js') }}"></script>
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
                dropdownParent: $('.patient-form')
            });

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

            // التحقق من النموذج قبل الإرسال
            $('#patientForm').on('submit', function(e) {
                let isValid = true;
                $('input, select, textarea').each(function() {
                    validateField($(this));
                    if ($(this).is(':invalid') || ($(this).is(':required') && !$(this).val())) {
                        isValid = false;
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    $('#errorAlert').fadeIn().addClass('animate__headShake');
                    $('html, body').animate({
                        scrollTop: $('.is-invalid').first().offset().top - 100
                    }, 500);
                }
            });

            // عرض رسائل الخطأ من لارافيل
            @if ($errors->any())
                $('#errorAlert').fadeIn().addClass('animate__headShake');
            @endif

            // عرض رسالة النجاح إذا كانت موجودة في الجلسة
            @if (session('success'))
                $('#successAlert').html('<i class="fas fa-check-circle"></i> {{ session('success') }}').fadeIn()
                    .addClass('animate__tada');
            @endif
        });


        var loadFile = function(event) {
            var output = document.getElementById('output');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src);
            }
        };
    </script>
@endsection
