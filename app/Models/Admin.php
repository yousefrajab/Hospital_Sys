<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait; // <-- استيراد
use Illuminate\Contracts\Auth\CanResetPassword;                         // <-- استيراد

class Admin extends Authenticatable implements CanResetPassword // <-- تطبيق الواجهة
{
    use HasFactory, Notifiable, CanResetPasswordTrait; // <-- استخدام الـ Trait

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // ** إضافة هذا **
    ];

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}
