<?php

namespace App\Http\Livewire\Chat;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class ChatWindow extends Component // Doctor sees Patients List
{
    public $users; // Patients
    public $auth_email;

    public function mount()
    {
        if (!Auth::guard('doctor')->check()) {
            abort(403, 'Only doctors can access this.');
        }
        $this->auth_email = Auth::guard('doctor')->user()->email;
    }

    /**
     * Find or create conversation with the selected patient and redirect to chat.
     */
    public function createConversation($receiver_email) // $receiver_email is Patient's email
    {
        Log::info("[ChatWindow] Attempting conversation. Doctor: {$this->auth_email}, Patient: {$receiver_email}");

        DB::beginTransaction();
        try {

            $conversation = Conversation::firstOrCreate(
                [
                    'sender_email'   => $receiver_email, // Patient's Email
                    'receiver_email' => $this->auth_email,   // Doctor's Email
                ],
                [
                    // Ensure data is set correctly if creating (matching the lookup order)
                    'sender_email'   => $receiver_email,
                    'receiver_email' => $this->auth_email,
                    // 'last_time_message' => now(),
                ]
            );

            // If a new conversation was created, add the initial message
            if ($conversation->wasRecentlyCreated) {
                Log::info("[ChatWindow] New conversation created (ID: {$conversation->id}). Adding initial message.");
                Message::create([
                    'conversation_id' => $conversation->id,
                    'sender_email' => $this->auth_email,    // Doctor sends
                    'receiver_email' => $receiver_email,  // To Patient
                    'body' => 'أهلاً بك، كيف يمكنني المساعدة؟',
                ]);
                 // Update last message time
                 $conversation->last_time_message = now();
                 $conversation->save();
            } else {
                 Log::info("[ChatWindow] Existing conversation found (ID: {$conversation->id}).");
            }

            DB::commit();

            // --- Redirect Logic ---
            Log::info("[ChatWindow] Conversation ready (ID: {$conversation->id}). Redirecting to doctor.chat.patients route.");
            // Flash the conversation ID to the session
            session()->flash('selected_conversation_id', $conversation->id);
            // Redirect to the main chat interface for doctors
            return redirect()->route('doctor.chat.patients'); // Route name for doctor's chat view

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("[ChatWindow] Failed: " . $e->getMessage());
            $this->dispatchBrowserEvent('alert', ['type' => 'error', 'message' => 'فشل بدء المحادثة.']);
            return; // Stay on the current page on error
        }
    }

    public function render()
    {
        // Doctor sees the list of patients
        $this->users = Patient::with('image')->get(); // Eager load patient image if relation exists
        return view('livewire.chat.chat-window')->extends('Dashboard.layouts.master');
    }
}
