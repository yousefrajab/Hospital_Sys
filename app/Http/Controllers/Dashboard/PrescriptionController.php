<?php

namespace App\Http\Controllers\Dashboard; // أو المسار المناسب

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Medication;
use App\Models\Prescription;
use Illuminate\Support\Carbon;
use App\Models\PrescriptionItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
// سننشئ هذا FormRequest
use Illuminate\Http\Request; // سنستبدله بـ FormRequest لاحقًا
use App\Http\Requests\Dashboard\Prescription\StorePrescriptionRequest;

class PrescriptionController extends Controller
{
    public function index(Request $request)
    {
        $doctorId = Auth::guard('doctor')->id();
        if (!$doctorId) {
            Log::error("PrescriptionController@index: Doctor not authenticated for doctor guard.");
            abort(403, 'غير مصرح لك بالوصول لهذه الصفحة. يرجى تسجيل الدخول كطبيب.');
        }

        Log::info("PrescriptionController@index: Fetching prescriptions for Doctor ID: {$doctorId}");

        // *** التعديل هنا: إزالة select() من with patient ***
        $query = Prescription::where('doctor_id', $doctorId)
            ->with(['patient.image']) // جلب علاقة المريض كاملة (الترجمة ستعمل تلقائيًا) وصورته
            ->orderBy('prescription_date', 'desc')
            ->orderBy('created_at', 'desc');

        if ($request->filled('search_prescription')) {
            $searchTerm = $request->search_prescription;
            Log::info("PrescriptionController@index: Filtering with search term: {$searchTerm} for Doctor ID: {$doctorId}");
            $query->where(function ($q) use ($searchTerm) {
                $q->where('prescription_number', 'like', "%{$searchTerm}%")
                    ->orWhereHas('patient', function ($patientQuery) use ($searchTerm) {
                        // البحث في الاسم المترجم للمريض
                        $patientQuery->whereTranslationLike('name', "%{$searchTerm}%")
                            ->orWhere('national_id', 'like', "%{$searchTerm}%");
                    });
            });
        }

        if ($request->filled('status_filter')) {
            $status = $request->status_filter;
            Log::info("PrescriptionController@index: Filtering by status: {$status} for Doctor ID: {$doctorId}");
            $query->where('status', $status);
        }

        if ($request->filled('date_from')) {
            $dateFrom = $request->date_from;
            // ... (بقية كود الفلترة بالتاريخ) ...
            try {
                $query->whereDate('prescription_date', '>=', \Carbon\Carbon::parse($dateFrom)->format('Y-m-d'));
            } catch (\Exception $e) {
                Log::warning("Invalid date_from format: {$dateFrom}");
            }
        }

        if ($request->filled('date_to')) {
            $dateTo = $request->date_to;
            // ... (بقية كود الفلترة بالتاريخ) ...
            try {
                $query->whereDate('prescription_date', '<=', \Carbon\Carbon::parse($dateTo)->format('Y-m-d'));
            } catch (\Exception $e) {
                Log::warning("Invalid date_to format: {$dateTo}");
            }
        }

        $prescriptions = $query->paginate(15)->appends($request->query());

        $prescriptionStatuses = [];
        if (class_exists(Prescription::class) && method_exists(Prescription::class, 'getStatusesForFilter')) {
            $prescriptionStatuses = Prescription::getStatusesForFilter();
        } else {
            Log::warning("Prescription model or getStatusesForFilter method not found. Status filter will be empty.");
        }

        // تأكد أن مسار الـ view هذا صحيح
        return view('Dashboard.Doctors.Prescriptions.index', compact('prescriptions', 'request', 'prescriptionStatuses'));
    }

