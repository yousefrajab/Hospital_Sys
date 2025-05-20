<?php

namespace App\Http\Controllers\Dashboard\PharmacyEmployee; // تأكد من المسار الصحيح

use App\Models\Patient;
use App\Models\Medication;
use App\Models\Prescription;
use App\Models\PharmacyStock; // مهم لعملية الصرف
use App\Models\PrescriptionItem;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
// ستحتاج FormRequest لعملية الصرف لاحقًا
// use App\Http\Requests\Dashboard\PharmacyEmployee\DispensePrescriptionRequest;

class PharmacyEmployeePrescriptionController extends Controller
{
    /**
     * عرض قائمة بالوصفات التي تنتظر الصرف أو المعالجة.
     */
    public function index(Request $request)
    {
        $pharmacyEmployeeId = Auth::guard('pharmacy_employee')->id(); // الموظف المسجل حاليًا
        Log::info("PharmacyEmployeePrescriptionController@index: Fetching pending prescriptions for PharmacyEmployee ID: {$pharmacyEmployeeId}");

        // جلب الوصفات التي حالتها 'approved' أو 'new' (حسب نظامك)
        // والتي لم يتم صرفها بالكامل بعد أو لم يتم إلغاؤها
        $query = Prescription::whereIn('status', [Prescription::STATUS_APPROVED, Prescription::STATUS_NEW, Prescription::STATUS_PARTIALLY_DISPENSED])
            ->where(function ($q) { // إذا أردت استثناء الوصفات التي صرفها هذا الموظف بالفعل بالكامل
                $q->where('dispensed_by_pharmacy_employee_id', '!=', Auth::guard('pharmacy_employee')->id())
                    ->orWhereNull('dispensed_by_pharmacy_employee_id');
            })
            ->with(['patient:id', 'doctor:id']) // معلومات أساسية
            ->orderBy('prescription_date', 'asc'); // الأقدم أولاً

        // --- الفلاتر ---
        if ($request->filled('search_term')) {
            $searchTerm = $request->search_term;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('prescription_number', 'like', "%{$searchTerm}%")
                    ->orWhereHas('patient', fn($pq) => $pq->whereTranslationLike('name', "%{$searchTerm}%"))
                    ->orWhereHas('doctor', fn($dq) => $dq->whereTranslationLike('name', "%{$searchTerm}%"));
            });
        }
        if ($request->filled('date_filter')) {
            try {
                $query->whereDate('prescription_date', '=', \Carbon\Carbon::parse($request->date_filter)->format('Y-m-d'));
            } catch (\Exception $e) { /* تجاهل التاريخ غير الصالح */
            }
        }
        // --- نهاية الفلاتر ---

        $pendingPrescriptions = $query->paginate(15)->appends($request->query());

        // يمكنك تمرير إحصائيات بسيطة إذا أردت
        $newPrescriptionsCount = Prescription::where('status', Prescription::STATUS_NEW)->count(); // مثال

        return view('Dashboard.pharmacy_employee.Prescriptions.index', compact(
            'pendingPrescriptions',
            'request',
            'newPrescriptionsCount'
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
