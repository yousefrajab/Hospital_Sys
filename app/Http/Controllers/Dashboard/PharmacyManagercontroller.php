<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Medication;
use App\Traits\UploadTrait;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\PharmacyManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StorePharmacyManagerRequest;
use App\Http\Requests\UpdatePharmacyManagerRequest;

class PharmacyManagercontroller extends Controller
{
    use UploadTrait;
    public function index()
    {
        $pharmacy_managers = PharmacyManager::with('image')->orderBy('created_at', 'desc')->get(); // استخدام get() أفضل من all() وتحميل الصورة
        return view('Dashboard.PharmacyManager.index', compact('pharmacy_managers'));
    }

    public function store(StorePharmacyManagerRequest $request)
    {
        DB::beginTransaction();
        try {
            $pharmacy_manager = new PharmacyManager();
            $pharmacy_manager->national_id = $request->national_id;
            $pharmacy_manager->name = $request->name;
            $pharmacy_manager->email = $request->email;
            $pharmacy_manager->password = Hash::make($request->password);
            $pharmacy_manager->phone = $request->phone; // تأكد من وجود هذا الحقل في الفورم والطلب
            $pharmacy_manager->status = $request->status ?? 1; // قراءة الحالة من الطلب أو الافتراضي 1
            $pharmacy_manager->save();

            if ($request->hasFile('photo')) {
                $this->verifyAndStoreImage(
                    $request,
                    'photo',
                    'pharmacy_managers',
                    'upload_image', // تأكد أن هذا القرص معرف بشكل صحيح
                    $pharmacy_manager->id,
                    PharmacyManager::class // استخدام ::class أفضل
                );
            }

            DB::commit();
            session()->flash('add', 'تم إضافة الموظف بنجاح.');
            return redirect()->route('admin.pharmacy_manager.index');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error storing PharmacyManager: " . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'حدث خطأ أثناء إضافة الموظف: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $pharmacy_manager = PharmacyManager::with('image')->find($id);
        if (!$pharmacy_manager) {
            abort(404, 'الموظف غير موجود.');
        }
        // *** تعديل هنا: عرض صفحة التعديل الكاملة ***
        return view('Dashboard.pharmacy_manager.edit', compact('pharmacy_manager'));
    }


    public function update(UpdatePharmacyManagerRequest $request, $id)
    {
        DB::beginTransaction(); // يفضل استخدام transaction هنا أيضًا
        try {
            $pharmacy_manager = PharmacyManager::findOrFail($id);

            // استخدام validated data من FormRequest
            $validatedData = $request->validated();
            $updateData = Arr::except($validatedData, ['photo', 'password', 'password_confirmation']); // استثناء الحقول التي تحتاج معالجة خاصة

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($validatedData['password']);
            }

            $pharmacy_manager->update($updateData);

            if ($request->hasFile('photo')) {
                if ($pharmacy_manager->image) {
                    // تمرير الـ Type الصحيح للحذف
                    $this->Delete_attachment(
                        'upload_image',
                        'pharmacy_managers/' . $pharmacy_manager->image->filename,
                        $pharmacy_manager->id,
                        PharmacyManager::class // ** مهم هنا **
                    );
                }
                $this->verifyAndStoreImage(
                    $request,
                    'photo',
                    'pharmacy_managers',
                    'upload_image',
                    $pharmacy_manager->id,
                    PharmacyManager::class
                );
            }
            DB::commit();
            session()->flash('edit', 'تم تعديل بيانات الموظف بنجاح.');
            return redirect()->route('admin.pharmacy_manager.index');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();
            Log::error("Validation error updating PharmacyManager ID {$id}: " . json_encode($e->errors()));
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error updating PharmacyManager ID {$id}: " . $e->getMessage());
            return redirect()->back()->withInput()->withErrors(['error' => 'حدث خطأ أثناء تعديل الموظف: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $pharmacy_manager = PharmacyManager::findOrFail($id);
            if ($pharmacy_manager->image) {
                $this->Delete_attachment(
                    'upload_image',
                    'pharmacy_managers/' . $pharmacy_manager->image->filename,
                    $pharmacy_manager->id,
                    PharmacyManager::class // ** مهم هنا **
                );
            }
            $pharmacy_manager->delete(); // استخدام delete على الموديل أفضل
            DB::commit();
            session()->flash('delete', 'تم حذف الموظف بنجاح.');
            return redirect()->route('admin.pharmacy_manager.index');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error deleting PharmacyManager ID {$id}: " . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'حدث خطأ أثناء حذف الموظف: ' . $e->getMessage()]);
        }
    }
}
