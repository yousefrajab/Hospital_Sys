<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; // اجعلها قابلة للـ Queue لأداء أفضل
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentCancelledByDoctor extends Mailable implements ShouldQueue // تطبيق ShouldQueue
{
    use Queueable, SerializesModels;

    public $patientName;
    public $appointmentTime; // الوقت كنص منسق جاهز للعرض
    public $doctorName;
    public $cancelReason;

    /**
     * Create a new message instance.
     *
     * @param string $patientName     اسم المريض
     * @param string $appointmentTime وقت الموعد (نص منسق)
     * @param string $doctorName      اسم الطبيب الذي ألغى
     * @param string $cancelReason    سبب الإلغاء
     * @return void
     */
    public function __construct(string $patientName, string $appointmentTime, string $doctorName, string $cancelReason)
    {
        $this->patientName = $patientName;
        $this->appointmentTime = $appointmentTime;
        $this->doctorName = $doctorName;
        $this->cancelReason = $cancelReason; // تخزين سبب الإلغاء
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // تحديد عنوان البريد والمحتوى من ملف الـ Markdown
        return $this->subject('⚠️ إلغاء موعد مع د. ' . $this->doctorName) // عنوان واضح
                    ->markdown('emails.appointments.cancelled_by_doctor'); // اسم ملف الـ view
    }
}
