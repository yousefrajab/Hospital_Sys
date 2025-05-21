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
        // تطبيق middleware المصادقة للمريض على جميع دوال هذا الكنترولر
        $this->middleware('auth:patient'); // تأكد أن 'patient' هو اسم الـ guard الصحيح
    }

    /**
     * عرض قائمة وصفات المريض.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request) // إضافة Request للتعامل مع الفلاتر
    {
        $patient = Auth::guard('patient')->user(); // التأكد من الـ guard الصحيح

        $query = $patient->prescriptions()
            ->with(['doctor:id']) // فقط اسم الطبيب إذا كان هذا كافيًا هنا
            ->latest('prescription_date');

        // تطبيق الفلاتر
        if ($request->filled('status_filter')) {
            $query->where('status', $request->status_filter);
        }
        if ($request->filled('date_from')) {
            try {
                $query->whereDate('prescription_date', '>=', \Carbon\Carbon::parse($request->date_from)->format('Y-m-d'));
            } catch (\Exception $e) { /* تجاهل التاريخ غير الصالح */
            }
        }
        if ($request->filled('date_to')) {
            try {
                $query->whereDate('prescription_date', '<=', \Carbon\Carbon::parse($request->date_to)->format('Y-m-d'));
            } catch (\Exception $e) { /* تجاهل التاريخ غير الصالح */
            }
        }

        $prescriptions = $query->paginate(10);

        // جلب حالات الوصفة للفلتر
        $prescriptionStatuses = [];
        if (class_exists(\App\Models\Prescription::class) && method_exists(\App\Models\Prescription::class, 'getStatusesForFilter')) {
            $prescriptionStatuses = \App\Models\Prescription::getStatusesForFilter();
            // يمكنك فلترة الحالات التي تهم المريض فقط هنا إذا أردت
            // مثال: استبعاد الحالات التي لا يجب أن يراها المريض في الفلتر
            $patientViewableStatuses = [
                \App\Models\Prescription::STATUS_NEW,
                \App\Models\Prescription::STATUS_APPROVED,
                \App\Models\Prescription::STATUS_PROCESSING, // حالة جديدة مقترحة
                \App\Models\Prescription::STATUS_READY_FOR_PICKUP, // حالة جديدة مقترحة
                \App\Models\Prescription::STATUS_DISPENSED,
                \App\Models\Prescription::STATUS_CANCELLED_BY_DOCTOR, // أو حالة إلغاء عامة
                \App\Models\Prescription::STATUS_EXPIRED,
                \App\Models\Prescription::STATUS_REFILL_REQUESTED, // حالة جديدة مقترحة
            ];
            $prescriptionStatuses = array_filter($prescriptionStatuses, function ($key) use ($patientViewableStatuses) {
                return in_array($key, $patientViewableStatuses);
            }, ARRAY_FILTER_USE_KEY);
        } else {
            Log::warning("Prescription model or getStatusesForFilter method not found. Status filter will be empty for patient pharmacy.");
        }


        // تمرير request لمزامنة الفلاتر مع الـ pagination
        return view('patient_dashboard.pharmacy.index', compact('prescriptions', 'prescriptionStatuses', 'request'));
    }


    public function show(\App\Models\Prescription $prescription) // استخدام Route Model Binding
    {
        $patient = Auth::guard('patient')->user();

        // 1. التأكد أن الوصفة تخص المريض المسجل دخوله
        if ($prescription->patient_id !== $patient->id) {
            // Log::warning("Patient ID {$patient->id} attempted to view prescription ID {$prescription->id} for patient ID {$prescription->patient_id}. Access denied.");
            return redirect()->route('patient.pharmacy.index')
                ->with('error', 'غير مصرح لك بعرض تفاصيل هذه الوصفة.');
        }

        // 2. تحميل العلاقات اللازمة لعرض كل التفاصيل (Eager Loading)
        $prescription->load([
            'doctor' => function ($query) {
                $query->select('id'); // يمكنك إضافة image إذا أردت عرض صورة الطبيب
            },
            'patient' => function ($query) { // معلومات المريض (قد لا تحتاجها كلها هنا إذا كان بروفايل المريض الحالي)
                $query->select('id',  'national_id', 'Date_Birth');
                //    $query->with('image'); // إذا أردت عرض صورة المريض
            },
            'items' => function ($query) {
                $query->with(['medication' => function ($medQuery) {
                    $medQuery->select('id', 'name', 'generic_name', 'strength', 'dosage_form', 'unit_of_measure');
                }]);
            },
            'dispensedByPharmacyEmployee' => function ($query) { // معلومات الصيدلي الذي صرفها (إذا صرفت)
                $query->select('id', 'name');
            }
            // يمكنك إضافة علاقة 'admission' إذا أردت عرض معلومات التنويم المرتبطة بالوصفة
        ]);

        // Log::info("Patient ID {$patient->id} is viewing prescription details for ID: {$prescription->id}");

        // تمرير متغير لتحديد اللغة في Flatpickr View
        $currentLocaleIsArabic = app()->getLocale() === 'ar';

        // تمرير البيانات إلى الـ view
        return view('patient_dashboard.pharmacy.show', compact('prescription', 'currentLocaleIsArabic'));
    }
    public function requestRefill(Request $request_from_form, Prescription $prescription)
    {
        $patient = Auth::guard('patient')->user();

        // 1. التأكيد أن الوصفة تابعة للمريض الحالي
        if ($prescription->patient_id !== $patient->id) {
            Log::warning("AUTH_FAIL: PatientID {$patient->id} tried to request refill for PrescriptionID {$prescription->id} of PatientID {$prescription->patient_id}.");
            return redirect()->back()->with('error', 'لا يمكنك طلب إعادة صرف لوصفة لا تخصك.');
        }

        // 2. استخدام الـ Accessor للتحقق مما إذا كان يمكن إعادة صرفها
        if (!$prescription->can_request_refill) { // الـAccessor سيتحقق من الحالة والبنود
            Log::warning("REFILL_DENIED: PatientID {$patient->id} tried to request refill for PrescriptionID {$prescription->id} which is not eligible. Status: {$prescription->status}.");
            return redirect()->route('patient.pharmacy.show', $prescription->id)
                ->with('error', 'هذه الوصفة غير قابلة لإعادة الصرف حاليًا أو يوجد طلب معلق بالفعل.');
        }

        DB::beginTransaction();
        try {
            // 3. تغيير حالة الوصفة إلى "طلب إعادة صرف"
            $oldStatus = $prescription->status;
            $prescription->status = Prescription::STATUS_REFILL_REQUESTED;
            // (اختياري) يمكنك تحديث تاريخ معين لطلب إعادة الصرف إذا كان لديك عمود لذلك
            // $prescription->last_refill_request_date = now();
            $prescription->save();

            Log::info("REFILL_REQUESTED: PatientID {$patient->id} requested refill for PrescriptionID {$prescription->id}. OldStatus: {$oldStatus}, NewStatus: {$prescription->status}.");

            // 4. إرسال إشعار للصيدلية و/أو الطبيب
            // هذا يتطلب نظام إشعارات (مثل Laravel Notifications)
            // مثال:
            // if (class_exists(\App\Notifications\Pharmacy\PrescriptionRefillRequested::class)) {
            //     // ابحث عن موظفي الصيدلية أو مدير الصيدلية لإرسال الإشعار لهم
            //     $pharmacyManagers = \App\Models\PharmacyManager::all(); // أو أي طريقة لجلبهم
            //     \Illuminate\Support\Facades\Notification::send($pharmacyManagers, new \App\Notifications\Pharmacy\PrescriptionRefillRequested($prescription, $patient));
            // }

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

        // جلب الوصفات التي حالتها 'refill_requested' أو 'processing' (إذا كانت الصيدلية تعالج الطلب)
        // والتي تخص المريض الحالي
        $query = $patient->prescriptions()
            ->whereIn('status', [
                Prescription::STATUS_REFILL_REQUESTED, // طلبات أرسلها المريض وتنتظر موافقة/مراجعة الصيدلية
                Prescription::STATUS_PROCESSING        // طلبات وافقت عليها الصيدلية وهي قيد التجهيز
                // يمكنك إضافة حالات أخرى إذا كان منطق عملك يتطلبها هنا
            ])
            ->with(['doctor:id']) // معلومات الطبيب الأساسية
            ->withCount('items')        // عدد الأدوية في كل وصفة
            ->orderBy('updated_at', 'desc'); // الأحدث تعديلاً (وقت إرسال الطلب أو بدء المعالجة)

        // يمكنك إضافة فلاتر بسيطة هنا إذا أردت (مثلاً، بالرقم المرجعي للوصفة)
        $pendingRefills = $query->paginate(10);

        // لا حاجة لـ $prescriptionStatuses هنا عادةً لأننا نعرض حالات محددة
        return view('patient_dashboard.pharmacy.pending_refills', compact('pendingRefills', 'request'));
    }
}
