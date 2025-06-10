<?php

namespace App\Http\Requests\Dashboard\Prescription;

use App\Models\Prescription; // لاستخدام الثوابت
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth; // للتحقق من الطبيب

class StorePrescriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        // فقط الطبيب المسجل يمكنه إنشاء وصفة
        return Auth::guard('doctor')->check();
        // أو يمكنك إضافة صلاحيات أكثر تحديدًا إذا لزم الأمر
        // return Auth::user()->can('create', Prescription::class);
    }

    public function rules(): array
    {
        return [
            'patient_id' => 'required|exists:patients,id',
            // 'patient_admission_id' => 'nullable|exists:patient_admissions,id',
            'prescription_date' => 'required|date|before_or_equal:today',
            'status' => ['nullable', Rule::in([
                Prescription::STATUS_NEW,
                // أضف حالات أخرى إذا سمحت للطبيب باختيارها مباشرة عند الإنشاء
            ])],
            'doctor_notes' => 'nullable|string|max:1000',
            'is_chronic_prescription' => 'nullable|boolean',
            // 'next_refill_due_date' => 'nullable|date|after_or_equal:prescription_date', // إذا كانت is_chronic_prescription = true

            // قواعد التحقق لبنود الأدوية (مصفوفة)
            'items' => 'required|array|min:1', // يجب أن تحتوي الوصفة على دواء واحد على الأقل
            'items.*.medication_id' => 'required|exists:medications,id',
            'items.*.dosage' => 'required|string|max:255',
            'items.*.frequency' => 'required|string|max:255',
            'items.*.duration' => 'nullable|string|max:100',
            'items.*.route_of_administration' => 'nullable|string|max:100',
            'items.*.quantity_prescribed' => 'nullable|integer|min:1',
            'items.*.instructions_for_patient' => 'nullable|string|max:1000',
            'items.*.refills_allowed' => 'nullable|integer|min:0',
            'items.*.is_prn' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'patient_id.required' => 'يجب اختيار المريض للوصفة.',
            'patient_id.exists' => 'المريض المختار غير صالح.',
            'prescription_date.required' => 'تاريخ الوصفة مطلوب.',
            'prescription_date.date' => 'صيغة تاريخ الوصفة غير صحيحة.',
            'prescription_date.before_or_equal' => 'تاريخ الوصفة لا يمكن أن يكون في المستقبل.',
            'status.in' => 'حالة الوصفة المختارة غير صالحة.',
            'doctor_notes.max' => 'ملاحظات الطبيب طويلة جدًا.',

            'items.required' => 'يجب إضافة دواء واحد على الأقل للوصفة.',
            'items.array' => 'بيانات الأدوية يجب أن تكون مصفوفة.',
            'items.min' => 'يجب إضافة دواء واحد على الأقل للوصفة.',

            'items.*.medication_id.required' => 'يجب اختيار الدواء لكل بند.',
            'items.*.medication_id.exists' => 'الدواء المختار غير صالح.',
            'items.*.dosage.required' => 'جرعة الدواء مطلوبة.',
            'items.*.frequency.required' => 'تكرار الدواء مطلوب.',
            // ... أضف رسائل لبقية حقول items
        ];
    }
}
