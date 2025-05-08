<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password; // **استيراد Password rule**

class UpdateAdminProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('admin')->check();
    }

    public function rules(): array
    {
        $adminId = Auth::guard('admin')->id();

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('admins')->ignore($adminId),
            ],
            'phone' => [
                'nullable',
                'string',
                // 'regex:/^05\d{8}$/', // يمكنك إبقاء هذا أو جعله أكثر عمومية
                Rule::unique('admins', 'phone')->ignore($adminId), // تأكد من أن اسم العمود 'phone'
            ],
            // كلمة المرور الحالية مطلوبة فقط إذا تم إدخال كلمة مرور جديدة
            'current_password' => ['nullable', 'string', 'required_with:password'],
            // كلمة المرور الجديدة اختيارية، ولكن إذا تم إدخالها، يجب تأكيدها وتلبية معايير القوة
            // وتأكيد كلمة المرور يبحث عن حقل "password_confirmation"
            'password' => ['nullable', 'string', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'], // 2MB
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
