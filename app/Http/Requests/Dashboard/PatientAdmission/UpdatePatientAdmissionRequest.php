<?php

namespace App\Http\Requests\Dashboard\PatientAdmission;

use App\Models\PatientAdmission;
use App\Models\Bed;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
// لا حاجة لـ use Illuminate\Contracts\Validation\Validator; أو use Illuminate\Support\Facades\Validator; هنا

class UpdatePatientAdmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        /** @var PatientAdmission $admission */
        $admission = $this->route('patient_admission');
        $isDischargingProcess = $this->input('status') === PatientAdmission::STATUS_DISCHARGED;

        return [
            // ... (نفس القواعد) ...
            'doctor_id' => 'sometimes|nullable|exists:doctors,id',
            'section_id' => 'sometimes|nullable|exists:sections,id',
            'bed_id' => [
                'sometimes',
                'nullable',
                'exists:beds,id',
                Rule::exists('beds', 'id')->where(function ($query) use ($admission) {
                    $query->where('status', Bed::STATUS_AVAILABLE)
                          ->orWhere('id', $admission->bed_id);
                }),
            ],
            'admission_date' => [
                'sometimes',
                'required',
                'date',
                'before_or_equal:now'
            ],
            'reason_for_admission' => 'sometimes|nullable|string|max:1000',
            'admitting_diagnosis' => 'sometimes|nullable|string|max:500',
            'discharge_date' => [
                Rule::requiredIf($isDischargingProcess),
                'nullable',
                'date_format:Y-m-d\TH:i',
                function ($attribute, $value, $fail) use ($admission) {
                    if ($value) {
                        $admissionDateInput = $this->input('admission_date');
                        $admissionDate = $admissionDateInput ? Carbon::parse($admissionDateInput) : $admission->admission_date;
                        if (Carbon::parse($value)->lt($admissionDate)) {
                            $fail('تاريخ الخروج يجب أن يكون بعد أو يساوي تاريخ الدخول.');
                        }
                    }
                },
            ],
            'discharge_reason' => 'nullable|string|max:1000',
            'discharge_diagnosis' => 'nullable|string|max:500',
            'status' => [
                'required',
                Rule::in(array_keys(PatientAdmission::getAllStatusesArray()))
            ],
            'notes' => 'nullable|string|max:2000',
        ];
    }

    public function messages(): array
    {
        return [
            // ... (نفس الرسائل) ...
            'doctor_id.exists' => 'الطبيب المختار غير صالح.',
            'section_id.exists' => 'القسم المختار غير صالح.',
            'bed_id.exists' => 'السرير المختار غير صالح أو غير موجود.',
            'admission_date.required' => 'تاريخ ووقت الدخول مطلوب.',
            'admission_date.date' => 'صيغة تاريخ الدخول غير صحيحة.',
            'admission_date.before_or_equal' => 'تاريخ الدخول لا يمكن أن يكون في المستقبل.',
            'discharge_date.required_if' => 'تاريخ ووقت الخروج مطلوب عند تسجيل خروج المريض.',
            'discharge_date.date_format' => 'صيغة تاريخ الخروج غير صحيحة. يجب أن تكون Y-m-d\TH:i.',
            'status.required' => 'حالة سجل الدخول مطلوبة.',
            'status.in' => 'حالة سجل الدخول المختارة غير صالحة.',
            'reason_for_admission.max' => 'سبب الدخول طويل جدًا (بحد أقصى 1000 حرف).',
            'admitting_diagnosis.max' => 'التشخيص عند الدخول طويل جدًا (بحد أقصى 500 حرف).',
            'discharge_reason.max' => 'سبب الخروج طويل جدًا (بحد أقصى 1000 حرف).',
            'discharge_diagnosis.max' => 'التشخيص عند الخروج طويل جدًا (بحد أقصى 500 حرف).',
            'notes.max' => 'الملاحظات طويلة جدًا (بحد أقصى 2000 حرف).',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  mixed  $validator  <-- إزالة الـ type hint
     * @return void
     */
    public function withValidator($validator): void // <-- إزالة الـ type hint
    {
        if ($this->route('patient_admission') && $this->input('status') === PatientAdmission::STATUS_DISCHARGED) {
            $this->errorBag = 'dischargeFormBag' . $this->route('patient_admission')->id;
        }
    }
}
