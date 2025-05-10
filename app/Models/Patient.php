<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Notifications\PatientResetPasswordNotification;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;                            // <-- استيراد
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract; // مهم للترجمة
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;    // <-- استيراد
use Illuminate\Notifications\Notifiable;                                  // مهم للإشعارات

class Patient extends Authenticatable implements TranslatableContract, CanResetPassword // <-- تطبيق الواجهات
{
    use HasFactory, Notifiable, Translatable, CanResetPasswordTrait; // <-- استخدام الـ Traits

    public $translatedAttributes = ['name', 'Address'];

    // تأكد من أن $fillable يحتوي على password إذا كنت ستسمح بتغييره
    public $fillable = [
        'national_id',
        'name', // هذا سيتم التعامل معه بواسطة Translatable
        'email',
        'password',
        'Date_Birth',
        'Phone', // اسم الحقل بحرف P كبير
        'Gender',
        'Blood_Group',
        'Address' // هذا سيتم التعامل معه بواسطة Translatable
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime', // إذا كان لديك هذا العمود
        'Date_Birth' => 'date', // تحويل تاريخ الميلاد إلى كائن Carbon
        // 'Gender' => 'integer', // إذا كان رقميًا
        // 'password' => 'hashed', // ** إضافة هذا **
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function Service() // افترض أن هذا Service وليس services (مفرد)
    {
        return $this->belongsTo(Service::class, 'Service_id');
    }

    // هذه العلاقات تبدو غير منطقية هنا، المريض لا ينتمي لموظف.
    // الموظف هو الذي قد يكون مرتبطًا بخدمة مقدمة للمريض.
    public function employee()
    {
        return $this->belongsTo(RayEmployee::class, 'RayEmployee_id');
    }

    // public function lab_employee()
    // {
    //     return $this->belongsTo(LaboratorieEmployee::class, 'LaboratorieEmployee_id');
    // }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function diseases()
    {
        return $this->belongsToMany(Disease::class, 'disease_patient')
            ->withTimestamps();
    }


    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PatientResetPasswordNotification($token));
    }
}
