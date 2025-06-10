<?php

namespace App\Notifications; // تأكد من أن الـ namespace صحيح

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; // يمكنك إزالتها إذا لم تكن الإشعارات في قائمة الانتظار
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang; // لاستخدام الترجمات

class DoctorResetPasswordNotification extends Notification // لا تحتاج لـ ShouldQueue افتراضيًا هنا
{
    use Queueable;

    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * The callback that should be used to build the mail message.
     *
     * @var \Closure|null
     */
    public static $toMailCallback;


    /**
     * Create a new notification instance.
     *
     * @param  string  $token
     * @return void
     */
    public function __construct($token) // ** يجب أن يستقبل الـ token **
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable  (هذا هو كائن موديل Doctor)
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }

        // إنشاء رابط إعادة تعيين كلمة المرور الخاص بالأدمن
        // 'doctor.password.reset' هو اسم الـ route الذي عرفناه سابقًا
        // نمرر الـ token والإيميل الخاص بالمستخدم (notifiable)
        $resetUrl = url(route('doctor.password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(), // دالة من موديل Doctor (CanResetPassword)
        ], false)); // false لعدم تضمين التوقيع (signed URL) إذا لم تكن تستخدمه

        return (new MailMessage)
            ->subject(Lang::get('إعادة تعيين كلمة المرور لحساب الطبيب')) // عنوان الإيميل
            ->greeting(Lang::get('مرحباً ') . $notifiable->name . '!') // تحية شخصية
            ->line(Lang::get('لقد تلقيت هذا البريد الإلكتروني لأننا تلقينا طلب إعادة تعيين كلمة المرور لحساب  الطبيب الخاص بك.'))
            ->action(Lang::get('إعادة تعيين كلمة المرور'), $resetUrl) // زر الإجراء
            ->line(Lang::get('رابط إعادة تعيين كلمة المرور هذا سينتهي صلاحيته بعد :count دقائق.', ['count' => config('auth.passwords.doctors.expire')])) // مدة صلاحية الرمز
            ->line(Lang::get('إذا لم تطلب إعادة تعيين كلمة المرور، فلا يلزم اتخاذ أي إجراء آخر.'))
            ->salutation(Lang::get('مع خالص التقدير،') . "<br>" . config('app.name')); // تحية ختامية
    }

    /**
     * Set a callback that should be used when building the mail message.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public static function toMailUsing($callback)
    {
        static::$toMailCallback = $callback;
    }

    /**
     * Get the array representation of the notification.
     * (غير مستخدم عادة لإشعارات البريد ولكن يمكن ملؤه إذا أردت تخزينه في قاعدة البيانات)
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
