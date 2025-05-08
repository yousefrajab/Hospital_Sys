{{-- resources/views/Dashboard/Admin/users_roles/edit_doctor.blade.php --}}
@extends('Dashboard.layouts.master')

@php
    // استخدام isTranslatableName لتحديد كيفية الحصول على الاسم
    $doctorName = $isTranslatableName ? ($user->getTranslation('name', app()->getLocale(), false) ?: $user->name) : $user->name;
@endphp
@section('title', 'تعديل بيانات الطبيب: ' . $doctorName)

@section('css')
    @parent
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link href="{{ URL::asset('Dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('Dashboard/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

    <style>
        /* ... (أنماط CSS الممتازة التي قدمتها - تبقى كما هي بالكامل) ... */
        :root {
            --primary-color: #4e73ff; --secondary-color: #3a56cd; --accent-color: #6c63ff;
            --light-color: #f8f9fa; --dark-color: #212529; --danger-color: #ff4757;
            --success-color: #2ed573; --warning-color: #ffa502;
            --admin-radius-md: 0.375rem;
        }
        body { background-color: var(--admin-bg, #f8f9fc); color: var(--admin-text-primary, #1e293b); font-family: 'Tajawal', sans-serif; }
        .doctor-edit-container { background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%); min-height: 100vh; padding: 2rem 0; }
        .doctor-edit-card { border-radius: 15px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1); border: none; overflow: hidden; background: white; transition: all 0.3s ease; }
        /* ... (بقية الـ CSS) ... */
        input[name="photo"]#user_photo_input { display: none; }
    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto">إدارة المستخدمين</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تعديل بيانات الطبيب / {{ $doctorName }}</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <a href="{{ route('admin.users.roles.index') }}" class="btn btn-outline-secondary btn-sm ripple-effect">
                <i class="fas fa-arrow-left me-1"></i> العودة لقائمة المستخدمين
            </a>
        </div>
    </div>
@endsection

