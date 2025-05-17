@extends('Dashboard.layouts.master')

@section('title', 'حجز موعد جديد')

@section('css')
    @parent
    <link href="{{ URL::asset('Dashboard/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/l10n/ar.css">
    <link href="{{ URL::asset('dashboard/plugins/notify/css/notifIt.css')}}" rel="stylesheet"/>

    <style>
        /* متغيرات أساسية مستوحاة من الكود الأول ومعدلة لتناسب */
        :root {
            --form-primary-color: #3498db; /* أزرق الكود الأول */
            --form-secondary-color: #2ecc71; /* أخضر الكود الأول */
            --form-text-color: #2c3e50;
            --form-border-color: #ddd;
            --form-focus-border-color: #3498db;
            --form-focus-shadow-color: rgba(52, 152, 219, 0.2);
            --form-background-light: #f9f9f9;
            --form-radius: 8px;
            /* Padding for LTR, will be overridden by RTL specific */
            --form-input-padding-left: 40px;
            --form-input-padding-right: 15px;
            --form-danger-color: #e74c3c;
        }
        [dir="rtl"] {
            --form-input-padding-left: 15px;
            --form-input-padding-right: 40px;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fc;
        }

        .appointment-form-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-top: 30px;
            margin-bottom: 30px;
        }

        .appointment-form-header {
            background: linear-gradient(135deg, var(--form-primary-color), var(--form-secondary-color));
            color: white;
            padding: 25px;
            text-align: center;
        }
        .appointment-form-header h5 {
            margin: 0;
            font-size: 1.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .form-section-fieldset {
            border: none;
            padding: 25px;
            margin-bottom: 0;
        }
        .form-section-fieldset.patient-info-section {
             background: var(--form-background-light);
        }

        .form-section-legend {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 25px;
            color: var(--form-text-color);
            font-size: 1.3rem;
            font-weight: 600;
            padding: 0;
            width: auto;
            border-bottom: none;
        }
        .form-section-legend i {
            font-size: 1.2rem;
            color: var(--form-primary-color);
        }

        .form-group {
            margin-bottom: 20px;
        }
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--form-text-color);
            font-size: 0.95rem;
        }

        .input-with-icon {
            position: relative;
        }
        .input-with-icon .form-control,
        .input-with-icon .form-select,
        .input-with-icon .select2-container--bootstrap-5 .select2-selection {
            padding-left: var(--form-input-padding-left) !important;
            padding-right: var(--form-input-padding-right) !important;
            border: 1px solid var(--form-border-color) !important;
            border-radius: var(--form-radius) !important;
            font-size: 14px !important;
            transition: all 0.3s;
            width: 100% !important;
            min-height: auto !important;
            height: calc(1.5em + 0.75rem + 2px + 10px); /* Approximate height */
        }

        .input-with-icon .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
            line-height: calc(1.5em + 0.75rem - 2px);
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        .input-with-icon .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
            height: calc(1.5em + 0.75rem + 2px + 10px - 2px) !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
        }
        [dir="rtl"] .input-with-icon .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
            left: 15px !important; right: auto !important;
        }
        [dir="ltr"] .input-with-icon .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
            right: 15px !important; left: auto !important;
        }

        .input-with-icon .form-control:focus,
        .input-with-icon .form-select:focus,
        .input-with-icon .select2-container--bootstrap-5.select2-container--open .select2-selection,
        .input-with-icon .select2-container--bootstrap-5.select2-container--focus .select2-selection {
            border-color: var(--form-focus-border-color) !important;
            box-shadow: 0 0 0 3px var(--form-focus-shadow-color) !important;
            outline: none !important;
        }
        .input-with-icon > i.fas, .input-with-icon > i.fa {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            color: #7f8c8d;
            z-index: 3;
        }
        [dir="rtl"] .input-with-icon > i.fas, [dir="rtl"] .input-with-icon > i.fa {
            right: 15px; left: auto;
        }
        [dir="ltr"] .input-with-icon > i.fas, [dir="ltr"] .input-with-icon > i.fa {
            left: 15px; right: auto;
        }

        textarea.form-control {
            padding: 12px 15px;
            border: 1px solid var(--form-border-color);
            border-radius: var(--form-radius);
            font-size: 14px;
            min-height: 100px;
            resize: vertical;
            transition: all 0.3s;
        }
        textarea.form-control:focus {
            border-color: var(--form-focus-border-color);
            box-shadow: 0 0 0 3px var(--form-focus-shadow-color);
            outline: none;
        }

        .flatpickr-input { background-color: #fff !important; }
        .flatpickr-input[disabled], .form-control.flatpickr-date[disabled] {
            background-color: #e9ecef !important;
            cursor: not-allowed !important;
            opacity: 0.6 !important;
        }

        .time-slots-container { margin-top: 10px; }
        #time_slots_wrapper { display: flex; flex-wrap: wrap; gap: 10px; }
        .time-slot-btn {
            background-color: #ecf0f1; border: 1px solid #bdc3c7; border-radius: 6px;
            padding: 8px 15px; cursor: pointer; transition: all 0.3s ease;
            font-size: 0.9rem; color: var(--form-text-color);
            line-height: normal; text-align: center; vertical-align: middle; user-select: none;
        }
        .time-slot-btn:hover { background-color: #d5dbdb; border-color: #aab1b5; }
        .time-slot-btn.selected, .time-slot-btn.active {
            background-color: var(--form-primary-color); color: white; border-color: #2980b9;
            font-weight: bold; box-shadow: 0 2px 5px rgba(52, 152, 219, 0.3);
        }
        .time-slot-btn:disabled {
            cursor: not-allowed !important; opacity: 0.6 !important;
            background-color: #ecf0f1 !important; border-color: #bdc3c7 !important; color: #7f8c8d !important;
        }

        #no_times_message {
            font-style: italic; margin-top: 10px; width: 100%;
            background-color: #fcf8e3; border: 1px solid #faebcc; color: #8a6d3b;
            padding: 10px 15px; border-radius: var(--form-radius);
        }
        #no_times_message.text-danger {
            background-color: #f2dede; border-color: #ebccd1; color: #a94442; font-style: normal;
        }

        .form-actions-container {
            text-align: center; padding: 25px; background: #f5f7fa;
            border-top: 1px solid #e9ecef;
        }
        .submit-btn-appointment {
            background: linear-gradient(135deg, var(--form-primary-color), var(--form-secondary-color));
            color: white; border: none; padding: 12px 30px; border-radius: 30px;
            font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.3s;
            display: inline-flex; align-items: center; gap: 10px;
        }
        .submit-btn-appointment:hover:not(:disabled) {
            transform: translateY(-3px); box-shadow: 0 10px 20px rgba(46, 204, 113, 0.3);
        }
        .submit-btn-appointment:disabled {
            opacity: 0.65; transform: none; box-shadow: none; cursor: not-allowed;
            background: var(--form-primary-color); /* Fallback */
        }

        .invalid-feedback {
            color: var(--form-danger-color) !important; font-size: 0.85em !important;
            display: block !important; margin-top: 4px !important;
        }
        .input-with-icon .form-control.is-invalid,
        .input-with-icon .was-validated .form-control:invalid,
        .input-with-icon .form-select.is-invalid,
        .input-with-icon .was-validated .form-select:invalid,
        .input-with-icon .select2-container .select2-selection.is-invalid,
        .input-with-icon input[type="text"].flatpickr-input.is-invalid {
            border-color: var(--form-danger-color) !important;
            background-image: none !important;
        }
        [dir="rtl"] .input-with-icon .form-control.is-invalid,
        /* ... (Rest of RTL invalid padding adjustments if needed, often not if icon padding is handled by variables) ... */

        .select2-container--bootstrap-5 .select2-dropdown {
            border-radius: var(--form-radius); border-color: var(--form-border-color);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
         .select2-container--bootstrap-5 .select2-results__option--selected { background-color: #e9ecef; }
        .select2-container--bootstrap-5 .select2-results__option--highlighted[aria-selected] {
            background-color: var(--form-primary-color) !important; color: white !important;
        }

        #doctors_loading, #times_loading, #dates_loading_indicator {
            color: var(--form-text-color); font-size: 0.9em;
        }

        input:disabled, select:disabled, textarea:disabled,
        .form-control:disabled, .form-select:disabled,
        .select2-container--disabled .select2-selection {
            cursor: not-allowed !important; opacity: 0.65 !important;
            background-color: #e9ecef !important;
        }
        .select2-container--bootstrap-5.select2-container--disabled .select2-selection {
             border-color: var(--form-border-color) !important;
        }
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
    @include('Dashboard.messages_alert')

    <div class="row justify-content-center">
        <div class="col-lg-10 col-md-12">
            <div class="card appointment-form-card">
                <div class="appointment-form-header">
                    <h5 class="text-white"><i class="fas fa-calendar-plus"></i> املأ بيانات حجز الموعد</h5>
                </div>
                <div class="card-body p-0">
                    <form action="{{ route('patient.appointments.store') }}" method="POST" class="needs-validation" novalidate autocomplete="off">
                        @csrf
                        <fieldset class="form-section-fieldset patient-info-section">
                            <legend class="form-section-legend">
                                <i class="fas fa-user-injured"></i> معلومات المريض
                            </legend>
                            <div class="row g-3">
                                <div class="col-md-4 form-group">
                                    <label for="patient_name" class="form-label">الاسم الكامل <span class="text-danger">*</span></label>
                                    <div class="input-with-icon">
                                        <input type="text" name="patient_name" id="patient_name" class="form-control @error('patient_name') is-invalid @enderror" value="{{ old('patient_name', $patientName ?? '') }}" required placeholder="أدخل اسمك الثلاثي">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    @error('patient_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="patient_email" class="form-label">البريد الإلكتروني <span class="text-danger">*</span></label>
                                    <div class="input-with-icon">
                                        <input type="email" name="patient_email" id="patient_email" class="form-control @error('patient_email') is-invalid @enderror" value="{{ old('patient_email', $patientEmail ?? '') }}" required placeholder="example@domain.com">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    @error('patient_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="patient_phone" class="form-label">رقم الهاتف <span class="text-danger">*</span></label>
                                    <div class="input-with-icon">
                                        <input type="tel" name="patient_phone" id="patient_phone" class="form-control @error('patient_phone') is-invalid @enderror" value="{{ old('patient_phone', $patientPhone ?? '') }}" required placeholder="05XXXXXXXX">
                                        <i class="fas fa-phone-alt"></i>
                                    </div>
                                    @error('patient_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="form-section-fieldset">
                            <legend class="form-section-legend">
                                <i class="fas fa-calendar-alt"></i> تفاصيل الموعد المطلوب
                            </legend>
                            <div class="row g-3">
                                <div class="col-md-6 form-group">
                                    <label for="section_id" class="form-label">القسم الطبي <span class="text-danger">*</span></label>
                                    <div class="input-with-icon">
                                        <select name="section_id" id="section_id" class="form-select select2 @error('section_id') is-invalid @enderror" required data-placeholder="-- اختر القسم --">
                                            <option value=""></option>
                                            @foreach($sections as $section)
                                                <option value="{{ $section->id }}" {{ old('section_id', $selectedSectionId ?? '') == $section->id ? 'selected' : '' }}>
                                                    {{ $section->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <i class="fas fa-stethoscope"></i>
                                    </div>
                                    @error('section_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="doctor_id" class="form-label">الطبيب المعالج <span class="text-danger">*</span></label>
                                    <div class="input-with-icon">
                                        <select name="doctor_id" id="doctor_id" class="form-select select2 @error('doctor_id') is-invalid @enderror" required data-placeholder="-- اختر الطبيب --" disabled>
                                            <option value=""></option>
                                            @if(old('doctor_id') && isset($doctors) && $doctors->isNotEmpty() && $doctors->contains('id', old('doctor_id')))
                                                <option value="{{ old('doctor_id') }}" selected>{{ $doctors->firstWhere('id', old('doctor_id'))->name }}</option>
                                            @elseif (isset($selectedDoctorId) && isset($doctors) && $doctors->isNotEmpty() && $doctors->contains('id', $selectedDoctorId))
                                                 <option value="{{ $selectedDoctorId }}" selected>{{ $doctors->firstWhere('id', $selectedDoctorId)->name }}</option>
                                            @endif
                                        </select>
                                        <i class="fas fa-user-md"></i>
                                    </div>
                                    @error('doctor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    <div id="doctors_loading" style="display: none;" class="mt-1"><small class="text-muted"><i class="fas fa-spinner fa-spin me-1"></i> جاري تحميل الأطباء...</small></div>
                                </div>

                                <div class="col-md-6 form-group">
                                    <label for="selected_date_display" class="form-label">تاريخ الموعد <span class="text-danger">*</span></label>
                                    <div class="input-with-icon">
                                        <input type="text" id="selected_date_display" class="form-control flatpickr-date @error('selected_date') is-invalid @enderror"
                                               value="{{ old('selected_date') }}" required placeholder="اختر طبيباً أولاً" disabled readonly="readonly">
                                        <i class="fas fa-calendar-day"></i>
                                    </div>
                                    <input type="hidden" name="selected_date" id="selected_date" value="{{ old('selected_date') }}">
                                    <div id="dates_loading_indicator" style="display: none;" class="mt-1"><small class="text-muted"><i class="fas fa-spinner fa-spin me-1"></i> جاري تحميل أيام عمل الطبيب...</small></div>
                                    @error('selected_date') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror {{-- d-block for error on hidden input --}}
                                </div>

                                <div class="col-md-12 form-group">
                                    <label class="form-label mt-2">وقت الموعد <span class="text-danger">*</span></label>
                                    <div id="times_loading" style="display: none;" class="mb-2"><small class="text-muted"><i class="fas fa-spinner fa-spin me-1"></i> جاري تحميل الأوقات...</small></div>
                                    <div id="no_times_message" style="display: none;"></div>

                                    <div id="available_time_slots_container" class="mt-1 time-slots-container" style="display: none;">
                                        <div class="d-flex flex-wrap" id="time_slots_wrapper">
                                            {{-- Time slot buttons will be added here by JavaScript --}}
                                        </div>
                                    </div>
                                    <input type="hidden" name="selected_time" id="selected_time" value="{{ old('selected_time') }}">
                                    @error('selected_time') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </fieldset>

                        <div class="p-4">
                            <div class="form-group">
                                <label for="notes" class="form-label">ملاحظات إضافية (اختياري)</label>
                                <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" placeholder="اكتب أي ملاحظات إضافية هنا...">{{ old('notes') }}</textarea>
                                @error('notes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="form-actions-container">
                            <button type="submit" class="submit-btn-appointment" id="submitAppointmentBtn">
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

    <script>
        const initialData = {
            selectedSectionId: @json(old('section_id', $selectedSectionId ?? null)),
            selectedDoctorId: @json(old('doctor_id', $selectedDoctorId ?? null)),
            oldSelectedDate: @json(old('selected_date', null)),
            oldSelectedTime: @json(old('selected_time', null)),
            doctorsForOldSection: @json(old('section_id') && isset($doctors) && $doctors->isNotEmpty() ? $doctors : collect())
        };

        $(document).ready(function() {
            $('#section_id, #doctor_id').select2({
                placeholder: $(this).data('placeholder') || "اختر...",
                width: '100%', dir: "rtl", theme: "bootstrap-5",
                allowClear: true, language: "ar"
            }).on('change', function() {
                const $select2Container = $(this).next('.select2-container').find('.select2-selection');
                const $errorFeedback = $(this).closest('.form-group').find('.invalid-feedback');
                if ($(this).val()) {
                    $select2Container.removeClass('is-invalid').addClass('is-valid');
                    if ($errorFeedback.length) $errorFeedback.hide();
                } else if ($(this).prop('required')) {
                    $select2Container.removeClass('is-valid').addClass('is-invalid');
                }
            });

            let flatpickrInstance = null;
            const dateDisplayInput = $('#selected_date_display');
            const dateHiddenInput = $('#selected_date');
            const datesLoadingIndicator = $('#dates_loading_indicator');
            const submitButton = $('#submitAppointmentBtn');

            function initializeFlatpickr() {
                if (dateDisplayInput.length === 0) return;
                flatpickrInstance = dateDisplayInput.flatpickr({
                    dateFormat: "Y-m-d", locale: "ar", minDate: "today",
                    disableMobile: true, enable: [], // Initially empty
                    onChange: function(selectedDates, dateStr, instance) {
                        dateHiddenInput.val(dateStr);
                        const $dateErrorFeedback = dateDisplayInput.closest('.form-group').find('.invalid-feedback');
                        if (dateStr) {
                            fetchAvailableTimes($('#doctor_id').val(), dateStr);
                            dateDisplayInput.removeClass('is-invalid').addClass('is-valid');
                            if($dateErrorFeedback.length) $dateErrorFeedback.hide();
                        } else {
                            resetTimeSelection();
                            if (dateDisplayInput.prop('required')) {
                               dateDisplayInput.removeClass('is-valid').addClass('is-invalid');
                            }
                        }
                        updateSubmitButtonState();
                    },
                });
                disableDateField();
            }
            initializeFlatpickr();

            function disableDateField() {
                if (flatpickrInstance) {
                    flatpickrInstance.clear();
                    flatpickrInstance.set('enable', []); // Clear enabled dates
                }
                dateDisplayInput.prop('disabled', true).attr('placeholder', 'اختر طبيباً أولاً').removeClass('is-valid is-invalid');
                dateHiddenInput.val('');
                resetTimeSelection();
            }

            function enableDateField() {
                 dateDisplayInput.prop('disabled', false); // Placeholder will be set after fetching dates
                 updateSubmitButtonState();
            }

            function resetTimeSelection() {
                $('#time_slots_wrapper').empty();
                $('#available_time_slots_container').hide();
                $('#no_times_message').hide().text('').removeClass('text-danger');
                $('#selected_time').val('');
                $('#selected_time').next('.invalid-feedback').hide();
                updateSubmitButtonState();
            }

            $('#section_id').on('change', function() {
                const sectionId = $(this).val();
                const doctorSelect = $('#doctor_id');
                const doctorsLoading = $('#doctors_loading');

                doctorSelect.html('<option value=""></option>').val(null).trigger('change.select2').prop('disabled', true);
                if (doctorSelect.prop('required')) {
                    doctorSelect.next('.select2-container').find('.select2-selection').removeClass('is-valid').addClass('is-invalid');
                }
                disableDateField(); // Reset date field

                if (sectionId) {
                    doctorsLoading.show();
                    $.ajax({
                        url: "{{ route('ajax.get_doctors_by_section') }}",
                        type: "GET", data: { section_id: sectionId }, dataType: 'json',
                        success: function(response) {
                            doctorsLoading.hide();
                            doctorSelect.html('<option value="">-- اختر الطبيب --</option>');
                            if (response && response.doctors && response.doctors.length > 0) {
                                response.doctors.forEach(doctor => doctorSelect.append(new Option(doctor.name, doctor.id)));
                                doctorSelect.prop('disabled', false);

                                // Restore old doctor selection if present for this section
                                if (initialData.selectedDoctorId && doctorSelect.find('option[value="' + initialData.selectedDoctorId + '"]').length > 0) {
                                     doctorSelect.val(initialData.selectedDoctorId).trigger('change'); // This will trigger date loading
                                } else if (doctorSelect.prop('required')) {
                                     doctorSelect.next('.select2-container').find('.select2-selection').addClass('is-invalid');
                                }
                            } else {
                                doctorSelect.append(new Option(response.error || 'لا يوجد أطباء في هذا القسم', '', true, true));
                            }
                            doctorSelect.trigger('change.select2'); // Update Select2 display
                        },
                        error: function(jqXHR) {
                            doctorsLoading.hide();
                            console.error("AJAX Error fetching doctors:", jqXHR.responseText);
                            notif({ msg: (jqXHR.responseJSON?.error || "خطأ في تحميل الأطباء."), type: "error", position: "bottom" });
                            doctorSelect.html('<option value="">-- خطأ --</option>').trigger('change.select2');
                        }
                    });
                }
            });

            $('#doctor_id').on('change', function() {
                const doctorId = $(this).val();
                disableDateField(); // Always reset date field on doctor change

                if (doctorId) {
                    datesLoadingIndicator.show();
                    $.ajax({
                        url: "{{ route('ajax.get_doctor_available_dates') }}",
                        type: "GET", data: { doctor_id: doctorId }, dataType: 'json',
                        success: function(response) {
                            datesLoadingIndicator.hide();
                            if (flatpickrInstance && response.enabledDates) {
                                flatpickrInstance.set('enable', response.enabledDates);
                                if (response.enabledDates.length > 0) {
                                    enableDateField();
                                    dateDisplayInput.attr('placeholder', 'اختر التاريخ من الأيام المتاحة');
                                    if (initialData.oldSelectedDate && response.enabledDates.includes(initialData.oldSelectedDate)) {
                                        flatpickrInstance.setDate(initialData.oldSelectedDate, true);
                                    }
                                } else {
                                    disableDateField(); // Keep disabled
                                    dateDisplayInput.attr('placeholder', 'لا توجد أيام متاحة لهذا الطبيب حالياً');
                                    $('#no_times_message').text(response.message || 'لا توجد أيام متاحة لهذا الطبيب حالياً.').show();
                                }
                            } else {
                                disableDateField();
                                dateDisplayInput.attr('placeholder', response.message || 'خطأ في تحميل أيام الطبيب');
                            }
                        },
                        error: function(jqXHR) {
                            datesLoadingIndicator.hide();
                            console.error("AJAX Error fetching doctor available dates:", jqXHR.responseText);
                            disableDateField();
                            dateDisplayInput.attr('placeholder', 'خطأ في تحميل أيام الطبيب');
                            notif({ msg: "خطأ في تحميل أيام عمل الطبيب.", type: "error", position: "bottom" });
                        }
                    });
                }
                // Update Select2 validation state
                const $select2Container = $(this).next('.select2-container').find('.select2-selection');
                 if ($(this).val()) {
                    $select2Container.removeClass('is-invalid').addClass('is-valid');
                } else if ($(this).prop('required')) {
                    $select2Container.removeClass('is-valid').addClass('is-invalid');
                }
            });

            function fetchAvailableTimes(doctorId, selectedDate) {
                const timeSlotsWrapper = $('#time_slots_wrapper');
                const availableTimeSlotsContainer = $('#available_time_slots_container');
                const timesLoading = $('#times_loading');
                const noTimesMessage = $('#no_times_message');
                const hiddenTimeInput = $('#selected_time');

                resetTimeSelection(); // This resets times and submit button

                if (doctorId && selectedDate) {
                    timesLoading.show();
                    $.ajax({
                        url: "{{ route('ajax.get_available_times') }}",
                        type: "GET", data: { doctor_id: doctorId, selected_date: selectedDate }, dataType: 'json',
                        success: function(response) {
                            timesLoading.hide();
                            if (response && response.times && response.times.length > 0) {
                                response.times.forEach(function(timeObj) {
                                    $('<button type="button" class="time-slot-btn"></button>')
                                        .text(timeObj.display).val(timeObj.value)
                                        .on('click', function() {
                                            timeSlotsWrapper.find('.time-slot-btn').removeClass('selected active');
                                            $(this).addClass('selected active');
                                            hiddenTimeInput.val(timeObj.value).trigger('change'); // Trigger change for any listeners
                                            $('#selected_time').next('.invalid-feedback').hide();
                                            noTimesMessage.hide().removeClass('text-danger');
                                            updateSubmitButtonState();
                                        }).appendTo(timeSlotsWrapper);
                                });
                                availableTimeSlotsContainer.show();
                                noTimesMessage.hide();
                                if (initialData.oldSelectedTime) {
                                    const matchingButton = timeSlotsWrapper.find('button[value="' + initialData.oldSelectedTime + '"]');
                                    if (matchingButton.length > 0) matchingButton.click();
                                    initialData.oldSelectedTime = null; // Consume it
                                }
                            } else {
                                noTimesMessage.text(response.message || 'لا توجد أوقات متاحة في هذا اليوم.').show();
                            }
                        },
                        error: function(jqXHR) {
                            timesLoading.hide();
                            console.error("AJAX Error fetching times:", jqXHR.responseText);
                            noTimesMessage.text(jqXHR.responseJSON?.message || jqXHR.responseJSON?.error || 'خطأ في تحميل الأوقات.').show();
                        }
                    });
                }
            }

            function updateSubmitButtonState() {
                const doctorSelected = $('#doctor_id').val();
                const dateSelected = $('#selected_date').val();
                const timeSelected = $('#selected_time').val();
                submitButton.prop('disabled', !(doctorSelected && dateSelected && timeSelected));
            }
            updateSubmitButtonState(); // Initial call

            // --- Handle initial data (old values) ---
            if (initialData.selectedSectionId) {
                $('#section_id').val(initialData.selectedSectionId).trigger('change');
                // The 'change' on section_id will handle loading doctors.
                // If `initialData.selectedDoctorId` is also set, the success callback of
                // `getDoctorsBySection` will attempt to select it and trigger its 'change' event,
                // which in turn will load the available dates.
            } else if (initialData.doctorsForOldSection && initialData.doctorsForOldSection.length > 0 && initialData.selectedDoctorId) {
                // This case handles if section_id was not set by query param but doctor was via old data
                const doctorSelect = $('#doctor_id');
                doctorSelect.html('<option value="">-- اختر الطبيب --</option>');
                initialData.doctorsForOldSection.forEach(doc => doctorSelect.append(new Option(doc.name, doc.id, false, doc.id == initialData.selectedDoctorId)));
                if (initialData.selectedDoctorId) doctorSelect.val(initialData.selectedDoctorId);
                doctorSelect.prop('disabled', false).trigger('change.select2');
                if (doctorSelect.val()) doctorSelect.trigger('change'); // Load dates for this pre-selected doctor
            }


            (function () {
                'use strict';
                var forms = document.querySelectorAll('.needs-validation');
                Array.prototype.slice.call(forms).forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        const hiddenTimeInput = form.querySelector('#selected_time');
                        const timeErrorFeedback = $(hiddenTimeInput).next('.invalid-feedback');

                        // Validate Select2 fields
                        $(form).find('select.select2[required]').each(function() {
                            if (!$(this).val()) {
                                $(this).next('.select2-container').find('.select2-selection').addClass('is-invalid');
                            }
                        });
                        // Validate Flatpickr display field
                        if ($(dateDisplayInput).prop('required') && !$('#selected_date').val()) {
                             $(dateDisplayInput).addClass('is-invalid');
                             $(dateDisplayInput).closest('.form-group').find('.invalid-feedback').show();
                        }

                        // Validate time selection if date is selected
                        if ($('#doctor_id').val() && $('#selected_date').val() && !hiddenTimeInput.value) {
                             $('#no_times_message').text('الرجاء اختيار وقت الموعد.').addClass('text-danger').show();
                             if(timeErrorFeedback.length) timeErrorFeedback.text('الرجاء اختيار وقت للموعد.').show();
                             event.preventDefault();
                             event.stopPropagation();
                        } else if (hiddenTimeInput.value) {
                             $('#no_times_message').hide().removeClass('text-danger');
                             if(timeErrorFeedback.length) timeErrorFeedback.hide();
                        }

                        if (!form.checkValidity()) {
                            event.preventDefault();
                            event.stopPropagation();
                        } else {
                            // If form is valid, disable submit button and show loading
                            submitButton.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> جاري الحجز...');
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            })();

            // Display session messages with NotifIt
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
