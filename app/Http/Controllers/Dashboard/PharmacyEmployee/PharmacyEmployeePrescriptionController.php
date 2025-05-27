<?php

namespace App\Http\Controllers\Dashboard\PharmacyEmployee; // تأكد من المسار الصحيح

use App\Models\Patient;
use App\Models\Medication;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\PrescriptionItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\PharmacyStock; // مهم لعملية الصرف
// ستحتاج FormRequest لعملية الصرف لاحقًا
// use App\Http\Requests\Dashboard\PharmacyEmployee\DispensePrescriptionRequest;

class PharmacyEmployeePrescriptionController extends Controller
{
    /**
     * عرض قائمة بالوصفات التي تنتظر الصرف أو المعالجة.
     */
    public function index(Request $request)
    {
        $pharmacyEmployeeId = Auth::guard('pharmacy_employee')->id();
        Log::info("PharmacyEmployeePrescriptionController@index: Fetching pending prescriptions for PharmacyEmployee ID: {$pharmacyEmployeeId}");

        // قائمة الحالات التي يجب أن يراها الصيدلي كمهام معلقة
        $targetStatuses = [
            Prescription::STATUS_NEW,                   // وصفات جديدة تماماً من الطبيب
            Prescription::STATUS_APPROVED,              // وصفات معتمدة (قد تكون جديدة أو تم تحديثها بعد مراجعة)
            Prescription::STATUS_RENEWAL_APPROVED,      // <<<--- أضفنا هذه الحالة: وصفات وافق الطبيب على تجديدها
            Prescription::STATUS_PARTIALLY_DISPENSED,   // وصفات تم صرف جزء منها وتحتاج إكمال
            // يمكنك إضافة Prescription::STATUS_REFILL_REQUESTED هنا إذا كان الصيدلي هو أول من يراجع طلبات المرضى الروتينية
            // ولكن بناءً على نقاشنا، الطلبات التي تصل للطبيب هي التي تحتاج موافقته، ثم تعود للصيدلي
        ];

        $query = Prescription::whereIn('status', $targetStatuses)
            // الشرط لإظهار الوصفات التي لم يتم تعيينها لأحد أو لم يتم تعيينها لهذا الموظف
            // أو إذا تم تعيينها لهذا الموظف ولم يتم صرفها بالكامل بعد
            // هذا الشرط مُحسَّن قليلاً
            ->where(function ($q) use ($pharmacyEmployeeId) {
                $q->whereNull('dispensed_by_pharmacy_employee_id') // لم يتم تعيينها لأحد بعد
                    ->orWhere('dispensed_by_pharmacy_employee_id', $pharmacyEmployeeId); // أو معينة لهذا الموظف (وربما صرف جزء منها)
            })
            // استبعاد الوصفات التي تم صرفها بالكامل من قبل هذا الموظف (إذا كانت status لا تزال partially_dispensed لسبب ما)
            // هذا الشرط إضافي للسلامة، الحالة الأساسية `dispensed` يجب أن تزيلها من القائمة
            ->where(function ($q) use ($pharmacyEmployeeId) {
                $q->where('status', '!=', Prescription::STATUS_DISPENSED) // ليست مصروفة بالكامل
                    ->orWhere('dispensed_by_pharmacy_employee_id', '!=', $pharmacyEmployeeId); // أو لم يصرفها هذا الموظف
            })
            // للتأكد أننا لا نعرض وصفة معينة لموظف آخر وهي مكتملة الصرف
            ->where(function ($q) {
                $q->whereNull('dispensed_at') // لم تصرف بالكامل
                    ->orWhereIn('status', [Prescription::STATUS_PARTIALLY_DISPENSED, Prescription::STATUS_RENEWAL_APPROVED, Prescription::STATUS_APPROVED, Prescription::STATUS_NEW]); // أو أنها لا تزال في حالة تتطلب عملاً
            })
            ->with([
                'patient' => function ($patientQuery) { // معلومات المريض الأساسية مع الصورة
                    $patientQuery->select('id')->with('image:id,filename,imageable_id,imageable_type');
                },
                'doctor:id', // معلومات الطبيب الأساسية
                'items' // تحميل البنود لمعرفة عددها أو عرض ملخص سريع
            ])
            ->withCount('items') // لحساب عدد الأدوية
            ->orderBy('prescription_date', 'asc') // الأقدم تاريخ إصدار أولاً
            ->orderBy('updated_at', 'asc'); // ثم الأقدم تحديثاً (مهم للطلبات المجددة)

        // --- الفلاتر ---
        if ($request->filled('search_term')) {
            $searchTerm = $request->search_term;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('prescription_number', 'like', "%{$searchTerm}%")
                    ->orWhereHas('patient', fn($pq) => $pq->whereTranslationLike('name', "%{$searchTerm}%"))
                    ->orWhereHas('doctor', fn($dq) => $dq->whereTranslationLike('name', "%{$searchTerm}%"));
            });
            Log::info("Filter applied: search_term = {$searchTerm}");
        }
        if ($request->filled('prescription_status_filter')) { // اسم فلتر مختلف هنا لصفحة الصيدلي
            $statusFilter = $request->prescription_status_filter;
            if (in_array($statusFilter, $targetStatuses)) { // تأكد أن الحالة المطلوبة ضمن الحالات المستهدفة
                $query->where('status', $statusFilter);
                Log::info("Filter applied: prescription_status_filter = {$statusFilter}");
            }
        }
        if ($request->filled('date_from_filter')) { // اسم فلتر مختلف
            try {
                $dateFrom = Carbon::parse($request->date_from_filter)->startOfDay();
                $query->where('prescription_date', '>=', $dateFrom);
                Log::info("Filter applied: date_from_filter = {$dateFrom->toDateString()}");
            } catch (\Exception $e) {
                Log::warning("Invalid date_from_filter format: " . $request->date_from_filter);
            }
        }
        if ($request->filled('date_to_filter')) { // اسم فلتر مختلف
            try {
                $dateTo = Carbon::parse($request->date_to_filter)->endOfDay();
                $query->where('prescription_date', '<=', $dateTo);
                Log::info("Filter applied: date_to_filter = {$dateTo->toDateString()}");
            } catch (\Exception $e) {
                Log::warning("Invalid date_to_filter format: " . $request->date_to_filter);
            }
        }
        // --- نهاية الفلاتر ---

        $pendingPrescriptions = $query->paginate(config('pagination.default', 15)) // استخدام قيمة من config
            ->appends($request->query());

        // إحصائية لعدد الوصفات الجديدة/المجددة التي تنتظر أول إجراء
        $actionablePrescriptionsCount = Prescription::whereIn('status', [
            Prescription::STATUS_NEW,
            Prescription::STATUS_APPROVED,
            Prescription::STATUS_RENEWAL_APPROVED
        ])
            ->where(function ($q) use ($pharmacyEmployeeId) {
                $q->whereNull('dispensed_by_pharmacy_employee_id')
                    ->orWhere('dispensed_by_pharmacy_employee_id', $pharmacyEmployeeId);
            })
            ->count();
        // حالات الفلترة لواجهة المستخدم
        $statusesForFilterDropdown = [];
        if (class_exists(Prescription::class) && method_exists(Prescription::class, 'getStatusesForFilter')) {
            $allStatuses = Prescription::getStatusesForFilter();
            foreach ($targetStatuses as $statusKey) {
                if (isset($allStatuses[$statusKey])) {
                    $statusesForFilterDropdown[$statusKey] = $allStatuses[$statusKey];
                }
            }
        }


        return view('Dashboard.pharmacy_employee.Prescriptions.index', compact(
            'pendingPrescriptions',
            'request', // لتمرير الفلاتر للـ view إذا كنت ستعرضها
            'actionablePrescriptionsCount', // تمرير العدد الجديد لواجهة المستخدم
            'statusesForFilterDropdown' // تمرير الحالات للفلتر
        ));
    }
    public function showDispenseForm(Prescription $prescription)
    {
        $pharmacyEmployeeId = Auth::guard('pharmacy_employee')->id();
        Log::info("PharmacyEmployeePrescriptionController@showDispenseForm: PharmacyEmployee ID {$pharmacyEmployeeId} is viewing prescription ID {$prescription->id} for dispensing.");

        $prescription->load([
            'patient.image',
            'patient.diagnosedChronicDiseases',
            'dispensedByPharmacyEmployee:id',
            // 'patient.initial_allergies_text', // إذا كان لديك هذا الحقل في Patient
            'doctor:id',
            'items.medication' => function ($query) {
                $query->select('id', 'name', 'strength', 'dosage_form', 'unit_of_measure');
            }
            // *** تم تعليق السطر الذي يسبب المشكلة ***
            // 'items.dispensedEntries.pharmacyStock'
        ]);

        // جلب دفعات المخزون المتاحة لكل دواء في الوصفة
        $availableStocksByMedication = [];
        if ($prescription->items->isNotEmpty()) { // تأكد أن الوصفة تحتوي على بنود
            foreach ($prescription->items as $item) {
                if ($item->medication) { // تحقق من وجود الدواء للبند الحالي
                    $medicationId = $item->medication_id; // أو $item->medication->id
                    Log::info("Fetching stocks for Medication ID: {$medicationId}"); // للتتبع

                    $stocks = PharmacyStock::where('medication_id', $medicationId)
                        ->where('quantity_on_hand', '>', 0) // فقط الدفعات التي بها كمية
                        ->whereDate('expiry_date', '>', now()) // فقط الدفعات الصالحة
                        ->orderBy('expiry_date', 'asc') // FEFO - الأقرب انتهاءً أولاً
                        ->get();

                    Log::info("Found " . $stocks->count() . " stock batches for Medication ID: {$medicationId}");
                    $availableStocksByMedication[$medicationId] = $stocks;
                } else {
                    // هذا يعني أن $item->medication لم يتم تحميله بشكل صحيح
                    Log::warning("Medication object not loaded for prescription item ID: {$item->id} on prescription ID: {$prescription->id}. Cannot fetch stocks.");
                    $availableStocksByMedication[$item->medication_id] = collect(); // مصفوفة فارغة لهذا الدواء
                }
            }
        }
        // dd($availableStocksByMedication); // <<<--- !!! نقطة اختبار مهمة: هل هذه المصفوفة تحتوي على البيانات الصحيحة؟

        return view('Dashboard.pharmacy_employee.Prescriptions.dispense', compact(
            'prescription',
            'availableStocksByMedication'
        ));
    }

    /**
     * معالجة عملية صرف الوصفة.
     */
    public function processDispense(Request $request, Prescription $prescription) // لاحقًا DispensePrescriptionRequest
    {
        $pharmacyEmployeeId = Auth::guard('pharmacy_employee')->id();
        Log::info("PharmacyEmployeePrescriptionController@processDispense: PharmacyEmployee ID {$pharmacyEmployeeId} attempting to dispense Prescription ID: {$prescription->id}", $request->all());

        // --- قواعد التحقق الأساسية (يجب نقلها إلى FormRequest) ---
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.prescription_item_id' => 'required|exists:prescription_items,id',
            'items.*.dispensed_quantity' => 'required|integer|min:0', // يمكن أن تكون صفر إذا لم يتم صرف الدواء
            'items.*.pharmacy_stock_id' => 'nullable|required_if:items.*.dispensed_quantity,>,0|exists:pharmacy_stocks,id', // مطلوب إذا كانت الكمية المصروفة أكبر من صفر
            'pharmacy_notes_dispense' => 'nullable|string|max:1000',
        ], [
            'items.required' => 'يجب تحديد الأدوية التي سيتم صرفها.',
            'items.*.dispensed_quantity.required' => 'يجب تحديد الكمية المصروفة لكل دواء.',
            'items.*.dispensed_quantity.min' => 'الكمية المصروفة لا يمكن أن تكون أقل من صفر.',
            'items.*.pharmacy_stock_id.required_if' => 'يجب اختيار دفعة المخزون للدواء الذي سيتم صرفه.',
        ]);
        // --- نهاية قواعد التحقق ---


        DB::beginTransaction();
        try {
            $totalItems = $prescription->items->count();
            $fullyDispensedItemsCount = 0;
            $partiallyDispensedItemsCount = 0; // لتتبع إذا كان هناك أي صرف جزئي

            foreach ($request->items as $dispensedItemData) {
                $prescriptionItem = PrescriptionItem::find($dispensedItemData['prescription_item_id']);
                $dispensedQuantity = (int)$dispensedItemData['dispensed_quantity'];

                if (!$prescriptionItem || $prescriptionItem->prescription_id !== $prescription->id) {
                    Log::warning("Invalid prescription_item_id {$dispensedItemData['prescription_item_id']} for prescription {$prescription->id}");
                    continue; // تجاهل هذا البند إذا كان غير صالح
                }

                // (اختياري) يمكنك إضافة عمود 'quantity_dispensed_so_far' في prescription_items وتحديثه

                if ($dispensedQuantity > 0) {
                    if (empty($dispensedItemData['pharmacy_stock_id'])) {
                        DB::rollBack();
                        return redirect()->back()->withInput()->with('error', "يجب اختيار دفعة المخزون للدواء: " . $prescriptionItem->medication->name);
                    }

                    $stock = PharmacyStock::find($dispensedItemData['pharmacy_stock_id']);
                    if (!$stock || $stock->medication_id !== $prescriptionItem->medication_id) {
                        DB::rollBack();
                        return redirect()->back()->withInput()->with('error', "دفعة المخزون غير صالحة للدواء: " . $prescriptionItem->medication->name);
                    }
                    if ($stock->quantity_on_hand < $dispensedQuantity) {
                        DB::rollBack();
                        return redirect()->back()->withInput()->with('error', "الكمية المطلوبة من " . $prescriptionItem->medication->name . " (الدفعة: {$stock->batch_number}) غير متوفرة. المتوفر: {$stock->quantity_on_hand}");
                    }

                    // تحديث كمية المخزون
                    $stock->decrement('quantity_on_hand', $dispensedQuantity);
                    Log::info("Stock ID {$stock->id} (Med: {$stock->medication->name}) decremented by {$dispensedQuantity}. New QOH: {$stock->quantity_on_hand}");

                    // (متقدم) تسجيل في جدول dispensed_items
                    // DispensedItem::create([...]);
                }

                // تحديث حالة بند الوصفة (مثال بسيط)
                if ($dispensedQuantity >= ($prescriptionItem->quantity_prescribed ?? $dispensedQuantity)) { // إذا كانت الكمية الموصوفة غير محددة، افترض أن ما صرف هو المطلوب
                    $fullyDispensedItemsCount++;
                } elseif ($dispensedQuantity > 0) {
                    $partiallyDispensedItemsCount++;
                }
            }

            // تحديث حالة الوصفة الرئيسية
            if ($fullyDispensedItemsCount === $totalItems) {
                $prescription->status = Prescription::STATUS_DISPENSED;
            } elseif ($fullyDispensedItemsCount > 0 || $partiallyDispensedItemsCount > 0) {
                $prescription->status = Prescription::STATUS_PARTIALLY_DISPENSED;
            } else {
                // لم يتم صرف أي شيء، ربما تبقى الحالة كما هي أو تنتقل لـ on_hold
                // أو يمكنك منع هذا من خلال التحقق من الصحة إذا كان يجب صرف دواء واحد على الأقل
            }

            $prescription->pharmacy_notes = $request->input('pharmacy_notes_dispense', $prescription->pharmacy_notes);
            if ($prescription->status == Prescription::STATUS_DISPENSED && !$prescription->dispensed_at) {
                $prescription->dispensed_at = now();
                $prescription->dispensed_by_pharmacy_employee_id = $pharmacyEmployeeId;
            }
            $prescription->save();

            DB::commit();
            Log::info("Prescription ID {$prescription->id} processed. Status: {$prescription->status}");
            return redirect()->route('pharmacy_employee.prescriptions.index') // مسار قائمة وصفات الصيدلي
                ->with('success', "تمت معالجة صرف الوصفة رقم {$prescription->prescription_number} بنجاح.");
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error("Validation error during dispensing Prescription ID {$prescription->id}", ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error dispensing Prescription ID {$prescription->id}: " . $e->getMessage(), ['trace' => substr($e->getTraceAsString(), 0, 500)]);
            return redirect()->back()->withInput()->with('error', 'حدث خطأ أثناء عملية صرف الوصفة: ' . $e->getMessage());
        }
    }


    public function dispensedPrescriptions(Request $request)
    {
        $pharmacyEmployeeId = Auth::guard('pharmacy_employee')->id();
        Log::info("PharmacyEmployeePrescriptionController@dispensedPrescriptions: Fetching dispensed prescriptions for PharmacyEmployee ID: {$pharmacyEmployeeId}");

        $query = Prescription::where('status', Prescription::STATUS_DISPENSED)
            // يمكنك إضافة فلتر إذا أردت أن يرى الموظف فقط ما صرفه هو
            ->where('dispensed_by_pharmacy_employee_id', $pharmacyEmployeeId)
            ->with(['patient:id', 'doctor:id'])
            ->orderBy('dispensed_at', 'desc'); // الأحدث صرفًا أولاً

        // --- الفلاتر (مشابهة لدالة index) ---
        if ($request->filled('search_term')) {
            $searchTerm = $request->search_term;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('prescription_number', 'like', "%{$searchTerm}%")
                    ->orWhereHas('patient', fn($pq) => $pq->whereTranslationLike('name', "%{$searchTerm}%"))
                    ->orWhereHas('doctor', fn($dq) => $dq->whereTranslationLike('name', "%{$searchTerm}%"));
            });
        }
        if ($request->filled('date_filter_dispensed')) { // اسم بارامتر مختلف لتجنب التداخل
            try {
                $query->whereDate('dispensed_at', '=', \Carbon\Carbon::parse($request->date_filter_dispensed)->format('Y-m-d'));
            } catch (\Exception $e) { /* تجاهل */
            }
        }
        // --- نهاية الفلاتر ---

        $dispensedPrescriptions = $query->paginate(15)->appends($request->query());

        return view('Dashboard.pharmacy_employee.Prescriptions.dispensed_index', compact(
            'dispensedPrescriptions',
            'request'
        ));
    }

    /**
     * عرض قائمة بالوصفات التي هي قيد الانتظار.
     */
    public function onHoldPrescriptions(Request $request)
    {
        $pharmacyEmployeeId = Auth::guard('pharmacy_employee')->id();
        Log::info("PharmacyEmployeePrescriptionController@onHoldPrescriptions: Fetching on-hold prescriptions for PharmacyEmployee ID: {$pharmacyEmployeeId}");

        $query = Prescription::where('status', Prescription::STATUS_ON_HOLD)
            // يمكنك إضافة فلتر بالمستشفى/الفرع إذا كان نظامك يدعم ذلك
            ->with(['patient:id,name', 'doctor:id,name'])
            ->orderBy('updated_at', 'desc'); // الأحدث تعديلاً (تاريخ وضعها قيد الانتظار)

        // --- الفلاتر (مشابهة لدالة index) ---
        if ($request->filled('search_term')) {
            // ... (نفس منطق البحث)
            $searchTerm = $request->search_term;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('prescription_number', 'like', "%{$searchTerm}%")
                    ->orWhereHas('patient', fn($pq) => $pq->whereTranslationLike('name', "%{$searchTerm}%"))
                    ->orWhereHas('doctor', fn($dq) => $dq->whereTranslationLike('name', "%{$searchTerm}%"));
            });
        }
        if ($request->filled('date_filter_onhold')) { // اسم بارامتر مختلف
            try {
                $query->whereDate('updated_at', '=', \Carbon\Carbon::parse($request->date_filter_onhold)->format('Y-m-d'));
            } catch (\Exception $e) { /* تجاهل */
            }
        }
        // --- نهاية الفلاتر ---

        $onHoldPrescriptions = $query->paginate(15)->appends($request->query());

        return view('Dashboard.pharmacy_employee.Prescriptions.on_hold_index', compact(
            'onHoldPrescriptions',
            'request'
        ));
    }
}
