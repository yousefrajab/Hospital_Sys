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
        'name', // مهم إذا كنت تستخدم $patient->name = ... ثم save() أو create/update مع الاسم
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

    public function generateQrCode(int $size = 150, int $margin = 1): string
    {
        if (!$this->id) {
            \Illuminate\Support\Facades\Log::warning("Attempted to generate QR code for a patient without an ID.");
            return '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 20 20"><text x="0" y="15" fill="red">Error: No ID</text></svg>'; // إرجاع SVG خطأ
        }

        try {

            $url = route('admin.Patients.show', $this->id);



            return QrCode::size($size)
                         ->style('round')
                         ->eye('circle') // لجعل "عيون" الـ QR دائرية (اختياري)
                         ->margin($margin)
                         // يمكنك إضافة ألوان إذا أردت:
                         ->color(79, 70, 229) // (R, G, B) - --admin-primary
                         ->backgroundColor(255, 255, 255) // أبيض
                         ->generate($url);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Error generating QR code for Patient ID {$this->id}: " . $e->getMessage());
            // إرجاع SVG بسيط يشير إلى خطأ بدلاً من كسر الصفحة
            return '<svg width="'.$size.'" height="'.$size.'" viewBox="0 0 100 20"><text x="0" y="15" fill="red">QR Error</text></svg>';
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


            // ** إذا كنت لا تزال تريد عرض الحساسيات من حقل نصي منفصل **
            // if (!empty($this->allergies)) {
            //     $infoLines[] = "حساسيات: " . Str::limit($this->allergies, 40);
            // }

            // إضافة رابط الملف الشخصي الكامل
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
}
