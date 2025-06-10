<?php

namespace App\Http\Requests\Dashboard\Bed; // تأكد من المسار الصحيح

use App\Models\Bed; // لاستخدام الثوابت وقيم enum
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBedRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // افترض أن الأدمن مصرح له
    }

    public function rules(): array
    {
        return [
            'room_id' => 'required|exists:rooms,id',
            'bed_number' => [
                'required',
                'string',
                'max:50', // طول مناسب لرقم السرير
                Rule::unique('beds')->where(function ($query) {
                    // رقم السرير يجب أن يكون فريدًا داخل نفس الغرفة
                    return $query->where('room_id', $this->room_id);
                }),
            ],
            'type' => ['required', Rule::in(array_keys(Bed::getBedTypes()))],
            'status' => ['required', Rule::in(array_keys(Bed::getAllBedStatuses()))], // عند الإنشاء، قد تكون الحالة محدودة
            // الحقول التي تم تعليقها في الـ migration لا تحتاج لقواعد تحقق هنا إلا إذا أضفتها
            // 'is_window_side' => 'nullable|boolean',
            // 'features' => 'nullable|array',
        ];
    }

    public function messages(): array
    {
        return [
            'room_id.required' => 'يجب اختيار الغرفة.',
            'room_id.exists' => 'الغرفة المختارة غير صالحة.',
            'bed_number.required' => 'حقل رقم/كود السرير مطلوب.',
            'bed_number.unique' => 'رقم/كود السرير هذا مستخدم بالفعل في هذه الغرفة.',
            'bed_number.max' => 'رقم/كود السرير طويل جدًا (الحد الأقصى 50 حرف).',
            'type.required' => 'نوع السرير مطلوب.',
            'type.in' => 'نوع السرير المختار غير صالح.',
            'status.required' => 'حالة السرير مطلوبة.',
            'status.in' => 'حالة السرير المختارة غير صالحة.',
        ];
    }
}
