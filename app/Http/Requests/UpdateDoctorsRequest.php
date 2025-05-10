<?php

namespace App\Http\Requests;

use App\Models\Doctor;

use App\Models\GlobalEmail;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDoctorsRequest extends FormRequest
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
        $doctorId = $this->route('doctor'); // إذا كان اسم البارامتر في الـ route هو 'doctor'
        if (!$doctorId && $this->id) { // $this->id هو قيمة حقل 'id' من الفورم إذا كان موجودًا
            $doctorId = $this->id;
        } elseif (!$doctorId && $this->route('id')) { // إذا كان اسم البارامتر في الـ route هو 'id'
            $doctorId = $this->route('id');
        }

        $currentDoctor = $doctorId ? Doctor::find($doctorId) : null;



        
        return [
            "national_id" => 'required|string|digits:9|unique:doctors,national_id,' . $this->id,
            'email' => [
                'required',
                'email',
                Rule::unique('doctors', 'email')->ignore($doctorId),
                function ($attribute, $value, $fail) use ($currentDoctor) {
                    // إذا لم نتمكن من جلب الطبيب الحالي، لا يمكننا مقارنة الإيميل الأصلي
                    // في هذه الحالة، يمكنك إما التحقق من global_emails دائمًا أو تجاوز هذا الجزء
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
                'min:3',
                'regex:/^[\p{Arabic}\p{L}\s\-\.]+$/u'
            ],
            "section_id" => 'required|exists:sections,id',
            "number_of_statements" => 'required|integer|min:1|max:20',
            // "photo" => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            // "appointments" => 'required|array',
            // "appointments.*" => 'exists:appointments,id'
        ];
    }

    public function messages()
    {
        return [
            // ... الرسائل الحالية
            'email.email' => 'يجب إدخال بريد إلكتروني صالح',
            'password.regex' => 'يجب أن تحتوي كلمة المرور على حروف وأرقام (8 أحرف على الأقل)',
            'phone.regex' => 'يجب أن يبدأ رقم الجوال بـ 05 ويتكون من 10 أرقام',
            'name.regex' => 'يجب أن يحتوي الاسم على أحرف عربية أو إنجليزية فقط',
            'appointments.required' => 'يجب اختيار مواعيد العمل',
            'photo.image' => 'يجب أن يكون الملف صورة',
            'photo.mimes' => 'يجب أن تكون الصورة من نوع: jpeg, png, jpg, gif',
            'photo.max' => 'يجب أن لا تتجاوز الصورة 2MB'
        ];
    }
}
