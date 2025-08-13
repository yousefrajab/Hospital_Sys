<?php

namespace App\Broadcasting;

use App\Models\Notification as CustomNotificationModel;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class YourCustomDbChannel
{

    public function send($notifiable, Notification $notification)
    {

        if (!method_exists($notification, 'getMessageInstance') || !method_exists($notification, 'getSenderName')) {
            Log::warning("[YourCustomDbChannel] Notification does not have expected methods (getMessageInstance/getSenderName). Skipping custom DB store.", ['notification_class' => get_class($notification)]);
            return;
        }

        $messageInstance = $notification->getMessageInstance();
        $senderName = $notification->getSenderName();

        if (!$notifiable || !$notifiable->id || !$messageInstance) {
            Log::error("[YourCustomDbChannel] Missing notifiable, notifiable ID, or message instance. Cannot create custom notification.", [
                'notifiable_id' => $notifiable->id ?? null,
                'message_id' => $messageInstance->id ?? null,
            ]);
            return;
        }

        $notificationText = "رسالة جديدة من {$senderName}: \"" . Str::limit($messageInstance->body, 45) . "\"";

        try {
            CustomNotificationModel::create([
                'user_id' => $notifiable->id,
                'message' => $notificationText,
                'reader_status' => false,

            ]);
            Log::info("[YourCustomDbChannel] Custom notification created successfully for User ID: {$notifiable->id} regarding message ID: {$messageInstance->id}.");
        } catch (\Exception $e) {
            Log::error("[YourCustomDbChannel] FAILED to create custom notification for User ID: {$notifiable->id}. Error: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
        }
    }
}
