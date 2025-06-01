<?php

namespace App\Repository\Patients;

use App\Models\Ray;
use App\Models\Disease;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Laboratorie;
use App\Traits\UploadTrait;
use Illuminate\Support\Str;
use App\Models\PatientAccount;
use App\Models\ReceiptAccount;
use App\Models\single_invoice;
use Illuminate\Support\Carbon;
use App\Models\PatientAdmission;
use Illuminate\Support\Facades\DB;
// use Illuminate\Database\Eloquent\Model; // ليس ضروريًا هنا
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use Illuminate\Http\Request; // تم إضافتها بشكل صحيح
use App\Interfaces\Patients\PatientRepositoryInterface;
use App\Models\PatientChronicDisease; // ** استيراد هذا الموديل **

class PatientRepository implements PatientRepositoryInterface
{
    use UploadTrait;

    public function index(Request $request)
    {
        $totalPatients = Patient::count();
        $admittedPatientsCount = PatientAdmission::where('status', PatientAdmission::STATUS_ADMITTED)
            ->whereNull('discharge_date')
            ->distinct('patient_id')
            ->count('patient_id');
        $newPatientsThisMonth = Patient::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $query = Patient::with([
            'image',
            'currentAdmission' => function ($q) {
                $q->with(['bed.room.section']); // تحميل متداخل
            }
        ]);

        if ($request->filled('search_patient')) {
            $searchTerm = $request->search_patient;
            $query->where(function ($q) use ($searchTerm) {
                // إذا كان name مترجمًا
                $q->whereTranslationLike('name', "%{$searchTerm}%")
                    ->orWhere('national_id', 'like', "%{$searchTerm}%")
                    ->orWhere('email', 'like', "%{$searchTerm}%")
                    ->orWhere('Phone', 'like', "%{$searchTerm}%");
            });
        }
        if ($request->filled('gender_filter')) {
            $query->where('Gender', $request->gender_filter);
        }
        if ($request->filled('blood_group_filter')) {
            $query->where('Blood_Group', $request->blood_group_filter);
        }
        if ($request->filled('admission_status_filter')) {
            if ($request->admission_status_filter == 'admitted') {
                $query->whereHas('currentAdmission');
            } elseif ($request->admission_status_filter == 'not_admitted') {
                $query->whereDoesntHave('currentAdmission');
            }
        }

        $Patients = $query->orderBy('created_at', 'desc')->paginate(20)->appends($request->query());

        $genders = [1 => 'ذكر', 2 => 'أنثى'];
        $bloodGroups = ['O-', 'O+', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-'];
        $admissionStatusesFilter = [
            'admitted' => 'مقيم حاليًا',
            'not_admitted' => 'غير مقيم حاليًا',
        ];

        return view('Dashboard.Patients.index', compact(
            'Patients',
            'request',
            'totalPatients',
            'admittedPatientsCount',
            'newPatientsThisMonth',
            'genders',
            'bloodGroups',
            'admissionStatusesFilter'
        ));
    }

    public function create()
    {
        // جلب قائمة الأمراض المزمنة فقط للاختيار منها
        $diseases_list = Disease::where('is_chronic', true)
            ->orderBy('name') // افترض أن name عمود عادي في Disease
            ->pluck('name', 'id');

        // جلب قيم Enum لحالة المرض المزمن للمريض
        $chronic_disease_statuses = PatientChronicDisease::getStatuses();

        Log::info("PatientRepository@create: Loading create patient form.");
        return view('Dashboard.Patients.create', compact('diseases_list', 'chronic_disease_statuses'));
    }

    public function store(StorePatientRequest $request)
    {
        DB::beginTransaction();
        try {
            // البيانات الأساسية (باستثناء الحقول المترجمة مباشرة والحقول الخاصة)
            $patientData = $request->except([
                'name',
                'Address', // ستتم معالجتها بواسطة Translatable
                'photo',
                '_token',
                '_method',
                'password_confirmation',
                'terms',
                'chronic_diseases' // اسم الحقل المجمع للأمراض المزمنة
            ]);
            $patientData['password'] = Hash::make($request->password);

            $patient = Patient::create($patientData); // هذا سيملأ الحقول في $fillable

            // تعيين الحقول المترجمة
            $patient->name = $request->name;
            $patient->Address = $request->Address;
            $patient->save(); // حفظ الكائن مع الترجمات
            Log::info("PatientRepository@store: Patient base data saved. ID: {$patient->id}");

            // حفظ الأمراض المزمنة
            if ($request->has('chronic_diseases') && is_array($request->chronic_diseases)) {
                $syncData = [];
                foreach ($request->chronic_diseases as $diseaseInput) {
                    if (!empty($diseaseInput['disease_id'])) {
                        $syncData[$diseaseInput['disease_id']] = [
                            'diagnosed_at' => $diseaseInput['diagnosed_at'] ?? null,
                            'diagnosed_by' => $diseaseInput['diagnosed_by'] ?? null,
                            'current_status' => $diseaseInput['current_status'] ?? null,
                            'treatment_plan' => $diseaseInput['treatment_plan'] ?? null,
                            'notes' => $diseaseInput['notes'] ?? null,
                        ];
                    }
                }
                if (!empty($syncData)) {
                    $patient->diagnosedChronicDiseases()->sync($syncData);
                    Log::info("PatientRepository@store: Synced chronic diseases for Patient ID: {$patient->id}", $syncData);
                }
            }

            if ($request->hasFile('photo')) {
                $this->verifyAndStoreImage($request, 'photo', 'patients', 'upload_image', $patient->id, Patient::class);
            }

            DB::commit();
            session()->flash('add', 'تم تسجيل المريض وبياناته الصحية بنجاح.');
            return redirect()->route('admin.Patients.index');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error("PatientRepository@store: Validation exception.", ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("PatientRepository@store: General error: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->back()->withInput()->with('error', 'حدث خطأ أثناء تسجيل المريض: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $Patient = Patient::with([
            'image',
            'diagnosedChronicDiseases', // جلب تفاصيل المرض نفسه
            'admissions' => function ($query) {
                $query->with(['doctor.translations', 'bed.room.section.translations'])->orderBy('admission_date', 'desc');
            }
        ])->findOrFail($id);

        // لتجنب تكرار استدعاء invoices، حدد أي واحدة تريدها أو استخدم اسمًا مختلفًا
        $invoices = single_invoice::where('patient_id', $id)->get();
        $invoices = Invoice::where('patient_id', $id)->get();
        $receipt_accounts = ReceiptAccount::where('patient_id', $id)->get();
        $Patient_accounts = PatientAccount::where('patient_id', $id)->get();
        $patient_rays = Ray::where('patient_id', $id)
            ->with(['doctor', 'employee'])
            ->orderBy('created_at', 'desc')->get();

        $patient_Laboratories = Laboratorie::where('patient_id', $id)
            ->with(['doctor', 'employee'])
            ->orderBy('created_at', 'desc')->get();
        $patient = Patient::with([
            'image',
            'diagnosedChronicDiseases',
            'prescription' => function ($query) {
                $query->with(['doctor'])
                    ->orderBy('prescription_date', 'desc')->take(10);
            },
            'admissions' => function ($query) {
                $query->with(['bed.room.section', 'doctor'])
                    ->orderBy('admission_date', 'desc')->take(5);
            }
        ])->find($id);

        if (!$patient) {
            Log::error("PatientDetailsController@index: Patient with ID {$id} not found.");
            abort(404, 'المريض المطلوب غير موجود.');
        }

        return view('Dashboard.Patients.show', compact(
            'Patient',
            'invoices',
            'receipt_accounts',
            'Patient_accounts',
            'patient_rays',
            'patient_Laboratories',
            'patient',
        ));
    }
    public function showQR($id = null)
    {
        $patient = null;
        if ($id) {
            $patient = Patient::with([
                'image',
                // ** التأكد من تحميل علاقة disease داخل diagnosedChronicDiseases **
                // إذا كانت diagnosedChronicDiseases هي belongsToMany(Disease::class, 'patient_chronic_diseases')
                // فإن $diagnosedDisease سيكون كائن Disease مباشرة.
                // إذا كانت diagnosedChronicDiseases هي hasMany(PatientChronicDisease::class)
                // ستحتاج لـ 'diagnosedChronicDiseases.disease'
                'diagnosedChronicDiseases' // إذا كانت هذه العلاقة belongsToMany(Disease::class, ...)
                // 'diagnosedChronicDiseases.disease' // إذا كانت diagnosedChronicDiseases هي hasMany(PatientChronicDisease::class)
            ])->find($id);
        } else {
            $patient = Auth::guard('patient')->user();
            if ($patient) {
                $patient->load(['image', 'diagnosedChronicDiseases.diseases']); // أو 'diagnosedChronicDiseases.disease'
            }
        }

        if (!$patient) {
            Log::warning("showQR: Patient not found or not authenticated.");
            return redirect()->route('home')->with('error', 'المريض غير موجود.');
        }

        // استدعاء الدالة من الموديل
        $qrCodeSvg = $patient->generateQrCodeSvg(220, 2, 'M');

        // تحضير المعلومات للعرض النصي في صفحة الويب
        $displayInfo = [];
        $displayInfo['الاسم'] = $patient->name;
        $displayInfo['الهوية'] = $patient->national_id;
        $displayInfo['ت.الميلاد'] = $patient->Date_Birth ? \Carbon\Carbon::parse($patient->Date_Birth)->format('Y-m-d') : '-';
        $displayInfo['العمر'] = $patient->Date_Birth ? \Carbon\Carbon::parse($patient->Date_Birth)->age . ' سنة' : '-';
        $displayInfo['الجنس'] = $patient->Gender == 1 ? 'ذكر' : ($patient->Gender == 2 ? 'أنثى' : '-');
        $displayInfo['فصيلة الدم'] = $patient->Blood_Group ?: 'غير محددة';

        // الأمراض المشخصة (من العلاقة)
        $chronicDisplayList = [];
        if ($patient->relationLoaded('diagnosedChronicDiseases') && $patient->diagnosedChronicDiseases->isNotEmpty()) {
            foreach ($patient->diagnosedChronicDiseases->take(3) as $diagnosedDiseaseInstance) {
                // $diagnosedDiseaseInstance هو كائن Disease
                $chronicDisplayList[] = $diagnosedDiseaseInstance->name;
            }
        }
        if (!empty($chronicDisplayList)) {
            $displayInfo['الأمراض المشخصة'] = implode('، ', $chronicDisplayList);
        }






        // الرابط الكامل
        try {
            $displayInfo['ملف إلكتروني (يتطلب إنترنت) / خاص بالأدمن'] = route('admin.Patients.show', $patient->id);
        } catch (\Exception $e) {
            $displayInfo['ملف إلكتروني (يتطلب إنترنت)'] = 'الرابط غير متوفر حاليًا';
        }

        return view('Dashboard.Patients.showQR', compact(
            'patient',
            'qrCodeSvg',
            'displayInfo',

        ));
    }



    public function edit($id)
    {
        $Patient = Patient::with(['diagnosedChronicDiseases', 'image'])->findOrFail($id);
        $diseases_list = Disease::where('is_chronic', true)->orderBy('name')->pluck('name', 'id');
        $chronic_disease_statuses = PatientChronicDisease::getStatuses();

        $patientExistingChronicDiseases = [];
        foreach ($Patient->diagnosedChronicDiseases as $diagnosedDisease) {
            // $diagnosedDisease هو كائن Disease
            // $diagnosedDisease->pivot هو كائن PatientChronicDisease (بيانات الجدول الوسيط)
            $patientExistingChronicDiseases[] = [ // تعديل ليكون مصفوفة من الكائنات/المصفوفات
                'disease_id' => $diagnosedDisease->id,
                'disease_name' => $diagnosedDisease->name, // اسم المرض للعرض
                'diagnosed_at' => $diagnosedDisease->pivot->diagnosed_at ? \Carbon\Carbon::parse($diagnosedDisease->pivot->diagnosed_at)->format('Y-m-d') : null,
                'diagnosed_by' => $diagnosedDisease->pivot->diagnosed_by,
                'current_status' => $diagnosedDisease->pivot->current_status,
                'treatment_plan' => $diagnosedDisease->pivot->treatment_plan,
                'notes' => $diagnosedDisease->pivot->notes,
                'pivot_record_id' => $diagnosedDisease->pivot->id // ID سجل patient_chronic_diseases نفسه (مفيد إذا أردت تحديث سجل معين)
            ];
        }
        Log::info("PatientRepository@edit: Loading edit patient form for ID: {$id}");
        return view('Dashboard.Patients.edit', compact('Patient', 'diseases_list', 'chronic_disease_statuses', 'patientExistingChronicDiseases'));
    }

    public function update(UpdatePatientRequest $request)
    {
        $patientId = $request->input('id'); // الحصول على ID من الحقل المخفي (كما هو في الكود الأصلي)
        $patient = Patient::findOrFail($patientId);

        Log::info("PatientRepository@update: Attempting to update Patient ID: {$patient->id}", $request->except(['_token', '_method', 'password', 'password_confirmation', 'photo']));
        DB::beginTransaction();
        try {
            // البيانات الأساسية (باستثناء الحقول المترجمة مباشرة وكلمة المرور والصورة)
            $patientData = $request->except([
                'name',
                'Address',
                'password',
                'password_confirmation',
                'photo',
                '_token',
                '_method',
                'terms',
                'id',
                'chronic_diseases'
            ]);
            $patient->update($patientData);

            // تحديث الترجمات
            $patient->name = $request->name;
            $patient->Address = $request->Address;

            if ($request->filled('password')) {
                $patient->password = Hash::make($request->password);
            }
            $patient->save();
            Log::info("PatientRepository@update: Patient base data, translations, and password (if any) saved for ID: {$patient->id}");

            // تحديث/مزامنة الأمراض المزمنة
            if ($request->has('chronic_diseases') && is_array($request->chronic_diseases)) {
                $syncData = [];
                foreach ($request->chronic_diseases as $diseaseInput) {
                    if (!empty($diseaseInput['disease_id'])) {
                        $syncData[$diseaseInput['disease_id']] = [
                            'diagnosed_at' => $diseaseInput['diagnosed_at'] ?? null,
                            'diagnosed_by' => $diseaseInput['diagnosed_by'] ?? null,
                            'current_status' => $diseaseInput['current_status'] ?? null,
                            'treatment_plan' => $diseaseInput['treatment_plan'] ?? null,
                            'notes' => $diseaseInput['notes'] ?? null,
                        ];
                    }
                }
                $patient->diagnosedChronicDiseases()->sync($syncData);
                Log::info("PatientRepository@update: Synced chronic diseases for Patient ID: {$patient->id}", $syncData);
            } else {
                $patient->diagnosedChronicDiseases()->detach();
                Log::info("PatientRepository@update: Detached all chronic diseases for Patient ID: {$patient->id}");
            }

            if ($request->hasFile('photo')) {
                if ($patient->image) {
                    $this->Delete_attachment('upload_image', 'patients/' . $patient->image->filename, $patient->id, Patient::class);
                }
                $this->verifyAndStoreImage($request, 'photo', 'patients', 'upload_image', $patient->id, Patient::class);
            }

            DB::commit();
            session()->flash('edit');
            return redirect()->route('admin.Patients.index');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error("PatientRepository@update: Validation exception for Patient ID {$patient->id}.", ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("PatientRepository@update: General error for Patient ID {$patient->id}: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->back()->withInput()->with('error', 'حدث خطأ أثناء تحديث بيانات المريض: ' . $e->getMessage());
        }
    }

    public function destroy($request) // تستقبل Request
    {
        $patientId = $request->input('id'); // ID من الحقل المخفي أو من الفورم
        if (!$patientId) {
            return redirect()->back()->with('error', 'لم يتم تحديد المريض للحذف.');
        }
        DB::beginTransaction();
        try {
            $patient = Patient::findOrFail($patientId);
            $patientName = $patient->name; // للاستخدام في الرسالة

            if ($patient->image) {
                $this->Delete_attachment('upload_image', 'patients/' . $patient->image->filename, $patient->id, Patient::class);
            }

            // PatientObserver سيهتم بـ GlobalEmail
            // cascadeOnDelete في Migrations سيهتم بـ patient_chronic_diseases و patient_admissions
            $patient->delete();

            DB::commit();
            session()->flash('delete', "تم حذف المريض '{$patientName}' وجميع سجلاته المرتبطة بنجاح.");
            return redirect()->route('admin.Patients.index');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error deleting patient ID {$patientId}: " . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء حذف المريض.');
        }
    }
}
