@component('mail::message')
{{-- يمكنك إضافة لوجو المستشفى هنا إذا أردت --}}
{{-- @component('mail::header', ['url' => config('app.url')]) ... @endcomponent --}}

# ⚠️ إشعار بإلغاء موعد

عزيزي/عزيزتي **{{ $patientName }}**,

نأسف لإبلاغك بأنه تم إلغاء موعدك المحدد مع **د. {{ $doctorName }}** في التاريخ والوقت التالي:

@component('mail::panel')
**{{ $appointmentTime }}**
@endcomponent

السبب المذكور للإلغاء:
> {{ $cancelReason }}

نعتذر عن أي إزعاج قد يسببه هذا الإلغاء. يمكنك زيارة موقعنا الإلكتروني أو التواصل معنا مباشرة لترتيب موعد بديل في وقت آخر يناسبك.

@component('mail::button', ['url' => url('/'), 'color' => 'primary']) {{-- رابط للصفحة الرئيسية أو صفحة المواعيد --}}
حجز موعد جديد
@endcomponent

للاستفسار، يمكنك التواصل معنا على:
*   الهاتف: [أدخل رقم هاتف المستشفى]
*   البريد الإلكتروني: [أدخل بريد الدعم]

مع خالص التقدير،<br>
فريق عمل د. {{ $doctorName }}<br>
{{ config('app.name') }}

@endcomponent
