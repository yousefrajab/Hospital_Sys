<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait; // <-- استيراد
use Illuminate\Contracts\Auth\CanResetPassword;                         // <-- استيراد

class RayEmployee extends Authenticatable implements CanResetPassword // <-- تطبيق الواجهة
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
        'password' => 'hashed', // ** إضافة هذا **
    ];

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}
