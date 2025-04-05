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
    public $fillable = ['email', 'Password', 'Date_Birth', 'Phone', 'Gender', 'Blood_Group'];

    public function doctor()
    {
        return $this->belongsTo(Invoice::class, 'doctor_id');
    }

    public function service()
    {
        return $this->belongsTo(Invoice::class, 'Service_id');
    }

    // public function image()
    // {
    //     return $this->hasOne(Image::class); // أو MorphOne إذا كنت تستخدم Polymorphic
    // }

    // أضف accessor لتحويل المصفوفات تلقائياً
    // public function getNameAttribute($value)
    // {
    //     return is_array($value) ? $value[0] : $value;
    // }
}
