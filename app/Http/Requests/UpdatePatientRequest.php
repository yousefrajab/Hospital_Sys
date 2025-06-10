<?php

namespace App\Http\Requests;

use App\Models\Patient;
use App\Models\GlobalEmail;
use Illuminate\Validation\Rule;
use App\Models\GlobalIdentifier;
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
            // 'national_id' => [
            //     'required',
            //     'digits:9',
            //     Rule::unique('patients')->ignore($patientId) // تجاهل ID الحالي
            // ],


            'national_id' => [
                'required',
                'string',
                'digits:9',
                Rule::unique('patients', 'national_id')->ignore($patientId), // 1. فريد في جدول patients (باستثناء الحالي)
                function ($attribute, $value, $fail) use ($currentPatient) { // ** استخدام $currentPatient هنا **
                    // تحقق من global_national_ids فقط إذا تغير الإيميل عن الإيميل الأصلي للطبيب
                    if (strtolower($value) !== strtolower($currentPatient->getOriginal('national_id'))) {
                        if (GlobalIdentifier::where('national_id', strtolower($value))->exists()) {
                            $fail('هذه الهوية  مستخدمة بالفعل من قبل حساب آخر في النظام.');
                        }
                    }
                },
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
                        } elseif (!$currentPatient) { // إذا لم يتم العثور على المريض (نادر، لكن للتحقق)
                            if (GlobalEmail::where('email', strtolower($value))->exists()) {
                                $fail('هذا البريد الإلكتروني مستخدم بالفعل في النظام.');
                            }
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
            'Address' => 'nullable|string|max:500',
            'chronic_diseases_input' => 'nullable|array',
            'chronic_diseases_input.*.disease_id' => 'required_with:chronic_diseases_input|exists:diseases,id',
            'chronic_diseases_input.*.diagnosed_at' => 'nullable|date|before_or_equal:today',
            'chronic_diseases_input.*.diagnosed_by' => 'nullable|string|max:255',
            'chronic_diseases_input.*.current_status' => ['nullable', Rule::in(array_keys(\App\Models\PatientChronicDisease::getStatuses()))],
            'chronic_diseases_input.*.treatment_plan' => 'nullable|string',
            'chronic_diseases_input.*.notes' => 'nullable|string',
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
