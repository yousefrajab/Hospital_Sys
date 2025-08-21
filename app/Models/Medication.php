<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'generic_name',
        'description',
        'category',
        'manufacturer',
        'dosage_form',
        'strength',
        'unit_of_measure',
        'barcode',
        'minimum_stock_level',
        'maximum_stock_level',
        'purchase_price',
        'selling_price',
        'requires_prescription',
        'contraindications',
        'side_effects',
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
            'antibiotic' => trans('phrM.category_antibiotic'),
            'analgesic' => trans('phrM.category_analgesic'),
            'antipyretic' => trans('phrM.category_antipyretic'),
            'anti_inflammatory' => trans('phrM.category_anti_inflammatory'),
            'antihistamine' => trans('phrM.category_antihistamine'),
            'cough_suppressant' => trans('phrM.category_cough_suppressant'),
            'decongestant' => trans('phrM.category_decongestant'),
            'antacid' => trans('phrM.category_antacid'),
            'laxative' => trans('phrM.category_laxative'),
            'vitamin' => trans('phrM.category_vitamin'),
            'cardiovascular' => trans('phrM.category_cardiovascular'),
            'diabetes_medication' => trans('phrM.category_diabetes_medication'),
            'other' => trans('phrM.category_other'),
        ];
    }

    public static function getCommonDosageForms(): array
    {
        return [
            'tablet' => trans('phrM.dosage_form_tablet'),
            'capsule' => trans('phrM.dosage_form_capsule'),
            'syrup' => trans('phrM.dosage_form_syrup'),
            'suspension' => trans('phrM.dosage_form_suspension'),
            'injection_iv' => trans('phrM.dosage_form_injection_iv'),
            'injection_im' => trans('phrM.dosage_form_injection_im'),
            'injection_sc' => trans('phrM.dosage_form_injection_sc'),
            'ointment' => trans('phrM.dosage_form_ointment'),
            'cream' => trans('phrM.dosage_form_cream'),
            'gel' => trans('phrM.dosage_form_gel'),
            'suppository' => trans('phrM.dosage_form_suppository'),
            'inhaler' => trans('phrM.dosage_form_inhaler'),
            'drops_eye' => trans('phrM.dosage_form_drops_eye'),
            'drops_ear' => trans('phrM.dosage_form_drops_ear'),
            'drops_nasal' => trans('phrM.dosage_form_drops_nasal'),
            'patch' => trans('phrM.dosage_form_patch'),
            'powder' => trans('phrM.dosage_form_powder'),
            'solution' => trans('phrM.dosage_form_solution'),
            'other' => trans('phrM.dosage_form_other'),
        ];
    }

    public static function getCommonUnitsOfMeasure(): array
    {
        return [
            'tablet' => trans('phrM.unit_tablet'),
            'capsule' => trans('phrM.unit_capsule'),
            'ml' => trans('phrM.unit_ml'),
            'mg' => trans('phrM.unit_mg'),
            'g' => trans('phrM.unit_g'),
            'unit' => trans('phrM.unit_unit'),
            'package' => trans('phrM.unit_package'),
            'bottle' => trans('phrM.unit_bottle'),
            'tube' => trans('phrM.unit_tube'),
            'sachet' => trans('phrM.unit_sachet'),
            'application' => trans('phrM.unit_application'),
            'puff' => trans('phrM.unit_puff'),
            'drop' => trans('phrM.unit_drop'),
            'other' => trans('phrM.unit_other'),
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
