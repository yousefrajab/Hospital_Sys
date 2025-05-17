<?php

namespace App\Mail;

use App\Models\Appointment; // موديل الموعد
use App\Models\Patient;    // موديل المريض
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentCancelledByPatientToDoctor extends Mailable implements ShouldQueue // استخدام ShouldQueue لإرسال الإيميل في الخلفية (موصى به)
{
    use Queueable, SerializesModels;

    public Appointment $appointment; // الموعد الملغى
    public Patient $cancellingPatient; // المريض الذي قام بالإلغاء

    /**
     * Create a new message instance.
     *
     * @param \App\Models\Appointment $appointment
     * @param \App\Models\Patient $cancellingPatient
     * @return void
     */
    public function __construct(Appointment $appointment, Patient $cancellingPatient)
    {
        $this->appointment = $appointment;
        $this->cancellingPatient = $cancellingPatient;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = 'إلغاء موعد من قبل المريض: ' . $this->cancellingPatient->name;
        $appointmentDateTime = $this->appointment->appointment ? $this->appointment->appointment->translatedFormat('l، j F Y - h:i A') : 'غير محدد';

        return $this->subject($subject)
                    ->markdown('emails.appointments.cancelled_by_patient_to_doctor', [ // سننشئ هذا الـ view
                        'patientName' => $this->cancellingPatient->name,
                        'appointmentTime' => $appointmentDateTime,
                        'doctorName' => $this->appointment->doctor->name ?? 'الطبيب المعالج', // اسم الطبيب (الذي هو أنت)
                        'appointmentId' => $this->appointment->id,
                      
                    ]);
    }
}
