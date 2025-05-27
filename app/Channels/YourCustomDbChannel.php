<?php

namespace App\Channels; // تأكد أن الـ namespace صحيح

use App\Models\Notification as CustomNotificationModel; // موديل Notification الخاص بك
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str; // لإستخدام Str::limit

class YourCustomDbChannel
{
   
    public function send($notifiable, Notification $notification)
    {
        // 1. التأكد أن $notification هو من النوع الذي نريد معالجته (NewChatMessageNotification)
        // وأن لديه البيانات التي نحتاجها. هذا اختياري ولكنه جيد للتحقق.
        if (!method_exists($notification, 'getMessageInstance') || !method_exists($notification, 'getSenderName')) {
            Log::warning("[YourCustomDbChannel] Notification does not have expected methods (getMessageInstance/getSenderName). Skipping custom DB store.", ['notification_class' => get_class($notification)]);
            return;
        }

        $messageInstance = $notification->getMessageInstance(); // دالة helper سنضيفها لـ NewChatMessageNotification
        $senderName = $notification->getSenderName();           // دالة helper سنضيفها لـ NewChatMessageNotification

        // 2. التأكد أن لدينا $notifiable و $messageInstance
        if (!$notifiable || !$notifiable->id || !$messageInstance) {
            Log::error("[YourCustomDbChannel] Missing notifiable, notifiable ID, or message instance. Cannot create custom notification.", [
                'notifiable_id' => $notifiable->id ?? null,
                'message_id' => $messageInstance->id ?? null,
            ]);
            return;
        }

        // 3. بناء نص رسالة الإشعار
        $notificationText = "رسالة جديدة من {$senderName}: \"" . Str::limit($messageInstance->body, 45) . "\"";

        // 4. إنشاء سجل في جدول `notifications` المخصص لك
        try {
            CustomNotificationModel::create([
                'user_id' => $notifiable->id, // ID المستخدم المستقبِل للإشعار
                'message' => $notificationText,
                'reader_status' => false, // أو 0، حسب تعريف عمودك
                // يمكنك إضافة أعمدة أخرى هنا إذا كان جدولك يحتوي عليها:
                // 'type' => 'new_chat_message', // نوع الإشعار
                // 'related_id' => $messageInstance->conversation_id, // ربط الإشعار بالمحادثة
                // 'link' => $notification->getActionUrl($notifiable) // إذا كان getActionUrl متاحاً
            ]);
            Log::info("[YourCustomDbChannel] Custom notification created successfully for User ID: {$notifiable->id} regarding message ID: {$messageInstance->id}.");
        } catch (\Exception $e) {
            Log::error("[YourCustomDbChannel] FAILED to create custom notification for User ID: {$notifiable->id}. Error: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
        }
    }
}
