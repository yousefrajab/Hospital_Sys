<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
    // protected static function booted()
    // {
    //     static::creating(function (Prescription $prescription) {
    //         if (empty($prescription->prescription_number)) {
    //             // مثال بسيط: PRSC-YYYYMMDD-XXXX (رقم عشوائي)
    //             $prescription->prescription_number = 'PRSC-' . now()->format('Ymd') . '-' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 6));
    //         }
    //     });
    // }
    public function dispensedByPharmacyEmployee()
    {
        // افترض أن اسم موديل الموظف هو Employee
        // وأن المفتاح الأجنبي هو dispensed_by_pharmacy_employee_id
        // وأن المفتاح المحلي في جدول employees هو id (الافتراضي)
        return $this->belongsTo(PharmacyEmployee::class, 'dispensed_by_pharmacy_employee_id');
    }

    protected static function booted()
    {
        static::creating(function ($prescription) {
            if (empty($prescription->prescription_number)) {
                $datePart = now()->format('Ymd');
                $uniquePart = strtoupper(Str::random(6));
                $baseNumber = 'PRSC-' . $datePart . '-' . $uniquePart;
                $count = 1;
                $prescription->prescription_number = $baseNumber;
                // حلقة للتحقق من التفرد (نادر جدًا أن تحتاجها مع random(6) + التاريخ)
                while (static::where('prescription_number', $prescription->prescription_number)->exists()) {
                    $uniquePart = strtoupper(Str::random(6));
                    $prescription->prescription_number = 'PRSC-' . $datePart . '-' . $uniquePart . '-' . $count;
                    $count++;
                }
            }
        });
    }

    /**
     * إرجاع مصفوفة بحالات الوصفة كنص مقروء للاستخدام في الفلاتر أو العرض.
     *
     * @return array
     */
    public static function getStatusesForFilter(): array
    {
        return [
            self::STATUS_NEW => 'جديدة', // تم إنشاؤها بواسطة الطبيب
            self::STATUS_APPROVED => 'معتمدة', // جاهزة للصرف من الصيدلية
            self::STATUS_PENDING_REVIEW => 'قيد المراجعة', // إذا كان هناك خطوة مراجعة
            self::STATUS_DISPENSED => 'تم صرفها بالكامل',
            self::STATUS_PARTIALLY_DISPENSED => 'مصروفة جزئياً',
            self::STATUS_ON_HOLD => 'قيد الانتظار', // معلقة لسبب ما في الصيدلية
            self::STATUS_CANCELLED_BY_DOCTOR => 'ملغاة (بواسطة الطبيب)',
            self::STATUS_CANCELLED_BY_PHARMACIST => 'ملغاة (بواسطة الصيدلي)',
            self::STATUS_CANCELLED_BY_PATIENT => 'ملغاة (بواسطة المريض)', // إذا كان المريض يمكنه الإلغاء
            self::STATUS_EXPIRED => 'منتهية الصلاحية', // إذا كان للوصفات تاريخ انتهاء
            // يمكنك إضافة أو إزالة الحالات حسب نظامك
        ];
    }
}
