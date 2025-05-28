<?php

namespace App\Http\Livewire\Appointments;

use Log;
use App\Models\Doctor;
use App\Models\Section;
use Livewire\Component;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use App\Models\DoctorWorkingDay; // *** استيراد ضروري ***
use Illuminate\Support\Carbon;    // *** استيراد ضروري ***
use Illuminate\Support\Facades\DB;    // *** استيراد ضروري ***

class Create extends Component
{
    // --- خصائص المكون ---

    // بيانات الفورم الأساسية
    public $sections;          // قائمة الأقسام المتاحة
    public $doctors;           // قائمة الأطباء المتاحين (تعتمد على القسم)
    public $form = [           // بيانات المريض (تملأ تلقائياً إذا سجل الدخول)
        'name' => '',
        'email' => '',
        'Phone' => ''
    ];
    public $notes = '';            // ملاحظات إضافية من المريض

    // حالة اختيار القسم والطبيب
    public $section = null;        // معرّف القسم المختار
    public $doctor = null;         // معرّف الطبيب المختار

    // حالة اختيار التاريخ والوقت
    public $selected_date = null;  // التاريخ المختار من التقويم (بصيغة Y-m-d)
    public $selected_time = null;  // الوقت المختار من الفترات المتاحة (بصيغة H:i)

    // بيانات التوفر المحسوبة
    public $available_dates = [];      // مصفوفة التواريخ المتاحة للطبيب المختار (لإرسالها للتقويم)
    public $available_time_slots = []; // مصفوفة الفترات الزمنية المتاحة لليوم المختار

    // رسائل للمستخدم
    public $message = false;       // رسالة نجاح الحجز
    public $message2 = false;      // رسالة تحذير (مثلاً: الطبيب محجوز بالكامل اليوم) - يمكن دمجها مع errorMessage
    public $errorMessage = '';     // رسالة خطأ محددة (مثل: اليوم ليس يوم عمل)

    // المستمعات لأحداث Livewire / JavaScript
    protected $listeners = [
        'setSelectedDoctor',    // حدث من زر "حجز موعد" بجانب الطبيب
        'dateSelected'          // حدث من تقويم JavaScript عند اختيار تاريخ
    ];

    // --- التهيئة ---

    public function mount()
    {
        $this->sections = Section::all();
        $this->doctors = collect();

        if (Auth::check()) {
            $user = Auth::user();
            $this->form = [
                'name' => $user->name,
                'email' => $user->email,
                'Phone' => $user->Phone // تأكد أن اسم الحقل في جدول users هو 'Phone' وليس 'phone'
            ];
        }
    }
    // --- معالجة تغييرات المستخدم في الفورم ---

    // عند تغيير القسم في القائمة المنسدلة
    public function updatedSection($section_id)
    {
        $this->doctors = collect(); // إفراغ قائمة الأطباء
        $this->doctor = null;       // إلغاء اختيار الطبيب
        $this->resetAvailability(); // إعادة تعيين كل ما يتعلق بالتوفر

        if (!empty($section_id)) {
            // جلب الأطباء النشطين فقط التابعين للقسم المختار
            $this->doctors = Doctor::where('section_id', $section_id)
                ->where('status', 1) // التأكد من أن الطبيب نشط
                ->get();
        }
    }

    // عند تغيير الطبيب في القائمة المنسدلة
    public function updatedDoctor($doctorId)
    {
        $this->resetAvailability(); // إعادة تعيين التوفر أولاً

        if (!empty($doctorId)) {
            // تحميل التواريخ المتاحة للطبيب المختار حديثاً
            $this->loadAvailableDatesForDoctor($doctorId);
        }
    }

    // --- الاستجابة للأحداث ---

