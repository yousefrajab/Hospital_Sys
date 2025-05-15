<?php // app/Models/Disease.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disease extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'is_chronic'];
    protected $casts = ['is_chronic' => 'boolean'];

    /**
     * سجلات الأمراض المزمنة المرتبطة بهذا المرض (عبر الجدول الوسيط).
     * هذه العلاقة تُظهر جميع حالات تسجيل هذا المرض كمرض مزمن لمختلف المرضى.
     */
    public function patientChronicRecords()
    {
        return $this->hasMany(PatientChronicDisease::class);
    }

    /**
     * المرضى الذين تم تشخيصهم بهذا المرض كمرض مزمن.
     * هذه علاقة belongsToMany مباشرة إذا لم تحتج للوصول لبيانات الجدول الوسيط.
     * ولكن بما أننا أضفنا حقولاً للجدول الوسيط، فالأفضل استخدام العلاقة أعلاه
     * أو تعريف belongsToMany مع withPivot.
     */
    public function patients()
    {
        return $this->belongsToMany(Patient::class, 'patient_chronic_diseases')
                    ->withPivot('diagnosed_at', 'diagnosed_by', 'current_status', 'treatment_plan', 'notes')
                    ->withTimestamps();
    }
}
