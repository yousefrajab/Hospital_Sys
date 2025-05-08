{{-- resources/views/Dashboard/Doctors/edit_Days.blade.php --}}
@extends('Dashboard.layouts.master')

{{-- ========================== CSS Section ========================== --}}
@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- Flatpickr CSS (لا يزال مطلوباً لحقول الوقت) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/l10n/ar.css">
    {{-- Animate.css (لتأثيرات أجمل) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

    <style>
        /* --- المتغيرات وتحسينات التصميم --- */
        :root {
            --primary-color: #4A90E2;  /* أزرق أكثر إشراقاً */
            --secondary-color: #4A4A4A;/* رمادي داكن للنصوص */
            --accent-color: #50E3C2;  /* تركواز كلون تأكيد */
            --light-bg: #f9fbfd;      /* خلفية فاتحة جداً */
            --border-color: #e5e9f2;
            --danger-color: #e74c3c;
            --success-color: #2ecc71;
            --white-color: #ffffff;
            --card-shadow: 0 8px 25px rgba(140, 152, 164, 0.15); /* ظل أنعم */
            --input-bg: #ffffff;
            --input-border: #d1d9e6;
            --input-focus-border: var(--primary-color);
            --input-focus-shadow: rgba(74, 144, 226, 0.2);
            --primary-gradient: linear-gradient(135deg, #4A90E2, #7FDBFF); /* تدرج أزرق */
        }
        body { background: var(--light-bg); font-family: 'Cairo', sans-serif; /* استخدام خط Cairo كمثال */ }

        .schedule-edit-container { background: var(--white-color); border-radius: 12px; box-shadow: var(--card-shadow); padding: 2rem 2.5rem; }
        .schedule-header { /* ... كما هو تقريباً ... */ display: flex; justify-content: space-between; align-items: center; margin-bottom: 2.5rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-color); }
        .schedule-title { font-size: 1.4rem; font-weight: 700; color: var(--secondary-color); display: flex; align-items: center; gap: 0.75rem; }
        .schedule-title i { color: var(--primary-color); }
        .doctor-badge { background: #eef2ff; color: var(--primary-color); padding: 0.4rem 0.8rem; border-radius: 50px; font-weight: 600; font-size: 0.85rem; display: flex; align-items: center; gap: 0.5rem; }
        .doctor-badge i { opacity: 0.8; }

        /* تصميم صف اليوم */
        .day-schedule-container { display: grid; gap: 0.75rem; }
        .day-row {
            display: grid;
            grid-template-columns: 150px auto auto 1fr;
            align-items: center; gap: 1.25rem; padding: 0.75rem 1rem;
            border-radius: 10px; border: 1px solid transparent; /* حدود شفافة للتحويم */
            transition: all 0.3s ease-in-out;
        }
        .day-row.active { background-color: #f5f9ff; border-color: #dbe7ff; }
        .day-row.inactive { opacity: 0.6; background-color: #fafafa; }
        .day-row:hover { border-color: var(--accent-color); } /* حدود عند المرور */

        .day-label { font-weight: 600; color: var(--secondary-color); display: flex; align-items: center; gap: 0.6rem; font-size: 0.95rem; }
        .day-icon { width: 20px; text-align: center; color: var(--primary-color); opacity: 0.9; }

        .time-inputs { display: flex; align-items: center; gap: 0.5rem; }
        .time-separator { color: #adb5bd; font-size: 0.9rem; padding: 0 0.25rem; }
        .time-input, .duration-input input[type="number"] {
            border: 1px solid var(--input-border); border-radius: 6px; padding: 6px 10px;
            font-size: 0.9rem; text-align: center; background-color: var(--input-bg);
            transition: all 0.2s ease;
        }
         .time-input:focus, .duration-input input[type="number"]:focus {
             border-color: var(--input-focus-border); box-shadow: 0 0 0 3px var(--input-focus-shadow); outline: none;
         }
        .duration-input .input-group-text { background-color: transparent; border: none; font-size: 0.85rem; color: var(--dark-gray); }

        /* تصميم زر التفعيل */
        .day-toggle { justify-self: end; }
        .form-switch .form-check-input { width: 2.8em; height: 1.4em; cursor: pointer; background-color: #e9ecef; border-color: #dee2e6; background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='rgba(0,0,0,.25)'/%3e%3c/svg%3e"); transition: background-color .15s ease-in-out,border-color .15s ease-in-out,background-position .15s ease-in-out; }
        .form-switch .form-check-input:checked { background-color: var(--success-color); border-color: var(--success-color); background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e"); }
        .form-switch .form-check-label { font-size: 0.8rem; color: var(--dark-gray); }

        /* تعطيل الحقول */
        .inputs-disabled input, .inputs-disabled .input-group-text { opacity: 0.5; cursor: not-allowed !important; }

        /* زر الحفظ */
        .btn-save-schedule {
            background: var(--primary-gradient); color: white; border: none; padding: 0.8rem 2rem; border-radius: 50px; /* أكثر استدارة */
            font-weight: 700; display: inline-flex; align-items: center; gap: 0.75rem; transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(74, 144, 226, 0.3); text-transform: uppercase; font-size: 0.9rem; letter-spacing: 0.5px;
        }
        .btn-save-schedule:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(74, 144, 226, 0.4); }
        .btn-save-schedule:active { transform: translateY(-1px); }

        /* تنبيهات Bootstrap مخصصة */
        .alert-custom { border-radius: 10px; border-left-width: 5px; padding: 1rem 1.25rem; }
        .alert-info.alert-custom { background-color: #e7f3fe; border-color: #85c5fe; color: #0c5464; }
        .alert-danger.alert-custom { background-color: #f8d7da; border-color: #f1aeb5; color: #721c24; }
        .alert-custom i { font-size: 1.2em; }
        .alert-custom strong { font-weight: 600; }
        .alert-custom ul { padding-right: 1.5rem; margin-top: 0.5rem; font-size: 0.9em; }

        /* Responsive */
        @media (max-width: 991px) {
            .day-row { grid-template-columns: 1fr auto; /* تبسيط لعمودين */ grid-template-areas: "label toggle" "times times" "duration duration"; }
            .day-label { grid-area: label; }
            .time-inputs { grid-area: times; flex-direction: column; align-items: stretch; gap: 0.5rem; }
            .time-separator { display: none; }
            .duration-input { grid-area: duration; }
            .day-toggle { grid-area: toggle; justify-self: end; }
            .time-input, .duration-input input[type="number"] { max-width: none; }
        }
        @media (max-width: 575px) {
            .p-6 { padding: 1.5rem; }
            .schedule-header { flex-direction: column; align-items: flex-start; gap: 0.5rem; }
            .day-row { gap: 0.75rem; }
        }
    </style>
@endsection

@section('title')
    إدارة جدول عمل الطبيب - {{ $doctor->name }}
@endsection

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
             {{-- نفس الـ breadcrumb --}}
             <div class="d-flex align-items-center"> <h4 class="content-title mb-0 my-auto">{{ trans('main-sidebar_trans.doctors') }}</h4> <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ <a href="{{ route('admin.Doctors.edit', $doctor->id) }}">{{ $doctor->name }}</a></span> <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تعديل جدول العمل</span> </div>
        </div>
         <div class="d-flex my-xl-auto right-content">
             {{-- نفس أزرار العودة --}}
             <a href="{{ route('admin.Doctors.edit', $doctor->id) }}" class="btn btn-outline-secondary btn-sm rounded-pill"> <i class="fas fa-user-edit me-1"></i> البيانات الأساسية </a>
             <a href="{{ route('admin.Doctors.index') }}" class="btn btn-outline-primary btn-sm rounded-pill mr-2"> <i class="fas fa-list me-1"></i> قائمة الأطباء </a>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

{{-- ====================== HTML Content Section ===================== --}}
@section('content')
    @include('Dashboard.messages_alert')

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="schedule-edit-container animate__animated animate__fadeIn">
                <div class="schedule-header">
                    <h2 class="schedule-title">
                        <i class="fas fa-calendar-check"></i>
                        إدارة جدول ساعات العمل
                    </h2>
                    <span class="doctor-badge">
                        <i class="fas fa-user-md"></i>
                        {{ $doctor->name }}
                    </span>
                </div>

                <form action="{{ route('admin.doctors.schedule.update', $doctor->id) }}" method="POST" id="schedule-form" class="needs-validation" novalidate>
                    @method('PUT')
                    @csrf

                    <div class="alert alert-info alert-custom mb-4 animate__animated animate__fadeIn" data-wow-delay="0.2s">
                         <div class="d-flex align-items-center">
                             <i class="fas fa-info-circle fa-lg me-2"></i>
                             <span>حدد الأيام النشطة وأدخل بياناتها. الأيام غير المفعلة ستُعتبر أيام راحة.</span>
                         </div>
                    </div>

                    {{-- عرض أخطاء التحقق المجمعة --}}
                    @if($errors->any())
                        <div class="alert alert-danger alert-custom mb-4 animate__animated animate__headShake" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle fa-lg me-2"></i>
                                <div>
                                    <strong>خطأ في الإدخال!</strong>
                                    <ul class="mb-0 mt-1 small">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="day-schedule-container">
                        @foreach ($daysOfWeek as $day)
                            @php
                                $currentDayData = $workingHoursData[$day] ?? ['active' => false, 'start_time' => '', 'end_time' => '', 'appointment_duration' => 30];
                                // نستخدم old() مع التحقق من أن الفورم أُرسل بالفعل لتجنب مشاكل التحميل الأول
                                $isActive = $errors->any() ? (old('days.'.$day.'.active') === 'on') : $currentDayData['active'];
                                $startTime = old('days.'.$day.'.start_time', $currentDayData['start_time']);
                                $endTime = old('days.'.$day.'.end_time', $currentDayData['end_time']);
                                $duration = old('days.'.$day.'.appointment_duration', $currentDayData['appointment_duration']);
                                $dayIcons = ['saturday' => 'fa-star-of-david', 'sunday' => 'fa-sun', 'monday' => 'fa-moon', 'tuesday' => 'fa-calendar-day', 'wednesday' => 'fa-calendar-day', 'thursday' => 'fa-calendar-week', 'friday' => 'fa-mosque'];
                            @endphp

                            {{-- إضافة تأثير حركة لكل صف --}}
                            <div class="day-row {{ $isActive ? 'active' : 'inactive' }} animate__animated animate__fadeInUp" style="animation-delay: {{ $loop->index * 0.05 }}s;" id="day-{{ $day }}">
                                <div class="day-label">
                                    <span class="day-icon"><i class="fas {{ $dayIcons[strtolower($day)] ?? 'fa-calendar' }}"></i></span>
                                    <span>{{ trans('doctors.days.' . strtolower($day)) ?? $day }}</span>
                                </div>

                                <div class="time-inputs">
                                    <input type="text" {{-- تغيير النوع لاستخدام Flatpickr --}}
                                           class="form-control time-input flatpickr-time" {{-- إضافة كلاس جديد --}}
                                           name="days[{{ $day }}][start_time]"
                                           value="{{ $startTime }}"
                                           placeholder="00:00"
                                           {{ !$isActive ? 'disabled' : '' }} required>
                                    <span class="time-separator">-</span>
                                    <input type="text" {{-- تغيير النوع لاستخدام Flatpickr --}}
                                           class="form-control time-input flatpickr-time" {{-- إضافة كلاس جديد --}}
                                           name="days[{{ $day }}][end_time]"
                                           value="{{ $endTime }}"
                                           placeholder="00:00"
                                           {{ !$isActive ? 'disabled' : '' }} required>
                                    {{-- رسالة الخطأ الخاصة بالوقت ستظهر في التنبيه العام أو يمكن إضافتها هنا --}}
                                </div>

                                <div class="duration-input">
                                    <div class="input-group input-group-sm">
                                        <input type="number" class="form-control duration-input-field"
                                               name="days[{{ $day }}][appointment_duration]" value="{{ $duration }}"
                                               min="5" max="120" step="5"
                                               {{ !$isActive ? 'disabled' : '' }} required>
                                        <span class="input-group-text">دقيقة</span>
                                    </div>
                                </div>

                                <div class="day-toggle">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input day-switch" type="checkbox" role="switch"
                                               id="toggle-{{ $day }}" name="days[{{ $day }}][active]" value="on"
                                               {{ $isActive ? 'checked' : '' }}>
                                        <label class="form-check-label small" for="toggle-{{ $day }}"></label>
                                        {{-- <span class="input-group-text">فعال</span> --}}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-center mt-5">
                        <button type="submit" class="btn-save-schedule">
                            <i class="fas fa-save"></i>
                            حفظ جدول العمل
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

{{-- ====================== JavaScript Section ===================== --}}
@section('js')
    @parent
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/ar.js"></script>
    {{-- Bootstrap JS Bundle (إذا لزم الأمر لإغلاق التنبيهات) --}}

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // --- تهيئة Flatpickr لحقول الوقت (مع وضع AM/PM) ---
            flatpickr('.flatpickr-time', {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",    // *** الصيغة التي ستُرسل إلى الخادم (24 ساعة) ***
                time_24hr: false,   // *** السماح بإدخال 12 ساعة (AM/PM) ***
                locale: "ar",       // تفعيل اللغة العربية (تظهر صباحاً/مساءً)
                minuteIncrement: 5,
                // (اختياري) عرض الوقت الحالي
                 defaultHour: 9, // يبدأ عند 9 صباحاً افتراضياً
                // defaultDate: new Date(), // يمكنك استخدام هذا أيضاً

                // تغيير طريقة عرض الوقت للمستخدم (تنسيق 12 ساعة)
                altInput: true, // إنشاء حقل إضافي مرئي للمستخدم
                altFormat: "h:i K", // *** الصيغة التي سيراها المستخدم (h: ساعة 12، i: دقائق، K: صباحاً/مساءً) ***

                onReady: function(selectedDates, dateStr, instance) {
                    if (instance.input.disabled) {
                        instance.input.style.backgroundColor = "#e9ecef";
                        // قد تحتاج أيضاً لتعطيل الحقل البديل المرئي (altInput)
                        if(instance.altInput) instance.altInput.disabled = true;
                    }
                },
                 // عند تغيير حالة التعطيل لاحقاً
                 // (نحتاج لطريقة لتحديث altInput أيضاً عند تغيير الـ checkbox)

            }); // نهاية flatpickr

            // --- التحكم في تفعيل/تعطيل أيام العمل ---
            document.querySelectorAll('.day-switch').forEach(switchEl => {
                const dayRow = switchEl.closest('.day-row');
                const relatedInputs = dayRow.querySelectorAll('.flatpickr-time, input[name$="[appointment_duration]"]'); // شملنا حقول flatpickr

                function toggleInputs(isActive) {
                    relatedInputs.forEach(input => {
                        input.disabled = !isActive;
                        input.required = isActive;

                        // التعامل مع Flatpickr بشكل خاص
                        if (input.classList.contains('flatpickr-time')) {
                            const fpInstance = input._flatpickr;
                             const altInput = fpInstance ? fpInstance.altInput : null; // الحصول على الحقل المرئي

                            if (fpInstance) {
                                if (!isActive) {
                                    fpInstance.clear(); // مسح القيمة
                                    input.style.backgroundColor = "#e9ecef";
                                     if(altInput) altInput.disabled = true; // تعطيل الحقل المرئي
                                } else {
                                    input.style.backgroundColor = ''; // إعادة اللون الافتراضي
                                     if(altInput) altInput.disabled = false; // تفعيل الحقل المرئي
                                }
                            }
                        }
                        input.closest('.time-inputs, .duration-input')?.classList.toggle('inputs-disabled', !isActive);
                    });
                    dayRow.classList.toggle('active', isActive);
                    dayRow.classList.toggle('inactive', !isActive);
                    dayRow.style.opacity = isActive ? '1' : '0.6';
                }

                switchEl.addEventListener('change', function() {
                    toggleInputs(this.checked);
                });
                toggleInputs(switchEl.checked); // تشغيل عند التحميل
            });

            // --- تفعيل Bootstrap Validation ---
            (function () { /* ... الكود كما هو ... */ })();
            // --- إغلاق تنبيهات Bootstrap ---
            (function () { /* ... الكود كما هو ... */ })();

        }); // نهاية DOMContentLoaded
    </script>
@endsection
