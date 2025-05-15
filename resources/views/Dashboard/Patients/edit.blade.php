@extends('Dashboard.layouts.master')

@php
    $patientNameForTitle = $Patient->name ?? 'مريض غير معروف';
@endphp
@section('title', 'تعديل بيانات المريض: ' . $patientNameForTitle)

@section('css')
    @parent
    <!-- Flatpickr CSS -->
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <!-- Notify CSS -->
    <link href="{{ URL::asset('dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    {{-- نفس أنماط CSS من صفحة الإضافة مع تعديلات طفيفة إذا لزم الأمر --}}
    <style>
        /* --- المتغيرات اللونية والتصميمية (مأخوذة من تصميم الإضافة) --- */
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

            --admin-primary: var(--primary-color);
            --admin-primary-dark: var(--primary-dark);
            --admin-secondary: var(--secondary-color);
            --admin-accent: var(--accent-color);
            --admin-danger: var(--error-color);
            --admin-warning: var(--warning-color);
            --admin-success: var(--success-color);
            --admin-info: #3B82F6;
            --admin-light: var(--light-color);
            --admin-dark: var(--dark-color);
            --admin-text: var(--dark-color);
            --admin-text-secondary: #6c757d;
            --admin-border: #dee2e6;
            --admin-input-bg: #fdfdfd;
            --admin-input-focus-border: var(--accent-color);
            --admin-input-focus-shadow: rgba(0, 180, 216, 0.2);
            --admin-danger-rgb: 255, 0, 110;

            --admin-radius-sm: 0.25rem;
            --admin-radius-md: 10px;
            --admin-radius-lg: 50px;
            --admin-shadow: var(--card-shadow);
            --admin-transition: var(--transition-speed);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Tajawal', sans-serif; background: linear-gradient(135deg, #f0f4f8 0%, #dfe7f0 100%); color: var(--dark-color); line-height: 1.6; }
        .patient-form { width: 100%; max-width: 1300px; background: white; border-radius: var(--admin-radius-lg); box-shadow: var(--admin-shadow); overflow: hidden; animation: fadeInUp 0.6s ease-out; margin: 20px auto; position: relative; transition: transform var(--admin-transition); }
        .patient-form:hover { transform: translateY(-5px); }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        .form-header { background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white; padding: 30px; text-align: center; position: relative; overflow: hidden; z-index: 1; }
        .form-header::before { content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiPjxkZWZzPjxwYXR0ZXJuIGlkPSJwYXR0ZXJuIiB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHBhdHRlcm5Vbml0cz0idXNlclNwYWNlT25Vc2UiIHBhdHRlcm5UcmFuc2Zvcm09InJvdGF0ZSg0NSkiPjxyZWN0IHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCIgZmlsbD0icmdiYSgyNTUsMjU1LDI1NSwwLjA1KSIvPjwvcGF0dGVybj48L2RlZnM+PHJlY3QgZmlsbD0idXJsKCNwYXR0ZXJuKSIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIvPjwvc3ZnPg=='); z-index: -1; }
        .form-header h3 { margin: 0; font-size: 2rem; font-weight: 700; letter-spacing: -0.5px; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); }
        .form-header p { margin: 10px 0 0; opacity: 0.9; font-size: 1rem; font-weight: 300; }
        .form-header i.fa-user-edit { /* أيقونة التعديل */
            font-size: 2.5rem; margin-bottom: 15px; display: inline-block;
            background: rgba(255, 255, 255, 0.2); width: 80px; height: 80px;
            line-height: 80px; border-radius: 50%; animation: pulse 2s infinite;
        }
        @keyframes pulse { 0% { transform: scale(1); } 50% { transform: scale(1.05); } 100% { transform: scale(1); } }
        .form-section { padding: 30px; position: relative; }
        .section-title-sub {
            color: var(--admin-primary); font-weight: 600; margin-bottom: 1.5rem;
            font-size: 1.2rem; display:flex; align-items:center;
            padding-bottom: 0.5rem; border-bottom: 2px solid var(--admin-accent);
        }
        .section-title-sub i { margin-left: 10px; color: var(--admin-primary); }
        .password-toggle-group { position: relative; }
        .password-toggle-group input { padding-right: 3rem; }
        .password-toggle-group .toggle-password { position: absolute; top: 50%; right: 0.75rem; transform: translateY(-50%); background: transparent; border: none; color: #888; font-size: 1rem; cursor: pointer; z-index: 2; }
        .paired-fields { display: flex; flex-direction: column; gap: 25px; margin-bottom: 30px; }
        .field-row { display: flex; gap: 25px; width: 100%; }
        .field-group { flex: 1; min-width: 0; position: relative; margin-bottom: 25px; }
        .form-label { display: block; margin-bottom: 10px; font-weight: 600; color: var(--dark-color); position: relative; }
        .form-label.required::after { content: '*'; color: var(--error-color); margin-right: 5px; font-size: 1.1em; }
        .form-control, .form-select {
            border: 2px solid #e9ecef; border-radius: var(--admin-radius-md); padding: 14px 18px;
            width: 100%; font-size: 1rem; background-color: var(--admin-input-bg);
            transition: all var(--admin-transition); font-family: 'Tajawal', sans-serif;
            height: auto;
        }
        .form-control:focus, .form-select:focus,
        .select2-container--bootstrap-5.select2-container--focus .select2-selection,
        .select2-container--bootstrap-5.select2-container--open .select2-selection {
            border-color: var(--admin-input-focus-border) !important;
            box-shadow: 0 0 0 4px var(--admin-input-focus-shadow) !important;
            background-color: white; outline: none;
        }
        .select2-container--default .select2-selection--single {
            height: calc(1.5em + (14px*2) + (2px*2));
            border-radius: var(--admin-radius-md); border: 2px solid #e9ecef;
            padding: 14px 18px; font-size: 1rem; transition: all var(--admin-transition);
            background-color: var(--admin-input-bg);
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered { line-height: normal; padding-left: 0; padding-right: 0; color: var(--admin-text); }
        .select2-container--default .select2-selection--single .select2-selection__arrow { height: calc(1.5em + (14px*2)); top: 2px; right: 10px; }
        .select2-container--bootstrap-5 .select2-dropdown {
            border-color: var(--admin-border); border-radius: var(--admin-radius-md); box-shadow: var(--admin-shadow);
        }
        .select2-container--bootstrap-5 .select2-results__option--highlighted[aria-selected] {
            background-color: var(--admin-primary); color: white;
        }
        .form-control.is-invalid { border-color: var(--error-color); background-image: none; }
        .avatar-upload { position: relative; width: 140px; height: 140px; margin: 0 auto 20px; }
        .avatar-upload img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; border: 3px solid white; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1); }
        .avatar-upload label { position: absolute; bottom: 0px; right: 0px; width: 40px; height: 40px; background: linear-gradient(135deg, var(--admin-accent), var(--admin-success)); border-radius: 50%; display: flex; color: white; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 3px 8px rgba(0,0,0,0.2); border: 2px solid white; transition: var(--admin-transition); }
        .avatar-upload label:hover { transform: scale(1.1); }
        .avatar-upload input[type="file"] { display: none; }
        .form-control.is-valid { border-color: var(--success-color); }
        .input-group { position: relative; display: flex; align-items: stretch; width: 100%; }
        .input-group .form-control { flex: 1 1 auto; position: relative; z-index: 1; border-left: 0; border-top-left-radius: 0; border-bottom-left-radius: 0;}
        .input-group .toggle-password { border: 2px solid #e9ecef; border-right: none; border-radius: var(--admin-radius-md) 0 0 var(--admin-radius-md); background: var(--admin-input-bg); padding: 0 15px; color: var(--primary-color); cursor: pointer; transition: all var(--admin-transition); z-index: 2;}
        .input-group .toggle-password:hover { background: #e0e0e0; }
        .invalid-feedback { color: var(--error-color); font-size: 0.85rem; margin-top: 8px; display: none; animation: fadeIn 0.3s; }
        .valid-feedback { color: var(--success-color); font-size: 0.85rem; margin-top: 8px; display: none; animation: fadeIn 0.3s; }
        .medical-info { background-color: #fdfdff; border-radius: 15px; padding: 25px; margin-top: 25px; border-left: 5px solid var(--accent-color); position: relative; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .medical-info::before { content: ''; position: absolute; top: -20px; right: -20px; width: 120px; height: 120px; background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%2300b4d8" opacity="0.07"><path d="M19 8h-1V3H6v5H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zM8 5h8v3H8V5zm8 14H8v-4h8v4zm2-4v-2H6v2H4v-4c0-.55.45-1 1-1h14c.55 0 1 .45 1 1v4h-2z"/></svg>') no-repeat; background-size: contain; }
        .btn-submit { background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); border: none; padding: 16px 32px; font-weight: 700; font-size: 1.1rem; letter-spacing: 0.5px; border-radius: 12px; color: white; cursor: pointer; width: 100%; max-width: 320px; margin: 30px auto 0; display: block; box-shadow: 0 6px 20px rgba(58, 134, 255, 0.3); transition: all var(--admin-transition); position: relative; overflow: hidden; z-index: 1; }
        .btn-submit::before { content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, var(--secondary-color), var(--primary-color)); opacity: 0; z-index: -1; transition: opacity var(--admin-transition); }
        .btn-submit:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(58, 134, 255, 0.4); }
        .btn-submit:hover::before { opacity: 1; }
        .btn-submit:active { transform: translateY(1px); }
        .btn-submit i { margin-left: 8px; }
        @media (max-width: 768px) { .patient-form { margin: 10px; border-radius: 15px; animation: fadeIn 0.6s ease-out; } .form-header { padding: 25px 20px; } .form-header h3 { font-size: 1.6rem; } .form-header i { font-size: 2rem; width: 70px; height: 70px; line-height: 70px; } .form-section { padding: 25px 20px; } .field-row { flex-direction: column; gap: 0; } .field-group { width: 100%; margin-bottom: 20px; } .btn-submit { padding: 14px 25px; font-size: 1rem; max-width: 100%; } }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes shake { 0%, 100% { transform: translateX(0); } 20%, 60% { transform: translateX(-5px); } 40%, 80% { transform: translateX(5px); } }
        .shake { animation: shake 0.5s; }
        .breadcrumb-header { background: white; padding: 15px; border-radius: 10px; margin-bottom: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .breadcrumb-header .btn-primary { background: var(--primary-color); border: none; border-radius: 8px; padding: 8px 15px; transition: all var(--admin-transition); }
        .breadcrumb-header .btn-primary:hover { background: var(--primary-dark); transform: translateY(-2px); box-shadow: 0 5px 15px rgba(58, 134, 255, 0.3); }

        /* === أنماط قسم الأمراض المزمنة المحسنة (نفس أنماط صفحة الإضافة) === */
        .chronic-diseases-section {
            margin-top: 2.5rem;
            padding-top: 1.5rem;
            border-top: 1px dashed var(--admin-border);
        }
        .chronic_disease_row {
            background-color: var(--admin-light); padding: 1rem 1.25rem; border-radius: var(--admin-radius-md);
            margin-bottom: 1rem; border: 1px solid var(--admin-border);
            display: flex; flex-wrap: wrap; gap: 1rem; align-items: flex-end;
            transition: var(--admin-transition);
        }
        .chronic_disease_row:hover { box-shadow: 0 3px 10px rgba(0,0,0,0.07); border-color: var(--admin-accent); }
        .chronic_disease_row > div { margin-bottom: 0; }
        .chronic_disease_row .col-md-3 { flex: 0 0 calc(25% - 0.75rem); }
        .chronic_disease_row .col-md-2 { flex: 0 0 calc(16.66% - 0.8rem); }
        .chronic_disease_row .col-md-4 { flex: 0 0 calc(33.33% - 0.67rem); }
        .chronic_disease_row .col-md-1 { flex: 0 0 calc(8.33% - 0.9rem); text-align: center;}
        .chronic_disease_row .form-label {
            font-size: 0.85rem; margin-bottom: 0.3rem;
            color: var(--admin-text-secondary); font-weight: 500;
        }
        .chronic_disease_row .form-control,
        .chronic_disease_row .form-select,
        .chronic_disease_row .select2-container--bootstrap-5 .select2-selection {
            font-size: 0.9rem; padding: 0.6rem 0.9rem; background-color: white;
            height: auto; border-radius: var(--admin-radius-sm);
        }
        .chronic_disease_row .select2-container--default .select2-selection--single {
             height: calc(1.5em + (0.6rem*2) + (2px*2)); padding: 0.6rem 0.9rem;
        }
        .chronic_disease_row .select2-container--default .select2-selection--single .select2-selection__arrow {
             height: calc(1.5em + (0.6rem*2));
        }
        .remove_chronic_disease_row_btn {
            background-color: transparent; border: 1px solid rgba(var(--admin-danger-rgb), 0.5);
            color: var(--admin-danger); padding: 0; font-size: 0.9rem;
            border-radius: var(--admin-radius-sm); width: 36px; height: 36px;
            display: inline-flex; align-items: center; justify-content: center;
            transition: var(--admin-transition); opacity: 0.8;
        }
        .remove_chronic_disease_row_btn:hover {
            background-color: var(--admin-danger); color: white;
            border-color: var(--admin-danger); transform: scale(1.05); opacity: 1;
        }
        #add_chronic_disease_btn_edit { /* تمييز ID زر الإضافة لصفحة التعديل */
            background-color: var(--admin-success); border: none; color: white;
            font-weight: 500; padding: 0.6rem 1.2rem; font-size: 0.95rem;
            border-radius: var(--admin-radius-md); transition: var(--admin-transition);
            display: inline-flex; align-items: center;
        }
        #add_chronic_disease_btn_edit:hover {
            background-color: #279b70; transform: translateY(-2px);
            box-shadow: 0 3px 8px rgba(0,0,0,0.1);
        }
        #add_chronic_disease_btn_edit i { margin-left: 0.5rem; }
        .btn-submit.loading .spinner-icon { display: inline-block !important; animation: spin 0.75s linear infinite; margin-left: 5px; }
        .spinner-icon { display: none; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
        .is-invalid { border-color: var(--error-color) !important; }
        .invalid-feedback { color: var(--error-color); font-size: 0.8rem; margin-top: 0.25rem; display: none; }
        .was-validated .form-control:invalid ~ .invalid-feedback,
        .form-control.is-invalid ~ .invalid-feedback,
        .was-validated .form-select:invalid ~ .invalid-feedback,
        .form-select.is-invalid ~ .invalid-feedback { display: block; }
        .is-valid { border-color: var(--success-color) !important; }
        .valid-feedback { color: var(--success-color); font-size: 0.8rem; margin-top: 0.25rem; display: none; }
        .was-validated .form-control:valid ~ .valid-feedback,
        .form-control.is-valid ~ .valid-feedback,
        .was-validated .form-select:valid ~ .valid-feedback,
        .form-select.is-valid ~ .valid-feedback { display: block; }
        @media (max-width: 991px) {
            .chronic_disease_row .col-md-3, .chronic_disease_row .col-md-2,
            .chronic_disease_row .col-md-4, .chronic_disease_row .col-md-1 {
                flex: 0 0 100%; max-width: 100%;
            }
            .remove_chronic_disease_row_btn { margin-top: 0.5rem; width: 100%; }
        }
        @media (max-width: 768px) {
            .form-section { padding: 20px; } .form-header { padding: 20px; }
            .form-header h3 { font-size: 1.5rem; } .field-row { flex-direction: column; gap: 0px; }
            .field-group { margin-bottom: 1rem; } .paired-fields { gap: 15px; }
            .avatar-upload { width:120px; height: 120px; margin-bottom: 10px;}
            .avatar-upload label { width: 35px; height: 35px;}
        }
    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <i class="fas fa-user-edit fa-lg me-2" style="color: var(--primary-color);"></i>
                <div>
                    <h4 class="content-title mb-0 my-auto" style="font-weight: 600;">تعديل بيانات المريض</h4>
                    <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ {{ $patientNameForTitle }}</span>
                </div>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <a href="{{ route('admin.Patients.index') }}" class="btn btn-outline-secondary btn-sm ripple-effect">
                <i class="fas fa-arrow-left me-1"></i> رجوع لقائمة المرضى
            </a>
        </div>
    </div>
@endsection

@section('content')
    @include('Dashboard.messages_alert')

    <div class="patient-form-container animate__animated animate__fadeInUp">
        <div class="form-header">
            <i class="fas fa-user-edit"></i>
            <h3>تعديل بيانات المريض</h3>
            <p class="mb-0">قم بتحديث بيانات المريض: <strong>{{ $patientNameForTitle }}</strong></p>
        </div>
        <div class="form-section">
            <form action="{{ route('admin.Patients.update', $Patient->id) }}" method="post" autocomplete="off"
                id="patientFormEdit" enctype="multipart/form-data" novalidate class="needs-validation">
                @method('PUT')
                @csrf
                <input type="hidden" name="id" value="{{ $Patient->id }}">

                <div class="text-center mb-4">
                    <div class="avatar-upload d-inline-block">
                        <img id="output_edit"
                            src="{{ $Patient->image ? asset('Dashboard/img/patients/' . $Patient->image->filename) : URL::asset('Dashboard/img/default_patient_avatar.png') }}"
                            alt="الصورة الشخصية"
                            onerror="this.onerror=null; this.src='{{ URL::asset('Dashboard/img/default_patient_avatar.png') }}';">
                        <label for="avatar_upload_input_edit">
                            <i class="fas fa-camera"></i>
                        </label>
                        <input id="avatar_upload_input_edit" type="file" accept="image/*" name="photo" onchange="loadFile(event, 'output_edit')">
                    </div>
                    @error('photo') <div class="text-danger d-block mt-1">{{ $message }}</div> @enderror
                </div>

                <h5 class="section-title-sub" style="border:none; padding-bottom:0; margin-bottom: 0.8rem;"><i class="fas fa-user-circle"></i> المعلومات الأساسية</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name_edit" class="form-label required">الاسم الكامل</label>
                            <input type="text" id="name_edit" name="name" value="{{ old('name', $Patient->name) }}"
                                class="form-control @error('name') is-invalid @enderror" required placeholder="الاسم رباعي">
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@else<div class="valid-feedback"><i class="fas fa-check-circle"></i></div>@enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email_edit" class="form-label required">البريد الإلكتروني</label>
                            <input type="email" id="email_edit" name="email" value="{{ old('email', $Patient->email) }}"
                                class="form-control @error('email') is-invalid @enderror" required placeholder="example@mail.com">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@else<div class="valid-feedback"><i class="fas fa-check-circle"></i></div>@enderror
                        </div>
                    </div>
                </div>

                <hr class="form-section-divider">
                <h5 class="section-title-sub"><i class="fas fa-id-card"></i> معلومات الهوية والاتصال</h5>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="national_id_edit" class="form-label required">رقم الهوية</label>
                            <input class="form-control @error('national_id') is-invalid @enderror" id="national_id_edit" name="national_id"
                                type="text" value="{{ old('national_id', $Patient->national_id) }}" pattern="[0-9]{9}"
                                maxlength="9" required oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder="9 أرقام">
                            @error('national_id')<div class="invalid-feedback">{{ $message }}</div>@else<div class="valid-feedback"><i class="fas fa-check-circle"></i></div>@enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="Phone_edit" class="form-label required">رقم الهاتف</label>
                            <input type="tel" id="Phone_edit" name="Phone" value="{{ old('Phone', $Patient->Phone) }}"
                                class="form-control @error('Phone') is-invalid @enderror"
                                pattern="^(05\d{8})$" required title="مثال: 059xxxxxxx أو 056xxxxxxx" placeholder="05xxxxxxxx">
                            @error('Phone')<div class="invalid-feedback">{{ $message }}</div>@else<div class="valid-feedback"><i class="fas fa-check-circle"></i></div>@enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="Date_Birth_edit" class="form-label required">تاريخ الميلاد</label>
                            <input class="form-control flatpickr-date @error('Date_Birth') is-invalid @enderror" id="Date_Birth_edit" name="Date_Birth" value="{{ old('Date_Birth', $Patient->Date_Birth ? $Patient->Date_Birth->format('Y-m-d') : '') }}" type="text" autocomplete="off" required placeholder="YYYY-MM-DD">
                            @error('Date_Birth')<div class="invalid-feedback">{{ $message }}</div>@else<div class="valid-feedback"><i class="fas fa-check-circle"></i></div>@enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Gender_edit" class="form-label required">الجنس</label>
                            <select class="form-select select2 @error('Gender') is-invalid @enderror" id="Gender_edit" name="Gender" required data-placeholder="-- اختر الجنس --">
                                <option value=""></option>
                                <option value="1" {{ old('Gender', $Patient->Gender) == 1 ? 'selected' : '' }}>ذكر</option>
                                <option value="2" {{ old('Gender', $Patient->Gender) == 2 ? 'selected' : '' }}>أنثى</option>
                            </select>
                            @error('Gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="Blood_Group_edit" class="form-label required">فصيلة الدم</label>
                            <select class="form-select select2 @error('Blood_Group') is-invalid @enderror" id="Blood_Group_edit" name="Blood_Group" required data-placeholder="-- اختر الفصيلة --">
                                <option value=""></option>
                                @php $bloodGroupsArray = ['O-', 'O+', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-']; @endphp
                                @foreach($bloodGroupsArray as $group)
                                    <option value="{{ $group }}" {{ old('Blood_Group', $Patient->Blood_Group) == $group ? 'selected' : '' }}>{{ $group }}</option>
                                @endforeach
                            </select>
                            @error('Blood_Group')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="Address_edit" class="form-label">العنوان التفصيلي</label>
                            <textarea rows="2" class="form-control @error('Address') is-invalid @enderror" id="Address_edit" name="Address" placeholder="مثال: الخليل - دورا - حي رأس كركر - بالقرب من مسجد التقوى">{{ old('Address', $Patient->getTranslation('Address', 'ar')) }}</textarea>
                            @error('Address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <hr class="form-section-divider">
                <div class="chronic-diseases-section">
                    <h5 class="section-title-sub"><i class="fas fa-laptop-medical"></i> السجل الطبي (الأمراض المزمنة)</h5>
                    <div id="chronic_diseases_wrapper_edit">
                        @php $cd_edit_idx = 0; @endphp
                        @if(!empty(old('chronic_diseases', $patientExistingChronicDiseases ?? [])))
                            @foreach(old('chronic_diseases', $patientExistingChronicDiseases) as $index => $existingDiseaseData)
                            <div class="chronic_disease_row">
                                <div class="col-md-3 field-group">
                                    <label class="form-label">المرض</label>
                                    <select name="chronic_diseases[{{$cd_edit_idx}}][disease_id]" class="form-select select2-diseases-dynamic @error('chronic_diseases.'.$cd_edit_idx.'.disease_id') is-invalid @enderror" data-placeholder="اختر مرض">
                                        <option value=""></option>
                                        @if(isset($diseases_list))
                                            @foreach($diseases_list as $id => $name)
                                                <option value="{{ $id }}" {{ ($existingDiseaseData['disease_id'] ?? null) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('chronic_diseases.'.$cd_edit_idx.'.disease_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-2 field-group">
                                     <label class="form-label">تاريخ التشخيص</label>
                                    <input type="text" name="chronic_diseases[{{$cd_edit_idx}}][diagnosed_at]" value="{{ $existingDiseaseData['diagnosed_at'] ?? '' }}" class="form-control flatpickr-date-chronic @error('chronic_diseases.'.$cd_edit_idx.'.diagnosed_at') is-invalid @enderror" placeholder="YYYY-MM-DD">
                                    @error('chronic_diseases.'.$cd_edit_idx.'.diagnosed_at') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-2 field-group">
                                    <label class="form-label">الحالة الحالية</label>
                                    <select name="chronic_diseases[{{$cd_edit_idx}}][current_status]" class="form-select select2-statuses-dynamic @error('chronic_diseases.'.$cd_edit_idx.'.current_status') is-invalid @enderror" data-placeholder="الحالة">
                                        <option value=""></option>
                                        @if(isset($chronic_disease_statuses))
                                            @foreach($chronic_disease_statuses as $key => $value)
                                                <option value="{{ $key }}" {{ ($existingDiseaseData['current_status'] ?? null) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('chronic_diseases.'.$cd_edit_idx.'.current_status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4 field-group">
                                    <label class="form-label">ملاحظات / علاج</label>
                                    <input type="text" name="chronic_diseases[{{$cd_edit_idx}}][notes]" value="{{ $existingDiseaseData['notes'] ?? '' }}" class="form-control @error('chronic_diseases.'.$cd_edit_idx.'.notes') is-invalid @enderror" placeholder="ملاحظات مختصرة أو خطة علاج">
                                    @error('chronic_diseases.'.$cd_edit_idx.'.notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-1 field-group">
                                    <button type="button" class="remove_chronic_disease_row_btn" title="حذف هذا المرض"><i class="fas fa-trash-can"></i></button>
                                </div>
                            </div>
                            @php $cd_edit_idx++; @endphp
                            @endforeach
                        @endif
                    </div>
                    <button type="button" class="btn mt-3" id="add_chronic_disease_btn_edit">
                        <i class="fas fa-plus-circle"></i> إضافة مرض مزمن
                    </button>
                </div>

                <hr class="form-section-divider">
                <h5 class="section-title-sub"><i class="fas fa-key"></i> تحديث كلمة المرور (اختياري)</h5>
                <div class="alert alert-light border-info text-info-emphasis" role="alert" style="font-size: 0.9rem; background-color: rgba(var(--admin-info-rgb), 0.05);">
                    <i class="fas fa-info-circle me-2"></i>
                    اترك حقول كلمة المرور فارغة إذا كنت لا ترغب في تغييرها. يجب أن تكون كلمة المرور الجديدة 8 أحرف على الأقل وتحتوي على حروف وأرقام.
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group password-wrapper">
                            <label for="password_edit" class="form-label">كلمة المرور الجديدة</label>
                            <input type="password" id="password_edit" name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="اتركه فارغًا لعدم التغيير" minlength="8"
                                pattern="^(?=.*[A-Za-z])(?=.*\d).{8,}$">
                            <button type="button" class="toggle-password" data-target="password_edit" tabindex="-1">
                                <i class="fas fa-eye"></i>
                            </button>
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group password-wrapper">
                            <label for="password_confirmation_edit" class="form-label">تأكيد كلمة المرور الجديدة</label>
                            <input type="password" id="password_confirmation_edit" name="password_confirmation"
                                class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="أعد إدخال كلمة المرور">
                            <button type="button" class="toggle-password" data-target="password_confirmation_edit" tabindex="-1">
                                <i class="fas fa-eye"></i>
                            </button>
                            @error('password_confirmation') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.Patients.index') }}" class="btn btn-cancel me-2 ripple-effect">
                        <i class="fas fa-times-circle me-1"></i> إلغاء
                    </a>
                    <button type="submit" class="btn btn-submit ripple-effect" id="submitPatientFormEdit">
                        <span class="btn-text"><i class="fas fa-save me-1"></i> حفظ التعديلات</span>
                        <i class="fas fa-spinner fa-spin spinner-icon"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- قالب صف الأمراض المزمنة (للاستنساخ بواسطة JavaScript) --}}
    <template id="chronic_disease_template_edit">
        <div class="chronic_disease_row">
            <div class="col-md-3 field-group">
                 <label class="form-label">المرض</label>
                <select name="chronic_diseases[__INDEX__][disease_id]" class="form-select select2-diseases-dynamic" data-placeholder="اختر مرض">
                    <option value=""></option>
                    @if(isset($diseases_list))
                        @foreach($diseases_list as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-2 field-group">
                <label class="form-label">تاريخ التشخيص</label>
                <input type="text" name="chronic_diseases[__INDEX__][diagnosed_at]" class="form-control flatpickr-date-chronic-dynamic" placeholder="YYYY-MM-DD">
            </div>
            <div class="col-md-2 field-group">
                <label class="form-label">الحالة الحالية</label>
                <select name="chronic_diseases[__INDEX__][current_status]" class="form-select select2-statuses-dynamic" data-placeholder="الحالة">
                    <option value=""></option>
                     @if(isset($chronic_disease_statuses))
                        @foreach($chronic_disease_statuses as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-md-4 field-group">
                <label class="form-label">ملاحظات / علاج</label>
                <input type="text" name="chronic_diseases[__INDEX__][notes]" class="form-control" placeholder="ملاحظات مختصرة أو خطة علاج">
            </div>
            <div class="col-md-1 field-group">
                <button type="button" class="remove_chronic_disease_row_btn" title="حذف هذا المرض"><i class="fas fa-trash-can"></i></button>
            </div>
        </div>
    </template>
@endsection

@section('js')
    @parent
    <script src="{{ URL::asset('dashboard/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('dashboard/plugins/notify/js/notifit-custom.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/ar.js"></script>
    <script src="{{ URL::asset('Dashboard/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/select2/js/i18n/ar.js') }}"></script>

    <script>
        var loadFile = function(event, outputId = 'output_edit') { // Default to edit ID
            var output = document.getElementById(outputId);
            var fileInput = event.target;
            if (fileInput.files && fileInput.files[0]) {
                const file = fileInput.files[0];
                if (file.size > 2 * 1024 * 1024) {
                    notif({ msg: "<b><i class='fas fa-exclamation-triangle me-1'></i> تنبيه:</b> حجم الصورة كبير جدًا. الحد الأقصى 2MB.", type: "warning", position: "center", timeout: 5000 });
                    fileInput.value = ""; return;
                }
                const validImageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml', 'image/webp'];
                if (!validImageTypes.includes(file.type)) {
                    notif({ msg: "<b><i class='fas fa-times-circle me-1'></i> خطأ:</b> نوع الملف غير مدعوم. يرجى اختيار صورة.", type: "error", position: "center", timeout: 5000 });
                    fileInput.value = ""; return;
                }
                output.src = URL.createObjectURL(file);
                output.onload = function() { URL.revokeObjectURL(output.src); }
            }
        };

        $(document).ready(function() {
            const flatpickrConfig = { dateFormat: "Y-m-d", locale: "ar", allowInput: true, disableMobile: "true" };
            flatpickr("#Date_Birth_edit", { ...flatpickrConfig, maxDate: "today" }); // Changed ID

            function initializeFlatpickrOnRow(selector) {
                flatpickr(selector, { ...flatpickrConfig, maxDate: "today" });
            }

            function initializeSelect2(selector, placeholder, parent = $(document.body), customOptions = {}) {
                $(selector).select2({
                    placeholder: placeholder || "-- اختر --",
                    width: '100%', dir: "rtl", theme: "bootstrap-5",
                    allowClear: !$(selector).prop('required'),
                    dropdownParent: parent,
                    ...customOptions
                }).on('select2:open', function (e) {
                    const evt = "scroll.select2";
                    $(e.target).parents().off(evt); $(window).off(evt);
                });
            }
            initializeSelect2('.select2', null, $('#patientFormEdit')); // Changed form ID


            // --- Chronic Diseases Section for EDIT page ---
            let chronicDiseaseCounterEdit = {{ $cd_edit_idx ?? 0 }}; // Use the counter from Blade for existing rows

            function initializeChronicDiseaseRowPluginsEdit(rowElement) {
                const $row = $(rowElement);
                initializeSelect2($row.find('.select2-diseases-dynamic'), "اختر المرض", $row.find('.col-md-3'));
                initializeSelect2($row.find('.select2-statuses-dynamic'), "اختر الحالة", $row.find('.col-md-2').eq(1)); // .eq(1) targets the second col-md-2 which is for status
                $row.find('.flatpickr-date-chronic-dynamic, .flatpickr-date-chronic').each(function() {
                    if (this._flatpickr) { this._flatpickr.destroy(); }
                    initializeFlatpickrOnRow(this);
                });
            }

            // Initialize for existing rows on page load
            $('#chronic_diseases_wrapper_edit .chronic_disease_row').each(function() {
                initializeChronicDiseaseRowPluginsEdit(this);
            });

            $('#add_chronic_disease_btn_edit').click(function() {
                const template = document.getElementById('chronic_disease_template_edit').innerHTML;
                const newRowHtml = template.replace(/__INDEX__/g, chronicDiseaseCounterEdit);
                chronicDiseaseCounterEdit++;
                const $newRow = $(newRowHtml).appendTo('#chronic_diseases_wrapper_edit');
                initializeChronicDiseaseRowPluginsEdit($newRow[0]);
                $newRow.find('select.select2-diseases-dynamic').first().select2('open');
            });

            $('#chronic_diseases_wrapper_edit').on('click', '.remove_chronic_disease_row_btn', function() {
                $(this).closest('.chronic_disease_row').addClass('animate__animated animate__zoomOutRight').one('animationend', function() {
                    $(this).remove();
                });
            });

            const patientForm = document.getElementById('patientFormEdit'); // Changed ID
            const submitButton = document.getElementById('submitPatientFormEdit'); // Changed ID

            if (patientForm) {
                patientForm.addEventListener('submit', function (event) {
                    if (!patientForm.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                        const firstInvalidField = patientForm.querySelector(':invalid:not(fieldset)');
                        if (firstInvalidField) {
                            firstInvalidField.focus({preventScroll:true});
                            $('html, body').animate({ scrollTop: $(firstInvalidField).offset().top - 120 }, 500);
                            showNotif("يرجى ملء جميع الحقول المطلوبة بشكل صحيح.", "warning", "top-center");
                        }
                    } else {
                        if(submitButton) {
                            submitButton.classList.add('loading');
                            submitButton.disabled = true;
                        }
                    }
                    patientForm.classList.add('was-validated');
                }, false);
            }

            const showNotif = (message, type = 'info', position = 'bottom-right', autohide = true, timeout = 5000) => {
                let iconClass = 'fas fa-info-circle';
                if (type === 'success') iconClass = 'fas fa-check-circle';
                else if (type === 'error') iconClass = 'fas fa-times-circle';
                else if (type === 'warning') iconClass = 'fas fa-exclamation-triangle';
                notif({ msg: `<div class="d-flex align-items-center"><i class='${iconClass} fa-lg me-2'></i><div>${message}</div></div>`, type, position, autohide, timeout, multiline: true, zindex: 99999, width: 'auto', padding: '15px' });
            };

            @if (session('edit')) // Changed from 'success' to 'edit' to match repository
                showNotif("{{ session('edit') }}", "success", "top-center", true, 6000);
            @endif
            @if (session('error'))
                showNotif("{{ session('error') }}", "error", "top-center", false);
            @endif
            @if ($errors->any())
                let errorList = "<strong><i class='fas fa-exclamation-triangle me-2'></i> حدثت الأخطاء التالية:</strong><ul class='mb-0 mt-2' style='list-style-type:none; padding-right:0;'>";
                @foreach ($errors->all() as $error)
                    errorList += "<li class='mb-1'>- {{ $error }}</li>";
                @endforeach
                errorList += "</ul>";
                showNotif(errorList, "error", "top-center", false);
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

            $('.form-control').on('blur input', function() {
                if ($(this).val()) { $(this).addClass('is-filled'); }
                else { $(this).removeClass('is-filled'); }
            }).trigger('blur');
        });
    </script>
@endsection
