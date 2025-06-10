<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth; // لاستخدام Auth للوصول للمستخدم الحالي
use Illuminate\Foundation\Http\FormRequest;
use App\Models\GlobalEmail; // استيراد موديل GlobalEmail
use App\Models\Admin; // استيراد موديل Admin (أو الموديل الذي يتم التحقق منه)

class UpdateAdminProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('admin')->check();
    }

    public function rules(): array
    {
        $currentAdmin = Auth::guard('admin')->user(); // المستخدم الحالي الذي يتم تعديله
        $adminId = $currentAdmin->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('admins', 'email')->ignore($adminId), // 1. فريد في جدول admins
                function ($attribute, $value, $fail) use ($currentAdmin) { // $currentAdmin هو المستخدم الذي يتم تعديله
                    // تحقق فقط إذا تغير الإيميل عن الإيميل الأصلي
                    if (strtolower($value) !== strtolower($currentAdmin->getOriginal('email'))) {
                        // إذا تغير الإيميل، تحقق مما إذا كان الإيميل الجديد مستخدمًا في global_emails
                        // من قبل أي شخص آخر (لا داعي لتجاهل السجل الحالي هنا لأننا بالفعل داخل if)
                        $existsInGlobal = GlobalEmail::where('email', $value)->exists();
                        if ($existsInGlobal) {
                            $fail('هذا البريد الإلكتروني مستخدم بالفعل من قبل حساب آخر في النظام.');
                        }
                    }
                },
            ],
            'phone' => [
                'nullable',
                'string',
                Rule::unique('admins', 'phone')->ignore($adminId),
                // يمكنك إضافة قاعدة تحقق من التفرد للهاتف في global_emails بنفس الطريقة إذا أردت
            ],
            'current_password' => ['nullable', 'string', 'required_with:password'],
            'password' => ['nullable', 'string', 'confirmed', \Illuminate\Validation\Rules\Password::min(8)],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'حقل الاسم مطلوب.',
            'email.required' => 'حقل البريد الإلكتروني مطلوب.',
            'email.email' => 'صيغة البريد الإلكتروني غير صحيحة.',
            'email.unique' => 'هذا البريد الإلكتروني مستخدم بالفعل من قبل مستخدم آخر.',
            'phone.regex' => 'صيغة رقم الهاتف غير صحيحة.',
            'phone.unique' => 'رقم الهاتف هذا مستخدم بالفعل من قبل مستخدم آخر.',
            'current_password.required_with' => 'يجب إدخال كلمة المرور الحالية لتغيير كلمة المرور.',
            'password.confirmed' => 'تأكيد كلمة المرور الجديدة غير متطابق.',
            'password.min' => 'كلمة المرور الجديدة يجب أن لا تقل عن 8 أحرف.',
            // يمكنك إضافة رسائل Password rules الأخرى هنا
            'photo.image' => 'الملف المرفوع يجب أن يكون صورة.',
            'photo.mimes' => 'صيغ الصور المسموح بها: jpeg, png, jpg, gif, svg.',
            'photo.max' => 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت.',
        ];
    }
}
