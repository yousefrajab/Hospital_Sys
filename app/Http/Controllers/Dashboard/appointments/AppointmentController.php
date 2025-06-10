<?php

namespace App\Http\Controllers\Dashboard\appointments;

// --- الاستيرادات الأساسية ---
use App\Models\Appointment;
use App\Models\Doctor; // استيراد موديل الطبيب (للإشعارات)
use App\Models\Patient; // استيراد موديل المريض (للإشعارات)
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

// --- استيرادات الإشعارات ---
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentConfirmation; // Mailable التأكيد (تأكد من وجوده)
use App\Mail\AppointmentCancelledByAdmin; // Mailable الإلغاء بواسطة الأدمن (تأكد من إنشائه)
use Twilio\Rest\Client;
use Twilio\Exceptions\TwilioException;

class AppointmentController extends Controller
{
    /**
     * عرض قائمة المواعيد غير المؤكدة (للأدمن).
     * GET /appointments
     */
    public function index(Request $request) // كانت للمواعيد "غير المؤكدة"
    {
        Log::info("Admin: Fetching PENDING appointments that need action.");
        // فترة سماح للمواعيد غير المؤكدة قبل أن نعتبرها "فائتة تماماً"
        $pendingGraceDays = intval(config('appointments.admin_pending_grace_days', 1)); // مثال: يوم واحد

        $query = Appointment::where('type', Appointment::STATUS_PENDING) // فقط "غير مؤكد"
            ->whereNotNull('appointment')
            // وقت الموعد لم يفت بعد بيوم كامل (كمثال)
            ->where('appointment', '>=', Carbon::now()->subDays($pendingGraceDays))
            // وأيضاً هي قادمة أو اليوم (لتجنب عرض ما فات بيوم ولكن لا يزال ضمن فترة السماح)
            // ->where('appointment', '>=', Carbon::today()->startOfDay()) //  هذا قد يكون صارماً جداً
            ->with(['doctor:id', 'section:id', 'patient:id'])
            ->orderBy('appointment', 'asc'); // الأقرب أولاً

        // فلاتر البحث (يمكنك تكييفها)
        if ($request->filled('search_pending')) {
            // ... منطق البحث ...
        }

        $appointments = $query->paginate(config('pagination.admin_pending', 10))->appends($request->query());

        return view('Dashboard.appointments.index', compact('appointments', 'request')); //  نفس الـ view
    }

    /**
     * عرض قائمة المواعيد المؤكدة والقادمة (بما في ذلك التي فات وقتها قليلاً وتحتاج تحديث حالة من الطبيب).
     */
    public function index2(Request $request) // كانت للمواعيد "المؤكدة"
    {
        Log::info("Admin: Fetching CONFIRMED & UPCOMING appointments.");
        // فترة سماح قصيرة بعد الموعد المؤكد ليقوم الطبيب بتحديث الحالة
        $confirmedActionGraceHours = intval(config('appointments.doctor_action_grace_hours', 3)); // مثال: 3 ساعات

        $query = Appointment::where('type', Appointment::STATUS_CONFIRMED)
            ->whereNotNull('appointment')
            // وقت الموعد لم يفت بعد بـ 3 ساعات (مثلاً)
            ->where('appointment', '>=', Carbon::now()->subHours($confirmedActionGraceHours))
            ->with(['doctor:id', 'section:id', 'patient:id'])
            ->orderBy('appointment', 'asc');

        if ($request->filled('search_confirmed')) {
            // ... منطق البحث ...
        }

        $appointments = $query->paginate(config('pagination.admin_confirmed', 10))->appends($request->query());

        return view('Dashboard.appointments.index2', compact('appointments', 'request')); // نفس الـ view
    }


    /**
     * عرض قائمة المواعيد المنتهية (للأدمن).
     * GET /appointments/completed
     */
    public function indexCompleted()
    {
        Log::info("Fetching completed appointments for admin view.");
        $appointments = Appointment::where('type', Appointment::STATUS_COMPLETED)
            ->with(['doctor', 'section', 'patient'])
            ->orderBy('appointment', 'desc')
            ->paginate(10);

        return view('Dashboard.appointments.index_completed', compact('appointments'));
    }

    /**
     * عرض قائمة المواعيد الملغاة (للأدمن).
     * GET /appointments/cancelled
     */
    public function indexCancelled()
    {
        Log::info("Fetching cancelled appointments for admin view.");
        $appointments = Appointment::where('type', Appointment::STATUS_CANCELLED)
            ->with(['doctor', 'section', 'patient'])
            ->orderBy('updated_at', 'desc') //  تاريخ الإلغاء هو آخر تحديث
            ->paginate(10);

        return view('Dashboard.appointments.index_cancelled', compact('appointments'));
    }

