<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// استخدم Authenticatable إذا كان الأطباء يسجلون الدخول بشكل منفصل
use Illuminate\Foundation\Auth\User as Authenticatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract; // تأكد من استيراد الواجهة
use Astrotomic\Translatable\Translatable;
use Illuminate\Notifications\Notifiable; // أضف هذا إذا كنت تستخدم إشعارات لارافيل

class Doctor extends Authenticatable implements TranslatableContract // تأكد من تطبيق الواجهة
{
    use HasFactory, Notifiable, Translatable; // أضف Notifiable إذا لزم الأمر

    // الحقول التي يتم ترجمتها (افترضت أن 'name' فقط، أضف غيرها إذا لزم الأمر مثل 'specialization')
    public $translatedAttributes = ['name'];

    // الحقول القابلة للتعبئة الجماعية
    protected $fillable= [
        'national_id',
        'name',
        'email',
        'password', // يجب أن يكون محمياً في $hidden
        'phone',
        'section_id',
        'status',
        'number_of_statements',
        'email_verified_at' // أضف هذا إذا كان موجوداً في قاعدة البيانات
    ];

    // الحقول التي يجب إخفاؤها عند التحويل لـ array/json
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // تحويل أنواع البيانات
    protected $casts = [
        'email_verified_at' => 'datetime',
        'status' => 'boolean',
    ];


    /**
     * Get the Doctor's image (علاقة MorphOne مع الصور).
     */
    public function image()
    {
        // تأكد من أن موديل Image واسم العلاقة 'imageable' صحيحان
        return $this->morphOne(Image::class, 'imageable');
    }

    /**
     * Get the section this doctor belongs to (علاقة BelongsTo مع الأقسام).
     */
    public function section()
    {
        // إذا لم يكن المفتاح الخارجي 'section_id'، يجب تحديده هنا
        return $this->belongsTo(Section::class, 'section_id');
    }

    /**
     * Get all appointments for the doctor (علاقة HasMany مع المواعيد).
     * تم تغيير اسم العلاقة من doctorappointments وتصحيح النوع إلى HasMany.
     */
    public function appointments()
    {
        // افترض أن المفتاح الخارجي في جدول appointments هو 'doctor_id'
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    /**
     * Get the working days for the doctor (علاقة HasMany مع أيام العمل).
     * *** هذه العلاقة ضرورية للمنطق الجديد ***
     */
    public function workingDays()
    {
        // افترض أن المفتاح الخارجي في جدول doctor_working_days هو 'doctor_id'
        return $this->hasMany(DoctorWorkingDay::class, 'doctor_id');
    }

    public function doctorappointments()
    {
        return $this->belongsToMany(Appointment::class,'appointment_doctor');
    }

}
