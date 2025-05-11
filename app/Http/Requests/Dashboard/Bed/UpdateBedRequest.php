<?php

namespace App\Http\Requests\Dashboard\Bed; // تأكد من المسار الصحيح

use App\Models\Bed;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBedRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // افترض أن الأدمن مصرح له
    }

    public function rules(): array
    {
        $bedId = $this->route('bed')->id; // أو $this->bed->id إذا كان Route Model Binding يعمل
                                          // أو $this->route('bed') إذا كان البارامتر هو كائن Bed

        return [
            'room_id' => 'required|exists:rooms,id',
            'bed_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('beds')->where(function ($query) {
                    return $query->where('room_id', $this->room_id);
                })->ignore($bedId), // تجاهل السرير الحالي عند التحقق من التفرد داخل نفس الغرفة
            ],
            'type' => ['required', Rule::in(array_keys(Bed::getBedTypes()))],
            'status' => ['required', Rule::in(array_keys(Bed::getAllBedStatuses()))], // كل الحالات الممكنة للتعديل
            // 'is_window_side' => 'nullable|boolean', // إذا أضفتها
            // 'features' => 'nullable|array',       // إذا أضفتها
        ];
    }

    public function messages(): array
    {
        return [
            'room_id.required' => 'يجب اختيار الغرفة التي يتبع لها السرير.',
            'room_id.exists' => 'الغرفة المختارة غير موجودة.',
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
