<?php

namespace App\Http\Requests\Dashboard\Admin\Disease;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDiseaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // افترض أن الأدمن مصرح له
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:diseases,name',
            'description' => 'nullable|string',
            'is_chronic' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم المرض مطلوب.',
            'name.unique' => 'اسم المرض هذا مسجل بالفعل.',
            'name.max' => 'اسم المرض طويل جدًا.',
            'is_chronic.required' => 'يجب تحديد ما إذا كان المرض مزمنًا أم لا.',
            'is_chronic.boolean' => 'قيمة حقل "مزمن" غير صالحة.',
        ];
    }
}
