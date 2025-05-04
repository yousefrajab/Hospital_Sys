<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => 'يجب قبول :attribute',
    'active_url'           => ':attribute ليس عنوان URL صالح',
    'after'                => 'يجب أن يكون :attribute تاريخًا بعد :date',
    'after_or_equal'       => 'يجب أن يكون :attribute تاريخًا بعد أو يساوي :date',
    'alpha'                => 'يجب أن يحتوي :attribute على أحرف فقط',
    'alpha_dash'           => 'يجب أن يحتوي :attribute على أحرف وأرقام وشرطات فقط',
    'alpha_num'            => 'يجب أن يحتوي :attribute على أحرف وأرقام فقط',
    'array'                => 'يجب أن يكون :attribute مصفوفة',
    'before'               => 'يجب أن يكون :attribute تاريخًا قبل :date',
    'before_or_equal'      => 'يجب أن يكون :attribute تاريخًا قبل أو يساوي :date',
    'between'              => [
        'numeric' => 'يجب أن يكون :attribute بين :min و :max',
        'file'    => 'يجب أن يكون :attribute بين :min و :max كيلوبايت',
        'string'  => 'يجب أن يكون :attribute بين :min و :max حرفًا',
        'array'   => 'يجب أن يحتوي :attribute بين :min و :max عنصرًا',
    ],
    'boolean'              => 'يجب أن يكون :attribute صحيحًا أو خاطئًا',
    'confirmed'            => 'تأكيد :attribute غير متطابق',
    'date'                 => ':attribute ليس تاريخًا صالحًا',
    'date_equals'          => 'يجب أن يكون :attribute تاريخًا يساوي :date',
    'date_format'          => 'لا يتطابق :attribute مع الصيغة :format',
    'different'            => 'يجب أن يكون :attribute و :other مختلفين',
    'digits'               => 'يجب أن يكون :attribute مكونًا من :digits رقمًا',
    'digits_between'       => 'يجب أن يكون :attribute بين :min و :max رقمًا',
    'dimensions'           => ':attribute يحتوي على أبعاد صورة غير صالحة',
    'distinct'             => 'حقل :attribute يحتوي على قيمة مكررة',
    'email'                => 'يجب أن يكون :attribute عنوان بريد إلكتروني صالح',
    'ends_with'            => 'يجب أن ينتهي :attribute بأحد القيم التالية: :values',
    'exists'               => ':attribute المحدد غير صالح',
    'file'                 => 'يجب أن يكون :attribute ملفًا',
    'filled'               => 'حقل :attribute مطلوب',
    'gt'                   => [
        'numeric' => 'يجب أن يكون :attribute أكبر من :value',
        'file'    => 'يجب أن يكون :attribute أكبر من :value كيلوبايت',
        'string'  => 'يجب أن يكون :attribute أكبر من :value حرفًا',
        'array'   => 'يجب أن يحتوي :attribute على أكثر من :value عنصرًا',
    ],
    'gte'                  => [
        'numeric' => 'يجب أن يكون :attribute أكبر من أو يساوي :value',
        'file'    => 'يجب أن يكون :attribute أكبر من أو يساوي :value كيلوبايت',
        'string'  => 'يجب أن يكون :attribute أكبر من أو يساوي :value حرفًا',
        'array'   => 'يجب أن يحتوي :attribute على :value عنصرًا أو أكثر',
    ],
    'image'                => 'يجب أن يكون :attribute صورة',
    'in'                   => ':attribute المحدد غير صالح',
    'in_array'             => 'حقل :attribute غير موجود في :other',
    'integer'              => 'يجب أن يكون :attribute عددًا صحيحًا',
    'ip'                   => 'يجب أن يكون :attribute عنوان IP صالحًا',
    'ipv4'                 => 'يجب أن يكون :attribute عنوان IPv4 صالحًا',
    'ipv6'                 => 'يجب أن يكون :attribute عنوان IPv6 صالحًا',
    'json'                 => 'يجب أن يكون :attribute نصًا JSON صالحًا',
    'lt'                   => [
        'numeric' => 'يجب أن يكون :attribute أقل من :value',
        'file'    => 'يجب أن يكون :attribute أقل من :value كيلوبايت',
        'string'  => 'يجب أن يكون :attribute أقل من :value حرفًا',
        'array'   => 'يجب أن يحتوي :attribute على أقل من :value عنصرًا',
    ],
    'lte'                  => [
        'numeric' => 'يجب أن يكون :attribute أقل من أو يساوي :value',
        'file'    => 'يجب أن يكون :attribute أقل من أو يساوي :value كيلوبايت',
        'string'  => 'يجب أن يكون :attribute أقل من أو يساوي :value حرفًا',
        'array'   => 'يجب ألا يحتوي :attribute على أكثر من :value عنصرًا',
    ],
    'max'                  => [
        'numeric' => 'لا يمكن أن يكون :attribute أكبر من :max',
        'file'    => 'لا يمكن أن يكون :attribute أكبر من :max كيلوبايت',
        'string'  => 'لا يمكن أن يكون :attribute أكبر من :max حرفًا',
        'array'   => 'لا يمكن أن يحتوي :attribute على أكثر من :max عنصرًا',
    ],
    'mimes'                => 'يجب أن يكون :attribute ملفًا من النوع: :values',
    'mimetypes'            => 'يجب أن يكون :attribute ملفًا من النوع: :values',
    'min'                  => [
        'numeric' => 'يجب أن يكون :attribute على الأقل :min',
        'file'    => 'يجب أن يكون :attribute على الأقل :min كيلوبايت',
        'string'  => 'يجب أن يكون :attribute على الأقل :min حرفًا',
        'array'   => 'يجب أن يحتوي :attribute على الأقل :min عنصرًا',
    ],
    'multiple_of'          => 'يجب أن يكون :attribute مضاعفًا لـ :value',
    'not_in'               => ':attribute المحدد غير صالح',
    'not_regex'            => 'صيغة :attribute غير صالحة',
    'numeric'              => 'يجب أن يكون :attribute رقمًا',
    'password'             => 'كلمة المرور غير صحيحة',
    'present'              => 'يجب أن يكون حقل :attribute موجودًا',
    'regex'                => 'صيغة :attribute غير صالحة',
    'required'             => 'حقل :attribute مطلوب',
    'required_if'          => 'حقل :attribute مطلوب عندما يكون :other يساوي :value',
    'required_unless'      => 'حقل :attribute مطلوب إلا إذا كان :other موجودًا في :values',
    'required_with'        => 'حقل :attribute مطلوب عندما يكون :values موجودًا',
    'required_with_all'    => 'حقل :attribute مطلوب عندما تكون :values موجودة',
    'required_without'     => 'حقل :attribute مطلوب عندما لا يكون :values موجودًا',
    'required_without_all' => 'حقل :attribute مطلوب عندما لا تكون أي من :values موجودة',
    'prohibited'           => 'حقل :attribute محظور',
    'prohibited_if'        => 'حقل :attribute محظور عندما يكون :other يساوي :value',
    'prohibited_unless'    => 'حقل :attribute محظور إلا إذا كان :other موجودًا في :values',
    'same'                 => 'يجب أن يتطابق :attribute مع :other',
    'size'                 => [
        'numeric' => 'يجب أن يكون :attribute مساويًا لـ :size',
        'file'    => 'يجب أن يكون :attribute بحجم :size كيلوبايت',
        'string'  => 'يجب أن يكون :attribute بحجم :size حرفًا',
        'array'   => 'يجب أن يحتوي :attribute على :size عنصرًا',
    ],
    'starts_with'          => 'يجب أن يبدأ :attribute بأحد القيم التالية: :values',
    'string'               => 'يجب أن يكون :attribute نصًا',
    'timezone'             => 'يجب أن يكون :attribute منطقة زمنية صالحة',
    'unique'               => ':attribute مستخدم من قبل',
    'uploaded'             => 'فشل تحميل :attribute',
    'url'                  => 'صيغة :attribute غير صالحة',
    'uuid'                 => 'يجب أن يكون :attribute UUID صالحًا',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'رسالة مخصصة',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'name' => 'الاسم',
        'email' => 'البريد الإلكتروني',
        'password' => 'كلمة المرور',
        'password_confirmation' => 'تأكيد كلمة المرور',
        'current_password' => 'كلمة المرور الحالية',
        'phone' => 'رقم الهاتف',
        'national_id' => 'رقم الهوية',
        'Date_Birth' => 'تاريخ الميلاد',
        'Blood_Group' => 'فصيلة الدم',
        'Address' => 'العنوان',
        'Gender' => 'الجنس',
        'section_id' => 'القسم',
        'appointments' => 'المواعيد',
        'number_of_statements' => 'عدد الكشوفات',
        'photo' => 'الصورة',
    ],
];
