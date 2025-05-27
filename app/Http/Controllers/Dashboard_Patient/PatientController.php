<?php

namespace App\Http\Controllers\Dashboard_Patient; // تأكد أن هذا هو الـ Namespace الصحيح

use App\Models\Ray;
use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\Message;
use App\Models\Section;
use App\Models\Appointment;
use App\Models\Laboratorie;
use Illuminate\Http\Request;
use App\Models\PatientAccount;
use App\Models\ReceiptAccount;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
// use App\Models\DoctorWorkingDay; // غير مستخدم في هذا الكنترولر حالياً
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Mail;
use App\Models\Notification as CustomNotification;
use App\Models\Patient; // لا تنسَ هذا إذا لم يكن مُستورداً
use App\Models\Prescription; // *** استيراد موديل الوصفة ***
use App\Mail\AppointmentCancelledByPatientToDoctor; // تأكد من وجود هذا الـ Mailable

class PatientController extends Controller
{
    public function __construct()
    {
        // تطبيق middleware المصادقة للمريض على جميع دوال هذا الكنترولر
        // أو يمكنك تحديد دوال معينة إذا لم تكن كلها محمية بنفس الـ guard
        $this->middleware('auth:patient');
    }

    /**
     * عرض لوحة التحكم الرئيسية للمريض مع ملخصات وتنبيهات.
     */
    public function dashboard()
    {
        $patient = Auth::guard('patient')->user();
        $patientId = $patient->id;
        $patientEmail = $patient->email; //  نحتاج إيميل المريض

        Log::info("Patient Dashboard: User {$patient->name} (ID: {$patientId}) accessed.");

        // --- حسابات البطاقات الإحصائية ---
        $totalDueInvoices = Invoice::where('patient_id', $patientId)
            ->where('invoice_status', 1) // افترض أن 1 = مستحقة
            ->sum(DB::raw('total_with_tax'));

        $upcomingAppointmentsCount = Appointment::where('patient_id', $patientId)
            ->where('appointment', '>=', now())
            ->whereIn('type', ['مؤكد', 'غير مؤكد'])
            ->count();

        $readyPrescriptionsCount = Prescription::where('patient_id', $patientId)
            ->where('status', \App\Models\Prescription::STATUS_READY_FOR_PICKUP) // تأكد من الثابت
            ->count();

        // *** حساب عدد رسائل الدردشة الجديدة مباشرة من جدول messages ***
        $unreadChatMessagesCount = Message::where('receiver_email', $patientEmail) // الرسائل الموجهة للمريض الحالي
            ->where('read', false) // التي لم تُقرأ بعد
            // (اختياري) يمكنك إضافة شرط للتأكد أن المرسل طبيب إذا أردت
            // ->whereIn('sender_email', function($query){
            //     $query->select('email')->from('doctors');
            // })
            ->count();
        Log::info("Patient Dashboard: Unread chat messages count for {$patientEmail}: {$unreadChatMessagesCount}");
        // *** نهاية حساب رسائل الدردشة الجديدة ***

        // --- حسابات التنبيهات الهامة ---
        $upcomingChronicRefill = Prescription::where('patient_id', $patientId)
            ->where('is_chronic_prescription', true)
            ->whereNotNull('next_refill_due_date')
            ->whereBetween('next_refill_due_date', [Carbon::today(), Carbon::today()->addDays(7)])
            ->whereNotIn('status', [Prescription::STATUS_REFILL_REQUESTED, Prescription::STATUS_CANCELLED_BY_DOCTOR, Prescription::STATUS_CANCELLED_BY_PATIENT, Prescription::STATUS_EXPIRED])
            ->orderBy('next_refill_due_date', 'asc')
            ->first();

        $imminentAppointment = Appointment::where('patient_id', $patientId)
            ->where('type', 'مؤكد')
            ->whereBetween('appointment', [Carbon::today()->startOfDay(), Carbon::tomorrow()->endOfDay()])
            ->with('doctor:id')
            ->orderBy('appointment', 'asc')
            ->first();

        $hasImportantAlerts = $upcomingChronicRefill || ($readyPrescriptionsCount > 0) || $imminentAppointment || ($unreadChatMessagesCount > 0);

        // --- قوائم العرض المختصر ---
        $latest_invoices = Invoice::with('Doctor:id')
            ->where('patient_id', $patientId)
            ->latest('invoice_date')
            ->take(5)
            ->get();

        $upcoming_appointments_list = Appointment::with(['doctor:id', 'section:id'])
            ->where('patient_id', $patientId)
            ->where('appointment', '>=', now())
            ->whereIn('type', ['مؤكد', 'غير مؤكد'])
            ->orderBy('appointment', 'asc')
            ->take(5)
            ->get();

        return view('Dashboard.dashboard_patient.dashboard', compact(
            'patient',
            'totalDueInvoices',
            'upcomingAppointmentsCount',
            'readyPrescriptionsCount',
            'unreadChatMessagesCount', //  <--- تمرير المتغير الجديد
            'upcomingChronicRefill',
            'imminentAppointment',
            'hasImportantAlerts',
            'latest_invoices',
            'upcoming_appointments_list'
        ));
    }


