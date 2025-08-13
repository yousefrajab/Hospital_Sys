<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Service;
use App\Models\Appointment;
use App\Models\Testimonial;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ReceiptAccount;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentCancelledByPatientToDoctor;
use App\Models\Section;

class WebsiteController extends Controller
{

    public function home()
    {
        $sections = Section::with(['doctors.image', 'Service']) 
            ->orderBy('id', 'asc')
            ->get();


        $featuredDoctors = Doctor::with('image', 'section')
            ->where('status', true)
            ->take(7)
            ->get();

        $latestServices = Service::where('status', 1)
            ->with([
                'doctor' => function ($query) {
                    $query->with([/*'translations',*/'section' /*=> function($q_section){
                        $q_section->with('translations');
                    }*/]);
                }
            ])
            ->latest()
            ->take(6)
            ->get();

        $latestGroupedServices = Group::with([
            'service_group' => function ($query) {
                $query->with([
                    'doctor' => function ($q_doctor) {
                        $q_doctor->with([/*'translations',*/'section' /*=> function($q_section){
                                $q_section->with('translations');
                            }*/]);
                    }
                ])->where('status', 1);
            }
        ])
            ->latest()
            ->take(6)
            ->get();
        $patientsCount = Patient::count();
        $doctorsCount = Doctor::count();
        $appointmentCount = Appointment::count();
        $successfulSurgeriesCount = Appointment::where('type', Appointment::STATUS_COMPLETED)->count();

        $testimonials = Testimonial::with(['patient.image'])
            ->approved()
            ->latest('approved_at')
            ->get();

        return view('welcome', compact('sections', 'featuredDoctors', 'latestServices', 'latestGroupedServices', 'patientsCount', 'doctorsCount', 'appointmentCount', 'testimonials'));
    }

    public function showAllDepartments()
    {
        $sections = Section::withCount('doctors')
            ->orderByTranslation('name', 'asc')
            ->paginate(9);

        return view('WebSite.departments.all_departments', compact('sections'));
    }


    public function showDepartmentDetails($id)
    {

        $section = Section::with(['doctors.image', 'Service'])
            ->findOrFail($id);

        $otherSections = Section::findOrFail($id)
            ->orderByTranslation('name', 'asc')
            ->take(5)
            ->get();

        return view('WebSite.departments.department_details', compact('section', 'otherSections'));
    }

    public function showAllDoctors(Request $request)
    {
        $query = Doctor::query()->with(['image', 'section']);

        if ($request->has('section_id') && $request->section_id != '') {
            $query->where('section_id', $request->section_id);
        }


        $doctors = $query->orderByTranslation('name', 'asc')
            ->paginate(12);

        $sectionsForFilter = Section::orderByTranslation('name', 'asc')->get();

        return view('WebSite.doctors.all_doctors', compact('doctors', 'sectionsForFilter'));
    }

