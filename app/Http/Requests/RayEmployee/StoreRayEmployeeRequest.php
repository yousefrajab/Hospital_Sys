<?php

namespace App\Http\Requests\RayEmployee;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRayEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // السماح لأي مستخدم مصرح له (أو قم بتعديل هذا حسب الحاجة)
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'national_id' => [
                'required',
                'string',
                'max:20', // أو الطول المناسب لرقم الهوية
                Rule::unique('ray_employees', 'national_id'), // يجب أن يكون فريدًا
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('ray_employees', 'email'), // يجب أن يكون فريدًا
            ],
            'phone' => [
                'required',
                'string',
                'regex:/^05\d{8}$/', // مثال لرقم جوال سعودي
                Rule::unique('ray_employees', 'phone'), // يجب أن يكون فريدًا
            ],
            'password' => 'required|string|min:8', // كلمة المرور مطلوبة وطولها 8 أحرف على الأقل
            'status' => 'required|boolean', // الحالة مطلوبة ويجب أن تكون قيمة منطقية (0 أو 1)
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // الصورة اختيارية ولكن إذا تم رفعها يجب أن تكون صورة. 2MB كحد أقصى
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'الاسم الكامل مطلوب.',
            'national_id.required' => 'رقم الهوية مطلوب.',
            'national_id.unique' => 'رقم الهوية مستخدم بالفعل.',
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.email' => 'صيغة البريد الإلكتروني غير صالحة.',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل.',
            'phone.required' => 'رقم الهاتف مطلوب.',
            'phone.regex' => 'يجب أن يكون رقم الهاتف بصيغة صحيحة (مثال: 05xxxxxxxx).',
            'phone.unique' => 'رقم الهاتف مستخدم بالفعل.',
            'password.required' => 'كلمة المرور مطلوبة.',
            'password.min' => 'كلمة المرور يجب أن تتكون من 8 أحرف على الأقل.',
            'status.required' => 'حالة الموظف مطلوبة.',
            'status.boolean' => 'قيمة الحالة غير صالحة.',
            'photo.image' => 'يجب أن يكون الملف المرفوع صورة.',
            'photo.mimes' => 'صيغ الصور المسموح بها: jpeg, png, jpg, gif, svg.',
            'photo.max' => 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت.',
        ];
    }
}