    public function invoices()
    {
        // استخدام auth()->id() أفضل إذا كنت تحتاج فقط الـ ID
        $invoices = Invoice::where('patient_id', auth()->id())->get();
        return view('Dashboard.dashboard_patient.invoices', compact('invoices'));
    }

    public function laboratories()
    {
        $laboratories = Laboratorie::where('patient_id', auth()->id())->get();
        return view('Dashboard.dashboard_patient.laboratories', compact('laboratories'));
    }

    public function viewLaboratories($id)
    {
        $laboratorie = Laboratorie::findOrFail($id); // استخدام findOrFail أفضل
        // التحقق من ملكية السجل للمريض الحالي
        if ($laboratorie->patient_id != auth()->id()) {
            // Log::warning("Patient ".auth()->id()." tried to access unauthorized laboratorie record {$id}.");
            // يمكنك توجيه لصفحة خطأ عامة أو 403 بدلاً من 404
            abort(403, 'غير مصرح لك بعرض هذا السجل.');
            // return redirect()->route('404'); // تأكد أن مسار 404 معرف أو استخدم abort(404)
        }
        // تأكد أن مسار الـ view مناسب للمريض وليس لموظف المختبر
        return view('Dashboard.dashboard_patient.view_laboratorie', compact('laboratorie')); // مسار view مقترح
    }

    public function rays()
    {
        $rays = Ray::where('patient_id', auth()->id())->get();
        return view('Dashboard.dashboard_patient.rays', compact('rays'));
    }

    public function viewRays($id)
    {
        $ray = Ray::findOrFail($id); // تغيير اسم المتغير ليكون مفرداً
        if ($ray->patient_id != auth()->id()) {
            // Log::warning("Patient ".auth()->id()." tried to access unauthorized ray record {$id}.");
            abort(403, 'غير مصرح لك بعرض هذا السجل.');
            // return redirect()->route('404');
        }
        // تأكد أن مسار الـ view مناسب للمريض وليس لموظف الأشعة
        return view('Dashboard.dashboard_patient.view_ray', compact('ray')); // مسار view مقترح
    }

    public function payments()
    {
        $payments = ReceiptAccount::where('patient_id', auth()->id())->get();
        return view('Dashboard.dashboard_patient.payments', compact('payments'));
    }

    // --- دوال عرض المواعيد للمريض (تبقى كما هي تقريباً، فقط Guard أوضح) ---
    public function upcomingAppointments(Request $request)
    {
        $patient = Auth::guard('patient')->user();

        $appointments = $patient->upcomingAppointments() // This scope should handle ->where('appointment', '>=', now())->whereIn('type', [Appointment::STATUS_CONFIRMED, Appointment::STATUS_PENDING])
            ->with([
                'doctor' => function ($query) {
                    // Select id and name. If using spatie/laravel-translatable for Doctor name,
                    // withTranslation() will handle loading translations for 'name'.
                    // Ensure 'name' is in translatedAttributes in Doctor model.
                    $query->withTranslation()->select('doctors.id'); // Select from doctors table to avoid ambiguity if joining
                },
                'section' => function ($query) {
                    // Similar to doctor, ensure 'name' is selected or handled by withTranslation()
                    $query->withTranslation()->select('sections.id');
                }
            ])
            ->orderBy('appointment', 'asc') // Ensure order
            ->paginate(config('pagination.appointments_patient_upcoming', 9)); // e.g., 9 for 3x3 grid

        return view('Dashboard.Patients.appointments.upcoming', compact('patient', 'appointments'));
    }


    public function pastAppointments(Request $request)
    {
        $patient = Auth::guard('patient')->user();

        $appointments = $patient->pastAppointments()
            ->with([
                'doctor' => function ($q_doc) {
                    $q_doc->withTranslation()->select('id');
                },
                'section' => function ($q_sec) {
                    $q_sec->withTranslation()->select('id');
                }
            ])
            ->paginate(config('pagination.appointments_patient', 10));

        // $appointmentsByMonth = $appointments->groupBy(function ($appointment) {
        //     return Carbon::parse($appointment->appointment)->translatedFormat('F Y');
        // });

        return view('Dashboard.Patients.appointments.past', compact('patient', 'appointments' /*, 'appointmentsByMonth'*/));
    }

