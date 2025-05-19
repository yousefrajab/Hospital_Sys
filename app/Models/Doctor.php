<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Astrotomic\Translatable\Translatable;
use App\Notifications\DoctorResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;

class Doctor extends Authenticatable implements TranslatableContract, CanResetPassword
{
    use HasFactory, Notifiable, Translatable, CanResetPasswordTrait;

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
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'status' => 'boolean',
    ];

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    public function workingDays()
    {
        return $this->hasMany(DoctorWorkingDay::class, 'doctor_id');
    }

    public function doctorappointments()
    {
        return $this->belongsToMany(Appointment::class,'appointment_doctor');
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new DoctorResetPasswordNotification($token));
    }


    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }


    public function PrescriptionItem()
    {
        return $this->belongsTo(PrescriptionItem::class);
    }
}
