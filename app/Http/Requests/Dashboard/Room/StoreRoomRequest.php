<?php
namespace App\Http\Requests\Dashboard\Room;

use App\Models\Room; // ** استيراد موديل Room للوصول إلى الدوال الـ static **
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRoomRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'section_id' => 'required|exists:sections,id',
            'room_number' => [
                'required',
                'string',
                'max:100', // يمكنك تعديل الطول إذا لزم الأمر
                Rule::unique('rooms')->where(function ($query) {
                    return $query->where('section_id', $this->section_id);
                }),
            ],
            'type' => ['required', Rule::in(array_keys(Room::getRoomTypes()))], // ** استخدام الدالة من الموديل **
            'gender_type' => ['required', Rule::in(array_keys(Room::getGenderTypes()))], // ** استخدام الدالة من الموديل **
            'floor' => 'nullable|string|max:50',
            // عند الإنشاء، نستخدم الحالات الأولية المسموح بها
            'status' => ['required', Rule::in(array_keys(Room::getInitialCreatableStatuses()))], // ** استخدام الدالة من الموديل **
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'section_id.required' => 'يجب اختيار القسم.',
            'section_id.exists' => 'القسم المختار غير صالح.',
            'room_number.required' => 'حقل رقم/اسم الغرفة مطلوب.',
            'room_number.unique' => 'رقم/اسم الغرفة هذا مستخدم بالفعل في هذا القسم.',
            'room_number.max' => 'رقم/اسم الغرفة طويل جدًا (الحد الأقصى 100 حرف).',
            'type.required' => 'نوع الغرفة مطلوب.',
            'type.in' => 'نوع الغرفة المختار غير صالح.', // هذه الرسالة ستكون عامة، يمكن تخصيصها أكثر إذا أردت
            'gender_type.required' => 'نوع تخصيص الجنس للغرفة مطلوب.',
            'gender_type.in' => 'نوع تخصيص الجنس المختار غير صالح.',
            'floor.max' => 'اسم الطابق طويل جدًا (الحد الأقصى 50 حرف).',
            'status.required' => 'حالة الغرفة مطلوبة.',
            'status.in' => 'حالة الغرفة المختارة غير صالحة.',
            'notes.max' => 'الملاحظات طويلة جدًا (الحد الأقصى 1000 حرف).',
        ];
    }
}
