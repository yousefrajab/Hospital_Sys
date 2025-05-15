<?php // app/Models/PatientChronicDisease.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientChronicDisease extends Model
{
    use HasFactory;

    protected $table = 'patient_chronic_diseases'; // تحديد اسم الجدول صراحة (اختياري إذا كان يتبع الاصطلاح)

    protected $fillable = [
        'patient_id',
        'disease_id',
        'diagnosed_at',
        'diagnosed_by',
        'current_status',
        'treatment_plan',
        'notes',
    ];

    protected $casts = [
        'diagnosed_at' => 'date',
    ];

    // لا توجد timestamps = false هنا لأن الجدول الوسيط لديه timestamps

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function disease()
    {
        return $this->belongsTo(Disease::class);
    }

    // قيم Enum للحالة (اختياري ولكن جيد)
    public const STATUS_ACTIVE = 'active';
    public const STATUS_CONTROLLED = 'controlled';
    public const STATUS_IN_REMISSION = 'in_remission';
    public const STATUS_RESOLVED = 'resolved';

    public static function getStatuses(): array
    {
        return [
            self::STATUS_ACTIVE => 'نشط',
            self::STATUS_CONTROLLED => 'تحت السيطرة',
            self::STATUS_IN_REMISSION => 'في حالة هدوء',
            self::STATUS_RESOLVED => 'تم الشفاء/حل المشكلة',
        ];
    }
}
