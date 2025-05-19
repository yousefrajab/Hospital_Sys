<?php

namespace App\Http\Requests\Dashboard\PharmacyManager\Stock;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\PharmacyStock;
use App\Models\Medication; // لاستخدامه إذا أردت
use Illuminate\Support\Facades\Auth; // لاستخدامه في authorize

class UpdatePharmacyStockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('pharmacy_manager')->check() || Auth::guard('admin')->check();
    }

    public function rules(): array
    {
        // $this->route('stock') هو كائن PharmacyStock بسبب Route Model Binding
        // المستخدم في تعريف الـ route: stocks/{stock}/edit
        $stockItem = $this->route('stock');
        $medicationId = $stockItem->medication_id; // ID الدواء لهذه الدفعة

        return [
            // medication_id لا يتم تعديله عادةً لدفعة قائمة
            'batch_number' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('pharmacy_stocks')->where(function ($query) use ($medicationId) {
                    return $query->where('medication_id', $medicationId);
                })->ignore($stockItem->id) // تجاهل هذه الدفعة عند التحقق
            ],
            'expiry_date' => 'required|date|after_or_equal:today', // يمكن أن يكون يساوي اليوم إذا كان آخر يوم صلاحية
            'quantity_on_hand' => 'required|integer|min:0|lte:' . ($stockItem->initial_quantity ?? 'initial_quantity'), // لا يمكن أن يتجاوز الكمية الأولية
            'initial_quantity' => 'required|integer|min:0|gte:quantity_on_hand', // الكمية الأولية لا يمكن أن تكون أقل من الحالية

            'cost_price_per_unit' => 'nullable|numeric|min:0|regex:/^\d+(\.\d{1,2})?$/',
            'supplier_name' => 'nullable|string|max:255',
            'received_date' => 'nullable|date|before_or_equal:today',
            'stock_notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        // يمكنك استخدام نفس رسائل StorePharmacyStockRequest مع تعديلات طفيفة إذا لزم الأمر
        return [
            'batch_number.unique' => 'رقم الدفعة هذا مستخدم بالفعل لهذا الدواء.',
            'expiry_date.required' => 'تاريخ انتهاء الصلاحية مطلوب.',
            'expiry_date.after_or_equal' => 'تاريخ انتهاء الصلاحية يجب أن يكون اليوم أو في المستقبل.',
            'quantity_on_hand.required' => 'الكمية الحالية مطلوبة.',
            'quantity_on_hand.integer' => 'الكمية الحالية يجب أن تكون رقمًا صحيحًا.',
            'quantity_on_hand.min' => 'الكمية الحالية لا يمكن أن تكون أقل من صفر.',
            'quantity_on_hand.lte' => 'الكمية الحالية لا يمكن أن تكون أكبر من الكمية الأولية.',
            'initial_quantity.required' => 'الكمية الأولية مطلوبة.',
            'initial_quantity.integer' => 'الكمية الأولية يجب أن تكون رقمًا صحيحًا.',
            'initial_quantity.min' => 'الكمية الأولية لا يمكن أن تكون أقل من صفر.',
            'initial_quantity.gte' => 'الكمية الأولية يجب أن تكون أكبر من أو تساوي الكمية الحالية.',
            // ... (أضف رسائل للحقول الجديدة كما في StoreRequest)
            'cost_price_per_unit.numeric' => 'سعر التكلفة يجب أن يكون رقمًا.',
            'supplier_name.max' => 'اسم المورد طويل جدًا.',
            'received_date.date' => 'صيغة تاريخ الاستلام غير صحيحة.',
        ];
    }
}
