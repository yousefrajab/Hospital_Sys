<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\GlobalEmail;

class GlobalEmailRequest extends FormRequest
{
    public function authorize()
    {
        return true; // يجب أن يكون `true` ليسمح بالتحقق
    }

    public function rules()
    {
        return [
            'email' => [
                'required',
                'email',
                function ($attribute, $value, $fail) {
                    // التحقق من وجود الإيميل في أي جدول
                    if (GlobalEmail::where('email', $value)->exists()) {
                        $fail('البريد الإلكتروني مستخدم بالفعل في نظام آخر!');
                    }
                },
            ],
            // باقي الحقول هنا (مثل: name, password...)
            'name' => 'required|string',
            'password' => 'required|min:8',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'حقل الإيميل مطلوب',
            'email.email' => 'يجب أن يكون الإيميل صالحًا',
            'name.required' => 'حقل الاسم مطلوب',
            'password.required' => 'كلمة المرور مطلوبة',
        ];
    }
}
