// ... (window._, window.axios) ...
import Echo from 'laravel-echo';
window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: true,
    // تشفير الاتصال مهم إذا كنت تستخدم قنوات خاصة (private channels)
    // authEndpoint: '/broadcasting/auth', //  تأكد أن لديك هذا المسار إذا كانت القنوات private
});

// --- الاستماع لإشعارات الرسائل الجديدة لتحديث الـ UI ---
// تأكد أن لديك بيانات المستخدم متاحة في window.Laravel.user
// يمكنك إضافتها في الـ layout الرئيسي:
// <script> window.Laravel = {!! json_encode(['user' => Auth::user() ? ['id' => Auth::id(), 'guard' => /* اسم الحارس الحالي */] : null]) !!}; </script>

if (window.Laravel && window.Laravel.user && window.Laravel.user.id) {
    let channelName = '';
    // تحديد اسم القناة بناءً على نوع المستخدم (الحارس)
    // هذا يفترض أنك تمرر window.Laravel.user.guard من ה-Blade
    if (window.Laravel.user.guard === 'patient') {
        channelName = `chat2.${window.Laravel.user.id}`;
    } else if (window.Laravel.user.guard === 'doctor') {
        channelName = `chat.${window.Laravel.user.id}`;
    }
    // يمكنك إضافة guards أخرى هنا

    if (channelName) {
        console.log(`Echo: Attempting to subscribe to private channel: ${channelName}`);
        window.Echo.private(channelName) // استخدم private إذا كانت القنوات كذلك في channels.php
            .listen('.Illuminate\\Notifications\\Events\\BroadcastNotificationCreated', (e) => { //  الحدث الافتراضي لـ Laravel Notifications
                console.log('Echo (Header): Real-time Laravel Notification event received on channel ' + channelName, e);
                // e هنا يحتوي على كل بيانات الإشعار من toBroadcast() أو toArray()
                // بما في ذلك e.notification_type

                if (e.notification_type === 'new_chat_message') { // تحقق من نوع الإشعار
                    // 1. تحديث عداد الرسائل المخصص في الهيدر
                    let messageBadge = document.getElementById('headerChatCountBadge');
                    if (messageBadge) {
                        let currentCount = parseInt(messageBadge.innerText) || 0;
                        let newCount = currentCount + 1;
                        messageBadge.innerText = newCount;
                        messageBadge.style.display = newCount > 0 ? 'inline-block' : 'none';
                        console.log('Echo (Header): Updated headerChatCountBadge to', newCount);
                    }

                     // 2. تحديث عداد الإشعارات العامة
                    let generalNotificationsBadge = document.getElementById('generalNotificationsCountBadge'); //  الخاص بجدول notifications
                    if (generalNotificationsBadge) {
                        let currentGeneralCount = parseInt(generalNotificationsBadge.innerText) || 0;
                        let newGeneralCount = currentGeneralCount + 1;
                        generalNotificationsBadge.innerText = newGeneralCount;
                        generalNotificationsBadge.style.display = newGeneralCount > 0 ? 'inline-block' : 'none';
                        console.log('Echo (Header): Updated generalNotificationsCountBadge to', newGeneralCount);
                    }


                    // 3. (اختياري) إضافة الإشعار إلى القائمة المنسدلة للرسائل
                    const messageListContainer = document.getElementById('headerMessageListContainer');
                    if (messageListContainer && e.sender_name && e.message_body_preview && e.created_human && e.link_to_chat) {
                        const newMsgHtml = `
                            <a href="${e.link_to_chat}" class="d-flex p-3 border-bottom dropdown-item">
                                <div class="flex-grow-1">
                                    <div class="d-flex">
                                        <h5 class="notification-user mb-1 small">${e.sender_name}</h5>
                                        <small class="notification-date text-muted ms-auto">${e.created_human}</small>
                                    </div>
                                    <div class="notification-subtext small text-muted">${e.message_body_preview}</div>
                                </div>
                            </a>`;
                        // إزالة رسالة "لا توجد رسائل" إذا كانت موجودة وإضافة الرسالة الجديدة للأعلى
                        const noMessagesEl = messageListContainer.querySelector('.text-center.text-muted');
                        if(noMessagesEl) noMessagesEl.remove();
                        messageListContainer.insertAdjacentHTML('afterbegin', newMsgHtml);
                    }


                    // 4. إظهار تنبيه NotifIt
                    if (typeof notif !== 'undefined') {
                        if (!window.location.pathname.includes('/chat/') && !window.location.pathname.includes('/list/')) {
                             notif({
                                msg: `<div class='d-flex align-items-center p-2'><i class='fas fa-comment-dots fa-lg me-2 text-primary'></i><div style='font-size: 0.95rem;'>رسالة جديدة من <strong>${e.sender_name}</strong>: "${e.message_body_preview}"</div></div>`,
                                type: "info", position: "bottom-right", autohide: true, timeout: 10000,
                                clickable: true,
                                onclick: function() { if(e.link_to_chat && e.link_to_chat !== '#') window.location.href = e.link_to_chat; }
                            });
                        }
                    }
                }
            })
            .error((error) => {
                console.error(`Echo: Subscription error on channel ${channelName}`, error);
            });
    } else {
        console.log('Echo: User guard or ID not available for Echo private channel subscription.');
    }
}
