<?php

namespace App\Http\Controllers\Dashboard\PharmacyManager; // تأكد من المسار الصحيح

use App\Models\Medication;
use App\Models\PharmacyStock;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\PharmacyManager\Stock\StorePharmacyStockRequest;
use App\Http\Requests\Dashboard\PharmacyManager\Stock\UpdatePharmacyStockRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon; // لاستخدامه في فلتر انتهاء الصلاحية

class PharmacyStockController extends Controller
{
    /**
     * عرض دفعات المخزون لدواء معين.
     */
    public function index(Request $request, Medication $medication)
    {
        Log::info("PharmacyStockController@index: Fetching stock for Medication ID: {$medication->id}, Name: {$medication->name}");
        $query = $medication->stocks()->orderBy('expiry_date', 'asc'); // الأقدم تاريخ انتهاء يظهر أولاً

        if ($request->filled('batch_search')) {
            $query->where('batch_number', 'like', '%' . $request->batch_search . '%');
        }
        if ($request->filled('supplier_search')) { // ** فلتر جديد بالمورد **
            $query->where('supplier_name', 'like', '%' . $request->supplier_search . '%');
        }
        if ($request->filled('expired_filter')) {
            $warningDays = config('pharmacy.stock_expiry_warning_days', 90); // استخدام قيمة من config
            if ($request->expired_filter == 'not_expired') {
                $query->whereDate('expiry_date', '>', now());
            } elseif ($request->expired_filter == 'expired_soon') {
                $query->whereDate('expiry_date', '>', now())
                      ->whereDate('expiry_date', '<=', now()->addDays($warningDays));
            } elseif ($request->expired_filter == 'expired') {
                $query->whereDate('expiry_date', '<=', now());
            }
        }
        $stocks = $query->paginate(10)->appends($request->query());

        $totalQuantityOnHand = $medication->stocks()
                                        ->where('quantity_on_hand', '>', 0)
                                        ->whereDate('expiry_date', '>', now()) // الكمية الصالحة للاستخدام
                                        ->sum('quantity_on_hand');

        $expiryFilterOptions = [
            '' => 'الكل',
            'not_expired' => 'سارية الصلاحية',
            'expired_soon' => 'قريبة من الانتهاء (خلال ' . config('pharmacy.stock_expiry_warning_days', 90) . ' يوم)',
            'expired' => 'منتهية الصلاحية'
        ];
        return view('Dashboard.PharmacyManager.Stocks.index', compact(
            'medication',
            'stocks',
            'request',
            'expiryFilterOptions',
            'totalQuantityOnHand' // ** تمرير إجمالي الكمية **
        ));
    }

    /**
     * عرض فورم إضافة دفعة جديدة لدواء معين.
     */
    public function create(Medication $medication)
    {
        Log::info("PharmacyStockController@create: Loading create stock form for Medication ID: {$medication->id}");
        // يمكنك تمرير قائمة بالموردين إذا كان لديك جدول suppliers
        // $suppliers = \App\Models\Supplier::orderBy('name')->pluck('name', 'id');
        return view('Dashboard.PharmacyManager.Stocks.create', compact('medication' /*, 'suppliers' */));
    }

    /**
     * حفظ دفعة مخزون جديدة.
     */
    public function store(StorePharmacyStockRequest $request, Medication $medication)
    {
        try {
            $validatedData = $request->validated();
            // medication_id يتم تعيينه تلقائيًا بواسطة العلاقة أو من الـ route parameter
            // $validatedData['medication_id'] = $medication->id; // هذا ليس ضروريًا إذا استخدمت علاقة

            // استخدام العلاقة لإنشاء الدفعة الجديدة يربطها تلقائيًا بالدواء
            $medication->stocks()->create($validatedData);

            Log::info("PharmacyStockController@store: New stock created for Medication ID {$medication->id}", $validatedData);
            return redirect()->route('pharmacy_manager.medications.stocks.index', $medication->id)
                             ->with('success', 'تمت إضافة دفعة المخزون بنجاح.');
        } catch (\Exception $e) {
            Log::error("PharmacyStockController@store: Error creating stock for MedID {$medication->id}: " . $e->getMessage(), ['trace' => substr($e->getTraceAsString(),0,500)]);
            return redirect()->back()->withInput()->with('error', 'حدث خطأ أثناء إضافة دفعة المخزون: ' . $e->getMessage());
        }
    }

