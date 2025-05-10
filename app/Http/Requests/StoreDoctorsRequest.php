<?php

namespace App\Http\Requests;

use App\Models\GlobalEmail;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreDoctorsRequest extends FormRequest
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
                Rule::unique('doctors', 'email'), // 1. فريد في جدول doctors
                function ($attribute, $value, $fail) { // 2. فريد في global_emails
                    if (GlobalEmail::where('email', strtolower($value))->exists()) {
                        $fail('هذا البريد الإلكتروني مستخدم بالفعل في النظام.');
                    }
                },
            ],
            "password" => [
                'required',
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
                'min:10',
                'regex:/^([\p{Arabic}\s]{10,}|[A-Za-z\s\-]{10,})$/u'
            ],
            "section_id" => 'required|exists:sections,id',
            "number_of_statements" => 'required|integer|min:1|max:20',
            "photo" => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            "appointments" => 'nullable|array',
            "appointments.*" => 'exists:appointments,id'
        ];
    }

    public function messages()
    {
        return [
            'national_id.required' => trans('validation.required'),
            'national_id.digits' => trans('validation.digits', ['digits' => 9]),
            'national_id.unique' => trans('validation.unique'),

            'email.required' => trans('validation.required'),
            'email.email' => trans('validation.email'),
            'email.unique' => trans('validation.unique'),

            'password.required' => trans('validation.required'),
            'password.min' => trans('validation.min.string', ['min' => 8]),
            'password.regex' => trans('doctors.password_regex'), // يجب أن تحتوي على حروف وأرقام

            'phone.required' => trans('validation.required'),
            'phone.regex' => trans('doctors.phone_regex'), // يجب أن يبدأ بـ 05 ويتكون من 10 أرقام
            'phone.unique' => trans('validation.unique'),

            'name.required' => trans('validation.required'),
            'name.min' => trans('validation.min.string', ['min' => 10]),
            'name.regex' => trans('doctors.name_regex'), // يجب إدخال الاسم الكامل (عربي أو إنجليزي)

            'section_id.required' => trans('validation.required'),
            'section_id.exists' => trans('validation.exists'),

            'number_of_statements.required' => trans('validation.required'),
            'number_of_statements.integer' => trans('validation.integer'),
            'number_of_statements.min' => trans('validation.min.numeric', ['min' => 1]),
            'number_of_statements.max' => trans('validation.max.numeric', ['max' => 20]),

            'photo.image' => trans('validation.image'),
            'photo.mimes' => trans('validation.mimes', ['values' => 'jpeg, png, jpg, gif']),
            'photo.max' => trans('validation.max.file', ['max' => 2048]),

            'appointments.array' => trans('validation.array'),
            'appointments.*.exists' => trans('validation.exists')
        ];
    }
}
