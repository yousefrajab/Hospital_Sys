<?php

namespace App\Observers;

use App\Models\Doctor;
use App\Models\GlobalEmail;
use Illuminate\Support\Facades\Log; // لإضافة تسجيل

class DoctorObserver
{
    /**
     * Handle the Doctor "created" event.
     */
    public function created(Doctor $doctor): void
    {
        try {
            GlobalEmail::create([
                'email' => $doctor->email,
                'owner_type' => Doctor::class, // استخدام اسم الكلاس الكامل
                'owner_id' => $doctor->id,
            ]);
            Log::info("GlobalEmail record created for new Doctor ID: {$doctor->id}, Email: {$doctor->email}");
        } catch (\Exception $e) {
            Log::error("Error creating GlobalEmail for Doctor ID {$doctor->id}: " . $e->getMessage());
            // يمكنك هنا رمي استثناء أو إرسال إشعار إذا أردت
        }
    }

    /**
     * Handle the Doctor "updated" event.
     */
    public function updated(Doctor $doctor): void
    {
        // تحقق مما إذا كان الإيميل قد تغير
        if ($doctor->isDirty('email')) {
            try {
                GlobalEmail::updateOrCreate(
                    ['owner_type' => Doctor::class, 'owner_id' => $doctor->id],
                    ['email' => $doctor->email]
                );
                Log::info("GlobalEmail record updated for Doctor ID: {$doctor->id}, New Email: {$doctor->email}");
            } catch (\Exception $e) {
                Log::error("Error updating GlobalEmail for Doctor ID {$doctor->id}: " . $e->getMessage());
            }
        }
    }

    /**
     * Handle the Doctor "deleted" event.
     */
    public function deleted(Doctor $doctor): void
    {
        try {
            GlobalEmail::where('owner_type', Doctor::class)
                ->where('owner_id', $doctor->id)
                ->delete();
            Log::info("GlobalEmail record deleted for Doctor ID: {$doctor->id}, Email: {$doctor->email}");
        } catch (\Exception $e) {
            Log::error("Error deleting GlobalEmail for Doctor ID {$doctor->id}: " . $e->getMessage());
        }
    }

    // يمكنك ترك restored و forceDeleted فارغتين إذا لم تكن هناك حاجة لمعالجة خاصة
    public function restored(Doctor $doctor): void
    {
        // يمكنك إعادة إنشاء سجل GlobalEmail إذا تم استعادة الطبيب
        // (اختياري ويعتمد على منطق عملك)
        // $this->created($doctor); // استدعاء دالة created مرة أخرى
    }

    public function forceDeleted(Doctor $doctor): void
    {
        // نفس منطق deleted إذا كنت تستخدم الحذف النهائي
        // $this->deleted($doctor);
    }
}
