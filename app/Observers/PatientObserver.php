<?php

namespace App\Observers;

use App\Models\Patient;
use App\Models\GlobalEmail; // سأفترض أن هذا لا يزال مستخدمًا للإيميلات كما في كودك الأصلي
use App\Models\GlobalIdentifier; // ** الموديل الجديد لرقم الهوية **
use Illuminate\Support\Facades\Log;

class PatientObserver
{
    /**
     * Handle the Patient "created" event.
     */
    public function created(Patient $patient): void
    {
        // --- الجزء الخاص بـ GlobalEmail (افترض أنه موجود لديك ولم يتغير) ---
        try {
            if ($patient->email) { // تحقق من وجود الإيميل قبل محاولة إنشاء السجل
                GlobalEmail::create([ // استخدام موديل GlobalEmail هنا
                    'email' => strtolower($patient->email),
                    'owner_type' => Patient::class,
                    'owner_id' => $patient->id,
                ]);
                Log::info("GlobalEmail record created for new Patient ID: {$patient->id}, Email: {$patient->email}");
            }
        } catch (\Exception $e) {
            Log::error("Error creating GlobalEmail for Patient ID {$patient->id}: " . $e->getMessage());
        }

        // --- الجزء الجديد الخاص بـ GlobalIdentifier (لرقم الهوية فقط) ---
        try {
            if ($patient->national_id) { // تحقق من وجود رقم الهوية
                GlobalIdentifier::create([
                    'national_id' => $patient->national_id, // العمود national_id في جدول global_identifiers
                    'owner_type' => Patient::class,
                    'owner_id' => $patient->id,
                ]);
                Log::info("GlobalIdentifier (national_id) created for new Patient ID: {$patient->id}, National ID: {$patient->national_id}");
            }
        } catch (\Exception $e) {
            Log::error("Error creating GlobalIdentifier (national_id) for Patient ID {$patient->id}: " . $e->getMessage());
        }
    }

    /**
     * Handle the Patient "updated" event.
     */
    public function updated(Patient $patient): void
    {
        // --- الجزء الخاص بـ GlobalEmail (افترض أنه موجود لديك ولم يتغير) ---
        if ($patient->isDirty('email')) {
            try {
                if ($patient->email) {
                    GlobalEmail::updateOrCreate(
                        ['owner_type' => Patient::class, 'owner_id' => $patient->id], // شروط البحث
                        ['email' => strtolower($patient->email)] // البيانات للتحديث أو الإنشاء
                    );
                    Log::info("GlobalEmail record updated for Patient ID: {$patient->id}, New Email: {$patient->email}");
                } else { // إذا تم حذف الإيميل
                    GlobalEmail::where('owner_type', Patient::class)
                        ->where('owner_id', $patient->id)
                        ->delete();
                    Log::info("GlobalEmail record deleted for Patient ID: {$patient->id} as email was set to null.");
                }
            } catch (\Exception $e) {
                Log::error("Error updating GlobalEmail for Patient ID {$patient->id}: " . $e->getMessage());
            }
        }

        // --- الجزء الجديد الخاص بـ GlobalIdentifier (لرقم الهوية فقط) ---
        if ($patient->isDirty('national_id')) {
            try {
                if ($patient->national_id) { // إذا كان هناك قيمة جديدة لرقم الهوية
                    GlobalIdentifier::updateOrCreate(
                        [ // شروط البحث: ابحث عن سجل لهذا المالك
                            'owner_type' => Patient::class,
                            'owner_id' => $patient->id,
                        ],
                        [ // البيانات للتحديث أو الإنشاء
                            'national_id' => $patient->national_id,
                        ]
                    );
                    Log::info("GlobalIdentifier (national_id) updated for Patient ID: {$patient->id}, New National ID: {$patient->national_id}");
                } else { // إذا تم حذف رقم الهوية (جعله null)
                    GlobalIdentifier::where('owner_type', Patient::class)
                        ->where('owner_id', $patient->id)
                        ->delete(); // حذف السجل بالكامل من global_identifiers لهذا المالك
                    Log::info("GlobalIdentifier (national_id) deleted for Patient ID: {$patient->id} as national_id was set to null.");
                }
            } catch (\Exception $e) {
                Log::error("Error updating GlobalIdentifier (national_id) for Patient ID {$patient->id}: " . $e->getMessage());
            }
        }
    }

    /**
     * Handle the Patient "deleted" event.
     */
    public function deleted(Patient $patient): void
    {
        // --- الجزء الخاص بـ GlobalEmail (افترض أنه موجود لديك ولم يتغير) ---
        try {
            GlobalEmail::where('owner_type', Patient::class)
                ->where('owner_id', $patient->id)
                ->delete();
            Log::info("GlobalEmail record deleted for Patient ID: {$patient->id}");
        } catch (\Exception $e) {
            Log::error("Error deleting GlobalEmail for Patient ID {$patient->id}: " . $e->getMessage());
        }

        // --- الجزء الجديد الخاص بـ GlobalIdentifier (لرقم الهوية فقط) ---
        try {
            GlobalIdentifier::where('owner_type', Patient::class)
                ->where('owner_id', $patient->id)
                ->delete();
            Log::info("GlobalIdentifier (national_id) record deleted for Patient ID: {$patient->id}");
        } catch (\Exception $e) {
            Log::error("Error deleting GlobalIdentifier (national_id) for Patient ID {$patient->id}: " . $e->getMessage());
        }
    }

    /**
     * Handle the Patient "restored" event.
     */
    public function restored(Patient $patient): void
    {
        // إذا كنت تستخدم SoftDeletes وتريد إعادة إنشاء السجلات عند الاستعادة
        Log::info("Patient ID: {$patient->id} restored. Re-creating global email and national ID identifiers.");
        // إعادة إنشاء سجل الإيميل (إذا كان هذا هو سلوكك المطلوب)
        if ($patient->email) {
            GlobalEmail::updateOrCreate(
                ['owner_type' => Patient::class, 'owner_id' => $patient->id],
                ['email' => strtolower($patient->email)]
            );
        }
        // إعادة إنشاء سجل رقم الهوية
        if ($patient->national_id) {
            GlobalIdentifier::updateOrCreate(
                ['owner_type' => Patient::class, 'owner_id' => $patient->id],
                ['national_id' => $patient->national_id]
            );
        }
    }

    /**
     * Handle the Patient "force deleted" event.
     */
    public function forceDeleted(Patient $patient): void
    {
        // عند الحذف النهائي، تأكد من حذف كل شيء (نفس منطق deleted)
        Log::info("Patient ID: {$patient->id} force deleted. Ensuring global identifiers are removed.");
        $this->deleted($patient);
    }
}
