<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\Notifiable;
use Astrotomic\Translatable\Translatable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use App\Notifications\PatientResetPasswordNotification; // تأكد من وجود هذا الملف
use SimpleSoftwareIO\QrCode\Facades\QrCode; // ** استيراد Facade لمكتبة QR Code **

class Patient extends Authenticatable implements TranslatableContract, CanResetPassword
{
    use HasFactory, Notifiable, Translatable, CanResetPasswordTrait;

    public $translatedAttributes = ['name', 'Address'];

    // مصفوفة $fillable كما هي لديك (مع التأكد من تضمين name و Address إذا كنت تستخدم create أو update معهم)
    public $fillable = [
        'national_id',
        // 'name', // مهم إذا كنت تستخدم $patient->name = ... ثم save() أو create/update مع الاسم
        'email',
        'password',
        'Date_Birth',
        'Phone',
        'Gender',
        'Blood_Group',
        'Address', // مهم إذا كنت تستخدم $patient->Address = ... ثم save() أو create/update مع العنوان
        'allergies', // إذا أضفت هذه الأعمدة
        'chronic_diseases', // إذا أضفت هذه الأعمدة
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'Date_Birth' => 'date',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    public function Service()
    {
        return $this->belongsTo(Service::class, 'Service_id');
    }

    public function employee()
    {
        return $this->belongsTo(RayEmployee::class, 'RayEmployee_id');
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function diseases() // هذه قد تكون عامة (كل الأمراض المرتبطة)
    {
        return $this->belongsToMany(Disease::class, 'disease_patient') // افترض أن الجدول الوسيط اسمه disease_patient
            ->withTimestamps();
    }

    public function admissions()
    {
        return $this->hasMany(PatientAdmission::class);
    }

    public function currentAdmission()
    {
        return $this->hasOne(PatientAdmission::class)
            ->whereNull('discharge_date')
            ->where('status', PatientAdmission::STATUS_ADMITTED);
    }

    public function currentBed()
    {
        return $this->hasOneThrough(
            Bed::class,
            PatientAdmission::class,
            'patient_id',
            'id',
            'id',
            'bed_id'
        )->whereNull('patient_admissions.discharge_date')
            ->where('patient_admissions.status', PatientAdmission::STATUS_ADMITTED);
    }

    public function chronicDiseasesRecords() // الاسم القديم كان diseases()، تم تغييره ليكون أوضح
    {
        // هذه العلاقة للوصول إلى سجلات الجدول الوسيط patient_chronic_diseases
        return $this->hasMany(PatientChronicDisease::class);
    }

    public function diagnosedChronicDiseases()
    {
        // هذه العلاقة للوصول إلى موديلات Disease نفسها مع بيانات الجدول الوسيط
        return $this->belongsToMany(Disease::class, 'patient_chronic_diseases') // اسم الجدول الوسيط الصحيح
            // يمكنك إضافة فلتر هنا إذا أردت فقط الأمراض التي is_chronic = true من جدول diseases
            // ->wherePivot('is_chronic_for_patient', true) // إذا كان لديك هذا العمود في الجدول الوسيط
            // أو ->whereHas('diseaseDetail', function($q){ $q->where('is_chronic', true); }) إذا كانت العلاقة مختلفة
            ->withPivot('id', 'diagnosed_at', 'diagnosed_by', 'current_status', 'treatment_plan', 'notes')
            ->withTimestamps()
            ->orderBy('pivot_diagnosed_at', 'desc');
    }

    public function generateQrCode(int $size = 220, int $margin = 2, string $errorCorrection = 'M'): string
    {
        if (!$this->id) {
            Log::warning("Patient Model (generateQrCodeSvg): Attempted for a patient without an ID.");
            return '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 120 30" xmlns="http://www.w3.org/2000/svg"><rect width="100%" height="100%" fill="#fee2e2"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" font-family="sans-serif" font-size="9" fill="#ef4444">خطأ:معرف مريض غير متوفر</text></svg>';
        }

        try {
            $infoLines = [];
            $locale = app()->getLocale();

            // 1. المعلومات الأساسية من جدول patients
            $patientName = $this->getTranslation('name', $locale, false) ?: $this->name; // استخدام الترجمة
            $infoLines[] = "الاسم: " . ($patientName ?? 'غير متوفر');
            $infoLines[] = "الهوية: " . ($this->national_id ?? 'غير متوفر');
            $infoLines[] = "ت.الميلاد: " . ($this->Date_Birth ? \Carbon\Carbon::parse($this->Date_Birth)->format('Y-m-d') : 'غير متوفر');
            $infoLines[] = "العمر: " . ($this->Date_Birth ? \Carbon\Carbon::parse($this->Date_Birth)->age . ' سنة' : '-');
            $infoLines[] = "الجنس: " . ($this->Gender == 1 ? 'ذكر' : ($this->Gender == 2 ? 'أنثى' : '-'));
            $infoLines[] = "فصيلة الدم: " . ($this->Blood_Group ?: 'غير محددة');

            // 2. الحساسيات (من الحقل النصي 'allergies' في جدول patients)
            if (!empty($this->allergies)) {
                $infoLines[] = "حساسيات: " . Str::limit($this->allergies, 50); // تحديد الطول
            }

            // 3. الأمراض المزمنة
            $chronicDisplay = [];
            // أولاً، من العلاقة المنظمة (diagnosedChronicDiseases)
            if ($this->relationLoaded('diagnosedChronicDiseases') && $this->diagnosedChronicDiseases->isNotEmpty()) {
                foreach ($this->diagnosedChronicDiseases->take(2) as $disease) { // $disease هو كائن Disease
                    // افترض أن موديل Disease لديه خاصية 'name' (مترجمة أو عادية)
                    $chronicDisplay[] = $disease->name;
                }
            }
            // ثانياً، كـ fallback من الحقل النصي 'chronic_diseases' في جدول patients
            // (إذا لم تكن هناك أمراض منظمة أو إذا كنت تريد إضافة النص أيضًا)
            if (!empty($this->chronic_diseases) && empty($chronicDisplay)) { // اعرض النص فقط إذا لم نجد أمراضًا منظمة
                $chronicDisplay[] = "مسجل كنص: " . Str::limit($this->chronic_diseases, 40);
            }

            if (!empty($chronicDisplay)) {
                $infoLines[] = "أمراض مزمنة/مشخصة: " . implode('، ', $chronicDisplay);
            }

            // 4. (اختياري) معلومات الاتصال بالطوارئ (إذا أضفت هذه الحقول لموديل Patient)
            // if (!empty($this->emergency_contact_name) && !empty($this->emergency_contact_phone)) {
            //     $infoLines[] = "اتصال طوارئ: {$this->emergency_contact_name} ({$this->emergency_contact_phone})";
            // }

            // 5. إضافة رابط الملف الشخصي الكامل (الذي يعرضه الأدمن)
            // هذا الرابط مهم جدًا ويجب الحفاظ عليه
            $profileUrl = null;
            try {
                $profileUrl = route('admin.Patients.show', $this->id); // ** الرابط لا يزال هنا **
                $infoLines[] = "ملف إلكتروني (إنترنت): الخاص بالأدمن " . $profileUrl;
            } catch (\Exception $routeException) {
                Log::warning("Could not generate admin patient profile URL for QR (Patient ID: {$this->id}): " . $routeException->getMessage());
                // لا تضف الرابط إذا فشل إنشاؤه، لكن لا توقف العملية كلها
                $infoLines[] = "ملف إلكتروني (إنترنت): الرابط غير متاح حاليًا";
            }

            $qrText = implode("\n", $infoLines); // استخدام فاصل أسطر

            Log::info("Patient Model (generateQrCodeSvg): Generating QR for Patient ID {$this->id}. Content length: " . mb_strlen($qrText) . ". Content: [{$qrText}]");
            if (mb_strlen($qrText) > 350) { // تحذير إذا كان النص طويلًا جدًا (يمكنك تعديل هذا الحد)
                Log::warning("QR Code content is very long for Patient ID {$this->id}, QR might be complex or unreadable by some scanners. Consider reducing data or increasing error correction to 'Q' or 'H' if absolutely necessary.");
            }

            return QrCode::format('svg')
                ->size($size)
                ->style('round')
                ->eye('circle')
                ->margin($margin)
                ->errorCorrection($errorCorrection) // 'M' جيد، يمكنك رفعه إلى 'Q' إذا كان النص طويلاً
                ->encoding('UTF-8') // مهم للأحرف العربية
                ->generate($qrText); // ** تشفير النص المجمع **

        } catch (\Exception $e) {
            Log::error("Patient Model (generateQrCodeSvg): Error generating QR code for Patient ID {$this->id}. Error: " . $e->getMessage(), ['trace' => substr($e->getTraceAsString(), 0, 250)]);
            return '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 100 30" xmlns="http://www.w3.org/2000/svg"><rect width="100%" height="100%" fill="#fee2e2"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" font-family="sans-serif" font-size="9" fill="#ef4444">خطأ في إنشاء QR</text></svg>';
        }
    }

    public function generateQrCodeSvg(int $size = 220, int $margin = 2, string $errorCorrection = 'M'): string
    {
        if (!$this->id) {
            Log::warning("Patient Model (generateQrCodeSvg): Attempted for a patient without an ID.");
            return '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 120 30" xmlns="http://www.w3.org/2000/svg"><rect width="100%" height="100%" fill="#fee2e2"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" font-family="sans-serif" font-size="10" fill="#ef4444">خطأ:معرف مريض غير متوفر</text></svg>';
        }

        try {
            $infoLines = [];
            $infoLines[] = "الاسم: " . ($this->name ?? 'غير متوفر');
            $infoLines[] = "الهوية: " . ($this->national_id ?? 'غير متوفر');
            $infoLines[] = "ت.الميلاد: " . ($this->Date_Birth ? \Carbon\Carbon::parse($this->Date_Birth)->format('Y-m-d') : 'غير متوفر');
            $infoLines[] = "العمر: " . ($this->Date_Birth ? \Carbon\Carbon::parse($this->Date_Birth)->age . ' سنة' : '-');
            $infoLines[] = "فصيلة الدم: " . ($this->Blood_Group ?: 'غير محددة');

            // ** عرض الأمراض المشخصة من العلاقة diagnosedChronicDiseases **
            $chronicDiseasesFromRelation = [];
            // تأكد أن العلاقة محملة وأنها ليست فارغة
            if ($this->relationLoaded('diagnosedChronicDiseases') && $this->diagnosedChronicDiseases->isNotEmpty()) {
                foreach ($this->diagnosedChronicDiseases->take(3) as $diagnosedDisease) { // عرض أول 3 أمراض للاختصار في QR
                    // $diagnosedDisease هنا هو كائن Disease
                    // يجب أن يكون لموديل Disease خاصية 'name' (يفترض أنها مترجمة إذا كان موديل Disease يستخدم Translatable)
                    $chronicDiseasesFromRelation[] = $diagnosedDisease->name; // اسم المرض
                }
            }

            if (!empty($chronicDiseasesFromRelation)) {
                $infoLines[] = "أمراض مشخصة: " . implode('، ', $chronicDiseasesFromRelation);
            }
            // ** يمكنك إزالة أو الإبقاء على قراءة الحقل النصي chronic_diseases كـ fallback **
            elseif (!empty($this->chronic_diseases)) { // إذا لم تكن هناك أمراض منظمة، اعرض النص
                $infoLines[] = "أمراض مزمنة (نص): " . Str::limit($this->chronic_diseases, 40);
            }
            try {
                $profileUrl = route('admin.Patients.show', $this->id);
                $infoLines[] = "ملف إلكتروني (إنترنت): خاص بالأدمن" . $profileUrl;
            } catch (\Exception $routeException) {
                Log::warning("Could not generate admin patient profile URL for QR (Patient ID: {$this->id}): " . $routeException->getMessage());
            }

            $qrText = implode("\n", $infoLines);
            Log::info("Patient Model (generateQrCodeSvg): Generating QR for Patient ID {$this->id}. Content: " . $qrText);

            return QrCode::format('svg')
                ->size($size)->style('round')->eye('circle')->margin($margin)
                ->errorCorrection($errorCorrection)->encoding('UTF-8')
                ->generate($qrText);
        } catch (\Exception $e) {
            Log::error("Patient Model (generateQrCodeSvg): Error generating QR code for Patient ID {$this->id}. Error: " . $e->getMessage(), ['trace' => substr($e->getTraceAsString(), 0, 250)]);
            return '<svg width="' . $size . '" height="' . $size . '" viewBox="0 0 100 30" xmlns="http://www.w3.org/2000/svg"><rect width="100%" height="100%" fill="#fee2e2"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" font-family="sans-serif" font-size="10" fill="#ef4444">خطأ في إنشاء QR</text></svg>';
        }
    }
    // =========================================================================
    // ***** نهاية الدالة الجديدة المضافة *****
    // =========================================================================


    public function sendPasswordResetNotification($token)
    {
        // تأكد من أن كلاس PatientResetPasswordNotification موجود في المسار الصحيح
        if (class_exists(PatientResetPasswordNotification::class)) {
            $this->notify(new PatientResetPasswordNotification($token));
        } else {
            \Illuminate\Support\Facades\Log::warning("PatientResetPasswordNotification class not found for Patient ID: {$this->id}");
            // يمكنك هنا استخدام إشعار Laravel الافتراضي كـ fallback إذا أردت
            // parent::sendPasswordResetNotification($token);
        }
    }


    public function appointments()
    {
        return $this->hasMany(Appointment::class)->orderBy('appointment', 'desc'); // ** استخدام 'appointment' **
    }

    public function upcomingAppointments()
    {
        return $this->hasMany(Appointment::class)
            ->where('appointment', '>=', now())
            ->whereIn('type', [Appointment::STATUS_PENDING, Appointment::STATUS_CONFIRMED]) // ** هنا **
            ->orderBy('appointment', 'asc');
    }
    public function pastAppointments()
    {
        return $this->hasMany(Appointment::class)
            ->where('appointment', '<', now()) // ** استخدام 'appointment' **
            ->orWhereIn('type', [             // ** استخدام 'type' **
                Appointment::STATUS_COMPLETED,
                Appointment::STATUS_CANCELLED, // افترض أن لديك حالة إلغاء عامة
                
            ])
            ->orderBy('appointment', 'desc');    // ** استخدام 'appointment' **
    }



    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(\App\Models\Prescription::class)->orderBy('prescription_date', 'desc');
    }

    public function PrescriptionItem()
    {
        return $this->belongsTo(PrescriptionItem::class);
    }
}