    public function showDoctorDetails($id)
    {
        $doctor = Doctor::with([
            'image',
            'section',
            'workingDays' => function ($query) {
                $query->where('active', true)
                    ->with(['breaks' => function ($q_break) {
                        $q_break->orderBy('start_time', 'asc');
                    }])
                    ->orderByRaw("FIELD(day, 'Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday')"); // لترتيب الأيام
            }
            // 'specializations'
        ])
            ->findOrFail($id);

        $relatedDoctors = [];
        if ($doctor->section) {
            $relatedDoctors = Doctor::where('section_id', $doctor->section_id)
                ->where('id', '!=', $doctor->id)
                ->where('status', true)
                ->with('image')
                ->take(4)
                ->get();
        }

        $scheduleData = [];
        $daysOrder = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        $activeWorkingDays = $doctor->workingDays->keyBy('day');
        foreach ($daysOrder as $dayName) {
            if ($activeWorkingDays->has($dayName)) {
                $workDay = $activeWorkingDays[$dayName];
                $scheduleData[$dayName] = [
                    'active' => true,
                    'start_time' => \Carbon\Carbon::parse($workDay->start_time)->translatedFormat('h:i A'),
                    'end_time' => \Carbon\Carbon::parse($workDay->end_time)->translatedFormat('h:i A'),
                    'appointment_duration' => $workDay->appointment_duration,
                    'breaks' => $workDay->breaks->map(function ($break) {
                        return [
                            'start_time' => \Carbon\Carbon::parse($break->start_time)->translatedFormat('h:i A'),
                            'end_time' => \Carbon\Carbon::parse($break->end_time)->translatedFormat('h:i A'),
                            'reason' => $break->reason,
                        ];
                    })
                ];
            } else {
                $scheduleData[$dayName] = ['active' => false];
            }
        }

        // $specializations = $doctor->specializations;

        return view('WebSite.doctors.doctor_details', compact(
            'doctor',
            'relatedDoctors',
            'scheduleData'
            // 'specializations'
        ));
    }
    public function showAllServices(Request $request)
    {
        $services = Service::where('status', 1)
            ->with([
                'doctor' => function ($query) {
                    $query->with('section');
                }
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        return view('WebSite.services.all_services_standalone', compact('services'));
    }

    public function showAllGroupServices(Request $request)
    {
        $groupedServices = Group::with([
            'doctor' => function ($query) {
                $query->with('section');
            },
            'service_group' => function ($query) {
                $query->where('status', 1)
                    ->with([
                        'doctor' => function ($q_doctor) {
                            $q_doctor->with('section');
                        }
                    ]);
            }
        ])
            ->orderBy('created_at', 'desc')
            ->paginate(6);

        // $settings = Setting::first();

        return view('WebSite.services.all_group_services_standalone', compact('groupedServices'));
    }

    public function myAppointments(Request $request)
    {
        if (!Auth::guard('patient')->check()) {
        }

        $patient = Auth::guard('patient')->user();
        $patientId = $patient->id;

        $upcomingAppointments = Appointment::where('patient_id', $patientId)
            ->where('appointment', '>=', now()->startOfDay())
            ->whereIn('type', [
                Appointment::STATUS_PENDING,
                Appointment::STATUS_CONFIRMED
            ])
            ->with([ /* ... with clauses ... */])
            ->orderBy('appointment', 'asc')
            ->paginate(config('pagination.website_patient_appointments_upcoming', 6), ['*'], 'upcoming_page');

        $pastAppointmentsQuery = Appointment::where('patient_id', $patientId)
            ->where(function ($query) {
                $query->where('appointment', '<', now()->startOfDay())
                    ->orWhereIn('type', [
                        Appointment::STATUS_COMPLETED,
                        Appointment::STATUS_CANCELLED,
                        Appointment::STATUS_LAPSED,
                    ]);
            })
            ->with([ /* ... with clauses ... */])
            ->orderBy('appointment', 'desc');

        $pastAppointments = $pastAppointmentsQuery->paginate(config('pagination.website_patient_appointments_past', 6), ['*'], 'past_page');

        return view('WebSite.appointments.my_appointments_standalone', compact(
            'patient',
            'upcomingAppointments',
            'pastAppointments'
        ));
    }

    public function cancelAppointmentFromWebsite(Request $request, Appointment $appointment)
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


    public function myInvoices(Request $request)
    {
        if (!Auth::guard('patient')->check()) {
            session()->flash('info_notify', 'يرجى تسجيل الدخول أولاً لعرض فواتيرك.');
            return redirect()->route('login');
        }

        $patient = Auth::guard('patient')->user();


        $invoices = Invoice::where('patient_id', $patient->id)
            // ->where('invoice_type', 1)
            ->with([
                'Service',
                'Group',
                'Doctor' => function ($query) {
                    $query->withTranslation()->select('id');
                },
                'Section' => function ($query) {
                    $query->withTranslation()->select('id');
                }
            ])
            ->orderBy('invoice_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(config('pagination.website_patient_invoices', 10));

        return view('WebSite.invoices.my_invoices_standalone', compact('patient', 'invoices'));
    }

    public function printInvoice(Request $request, Invoice $invoice)
    {
        $patient = Auth::guard('patient')->user();

        if ($invoice->patient_id !== $patient->id) {
            abort(403, 'غير مصرح لك بعرض هذه الفاتورة.');
        }

        $invoice->load([
            'Service',
            'Group',
            'Doctor' => function ($query) {
                $query->withTranslation()->select('id');
            },
            'Section' => function ($query) {
                $query->withTranslation()->select('id');
            },
            'patient:id,email,Phone'
        ]);

        $hospitalSettings = [
            'name' => config('app.name', 'المنصة الطبية'),
            'logo' => asset('path/to/your/logo_for_print.png'),
            'address' => '123 شارع الصحة، مدينة العافية',
            'phone' => '012-345-6789',
            'email' => 'info@example.com',
            'tax_number' => '123456789012345',
        ];

        return view('WebSite.invoices.print_invoice_standalone', compact('invoice', 'hospitalSettings'));
    }

    public function myAccountStatement(Request $request)
    {
        if (!Auth::guard('patient')->check()) {
            session()->flash('info_notify', 'يرجى تسجيل الدخول أولاً لعرض كشف حسابك.');
            return redirect()->route('login');
        }

        $patient = Auth::guard('patient')->user();
        $receipts = ReceiptAccount::where('patient_id', $patient->id)
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(config('pagination.website_patient_receipts', 10));

        // $unpaid_invoices_total = Invoice::where('patient_id', $patient->id)
        //                                 ->where('type', 2) // آجل
        //                                 ->where('invoice_status', 1) // غير مدفوعة
        //                                 ->sum('total_with_tax');
        // $total_paid = $receipts->sum('amount'); // أو $patient->patient_accounts()->sum('credit') - $patient->patient_accounts()->sum('Debit')

        return view('WebSite.account.my_account_statement_standalone', compact(
            'patient',
            'receipts'
            // 'unpaid_invoices_total',
            // 'total_paid'
        ));
    }

    public function printReceipt(Request $request, ReceiptAccount $receiptAccount)
    {
        $currentUser = Auth::guard('patient')->user();

        if ($receiptAccount->patient_id !== $currentUser->id) {
            abort(403, 'غير مصرح لك بعرض هذا السند.');
        }

        $receiptAccount->load([
            'patients' => function ($query) {
                // $query->select('id', 'email', 'Phone');
                $query->withTranslation(app()->getLocale());
            }
        ]);


        $hospitalSettings = [
            'name' => config('app.name', 'المنصة الطبية'),
            'logo' => asset('path/to/your/logo_for_print.png'),
            'address' => '123 شارع الصحة، مدينة العافية',
            'phone' => '012-345-6789',
            'email' => 'info@example.com',
            'tax_number' => '123456789012345',
        ];

        return view('WebSite.account.print_receipt_standalone', compact('receiptAccount', 'hospitalSettings'));
    }
}
