<?php

namespace App\Http\Requests\Dashboard\Room;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $roomId = $this->route('room')->id; // أو $this->room إذا كان Route Model Binding

        return [
            'section_id' => 'required|exists:sections,id',
            'room_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('rooms')->where(function ($query) {
                    return $query->where('section_id', $this->section_id);
                })->ignore($roomId), // تجاهل الغرفة الحالية
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
