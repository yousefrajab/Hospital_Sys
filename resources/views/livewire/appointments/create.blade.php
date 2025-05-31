<div class="appointment-container" wire:ignore.self>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://npmcdn.com/flatpickr/dist/l10n/ar.css">

    {{-- الأنماط المخصصة والأساسية (تم دمجها هنا) --}}
    <style>
        /* أنماط Flatpickr والفترات الزمنية */
        .input-with-icon .flatpickr-input+i {
            z-index: 2;
        }

        .time-slots {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }

        .time-slot-btn {
            background-color: #ecf0f1;
            border: 1px solid #bdc3c7;
            border-radius: 6px;
            padding: 8px 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            color: #2c3e50;
        }

        .time-slot-btn:hover {
            background-color: #d5dbdb;
            border-color: #aab1b5;
        }

        .time-slot-btn.selected {
            background-color: #3498db;
            color: white;
            border-color: #2980b9;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(52, 152, 219, 0.3);
        }

        .no-slots-message {
            color: #7f8c8d;
            font-style: italic;
            margin-top: 10px;
            width: 100%;
        }

        .error-validation {
            color: #e74c3c;
            font-size: 0.85em;
            display: block;
            margin-top: 4px;
        }

        input:disabled,
        select:disabled,
        button:disabled {
            cursor: not-allowed !important;
            opacity: 0.6 !important;
        }

        .flatpickr-input[readonly]:disabled {
            background-color: #eee;
        }

        .flatpickr-calendar.inline {
            width: 100%;
            box-shadow: none;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        /* الأنماط الأصلية للبطاقة والرسائل */
        .appointment-container {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 1000px;
            margin: 30px auto;
            padding: 20px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
                transform: translateY(0);
            }

            to {
                opacity: 0;
                transform: translateY(-20px);
            }
        }

        .appointment-alert {
            animation: fadeIn 0.5s ease-out forwards;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .appointment-alert.fade-out {
            animation: fadeOut 0.5s ease-out forwards;
        }

        .appointment-alert.success {
            background: #d4edda;
            color: #155724;
            border-left: 5px solid #28a745;
        }

        .appointment-alert.warning {
            background: #fff3cd;
            color: #856404;
            border-left: 5px solid #ffc107;
        }

        .appointment-alert i {
            font-size: 24px;
        }

        .appointment-alert h4 {
            margin: 0 0 5px;
            font-size: 1.1rem;
        }

        .appointment-alert p {
            margin: 0;
            font-size: 0.9rem;
        }

        .close-btn {
            position: absolute;
            top: 10px;
            left: 10px;
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: inherit;
        }

        .appointment-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .appointment-header {
            background: linear-gradient(135deg, #3498db, #2ecc71);
            color: white;
            padding: 25px;
            text-align: center;
        }

        .appointment-header h2 {
            margin: 0;
            font-size: 1.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .appointment-header p {
            margin: 10px 0 0;
            opacity: 0.9;
            font-size: 1rem;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
        }

        .form-section {
            flex: 1;
            min-width: 300px;
            padding: 25px;
        }

        .patient-info {
            background: #f9f9f9;
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 25px;
            color: #2c3e50;
        }

        .section-header h3 {
            margin: 0;
            font-size: 1.3rem;
        }

        .section-header i {
            font-size: 1.2rem;
            color: #3498db;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2c3e50;
            font-size: 0.95rem;
        }

        .input-with-icon {
            position: relative;
        }

        .input-with-icon input {
            width: 100%;
            padding: 12px 15px 12px 40px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
        }

        .input-with-icon input:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
            outline: none;
        }

        .input-with-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #7f8c8d;
        }

        .select-with-icon {
            position: relative;
        }

        .select-with-icon select {
            width: 100%;
            padding: 12px 15px 12px 40px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            appearance: none;
            background: white;
            transition: all 0.3s;
        }

        .select-with-icon select:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
            outline: none;
        }

        .select-with-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #7f8c8d;
        }

        textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            min-height: 100px;
            resize: vertical;
            transition: all 0.3s;
        }

        textarea:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
            outline: none;
        }

        .form-actions {
            text-align: center;
            padding: 20px;
            background: #f5f7fa;
        }

        .submit-btn {
            background: linear-gradient(135deg, #3498db, #2ecc71);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 30px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(46, 204, 113, 0.3);
        }

        @media (max-width: 768px) {
            .form-section {
                min-width: 100%;
            }

            .appointment-header h2 {
                font-size: 1.5rem;
            }

            .appointment-alert {
                flex-direction: column;
                text-align: center;
            }

            .appointment-alert i {
                margin-bottom: 10px;
            }
        }

        .error-message {
            color: #e74c3c;
            padding: 10px;
            margin: 10px 0;
            background: #fdecea;
            border-radius: 5px;
            border-left: 4px solid #e74c3c;
        }

        .d-none {
            display: none;
        }

        /* إضافة بسيطة لتحسين مظهر الرسائل داخل البطاقة */
        .appointment-card .appointment-alert {
            margin: 0 25px 20px;
        }
    </style>

    {{-- ======================================== --}}
    {{--  1. محتوى الـ HTML الخاص بالمكون          --}}
    {{-- ======================================== --}}

    <!-- رسائل التنبيه (تستخدم Alpine.js) -->
    @if ($message)
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show"
            class="appointment-alert success animate__animated animate__fadeInDown" x-transition>
            <i class="fas fa-check-circle"></i>
            <div>
                <h4>تم تأكيد الحجز بنجاح!</h4>
                <p>سيتم التواصل معك لتأكيد التفاصيل النهائية.</p>
            </div>
            <button wire:click="dismissMessage" @click="show = false" class="close-btn">×</button>
        </div>
    @endif
    @if ($errorMessage || $message2)
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 7000)" x-show="show"
            class="appointment-alert warning animate__animated animate__fadeInDown" x-transition>
            <i class="fas fa-exclamation-triangle"></i>
            <div>
                <h4>
                    @if ($errorMessage)
                        خطأ أو تنبيه
                    @else
                        لا توجد مواعيد متاحة
                    @endif
                </h4>
                <p>{{ $errorMessage ?: 'عذرًا، لا توجد مواعيد متاحة لهذا اليوم. يرجى اختيار يوم آخر' }}</p>
            </div>
            <button wire:click="dismissMessage" @click="show = false" class="close-btn">×</button>
        </div>
    @endif
    @if ($errors->any())
        <div class="appointment-alert warning animate__animated animate__fadeInDown mb-3">
            <i class="fas fa-exclamation-circle"></i>
            <div>
                <h4 class="alert-heading">الرجاء مراجعة الحقول التالية:</h4>
                <ul class="list-unstyled mb-0">
                    @foreach ($errors->all() as $error)
                        <li>- {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- بطاقة نموذج الحجز -->
    <div class="appointment-card">
        <div class="appointment-header">
            <h2><i class="fas fa-calendar-plus"></i> حجز موعد طبي</h2>
            <p>املأ النموذج التالي لحجز موعد مع طبيبك المختار</p>
        </div>

        <form wire:submit.prevent="store" class="appointment-form">
            <div class="form-row">

                <!-- قسم تفاصيل الموعد -->
                <div class="form-section appointment-info">
                    <div class="section-header"> <i class="fas fa-calendar-alt"></i>
                        <h3>تفاصيل الموعد</h3>
                    </div>
                    <div class="form-group"> <label for="section">القسم الطبي</label>
                        <div class="select-with-icon"> <select id="section" wire:model="section" required>
                                <option value="">-- اختر القسم --</option>
                                @foreach ($sections as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                @endforeach
                            </select> <i class="fas fa-chevron-down"></i> </div> @error('section')
                            <span class="error-validation">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group"> <label for="doctor">الطبيب المعالج</label>
                        <div class="select-with-icon"> <select id="doctor" wire:model="doctor" required
                                {{ empty($section) ? 'disabled' : '' }}>
                                <option value="">-- اختر الطبيب أولاً --</option>
                                @foreach ($doctors as $d)
                                    <option value="{{ $d->id }}">{{ $d->name }}</option>
                                @endforeach
                            </select> <i class="fas fa-user-md"></i> </div> @error('doctor')
                            <span class="error-validation">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- حقل التاريخ الجديد --}}
                    <div class="form-group">
                        <label for="appointment_flatpickr">اختر تاريخ الموعد</label>
                        <div class="input-with-icon" wire:ignore>
                            <input type="text" id="appointment_flatpickr" placeholder="الرجاء اختيار طبيب أولاً..."
                                readonly="readonly" class="form-control flatpickr-input">
                            <i class="fas fa-calendar-day"></i>
                        </div>
                        @error('selected_date')
                            <span class="error-validation">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- منطقة عرض الفترات الزمنية --}}
                    <div class="form-group" id="time-slots-container">
                        @if (!empty($selected_date))
                            <label>اختر وقت الموعد:</label>
                            <div class="time-slots">
                                @if (!empty($available_time_slots))
                                    @foreach ($available_time_slots as $slot)
                                        <button type="button" wire:click="selectTime('{{ $slot }}')"
                                            class="time-slot-btn {{ $selected_time == $slot ? 'selected' : '' }}">
                                            {{ \Carbon\Carbon::parse($slot)->translatedFormat('h:i A') }}
                                        </button>
                                    @endforeach
                                @else
                                    @if (empty($errorMessage) && empty($message2))
                                        <p class="no-slots-message">لا توجد أوقات متاحة في هذا اليوم.</p>
                                    @endif
                                @endif
                            </div>
                            @error('selected_time')
                                <span class="error-validation">{{ $message }}</span>
                            @enderror
                        @endif
                    </div>
                </div>

                <!-- قسم معلومات المريض -->
                <div class="form-section patient-info">
                    <div class="section-header"> <i class="fas fa-user-injured"></i>
                        <h3>معلومات المريض</h3>
                    </div>
                    <div class="form-group"> <label for="name">الاسم الكامل</label>
                        <div class="input-with-icon"> <input type="text" id="name" wire:model.defer="form.name"
                                placeholder="أدخل اسمك الثلاثي" required> <i class="fas fa-user"></i> </div>
                        @error('form.name')
                            <span class="error-validation">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group"> <label for="email">البريد الإلكتروني</label>
                        <div class="input-with-icon"> <input type="email" id="email" wire:model.defer="form.email"
                                placeholder="example@domain.com" required> <i class="fas fa-envelope"></i> </div>
                        @error('form.email')
                            <span class="error-validation">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group"> <label for="phone">رقم الهاتف</label>
                        <div class="input-with-icon"> <input type="tel" id="Phone" wire:model.defer="form.Phone"
                                placeholder="05XXXXXXXX" required> <i class="fas fa-phone-alt"></i> </div>
                        @error('form.Phone')
                            <span class="error-validation">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group"> <label for="notes">ملاحظات إضافية (اختياري)</label>
                        <textarea id="notes" wire:model.defer="notes" placeholder="أي معلومات إضافية تريد إضافتها..."></textarea> @error('notes')
                            <span class="error-validation">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

            </div>

            {{-- زر الإرسال --}}
            <div class="form-actions">
                <button type="submit" class="submit-btn" {{ empty($selected_time) ? 'disabled' : '' }}
                    wire:loading.attr="disabled" wire:target="store">
                    <span wire:loading.remove wire:target="store"> <i class="fas fa-calendar-check"></i> تأكيد الحجز
                    </span>
                    <span wire:loading wire:target="store"> <i class="fas fa-spinner fa-spin"></i> جاري الحجز...
                    </span>
                </button>
            </div>
        </form>
    </div>

