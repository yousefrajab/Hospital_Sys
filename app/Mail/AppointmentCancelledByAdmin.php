<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; // لجعله قابلاً للـ Queue (موصى به)
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentCancelledByAdmin extends Mailable // implements ShouldQueue // (اختياري)
{
    use Queueable, SerializesModels;

    public $patientName;
    public $appointmentTime;
    public $doctorName;
    public $cancelReason;
    public $isForDoctor; // لتحديد إذا كانت الرسالة للطبيب

    /**
     * Create a new message instance.
     *
     * @param string $patientName
     * @param string $appointmentTime (الوقت منسق كنص)
     * @param string $doctorName
     * @param string $cancelReason
     * @param bool $isForDoctor (افتراضي false للمريض)
     * @return void
     */
    public function __construct(string $patientName, string $appointmentTime, string $doctorName, string $cancelReason, bool $isForDoctor = false)
    {
        $this->patientName = $patientName;
        $this->appointmentTime = $appointmentTime;
        $this->doctorName = $doctorName;
        $this->cancelReason = $cancelReason;
        $this->isForDoctor = $isForDoctor;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // تحديد عنوان البريد بناءً على المستلم
        $subject = $this->isForDoctor ? 'إلغاء موعد بواسطة الإدارة' : 'إلغاء موعدك في المستشفى';

        return $this->subject($subject)
                    ->markdown('emails.appointments.cancelled_by_admin'); // استخدام الـ view الجديد
    }
}