@section('content')
    @include('Dashboard.messages_alert')

    <div class="doctor-edit-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9 col-md-10">
                    <div class="doctor-edit-card">
                        <div class="edit-card-header">
                            <h3 class="edit-card-title">
                                <i class="fas fa-user-md"></i>
                                تعديل بيانات الطبيب: {{ $doctorName }}
                            </h3>
                        </div>

                        <div class="card-body p-lg-5 p-4">
                            <form action="{{ route('admin.users.roles.update', ['role_key' => $role_key, 'id' => $user->id]) }}"
                                  method="POST"
                                  enctype="multipart/form-data"
                                  class="needs-validation"
                                  novalidate
                                  autocomplete="off">
                                @method('PUT')
                                @csrf

                                <div class="doctor-avatar-form-container">
                                    <img id="user_photo_preview"
                                         src="{{ $user->image ? URL::asset('Dashboard/img/doctors/' . $user->image->filename) : URL::asset('Dashboard/img/faces/doctor_default.png') }}"
                                         alt="صورة الطبيب">
                                    <label for="user_photo_input" class="upload-btn-edit" title="تغيير الصورة">
                                        <i class="fas fa-camera"></i>
                                    </label>
                                    <input type="file" name="photo" id="user_photo_input" accept="image/*,image/webp"
                                           onchange="previewUserImage(event, 'user_photo_preview')">
                                </div>
                                @error('photo')
                                    <div class="text-center mb-3">
                                        <small class="text-danger">{{ $message }}</small>
                                    </div>
                                @enderror

                                <div class="row">
                                    <!-- الاسم الكامل -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name_edit" class="form-label">الاسم الكامل <span class="text-danger">*</span></label>
                                            <input type="text"
                                                   id="name_edit"
                                                   name="name"
                                                   class="form-control @error('name') is-invalid @enderror"
                                                   value="{{ old('name', ($isTranslatableName ? ($user->getTranslation('name', app()->getLocale(), false) ?: $user->name) : $user->name)) }}"
                                                   required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- البريد الإلكتروني -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email_edit" class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                                            <input type="email"
                                                   id="email_edit"
                                                   name="email"
                                                   class="form-control @error('email') is-invalid @enderror"
                                                   value="{{ old('email', $user->email) }}"
                                                   required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- رقم الهاتف -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone_edit" class="form-label">رقم الهاتف <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                {{-- <span class="input-group-text" id="phone-addon-edit">+966</span> --}}
                                                <input type="tel" id="phone" name="phone"
                                                value="{{ $user->phone }}"
                                                class="form-control @error('phone') is-invalid @enderror"
                                                placeholder="05XXXXXXXX" maxlength="12" required pattern="^05\d{8}$"
                                                title="يجب أن يبدأ رقم الجوال بـ 05 ويتكون من 10 أرقام">
                                            <div class="invalid-feedback">يجب أن يبدأ رقم الجوال بـ 05 ويتكون من 10 أرقام</div>
                                            <div class="valid-feedback"><i class="fas fa-check"></i> صحيح</div>
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- الحالة -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status_edit" class="form-label">الحالة <span class="text-danger">*</span></label>
                                            <select name="status"
                                                    id="status_edit"
                                                    class="form-select @error('status') is-invalid @enderror"
                                                    required>
                                                <option value="1" {{ old('status', $user->status) == 1 ? 'selected' : '' }}>نشط</option>
                                                <option value="0" {{ old('status', $user->status) == 0 ? 'selected' : '' }}>غير نشط</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- قسم الطبيب -->
                                    @if($role_key === 'doctor' && isset($sections) && $sections->count() > 0)
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="section_id_edit" class="form-label">القسم التخصصي <span class="text-danger">*</span></label>
                                            <select name="section_id"
                                                    id="section_id_edit"
                                                    class="form-select select2 @error('section_id') is-invalid @enderror"
                                                    required>
                                                <option value="">-- اختر القسم --</option>
                                                @foreach($sections as $section)
                                                    <option value="{{ $section->id }}"
                                                        {{ old('section_id', $user->section_id ?? '') == $section->id ? 'selected' : '' }}>
                                                        {{ $section->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('section_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    @endif

                                    <!-- كلمة المرور -->
                                    <div class="col-md-12">
                                        <hr class="my-4">
                                        <div class="alert alert-info bg-light border-info text-info-emphasis" role="alert" style="font-size: 0.9rem;">
                                            <i class="fas fa-info-circle me-2"></i>
                                            اترك حقول كلمة المرور فارغة إذا كنت لا تريد تغييرها.
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group password-wrapper">
                                            <label for="password_edit_input" class="form-label">كلمة المرور الجديدة</label>
                                            <input type="password"
                                                   id="password_edit_input"
                                                   name="password"
                                                   class="form-control @error('password') is-invalid @enderror"
                                                   autocomplete="new-password"
                                                   minlength="8">
                                            <button type="button" class="toggle-password-btn" data-target="password_edit_input" tabindex="-1">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group password-wrapper">
                                            <label for="password_confirmation_input" class="form-label">تأكيد كلمة المرور</label>
                                            <input type="password"
                                                   id="password_confirmation_input"
                                                   name="password_confirmation"
                                                   class="form-control @error('password_confirmation') is-invalid @enderror"
                                                   autocomplete="new-password"
                                                   minlength="8">
                                            <button type="button" class="toggle-password-btn" data-target="password_confirmation_input" tabindex="-1">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @error('password_confirmation')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <a href="{{ route('admin.users.roles.index') }}" class="btn btn-cancel ripple-effect">
                                        <i class="fas fa-times me-1"></i> إلغاء
                                    </a>
                                    <button type="submit" class="btn btn-submit ripple-effect pulse-animation">
                                        <i class="fas fa-save me-1"></i> حفظ التغييرات
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    @parent
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifit-custom.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/select2/js/i18n/ar.js') }}"></script>
    <script>
        // (نفس أكواد JavaScript الممتازة التي قدمتها)
        // معاينة الصورة
        if (typeof window.previewUserImage !== 'function') { /* ... */ }
        document.addEventListener('DOMContentLoaded', function() { /* ... */ });
    </script>
@endsection
