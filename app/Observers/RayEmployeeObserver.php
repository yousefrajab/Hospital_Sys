<?php

namespace App\Observers;

use App\Models\GlobalEmail;
use App\Models\RayEmployee;
use App\Models\GlobalIdentifier;
use Illuminate\Support\Facades\Log;

class RayEmployeeObserver
{
    public function created(RayEmployee $rayEmployee): void
    {
        // --- الجزء الخاص بـ GlobalEmail (افترض أنه موجود لديك ولم يتغير) ---
        try {
            if ($rayEmployee->email) { // تحقق من وجود الإيميل قبل محاولة إنشاء السجل
                GlobalEmail::create([ // استخدام موديل GlobalEmail هنا
                    'email' => strtolower($rayEmployee->email),
                    'owner_type' => RayEmployee::class,
                    'owner_id' => $rayEmployee->id,
                ]);
                Log::info("GlobalEmail record created for new RayEmployee ID: {$rayEmployee->id}, Email: {$rayEmployee->email}");
            }
        } catch (\Exception $e) {
            Log::error("Error creating GlobalEmail for RayEmployee ID {$rayEmployee->id}: " . $e->getMessage());
        }

        // --- الجزء الجديد الخاص بـ GlobalIdentifier (لرقم الهوية فقط) ---
        try {
            if ($rayEmployee->national_id) { // تحقق من وجود رقم الهوية
                GlobalIdentifier::create([
                    'national_id' => $rayEmployee->national_id, // العمود national_id في جدول global_identifiers
                    'owner_type' => RayEmployee::class,
                    'owner_id' => $rayEmployee->id,
                ]);
                Log::info("GlobalIdentifier (national_id) created for new RayEmployee ID: {$rayEmployee->id}, National ID: {$rayEmployee->national_id}");
            }
        } catch (\Exception $e) {
            Log::error("Error creating GlobalIdentifier (national_id) for RayEmployee ID {$rayEmployee->id}: " . $e->getMessage());
        }
    }

    // public function created(RayEmployee $rayEmployee)
    // {
    //     try {
    //         GlobalEmail::create([
    //             'email' => $rayEmployee->email,
    //             'owner_type' => RayEmployee::class, // استخدام اسم الكلاس الكامل
    //             'owner_id' => $rayEmployee->id,
    //         ]);
    //         Log::info("GlobalEmail record created for new RayEmployee ID: {$rayEmployee->id}, Email: {$rayEmployee->email}");
    //     } catch (\Exception $e) {
    //         Log::error("Error creating GlobalEmail for RayEmployee ID {$rayEmployee->id}: " . $e->getMessage());
    //         // يمكنك هنا رمي استثناء أو إرسال إشعار إذا أردت
    //     }
    // }


    // public function updated(RayEmployee $rayEmployee)
    // {
    //     // تحقق مما إذا كان الإيميل قد تغير
    //     if ($rayEmployee->isDirty('email')) {
    //         try {
    //             GlobalEmail::updateOrCreate(
    //                 ['owner_type' => RayEmployee::class, 'owner_id' => $rayEmployee->id],
    //                 ['email' => $rayEmployee->email]
    //             );
    //             Log::info("GlobalEmail record updated for RayEmployee ID: {$rayEmployee->id}, New Email: {$rayEmployee->email}");
    //         } catch (\Exception $e) {
    //             Log::error("Error updating GlobalEmail for RayEmployee ID {$rayEmployee->id}: " . $e->getMessage());
    //         }
    //     }
    // }



