{{-- resources/views/Dashboard/Doctors/profile/edit_schedule.blade.php --}}
@extends('Dashboard.layouts.master') {{-- أو أي layout تستخدمه للطبيب --}}

{{-- ========================== CSS Section ========================== --}}
@section('css')
    @parent {{-- لاستيراد أي CSS من الـ layout الرئيسي --}}
    {{-- استيراد المكتبات --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/l10n/ar.css"> {{-- تأكد من وجود ملف اللغة العربية --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="{{ URL::asset('dashboard/plugins/notify/css/notifIt.css') }}" rel="stylesheet" />

    {{-- *** أنماط محسنة لتعديل تفاصيل الجدول *** --}}
    <style>
        :root { /* لوحة الألوان العصرية */
            --primary-color: #3a86ff; --primary-dark: #2667cc; --secondary-color: #8338ec;
            --accent-color: #00b4d8; --light-color: #f8f9fa; --dark-color: #212529;
            --success-color: #38b000; --error-color: #ff006e; --warning-color: #ffbe0b;
            --card-shadow: 0 12px 24px rgba(0, 0, 0, 0.08); --transition-speed: 0.3s;
        }
        body { font-family: 'Tajawal', sans-serif; background: linear-gradient(135deg, #f0f4f8 0%, #dfe7f0 100%); line-height: 1.6; color: var(--dark-color); }
        * { box-sizing: border-box; }

        /* الحاوية الرئيسية */
        .schedule-edit-container {
            width: 100%; max-width: 950px; background: white; border-radius: 16px;
            box-shadow: var(--card-shadow); overflow: hidden; margin: 30px auto;
            animation: fadeInUp 0.6s ease-out; position: relative;
        }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }

        /* الهيدر */
        .form-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white;
            padding: 25px 30px; text-align: center; position: relative; overflow: hidden; z-index: 1; margin-bottom: 30px;
            border-radius: 16px 16px 0 0;
        }
        .form-header::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0;
            background: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiPjxkZWZzPjxwYXR0ZXJuIGlkPSJwYXR0ZXJuIiB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHBhdHRlcm5Vbml0cz0idXNlclNwYWNlT25Vc2UiIHBhdHRlcm5UcmFuc2Zvcm09InJvdGF0ZSg0NSkiPjxyZWN0IHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCIgZmlsbD0icmdiYSgyNTUsMjU1LDI1NSwwLjA1KSIvPjwvcGF0dGVybj48L2RlZnM+PHJlY3QgZmlsbD0idXJsKCNwYXR0ZXJuKSIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIvPjwvc3ZnPg==');
            z-index: -1; opacity: 0.5;
        }
        .form-header h3 { margin: 0; font-size: 1.7rem; font-weight: 700; text-shadow: 0 1px 3px rgba(0,0,0,0.2); }
        .form-header p { margin: 8px 0 0; opacity: 0.9; font-size: 0.9rem; }
        .form-header i.header-icon { font-size: 1.6rem; margin-bottom: 10px; display: block; color: rgba(255,255,255,0.8); }

        /* قسم الفورم */
        .form-section { padding: 0 30px 30px; }

        /* تصميم بطاقة اليوم */
        .days-container { display: grid; gap: 1rem; }
        .day-card {
            border: 1px solid #eef2f7; border-radius: 12px; overflow: hidden;
            transition: var(--transition-speed); background: var(--white-color);
            box-shadow: 0 4px 10px rgba(0,0,0,0.04);
            border-left: 5px solid var(--primary-color);
        }
        .day-card:hover { box-shadow: 0 6px 18px rgba(0,0,0,0.08); }

        .day-header {
            padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center;
            background: #f7faff; border-bottom: 1px solid #eef2f7;
        }
        .day-header-left { display: flex; align-items: center; gap: 1rem; flex-wrap: wrap; } /* Allow wrapping */
        .day-title { font-weight: 600; font-size: 1.05rem; color: var(--dark-color); margin: 0; }
        .day-icon { color: var(--primary-color); font-size: 1.1rem; }
        .current-time-summary { font-size: 0.8rem; color: #6c757d; font-weight: 500; white-space: nowrap; } /* Prevent wrapping */
        .day-header-right { display: none; } /* Hide right section (switch/chevron) */

        .day-body { padding: 1.5rem; }

        /* حقول الوقت والمدة */
        .form-label-sm { font-size: 0.8rem; font-weight: 500; color: #555; margin-bottom: 0.4rem; display: block; }
        .form-control-time, .duration-input .form-control {
            border: 1px solid #d1d9e6; border-radius: 8px; padding: 8px 12px;
            font-size: 0.9rem; text-align: center; background-color: white;
            transition: all var(--transition-speed); height: auto;
        }
        .form-control-time { direction: ltr; }
        .form-control-time:focus, .duration-input .form-control:focus {
            border-color: var(--accent-color); box-shadow: 0 0 0 3px rgba(0, 180, 216, 0.2); outline: none;
        }
        .duration-input .input-group-text { background-color: #e9ecef; border: 1px solid #d1d9e6; border-right: 0; font-size: 0.8rem; color: #495057; border-radius: 0 8px 8px 0; padding: 8px 12px; }
        .duration-input .form-control { border-radius: 8px 0 0 8px; border-right: 0; }
        .duration-input { display: flex; }

        /* قسم الاستراحات */
        .breaks-section { margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px dashed #d1d9e6; }
        .breaks-section h6 {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 1rem; font-size: 0.95rem; color: var(--error-color); font-weight: 600;
        }
        .add-break-btn { font-size: 0.75rem; padding: 4px 10px; border: 1px solid rgba(231, 76, 60, 0.4); color: var(--error-color); border-radius: 6px; background: transparent; transition: all 0.2s ease; }
        .add-break-btn:hover { background-color: rgba(231, 76, 60, 0.05); border-color: rgba(231, 76, 60, 0.6); }
        .breaks-container { max-height: 180px; overflow-y: auto; padding-right: 8px; }
        .breaks-container::-webkit-scrollbar { width: 6px; }
        .breaks-container::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
        .breaks-container::-webkit-scrollbar-thumb { background: #ddd; border-radius: 10px; }
        .breaks-container::-webkit-scrollbar-thumb:hover { background: #ccc; }
        .break-card { background: #fff7f7; border: 1px solid #ffe5e5; border-radius: 8px; padding: 0.7rem 1rem; margin-bottom: 0.75rem; animation: fadeIn 0.3s ease; }
        .break-card label { display: none; }
        .break-card .form-control-time, .break-card .form-control { font-size: 0.85rem; padding: 5px 8px; height: auto; }
        .break-card .time-separator { color: #adb5bd; padding: 0 0.25rem; }
        .remove-break-btn { background-color: transparent; border: none; color: var(--error-color); opacity: 0.6; transition: opacity 0.2s ease; padding: 0; font-size: 0.9rem; line-height: 1; }
        .remove-break-btn:hover { opacity: 1; }

        /* أزرار الحفظ وإعادة التعيين */
        .form-actions {
            border-top: 1px solid #eef2f7; padding-top: 2rem; margin-top: 2rem;
            display: flex; justify-content: center; align-items: center; gap: 1rem; flex-wrap: wrap;
        }
        .btn-custom {
            padding: 10px 25px; font-weight: 600; font-size: 0.9rem;
            letter-spacing: 0.5px; border-radius: 8px; cursor: pointer;
            transition: all var(--transition-speed); display: inline-flex; align-items: center; gap: 0.5rem; border: none;
        }
        .btn-save-schedule {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); color: white;
            box-shadow: 0 4px 15px rgba(58, 134, 255, 0.25);
        }
        .btn-save-schedule:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(58, 134, 255, 0.35); }
        .btn-save-schedule:active { transform: translateY(0px); }
        .btn-reset-schedule {
            background-color: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0;
        }
        .btn-reset-schedule:hover { background-color: #e2e8f0; transform: translateY(-2px); }

        /* تنسيقات التحقق */
        .form-control.is-invalid, .was-validated .form-control:invalid {
            border-color: var(--error-color);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23ff006e'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23ff006e' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat; background-position: right calc(0.375em + 0.1875rem) center; background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
            padding-right: calc(1.5em + 0.75rem);
        }
        .input-group .form-control.is-invalid { z-index: 1; }
        .invalid-feedback { display: none; width: 100%; margin-top: 0.3rem; font-size: .8rem; color: var(--error-color); }
        .was-validated .form-control:invalid ~ .invalid-feedback, .form-control.is-invalid ~ .invalid-feedback { display: block; }
        .break-card .invalid-feedback.break-time-error { display: none; }
        .break-card .row.has-error .invalid-feedback.break-time-error { display: block; }

        .alert-custom { border-radius: 10px; border-left-width: 4px; padding: 1rem 1.25rem; font-size: 0.9rem; }
        .alert-info.alert-custom { background-color: #eef6ff; border-color: var(--primary-color); color: var(--primary-dark); }
        .alert-danger.alert-custom { background-color: #fff0f1; border-color: var(--error-color); color: #c81a4a; }
        .alert-custom i { font-size: 1.1em; margin-right: 0.75rem; }
        .alert-custom ul { padding-right: 1.5rem; margin-bottom: 0; }

        /* Responsive */
        @media (max-width: 767px) {
            .schedule-edit-container { border-radius: 10px; margin: 15px; }
            .form-header { padding: 20px; border-radius: 10px 10px 0 0; }
            .form-header h3 { font-size: 1.5rem; }
            .form-section { padding: 20px; }
            .day-card { border-radius: 8px; }
            .day-header { padding: 0.8rem 1rem; }
            .day-header-left { gap: 0.75rem; }
            .day-body { padding: 1rem; }
            .break-card .row > div { margin-bottom: 0.5rem; }
            .break-card .row .col, .break-card .row .col-md-4 { flex-basis: auto; }
            .form-actions { padding-top: 1.5rem; margin-top: 1.5rem; }
            .btn-custom { width: 100%; justify-content: center; }
        }
        @media (max-width: 575px) {
            .day-header-left { flex-direction: column; align-items: flex-start; gap: 0.25rem; }
            .current-time-summary { margin-left: 0 !important; }
            .day-body .row > .col-md-4 { width: 100%; flex: 0 0 100%; max-width: 100%; }
        }

        /* Keyframes للـ animation */
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes fadeOut { from { opacity: 1; } to { opacity: 0; } }
    </style>
@endsection

@section('title')
    تعديل تفاصيل أوقات العمل
@endsection

@section('page-header')
    <!-- breadcrumb -->
     <div class="breadcrumb-header justify-content-between">
         <div class="my-auto">
             <div class="d-flex align-items-center">
                 <h4 class="content-title mb-0 my-auto">لوحة التحكم</h4>
                 {{-- تأكد من أن هذه الـ routes موجودة أو قم بتعديلها --}}
                 @if(Route::has('doctor.profile.show'))
                    <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ <a href="{{ route('doctor.profile.show') }}">ملفي الشخصي</a></span>
                 @endif
                 <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ تعديل أوقات العمل</span>
             </div>
         </div>
         <div class="d-flex my-xl-auto right-content">
            <a href="{{ route('doctor.schedule.show') }}" class="btn btn-primary-gradient btn-sm">
                <i class="fas fa-edit me-1"></i> عرض جدول العمل
            </a>
            <a href="{{ route('doctor.profile.show') }}" class="btn btn-outline-secondary btn-sm mr-2">
                <i class="fas fa-user me-1"></i> العودة للملف الشخصي
            </a>
        </div>
         {{-- يمكنك إعادة تفعيل هذا إذا كان لديك صفحة عرض منفصلة --}}
         {{-- @if(Route::has('doctor.schedule.show'))
         <div class="d-flex my-xl-auto right-content">
             <a href="{{ route('doctor.schedule.show') }}" class="btn btn-outline-secondary btn-sm rounded-pill">
                 <i class="fas fa-eye me-1"></i> عرض جدولي الحالي
             </a>
         </div>
         @endif --}}
     </div>
    <!-- breadcrumb -->
@endsection

{{-- ====================== HTML Content Section ===================== --}}
@section('content')
    @include('Dashboard.messages_alert') {{-- لعرض رسائل النجاح/الخطأ العامة --}}

    <div class="row justify-content-center">
        <div class="col-12"> {{-- استخدام عرض كامل للاستفادة من max-width للحاوية --}}
            {{-- حاوية تعديل الجدول --}}
            <form action="{{ route('doctor.schedule.updatee', $doctor->id) }}" method="POST" id="scheduleForm" class="needs-validation schedule-edit-container" novalidate>
                @csrf
                @method('PUT')

                {{-- الهيدر --}}
                <div class="form-header">
                    <i class="fas fa-user-clock header-icon"></i>
                    <h3>تعديل تفاصيل أوقات العمل</h3>
                    <p>حدد ساعات البدء والانتهاء ومدة الموعد لأيام عملك المعتمدة.</p>
                </div>

                {{-- قسم الفورم --}}
                <div class="form-section">

                    {{-- رسالة التعليمات --}}
                    <div class="alert alert-info alert-custom mb-4 small">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-info-circle fa-lg me-2 mt-1 flex-shrink-0"></i>
                            <span>أيام العمل النشطة تم تحديدها مسبقاً من قبل الإدارة. يمكنك هنا فقط تعديل <strong>توقيتات البدء والانتهاء</strong> و<strong>مدة الموعد</strong> و<strong>فترات الاستراحة</strong> لهذه الأيام.</span>
                        </div>
                    </div>

                    {{-- عرض أخطاء التحقق المجمعة --}}
                    @if($errors->any())
                        <div class="alert alert-danger alert-custom mb-4 animate__animated animate__headShake" role="alert">
                            <div class="d-flex align-items-start">
                                <i class="fas fa-exclamation-triangle fa-lg me-2 mt-1 flex-shrink-0"></i>
                                <div>
                                    <strong>خطأ في الإدخال! يرجى مراجعة الحقول التالية:</strong>
                                    <ul class="mb-0 mt-1 small">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- حاوية أيام الأسبوع --}}
                    <div class="days-container">
                         @php
                            $dayIcons = ['saturday' => 'fa-star-of-david','sunday' => 'fa-sun','monday' => 'fa-moon','tuesday' => 'fa-calendar-day','wednesday' => 'fa-calendar-day','thursday' => 'fa-calendar-week','friday' => 'fa-mosque'];
                        @endphp

                         {{-- الحلقة على الأيام النشطة فقط --}}
                         @forelse ($activeDaysList as $day)
                            @php
                                $currentDayData = $workingHoursData[$day];
                                $startTime = old('days.'.$day.'.start_time', $currentDayData['start_time']);
                                $endTime = old('days.'.$day.'.end_time', $currentDayData['end_time']);
                                $duration = old('days.'.$day.'.appointment_duration', $currentDayData['appointment_duration']);
                                $breaks = old('days.'.$day.'.breaks', $currentDayData['breaks']); // $breaks هي مصفوفة بالفعل من Controller
                                if (!is_array($breaks)) $breaks = [];

                                $dayNameLower = strtolower($day);
                                $dayIcon = $dayIcons[$dayNameLower] ?? 'fa-calendar-alt';
                                $dayDisplayName = trans('doctors.days.' . $dayNameLower) ?? $day;
                            @endphp

                            <div class="day-card">
                                {{-- رأس اليوم --}}
                                <div class="day-header">
                                    <div class="day-header-left">
                                         <span class="day-icon"><i class="fas {{ $dayIcon }}"></i></span>
                                         <h4 class="day-title mb-0">{{ $dayDisplayName }}</h4>
                                         <small class="text-muted current-time-summary ms-3">
                                             (<span class="start-time-display">{{ $startTime ? \Carbon\Carbon::parse($startTime)->format('h:i A') : '--:--' }}</span> -
                                              <span class="end-time-display">{{ $endTime ? \Carbon\Carbon::parse($endTime)->format('h:i A') : '--:--' }}</span>)
                                         </small>
                                    </div>
                                </div>

                                {{-- جسم اليوم --}}
                                <div class="day-body" id="day-body-{{ $day }}">
                                    <div class="row g-3">
                                        {{-- حقول الوقت والمدة --}}
                                        <div class="col-md-4">
                                            <label for="start_time_{{ $day }}" class="form-label-sm">وقت البدء</label>
                                            <input type="text" id="start_time_{{ $day }}"
                                                   class="form-control form-control-sm form-control-time flatpickr-time start-time @error('days.'.$day.'.start_time') is-invalid @enderror"
                                                   name="days[{{ $day }}][start_time]"
                                                   value="{{ $startTime }}" placeholder="HH:MM" required>
                                            <div class="invalid-feedback">@error('days.'.$day.'.start_time') {{ $message }} @else مطلوب @enderror</div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="end_time_{{ $day }}" class="form-label-sm">وقت الانتهاء</label>
                                            <input type="text" id="end_time_{{ $day }}"
                                                   class="form-control form-control-sm form-control-time flatpickr-time end-time @error('days.'.$day.'.end_time') is-invalid @enderror"
                                                   name="days[{{ $day }}][end_time]"
                                                   value="{{ $endTime }}" placeholder="HH:MM" required>
                                            <div class="invalid-feedback">@error('days.'.$day.'.end_time') {{ $message }} @else مطلوب ويجب أن يكون بعد وقت البدء @enderror</div>
                                        </div>
                                        <div class="col-md-4">
                                            <label for="duration_{{ $day }}" class="form-label-sm">مدة الموعد (بالدقائق)</label>
                                            <div class="input-group input-group-sm duration-input">
                                                <input type="number" id="duration_{{ $day }}"
                                                       class="form-control form-control-sm @error('days.'.$day.'.appointment_duration') is-invalid @enderror"
                                                       name="days[{{ $day }}][appointment_duration]"
                                                       value="{{ $duration }}" min="5" max="120" step="5" required>
                                                <span class="input-group-text">دقيقة</span>
                                            </div>
                                            <div class="invalid-feedback">@error('days.'.$day.'.appointment_duration') {{ $message }} @else مطلوبة (بين 5 و 120) @enderror</div>
                                        </div>

                                        {{-- قسم الاستراحات --}}
                                        <div class="col-12 breaks-section mt-3">
                                            <h6>
                                                <span><i class="fas fa-mug-hot me-2"></i> فترات الاستراحة</span>
                                                <button type="button" class="btn btn-sm add-break-btn" onclick="addBreak('{{ $day }}')"> <i class="fas fa-plus me-1"></i> إضافة فترة </button>
                                            </h6>
                                             <div class="breaks-container" id="breaks-container-{{ $day }}">
                                                 {{-- عرض الاستراحات الموجودة --}}
                                                 @if(!empty($breaks))
                                                     @foreach($breaks as $index => $break)
                                                        @php
                                                            $breakStartTime = $break['start_time'] ?? '';
                                                            $breakEndTime = $break['end_time'] ?? '';
                                                            $breakReason = $break['reason'] ?? '';
                                                        @endphp
                                                        <div class="break-card animate__animated animate__fadeIn">
                                                            <div class="row g-2 align-items-center">
                                                                <div class="col">
                                                                    <input type="text" class="form-control form-control-sm flatpickr-time break-start-time @error("days.$day.breaks.$index.start_time") is-invalid @enderror"
                                                                           placeholder="HH:MM" name="days[{{ $day }}][breaks][{{ $index }}][start_time]"
                                                                           value="{{ $breakStartTime }}" required>
                                                                     <div class="invalid-feedback">@error("days.$day.breaks.$index.start_time") {{ $message }} @enderror</div>
                                                                </div>
                                                                <div class="col-auto"><span class="time-separator">-</span></div>
                                                                <div class="col">
                                                                    <input type="text" class="form-control form-control-sm flatpickr-time break-end-time @error("days.$day.breaks.$index.end_time") is-invalid @enderror"
                                                                           placeholder="HH:MM" name="days[{{ $day }}][breaks][{{ $index }}][end_time]"
                                                                           value="{{ $breakEndTime }}" required>
                                                                    <div class="invalid-feedback">@error("days.$day.breaks.$index.end_time") {{ $message }} @enderror</div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <input type="text" class="form-control form-control-sm @error("days.$day.breaks.$index.reason") is-invalid @enderror"
                                                                           name="days[{{ $day }}][breaks][{{ $index }}][reason]"
                                                                           value="{{ $breakReason }}" placeholder="السبب (اختياري)">
                                                                     <div class="invalid-feedback">@error("days.$day.breaks.$index.reason") {{ $message }} @enderror</div>
                                                                </div>
                                                                <div class="col-auto">
                                                                    <button type="button" class="btn btn-sm remove-break-btn" onclick="removeBreak(this)" title="إزالة الاستراحة"><i class="fas fa-times"></i></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                     @endforeach
                                                 @endif
                                                 {{-- قالب لإضافة استراحة جديدة --}}
                                                  <template id="break-template-{{ $day }}">
                                                      <div class="break-card">
                                                          <div class="row g-2 align-items-center">
                                                              <div class="col"><input type="text" class="form-control form-control-sm flatpickr-time break-start-time" placeholder="HH:MM" name="days[{{ $day }}][breaks][__INDEX__][start_time]" required></div>
                                                              <div class="col-auto"><span class="time-separator">-</span></div>
                                                              <div class="col"><input type="text" class="form-control form-control-sm flatpickr-time break-end-time" placeholder="HH:MM" name="days[{{ $day }}][breaks][__INDEX__][end_time]" required></div>
                                                              <div class="col-md-4"><input type="text" class="form-control form-control-sm" name="days[{{ $day }}][breaks][__INDEX__][reason]" placeholder="السبب (اختياري)"></div>
                                                              <div class="col-auto"><button type="button" class="btn btn-sm remove-break-btn" onclick="removeBreak(this)" title="إزالة الاستراحة"><i class="fas fa-times"></i></button></div>
                                                          </div>
                                                      </div>
                                                  </template>
                                             </div>
                                        </div>
                                    </div>
                                </div>
                            </div> {{-- نهاية day-card --}}
                        @empty
                            {{-- رسالة في حالة عدم وجود أيام نشطة --}}
                            <div class="alert alert-warning text-center py-4">
                                <i class="fas fa-exclamation-circle fa-2x mb-3 d-block"></i>
                                لم تحدد الإدارة أي أيام عمل نشطة لك حتى الآن. لا يمكنك تعديل الجدول الزمني حاليًا.
                            </div>
                        @endforelse
                    </div> {{-- نهاية days-container --}}

                     {{-- أزرار الحفظ وإعادة التعيين --}}
                     @if(!empty($activeDaysList))
                         <div class="form-actions">
                             <button type="reset" class="btn-custom btn-reset-schedule"> <i class="fas fa-sync-alt"></i> <span>إعادة التعيين</span> </button>
                             <button type="submit" class="btn-custom btn-save-schedule"> <i class="fas fa-save"></i> <span>حفظ التعديلات</span> </button>
                         </div>
                     @endif
                </div> {{-- نهاية form-section --}}
            </form> {{-- نهاية الفورم الرئيسي --}}
        </div> {{-- نهاية col --}}
    </div> {{-- نهاية row --}}
@endsection

{{-- ====================== JavaScript Section ===================== --}}
@section('js')
    @parent {{-- لاستيراد أي JS من الـ layout الرئيسي --}}
    {{-- استيراد المكتبات --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/ar.js"></script> {{-- ملف اللغة العربية لـ Flatpickr --}}
    <script src="{{ URL::asset('Dashboard/plugins/notify/js/notifIt.js') }}"></script>
    <script src="{{ URL::asset('Dashboard/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script> {{-- Bootstrap JS ضروري للـ Tooltips أو Modals إن وجدت --}}

    <script>
        // --- تهيئة Flatpickr ---
        function initializeFlatpickr(selector = '.flatpickr-time') {
            const elements = typeof selector === 'string' ? document.querySelectorAll(selector) : Array.from(selector); // Ensure it's an array
            elements.forEach(el => {
                if (el && !el._flatpickr) { // Check if element exists and not initialized
                    flatpickr(el, {
                        enableTime: true, noCalendar: true, dateFormat: "H:i",
                        time_24hr: true, locale: "ar", minuteIncrement: 5,
                        onChange: function(selectedDates, dateStr, instance) {
                            const dayCard = instance.element.closest('.day-card');
                            if(dayCard) updateHeaderTimeSummary(dayCard);
                        },
                        // onClose: function(selectedDates, dateStr, instance) { /* Optional: Validation on close */ }
                    });
                }
            });
        }

        // --- تحديث ملخص الوقت في الهيدر ---
         function updateHeaderTimeSummary(dayCard) {
             const startTimeInput = dayCard.querySelector('.start-time');
             const endTimeInput = dayCard.querySelector('.end-time');
             const startTimeDisplay = dayCard.querySelector('.start-time-display');
             const endTimeDisplay = dayCard.querySelector('.end-time-display');

             if (startTimeInput && endTimeInput && startTimeDisplay && endTimeDisplay) {
                 const startVal = startTimeInput.value;
                 const endVal = endTimeInput.value;
                 startTimeDisplay.textContent = formatTime12(startVal);
                 endTimeDisplay.textContent = formatTime12(endVal);
             }
         }

         // --- تحويل الوقت من 24 ساعة إلى 12 ساعة (AM/PM) للعرض ---
         function formatTime12(time24) {
             if (!time24 || typeof time24 !== 'string' || !time24.includes(':')) return '--:--';
             try {
                 const [hours, minutes] = time24.split(':');
                 const h = parseInt(hours, 10);
                 const m = parseInt(minutes, 10);
                 if (isNaN(h) || isNaN(m)) return '--:--';
                 const ampm = h >= 12 ? 'مساءً' : 'صباحًا';
                 const hour12 = h % 12 || 12;
                 return `${hour12}:${m < 10 ? '0' + m : m} ${ampm}`;
             } catch (e) {
                 console.error("Error formatting time:", time24, e);
                 return '--:--';
             }
         }

         // --- عدادات index لكل يوم لإضافة الاستراحات بشكل صحيح ---
         let breakIndexCounters = {}; // We'll initialize this based on existing breaks in DOMContentLoaded

        // --- إضافة فترة استراحة ---
        function addBreak(dayName) {
            const container = document.getElementById(`breaks-container-${dayName}`);
            const template = document.getElementById(`break-template-${dayName}`);

            if (!container || !template) {
                console.error(`Add Break Error: Container or template not found for day: ${dayName}`);
                showNotification(`حدث خطأ: لم يتم العثور على حاوية الاستراحات لليوم ${dayName}.`, 'error');
                return;
            }

            // استخدم عدد العناصر الموجودة + رقم عشوائي صغير أو timestamp لتجنب التضارب تماماً
            const existingBreaksCount = container.querySelectorAll('.break-card').length;
            const nextIndex = existingBreaksCount + Date.now(); // Use a more unique index

            const clone = template.content.cloneNode(true);
            const breakCard = clone.querySelector('.break-card');
            if (!breakCard) {
                 console.error(`Add Break Error: Template for ${dayName} is missing .break-card.`);
                 return;
            }


            // تحديث name و id (إذا لزم الأمر) باستخدام الـ index الجديد
            breakCard.querySelectorAll('[name*="__INDEX__"]').forEach(input => {
                input.name = input.name.replace('__INDEX__', nextIndex);
            });

            breakCard.style.opacity = '0'; // For fade-in effect
            container.appendChild(clone); // Add the cloned content

            // تهيئة flatpickr لحقول الوقت الجديدة *بعد* إضافتها للـ DOM
            const addedCard = container.querySelector('.break-card:last-child'); // Get the card just added
             if (addedCard) {
                 const newTimeInputs = addedCard.querySelectorAll('.flatpickr-time');
                 initializeFlatpickr(newTimeInputs); // Initialize flatpickr on the new inputs

                 // إظهار العنصر تدريجياً وتطبيق animation
                 requestAnimationFrame(() => {
                     addedCard.style.transition = 'opacity 0.5s ease';
                     addedCard.style.opacity = '1';
                     addedCard.classList.add('animate__animated', 'animate__fadeIn');
                     addedCard.addEventListener('animationend', () => {
                         addedCard.classList.remove('animate__animated', 'animate__fadeIn');
                     }, { once: true });
                 });
             } else {
                  console.error(`Add Break Error: Couldn't find the newly added break card for ${dayName}.`);
             }
        }

        // --- إزالة فترة استراحة ---
        function removeBreak(button) {
            const breakCard = button.closest('.break-card');
            if (breakCard) {
                breakCard.style.transition = 'opacity 0.3s ease, transform 0.3s ease, margin-bottom 0.3s ease, padding 0.3s ease';
                breakCard.style.opacity = '0';
                breakCard.style.transform = 'scale(0.9)';
                breakCard.style.marginBottom = '0'; // Collapse margin
                breakCard.style.paddingTop = '0'; // Collapse padding
                breakCard.style.paddingBottom = '0';

                breakCard.addEventListener('transitionend', () => {
                    breakCard.remove();
                }, { once: true });

                 // Fallback
                 setTimeout(() => { if (breakCard.parentNode) { breakCard.remove(); } }, 350);
            }
        }

        // --- التنفيذ عند تحميل الصفحة ---
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Initialize all existing Flatpickr inputs
            initializeFlatpickr();
            document.querySelectorAll('.day-card').forEach(updateHeaderTimeSummary);
            (function () {
                'use strict';
                var forms = document.querySelectorAll('.needs-validation');
                Array.prototype.slice.call(forms)
                    .forEach(function (form) {
                        form.addEventListener('submit', function (event) {
                            let customValid = true;
                            form.querySelectorAll('.day-card').forEach(dayCard => {
                                const dayStartTime = dayCard.querySelector('.start-time')?.value;
                                const dayEndTime = dayCard.querySelector('.end-time')?.value;
                                dayCard.querySelectorAll('.break-card').forEach(breakCard => {
                                    const breakStartInput = breakCard.querySelector('.break-start-time');
                                    const breakEndInput = breakCard.querySelector('.break-end-time');
                                    if(breakStartInput && breakEndInput) {

                                        if(breakEndInput.value && breakStartInput.value && breakEndInput.value <= breakStartInput.value) {
                                            customValid = false;
                                            breakEndInput.classList.add('is-invalid');

                                        } else {
                                            breakEndInput.classList.remove('is-invalid');
                                        }

                                    }
                                });
                            });

                            if (!form.checkValidity() || !customValid) {
                                event.preventDefault();
                                event.stopPropagation();
                                showNotification('يرجى التحقق من جميع الحقول المطلوبة وتصحيح الأخطاء الموضحة.', 'warning', 'top-center', 6000);
                            }
                            form.classList.add('was-validated');
                        }, false);
                    });
            })();
             const resetButton = document.querySelector('.btn-reset-schedule');
             const scheduleForm = document.getElementById('scheduleForm');
             if(resetButton && scheduleForm) {
                 resetButton.addEventListener('click', function(e) {
                     e.preventDefault();
                     if (confirm('هل أنت متأكد أنك تريد إعادة تعيين جميع الحقول إلى قيمها الأصلية؟ ستفقد أي تغييرات غير محفوظة.')) {
                         scheduleForm.reset();
                         document.querySelectorAll('.day-card').forEach(updateHeaderTimeSummary);
                         scheduleForm.classList.remove('was-validated');
                         document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                         showNotification('تمت إعادة تعيين الحقول.', 'info');
                     }
                 });
             }


            document.querySelectorAll('.alert-dismissible .btn-close').forEach(function (closeBtn) {
                 closeBtn.addEventListener('click', function () {
                     const alert = this.closest('.alert');
                     if (alert) {
                         alert.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                         alert.style.opacity = '0';
                         alert.style.transform = 'scale(0.95)';
                         alert.addEventListener('transitionend', () => alert.remove(), { once: true });

                         setTimeout(() => { if (alert.parentNode) alert.remove(); }, 350);
                     }
                 });
             });

        });



         function showNotification(message, type = 'info', position = 'top-right', timeout = 5000) {
             if (typeof notif !== 'undefined') {
                 notif({
                     msg: message, type: type, position: position, timeout: timeout,
                     clickable: true, autohide: timeout > 0
                 });
             } else {
                 console.warn('NotifIt library not loaded. Using fallback alert.');
                 alert(`${type.toUpperCase()}: ${message}`);
             }
         }

    </script>
@endsection
