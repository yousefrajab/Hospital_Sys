<?php

namespace App\Http\Controllers\Dashboard\appointments;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentConfirmation;
use App\Mail\AppointmentCancelledByAdmin;
use Twilio\Rest\Client;
use Twilio\Exceptions\TwilioException;

class AppointmentController extends Controller
{

    public function index(Request $request)
    {
        Log::info("Admin: Fetching PENDING appointments that need action.");
        $pendingGraceDays = intval(config('appointments.admin_pending_grace_days', 1));

        $query = Appointment::where('type', Appointment::STATUS_PENDING)
            ->whereNotNull('appointment')
            ->where('appointment', '>=', Carbon::now()->subDays($pendingGraceDays))
            ->with(['doctor:id', 'section:id', 'patient:id'])
            ->orderBy('appointment', 'asc');

        if ($request->filled('search_pending')) {
        }

        $appointments = $query->paginate(config('pagination.admin_pending', 10))->appends($request->query());

        return view('Dashboard.appointments.index', compact('appointments', 'request'));
    }


    public function index2(Request $request)
    {
        Log::info("Admin: Fetching CONFIRMED & UPCOMING appointments.");
        $confirmedActionGraceHours = intval(config('appointments.doctor_action_grace_hours', 3));
        $query = Appointment::where('type', Appointment::STATUS_CONFIRMED)
            ->whereNotNull('appointment')
            ->where('appointment', '>=', Carbon::now()->subHours($confirmedActionGraceHours))
            ->with(['doctor:id', 'section:id', 'patient:id'])
            ->orderBy('appointment', 'asc');

        if ($request->filled('search_confirmed')) {
        }

        $appointments = $query->paginate(config('pagination.admin_confirmed', 10))->appends($request->query());

        return view('Dashboard.appointments.index2', compact('appointments', 'request'));
    }

    public function indexCompleted()
    {
        Log::info("Fetching completed appointments for admin view.");
        $appointments = Appointment::where('type', Appointment::STATUS_COMPLETED)
            ->with(['doctor', 'section', 'patient'])
            ->orderBy('appointment', 'desc')
            ->paginate(10);

        return view('Dashboard.appointments.index_completed', compact('appointments'));
    }

    public function indexCancelled()
    {
        Log::info("Fetching cancelled appointments for admin view.");
        $appointments = Appointment::where('type', Appointment::STATUS_CANCELLED)
            ->with(['doctor', 'section', 'patient'])
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('Dashboard.appointments.index_cancelled', compact('appointments'));
    }