    /**
     * عرض تفاصيل دفعة مخزون معينة (عادة لا نحتاج صفحة show منفصلة للدفعة).
     */
    public function show(PharmacyStock $stock) // Route Model Binding لـ PharmacyStock
    {
        // سأوجه إلى صفحة تعديلها، أو يمكنك إنشاء view خاص بـ show إذا أردت
        $stock->load('medication');
        return view('Dashboard.PharmacyManager.Stocks.show', compact('stock')); // إنشاء view show إذا أردت
        // return redirect()->route('pharmacy_manager.stocks.edit', $stock->id);
    }

    /**
     * عرض فورم تعديل دفعة مخزون.
     */
    public function edit(PharmacyStock $stock) // Route Model Binding لـ PharmacyStock
    {
        $stock->load('medication'); // تحميل الدواء المرتبط
        $medication = $stock->medication;
        // $suppliers = \App\Models\Supplier::orderBy('name')->pluck('name', 'id'); // إذا كان لديك موردين
        Log::info("PharmacyStockController@edit: Loading edit form for Stock ID: {$stock->id} of Medication ID: {$medication->id}");
        return view('Dashboard.PharmacyManager.Stocks.edit', compact('stock', 'medication' /*, 'suppliers'*/));
    }

    /**
     * تحديث دفعة مخزون قائمة.
     */
    public function update(UpdatePharmacyStockRequest $request, PharmacyStock $stock) // Route Model Binding
    {
        try {
            $validatedData = $request->validated();
            // لا يتم تحديث medication_id عادة
            $stock->update($validatedData);

            Log::info("PharmacyStockController@update: Stock ID {$stock->id} updated successfully.", $validatedData);
            return redirect()->route('pharmacy_manager.medications.stocks.index', $stock->medication_id)
                             ->with('success', 'تم تعديل دفعة المخزون بنجاح.');
        } catch (\Exception $e) {
            Log::error("PharmacyStockController@update: Error updating stock ID {$stock->id}: " . $e->getMessage(), ['trace' => substr($e->getTraceAsString(),0,500)]);
            return redirect()->back()->withInput()->with('error', 'حدث خطأ أثناء تعديل دفعة المخزون.');
        }
    }

    /**
     * حذف دفعة مخزون.
     */
    public function destroy(PharmacyStock $stock) // Route Model Binding
    {
        try {
            // لا يمكنك حذف دفعة إذا كانت الكمية الحالية فيها ليست صفرًا (للحفاظ على السجلات)
            // أو إذا كانت مرتبطة بوصفات تم صرفها منها (أكثر تعقيدًا ويتطلب تتبعًا)
            if ($stock->quantity_on_hand > 0) {
                Log::warning("PharmacyStockController@destroy: Attempt to delete stock ID {$stock->id} with quantity > 0.");
                return redirect()->route('pharmacy_manager.medications.stocks.index', $stock->medication_id)
                                 ->with('error', 'لا يمكن حذف هذه الدفعة لأنها لا تزال تحتوي على كمية. قم بتعديل الكمية إلى صفر أولاً أو استخدم إجراء "إتلاف مخزون".');
            }

            $medicationId = $stock->medication_id;
            $batchNumber = $stock->batch_number ?? 'غير معروف';
            $stock->delete();

            Log::info("PharmacyStockController@destroy: Stock ID {$stock->id} (Batch: {$batchNumber}) deleted successfully.");
            return redirect()->route('pharmacy_manager.medications.stocks.index', $medicationId)
                             ->with('success', "تم حذف دفعة المخزون '{$batchNumber}' بنجاح.");
        } catch (\Exception $e) {
            Log::error("PharmacyStockController@destroy: Error deleting stock ID {$stock->id}: " . $e->getMessage(), ['trace' => substr($e->getTraceAsString(),0,500)]);
            return redirect()->route('pharmacy_manager.medications.stocks.index', $stock->medication_id ?? $this->fallbackMedicationId()) // Fallback
                             ->with('error', 'حدث خطأ أثناء حذف دفعة المخزون.');
        }
    }

    // (اختياري) دالة Fallback إذا كان $stock->medication_id غير متاح لسبب ما بعد الحذف
    private function fallbackMedicationId()
    {
        $firstMed = Medication::first();
        return $firstMed ? $firstMed->id : 0; // إرجاع 0 أو ID دواء افتراضي
    }
}
