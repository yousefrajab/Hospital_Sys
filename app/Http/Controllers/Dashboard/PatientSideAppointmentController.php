<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Doctor;
use App\Models\Section;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Dashboard\Appointment\CreatePatientAppointmentRequest;
use App\Models\DoctorWorkingDay; // تأكد من استيراد هذا

class PatientSideAppointmentController extends Controller
{
    public function create(Request $request)
    {
        Log::info("PatientSideAppointmentController@create: Loading create appointment form.");

        try {
            // جلب الأقسام مع الترتيب بالاسم المترجم للغة الحالية (تصاعدياً بشكل افتراضي)
            $sections = Section::orderByTranslation('name', 'asc')->get(); // تم التعديل لـ Section

            $doctors = collect();
            $selectedSectionId = old('section_id', $request->query('section_id'));
            $selectedDoctorId = old('doctor_id', $request->query('doctor_id'));

            $patientName = '';
            $patientEmail = '';
            $patientPhone = '';

            if (Auth::guard('patient')->check()) {
                $patient = Auth::guard('patient')->user();
                $patientName = $patient->name;
                $patientEmail = $patient->email;
                $patientPhone = $patient->Phone; // تأكد أن اسم الحقل في جدول users هو 'Phone'
            }

            if ($selectedSectionId) {
                $doctors = Doctor::where('section_id', $selectedSectionId)
                    ->where('status', 1)
                    ->select('doctors.id', 'doctors.name')
                    ->orderByTranslation('name', 'asc')
                    ->get();
            }

            return view('Dashboard.Patients.appointments.patient_create', compact(
                'sections',
                'doctors',
                'selectedSectionId',
                'selectedDoctorId',
                'patientName',
                'patientEmail',
                'patientPhone'
            ));

        } catch (\Exception $e) {
            Log::error("Error in PatientSideAppointmentController@create: " . $e->getMessage(), [
                'userId' => Auth::guard('patient')->check() ? Auth::guard('patient')->id() : null,
                'exception_trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحميل صفحة حجز المواعيد. يرجى المحاولة لاحقاً.');
        }
    }

    public function getDoctorsBySection(Request $request)
    {
        if (!$request->filled('section_id')) {
            Log::warning("AJAX: getDoctorsBySection - section_id is missing.");
            return response()->json(['doctors' => [], 'error' => 'معرّف القسم مطلوب.'], 400);
        }
        $sectionId = $request->input('section_id');
        Log::info("AJAX: Fetching doctors for section ID: {$sectionId}");

        try {
            $doctors = Doctor::where('section_id', $sectionId)
                ->where('status', 1) // الأطباء النشطون فقط
                ->select('doctors.id', 'doctors.name as doctor_name_translatable')
                ->orderByTranslation('name', 'asc')
                ->get()
                ->map(function ($doctor) {
                    return [
                        'id' => $doctor->id,
                        'name' => $doctor->name, // الاسم المترجم
                    ];
                });

            Log::info("AJAX: Found " . $doctors->count() . " doctors for section ID: {$sectionId}", ['doctors_data' => $doctors->toArray()]);
            return response()->json(['doctors' => $doctors]);

        } catch (\Exception $e) {
            Log::error("Error in getDoctorsBySection for section ID {$sectionId}: " . $e->getMessage(), ['trace' => $e->getTraceAsString(), 'sql' => $e instanceof \Illuminate\Database\QueryException ? $e->getSql() : 'N/A']);
            return response()->json(['error' => 'حدث خطأ أثناء جلب الأطباء.', 'details' => $e->getMessage()], 500);
        }
    }

    public function getDoctorAvailableDates(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
        ]);

        $doctorId = $validated['doctor_id'];
        Log::info("AJAX: Fetching available dates for Doctor ID: {$doctorId}");

        try {
            $doctorModel = Doctor::with(['workingDays' => function ($query) {
                $query->where('active', true);
            }])->find($doctorId);

            if (!$doctorModel) {
                Log::warning("AJAX: Doctor ID {$doctorId} not found for fetching available dates.");
                return response()->json(['enabledDates' => [], 'message' => 'الطبيب المحدد غير موجود.'], 404);
            }

            if ($doctorModel->workingDays->isEmpty()) {
                Log::info("AJAX: Doctor ID {$doctorId} has no active working days.");
                return response()->json(['enabledDates' => [], 'message' => 'الطبيب ليس لديه أيام عمل مجدولة حالياً.']);
            }

            $enabledDates = [];
            $startDate = Carbon::today();
            $endDate = Carbon::today()->addMonths(3); // نطاق البحث: 3 أشهر

            $dailyAppointmentCounts = Appointment::where('doctor_id', $doctorId)
                ->where('type', '!=', 'ملغي')
                ->whereBetween('appointment', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()])
                ->select(DB::raw('DATE(appointment) as appointment_date'), DB::raw('count(*) as count'))
                ->groupBy('appointment_date')
                ->pluck('count', 'appointment_date');