    // استجابة لحدث 'setSelectedDoctor' (من زر حجز الطبيب)
    public function setSelectedDoctor($sectionId, $doctorId)
    {
        // 1. تحديث القسم المختار واستدعاء updatedSection لتحميل الأطباء
        $this->section = $sectionId;
        $this->updatedSection($sectionId); // سيقوم هذا بإعادة تعيين التوفر أيضاً

        // 2. التحقق من وجود الطبيب وتحديث الاختيار
        if ($this->doctors->contains('id', $doctorId)) {
            $this->doctor = $doctorId;
            // 3. تحميل التواريخ المتاحة للطبيب المختار
            $this->loadAvailableDatesForDoctor($doctorId);
        } else {
            // إذا لم يتم العثور على الطبيب (حالة نادرة)، ألغِ الاختيار
            $this->doctor = null;
            $this->resetAvailability();
        }
    }

    // استجابة لحدث 'dateSelected' (من تقويم JavaScript)
    public function dateSelected($date)
    {
        // التحقق المبدئي من التاريخ المستلم
        try {
            $parsedDate = Carbon::parse($date)->format('Y-m-d');
            $this->selected_date = $parsedDate;
        } catch (\Exception $e) {
            $this->errorMessage = "صيغة التاريخ غير صالحة.";
            $this->resetAvailability(); // إعادة تعيين كل شيء
            return;
        }

        // إعادة تعيين الوقت والفترات المتاحة قبل الحساب الجديد
        $this->selected_time = null;
        $this->available_time_slots = [];
        $this->errorMessage = ''; // مسح أي خطأ سابق

        // التحقق من اختيار طبيب وتاريخ صالح
        if (empty($this->doctor) || empty($this->selected_date)) {
            $this->errorMessage = "يرجى اختيار الطبيب أولاً.";
            return;
        }

        // --- بدء حساب الفترات الزمنية المتاحة ---

        // 1. جلب يوم العمل المطابق للطبيب والتاريخ
        $selectedDayName = Carbon::parse($this->selected_date)->format('l'); // اسم اليوم (e.g., Sunday)
        $workingDay = DoctorWorkingDay::with('breaks') // جلب الاستراحات معه
            ->where('doctor_id', $this->doctor)
            ->where('day', $selectedDayName)
            ->where('active', true) // التأكد من أن يوم العمل نشط
            ->first();

        // إذا لم يكن يوم عمل أو غير نشط
        if (!$workingDay) {
            $this->errorMessage = "اليوم المختار ليس يوم عمل متاح لهذا الطبيب.";
            $this->available_time_slots = []; // تأكيد إفراغ القائمة
            return;
        }

        // 2. جلب المواعيد المحجوزة مسبقاً لهذا الطبيب في هذا اليوم
        $bookedAppointmentsTimes = Appointment::where('doctor_id', $this->doctor)
            ->whereDate('appointment', $this->selected_date)
            // ->where('type', '!=', 'ملغي') // يمكن استثناء أنواع معينة إذا لزم الأمر
            ->pluck('appointment') // جلب كائنات Carbon
            ->map(function ($datetime) {
                return $datetime->format('H:i'); // تحويل لصيغة H:i
            })
            ->toArray();

        // 3. التحقق من الحد الأقصى للمواعيد اليومية
        $doctorModel = Doctor::find($this->doctor);
        $dailyAppointmentCount = count($bookedAppointmentsTimes); // عدد المواعيد المحجوزة فعلاً
        if ($doctorModel && $doctorModel->number_of_statements > 0 && $dailyAppointmentCount >= $doctorModel->number_of_statements) {
            $this->errorMessage = "تم الوصول للحد الأقصى لعدد المواعيد المسموح به في هذا اليوم.";
            $this->available_time_slots = [];
            // يمكن استخدام $this->message2 = true هنا إذا أردت رسالة تحذير مميزة
            return;
        }


        // 4. حساب الفترات الزمنية المتاحة
        try {
            $startTime = Carbon::parse($workingDay->start_time);
            $endTime = Carbon::parse($workingDay->end_time);
            $duration = $workingDay->appointment_duration; // مدة الموعد بالدقائق

            if ($duration <= 0) {
                $this->errorMessage = "خطأ في تحديد مدة الموعد للطبيب.";
                return;
            }

            $slots = [];
            $currentTime = $startTime->copy();

            while ($currentTime->copy()->addMinutes($duration)->lte($endTime)) {
                $slotStart = $currentTime->copy();
                $slotEnd = $currentTime->copy()->addMinutes($duration);
                $slotStartTimeString = $slotStart->format('H:i');
                $isAvailable = true;

                // أ. التحقق من عدم الوقوع ضمن فترة استراحة
                foreach ($workingDay->breaks as $break) {
                    $breakStart = Carbon::parse($break->start_time);
                    $breakEnd = Carbon::parse($break->end_time);
                    // هل الفترة تتداخل مع الاستراحة؟ (تتداخل إذا بدأت قبل نهاية الاستراحة وانتهت بعد بدايتها)
                    if ($slotStart->lt($breakEnd) && $slotEnd->gt($breakStart)) {
                        $isAvailable = false;
                        break;
                    }
                }
                if (!$isAvailable) {
                    $currentTime->addMinutes($duration); // انتقل للفترة التالية
                    continue;
                }

                // ب. التحقق من عدم كونه موعداً محجوزاً بالفعل
                if (in_array($slotStartTimeString, $bookedAppointmentsTimes)) {
                    $isAvailable = false;
                }

                // ج. التحقق من أن الوقت لم يمضِ (إذا كان الحجز لنفس اليوم)
                if ($this->selected_date == Carbon::today()->toDateString() && $slotStart->isPast()) {
                    $isAvailable = false;
                }

                // إذا كانت الفترة متاحة، أضفها
                if ($isAvailable) {
                    $slots[] = $slotStartTimeString;
                }

                // الانتقال لبداية الفترة التالية
                $currentTime->addMinutes($duration);
            }

            $this->available_time_slots = $slots;

            // رسالة إذا لم يتم العثور على أي فترات متاحة
            if (empty($slots)) {
                $this->errorMessage = "لا توجد أوقات متاحة في هذا اليوم حالياً.";
            }
        } catch (\Exception $e) {
            // التعامل مع أي خطأ غير متوقع أثناء حساب الفترات
            $this->errorMessage = "حدث خطأ أثناء حساب الأوقات المتاحة. يرجى المحاولة لاحقاً.";
            Log::error("Error calculating time slots: " . $e->getMessage()); // تسجيل الخطأ للمطور
        }
    }

