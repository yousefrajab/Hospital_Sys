<?php

namespace App\Observers;

use App\Models\Patient;
use App\Models\GlobalEmail;
use Illuminate\Support\Facades\Log;

class PatientObserver
{
    /**
     * Handle the Patient "created" event.
     *
     * @param  \App\Models\Patient  $patient
     * @return void
     */
    public function created(Patient $patient)
    {
        try {
            GlobalEmail::create([
                'email' => $patient->email,
                'owner_type' => Patient::class, // استخدام اسم الكلاس الكامل
                'owner_id' => $patient->id,
            ]);
            Log::info("GlobalEmail record created for new Patient ID: {$patient->id}, Email: {$patient->email}");
        } catch (\Exception $e) {
            Log::error("Error creating GlobalEmail for Patient ID {$patient->id}: " . $e->getMessage());
            // يمكنك هنا رمي استثناء أو إرسال إشعار إذا أردت
        }
    }

    /**
     * Handle the Patient "updated" event.
     *
     * @param  \App\Models\Patient  $patient
     * @return void
     */
    public function updated(Patient $patient)
    {
         // تحقق مما إذا كان الإيميل قد تغير
         if ($patient->isDirty('email')) {
            try {
                GlobalEmail::updateOrCreate(
                    ['owner_type' => Patient::class, 'owner_id' => $patient->id],
                    ['email' => $patient->email]
                );
                Log::info("GlobalEmail record updated for Patient ID: {$patient->id}, New Email: {$patient->email}");
            } catch (\Exception $e) {
                Log::error("Error updating GlobalEmail for Patient ID {$patient->id}: " . $e->getMessage());
            }
        }
    }

    /**
     * Handle the Patient "deleted" event.
     *
     * @param  \App\Models\Patient  $patient
     * @return void
     */
    public function deleted(Patient $patient)
    {
        try {
            GlobalEmail::where('owner_type', Patient::class)
                ->where('owner_id', $patient->id)
                ->delete();
            Log::info("GlobalEmail record deleted for Patient ID: {$patient->id}, Email: {$patient->email}");
        } catch (\Exception $e) {
            Log::error("Error deleting GlobalEmail for Patient ID {$patient->id}: " . $e->getMessage());
        }
    }

    /**
     * Handle the Patient "restored" event.
     *
     * @param  \App\Models\Patient  $patient
     * @return void
     */
    public function restored(Patient $patient)
    {
        //
    }

    /**
     * Handle the Patient "force deleted" event.
     *
     * @param  \App\Models\Patient  $patient
     * @return void
     */
    public function forceDeleted(Patient $patient)
    {
        //
    }
}