            foreach ($doctorModel->workingDays as $day) {
                $dayOfWeekNumber = $this->getDayOfWeekNumber($day->day);
                if ($dayOfWeekNumber === null) {
                    Log::warning("AJAX: Invalid day name '{$day->day}' for doctor ID {$doctorId}. Skipping.");
                    continue;
                }

                for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                    if ($date->dayOfWeek == $dayOfWeekNumber) {
                        $dateString = $date->toDateString();

                        $currentCount = $dailyAppointmentCounts->get($dateString, 0);
                        if ($doctorModel->number_of_statements > 0 && $currentCount >= $doctorModel->number_of_statements) {
                            continue;
                        }

                        if (!in_array($dateString, $enabledDates)) {
                            $enabledDates[] = $dateString;
                        }
                    }
                }
            }
            sort($enabledDates);

            Log::info("AJAX: Found " . count($enabledDates) . " enabled dates for Doctor ID: {$doctorId}");
            return response()->json(['enabledDates' => $enabledDates]);

        } catch (\Exception $e) {
            Log::error("Error fetching available dates for Doctor ID {$doctorId}: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'حدث خطأ أثناء جلب التواريخ المتاحة.', 'details' => $e->getMessage()], 500);
        }
    }

    private function getDayOfWeekNumber($dayName)
    {
        $dayName = trim(strtolower($dayName));
        $daysMap = [
            'sunday'    => 0, 'الأحد' => 0, 'الاحد' => 0,
            'monday'    => 1, 'الإثنين' => 1, 'الاثنين' => 1,
            'tuesday'   => 2, 'الثلاثاء' => 2,
            'wednesday' => 3, 'الأربعاء' => 3, 'الاربعاء' => 3,
            'thursday'  => 4, 'الخميس' => 4,
            'friday'    => 5, 'الجمعة' => 5,
            'saturday'  => 6, 'السبت' => 6,
        ];
        if (isset($daysMap[$dayName])) {
            return $daysMap[$dayName];
        }
        foreach ($daysMap as $key => $value) {
            if (str_contains($dayName, $key) || str_contains($key, $dayName)) {
                return $value;
            }
        }
        Log::warning("Could not map day name to number: '{$dayName}'");
        return null;
    }

    public function store(CreatePatientAppointmentRequest $request)
    {
        $validatedData = $request->validated();
        Log::info("PatientSideAppointmentController@store: Attempting to store appointment.", $validatedData);
        DB::beginTransaction();
        try {
            $appointmentDateTime = Carbon::parse($validatedData['selected_date'] . ' ' . $validatedData['selected_time']);

            $isSlotStillAvailable = !Appointment::where('doctor_id', $validatedData['doctor_id'])
                ->where('appointment', $appointmentDateTime)
                ->where('type', '!=', 'ملغي') // لا تحسب المواعيد الملغاة
                ->exists();

            if (!$isSlotStillAvailable) {
                DB::rollBack();
                Log::warning("PatientSideAppointmentController@store: Slot no longer available.", $validatedData);
                return redirect()->back()->withInput()->with('error', 'عذراً، هذا الموعد تم حجزه للتو. يرجى اختيار وقت آخر.');
            }

            $doctorModel = Doctor::find($validatedData['doctor_id']);
            $dailyAppointmentCount = Appointment::where('doctor_id', $validatedData['doctor_id'])
                ->whereDate('appointment', $validatedData['selected_date'])
                ->where('type', '!=', 'ملغي') // لا تحسب المواعيد الملغاة
                ->count();

            if ($doctorModel && $doctorModel->number_of_statements > 0 && $dailyAppointmentCount >= $doctorModel->number_of_statements) {
                DB::rollBack();
                Log::warning("PatientSideAppointmentController@store: Doctor reached daily limit.", $validatedData);
                return redirect()->back()->withInput()->with('error', 'عذراً، تم الوصول للحد الأقصى للمواعيد المسموح به لهذا اليوم للطبيب المختار.');
            }

            $appointment = Appointment::create([
                'doctor_id' => $validatedData['doctor_id'],
                'section_id' => $validatedData['section_id'],
                'patient_id' => Auth::guard('patient')->check() ? Auth::guard('patient')->id() : null,
                'name' => $validatedData['patient_name'],
                'email' => $validatedData['patient_email'],
                'phone' => $validatedData['patient_phone'],
                'appointment' => $appointmentDateTime,
                'notes' => $validatedData['notes'] ?? null,
                'type' => 'غير مؤكد' // الحالة الافتراضية
            ]);

            DB::commit();
            Log::info("New appointment [ID: {$appointment->id}] booked successfully for doctor ID: {$appointment->doctor_id}");
            // افترض أن لديك مساراً لعرض رسالة نجاح أو صفحة تفاصيل الموعد
            return redirect()->route('patient.appointment.success') // تأكد أن هذا المسار معرف
                             ->with('success_message', 'تم حجز موعدك بنجاح! رقم الموعد هو #' . $appointment->id . '. سيتم التواصل معك للتأكيد.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error("PatientSideAppointmentController@store: Validation exception.", ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("PatientSideAppointmentController@store: General error: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->back()->withInput()->with('error', 'حدث خطأ غير متوقع أثناء محاولة حجز الموعد: ' . $e->getMessage());
        }
    }

    public function getAvailableTimes(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'selected_date' => 'required|date_format:Y-m-d|after_or_equal:' . now()->toDateString(),
        ]);

        $doctorId = $validated['doctor_id'];
        $selectedDate = $validated['selected_date'];
        Log::info("AJAX: Fetching available times for Doctor ID: {$doctorId} on Date: {$selectedDate}");

        $doctor = Doctor::with(['workingDays' => function ($query) {
            $query->where('active', true)->with('breaks'); // تأكد من تحميل breaks
        }])->find($doctorId);

        if (!$doctor) {
            return response()->json(['times' => [], 'message' => 'الطبيب المحدد غير موجود.'], 404);
        }

        try {
            // استخدام translatedFormat إذا كانت أيام العمل في قاعدة البيانات باللغة الافتراضية (إنجليزية)
            // وتريد المقارنة مع اسم اليوم المترجم من التاريخ المختار.
            // أو، إذا كانت أيام العمل مخزنة بالعربية، استخدم format('l') وقارن معها مباشرة.
            $selectedDayNameCarbon = Carbon::parse($selectedDate);
            $dayName = $selectedDayNameCarbon->format('l'); // اسم اليوم بالإنجليزية (e.g., Sunday)

            $workingDay = $doctor->workingDays->first(function ($wd) use ($dayName) {
                // مقارنة اسم اليوم بعد تحويله لحروف صغيرة
                return strtolower(trim($wd->day)) === strtolower(trim($dayName));
            });

            if (!$workingDay) {
                Log::info("AJAX: No working day found for Doctor ID: {$doctorId} on {$dayName} ({$selectedDate})");
                return response()->json(['times' => [], 'message' => 'الطبيب غير متاح في هذا اليوم (ليس يوم عمل).']);
            }

            $startTime = Carbon::parse($workingDay->start_time);
            $endTime = Carbon::parse($workingDay->end_time);
            $duration = (int) $workingDay->appointment_duration;

            if ($duration <= 0) {
                return response()->json(['times' => [], 'message' => 'خطأ في مدة الموعد للطبيب.'], 400);
            }

            $bookedTimes = Appointment::where('doctor_id', $doctorId)
                ->whereDate('appointment', $selectedDate)
                ->where('type', '!=', 'ملغي') // لا تحسب المواعيد الملغاة
                ->pluck('appointment')
                ->map(fn($dt) => Carbon::parse($dt)->format('H:i'))
                ->toArray();

            // التحقق من الحد الأقصى للمواعيد اليومية
            if ($doctor->number_of_statements > 0 && count($bookedTimes) >= $doctor->number_of_statements) {
                 return response()->json(['times' => [], 'message' => 'تم الوصول للحد الأقصى للمواعيد المحجوزة لهذا اليوم.']);
            }

            $slots = [];
            $currentTime = $startTime->copy();

            while ($currentTime->copy()->addMinutes($duration)->lte($endTime)) {
                $slotStart = $currentTime->copy();
                $slotEnd = $currentTime->copy()->addMinutes($duration);
                $timeStr = $slotStart->format('H:i');
                $isAvailable = true;

                // التحقق من فترات الراحة
                if ($workingDay->relationLoaded('breaks') && $workingDay->breaks->isNotEmpty()) {
                    foreach ($workingDay->breaks as $break) {
                        $breakStart = Carbon::parse($break->start_time);
                        $breakEnd = Carbon::parse($break->end_time);
                        if ($slotStart->lt($breakEnd) && $slotEnd->gt($breakStart)) {
                            $isAvailable = false; break;
                        }
                    }
                }
                if (!$isAvailable) { $currentTime->addMinutes($duration); continue; }

                // التحقق من المواعيد المحجوزة
                if (in_array($timeStr, $bookedTimes)) { $isAvailable = false; }

                // التحقق من أن الوقت لم يمضِ
                $fullSlotStartDateTime = Carbon::parse($selectedDate . ' ' . $timeStr);
                if ($fullSlotStartDateTime->isPast() || $fullSlotStartDateTime->lessThan(now()->addMinutes(1))) { // هامش دقيقة واحدة
                    $isAvailable = false;
                }

                if ($isAvailable) {
                    // استخدام translatedFormat لعرض الوقت بالصيغة المحلية (AM/PM)
                    $slots[] = ['value' => $timeStr, 'display' => $slotStart->translatedFormat('h:i A')];
                }
                $currentTime->addMinutes($duration);
            }
            Log::info("AJAX: Available times for Doctor ID: {$doctorId} on {$selectedDate}", ['slots_count' => count($slots)]);
            return response()->json(['times' => $slots, 'message' => empty($slots) ? 'لا توجد أوقات متاحة في هذا اليوم.' : null]);

        } catch (\Exception $e) {
            Log::error("Error fetching available times via AJAX for Doctor ID {$doctorId}, Date {$selectedDate}: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'خطأ في جلب الأوقات المتاحة.', 'details' => $e->getMessage()], 500);
        }
    }
}
