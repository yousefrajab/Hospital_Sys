<?php

namespace App\Http\Requests;

use App\Models\GlobalEmail;
use Illuminate\Validation\Rule;
use App\Models\GlobalIdentifier;
use App\Models\PatientChronicDisease;
use Illuminate\Foundation\Http\FormRequest;

class StorePatientRequest extends FormRequest
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
        return [
            'name' => 'required|string|max:255',
            // 'national_id' => 'required|digits:9|unique:patients,national_id',
            'national_id' => [
                'required',
                'string',
                'digits:9',
                Rule::unique('patients', 'national_id'), // 1. فريد في جدول patients
                function ($attribute, $value, $fail) { // 2. فريد في global_national_ids
                    if (GlobalIdentifier::where('national_id', strtolower($value))->exists()) {
                        $fail('هذا الرقم مستخدم بالفعل في النظام.');
                    }
                },
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('patients', 'email'), // 1. فريد في جدول patients
                function ($attribute, $value, $fail) { // 2. فريد في global_emails
                    if (GlobalEmail::where('email', strtolower($value))->exists()) {
                        $fail('هذا البريد الإلكتروني مستخدم بالفعل في النظام.');
                    }
                },
            ],
            'password' => 'required|confirmed|min:8',
            'Phone' => 'required|numeric|unique:patients,Phone',
            'Date_Birth' => 'required|date|before:today',
            'Gender' => 'required|integer|in:1,2',
            'Blood_Group' => 'required|string|in:O-,O+,A+,A-,B+,B-,AB+,AB-',
            'Address' => 'nullable|string|max:500',
            // حقول الأمراض المزمنة (ستأتي لاحقًا)
            'chronic_diseases' => 'nullable|array',
            'chronic_diseases.*.disease_id' => 'required_with:chronic_diseases|integer|exists:diseases,id',
            'chronic_diseases.*.diagnosed_at' => 'nullable|date|before_or_equal:today',
            'chronic_diseases.*.diagnosed_by' => 'nullable|string|max:255',
            'chronic_diseases.*.current_status' => ['nullable', 'string', Rule::in(array_keys(PatientChronicDisease::getStatuses()))],
            'chronic_diseases.*.treatment_plan' => 'nullable|string|max:1000',
            'chronic_diseases.*.notes' => 'nullable|string|max:1000',
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


            'chronic_diseases.array' => 'بيانات الأمراض المزمنة يجب أن تكون مصفوفة.',
            'chronic_diseases.*.disease_id.required_with' => 'يجب اختيار مرض مزمن لكل صف يتم إضافته.',
            'chronic_diseases.*.disease_id.exists' => 'المرض المزمن المختار غير صالح.',
            'chronic_diseases.*.diagnosed_at.date' => 'تاريخ تشخيص المرض المزمن يجب أن يكون تاريخًا.',
            'chronic_diseases.*.diagnosed_at.before_or_equal' => 'تاريخ التشخيص لا يمكن أن يكون في المستقبل.',
            'chronic_diseases.*.current_status.in' => 'الحالة المختارة للمرض المزمن غير صالحة.',
        ];
    }
}
