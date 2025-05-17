<?php

namespace App\Http\Controllers\Dashboard;

// ... (باقي الاستيرادات كما هي)
use App\Models\Doctor;
use App\Models\Section;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // أضف هذا إذا لم يكن موجوداً
use App\Http\Requests\Dashboard\Appointment\CreatePatientAppointmentRequest;

class PatientSideAppointmentController extends Controller
{
    // ... (دالة create كما هي من التعديل السابق)
    public function create(Request $request)
    {
        Log::info("PatientSideAppointmentController@create: Loading create appointment form.");

        try {
            // جلب الأقسام مع الترتيب بالاسم المترجم للغة الحالية (تصاعدياً بشكل افتراضي)
            $sections = \App\Models\Section::orderByTranslation('name', 'asc')->get();

            $doctors = collect();
            $selectedSectionId = old('section_id', $request->query('section_id'));
            $selectedDoctorId = old('doctor_id', $request->query('doctor_id'));

            $patientName = '';
            $patientEmail = '';
            $patientPhone = '';

            if (\Illuminate\Support\Facades\Auth::guard('patient')->check()) {
                $patient = \Illuminate\Support\Facades\Auth::guard('patient')->user();
                $patientName = $patient->name;
                $patientEmail = $patient->email;
                $patientPhone = $patient->Phone;
            }

            if ($selectedSectionId) {
                // هنا يجب أيضاً تحديد اسم الجدول إذا كان orderByTranslation يقوم بعمل join
                $doctors = \App\Models\Doctor::where('section_id', $selectedSectionId)
                    ->where('status', 1)
                    // عند استخدام orderByTranslation مع select، يجب تحديد الأعمدة بوضوح
                    // لتجنب الغموض، الأفضل هو تحديد جميع الأعمدة المطلوبة من جدول doctors أولاً
                    // ثم السماح لـ orderByTranslation بالعمل.
                    // أو، إذا أردنا select محدد:
                    ->select('doctors.id', 'doctors.name') // حدد اسم الجدول للأعمدة
                    ->orderByTranslation('name', 'asc') // يجب أن تكون 'name' هنا هي الحقل المترجم في Doctors
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
                'userId' => \Illuminate\Support\Facades\Auth::guard('patient')->check() ? \Illuminate\Support\Facades\Auth::guard('patient')->id() : null,
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
            $doctorsQuery = \App\Models\Doctor::where('section_id', $sectionId)
                ->where('status', 1); // الأطباء النشطون فقط

            // عندما تستخدم orderByTranslation، تقوم الحزمة بعمل JOIN.
            // لتجنب غموض عمود 'id' وعمود 'name' (إذا كان 'name' موجوداً أيضاً في جدول الترجمات كجزء من النص المترجم)
            // يجب أن تحدد الأعمدة التي تريدها من الجدول الرئيسي (doctors) بشكل صريح.
            // 'name' هنا هو اسم الحقل في نموذج Doctor الذي يتم ترجمته.
            // الحزمة ستتعامل مع جلب الترجمة الصحيحة للاسم.
            $doctors = $doctorsQuery
                ->select('doctors.id', 'doctors.name as doctor_name_translatable') // حدد أعمدة جدول doctors
                // 'name' هنا يجب أن يكون الحقل القابل للترجمة في نموذج Doctor
                // الـ alias 'doctor_name_translatable' هو فقط لتجنب أي التباس إذا كان 'name' هو اسم الحقل المترجم فعلاً
                // ويمكنك تركه كـ 'doctors.name' إذا كان الحقل المترجم لديك هو 'name'
                ->orderByTranslation('name', 'asc') // 'name' هو الحقل الذي تريد الترتيب بناءً على ترجمته
                ->get()
                ->map(function ($doctor) {
                    // عند الوصول هنا، $doctor->name يجب أن يعيد الترجمة الصحيحة
                    // بسبب كيفية عمل spatie/laravel-translatable.
                    return [
                        'id' => $doctor->id, // هذا هو doctors.id
                        'name' => $doctor->name, // هذا هو الاسم المترجم
                    ];
                });

            Log::info("AJAX: Found " . $doctors->count() . " doctors for section ID: {$sectionId}", ['doctors_data' => $doctors->toArray()]);
            return response()->json(['doctors' => $doctors]);

        } catch (\Exception $e) {
            Log::error("Error in getDoctorsBySection for section ID {$sectionId}: " . $e->getMessage(), ['trace' => $e->getTraceAsString(), 'sql' => $e instanceof \Illuminate\Database\QueryException ? $e->getSql() : 'N/A']);
            return response()->json(['error' => 'حدث خطأ أثناء جلب الأطباء.', 'details' => $e->getMessage()], 500);
        }
    }

    // ... (دوال store و getAvailableTimes كما هي من التعديل السابق) ...
    // تأكد من أنها لا تحتوي على نفس المشكلة إذا كانت تستخدم select مع join.
    public function store(CreatePatientAppointmentRequest $request)
    {
        $validatedData = $request->validated();
        Log::info("PatientSideAppointmentController@store: Attempting to store appointment.", $validatedData);
        DB::beginTransaction(); // تأكد من استيراد DB: use Illuminate\Support\Facades\DB;
        try {
            $appointmentDateTime = \Illuminate\Support\Carbon::parse($validatedData['selected_date'] . ' ' . $validatedData['selected_time']);

            $isSlotStillAvailable = !\App\Models\Appointment::where('doctor_id', $validatedData['doctor_id'])
                ->where('appointment', $appointmentDateTime)
                ->where('type', '!=', 'ملغي')
                ->exists();

            if (!$isSlotStillAvailable) {
                DB::rollBack();
                Log::warning("PatientSideAppointmentController@store: Slot no longer available.", $validatedData);
                return redirect()->back()->withInput()->with('error', 'عذراً، هذا الموعد تم حجزه للتو. يرجى اختيار وقت آخر.');
            }

            $doctorModel = \App\Models\Doctor::find($validatedData['doctor_id']);
            $dailyAppointmentCount = \App\Models\Appointment::where('doctor_id', $validatedData['doctor_id'])
                ->whereDate('appointment', $validatedData['selected_date'])
                ->where('type', '!=', 'ملغي')
                ->count();

            if ($doctorModel && $doctorModel->number_of_statements > 0 && $dailyAppointmentCount >= $doctorModel->number_of_statements) {
                DB::rollBack();
                Log::warning("PatientSideAppointmentController@store: Doctor reached daily limit.", $validatedData);
                return redirect()->back()->withInput()->with('error', 'عذراً، تم الوصول للحد الأقصى للمواعيد المسموح به لهذا اليوم للطبيب المختار.');
            }

            $appointment = \App\Models\Appointment::create([
                'doctor_id' => $validatedData['doctor_id'],
                'section_id' => $validatedData['section_id'],
                'patient_id' => \Illuminate\Support\Facades\Auth::guard('patient')->check() ? \Illuminate\Support\Facades\Auth::guard('patient')->id() : null,
                'name' => $validatedData['patient_name'],
                'email' => $validatedData['patient_email'],
                'phone' => $validatedData['patient_phone'],
                'appointment' => $appointmentDateTime,
                'notes' => $validatedData['notes'] ?? null,
                'type' => 'غير مؤكد'
            ]);

            DB::commit();
            Log::info("New appointment [ID: {$appointment->id}] booked successfully for doctor ID: {$appointment->doctor_id}");
            return redirect()->route('patient.appointment.success')
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

        $doctor = \App\Models\Doctor::with(['workingDays' => function ($query) {
            $query->where('active', true)->with('breaks');
        }])->find($doctorId);

        if (!$doctor) {
            return response()->json(['times' => [], 'message' => 'الطبيب المحدد غير موجود.'], 404);
        }

        try {
            $dayName = \Illuminate\Support\Carbon::parse($selectedDate)->format('l');
            $workingDay = $doctor->workingDays->firstWhere('day', $dayName);

            if (!$workingDay) {
                Log::info("AJAX: No working day found for Doctor ID: {$doctorId} on {$dayName} ({$selectedDate})");
                return response()->json(['times' => [], 'message' => 'الطبيب غير متاح في هذا اليوم (ليس يوم عمل).']);
            }

            $startTime = \Illuminate\Support\Carbon::parse($workingDay->start_time);
            $endTime = \Illuminate\Support\Carbon::parse($workingDay->end_time);
            $duration = (int) $workingDay->appointment_duration;

            if ($duration <= 0) {
                return response()->json(['times' => [], 'message' => 'خطأ في مدة الموعد للطبيب.'], 400);
            }

            $bookedTimes = \App\Models\Appointment::where('doctor_id', $doctorId)
                ->whereDate('appointment', $selectedDate)
                ->where('type', '!=', 'ملغي')
                ->pluck('appointment')
                ->map(fn($dt) => \Illuminate\Support\Carbon::parse($dt)->format('H:i'))
                ->toArray();

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

                if ($workingDay->relationLoaded('breaks') && $workingDay->breaks->isNotEmpty()) {
                    foreach ($workingDay->breaks as $break) {
                        $breakStart = \Illuminate\Support\Carbon::parse($break->start_time);
                        $breakEnd = \Illuminate\Support\Carbon::parse($break->end_time);
                        if ($slotStart->lt($breakEnd) && $slotEnd->gt($breakStart)) {
                            $isAvailable = false; break;
                        }
                    }
                }
                if (!$isAvailable) { $currentTime->addMinutes($duration); continue; }

                if (in_array($timeStr, $bookedTimes)) { $isAvailable = false; }

                $fullSlotStartDateTime = \Illuminate\Support\Carbon::parse($selectedDate . ' ' . $timeStr);
                if ($fullSlotStartDateTime->isPast()) {
                    $isAvailable = false;
                }

                if ($isAvailable) {
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
