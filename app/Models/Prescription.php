<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'prescription_number',
        'patient_id',
        'doctor_id',
        'patient_admission_id',
        'prescription_date',
        'status',
        'doctor_notes',
        'pharmacy_notes',
        'dispensed_by_pharmacist_id',
        'dispensed_at',
        'total_amount',
        'is_chronic_prescription',
        'next_refill_due_date',
    ];

    protected $casts = [
        'prescription_date' => 'date:Y-m-d',
        'dispensed_at' => 'datetime',
        'is_chronic_prescription' => 'boolean',
        'next_refill_due_date' => 'date:Y-m-d',
        'total_amount' => 'decimal:2',
    ];

    // تعريف قيم Enum كـ constants
    public const STATUS_NEW = 'new';
    public const STATUS_PENDING_REVIEW = 'pending_review';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_DISPENSED = 'dispensed';
    public const STATUS_PARTIALLY_DISPENSED = 'partially_dispensed';
    public const STATUS_ON_HOLD = 'on_hold';
    public const STATUS_CANCELLED_BY_DOCTOR = 'cancelled_by_doctor';
    public const STATUS_CANCELLED_BY_PHARMACIST = 'cancelled_by_pharmacist';
    public const STATUS_CANCELLED_BY_PATIENT = 'cancelled_by_patient';
    public const STATUS_EXPIRED = 'expired';

    /**
     * المريض صاحب الوصفة.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * الطبيب الذي كتب الوصفة.
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * سجل الدخول المرتبط بهذه الوصفة (إن وجد).
     */
    public function admission() // اسم العلاقة admission
    {
        return $this->belongsTo(PatientAdmission::class, 'patient_admission_id');
    }

    /**
     * الصيدلي الذي قام بصرف الوصفة.
     */
    public function dispenser() // اسم العلاقة dispenser
    {
        return $this->belongsTo(PharmacyEmployee::class, 'dispensed_by_pharmacist_id');
    }

    /**
     * بنود الأدوية الموجودة في هذه الوصفة.
     */
    public function items()
    {
        return $this->hasMany(PrescriptionItem::class);
    }

    /**
     * (Accessor) للحصول على نص وصفي لحالة الوصفة.
     */
    public function getStatusDisplayAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_NEW => 'جديدة',
            self::STATUS_PENDING_REVIEW => 'تحت المراجعة',
            self::STATUS_APPROVED => 'موافق عليها',
            self::STATUS_DISPENSED => 'تم الصرف بالكامل',
            self::STATUS_PARTIALLY_DISPENSED => 'تم الصرف جزئيًا',
            self::STATUS_ON_HOLD => 'معلقة',
            self::STATUS_CANCELLED_BY_DOCTOR => 'ملغاة (الطبيب)',
            self::STATUS_CANCELLED_BY_PHARMACIST => 'ملغاة (الصيدلي)',
            self::STATUS_CANCELLED_BY_PATIENT => 'ملغاة (المريض)',
            self::STATUS_EXPIRED => 'منتهية الصلاحية',
            default => 'غير معروفة',
        };
    }

    /**
     * (اختياري) يمكن إضافة دالة لإنشاء رقم وصفي فريد للوصفة تلقائيًا.
     */
    protected static function booted()
    {
        static::creating(function (Prescription $prescription) {
            if (empty($prescription->prescription_number)) {
                // مثال بسيط: PRSC-YYYYMMDD-XXXX (رقم عشوائي)
                $prescription->prescription_number = 'PRSC-' . now()->format('Ymd') . '-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 6));
            }
        });
    }
}
