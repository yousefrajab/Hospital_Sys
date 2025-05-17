@extends('Dashboard.layouts.master')

@section('title', 'حجز موعد جديد')

@section('css')
    @parent
    <link href="{{ URL::asset('Dashboard/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="{{ URL::asset('dashboard/plugins/notify/css/notifIt.css')}}" rel="stylesheet"/>
    <style>
        :root {
            --bs-primary-rgb: 79, 70, 229; /* لـ Bootstrap 5 shadow/focus colors */
            --admin-primary: #4f46e5;
            --admin-primary-dark: #4338ca;
            --admin-border-color: #dee2e6; /* Bootstrap default */
            --admin-radius-md: 0.375rem; /* Bootstrap default .form-control */
            --admin-danger: #dc3545; /* Bootstrap default */
            --admin-success: #198754; /* Bootstrap default */
        }
        body { font-family: 'Tajawal', sans-serif; background-color: #f8f9fc; }
        .card { border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.07); }

        .form-control, .form-select {
            border-radius: var(--admin-radius-md);
            border: 1px solid var(--admin-border-color);
            padding: 0.65rem 1rem;
            font-size: 0.9rem;
            transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--admin-primary);
            box-shadow: 0 0 0 0.25rem rgba(var(--bs-primary-rgb), 0.25);
            outline: 0;
        }
        .select2-container--bootstrap-5 .select2-selection {
            border-radius: var(--admin-radius-md) !important;
            border: 1px solid var(--admin-border-color) !important;
            padding: 0.47rem 0.75rem !important; /* يجب أن يكون أقل من form-control بسبب padding داخلي لـ select2 */
            min-height: calc(1.5em + 1.3rem + 2px) !important; /* ليتماشى مع form-control */
            font-size: 0.9rem !important; /* نفس حجم خط form-control */
            display: flex; /* لتحسين محاذاة النص داخل select2 */
            align-items: center; /* لتحسين محاذاة النص داخل select2 */
        }
         .select2-container--bootstrap-5.select2-container--focus .select2-selection,
        .select2-container--bootstrap-5.select2-container--open .select2-selection {
            border-color: var(--admin-primary) !important;
            box-shadow: 0 0 0 0.25rem rgba(var(--bs-primary-rgb), 0.25) !important;
        }
        .select2-container--bootstrap-5 .select2-dropdown {
            border-radius: var(--admin-radius-md);
            border-color: var(--admin-border-color);
        }

        .time-slot-btn {
            padding: 0.5rem 1rem; border: 1px solid var(--admin-border-color); border-radius: 0.25rem;
            cursor: pointer; transition: all 0.2s; margin: 0.25rem;
            background-color: white; color: #495057;
            font-weight: 500;
        }
        .time-slot-btn:hover { background-color: #e9ecef; border-color: #ced4da;}
        .time-slot-btn.selected, .time-slot-btn.active { /* .active لـ bootstrap */
            background-color: var(--admin-primary); color: white; border-color: var(--admin-primary-dark);
            box-shadow: 0 0 0 0.2rem rgba(var(--bs-primary-rgb), 0.2);
        }
        .time-slot-btn:disabled { background-color: #e9ecef; cursor: not-allowed; opacity: 0.65; }

        .flatpickr-input { background-color: #fff !important; } /* لجعل الحقل يبدو كحقل عادي */
        .flatpickr-input[disabled] { background-color: #e9ecef !important; }

        /* Bootstrap validation styles */
        .form-control.is-invalid, .was-validated .form-control:invalid,
        .form-select.is-invalid, .was-validated .form-select:invalid,
        input[type="text"].flatpickr-input.is-invalid { /* استهداف flatpickr مع is-invalid */
            border-color: var(--admin-danger) !important;
            padding-right: calc(1.5em + 0.75rem); /* مساحة لأيقونة الخطأ الافتراضية */
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }
        .form-control.is-valid, .was-validated .form-control:valid,
        .form-select.is-valid, .was-validated .form-select:valid,
        input[type="text"].flatpickr-input.is-valid { /* استهداف flatpickr مع is-valid */
            border-color: var(--admin-success) !important;
            /* ... يمكنك إضافة أيقونة صح إذا أردت ... */
        }
        .invalid-feedback { color: var(--admin-danger); font-size: 0.875em; display: block; }
        .valid-feedback { color: var(--admin-success); font-size: 0.875em; display: block;}

        fieldset { border: 1px solid var(--admin-border-color); border-radius: var(--admin-radius-md); }
        fieldset legend {
            font-size: 1rem; font-weight: 600; color: var(--admin-primary);
            padding: 0 0.5rem; margin-bottom: 0.75rem; /* تعديل المسافة */
        }
        .form-label { font-weight: 500; margin-bottom: 0.4rem; }
    </style>
@endsection

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex align-items-center">
                <h4 class="content-title mb-0 my-auto"><i class="fas fa-calendar-plus text-primary me-2"></i> المواعيد</h4>
                <span class="text-muted mt-1 tx-13 ms-2 mb-0">/ حجز موعد جديد</span>
            </div>
        </div>
         <div class="d-flex my-xl-auto right-content">
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> رجوع
            </a>
        </div>
    </div>
@endsection

@section('content')
    @include('Dashboard.messages_alert') {{-- تأكد أن هذا الملف يعرض session('success_message') و session('error') --}}

    <div class="row justify-content-center">
        <div class="col-lg-10 col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="card-title mb-0 text-white"><i class="fas fa-notes-medical me-2"></i> املأ بيانات حجز الموعد</h5>
                </div>
                <div class="card-body p-lg-4 p-3">
                    <form action="{{ route('patient.appointments.store') }}" method="POST" class="needs-validation" novalidate autocomplete="off">
                        @csrf
                        <fieldset class="mb-4 p-3 pt-2 border rounded bg-light">
                            <legend class="w-auto px-2 h6">معلومات المريض</legend>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="patient_name" class="form-label">الاسم الكامل <span class="text-danger">*</span></label>
                                    <input type="text" name="patient_name" id="patient_name" class="form-control @error('patient_name') is-invalid @enderror" value="{{ old('patient_name', $patientName) }}" required>
                                    @error('patient_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    <div class="valid-feedback">جيد!</div>
                                </div>
                                <div class="col-md-4">
                                    <label for="patient_email" class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                                    <input type="email" name="patient_email" id="patient_email" class="form-control @error('patient_email') is-invalid @enderror" value="{{ old('patient_email', $patientEmail) }}" required>
                                    @error('patient_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    <div class="valid-feedback">جيد!</div>
                                </div>
                                <div class="col-md-4">
                                    <label for="patient_phone" class="form-label">رقم الهاتف <span class="text-danger">*</span></label>
                                    <input type="tel" name="patient_phone" id="patient_phone" class="form-control @error('patient_phone') is-invalid @enderror" value="{{ old('patient_phone', $patientPhone) }}" required placeholder="مثال: 05xxxxxxxx">
                                    @error('patient_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    <div class="valid-feedback">جيد!</div>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="mb-4 p-3 pt-2 border rounded">
                            <legend class="w-auto px-2 h6">تفاصيل الموعد المطلوب</legend>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="section_id" class="form-label">القسم الطبي <span class="text-danger">*</span></label>
                                    <select name="section_id" id="section_id" class="form-select select2 @error('section_id') is-invalid @enderror" required data-placeholder="-- اختر القسم --">
                                        <option value=""></option>
                                        @foreach($sections as $section)
                                            <option value="{{ $section->id }}" {{ old('section_id', $selectedSectionId ?? '') == $section->id ? 'selected' : '' }}>
                                                {{ $section->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('section_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    <div class="valid-feedback">جيد!</div>
                                </div>

                                <div class="col-md-6">
                                    <label for="doctor_id" class="form-label">الطبيب المعالج <span class="text-danger">*</span></label>
                                    <select name="doctor_id" id="doctor_id" class="form-select select2 @error('doctor_id') is-invalid @enderror" required data-placeholder="-- اختر الطبيب --" disabled>
                                        <option value=""></option>
                                        {{-- إذا كان هناك old('doctor_id') بسبب فشل التحقق، وكان $doctors محملة للقسم القديم --}}
                                        @if(old('doctor_id') && $doctors->isNotEmpty() && $doctors->contains('id', old('doctor_id')))
                                            <option value="{{ old('doctor_id') }}" selected>{{ $doctors->firstWhere('id', old('doctor_id'))->name }}</option>
                                        @elseif ($selectedDoctorId && $doctors->isNotEmpty() && $doctors->contains('id', $selectedDoctorId))
                                             <option value="{{ $selectedDoctorId }}" selected>{{ $doctors->firstWhere('id', $selectedDoctorId)->name }}</option>
                                        @endif
                                    </select>
                                    @error('doctor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    <div id="doctors_loading" style="display: none;" class="mt-1"><small class="text-muted"><i class="fas fa-spinner fa-spin me-1"></i> جاري تحميل الأطباء...</small></div>
                                    <div class="valid-feedback">جيد!</div>
                                </div>

                                <div class="col-md-6">
                                    <label for="selected_date_display" class="form-label">تاريخ الموعد <span class="text-danger">*</span></label>
                                    {{-- حقل العرض لـ Flatpickr --}}
                                    <input type="text" id="selected_date_display" class="form-control flatpickr-date @error('selected_date') is-invalid @enderror"
                                           value="{{ old('selected_date') }}" required placeholder="اختر التاريخ بعد تحديد الطبيب" disabled>
                                    {{-- حقل الإدخال المخفي للقيمة الفعلية للتاريخ --}}
                                    <input type="hidden" name="selected_date" id="selected_date" value="{{ old('selected_date') }}">
                                    @error('selected_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    <div class="valid-feedback">جيد!</div>
                                </div>

                                <div class="col-md-12"> {{-- لعرض الأوقات أسفل التاريخ بشكل أوضح --}}
                                    <label class="form-label mt-2">وقت الموعد <span class="text-danger">*</span></label>
                                    <div id="times_loading" style="display: none;" class="mb-2"><small class="text-muted"><i class="fas fa-spinner fa-spin me-1"></i> جاري تحميل الأوقات...</small></div>
                                    <div id="no_times_message" class="alert alert-sm alert-warning py-2 px-3" style="display: none; font-size: 0.9rem;"></div>

                                    <div id="available_time_slots_container" class="mt-1" style="display: none;">
                                        <div class="d-flex flex-wrap" id="time_slots_wrapper">
                                            {{-- أزرار الوقت ستضاف هنا بواسطة JavaScript --}}
                                        </div>
                                    </div>
                                    {{-- حقل الإدخال المخفي لقيمة الوقت المختارة --}}
                                    <input type="hidden" name="selected_time" id="selected_time" value="{{ old('selected_time') }}">
                                    @error('selected_time') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                    {{-- لا حاجة لـ valid-feedback هنا لأنه مخفي --}}
                                </div>
                            </div>
                        </fieldset>

                        <div class="mb-3">
                            <label for="notes" class="form-label">ملاحظات إضافية (اختياري)</label>
                            <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" placeholder="اكتب أي ملاحظات إضافية هنا...">{{ old('notes') }}</textarea>
                            @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="text-center mt-4 pt-2">
                            <button type="submit" class="btn btn-primary btn-lg ripple-effect px-5">
                                <i class="fas fa-calendar-check me-2"></i> تأكيد الحجز
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
    <script src="{{ URL::asset('Dashboard/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/select2/js/i18n/ar.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/ar.js"></script>
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifIt.js') }}"></script>
    {{-- <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifit-custom.js') }}"></script> --}}

    <script>
        // تمرير البيانات الأولية من PHP إلى JavaScript
        const initialData = {
            selectedSectionId: @json(old('section_id', $selectedSectionId ?? null)),
            selectedDoctorId: @json(old('doctor_id', $selectedDoctorId ?? null)),
            oldSelectedDate: @json(old('selected_date', null)),
            oldSelectedTime: @json(old('selected_time', null)),
            // إذا كان هناك قسم قديم، جلب الأطباء المرتبطين به (مفيد إذا فشل التحقق)
            doctorsForOldSection: @json(old('section_id') && isset($doctors) ? $doctors : collect())
        };

        $(document).ready(function() {
            // تهيئة Select2
            $('#section_id, #doctor_id').select2({
                placeholder: $(this).data('placeholder') || "اختر...",
                width: '100%',
                dir: "rtl",
                theme: "bootstrap-5",
                allowClear: true,
                language: "ar"
            });

            let flatpickrInstance = null;
            const dateDisplayInput = $('#selected_date_display');
            const dateHiddenInput = $('#selected_date');

            function initializeFlatpickr() {
                if (dateDisplayInput.length === 0) return;
                flatpickrInstance = dateDisplayInput.flatpickr({
                    dateFormat: "Y-m-d",
                    locale: "ar",
                    minDate: "today",
                    disableMobile: true,
                    onChange: function(selectedDates, dateStr, instance) {
                        dateHiddenInput.val(dateStr); // تحديث الحقل المخفي
                        if (dateStr) {
                            fetchAvailableTimes($('#doctor_id').val(), dateStr);
                            dateDisplayInput.removeClass('is-invalid').addClass('is-valid'); // تحديث حالة التحقق
                        } else {
                            resetTimeSelection();
                            if (dateDisplayInput.prop('required')) { // إذا كان الحقل مطلوباً
                               dateDisplayInput.removeClass('is-valid').addClass('is-invalid');
                            }
                        }
                    },
                });
                disableDateField(); // تعطيل حقل التاريخ مبدئياً
            }
            initializeFlatpickr();

            function disableDateField() {
                if (flatpickrInstance) flatpickrInstance.clear();
                dateDisplayInput.prop('disabled', true).attr('placeholder', 'اختر طبيباً أولاً').removeClass('is-valid is-invalid');
                dateHiddenInput.val('');
                resetTimeSelection();
            }

            function enableDateField() {
                 dateDisplayInput.prop('disabled', false).attr('placeholder', 'اختر التاريخ');
            }

            function resetTimeSelection() {
                $('#time_slots_wrapper').empty();
                $('#available_time_slots_container').hide();
                $('#no_times_message').hide().text('');
                $('#selected_time').val('');
                // لا حاجة لتحديث حالة التحقق لحقل الوقت المخفي هنا مباشرة
            }

            $('#section_id').on('change', function() {
                const sectionId = $(this).val();
                const doctorSelect = $('#doctor_id');
                const doctorsLoading = $('#doctors_loading');

                doctorSelect.html('<option value=""></option>').val(null).trigger('change.select2').prop('disabled', true);
                disableDateField(); // هذا يعيد تعيين التاريخ والوقت

                if (sectionId) {
                    doctorsLoading.show();
                    $.ajax({
                        url: "{{ route('ajax.get_doctors_by_section') }}",
                        type: "GET",
                        data: { section_id: sectionId },
                        dataType: 'json',
                        success: function(response) {
                            doctorsLoading.hide();
                            doctorSelect.html('<option value="">-- اختر الطبيب --</option>');

                            if (response && response.doctors && response.doctors.length > 0) {
                                response.doctors.forEach(function(doctor) {
                                    doctorSelect.append(new Option(doctor.name, doctor.id));
                                });
                                doctorSelect.prop('disabled', false);

                                // إذا كان هناك طبيب محدد مسبقًا (من old أو query) لهذا القسم
                                if (initialData.selectedDoctorId && doctorSelect.find('option[value="' + initialData.selectedDoctorId + '"]').length > 0) {
                                     doctorSelect.val(initialData.selectedDoctorId).trigger('change');
                                     // initialData.selectedDoctorId = null; // لا تمسحه هنا، قد يُستخدم إذا غير المستخدم القسم ثم عاد
                                }
                            } else {
                                doctorSelect.append(new Option(response.error || 'لا يوجد أطباء في هذا القسم', '', true, true));
                            }
                            doctorSelect.trigger('change.select2');
                        },
                        error: function(jqXHR) {
                            doctorsLoading.hide();
                            console.error("AJAX Error fetching doctors:", jqXHR.responseText);
                            let msg = "خطأ في تحميل الأطباء.";
                            if(jqXHR.responseJSON && jqXHR.responseJSON.error) msg = jqXHR.responseJSON.error;
                            notif({ msg: msg, type: "error", position: "bottom" });
                            doctorSelect.html('<option value="">-- خطأ --</option>').trigger('change.select2');
                        }
                    });
                }
            });

            $('#doctor_id').on('change', function() {
                const doctorId = $(this).val();
                disableDateField(); // دائماً أعد تعيين التاريخ والوقت عند تغيير الطبيب

                if (doctorId) {
                    enableDateField();
                    // إذا كان هناك تاريخ قديم محدد (بعد فشل التحقق)، حاول استعادته
                    if (initialData.oldSelectedDate && flatpickrInstance) {
                        flatpickrInstance.setDate(initialData.oldSelectedDate, true); // true لـ trigger onChange
                        // initialData.oldSelectedDate = null; // لا تمسحه، قد يحتاجه المستخدم إذا غير شيئاً آخر
                    }
                }
            });

            function fetchAvailableTimes(doctorId, selectedDate) {
                const timeSlotsWrapper = $('#time_slots_wrapper');
                const availableTimeSlotsContainer = $('#available_time_slots_container');
                const timesLoading = $('#times_loading');
                const noTimesMessage = $('#no_times_message');
                const hiddenTimeInput = $('#selected_time');

                resetTimeSelection();

                if (doctorId && selectedDate) {
                    timesLoading.show();
                    $.ajax({
                        url: "{{ route('ajax.get_available_times') }}",
                        type: "GET",
                        data: { doctor_id: doctorId, selected_date: selectedDate },
                        dataType: 'json',
                        success: function(response) {
                            timesLoading.hide();
                            if (response && response.times && response.times.length > 0) {
                                response.times.forEach(function(timeObj) {
                                    const timeButton = $('<button type="button" class="btn time-slot-btn btn-outline-secondary"></button>')
                                        .text(timeObj.display)
                                        .val(timeObj.value)
                                        .on('click', function() {
                                            timeSlotsWrapper.find('.time-slot-btn').removeClass('selected active btn-primary').addClass('btn-outline-secondary');
                                            $(this).addClass('selected active btn-primary').removeClass('btn-outline-secondary');
                                            hiddenTimeInput.val(timeObj.value).trigger('change'); // تحديث الحقل المخفي
                                        });
                                    timeSlotsWrapper.append(timeButton);
                                });
                                availableTimeSlotsContainer.show();
                                noTimesMessage.hide();

                                // إذا كان هناك وقت قديم محدد، حاول تحديده
                                if (initialData.oldSelectedTime) {
                                    const matchingButton = timeSlotsWrapper.find('button[value="' + initialData.oldSelectedTime + '"]');
                                    if (matchingButton.length > 0) {
                                        matchingButton.click(); // هذا سيقوم بتحديث hiddenTimeInput
                                    }
                                    // initialData.oldSelectedTime = null; // لا تمسحه
                                }
                            } else {
                                noTimesMessage.text(response.message || 'لا توجد أوقات متاحة.').show();
                            }
                        },
                        error: function(jqXHR) {
                            timesLoading.hide();
                            console.error("AJAX Error fetching times:", jqXHR.responseText);
                            let msg = 'خطأ في تحميل الأوقات.';
                            if(jqXHR.responseJSON && (jqXHR.responseJSON.message || jqXHR.responseJSON.error) ) {
                                msg = jqXHR.responseJSON.message || jqXHR.responseJSON.error;
                            }
                            noTimesMessage.text(msg).show();
                        }
                    });
                }
            }

            // --- التعامل مع القيم الأولية عند تحميل الصفحة ---
            if (initialData.selectedSectionId) {
                $('#section_id').val(initialData.selectedSectionId).trigger('change');
                // اختيار الطبيب والتاريخ والوقت سيتم بشكل متسلسل داخل الـ callbacks
                // إذا كان initialData.selectedDoctorId و initialData.oldSelectedDate موجودين.
            } else if (initialData.doctorsForOldSection && initialData.doctorsForOldSection.length > 0 && initialData.selectedDoctorId) {
                // حالة خاصة: إذا كان هناك old(section_id) ولكن لم يتم تمريره كـ $selectedSectionId
                const doctorSelect = $('#doctor_id');
                doctorSelect.html('<option value="">-- اختر الطبيب --</option>');
                initialData.doctorsForOldSection.forEach(function(doc){
                    doctorSelect.append(new Option(doc.name, doc.id, false, doc.id == initialData.selectedDoctorId));
                });
                if (initialData.selectedDoctorId) {
                     doctorSelect.val(initialData.selectedDoctorId);
                }
                doctorSelect.prop('disabled', false).trigger('change.select2');
                // إذا تم اختيار طبيب هنا، حفز change event له
                if (doctorSelect.val()) {
                    doctorSelect.trigger('change');
                }
            }

            // Bootstrap 5 validation
            (function () {
                'use strict';
                var forms = document.querySelectorAll('.needs-validation');
                Array.prototype.slice.call(forms).forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        const hiddenTimeInput = form.querySelector('#selected_time');
                        const selectedDateInput = form.querySelector('#selected_date_display'); // حقل التاريخ المعروض

                        // التحقق من حقل الوقت المخفي
                        if (hiddenTimeInput && selectedDateInput.value && !selectedDateInput.disabled && !hiddenTimeInput.value) {
                             $('#no_times_message').text('الرجاء اختيار وقت الموعد.').show();
                             notif({ msg: "الرجاء اختيار وقت الموعد.", type: "error", position: "bottom" });
                             event.preventDefault();
                             event.stopPropagation();
                        } else if (hiddenTimeInput.value) {
                             $('#no_times_message').hide(); // إخفاء الرسالة إذا تم اختيار وقت
                        }

                        if (!form.checkValidity()) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            })();

            @if (session('success_message'))
                notif({ msg: "<i class='fas fa-check-circle me-2'></i> {{ session('success_message') }}", type: "success", position: "bottom", autohide: true, timeout: 7000 });
            @endif
            @if (session('error'))
                notif({ msg: "<i class='fas fa-exclamation-triangle me-2'></i> {{ session('error') }}", type: "error", position: "bottom", autohide: true, timeout: 7000 });
            @endif
            @if ($errors->any())
                let errorMsg = "<strong class='d-block mb-1'><i class='fas fa-times-circle me-2'></i> يرجى تصحيح الأخطاء التالية:</strong><ul class='list-unstyled mb-0 ps-3' style='text-align: right;'>";
                @foreach ($errors->all() as $error)
                    errorMsg += "<li>- {{ $error }}</li>";
                @endforeach
                errorMsg += "</ul>";
                notif({ msg: errorMsg, type: "error", position: "bottom", multiline: true, autohide: false });
            @endif
        });
    </script>
@endsection
