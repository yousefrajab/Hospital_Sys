<?php

namespace App\Http\Controllers\Dashboard_Patient;

use App\Models\Ray;
use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\Message;
use App\Models\Section;
use App\Models\Appointment;
use App\Models\Laboratorie;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use App\Models\PatientAccount;
use App\Models\ReceiptAccount;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\Notification as CustomNotification;
use App\Models\Patient;
use App\Models\Prescription;
use App\Mail\AppointmentCancelledByPatientToDoctor;

class PatientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:patient');
    }


    public function dashboard()
    {
        $patient = Auth::guard('patient')->user();
        if (!$patient) {

            session()->flash('error', 'حدث خطأ، يرجى تسجيل الدخول مرة أخرى.');
            return redirect()->route('login');
        }
        $patientId = $patient->id;
        $patientEmail = $patient->email;

        Log::info("Patient Dashboard: User {$patient->name} (ID: {$patientId}) accessed.");
        $totalDueInvoices = Invoice::where('patient_id', $patientId)

            ->sum(DB::raw('total_with_tax'));

        $totalPaidByPatient = ReceiptAccount::where('patient_id', $patientId)
            ->sum('amount');

        $upcomingAppointmentsCount = Appointment::where('patient_id', $patientId)
            ->where('appointment', '>=', now())
            ->whereIn('type', ['مؤكد', 'غير مؤكد'])
            ->count();

        $readyPrescriptionsCount = Prescription::where('patient_id', $patientId)
            ->where('status', \App\Models\Prescription::STATUS_READY_FOR_PICKUP)
            ->count();

        $unreadChatMessagesCount = Message::where('receiver_email', $patientEmail)
            ->where('read', false)
            ->count();
        Log::info("Patient Dashboard: Unread chat messages count for {$patientEmail}: {$unreadChatMessagesCount}");

        $upcomingChronicRefill = Prescription::where('patient_id', $patientId)
            ->where('is_chronic_prescription', true)
            ->whereNotNull('next_refill_due_date')
            ->whereBetween('next_refill_due_date', [Carbon::today(), Carbon::today()->addDays(7)])
            ->whereNotIn('status', [
                Prescription::STATUS_REFILL_REQUESTED,
                Prescription::STATUS_CANCELLED_BY_DOCTOR,
                Prescription::STATUS_CANCELLED_BY_PATIENT,
                Prescription::STATUS_EXPIRED,
                // Prescription::STATUS_DELIVERED
            ])
            ->orderBy('next_refill_due_date', 'asc')
            ->first();

        $imminentAppointment = Appointment::where('patient_id', $patientId)
            ->where('type', 'مؤكد')
            ->whereBetween('appointment', [Carbon::now()->startOfDay(), Carbon::tomorrow()->endOfDay()])
            ->with('doctor:id')
            ->orderBy('appointment', 'asc')
            ->first();
        if ($imminentAppointment && $imminentAppointment->doctor) {
            $imminentAppointment->doctor->unsetRelations();
            $imminentAppointment->doctor->load('translations');
        }


        $hasImportantAlerts = $upcomingChronicRefill || ($readyPrescriptionsCount > 0) || $imminentAppointment || ($unreadChatMessagesCount > 0);


        // $latest_invoices = Invoice::with('Doctor:id')
        //     ->where('patient_id', $patientId)
        //     ->latest('invoice_date')
        //     ->take(5)
        //     ->get();

        $upcoming_appointments_list = Appointment::with(['doctor:id', 'section:id'])
            ->where('patient_id', $patientId)
            ->where('appointment', '>=', now())
            ->whereIn('type', ['مؤكد', 'غير مؤكد'])
            ->orderBy('appointment', 'asc')
            ->take(5)
            ->get();
        $upcoming_appointments_list->each(function ($appointment) {
            if ($appointment->doctor) {
                $appointment->doctor->unsetRelations();
                $appointment->doctor->load('translations');
            }
            if ($appointment->section) {
                $appointment->section->unsetRelations();
                $appointment->section->load('translations');
            }
        });


        return view('Dashboard.dashboard_patient.dashboard', compact(
            'patient',
            'totalDueInvoices',
            'totalPaidByPatient',
            'upcomingAppointmentsCount',
            'readyPrescriptionsCount',
            'unreadChatMessagesCount',
            'upcomingChronicRefill',
            'imminentAppointment',
            'hasImportantAlerts',
            // 'latest_invoices',
            'upcoming_appointments_list'
        ));
    }
    public function invoices()
    {
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
        $laboratorie = Laboratorie::findOrFail($id);
        if ($laboratorie->patient_id != auth()->id()) {

            abort(403, 'غير مصرح لك بعرض هذا السجل.');
        }
        return view('Dashboard.dashboard_patient.view_laboratorie', compact('laboratorie'));
    }

    public function rays()
    {
        $rays = Ray::where('patient_id', auth()->id())->get();
        return view('Dashboard.dashboard_patient.rays', compact('rays'));
    }

    public function viewRays($id)
    {
        $ray = Ray::findOrFail($id);
        if ($ray->patient_id != auth()->id()) {
            abort(403, 'غير مصرح لك بعرض هذا السجل.');
        }
        return view('Dashboard.dashboard_patient.view_ray', compact('ray'));
    }

    public function payments()
    {
        $payments = ReceiptAccount::where('patient_id', auth()->id())->get();
        return view('Dashboard.dashboard_patient.payments', compact('payments'));
    }

    public function upcomingAppointments(Request $request)
    {
        $patient = Auth::guard('patient')->user();

        $appointments = $patient->upcomingAppointments()
            ->with([
                'doctor' => function ($query) {

                    $query->withTranslation()->select('doctors.id');
                },
                'section' => function ($query) {
                    $query->withTranslation()->select('sections.id');
                }
            ])
            ->orderBy('appointment', 'asc')
            ->paginate(config('pagination.appointments_patient_upcoming', 9));

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
        return view('Dashboard.Patients.appointments.past', compact('patient', 'appointments'));
    }

    public function cancelAppointmentByPatient(Request $request, Appointment $appointment)
    {
        $patient = Auth::guard('patient')->user();

        if ($appointment->patient_id !== $patient->id) {
            Log::warning("AUTH_FAIL_CANCEL_PATIENT: Patient {$patient->id} attempted to cancel appointment {$appointment->id} not belonging to them (belongs to patient {$appointment->patient_id}).");
            return response()->json(['message' => 'غير مصرح لك بإلغاء هذا الموعد.'], 403);
        }

        $cancellableTypes = [Appointment::STATUS_PENDING, Appointment::STATUS_CONFIRMED];
        if (!in_array($appointment->type, $cancellableTypes, true)) {
            $message = 'لا يمكن إلغاء هذا الموعد لأن حالته الحالية هي: (' . ($appointment->status_display ?? $appointment->type) . ').';
            Log::info("CANCEL_DENIED_STATUS_PATIENT: Patient {$patient->id} failed to cancel appointment {$appointment->id}. Status: '{$appointment->type}'.");
            return response()->json(['message' => $message], 422);
        }


        $cancellationWindowHours = config('settings.patient_appointment_cancellation_window_hours', 24);
        if (Carbon::parse($appointment->appointment)->isPast() || Carbon::parse($appointment->appointment)->diffInHours(now(), false) < $cancellationWindowHours) {
            if (Carbon::parse($appointment->appointment)->isFuture() && Carbon::parse($appointment->appointment)->diffInHours(now()) < $cancellationWindowHours) {
                $errorMessage = "لا يمكن إلغاء الموعد قبل أقل من {$cancellationWindowHours} ساعة من وقته المحدد. يرجى التواصل مباشرة مع العيادة إذا لزم الأمر.";
                Log::info("CANCEL_DENIED_TIME_PATIENT: Patient {$patient->id} for appt {$appointment->id}. Appt time: {$appointment->appointment}, Window: {$cancellationWindowHours}h.");
                return response()->json(['message' => $errorMessage], 422);
            }
        }

        DB::beginTransaction();
        try {
            $oldType = $appointment->type;

            $appointment->type = Appointment::STATUS_CANCELLED;

            // $appointment->cancellation_reason = $request->input('reason_patient', 'تم الإلغاء بواسطة المريض عبر البوابة.');
            // $appointment->cancelled_by_user_type = Patient::class;
            // $appointment->cancelled_by_user_id = $patient->id;
            // $appointment->cancelled_at = now();

            $appointment->saveOrFail();

            Log::info("APPOINTMENT_CANCELLED_BY_PATIENT: ID {$appointment->id}, NewType: '{$appointment->type}' (Old: '{$oldType}'), ByPatientID: {$patient->id}.");

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
                'appointment_id' => $appointment->id,
                'new_status' => $appointment->type,
                'status_display' => $appointment->status_display
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
    public function create()
    {
        $sections = Section::orderByTranslation('name')->get();
        $doctors = Doctor::orderByTranslation('name')->get();
        return view('Dashboard.Patients.appointments.create', compact('sections', 'doctors'));
    }

    public function store(Request $request)
    {
        Log::info('Patient appointment store request received: ', $request->all());
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'section_id' => 'required|exists:sections,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string|max:1000'
        ], [
            'doctor_id.required' => 'حقل الطبيب مطلوب.',
            'section_id.required' => 'حقل القسم مطلوب.',
            'appointment_date.required' => 'حقل تاريخ الموعد مطلوب.',
            'appointment_date.after_or_equal' => 'تاريخ الموعد يجب أن يكون اليوم أو في المستقبل.',
            'appointment_time.required' => 'حقل وقت الموعد مطلوب.',
            'appointment_time.date_format' => 'صيغة وقت الموعد غير صحيحة (مثال: 14:30).',
        ]);
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
                'type' => Appointment::STATUS_PENDING ?? 'غير مؤكد'
            ]);

            Log::info("Appointment created with ID: {$appointment->id} for patient {$patient->id} with doctor {$validated['doctor_id']}");
            // if ($appointment->doctor && $appointment->doctor->email) {
            //     Mail::to($appointment->doctor->email)->send(new \App\Mail\AppointmentCreated($appointment));
            // }

            return redirect()->route('patient.appointment.success')
                ->with('success_message', 'تم إرسال طلب حجز الموعد بنجاح. سيتم التواصل معك للتأكيد قريباً.');
        } catch (\Exception $e) {
            Log::error("Error creating appointment for patient {$patient->id}: " . $e->getMessage(), ['trace' => $e->getTraceAsString(), 'request_data' => $request->all()]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء حجز الموعد: ' . $e->getMessage());
        }
    }



    public function createTestimonial()
    {

        $existingPendingTestimonial = Testimonial::where('patient_id', Auth::id())
            ->where('status', 'pending')
            ->exists();
        if ($existingPendingTestimonial) {
            return redirect()->route('dashboard.patient')->with('warning', 'لديك تعليق سابق قيد المراجعة.');
        }


        return view('Dashboard.dashboard_patient.testimonials.create');
    }
    public function storeTestimonial(Request $request)
    {
        $patient = Auth::guard('patient')->user();

        $request->validate([
            'comment' => 'required|string|min:10|max:1000',
        ], [
            'comment.required' => 'حقل التعليق مطلوب.',
            'comment.min' => 'يجب أن يحتوي التعليق على 10 أحرف على الأقل.',
            'comment.max' => 'التعليق طويل جدًا، الحد الأقصى 1000 حرف.',
        ]);



        Testimonial::create([
            'patient_id' => $patient->id,
            'patient_name' => $patient->name,
            'comment' => $request->comment,
            'status' => 'pending',
        ]);

        session()->flash('success', 'تم إرسال تعليقك بنجاح! سيتم مراجعته من قبل الإدارة قريبًا.');

        return redirect()->route('dashboard.patient'); 
    }
}
