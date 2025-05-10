<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Astrotomic\Translatable\Translatable;
use App\Notifications\DoctorResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Illuminate\Contracts\Auth\CanResetPassword;                         // <-- استيراد
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait; // <-- استيراد

class Doctor extends Authenticatable implements TranslatableContract, CanResetPassword // <-- تطبيق الواجهة
{
    use HasFactory, Notifiable, Translatable, CanResetPasswordTrait; // <-- استخدام الـ Trait

    public $translatedAttributes = ['name'];

    protected $fillable = [
        'national_id',
        'name',
        'email',
        'password',
        'phone',
        'section_id',
        'status',
        'number_of_statements',
        'email_verified_at'
    ];

    protected $hidden = [
        'password',
        // 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'status' => 'boolean',
        // 'password' => 'hashed', // ** إضافة هذا لضمان تشفير كلمة المرور تلقائيًا عند التعيين **
    ];

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function appointments() // افترض أن هذه هي العلاقة الصحيحة للمواعيد المرتبطة بالطبيب
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    public function workingDays()
    {
        return $this->hasMany(DoctorWorkingDay::class, 'doctor_id');
    }

    // هذه العلاقة تبدو مكررة مع appointments() إذا كانت HasMany هي الصحيحة
    // إذا كانت علاقة many-to-many مع جدول وسيط، أبقِ عليها وعدّل appointments()
    public function doctorappointments()
    {
        return $this->belongsToMany(Appointment::class,'appointment_doctor');
    }


    public function sendPasswordResetNotification($token)
{
    $this->notify(new DoctorResetPasswordNotification($token));
}
}
