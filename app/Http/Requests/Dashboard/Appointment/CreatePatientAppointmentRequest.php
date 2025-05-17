<?php

namespace App\Http\Requests\Dashboard\Appointment;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Bed; // لاستخدامه في التحقق من السرير إذا لزم الأمر
use App\Models\PatientAdmission; // لاستخدام الثوابت

class CreatePatientAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // يمكن لأي شخص محاولة الحجز مبدئيًا
    }

    public function rules(): array
    {
        return [
            'patient_name' => 'required|string|max:255',
            'patient_email' => 'required|email|max:255',
            // يمكنك إضافة تحقق من تفرد الإيميل هنا إذا كان المريض يسجل حسابًا جديدًا
            // أو إذا كنت تريد التأكد أن هذا الإيميل ليس مرتبطًا بموعد آخر في نفس الوقت
            'patient_phone' => 'required|string|regex:/^\+?[0-9\s\-]{10,15}$/', // regex أكثر مرونة للهاتف
            'section_id' => 'required|exists:sections,id',
            'doctor_id' => 'required|exists:doctors,id',
            'selected_date' => 'required|date|after_or_equal:' . now()->format('Y-m-d'),
            'selected_time' => 'required|date_format:H:i', // مثال: 14:30
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'patient_name.required' => 'اسم المريض مطلوب.',
            'patient_email.required' => 'البريد الإلكتروني للمريض مطلوب.',
            'patient_email.email' => 'صيغة البريد الإلكتروني غير صحيحة.',
            'patient_phone.required' => 'رقم هاتف المريض مطلوب.',
            'patient_phone.regex' => 'صيغة رقم الهاتف غير صحيحة.',
            'section_id.required' => 'يجب اختيار القسم الطبي.',
            'doctor_id.required' => 'يجب اختيار الطبيب المعالج.',
            'selected_date.required' => 'يجب اختيار تاريخ الموعد.',
            'selected_date.after_or_equal' => 'لا يمكن حجز موعد في تاريخ سابق.',
            'selected_time.required' => 'يجب اختيار وقت الموعد.',
            'selected_time.date_format' => 'صيغة الوقت غير صحيحة (مثال: 14:30).',
        ];
    }
}
