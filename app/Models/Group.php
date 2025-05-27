<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use Translatable;
    use HasFactory;

    public $translatedAttributes = ['name', 'notes'];
    // الحقول الموجودة مباشرة في جدول 'groups'
    public $fillable = [
        'Total_before_discount',
        'discount_value',
        'Total_after_discount',
        'tax_rate',
        'Total_with_tax'
      
    ];

    /**
     * الخدمات المتضمنة في هذه المجموعة.
     */
    public function service_group()
    {
        // service_group هو اسم الجدول الوسيط (pivot table)
        // إذا كان اسم الجدول الوسيط مختلفًا، قم بتعديله هنا.
        // 'group_id' و 'service_id' هي المفاتيح الأجنبية الافتراضية، عدلها إذا كانت مختلفة.
        return $this->belongsToMany(Service::class, 'service_group', 'group_id', 'service_id')
            ->withPivot('quantity') // لجلب الكمية من الجدول الوسيط
            ->withTimestamps(); // إذا كان الجدول الوسيط يحتوي على created_at و updated_at
    }

    // يمكنك إضافة Accessor لعرض الطبيب/القسم إذا كانت المجموعة مرتبطة بطبيب/قسم معين
    // هذا يتطلب وجود علاقات إضافية في موديل Group (مثلاً doctor_id, section_id)
    /*
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
    */
}
