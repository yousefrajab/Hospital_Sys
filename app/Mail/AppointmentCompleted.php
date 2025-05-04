<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Appointment; // استيراد الموعد

class AppointmentCompleted extends Mailable // implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $patientName;
    public $doctorName;
    public Appointment $appointment; // تمرير الموعد كاملاً

    /**
     * Create a new message instance.
     *
     * @param string $patientName
     * @param string $doctorName
     * @param Appointment $appointment
     * @return void
     */
    public function __construct(string $patientName, string $doctorName, Appointment $appointment)
    {
        $this->patientName = $patientName;
        $this->doctorName = $doctorName;
        $this->appointment = $appointment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // عنوان ورسالة مناسبة لانتهاء الموعد
        return $this->subject('شكراً لزيارتك لعيادة د. ' . $this->doctorName)
                    ->markdown('emails.appointments.completed');
    }
}
