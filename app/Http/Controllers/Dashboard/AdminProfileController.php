<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\GlobalEmail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;                                 // **استيراد موديل Admin**
use App\Traits\UploadTrait;                           // **استيراد UploadTrait**
use App\Http\Requests\Admin\UpdateAdminProfileRequest; // **استيراد الـ FormRequest**
use Illuminate\Http\Request; // لا يزال مطلوبًا إذا كنت ستستخدم $request مباشرة في أي مكان
use Illuminate\Validation\ValidationException;        // **لرمي أخطاء التحقق يدويًا إذا لزم الأمر**

class AdminProfileController extends Controller
{
    use UploadTrait; // **استخدام الـ Trait**

    public function show()
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            // يمكنك توجيه إلى صفحة تسجيل الدخول أو خطأ مخصص
            return redirect()->route('admin.login.form')->with('error', 'يرجى تسجيل الدخول أولاً.');
        }
        return view('Dashboard.Admin.profile.show', compact('admin'));
    }

    public function edit()
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            return redirect()->route('admin.login.form')->with('error', 'يرجى تسجيل الدخول أولاً.');
        }
        return view('Dashboard.Admin.profile.edit', compact('admin'));
    }

    public function update(UpdateAdminProfileRequest $request) // ** استخدام الـ FormRequest **
    {
        $admin = Auth::guard('admin')->user();
        $validatedData = $request->validated(); // الحصول على البيانات المتحقق منها

        DB::beginTransaction();
        try {
            $dataToUpdate = [
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
            ];
            if (isset($validatedData['phone'])) {
                $dataToUpdate['phone'] = $validatedData['phone'];
            }
            if (!empty($validatedData['password'])) { // تحقق مما إذا تم إرسال كلمة مرور جديدة
                $dataToUpdate['password'] = Hash::make($validatedData['password']);
                Log::info("Admin ID: {$admin->id} password will be updated.");
            }

            $admin->update($dataToUpdate); // تحديث البيانات الأساسية
            // الـ Observer سيهتم بتحديث global_emails إذا تغير الإيميل

            // التعامل مع رفع الصورة
            if ($request->hasFile('photo')) {
                if ($admin->image) {
                    $this->Delete_attachment('upload_image', 'admin_photos/' . $admin->image->filename, $admin->id, Admin::class);
                }
                $this->verifyAndStoreImage($request, 'photo', 'admin_photos', 'upload_image', $admin->id, Admin::class);
            }

            DB::commit();
            Log::info("Admin ID: {$admin->id} profile updated successfully.");
            return redirect()->route('admin.profile.show')->with('success', 'تم تحديث الملف الشخصي بنجاح.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error updating admin profile ID {$admin->id}: " . $e->getMessage() . " TRACE: " . $e->getTraceAsString());
            return redirect()->back()->withInput()->with('error', 'حدث خطأ أثناء تحديث الملف الشخصي: ' . $e->getMessage());
        }
    }
}
