<?php

namespace App\Http\Requests;

use App\Models\Patient;
use App\Models\GlobalEmail;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePatientRequest extends FormRequest
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

        $patientId = $this->route('patient'); // إذا كان اسم البارامتر في الـ route هو 'patient'
        if (!$patientId && $this->id) { // $this->id هو قيمة حقل 'id' من الفورم إذا كان موجودًا
            $patientId = $this->id;
        } elseif (!$patientId && $this->route('id')) { // إذا كان اسم البارامتر في الـ route هو 'id'
            $patientId = $this->route('id');
        }

        $currentPatient = $patientId ? Patient::find($patientId) : null;

        return [
            'name' => 'required|string|max:255',
            'national_id' => [
                'required',
                'digits:9',
                Rule::unique('patients')->ignore($patientId) // تجاهل ID الحالي
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('patients', 'email')->ignore($patientId),
                function ($attribute, $value, $fail) use ($currentPatient) {
                    // إذا لم نتمكن من جلب الطبيب الحالي، لا يمكننا مقارنة الإيميل الأصلي
                    // في هذه الحالة، يمكنك إما التحقق من global_emails دائمًا أو تجاوز هذا الجزء
                    if ($currentPatient && strtolower($value) !== strtolower($currentPatient->getOriginal('email'))) {
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
