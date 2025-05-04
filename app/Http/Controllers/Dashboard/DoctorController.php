<?php

namespace App\Http\Controllers\Dashboard; // أو Namespace الخاص بـ Controller الطبيب

use App\Models\Doctor;
use Twilio\Rest\Client;
use App\Models\Appointment;
use App\Models\DoctorBreak;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\DoctorWorkingDay;
use App\Mail\AppointmentCompleted;

// --- استيرادات الإشعارات ---
use Illuminate\Support\Facades\DB;
// *** إنشاء Mailable جديد أو استخدام نفس Mailable الإلغاء مع تعديل النص ***
use Illuminate\Support\Facades\Log;
// use App\Mail\AppointmentCancelled; // أو استخدام الموجود
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Twilio\Exceptions\TwilioException;
use App\Http\Requests\StoreDoctorsRequest;
use App\Http\Requests\UpdateDoctorsRequest;
use App\Interfaces\Doctors\DoctorRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Mail\AppointmentCancelledByDoctor; // <-- Mailable جديد مقترح

class DoctorController extends Controller
{

    private $Doctors; // اسم المتغير يبدأ بحرف صغير عادة: private $doctorRepository;

    public function __construct(DoctorRepositoryInterface $Doctors) // اسم البارامتر يبدأ بحرف صغير: $doctorRepository
    {
        $this->Doctors = $Doctors; // $this->doctorRepository = $doctorRepository;
    }


    public function index()
    {
        return $this->Doctors->index();
    }


    public function create()
    {
        return $this->Doctors->create();
    }


    public function store(StoreDoctorsRequest $request)
    {
        return $this->Doctors->store($request);
    }


    public function show($id)
    {
        // لا يوجد تطبيق لهذه الدالة حالياً، يمكنك تركها فارغة أو إزالتها إذا لم تستخدم
    }


    public function edit($id)
    {
        return $this->Doctors->edit($id);
    }


    public function update(UpdateDoctorsRequest $request)
    {
        return $this->Doctors->update($request);
    }


    public function destroy(Request $request) // جيد استخدام Request هنا
    {
        return $this->Doctors->destroy($request);
    }

    public function update_password(Request $request)
    {
        // التحقق من الصحة يمكن أن يكون في Form Request مخصص
        $validated = $request->validate([
            // استخدام rules أفضل من validate مباشرة في الـ controller
            'password' => 'required|min:6|confirmed',
            // لا حاجة للتحقق من password_confirmation هنا، confirmed تقوم بذلك
            // 'password_confirmation' => 'required|min:6' // يمكن إزالته
            'id' => 'required|exists:doctors,id' // إضافة تحقق من وجود الطبيب
        ]);

        return $this->Doctors->update_password($request);
    }

    public function update_status(Request $request)
    {
        $validated = $request->validate([
            'status' => 'required|boolean', // استخدام boolean أفضل لـ 0 و 1
            'id' => 'required|exists:doctors,id' // إضافة تحقق من وجود الطبيب
        ]);
        return $this->Doctors->update_status($request);
    }

    public function editSchedule($id)
    {

        // استدعاء دالة جديدة في الـ Repository لجلب بيانات الطبيب والجدول
        return $this->Doctors->getScheduleForEdit($id);
        if ($success) {
            // إعادة التوجيه لصفحة *عرض* الجدول بعد الحفظ
            return redirect()->route('doctor.schedule.show')->with('success', 'تم تحديث جدول العمل بنجاح.'); // <--- تعديل اسم المسار
        } else {
            // العودة لصفحة *تعديل* الجدول مع الأخطاء
            return redirect()->route('doctors.schedule.edit', $id)->withInput()->withErrors(['schedule_error' => session('error', 'فشل تحديث الجدول.')]); // <--- تعديل اسم المسار وتمرير الخطأ// سننشئ هذه الدالة
        }
    }

