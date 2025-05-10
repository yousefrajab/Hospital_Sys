<?php

namespace App\Observers;

use App\Models\Admin;
use App\Models\GlobalEmail;

class AdminObserver
{
    /**
     * Handle the Admin "created" event.
     *
     * @param  \App\Models\Admin  $admin
     * @return void
     */
    public function created(Admin $admin)
    {
        GlobalEmail::create([
            'email' => $admin->email,
            'owner_type' => Admin::class, // استخدام اسم الكلاس الكامل
            'owner_id' => $admin->id,
        ]);
    }

    /**
     * Handle the Admin "updated" event.
     *
     * @param  \App\Models\Admin  $admin
     * @return void
     */
    public function updated(Admin $admin)
    {
        if ($admin->isDirty('email')) {
            GlobalEmail::updateOrCreate(
                ['owner_type' => Admin::class, 'owner_id' => $admin->id],
                ['email' => $admin->email]
            );
        }
    }
    /**
     * Handle the Admin "deleted" event.
     *
     * @param  \App\Models\Admin  $admin
     * @return void
     */
    public function deleted(Admin $admin)
    {
        GlobalEmail::where('owner_type', Admin::class)
            ->where('owner_id', $admin->id)
            ->delete();
    }

    /**
     * Handle the Admin "restored" event.
     *
     * @param  \App\Models\Admin  $admin
     * @return void
     */
    public function restored(Admin $admin)
    {
        //
    }

    /**
     * Handle the Admin "force deleted" event.
     *
     * @param  \App\Models\Admin  $admin
     * @return void
     */
    public function forceDeleted(Admin $admin)
    {
        //
    }
}
