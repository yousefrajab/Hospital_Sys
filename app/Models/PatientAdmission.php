<?php // app/Models/PatientAdmission.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientAdmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'bed_id',
        'doctor_id',
        'section_id',
        'admission_date',
        'discharge_date',
        'reason_for_admission',
        'discharge_reason',
        'admitting_diagnosis',
        'discharge_diagnosis',
        'status',
        'notes',
    ];

    protected $casts = [
        'admission_date' => 'datetime',
        'discharge_date' => 'datetime',
    ];

    public const STATUS_ADMITTED = 'admitted';
    public const STATUS_DISCHARGED = 'discharged';
    public const STATUS_TRANSFERRED_OUT = 'transferred_out';
    public const STATUS_TRANSFERRED_IN = 'transferred_in';
    public const STATUS_CANCELLED = 'cancelled';


    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function bed()
    {
        return $this->belongsTo(Bed::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * عند إنشاء أو تحديث أو حذف سجل دخول، قم بتحديث حالة السرير والغرفة.
     */
    protected static function booted()
    {
        static::saved(function (PatientAdmission $admission) {
            if ($admission->bed) {
                // إذا تم تغيير السرير أو حالة الدخول، أعد حساب حالة السرير ثم حالة الغرفة
                $admission->bed->status = ($admission->status === self::STATUS_ADMITTED && !$admission->discharge_date)
                    ? Bed::STATUS_OCCUPIED
                    : Bed::STATUS_AVAILABLE;
                $admission->bed->saveQuietly(); // لمنع حلقة لا نهائية إذا كان BedObserver يستمع لـ save
                // بعد حفظ السرير، سيقوم BedObserver بتحديث حالة الغرفة
            }

            // إذا كان هناك سرير قديم وتم تغيير السرير
            if ($admission->isDirty('bed_id') && $admission->getOriginal('bed_id')) {
                $oldBed = Bed::find($admission->getOriginal('bed_id'));
                if ($oldBed) {
                    $oldBed->status = Bed::STATUS_AVAILABLE;
                    $oldBed->saveQuietly();
                    // بعد حفظ السرير القديم، سيقوم BedObserver بتحديث حالة الغرفة القديمة
                }
            }
        });

        static::deleted(function (PatientAdmission $admission) {
            if ($admission->bed) {
                $admission->bed->status = Bed::STATUS_AVAILABLE;
                $admission->bed->saveQuietly();
                // بعد حفظ السرير، سيقوم BedObserver بتحديث حالة الغرفة
            }
        });
    }

    public static function getAdmissionStatusText($status)
    {
        $statuses = [
            self::STATUS_ADMITTED => 'مقيم',
            self::STATUS_DISCHARGED => 'خروج',
            // Add other statuses here
        ];
        return $statuses[$status] ?? 'غير معروف';
    }
}
