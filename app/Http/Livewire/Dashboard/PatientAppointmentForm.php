<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Section;
use App\Models\Doctor;
use App\Models\DoctorWorkingDay;
use App\Models\Appointment;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection as EloquentCollection; // استخدام الاسم الكامل هنا

class PatientAppointmentForm extends Component
{
    // الخصائص التي سيتم ربطها بالـ view (wire:model)
    public $selectedSection = null;
    public $selectedDoctor = null;
    public $selectedDate = null;
    public $selectedTime = null;
    public $patientName;
    public $patientEmail;
    public $patientPhone;
    public $notes = '';

    // الخصائص التي ستحمل البيانات للقوائم المنسدلة والأوقات
    // سيتم تهيئتها كـ Eloquent Collections فارغة مبدئيًا أو في mount
    public EloquentCollection $sections;
    public EloquentCollection $doctors;
    public EloquentCollection $workingDays; // لتخزين أيام عمل الطبيب المختار
    public array $availableTimes = [];

    public bool $message = false; // لرسالة النجاح
    public string $errorMessage = ''; // لرسائل الخطأ المخصصة

    // قواعد التحقق (استخدم نفس أسماء الخصائص العامة أعلاه)
    protected function rules()
    {
        return [
            'selectedSection' => 'required|exists:sections,id',
            'selectedDoctor' => 'required|exists:doctors,id',
            'selectedDate' => 'required|date|after_or_equal:' . now()->format('Y-m-d'),
            'selectedTime' => 'required|date_format:H:i',
            'patientName' => 'required|string|max:255',
            'patientEmail' => 'required|email|max:255',
            'patientPhone' => 'required|string|regex:/^\+?[0-9\s\-]{10,15}$/', // قاعدة هاتف أكثر مرونة
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages()
    {
        return [
            'selectedSection.required' => 'يجب اختيار القسم الطبي.',
            'selectedDoctor.required' => 'يجب اختيار الطبيب المعالج.',
            'selectedDate.required' => 'يجب اختيار تاريخ الموعد.',
            'selectedDate.date' => 'صيغة التاريخ غير صحيحة.',
            'selectedDate.after_or_equal' => 'لا يمكن حجز موعد في تاريخ سابق.',
            'selectedTime.required' => 'يجب اختيار وقت الموعد.',
            'selectedTime.date_format' => 'صيغة الوقت غير صحيحة (مثال: 14:30).',
            'patientName.required' => 'اسم المريض الكامل مطلوب.',
            'patientEmail.required' => 'البريد الإلكتروني للمريض مطلوب.',
            'patientEmail.email' => 'صيغة البريد الإلكتروني غير صحيحة.',
            'patientPhone.required' => 'رقم هاتف المريض مطلوب.',
            'patientPhone.regex' => 'صيغة رقم الهاتف غير صحيحة.',
        ];
    }

    public function mount()
    {
        Log::info('PatientAppointmentForm: MOUNT method started.');
        try {
            $this->sections = Section::where('status', 1) // جلب الأقسام النشطة فقط
                ->orderByTranslation('name') // أو orderBy('name') إذا لم يكن الاسم مترجمًا
                ->get();
            Log::info('PatientAppointmentForm: Sections fetched in MOUNT.', ['count' => $this->sections->count()]);
        } catch (\Exception $e) {
            Log::error('PatientAppointmentForm: Error fetching sections in MOUNT.', ['error' => $e->getMessage()]);
            $this->sections = new EloquentCollection(); // تهيئة بمجموعة فارغة عند الخطأ
        }

        $this->doctors = new EloquentCollection(); // تهيئة doctors
        $this->workingDays = new EloquentCollection(); // تهيئة workingDays
        $this->availableTimes = [];

        if (Auth::guard('patient')->check()) {
            $patient = Auth::guard('patient')->user();
            $this->patientName = $patient->name;
            $this->patientEmail = $patient->email;
            $this->patientPhone = $patient->Phone; // تأكد من اسم الحقل في موديل Patient
        }
        Log::info('PatientAppointmentForm: MOUNT method finished.');
    }

    public function updatedSelectedSection($sectionId)
    {
        Log::info('PatientAppointmentForm: updatedSelectedSection called.', ['section_id' => $sectionId]);
        $this->selectedDoctor = null;
        $this->selectedDate = null;
        $this->selectedTime = null;
        $this->availableTimes = [];
        $this->doctors = new EloquentCollection(); // إفراغ الأطباء

        if ($sectionId) {
            $this->doctors = Doctor::where('section_id', $sectionId)
                ->where('status', 1)
                ->orderByTranslation('name') // أو orderBy('name')
                ->get();
            Log::info('PatientAppointmentForm: Doctors loaded for section ' . $sectionId, ['count' => $this->doctors->count()]);
        }
        $this->dispatchBrowserEvent('doctors-updated'); // إذا كنت تحتاج لتحديث select2 JS
    }

    public function updatedSelectedDoctor($doctorId)
    {
        Log::info('PatientAppointmentForm: updatedSelectedDoctor called.', ['doctor_id' => $doctorId]);
        $this->selectedDate = null;
        $this->selectedTime = null;
        $this->availableTimes = [];
        $this->workingDays = new EloquentCollection(); // إفراغ أيام العمل

        if ($doctorId) {
            $this->workingDays = DoctorWorkingDay::with('breaks') // جلب الاستراحات
                ->where('doctor_id', $doctorId)
                ->where('active', true)
                ->get();
            Log::info('PatientAppointmentForm: Working days loaded for doctor ' . $doctorId, ['count' => $this->workingDays->count()]);
        }
        $this->dispatchBrowserEvent('doctor-selected'); // إذا كنت تحتاج لتحديث أي شيء في JS
    }

    public function updatedSelectedDate($date)
    {
        Log::info('PatientAppointmentForm: updatedSelectedDate called.', ['date' => $date]);
        $this->selectedTime = null;
        $this->availableTimes = [];
        $this->resetErrorBag(['selectedTime', 'selectedDate']); // مسح أخطاء الوقت والتاريخ السابقة
        // $this->errorMessage = ''; // إذا كنت تستخدم خاصية errorMessage

        if ($date && $this->selectedDoctor) {
            try {
                $parsedDate = Carbon::parse($date);
                if ($parsedDate->isPast() && !$parsedDate->isToday()) {
                    $this->addError('selectedDate', 'لا يمكن اختيار تاريخ في الماضي.');
                    return;
                }
                $this->calculateAvailableTimes();
            } catch (\Exception $e) {
                Log::error("Error parsing date in updatedSelectedDate: " . $e->getMessage());
                $this->addError('selectedDate', 'صيغة التاريخ غير صالحة.');
                return;
            }
        }
    }

    private function calculateAvailableTimes()
    {
        Log::info('PatientAppointmentForm: calculateAvailableTimes called.');
        $this->availableTimes = [];
        if (!$this->selectedDoctor || !$this->selectedDate) {
            Log::warning('PatientAppointmentForm: calculateAvailableTimes - Doctor or Date not selected.');
            return;
        }

        // التأكد من أن workingDays محملة للطبيب المختار
        if ($this->workingDays->isEmpty() || $this->workingDays->first()->doctor_id != $this->selectedDoctor) {
            $this->workingDays = DoctorWorkingDay::with('breaks')
                ->where('doctor_id', $this->selectedDoctor)
                ->where('active', true)
                ->get();
            Log::info('PatientAppointmentForm: Re-fetched working days in calculateAvailableTimes.', ['count' => $this->workingDays->count()]);
        }


        $dayName = Carbon::parse($this->selectedDate)->format('l');
        $workingDay = $this->workingDays->firstWhere('day', $dayName);

        if (!$workingDay) {
            Log::info('PatientAppointmentForm: No working day found for ' . $dayName);
            $this->addError('selectedDate', 'الطبيب غير متاح في هذا اليوم المحدد.');
            return;
        }
        Log::info('PatientAppointmentForm: Working day found.', ['day' => $workingDay->toArray()]);


        $startTime = Carbon::parse($workingDay->start_time);
        $endTime = Carbon::parse($workingDay->end_time);
        $duration = (int) $workingDay->appointment_duration;

        if ($duration <= 0) {
            Log::error('PatientAppointmentForm: Invalid appointment duration for doctor.', ['duration' => $duration]);
            $this->addError('selectedDoctor', 'خطأ في مدة الموعد المحددة للطبيب.');
            return;
        }

        $bookedTimes = Appointment::where('doctor_id', $this->selectedDoctor)
            ->whereDate('appointment', $this->selectedDate)
            ->pluck('appointment')
            ->map(fn($dt) => Carbon::parse($dt)->format('H:i'))
            ->toArray();
        Log::info('PatientAppointmentForm: Booked times.', ['times' => $bookedTimes]);

        $slots = [];
        $currentTime = $startTime->copy();
        while ($currentTime->copy()->addMinutes($duration)->lte($endTime)) {
            $slotStart = $currentTime->copy();
            $slotEnd = $currentTime->copy()->addMinutes($duration);
            $timeStr = $slotStart->format('H:i');
            $isAvailable = true;

            if ($workingDay->relationLoaded('breaks') && $workingDay->breaks->isNotEmpty()) {
                foreach ($workingDay->breaks as $break) {
                    $breakStart = Carbon::parse($break->start_time);
                    $breakEnd = Carbon::parse($break->end_time);
                    if ($slotStart->lt($breakEnd) && $slotEnd->gt($breakStart)) {
                        $isAvailable = false;
                        break;
                    }
                }
            }

            if (!$isAvailable) {
                $currentTime->addMinutes($duration);
                continue;
            }
            if (in_array($timeStr, $bookedTimes)) {
                $isAvailable = false;
            }
            if (Carbon::parse($this->selectedDate)->isToday() && $slotStart->isPast()) {
                $isAvailable = false;
            }

            if ($isAvailable) {
                $slots[] = $timeStr;
            }
            $currentTime->addMinutes($duration);
        }

        $this->availableTimes = $slots;
        Log::info('PatientAppointmentForm: Calculated available times.', ['slots' => $this->availableTimes]);

        if (empty($slots) && !$this->getErrorBag()->has('selectedDate')) {
            $this->addError('selectedTime', 'لا توجد أوقات متاحة في هذا اليوم. يرجى اختيار يوم آخر.');
        }
    }

    public function submit()
    {
        $validatedData = $this->validate();
        Log::info('PatientAppointmentForm: Submit validation passed.');

        DB::beginTransaction();
        try {
            $appointmentDateTime = Carbon::parse($validatedData['selectedDate'] . ' ' . $validatedData['selectedTime']);

            // ... (بقية منطق التحقق من توفر الموعد والحد الأقصى كما هو) ...
            $isSlotStillAvailable = !Appointment::where('doctor_id', $validatedData['selectedDoctor'])
                ->where('appointment', $appointmentDateTime)->exists();
            if (!$isSlotStillAvailable) { /* ... addError, rollback, return ... */
            }
            // ...

            Appointment::create([
                'doctor_id' => $validatedData['selectedDoctor'],
                'section_id' => $validatedData['selectedSection'],
                'patient_id' => Auth::guard('patient')->check() ? Auth::guard('patient')->id() : null,
                'name' => $validatedData['patientName'],
                'email' => $validatedData['patientEmail'],
                'phone' => $validatedData['patientPhone'],
                'appointment' => $appointmentDateTime,
                'notes' => $validatedData['notes'] ?? null,
                'type' => 'غير مؤكد'
            ]);

            DB::commit();
            session()->flash('success', 'تم حجز الموعد بنجاح! سيتم التواصل معك للتأكيد.');
            $this->resetForm();
            $this->dispatchBrowserEvent('appointment-booked-successfully');
            Log::info('PatientAppointmentForm: Appointment booked successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning("PatientAppointmentForm: Submit validation failed during DB operation.", $e->errors());
            // لا تحتاج لـ DB::rollBack() هنا عادة لأن التحقق قبل العمليات
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("PatientAppointmentForm: Error saving appointment: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            session()->flash('error', 'حدث خطأ غير متوقع: ' . $e->getMessage());
        }
    }

    private function resetForm()
    {
        $this->resetErrorBag(); // مسح كل أخطاء التحقق
        $this->reset(['selectedDate', 'selectedTime', 'notes', 'availableTimes'/*, 'errorMessage', 'message'*/]);
        if (!Auth::guard('patient')->check()) {
            $this->reset(['patientName', 'patientEmail', 'patientPhone']);
        }
        // لا تقم بإعادة تعيين selectedSection و selectedDoctor للحفاظ على اختيار المستخدم
        // إذا أردت إعادة تحميل الأطباء بعد حجز ناجح (لأن القائمة قد تتغير):
        // if ($this->selectedSection) {
        //     $this->updatedSelectedSection($this->selectedSection);
        // }
        Log::info('PatientAppointmentForm: Form reset.');
    }

    public function render()
    {
        Log::info('PatientAppointmentForm: RENDER method called.');
        // لا حاجة لتمرير الخصائص العامة صراحة هنا، Livewire يعالجها
        // ولكن لضمان أن $sections و $doctors موجودتان دائمًا عند أول render (حتى لو فارغتين)
        // تم تهيئتهما في mount
        return view('livewire.dashboard.patient-appointment-form');
    }
}
