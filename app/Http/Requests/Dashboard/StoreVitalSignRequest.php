<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class StoreVitalSignRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // قم بتعيين الصلاحيات المناسبة هنا، مثلاً:
        // return auth()->check() && auth()->user()->can('create vital_signs');
        return true; // مؤقتاً
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'recorded_at' => 'required|date_format:Y-m-d\TH:i|before_or_equal:now',
            'temperature' => 'nullable|numeric|between:30,45',
            'systolic_bp' => 'nullable|integer|min:50|max:300',
            'diastolic_bp' => 'nullable|integer|min:30|max:200',
            'heart_rate' => 'nullable|integer|min:30|max:250',
            'respiratory_rate' => 'nullable|integer|min:5|max:60',
            'oxygen_saturation' => 'nullable|numeric|between:70,100',
            'pain_level' => 'nullable|integer|between:0,10',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'recorded_at.required' => 'حقل وقت التسجيل مطلوب.',
            'recorded_at.date_format' => 'صيغة وقت التسجيل غير صحيحة.',
            'recorded_at.before_or_equal' => 'وقت التسجيل لا يمكن أن يكون في المستقبل.',
            // ... أضف رسائل أخرى إذا أردت
        ];
    }

    /**
     * The key to be used for the view error bag.
     *
     * @var string
     */
    protected $errorBag = 'vitalStore'; // تحديد اسم الـ Error Bag هنا
}
