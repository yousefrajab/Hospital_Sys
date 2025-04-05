<?php

namespace App\Http\Livewire\Chat;

use App\Models\Conversation;
use App\Models\Doctor;
use App\Models\Patient;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Chatlist extends Component
{
    public $conversations;
    public $auth_email;
    public $receviverUser;
    public $selected_conversation;
    protected $listeners = ['chatUserSelected', 'refresh' => '$refresh'];


    public function mount()
    {
        $this->auth_email = auth()->user()->email;
        $this->conversations = Doctor::all(); // جلب جميع الأطباء
    }

    public function getUsers(Conversation $conversation, $request)
    {
        if ($conversation->sender_email == $this->auth_email) {
            $this->receviverUser = Doctor::where('email', $conversation->receiver_email)->first();
        } else {
            $this->receviverUser = Patient::where('email', $conversation->sender_email)->first();
        }

        return $this->receviverUser->$request ?? null;
    }

    public function chatUserSelected(Conversation $conversation, $receiver_id)
    {

        $this->selected_conversation = $conversation;
        $this->receviverUser = Doctor::find($receiver_id);
        if (Auth::guard('patient')->check()) {
            $this->emitTo('chat.chatbox', 'load_conversationDoctor', $this->selected_conversation, $this->receviverUser);
            $this->emitTo('chat.send-message', 'updateMessage', $this->selected_conversation, $this->receviverUser);

        } elseif (Auth::guard('doctor')->check()) {
            $this->receviverUser = Patient::find($receiver_id);

            $this->emitTo('chat.chatbox', 'load_conversationPatient', $this->selected_conversation, $this->receviverUser);
            $this->emitTo('chat.send-message', 'updateMessage2', $this->selected_conversation, $this->receviverUser);

        }

    }

    public function render()
{
    $this->conversations = Conversation::with([
            'doctor.image',
            'patient',
            'lastMessage' => function($query) {
                $query->latest();
            }
        ])
        ->where(function($query) {
            $query->where('sender_email', $this->auth_email)
                  ->orWhere('receiver_email', $this->auth_email);
        })
        ->get()
        ->sortByDesc(function($conversation) {
            return optional($conversation->lastMessage)->created_at ?? now();
        });

    return view('livewire.chat.chatlist');
}
}
