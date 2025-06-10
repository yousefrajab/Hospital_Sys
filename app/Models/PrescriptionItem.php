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
        'refills_allowed', // عدد مرات إعادة الصرف المسموح بها لهذا البند
        'refills_done',    // عدد مرات إعادة الصرف التي تمت لهذا البند
        'is_prn'
    ];

    protected $casts = [
        'is_prn' => 'boolean',
        'refills_allowed' => 'integer',
        'refills_done' => 'integer',
        'quantity_prescribed' => 'decimal:2', // أو integer حسب ما تخزن
    ];

    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }

    public function medication()
    {
        return $this->belongsTo(Medication::class);
    }

    // لا توجد دالة getStatusesForFilter هنا
}