    public function cancelAppointmentByPatient(Request $request, Appointment $appointment)
    {
        $patient = Auth::guard('patient')->user();

        // 1. التحقق من ملكية الموعد للمريض الحالي
        if ($appointment->patient_id !== $patient->id) {
            Log::warning("AUTH_FAIL_CANCEL_PATIENT: Patient {$patient->id} attempted to cancel appointment {$appointment->id} not belonging to them (belongs to patient {$appointment->patient_id}).");
            return response()->json(['message' => 'غير مصرح لك بإلغاء هذا الموعد.'], 403);
        }

        // 2. التحقق من أن الموعد قابل للإلغاء (حالته 'غير مؤكد' أو 'مؤكد')
        $cancellableTypes = [Appointment::STATUS_PENDING, Appointment::STATUS_CONFIRMED];
        if (!in_array($appointment->type, $cancellableTypes, true)) {
            $message = 'لا يمكن إلغاء هذا الموعد لأن حالته الحالية هي: (' . ($appointment->status_display ?? $appointment->type) . ').';
            Log::info("CANCEL_DENIED_STATUS_PATIENT: Patient {$patient->id} failed to cancel appointment {$appointment->id}. Status: '{$appointment->type}'.");
            return response()->json(['message' => $message], 422);
        }

        // 3. (اختياري، ولكنه مهم كما ناقشنا) التحقق من الوقت المتبقي للموعد
        // يمكنك إضافة قيمة config لعدد الساعات المسموح بها للإلغاء قبل الموعد
        $cancellationWindowHours = config('settings.patient_appointment_cancellation_window_hours', 24); // مثال: 24 ساعة
        if (Carbon::parse($appointment->appointment)->isPast() || Carbon::parse($appointment->appointment)->diffInHours(now(), false) < $cancellationWindowHours) {
            // diffInHours(now(), false)  تعطي فرقاً سالباً إذا كان الموعد في المستقبل
            // لذا، إذا كان الفرق أقل من 24 ساعة (أو سالب، أي في الماضي القريب جداً ولم يُعالج بعد كفائت)
            if (Carbon::parse($appointment->appointment)->isFuture() && Carbon::parse($appointment->appointment)->diffInHours(now()) < $cancellationWindowHours) {
                $errorMessage = "لا يمكن إلغاء الموعد قبل أقل من {$cancellationWindowHours} ساعة من وقته المحدد. يرجى التواصل مباشرة مع العيادة إذا لزم الأمر.";
                Log::info("CANCEL_DENIED_TIME_PATIENT: Patient {$patient->id} for appt {$appointment->id}. Appt time: {$appointment->appointment}, Window: {$cancellationWindowHours}h.");
                return response()->json(['message' => $errorMessage], 422);
            }
        }
        // --- نهاية التحقق من الوقت ---

        DB::beginTransaction();
        try {
            $oldType = $appointment->type;

            // تحديث الحقول الأساسية فقط
            $appointment->type = Appointment::STATUS_CANCELLED; //  قيمته يجب أن تكون 'ملغي' matching ENUM

            // (مهم) إذا أردت تسجيل من قام بالإلغاء، أضف أعمدة للجدول والموديل
            // $appointment->cancellation_reason = $request->input('reason_patient', 'تم الإلغاء بواسطة المريض عبر البوابة.');
            // $appointment->cancelled_by_user_type = Patient::class;
            // $appointment->cancelled_by_user_id = $patient->id;
            // $appointment->cancelled_at = now();

            $appointment->saveOrFail(); // استخدام saveOrFail أفضل

            Log::info("APPOINTMENT_CANCELLED_BY_PATIENT: ID {$appointment->id}, NewType: '{$appointment->type}' (Old: '{$oldType}'), ByPatientID: {$patient->id}.");

            // إرسال إشعار للطبيب (بافتراض أن Mailable موجود ويعمل)
            if ($appointment->doctor && $appointment->doctor->email) {
                try {
                    Mail::to($appointment->doctor->email)
                        ->send(new AppointmentCancelledByPatientToDoctor($appointment, $patient));
                    Log::info("Mail_Sent: Patient cancellation email to Doctor {$appointment->doctor->email} for appt ID {$appointment->id}.");
                } catch (\Exception $e) {
                    Log::error("Mail_Error: Failed sending patient cancellation email to doctor for appt ID {$appointment->id}: " . $e->getMessage());
                }
            } else {
                Log::warning("Mail_Warn: Doctor or Doctor's email not found for appt ID {$appointment->id} (Patient Cancel).");
            }

            DB::commit();
            $successMessage = 'تم إلغاء موعدك بنجاح. سيتم إشعار الطبيب بذلك.';

            return response()->json([
                'message' => $successMessage,
                'appointment_id' => $appointment->id, // لإمكانية تحديث الـ UI
                'new_status' => $appointment->type,    // الحالة الجديدة => 'ملغي'
                'status_display' => $appointment->status_display //  النص المقابل للحالة الجديدة
            ]);
        } catch (\Illuminate\Database\QueryException $qe) {
            DB::rollBack();
            Log::critical("CRITICAL_DB_PATIENT_CANCEL_ERROR: PatientID {$patient->id} for ApptID {$appointment->id}: " . $qe->getMessage(), [
                'sql' => $qe->getSql(),
                'bindings' => $qe->getBindings()
            ]);
            return response()->json(['message' => 'حدث خطأ أثناء محاولة تحديث حالة الموعد في قاعدة البيانات. يرجى المحاولة مرة أخرى.'], 500);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::critical("CRITICAL_GENERAL_PATIENT_CANCEL_ERROR: PatientID {$patient->id} for ApptID {$appointment->id}: " . $e->getMessage(), ['trace_short' => Str::limit($e->getTraceAsString(), 1000)]);
            return response()->json(['message' => 'حدث خطأ غير متوقع أثناء إلغاء الموعد. تم تسجيل الخطأ.'], 500);
        }
    }
    // دوال create و store للمواعيد تبدو جيدة كما هي، فقط تأكد من استخدام Guard المريض
    public function create()
    {
        $sections = Section::orderByTranslation('name')->get(); // لتحميل الترجمة إذا كانت متاحة
        $doctors = Doctor::orderByTranslation('name')->get(); //  أو فلتر الأطباء بناءً على القسم عند الاختيار
        return view('Dashboard.Patients.appointments.create', compact('sections', 'doctors'));
    }

