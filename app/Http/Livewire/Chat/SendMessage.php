<?php

namespace App\Http\Livewire\Chat;

use App\Models\Conversation;
use App\Models\Doctor;
use App\Models\Message;
use App\Models\Patient;
use App\Models\Notification as CustomNotification; // <-- اسم مستعار
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Events\MassageSent;  // تأكد أن هذا الحدث مُعرّف
use App\Events\MassageSent2; // تأكد أن هذا الحدث مُعرّف
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str; //  <-- لإستخدام Str::limit

class SendMessage extends Component
{
    public string $body = '';
    public ?Conversation $selected_conversation = null;
    public ?object $receviverUser = null; // هذا هو مستقبل الرسالة
    public $auth_email;
    public $sender; // هذا هو مرسل الرسالة
    public ?Message $createdMessage = null;

    protected $listeners = [
        'updateConversationFromList' => 'updateConversation',
        // 'dispatchSentMassage' // لا حاجة لـ listener إذا كانت تُستدعى محلياً
    ];

    public function mount()
    {
        if (Auth::guard('patient')->check()) {
            $this->auth_email = Auth::guard('patient')->user()->email;
            $this->sender = Auth::guard('patient')->user();
        } elseif (Auth::guard('doctor')->check()) {
            $this->auth_email = Auth::guard('doctor')->user()->email;
            $this->sender = Auth::guard('doctor')->user();
        } else {
            Log::error("[SendMessage Mount] User not authenticated.");
        }
    }

    public function updateConversation(int $conversationId, int $receiverId, string $receiverType)
    {
        Log::info("[SendMessage] Updating conversation. ConvID: {$conversationId}, ReceiverID: {$receiverId}, Type: {$receiverType}");
        $this->selected_conversation = Conversation::find($conversationId);
        $receiver = null;

        if ($receiverType === Doctor::class || $receiverType === 'App\\Models\\Doctor') {
            $receiver = Doctor::find($receiverId);
        } elseif ($receiverType === Patient::class || $receiverType === 'App\\Models\\Patient') {
            $receiver = Patient::find($receiverId);
        }

        if ($this->selected_conversation && $receiver) {
            $this->receviverUser = $receiver;
            $this->reset('body');
        } else {
            Log::error("[SendMessage] Failed to update conversation/receiver.");
            $this->selected_conversation = null;
            $this->receviverUser = null;
            $this->reset('body');
        }
    }

    public function sendMessage()
    {
        if (!$this->selected_conversation || !$this->receviverUser || empty(trim($this->body))) {
            return;
        }
        $this->validate(['body' => 'required|string|max:5000']);

        DB::beginTransaction();
        try {
            $this->createdMessage = Message::create([
                'conversation_id' => $this->selected_conversation->id,
                'sender_email' => $this->auth_email,
                'receiver_email' => $this->receviverUser->email,
                'body' => $this->body,
                'read' => false // الرسالة جديدة
            ]);
            $this->selected_conversation->last_time_message = $this->createdMessage->created_at;
            $this->selected_conversation->save();

            DB::commit();

            $this->reset('body');
            Log::info("[SendMessage] Message ID {$this->createdMessage->id} created. Broadcasting and notifying.");
            $this->emitTo('chat.chatbox', 'pushMessage', $this->createdMessage->id);
            $this->emitTo('chat.chatlist', 'refreshComponent');

            // --- استدعاء دالة الإشعارات والأحداث ---
            $this->dispatchNotificationsAndEventsAfterSend($this->createdMessage, $this->sender, $this->receviverUser);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("[SendMessage] Failed to send message: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $this->dispatchBrowserEvent('alert', ['type' => 'error', 'message' => 'فشل إرسال الرسالة.']);
        }
    }

    /**
     * دالة مخصصة لإرسال الإشعارات والأحداث بعد إرسال الرسالة بنجاح.
     */
    protected function dispatchNotificationsAndEventsAfterSend(Message $message, $senderObject, $receiverObject)
    {
        if (!$message || !$senderObject || !$receiverObject) {
            Log::error("[dispatchNotificationsAndEventsAfterSend] Missing critical data.", ['message_id' => $message->id ?? null, 'sender' => $senderObject->id ?? null, 'receiver' => $receiverObject->id ?? null]);
            return;
        }

        $senderDisplayName = $senderObject->name;
        $senderDisplayAvatar = $senderObject->image ? asset('Dashboard/img/' . ($senderObject instanceof Patient ? 'patients' : 'doctors') . '/' . $senderObject->image->filename) : null;

        // 1. إرسال إشعار Laravel (للبث) إلى المستقبِل
        try {
            $receiverObject->notify(new \App\Notifications\NewChatMessageNotification($message, $senderDisplayName, $senderDisplayAvatar));
            Log::info("[SendMessage] Laravel Notification sent to Receiver ID: {$receiverObject->id} for message ID: {$message->id}");
        } catch (\Exception $e) {
            Log::error("[SendMessage] Failed to send Laravel Notification: " . $e->getMessage());
        }

        // 2. *** إنشاء سجل في جدول notifications المخصص لك للمستقبِل ***
        try {
            CustomNotification::create([
                'user_id' => $receiverObject->id, // ID المستقبِل
                'message' => "رسالة جديدة من {$senderDisplayName}: \"" . Str::limit($message->body, 45) . "\"",
                'reader_status' => false,
                // 'data' => json_encode(['conversation_id' => $message->conversation_id, 'message_id' => $message->id, 'link' => ...])
            ]);
            Log::info("[SendMessage] Custom App\\Models\\Notification record CREATED for Receiver ID: {$receiverObject->id} for message ID: {$message->id}.");
        } catch (\Exception $e) {
            Log::error("[SendMessage] FAILED to create Custom App\\Models\\Notification for Receiver ID: {$receiverObject->id}. Error: " . $e->getMessage());
        }

        // 3. بث الحدث عبر Echo (إذا كان هذا جزء من نظامك الحالي)
        try {
            if ($senderObject instanceof Patient && $receiverObject instanceof Doctor) {
                 Log::info("[SendMessage] Broadcasting MassageSent event.");
                broadcast(new MassageSent($senderObject, $message, $this->selected_conversation, $receiverObject))->toOthers();
            } elseif ($senderObject instanceof Doctor && $receiverObject instanceof Patient) {
                Log::info("[SendMessage] Broadcasting MassageSent2 event.");
                broadcast(new MassageSent2($senderObject, $message, $this->selected_conversation, $receiverObject))->toOthers();
            }
        } catch (\Exception $e) {
            Log::error("[SendMessage] Failed to broadcast Echo event: " . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.chat.send-message');
    }
}
