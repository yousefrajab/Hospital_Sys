<?php

namespace App\Repository\Patients;

use App\Models\Invoice;
use App\Models\Patient;
use App\Traits\UploadTrait;
use App\Models\PatientAccount;
use App\Models\ReceiptAccount;
use App\Models\single_invoice;



use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Interfaces\Patients\PatientRepositoryInterface;

class PatientRepository implements PatientRepositoryInterface
{
    use UploadTrait;
    public function index()
    {
        $Patients = Patient::all();
        return view('Dashboard.Patients.index', compact('Patients'));
    }

    public function Show($id)
    {
        $Patient = patient::findorfail($id);
        $invoices = single_invoice::where('patient_id', $id)->get();
        $invoices = Invoice::where('patient_id', $id)->get();
        $receipt_accounts = ReceiptAccount::where('patient_id', $id)->get();
        $Patient_accounts = PatientAccount::where('patient_id', $id)->get();
        //  $Patient_accounts = PatientAccount::where('patient_id', $id)->get();

        return view('Dashboard.Patients.show', compact('Patient', 'invoices', 'receipt_accounts', 'Patient_accounts'));
    }

    public function create()
    {
        return view('Dashboard.Patients.create');
    }

    public function edit($id)
    {
        $Patient = Patient::findorfail($id);
        return view('Dashboard.Patients.edit', compact('Patient'));
    }
    
    public function store(StorePatientRequest $request)
    {
        try {
            $Patients = new Patient();
            $Patients->national_id = $request->national_id;
            $Patients->email = $request->email;
            $Patients->password = Hash::make($request->password);
            $Patients->Date_Birth = $request->Date_Birth;
            $Patients->Phone = $request->Phone;
            $Patients->Gender = $request->Gender;
            $Patients->Blood_Group = $request->Blood_Group;

            // حفظ الحقول المترجمة
            $Patients->translateOrNew(app()->getLocale())->name = $request->name;
            $Patients->translateOrNew(app()->getLocale())->Address = $request->Address;

            $Patients->save();

            // Upload img
            $this->verifyAndStoreImage($request, 'photo', 'patients', 'upload_image', $Patients->id, 'App\Models\Patient');

            session()->flash('add');
            return redirect()->route('admin.Patients.index');
            // return back();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }


    public function update(UpdatePatientRequest $request) // <<<--- استخدام UpdatePatientRequest
    {
        // التحقق من الصحة تم بواسطة UpdatePatientRequest

        // جلب المريض المطلوب تعديله (findOrFail لإيجاده أو إرجاع 404)
        $Patient = Patient::findOrFail($request->input('id')); // الحصول على ID من الحقل المخفي

        Log::info("Updating patient ID: {$Patient->id}");

        DB::beginTransaction(); // بدء Transaction للأمان

        try {
            // 1. تحديث الحقول الأساسية (غير المترجمة وغير كلمة المرور)
            $Patient->national_id = $request->national_id;
            $Patient->email = $request->email;
            $Patient->Date_Birth = $request->Date_Birth;
            $Patient->Phone = $request->Phone; // اسم الحقل بحرف P كبير
            $Patient->Gender = $request->Gender;
            $Patient->Blood_Group = $request->Blood_Group;

            if ($request->filled('password')) {
                $Patient->password = bcrypt($request->password);
                Log::info("Password updated for patient ID: {$Patient->id}");
            }


            // 3. تحديث الحقول المترجمة
            $Patient->name = $request->name;
            $Patient->Address = $request->Address;

            // 4. حفظ كل التغييرات الأساسية والمترجمة وكلمة المرور (إذا تغيرت) مرة واحدة
            $Patient->save();
            Log::debug("Patient base data and translations saved for ID: {$Patient->id}");

            // 5. تحديث الصورة (باستخدام الـ Trait) - يتم بعد حفظ البيانات الأخرى
            if ($request->hasFile('photo')) { // التأكد من اسم الحقل 'photo' في الفورم
                Log::info("Processing image update for Patient ID: {$Patient->id}");
                // أ. حذف الصورة القديمة إذا وجدت
                if ($Patient->image) {
                    $old_img_filename = $Patient->image->filename;
                    Log::debug("Attempting to delete old image '{$old_img_filename}' for Patient ID: {$Patient->id}");
                    // استدعاء دالة الحذف من الـ Trait
                    // تأكد من اسم الديسك ('upload_attachments') والمجلد ('patients')
                    $this->Delete_attachment('upload_attachments', 'patients/' . $old_img_filename, $Patient->id);
                    Log::info("Delete_attachment called for old image of Patient ID: {$Patient->id}");
                } else {
                    Log::debug("No old image record found for Patient ID: {$Patient->id}");
                }

                // ب. رفع وتخزين الصورة الجديدة
                Log::debug("Attempting to store new image for Patient ID: {$Patient->id}");
                // استدعاء دالة الرفع من الـ Trait
                $this->verifyAndStoreImage($request, 'photo', 'patients', 'upload_attachments', $Patient->id, 'App\Models\Patient');
                Log::info("verifyAndStoreImage called for new image of Patient ID: {$Patient->id}");
            }

            // 6. Commit Transaction
            DB::commit();
            Log::info("Patient update committed successfully for ID: {$Patient->id}");

            // 7. إعادة التوجيه مع رسالة نجاح
            session()->flash('edit');
            return redirect()->route('admin.Patients.index');
            // session()->flash('edit', 'تم تعديل بيانات المريض بنجاح.');
            // return redirect()->route('admin.Patients.index');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // هذا لا يجب أن     يحدث لأن التحقق يتم في FormRequest
            DB::rollBack();
            Log::warning("Validation Exception during patient update (should not happen here): ", $e->errors());
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error updating patient ID {$request->input('id')}: " . $e->getMessage());
            // إرجاع الخطأ للمستخدم مع الحفاظ على المدخلات
            return redirect()->back()->withErrors(['error' => 'حدث خطأ غير متوقع أثناء التحديث: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy($request)
    {
        if ($request->filename) {

            $this->Delete_attachment('upload_image', 'patients/' . $request->filename, $request->id, $request->filename);
        }
        Patient::destroy($request->id);
        session()->flash('delete');
        return redirect()->back();
    }
}