    // عند النقر على زر وقت متاح
    public function selectTime($time)
    {
        // التأكد من أن الوقت المختار هو فعلاً ضمن الأوقات المتاحة المعروضة
        if (in_array($time, $this->available_time_slots)) {
            $this->selected_time = $time;
        }
    }

    // --- حفظ الموعد ---

    public function store()
    {
        // 1. التحقق الأساسي من المدخلات
        $validatedData = $this->validate([
            'section' => 'required|exists:sections,id',
            'doctor' => 'required|exists:doctors,id',
            // 'patient' => 'nullable|exists:patients,id', // إذا كان ضرورياً
            'form.name' => 'required|string|max:255',
            'form.email' => 'required|email|max:255',
            'form.Phone' => 'required|string|max:20', // يمكن إضافة تحقق من صيغة الهاتف
            'selected_date' => 'required|date_format:Y-m-d|after_or_equal:today',
            'selected_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string|max:1000' // زيادة الحد الأقصى للملاحظات
        ]);

        // 2. تجميع التاريخ والوقت المحدد
        try {
            $fullDateTime = Carbon::parse($this->selected_date . ' ' . $this->selected_time);
        } catch (\Exception $e) {
            $this->addError('selected_time', 'التاريخ أو الوقت المحدد غير صالح.');
            return;
        }

        // --- 3. التحقق النهائي والحاسم قبل الحفظ (لمنع الحجوزات المتضاربة) ---
        // أ. هل الوقت لا يزال متاحاً (لم يحجزه أحد آخر في هذه اللحظة)؟
        $isSlotStillAvailable = !Appointment::where('doctor_id', $this->doctor)
            ->where('appointment', $fullDateTime)
            ->exists();

        if (!$isSlotStillAvailable) {
            $this->addError('selected_time', 'عذراً، هذا الموعد تم حجزه للتو. يرجى اختيار وقت آخر.');
            // إعادة تحميل الفترات المتاحة لإزالة الوقت المحجوز
            $this->dateSelected($this->selected_date);
            return;
        }

        // ب. هل الطبيب متاح فعلاً في هذا الوقت (تحقق من أيام العمل والاستراحات مرة أخرى)؟
        // يمكن إضافة هذا التحقق كطبقة أمان إضافية، لكن التحقق الأولي في dateSelected يغطيه غالباً.

        // ج. هل تجاوز الحد الأقصى اليومي؟
        $doctorModel = Doctor::find($this->doctor);
        $dailyAppointmentCount = Appointment::where('doctor_id', $this->doctor)
            ->whereDate('appointment', $this->selected_date)
            ->count();

        if ($doctorModel && $doctorModel->number_of_statements > 0 && $dailyAppointmentCount >= $doctorModel->number_of_statements) {
            $this->addError('selected_date', 'عذراً، تم الوصول للحد الأقصى للمواعيد المسموح به لهذا اليوم.');
            $this->dateSelected($this->selected_date); // أعد تحميل الخانات
            return;
        }


        // --- 4. إنشاء الموعد في قاعدة البيانات ---
        try {
            Appointment::create([
                'doctor_id' => $this->doctor,
                'section_id' => $this->section,
                'patient_id' => Auth::id(), // معرّف المستخدم المسجل دخوله (إذا كان ضرورياً)
                'name' => $this->form['name'],
                'email' => $this->form['email'],
                'phone' => $this->form['Phone'],
                'appointment' => $fullDateTime, // <-- التاريخ والوقت الكامل للموعد
                'notes' => $this->notes,
                'type' => 'غير مؤكد' // الحالة الافتراضية
            ]);

            // --- 5. التعامل بعد النجاح ---
            $this->message = true; // إظهار رسالة النجاح
            $this->message2 = false;
            $this->errorMessage = '';
            $this->resetFormAndAvailability(); // إعادة تعيين الفورم والتوفر

            // إرسال حدث لإعادة تعيين التقويم في JavaScript
            $this->dispatchBrowserEvent('reset-calendar');

            // اختياري: إرسال بريد إلكتروني أو إشعار للمريض/الطبيب

        } catch (\Exception $e) {
            // التعامل مع أي خطأ أثناء عملية الحفظ
            $this->errorMessage = "حدث خطأ غير متوقع أثناء محاولة حفظ الموعد. يرجى المحاولة مرة أخرى.";
            Log::error("Error saving appointment: " . $e->getMessage()); // تسجيل الخطأ
        }
    }

