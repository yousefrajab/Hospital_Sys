<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disease extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_chronic',
    ];

    protected $casts = [
        'is_chronic' => 'boolean',
    ];

    /**
     * المرضى الذين لديهم هذا المرض.
     * علاقة ManyToMany مع Patient.
     */
    public function patients()
    {
        // اسم الجدول الوسيط 'disease_patient' (سننشئه لاحقًا)
        // المفاتيح الأجنبية 'disease_id' و 'patient_id'
        return $this->belongsToMany(Patient::class, 'disease_patient');
    }
}