    public function store(Request $request) // يفضل استخدام FormRequest مخصص هنا
    {
        Log::info('Patient appointment store request received: ', $request->all());
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'section_id' => 'required|exists:sections,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i', // تأكد أن هذا الفورمات يتوافق مع ما يرسله الـ time picker
            'notes' => 'nullable|string|max:1000'
        ], [
            'doctor_id.required' => 'حقل الطبيب مطلوب.',
            'section_id.required' => 'حقل القسم مطلوب.',
            'appointment_date.required' => 'حقل تاريخ الموعد مطلوب.',
            'appointment_date.after_or_equal' => 'تاريخ الموعد يجب أن يكون اليوم أو في المستقبل.',
            'appointment_time.required' => 'حقل وقت الموعد مطلوب.',
            'appointment_time.date_format' => 'صيغة وقت الموعد غير صحيحة (مثال: 14:30).',
        ]);

        // (اختياري ولكنه مهم) التحقق من تضارب المواعيد أو أن الوقت متاح فعلاً للطبيب
        // هذا يتطلب منطقاً إضافياً للتحقق من جدول الطبيب
        // ...

        $patient = Auth::guard('patient')->user();

        try {
            $appointmentDateTime = Carbon::parse($validated['appointment_date'] . ' ' . $validated['appointment_time']);

            $appointment = Appointment::create([
                'doctor_id' => $validated['doctor_id'],
                'section_id' => $validated['section_id'],
                'patient_id' => $patient->id,
                'name' => $patient->name,
                'email' => $patient->email,
                'phone' => $patient->Phone,
                'appointment' => $appointmentDateTime,
                'notes' => $validated['notes'],
                'type' => Appointment::STATUS_PENDING ?? 'غير مؤكد' // استخدام الثابت إذا كان معرفاً
            ]);

            Log::info("Appointment created with ID: {$appointment->id} for patient {$patient->id} with doctor {$validated['doctor_id']}");

            // TODO: إرسال إشعار للطبيب/الاستقبال بالموعد الجديد
            // إذا كنت تستخدم AppointmentCreated Mailable (كما نفترض من قبل)
            // if ($appointment->doctor && $appointment->doctor->email) {
            //     Mail::to($appointment->doctor->email)->send(new \App\Mail\AppointmentCreated($appointment));
            // }

            return redirect()->route('patient.appointment.success') // افترض أن هذا مسار لصفحة نجاح
                ->with('success_message', 'تم إرسال طلب حجز الموعد بنجاح. سيتم التواصل معك للتأكيد قريباً.');
        } catch (\Exception $e) {
            Log::error("Error creating appointment for patient {$patient->id}: " . $e->getMessage(), ['trace' => $e->getTraceAsString(), 'request_data' => $request->all()]);
            return redirect()->back()
                ->withInput() // لإعادة ملء الفورم
                ->with('error', 'حدث خطأ أثناء حجز الموعد: ' . $e->getMessage());
        }
    }
}
