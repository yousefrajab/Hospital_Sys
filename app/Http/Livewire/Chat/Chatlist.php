<?php

namespace App\Http\Livewire\Chat;

use App\Models\Conversation;
use App\Models\Doctor;
use App\Models\Patient;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; // تأكد من وجوده
use Carbon\Carbon;

class Chatlist extends Component
{
    public ?Collection $conversations = null;
    public $auth_email;
    public ?object $receviverUser = null; // سيتم تعيينه عند اختيار محادثة
    public ?Conversation $selected_conversation = null;
    public $auth_user_id;
    public $auth_user_guard;

    // المستمعون (يمكن تبسيطها إذا لم تعد تستخدم النقر لتحديد المحادثة)
    protected $listeners = [
        'chatUserSelected', // يُستدعى عند النقر على محادثة موجودة
        'refreshComponent' => '$refresh', // لتحديث القائمة عند إرسال رسالة من مكان آخر
        'messageSent' => '$refresh' // لتحديث القائمة عند إرسال رسالة من هذا المستخدم
        ];

    /**
     * تهيئة المكون وتحديد المستخدم الحالي.
     * يتحقق من وجود محادثة محددة في الـ Session (قادمة من redirect).
     */
    public function mount()
    {
        if (Auth::guard('patient')->check()) {
            $this->auth_user_guard = 'patient';
            $this->auth_user_id = Auth::guard('patient')->id();
            $this->auth_email = Auth::guard('patient')->user()->email; // تحديد الإيميل هنا
        } elseif (Auth::guard('doctor')->check()) {
            $this->auth_user_guard = 'doctor';
            $this->auth_user_id = Auth::guard('doctor')->id();
            $this->auth_email = Auth::guard('doctor')->user()->email; // تحديد الإيميل هنا
        } else {
             abort(401, 'User not authenticated.');
        }

        $this->conversations = new Collection(); // تهيئة كمجموعة فارغة

        // التحقق من وجود محادثة مختارة من الـ Redirect وتحديدها
        if (Session::has('selected_conversation_id')) {
            $selectedId = Session::get('selected_conversation_id');
            Log::info("[Chatlist Mount] Found selected_conversation_id in session: {$selectedId}. Attempting to select.");
            // استدعاء الدالة التي تحدد المحادثة وترسل الأحداث للمكونات الأخرى
            $this->chatUserSelectedOnLoad($selectedId);
        } else {
            Log::info("[Chatlist Mount] No selected_conversation_id found in session.");
        }
    }

    /**
     * تحميل قائمة المحادثات الحالية للمستخدم (مع إصلاح تحميل آخر رسالة).
     */
    public function loadConversations()
    {
        // التأكد من وجود auth_email قبل استخدامه في الاستعلام
        if (empty($this->auth_email)) {
            Log::error("[Chatlist loadConversations] Auth email is not set. Cannot load conversations.");
            $this->conversations = new Collection(); // إرجاع مجموعة فارغة
            return;
        }

        try {
            $this->conversations = Conversation::with([
                    // عدم استخدام select() هنا للسماح للترجمة بالعمل
                    'doctor.image' => fn($q) => $q->select('filename', 'imageable_id', 'imageable_type'), // تحميل الصورة
                    'patient.image' => fn($q) => $q->select('filename', 'imageable_id', 'imageable_type'), // تحميل الصورة

                    // *** استخدام علاقة lastMessage البسيطة (hasOne->latest) ***
                    'lastMessage' => fn($query) => $query->select('id', 'conversation_id', 'body', 'created_at') // فقط الأعمدة المطلوبة
                ])
                ->where(function($query) { // فلترة المحادثات
                    $query->where('sender_email', $this->auth_email)
                          ->orWhere('receiver_email', $this->auth_email);
                })
                ->get() // الحصول على المجموعة
                ->sortByDesc(function($conversation) { // الفرز باستخدام Collection::sortByDesc
                    return optional($conversation->lastMessage)->created_at ?? Carbon::createFromTimestamp(0);
                });

             Log::debug("[Chatlist] Loaded " . ($this->conversations ? $this->conversations->count() : 0) . " conversations for user {$this->auth_email}");

        } catch (\Exception $e) {
             Log::error("[Chatlist loadConversations] Error loading conversations for {$this->auth_email}: " . $e->getMessage());
             $this->conversations = new Collection(); // إرجاع مجموعة فارغة في حالة الخطأ
        }
    }


