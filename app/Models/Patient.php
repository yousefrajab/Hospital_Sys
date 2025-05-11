<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Notifications\PatientResetPasswordNotification; // تأكد من وجود هذا الملف
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use Illuminate\Notifications\Notifiable;

class Patient extends Authenticatable implements TranslatableContract, CanResetPassword
{
    use HasFactory, Notifiable, Translatable, CanResetPasswordTrait;

    public $translatedAttributes = ['name', 'Address'];

    public $fillable = [
        'national_id',
        // 'name', // يتم التعامل معه بواسطة Translatable
        'email',
        'password',
        'Date_Birth',
        'Phone',
        'Gender',
        'Blood_Group',
        // 'Address' // يتم التعامل معه بواسطة Translatable
    ];

    // ** إضافة حقل 'name' و 'Address' إلى fillable إذا كنت ستنشئ/تحدثهم مباشرة أحيانًا **
    // ** بدون استخدام setTranslation بشكل صريح لكل لغة. **
    // ** إذا كنت دائمًا تستخدم setTranslation، فهذه ليست ضرورية. **
    // ** ولكن بما أنك في Controller كنت تستخدم $patient->name = $request->name، فمن الأفضل إضافتهم هنا **
    // protected $fillable = [
    //     'national_id',
    //     'name', // للاسم المترجم
    //     'email',
    //     'password',
    //     'Date_Birth',
    //     'Phone',
    //     'Gender',
    //     'Blood_Group',
    //     'Address', // للعنوان المترجم
    // ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'Date_Birth' => 'date',
        'password' => 'hashed', // ** تم إضافة هذا لـ Laravel 9+ لتشفير كلمة المرور تلقائيًا **
        // ** إذا كنت تستخدم إصدارًا أقدم، قم بإزالته وتشفير كلمة المرور يدويًا **
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function Service() // اسم الدالة مفرد (Service)
    {
        return $this->belongsTo(Service::class, 'Service_id');
    }

    // هذه العلاقات قد تحتاج لمراجعة منطقها كما ذكرت سابقًا
    public function employee() // افترض أن هذا يعني موظف أشعة
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

    // =========================================================================
    // ***** الدوال الجديدة المضافة هنا *****
    // =========================================================================

    /**
     * جميع سجلات دخول هذا المريض.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function admissions()
    {
        return $this->hasMany(PatientAdmission::class);
    }

    /**
     * سجل الدخول الحالي النشط لهذا المريض.
     * سجل نشط يعني أنه لم يتم تسجيل خروجه بعد وحالته 'admitted'.
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function currentAdmission()
    {
        return $this->hasOne(PatientAdmission::class)
            ->whereNull('discharge_date') // لم يتم تحديد تاريخ خروج
            ->where('status', PatientAdmission::STATUS_ADMITTED); // الحالة هي 'admitted'
    }

    /**
     * السرير الذي يشغله المريض حاليًا (عبر currentAdmission).
     * هذه علاقة مساعدة إذا أردت الوصول للسرير مباشرة من المريض.
     * @return \Illuminate\Database\Eloquent\Relations\HasOneThrough
     */
    public function currentBed()
    {
        return $this->hasOneThrough(
            Bed::class,                     // الموديل النهائي الذي نريد الوصول إليه (Bed)
            PatientAdmission::class,        // الموديل الوسيط (PatientAdmission)
            'patient_id',                   // المفتاح الأجنبي في جدول PatientAdmission الذي يشير إلى Patient (هذا الموديل)
            'id',                           // المفتاح المحلي في جدول Bed (الذي يطابق bed_id في PatientAdmission)
            'id',                           // المفتاح المحلي في جدول Patient (هذا الموديل)
            'bed_id'                        // المفتاح الأجنبي في جدول PatientAdmission الذي يشير إلى Bed
        )->whereNull('patient_admissions.discharge_date') // شرط على الجدول الوسيط
            ->where('patient_admissions.status', PatientAdmission::STATUS_ADMITTED); // شرط آخر على الجدول الوسيط
    }

    // =========================================================================
    // ***** نهاية الدوال الجديدة المضافة *****
    // =========================================================================

    public function sendPasswordResetNotification($token)
    {
        // تأكد من أن كلاس PatientResetPasswordNotification موجود في المسار الصحيح
        // App\Notifications\PatientResetPasswordNotification
        $this->notify(new PatientResetPasswordNotification($token));
    }
}