    public function create(Request $request)
    {
        Log::info("PrescriptionController@create: Loading create prescription form with patient_id: " . $request->query('patient_id'));

        $patientId = $request->query('patient_id');
        $patient = null;

        if ($patientId) {
            $patient = Patient::with([
                'image',
                'diagnosedChronicDiseases', // .disease لجلب تفاصيل المرض من جدول diseases

            ])
                ->find($patientId);
        }

        if (!$patient) {
            Log::error("PrescriptionController@create: Patient not found for ID: " . ($patientId ?: 'Not Provided') . ". Redirecting to search.");
            return redirect()->route('doctor.patients.search_for_prescription') // تأكد من أن هذا المسار صحيح
                ->with('error', 'المريض المحدد غير موجود أو لم يتم توفيره. يرجى البحث عن مريض أولاً.');
        }

        $medications = Medication::where('status', 1)
            ->orderBy('name')
            ->select('id', 'name', 'strength', 'dosage_form', 'unit_of_measure')
            ->get()
            ->map(function ($med) {
                $dosageFormText = '';
                if ($med->dosage_form) {
                    if (method_exists(Medication::class, 'getCommonDosageForms')) {
                        $commonDosageForms = Medication::getCommonDosageForms();
                        $dosageFormText = $commonDosageForms[$med->dosage_form] ?? $med->dosage_form;
                    } else {
                        $dosageFormText = $med->dosage_form; // Fallback
                    }
                }

                $details = ($med->strength ? $med->strength : '');
                if ($details && $dosageFormText) {
                    $details .= ' - ' . $dosageFormText;
                } elseif ($dosageFormText) {
                    $details = $dosageFormText;
                }

                $med->display_text_for_select2 = $med->name . ($details ? " ({$details})" : '');
                $med->details_for_select2_dropdown = $details;
                return $med;
            });

        $doctor = Auth::guard('doctor')->user();

        Log::info("PrescriptionController@create: Successfully loaded data for prescription form. Patient ID: {$patient->id}");

        return view('Dashboard.Prescriptions.create', compact( // تأكد أن المسار صحيح
            'patient',
            'medications',
            'doctor'
        ));
    }

