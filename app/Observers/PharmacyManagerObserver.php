<?php

namespace App\Observers;

use App\Models\GlobalEmail;
use App\Models\PharmacyManager;
use App\Models\GlobalIdentifier;
use Illuminate\Support\Facades\Log;

class PharmacyManagerObserver
{
    public function created(PharmacyManager $pharmacy_manager): void
    {
        // --- الجزء الخاص بـ GlobalEmail (افترض أنه موجود لديك ولم يتغير) ---
        try {
            if ($pharmacy_manager->email) { // تحقق من وجود الإيميل قبل محاولة إنشاء السجل
                GlobalEmail::create([ // استخدام موديل GlobalEmail هنا
                    'email' => strtolower($pharmacy_manager->email),
                    'owner_type' => PharmacyManager::class,
                    'owner_id' => $pharmacy_manager->id,
                ]);
                Log::info("GlobalEmail record created for new PharmacyManager ID: {$pharmacy_manager->id}, Email: {$pharmacy_manager->email}");
            }
        } catch (\Exception $e) {
            Log::error("Error creating GlobalEmail for PharmacyManager ID {$pharmacy_manager->id}: " . $e->getMessage());
        }

        // --- الجزء الجديد الخاص بـ GlobalIdentifier (لرقم الهوية فقط) ---
        try {
            if ($pharmacy_manager->national_id) { // تحقق من وجود رقم الهوية
                GlobalIdentifier::create([
                    'national_id' => $pharmacy_manager->national_id, // العمود national_id في جدول global_identifiers
                    'owner_type' => PharmacyManager::class,
                    'owner_id' => $pharmacy_manager->id,
                ]);
                Log::info("GlobalIdentifier (national_id) created for new PharmacyManager ID: {$pharmacy_manager->id}, National ID: {$pharmacy_manager->national_id}");
            }
        } catch (\Exception $e) {
            Log::error("Error creating GlobalIdentifier (national_id) for PharmacyManager ID {$pharmacy_manager->id}: " . $e->getMessage());
        }
    }



    public function updated(PharmacyManager $pharmacy_manager): void
    {
        // --- الجزء الخاص بـ GlobalEmail (افترض أنه موجود لديك ولم يتغير) ---
        if ($pharmacy_manager->isDirty('email')) {
            try {
                if ($pharmacy_manager->email) {
                    GlobalEmail::updateOrCreate(
                        ['owner_type' => PharmacyManager::class, 'owner_id' => $pharmacy_manager->id], // شروط البحث
                        ['email' => strtolower($pharmacy_manager->email)] // البيانات للتحديث أو الإنشاء
                    );
                    Log::info("GlobalEmail record updated for PharmacyManager ID: {$pharmacy_manager->id}, New Email: {$pharmacy_manager->email}");
                } else { // إذا تم حذف الإيميل
                    GlobalEmail::where('owner_type', PharmacyManager::class)
                        ->where('owner_id', $pharmacy_manager->id)
                        ->delete();
                    Log::info("GlobalEmail record deleted for PharmacyManager ID: {$pharmacy_manager->id} as email was set to null.");
                }
            } catch (\Exception $e) {
                Log::error("Error updating GlobalEmail for PharmacyManager ID {$pharmacy_manager->id}: " . $e->getMessage());
            }
        }

        // --- الجزء الجديد الخاص بـ GlobalIdentifier (لرقم الهوية فقط) ---
        if ($pharmacy_manager->isDirty('national_id')) {
            try {
                if ($pharmacy_manager->national_id) { // إذا كان هناك قيمة جديدة لرقم الهوية
                    GlobalIdentifier::updateOrCreate(
                        [ // شروط البحث: ابحث عن سجل لهذا المالك
                            'owner_type' => PharmacyManager::class,
                            'owner_id' => $pharmacy_manager->id,
                        ],
                        [ // البيانات للتحديث أو الإنشاء
                            'national_id' => $pharmacy_manager->national_id,
                        ]
                    );
                    Log::info("GlobalIdentifier (national_id) updated for PharmacyManager ID: {$pharmacy_manager->id}, New National ID: {$pharmacy_manager->national_id}");
                } else { // إذا تم حذف رقم الهوية (جعله null)
                    GlobalIdentifier::where('owner_type', PharmacyManager::class)
                        ->where('owner_id', $pharmacy_manager->id)
                        ->delete(); // حذف السجل بالكامل من global_identifiers لهذا المالك
                    Log::info("GlobalIdentifier (national_id) deleted for PharmacyManager ID: {$pharmacy_manager->id} as national_id was set to null.");
                }
            } catch (\Exception $e) {
                Log::error("Error updating GlobalIdentifier (national_id) for PharmacyManager ID {$pharmacy_manager->id}: " . $e->getMessage());
            }
        }
    }

    public function deleted(PharmacyManager $pharmacy_manager): void
    {
        // --- الجزء الخاص بـ GlobalEmail (افترض أنه موجود لديك ولم يتغير) ---
        try {
            GlobalEmail::where('owner_type', PharmacyManager::class)
                ->where('owner_id', $pharmacy_manager->id)
                ->delete();
            Log::info("GlobalEmail record deleted for PharmacyManager ID: {$pharmacy_manager->id}");
        } catch (\Exception $e) {
            Log::error("Error deleting GlobalEmail for PharmacyManager ID {$pharmacy_manager->id}: " . $e->getMessage());
        }

        // --- الجزء الجديد الخاص بـ GlobalIdentifier (لرقم الهوية فقط) ---
        try {
            GlobalIdentifier::where('owner_type', PharmacyManager::class)
                ->where('owner_id', $pharmacy_manager->id)
                ->delete();
            Log::info("GlobalIdentifier (national_id) record deleted for PharmacyManager ID: {$pharmacy_manager->id}");
        } catch (\Exception $e) {
            Log::error("Error deleting GlobalIdentifier (national_id) for PharmacyManager ID {$pharmacy_manager->id}: " . $e->getMessage());
        }
    }

    /**
     * Handle the PharmacyManager "restored" event.
     *
     * @param  \App\Models\PharmacyManager  $pharmacy_manager
     * @return void
     */
    public function restored(PharmacyManager $pharmacy_manager)
    {
        //
    }

    /**
     * Handle the PharmacyManager "force deleted" event.
     *
     * @param  \App\Models\PharmacyManager  $pharmacy_manager
     * @return void
     */
    public function forceDeleted(PharmacyManager $pharmacy_manager)
    {
        //
    }
}