    public function editSchedulee(Doctor $doctor) // استخدام Route Model Binding
    {
        // --- التحقق من الصلاحية: هل المستخدم الحالي هو الطبيب المعني؟ ---
        // مثال إذا كانت الـ routes ضمن مجموعة الطبيب
        // $doctor = Auth::guard('doctor')->user();
        // أو إذا كان ID الطبيب يمرر عبر الـ route parameter
        // abort_unless(Auth::id() === $doctor->user_id || Auth::user()->isAdmin(), 403, 'غير مصرح لك بعرض هذا الجدول.'); // مثال: تحقق من أن المستخدم الحالي هو مالك البروفايل أو أدمن

        // --- جلب أيام العمل النشطة فقط مع استراحاتها ---
        $doctor->load(['workingDays' => function ($query) {
            $query->where('active', true) // جلب الأيام النشطة فقط
                ->with('breaks') // تحميل الاستراحات المتعلقة بالأيام النشطة
                ->orderByRaw("FIELD(day, 'Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday')"); // للترتيب الصحيح
        }]);

        // --- تجهيز البيانات للعرض (فقط للأيام النشطة) ---
        $workingHoursData = [];
        $activeDaysList = $doctor->workingDays->pluck('day')->toArray(); // قائمة بأسماء الأيام النشطة فقط

        foreach ($doctor->workingDays as $workingDay) { // الآن الحلقة تتم فقط على الأيام النشطة التي تم جلبها
            $workingHoursData[$workingDay->day] = [
                // 'active' لم يعد ضرورياً هنا لأننا نعرض فقط النشطة، لكن يمكن إبقاؤه للقراءة الواضحة
                'active' => $workingDay->active, // سيكون دائماً true هنا
                'start_time' => $workingDay->start_time ? Carbon::parse($workingDay->start_time)->format('H:i') : '09:00',
                'end_time' => $workingDay->end_time ? Carbon::parse($workingDay->end_time)->format('H:i') : '17:00',
                'appointment_duration' => $workingDay->appointment_duration ?? 30,
                // تحضير بيانات الاستراحات للفورم (محولة لمصفوفة هنا)
                'breaks' => $workingDay->breaks->map(function ($break) {
                    return [
                        'start_time' => Carbon::parse($break->start_time)->format('H:i'),
                        'end_time' => Carbon::parse($break->end_time)->format('H:i'),
                        'reason' => $break->reason
                    ];
                })->toArray() // <<<=== تم التحويل لمصفوفة هنا
            ];
        }

        return view('Dashboard.Doctors.schedule.edit_schedule', compact(
            'doctor',
            'workingHoursData', // يحتوي فقط بيانات الأيام النشطة
            'activeDaysList'   // قائمة بأسماء الأيام النشطة ليتم عرضها فقط
        ));
    }
    public function updateSchedulee(Request $request, Doctor $doctor)
    {
        // --- جلب قائمة الأيام التي يُسمح للطبيب بتعديلها (النشطة فقط في قاعدة البيانات) ---
        $allowedActiveDays = $doctor->workingDays()->where('active', true)->pluck('day')->toArray();
        Log::info("Allowed active days for Doctor ID {$doctor->id}: ", $allowedActiveDays);

        // --- بناء قواعد التحقق ديناميكياً فقط للأيام المسموح بها والتي تم إرسالها ---
        $rules = [];
        $submittedDaysData = $request->input('days', []);
        Log::info("Submitted data for Doctor ID {$doctor->id}: ", $submittedDaysData);

        foreach ($submittedDaysData as $day => $data) {
            // تحقق فقط إذا كان اليوم المُرسل ضمن الأيام النشطة المسموح للطبيب بتعديلها
            if (in_array($day, $allowedActiveDays)) {
                Log::debug("Validating submitted active day: {$day}");
                $rules["days.$day.start_time"] = 'required|date_format:H:i';
                // التحقق أن النهاية بعد البداية
                $rules["days.$day.end_time"] = ['required', 'date_format:H:i', function ($attribute, $value, $fail) use ($day, $submittedDaysData) {
                    $startTime = $submittedDaysData[$day]['start_time'] ?? null;
                    if ($startTime && $value && strtotime($value . ':00') <= strtotime($startTime . ':00')) { // مقارنة كـ timestamp
                        $dayNameTranslated = trans('doctors.days.' . strtolower($day)) ?? $day;
                        $fail("وقت الانتهاء لليوم '{$dayNameTranslated}' يجب أن يكون بعد وقت البدء.");
                    }
                }];
                $rules["days.$day.appointment_duration"] = 'required|integer|min:5|max:120'; // الحد الأدنى 5 دقائق

                // قواعد التحقق للاستراحات (مثال)
                $rules["days.$day.breaks"] = 'nullable|array'; // يمكن أن تكون فارغة أو مصفوفة
                $rules["days.$day.breaks.*.start_time"] = 'required|date_format:H:i';
                $rules["days.$day.breaks.*.end_time"] = ['required', 'date_format:H:i', function ($attribute, $value, $fail) use ($day, $submittedDaysData, $request) {
                    // الحصول على index الاستراحة من المفتاح: days.Saturday.breaks.0.end_time -> 0
                    $parts = explode('.', $attribute);
                    $index = $parts[3] ?? null;
                    if ($index === null) return; // Skip if index not found

                    $breakStartTime = $request->input("days.$day.breaks.$index.start_time");
                    $dayStartTime = $submittedDaysData[$day]['start_time'] ?? null;
                    $dayEndTime = $submittedDaysData[$day]['end_time'] ?? null;

                    if ($breakStartTime && $value && strtotime($value . ':00') <= strtotime($breakStartTime . ':00')) {
                        $fail("وقت نهاية الاستراحة ($index) يجب أن يكون بعد وقت بدايتها.");
                    }
                    // (اختياري) التحقق من أن الاستراحة ضمن ساعات عمل اليوم
                    if ($dayStartTime && $dayEndTime && $breakStartTime && $value) {
                        if (strtotime($breakStartTime . ':00') < strtotime($dayStartTime . ':00') || strtotime($value . ':00') > strtotime($dayEndTime . ':00')) {
                            //  $fail("فترة الاستراحة ($index) يجب أن تكون ضمن ساعات العمل المحددة لليوم.");
                        }
                    }
                }];
                $rules["days.$day.breaks.*.reason"] = 'nullable|string|max:100'; // سبب اختياري
            } else {
                Log::warning("Doctor ID {$doctor->id} attempted to submit data for non-active day '{$day}'. Skipping.");
                // لا نضع قواعد تحقق لليوم غير المسموح به
            }
        }


        // تنفيذ التحقق
        $validated = $request->validate($rules);
        Log::info("Validation successful for Doctor ID {$doctor->id}. Validated data keys: ", array_keys($validated['days'] ?? []));

        // --- تحديث البيانات (داخل Transaction للأمان) ---
        DB::beginTransaction();
        try {

            // الحصول على البيانات التي تم التحقق منها فقط
            $validDaysData = $validated['days'] ?? [];

            foreach ($validDaysData as $day => $data) {
                // تحقق مرة أخرى (احتياطي) أننا نحدث فقط الأيام المسموحة
                if (in_array($day, $allowedActiveDays)) {
                    Log::debug("Updating working day: {$day} for Doctor ID {$doctor->id}");

                    // تحديث أو إنشاء (يفضل update بما أننا نضمن أنها نشطة)
                    $workingDay = $doctor->workingDays()->where('day', $day)->where('active', true)->first();

                    if ($workingDay) {

                        $workingDay->update([
                            // --- !! تعديل مهم !! ---
                            // تحديث الحقول المسموحة فقط
                            'start_time' => $data['start_time'] . ':00', // إضافة الثواني
                            'end_time' => $data['end_time'] . ':00',     // إضافة الثواني
                            'appointment_duration' => $data['appointment_duration'],
                            // لا تقم بتحديث حقل 'active' هنا أبداً!
                        ]);

                        Log::info("Updated details for working day ID: {$workingDay->id} ({$day})");

                        // --- تحديث الاستراحات ---
                        // $this->syncBreaks($workingDay, $data['breaks'] ?? []);
                    } else {
                        // هذا لا يجب أن يحدث لأننا نتحقق من allowedActiveDays، لكنه تحذير جيد
                        Log::warning("Attempted to update non-existent or inactive working day '{$day}' for Doctor ID {$doctor->id}. This shouldn't happen.");
                    }

                }
            }

            DB::commit();
            Log::info("--- Schedule update committed successfully for Doctor ID: {$doctor->id} ---");
            return redirect()->route('doctor.schedule.show')->with('success', 'تم تحديث ساعات العمل بنجاح.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::warning("--- Validation Exception during schedule update for Doctor ID: {$doctor->id} ---", ['errors' => $e->errors()]);
            // سيعيد Laravel التوجيه تلقائيًا مع الأخطاء عند استخدام validate()
            // return redirect()->back()->withInput()->withErrors($e->errors()); // غير ضروري عادةً
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("--- CRITICAL Error updating schedule for Doctor ID: {$doctor->id}. Error: " . $e->getMessage() . " ---", ['trace' => $e->getTraceAsString()]);
            return redirect()->back()->withInput()->with('error', 'حدث خطأ غير متوقع أثناء تحديث الجدول. يرجى المحاولة مرة أخرى أو التواصل مع الدعم.');
        }
    }

    // protected function syncBreaks(DoctorBreak $workingDay, array $breaksData)
    // {
    //     Log::debug("Syncing breaks for Working Day ID: {$workingDay->id}");
    //     // 1. حذف جميع الاستراحات الحالية لهذا اليوم
    //     $workingDay->breaks()->delete();
    //     Log::debug("Deleted existing breaks for Working Day ID: {$workingDay->id}");

    //     // 2. إضافة الاستراحات الجديدة من البيانات المرسلة
    //     $newBreaks = [];
    //     foreach ($breaksData as $index => $break) {
    //         if (!empty($break['start_time']) && !empty($break['end_time'])) {
    //             // (اختياري) تحقق إضافي أن النهاية بعد البداية قبل الحفظ
    //             if (strtotime($break['end_time'] . ':00') > strtotime($break['start_time'] . ':00')) {
    //                 $newBreaks[] = new DoctorBreak([ // تأكد من اسم الموديل الصحيح
    //                     'start_time' => $break['start_time'] . ':00',
    //                     'end_time' => $break['end_time'] . ':00',
    //                     'reason' => $break['reason'] ?? null,
    //                     // doctor_working_day_id سيتم تعيينه تلقائيًا بواسطة العلاقة
    //                 ]);
    //                 Log::debug("Prepared new break #{$index}: {$break['start_time']} - {$break['end_time']}");
    //             } else {
    //                 Log::warning("Skipped invalid break #{$index} for Working Day ID {$workingDay->id}: End time not after start time.");
    //             }
    //         } else {
    //             Log::debug("Skipped empty break data at index #{$index}");
    //         }
    //     }

    //     // 3. حفظ الاستراحات الجديدة دفعة واحدة إذا وجدت
    //     if (!empty($newBreaks)) {
    //         $workingDay->breaks()->saveMany($newBreaks);
    //         Log::info("Saved " . count($newBreaks) . " new breaks for Working Day ID: {$workingDay->id}");
    //     } else {
    //         Log::info("No new valid breaks to save for Working Day ID: {$workingDay->id}");
    //     }
    // }



    public function showSchedule()
    {
        $doctor = Auth::guard('doctor')->user();
        if (!$doctor) {
            abort(404);
        }

        // جلب أيام العمل والاستراحات من الريبو أو مباشرة
        // استخدام دالة getScheduleForEdit مناسبة هنا أيضاً لأنها تجهز البيانات
        // أو يمكنك عمل استعلام مباشر هنا
        $workingDays = $doctor->workingDays()->with('breaks')->get(); // جلب البيانات

        // تحضير البيانات للعرض (نفس منطق getScheduleForEdit) - أو تمرير workingDays مباشرة
        $daysOrder = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        $workingDaysCollection = collect($workingDays); // تحويل لمجموعة لتسهيل البحث

        return view('Dashboard.Doctors.schedule.show_schedule', compact('doctor', 'workingDaysCollection')); // عرض الـ view الجديد
    }

    public function updateSchedule(Request $request, $id)
    {
        // (اختياري) التحقق من صحة البيانات
        $validated = $request->validate([
            'days' => 'required|array',
            'days.*.start_time' => 'nullable|required_if:days.*.active,on|date_format:H:i',
            'days.*.end_time' => 'nullable|required_if:days.*.active,on|date_format:H:i|after:days.*.start_time',
            'days.*.appointment_duration' => 'nullable|required_if:days.*.active,on|integer|min:5',
            'days.*.active' => 'nullable|string',
        ]);
        $success = $this->Doctors->updateWorkingHours($request, $id);

        if ($success) {
            // رسالة النجاح تم وضعها في الـ Repository
            return redirect()->route('doctors.schedule.edit', $id);
        } else {
            // رسالة الخطأ تم وضعها في الـ Repository
            // العودة للصفحة السابقة مع الأخطاء والبيانات القديمة
            return redirect()->back()->withInput();
        }

        // استدعاء ميثود الـ Repository لتنفيذ التحديث
        try {
            $this->Doctors->updateWorkingHours($request, $id);
        } catch (\Exception $e) {
            // Log أو التعامل مع الخطأ
        }

        // إعادة التوجيه إلى صفحة *عرض* جدول العمل مرة أخرى (أو قائمة الأطباء)
        return redirect()->route('doctors.schedule.edit', $id)->with('success', 'تم تحديث جدول العمل بنجاح.');
        // أو
        // return redirect()->route('Doctors.index')->with('success', 'تم تحديث جدول عمل الطبيب ' . $doctor->name);
    }
    public function myAppointments(Request $request)
    {
        $doctorId = Auth::guard('doctor')->id();
        $query = Appointment::where('doctor_id', $doctorId)
            ->where('type', 'مؤكد')
            ->where('appointment', '>=', now())
            ->with('section') // قد تحتاج 'patient' إذا أردت عرض بياناته هنا
            ->orderBy('appointment', 'asc');

        $appointments = $query->paginate(12);
        return view('Dashboard.Doctors.appointments.my_appointments', compact('appointments'));
    }

    /**
     * إلغاء موعد محدد بواسطة الطبيب.
     * PATCH /doctor/my-appointments/{appointment}/cancel
     */
    public function cancelAppointment(Request $request, Appointment $appointment)
    {
        Log::info("Doctor ID: " . Auth::guard('doctor')->id() . " attempting to cancel appointment ID: {$appointment->id}");

        // 1. التحقق من الصلاحية
        if ($appointment->doctor_id !== Auth::guard('doctor')->id()) {
            Log::warning("Authorization failed: Doctor " . Auth::guard('doctor')->id() . " tried to cancel appointment {$appointment->id} of another doctor.");
            return response()->json(['message' => 'غير مصرح لك بإلغاء هذا الموعد.'], 403);
        }

        // 2. التأكد أن الموعد ليس منتهياً أو ملغياً بالفعل
        if (in_array($appointment->type, ['منتهي', 'ملغي'])) {
            Log::warning("Cannot cancel appointment ID: {$appointment->id}. Current status: {$appointment->type}");
            return response()->json(['message' => 'لا يمكن إلغاء هذا الموعد (الحالة: ' . $appointment->type . ').'], 422);
        }

        // 3. (اختياري) الحصول على سبب الإلغاء (يمكن إضافة حقل في الواجهة إذا لزم الأمر)
        $cancelReason = $request->input('reason', 'تم الإلغاء من قبل الطبيب لظرف طارئ'); // سبب افتراضي

        // 4. تحديث الحالة إلى "ملغي"
        try {
            // تحميل علاقة المريض للإشعار (إذا لم تكن محملة بالفعل)
            $appointment->loadMissing('patient'); // استخدام loadMissing لتحميلها فقط إذا لم تكن موجودة

            $appointment->update(['type' => 'ملغي']);
            Log::info("Appointment ID {$appointment->id} successfully cancelled by Doctor ID: " . Auth::guard('doctor')->id());

            // --- *** إرسال إشعارات الإلغاء للمريض *** ---
            $this->sendDoctorCancellationNotifications($appointment, $cancelReason); // استدعاء دالة الإشعارات

            // إرجاع استجابة نجاح للـ AJAX
            return response()->json([
                'message' => 'تم إلغاء الموعد بنجاح وإبلاغ المريض!', // تعديل الرسالة
                'new_status' => 'ملغي',
                'appointment_id' => $appointment->id
            ]);
        } catch (\Exception $e) {
            Log::error("Error cancelling appointment ID {$appointment->id} by doctor: " . $e->getMessage());
            return response()->json(['message' => 'حدث خطأ أثناء إلغاء الموعد.'], 500);
        }
    } // نهاية cancelAppointment

    /**
     * وضع علامة اكتمال على الموعد (بواسطة الطبيب).
     * PATCH /doctor/my-appointments/{appointment}/complete
     */
    public function completeAppointment(Request $request, Appointment $appointment)
    {
        // 1. التحقق من الصلاحية
        if ($appointment->doctor_id !== Auth::guard('doctor')->id()) {
            return response()->json(['message' => 'غير مصرح لك بتعديل هذا الموعد.'], 403);
        }

        // 2. التأكد من أن الموعد مؤكد
        if ($appointment->type !== 'مؤكد') {
            return response()->json(['message' => 'يمكن فقط إكمال المواعيد المؤكدة.'], 422);
        }

        // 3. التحقق من أن وقت الموعد قد حان
        if (!$appointment->appointment || Carbon::parse($appointment->appointment)->isFuture()) {
            return response()->json(['message' => 'لا يمكن تحديد اكتمال موعد لم يبدأ وقته بعد.'], 422);
        }

        // 4. تحديث الحالة إلى "منتهي"
        try {
            // تحميل علاقة المريض للإشعار
            $appointment->loadMissing('patient');

            $appointment->update(['type' => 'منتهي']);
            Log::info("Appointment ID {$appointment->id} marked as completed by Doctor ID: " . Auth::guard('doctor')->id());

            // --- *** إرسال إشعارات الاكتمال للمريض *** ---
            $this->sendCompletionNotifications($appointment); // استدعاء دالة الإشعارات

            return response()->json([
                'message' => 'تم تحديد الموعد كمكتمل بنجاح!',
                'new_status' => 'منتهي',
                'appointment_id' => $appointment->id
            ]);
        } catch (\Exception $e) {
            Log::error("Error completing appointment ID {$appointment->id}: " . $e->getMessage());
            return response()->json(['message' => 'حدث خطأ أثناء تحديد اكتمال الموعد.'], 500);
        }
    } // نهاية completeAppointment

    protected function sendCompletionNotifications(Appointment $appointment)
    {
        try {
            // تأكد من تحميل علاقة الطبيب أيضاً إذا لم تكن محملة
            $appointment->loadMissing('doctor');

            $patientName = $appointment->patient->name ?? $appointment->name;
            $doctorName = $appointment->doctor->name ?? 'طبيبك';

            // 1. إرسال بريد للمريض
            $patientEmail = $appointment->patient->email ?? $appointment->email;
            if ($patientEmail) {
                Mail::to($patientEmail)->send(new AppointmentCompleted($patientName, $doctorName, $appointment));
                Log::info("Completion email sent to patient: {$patientEmail} for appt ID: {$appointment->id}");
            } else {
                Log::warning("Cannot send completion email for appt ID: {$appointment->id}. Email missing.");
            }

            // 2. إرسال SMS للمريض (رسالة شكر بسيطة)
            $receiverNumber = $appointment->patient->Phone ?? $appointment->phone;
            if ($receiverNumber) {
                $message = "عزيزي " . $patientName . "، نشكرك على زيارتك لعيادة د. " . $doctorName . " اليوم. نتمنى لك دوام الصحة.";
                $this->sendTwilioSms($receiverNumber, $message, $appointment->id, 'completion');
            } else {
                Log::warning("Cannot send completion SMS for appt ID: {$appointment->id}. Phone missing.");
            }
        } catch (\Exception $e) {
            Log::error("Failed sending completion notifications for appt ID: {$appointment->id}. Error: " . $e->getMessage());
            // لا توقف العملية، لكن سجل الخطأ
            // لا تضع session()->flash هنا لأن هذا قد يُستدعى من AJAX
        }
    }


    // ================================================================
    //  *** الدوال المساعدة لإرسال الإشعارات (داخل DoctorController) ***
    // ================================================================


    /**
     * إرسال إشعارات إلغاء الموعد (عند إلغاء الطبيب).
     */
    protected function sendDoctorCancellationNotifications(Appointment $appointment, $reason)
    {
        try {
            $patientName = $appointment->patient->name ?? $appointment->name; // استخدم العلاقة الصحيحة
            $appointmentTime = $appointment->appointment ? $appointment->appointment->translatedFormat('l، d M Y - h:i A') : 'غير محدد';
            $doctorName = Auth::guard('doctor')->user()->name; // اسم الطبيب الحالي

            // 1. للمريض (بريد و/أو SMS)
            $patientEmail = $appointment->patient->email ?? $appointment->email; // العلاقة الصحيحة
            if ($patientEmail) {
                // *** استخدم Mailable جديد للإلغاء بواسطة الطبيب ***
                Mail::to($patientEmail)->send(new AppointmentCancelledByDoctor($patientName, $appointmentTime, $doctorName, $reason));
                Log::info("Doctor cancellation email sent to patient: {$patientEmail} for appt ID: {$appointment->id}");
            } else {
                Log::warning("Cannot send doctor cancellation email for appt ID: {$appointment->id}. Email missing.");
            }

            $patientPhone = $appointment->patient->Phone ?? $appointment->phone; // العلاقة واسم الحقل الصحيح
            if ($patientPhone) {
                $smsMessagePatient = "عزيزي " . $patientName . "، نعتذر لإلغاء موعدك مع د. " . $doctorName . " بتاريخ " . $appointmentTime . " بسبب: " . $reason . ". سيتم التواصل معك قريباً لترتيب موعد بديل.";
                $this->sendTwilioSms($patientPhone, $smsMessagePatient, $appointment->id, 'doctor_cancellation_patient'); // استدعاء الدالة المساعدة
            } else {
                Log::warning("Cannot send doctor cancellation SMS for appt ID: {$appointment->id}. Phone missing.");
            }

            // 2. (اختياري) إشعار للأدمن (إذا أردت إعلام الإدارة بإلغاء الطبيب)
            // $adminEmail = 'admin@youremail.com'; // أو جلب إيميل الأدمن
            // Mail::to($adminEmail)->send(new DoctorCancelledAppointmentNotification($appointment, $reason));

        } catch (\Exception $e) {
            Log::error("Failed sending doctor cancellation notifications for appt ID: {$appointment->id}. Error: " . $e->getMessage());
            // لا تضع session()->flash هنا لأن هذا يُستدعى من AJAX
        }
    }
    protected function sendTwilioSms($receiverNumber, $message, $appointmentId, $context = 'message')
    {
        // ... (نفس كود Twilio من الرد السابق مع التحققات وتسجيل Log) ...
        $account_sid = getenv("TWILIO_SID");
        $auth_token = getenv("TWILIO_TOKEN");
        $twilio_number = getenv("TWILIO_FROM");
        if (!$account_sid || !$auth_token || !$twilio_number) {
            Log::warning("Twilio credentials missing for {$context} SMS. Appt ID: {$appointmentId}.");
            return false;
        }
        if (!$receiverNumber) {
            Log::warning("Receiver number missing for {$context} SMS. Appt ID: {$appointmentId}.");
            return false;
        }
        try {
            $client = new Client($account_sid, $auth_token);
            $client->messages->create($receiverNumber, ['from' => $twilio_number, 'body' => $message]);
            Log::info("Twilio {$context} SMS sent to: {$receiverNumber} for appt ID: {$appointmentId}");
            return true;
        } catch (TwilioException $e) {
            Log::error("Twilio SMS failed for {$context} - Appt ID: {$appointmentId} - Twilio Error: " . $e->getMessage());
            return false;
        } catch (\Exception $e) {
            Log::error("General Exception sending Twilio SMS for {$context} - Appt ID: {$appointmentId} - Error: " . $e->getMessage());
            return false;
        }
    }
} // نهاية الكلاس DoctorController
