<?php

namespace App\Observers;

use App\Models\GlobalEmail;
use App\Models\RayEmployee;
use Illuminate\Support\Facades\Log;

class RayEmployeeObserver
{
    /**
     * Handle the RayEmployee "created" event.
     *
     * @param  \App\Models\RayEmployee  $rayEmployee
     * @return void
     */
    public function created(RayEmployee $rayEmployee)
    {
        try {
            GlobalEmail::create([
                'email' => $rayEmployee->email,
                'owner_type' => RayEmployee::class, // استخدام اسم الكلاس الكامل
                'owner_id' => $rayEmployee->id,
            ]);
            Log::info("GlobalEmail record created for new RayEmployee ID: {$rayEmployee->id}, Email: {$rayEmployee->email}");
        } catch (\Exception $e) {
            Log::error("Error creating GlobalEmail for RayEmployee ID {$rayEmployee->id}: " . $e->getMessage());
            // يمكنك هنا رمي استثناء أو إرسال إشعار إذا أردت
        }
    }


    public function updated(RayEmployee $rayEmployee)
    {
        // تحقق مما إذا كان الإيميل قد تغير
        if ($rayEmployee->isDirty('email')) {
            try {
                GlobalEmail::updateOrCreate(
                    ['owner_type' => RayEmployee::class, 'owner_id' => $rayEmployee->id],
                    ['email' => $rayEmployee->email]
                );
                Log::info("GlobalEmail record updated for RayEmployee ID: {$rayEmployee->id}, New Email: {$rayEmployee->email}");
            } catch (\Exception $e) {
                Log::error("Error updating GlobalEmail for RayEmployee ID {$rayEmployee->id}: " . $e->getMessage());
            }
        }
    }


    public function deleted(RayEmployee $rayEmployee)
    {
        try {
            GlobalEmail::where('owner_type', RayEmployee::class)
                ->where('owner_id', $rayEmployee->id)
                ->delete();
            Log::info("GlobalEmail record deleted for RayEmployee ID: {$rayEmployee->id}, Email: {$rayEmployee->email}");
        } catch (\Exception $e) {
            Log::error("Error deleting GlobalEmail for RayEmployee ID {$rayEmployee->id}: " . $e->getMessage());
        }
    }

    /**
     * Handle the RayEmployee "restored" event.
     *
     * @param  \App\Models\RayEmployee  $rayEmployee
     * @return void
     */
    public function restored(RayEmployee $rayEmployee)
    {
        //
    }

    /**
     * Handle the RayEmployee "force deleted" event.
     *
     * @param  \App\Models\RayEmployee  $rayEmployee
     * @return void
     */
    public function forceDeleted(RayEmployee $rayEmployee)
    {
        //
    }
}
