<?php

namespace App\Http\Requests\Dashboard\RayEmployee;

use App\Models\GlobalEmail;        // للإيميل
use App\Models\GlobalIdentifier;  // ** لرقم الهوية **
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;
 // ** استيراد موديل الموظف الحالي **

class UpdateRayEmployeeProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('ray_employee')->check();
    }

    public function rules(): array
    {
        /** @var \App\Models\LaboratorieEmployee $currentEmployee */ // توضيح نوع المتغير
        $currentEmployee = Auth::guard('ray_employee')->user();
        $employeeId = $currentEmployee->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'national_id' => [
                'required', // افترض أنه مطلوب دائمًا
                'string',
                'digits:9', // أو الطول المناسب لرقم الهوية
                Rule::unique('ray_employees', 'national_id')->ignore($employeeId), // 1. فريد في جدول الموظفين
                function ($attribute, $value, $fail) use ($currentEmployee) { // 2. فريد في global_identifiers إذا تغير
                    if ($currentEmployee && $value !== $currentEmployee->getOriginal('national_id')) {
                        // تحقق من جدول global_identifiers باستخدام عمود national_id
                        if (GlobalIdentifier::where('national_id', $value)->exists()) {
                            $fail('رقم الهوية هذا مستخدم بالفعل من قبل حساب آخر في النظام.');
                        }
                    }
                },
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('ray_employees', 'email')->ignore($employeeId), // 1. فريد في جدول الموظفين
                function ($attribute, $value, $fail) use ($currentEmployee) { // 2. فريد في global_emails إذا تغير
                    if ($currentEmployee && strtolower($value) !== strtolower($currentEmployee->getOriginal('email'))) {
                        if (GlobalEmail::where('email', strtolower($value))->exists()) {
                            $fail('هذا البريد الإلكتروني مستخدم بالفعل من قبل حساب آخر في النظام.');
                        }
                    }
                },
            ],
            'phone' => [
                'nullable', // أو 'required' حسب الحاجة
                'string',
                'regex:/^05\d{8}$/',
                Rule::unique('ray_employees', 'phone')->ignore($employeeId), // ** تم تعديل هنا ليكون اسم الجدول صحيحًا **
            ],
            'current_password' => ['nullable', 'string', 'required_with:password'],
            'password' => ['nullable', 'string', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048'], // ** تم إضافة حقل الصورة هنا **
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'حقل الاسم مطلوب.',
            'national_id.required' => 'رقم الهوية مطلوب.',
            'national_id.string' => 'رقم الهوية يجب أن يكون نصًا.',
            'national_id.max' => 'رقم الهوية طويل جدًا.',
            'national_id.unique' => 'رقم الهوية هذا مستخدم بالفعل من قبل موظف آخر.',
            // رسالة GlobalIdentifier لـ national_id تأتي من الـ closure
            'email.required' => 'حقل البريد الإلكتروني مطلوب.',
            'email.email' => 'صيغة البريد الإلكتروني غير صالحة.',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل من قبل موظف آخر.',
            // رسالة GlobalEmail لـ email تأتي من الـ closure
            'phone.string' => 'رقم الهاتف يجب أن يكون نصيًا.',
            'phone.regex' => 'صيغة رقم الهاتف غير صحيحة (مثال: 05xxxxxxxx).',
            'phone.unique' => 'رقم الهاتف مستخدم بالفعل من قبل موظف آخر.',
            'current_password.required_with' => 'يجب إدخال كلمة المرور الحالية لتغيير كلمة المرور.',
            'password.confirmed' => 'تأكيد كلمة المرور الجديدة غير متطابق.',
            'password.min' => 'كلمة المرور الجديدة يجب أن لا تقل عن 8 أحرف.',
            'photo.image' => 'الملف المرفوع يجب أن يكون صورة.',
            'photo.mimes' => 'صيغ الصور المسموح بها هي: jpeg, png, jpg, gif, svg, webp.',
            'photo.max' => 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت.',
        ];
    }
}
