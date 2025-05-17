<?php

namespace App\Http\Controllers\Dashboard_Patient;

use App\Models\Ray;
use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\Section;
use App\Models\Appointment;
use App\Models\Laboratorie;
use Illuminate\Http\Request;
use App\Models\PatientAccount;
use App\Models\ReceiptAccount;
use Illuminate\Support\Carbon;
use App\Models\DoctorWorkingDay;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentCancelledByPatientToDoctor;

class PatientController extends Controller
{
    public function invoices()
    {

        $invoices = Invoice::where('patient_id', auth()->user()->id)->get();
        return view('Dashboard.dashboard_patient.invoices', compact('invoices'));
    }

    public function laboratories()
    {

        $laboratories = Laboratorie::where('patient_id', auth()->user()->id)->get();
        return view('Dashboard.dashboard_patient.laboratories', compact('laboratories'));
    }

    public function viewLaboratories($id)
    {

        $laboratorie = Laboratorie::findorFail($id);
        if ($laboratorie->patient_id != auth()->user()->id) {
            return redirect()->route('404');
        }
        return view('Dashboard.dashboard_LaboratorieEmployee.invoices.patient_details', compact('laboratorie'));
    }

    public function rays()
    {

        $rays = Ray::where('patient_id', auth()->user()->id)->get();
        return view('Dashboard.dashboard_patient.rays', compact('rays'));
    }

    public function viewRays($id)
    {
        $rays = Ray::findorFail($id);
        if ($rays->patient_id != auth()->user()->id) {
            return redirect()->route('404');
        }
        return view('Dashboard.dashboard_RayEmployee.invoices.patient_details', compact('rays'));
    }

    public function payments()
    {

        $payments = ReceiptAccount::where('patient_id', auth()->user()->id)->get();
        return view('Dashboard.dashboard_patient.payments', compact('payments'));
    }

    // --- دوال عرض المواعيد للمريض ---
    public function upcomingAppointments(Request $request) // إضافة Request للترقيم
    {
        $patient = Auth::guard('patient')->user();
        if (!$patient) {
            return redirect()->route('login.patient.form');
        } // افترض أن هذا هو route تسجيل الدخول للمريض

        $appointments = $patient->upcomingAppointments() // ** العلاقة المحدثة **
            ->with([
                'doctor' => function ($q_doc) {
                    $q_doc->withTranslation();
                },
                'section' => function ($q_sec) {
                    $q_sec->withTranslation();
                }
            ])
            ->paginate(10); // يمكنك تحديد عدد العناصر لكل صفحة

        return view('Dashboard.Patients.appointments.upcoming', compact('patient', 'appointments'));
    }

    public function pastAppointments(Request $request)
    {
        $patient = Auth::guard('patient')->user();
        if (!$patient) {
            return redirect()->route('login.patient.form');
        }

        // جلب المواعيد السابقة مع العلاقات اللازمة
        $appointments = $patient->pastAppointments() // استخدام العلاقة من موديل Patient
            ->with([
                'doctor' => function ($q_doc) {
                    $q_doc->withTranslation();
                }, // تحميل اسم الطبيب المترجم
                'section' => function ($q_sec) {
                    $q_sec->withTranslation();
                } // تحميل اسم القسم المترجم
            ])
            ->paginate(10); // يمكنك التحكم في عدد المواعيد لكل صفحة

        // (اختياري) يمكنك تجميع المواعيد حسب الشهر أو السنة إذا أردت عرضها بهذا الشكل
        $appointmentsByMonth = $appointments->groupBy(function ($appointment) {
            return $appointment->appointment->translatedFormat('F Y'); // مثال: "مايو 2025"
        });

        return view('Dashboard.Patients.appointments.past', compact('patient', 'appointments' /*, 'appointmentsByMonth'*/));
    }

    public function cancelAppointmentByPatient(Request $request, Appointment $appointment)
    {
        $patient = Auth::guard('patient')->user();

        if (!$patient || $appointment->patient_id !== $patient->id) {
            // ... (معالجة الخطأ كما هي)
            if ($request->expectsJson()) {
                return response()->json(['message' => 'غير مصرح لك.'], 403);
            }
            return redirect()->back()->with('error', 'غير مصرح لك بإلغاء هذا الموعد.');
        }

        if (!in_array($appointment->type, [Appointment::STATUS_PENDING, Appointment::STATUS_CONFIRMED])) {
            // ... (معالجة الخطأ كما هي)
            $message = 'لا يمكن إلغاء هذا الموعد لأنه (' . ($appointment->status_display ?? $appointment->type) . ').';
            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 422);
            }
            return redirect()->back()->with('error', $message);
        }

        try {
            $appointment->type = Appointment::STATUS_CANCELLED; // أو قيمة 'ملغي' مباشرة
            // $appointment->cancellation_reason_by_patient = $request->input('reason_patient', 'تم الإلغاء بواسطة المريض عبر البوابة'); // (اختياري) سبب الإلغاء
            $appointment->save();

            Log::info("Appointment ID: {$appointment->id} cancelled by Patient ID: {$patient->id}");

            // ***** إرسال إشعار بالبريد الإلكتروني للطبيب *****
            if ($appointment->doctor && $appointment->doctor->email) {
                try {
                    Mail::to($appointment->doctor->email)
                        ->send(new AppointmentCancelledByPatientToDoctor($appointment, $patient));
                    Log::info("Cancellation notification email sent to Doctor {$appointment->doctor->email} for appointment ID {$appointment->id}.");
                } catch (\Exception $e) {
                    Log::error("Failed to send cancellation email to doctor for appointment ID {$appointment->id}: " . $e->getMessage());
                    // لا توقف العملية كلها بسبب فشل الإيميل، ولكن يمكنك تسجيله أو إعلام الأدمن
                }
            } else {
                Log::warning("Doctor email not found for appointment ID {$appointment->id}. Cannot send cancellation email.");
            }
            // ***** نهاية إرسال الإشعار *****


            $successMessage = 'تم إلغاء موعدك بنجاح. تم إبلاغ الطبيب.';
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $successMessage,
                    'appointment_id' => $appointment->id,
                    'new_status' => $appointment->type
                ]);
            }
            return redirect()->route('patient.appointments.upcoming')->with('success', $successMessage);
        } catch (\Exception $e) {
            Log::error("Error cancelling appointment ID {$appointment->id} by patient: " . $e->getMessage());
            if ($request->expectsJson()) {
                return response()->json(['message' => 'حدث خطأ أثناء محاولة إلغاء الموعد.'], 500);
            }
            return redirect()->back()->with('error', 'حدث خطأ أثناء محاولة إلغاء الموعد.');
        }
    }

    public function create()
    {
        $sections = Section::all();
        return view('Dashboard.Patients.appointments.create', compact('sections'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'section_id' => 'required|exists:sections,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string|max:1000'
        ]);

        try {
            $appointment = Appointment::create([
                'doctor_id' => $validated['doctor_id'],
                'section_id' => $validated['section_id'],
                'patient_id' => Auth::guard('patient')->id(),
                'name' => Auth::guard('patient')->user()->name,
                'email' => Auth::guard('patient')->user()->email,
                'phone' => Auth::guard('patient')->user()->Phone,
                'appointment' => $validated['appointment_date'] . ' ' . $validated['appointment_time'],
                'notes' => $validated['notes'],
                'type' => 'غير مؤكد'
            ]);

            return redirect()->back()
                ->with('success', 'تم حجز الموعد بنجاح، سيتم التواصل معك للتأكيد');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء حجز الموعد: ' . $e->getMessage());
        }
    }
}
