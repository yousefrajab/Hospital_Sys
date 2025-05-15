<?php

namespace App\Http\Controllers\Dashboard; // تأكد من المسار الصحيح

use App\Models\Disease;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Admin\Disease\StoreDiseaseRequest;
use App\Http\Requests\Dashboard\Admin\Disease\UpdateDiseaseRequest;
use Illuminate\Http\Request; // لاستخدامها في index للبحث
use Illuminate\Support\Facades\Log;

class DiseaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Disease::query();

        if ($request->filled('search_disease')) {
            $query->where('name', 'like', '%' . $request->search_disease . '%')
                ->orWhere('description', 'like', '%' . $request->search_disease . '%');
        }
        if ($request->filled('is_chronic_filter')) {
            if ($request->is_chronic_filter !== 'all') { // 'all' لعرض الكل
                $query->where('is_chronic', (bool)$request->is_chronic_filter);
            }
        }

        $diseases = $query->orderBy('name')->paginate(40)->appends($request->query());

        Log::info("DiseaseController@index: Fetching diseases list.");
        return view('Dashboard.Diseases.index', compact('diseases', 'request'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Log::info("DiseaseController@create: Loading create disease form.");
        return view('Dashboard.Diseases.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDiseaseRequest $request)
    {
        try {
            Disease::create($request->validated());
            Log::info("DiseaseController@store: New disease created successfully.", $request->validated());
            return redirect()->route('admin.diseases.index')->with('success', 'تمت إضافة المرض بنجاح.');
        } catch (\Exception $e) {
            Log::error("DiseaseController@store: Error creating disease: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'حدث خطأ أثناء إضافة المرض.');
        }
    }

    /**
     * Display the specified resource.
     * (قد لا نحتاج لصفحة show منفصلة إذا كانت التفاصيل بسيطة وتظهر في edit)
     * ولكن سأقوم بإنشائها للكمال.
     */
    public function show(Disease $disease) // Route Model Binding
    {
        Log::info("DiseaseController@show: Displaying disease ID: {$disease->id}");
        return view('Dashboard.Diseases.show', compact('disease'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Disease $disease) // Route Model Binding
    {
        Log::info("DiseaseController@edit: Loading edit form for disease ID: {$disease->id}");
        return view('Dashboard.Diseases.edit', compact('disease'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDiseaseRequest $request, Disease $disease)
    {
        try {
            $validatedData = $request->validated();
            $disease->update($validatedData);

            Log::info("DiseaseController@update: Disease ID {$disease->id} ('{$disease->name}') updated successfully.", $validatedData);
            return redirect()->route('admin.diseases.index')->with('success', 'تم تعديل بيانات المرض بنجاح.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error("DiseaseController@update: Validation exception for Disease ID {$disease->id}.", ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error("DiseaseController@update: General exception for Disease ID {$disease->id}: " . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'حدث خطأ غير متوقع أثناء تعديل المرض.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Disease $disease) // Route Model Binding
    {
        try {
            // تحقق مما إذا كان المرض مرتبطًا بأي مرضى قبل الحذف
            // (cascadeOnDelete في patient_chronic_diseases سيهتم بحذف الارتباطات،
            // ولكن قد ترغب في منع حذف مرض لا يزال مسجلاً لمرضى)
            if ($disease->patientChronicRecords()->exists()) { // استخدام العلاقة التي عرفناها
                Log::warning("DiseaseController@destroy: Attempt to delete disease ID {$disease->id} ('{$disease->name}') which is linked to patients.");
                return redirect()->route('admin.diseases.index')
                    ->with('error', "لا يمكن حذف المرض '{$disease->name}' لأنه مسجل لبعض المرضى. قم بإزالته من سجلات المرضى أولاً.");
            }

            $diseaseName = $disease->name;
            $disease->delete();

            Log::info("DiseaseController@destroy: Disease '{$diseaseName}' (ID {$disease->id}) deleted successfully.");
            return redirect()->route('admin.diseases.index')->with('success', "تم حذف المرض '{$diseaseName}' بنجاح.");
        } catch (\Exception $e) {
            Log::error("DiseaseController@destroy: Error deleting disease ID {$disease->id}: " . $e->getMessage());
            return redirect()->route('admin.diseases.index')->with('error', 'حدث خطأ أثناء حذف المرض.');
        }
    }
}
