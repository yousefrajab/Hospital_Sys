<?php

namespace App\Http\Livewire\Chat;

use App\Models\Conversation;
use App\Models\Doctor;
use App\Models\Message; // تأكد من استيراد Message
use App\Models\Patient;
use App\Models\Notification as CustomNotification; // <-- اسم مستعار
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon; // استيراد Carbon

class Chatlist extends Component
{
    public ?Collection $conversations = null;
    public $auth_email;
    public ?object $receviverUser = null;
    public ?Conversation $selected_conversation = null;
    public $auth_user_id;
    public $auth_user_guard;

    protected $listeners = ['chatUserSelected', 'refreshComponent' => '$refresh', 'messageSent' => '$refresh'];

    public function mount()
    {
        if (Auth::guard('patient')->check()) {
            $this->auth_user_guard = 'patient';
            $this->auth_user_id = Auth::guard('patient')->id();
            $this->auth_email = Auth::guard('patient')->user()->email;
        } elseif (Auth::guard('doctor')->check()) {
            $this->auth_user_guard = 'doctor';
            $this->auth_user_id = Auth::guard('doctor')->id();
            $this->auth_email = Auth::guard('doctor')->user()->email;
        } else {
            Log::error("[Chatlist Mount] User not authenticated for any relevant guard.");
             abort(401, 'User not authenticated.');
        }
        $this->conversations = new Collection();

        if (Session::has('selected_conversation_id')) {
            $selectedId = Session::pull('selected_conversation_id'); // استخدم pull لإزالته
            $this->chatUserSelectedOnLoad($selectedId);
        }

        // تعليم الإشعارات كمقروءة عند تحميل القائمة لأول مرة
        $this->markChatNotificationsAsReadGeneral();
    }


    // دالة لتعليم *كل* إشعارات الرسائل غير المقروءة للمستخدم الحالي كمقروءة
    // تُستدعى عند تحميل صفحة قائمة المحادثات بشكل عام
    protected function markChatNotificationsAsReadGeneral()
    {
        if ($this->auth_user_id) {
            try {
                // هذا سيعلم كل إشعارات المستخدم غير المقروءة كمقروءة
                // إذا أردت فقط إشعارات الرسائل، ستحتاج لطريقة لتمييزها في جدول notifications
                // مثال: ->where('notification_type_in_your_table', 'chat')
                $updatedCount = CustomNotification::where('user_id', $this->auth_user_id)
                                   ->where('reader_status', false)
                                   ->update(['reader_status' => true]);
                if($updatedCount > 0) {
                    Log::info("{$updatedCount} general notifications marked as read for User ID: " . $this->auth_user_id);
                }
            } catch(\Exception $e) {
                Log::error("Error marking general notifications as read for User ID: {$this->auth_user_id}. Error: " . $e->getMessage());
            }
        }
    }

    // دالة لتعليم إشعارات *محادثة معينة* كمقروءة
    // تُستدعى عند فتح محادثة محددة
    protected function markSpecificConversationNotificationsAsRead(int $conversationId)
    {
        if ($this->auth_user_id) {
            try {
                // هذا يتطلب أنك تخزن conversation_id بطريقة ما في جدول notifications
                // أو أن نص الإشعار يحتوي على شيء يمكن البحث به عن المحادثة
                // إذا كنت تخزن conversation_id في عمود 'data' (JSON) في جدول Laravel الافتراضي:
                // $updatedCount = \Illuminate\Notifications\DatabaseNotification::where('notifiable_id', $this->auth_user_id)
                //                    ->where('notifiable_type', get_class(Auth::guard($this->auth_user_guard)->user()))
                //                    ->whereNull('read_at')
                //                    ->whereJsonContains('data->conversation_id', $conversationId)
                //                    ->update(['read_at' => now()]);

                // إذا كنت لا تخزن conversation_id في جدول notifications المخصص لك،
                // يمكنك الاعتماد على markChatNotificationsAsReadGeneral() عند فتح صفحة المحادثات الرئيسية
                // أو تعليم الرسائل الفعلية كمقروءة في جدول messages (وهو ما يتم في Chatbox).
                // حالياً، سنفترض أن فتح أي محادثة يعلم *كل* إشعارات الرسائل كمقروءة إذا لم يكن هناك ربط مباشر.
                // $this->markChatNotificationsAsReadGeneral(); //  أو اترك هذا للمستخدم بالنقر على "تعليم الكل كمقروء" في الهيدر

                 Log::info("Attempting to mark specific notifications for conversation ID {$conversationId} as read (if applicable).");

            } catch(\Exception $e) {
                 Log::error("Error marking specific conv notifications for User ID: {$this->auth_user_id}, Conv ID: {$conversationId}. Error: " . $e->getMessage());
            }
        }
    }


