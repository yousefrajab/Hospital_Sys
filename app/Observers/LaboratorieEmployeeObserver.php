<?php

namespace App\Observers;

use App\Models\GlobalEmail;
use App\Models\LaboratorieEmployee;
use Illuminate\Support\Facades\Log;

class LaboratorieEmployeeObserver
{
    /**
     * Handle the LaboratorieEmployee "created" event.
     *
     * @param  \App\Models\LaboratorieEmployee  $laboratorieEmployee
     * @return void
     */
    public function created(LaboratorieEmployee $laboratorieEmployee)
    {
        try {
            GlobalEmail::create([
                'email' => $laboratorieEmployee->email,
                'owner_type' => LaboratorieEmployee::class, // استخدام اسم الكلاس الكامل
                'owner_id' => $laboratorieEmployee->id,
            ]);
            Log::info("GlobalEmail record created for new LaboratorieEmployee ID: {$laboratorieEmployee->id}, Email: {$laboratorieEmployee->email}");
        } catch (\Exception $e) {
            Log::error("Error creating GlobalEmail for LaboratorieEmployee ID {$laboratorieEmployee->id}: " . $e->getMessage());
            // يمكنك هنا رمي استثناء أو إرسال إشعار إذا أردت
        }
    }


    public function updated(LaboratorieEmployee $laboratorieEmployee)
    {
        // تحقق مما إذا كان الإيميل قد تغير
        if ($laboratorieEmployee->isDirty('email')) {
            try {
                GlobalEmail::updateOrCreate(
                    ['owner_type' => LaboratorieEmployee::class, 'owner_id' => $laboratorieEmployee->id],
                    ['email' => $laboratorieEmployee->email]
                );
                Log::info("GlobalEmail record updated for LaboratorieEmployee ID: {$laboratorieEmployee->id}, New Email: {$laboratorieEmployee->email}");
            } catch (\Exception $e) {
                Log::error("Error updating GlobalEmail for LaboratorieEmployee ID {$laboratorieEmployee->id}: " . $e->getMessage());
            }
        }
    }

    
    public function deleted(LaboratorieEmployee $laboratorieEmployee)
    {
        try {
            GlobalEmail::where('owner_type', LaboratorieEmployee::class)
                ->where('owner_id', $laboratorieEmployee->id)
                ->delete();
            Log::info("GlobalEmail record deleted for LaboratorieEmployee ID: {$laboratorieEmployee->id}, Email: {$laboratorieEmployee->email}");
        } catch (\Exception $e) {
            Log::error("Error deleting GlobalEmail for LaboratorieEmployee ID {$laboratorieEmployee->id}: " . $e->getMessage());
        }
    }

    /**
     * Handle the LaboratorieEmployee "restored" event.
     *
     * @param  \App\Models\LaboratorieEmployee  $laboratorieEmployee
     * @return void
     */
    public function restored(LaboratorieEmployee $laboratorieEmployee)
    {
        //
    }

    /**
     * Handle the LaboratorieEmployee "force deleted" event.
     *
     * @param  \App\Models\LaboratorieEmployee  $laboratorieEmployee
     * @return void
     */
    public function forceDeleted(LaboratorieEmployee $laboratorieEmployee)
    {
        //
    }
}
