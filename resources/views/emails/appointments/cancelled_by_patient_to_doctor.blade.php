{{-- resources/views/emails/appointments/cancelled_by_patient_to_doctor.blade.php --}}
@component('mail::message')
# إلغاء موعد

مرحباً دكتور **{{ $doctorName }}**,

نود إعلامك بأن المريض **{{ $patientName }}** قام بإلغاء الموعد المحدد بالتفاصيل التالية:

**تفاصيل الموعد الملغى:**
*   **وقت الموعد:** {{ $appointmentTime }}
*   **معرف الموعد:** #{{ $appointmentId }}

يرجى أخذ العلم بذلك.

@component('mail::button', ['url' => route('doctor.appointments')]) {{-- رابط لقائمة مواعيد الطبيب --}}
عرض مواعيدي
@endcomponent

شكراً لك،<br>
{{ config('app.name') }}
@endcomponent
