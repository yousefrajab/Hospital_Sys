@component('mail::message')
{{-- رأس الرسالة (اختياري) --}}
@slot('header')
    @component('mail::header', ['url' => config('app.url')])
        <img src="{{ asset('Dashboard/img/brand/hospital-logo.png') }}" alt="{{ config('app.name') }} Logo" style="max-height: 50px;">
    @endcomponent
    <div style="height: 4px; background: linear-gradient(90deg, #4A90E2, #50E3C2);"></div>
@endslot

{{-- المحتوى الرئيسي للرسالة --}}
<div style="direction: rtl; text-align: right; font-family: 'Cairo', sans-serif; color: #4A4A4A;">

<h1 style="color: #4A90E2; font-weight: 700; margin-bottom: 15px; font-size: 22px;">
    <i class="fas fa-calendar-check" style="color: #2ecc71; margin-left: 10px;"></i> تم تأكيد موعدك بنجاح!
</h1>

<p style="font-size: 15px; line-height: 1.7; margin-bottom: 20px;">
    مرحباً **{{ $name }}**,
    <br>
    يسرنا تأكيد موعدك القادم في {{ config('app.name') }}.
</p>

@component('mail::panel')
    <h2 style="margin-top: 0; margin-bottom: 15px; color: #3f37c9; font-weight: 600; font-size: 18px; text-align: center;">تفاصيل الموعد المؤكد</h2>
    <div style="line-height: 1.8; font-size: 14px;">
        <p><strong> الطبيب:</strong> د. {{ $doctorName }}</p>
        <p><strong> القسم:</strong> {{ $sectionName }}</p>
        <p><strong> التاريخ:</strong> {{ $appointment->translatedFormat('l، d F Y') }}</p>
        <p><strong> الوقت:</strong> {{ $appointment->translatedFormat('h:i A') }}</p>
    </div>
@endcomponent

<h2 style="color: #4A90E2; font-weight: 600; margin-top: 25px; margin-bottom: 12px; font-size: 18px;">
    <i class="fas fa-info-circle" style="color: #3498db; margin-left: 8px;"></i> تعليمات هامة:
</h2>

<ul style="list-style: none; padding-right: 0; margin-bottom: 25px; font-size: 14px;">
    <li style="margin-bottom: 8px;"><i class="fas fa-check-circle" style="color: #2ecc71; margin-left: 8px;"></i> الحضور قبل الموعد بـ 15 دقيقة.</li>
    <li style="margin-bottom: 8px;"><i class="fas fa-id-card" style="color: #2ecc71; margin-left: 8px;"></i> إحضار الهوية وبطاقة التأمين (إن وجدت).</li>
    <li style="margin-bottom: 8px;"><i class="fas fa-file-medical" style="color: #2ecc71; margin-left: 8px;"></i> إحضار التقارير أو الأدوية السابقة (إن وجدت).</li>
</ul>

<p style="font-size: 14px; line-height: 1.7; margin-bottom: 20px;">
    لإلغاء أو تعديل الموعد، يرجى التواصل معنا قبل 24 ساعة على الأقل.
</p>

@component('mail::button', ['url' => $appointmentLink, 'color' => 'primary'])
عرض تفاصيل الموعد
@endcomponent

<p style="font-size: 13px; color: #6c757d; margin-top: 25px; text-align: center;">
    للاستفسار: هاتف <a href="tel:[+رقم الهاتف]" style="color: #3a86ff; text-decoration: none;">[رقم الهاتف]</a> | بريد <a href="mailto:[بريد الدعم]" style="color: #3a86ff; text-decoration: none;">[بريد الدعم]</a>
</p>

</div>

@slot('footer')
    @component('mail::footer')
        © {{ date('Y') }} {{ config('app.name') }}. جميع الحقوق محفوظة.
    @endcomponent
@endslot

@endcomponent
