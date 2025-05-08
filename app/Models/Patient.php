<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Patient extends Authenticatable
{
    use Translatable;
    use HasFactory;
    public $translatedAttributes = ['name', 'Address'];
    public $fillable = ['national_id','name', 'email', 'password', 'Date_Birth', 'Phone', 'Gender', 'Blood_Group'];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function Service()
    {
        return $this->belongsTo(Service::class, 'Service_id');
    }

    public function employee()
    {
        return $this->belongsTo(RayEmployee::class, 'RayEmployee_id');
    }

    public function lab_employee()
    {
        return $this->belongsTo(LaboratorieEmployee::class, 'LaboratorieEmployee_id');
    }
    // علاقة الصورة MorphOne
    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function diseases()
    {
        // اسم الجدول الوسيط 'disease_patient'
        // المفاتيح الأجنبية 'patient_id' و 'disease_id'
        return $this->belongsToMany(Disease::class, 'disease_patient')
            ->withTimestamps(); // (اختياري) لتتبع متى تم الربط
        // ->withPivot('notes', 'diagnosed_at', 'is_active'); // (اختياري) لجلب الحقول الإضافية من الجدول الوسيط
    }
}
