<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use App\Notifications\AdminResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait; // هذا قد لا يكون ضروريًا إذا كان Authenticatable يعالجه

class Admin extends Authenticatable implements CanResetPassword
{
    use HasFactory, Notifiable, CanResetPasswordTrait; // CanResetPasswordTrait قد لا يكون مطلوبًا بشكل صريح في L8

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
        // ** تم إزالة 'password' => 'hashed', من هنا **
    ];

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new AdminResetPasswordNotification($token));
    }

    // ** مهم: إذا كنت لا تستخدم 'hashed' cast، ستحتاج إلى mutator لتشفير كلمة المرور تلقائيًا عند التعيين **
    // أو تقوم بتشفيرها يدويًا في كل مكان تقوم فيه بتعيين كلمة المرور
    public function setPasswordAttribute($value)
    {
        // قم بالتشفير فقط إذا لم تكن القيمة مشفرة بالفعل (لتجنب التشفير المزدوج)
        // وإذا كانت القيمة ليست null (لتجنب تشفير null عند تحديث حقول أخرى)
        if ($value !== null && !empty($value)) {
             // لا يوجد طريقة مضمونة 100% لمعرفة إذا كانت مشفرة بدون محاولة فكها أو مقارنتها
             // لذا، الطريقة الأكثر أمانًا هي التشفير دائمًا عند التعيين إذا لم تكن متأكدًا.
             // لكن هذا قد يسبب مشاكل إذا قمت بحفظ الموديل عدة مرات بدون تغيير كلمة المرور.
             // Laravel 8 لا يحتوي على Hash::needsRehash() افتراضيًا بدون الـ cast.
             // الحل الأبسط هو تشفيرها في الـ Controllers/Repositories عند التعيين.
             // أو إذا أردت mutator، يجب أن تكون حذرًا.
             // للتبسيط الآن، سنعتمد على التشفير في الـ Controller.
             // إذا أردت mutator:
            // $this->attributes['password'] = bcrypt($value);
            // ولكن هذا سيشفرها في كل مرة يتم فيها حفظ الموديل إذا تم تمرير قيمة password
            // حتى لو لم تتغير، مما قد يكون غير ضروري.
            // سأتركها مشفرة في الـ Controller ليكون أوضح.
            $this->attributes['password'] = $value; // قم بتعيينها كما هي، وقم بالتشفير في الـ Controller
        }
    }
}
