<?php

namespace App\Http\Requests\Dashboard\PatientAdmission;

use App\Models\PatientAdmission;
use App\Models\Bed; // لاستخدامه في التحقق من السرير
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePatientAdmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // افترض أن الأدمن مصرح له
    }

    public function rules(): array
    {
        $admissionId = $this->route('patient_admission')->id; // أو $this->patient_admission->id
        $admission = PatientAdmission::find($admissionId); // جلب سجل الدخول الحالي

        return [
            // لا يتم تعديل patient_id عادةً في سجل دخول قائم
            'doctor_id' => 'nullable|exists:doctors,id',
            'section_id' => 'nullable|exists:sections,id',
            'bed_id' => [
                'nullable',
                'exists:beds,id',
                // تحقق من أن السرير متاح أو هو السرير الحالي للمريض
                Rule::exists('beds', 'id')->where(function ($query) use ($admission) {
                    $query->where('status', Bed::STATUS_AVAILABLE)
                        ->orWhere('id', $admission->bed_id); // السماح باختيار السرير الحالي مرة أخرى
                }),
            ],
            'admission_date' => 'required|date|before_or_equal:now', // تاريخ الدخول يجب أن يكون قبل أو يساوي تاريخ اليوم
            'discharge_date' => 'nullable|date|after_or_equal:admission_date',
            'discharge_date' => 'nullable|date|after_or_equal:admission_date', // تاريخ الخروج يجب أن يكون بعد أو يساوي تاريخ الدخول
            'reason_for_admission' => 'nullable|string|max:1000',
            'admitting_diagnosis' => 'nullable|string|max:500',
            'discharge_reason' => 'nullable|string|max:1000',
            'discharge_diagnosis' => 'nullable|string|max:500',
            'status' => ['required', Rule::in(array_keys([ // استخدام كل الحالات الممكنة
                PatientAdmission::STATUS_ADMITTED => 'مقيم حاليًا',
                PatientAdmission::STATUS_DISCHARGED => 'خرج',
                PatientAdmission::STATUS_TRANSFERRED_OUT => 'منقول للخارج',
                PatientAdmission::STATUS_TRANSFERRED_IN => 'منقول للداخل',
                PatientAdmission::STATUS_CANCELLED => 'ملغى'
            ]))],
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'doctor_id.exists' => 'الطبيب المختار غير صالح.',
            'section_id.exists' => 'القسم المختار غير صالح.',
            'bed_id.exists' => 'السرير المختار غير صالح أو غير موجود.',
            // رسالة مخصصة لقاعدة Rule::exists مع where
            // يمكنك تعريفها بشكل أفضل باستخدام Custom Rule إذا أردت رسالة أدق
            'admission_date.required' => 'تاريخ ووقت الدخول مطلوب.',
            'admission_date.before_or_equal' => 'تاريخ الدخول يجب أن يكون قبل أو يساوي تاريخ الخروج وتاريخ اليوم.',
            'discharge_date.after_or_equal' => 'تاريخ الخروج يجب أن يكون بعد أو يساوي تاريخ الدخول.',
            'status.required' => 'حالة سجل الدخول مطلوبة.',
            'status.in' => 'حالة سجل الدخول المختارة غير صالحة.',
            // ... (أضف رسائل أخرى حسب الحاجة)
        ];
    }
}
