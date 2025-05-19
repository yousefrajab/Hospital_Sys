<?php

namespace App\Http\Controllers\Dashboard; // أو المسار الصحيح مثل Dashboard\PharmacyManager

use App\Models\Medication;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\PharmacyManager\Medication\StoreMedicationRequest;
use App\Http\Requests\Dashboard\PharmacyManager\Medication\UpdateMedicationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        }
        catch (\Exception $e) {
            Log::error("MedicationController@store: Error creating medication: " . $e->getMessage(), ['trace' => substr($e->getTraceAsString(),0,500)]);
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
        }
        catch (\Exception $e) {
            Log::error("MedicationController@update: Error updating medication ID {$medication->id}: " . $e->getMessage(), ['trace' => substr($e->getTraceAsString(),0,500)]);
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
            Log::error("MedicationController@destroy: Error deleting medication ID {$medication->id}: " . $e->getMessage(), ['trace' => substr($e->getTraceAsString(),0,500)]);
            return redirect()->route('admin.medications.index') // تأكد من اسم الـ route
                ->with('error', 'حدث خطأ أثناء حذف الدواء.');
        }
    }
}
