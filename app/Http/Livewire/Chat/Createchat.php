<?php

namespace App\Http\Livewire\Chat;

use App\Models\Conversation;
use App\Models\Doctor;
use App\Models\Message;
use App\Models\Patient; // تأكد من استيراد Patient
use App\Models\Notification as CustomNotification; // <-- اسم مستعار
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str; //  <-- لإستخدام Str::limit

class Createchat extends Component // Patient sees Doctors List
{
    public $users; // Doctors
    public $auth_email;

    public function mount()
    {
        if (!Auth::guard('patient')->check()) {
            abort(403, 'Only patients can access this.');
        }
        $this->auth_email = Auth::guard('patient')->user()->email;
    }

    public function createConversation($receiver_email) // $receiver_email is Doctor's email
    {
        Log::info("[Createchat] Attempting conversation. Patient: {$this->auth_email}, Doctor: {$receiver_email}");
        $currentPatient = Auth::guard('patient')->user();

        DB::beginTransaction();
        try {
            $conversation = Conversation::firstOrCreate(
                ['sender_email' => $this->auth_email, 'receiver_email' => $receiver_email]
            );

            $newMessage = null;
            // عند إنشاء محادثة لأول مرة، عادة ما ترسل رسالة أولى
            // أو يمكن للمستخدم أن يرسل رسالة بعد فتح المحادثة من SendMessage.php
            // سأفترض هنا أننا نضيف رسالة أولية إذا كانت المحادثة جديدة.
            if ($conversation->wasRecentlyCreated) {
                Log::info("[Createchat] New conversation created (ID: {$conversation->id}). Adding initial message.");
                $newMessage = Message::create([
                    'conversation_id' => $conversation->id,
                    'sender_email' => $this->auth_email,    // Patient sends
                    'receiver_email' => $receiver_email,  // To Doctor
                    'body' => 'السلام عليكم، أود بدء محادثة.', // رسالة أولية من المريض
                    'read' => false // الرسالة جديدة
                ]);
                $conversation->last_time_message = $newMessage->created_at;
                $conversation->save();
            } else {
                Log::info("[Createchat] Existing conversation found (ID: {$conversation->id}). Redirecting.");
                // إذا كانت المحادثة موجودة، ربما لا نحتاج لإنشاء رسالة هنا
                // بل نعتمد على آخر رسالة إذا أردنا إرسال إشعار بناءً على فتح المحادثة
                $newMessage = $conversation->messages()->latest()->first(); // جلب آخر رسالة كمرجع للإشعار
            }

            // --- إنشاء إشعار في جدول notifications المخصص للطبيب المستلم ---
            if ($newMessage) { // تأكد من وجود رسالة
                $doctorReceiver = Doctor::where('email', $newMessage->receiver_email)->first();
                if ($doctorReceiver) {
                    // 1. إرسال إشعار Laravel (للبث أو لجدول Laravel الافتراضي إذا كنت تستخدمه)
                    // هذا الجزء مهم إذا كنت تعتمد على Echo لتحديثات فورية في واجهة الطبيب
                    $patientSenderName = $currentPatient->name;
                    $patientSenderAvatar = $currentPatient->image ? asset('Dashboard/img/patients/' . $currentPatient->image->filename) : null; //  مسار صورة المريض
                    $doctorReceiver->notify(new \App\Notifications\NewChatMessageNotification($newMessage, $patientSenderName, $patientSenderAvatar));
                    Log::info("[Createchat] Laravel Notification (NewChatMessageNotification) sent to Doctor ID: {$doctorReceiver->id} for message ID: {$newMessage->id}");

                    // 2. *** إنشاء سجل في جدول notifications المخصص لك للطبيب ***
                    try {
                        CustomNotification::create([
                            'user_id' => $doctorReceiver->id, // ID الطبيب المستقبِل
                            'message' => "رسالة جديدة من المريض: {$patientSenderName} - \"". Str::limit($newMessage->body, 45) ."\"",
                            'reader_status' => false, // الإشعار جديد
                            // يمكنك إضافة 'type' => 'chat_message', 'link' => route('doctor.chat.patients', ['conversation_id' => $conversation->id]) هنا
                            // إذا كان جدول notifications لديك يدعم هذه الأعمدة
                        ]);
                        Log::info("[Createchat] Custom App\\Models\\Notification record CREATED for Doctor ID: {$doctorReceiver->id} for message ID: {$newMessage->id}.");
                    } catch (\Exception $e) {
                        Log::error("[Createchat] FAILED to create Custom App\\Models\\Notification for Doctor ID: {$doctorReceiver->id}. Error: " . $e->getMessage());
                    }
                }
            }
            // --- نهاية جزء الإشعار ---

            DB::commit();

            session()->flash('selected_conversation_id', $conversation->id);
            return redirect()->route('chat.doctors');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("[Createchat] Failed to create/open conversation: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            $this->dispatchBrowserEvent('alert', ['type' => 'error', 'message' => 'فشل بدء المحادثة. ' . $e->getMessage()]);
            return;
        }
    }

    public function render()
    {
        $this->users = Doctor::with('image')->get();
        return view('livewire.chat.createchat')->extends('Dashboard.layouts.master');
    }
}
