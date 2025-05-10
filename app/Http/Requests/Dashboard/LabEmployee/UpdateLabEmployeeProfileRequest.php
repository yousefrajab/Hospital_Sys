<?php

namespace App\Http\Requests\Dashboard\LabEmployee;

use App\Models\GlobalEmail;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLabEmployeeProfileRequest extends FormRequest
{

    public function authorize(): bool
    {
        // التأكد من أن المستخدم المسجل هو موظف أشعة
        return Auth::guard('laboratorie_employee')->check();
    }
    public function rules(): array
    {
        // $employeeId = Auth::guard('laboratorie_employee')->id();

        $currentPatient = Auth::guard('laboratorie_employee')->user(); // ** الحصول على الموديل الحالي للمريض **
        $employeeId = $currentPatient->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email', // Laravel يضيف max:255 تلقائيًا مع 'email'
                Rule::unique('laboratorie_employees', 'email')->ignore($employeeId), // 1. فريد في جدول laboratorie_employees
                function ($attribute, $value, $fail) use ($currentPatient) { // 2. فريد في global_emails إذا تغير
                    if (strtolower($value) !== strtolower($currentPatient->getOriginal('email'))) {
                        if (GlobalEmail::where('email', strtolower($value))->exists()) {
                            $fail('هذا البريد الإلكتروني مستخدم بالفعل من قبل حساب آخر في النظام.');
                        }
                    }
                },
            ],
            'phone' => [
                'nullable', // اجعله اختياريًا أو required حسب الحاجة
                'string',
                'regex:/^05\d{8}$/', // مثال للجوال السعودي
                Rule::unique('laboratorie_employees')->ignore($employeeId),
            ],
            // كلمة المرور الحالية مطلوبة فقط إذا تم إدخال كلمة مرور جديدة
            'current_password' => ['nullable', 'string', 'required_with:password'],
            // كلمة المرور الجديدة اختيارية، وتتطلب تأكيدًا وقواعد قوة
            'password' => ['nullable', 'string', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            // 'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048'], // 2MB
        ];
    }

    /**
     * رسائل التحقق المخصصة (اختياري).
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'حقل الاسم مطلوب.',
            'email.required' => 'حقل البريد الإلكتروني مطلوب.',
            'email.email' => 'صيغة البريد الإلكتروني غير صحيحة.',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل.',
            'phone.regex' => 'صيغة رقم الهاتف غير صحيحة (مثال: 05xxxxxxxx).',
            'phone.unique' => 'رقم الهاتف مستخدم بالفعل.',
            'current_password.required_with' => 'يجب إدخال كلمة المرور الحالية لتغيير كلمة المرور.',
            'password.confirmed' => 'تأكيد كلمة المرور الجديدة غير متطابق.',
            'password.min' => 'كلمة المرور الجديدة يجب أن لا تقل عن 8 أحرف.',
            // 'photo.*' => 'خطأ في تحميل الصورة (تأكد من النوع والحجم).',
        ];
    }
}
