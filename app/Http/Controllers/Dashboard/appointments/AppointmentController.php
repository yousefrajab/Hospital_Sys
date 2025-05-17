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
    public function index()
    {
        Log::info("Fetching pending appointments for admin view.");
        $appointments = Appointment::where('type', 'غير مؤكد')
            ->with(['doctor', 'section']) // تحميل العلاقات
            ->latest('created_at')
            ->paginate(10); // استخدام Pagination

        return view('Dashboard.appointments.index', compact('appointments'));
    }

    /**
     * عرض قائمة المواعيد المؤكدة (للأدمن).
     * GET /appointments/confirmed
     */
    public function index2()
    {
        Log::info("Fetching confirmed appointments for admin view.");
        $appointments = Appointment::where('type', 'مؤكد')
            ->with(['doctor', 'section'])
            ->orderBy('appointment', 'asc')
            ->paginate(10);

        return view('Dashboard.appointments.index2', compact('appointments'));
    }

    /**
     * عرض قائمة المواعيد المنتهية (للأدمن).
     * GET /appointments/completed
     */
    public function indexCompleted()
    {
        Log::info("Fetching completed appointments for admin view.");
        $appointments = Appointment::where('type', 'منتهي')
            ->with(['doctor', 'section'])
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
        $appointments = Appointment::where('type', 'ملغي') // التأكد من القيمة الصحيحة
            ->with(['doctor', 'section'])
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('Dashboard.appointments.index_cancelled', compact('appointments'));
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
