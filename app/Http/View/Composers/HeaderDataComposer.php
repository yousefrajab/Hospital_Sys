<?php
namespace App\Http\View\Composers; // تأكد أن الـ namespace صحيح

use App\Models\Message;
use App\Models\Notification as CustomNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log; // لإضافة تسجيل

class HeaderDataComposer
{
    public function compose(View $view)
    {
        $unreadMessagesForHeader = 0;
        $generalNotificationsCountForHeader = 0;
        $latestMessagesForHeader = collect(); // Initialize as empty collection
        $latestGeneralNotificationsForHeader = collect(); // Initialize as empty collection

        if (Auth::check()) { // تحقق أولاً أن هناك مستخدم مسجل
            $currentUser = Auth::user(); // الـ guard النشط حالياً

            // 1. حساب الرسائل الجديدة من جدول messages للمستخدم الحالي
            if (property_exists($currentUser, 'email')) {
                try {
                    $unreadMessagesForHeader = Message::where('receiver_email', $currentUser->email)
                                                     ->where('read', false)
                                                     ->count();

                    $latestMessagesForHeader = Message::where('receiver_email', $currentUser->email)
                                                     ->where('read', false)
                                                     // ->with('senderUserObject') // (اختياري) علاقة لجلب بيانات المرسل
                                                     ->latest('created_at') // تأكد من وجود عمود الترتيب
                                                     ->take(3)
                                                     ->get();
                } catch (\Exception $e) {
                    Log::error("HeaderDataComposer: Error fetching messages for user {$currentUser->id}: " . $e->getMessage());
                }
            } else {
                Log::warning("HeaderDataComposer: Current user (ID: {$currentUser->id}) does not have an email property for fetching messages.");
            }


            // 2. حساب الإشعارات "العامة" من جدول notifications المخصص للمستخدم الحالي
            if (property_exists($currentUser, 'id')) {
                try {
                    $generalNotificationsCountForHeader = CustomNotification::where('user_id', $currentUser->id)
                                                                      ->where('reader_status', false)
                                                                      ->count();
                    $latestGeneralNotificationsForHeader = CustomNotification::where('user_id', $currentUser->id)
                                                                      ->where('reader_status', false)
                                                                      ->latest('created_at') // تأكد من وجود عمود الترتيب
                                                                      ->take(5)
                                                                      ->get();
                } catch (\Exception $e) {
                    Log::error("HeaderDataComposer: Error fetching custom notifications for user {$currentUser->id}: " . $e->getMessage());
                }
            } else {
                 Log::warning("HeaderDataComposer: Current user does not have an ID property for fetching custom notifications.");
            }


        } else {
            Log::info("HeaderDataComposer: No authenticated user for header data.");
        }

        $view->with('unreadMessagesForHeader', $unreadMessagesForHeader)
             ->with('latestMessagesForHeader', $latestMessagesForHeader)
             ->with('generalNotificationsCountForHeader', $generalNotificationsCountForHeader)
             ->with('latestGeneralNotificationsForHeader', $latestGeneralNotificationsForHeader);
    }
}
