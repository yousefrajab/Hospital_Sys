<?php

namespace App\Http\Requests\Dashboard\PharmacyManager\Medication;

use App\Models\Medication; // ** استيراد موديل Medication **
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth; // ** استيراد Auth **

class UpdateMedicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('admin')->check() || Auth::guard('pharmacy_manager')->check();
    }

    public function rules(): array
    {
        // الحصول على ID الدواء من الـ Route
        // نفترض أن اسم البارامتر في الـ route هو 'medication' (بسبب Route Model Binding)
        $medicationId = $this->route('medication')->id;

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('medications', 'name')->ignore($medicationId)],
            'generic_name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',

            'category' => ['nullable', 'string', Rule::in(array_keys(Medication::getCommonCategories()))],
            'manufacturer' => 'nullable|string|max:255',
            'dosage_form' => ['nullable', 'string', Rule::in(array_keys(Medication::getCommonDosageForms()))],
            'strength' => 'nullable|string|max:100',
            'unit_of_measure' => ['nullable', 'string', Rule::in(array_keys(Medication::getCommonUnitsOfMeasure()))],

            'barcode' => ['nullable', 'string', 'max:100', Rule::unique('medications', 'barcode')->ignore($medicationId)],

            'minimum_stock_level' => 'required|integer|min:0',
            'maximum_stock_level' => 'nullable|integer|min:0|gte:minimum_stock_level',
            'purchase_price' => 'nullable|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/',
            'selling_price' => 'nullable|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/',

            'requires_prescription' => 'required|boolean',
            'contraindications' => 'nullable|string|max:2000',
            'side_effects' => 'nullable|string|max:2000',

            'status' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        // يمكنك استخدام نفس مصفوفة messages() من StoreMedicationRequest
        // أو تخصيصها إذا كانت هناك رسائل مختلفة للتحديث
        return (new StoreMedicationRequest())->messages(); // إعادة استخدام رسائل الإنشاء
        // أو يمكنك نسخ ولصق الرسائل هنا وتعديلها إذا لزم الأمر
    }
}
