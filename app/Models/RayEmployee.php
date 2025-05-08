<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// استخدم Authenticatable إذا كان هذا المستخدم يسجل الدخول
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable; // إضافة Notifiable

class RayEmployee extends Authenticatable
{
    use HasFactory, Notifiable; // إضافة Notifiable

    /**
     * الحقول القابلة للتعبئة الجماعية (أفضل من guarded فارغة).
     */
    protected $fillable = [
        'national_id',
        'name',
        'email',
        'password',
        'email_verified_at',
        'phone',
        'status',
    ];

    /**
     * الحقول المخفية.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * أنواع الحقول.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
         // أضف status إذا كان موجوداً
         // 'status' => 'boolean',
    ];

    /**
     * علاقة الصورة (MorphOne).
     * افترض أن جدول الصور وموديل Image موجودان.
     */
    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}
