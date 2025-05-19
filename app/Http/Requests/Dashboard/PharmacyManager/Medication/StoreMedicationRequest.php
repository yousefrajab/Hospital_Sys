<?php

namespace App\Http\Requests\Dashboard\PharmacyManager\Medication;

use App\Models\Medication; // ** استيراد موديل Medication للوصول للدوال الـ static **
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth; // ** استيراد Auth **

class StoreMedicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        // افترض أن المستخدم المسجل عبر حارس 'admin' أو 'pharmacy_manager' هو المصرح له
        // عدّل هذا ليناسب نظام الصلاحيات لديك
        return Auth::guard('admin')->check() || Auth::guard('pharmacy_manager')->check();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:medications,name', // الاسم فريد في جدول medications
            'generic_name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000', // زيادة الحد الأقصى للوصف

            // الحقول التي ستستخدم قوائم منسدلة من الموديل
            'category' => ['nullable', 'string', Rule::in(array_keys(Medication::getCommonCategories()))],
            'manufacturer' => 'nullable|string|max:255', // يمكن تحويله لـ FK لاحقًا
            'dosage_form' => ['nullable', 'string', Rule::in(array_keys(Medication::getCommonDosageForms()))],
            'strength' => 'nullable|string|max:100',
            'unit_of_measure' => ['nullable', 'string', Rule::in(array_keys(Medication::getCommonUnitsOfMeasure()))],

            'barcode' => 'nullable|string|max:100|unique:medications,barcode', // الباركود يجب أن يكون فريدًا

            // معلومات المخزون والتسعير
            'minimum_stock_level' => 'required|integer|min:0',
            'maximum_stock_level' => 'nullable|integer|min:0|gte:minimum_stock_level', // يجب أن يكون أكبر من أو يساوي الحد الأدنى
            'purchase_price' => 'nullable|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/', // رقم موجب مع خانتين عشريتين كحد أقصى
            'selling_price' => 'nullable|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/',  // رقم موجب مع خانتين عشريتين كحد أقصى

            // معلومات تنظيمية وإضافية
            'requires_prescription' => 'required|boolean',
            // 'is_controlled_substance' تم حذفه من الـ migration الأخير الذي أرسلته
            'contraindications' => 'nullable|string|max:2000', // زيادة الحد الأقصى
            'side_effects' => 'nullable|string|max:2000',    // زيادة الحد الأقصى

            'status' => 'required|boolean',
            // 'storage_conditions' تم حذفه من الـ migration الأخير الذي أرسلته
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم الدواء مطلوب.',
            'name.unique' => 'اسم الدواء هذا مسجل بالفعل.',
            'name.max' => 'اسم الدواء طويل جدًا (الحد الأقصى 255 حرف).',
            'generic_name.max' => 'الاسم العلمي طويل جدًا (الحد الأقصى 255 حرف).',
            'description.max' => 'وصف الدواء طويل جدًا (الحد الأقصى 1000 حرف).',

            'category.in' => 'تصنيف الدواء المختار غير صالح.',
            'manufacturer.max' => 'اسم الشركة المصنعة طويل جدًا (الحد الأقصى 255 حرف).',
            'dosage_form.in' => 'الشكل الصيدلاني المختار غير صالح.',
            'strength.max' => 'تركيز الدواء طويل جدًا (الحد الأقصى 100 حرف).',
            'unit_of_measure.in' => 'وحدة القياس المختارة غير صالحة.',

            'barcode.max' => 'الباركود طويل جدًا (الحد الأقصى 100 حرف).',
            'barcode.unique' => 'هذا الباركود مسجل بالفعل لدواء آخر.',

            'minimum_stock_level.required' => 'حد الطلب الأدنى للمخزون مطلوب.',
            'minimum_stock_level.integer' => 'حد الطلب الأدنى يجب أن يكون رقمًا صحيحًا.',
            'minimum_stock_level.min' => 'حد الطلب الأدنى لا يمكن أن يكون أقل من صفر.',
            'maximum_stock_level.integer' => 'الحد الأقصى للمخزون يجب أن يكون رقمًا صحيحًا.',
            'maximum_stock_level.min' => 'الحد الأقصى للمخزون لا يمكن أن يكون أقل من صفر.',
            'maximum_stock_level.gte' => 'الحد الأقصى للمخزون يجب أن يكون أكبر من أو يساوي الحد الأدنى.',
            'purchase_price.numeric' => 'سعر الشراء يجب أن يكون رقمًا.',
            'purchase_price.min' => 'سعر الشراء لا يمكن أن يكون أقل من صفر.',
            'purchase_price.regex' => 'صيغة سعر الشراء غير صحيحة (مثال: 150.50).',
            'selling_price.numeric' => 'سعر البيع يجب أن يكون رقمًا.',
            'selling_price.min' => 'سعر البيع لا يمكن أن يكون أقل من صفر.',
            'selling_price.regex' => 'صيغة سعر البيع غير صحيحة (مثال: 150.50).',

            'requires_prescription.required' => 'تحديد ما إذا كان الدواء يتطلب وصفة طبية أمر مطلوب.',
            'requires_prescription.boolean' => 'قيمة حقل "يتطلب وصفة" غير صالحة.',
            'contraindications.max' => 'نص موانع الاستعمال طويل جدًا (الحد الأقصى 2000 حرف).',
            'side_effects.max' => 'نص الآثار الجانبية طويل جدًا (الحد الأقصى 2000 حرف).',

            'status.required' => 'حالة الدواء مطلوبة.',
            'status.boolean' => 'قيمة حالة الدواء غير صالحة.',
        ];
    }
}
