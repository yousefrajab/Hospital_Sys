<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    // تأكد من أن 'appointment' موجود هنا إذا كنت ستعتمد عليه
    protected $fillable = [
        'name',
        'email',
        'phone',
        'notes',
        'doctor_id',
        'section_id',
        'type',
        'appointment', // هذا سيحمل التاريخ والوقت المختار
        // 'appointment_patient', // لن نستخدمه في المنطق الجديد
        'patient_id' // تأكد من إضافته إذا كان موجوداً في المكون
    ];

    // تحويل حقل الوقت إلى كائن Carbon تلقائياً
    protected $casts = [
        'appointment' => 'datetime',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    // في Appointment.php
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id'); // استخدام user_id كمفتاح خارجي
    }
}
