<?php

namespace App\Http\Livewire\Chat;

use App\Models\Conversation;
use App\Models\Doctor;
use App\Models\Message;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class Chatbox extends Component
{
    // الخصائص (تبقى كما هي)
    public ?Conversation $selected_conversation = null;
    public ?object $receviverUser = null;
    public ?Collection $messages = null;
    public $auth_email;
    public $auth_id;
    public $event_name;
    public $chat_page;

    /**
     * تحديد الـ listeners (يبقى كما هو).
     */
    public function getListeners()
    {
        $auth_id = null;
        if (Auth::guard('patient')->check()) { $auth_id = Auth::guard('patient')->id(); $this->event_name = "MassageSent2"; $this->chat_page = "chat2"; }
        elseif (Auth::guard('doctor')->check()) { $auth_id = Auth::guard('doctor')->id(); $this->event_name = "MassageSent"; $this->chat_page = "chat"; }
        else { return []; }

        if ($auth_id) {
            return [
                "echo-private:{$this->chat_page}.{$auth_id},{$this->event_name}" => 'broadcastMassage',
                'loadConversationFromList' => 'loadConversation',
                'pushMessage' => 'pushMessageToList'
            ];
        } else { return ['loadConversationFromList' => 'loadConversation', 'pushMessage' => 'pushMessageToList']; }
    }

    /**
     * التهيئة (تبقى كما هي).
     */
    public function mount()
    {
        if (Auth::guard('patient')->check()) { $this->auth_email = Auth::guard('patient')->user()->email; $this->auth_id = Auth::guard('patient')->id();}
        elseif (Auth::guard('doctor')->check()) {$this->auth_email = Auth::guard('doctor')->user()->email; $this->auth_id = Auth::guard('doctor')->id();}
        else {Log::error("[Chatbox Mount] User not authenticated.");}
        $this->messages = new Collection();
    }

    /**
     * استقبال الرسائل من البث (مع تعديل لتحديث حقل 'read').
     */
    public function broadcastMassage($event)
    {
        Log::info('[Chatbox] Received broadcast message event:', $event);
        if ($this->selected_conversation && isset($event['conversation_id']) && $this->selected_conversation->id == $event['conversation_id']) {
            $broadcastMessage = Message::find($event['message']);
            if ($broadcastMessage) {
                // --- >> التحقق والتحديث لـ read وليس read_at << ---
                // نتحقق إذا كانت القيمة الحالية false أو 0
                if (!$broadcastMessage->read && $broadcastMessage->receiver_email === $this->auth_email) {
                    // >>> تحديث read إلى true أو 1 <<<
                    $broadcastMessage->read = true; // أو 1
                    $broadcastMessage->save(); // حفظ التغيير
                    Log::info("[Chatbox] Marked broadcast message ID {$broadcastMessage->id} as read (boolean).");
                }
                $this->pushMessageToList($broadcastMessage);
            } else { Log::warning("[Chatbox] Broadcast message ID {$event['message']} not found."); }
        } else {
            Log::debug("[Chatbox] Ignoring broadcast for different/no conversation.");
            $this->emitTo('chat.chatlist','refreshComponent');
        }
    }

    /**
     * إضافة رسالة لقائمة العرض (تبقى كما هي).
     */
    public function pushMessageToList($message)
    {
        if (is_numeric($message)) { $newMessage = Message::find($message); }
        elseif ($message instanceof Message) { $newMessage = $message; }
        else { Log::error("[Chatbox pushMessageToList] Invalid message data.", ['data' => $message]); return; }

        if ($newMessage && $this->selected_conversation && $newMessage->conversation_id == $this->selected_conversation->id) {
            if (!$this->messages->contains('id', $newMessage->id)) {
                $this->messages->push($newMessage);
                Log::info("[Chatbox pushMessageToList] Message ID {$newMessage->id} pushed.");
                $this->dispatchBrowserEvent('scroll-to-bottom');
            } else { Log::debug("[Chatbox pushMessageToList] Message ID {$newMessage->id} already exists."); }
        } else { Log::warning("[Chatbox pushMessageToList] Message invalid or not for current conversation."); }
    }

    /**
     * تحميل المحادثة والرسائل (تبقى كما هي).
     */
    public function loadConversation(int $conversationId, int $receiverId, string $receiverType)
    {
        Log::info("[Chatbox] Loading conversation. ConvID: {$conversationId}, ReceiverID: {$receiverId}, Type: {$receiverType}");
        $conversation = Conversation::find($conversationId);
        $receiver = null;

        if (!$conversation) { Log::error("[Chatbox] Conversation {$conversationId} not found."); $this->resetConversation(); return; }

        $this->selected_conversation = $conversation;

        if ($receiverType === Doctor::class || $receiverType === 'App\\Models\\Doctor') { $receiver = Doctor::with('image')->find($receiverId); }
        elseif ($receiverType === Patient::class || $receiverType === 'App\\Models\\Patient') { $receiver = Patient::with('image')->find($receiverId); }

        if (!$receiver) { Log::error("[Chatbox] Receiver not found. ID: {$receiverId}, Type: {$receiverType}"); $this->resetConversation(); return; }

        $this->receviverUser = $receiver;
        $this->messages = Message::where('conversation_id', $this->selected_conversation->id)
                                   ->orderBy('created_at', 'asc')->get();
        Log::info("[Chatbox] Loaded " . $this->messages->count() . " messages.");
        $this->markMessagesAsRead(); // وضع علامة مقروءة
        $this->dispatchBrowserEvent('scroll-to-bottom'); // التمرير للأسفل
    }

    /**
     * وضع علامة مقروءة (read = true) على الرسائل غير المقروءة في المحادثة الحالية.
     */
    public function markMessagesAsRead()
    {
        if ($this->selected_conversation && $this->auth_email) {
            try {
                // --- >> التأكد من تحديث read وليس read_at << ---
                $updatedCount = Message::where('conversation_id', $this->selected_conversation->id)
                                       ->where('receiver_email', $this->auth_email)
                                       ->where('read', false) // <<< التحقق من read = false (أو 0)
                                       ->update(['read' => true]); // <<< تحديث read إلى true (أو 1)

                if ($updatedCount > 0) {
                    Log::info("[Chatbox] Marked {$updatedCount} messages as read (boolean) for conversation ID {$this->selected_conversation->id} and user {$this->auth_email}");
                }
            } catch (\Exception $e) {
                 Log::error("[Chatbox markMessagesAsRead] Failed to update read status: " . $e->getMessage());
            }
        }
    }

    /**
     * إعادة تعيين حالة صندوق المحادثة (تبقى كما هي).
     */
    private function resetConversation() {
        $this->selected_conversation = null;
        $this->receviverUser = null;
        $this->messages = new Collection();
    }

    /**
     * عرض الواجهة (تبقى كما هي).
     */
    public function render()
    {
        return view('livewire.chat.chatbox');
    }
}