    public function lapsedAppointments(Request $request)
    {
        Log::info("Admin: Fetching Lapsed Appointments - SIMPLIFIED QUERY v2.");

        $now = Carbon::now(); // الوقت الحالي

        $query = Appointment::query()
            // الشرط الأساسي: أحضر المواعيد التي وقتها المحدد قد مضى
            ->whereNotNull('appointment') // تأكد أن حقل وقت الموعد ليس فارغاً
            ->where('appointment', '<', $now) // وقت الموعد أقدم من الآن

            // و حالتها لا تزال "نشطة" (مؤكد أو غير مؤكد)
            ->whereIn('type', [
                Appointment::STATUS_PENDING,   // 'غير مؤكد'
                Appointment::STATUS_CONFIRMED  // 'مؤكد'
            ])
            // لا نحتاج لـ whereNotIn هنا إذا كنا سنعتمد على المهمة المجدولة لاحقاً
            // لتغييرها إلى STATUS_LAPSED, ولكن للإضافة الآن:
            // ->whereNotIn('type', [
            //     Appointment::STATUS_COMPLETED,
            //     Appointment::STATUS_CANCELLED,
            //     Appointment::STATUS_LAPSED
            // ])
            ->with([
                'doctor' => fn($q) => $q->select('id'),
                'section' => fn($q) => $q->select('id'),
                'patient' => fn($q) => $q->select('id', 'phone')
            ])
            ->orderBy('appointment', 'asc'); // الأقدم (الأكثر فواتاً) أولاً

        // فلتر البحث (كما هو لديك)
        if ($request->filled('search_lapsed')) {
            $searchTerm = $request->search_lapsed;
            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('patient', fn($pq) => $pq->where('name', 'like', "%{$searchTerm}%")
                    ->orWhereTranslationLike('name', "%{$searchTerm}%"))
                    ->orWhereHas('doctor', fn($dq) => $dq->where('name', 'like', "%{$searchTerm}%")
                        ->orWhereTranslationLike('name', "%{$searchTerm}%"))
                    ->orWhere('appointments.name', 'like', "%{$searchTerm}%"); // اسم المريض في جدول appointments
            });
        }
        // فلتر التاريخ (كما هو لديك)
        if ($request->filled('date_lapsed_filter')) {
            try {
                $dateFilter = Carbon::parse($request->date_lapsed_filter)->toDateString();
                $query->whereDate('appointment', $dateFilter);
            } catch (\Exception $e) {/* ignore */
            }
        }

        $lapsedAppointments = $query->paginate(15)->appends($request->query()); // 15 عنصر لكل صفحة

        Log::info("Admin Lapsed Appointments: Found {$lapsedAppointments->total()} results with simplified query.");
        if ($lapsedAppointments->isEmpty()) {
            Log::warning("Admin Lapsed Appointments: No appointments matched the simplified query conditions.");
            // يمكنك هنا تسجيل بعض استعلامات SQL التي نفذت إذا لم تظهر نتائج لفحصها
            // DB::enableQueryLog();
            // $lapsedAppointments = $query->paginate(15)...
            // Log::debug(DB::getQueryLog());
        }


        // تم تغيير اسم المتغير هنا ليتطابق مع ما استخدمته في الـ Blade المُرسل
        return view('Dashboard.appointments.lapsed_appointments', compact('lapsedAppointments', 'request'));
    }


    /**
     * تأكيد موعد محدد بواسطة الأدمن.
     * PUT/PATCH /appointments/approval/{id}
     */
    public function approval(Request $request, $id)
    {
        Log::info("Attempting to approve appointment ID: {$id} by admin.");
        $appointment = null;
        $notificationWarning = null; // لتجميع رسائل فشل الإشعارات

        try {
            // تحميل العلاقات اللازمة (استخدم اسم علاقة المريض الصحيح: patient أو user)
            $appointment = Appointment::with(['doctor', 'section', 'patient'])->findOrFail($id); // نفترض 'patient'

            // 1. التحقق من الحالة والوقت
            if ($appointment->type !== 'غير مؤكد') {
                Log::warning("Approval failed for appointment ID: {$id}. Current status: {$appointment->type}");
                return redirect()->back()->with('error', 'لا يمكن تأكيد هذا الموعد، حالته ليست "غير مؤكد".');
            }
            if ($appointment->appointment && Carbon::parse($appointment->appointment)->isPast()) {
                Log::warning("Approval failed for appointment ID: {$id}. Appointment time is in the past: {$appointment->appointment}");
                return redirect()->back()->with('error', 'لا يمكن تأكيد موعد قد فات وقته.');
            }

            // 2. تحديث الحالة
            $appointment->update(['type' => 'مؤكد']);
            Log::info("Appointment ID {$appointment->id} confirmed successfully by admin (DB updated).");

            // 3. محاولة إرسال الإشعارات
            $emailWarning = null;
            $smsWarning = null;

            // --- إرسال البريد ---
            try {
                $patientEmail = $appointment->patient->email ?? $appointment->email;
                // $patientName = $appointment->patient->name ?? $appointment->name;
                // $doctorName = $appointment->doctor->name ?? 'الطبيب المختص';
                $patientNameForMail = $appointment->name; // اسم المريض من الموعد نفسه
                $appointmentObject = $appointment->appointment; // كائن Carbon للوقت
                $doctorNameForMail = $appointment->doctor->name ?? 'الطبيب المعالج'; // اسم الطبيب
                $sectionNameForMail = $appointment->section->name ?? 'القسم المختص'; // اسم القسم (تحتاج لتحميل علاقة section أيضاً)

                if ($patientEmail && $appointmentObject) {
                    // *** تمرير المتغيرات بالأسماء الصحيحة ***
                    Mail::to($patientEmail)->send(new AppointmentConfirmation(
                        $patientNameForMail,
                        $appointmentObject, // كائن Carbon
                        $doctorNameForMail,
                        $sectionNameForMail
                    ));
                    Log::info("Confirmation email sent to patient: {$patientEmail} for appt ID: {$appointment->id}");
                } else {
                    Log::warning("Cannot send confirmation email for appt ID: {$appointment->id}. Email or Time missing.");
                    $emailWarning = 'لم يتم العثور على بريد إلكتروني صالح للمريض.';
                }
            } catch (\Exception $e) {
                Log::error("Failed to send confirmation EMAIL for appt ID: {$appointment->id}. Error: " . $e->getMessage());
                $emailWarning = 'فشل إرسال البريد الإلكتروني.';
            }

            // --- إرسال SMS ---
            try {
                // استخدام الحقل الصحيح للهاتف
                $receiverNumber = $appointment->patient->Phone ?? $appointment->phone; // تأكد من اسم الحقل Phone أو phone
                if ($receiverNumber && $appointment->appointment) {
                    $message = "عزيزي المريض " . $appointment->name . "، تم تأكيد موعدك بنجاح بتاريخ " . $appointment->appointment->format('Y-m-d \ا\ل\س\ا\ع\ة H:i') . ".";
                    $smsSent = $this->sendTwilioSms($receiverNumber, $message, $appointment->id, 'confirmation');
                    if (!$smsSent) { // التحقق من نتيجة دالة الإرسال
                        $smsWarning = 'فشل إرسال رسالة SMS (تحقق من اللوغات).';
                    }
                } else {
                    Log::warning("Cannot send confirmation SMS for appt ID: {$appointment->id}. Phone or Time missing.");
                    // لا تضع رسالة خطأ إذا فشل البريد أيضاً هنا، سيتم تجميعها لاحقاً
                    if (!$emailWarning) $smsWarning = 'لم يتم العثور على رقم هاتف صالح للمريض.';
                }
            } catch (\Exception $e) {
                Log::error("General error sending confirmation SMS for appt ID: {$appointment->id}. Error: " . $e->getMessage());
                $smsWarning = 'خطأ غير متوقع أثناء إرسال SMS.';
            }


            // --- تجميع رسائل التحذير ---
            if ($emailWarning && $smsWarning) {
                $notificationWarning = $emailWarning . " و" . lcfirst($smsWarning);
            } elseif ($emailWarning) {
                $notificationWarning = $emailWarning;
            } elseif ($smsWarning) {
                $notificationWarning = $smsWarning;
            }

            // --- تحديد رسالة الـ Flash النهائية ---
            if ($notificationWarning) {
                session()->flash('warning', 'تم تأكيد الموعد بنجاح، لكن ' . $notificationWarning);
            } else {
                session()->flash('add', 'تم تأكيد الموعد بنجاح وإرسال الإشعارات.'); // أو 'success'
            }

            return redirect()->route('appointments.index'); // العودة لقائمة غير المؤكدة

        } catch (ModelNotFoundException $e) {
            Log::error("Appointment not found for approval. ID: {$id}");
            return redirect()->back()->with('error', 'الموعد المطلوب غير موجود.');
        } catch (\Exception $e) { // التقاط أي خطأ يحدث *قبل* أو *أثناء* Update
            Log::error("CRITICAL Error approving appointment ID {$id}: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());
            return redirect()->back()->with('error', 'حدث خطأ حرج وغير متوقع أثناء محاولة تأكيد الموعد.');
        }
    } // نهاية approval

    /**
     * إلغاء موعد محدد بواسطة الأدمن.
     * PATCH /appointments/admin-cancel/{appointment}
     */
    public function adminCancelAppointment(Request $request, Appointment $appointment)
    {
        Log::info("Admin attempting to cancel appointment ID: {$appointment->id}");
        if (in_array($appointment->type, ['ملغي', 'منتهي'])) { /* ... */
            return redirect()->back()->with('error', '...');
        }

        $cancelReason = $request->input('cancel_reason', 'تم الإلغاء من قبل الإدارة لأسباب ادارية');

        try {
            $appointment->load(['doctor', 'patient']); // تحميل العلاقات اللازمة للإشعارات
            $appointment->update(['type' => 'ملغي']);
            Log::info("Appointment ID {$appointment->id} cancelled successfully by Admin.");
            $this->sendAdminCancellationNotifications($appointment, $cancelReason); // إرسال الإشعارات

            session()->flash('success', 'تم إلغاء الموعد بنجاح وإرسال الإشعارات.');
            return redirect()->route('appointments.index2'); // العودة للمواعيد المؤكدة

        } catch (\Exception $e) { /* ... معالجة الخطأ ... */
        }
    }

    /**
     * حذف سجل موعد (نهائي).
     * DELETE /appointments/{id}
     */
    public function destroy($id)
    {
        Log::info("Attempting to delete appointment ID: {$id}");
        try {
            $appointment = Appointment::findOrFail($id);
            $appointment->delete();
            Log::info("Appointment ID: {$id} deleted successfully.");
            session()->flash('delete');
        } catch (ModelNotFoundException $e) { /* ... */
        } catch (\Exception $e) { /* ... */
        }
        return redirect()->back();
    }

    // ================================================================
    //  *** دوال مساعدة لإرسال الإشعارات ***
    // ================================================================

    /**
     * إرسال إشعارات إلغاء الموعد (عند إلغاء الأدمن).
     */
    protected function sendAdminCancellationNotifications(Appointment $appointment, $reason)
    {
        try {
            $patientName = $appointment->patient->name ?? $appointment->name; // استخدم العلاقة الصحيحة
            $appointmentTime = $appointment->appointment ? $appointment->appointment->translatedFormat('l، d M Y - h:i A') : 'غير محدد';
            $doctorName = $appointment->doctor->name ?? 'الطبيب';

            // للمريض
            $patientEmail = $appointment->patient->email ?? $appointment->email; // العلاقة الصحيحة
            if ($patientEmail) {
                Mail::to($patientEmail)->send(new AppointmentCancelledByAdmin($patientName, $appointmentTime, $doctorName, $reason));
                Log::info("Admin cancellation email sent to patient: {$patientEmail} for appt ID: {$appointment->id}");
            }
            $patientPhone = $appointment->patient->Phone ?? $appointment->phone; // العلاقة واسم الحقل الصحيح
            if ($patientPhone) {
                $smsMessagePatient = "عزيزي " . $patientName . "، نأسف لإلغاء موعدك مع د. " . $doctorName . " بتاريخ " . $appointmentTime . " بسبب: " . $reason . ". يرجى التواصل معنا.";
                $this->sendTwilioSms($patientPhone, $smsMessagePatient, $appointment->id, 'admin_cancellation_patient');
            }

            // للطبيب
            if ($appointment->doctor) { /* ... نفس الكود السابق لإرسال للطبيب ... */
            }
        } catch (\Exception $e) {
            Log::error("Failed sending admin cancellation notifications for appt ID: {$appointment->id}. Error: " . $e->getMessage());
            // لا تضع flash هنا مباشرة، لكن يمكن لدالة adminCancelAppointment التحقق من قيمة الإرجاع
        }
    }

    /**
     * دالة مساعدة لإرسال رسائل Twilio SMS.
     */
    protected function sendTwilioSms($receiverNumber, $message, $appointmentId, $context = 'message')
    {
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
} // نهاية الكلاس
