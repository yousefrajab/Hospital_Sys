<?php

namespace App\Http\Requests;

use App\Traits\UploadTrait;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash; // استيراد Hash

class UpdateDoctorProfileRequest extends FormRequest
{
   
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // السماح فقط للطبيب المسجل دخوله بتحديث ملفه
        return Auth::guard('doctor')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $doctorId = Auth::guard('doctor')->id(); // الحصول على ID الطبيب الحالي

        return [
            // --- نفس قواعد التحقق التي أرسلتها تقريباً ---
            "national_id" => ['required', 'string', 'digits:9', Rule::unique('doctors', 'national_id')->ignore($doctorId)],
            'email' => ['required', 'email', Rule::unique('doctors', 'email')->ignore($doctorId)],
             // --- التحقق من كلمة المرور الحالية هنا ---
             'current_password' => ['nullable', 'required_with:new_password', function ($attribute, $value, $fail) {
                 if (!Hash::check($value, Auth::guard('doctor')->user()->password)) {
                     $fail('كلمة المرور الحالية غير صحيحة.');
                 }
             }],
             // --- تأكد من أن اسم حقل كلمة المرور الجديدة هو new_password ---
            "new_password" => ['nullable','string','min:8','regex:/^(?=.*[A-Za-z])(?=.*\d).{8,}$/','confirmed'],
            // --- حقل التأكيد الافتراضي هو new_password_confirmation ---
            // "new_password_confirmation" => ['required_with:new_password'], // لا حاجة لهذا إذا استخدمت confirmed

             "phone" => ['required','string','regex:/^05\d{8}$/', Rule::unique('doctors', 'phone')->ignore($doctorId)],
             "name" => ['required','string','min:3','regex:/^[\p{Arabic}\p{L}\s\-\.]+$/u'],
             // --- اسم حقل الصورة هنا photo وليس image ---
             "photo" => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'], // تأكد من اسم الحقل في الفورم
        ];
    }

     /**
      * Get custom messages for validator errors.
      *
      * @return array
      */
     public function messages()
     {
         return [
             'name.required' => 'حقل اسم الطبيب مطلوب.',
             'name.regex' => 'يجب أن يحتوي الاسم على أحرف عربية أو إنجليزية فقط.',
             'email.required' => 'حقل البريد الإلكتروني مطلوب.',
             'email.email' => 'يجب إدخال بريد إلكتروني صالح.',
             'email.unique' => 'هذا البريد الإلكتروني مستخدم بالفعل.',
             'phone.required' => 'حقل رقم الهاتف مطلوب.',
             'phone.regex' => 'يجب أن يبدأ رقم الجوال بـ 05 ويتكون من 10 أرقام.',
             'phone.unique' => 'رقم الهاتف هذا مستخدم بالفعل.',
             'national_id.required' => 'حقل الرقم الوطني مطلوب.',
             'national_id.digits' => 'رقم الهوية يجب أن يتكون من 9 أرقام.',
             'national_id.unique' => 'الرقم الوطني هذا مستخدم بالفعل.',
             'current_password.required_with' => 'كلمة المرور الحالية مطلوبة لتغيير كلمة المرور.',
             // 'new_password.required' => 'حقل كلمة المرور الجديدة مطلوب.', // ليس مطلوباً دائماً
             'new_password.min' => 'كلمة المرور الجديدة يجب أن تتكون من 8 أحرف على الأقل.',
             'new_password.regex' => 'كلمة المرور الجديدة يجب أن تحتوي على حروف وأرقام.',
             'new_password.confirmed' => 'تأكيد كلمة المرور الجديدة غير مطابق.',
             'photo.image' => 'الملف المرفوع يجب أن يكون صورة.',
             'photo.mimes' => 'صيغ الصور المسموح بها هي: jpeg, png, jpg, gif, svg.',
             'photo.max' => 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت.',
         ];
     }
}
