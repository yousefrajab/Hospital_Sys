<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'generic_name', 'description', 'category', 'manufacturer',
        'dosage_form', 'strength', 'unit_of_measure', 'barcode',
        'minimum_stock_level', 'maximum_stock_level',
        'purchase_price', 'selling_price',
        'requires_prescription',
        'contraindications', 'side_effects',
        'status',
    ];

    protected $casts = [
        'requires_prescription' => 'boolean',
        'status' => 'boolean',
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'minimum_stock_level' => 'integer',
        'maximum_stock_level' => 'integer',
    ];

    // --- الدوال المساعدة لجلب القوائم الثابتة ---
    public static function getCommonCategories(): array
    {
        return [
            'antibiotic' => 'مضاد حيوي', // ... باقي القائمة
            'analgesic' => 'مسكن للألم',
            'antipyretic' => 'خافض للحرارة',
            'anti_inflammatory' => 'مضاد للالتهاب',
            'antihistamine' => 'مضاد للهيستامين (للحساسية)',
            'cough_suppressant' => 'مهدئ للسعال',
            'decongestant' => 'مزيل للاحتقان',
            'antacid' => 'مضاد للحموضة',
            'laxative' => 'ملين',
            'vitamin' => 'فيتامين/مكمل غذائي',
            'cardiovascular' => 'أدوية القلب والأوعية الدموية',
            'diabetes_medication' => 'أدوية السكري',
            'other' => 'أخرى',
        ];
    }

    public static function getCommonDosageForms(): array
    {
        return [
            'tablet' => 'أقراص (حبوب)', // ... باقي القائمة
            'capsule' => 'كبسولات',
            'syrup' => 'شراب',
            'suspension' => 'معلق',
            'injection_iv' => 'حقن وريدي',
            'injection_im' => 'حقن عضلي',
            'injection_sc' => 'حقن تحت الجلد',
            'ointment' => 'مرهم',
            'cream' => 'كريم',
            'gel' => 'جل',
            'suppository' => 'تحاميل',
            'inhaler' => 'بخاخ/جهاز استنشاق',
            'drops_eye' => 'قطرات للعين',
            'drops_ear' => 'قطرات للأذن',
            'drops_nasal' => 'قطرات للأنف',
            'patch' => 'لصقة جلدية',
            'powder' => 'مسحوق (بودرة)',
            'solution' => 'محلول',
            'other' => 'شكل آخر',
        ];
    }

    public static function getCommonUnitsOfMeasure(): array
    {
        return [
            'tablet' => 'قرص', // ... باقي القائمة
            'capsule' => 'كبسولة',
            'ml' => 'مل (مليلتر)',
            'mg' => 'مجم (مليجرام)',
            'g' => 'جم (جرام)',
            'unit' => 'وحدة (Unit)',
            'package' => 'علبة',
            'bottle' => 'زجاجة',
            'tube' => 'أنبوب',
            'sachet' => 'كيس (مغلف)',
            'application' => 'تطبيق',
            'puff' => 'بخة',
            'drop' => 'قطرة',
            'other' => 'وحدة أخرى',
        ];
    }

    // --- الـ Accessors المفقودة ---
    /**
     * Accessor: للحصول على النص المقروء للشكل الصيدلاني.
     * @return string
     */
    public function getDosageFormDisplayAttribute(): string
    {
        if ($this->dosage_form) {
            // تستدعي الدالة الثابتة من نفس الكلاس
            $commonForms = self::getCommonDosageForms();
            return $commonForms[$this->dosage_form] ?? ucfirst(str_replace('_', ' ', $this->dosage_form));
        }
        return ''; // أو 'غير محدد' أو أي قيمة افتراضية
    }

    /**
     * Accessor: للحصول على النص المقروء لوحدة القياس.
     * @return string
     */
    public function getUnitOfMeasureDisplayAttribute(): string
    {
        if ($this->unit_of_measure) {
             // تستدعي الدالة الثابتة من نفس الكلاس
            $commonUnits = self::getCommonUnitsOfMeasure();
            return $commonUnits[$this->unit_of_measure] ?? ucfirst(str_replace('_', ' ', $this->unit_of_measure));
        }
        return ''; // أو 'غير محدد'
    }
    // --- نهاية الـ Accessors ---


    public function stocks()
    {
        return $this->hasMany(PharmacyStock::class);
    }

    public function prescriptionItems()
    {
        return $this->hasMany(PrescriptionItem::class);
    }

    public function getTotalStockAttribute(): int
    {
        return $this->stocks()
                    ->where('quantity_on_hand', '>', 0)
                    ->whereDate('expiry_date', '>', now())
                    ->sum('quantity_on_hand');
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->minimum_stock_level !== null && $this->total_stock <= $this->minimum_stock_level;
    }
}
