<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorWorkingDay extends Model
{
    use HasFactory;

    // اسم الجدول (إذا كان يتبع الاصطلاح، هذا السطر ليس ضرورياً)
    protected $table = 'doctor_working_days';

    // الحقول القابلة للتعبئة
    protected $fillable = [
        'doctor_id',
        'day', // e.g., 'Saturday', 'Sunday', etc.
        'start_time', // e.g., '09:00:00'
        'end_time', // e.g., '17:00:00'
        'appointment_duration', // e.g., 30 (minutes)
        'active', // boolean
    ];

    // تحويل أنواع البيانات
    protected $casts = [
        'active' => 'boolean',
        'start_time' => 'datetime:H:i:s', // أو 'datetime:H:i' إذا لم تخزن الثواني
        'end_time' => 'datetime:H:i:s',   // أو 'datetime:H:i'
        'appointment_duration' => 'integer',
    ];


    /**
     * Get the doctor that this working day belongs to (علاقة BelongsTo).
     */
    public function doctor()
    {
        // افترض أن المفتاح الخارجي هو 'doctor_id'
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    /**
     * Get all breaks for this working day (علاقة HasMany).
     */
    public function breaks()
    {
        // افترض أن المفتاح الخارجي في جدول doctor_breaks هو 'doctor_working_day_id'
        return $this->hasMany(DoctorBreak::class, 'doctor_working_day_id');
    }
}