    public function updated(RayEmployee $rayEmployee): void
    {
        // --- الجزء الخاص بـ GlobalEmail (افترض أنه موجود لديك ولم يتغير) ---
        if ($rayEmployee->isDirty('email')) {
            try {
                if ($rayEmployee->email) {
                    GlobalEmail::updateOrCreate(
                        ['owner_type' => RayEmployee::class, 'owner_id' => $rayEmployee->id], // شروط البحث
                        ['email' => strtolower($rayEmployee->email)] // البيانات للتحديث أو الإنشاء
                    );
                    Log::info("GlobalEmail record updated for RayEmployee ID: {$rayEmployee->id}, New Email: {$rayEmployee->email}");
                } else { // إذا تم حذف الإيميل
                    GlobalEmail::where('owner_type', RayEmployee::class)
                        ->where('owner_id', $rayEmployee->id)
                        ->delete();
                    Log::info("GlobalEmail record deleted for RayEmployee ID: {$rayEmployee->id} as email was set to null.");
                }
            } catch (\Exception $e) {
                Log::error("Error updating GlobalEmail for RayEmployee ID {$rayEmployee->id}: " . $e->getMessage());
            }
        }

        // --- الجزء الجديد الخاص بـ GlobalIdentifier (لرقم الهوية فقط) ---
        if ($rayEmployee->isDirty('national_id')) {
            try {
                if ($rayEmployee->national_id) { // إذا كان هناك قيمة جديدة لرقم الهوية
                    GlobalIdentifier::updateOrCreate(
                        [ // شروط البحث: ابحث عن سجل لهذا المالك
                            'owner_type' => RayEmployee::class,
                            'owner_id' => $rayEmployee->id,
                        ],
                        [ // البيانات للتحديث أو الإنشاء
                            'national_id' => $rayEmployee->national_id,
                        ]
                    );
                    Log::info("GlobalIdentifier (national_id) updated for RayEmployee ID: {$rayEmployee->id}, New National ID: {$rayEmployee->national_id}");
                } else { // إذا تم حذف رقم الهوية (جعله null)
                    GlobalIdentifier::where('owner_type', RayEmployee::class)
                        ->where('owner_id', $rayEmployee->id)
                        ->delete(); // حذف السجل بالكامل من global_identifiers لهذا المالك
                    Log::info("GlobalIdentifier (national_id) deleted for RayEmployee ID: {$rayEmployee->id} as national_id was set to null.");
                }
            } catch (\Exception $e) {
                Log::error("Error updating GlobalIdentifier (national_id) for RayEmployee ID {$rayEmployee->id}: " . $e->getMessage());
            }
        }
    }



    // public function deleted(RayEmployee $rayEmployee)
    // {
    //     try {
    //         GlobalEmail::where('owner_type', RayEmployee::class)
    //             ->where('owner_id', $rayEmployee->id)
    //             ->delete();
    //         Log::info("GlobalEmail record deleted for RayEmployee ID: {$rayEmployee->id}, Email: {$rayEmployee->email}");
    //     } catch (\Exception $e) {
    //         Log::error("Error deleting GlobalEmail for RayEmployee ID {$rayEmployee->id}: " . $e->getMessage());
    //     }
    // }


    public function deleted(RayEmployee $rayEmployee): void
    {
        // --- الجزء الخاص بـ GlobalEmail (افترض أنه موجود لديك ولم يتغير) ---
        try {
            GlobalEmail::where('owner_type', RayEmployee::class)
                ->where('owner_id', $rayEmployee->id)
                ->delete();
            Log::info("GlobalEmail record deleted for RayEmployee ID: {$rayEmployee->id}");
        } catch (\Exception $e) {
            Log::error("Error deleting GlobalEmail for RayEmployee ID {$rayEmployee->id}: " . $e->getMessage());
        }

        // --- الجزء الجديد الخاص بـ GlobalIdentifier (لرقم الهوية فقط) ---
        try {
            GlobalIdentifier::where('owner_type', RayEmployee::class)
                ->where('owner_id', $rayEmployee->id)
                ->delete();
            Log::info("GlobalIdentifier (national_id) record deleted for RayEmployee ID: {$rayEmployee->id}");
        } catch (\Exception $e) {
            Log::error("Error deleting GlobalIdentifier (national_id) for RayEmployee ID {$rayEmployee->id}: " . $e->getMessage());
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