    public function lapsedAppointments(Request $request)
    {
        Log::info("Admin: Fetching Lapsed Appointments - SIMPLIFIED QUERY v2.");

        $now = Carbon::now();

        $query = Appointment::query()
            ->whereNotNull('appointment')
            ->where('appointment', '<', $now)

            ->whereIn('type', [
                Appointment::STATUS_PENDING,
                Appointment::STATUS_CONFIRMED
            ])

            ->with([
                'doctor' => fn($q) => $q->select('id'),
                'section' => fn($q) => $q->select('id'),
                'patient' => fn($q) => $q->select('id', 'phone')
            ])
            ->orderBy('appointment', 'asc');

        if ($request->filled('search_lapsed')) {
            $searchTerm = $request->search_lapsed;
            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('patient', fn($pq) => $pq->where('name', 'like', "%{$searchTerm}%")
                    ->orWhereTranslationLike('name', "%{$searchTerm}%"))
                    ->orWhereHas('doctor', fn($dq) => $dq->where('name', 'like', "%{$searchTerm}%")
                        ->orWhereTranslationLike('name', "%{$searchTerm}%"))
                    ->orWhere('appointments.name', 'like', "%{$searchTerm}%");
            });
        }
        if ($request->filled('date_lapsed_filter')) {
            try {
                $dateFilter = Carbon::parse($request->date_lapsed_filter)->toDateString();
                $query->whereDate('appointment', $dateFilter);
            } catch (\Exception $e) {/* ignore */
            }
        }

        $lapsedAppointments = $query->paginate(15)->appends($request->query());

        Log::info("Admin Lapsed Appointments: Found {$lapsedAppointments->total()} results with simplified query.");
        if ($lapsedAppointments->isEmpty()) {
            Log::warning("Admin Lapsed Appointments: No appointments matched the simplified query conditions.");

        }
        return view('Dashboard.appointments.lapsed_appointments', compact('lapsedAppointments', 'request'));
    }
    public function approval(Request $request, $id)
    {
        Log::info("Attempting to approve appointment ID: {$id} by admin.");
        $appointment = null;
        $notificationWarning = null;
        try {
            $appointment = Appointment::with(['doctor', 'section', 'patient'])->findOrFail($id);

            if ($appointment->type !== 'غير مؤكد') {
                Log::warning("Approval failed for appointment ID: {$id}. Current status: {$appointment->type}");
                return redirect()->back()->with('error', 'لا يمكن تأكيد هذا الموعد، حالته ليست "غير مؤكد".');
            }
            if ($appointment->appointment && Carbon::parse($appointment->appointment)->isPast()) {
                Log::warning("Approval failed for appointment ID: {$id}. Appointment time is in the past: {$appointment->appointment}");
                return redirect()->back()->with('error', 'لا يمكن تأكيد موعد قد فات وقته.');
            }

            $appointment->update(['type' => 'مؤكد']);
            Log::info("Appointment ID {$appointment->id} confirmed successfully by admin (DB updated).");

            $emailWarning = null;
            $smsWarning = null;

            try {
                $patientEmail = $appointment->patient->email ?? $appointment->email;
                // $patientName = $appointment->patient->name ?? $appointment->name;
                // $doctorName = $appointment->doctor->name ?? 'الطبيب المختص';
                $patientNameForMail = $appointment->name;
                $appointmentObject = $appointment->appointment;
                $doctorNameForMail = $appointment->doctor->name ?? 'الطبيب المعالج';
                $sectionNameForMail = $appointment->section->name ?? 'القسم المختص';

                if ($patientEmail && $appointmentObject) {
                    Mail::to($patientEmail)->send(new AppointmentConfirmation(
                        $patientNameForMail,
                        $appointmentObject,
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

            try {
                $receiverNumber = $appointment->patient->Phone ?? $appointment->phone;
                if ($receiverNumber && $appointment->appointment) {
                    $message = "عزيزي المريض " . $appointment->name . "، تم تأكيد موعدك بنجاح بتاريخ " . $appointment->appointment->format('Y-m-d \ا\ل\س\ا\ع\ة H:i') . ".";
                    $smsSent = $this->sendTwilioSms($receiverNumber, $message, $appointment->id, 'confirmation');
                    if (!$smsSent) {
                        $smsWarning = 'فشل إرسال رسالة SMS (تحقق من اللوغات).';
                    }
                } else {
                    Log::warning("Cannot send confirmation SMS for appt ID: {$appointment->id}. Phone or Time missing.");
                    if (!$emailWarning) $smsWarning = 'لم يتم العثور على رقم هاتف صالح للمريض.';
                }
            } catch (\Exception $e) {
                Log::error("General error sending confirmation SMS for appt ID: {$appointment->id}. Error: " . $e->getMessage());
                $smsWarning = 'خطأ غير متوقع أثناء إرسال SMS.';
            }
            if ($emailWarning && $smsWarning) {
                $notificationWarning = $emailWarning . " و" . lcfirst($smsWarning);
            } elseif ($emailWarning) {
                $notificationWarning = $emailWarning;
            } elseif ($smsWarning) {
                $notificationWarning = $smsWarning;
            }

            if ($notificationWarning) {
                session()->flash('warning', 'تم تأكيد الموعد بنجاح، لكن ' . $notificationWarning);
            } else {
                session()->flash('add', 'تم تأكيد الموعد بنجاح وإرسال الإشعارات.');
            }

            return redirect()->route('appointments.index');

        } catch (ModelNotFoundException $e) {
            Log::error("Appointment not found for approval. ID: {$id}");
            return redirect()->back()->with('error', 'الموعد المطلوب غير موجود.');
        } catch (\Exception $e) {
            Log::error("CRITICAL Error approving appointment ID {$id}: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());
            return redirect()->back()->with('error', 'حدث خطأ حرج وغير متوقع أثناء محاولة تأكيد الموعد.');
        }
    }

    public function adminCancelAppointment(Request $request, Appointment $appointment)
    {
        Log::info("Admin attempting to cancel appointment ID: {$appointment->id}");
        if (in_array($appointment->type, ['ملغي', 'منتهي'])) { /* ... */
            return redirect()->back()->with('error', '...');
        }

        $cancelReason = $request->input('cancel_reason', 'تم الإلغاء من قبل الإدارة لأسباب ادارية');

        try {
            $appointment->load(['doctor', 'patient']);
            $appointment->update(['type' => 'ملغي']);
            Log::info("Appointment ID {$appointment->id} cancelled successfully by Admin.");
            $this->sendAdminCancellationNotifications($appointment, $cancelReason);

            session()->flash('success', 'تم إلغاء الموعد بنجاح وإرسال الإشعارات.');
            return redirect()->route('appointments.index2');

        } catch (\Exception $e) {
        }
    }
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
    protected function sendAdminCancellationNotifications(Appointment $appointment, $reason)
    {
        try {
            $patientName = $appointment->patient->name ?? $appointment->name;
            $appointmentTime = $appointment->appointment ? $appointment->appointment->translatedFormat('l، d M Y - h:i A') : 'غير محدد';
            $doctorName = $appointment->doctor->name ?? 'الطبيب';

            // للمريض
            $patientEmail = $appointment->patient->email ?? $appointment->email;
            if ($patientEmail) {
                Mail::to($patientEmail)->send(new AppointmentCancelledByAdmin($patientName, $appointmentTime, $doctorName, $reason));
                Log::info("Admin cancellation email sent to patient: {$patientEmail} for appt ID: {$appointment->id}");
            }
            $patientPhone = $appointment->patient->Phone ?? $appointment->phone;
            if ($patientPhone) {
                $smsMessagePatient = "عزيزي " . $patientName . "، نأسف لإلغاء موعدك مع د. " . $doctorName . " بتاريخ " . $appointmentTime . " بسبب: " . $reason . ". يرجى التواصل معنا.";
                $this->sendTwilioSms($patientPhone, $smsMessagePatient, $appointment->id, 'admin_cancellation_patient');
            }

            if ($appointment->doctor) {
            }
        } catch (\Exception $e) {
            Log::error("Failed sending admin cancellation notifications for appt ID: {$appointment->id}. Error: " . $e->getMessage());
        }
    }
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
} 
