<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon; // استيراد Carbon للـ Accessor

class Message extends Model
{
    use HasFactory;

    /**
     * الحقول القابلة للتعبئة الجماعية.
     * استخدام fillable أفضل من guarded فارغة.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'conversation_id',
        'sender_email',
        'receiver_email',
        'body',
        'type', // تم إضافته لأنه موجود في الـ Migration
        'read', // <<< التأكد من وجود read هنا (إذا كنت ستستخدم update)
    ];

    /**
     * الحقول التي يجب تحويل أنواعها.
     * مهم للتعامل مع read كـ boolean.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'read' => 'boolean', // <<< التعامل مع read كقيمة منطقية (true/false أو 1/0)
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * علاقة مع المحادثة.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function conversation()
    {
        return $this->belongsTo(Conversation::class, 'conversation_id');
    }

    // --- Accessors ---

    /**
     * Accessor للحصول على تاريخ الإنشاء بصيغة مقروءة.
     *
     * @return string|null
     */
    public function getFormattedCreatedAtAttribute(): ?string
    {
        return optional($this->created_at)->locale('ar')->shortAbsoluteDiffForHumans();
    }

    /**
     * Accessor للتحقق مما إذا كانت الرسالة قد قُرئت (يعتمد على حقل read).
     * الاسم is_read متوافق مع الـ cast boolean.
     *
     * @return bool
     */
    public function getIsReadAttribute(): bool
    {
        // سيعيد true إذا كانت قيمة read هي 1 أو true، و false إذا كانت 0 أو false أو null
        return (bool) $this->read;
    }
}
