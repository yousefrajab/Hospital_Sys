<?php
namespace App\Http\View\Composers;

use App\Models\Doctor;
use App\Models\Message;
use App\Models\Notification as CustomNotification;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class HeaderDataComposer
{
    // Every role has its own guard; the first one that is logged in is the current user.
    private const GUARDS = [
        'admin', 'doctor', 'patient', 'ray_employee', 'laboratorie_employee', 'pharmacy_employee', 'pharmacy_manager',
    ];

    public function compose(View $view)
    {
        $unreadMessagesForHeader = 0;
        $generalNotificationsCountForHeader = 0;
        $latestMessagesForHeader = collect();
        $latestGeneralNotificationsForHeader = collect();
        $chatRouteForHeader = null;

        $currentUser = null;
        $currentGuard = null;
        foreach (self::GUARDS as $guard) {
            if (Auth::guard($guard)->check()) {
                $currentUser = Auth::guard($guard)->user();
                $currentGuard = $guard;
                break;
            }
        }

        if ($currentUser) {
            try {
                // Chat exists only between doctors and patients.
                if (in_array($currentGuard, ['doctor', 'patient'], true) && $currentUser->email) {
                    $unread = Message::where('receiver_email', $currentUser->email)->where('read', false);
                    $unreadMessagesForHeader = (clone $unread)->count();
                    $latestMessagesForHeader = $unread->latest('created_at')->take(3)->get();

                    $senderModel = $currentGuard === 'doctor' ? Patient::class : Doctor::class;
                    $senders = $senderModel::whereIn('email', $latestMessagesForHeader->pluck('sender_email')->unique())
                        ->get()->keyBy('email');
                    $latestMessagesForHeader->each(function ($message) use ($senders) {
                        $message->sender_display_name = optional($senders->get($message->sender_email))->name ?? $message->sender_email;
                    });

                    $chatRouteForHeader = $currentGuard === 'doctor' ? route('doctor.chat.patients') : route('chat.doctors');
                }

                $notifications = CustomNotification::forUser($currentUser)->unread();
                $generalNotificationsCountForHeader = (clone $notifications)->count();
                $latestGeneralNotificationsForHeader = $notifications->latest('created_at')->take(5)->get();
            } catch (\Exception $e) {
                Log::error("HeaderDataComposer: error loading header data for {$currentGuard} #{$currentUser->id}: " . $e->getMessage());
            }
        }

        $view->with('unreadMessagesForHeader', $unreadMessagesForHeader)
             ->with('latestMessagesForHeader', $latestMessagesForHeader)
             ->with('generalNotificationsCountForHeader', $generalNotificationsCountForHeader)
             ->with('latestGeneralNotificationsForHeader', $latestGeneralNotificationsForHeader)
             ->with('chatRouteForHeader', $chatRouteForHeader);
    }
}
