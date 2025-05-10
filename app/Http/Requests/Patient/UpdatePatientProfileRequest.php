<?php

namespace App\Http\Requests\Patient; // تأكد من المسار الصحيح

use App\Models\GlobalEmail;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password; // لاستخدام قواعد كلمة المرور المتقدمة

class UpdatePatientProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        // فقط المريض المسجل يمكنه تحديث ملفه الشخصي
        return Auth::guard('patient')->check();
    }


    public function rules()
    {
        $currentPatient = Auth::guard('patient')->user(); // ** الحصول على الموديل الحالي للمريض **
        $patientId = $currentPatient->id;

        return [
            'name' => 'required|string|max:255',
            'national_id' => [
                'required',
                'digits:9',
                Rule::unique('patients')->ignore($patientId) // تجاهل ID الحالي
            ],
            'email' => [
                'required',
                'email', // Laravel يضيف max:255 تلقائيًا مع 'email'
                Rule::unique('patients', 'email')->ignore($patientId), // 1. فريد في جدول patients
                function ($attribute, $value, $fail) use ($currentPatient) { // 2. فريد في global_emails إذا تغير
                    if (strtolower($value) !== strtolower($currentPatient->getOriginal('email'))) {
                        if (GlobalEmail::where('email', strtolower($value))->exists()) {
                            $fail('هذا البريد الإلكتروني مستخدم بالفعل من قبل حساب آخر في النظام.');
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
            'Phone' => [
                'required',
                'numeric',
                Rule::unique('patients')->ignore($patientId) // تجاهل ID الحالي
            ],
            'Date_Birth' => 'required|date|before:today',
            'Gender' => 'required|integer|in:1,2',
            'Blood_Group' => 'required|string|in:O-,O+,A+,A-,B+,B-,AB+,AB-',
            'Address' => 'nullable|string|max:500'
        ];
    }
    public function messages()
    {
        return [
            'national_id.required' => trans('validation.required'),
            'national_id.digits' => trans('validation.digits', ['digits' => 9]),
            'national_id.unique' => trans('validation.unique'),
            'email.required' => trans('validation.required'),
            'email.unique' => trans('validation.unique'),
            'password.required' => trans('validation.required'),
            'password.sometimes' => trans('validation.sometimes'),
            'Phone.required' => trans('validation.required'),
            'Phone.unique' => trans('validation.unique'),
            'Phone.numeric' => trans('validation.numeric'),
            'Date_Birth.required' => trans('validation.required'),
            'Date_Birth.date' => trans('validation.date'),
            'Gender.required' => trans('validation.required'),
            'Gender.integer' => trans('validation.integer'),
            'Blood_Group.required' => trans('validation.required'),
        ];
    }

}
