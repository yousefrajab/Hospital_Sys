<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDoctorsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "national_id" => 'required|string|digits:9|unique:doctors,national_id,' . $this->id,
            'email' => [
                'required',
                'email',
                Rule::unique('doctors', 'email')->ignore($this->id),
            ],
            "password" => [
                'nullable',
                'string',
                'min:8',
                'regex:/^(?=.*[A-Za-z])(?=.*\d).{8,}$/'
            ],
            "phone" => [
                'required',
                'string',
                'regex:/^05\d{8}$/',
                Rule::unique('doctors', 'phone')->ignore($this->id)
            ],
            "name" => [
                'required',
                'string',
                'min:3',
                'regex:/^[\p{Arabic}\p{L}\s\-\.]+$/u'
            ],
            "section_id" => 'required|exists:sections,id',
            "number_of_statements" => 'required|integer|min:1|max:20',
            // "photo" => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // "appointments" => 'required|array',
            // "appointments.*" => 'exists:appointments,id'
        ];
    }

    public function messages()
    {
        return [
            // ... الرسائل الحالية
            'email.email' => 'يجب إدخال بريد إلكتروني صالح',
            'password.regex' => 'يجب أن تحتوي كلمة المرور على حروف وأرقام (8 أحرف على الأقل)',
            'phone.regex' => 'يجب أن يبدأ رقم الجوال بـ 05 ويتكون من 10 أرقام',
            'name.regex' => 'يجب أن يحتوي الاسم على أحرف عربية أو إنجليزية فقط',
            'appointments.required' => 'يجب اختيار مواعيد العمل',
            'photo.image' => 'يجب أن يكون الملف صورة',
            'photo.mimes' => 'يجب أن تكون الصورة من نوع: jpeg, png, jpg, gif',
            'photo.max' => 'يجب أن لا تتجاوز الصورة 2MB'
        ];
    }
}
