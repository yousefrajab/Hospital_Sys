@extends('Dashboard.layouts.master')

@section('css')
    @include('Style.Style')
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #6366F1, #8B5CF6);
            --secondary-gradient: linear-gradient(135deg, #06B6D4, #0EA5E9);
            --glass-effect: rgba(255, 255, 255, 0.25);
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
    </style>
    <link href="{{ URL::asset('Dashboard/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('Dashboard/plugins/neomorphic-ui/neomorphic.css') }}" rel="stylesheet">

@endsection

@section('title') {{ trans('doctors.edit_doctor') }} @stop

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto">{{ trans('doctors.The Doctors') }}</h4>
                <span class="text mt-1 tx-13 mr-2 mb-0">/ {{ trans('doctors.edit_doctor_data') }}</span>
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
                        <i class="fas fa-user-md me-2"></i> {{ trans('doctors.edit_doctor') }}
                    </h3>
                    <div class="badge bg-indigo-100 text-indigo-800 px-3 py-2 rounded-full">
                        <i class="fas fa-edit me-2"></i> {{ trans('doctors.modify data') }}
                    </div>
                </div>

                <form action="{{ route('Doctors.update', $doctor->id) }}" method="POST" enctype="multipart/form-data"
                    autocomplete="off">
                    @method('patch')
                    @csrf

                    <div class="row">
                        <div class="col-md-4">
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
                        </div>

                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="floating-label">
                                        <label>{{ trans('doctors.name') }}</label>
                                        {{-- <input class="form-control-modern w-100" name="name" type="text"
                                            value="{{ $doctor->name }}" required> --}}

                                        <input type="text" id="name" name="name" value="{{ $doctor->name }}"
                                            class="form-control @error('name') is-invalid @enderror"
                                            placeholder="الاسم الأول، الأب، الجد، العائلة" required
                                            pattern="^([\u0600-\u06FF\s]{10,}|[A-Za-z\s\-']{10,})$">
                                        <div class="invalid-feedback">يجب إدخال الاسم الكامل (على الأقل 10 أحرف)</div>
                                        <div class="valid-feedback"><i class="fas fa-check"></i> صحيح</div>
                                        <input type="hidden" name="id" value="{{ $doctor->id }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="floating-label">
                                        <label>{{ trans('doctors.national_id') }}</label>
                                        {{-- <input class="form-control-modern w-100" name="national_id" type="text"
                                            value="{{ $doctor->national_id }}" pattern="[0-9]{9}"
                                            title="يجب أن يتكون من 9 أرقام فقط"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '')" maxlength="9"
                                            required> --}}


                                        <input class="form-control" id="national_id" name="national_id"
                                            placeholder="ادخل رقم الهوية (9 أرقام)" type="text"
                                            value="{{ $doctor->national_id }}" pattern="[0-9]{9}"
                                            title="يجب أن يتكون رقم الهوية من 9 أرقام" maxlength="9" required
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                        <div class="invalid-feedback">يجب أن يتكون رقم الهوية من 9 أرقام</div>
                                        <div class="valid-feedback"><i class="fas fa-check"></i> صحيح</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="floating-label">
                                        <label>{{ trans('doctors.email') }}</label>


                                        <input type="email" id="email" name="email" value="{{ $doctor->email }}"
                                            class="form-control @error('email') is-invalid @enderror"
                                            placeholder="example@domain.com" required>
                                        <div class="invalid-feedback">يرجى إدخال بريد إلكتروني صحيح</div>
                                        <div class="valid-feedback"><i class="fas fa-check"></i> صحيح</div>
                                    </div>
                                </div>





                                <div class="col-md-6">
                                    <div class="floating-label position-relative">
                                        <label>{{ trans('doctors.password') }}</label>
                                        <input class="form-control-modern w-100 pr-5" name="password" type="password"
                                            placeholder="{{ trans('doctors.Leave it') }}" autocomplete="new-password"
                                            id="password-input">

                                        <!-- زر العين -->
                                        <button type="button" class="toggle-password-eye" onclick="togglePassword()">
                                            <i class="fas fa-eye" id="password-icon"></i>
                                        </button>

                                        {{-- <small class="form-text text-muted">يجب أن تحتوي أحرف على الأقل</small> --}}
                                    </div>


                                </div>


                                <div class="col-md-6">
                                    <div class="floating-label">
                                        <label>{{ trans('doctors.phone') }}</label>
                                        {{-- <input class="form-control-modern w-100" name="phone" type="tel"
                                            value="{{ $doctor->phone }}" required> --}}

                                        <input type="tel" id="phone" name="phone"
                                            value="{{ $doctor->phone }}"
                                            class="form-control @error('phone') is-invalid @enderror"
                                            placeholder="05XXXXXXXX" maxlength="12" required pattern="^05\d{8}$"
                                            title="يجب أن يبدأ رقم الجوال بـ 05 ويتكون من 10 أرقام">
                                        <div class="invalid-feedback">يجب أن يبدأ رقم الجوال بـ 05 ويتكون من 10 أرقام</div>
                                        <div class="valid-feedback"><i class="fas fa-check"></i> صحيح</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="floating-label">
                                        <label>{{ trans('doctors.number_of_statements') }}</label>
                                        {{-- <input class="form-control-modern w-100" name="number_of_statements"
                                            type="number" min="0" value="{{ $doctor->number_of_statements }}"
                                            required> --}}

                                        <input class="form-control-modern w-100" name="number_of_statements"
                                            type="number" value="{{ $doctor->number_of_statements }}" min="1"
                                            max="20" required>
                                        <div class="invalid-feedback">يجب أن يكون العدد بين 1 و 20</div>
                                        <div class="valid-feedback"><i class="fas fa-check"></i> صحيح</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="floating-label">
                                <label>{{ trans('doctors.section') }}</label>
                                <select name="section_id" class="form-control-modern w-100" required>
                                    {{-- <option value="" disabled selected>---{{ trans('doctors.Select Section') }}---
                                    </option> --}}
                                    @foreach ($sections as $section)
                                        <option
                                            value="{{ $section->id }}"{{ $section->id == $doctor->section_id ? 'selected' : '' }}>
                                            {{ $section->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">يرجى اختيار القسم</div>
                                <div class="valid-feedback"><i class="fas fa-check"></i> تم الاختيار</div>
                            </div>
                        </div>

                        <div class="col-md-6">
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
                        </div>
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
                                <span>{{ trans('doctors.modify data') }}</span>
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
    <script src="{{ URL::asset('Dashboard/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/neomorphic-ui/neomorphic.js') }}"></script>

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
    <script>
        var loadFile = function(event) {
            var output = document.getElementById('output');
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src);
            }
        };
    </script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap4',
                placeholder: "اختر",
                width: '100%'
            });

            // تأثيرات عند التركيز على الحقول
            $('.form-control-modern').focus(function() {
                $(this).parent().find('label').css('color', '#8B5CF6');
            }).blur(function() {
                $(this).parent().find('label').css('color', '#6366F1');
            });
        });
    </script>
    <script>
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
    </script>

@endsection
