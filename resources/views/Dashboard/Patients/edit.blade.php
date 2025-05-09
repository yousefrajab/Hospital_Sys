@extends('Dashboard.layouts.master')

@section('css')
    <!-- Flatpickr CSS -->
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Notify CSS -->
    <link href="{{ URL::asset('dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />

    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4895ef;
            --light-color: #f8f9fa;
            --dark-color: #212529;
        }

        .patient-form {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .form-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            padding: 20px;
            margin-bottom: 30px;
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

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 12px 15px;
            transition: all 0.3s;
            height: auto;
        }

        .form-control:focus {
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(72, 149, 239, 0.2);
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

        .select2-container--default .select2-selection--single {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            height: auto;
            padding: 10px;
        }

        .btn-submit {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border: none;
            padding: 12px 30px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }

        @media (max-width: 768px) {
            .form-section {
                padding: 0 15px 20px;
            }
        }


        .avatar-upload {
            position: relative;
            width: 100px;
            height: 180px;
            /* margin: 0 auto; */
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
    </style>
@endsection

@section('title')
    تعديل بيانات مريض
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto">المرضى</h4>
                <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تعديل بيانات مريض</span>
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
                    <h3><i class="fas fa-user-edit"></i> تعديل بيانات المريض</h3>
                    <p class="mb-0">قم بتحديث بيانات المريض {{ $Patient->name }} في النظام</p>
                </div>
                <div class="form-section">
                    <form action="{{ route('admin.Patients.update', 'test') }}" method="post" autocomplete="off"
                        id="patientForm" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <input type="hidden" name="id" value="{{ $Patient->id }}">
                        <div class="col-md-3">
                            <div class="avatar-upload mb-4">
                                @if ($Patient->image)
                                    <img id="output"
                                        src="{{ URL::asset('Dashboard/img/patients/' . $Patient->image->filename) }}"
                                        alt="{{ trans('patients.img') }}">
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
                        <div class="row">
                            <!-- المعلومات الشخصية -->

                            <div class="col-md-6">
                                <h5 class="section-title mb-4"><i class="fas fa-id-card"></i> المعلومات الشخصية</h5>

                                <div class="form-group">
                                    <label class="form-label">اسم المريض الكامل</label>
                                    <input type="text" name="name" value="{{ $Patient->name }}"
                                        class="form-control @error('name') is-invalid @enderror"
                                        placeholder="أدخل الاسم الثلاثي" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">البريد الإلكتروني</label>
                                    <input type="email" name="email" value="{{ $Patient->email }}"
                                        class="form-control @error('email') is-invalid @enderror"
                                        placeholder="example@domain.com" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">تاريخ الميلاد</label>
                                    <div class="datepicker-input">
                                        <input class="form-control" id="Date_Birth" name="Date_Birth"
                                            value="{{ $Patient->Date_Birth }}" type="text" autocomplete="off" required>
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">رقم الهاتف</label>
                                    <input type="tel" name="Phone" value="{{ $Patient->Phone }}"
                                        class="form-control @error('Phone') is-invalid @enderror" placeholder="05XXXXXXXX"
                                        required>
                                </div>


                                <div class="field-group">
                                    <label for="password" class="form-label required">كلمة المرور</label>
                                    <div class="input-group password-toggle-group">
                                        <input type="password" id="password" name="password"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="اتركه فارغا اذا لم ترد التغيير " minlength="8"
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
                                {{-- <div class="field-group">
                                    <label for="password_confirmation" class="form-label required">تأكيد كلمة
                                        المرور</label>
                                    <div class="input-group password-toggle-group">
                                        <input type="password" id="password_confirmation" name="password_confirmation"

                                            class="form-control" placeholder="أعد إدخال كلمة المرور" >
                                        <button class="btn toggle-password" type="button" tabindex="-1">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="invalid-feedback">كلمة المرور غير متطابقة</div>
                                    <div class="valid-feedback"><i class="fas fa-check"></i> متطابق</div>
                                </div> --}}
                            </div>

                            <!-- المعلومات الطبية -->
                            <div class="col-md-6">
                                <h5 class="section-title mb-4"><i class="fas fa-heartbeat"></i> المعلومات الطبية</h5>

                                <div class="form-group">
                                    <label class="form-label">رقم الهوية</label>
                                    <input type="text" name="national_id" title="يجب أن يتكون من 9 أرقام فقط"
                                        value="{{ $Patient->national_id }}" pattern="[0-9]{9}"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')" maxlength="9"
                                        class="form-control @error('national_id') is-invalid @enderror"
                                        placeholder="أدخل رقم الهوية" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">فصيلة الدم</label>
                                    <select class="form-control select2" name="Blood_Group" required>
                                        <option value="O-" {{ $Patient->Blood_Group == 'O-' ? 'selected' : '' }}>O-
                                        </option>
                                        <option value="O+" {{ $Patient->Blood_Group == 'O+' ? 'selected' : '' }}>O+
                                        </option>
                                        <option value="A+" {{ $Patient->Blood_Group == 'A+' ? 'selected' : '' }}>A+
                                        </option>
                                        <option value="A-" {{ $Patient->Blood_Group == 'A-' ? 'selected' : '' }}>A-
                                        </option>
                                        <option value="B+" {{ $Patient->Blood_Group == 'B+' ? 'selected' : '' }}>B+
                                        </option>
                                        <option value="B-" {{ $Patient->Blood_Group == 'B-' ? 'selected' : '' }}>B-
                                        </option>
                                        <option value="AB+" {{ $Patient->Blood_Group == 'AB+' ? 'selected' : '' }}>AB+
                                        </option>
                                        <option value="AB-" {{ $Patient->Blood_Group == 'AB-' ? 'selected' : '' }}>AB-
                                        </option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">الجنس</label>
                                    <select class="form-control select2" name="Gender" required>
                                        <option value="1" {{ $Patient->Gender == 1 ? 'selected' : '' }}>ذكر</option>
                                        <option value="2" {{ $Patient->Gender == 2 ? 'selected' : '' }}>أنثى</option>
                                    </select>
                                </div>


                                <div class="form-group">
                                    <label class="form-label">العنوان</label>
                                    <textarea rows="3" class="form-control" name="Address" placeholder="الحي، الشارع، المدينة، الرمز البريدي">{{ $Patient->Address }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- <div class="row">
                            <div class="col-12">

                            </div>
                        </div> --}}

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-submit btn-lg">
                                <i class="fas fa-save"></i> حفظ التعديلات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ar.js"></script>
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Notify JS -->
    <script src="{{ URL::asset('dashboard/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('/plugins/notify/js/notifit-custom.js') }}"></script>

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
                dir: "rtl"
            });

            // إضافة زر التقويم بشكل برمجي
            $('.datepicker-input').click(function() {
                $('#Date_Birth').focus();
            });

            // التحقق من النموذج قبل الإرسال
            $('#patientForm').on('submit', function(e) {
                if (!this.checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                }
                this.classList.add('was-validated');
            });
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
        //     const confirm = $('#password_confirmation').val();

        //     if (confirm !== password) {
        //         $('#password_confirmation')[0].setCustomValidity('كلمة المرور غير متطابقة');
        //     } else {
        //         $('#password_confirmation')[0].setCustomValidity('');
        //     }
        // }

        // // عند الكتابة في حقل التأكيد أو حقل كلمة المرور الأصلية
        // $('#password, #password_confirmation').on('input', validatePasswordMatch);

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
    </script>
@endsection
