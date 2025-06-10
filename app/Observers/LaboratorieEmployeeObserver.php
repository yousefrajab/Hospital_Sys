<?php

namespace App\Observers;

use App\Models\GlobalEmail;
use App\Models\GlobalIdentifier;
use App\Models\LaboratorieEmployee;
use Illuminate\Support\Facades\Log;

class LaboratorieEmployeeObserver
{
    public function created(LaboratorieEmployee $laboratorieEmployee): void
    {
        // --- الجزء الخاص بـ GlobalEmail (افترض أنه موجود لديك ولم يتغير) ---
        try {
            if ($laboratorieEmployee->email) { // تحقق من وجود الإيميل قبل محاولة إنشاء السجل
                GlobalEmail::create([ // استخدام موديل GlobalEmail هنا
                    'email' => strtolower($laboratorieEmployee->email),
                    'owner_type' => LaboratorieEmployee::class,
                    'owner_id' => $laboratorieEmployee->id,
                ]);
                Log::info("GlobalEmail record created for new LaboratorieEmployee ID: {$laboratorieEmployee->id}, Email: {$laboratorieEmployee->email}");
            }
        } catch (\Exception $e) {
            Log::error("Error creating GlobalEmail for LaboratorieEmployee ID {$laboratorieEmployee->id}: " . $e->getMessage());
        }

        // --- الجزء الجديد الخاص بـ GlobalIdentifier (لرقم الهوية فقط) ---
        try {
            if ($laboratorieEmployee->national_id) { // تحقق من وجود رقم الهوية
                GlobalIdentifier::create([
                    'national_id' => $laboratorieEmployee->national_id, // العمود national_id في جدول global_identifiers
                    'owner_type' => LaboratorieEmployee::class,
                    'owner_id' => $laboratorieEmployee->id,
                ]);
                Log::info("GlobalIdentifier (national_id) created for new LaboratorieEmployee ID: {$laboratorieEmployee->id}, National ID: {$laboratorieEmployee->national_id}");
            }
        } catch (\Exception $e) {
            Log::error("Error creating GlobalIdentifier (national_id) for LaboratorieEmployee ID {$laboratorieEmployee->id}: " . $e->getMessage());
        }
    }

    // public function created(LaboratorieEmployee $laboratorieEmployee)
    // {
    //     try {
    //         GlobalEmail::create([
    //             'email' => $laboratorieEmployee->email,
    //             'owner_type' => LaboratorieEmployee::class, // استخدام اسم الكلاس الكامل
    //             'owner_id' => $laboratorieEmployee->id,
    //         ]);
    //         Log::info("GlobalEmail record created for new LaboratorieEmployee ID: {$laboratorieEmployee->id}, Email: {$laboratorieEmployee->email}");
    //     } catch (\Exception $e) {
    //         Log::error("Error creating GlobalEmail for LaboratorieEmployee ID {$laboratorieEmployee->id}: " . $e->getMessage());
    //         // يمكنك هنا رمي استثناء أو إرسال إشعار إذا أردت
    //     }
    // }


    // public function updated(LaboratorieEmployee $laboratorieEmployee)
    // {
    //     // تحقق مما إذا كان الإيميل قد تغير
    //     if ($laboratorieEmployee->isDirty('email')) {
    //         try {
    //             GlobalEmail::updateOrCreate(
    //                 ['owner_type' => LaboratorieEmployee::class, 'owner_id' => $laboratorieEmployee->id],
    //                 ['email' => $laboratorieEmployee->email]
    //             );
    //             Log::info("GlobalEmail record updated for LaboratorieEmployee ID: {$laboratorieEmployee->id}, New Email: {$laboratorieEmployee->email}");
    //         } catch (\Exception $e) {
    //             Log::error("Error updating GlobalEmail for LaboratorieEmployee ID {$laboratorieEmployee->id}: " . $e->getMessage());
    //         }
    //     }
    // }



