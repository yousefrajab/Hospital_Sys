<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// (اختياري) إذا كنت ستستخدم الترجمة لاحقًا
// use Astrotomic\Translatable\Translatable;
// use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class Medication extends Model // implements TranslatableContract (إذا استخدمت الترجمة)
{
    use HasFactory;
    // use Translatable; // إذا استخدمت الترجمة

    // ** تعديل $fillable ليطابق الـ migration الحالي **
    protected $fillable = [
        'name', 'generic_name', 'description', 'category', 'manufacturer',
        'dosage_form', 'strength', 'unit_of_measure', 'barcode',
        'minimum_stock_level', 'maximum_stock_level',
        'purchase_price', 'selling_price',
        'requires_prescription',
        'contraindications', 'side_effects',
        'status',
    ];

    // public $translatedAttributes = ['name', 'description', 'contraindications', 'side_effects']; // إذا استخدمت الترجمة

    protected $casts = [
        'requires_prescription' => 'boolean',
        'status' => 'boolean',
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'minimum_stock_level' => 'integer', // تأكد من تطابق النوع
        'maximum_stock_level' => 'integer', // تأكد من تطابق النوع
    ];


    public static function getCommonCategories(): array
    {
        // يمكنك توسيع هذه القائمة أو جلبها من جدول منفصل لاحقًا إذا أردت
        return [
            'antibiotic' => 'مضاد حيوي',
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

    /**
     * إرجاع مصفوفة بالأشكال الصيدلانية الشائعة.
     * @return array
     */
    public static function getCommonDosageForms(): array
    {
        return [
            'tablet' => 'أقراص (حبوب)',
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

    /**
     * إرجاع مصفوفة بوحدات القياس الشائعة للأدوية.
     * @return array
     */
    public static function getCommonUnitsOfMeasure(): array
    {
        return [
            'tablet' => 'قرص',
            'capsule' => 'كبسولة',
            'ml' => 'مل (مليلتر)',
            'mg' => 'مجم (مليجرام)',
            'g' => 'جم (جرام)',
            'unit' => 'وحدة (Unit)', // مثل الأنسولين
            'package' => 'علبة',
            'bottle' => 'زجاجة',
            'tube' => 'أنبوب',
            'sachet' => 'كيس (مغلف)',
            'application' => 'تطبيق (Application - للمراهم مثلاً)',
            'puff' => 'بخة (للبخاخات)',
            'drop' => 'قطرة',
            'other' => 'وحدة أخرى',
        ];
    }

    public function stocks()
    {
        return $this->hasMany(PharmacyStock::class);
    }

    public function prescriptionItems()
    {
        // افترض أن لديك موديل PrescriptionItem
        // إذا لم يكن لديك بعد، يمكنك تعليق هذه العلاقة أو إنشائه لاحقًا
        return $this->hasMany(PrescriptionItem::class);
        // return null; // مؤقتًا إذا لم يكن الموديل موجودًا
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
        // تأكد من أن minimum_stock_level ليس null قبل المقارنة
        return $this->minimum_stock_level !== null && $this->total_stock <= $this->minimum_stock_level;
    }
}
