<?php

namespace App\Http\Requests\Dashboard\Admin\Disease;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDiseaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // افترض أن الأدمن مصرح له
    }

    public function rules(): array
    {
        $diseaseId = $this->route('disease')->id; // أو $this->disease->id

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('diseases', 'name')->ignore($diseaseId)],
            'description' => 'nullable|string',
            'is_chronic' => 'required|boolean',
        ];
    }
    // يمكنك إضافة دالة messages() مشابهة لـ StoreDiseaseRequest
    public function messages(): array
    {
        return [
            'name.required' => 'اسم المرض مطلوب.',
            'name.unique' => 'اسم المرض هذا مسجل بالفعل.',
            'name.max' => 'اسم المرض طويل جدًا (الحد الأقصى 255 حرف).',
            'description.max' => 'وصف المرض طويل جدًا (الحد الأقصى 1000 حرف).',
            'is_chronic.required' => 'يجب تحديد ما إذا كان المرض مزمنًا أم لا.',
            'is_chronic.boolean' => 'قيمة حقل "مزمن" غير صالحة.',
        ];
    }
}
