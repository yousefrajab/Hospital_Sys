<?php

 namespace App\Mail;

 use Illuminate\Bus\Queueable;
 use Illuminate\Contracts\Queue\ShouldQueue; // يمكنك إضافتها للـ Queue
 use Illuminate\Mail\Mailable;
 use Illuminate\Queue\SerializesModels;
 use Illuminate\Support\Carbon; // استيراد Carbon

 class AppointmentConfirmation extends Mailable // implements ShouldQueue
 {
     use Queueable, SerializesModels;

     public $name;          // *** اسم المريض (كما كان) ***
     public Carbon $appointment; // *** كائن الوقت (كما كان، ولكن تأكد من النوع Carbon) ***

     // *** متغيرات إضافية اختيارية (يمكن جلبها هنا أو تمريرها) ***
     public $doctorName;
     public $sectionName;
     public $appointmentLink;

     /**
      * Create a new message instance.
      *
      * @param string $name اسم المريض
      * @param \Illuminate\Support\Carbon $appointment وقت الموعد (كائن Carbon)
      * @param string|null $doctorName اسم الطبيب (اختياري)
      * @param string|null $sectionName اسم القسم (اختياري)
      * @return void
      */
     public function __construct($name, Carbon $appointment, $doctorName = null, $sectionName = null)
     {
         $this->name = $name; // استخدام نفس الاسم $name
         $this->appointment = $appointment; // استخدام نفس الاسم $appointment
         $this->doctorName = $doctorName ?? 'الطبيب المعالج'; // قيمة افتراضية
         $this->sectionName = $sectionName ?? 'القسم المختص'; // قيمة افتراضية
          // إنشاء رابط افتراضي (يمكن تعديله)
         $this->appointmentLink = url('/'); // رابط للصفحة الرئيسية كمثال
     }

     /**
      * Build the message.
      *
      * @return $this
      */
     public function build()
     {
         // استخدام اسم الـ view الأصلي لديك إذا كان مختلفاً
         return $this->subject('✅ تأكيد موعد في ' . config('app.name')) // تعديل العنوان
                     ->markdown('emails.appointments'); // اسم الـ view الأصلي
     }
 }
