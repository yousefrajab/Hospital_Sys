<?php // app/Models/Room.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id',
        'room_number',
        'type',
        'gender_type',
        'floor',
        'status',
        'notes',
    ];

    // تعريف قيم Enum لتسهيل الاستخدام والتحقق
    public const TYPE_PATIENT_ROOM = 'patient_room';
    public const TYPE_PRIVATE_ROOM = 'private_room';
    public const TYPE_SEMI_PRIVATE_ROOM = 'semi_private_room';
    public const TYPE_ICU_ROOM = 'icu_room';
    public const TYPE_EXAMINATION_ROOM = 'examination_room';
    public const TYPE_CONSULTATION_ROOM = 'consultation_room';
    public const TYPE_TREATMENT_ROOM = 'treatment_room';
    public const TYPE_OPERATING_ROOM = 'operating_room';
    public const TYPE_RADIOLOGY_ROOM = 'radiology_room';
    public const TYPE_LABORATORY_ROOM = 'laboratory_room';
    public const TYPE_OFFICE = 'office';
    public const TYPE_OTHER = 'other';

    public const GENDER_MALE = 'male';
    public const GENDER_FEMALE = 'female';
    public const GENDER_MIXED = 'mixed';
    public const GENDER_ANY = 'any';

    public const STATUS_AVAILABLE = 'available';
    public const STATUS_PARTIALLY_OCCUPIED = 'partially_occupied';
    public const STATUS_FULLY_OCCUPIED = 'fully_occupied';
    public const STATUS_OUT_OF_SERVICE = 'out_of_service';
    // يمكنك إضافة ثوابت للحالات المعلقة (maintenance, cleaning, reserved) إذا أضفتها للـ enum

    /**
     * القسم الذي تنتمي إليه هذه الغرفة.
     */
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    /**
     * الأسرة الموجودة في هذه الغرفة.
     */
    public function beds()
    {
        return $this->hasMany(Bed::class);
    }

    /**
     * دالة لتحديث حالة إشغال الغرفة بناءً على حالة أسرتها.
     * تُستدعى هذه الدالة عادةً من BedObserver.
     */
    public function updateOccupancyStatus(): void
    {
        // لا تقم بتحديث حالة الإشغال للغرف التي لا تحتوي على أسرة مرضى تُدار بهذه الطريقة
        // أو إذا كانت الغرفة خارج الخدمة حاليًا
        if (!in_array($this->type, [
                self::TYPE_PATIENT_ROOM, self::TYPE_PRIVATE_ROOM,
                self::TYPE_SEMI_PRIVATE_ROOM, self::TYPE_ICU_ROOM
            ]) || $this->status === self::STATUS_OUT_OF_SERVICE) {
            return;
        }

        $totalBeds = $this->beds()->count();
        // إذا لم يكن هناك أسرة في غرفة مريض (وهذا قد يشير إلى خطأ في البيانات)،
        // يمكن اعتبارها متاحة أو أي حالة تراها مناسبة.
        if ($totalBeds === 0) {
            if ($this->status !== self::STATUS_AVAILABLE) {
                $this->status = self::STATUS_AVAILABLE;
                $this->saveQuietly(); // استخدام saveQuietly لتجنب تشغيل Observers بشكل متكرر إذا لزم الأمر
            }
            return;
        }

        $occupiedBedsCount = $this->beds()->where('status', Bed::STATUS_OCCUPIED)->count();

        $newStatus = self::STATUS_AVAILABLE; // الحالة الافتراضية
        if ($occupiedBedsCount === $totalBeds) {
            $newStatus = self::STATUS_FULLY_OCCUPIED;
        } elseif ($occupiedBedsCount > 0) {
            $newStatus = self::STATUS_PARTIALLY_OCCUPIED;
        }

        if ($this->status !== $newStatus) {
            $this->status = $newStatus;
            $this->saveQuietly();
        }
    }
}
