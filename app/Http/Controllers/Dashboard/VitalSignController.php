<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\VitalSign;
use Illuminate\Http\Request;
use App\Models\PatientAdmission;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Dashboard\StoreVitalSignRequest;

class VitalSignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreVitalSignRequest $request, PatientAdmission $patientAdmission) // تغيير نوع الـ Request
    {
        // لم نعد بحاجة لـ $request->validate() هنا، الـ Form Request سيقوم بذلك.
        try {
            $vitalSign = new VitalSign($request->validated()); // استخدم validated()
            $vitalSign->patient_admission_id = $patientAdmission->id;
            $vitalSign->recorded_by_user_id = Auth::id();
            $vitalSign->save();

            Log::info("Vital signs recorded for admission ID: {$patientAdmission->id} by user ID: " . Auth::id());
            return redirect()->route('admin.patient_admissions.vital_signs_sheet', $patientAdmission->id)
                ->with('success', 'تم تسجيل العلامات الحيوية بنجاح.');
        } catch (\Exception $e) {
            Log::error("Error recording vital signs for admission ID: {$patientAdmission->id}. Error: " . $e->getMessage());
            // في حالة الـ Form Request، إذا فشل التحقق، سيتم إعادة التوجيه تلقائياً مع الأخطاء إلى الـ errorBag المحدد.
            // لذا هذا الـ catch سيتعامل مع الأخطاء الأخرى غير المتعلقة بالتحقق.
            return redirect()->back()
                ->with('error', 'حدث خطأ عام أثناء تسجيل العلامات الحيوية: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\VitalSign  $vitalSign
     * @return \Illuminate\Http\Response
     */
    public function show(VitalSign $vitalSign)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\VitalSign  $vitalSign
     * @return \Illuminate\Http\Response
     */
    public function edit(VitalSign $vitalSign) // استخدام Route Model Binding
    {
        // تحميل معلومات الإقامة والمريض المرتبطة بهذه القراءة (اختياري، لكن مفيد للعرض)
        $vitalSign->load('patientAdmission.patient');

        // يمكنك هنا التحقق من الصلاحيات إذا لزم الأمر (مثلاً، هل المستخدم الحالي هو من سجلها أو لديه صلاحية التعديل)

        return view('Dashboard.VitalSigns.edit', compact('vitalSign'));
    }

    public function update(StoreVitalSignRequest $request, VitalSign $vitalSign) // يمكن استخدام نفس الـ Request أو إنشاء واحد جديد للتحديث
    {
        // التحقق من الصلاحيات (مثال بسيط)
        // if ($vitalSign->recorded_by_user_id !== Auth::id() && !Auth::user()->can('edit_all_vital_signs')) {
        //     abort(403, 'ليس لديك الصلاحية لتعديل هذه القراءة.');
        // }

        try {
            $validatedData = $request->validated();
            $vitalSign->update($validatedData);

            Log::info("Vital sign ID: {$vitalSign->id} for admission ID: {$vitalSign->patient_admission_id} updated by user ID: " . Auth::id());

            return redirect()->route('admin.patient_admissions.vital_signs_sheet', $vitalSign->patient_admission_id)
                ->with('success', 'تم تحديث قراءة العلامات الحيوية بنجاح.');
        } catch (\Exception $e) {
            Log::error("Error updating vital sign ID: {$vitalSign->id}. Error: " . $e->getMessage());
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تحديث القراءة: ' . $e->getMessage())
                ->withInput();
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\VitalSign  $vitalSign
     * @return \Illuminate\Http\Response
     */
    public function destroy(VitalSign $vitalSign)
    {
        // التحقق من الصلاحيات
        // if (!Auth::user()->can('delete_vital_signs')) { // مثال
        //     abort(403, 'ليس لديك الصلاحية لحذف هذه القراءة.');
        // }

        $patientAdmissionId = $vitalSign->patient_admission_id; // احفظه قبل الحذف

        try {
            $vitalSign->delete();
            Log::info("Vital sign ID: {$vitalSign->id} deleted by user ID: " . Auth::id());
            return redirect()->route('admin.patient_admissions.vital_signs_sheet', $patientAdmissionId)
                ->with('success', 'تم حذف قراءة العلامات الحيوية بنجاح.');
        } catch (\Exception $e) {
            Log::error("Error deleting vital sign ID: {$vitalSign->id}. Error: " . $e->getMessage());
            return redirect()->route('admin.patient_admissions.vital_signs_sheet', $patientAdmissionId)
                ->with('error', 'حدث خطأ أثناء حذف القراءة: ' . $e->getMessage());
        }
    }
}
