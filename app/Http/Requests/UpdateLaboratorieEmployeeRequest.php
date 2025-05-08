<?php

namespace App\Http\Requests; // تأكد من المسار الصحيح

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; // استيراد Rule

class UpdateLaboratorieEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // افترض أن الأدمن مصرح له دائمًا
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
{
    $employeeId = $this->route('laboratorie_employee'); // تأكد من اسم البارامتر في الراوت

    return [
        'name' => 'required|string|max:255',
        'national_id' => [
            'required',
            'string',
            'max:20',
            Rule::unique('laboratorie_employees', 'national_id')->ignore($employeeId),
        ],
        'email' => [
            'required',
            'email',
            'max:255',
            Rule::unique('laboratorie_employees', 'email')->ignore($employeeId),
        ],
        'phone' => [
            'required',
            'string',
            'regex:/^05\d{8}$/',
            Rule::unique('laboratorie_employees', 'phone')->ignore($employeeId),
        ],
        'password' => 'nullable|string|min:8',
        'status' => 'required|boolean',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
    ];
}
     /**
      * رسائل تحقق مخصصة (اختياري).
      */
     public function messages(): array
     {
         return [
             'name.required' => 'حقل الاسم مطلوب.',
             'national_id.required' => 'حقل الرقم الوطني/الإقامة مطلوب.',
             'national_id.unique' => 'الرقم الوطني/الإقامة مستخدم بالفعل.',
             'email.required' => 'حقل البريد الإلكتروني مطلوب.',
             'email.email' => 'صيغة البريد الإلكتروني غير صحيحة.',
             'email.unique' => 'البريد الإلكتروني مستخدم بالفعل.',
             'phone.required' => 'حقل الهاتف مطلوب.',
             'phone.unique' => 'رقم الهاتف مستخدم بالفعل.',
             'phone.regex' => 'صيغة رقم الهاتف غير صحيحة (مثال: 05xxxxxxxx).',
             'password.min' => 'كلمة المرور يجب أن تتكون من 8 أحرف على الأقل.',
             'status.required' => 'حقل الحالة مطلوب.',
             'status.boolean' => 'قيمة الحالة يجب أن تكون صحيحة أو خاطئة.',
             'photo.image' => 'الملف المرفوع يجب أن يكون صورة.',
             'photo.mimes' => 'صيغ الصور المسموح بها: jpeg, png, jpg, gif, svg.',
             'photo.max' => 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت.',
         ];
     }
}
