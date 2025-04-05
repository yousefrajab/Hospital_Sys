<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function scopechekConversation($query, $auth_email, $receiver_email)
    {
        return $query->where('sender_email', $auth_email)
            ->where('receiver_email', $receiver_email)->orwhere('receiver_email', $auth_email)->where('sender_email', $receiver_email);
    }

    public function messages()
    {

        return $this->hasMany(Message::class);
    }

    public function image()
    {
        return $this->belongsTo(Image::class); // أو أي علاقة مناسبة
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'receiver_email', 'email');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'sender_email', 'email');
    }



    public function lastMessage()
{
    return $this->hasOne(Message::class)->latestOfMany('created_at');
}
}
