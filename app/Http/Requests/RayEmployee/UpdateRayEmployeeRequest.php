<?php

namespace App\Http\Requests\RayEmployee;

use App\Models\GlobalEmail;
use App\Models\RayEmployee;
use Illuminate\Validation\Rule;
use App\Models\GlobalIdentifier;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRayEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // السماح لأي مستخدم مصرح له (أو قم بتعديل هذا حسب الحاجة)
    }

    public function rules(): array
    {
        $employeeId = $this->route('ray_employee'); // إذا كان اسم البارامتر في الـ route هو 'ray_employee'
        if (!$employeeId && $this->id) { // $this->id هو قيمة حقل 'id' من الفورم إذا كان موجودًا
            $employeeId = $this->id;
        } elseif (!$employeeId && $this->route('id')) { // إذا كان اسم البارامتر في الـ route هو 'id'
            $employeeId = $this->route('id');
        }

        $currentRayEmployee = $employeeId ? RayEmployee::find($employeeId) : null;

        return [
            'name' => 'required|string|max:255',
            // 'national_id' => [
            //     'required',
            //     'string',
            //     'max:20',
            //     Rule::unique('ray_employees', 'national_id')->ignore($employeeId),
            // ],
            'national_id' => [
                'required',
                'string',
                'max:20',
                Rule::unique('ray_employees', 'national_id')->ignore($employeeId),
                function ($attribute, $value, $fail) use ($currentRayEmployee) {
                    // إذا لم نتمكن من جلب الطبيب الحالي، لا يمكننا مقارنة الإيميل الأصلي
                    // في هذه الحالة، يمكنك إما التحقق من global_national_ids دائمًا أو تجاوز هذا الجزء
                    if ($currentRayEmployee && strtolower($value) !== strtolower($currentRayEmployee->getOriginal('national_id'))) {
                        if (GlobalIdentifier::where('national_id', strtolower($value))->exists()) {
                            $fail('هذا الرقم مستخدم بالفعل في النظام من قبل حساب آخر.');
                        }
                    }
                },
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('ray_employees', 'email')->ignore($employeeId),
                function ($attribute, $value, $fail) use ($currentRayEmployee) {
                    // إذا لم نتمكن من جلب الطبيب الحالي، لا يمكننا مقارنة الإيميل الأصلي
                    // في هذه الحالة، يمكنك إما التحقق من global_emails دائمًا أو تجاوز هذا الجزء
                    if ($currentRayEmployee && strtolower($value) !== strtolower($currentRayEmployee->getOriginal('email'))) {
                        if (GlobalEmail::where('email', strtolower($value))->exists()) {
                            $fail('هذا البريد الإلكتروني مستخدم بالفعل في النظام من قبل حساب آخر.');
                        }
                    }
                },
            ],
            'phone' => [
                'required',
                'string',
                'regex:/^05\d{8}$/', // مثال لرقم جوال سعودي
                Rule::unique('ray_employees', 'phone')->ignore($employeeId), // فريد باستثناء الحالي
            ],
            'password' => 'nullable|string|min:8', // كلمة المرور اختيارية عند التحديث
            'status' => 'required|boolean', // الحالة مطلوبة ويجب أن تكون قيمة منطقية
            // 'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // الصورة اختيارية
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'الاسم الكامل مطلوب.',
            'national_id.required' => 'رقم الهوية مطلوب.',
            'national_id.unique' => 'رقم الهوية مستخدم بالفعل.',
            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.email' => 'صيغة البريد الإلكتروني غير صالحة.',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل.',
            'phone.required' => 'رقم الهاتف مطلوب.',
            'phone.regex' => 'يجب أن يكون رقم الهاتف بصيغة صحيحة (مثال: 05xxxxxxxx).',
            'phone.unique' => 'رقم الهاتف مستخدم بالفعل.',
            'password.min' => 'كلمة المرور يجب أن تتكون من 8 أحرف على الأقل.',
            'status.required' => 'حالة الموظف مطلوبة.',
            'status.boolean' => 'قيمة الحالة غير صالحة.',
            // 'photo.image' => 'يجب أن يكون الملف المرفوع صورة.',
            // 'photo.mimes' => 'صيغ الصور المسموح بها: jpeg, png, jpg, gif, svg.',
            // 'photo.max' => 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت.',
        ];
    }
}
