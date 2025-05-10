<?php

namespace App\Http\Requests; // تأكد من المسار الصحيح

use App\Models\GlobalEmail;
use App\Models\LaboratorieEmployee;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; // استيراد Rule

class UpdateLaboratorieEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // افترض أن الأدمن مصرح له دائمًا
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
{
    $employeeId = $this->route('laboratorie_employee'); // إذا كان اسم البارامتر في الـ route هو 'laboratorie_employee'
    if (!$employeeId && $this->id) { // $this->id هو قيمة حقل 'id' من الفورم إذا كان موجودًا
        $employeeId = $this->id;
    } elseif (!$employeeId && $this->route('id')) { // إذا كان اسم البارامتر في الـ route هو 'id'
        $employeeId = $this->route('id');
    }

    $currentLaboratorieEmployee = $employeeId ? LaboratorieEmployee::find($employeeId) : null;

    return [
        'name' => 'required|string|max:255',
        'national_id' => [
            'required',
            'string',
            'max:20',
            Rule::unique('laboratorie_employees', 'national_id')->ignore($employeeId),
        ],
        'email' => [
                'required',
                'email',
                Rule::unique('laboratorie_employees', 'email')->ignore($employeeId),
                function ($attribute, $value, $fail) use ( $currentLaboratorieEmployee) {
                    // إذا لم نتمكن من جلب الطبيب الحالي، لا يمكننا مقارنة الإيميل الأصلي
                    // في هذه الحالة، يمكنك إما التحقق من global_emails دائمًا أو تجاوز هذا الجزء
                    if ( $currentLaboratorieEmployee && strtolower($value) !== strtolower( $currentLaboratorieEmployee->getOriginal('email'))) {
                        if (GlobalEmail::where('email', strtolower($value))->exists()) {
                            $fail('هذا البريد الإلكتروني مستخدم بالفعل في النظام من قبل حساب آخر.');
                        }
                    }
                },
            ],
        'phone' => [
            'required',
            'string',
            'regex:/^05\d{8}$/',
            Rule::unique('laboratorie_employees', 'phone')->ignore($employeeId),
        ],
        'password' => 'nullable|string|min:8',
        'status' => 'required|boolean',
        // 'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
    ];
}
     /**
      * رسائل تحقق مخصصة (اختياري).
      */
     public function messages(): array
     {
         return [
             'name.required' => 'حقل الاسم مطلوب.',
             'national_id.required' => 'حقل الرقم الوطني/الإقامة مطلوب.',
             'national_id.unique' => 'الرقم الوطني/الإقامة مستخدم بالفعل.',
             'email.required' => 'حقل البريد الإلكتروني مطلوب.',
             'email.email' => 'صيغة البريد الإلكتروني غير صحيحة.',
             'email.unique' => 'البريد الإلكتروني مستخدم بالفعل.',
             'phone.required' => 'حقل الهاتف مطلوب.',
             'phone.unique' => 'رقم الهاتف مستخدم بالفعل.',
             'phone.regex' => 'صيغة رقم الهاتف غير صحيحة (مثال: 05xxxxxxxx).',
             'password.min' => 'كلمة المرور يجب أن تتكون من 8 أحرف على الأقل.',
             'status.required' => 'حقل الحالة مطلوب.',
             'status.boolean' => 'قيمة الحالة يجب أن تكون صحيحة أو خاطئة.',
            //  'photo.image' => 'الملف المرفوع يجب أن يكون صورة.',
            //  'photo.mimes' => 'صيغ الصور المسموح بها: jpeg, png, jpg, gif, svg.',
            //  'photo.max' => 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت.',
         ];
     }
}