    public function updated(LaboratorieEmployee $laboratorieEmployee): void
    {
        // --- الجزء الخاص بـ GlobalEmail (افترض أنه موجود لديك ولم يتغير) ---
        if ($laboratorieEmployee->isDirty('email')) {
            try {
                if ($laboratorieEmployee->email) {
                    GlobalEmail::updateOrCreate(
                        ['owner_type' => LaboratorieEmployee::class, 'owner_id' => $laboratorieEmployee->id], // شروط البحث
                        ['email' => strtolower($laboratorieEmployee->email)] // البيانات للتحديث أو الإنشاء
                    );
                    Log::info("GlobalEmail record updated for LaboratorieEmployee ID: {$laboratorieEmployee->id}, New Email: {$laboratorieEmployee->email}");
                } else { // إذا تم حذف الإيميل
                    GlobalEmail::where('owner_type', LaboratorieEmployee::class)
                        ->where('owner_id', $laboratorieEmployee->id)
                        ->delete();
                    Log::info("GlobalEmail record deleted for LaboratorieEmployee ID: {$laboratorieEmployee->id} as email was set to null.");
                }
            } catch (\Exception $e) {
                Log::error("Error updating GlobalEmail for LaboratorieEmployee ID {$laboratorieEmployee->id}: " . $e->getMessage());
            }
        }

        // --- الجزء الجديد الخاص بـ GlobalIdentifier (لرقم الهوية فقط) ---
        if ($laboratorieEmployee->isDirty('national_id')) {
            try {
                if ($laboratorieEmployee->national_id) { // إذا كان هناك قيمة جديدة لرقم الهوية
                    GlobalIdentifier::updateOrCreate(
                        [ // شروط البحث: ابحث عن سجل لهذا المالك
                            'owner_type' => LaboratorieEmployee::class,
                            'owner_id' => $laboratorieEmployee->id,
                        ],
                        [ // البيانات للتحديث أو الإنشاء
                            'national_id' => $laboratorieEmployee->national_id,
                        ]
                    );
                    Log::info("GlobalIdentifier (national_id) updated for LaboratorieEmployee ID: {$laboratorieEmployee->id}, New National ID: {$laboratorieEmployee->national_id}");
                } else { // إذا تم حذف رقم الهوية (جعله null)
                    GlobalIdentifier::where('owner_type', LaboratorieEmployee::class)
                        ->where('owner_id', $laboratorieEmployee->id)
                        ->delete(); // حذف السجل بالكامل من global_identifiers لهذا المالك
                    Log::info("GlobalIdentifier (national_id) deleted for LaboratorieEmployee ID: {$laboratorieEmployee->id} as national_id was set to null.");
                }
            } catch (\Exception $e) {
                Log::error("Error updating GlobalIdentifier (national_id) for LaboratorieEmployee ID {$laboratorieEmployee->id}: " . $e->getMessage());
            }
        }
    }



    // public function deleted(LaboratorieEmployee $laboratorieEmployee)
    // {
    //     try {
    //         GlobalEmail::where('owner_type', LaboratorieEmployee::class)
    //             ->where('owner_id', $laboratorieEmployee->id)
    //             ->delete();
    //         Log::info("GlobalEmail record deleted for LaboratorieEmployee ID: {$laboratorieEmployee->id}, Email: {$laboratorieEmployee->email}");
    //     } catch (\Exception $e) {
    //         Log::error("Error deleting GlobalEmail for LaboratorieEmployee ID {$laboratorieEmployee->id}: " . $e->getMessage());
    //     }
    // }


    public function deleted(LaboratorieEmployee $laboratorieEmployee): void
    {
        // --- الجزء الخاص بـ GlobalEmail (افترض أنه موجود لديك ولم يتغير) ---
        try {
            GlobalEmail::where('owner_type', LaboratorieEmployee::class)
                ->where('owner_id', $laboratorieEmployee->id)
                ->delete();
            Log::info("GlobalEmail record deleted for LaboratorieEmployee ID: {$laboratorieEmployee->id}");
        } catch (\Exception $e) {
            Log::error("Error deleting GlobalEmail for LaboratorieEmployee ID {$laboratorieEmployee->id}: " . $e->getMessage());
        }

        // --- الجزء الجديد الخاص بـ GlobalIdentifier (لرقم الهوية فقط) ---
        try {
            GlobalIdentifier::where('owner_type', LaboratorieEmployee::class)
                ->where('owner_id', $laboratorieEmployee->id)
                ->delete();
            Log::info("GlobalIdentifier (national_id) record deleted for LaboratorieEmployee ID: {$laboratorieEmployee->id}");
        } catch (\Exception $e) {
            Log::error("Error deleting GlobalIdentifier (national_id) for LaboratorieEmployee ID {$laboratorieEmployee->id}: " . $e->getMessage());
        }
    }
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
