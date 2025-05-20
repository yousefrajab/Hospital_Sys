<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon; // لاستخدامه في Accessors

class PharmacyStock extends Model
{
    use HasFactory;

    protected $table = 'pharmacy_stocks'; // تحديد اسم الجدول صراحة (ممارسة جيدة)

    // ** تحديث $fillable ليشمل جميع الحقول الجديدة من الـ migration **
    protected $fillable = [
        'medication_id',
        'batch_number',
        'expiry_date',
        'quantity_on_hand',
        'initial_quantity',     // الكمية الأولية عند استلام الدفعة
        'cost_price_per_unit',  // سعر تكلفة الوحدة لهذه الدفعة
        'supplier_name',        // اسم المورد
        'received_date',        // تاريخ استلام الدفعة
                                 // إذا أضفته، قم بإضافته هنا أيضًا
        'stock_notes',          // ملاحظات على هذه الدفعة
    ];

    // ** تحديث $casts ليتوافق مع أنواع البيانات الجديدة **
    protected $casts = [
        'expiry_date' => 'date:Y-m-d',          // تحديد التنسيق المفضل عند التحويل
        'received_date' => 'date:Y-m-d',        // تحديد التنسيق المفضل عند التحويل
        'quantity_on_hand' => 'integer',
        'initial_quantity' => 'integer',
        'cost_price_per_unit' => 'decimal:2', // عدد الخانات العشرية
    ];


    public function medication()
    {
        return $this->belongsTo(Medication::class);
    }


    public function getIsExpiringSoonAttribute(): bool
    {
        if (!$this->expiry_date) {
            return false;
        }
        // يمكنك جعل عدد أيام التنبيه قابل للتكوين
        $warningDays = config('pharmacy.stock_expiry_warning_days', 90); // مثال لقراءة من ملف config
        // أو ببساطة: $warningDays = 90;

        $expiry = Carbon::parse($this->expiry_date);
        return $expiry->isAfter(now()) && $expiry->isBefore(now()->addDays($warningDays));
    }

    /**
     * Accessor: لتحديد ما إذا كانت الدفعة منتهية الصلاحية.
     * @return bool
     */
    public function getIsExpiredAttribute(): bool
    {
        if (!$this->expiry_date) {
            return false; // أو true إذا اعتبرت عدم وجود تاريخ انتهاء يعني منتهية
        }
        return Carbon::parse($this->expiry_date)->isPast();
    }

    /**
     * Accessor: (اختياري) لحساب قيمة المخزون الحالية لهذه الدفعة (الكمية * سعر التكلفة).
     * @return float|null
     */
    public function getCurrentStockValueAttribute(): ?float
    {
        if (isset($this->quantity_on_hand) && isset($this->cost_price_per_unit)) {
            return $this->quantity_on_hand * $this->cost_price_per_unit;
        }
        return null;
    }

    /**
     * Accessor: (اختياري) لحساب قيمة المخزون الأولية لهذه الدفعة.
     * @return float|null
     */
    public function getInitialStockValueAttribute(): ?float
    {
        if (isset($this->initial_quantity) && isset($this->cost_price_per_unit)) {
            return $this->initial_quantity * $this->cost_price_per_unit;
        }
        return null;
    }

    // يمكنك إضافة المزيد من الـ Scopes أو الـ Accessors حسب الحاجة
    // مثال لـ Scope لجلب الدفعات التي لم تنته صلاحيتها بعد:
    // public function scopeNotExpired($query)
    // {
    //     return $query->whereDate('expiry_date', '>', now());
    // }


    
}
