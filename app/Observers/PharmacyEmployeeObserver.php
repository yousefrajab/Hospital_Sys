<?php

namespace App\Observers;

use App\Models\GlobalEmail;
use App\Models\PharmacyEmployee;
use App\Models\GlobalIdentifier;
use Illuminate\Support\Facades\Log;

class PharmacyEmployeeObserver
{
    public function created(PharmacyEmployee $pharmacy_employee): void
    {
        // --- الجزء الخاص بـ GlobalEmail (افترض أنه موجود لديك ولم يتغير) ---
        try {
            if ($pharmacy_employee->email) { // تحقق من وجود الإيميل قبل محاولة إنشاء السجل
                GlobalEmail::create([ // استخدام موديل GlobalEmail هنا
                    'email' => strtolower($pharmacy_employee->email),
                    'owner_type' => PharmacyEmployee::class,
                    'owner_id' => $pharmacy_employee->id,
                ]);
                Log::info("GlobalEmail record created for new PharmacyEmployee ID: {$pharmacy_employee->id}, Email: {$pharmacy_employee->email}");
            }
        } catch (\Exception $e) {
            Log::error("Error creating GlobalEmail for PharmacyEmployee ID {$pharmacy_employee->id}: " . $e->getMessage());
        }

        // --- الجزء الجديد الخاص بـ GlobalIdentifier (لرقم الهوية فقط) ---
        try {
            if ($pharmacy_employee->national_id) { // تحقق من وجود رقم الهوية
                GlobalIdentifier::create([
                    'national_id' => $pharmacy_employee->national_id, // العمود national_id في جدول global_identifiers
                    'owner_type' => PharmacyEmployee::class,
                    'owner_id' => $pharmacy_employee->id,
                ]);
                Log::info("GlobalIdentifier (national_id) created for new PharmacyEmployee ID: {$pharmacy_employee->id}, National ID: {$pharmacy_employee->national_id}");
            }
        } catch (\Exception $e) {
            Log::error("Error creating GlobalIdentifier (national_id) for PharmacyEmployee ID {$pharmacy_employee->id}: " . $e->getMessage());
        }
    }



    public function updated(PharmacyEmployee $pharmacy_employee): void
    {
        // --- الجزء الخاص بـ GlobalEmail (افترض أنه موجود لديك ولم يتغير) ---
        if ($pharmacy_employee->isDirty('email')) {
            try {
                if ($pharmacy_employee->email) {
                    GlobalEmail::updateOrCreate(
                        ['owner_type' => PharmacyEmployee::class, 'owner_id' => $pharmacy_employee->id], // شروط البحث
                        ['email' => strtolower($pharmacy_employee->email)] // البيانات للتحديث أو الإنشاء
                    );
                    Log::info("GlobalEmail record updated for PharmacyEmployee ID: {$pharmacy_employee->id}, New Email: {$pharmacy_employee->email}");
                } else { // إذا تم حذف الإيميل
                    GlobalEmail::where('owner_type', PharmacyEmployee::class)
                        ->where('owner_id', $pharmacy_employee->id)
                        ->delete();
                    Log::info("GlobalEmail record deleted for PharmacyEmployee ID: {$pharmacy_employee->id} as email was set to null.");
                }
            } catch (\Exception $e) {
                Log::error("Error updating GlobalEmail for PharmacyEmployee ID {$pharmacy_employee->id}: " . $e->getMessage());
            }
        }

        // --- الجزء الجديد الخاص بـ GlobalIdentifier (لرقم الهوية فقط) ---
        if ($pharmacy_employee->isDirty('national_id')) {
            try {
                if ($pharmacy_employee->national_id) { // إذا كان هناك قيمة جديدة لرقم الهوية
                    GlobalIdentifier::updateOrCreate(
                        [ // شروط البحث: ابحث عن سجل لهذا المالك
                            'owner_type' => PharmacyEmployee::class,
                            'owner_id' => $pharmacy_employee->id,
                        ],
                        [ // البيانات للتحديث أو الإنشاء
                            'national_id' => $pharmacy_employee->national_id,
                        ]
                    );
                    Log::info("GlobalIdentifier (national_id) updated for PharmacyEmployee ID: {$pharmacy_employee->id}, New National ID: {$pharmacy_employee->national_id}");
                } else { // إذا تم حذف رقم الهوية (جعله null)
                    GlobalIdentifier::where('owner_type', PharmacyEmployee::class)
                        ->where('owner_id', $pharmacy_employee->id)
                        ->delete(); // حذف السجل بالكامل من global_identifiers لهذا المالك
                    Log::info("GlobalIdentifier (national_id) deleted for PharmacyEmployee ID: {$pharmacy_employee->id} as national_id was set to null.");
                }
            } catch (\Exception $e) {
                Log::error("Error updating GlobalIdentifier (national_id) for PharmacyEmployee ID {$pharmacy_employee->id}: " . $e->getMessage());
            }
        }
    }

    public function deleted(PharmacyEmployee $pharmacy_employee): void
    {
        // --- الجزء الخاص بـ GlobalEmail (افترض أنه موجود لديك ولم يتغير) ---
        try {
            GlobalEmail::where('owner_type', PharmacyEmployee::class)
                ->where('owner_id', $pharmacy_employee->id)
                ->delete();
            Log::info("GlobalEmail record deleted for PharmacyEmployee ID: {$pharmacy_employee->id}");
        } catch (\Exception $e) {
            Log::error("Error deleting GlobalEmail for PharmacyEmployee ID {$pharmacy_employee->id}: " . $e->getMessage());
        }

        // --- الجزء الجديد الخاص بـ GlobalIdentifier (لرقم الهوية فقط) ---
        try {
            GlobalIdentifier::where('owner_type', PharmacyEmployee::class)
                ->where('owner_id', $pharmacy_employee->id)
                ->delete();
            Log::info("GlobalIdentifier (national_id) record deleted for PharmacyEmployee ID: {$pharmacy_employee->id}");
        } catch (\Exception $e) {
            Log::error("Error deleting GlobalIdentifier (national_id) for PharmacyEmployee ID {$pharmacy_employee->id}: " . $e->getMessage());
        }
    }

    /**
     * Handle the PharmacyEmployee "restored" event.
     *
     * @param  \App\Models\PharmacyEmployee  $pharmacy_employee
     * @return void
     */
    public function restored(PharmacyEmployee $pharmacy_employee)
    {
        //
    }

    /**
     * Handle the PharmacyEmployee "force deleted" event.
     *
     * @param  \App\Models\PharmacyEmployee  $pharmacy_employee
     * @return void
     */
    public function forceDeleted(PharmacyEmployee $pharmacy_employee)
    {
        //
    }
}
