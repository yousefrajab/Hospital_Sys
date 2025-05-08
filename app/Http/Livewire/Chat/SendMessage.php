<?php

namespace App\Http\Livewire\Chat;

use App\Models\Conversation;
use App\Models\Doctor;
use App\Models\Message;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Events\MassageSent;
use App\Events\MassageSent2;
use Illuminate\Support\Facades\Log;

class SendMessage extends Component
{
    public string $body = '';
    public ?Conversation $selected_conversation = null;
    public ?object $receviverUser = null;
    public $auth_email;
    public $sender;
    public ?Message $createdMessage = null;

    // استقبال الحدث الجديد
    protected $listeners = [
        'updateConversationFromList' => 'updateConversation',
        'dispatchSentMassage'
    ];

    public function mount()
    { /* ... (نفس الكود السابق لـ mount) ... */
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

    /**
     * تحديث المحادثة والمستقبل بناءً على IDs والنوع.
     */
    public function updateConversation(int $conversationId, int $receiverId, string $receiverType)
    {
        Log::info("[SendMessage] Updating conversation via 'updateConversation'. ConvID: {$conversationId}, ReceiverID: {$receiverId}, ReceiverType: {$receiverType}");
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
            Log::error("[SendMessage] Failed to update conversation/receiver. Conv found: " . ($this->selected_conversation ? 'Yes' : 'No') . ", Receiver found: " . ($receiver ? 'Yes' : 'No'));
            // إعادة التعيين إذا فشل التحديث
            $this->selected_conversation = null;
            $this->receviverUser = null;
            $this->reset('body');
        }
    }

    public function sendMessage()
    {
        /* ... (نفس الكود السابق لـ sendMessage) ... */
        if (!$this->selected_conversation || !$this->receviverUser || empty(trim($this->body))) {
            return;
        }
        $this->validate(['body' => 'required|string|max:5000']);
        DB::beginTransaction();
        try {
            $this->createdMessage = Message::create([ /* ... */
                'conversation_id' => $this->selected_conversation->id,
                'sender_email' => $this->auth_email,
                'receiver_email' => $this->receviverUser->email,
                'body' => $this->body,
            ]);
            $this->selected_conversation->last_time_message = $this->createdMessage->created_at;
            $this->selected_conversation->save();
            DB::commit();
            $this->reset('body');
            Log::info("[SendMessage] Emitting pushMessage with ID: " . $this->createdMessage->id . " to chat.chatbox");
            $this->emitTo('chat.chatbox', 'pushMessage', $this->createdMessage->id);
            $this->emitTo('chat.chatlist', 'refreshComponent');
            $this->emitSelf('dispatchSentMassage');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("[SendMessage] Failed: " . $e->getMessage());
            $this->dispatchBrowserEvent('alert', ['type' => 'error', 'message' => 'فشل إرسال الرسالة.']);
        }
    }
    public function dispatchSentMassage()
    { /* ... (نفس الكود السابق لـ dispatchSentMassage) ... */
        if (!$this->createdMessage) {
            return;
        }
        try {
            if ($this->sender instanceof Patient && $this->receviverUser instanceof Doctor) {
                broadcast(new MassageSent($this->sender, $this->createdMessage, $this->selected_conversation, $this->receviverUser))->toOthers();
            } elseif ($this->sender instanceof Doctor && $this->receviverUser instanceof Patient) {
                broadcast(new MassageSent2($this->sender, $this->createdMessage, $this->selected_conversation, $this->receviverUser))->toOthers();
            }
        } catch (\Exception $e) {
            Log::error("[SendMessage Dispatch] Failed: " . $e->getMessage());
        }
    }
    function render()
    {
        return view('livewire.chat.send-message');
    }
}
