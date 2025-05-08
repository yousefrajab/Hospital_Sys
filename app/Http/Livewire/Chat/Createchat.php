<?php

namespace App\Http\Livewire\Chat;

use App\Models\Conversation;
use App\Models\Doctor;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

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

    /**
     * Find or create conversation with the selected doctor and redirect to chat.
     */
    public function createConversation($receiver_email) // $receiver_email is Doctor's email
    {
        Log::info("[Createchat] Attempting conversation. Patient: {$this->auth_email}, Doctor: {$receiver_email}");

        DB::beginTransaction();
        try {
            // Find or create the conversation
            // Assuming 'sender_email' is patient and 'receiver_email' is doctor in your DB structure
            $conversation = Conversation::firstOrCreate(
                [
                    'sender_email' => $this->auth_email,
                    'receiver_email' => $receiver_email,
                ]
                // No need for extra data here unless required by table structure
            );

            // If a new conversation was created, add the initial message
            if ($conversation->wasRecentlyCreated) {
                Log::info("[Createchat] New conversation created (ID: {$conversation->id}). Adding initial message.");
                Message::create([
                    'conversation_id' => $conversation->id,
                    'sender_email' => $this->auth_email,    // Patient sends
                    'receiver_email' => $receiver_email,  // To Doctor
                    'body' => 'السلام عليكم',
                ]);
                // Update last message time
                $conversation->last_time_message = now();
                $conversation->save();
            } else {
                Log::info("[Createchat] Existing conversation found (ID: {$conversation->id}).");
            }

            DB::commit();

            // --- Redirect Logic ---
            Log::info("[Createchat] Conversation ready (ID: {$conversation->id}). Redirecting to chat.doctors route.");
            // Flash the conversation ID to the session
            session()->flash('selected_conversation_id', $conversation->id);
            // Redirect to the main chat interface for patients
            return redirect()->route('chat.doctors'); // Route name for patient's chat view

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("[Createchat] Failed: " . $e->getMessage());
            // Optionally dispatch a browser event for user feedback
            $this->dispatchBrowserEvent('alert', ['type' => 'error', 'message' => 'فشل بدء المحادثة.']);
            return; // Stay on the current page on error
        }
    }

    public function render()
    {
        // Patient sees the list of doctors
        $this->users = Doctor::with('image')->get(); // Eager load image
        return view('livewire.chat.createchat')->extends('Dashboard.layouts.master');
    }
}
