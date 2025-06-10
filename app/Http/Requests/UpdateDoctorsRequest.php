<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\GlobalEmail;        // للإيميل
use App\Models\GlobalIdentifier;  // ** لرقم الهوية **
use App\Models\Doctor;            // ** لجلب الموديل الحالي **

class UpdateDoctorsRequest extends FormRequest
{
    public function authorize()
    {
        return true; // افترض أن الأدمن مصرح له
    }

    public function rules()
    {
        // ** طريقة أفضل وأكثر موثوقية لجلب ID الطبيب الذي يتم تعديله **
        // إذا كان الـ route يستخدم Route Model Binding لـ Doctor:
        // $currentDoctor = $this->route('doctor'); // افترض أن اسم البارامتر هو 'doctor'
        // $doctorId = $currentDoctor ? $currentDoctor->id : null;

        // إذا كان الـ route يمرر ID فقط (مثلاً /doctors/{id}/edit):
        // $doctorId = $this->route('id'); // افترض أن اسم البارامتر هو 'id'

        // الطريقة التي كنت تستخدمها في DoctorRepository@update كانت $request->id
        // مما يعني أن حقل 'id' يتم إرساله مع الفورم.
        // سأفترض أنك سترسل 'id' الطبيب كحقل مخفي في الفورم.
        $doctorId = $this->input('id'); // الحصول على الـ ID من بيانات الطلب
        $currentDoctor = $doctorId ? Doctor::find($doctorId) : null;

        if (!$currentDoctor) {
            // إذا لم يتم العثور على الطبيب، لا يمكن تطبيق قواعد ignore بشكل صحيح
            // يمكنك رمي استثناء هنا أو جعل قواعد unique تفشل إذا لم يتم العثور على الطبيب
            // ولكن FormRequest يجب أن يفشل إذا كان $doctorId هو null وقاعدة unique تحتاجه
        }

        return [
            "national_id" => [
                'required',
                'string',
                'digits:9',
                Rule::unique('doctors', 'national_id')->ignore($doctorId),
                function ($attribute, $value, $fail) use ($currentDoctor) {
                    if ($currentDoctor && $value !== $currentDoctor->getOriginal('national_id')) {
                        if (GlobalIdentifier::where('national_id', $value)->exists()) {
                            $fail('رقم الهوية هذا مستخدم بالفعل في النظام من قبل حساب آخر.');
                        }
                    }
                },
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('doctors', 'email')->ignore($doctorId),
                function ($attribute, $value, $fail) use ($currentDoctor) {
                    if ($currentDoctor && strtolower($value) !== strtolower($currentDoctor->getOriginal('email'))) {
                        if (GlobalEmail::where('email', strtolower($value))->exists()) {
                            $fail('هذا البريد الإلكتروني مستخدم بالفعل في النظام من قبل حساب آخر.');
                        }
                    }
                },
            ],
            "password" => [
                'nullable',
                'string',
                'min:8',
                'regex:/^(?=.*[A-Za-z])(?=.*\d).{8,}$/',
                'confirmed' // يتطلب حقل password_confirmation
            ],
            // "password_confirmation" => 'nullable|same:password',
            "phone" => [
                'required',
                'string',
                'regex:/^05\d{8}$/',
                Rule::unique('doctors', 'phone')->ignore($doctorId) // ** استخدام $doctorId **
            ],
            "name" => [
                'required',
                'string',
                'min:3', // أو الطول المناسب للاسم المترجم
                'regex:/^[\p{Arabic}\p{L}\s\-\.]+$/u'
            ],
            "section_id" => 'required|exists:sections,id',
            "number_of_statements" => 'required|integer|min:1|max:20',
           
        ];
    }

    public function messages()
    {
        // ... (الرسائل كما هي مع إضافة رسالة لـ status و password.confirmed)
        return [
            // ...
            'national_id.required' => 'حقل الرقم الوطني مطلوب.',
            'national_id.digits' => 'رقم الهوية يجب أن يتكون من 9 أرقام.',
            'national_id.unique' => 'الرقم الوطني هذا مستخدم بالفعل من قبل طبيب آخر.',
            'email.required' => 'حقل البريد الإلكتروني مطلوب.',
            'email.email' => 'يجب إدخال بريد إلكتروني صالح.',
            'email.unique' => 'هذا البريد الإلكتروني مستخدم بالفعل من قبل طبيب آخر.',
            'password.min' => 'كلمة المرور يجب أن تتكون من 8 أحرف على الأقل.',
            'password.regex' => 'يجب أن تحتوي كلمة المرور على حروف وأرقام (8 أحرف على الأقل).',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',
            'phone.required' => 'حقل رقم الهاتف مطلوب.',
            'phone.regex' => 'يجب أن يبدأ رقم الجوال بـ 05 ويتكون من 10 أرقام.',
            'phone.unique' => 'رقم الهاتف هذا مستخدم بالفعل من قبل طبيب آخر.',
            'name.required' => 'حقل اسم الطبيب مطلوب.',
            // ...
            'status.required' => 'حالة الطبيب مطلوبة.',
            'status.boolean' => 'قيمة الحالة غير صالحة.',
        ];
    }
}
