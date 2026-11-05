<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * In-app notification for any role (custom table, not Laravel's notifications).
 * A recipient is identified by user_id + user_type (the recipient's model class).
 */
class Notification extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'user_type', 'message', 'reader_status'];

    protected $casts = [
        'reader_status' => 'boolean',
    ];

    public static function send(Model $recipient, string $message): self
    {
        return static::create([
            'user_id' => $recipient->getKey(),
            'user_type' => get_class($recipient),
            'message' => $message,
            'reader_status' => false,
        ]);
    }

    public function scopeForUser($query, Model $user)
    {
        return $query->where('user_id', $user->getKey())->where('user_type', get_class($user));
    }

    public function scopeUnread($query)
    {
        return $query->where('reader_status', false);
    }
}
