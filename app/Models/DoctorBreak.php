<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorBreak extends Model
{
    use HasFactory;

    protected $table = 'doctor_breaks'; // تأكد من تطابق اسم الجدول

    protected $fillable = [
        'doctor_working_day_id',
        'start_time',
        'end_time',
        'reason',
    ];

    // --- العلاقات ---

    public function workingDay()
    {
        return $this->belongsTo(DoctorWorkingDay::class, 'doctor_working_day_id');
    }
}
