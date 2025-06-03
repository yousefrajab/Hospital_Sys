<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VitalSign extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_admission_id',
        // 'patient_id',
        'recorded_at',
        'temperature',
        'systolic_bp',
        'diastolic_bp',
        'heart_rate',
        'respiratory_rate',
        'oxygen_saturation',
        'pain_level',
        'recorded_by_user_id',
        'notes',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
        'temperature' => 'decimal:1',
        'oxygen_saturation' => 'decimal:1',
    ];

    public function patientAdmission()
    {
        return $this->belongsTo(PatientAdmission::class);
    }

    public function patient()
    {
        // للوصول للمريض مباشرة عبر سجل الدخول
        return $this->hasOneThrough(Patient::class, PatientAdmission::class, 'id', 'id', 'patient_admission_id', 'patient_id');
    }

    public function recordedBy()
    {
        // افترض أن لديك نموذج User لموظفي المستشفى
        return $this->belongsTo(User::class, 'recorded_by_user_id');
    }

    // Accessor لعرض ضغط الدم بشكل مجمع
    public function getBloodPressureAttribute(): ?string
    {
        if ($this->systolic_bp && $this->diastolic_bp) {
            return $this->systolic_bp . '/' . $this->diastolic_bp . ' mmHg';
        }
        return null;
    }
}
