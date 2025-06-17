<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'patient_id',       //  <--- *** إضافة هذا السطر إذا لم يكن موجودًا ***
        'patient_name',     // سيبقى هذا لتخزين اسم المريض كما أدخله (أو كما تم جلبه عند إنشاء التعليق)
        'comment',
        'status',
        'approved_at',
        // 'patient_designation', //  أضف هذا إذا قررت إضافة حقل لوصف المريض
        // 'image_path',          //  أضف هذا إذا قررت إضافة حقل لصورة مخصصة للتعليق نفسه (غير صورة المريض)
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'approved_at' => 'datetime',
    ];

    /**
     * Scope a query to only include approved testimonials.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Get the patient that owns the testimonial.
     * (This assumes you have a patient_id foreign key in your testimonials table)
     */
    public function patient()
    {
        // تأكد من أن Namespace لموديل Patient صحيح
        return $this->belongsTo(Patient::class, 'patient_id');
    }
}
