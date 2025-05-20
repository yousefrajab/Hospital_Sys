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
            self::STATUS_PENDING_REVIEW => 'قيد المراجعة',
            self::STATUS_APPROVED => 'موافقة',
            self::STATUS_DISPENSED => 'تم صرفها',
            self::STATUS_PARTIALLY_DISPENSED => 'تم صرفها جزئيًا',
            self::STATUS_ON_HOLD => 'معلقة',
            self::STATUS_CANCELLED_BY_DOCTOR => 'ملغاة من قبل الطبيب',
            self::STATUS_CANCELLED_BY_PHARMACIST => 'ملغاة من قبل الصيدلي',
            self::STATUS_CANCELLED_BY_PATIENT => 'ملغاة من قبل المريض',
            self::STATUS_EXPIRED => 'منتهية الصلاحية',
        ];
    }

    // public function dispensedEntries() // <-- هذا هو اسم العلاقة
    // {
    //     // افترض أن لديك موديل DispensedItem وجدول dispensed_items
    //     // وأن جدول dispensed_items يحتوي على prescription_item_id
    //     return $this->hasMany(DispensedItem::class, 'prescription_item_id');
    // }
}
