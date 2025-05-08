<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_email',
        'receiver_email',
        'last_time_message',
        'doctor_id',
        'patient_id',
    ];

    // تحويل التاريخ (مهم للفرز)
    protected $casts = [
        'last_time_message' => 'datetime',
    ];

    // --- علاقة الطبيب --- (تأكد من صحة المفاتيح الأجنبية)
    public function doctor()
    {
        // إذا كنت تستخدم doctor_id:
        // return $this->belongsTo(Doctor::class, 'doctor_id');
        // إذا كنت ما زلت تعتمد على الإيميل (أقل كفاءة):
        return $this->belongsTo(Doctor::class, 'receiver_email', 'email'); // افترض أن الطبيب هو المستقبل
    }

    // --- علاقة المريض --- (تأكد من صحة المفاتيح الأجنبية)
    public function patient()
    {
        // إذا كنت تستخدم patient_id:
        // return $this->belongsTo(Patient::class, 'patient_id');
        // إذا كنت ما زلت تعتمد على الإيميل:
        return $this->belongsTo(Patient::class, 'sender_email', 'email'); // افترض أن المريض هو المرسل
    }

    // --- علاقة الرسائل ---
    public function messages()
    {
        return $this->hasMany(Message::class, 'conversation_id');
    }

    // ===>>> تعديل علاقة lastMessage <<<===
    /**
     * الحصول على آخر رسالة في المحادثة.
     * استخدام hasOne مع latest() أبسط وأكثر توافقية غالبًا.
     */
    public function lastMessage()
    {
        // 'created_at' هو العمود الافتراضي لـ latest()
        // إذا كان هناك عمود آخر للترتيب استخدمه ->latest('column_name')
        return $this->hasOne(Message::class, 'conversation_id')->latest();
    }

    // --- (اختياري) علاقة المرسل والمستقبل (إذا كانت الحقول sender_email/receiver_email ثابتة) ---
    // public function sender() { // قد يكون طبيب أو مريض }
    // public function receiver() { // قد يكون طبيب أو مريض }


    // --- Scope للبحث عن المحادثة (موجود في الكود الأصلي) ---
    public function scopeChekConversation($query, $sender_email, $receiver_email)
    {
        return $query->where(function ($q) use ($sender_email, $receiver_email) {
            $q->where('sender_email', $sender_email)->where('receiver_email', $receiver_email);
        })->orWhere(function ($q) use ($sender_email, $receiver_email) {
            $q->where('sender_email', $receiver_email)->where('receiver_email', $sender_email);
        });
    }

}
