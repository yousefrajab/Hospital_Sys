<?php

namespace App\Observers;

use App\Models\Doctor;
use App\Models\GlobalEmail;
use App\Models\GlobalIdentifier;
use Illuminate\Support\Facades\Log; // لإضافة تسجيل

class DoctorObserver
{
    /**
     * Handle the Doctor "created" event.
     */
    public function created(Doctor $doctor): void
    {
        // --- الجزء الخاص بـ GlobalEmail (افترض أنه موجود لديك ولم يتغير) ---
        try {
            if ($doctor->email) { // تحقق من وجود الإيميل قبل محاولة إنشاء السجل
                GlobalEmail::create([ // استخدام موديل GlobalEmail هنا
                    'email' => strtolower($doctor->email),
                    'owner_type' => Doctor::class,
                    'owner_id' => $doctor->id,
                ]);
                Log::info("GlobalEmail record created for new Doctor ID: {$doctor->id}, Email: {$doctor->email}");
            }
        } catch (\Exception $e) {
            Log::error("Error creating GlobalEmail for Doctor ID {$doctor->id}: " . $e->getMessage());
        }

        // --- الجزء الجديد الخاص بـ GlobalIdentifier (لرقم الهوية فقط) ---
        try {
            if ($doctor->national_id) { // تحقق من وجود رقم الهوية
                GlobalIdentifier::create([
                    'national_id' => $doctor->national_id, // العمود national_id في جدول global_identifiers
                    'owner_type' => Doctor::class,
                    'owner_id' => $doctor->id,
                ]);
                Log::info("GlobalIdentifier (national_id) created for new Doctor ID: {$doctor->id}, National ID: {$doctor->national_id}");
            }
        } catch (\Exception $e) {
            Log::error("Error creating GlobalIdentifier (national_id) for Doctor ID {$doctor->id}: " . $e->getMessage());
        }
    }

    // public function created(Doctor $doctor)
    // {
    //     try {
    //         GlobalEmail::create([
    //             'email' => $doctor->email,
    //             'owner_type' => Doctor::class, // استخدام اسم الكلاس الكامل
    //             'owner_id' => $doctor->id,
    //         ]);
    //         Log::info("GlobalEmail record created for new Doctor ID: {$doctor->id}, Email: {$doctor->email}");
    //     } catch (\Exception $e) {
    //         Log::error("Error creating GlobalEmail for Doctor ID {$doctor->id}: " . $e->getMessage());
    //         // يمكنك هنا رمي استثناء أو إرسال إشعار إذا أردت
    //     }
    // }


    // public function updated(Doctor $doctor)
    // {
    //     // تحقق مما إذا كان الإيميل قد تغير
    //     if ($doctor->isDirty('email')) {
    //         try {
    //             GlobalEmail::updateOrCreate(
    //                 ['owner_type' => Doctor::class, 'owner_id' => $doctor->id],
    //                 ['email' => $doctor->email]
    //             );
    //             Log::info("GlobalEmail record updated for Doctor ID: {$doctor->id}, New Email: {$doctor->email}");
    //         } catch (\Exception $e) {
    //             Log::error("Error updating GlobalEmail for Doctor ID {$doctor->id}: " . $e->getMessage());
    //         }
    //     }
    // }



    public function updated(Doctor $doctor): void
    {
        // --- الجزء الخاص بـ GlobalEmail (افترض أنه موجود لديك ولم يتغير) ---
        if ($doctor->isDirty('email')) {
            try {
                if ($doctor->email) {
                    GlobalEmail::updateOrCreate(
                        ['owner_type' => Doctor::class, 'owner_id' => $doctor->id], // شروط البحث
                        ['email' => strtolower($doctor->email)] // البيانات للتحديث أو الإنشاء
                    );
                    Log::info("GlobalEmail record updated for Doctor ID: {$doctor->id}, New Email: {$doctor->email}");
                } else { // إذا تم حذف الإيميل
                    GlobalEmail::where('owner_type', Doctor::class)
                        ->where('owner_id', $doctor->id)
                        ->delete();
                    Log::info("GlobalEmail record deleted for Doctor ID: {$doctor->id} as email was set to null.");
                }
            } catch (\Exception $e) {
                Log::error("Error updating GlobalEmail for Doctor ID {$doctor->id}: " . $e->getMessage());
            }
        }

        // --- الجزء الجديد الخاص بـ GlobalIdentifier (لرقم الهوية فقط) ---
        if ($doctor->isDirty('national_id')) {
            try {
                if ($doctor->national_id) { // إذا كان هناك قيمة جديدة لرقم الهوية
                    GlobalIdentifier::updateOrCreate(
                        [ // شروط البحث: ابحث عن سجل لهذا المالك
                            'owner_type' => Doctor::class,
                            'owner_id' => $doctor->id,
                        ],
                        [ // البيانات للتحديث أو الإنشاء
                            'national_id' => $doctor->national_id,
                        ]
                    );
                    Log::info("GlobalIdentifier (national_id) updated for Doctor ID: {$doctor->id}, New National ID: {$doctor->national_id}");
                } else { // إذا تم حذف رقم الهوية (جعله null)
                    GlobalIdentifier::where('owner_type', Doctor::class)
                        ->where('owner_id', $doctor->id)
                        ->delete(); // حذف السجل بالكامل من global_identifiers لهذا المالك
                    Log::info("GlobalIdentifier (national_id) deleted for Doctor ID: {$doctor->id} as national_id was set to null.");
                }
            } catch (\Exception $e) {
                Log::error("Error updating GlobalIdentifier (national_id) for Doctor ID {$doctor->id}: " . $e->getMessage());
            }
        }
    }



    // public function deleted(Doctor $doctor)
    // {
    //     try {
    //         GlobalEmail::where('owner_type', Doctor::class)
    //             ->where('owner_id', $doctor->id)
    //             ->delete();
    //         Log::info("GlobalEmail record deleted for Doctor ID: {$doctor->id}, Email: {$doctor->email}");
    //     } catch (\Exception $e) {
    //         Log::error("Error deleting GlobalEmail for Doctor ID {$doctor->id}: " . $e->getMessage());
    //     }
    // }


    public function deleted(Doctor $doctor): void
    {
        // --- الجزء الخاص بـ GlobalEmail (افترض أنه موجود لديك ولم يتغير) ---
        try {
            GlobalEmail::where('owner_type', Doctor::class)
                ->where('owner_id', $doctor->id)
                ->delete();
            Log::info("GlobalEmail record deleted for Doctor ID: {$doctor->id}");
        } catch (\Exception $e) {
            Log::error("Error deleting GlobalEmail for Doctor ID {$doctor->id}: " . $e->getMessage());
        }

        // --- الجزء الجديد الخاص بـ GlobalIdentifier (لرقم الهوية فقط) ---
        try {
            GlobalIdentifier::where('owner_type', Doctor::class)
                ->where('owner_id', $doctor->id)
                ->delete();
            Log::info("GlobalIdentifier (national_id) record deleted for Doctor ID: {$doctor->id}");
        } catch (\Exception $e) {
            Log::error("Error deleting GlobalIdentifier (national_id) for Doctor ID {$doctor->id}: " . $e->getMessage());
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