    // --- دوال مساعدة ---

    // تحميل التواريخ المتاحة وإرسالها للتقويم
    private function loadAvailableDatesForDoctor($doctorId)
    {
        $this->available_dates = []; // إفراغ القائمة قبل البدء
        $doctorModel = Doctor::with(['workingDays' => function ($query) {
            $query->where('active', true); // جلب أيام العمل النشطة فقط
        }])->find($doctorId);

        // إذا لم يتم العثور على الطبيب أو ليس لديه أيام عمل نشطة
        if (!$doctorModel || $doctorModel->workingDays->isEmpty()) {
            $this->dispatchBrowserEvent('update-calendar', ['enabledDates' => []]); // إرسال قائمة فارغة
            return;
        }

        $enabledDates = [];
        $startDate = Carbon::today();
        $endDate = Carbon::today()->addMonths(2); // نطاق البحث: شهران

        // تحسين: جلب عدد المواعيد المحجوزة لكل يوم ضمن النطاق مرة واحدة
        $dailyAppointmentCounts = Appointment::where('doctor_id', $doctorId)
            // ->where('type', '!=', 'ملغي') // حسب منطق العمل
            ->whereBetween('appointment', [$startDate, $endDate->endOfDay()])
            ->select(DB::raw('DATE(appointment) as appointment_date'), DB::raw('count(*) as count'))
            ->groupBy('appointment_date')
            ->pluck('count', 'appointment_date');

        // المرور على أيام العمل المجدولة للطبيب
        foreach ($doctorModel->workingDays as $day) {
            $dayOfWeekNumber = $this->getDayOfWeekNumber($day->day);
            if ($dayOfWeekNumber === null) continue; // تجاهل اليوم إذا كان الاسم غير صالح

            // المرور على التواريخ ضمن النطاق المحدد
            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                // هل يطابق اليوم الحالي يوم عمل الطبيب؟
                if ($date->dayOfWeek == $dayOfWeekNumber) {
                    $dateString = $date->toDateString(); // 'Y-m-d'

                    // التحقق من الحد الأقصى للمواعيد اليومي
                    $currentCount = $dailyAppointmentCounts->get($dateString) ?? 0;
                    // تأكد من أن number_of_statements > 0 لتطبيق الحد
                    if ($doctorModel->number_of_statements > 0 && $currentCount >= $doctorModel->number_of_statements) {
                        continue; // تخطي اليوم إذا وصل للحد الأقصى
                    }

                    // إضافة التاريخ للقائمة إذا لم يكن مضافاً
                    if (!in_array($dateString, $enabledDates)) {
                        $enabledDates[] = $dateString;
                    }
                }
            }
        }
        sort($enabledDates); // ترتيب التواريخ
        $this->available_dates = $enabledDates;