    public function chatUserSelectedOnLoad(int $conversationId)
    {
        try {
            $conversation = Conversation::with(['doctor.image', 'patient.image'])->findOrFail($conversationId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
             Log::error("[Chatlist Load] Conversation ID {$conversationId} not found in DB.");
             $this->dispatchBrowserEvent('alert', ['type' => 'error', 'message' => 'المحادثة المطلوبة غير موجودة.']);
             return;
        }
        if ($conversation->sender_email !== $this->auth_email && $conversation->receiver_email !== $this->auth_email) {
             Log::warning("[Chatlist Load] Unauthorized access attempt for conversation {$conversationId} by user {$this->auth_email}.");
             $this->dispatchBrowserEvent('alert', ['type' => 'error', 'message' => 'غير مصرح لك بالوصول لهذه المحادثة.']);
             return;
        }
        $this->selected_conversation = $conversation;
        $this->receviverUser = ($this->auth_user_guard === 'patient') ? $conversation->doctor : $conversation->patient;

        if ($this->receviverUser) {
            Log::info("[Chatlist Load] Selecting Conv ID: {$this->selected_conversation->id}, Receiver: {$this->receviverUser->email}");
            $this->emitTo('chat.chatbox', 'loadConversationFromList', $this->selected_conversation->id, $this->receviverUser->id, get_class($this->receviverUser));
            $this->emitTo('chat.send-message', 'updateConversationFromList', $this->selected_conversation->id, $this->receviverUser->id, get_class($this->receviverUser));
            $this->dispatchBrowserEvent('conversation-selected', ['id' => $this->selected_conversation->id]);
            $this->markSpecificConversationNotificationsAsRead($this->selected_conversation->id); // تعليم إشعارات هذه المحادثة كمقروءة
        } else {
            Log::error("[Chatlist Load] Could not determine receiver for conversation ID: {$conversationId}");
            $this->selected_conversation = null;
        }
    }

    public function chatUserSelected(Conversation $conversation, $receiver_id = null)
    {
         Log::info("[Chatlist Click] User clicked on existing conversation ID: {$conversation->id}");
        if ($conversation->sender_email !== $this->auth_email && $conversation->receiver_email !== $this->auth_email) {
            return;
        }
         $this->selected_conversation = $conversation;
         $this->receviverUser = ($this->auth_user_guard === 'patient') ? $conversation->loadMissing('doctor.image')->doctor : $conversation->loadMissing('patient.image')->patient;

         if ($this->receviverUser) {
             Log::info("[Chatlist Click] Selecting Conv ID: {$this->selected_conversation->id}, Receiver: {$this->receviverUser->email}");
             $this->emitTo('chat.chatbox', 'loadConversationFromList', $this->selected_conversation->id, $this->receviverUser->id, get_class($this->receviverUser));
             $this->emitTo('chat.send-message', 'updateConversationFromList', $this->selected_conversation->id, $this->receviverUser->id, get_class($this->receviverUser));
             $this->dispatchBrowserEvent('conversation-selected', ['id' => $this->selected_conversation->id]);
             $this->markSpecificConversationNotificationsAsRead($this->selected_conversation->id); // تعليم إشعارات هذه المحادثة كمقروءة
         } else {
             Log::error("[Chatlist Click] Could not determine receiver for conversation ID: {$conversation->id}");
             $this->selected_conversation = null;
         }
    }

    public function loadConversations()
    {
        if (empty($this->auth_email)) {
            $this->conversations = new Collection(); return;
        }
        try {
            $this->conversations = Conversation::with([
                    'doctor.image' => fn($q) => $q->select('filename', 'imageable_id', 'imageable_type'),
                    'patient.image' => fn($q) => $q->select('filename', 'imageable_id', 'imageable_type'),
                    'lastMessage' => fn($query) => $query->select('id', 'conversation_id', 'body', 'created_at', 'sender_email', 'read') // أضفت read و sender_email
                ])
                ->where(function($query) {
                    $query->where('sender_email', $this->auth_email)
                          ->orWhere('receiver_email', $this->auth_email);
                })
                ->get()
                ->map(function ($conversation) {
                    // حساب عدد الرسائل غير المقروءة *لهذه المحادثة* الموجهة للمستخدم الحالي
                    $conversation->unread_messages_for_user_count = $conversation->messages()
                                                                ->where('receiver_email', $this->auth_email)
                                                                ->where('read', false) // الاعتماد على عمود read في messages
                                                                ->count();
                    return $conversation;
                })
                ->sortByDesc(function($conversation) {
                    return optional($conversation->lastMessage)->created_at ?? Carbon::createFromTimestamp(0);
                });
        } catch (\Exception $e) {
             Log::error("[Chatlist loadConversations] Error: " . $e->getMessage());
             $this->conversations = new Collection();
        }
    }

    public function render()
    {
        $this->loadConversations();
        return view('livewire.chat.chatlist');
    }

    // getUsers يمكن إزالتها إذا لم يعد الـ blade يستدعيها مباشرة
    public function getUsers(Conversation $conversation, $request){ /* ... */ }
}
