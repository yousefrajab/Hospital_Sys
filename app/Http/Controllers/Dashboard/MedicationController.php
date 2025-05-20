<?php

namespace App\Http\Controllers\Dashboard; // أو المسار الصحيح مثل Dashboard\PharmacyManager

use App\Models\Medication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Requests\Dashboard\PharmacyManager\Medication\StoreMedicationRequest;
use App\Http\Requests\Dashboard\PharmacyManager\Medication\UpdateMedicationRequest;

class MedicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Log::info("MedicationController@index: Fetching medications list.");
        $query = Medication::orderBy('name', 'asc'); // افترض أن name عمود عادي حاليًا

        if ($request->filled('search_medication')) {
            $searchTerm = $request->search_medication;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('generic_name', 'like', "%{$searchTerm}%")
                    ->orWhere('barcode', 'like', "%{$searchTerm}%"); // إضافة البحث بالباركود
            });
        }
        if ($request->filled('status_filter')) {
            $query->where('status', $request->status_filter);
        }
        if ($request->filled('category_filter')) {
            $query->where('category', $request->category_filter); // إذا كان category نصًا في DB
        }
        if ($request->filled('dosage_form_filter')) {
            $query->where('dosage_form', $request->dosage_form_filter);
        }
        if ($request->filled('requires_prescription_filter')) {
            $query->where('requires_prescription', $request->requires_prescription_filter);
        }


        $medications = $query->paginate(20)->appends($request->query());

        // جلب البيانات لـ dropdowns الفلترة
        $categories = Medication::getCommonCategories(); // من الموديل
        $dosageForms = Medication::getCommonDosageForms(); // من الموديل
        $statuses = ['1' => 'نشط', '0' => 'غير نشط'];
        $prescriptionRequirement = ['1' => 'يتطلب وصفة', '0' => 'لا يتطلب وصفة'];


        return view('Dashboard.PharmacyManager.Medications.index', compact(
            'medications',
            'request',
            'categories',
            'dosageForms',
            'statuses',
            'prescriptionRequirement'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Log::info("MedicationController@create: Loading create medication form.");

        // استخدام الدوال من الموديل لملء القوائم المنسدلة
        $categories = Medication::getCommonCategories();
        $dosage_forms = Medication::getCommonDosageForms();
        $units_of_measure = Medication::getCommonUnitsOfMeasure();
        // يمكنك إضافة الشركات المصنعة إذا أنشأت لها موديل وجدول
        // $manufacturers = \App\Models\Manufacturer::orderBy('name')->pluck('name', 'id');

        return view('Dashboard.PharmacyManager.Medications.create', compact(
            'categories',
            'dosage_forms',
            'units_of_measure'
            // 'manufacturers'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMedicationRequest $request) // استخدام FormRequest
    {
        try {
            $validatedData = $request->validated();

            // إذا كانت هناك حقول تحتاج لمعالجة خاصة (مثل الترجمة)، قم بها هنا
            // حاليًا، نفترض أن $validatedData جاهزة للإنشاء المباشر

            Medication::create($validatedData);

            Log::info("MedicationController@store: New medication created successfully.", $validatedData);
            // تأكد من اسم الـ route الصحيح هنا (الذي استخدمته في Route::resource)
            return redirect()->route('admin.medications.index') // أو 'pharmacy_manager.medications.index'
                ->with('success', 'تمت إضافة الدواء بنجاح.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // FormRequest يعالج هذا عادةً ويعيد التوجيه مع الأخطاء
            Log::error("MedicationController@store: Validation exception during medication creation.", ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error("MedicationController@store: Error creating medication: " . $e->getMessage(), ['trace' => substr($e->getTraceAsString(), 0, 500)]);
            return redirect()->back()->withInput()->with('error', 'حدث خطأ أثناء إضافة الدواء: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Medication $medication) // Route Model Binding
    {
        Log::info("MedicationController@show: Displaying medication ID {$medication->id}.");
        $medication->load('stocks'); // تحميل المخزون المرتبط

        // تحضير القيم المعروضة للـ enums أو الحقول النصية
        $categoryDisplay = Medication::getCommonCategories()[$medication->category] ?? $medication->category;
        $dosageFormDisplay = Medication::getCommonDosageForms()[$medication->dosage_form] ?? $medication->dosage_form;
        $unitDisplay = Medication::getCommonUnitsOfMeasure()[$medication->unit_of_measure] ?? $medication->unit_of_measure;

        return view('Dashboard.PharmacyManager.Medications.show', compact(
            'medication',
            'categoryDisplay',
            'dosageFormDisplay',
            'unitDisplay'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Medication $medication) // Route Model Binding
    {
        Log::info("MedicationController@edit: Loading edit form for medication ID {$medication->id}.");
        $categories = Medication::getCommonCategories();
        $dosage_forms = Medication::getCommonDosageForms();
        $units_of_measure = Medication::getCommonUnitsOfMeasure();
        // $manufacturers = \App\Models\Manufacturer::orderBy('name')->pluck('name', 'id');

        return view('Dashboard.PharmacyManager.Medications.edit', compact(
            'medication',
            'categories',
            'dosage_forms',
            'units_of_measure'
            // 'manufacturers'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMedicationRequest $request, Medication $medication) // Route Model Binding
    {
        try {
            $validatedData = $request->validated();
            $medication->update($validatedData);

            Log::info("MedicationController@update: Medication ID {$medication->id} updated successfully.", $validatedData);
            return redirect()->route('admin.medications.index') // تأكد من اسم الـ route
                ->with('success', 'تم تعديل بيانات الدواء بنجاح.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error("MedicationController@update: Validation exception for Med ID {$medication->id}.", ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error("MedicationController@update: Error updating medication ID {$medication->id}: " . $e->getMessage(), ['trace' => substr($e->getTraceAsString(), 0, 500)]);
            return redirect()->back()->withInput()->with('error', 'حدث خطأ أثناء تعديل الدواء: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Medication $medication) // Route Model Binding
    {
        try {
            // التحقق مما إذا كان الدواء مستخدمًا
            // (prescriptionItems هي العلاقة التي أضفناها في موديل Medication)
            // (stocks هي العلاقة مع PharmacyStock)
            if ($medication->prescriptionItems()->exists() || $medication->stocks()->where('quantity_on_hand', '>', 0)->exists()) {
                Log::warning("MedicationController@destroy: Attempt to delete medication ID {$medication->id} which is in use or has stock.");
                return redirect()->route('admin.medications.index') // تأكد من اسم الـ route
                    ->with('error', 'لا يمكن حذف هذا الدواء لأنه مستخدم في وصفات حالية أو لديه رصيد في المخزون.');
            }

            $medicationName = $medication->name; // افترض أن name هو الاسم الأساسي
            $medication->delete();

            Log::info("MedicationController@destroy: Medication '{$medicationName}' (ID {$medication->id}) deleted successfully.");
            return redirect()->route('admin.medications.index') // تأكد من اسم الـ route
                ->with('success', "تم حذف الدواء '{$medicationName}' بنجاح.");
        } catch (\Exception $e) {
            Log::error("MedicationController@destroy: Error deleting medication ID {$medication->id}: " . $e->getMessage(), ['trace' => substr($e->getTraceAsString(), 0, 500)]);
            return redirect()->route('admin.medications.index') // تأكد من اسم الـ route
                ->with('error', 'حدث خطأ أثناء حذف الدواء.');
        }
    }
    // public function searchIndex(Request $request)
    // {
    //     Log::info("MedicationController@searchIndexForPharmacy: Fetching medications list for pharmacy employee search.");

    //     $query = Medication::where('status', 1) // عرض الأدوية النشطة فقط لموظف الصيدلية عادةً
    //         ->withSum(['stocks' => function ($query) {
    //             $query->where('quantity_on_hand', '>', 0)
    //                 ->whereDate('expiry_date', '>', now());
    //         }], 'quantity_on_hand') // لحساب إجمالي الكمية المتاحة
    //         ->with(['stocks' => function ($query) { // لتحميل تفاصيل الدفعات إذا أردت عرضها مباشرة
    //             $query->where('quantity_on_hand', '>', 0)
    //                 ->whereDate('expiry_date', '>', now())
    //                 ->orderBy('expiry_date', 'asc');
    //         }])
    //         ->orderBy('name', 'asc');

    //     if ($request->filled('search_medication_pharmacy')) { // استخدم اسم بارامتر مختلف للبحث لتجنب التعارض
    //         $searchTerm = $request->search_medication_pharmacy;
    //         $query->where(function ($q) use ($searchTerm) {
    //             $q->where('name', 'like', "%{$searchTerm}%")
    //                 ->orWhere('generic_name', 'like', "%{$searchTerm}%")
    //                 ->orWhere('barcode', 'like', "%{$searchTerm}%");
    //         });
    //     }
    //     if ($request->filled('category_filter_pharmacy')) {
    //         $query->where('category', $request->category_filter_pharmacy);
    //     }
    //     // يمكنك إضافة فلاتر أخرى إذا لزم الأمر

    //     $medications = $query->paginate(15)->appends($request->query());

    //     $categories = Medication::getCommonCategories(); // لفلتر التصنيف

    //     // تأكد أن مسار الـ view هذا صحيح أو قم بإنشائه
    //     return view('Dashboard.pharmacy_employee.Medications.search', compact(
    //         'medications',
    //         'request',
    //         'categories'
    //     ));
    // }

    public function searchIndexForPharmacy(Request $request) // تم تغيير اسم الدالة ليتطابق مع المسار
    {
        Log::info("MedicationController@searchIndexForPharmacy: Pharmacy employee searching for medications.");

        $query = Medication::where('status', 1) // عرض الأدوية النشطة فقط
            ->orderBy('name', 'asc');

        // فلتر البحث بالاسم، الاسم العلمي، الباركود
        if ($request->filled('search_medication_term')) { // <<-- تعديل اسم البارامتر ليكون فريدًا
            $searchTerm = $request->search_medication_term;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('generic_name', 'like', "%{$searchTerm}%")
                    ->orWhere('barcode', 'like', "%{$searchTerm}%");
            });
        }

        // فلتر حسب التصنيف
        if ($request->filled('category_filter')) { // <<-- تعديل اسم البارامتر
            $query->where('category', $request->category_filter);
        }

        // جلب كل النتائج المطابقة للفلاتر الأولية قبل تطبيق map
        $allFilteredMedications = $query->get();

        // معالجة البيانات لحساب الكميات وحالة الصلاحية والمخزون
        $medicationsProcessed = $allFilteredMedications->map(function ($medication) {
            $activeStocks = $medication->stocks() // افترض وجود علاقة 'stocks' في موديل Medication
                ->where('quantity_on_hand', '>', 0)
                ->whereDate('expiry_date', '>', now()->startOfDay()) // الصالحة فقط
                ->get();

            $medication->total_available_quantity = $activeStocks->sum('quantity_on_hand');
            $medication->nearest_expiry_date = $activeStocks->isNotEmpty() ? $activeStocks->min('expiry_date') : null;

            // تحديد حالة المخزون
            if ($medication->total_available_quantity == 0) {
                $medication->stock_status_key = 'out_of_stock'; // مفتاح للفلترة
                $medication->stock_status_text = 'نفذت الكمية';
                $medication->stock_status_class = 'danger'; // كلاس CSS
            } elseif ($medication->total_available_quantity <= $medication->minimum_stock_level) {
                $medication->stock_status_key = 'low_stock';
                $medication->stock_status_text = 'منخفض المخزون';
                $medication->stock_status_class = 'warning';
            } else {
                $medication->stock_status_key = 'in_stock';
                $medication->stock_status_text = 'متوفر';
                $medication->stock_status_class = 'success';
            }

            // تحديد حالة الصلاحية
            $medication->expiry_status_key = 'no_valid_stock'; // افتراضي
            $medication->expiry_status_text = 'لا دفعات صالحة';
            $medication->expiry_status_class = 'secondary';
            if ($medication->nearest_expiry_date) {
                $expiryDateCarbon = \Carbon\Carbon::parse($medication->nearest_expiry_date);
                $warningDays = config('pharmacy.stock_expiry_warning_days', 90); // من ملف config
                if ($expiryDateCarbon->isPast()) { // نظريًا لن يحدث بسبب فلتر whereDate أعلاه
                    $medication->expiry_status_key = 'expired';
                    $medication->expiry_status_text = 'منتهي الصلاحية';
                    $medication->expiry_status_class = 'dark';
                } elseif ($expiryDateCarbon->isBefore(now()->addDays($warningDays))) {
                    $medication->expiry_status_key = 'expired_soon';
                    $medication->expiry_status_text = 'قريب الانتهاء';
                    $medication->expiry_status_class = 'warning';
                } else {
                    $medication->expiry_status_key = 'valid';
                    $medication->expiry_status_text = 'صلاحية جيدة';
                    $medication->expiry_status_class = 'success';
                }
            }
            return $medication;
        });

        // فلترة إضافية بناءً على حالة المخزون (بعد حسابها)
        if ($request->filled('stock_status_filter')) {
            $stockStatusFilter = $request->stock_status_filter;
            $medicationsProcessed = $medicationsProcessed->filter(function ($medication) use ($stockStatusFilter) {
                return $medication->stock_status_key === $stockStatusFilter;
            });
        }

        // فلترة إضافية بناءً على حالة الصلاحية (بعد حسابها)
        if ($request->filled('expiry_status_filter')) {
            $expiryStatusFilter = $request->expiry_status_filter;
            $medicationsProcessed = $medicationsProcessed->filter(function ($medication) use ($expiryStatusFilter) {
                return $medication->expiry_status_key === $expiryStatusFilter;
            });
        }

        // تطبيق الترقيم يدويًا
        $perPage = 15; // عدد العناصر في كل صفحة
        $currentPage = Paginator::resolveCurrentPage('page'); // الحصول على الصفحة الحالية
        $currentPageItems = $medicationsProcessed->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $medications = new LengthAwarePaginator($currentPageItems, $medicationsProcessed->count(), $perPage, $currentPage, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);


        $categories = Medication::getCommonCategories();
        $stockStatusOptions = [
            '' => 'كل حالات المخزون',
            'in_stock' => 'متوفر',
            'low_stock' => 'منخفض المخزون',
            'out_of_stock' => 'نفذت الكمية',
        ];
        $expiryWarningDaysConfig = config('pharmacy.stock_expiry_warning_days', 90);
        $expiryStatusOptions = [
            '' => 'كل حالات الصلاحية',
            'valid' => 'صلاحية جيدة',
            'expired_soon' => "قريبة الانتهاء (خلال {$expiryWarningDaysConfig} يوم)",
            'no_valid_stock' => 'لا توجد دفعات صالحة',
            // يمكنك إضافة 'expired' إذا كنت ستعرض الأدوية منتهية الصلاحية من دفعات غير فعالة
        ];

        return view('Dashboard.pharmacy_employee.Medications.search', compact(
            'medications',
            'request',
            'categories',
            'stockStatusOptions',
            'expiryStatusOptions'
        ));
    }
}
