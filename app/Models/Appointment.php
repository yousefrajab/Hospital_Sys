<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;


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

    public const STATUS_PENDING   = 'غير مؤكد';
    public const STATUS_CONFIRMED = 'مؤكد';
    public const STATUS_COMPLETED = 'منتهي';
    public const STATUS_CANCELLED = 'ملغي'; // حالة إلغاء عامة، يمكنك تفصيلها إذا أردت

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
        return $this->belongsTo(Patient::class, 'patient_id');
    }


    public function getStatusDisplayAttribute(): string
    {
        // يمكنك إضافة المزيد من الحالات هنا إذا لزم الأمر
        return match ($this->type) {
            self::STATUS_PENDING => 'بانتظار التأكيد',
            self::STATUS_CONFIRMED => 'مؤكد',
            self::STATUS_COMPLETED => 'مكتمل',
            self::STATUS_CANCELLED => 'ملغى',
            default => $this->type, // إرجاع القيمة كما هي إذا لم تطابق
        };
    }
}