    public function store(StorePrescriptionRequest $request)
    {
        $validatedData = $request->validated();
        Log::info("PrescriptionController@store: Attempting to store new prescription.", ['patient_id' => $validatedData['patient_id'], 'items_count' => count($validatedData['items'] ?? [])]);

        DB::beginTransaction();
        try {
            $doctor = Auth::guard('doctor')->user();
            if (!$doctor) {
                Log::error("PrescriptionController@store: Doctor not authenticated.");
                DB::rollBack();
                return redirect()->back()->withInput()->with('error', 'خطأ في المصادقة، يرجى تسجيل الدخول مرة أخرى.');
            }

            $prescriptionData = [
                'patient_id' => $validatedData['patient_id'],
                'doctor_id' => $doctor->id,
                'prescription_date' => $validatedData['prescription_date'],
                'status' => $validatedData['status'] ?? Prescription::STATUS_NEW,
                'doctor_notes' => $validatedData['doctor_notes'] ?? null,
                'is_chronic_prescription' => $request->boolean('is_chronic_prescription'),
            ];

            $prescription = Prescription::create($prescriptionData);
            Log::info("Prescription created with ID: {$prescription->id} and Number: {$prescription->prescription_number}");

            if (isset($validatedData['items']) && is_array($validatedData['items'])) {
                $itemsToCreate = [];
                // استخدام $key مهم هنا للحصول على الفهرس الصحيح لـ is_prn
                foreach ($validatedData['items'] as $key => $itemData) {
                    if (!empty($itemData['medication_id']) && !empty($itemData['dosage']) && !empty($itemData['frequency'])) {
                        $itemsToCreate[] = new \App\Models\PrescriptionItem([ // استخدام FQCN
                            'medication_id' => $itemData['medication_id'],
                            'dosage' => $itemData['dosage'],
                            'frequency' => $itemData['frequency'],
                            'duration' => $itemData['duration'] ?? null,
                            'route_of_administration' => $itemData['route_of_administration'] ?? null,
                            'quantity_prescribed' => $itemData['quantity_prescribed'] ?? null,
                            'instructions_for_patient' => $itemData['instructions_for_patient'] ?? null,
                            'refills_allowed' => $itemData['refills_allowed'] ?? 0,
                            // الطريقة الصحيحة لجلب قيمة checkbox is_prn
                            'is_prn' => $request->input("items.{$key}.is_prn", false) ? true : false,
                        ]);
                    } else {
                        Log::warning("Skipping an item for Prescription ID: {$prescription->id} due to missing medication_id, dosage, or frequency.", $itemData);
                    }
                }
                if (!empty($itemsToCreate)) {
                    $prescription->items()->saveMany($itemsToCreate);
                    Log::info(count($itemsToCreate) . " prescription items added for Prescription ID: {$prescription->id}");
                }
            } else {
                Log::warning("No items found in request for Prescription ID: {$prescription->id}");
            }

            DB::commit();
            return redirect()->route('doctor.prescriptions.index') // تأكد أن هذا المسار صحيح
                ->with('success', "تم إنشاء الوصفة رقم {$prescription->prescription_number} بنجاح.");
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error("PrescriptionController@store: Validation exception.", ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("PrescriptionController@store: General error: " . $e->getMessage(), ['trace' => substr($e->getTraceAsString(), 0, 1000), 'request_data' => $request->all()]);
            return redirect()->back()->withInput()->with('error', 'حدث خطأ أثناء إنشاء الوصفة: ' . $e->getMessage());
        }
    }

    public function show(Prescription $prescription)
    {
        $prescription->load(['patient.image', 'doctor.image', 'items.medication', 'dispensedByPharmacyEmployee']);
        Log::info("PrescriptionController@show: Displaying Prescription ID: {$prescription->id}");
        // تأكد من أن مسار الـ view هذا صحيح
        return view('Dashboard.Doctors.Prescriptions.show', compact('prescription'));
    }

    public function edit(Prescription $prescription)
    {
        $doctor = Auth::guard('doctor')->user();
        // تحقق أن الطبيب الحالي هو من أنشأ الوصفة
        if (!$doctor || $doctor->id !== $prescription->doctor_id) {
            Log::warning("Unauthorized attempt to edit prescription ID {$prescription->id} by Doctor ID " . ($doctor->id ?? 'Unknown'));
            abort(403, 'غير مصرح لك بتعديل هذه الوصفة.');
        }

        $editableStatuses = [Prescription::STATUS_NEW, Prescription::STATUS_APPROVED];
        if (!in_array($prescription->status, $editableStatuses)) {
            Log::warning("Attempt to edit prescription ID {$prescription->id} with non-editable status '{$prescription->status}'.");
            $statusName = $prescription->status; // Fallback
            if (method_exists(Prescription::class, 'getStatusesForFilter')) {
                $statusName = (Prescription::getStatusesForFilter()[$prescription->status] ?? $prescription->status);
            }
            return redirect()->route('doctor.prescriptions.show', $prescription->id)
                ->with('error', 'لا يمكن تعديل هذه الوصفة لأن حالتها الحالية هي: ' . $statusName);
        }

        $prescription->load(['patient.image', 'items.medication']); // items.medication لجلب تفاصيل الدواء
        $patient = $prescription->patient;

        $medications = Medication::where('status', 1)
            ->orderBy('name')
            ->select('id', 'name', 'strength', 'dosage_form', 'unit_of_measure')
            ->get()
            ->map(function ($med) {
                $dosageFormText = $med->dosage_form ? (Medication::getCommonDosageForms()[$med->dosage_form] ?? $med->dosage_form) : '';
                $details = ($med->strength ? $med->strength : '');
                if ($details && $dosageFormText) $details .= ' - ' . $dosageFormText;
                elseif ($dosageFormText) $details = $dosageFormText;
                $med->display_text_for_select2 = $med->name . ($details ? " ({$details})" : '');
                $med->details_for_select2_dropdown = $details;
                return $med;
            });

        Log::info("PrescriptionController@edit: Loading edit form for Prescription ID: {$prescription->id}");
        return view('Dashboard.Prescriptions.edit', compact('prescription', 'patient', 'medications', 'doctor'));
    }

    public function update(StorePrescriptionRequest $request, Prescription $prescription)
    { // استخدام نفس StoreRequest أو إنشاء UpdateRequest
        $doctor = Auth::guard('doctor')->user();
        if (!$doctor || $doctor->id !== $prescription->doctor_id) {
            abort(403, 'غير مصرح لك بتعديل هذه الوصفة.');
        }
        $editableStatuses = [Prescription::STATUS_NEW, Prescription::STATUS_APPROVED];
        if (!in_array($prescription->status, $editableStatuses)) {
            $statusName = method_exists(Prescription::class, 'getStatusesForFilter')
                ? (Prescription::getStatusesForFilter()[$prescription->status] ?? $prescription->status)
                : ucfirst(str_replace('_', ' ', $prescription->status));
            return redirect()->route('doctor.prescriptions.show', $prescription->id)
                ->with('error', 'لا يمكن تعديل هذه الوصفة بسبب حالتها الحالية: ' . $statusName);
        }

        $validatedData = $request->validated();
        Log::info("PrescriptionController@update: Attempting to update prescription ID: {$prescription->id}", ['patient_id' => $validatedData['patient_id'], 'items_count' => count($validatedData['items'] ?? [])]);

        DB::beginTransaction();
        try {
            $prescription->update([
                'prescription_date' => $validatedData['prescription_date'],
                'status' => $validatedData['status'] ?? $prescription->status, // قد لا تريد السماح بتغيير الحالة من هنا مباشرة
                'doctor_notes' => $validatedData['doctor_notes'] ?? null,
                'is_chronic_prescription' => $request->boolean('is_chronic_prescription'),
            ]);

            // تحديث بنود الأدوية: حذف القديم وإضافة الجديد
            $prescription->items()->delete(); // احذف البنود القديمة
            if (isset($validatedData['items']) && is_array($validatedData['items'])) {
                $newItems = [];
                foreach ($validatedData['items'] as $key => $itemData) {
                    if (!empty($itemData['medication_id']) && !empty($itemData['dosage']) && !empty($itemData['frequency'])) {
                        $newItems[] = new \App\Models\PrescriptionItem([
                            'medication_id' => $itemData['medication_id'],
                            'dosage' => $itemData['dosage'],
                            'frequency' => $itemData['frequency'],
                            'duration' => $itemData['duration'] ?? null,
                            'route_of_administration' => $itemData['route_of_administration'] ?? null,
                            'quantity_prescribed' => $itemData['quantity_prescribed'] ?? null,
                            'instructions_for_patient' => $itemData['instructions_for_patient'] ?? null,
                            'refills_allowed' => $itemData['refills_allowed'] ?? 0,
                            'is_prn' => $request->input("items.{$key}.is_prn", false) ? true : false,
                        ]);
                    }
                }
                if (!empty($newItems)) {
                    $prescription->items()->saveMany($newItems); // إضافة البنود الجديدة
                    Log::info(count($newItems) . " prescription items (re)created for Prescription ID: {$prescription->id}");
                }
            }

            DB::commit();
            Log::info("Prescription ID: {$prescription->id} updated successfully.");
            return redirect()->route('doctor.prescriptions.show', $prescription->id)
                ->with('success', "تم تعديل الوصفة رقم {$prescription->prescription_number} بنجاح.");
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("PrescriptionController@update: General error for ID {$prescription->id}: " . $e->getMessage(), ['trace' => substr($e->getTraceAsString(), 0, 500)]);
            return redirect()->back()->withInput()->with('error', 'حدث خطأ أثناء تعديل الوصفة.');
        }
    }

    public function destroy(Prescription $prescription)
    {
        $doctor = Auth::guard('doctor')->user();
        if (!$doctor || $doctor->id !== $prescription->doctor_id) {
            abort(403, 'غير مصرح لك بهذا الإجراء.');
        }

        $cancellableStatuses = [
            Prescription::STATUS_NEW,
            Prescription::STATUS_APPROVED,
            Prescription::STATUS_PARTIALLY_DISPENSED,
            Prescription::STATUS_ON_HOLD
        ];

        if (!in_array($prescription->status, $cancellableStatuses)) {
            $statusName = method_exists(Prescription::class, 'getStatusesForFilter')
                ? (Prescription::getStatusesForFilter()[$prescription->status] ?? $prescription->status)
                : ucfirst(str_replace('_', ' ', $prescription->status));
            return redirect()->route('doctor.prescriptions.show', $prescription->id)
                ->with('error', 'لا يمكن إلغاء هذه الوصفة لأن حالتها الحالية هي: ' . $statusName . '.');
        }

        DB::beginTransaction();
        try {
            $prescriptionNumber = $prescription->prescription_number;
            $prescription->status = Prescription::STATUS_CANCELLED_BY_DOCTOR;
            $prescription->save();

            DB::commit();
            Log::info("Prescription ID {$prescription->id} (Number: {$prescriptionNumber}) cancelled by Doctor ID: {$doctor->id}.");
            return redirect()->route('doctor.prescriptions.index')
                ->with('success', "تم إلغاء الوصفة رقم {$prescriptionNumber} بنجاح.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error cancelling prescription ID {$prescription->id} by doctor: " . $e->getMessage());
            return redirect()->route('doctor.prescriptions.index')
                ->with('error', 'حدث خطأ أثناء إلغاء الوصفة.');
        }
    }
    public function adherenceDashboard(Request $request)
    {
        $doctorId = Auth::guard('doctor')->id();
        if (!$doctorId) {
            abort(403, 'غير مصرح لك بالوصول.');
        }
        Log::info("DoctorID {$doctorId} accessing adherence dashboard with filter: " . $request->input('filter_type'));

        // --- 1. بيانات البطاقات الإحصائية العلوية (تبقى كما هي تقريباً) ---
        $needsDecisionCount = Prescription::where('doctor_id', $doctorId)
            ->where('status', Prescription::STATUS_REFILL_REQUESTED) // مثال مبدئي
            // ... (أضف شروط أكثر دقة هنا إذا كان هناك تصعيد للطبيب)
            ->count();

        $nonCompliantGracePeriod = config('your_config_file.non_compliant_grace_days', 7); // مثال لقراءة من config
        $nonCompliantPatientsCount = Prescription::where('doctor_id', $doctorId)
            ->where('is_chronic_prescription', true)
            ->whereNotNull('next_refill_due_date')
            ->where('next_refill_due_date', '<', Carbon::today()->subDays($nonCompliantGracePeriod))
            ->whereNotIn('status', [
                Prescription::STATUS_REFILL_REQUESTED,
                Prescription::STATUS_DISPENSED, // قد تحتاج لإعادة النظر في هذا الشرط
                Prescription::STATUS_CANCELLED_BY_DOCTOR,
                Prescription::STATUS_EXPIRED
            ])
            ->distinct('patient_id')
            ->count();

        $upcomingRefillWindow = config('your_config_file.upcoming_refill_window_days', 7); // مثال
        $upcomingRefillsCount = Prescription::where('doctor_id', $doctorId)
            ->where('is_chronic_prescription', true)
            ->whereNotNull('next_refill_due_date')
            ->whereBetween('next_refill_due_date', [Carbon::today(), Carbon::today()->addDays($upcomingRefillWindow)])
            ->whereNotIn('status', [Prescription::STATUS_REFILL_REQUESTED, Prescription::STATUS_DISPENSED])
            ->count();

        // --- 2. بيانات قسم "متابعة التزام المرضى (الوصفات المزمنة)" ---
        $chronicPrescriptionsQuery = Prescription::where('doctor_id', $doctorId)
            ->where('is_chronic_prescription', true)
            ->whereIn('status', [ /* ... الحالات النشطة ... */
                Prescription::STATUS_NEW,
                Prescription::STATUS_APPROVED,
                Prescription::STATUS_PROCESSING,
                Prescription::STATUS_READY_FOR_PICKUP,
                Prescription::STATUS_DISPENSED,
                Prescription::STATUS_PARTIALLY_DISPENSED,
                Prescription::STATUS_REFILL_REQUESTED,
                Prescription::STATUS_ON_HOLD
            ])
            ->with([
                'patient' => function ($query) {
                    $query->select('id')->with('image');
                },
                'items' => function ($query) {
                    $query->select('prescription_id', 'refills_allowed', 'refills_done', 'medication_id')
                        ->with('medication:id,name');
                }
            ])
            ->withCount('items'); // لحساب items_count

        // ***** تطبيق الفلترة بناءً على البارامتر القادم من البطاقات *****
        $filter_type = $request->input('filter_type');
        $pageTitleSuffix = ''; // لتغيير عنوان الصفحة الفرعي بناءً على الفلتر

        if ($filter_type === 'needs_decision') {
            // هذا الفلتر سيوجه لقسم آخر لاحقاً، لكن مبدئياً يمكن أن يفلتر الوصفات التي STATUS_REFILL_REQUESTED
            $chronicPrescriptionsQuery->where('status', Prescription::STATUS_REFILL_REQUESTED);
            // ... أضف شروطاً أكثر دقة للطلبات التي تحتاج قرار الطبيب ...
            $pageTitleSuffix = ' - طلبات تحتاج قرارك';
        } elseif ($filter_type === 'non_compliant') {
            $chronicPrescriptionsQuery->whereNotNull('next_refill_due_date')
                ->where('next_refill_due_date', '<', Carbon::today()->subDays($nonCompliantGracePeriod))
                ->whereNotIn('status', [
                    Prescription::STATUS_REFILL_REQUESTED,
                    Prescription::STATUS_DISPENSED,
                    Prescription::STATUS_CANCELLED_BY_DOCTOR,
                    Prescription::STATUS_EXPIRED
                ]);
            $pageTitleSuffix = ' - مرضى بعدم التزام محتمل';
        } elseif ($filter_type === 'upcoming_refills') {
            $chronicPrescriptionsQuery->whereNotNull('next_refill_due_date')
                ->whereBetween('next_refill_due_date', [Carbon::today(), Carbon::today()->addDays($upcomingRefillWindow)])
                ->whereNotIn('status', [Prescription::STATUS_REFILL_REQUESTED, Prescription::STATUS_DISPENSED]);
            $pageTitleSuffix = ' - وصفات بتجديد قريب';
        }
        // إذا لم يتم تحديد فلتر، يعرض الكل (مرتبة بالأولوية)
        $chronicPrescriptionsQuery->orderByRaw("CASE
         WHEN next_refill_due_date IS NOT NULL AND next_refill_due_date < CURDATE() - INTERVAL {$nonCompliantGracePeriod} DAY THEN 1 /* الأكثر تأخراً أولاً */
         WHEN next_refill_due_date IS NOT NULL AND next_refill_due_date BETWEEN CURDATE() AND CURDATE() + INTERVAL {$upcomingRefillWindow} DAY THEN 2 /* القريبة ثانياً */
         WHEN status = '" . Prescription::STATUS_REFILL_REQUESTED . "' THEN 3 /* طلبات التجديد ثالثاً */
         ELSE 4
         END, next_refill_due_date ASC, updated_at DESC");


        $monitoredPrescriptions = $chronicPrescriptionsQuery->paginate(10, ['*'], 'monitored_page')->appends($request->except('monitored_page'));

        $monitoredPrescriptions->getCollection()->transform(function ($prescription) use ($nonCompliantGracePeriod, $upcomingRefillWindow) {
            $prescription->compliance_status_key = 'normal'; // normal, warning, danger, info
            $prescription->compliance_status = 'متابعة عادية';
            $prescription->compliance_badge_class = 'status-default';

            if ($prescription->status === Prescription::STATUS_REFILL_REQUESTED) {
                $prescription->compliance_status_key = 'info';
                $prescription->compliance_status = 'طلب تجديد مُرسل';
                $prescription->compliance_badge_class = 'status-refill_requested';
            } elseif ($prescription->next_refill_due_date) {
                $dueDate = Carbon::parse($prescription->next_refill_due_date);
                $today = Carbon::today();

                if ($dueDate->isPast() && $dueDate->diffInDays($today) > $nonCompliantGracePeriod) {
                    if (!in_array($prescription->status, [Prescription::STATUS_DISPENSED, Prescription::STATUS_CANCELLED_BY_DOCTOR, Prescription::STATUS_EXPIRED])) {
                        $prescription->compliance_status_key = 'danger';
                        $prescription->compliance_status = 'متأخر عن التجديد';
                        $prescription->compliance_badge_class = 'status-cancelled_by_doctor'; // Red
                    }
                } elseif ($dueDate->isBetween($today, $today->copy()->addDays($upcomingRefillWindow))) {
                    if (!in_array($prescription->status, [Prescription::STATUS_DISPENSED])) {
                        $prescription->compliance_status_key = 'warning';
                        $prescription->compliance_status = 'تجديد قريب';
                        $prescription->compliance_badge_class = 'status-pending_review'; // Yellow
                    }
                } elseif ($prescription->status === Prescription::STATUS_DISPENSED || $prescription->status === Prescription::STATUS_PARTIALLY_DISPENSED || $dueDate->isFuture()) {
                    $lastDispensed = $prescription->dispensed_at ?? $prescription->updated_at;
                    if ($lastDispensed && Carbon::parse($lastDispensed)->diffInDays($today) < (config('your_config_file.assumed_compliance_period_days', 45))) { //  فترة افتراضية للالتزام
                        $prescription->compliance_status_key = 'success';
                        $prescription->compliance_status = 'ملتزم (متابعة جيدة)';
                        $prescription->compliance_badge_class = 'status-dispensed'; // Green
                    }
                }
            }
            return $prescription;
        });


        return view('Dashboard.Doctors.Prescriptions.adherence_dashboard', compact(
            'needsDecisionCount',
            'nonCompliantPatientsCount',
            'upcomingRefillsCount',
            'monitoredPrescriptions',
            'request', // لتمرير الـ request للفلاتر إذا أضفتها
            'filter_type', // لتمرير نوع الفلتر للـ view لعرض عنوان مناسب
            'pageTitleSuffix'
        ));
    }
    public function approvalRequests(Request $request)
    {
        $doctorId = Auth::guard('doctor')->id();
        if (!$doctorId) { abort(403, 'غير مصرح لك بالوصول.'); }

        Log::info("DoctorID {$doctorId} accessing prescription refill approval requests page.");

        $query = Prescription::where('doctor_id', $doctorId)
            ->where('status', Prescription::STATUS_REFILL_REQUESTED) // فقط الطلبات التي أرسلها المريض
            // الشروط التي تجعل الطلب يحتاج موافقة الطبيب
            // سنبسطها مبدئياً، ويمكن تعقيدها لاحقاً لتشمل حالات التصعيد من الصيدلي الخ
            ->where(function ($q) {
                // الحالة 1: الوصفة لديها بنود، وجميع البنود المسموح لها بإعادة الصرف قد استنفدت
                $q->whereHas('items', function($itemQuery){
                    $itemQuery->havingRaw('SUM(refills_allowed) > 0 AND SUM(refills_done) >= SUM(refills_allowed)');
                }, '>=', 1) // يجب أن يكون هناك على الأقل بند واحد يطابق هذا الشرط
                // الحالة 2: الوصفة ليس لديها أي بنود مسموح لها بإعادة الصرف (refills_allowed كلها صفر أو null)
                // وهي وصفة مزمنة والمريض يطلب تجديداً
                ->orWhere(function ($subQ){
                     $subQ->where('is_chronic_prescription', true)
                          ->whereDoesntHave('items', function($itemQuery){
                              $itemQuery->where('refills_allowed', '>', 0);
                          });
                })
                // الحالة 3: (مثال لمستقبل) إذا انتهت صلاحية الوصفة - تحتاج حقل prescription_expiry_date
                // ->orWhere('prescription_expiry_date', '<', now())
                // الحالة 4: (مثال لمستقبل) إذا تم تصعيدها من الصيدلية - تحتاج حقل escalation_reason
                // ->orWhereNotNull('pharmacy_escalation_reason')
                ;
            })
            ->with([
                'patient' => function ($patientQuery) {
                    $patientQuery->select('id', 'name', 'gender', 'Date_Birth')->with('image');
                },
                'items.medication' => function ($medQuery){ // تحميل الأدوية لتفاصيل سريعة
                    $medQuery->select('id', 'name', 'strength', 'dosage_form');
                }
            ])
            ->withCount('items')
            ->orderBy('updated_at', 'desc'); // الطلبات الأحدث تعديلاً (وقت إرسال الطلب)

        // يمكنك إضافة فلاتر هنا (بحث باسم المريض، إلخ)
         if ($request->filled('search_patient_approval')) {
             $searchTerm = $request->search_patient_approval;
             $query->whereHas('patient', function($q) use ($searchTerm){
                 $q->whereTranslationLike('name', "%{$searchTerm}%")
                   ->orWhere('national_id', 'like', "%{$searchTerm}%");
             });
         }

        $approvalRequests = $query->paginate(10)->appends($request->query());

        return view('Dashboard.Doctors.Prescriptions.approval_requests', compact(
            'approvalRequests',
            'request'
        ));
    }
}