        // إرسال التواريخ المتاحة إلى JavaScript لتحديث التقويم
        $this->dispatchBrowserEvent('update-calendar', ['enabledDates' => $this->available_dates]);
    }

    // إعادة تعيين كل ما يتعلق بالتوفر
    private function resetAvailability()
    {
        $this->selected_date = null;
        $this->selected_time = null;
        $this->available_dates = [];
        $this->available_time_slots = [];
        $this->errorMessage = '';
        // إرسال حدث للتقويم لإعادة التعيين (اختياري، قد يكون جزءاً من update-calendar)
        $this->dispatchBrowserEvent('update-calendar', ['enabledDates' => []]); // إرسال قائمة فارغة لتنظيف التقويم
    }

    // إعادة تعيين حقول الفورم الأساسية والتوفر بعد الحفظ الناجح
    private function resetFormAndAvailability()
    {
        // لا تقم بإعادة تعيين القسم والطبيب هنا إذا أردت للمستخدم حجز موعد آخر لنفس الطبيب
        $this->reset(['notes', 'selected_date', 'selected_time', 'available_time_slots', 'errorMessage']);
        // لا تعيد تعيين $this->form إذا كان المستخدم مسجلاً دخوله وتريد الاحتفاظ بمعلوماته
    }


    // تحويل اسم اليوم (إنجليزي/عربي محتمل) إلى رقم (الأحد=0)
    private function getDayOfWeekNumber($dayName)
    {
        $dayName = trim($dayName); // إزالة المسافات الزائدة
        $daysMap = [
            // الإنجليزية (الأولوية)
            'Sunday'    => 0,
            'sunday' => 0,
            'Monday'    => 1,
            'monday' => 1,
            'Tuesday'   => 2,
            'tuesday' => 2,
            'Wednesday' => 3,
            'wednesday' => 3,
            'Thursday'  => 4,
            'thursday' => 4,
            'Friday'    => 5,
            'friday' => 5,
            'Saturday'  => 6,
            'saturday' => 6,
            // العربية (كمحاولة ثانية)
            'الاحد' => 0,
            'الأحد' => 0,
            'الاثنين' => 1,
            'الإثنين' => 1,
            'الثلاثاء' => 2,
            'الاربعاء' => 3,
            'الأربعاء' => 3,
            'الخميس' => 4,
            'الجمعة' => 5,
            'السبت' => 6,
        ];
        return $daysMap[$dayName] ?? $daysMap[ucfirst(strtolower($dayName))] ?? null; // محاولة مطابقة حالة الأحرف المختلفة
    }

    // إغلاق رسائل التنبيه يدوياً (إذا كان الزر موجوداً)
    public function dismissMessage()
    {
        $this->message = false;
        $this->message2 = false;
        $this->errorMessage = '';
    }

    // --- العرض ---

    public function render()
{
    // لا حاجة لجلب الأقسام أو تمريرها يدوياً هنا
    // لأن $this->sections متاحة تلقائياً في الـ view
    return view('livewire.appointments.create');
}
}
