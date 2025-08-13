<?php

namespace App\Http\Controllers\Patient;

use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PatientPharmacyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:patient');
    }

    public function index(Request $request)
    {
        $patient = Auth::guard('patient')->user();

        $query = $patient->prescriptions()
            ->with(['doctor:id'])
            ->latest('prescription_date');

        if ($request->filled('status_filter')) {
            $query->where('status', $request->status_filter);
        }
        if ($request->filled('date_from')) {
            try {
                $query->whereDate('prescription_date', '>=', \Carbon\Carbon::parse($request->date_from)->format('Y-m-d'));
            } catch (\Exception $e) {
            }
        }
        if ($request->filled('date_to')) {
            try {
                $query->whereDate('prescription_date', '<=', \Carbon\Carbon::parse($request->date_to)->format('Y-m-d'));
            } catch (\Exception $e) {
            }
        }

        $prescriptions = $query->paginate(10);

        $prescriptionStatuses = [];
        if (class_exists(\App\Models\Prescription::class) && method_exists(\App\Models\Prescription::class, 'getStatusesForFilter')) {
            $prescriptionStatuses = \App\Models\Prescription::getStatusesForFilter();
            $patientViewableStatuses = [
                \App\Models\Prescription::STATUS_NEW,
                \App\Models\Prescription::STATUS_APPROVED,
                \App\Models\Prescription::STATUS_PROCESSING,
                \App\Models\Prescription::STATUS_READY_FOR_PICKUP,
                \App\Models\Prescription::STATUS_DISPENSED,
                \App\Models\Prescription::STATUS_CANCELLED_BY_DOCTOR,
                \App\Models\Prescription::STATUS_EXPIRED,
                \App\Models\Prescription::STATUS_REFILL_REQUESTED,
            ];
            $prescriptionStatuses = array_filter($prescriptionStatuses, function ($key) use ($patientViewableStatuses) {
                return in_array($key, $patientViewableStatuses);
            }, ARRAY_FILTER_USE_KEY);
        } else {
            Log::warning("Prescription model or getStatusesForFilter method not found. Status filter will be empty for patient pharmacy.");
        }


        return view('patient_dashboard.pharmacy.index', compact('prescriptions', 'prescriptionStatuses', 'request'));
    }


    public function show(\App\Models\Prescription $prescription)
    {
        $patient = Auth::guard('patient')->user();

        if ($prescription->patient_id !== $patient->id) {
            // Log::warning("Patient ID {$patient->id} attempted to view prescription ID {$prescription->id} for patient ID {$prescription->patient_id}. Access denied.");
            return redirect()->route('patient.pharmacy.index')
                ->with('error', 'غير مصرح لك بعرض تفاصيل هذه الوصفة.');
        }

        $prescription->load([
            'doctor' => function ($query) {
                $query->select('id');
            },
            'patient' => function ($query) {
                $query->select('id',  'national_id', 'Date_Birth');
                //    $query->with('image');
            },
            'items' => function ($query) {
                $query->with(['medication' => function ($medQuery) {
                    $medQuery->select('id', 'name', 'generic_name', 'strength', 'dosage_form', 'unit_of_measure');
                }]);
            },
            'dispensedByPharmacyEmployee' => function ($query) {
                $query->select('id', 'name');
            }
        ]);

        // Log::info("Patient ID {$patient->id} is viewing prescription details for ID: {$prescription->id}");

        $currentLocaleIsArabic = app()->getLocale() === 'ar';

        return view('patient_dashboard.pharmacy.show', compact('prescription', 'currentLocaleIsArabic'));
    }
    public function requestRefill(Request $request_from_form, Prescription $prescription)
    {
        $patient = Auth::guard('patient')->user();

        if ($prescription->patient_id !== $patient->id) {
            Log::warning("AUTH_FAIL: PatientID {$patient->id} tried to request refill for PrescriptionID {$prescription->id} of PatientID {$prescription->patient_id}.");
            return redirect()->back()->with('error', 'لا يمكنك طلب إعادة صرف لوصفة لا تخصك.');
        }

        if (!$prescription->can_request_refill) {
            Log::warning("REFILL_DENIED: PatientID {$patient->id} tried to request refill for PrescriptionID {$prescription->id} which is not eligible. Status: {$prescription->status}.");
            return redirect()->route('patient.pharmacy.show', $prescription->id)
                ->with('error', 'هذه الوصفة غير قابلة لإعادة الصرف حاليًا أو يوجد طلب معلق بالفعل.');
        }

        DB::beginTransaction();
        try {
            $oldStatus = $prescription->status;
            $prescription->status = Prescription::STATUS_REFILL_REQUESTED;
            // $prescription->last_refill_request_date = now();
            $prescription->save();

            Log::info("REFILL_REQUESTED: PatientID {$patient->id} requested refill for PrescriptionID {$prescription->id}. OldStatus: {$oldStatus}, NewStatus: {$prescription->status}.");

            DB::commit();

            return redirect()->route('patient.pharmacy.show', $prescription->id)
                ->with('success', 'تم إرسال طلب إعادة صرف الوصفة بنجاح. سيتم مراجعته من قبل الصيدلية.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("REFILL_ERROR: PatientID {$patient->id} - Error requesting refill for PrescriptionID {$prescription->id}: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'حدث خطأ أثناء معالجة طلب إعادة الصرف. يرجى المحاولة مرة أخرى.');
        }
    }

    public function pendingRefillRequests(Request $request)
    {
        $patient = Auth::guard('patient')->user();
        Log::info("Patient ID {$patient->id} is viewing their pending refill requests.");
        $query = $patient->prescriptions()
            ->whereIn('status', [
                Prescription::STATUS_REFILL_REQUESTED,
                Prescription::STATUS_PROCESSING
            ])
            ->with(['doctor:id'])
            ->withCount('items')
            ->orderBy('updated_at', 'desc');

        $pendingRefills = $query->paginate(10);

        return view('patient_dashboard.pharmacy.pending_refills', compact('pendingRefills', 'request'));
    }
}
