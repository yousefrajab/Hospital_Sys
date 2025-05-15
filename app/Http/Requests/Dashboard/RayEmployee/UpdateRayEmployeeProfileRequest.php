<?php

namespace App\Http\Requests\Dashboard\RayEmployee; // تم تعديل المسار قليلاً

use App\Models\GlobalEmail;
use Illuminate\Validation\Rule;
use App\Models\GlobalIdentifier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRayEmployeeProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        // التأكد من أن المستخدم المسجل هو موظف أشعة
        return Auth::guard('ray_employee')->check();
    }

    public function rules(): array
    {
        $currentPatient = Auth::guard('ray_employee')->user(); // ** الحصول على الموديل الحالي للمريض **
        $employeeId = $currentPatient->id;

        return [
            'name' => ['required', 'string', 'max:255'],
             'national_id' => [
                'required',
                'string',
                'digits:9', // Laravel يضيف max:255 تلقائيًا مع 'national_id'
                Rule::unique('ray_employees', 'national_id')->ignore($employeeId), // 1. فريد في جدول ray_employees
                function ($attribute, $value, $fail) use ($currentPatient) { // 2. فريد في global_national_ids إذا تغير
                    if (strtolower($value) !== strtolower($currentPatient->getOriginal('national_id'))) {
                        if (GlobalIdentifier::where('national_id', strtolower($value))->exists()) {
                            $fail('هذا الرقم مستخدم بالفعل من قبل حساب آخر في النظام.');
                        }
                    }
                },
            ],
            'email' => [
                'required',
                'email', // Laravel يضيف max:255 تلقائيًا مع 'email'
                Rule::unique('ray_employees', 'email')->ignore($employeeId), // 1. فريد في جدول ray_employees
                function ($attribute, $value, $fail) use ($currentPatient) { // 2. فريد في global_emails إذا تغير
                    if (strtolower($value) !== strtolower($currentPatient->getOriginal('email'))) {
                        if (GlobalEmail::where('email', strtolower($value))->exists()) {
                            $fail('هذا البريد الإلكتروني مستخدم بالفعل من قبل حساب آخر في النظام.');
                        }
                    }
                },
            ],
            'phone' => [
                // تم جعله required ليتطابق مع الأول، يمكنك تغييره إلى nullable إذا كان الهاتف اختياريًا للموظف
                'required',
                'string',
                'regex:/^05\d{8}$/', // ** متطابق مع الأول **
                Rule::unique('ray_employees', 'phone')->ignore($employeeId),
            ],
            // قواعد كلمة المرور هنا تتضمن كلمة المرور الحالية والتأكيد
            // 'current_password' => ['nullable', 'string', 'required_with:password'],
            'password' => ['nullable', 'string', 'confirmed', Password::min(8)->numbers()], // أبقينا على قواعد القوة هنا
            // 'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048'], // ** متطابق مع الأول (مع إضافة webp) **
            // 'status' غير موجود هنا عادةً (الموظف لا يعدل حالته بنفسه)
        ];
    }

    public function messages(): array
    {
        // الرسائل للحقول المشتركة يجب أن تكون متطابقة أو مشابهة جدًا
        return [
            'name.required' => 'الاسم الكامل مطلوب.',
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.string' => 'البريد الإلكتروني يجب أن يكون نصًا.',
            'email.email' => 'صيغة البريد الإلكتروني غير صالحة.',
            'email.max' => 'البريد الإلكتروني طويل جدًا.',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل.',
            'phone.required' => 'رقم الهاتف مطلوب.', // ** جعله مطلوبًا **
            'phone.string' => 'رقم الهاتف يجب أن يكون نصًا.',
            'phone.regex' => 'يجب أن يكون رقم الهاتف بصيغة صحيحة (مثال: 05xxxxxxxx).',
            'phone.unique' => 'رقم الهاتف مستخدم بالفعل.',
            // رسائل كلمة المرور الخاصة بهذا الفورم
            'current_password.required_with' => 'يجب إدخال كلمة المرور الحالية لتغيير كلمة المرور.',
            'password.confirmed' => 'تأكيد كلمة المرور الجديدة غير متطابق.',
            'password.min' => 'كلمة المرور الجديدة يجب أن لا تقل عن 8 أحرف.',
            // رسائل الصورة
            // 'photo.image' => 'الملف المرفوع يجب أن يكون صورة.',
            // 'photo.mimes' => 'صيغ الصور المسموح بها: jpeg, png, jpg, gif, svg, webp.',
            // 'photo.max' => 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت.',
        ];
    }
}
