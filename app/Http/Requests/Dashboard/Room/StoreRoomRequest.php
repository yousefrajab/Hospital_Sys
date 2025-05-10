<?php
namespace App\Http\Requests\Dashboard\Room;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRoomRequest extends FormRequest
{
    public function authorize(): bool { return true; } // افترض أن الأدمن مصرح له

    public function rules(): array
    {
        return [
            'section_id' => 'required|exists:sections,id',
            'room_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('rooms')->where(function ($query) {
                    return $query->where('section_id', $this->section_id);
                }), // رقم الغرفة يجب أن يكون فريدًا داخل نفس القسم
            ],
            'type' => ['required', Rule::in(['patient_room', 'private_room', 'semi_private_room', 'icu_room', 'examination_room', 'consultation_room', 'treatment_room', 'operating_room', 'radiology_room', 'laboratory_room', 'office', 'other'])],
            'gender_type' => ['required', Rule::in(['male', 'female', 'mixed', 'any'])],
            'floor' => 'nullable|string|max:255',
            'status' => ['required', Rule::in(['available', 'partially_occupied', 'fully_occupied', 'out_of_service'])],
            'notes' => 'nullable|string',
        ];
    }
    public function messages(): array // رسائل مخصصة
    {
        return [
            'section_id.required' => 'حقل القسم مطلوب.',
            'section_id.exists' => 'القسم المختار غير صالح.',
            'room_number.required' => 'حقل رقم/اسم الغرفة مطلوب.',
            'room_number.unique' => 'رقم/اسم الغرفة هذا مستخدم بالفعل في هذا القسم.',
            'type.required' => 'نوع الغرفة مطلوب.',
            'type.in' => 'نوع الغرفة المختار غير صالح.',
            'gender_type.required' => 'نوع تخصيص الجنس للغرفة مطلوب.',
            'gender_type.in' => 'نوع تخصيص الجنس المختار غير صالح.',
            'status.required' => 'حالة الغرفة مطلوبة.',
            'status.in' => 'حالة الغرفة المختارة غير صالحة.',
        ];
    }
}
