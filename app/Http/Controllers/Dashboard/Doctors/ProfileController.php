<?php

namespace App\Http\Controllers\Dashboard\Doctors;

use App\Models\Doctor;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UpdateDoctorProfileRequest;
use Illuminate\Support\Facades\Auth; // ضروري للوصول للطبيب المسجل دخوله

class ProfileController extends Controller
{

    use UploadTrait;
    /**
     * Display the doctor's profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function showProfile() // تغيير اسم الدالة ليكون أوضح
    {
        $doctor = Auth::guard('doctor')->user();
        if (!$doctor) {
            abort(404);
        }
        // تحميل العلاقات الأساسية فقط
        $doctor->load(['section', 'image', 'workingDays.breaks']);
        return view('Dashboard.Doctors.profile.show', compact('doctor'));
    }

    public function edit()
    {
        $doctor = Auth::guard('doctor')->user();
        if (!$doctor) {
            abort(404);
        }
        // يمكنك جلب بيانات إضافية هنا إذا لزم الأمر (مثل الأقسام)
        return view('Dashboard.Doctors.profile.edit', compact('doctor'));
    }

    public function update(UpdateDoctorProfileRequest $request) // استخدام Form Request
    {
        // الحصول على الطبيب المسجل دخوله
        $doctor = Auth::guard('doctor')->user();
        if (!$doctor) {
            abort(403, 'غير مصرح لك بالقيام بهذا الإجراء.');
        }
        Log::info("Attempting profile update for Doctor ID: {$doctor->id}");
        DB::beginTransaction();

        try {
            $dataToUpdate = $request->only(['email', 'phone', 'national_id']);
            if ($request->filled('new_password')) {
                $dataToUpdate['password'] = Hash::make($request->new_password);
                Log::info("Password will be updated for Doctor ID: {$doctor->id}");
            }

            $doctor->update($dataToUpdate);
            $doctor->name = $request->name;
            $doctor->save();

            if ($request->hasFile('photo')) {
                Log::info("[Doctor Profile Update] Image file detected for Doctor ID: {$doctor->id}");
                if ($doctor->image && $doctor->image->filename) {
                    $old_img_filename = $doctor->image->filename;
                    Log::debug("[Doctor Profile Update] Old image found: {$old_img_filename}. Attempting deletion.");
                    $this->Delete_attachment('upload_image', 'doctors/' . $old_img_filename, $doctor->id, 'App\Models\Doctor');
                    Log::info("[Doctor Profile Update] Called Delete_attachment for old image '{$old_img_filename}'.");
                } else {
                    Log::debug("[Doctor Profile Update] No old image record found for Doctor ID: {$doctor->id}");
                }
                Log::debug("[Doctor Profile Update] Attempting to store new image.");
                $this->verifyAndStoreImage($request, 'photo', 'doctors', 'upload_image', $doctor->id, 'App\Models\Doctor');
                Log::info("[Doctor Profile Update] Called verifyAndStoreImage for new image.");
            }
            // dd($doctor->image);

            DB::commit();
            Log::info("Profile update committed successfully for Doctor ID {$doctor->id}");
            return redirect()->route('doctor.profile.show')->with('success', 'تم تحديث الملف الشخصي بنجاح.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error updating doctor profile for ID {$doctor->id}: " . $e->getMessage());
            Log::error($e->getTraceAsString());
            return redirect()->back()->withInput()->with('error', 'حدث خطأ غير متوقع أثناء تحديث البيانات. يرجى المحاولة مرة أخرى.');
        }
    }
}
