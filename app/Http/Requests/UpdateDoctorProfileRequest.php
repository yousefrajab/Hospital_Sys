<?php

namespace App\Http\Requests; // أو المسار الصحيح App\Http\Requests\Doctor

use App\Models\GlobalEmail;
use App\Traits\UploadTrait; // هذا لا يُستخدم عادةً في FormRequest مباشرة
use Illuminate\Validation\Rule;
use App\Models\GlobalIdentifier; // ** موديل المعرفات العالمية **
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\Doctor; // ** استيراد موديل Doctor **
use Illuminate\Validation\Rules\Password; // ** استيراد Password rule إذا لم تكن مستوردة **

class UpdateDoctorProfileRequest extends FormRequest
{
    public function authorize()
    {
        return Auth::guard('doctor')->check();
    }

    public function rules()
    {
        /** @var \App\Models\Doctor $currentDoctor */
        $currentDoctor = Auth::guard('doctor')->user();
        $doctorId = $currentDoctor->id;

        return [
            'name' => ['required', 'string', 'min:3', 'regex:/^[\p{Arabic}\p{L}\s\-\.]+$/u'], // قاعدة الاسم من كودك
            'national_id' => [
                'required', // افترض أنه مطلوب
                'string',
                'digits:9', // أو الطول المناسب
                Rule::unique('doctors', 'national_id')->ignore($doctorId), // 1. فريد في جدول doctors
                function ($attribute, $value, $fail) use ($currentDoctor) { // 2. فريد في global_identifiers إذا تغير
                    if ($currentDoctor && $value !== $currentDoctor->getOriginal('national_id')) {
                        // بما أن جدول global_identifiers يحتوي على عمود national_id مباشرة
                        if (GlobalIdentifier::where('national_id', $value)->exists()) {
                            $fail('رقم الهوية هذا مستخدم بالفعل من قبل حساب آخر في النظام.');
                        }
                    }
                },
            ],
            'email' => [
                'required',
                'email', // Laravel يضيف max:255 تلقائيًا مع 'email'
                Rule::unique('doctors', 'email')->ignore($doctorId),
                function ($attribute, $value, $fail) use ($currentDoctor) {
                    if ($currentDoctor && strtolower($value) !== strtolower($currentDoctor->getOriginal('email'))) {
                        if (GlobalEmail::where('email', strtolower($value))->exists()) {
                            $fail('هذا البريد الإلكتروني مستخدم بالفعل من قبل حساب آخر في النظام.');
                        }
                    }
                },
            ],
            'current_password' => ['nullable', 'required_with:new_password', function ($attribute, $value, $fail) {
                if ($value && Auth::guard('doctor')->user() && !Hash::check($value, Auth::guard('doctor')->user()->password)) {
                    $fail('كلمة المرور الحالية غير صحيحة.');
                }
            }],
            "new_password" => ['nullable','string','min:8','regex:/^(?=.*[A-Za-z])(?=.*\d).{8,}$/','confirmed'], // اسم الحقل new_password
            "phone" => ['required','string','regex:/^05\d{8}$/', Rule::unique('doctors', 'phone')->ignore($doctorId)],
            "photo" => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg,webp', 'max:2048'],
            // أضف section_id إذا كان الطبيب يمكنه تعديله هنا
            // 'section_id' => 'sometimes|required|exists:sections,id',
        ];
    }

    public function messages()
    {
        // ... (الرسائل كما هي من كودك، مع إضافة رسالة لـ national_id إذا لزم الأمر)
        return [
            'name.required' => 'حقل اسم الطبيب مطلوب.',
            'name.regex' => 'يجب أن يحتوي الاسم على أحرف عربية أو إنجليزية فقط.',
            'national_id.required' => 'حقل الرقم الوطني مطلوب.',
            'national_id.digits' => 'رقم الهوية يجب أن يتكون من 9 أرقام.',
            'national_id.unique' => 'الرقم الوطني هذا مستخدم بالفعل من قبل طبيب آخر.',
            // رسالة الخطأ من الـ closure لـ GlobalIdentifier ستكون "رقم الهوية هذا مستخدم بالفعل..."
            'email.required' => 'حقل البريد الإلكتروني مطلوب.',
            'email.email' => 'يجب إدخال بريد إلكتروني صالح.',
            'email.unique' => 'هذا البريد الإلكتروني مستخدم بالفعل من قبل طبيب آخر.',
            'phone.required' => 'حقل رقم الهاتف مطلوب.',
            'phone.regex' => 'يجب أن يبدأ رقم الجوال بـ 05 ويتكون من 10 أرقام.',
            'phone.unique' => 'رقم الهاتف هذا مستخدم بالفعل من قبل طبيب آخر.',
            'current_password.required_with' => 'كلمة المرور الحالية مطلوبة لتغيير كلمة المرور.',
            'new_password.min' => 'كلمة المرور الجديدة يجب أن تتكون من 8 أحرف على الأقل.',
            'new_password.regex' => 'كلمة المرور الجديدة يجب أن تحتوي على حروف وأرقام.',
            'new_password.confirmed' => 'تأكيد كلمة المرور الجديدة غير مطابق.',
            'photo.image' => 'الملف المرفوع يجب أن يكون صورة.',
            'photo.mimes' => 'صيغ الصور المسموح بها هي: jpeg, png, jpg, gif, svg, webp.',
            'photo.max' => 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت.',
        ];
    }
}
