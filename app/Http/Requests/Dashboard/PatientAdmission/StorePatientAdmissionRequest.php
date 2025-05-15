<?php

namespace App\Http\Requests\Dashboard\PatientAdmission; // تأكد من المسار

use App\Models\PatientAdmission; // لاستخدام الثوابت
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePatientAdmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // افترض أن الأدمن مصرح له
    }

    public function rules(): array
    {
        return [
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'section_id' => 'nullable|exists:sections,id', // قد يكون اختياريًا إذا تم تحديده عبر الغرفة
            'bed_id' => [
                'nullable', // السرير قد يكون اختياريًا عند الدخول الأولي
                'exists:beds,id',
                // قاعدة تحقق مخصصة للتأكد من أن السرير المختار متاح
                Rule::exists('beds', 'id')->where(function ($query) {
                    $query->where('status', \App\Models\Bed::STATUS_AVAILABLE);
                }),
            ],
            'admission_date' => 'required|date|before_or_equal:now', // تاريخ الدخول لا يمكن أن يكون في المستقبل
            'reason_for_admission' => 'nullable|string|max:1000',
            'admitting_diagnosis' => 'nullable|string|max:500',
            'status' => ['required', Rule::in([PatientAdmission::STATUS_ADMITTED /*, PatientAdmission::STATUS_RESERVED */])],
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'patient_id.required' => 'يجب اختيار المريض.',
            'patient_id.exists' => 'المريض المختار غير صالح.',
            'doctor_id.exists' => 'الطبيب المختار غير صالح.',
            'section_id.exists' => 'القسم المختار غير صالح.',
            'bed_id.exists' => 'السرير المختار غير صالح أو غير موجود.',
            'bed_id.unique_if_available' => 'السرير المختار مشغول حاليًا.', // هذه رسالة لقاعدة مخصصة إذا أنشأتها
            'admission_date.required' => 'تاريخ ووقت الدخول مطلوب.',
            'admission_date.date' => 'صيغة تاريخ الدخول غير صحيحة.',
            'admission_date.before_or_equal' => 'تاريخ الدخول لا يمكن أن يكون في المستقبل.',
            'status.required' => 'حالة سجل الدخول مطلوبة.',
            'status.in' => 'حالة سجل الدخول المختارة غير صالحة.',
            'reason_for_admission.max' => 'سبب الدخول طويل جدًا.',
            'admitting_diagnosis.max' => 'التشخيص عند الدخول طويل جدًا.',
            'notes.max' => 'الملاحظات طويلة جدًا.',
        ];
    }

    
}
