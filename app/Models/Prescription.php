<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
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
        'dispensed_by_pharmacy_employee_id', // هذا هو الصحيح بدلاً من dispensed_by_pharmacy_employee_id
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
    public const STATUS_NEW = 'new';                         // الوصفة أنشئت للتو من قبل الطبيب
    public const STATUS_PENDING_REVIEW = 'pending_review';   // الصيدلية تقوم بمراجعتها (اختياري)
    public const STATUS_APPROVED = 'approved';               // الطبيب/النظام وافق عليها (أو الصيدلي بعد المراجعة)
    public const STATUS_PROCESSING = 'processing';           // الصيدلي بدأ في تجهيز الأدوية
    public const STATUS_READY_FOR_PICKUP = 'ready_for_pickup'; // الوصفة جاهزة للاستلام من قبل المريض
    public const STATUS_DISPENSED = 'dispensed';               // تم صرف جميع الأدوية للمريض
    public const STATUS_PARTIALLY_DISPENSED = 'partially_dispensed'; // تم صرف جزء من الأدوية
    public const STATUS_ON_HOLD = 'on_hold';                 // الوصفة معلقة لسبب ما (مثل نقص دواء)
    public const STATUS_CANCELLED_BY_DOCTOR = 'cancelled_by_doctor';
    public const STATUS_CANCELLED_BY_PHARMACIST = 'cancelled_by_pharmacist';
    public const STATUS_CANCELLED_BY_PATIENT = 'cancelled_by_patient'; // إذا كان المريض يمكنه الإلغاء
    public const STATUS_EXPIRED = 'expired';                 // الوصفة انتهت صلاحيتها (إذا كان لها تاريخ انتهاء)
    public const STATUS_REFILL_REQUESTED = 'refill_requested'; // المريض طلب إعادة صرف
    public const STATUS_REFILL_INTERNAL_APPROVED = 'refill_internal_approved'; // الطبيب وافق، تنتظر إجراء الصيدلية
    public const STATUS_REFILL_DENIED_BY_DOCTOR  = 'refill_denied_by_doctor';  // الطبيب رفض طلب التجديد
    public const STATUS_REFILL_DENIED_BY_PHARMACY = 'refill_denied_by_pharmacy';
    public const STATUS_RENEWAL_APPROVED = 'renewal_approved'; // تجديد معتمد (جاهز للصرف)
    public const STATUS_RENEWAL_DENIED = 'renewal_denied'; // تجديد مرفوض (من قبل الطبيب أو الصيدلي)
    public const STATUS_RENEWAL_REQUESTED = 'renewal_requested'; // طلب تجديد من المريض
    public const STATUS_RENEWAL_INTERNAL_APPROVED = 'renewal_internal_approved'; // تجديد داخلي معتمد


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
    public function admission()
    {
        return $this->belongsTo(PatientAdmission::class, 'patient_admission_id');
    }

    /**
     * الصيدلي الذي قام بصرف الوصفة.
     * تم تغيير اسم العلاقة ليكون أكثر وضوحاً.
     */
    public function dispensedByPharmacyEmployee() // اسم العلاقة ليتطابق مع الاستخدام
    {
        return $this->belongsTo(PharmacyEmployee::class, 'dispensed_by_pharmacy_employee_id');
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
            self::STATUS_PENDING_REVIEW => 'قيد مراجعة الصيدلية',
            self::STATUS_APPROVED => 'معتمدة من الطبيب',
            self::STATUS_PROCESSING => 'قيد التجهيز بالصيدلية',
            self::STATUS_READY_FOR_PICKUP => 'جاهزة للاستلام',
            self::STATUS_DISPENSED => 'تم الصرف بالكامل',
            self::STATUS_PARTIALLY_DISPENSED => 'تم الصرف جزئيًا',
            self::STATUS_ON_HOLD => 'معلقة',
            self::STATUS_CANCELLED_BY_DOCTOR => 'ملغاة (الطبيب)',
            self::STATUS_CANCELLED_BY_PHARMACIST => 'ملغاة (الصيدلية)',
            self::STATUS_CANCELLED_BY_PATIENT => 'ملغاة (المريض)',
            self::STATUS_EXPIRED => 'منتهية الصلاحية',
            self::STATUS_REFILL_REQUESTED => 'طلب إعادة صرف',
            self::STATUS_RENEWAL_APPROVED => 'تجديد معتمد (جاهز للصرف)',
            self::STATUS_REFILL_DENIED_BY_DOCTOR => 'مرفوض (بواسطة الطبيب)',
            default => ucfirst(str_replace('_', ' ', $this->status ?? 'غير معروفة')),
        };
    }


    protected static function booted()
    {
        static::creating(function ($prescription) {
            if (empty($prescription->prescription_number)) {
                $datePart = now()->format('Ymd');
                // زيادة طول الجزء العشوائي لتقليل احتمالية التكرار بشكل أكبر
                $uniquePart = strtoupper(Str::random(8));
                $baseNumber = 'PRSC-' . $datePart . '-' . $uniquePart;
                $count = 1;
                $finalNumber = $baseNumber;
                // حلقة للتحقق من التفرد (أكثر قوة)
                while (static::where('prescription_number', $finalNumber)->exists()) {
                    $uniquePart = strtoupper(Str::random(8)); // جزء عشوائي جديد
                    $finalNumber = 'PRSC-' . $datePart . '-' . $uniquePart . ($count > 1 ? '-' . $count : '');
                    $count++;
                    if ($count > 10) { // حد أقصى للمحاولات لتجنب حلقة لا نهائية في حالة نادرة جداً
                        // يمكنك هنا إلقاء استثناء أو تسجيل خطأ فادح
                        Log::error("Failed to generate unique prescription number after multiple attempts for patient_id: {$prescription->patient_id}");
                        // قد ترغب في استخدام UUID كـ fallback هنا
                        $finalNumber = 'PRSC-ERR-' . (string) Str::uuid();
                        break;
                    }
                }
                $prescription->prescription_number = $finalNumber;
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
            self::STATUS_NEW => 'جديدة',
            self::STATUS_PENDING_REVIEW => 'قيد مراجعة الصيدلية',
            self::STATUS_APPROVED => 'معتمدة من الطبيب',
            self::STATUS_PROCESSING => 'قيد التجهيز بالصيدلية',
            self::STATUS_READY_FOR_PICKUP => 'جاهزة للاستلام',
            self::STATUS_DISPENSED => 'تم صرفها بالكامل',
            self::STATUS_PARTIALLY_DISPENSED => 'مصروفة جزئيًا',
            self::STATUS_ON_HOLD => 'معلقة',
            self::STATUS_CANCELLED_BY_DOCTOR => 'ملغاة (بواسطة الطبيب)',
            self::STATUS_CANCELLED_BY_PHARMACIST => 'ملغاة (بواسطة الصيدلية)',
            self::STATUS_CANCELLED_BY_PATIENT => 'ملغاة (بواسطة المريض)',
            self::STATUS_EXPIRED => 'منتهية الصلاحية',
            self::STATUS_REFILL_REQUESTED => 'طلب إعادة صرف',
            self::STATUS_RENEWAL_APPROVED => 'تجديد معتمد',
            self::STATUS_REFILL_DENIED_BY_DOCTOR => 'مرفوض (بواسطة الطبيب)',
        ];
    }

    /**
     * Accessor لتحديد كلاس CSS للـ badge بناءً على الحالة.
     */
    public function getStatusBadgeClassAttribute()
    {


        return 'status-' . str_replace('_', '-', $this->status ?? 'default');
    }



    public function getCanRequestRefillAttribute(): bool
    {
        // 1. يجب ألا يكون هناك طلب إعادة صرف معلق بالفعل لهذه الوصفة
        if ($this->status === self::STATUS_REFILL_REQUESTED) {
            return false;
        }

        // 2. يجب أن تكون الوصفة في حالة تسمح بإعادة الصرف مبدئياً
        $allowedStatusesForRefill = [
            self::STATUS_DISPENSED,
            self::STATUS_PARTIALLY_DISPENSED,
            // يمكنك إضافة self::STATUS_EXPIRED إذا أردت السماح بطلب تجديد لوصفة منتهية
        ];
        if (!in_array($this->status, $allowedStatusesForRefill)) {
            return false;
        }

        // 3. (الأهم) تحقق من بنود الوصفة إذا كانت تحتوي على بنود قابلة لإعادة الصرف
        // هذا يفترض أن كل بند في الوصفة لديه 'refills_allowed' و 'refills_done'
        // وأن refills_allowed > 0
        if ($this->items()->where('refills_allowed', '>', 0)->whereColumn('refills_done', '<', 'refills_allowed')->exists()) {
            return true;
        }

        // 4. حالة خاصة للوصفات المزمنة (إذا لم تعتمد على الـ refills_allowed per item)
        if ($this->is_chronic_prescription) {
            // إذا كانت مزمنة *ولا يوجد* تاريخ استحقاق próximo أو تاريخ الاستحقاق لم يأت بعد
            // هذا يعني أنك لم تحدد refills_allowed لكل بند، بل الطبيب حددها كـ مزمنة بشكل عام
            // هنا يمكنك إضافة منطق مثل: مضى شهر على آخر صرف (إذا كانت شهرية)
            // أو أن next_refill_due_date قد اقترب أو حان.
            // مثال بسيط:
            // if (!$this->next_refill_due_date || $this->next_refill_due_date->isPast() || $this->next_refill_due_date->isToday()) {
            // return true;
            // }
        }

        return false;
    }
}