</div> {{-- نهاية العنصر الجذري للمكون --}}


{{-- ======================================== --}}
{{--  2. قسم الـ CSS                        --}}
{{-- ======================================== --}}
{{-- انتبه: استبدل 'css' بالاسم الصحيح للـ section المستخدم في ملف الـ layout الرئيسي لديك --}}
{{-- @parent --}} {{-- استخدم هذا إذا كان الـ layout يضع CSS أساسي هنا وتريد الإضافة إليه --}}

{{-- استيراد CSS الخاص بـ Flatpickr --}}



{{-- ======================================== --}}
{{--  3. قسم الـ JavaScript                --}}
{{-- ======================================== --}}


{{-- *** السكربتات الجديدة المطلوبة *** --}}

{{-- 1. استيراد JS الخاص بـ Flatpickr --}}
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/ar.js"></script>

{{-- 2. استيراد Alpine.js (إذا لم يكن مستورداً بشكل عام في الـ layout) --}}
{{-- تأكد من استيراده مرة واحدة فقط --}}
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

{{-- 3. الكود المخصص لـ Flatpickr و Livewire Hooks --}}
<script>
    // التأكد من تحميل Alpine قبل استخدامه
    document.addEventListener('alpine:init', () => {
        let flatpickrInstance = null;

        function initializeFlatpickr() {
            const datePickerElement = document.getElementById('appointment_flatpickr');
            if (!datePickerElement) return;
            flatpickrInstance = flatpickr(datePickerElement, {
                inline: true,
                dateFormat: "Y-m-d",
                minDate: "today",
                locale: "ar",
                enable: [],
                disableMobile: "true",
                onChange: function(selectedDates, dateStr, instance) {
                    if (dateStr) {
                        console.log('Flatpickr: Date selected ->', dateStr);
                        @this.call('dateSelected', dateStr);
                    }
                },
            });
        }
        initializeFlatpickr();
        window.addEventListener('update-calendar', event => {
            if (flatpickrInstance && event.detail.enabledDates) {
                console.log('Livewire event: update-calendar ->', event.detail.enabledDates);
                flatpickrInstance.set('enable', event.detail.enabledDates);
                const dateInputEl = document.getElementById('appointment_flatpickr');
                if (dateInputEl && dateInputEl.disabled) {
                    flatpickrInstance.set('enable', []);
                }
            }
        });
        window.addEventListener('reset-calendar', event => {
            if (flatpickrInstance) {
                console.log('Livewire event: reset-calendar');
                flatpickrInstance.clear();
                flatpickrInstance.set('enable', []);
            }
        });
        if (typeof Livewire !== 'undefined') {
            Livewire.hook('element.updated', (el, component) => {
                // التأكد من أن المكون هو المكون الصحيح (اختياري لكن جيد)
                // if (component.fingerprint.name === 'appointments.create') {
                if (el.id === 'doctor') {
                    const doctorId = component.get('doctor');
                    const dateInputEl = document.getElementById('appointment_flatpickr');
                    if (dateInputEl && flatpickrInstance) {
                        const isDisabled = !doctorId;
                        dateInputEl.disabled = isDisabled;
                        console.log('Livewire hook: Doctor changed, Flatpickr disabled ->', isDisabled);
                        if (isDisabled) {
                            flatpickrInstance.clear();
                            flatpickrInstance.set('enable', []);
                        }
                    }
                }
                // }
            });
        }
        const initialDoctorSelect = document.getElementById('doctor');
        const initialDateInput = document.getElementById('appointment_flatpickr');
        if (initialDoctorSelect && initialDateInput) {
            initialDateInput.disabled = !initialDoctorSelect.value;
            console.log('Initial check: Flatpickr disabled ->', initialDateInput.disabled);
        }
    }); // نهاية alpine:init

    // --- كود التمرير إلى رسائل التنبيه ---
    // --- كود التمرير إلى رسائل التنبيه وإعادة التحميل عند النجاح ---
    document.addEventListener('livewire:load', function() {
        // منع اختيار التواريخ الماضية وعرض رسائل الخطأ
        const dateInput = document.getElementById('appointment_patient');
        const errorDiv = document.getElementById('date-error');

        if (dateInput && errorDiv) {
            // تعيين الحد الأدنى للتاريخ (اليوم)
            const today = new Date();
            const dd = String(today.getDate()).padStart(2, '0');
            const mm = String(today.getMonth() + 1).padStart(2, '0');
            const yyyy = today.getFullYear();
            const todayStr = `${yyyy}-${mm}-${dd}`;

            dateInput.setAttribute('min', todayStr);

            // التحقق عند تغيير التاريخ
            dateInput.addEventListener('change', function() {
                const selectedDate = new Date(this.value);
                const todayDate = new Date(todayStr);

                if (selectedDate < todayDate) {
                    this.value = '';
                    errorDiv.classList.remove('d-none');

                    // إخفاء رسالة الخطأ بعد 5 ثواني
                    setTimeout(() => {
                        errorDiv.classList.add('d-none');
                    }, 10000);
                } else {
                    errorDiv.classList.add('d-none');
                }
            });
        }

        // إدارة رسائل التنبيه (النجاح/التحذير)
        Livewire.hook('message.processed', (message, component) => {
            if (message.response.serverMemo.data.message || message.response.serverMemo.data.message2) {
                const alertBox = document.querySelector('.appointment-alert');

                // التمرير إلى الرسالة
                if (alertBox) {
                    setTimeout(() => {
                        alertBox.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }, 300);
                }

                // إخفاء الرسالة تلقائياً بعد 5 ثواني
                setTimeout(() => {
                    const alerts = document.querySelectorAll('.appointment-alert');
                    alerts.forEach(alert => {
                        alert.classList.add('fade-out');
                        setTimeout(() => {
                            Livewire.emit('dismissMessage');
                            setTimeout(() => {
                                window.location.reload();
                            }, 500);
                        }, 500);
                    });
                }, 5000);
            }
        });
    });
</script>
