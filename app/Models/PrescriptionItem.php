<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrescriptionItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'prescription_id',
        'medication_id',
        'dosage',
        'frequency',
        'duration',
        'route_of_administration',
        'quantity_prescribed',
        'instructions_for_patient',
        'refills_allowed',
        'refills_done',
        'is_prn'
    ];
    protected $casts = ['is_prn' => 'boolean'];

    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }
    public function medication()
    {
        return $this->belongsTo(Medication::class);
    }



    public static function getStatusesForFilter(): array
    {
        return [
            self::STATUS_NEW => 'جديدة',
            self::STATUS_APPROVED => 'معتمدة (جاهزة للصرف)',
            self::STATUS_DISPENSED => 'تم صرفها بالكامل',
            self::STATUS_PARTIALLY_DISPENSED => 'تم صرفها جزئيًا',
            self::STATUS_ON_HOLD => 'قيد الانتظار',
            self::STATUS_CANCELLED_BY_DOCTOR => 'ملغاة (بواسطة الطبيب)',
            // أضف الحالات الأخرى إذا أردت فلترتها
        ];
    }
}
