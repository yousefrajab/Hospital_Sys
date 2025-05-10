<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use App\Notifications\LabResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;                         // <-- استيراد
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait; // <-- استيراد

class LaboratorieEmployee extends Authenticatable implements CanResetPassword // <-- تطبيق الواجهة
{
    use HasFactory, Notifiable, CanResetPasswordTrait; // <-- استخدام الـ Trait

    protected $fillable = [
        'national_id',
        'name',
        'email',
        'password',
        'email_verified_at',
        'phone',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'status' => 'boolean', // ** تفعيل هذا إذا كان لديك عمود status **
        // 'password' => 'hashed', // ** إضافة هذا **
    ];

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    // هذه العلاقة قد لا تكون منطقية هنا، الموظف لا ينتمي لمريض.
    // إذا كان الموظف مرتبطًا بالتحاليل الخاصة بمريض، يجب أن تكون العلاقة من خلال جدول التحاليل.
    // public function patient()
    // {
    //     return $this->belongsTo(Patient::class, 'patient_id');
    // }


    public function sendPasswordResetNotification($token)
    {
        $this->notify(new LabResetPasswordNotification($token));
    }
}
