<?php // app/Models/Bed.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bed extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'bed_number',
        'type',
        'status',
        // 'is_window_side', // معلقة
        // 'features',       // معلقة
    ];

    // protected $casts = [
    //     'is_window_side' => 'boolean', // إذا أضفتها لاحقًا
    //     'features' => 'array',       // إذا أضفتها لاحقًا
    // ];

    public const TYPE_STANDARD = 'standard';
    public const TYPE_ICU = 'icu_bed';
    public const TYPE_PEDIATRIC = 'pediatric_bed';
    public const TYPE_SPECIAL_CARE = 'special_care_bed';
    public const TYPE_OTHER = 'other';

    public const STATUS_AVAILABLE = 'available';
    public const STATUS_OCCUPIED = 'occupied';
    // يمكنك إضافة ثوابت للحالات المعلقة (reserved, maintenance, cleaning)

    /**
     * الغرفة التي ينتمي إليها هذا السرير.
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }


    public static function getBedTypes(): array
    {
        return [
            self::TYPE_STANDARD => 'سرير قياسي',
            self::TYPE_ICU => 'سرير عناية مركزة',
            self::TYPE_PEDIATRIC => 'سرير أطفال',
            self::TYPE_SPECIAL_CARE => 'سرير رعاية خاصة',
            self::TYPE_OTHER => 'آخر',
        ];
    }

    public static function getAllBedStatuses(): array
    {
        return [
            self::STATUS_AVAILABLE => 'متاح',
            self::STATUS_OCCUPIED => 'مشغول',
            // 'reserved' => 'محجوز',
            // 'maintenance' => 'تحت الصيانة',
            // 'cleaning' => 'قيد التنظيف',
        ];
    }

    /**
     * سجل الدخول الحالي النشط المرتبط بهذا السرير (إذا كان مشغولاً).
     */
    public function currentAdmission()
    {
        return $this->hasOne(PatientAdmission::class)
            ->whereNull('discharge_date')
            ->where('status', PatientAdmission::STATUS_ADMITTED); // افترض أن لديك هذا الثابت في PatientAdmission
    }

    /**
     * المريض الذي يشغل هذا السرير حاليًا (عبر currentAdmission).
     */
    public function currentPatient()
    {
        return $this->hasOneThrough(
            Patient::class,
            PatientAdmission::class,
            'bed_id',     // Foreign key on patient_admissions table
            'id',         // Foreign key on patients table
            'id',         // Local key on beds table
            'patient_id'  // Local key on patient_admissions table
        )->whereNull('patient_admissions.discharge_date')
            ->where('patient_admissions.status', PatientAdmission::STATUS_ADMITTED);
    }

    /**
     * جميع سجلات الدخول التي تمت على هذا السرير.
     */
    public function admissionsHistory()
    {
        return $this->hasMany(PatientAdmission::class);
    }

    /**
     * عند تغيير حالة السرير، قم بتحديث حالة الغرفة.
     * هذا يتم عادةً من خلال Observer.
     */
    protected static function booted()
    {
        static::saved(function (Bed $bed) {
            // تأكد من وجود علاقة الغرفة قبل محاولة تحديث حالتها
            if ($bed->room) {
                $bed->room->updateOccupancyStatus();
            }
        });

        static::deleted(function (Bed $bed) {
            // تأكد من وجود علاقة الغرفة قبل محاولة تحديث حالتها
            if ($bed->room) {
                $bed->room->updateOccupancyStatus();
            }
        });
    }
}
