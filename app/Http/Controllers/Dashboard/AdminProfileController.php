<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Bed;
use App\Models\Room;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Section;
use App\Models\Appointment;
use App\Models\GlobalEmail;
use App\Models\RayEmployee;
use Illuminate\Support\Carbon;
use App\Models\PharmacyManager;
use App\Models\PatientAdmission;
use App\Models\PharmacyEmployee;
use Illuminate\Support\Facades\DB;
use App\Models\LaboratorieEmployee;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;                                 // **استيراد موديل Admin**
use App\Traits\UploadTrait;                           // **استيراد UploadTrait**
use App\Http\Requests\Admin\UpdateAdminProfileRequest; // **استيراد الـ FormRequest**
use Illuminate\Http\Request; // لا يزال مطلوبًا إذا كنت ستستخدم $request مباشرة في أي مكان
use Illuminate\Validation\ValidationException;

class AdminProfileController extends Controller
{
    use UploadTrait;

    public function dashboard(Request $request)
    {
        return view('Dashboard.Admin.dashboard', \App\Services\Dashboard\AdminDashboard::build());
    }

    public function show()
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            // يمكنك توجيه إلى صفحة تسجيل الدخول أو خطأ مخصص
            return redirect()->route('login')->with('error', 'يرجى تسجيل الدخول أولاً.');
        }
        return view('Dashboard.Admin.profile.show', compact('admin'));
    }

    public function edit()
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            return redirect()->route('login')->with('error', 'يرجى تسجيل الدخول أولاً.');
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
            return redirect()->back()->withInput()->with('error', 'حدث خطأ أثناء تحديث الملف الشخصي: ' . error_detail($e));
        }
    }
}
