<?php

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

    // علاقات الموديل
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

    public function vitalSigns()
    {
        return $this->hasMany(VitalSign::class)->orderBy('recorded_at', 'desc');
    }

    // دوال التحقق من الحالة
    public function isAdmitted(): bool
    {
        return $this->status === self::STATUS_ADMITTED && !$this->discharge_date;
    }

    public function isDischarged(): bool
    {
        return $this->status === self::STATUS_DISCHARGED && $this->discharge_date;
    }

    // دوال مساعدة للحالة
    public function getStatusClassAttribute(): string
    {
        return match($this->status) {
            self::STATUS_ADMITTED => 'success',
            self::STATUS_DISCHARGED => 'danger',
            self::STATUS_CANCELLED => 'warning',
            default => 'secondary'
        };
    }

    public function getStatusIconAttribute(): string
    {
        return match($this->status) {
            self::STATUS_ADMITTED => 'fa-bed',
            self::STATUS_DISCHARGED => 'fa-user-check',
            self::STATUS_CANCELLED => 'fa-times-circle',
            default => 'fa-question-circle'
        };
    }

    public function getStatusDisplayAttribute(): string
    {
        return self::getAllStatusesArray()[$this->status] ?? $this->status;
    }

    // دوال ثابتة
    public static function getAdmissionStatusText($status): string
    {
        return self::getAllStatusesArray()[$status] ?? 'غير معروف';
    }

    public static function getAllStatusesArray(): array
    {
        return [
            self::STATUS_ADMITTED => 'مقيم حاليًا',
            self::STATUS_DISCHARGED => 'خرج من المستشفى',
            self::STATUS_TRANSFERRED_OUT => 'تم نقله للخارج',
            self::STATUS_TRANSFERRED_IN => 'تم نقله للداخل',
            self::STATUS_CANCELLED => 'ملغى',
        ];
    }

    // Event handlers
    protected static function booted()
    {
        static::saved(function (PatientAdmission $admission) {
            if ($admission->bed) {
                $admission->bed->status = ($admission->isAdmitted())
                    ? Bed::STATUS_OCCUPIED
                    : Bed::STATUS_AVAILABLE;
                $admission->bed->saveQuietly();
            }

            if ($admission->isDirty('bed_id') && $admission->getOriginal('bed_id')) {
                $oldBed = Bed::find($admission->getOriginal('bed_id'));
                if ($oldBed) {
                    $oldBed->status = Bed::STATUS_AVAILABLE;
                    $oldBed->saveQuietly();
                }
            }
        });

        static::deleted(function (PatientAdmission $admission) {
            if ($admission->bed) {
                $admission->bed->status = Bed::STATUS_AVAILABLE;
                $admission->bed->saveQuietly();
            }
        });
    }
}