    /**
     * دالة يتم استدعاؤها عند تحميل الصفحة إذا كان هناك ID محادثة في الـ Session.
     * تحدد المحادثة وترسل الأحداث اللازمة للمكونات الأخرى.
     */
    public function chatUserSelectedOnLoad(int $conversationId)
    {
        // استخدام findOrFail لرمي استثناء إذا لم يتم العثور على المحادثة
        try {
            $conversation = Conversation::with(['doctor.image', 'patient.image'])->findOrFail($conversationId);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
             Log::error("[Chatlist Load] Conversation ID {$conversationId} from session not found in DB.");
             Session::forget('selected_conversation_id');
             $this->dispatchBrowserEvent('alert', ['type' => 'error', 'message' => 'المحادثة المطلوبة غير موجودة.']);
             return;
        }

        // التحقق من أن المستخدم الحالي طرف في المحادثة
        if ($conversation->sender_email !== $this->auth_email && $conversation->receiver_email !== $this->auth_email) {
             Log::warning("[Chatlist Load] User {$this->auth_email} ({$this->auth_user_guard}) is not a participant in conversation {$conversationId}.");
             Session::forget('selected_conversation_id');
             $this->dispatchBrowserEvent('alert', ['type' => 'error', 'message' => 'غير مصرح لك بالوصول لهذه المحادثة.']);
             return;
        }

        // تحديد المحادثة الحالية
        $this->selected_conversation = $conversation;

        // تحديد المستخدم الآخر
        if ($this->auth_user_guard === 'patient') {
            $this->receviverUser = $conversation->doctor;
        } else { // إذا كان المستخدم طبيبًا
            $this->receviverUser = $conversation->patient;
        }

        // إرسال الأحداث للمكونات الأخرى إذا تم تحديد المستخدم الآخر
        if ($this->receviverUser) {
            Log::info("[Chatlist Load] Selecting Conv ID: {$this->selected_conversation->id}, Receiver: {$this->receviverUser->email}");
            // تمرير IDs وأنواع بسيطة (أفضل لـ Livewire)
            $receiverId = $this->receviverUser->id;
            $receiverType = get_class($this->receviverUser);
            $this->emitTo('chat.chatbox', 'loadConversationFromList', $this->selected_conversation->id, $receiverId, $receiverType);
            $this->emitTo('chat.send-message', 'updateConversationFromList', $this->selected_conversation->id, $receiverId, $receiverType);
            // إرسال حدث للمتصفح لتحديد العنصر النشط في القائمة
            $this->dispatchBrowserEvent('conversation-selected', ['id' => $this->selected_conversation->id]);
        } else {
            Log::error("[Chatlist Load] Could not determine receiver for conversation ID: {$conversationId}");
            // إعادة تعيين المحادثة المحددة إذا لم يتم العثور على الطرف الآخر
            $this->selected_conversation = null;
        }
    }

    /**
     * يتم استدعاؤها عند النقر على محادثة موجودة في القائمة.
     * (إذا كنت لا تزال تسمح بالنقر على القائمة لتغيير المحادثة)
     */
    public function chatUserSelected(Conversation $conversation, $receiver_id = null) // receiver_id غير مستخدم فعلياً هنا
    {
         Log::info("[Chatlist Click] User clicked on existing conversation ID: {$conversation->id}");

        // التحقق من أن المستخدم الحالي طرف في المحادثة (احتياطي)
        if ($conversation->sender_email !== $this->auth_email && $conversation->receiver_email !== $this->auth_email) {
            Log::warning("[Chatlist Click] User {$this->auth_email} attempted to select unauthorized conversation {$conversation->id}");
            return; // لا تفعل شيئًا
        }

         $this->selected_conversation = $conversation;

         // تحديد المستخدم الآخر بنفس الطريقة المستخدمة في chatUserSelectedOnLoad
         if ($this->auth_user_guard === 'patient') {
             $this->receviverUser = $conversation->loadMissing('doctor.image')->doctor; // تحميل العلاقة إذا لم تكن محملة
         } else {
             $this->receviverUser = $conversation->loadMissing('patient.image')->patient; // تحميل العلاقة إذا لم تكن محملة
         }

         if ($this->receviverUser) {
             Log::info("[Chatlist Click] Selecting Conv ID: {$this->selected_conversation->id}, Receiver: {$this->receviverUser->email}");
             // تمرير IDs وأنواع بسيطة
             $receiverId = $this->receviverUser->id;
             $receiverType = get_class($this->receviverUser);
             $this->emitTo('chat.chatbox', 'loadConversationFromList', $this->selected_conversation->id, $receiverId, $receiverType);
             $this->emitTo('chat.send-message', 'updateConversationFromList', $this->selected_conversation->id, $receiverId, $receiverType);
             $this->dispatchBrowserEvent('conversation-selected', ['id' => $this->selected_conversation->id]);
         } else {
             Log::error("[Chatlist Click] Could not determine receiver for conversation ID: {$conversation->id}");
             $this->selected_conversation = null; // إلغاء التحديد إذا فشل
         }
    }


    /**
     * عرض الواجهة.
     */
    public function render()
    {
        $this->loadConversations(); // تحميل المحادثات قبل عرض الواجهة
        return view('livewire.chat.chatlist');
    }

     // --- دالة getUsers القديمة ---
     // تم الإبقاء عليها لأن الـ Blade يستدعيها، لكن يجب تعديل الـ Blade لإزالتها
     public function getUsers(Conversation $conversation, $request)
     {
         // يمكنك إرجاع null أو إظهار تحذير هنا لتشجيع استخدام العلاقات
         Log::warning("Deprecated function getUsers() called in Chatlist. Use eager loaded relations instead.");

         $receiver = null; $sender = null;
         if($conversation->receiver_email) { $receiver = Doctor::where('email', $conversation->receiver_email)->first() ?? Patient::where('email', $conversation->receiver_email)->first(); }
         if($conversation->sender_email) { $sender = Doctor::where('email', $conversation->sender_email)->first() ?? Patient::where('email', $conversation->sender_email)->first(); }
         if ($conversation->sender_email == $this->auth_email) { $this->receviverUser = $receiver; } else { $this->receviverUser = $sender; }
         return $this->receviverUser ? $this->receviverUser->{$request} : null;
     }

}
