@extends('Dashboard.layouts.master')

@section('css')
    @include('Style.Style')
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #6366F1, #8B5CF6);
            --secondary-gradient: linear-gradient(135deg, #06B6D4, #0EA5E9);
            --glass-effect: rgba(255, 255, 255, 0.25);
            --error-color: #ff006e;
            --success-color: #38b000;
        }

        body {
            background: #F8FAFC;
            font-family: 'Inter', sans-serif;
        }

        /* التنسيقات الأساسية */
        .btn-custom {
            position: relative;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            overflow: hidden;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 150px;
            text-align: center;
        }

        /* زر الإلغاء */
        .btn-cancel {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8eb 100%);
            color: #6c757d;
            box-shadow: 0 4px 6px rgba(108, 117, 125, 0.1);
        }

        /* زر الحفظ */
        .btn-save {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            box-shadow: 0 4px 6px rgba(0, 178, 255, 0.2);
        }

        /* تأثيرات التحويم */
        .btn-cancel:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(108, 117, 125, 0.15);
            color: #5a6268;
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 178, 255, 0.3);
            background: linear-gradient(135deg, #3a9ffd 0%, #00d9e9 100%);
        }

        /* تأثير النقر */
        .btn-custom:active {
            transform: translateY(1px);
        }

        /* موجة الحركة */
        .btn-wave {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 5px;
            height: 5px;
            background: rgba(255, 255, 255, 0.5);
            opacity: 0;
            border-radius: 100%;
            transform: scale(1, 1) translate(-50%, -50%);
            transform-origin: 50% 50%;
            transition: all 0.5s ease-out;
        }

        .btn-custom:hover .btn-wave {
            opacity: 1;
            transform: scale(50, 50) translate(-50%, -50%);
        }

        /* التأخير في موجة الحركة */
        .btn-save .btn-wave {
            transition-delay: 0.1s;
        }

        /* محتوى الزر */
        .btn-content {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
        }

        /* المسافة بين الأزرار */
        .gap-4 {
            gap: 1rem;
        }

        .card-3d {
            background: white;
            border-radius: 24px;
            box-shadow:
                0 10px 25px -5px rgba(0, 0, 0, 0.1),
                0 10px 10px -5px rgba(0, 0, 0, 0.04);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-3d:hover {
            transform: translateY(-4px);
            box-shadow:
                0 20px 25px -5px rgba(0, 0, 0, 0.1),
                0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            position: relative;
            padding-bottom: 12px;
        }

        .section-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 4px;
            background: var(--primary-gradient);
            border-radius: 2px;
        }

        .form-control-modern {
            border-radius: 12px;
            border: 1px solid #E2E8F0;
            padding: 12px 16px;
            transition: all 0.3s;
            background: rgba(255, 255, 255, 0.7);
        }

        .form-control-modern:focus {
            border-color: #8B5CF6;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.2);
            background: white;
        }

        .form-control-modern.is-invalid {
            border-color: var(--error-color);
            background-image: none;
        }

        .form-control-modern.is-valid {
            border-color: var(--success-color);
        }

        .invalid-feedback {
            color: var(--error-color);
            font-size: 0.85rem;
            margin-top: 8px;
            display: none;
        }

        .valid-feedback {
            color: var(--success-color);
            font-size: 0.85rem;
            margin-top: 8px;
            display: none;
        }

        .avatar-upload {
            position: relative;
            width: 150px;
            height: 200px;
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

        .select2-container--bootstrap4 .select2-selection {
            border-radius: 12px !important;
            border: 1px solid #E2E8F0 !important;
            padding: 8px 12px !important;
        }

        .floating-label {
            position: relative;
            margin-bottom: 24px;
        }

        .floating-label label {
            position: absolute;
            top: -10px;
            left: 16px;
            background: white;
            padding: 0 8px;
            font-size: 13px;
            color: #6366F1;
            font-weight: 600;
        }

        .toggle-password-eye {
            position: absolute;
            top: 50%;
            right: 16px;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            z-index: 10;
        }

        .toggle-password-eye i {
            font-size: 18px;
            color: #8B5CF6;
        }

        /* رسائل التنبيه */
        .alert-message {
            padding: 18px;
            border-radius: 12px;
            margin-bottom: 25px;
            display: none;
            position: relative;
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

        /* تأثيرات الحركة */
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

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .animate__fadeIn {
            animation: fadeIn 0.5s;
        }
    </style>
@endsection

@section('title') {{ trans('doctors.add_doctor') }} @stop

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto">{{ trans('doctors.The Doctors') }}</h4>
                <span class="text mt-1 tx-13 mr-2 mb-0">/ {{ trans('doctors.Add new Doctor') }}</span>
            </div>
        </div>
        <div class="d-flex my-xl-auto right-content">
            <a href="{{ route('Doctors.index') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> {{ trans('doctors.Return to Doctors List') }}
            </a>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    @include('Dashboard.messages_alert')

    <div class="row justify-content-center">
        <div class="col-lg-10 col-md-12">
            <div class="card-3d p-6">
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <h3 class="section-title">
                        <i class="fas fa-user-md me-2"></i> {{ trans('doctors.add_doctor') }}
                    </h3>
                    <div class="badge bg-indigo-100 text-indigo-800 px-3 py-2 rounded-full">
                        {{-- <i class="fas fa-id-card me-2"></i> تسجيل جديد --}}
                    </div>
                </div>

                <!-- رسائل التنبيه -->
                <div id="successAlert" class="alert-message alert-success animate__fadeIn">
                    <i class="fas fa-check-circle"></i> تم تسجيل الطبيب بنجاح في قاعدة البيانات!
                </div>

                <div id="errorAlert" class="alert-message alert-error animate__fadeIn">
                    <i class="fas fa-exclamation-circle"></i> حدث خطأ أثناء التسجيل، يرجى مراجعة البيانات المطلوبة.
                </div>

                <form action="{{ route('Doctors.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off"
                    id="doctorForm">
                    @csrf

                    <div class="row">
                        <div class="col-md-4">
                            <div class="avatar-upload mb-4">
                                <img id="output" src="{{ URL::asset('Dashboard/img/doctorr_default.png') }}"
                                    alt="{{ trans('doctors.img') }}">
                                <label for="avatar-upload">
                                    <i class="fas fa-camera"></i>
                                </label>
                                <input id="avatar-upload" type="file" accept="image/*" name="photo"
                                    onchange="loadFile(event)">
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="floating-label">
                                        <label class="required">{{ trans('doctors.name') }}</label>
                                        <input class="form-control-modern w-100" name="name" type="text"
                                            pattern="^([\u0600-\u06FF\s]{10,}|[A-Za-z\s\-']{10,})$"
                                            title="يجب إدخال الاسم الكامل (على الأقل 10 أحرف)" required>
                                        <div class="invalid-feedback">يجب إدخال الاسم الكامل (على الأقل 10 أحرف)</div>
                                        <div class="valid-feedback"><i class="fas fa-check"></i> صحيح</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="floating-label">
                                        <label class="required">{{ trans('doctors.national_id') }}</label>
                                        <input class="form-control-modern w-100" name="national_id" type="text"
                                            pattern="[0-9]{9}" title="يجب أن يتكون رقم الهوية من 9 أرقام فقط" required
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '')" maxlength="9">
                                        <div class="invalid-feedback">يجب أن يتكون رقم الهوية من 9 أرقام</div>
                                        <div class="valid-feedback"><i class="fas fa-check"></i> صحيح</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="floating-label">
                                        <label class="required">{{ trans('doctors.email') }}</label>
                                        <input class="form-control-modern w-100" name="email" type="email" required>
                                        <div class="invalid-feedback">يرجى إدخال بريد إلكتروني صحيح</div>
                                        <div class="valid-feedback"><i class="fas fa-check"></i> صحيح</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="floating-label">
                                        <label class="required">{{ trans('doctors.password') }}</label>
                                        <div class="password-toggle-group">
                                            <input class="form-control-modern w-100 pr-5" name="password" type="password"
                                                id="password-input" required minlength="8"
                                                pattern="^(?=.*[A-Za-z])(?=.*\d).{8,}$"
                                                title="يجب أن تحتوي على حروف وأرقام">
                                            <button type="button" class="toggle-password-eye" onclick="togglePassword()">
                                                <i class="fas fa-eye" id="password-icon"></i>
                                            </button>
                                        </div>
                                        <div class="invalid-feedback">يجب أن تحتوي كلمة المرور على 8 أحرف على الأقل وتشمل
                                            حروف وأرقام</div>
                                        <div class="valid-feedback"><i class="fas fa-check"></i> قوي</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="floating-label">
                                        <label class="required">{{ trans('doctors.phone') }}</label>
                                        <input class="form-control-modern w-100" name="phone" type="tel"
                                            pattern="^05\d{8}$" title="يجب أن يبدأ رقم الجوال بـ 05 ويتكون من 10 أرقام"
                                            required>
                                        <div class="invalid-feedback">يجب أن يبدأ رقم الجوال بـ 05 ويتكون من 10 أرقام</div>
                                        <div class="valid-feedback"><i class="fas fa-check"></i> صحيح</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="floating-label">
                                        <label class="required">{{ trans('doctors.number_of_statements') }}</label>
                                        <input class="form-control-modern w-100" name="number_of_statements"
                                            type="number" min="1" max="20" required>
                                        <div class="invalid-feedback">يجب أن يكون العدد بين 1 و 20</div>
                                        <div class="valid-feedback"><i class="fas fa-check"></i> صحيح</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="floating-label">
                                <label class="required">{{ trans('doctors.section') }}</label>
                                <select name="section_id" class="form-control-modern w-100" required>
                                    <option value="" disabled selected>---{{ trans('doctors.Select Section') }}---
                                    </option>
                                    @foreach ($sections as $section)
                                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">يرجى اختيار القسم</div>
                                <div class="valid-feedback"><i class="fas fa-check"></i> تم الاختيار</div>
                            </div>
                        </div>

                        {{-- <div class="col-md-6">
                            <div class="floating-label">
                                <label>{{ trans('doctors.appointments') }}</label>
                                <select class="form-control-modern select w-100" name="appointments[]"
                                    multiple="multiple">
                                    @foreach ($appointments as $appointment)
                                        @php $check = []; @endphp
                                        @foreach ($doctor->doctorappointments as $key => $appointmentDOC)
                                            @php
                                                $check[] = $appointmentDOC->id;
                                            @endphp
                                        @endforeach
                                        <option value="{{ $appointment->id }}"
                                            {{ in_array($appointment->id, $check) ? 'selected' : '' }}>
                                            {{ $appointment->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> --}}
                    </div>

                    <div class="d-flex justify-content-end mt-6 gap-4">
                        <!-- زر الإلغاء -->
                        <a href="{{ route('Doctors.index') }}" class="btn-custom btn-cancel">
                            <span class="btn-content">
                                <i class="fas fa-times me-2"></i>
                                <span>{{ trans('doctors.cancel') }}</span>
                            </span>
                            <span class="btn-wave"></span>
                        </a>

                        <!-- زر الحفظ -->
                        <button type="submit" class="btn-custom btn-save">
                            <span class="btn-content">
                                <i class="fas fa-save me-2"></i>
                                <span>{{ trans('doctors.submit') }}</span>
                            </span>
                            <span class="btn-wave"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ URL::asset('Dashboard/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/neomorphic-ui/neomorphic.js') }}"></script>

    <script>
        // تحميل الصورة المعاينة
        var loadFile = function(event) {
            var output = document.getElementById('output');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src);
            }
        };

        // تبديل رؤية كلمة المرور
        function togglePassword() {
            const input = document.getElementById('password-input');
            const icon = document.getElementById('password-icon');

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

        // تهيئة Select2
        $(document).ready(function() {
            // $('.select').select2({
            //     placeholder: "-- اختر المواعيد --",
            //     dir: "rtl",
            //     width: '100%'
            // });

            // التحقق من الحقول في الوقت الحقيقي
            $('input, select').on('input change', function() {
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
                        setTimeout(() => field.removeClass('shake'), 500);
                    }
                } else {
                    field.removeClass('is-invalid');
                    field.addClass('is-valid');
                    field.nextAll('.invalid-feedback').hide();
                    field.nextAll('.valid-feedback').show();
                }
            }

            // التحقق من النموذج قبل الإرسال
            $('#doctorForm').on('submit', function(e) {
                e.preventDefault();

                let isValid = true;
                $('input, select').each(function() {
                    validateField($(this));
                    if ($(this).is(':invalid') || ($(this).is(':required') && !$(this).val())) {
                        isValid = false;
                    }
                });

                if (isValid) {
                    // إرسال النموذج عبر AJAX لعرض الأخطاء من الخادم
                    $.ajax({
                        url: $(this).attr('action'),
                        method: 'POST',
                        data: new FormData(this),
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            // نجاح - إعادة توجيه أو عرض رسالة
                            window.location.href = "{{ route('Doctors.index') }}";
                        },
                        error: function(xhr) {
                            // عرض أخطاء الخادم
                            const errors = xhr.responseJSON.errors;
                            for (const field in errors) {
                                const input = $(`[name="${field}"]`);
                                input.addClass('is-invalid');
                                input.nextAll('.invalid-feedback').text(errors[field][0])
                                    .show();
                            }
                        }
                    });
                } else {
                    // عرض رسالة خطأ للتحقق من العميل
                    showErrorAlert();
                    $('html, body').animate({
                        scrollTop: $('.is-invalid').first().offset().top - 100
                    }, 500);
                }
            });
            // عرض رسالة النجاح
            function showSuccessAlert() {
                $('#successAlert').fadeIn().addClass('animate__tada');
                $('#errorAlert').fadeOut();
            }

            // عرض رسالة الخطأ
            function showErrorAlert() {
                $('#errorAlert').fadeIn().addClass('animate__headShake');
                $('#successAlert').fadeOut();
            }
        });
    </script>
@endsection
