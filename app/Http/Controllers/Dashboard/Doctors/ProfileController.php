<?php

namespace App\Http\Controllers\Dashboard\Doctors;

use App\Models\Doctor;
use App\Traits\UploadTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateDoctorProfileRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    use UploadTrait;

    public function showProfile()
    {
        $doctor = Auth::guard('doctor')->user();
        if (!$doctor) {
            abort(404, 'الطبيب غير موجود أو غير مسجل الدخول.');
        }
        $doctor->load(['section', 'image', 'workingDays.breaks']);
        return view('Dashboard.Doctors.profile.show', compact('doctor'));
    }

    public function edit()
    {
        $doctor = Auth::guard('doctor')->user();
        if (!$doctor) {
            abort(404, 'الطبيب غير موجود أو غير مسجل الدخول.');
        }
        // $sections = \App\Models\Section::all();
        return view('Dashboard.Doctors.profile.edit', compact('doctor' /*, 'sections'*/));
    }

    public function update(UpdateDoctorProfileRequest $request)
    {
        $doctor = Auth::guard('doctor')->user();
        if (!$doctor) {
            abort(403, 'غير مصرح لك بالقيام بهذا الإجراء.');
        }

        $validatedData = $request->validated();
        Log::info("Attempting profile update for Doctor ID: {$doctor->id}", $request->except(['_token', '_method', 'password', 'password_confirmation', 'current_password', 'photo']));

        DB::beginTransaction();
        try {
            $dataToUpdate = [
                'email' => strtolower($validatedData['email']),
                'national_id' => $validatedData['national_id'],
            ];

            if (isset($validatedData['phone'])) {
                $dataToUpdate['phone'] = $validatedData['phone'];
            }
            // if (isset($validatedData['section_id'])) {
            //     $dataToUpdate['section_id'] = $validatedData['section_id'];
            // }

            if (!empty($validatedData['password'])) {
                $dataToUpdate['password'] = Hash::make($validatedData['password']);
                Log::info("Password will be updated for Doctor ID: {$doctor->id}");
            }

            $doctor->update($dataToUpdate);


            $doctor->name = $validatedData['name'];
            $doctor->save();

            Log::info("Doctor data (excluding image) updated for ID: {$doctor->id}");


            if ($request->hasFile('photo')) {
                Log::info("[Doctor Profile Update] Image file detected for Doctor ID: {$doctor->id}");
                if ($doctor->image && $doctor->image->filename) {
                    $this->Delete_attachment(
                        'upload_image',
                        'doctors/' . $doctor->image->filename,
                        $doctor->id,
                        Doctor::class
                    );
                    Log::info("[Doctor Profile Update] Old image deleted for Doctor ID: {$doctor->id}");
                }
                $this->verifyAndStoreImage(
                    $request,
                    'photo',
                    'doctors',
                    'upload_image',
                    $doctor->id,
                    Doctor::class
                );
                Log::info("[Doctor Profile Update] New image stored for Doctor ID: {$doctor->id}");
            }

            DB::commit();
            Log::info("Profile update committed successfully for Doctor ID {$doctor->id}");
            return redirect()->route('doctor.profile.show')->with('success', 'تم تحديث الملف الشخصي بنجاح.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error("Doctor Profile Update Validation Exception (Controller Level) for ID {$doctor->id}: " . json_encode($e->errors()));
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error updating doctor profile for ID {$doctor->id}: " . $e->getMessage());
            Log::error($e->getTraceAsString()); 
            return redirect()->back()->withInput()->with('error', 'حدث خطأ غير متوقع أثناء تحديث البيانات. يرجى المحاولة مرة أخرى أو مراجعة سجلات النظام.');
        }
    }
}
