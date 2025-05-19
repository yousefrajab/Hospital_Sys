<?php

namespace App\Http\Requests\Dashboard\PharmacyManager\Stock;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Medication; // لاستخدامه إذا أردت الوصول لبيانات الدواء

class StorePharmacyStockRequest extends FormRequest
{
    public function authorize(): bool
    {
        // افترض أن المستخدم المصرح له (مدير صيدلية أو أدمن) يمكنه الإضافة
        return auth()->guard('pharmacy_manager')->check() || auth()->guard('admin')->check();
    }

    public function rules(): array
    {
        // $this->route('medication') هو كائن Medication بسبب Route Model Binding
        // المستخدم في تعريف الـ route: medications/{medication}/stocks/create
        $medicationId = $this->route('medication')->id;

        return [
            // medication_id سيتم أخذه من الـ route parameter في الـ controller
            'batch_number' => [
                'nullable', // قد يكون رقم الدفعة غير معروف أو غير مطبق دائمًا
                'string',
                'max:100',
                Rule::unique('pharmacy_stocks')->where(function ($query) use ($medicationId) {
                    return $query->where('medication_id', $medicationId);
                }) // رقم الدفعة يجب أن يكون فريدًا لنفس الدواء
            ],
            'expiry_date' => 'required|date|after:today', // تاريخ الصلاحية يجب أن يكون في المستقبل
            'initial_quantity' => 'required|integer|min:1', // الكمية الأولية عند الإضافة
            'quantity_on_hand' => 'required|integer|min:0|lte:initial_quantity', // الكمية الحالية لا يمكن أن تتجاوز الأولية عند الإنشاء

            'cost_price_per_unit' => 'nullable|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/', // سعر التكلفة
            'supplier_name' => 'nullable|string|max:255', // اسم المورد
            'received_date' => 'nullable|date|before_or_equal:today', // تاريخ الاستلام
            'stock_notes' => 'nullable|string|max:1000', // ملاحظات على الدفعة
        ];
    }

    public function messages(): array
    {
        return [
            'batch_number.unique' => 'رقم الدفعة هذا مستخدم بالفعل لهذا الدواء.',
            'batch_number.max' => 'رقم الدفعة طويل جدًا.',
            'expiry_date.required' => 'تاريخ انتهاء الصلاحية مطلوب.',
            'expiry_date.after' => 'تاريخ انتهاء الصلاحية يجب أن يكون في المستقبل.',
            'initial_quantity.required' => 'الكمية الأولية مطلوبة.',
            'initial_quantity.integer' => 'الكمية الأولية يجب أن تكون رقمًا صحيحًا.',
            'initial_quantity.min' => 'الكمية الأولية يجب أن تكون على الأقل 1.',
            'quantity_on_hand.required' => 'الكمية الحالية مطلوبة.',
            'quantity_on_hand.integer' => 'الكمية الحالية يجب أن تكون رقمًا صحيحًا.',
            'quantity_on_hand.min' => 'الكمية الحالية لا يمكن أن تكون أقل من صفر.',
            'quantity_on_hand.lte' => 'الكمية الحالية لا يمكن أن تكون أكبر من الكمية الأولية عند إنشاء الدفعة.',

            'cost_price_per_unit.numeric' => 'سعر التكلفة يجب أن يكون رقمًا.',
            'cost_price_per_unit.min' => 'سعر التكلفة لا يمكن أن يكون أقل من صفر.',
            'cost_price_per_unit.regex' => 'صيغة سعر التكلفة غير صحيحة (مثال: 10.50).',
            'supplier_name.max' => 'اسم المورد طويل جدًا.',
            'received_date.date' => 'صيغة تاريخ الاستلام غير صحيحة.',
            'received_date.before_or_equal' => 'تاريخ الاستلام لا يمكن أن يكون في المستقبل.',
            'stock_notes.max' => 'ملاحظات الدفعة طويلة جدًا.',
        ];
    }
}
