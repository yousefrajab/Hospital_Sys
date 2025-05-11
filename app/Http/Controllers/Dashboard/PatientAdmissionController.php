<?php

namespace App\Http\Controllers\Dashboard; // تأكد من المسار الصحيح

use Illuminate\Http\Request;
use App\Models\PatientAdmission;
use App\Models\Bed;     // للفلترة
use App\Models\Doctor;  // للفلترة
use App\Models\Patient; // للفلترة
use App\Models\Room;    // للفلترة
use App\Models\Section; // للفلترة
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\PatientAdmission\StorePatientAdmissionRequest;
use App\Http\Requests\Dashboard\PatientAdmission\UpdatePatientAdmissionRequest;

class PatientAdmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Log::info("PatientAdmissionController@index: Fetching patient admissions list.");
        $query = PatientAdmission::with([
            'patient' => function ($q) {
                $q->with('image');
            }, // جلب المريض مع صورته
            'bed.room.section', // جلب السرير والغرفة والقسم
            'doctor' => function ($q) {
                $q->with('image');
            } // جلب الطبيب مع صورته
        ])
            ->orderBy('admission_date', 'desc'); // ترتيب حسب تاريخ الدخول الأحدث

        // --- الفلترة ---
        // فلتر لعرض المرضى المقيمين حاليًا فقط أو كل السجلات
        if ($request->filled('current_status')) {
            if ($request->current_status === 'admitted') {
                $query->where('status', PatientAdmission::STATUS_ADMITTED)->whereNull('discharge_date');
            } elseif ($request->current_status === 'discharged') {
                $query->where('status', PatientAdmission::STATUS_DISCHARGED)->whereNotNull('discharge_date');
            }
            // يمكنك إضافة المزيد من الحالات إذا أردت
        }

        // فلتر باسم المريض أو رقم هويته
        if ($request->filled('search_patient')) {
            $searchTerm = $request->search_patient;
            $query->whereHas('patient', function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%") // افترض أن name عمود نصي أو لديك طريقة للبحث في الترجمة
                    ->orWhere('national_id', 'like', "%{$searchTerm}%")
                    ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }

        // فلتر بالقسم
        if ($request->filled('section_id_filter')) {
            $query->where('section_id', $request->section_id_filter);
            // أو إذا كان section_id على الغرفة فقط:
            $query->whereHas('bed.room', function ($q) use ($request) {
                $q->where('section_id', $request->section_id_filter);
            });
        }

        // فلتر بالطبيب
        if ($request->filled('doctor_id_filter')) {
            $query->where('doctor_id', $request->doctor_id_filter);
        }

        // فلتر بتاريخ الدخول
        if ($request->filled('admission_date_from')) {
            $query->whereDate('admission_date', '>=', $request->admission_date_from);
        }
        if ($request->filled('admission_date_to')) {
            $query->whereDate('admission_date', '<=', $request->admission_date_to);
        }
        // --- نهاية الفلترة ---

        $admissions = $query->paginate(15)->appends($request->query());

        // بيانات لـ dropdowns الفلترة
        $patients = Patient::orderByTranslation('name')->get(); // لجلب المرضى للفلتر (قد يكون كثيرًا، فكر في select2 AJAX)
        $doctors = Doctor::orderByTranslation('name')->get(); // لجلب الأطباء للفلتر (نفس الملاحظة)

        $sections = Section::orderByTranslation('name')->get();
        $admissionStatuses = [
            PatientAdmission::STATUS_ADMITTED => 'مقيم حاليًا',
            PatientAdmission::STATUS_DISCHARGED => 'خرج من المستشفى',
            // أضف الحالات الأخرى إذا كنت ستستخدمها في الفلتر
        ];

        return view('Dashboard.PatientAdmissions.index', compact(
            'admissions',
            'patients',
            'doctors',
            'sections',
            'request',
            'admissionStatuses'
        ));
    }

    // ... بقية دوال CRUD ستكون فارغة حاليًا ...
    public function create(Request $request) // يمكن استقبال Request لجلب أي بارامترات أولية
    {
        Log::info("PatientAdmissionController@create: Loading new patient admission form.");

        // جلب البيانات اللازمة لـ dropdowns
        // المرضى الذين ليس لديهم سجل دخول نشط حاليًا
        $patients = Patient::whereDoesntHave('currentAdmission')->orderByTranslation('name')->get();

        $doctors = Doctor::where('status', 1) // الأطباء النشطون فقط
            ->orderByTranslation('name')->get();
        $sections = Section::all(); // الأقسام النشطة فقط
        // ->orderBy('name')     // افترض أن name عمود عادي
        // ->get();

        // الغرف المتاحة أو المشغولة جزئيًا والتي هي ليست خارج الخدمة
        // ويمكن فلترتها أكثر بناءً على القسم المختار لاحقًا بواسطة AJAX أو تمرير كل الغرف
        $rooms = Room::with('section')
            ->whereIn('status', [Room::STATUS_AVAILABLE, Room::STATUS_PARTIALLY_OCCUPIED])
            // ->where('type', Room::TYPE_PATIENT_ROOM) // فلتر بأنواع الغرف التي تقبل مرضى
            ->orderBy('room_number')
            ->get()
            ->map(function ($room) {
                $room->display_name = $room->room_number . ($room->section ? ' (' . $room->section->name . ')' : '');
                return $room;
            });

        // الأسرة المتاحة فقط (يمكن تحديث هذه القائمة ديناميكيًا بناءً على الغرفة المختارة بواسطة AJAX)
        // حاليًا، سنجلب كل الأسرة المتاحة
        $availableBeds = Bed::with('room.section')
            ->where('status', Bed::STATUS_AVAILABLE)
            // ->whereHas('room', fn($q) => $q->where('type', Room::TYPE_PATIENT_ROOM)) // فلتر إضافي
            ->orderBy('room_id')->orderBy('bed_number')->get()
            ->map(function ($bed) {
                $bed->display_name = $bed->bed_number . ($bed->room ? ' (غرفة: ' . $bed->room->room_number . ($bed->room->section ? ' - قسم: ' . $bed->room->section->name : '') . ')' : '');
                return $bed;
            });


        $admissionStatuses = [ // الحالات الأولية عند تسجيل الدخول
            PatientAdmission::STATUS_ADMITTED => 'تسجيل دخول (مقيم)',
            // PatientAdmission::STATUS_RESERVED => 'حجز موعد دخول', // إذا كان لديك نظام حجز
        ];

        return view('Dashboard.PatientAdmissions.create', compact(
            'patients',
            'doctors',
            'sections',
            'rooms', // قائمة أولية للغرف
            'availableBeds', // قائمة أولية للأسرة المتاحة
            'admissionStatuses'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Dashboard\PatientAdmission\StorePatientAdmissionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePatientAdmissionRequest $request)
    {
        Log::info("PatientAdmissionController@store: Attempting to store new patient admission.", $request->except('password', '_token'));
        DB::beginTransaction();
        try {
            $validatedData = $request->validated();

            // 1. إنشاء سجل الدخول
            $admission = new PatientAdmission();
            $admission->patient_id = $validatedData['patient_id'];
            $admission->doctor_id = $validatedData['doctor_id'] ?? null;
            $admission->section_id = $validatedData['section_id'] ?? null; // القسم قد يُستنتج من الغرفة لاحقًا
            $admission->bed_id = $validatedData['bed_id'] ?? null; // قد يتم تخصيص السرير لاحقًا
            $admission->admission_date = $validatedData['admission_date'];
            // discharge_date يكون null عند الدخول
            $admission->reason_for_admission = $validatedData['reason_for_admission'] ?? null;
            $admission->admitting_diagnosis = $validatedData['admitting_diagnosis'] ?? null;
            $admission->status = $validatedData['status'] ?? PatientAdmission::STATUS_ADMITTED;
            $admission->notes = $validatedData['notes'] ?? null;
            $admission->save();
            Log::info("Patient admission record created with ID: {$admission->id}");

            // 2. تحديث حالة السرير إذا تم تخصيص سرير
            if ($admission->bed_id) {
                $bed = Bed::find($admission->bed_id);
                if ($bed) {
                    $bed->status = Bed::STATUS_OCCUPIED;
                    $bed->save(); // الـ booted method في Bed سيهتم بتحديث حالة الغرفة
                    Log::info("Bed ID: {$bed->id} status updated to occupied.");

                    // (اختياري) تحديث section_id في سجل الدخول بناءً على قسم الغرفة
                    if ($bed->room && $bed->room->section_id && !$admission->section_id) {
                        $admission->section_id = $bed->room->section_id;
                        $admission->save();
                    }
                } else {
                    Log::warning("Bed ID: {$admission->bed_id} not found during admission creation for admission ID: {$admission->id}.");
                }
            }

            DB::commit();
            return redirect()->route('admin.patient_admissions.index')
                ->with('success', 'تم تسجيل دخول المريض وتخصيص السرير بنجاح.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error("PatientAdmissionController@store: Validation exception.", ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("PatientAdmissionController@store: General exception: " . $e->getMessage(), [
                'trace' => substr($e->getTraceAsString(), 0, 1000), // زيادة طول التتبع المسجل
                'request_data' => $request->all()
            ]);
            return redirect()->back()->withInput()->with('error', 'حدث خطأ غير متوقع أثناء تسجيل دخول المريض: ' . $e->getMessage());
        }
    }

    public function show(PatientAdmission $patientAdmission)
    {
        Log::info("PatientAdmissionController@show: Displaying details for Admission ID: {$patientAdmission->id}");

        // تحميل العلاقات اللازمة لعرض التفاصيل بشكل كامل
        $patientAdmission->load([
            'patient' => function ($query) {
                $query->with('image'); // تحميل صورة المريض
            },
            'doctor' => function ($query) {
                $query->with('image', 'section'); // تحميل صورة الطبيب وقسمه
            },
            'section', // القسم الذي تم فيه الدخول مباشرة (إذا كان مسجلاً)
            'bed' => function ($query) {
                $query->with(['room' => function ($q_room) {
                    $q_room->with('section'); // تحميل قسم الغرفة
                }]);
            }
        ]);

        // الحصول على القيم النصية للـ Enum لحالة سجل الدخول
        $admissionStatuses = [
            PatientAdmission::STATUS_ADMITTED => 'مقيم حاليًا',
            PatientAdmission::STATUS_DISCHARGED => 'خرج من المستشفى',
            PatientAdmission::STATUS_TRANSFERRED_OUT => 'تم نقله للخارج',
            PatientAdmission::STATUS_TRANSFERRED_IN => 'تم نقله للداخل',
            PatientAdmission::STATUS_CANCELLED => 'ملغى',
        ];
        $statusDisplay = $admissionStatuses[$patientAdmission->status] ?? $patientAdmission->status;

        // يمكنك أيضًا جلب قائمة بالأسرة المتاحة إذا كنت ستسمح بنقل المريض من هذه الصفحة
        $availableBeds = Bed::where('status', Bed::STATUS_AVAILABLE)->get();

        return view('Dashboard.PatientAdmissions.show', compact(
            'patientAdmission',
            'statusDisplay',
            'availableBeds' // إذا أردت تمريرها
        ));
    }
    public function edit(PatientAdmission $patientAdmission)
    {
        Log::info("PatientAdmissionController@edit: Loading edit form for Admission ID: {$patientAdmission->id}");

        // تحميل العلاقات اللازمة للفورم
        $patientAdmission->load(['patient', 'doctor', 'section', 'bed.room']);

        // جلب البيانات لـ dropdowns
        // المرضى (عادة لا يتم تغيير المريض في سجل دخول قائم، ولكن قد تحتاج لعرضه)
        $patients = Patient::orderByTranslation('name')->get(); // أو يمكنك تمرير المريض الحالي فقط
        $doctors = Doctor::where('status', 1)->orderByTranslation('name')->get();
        $sections = Section::all();

        // الأسرة المتاحة + السرير الحالي للمريض (إذا كان لا يزال متاحًا أو هو نفسه)
        $availableBeds = Bed::with('room.section')
                            ->where('status', Bed::STATUS_AVAILABLE)
                            ->orWhere('id', $patientAdmission->bed_id) // تضمين السرير الحالي حتى لو كان مشغولاً (بهذا المريض)
                            ->orderBy('room_id')->orderBy('bed_number')->get()
                            ->map(function($bed) use ($patientAdmission){
                                $bed->display_name = $bed->bed_number .
                                                    ($bed->room ? ' (غرفة: ' . $bed->room->room_number .
                                                    ($bed->room->section ? ' - قسم: '.$bed->room->section->name : '') . ')' : '');
                                // تمييز السرير الحالي
                                if ($patientAdmission->bed_id == $bed->id) {
                                    $bed->display_name .= " (السرير الحالي)";
                                }
                                return $bed;
                            });

        $admissionStatuses = [ // كل الحالات الممكنة للتعديل
            PatientAdmission::STATUS_ADMITTED => 'مقيم حاليًا',
            PatientAdmission::STATUS_DISCHARGED => 'خرج من المستشفى',
            PatientAdmission::STATUS_TRANSFERRED_OUT => 'تم نقله للخارج', // إذا كنت ستدعم النقل
            PatientAdmission::STATUS_TRANSFERRED_IN => 'تم نقله للداخل',   // إذا كنت ستدعم النقل
            PatientAdmission::STATUS_CANCELLED => 'ملغى',
        ];

        return view('Dashboard.PatientAdmissions.edit', compact(
            'patientAdmission',
            'patients', // أو يمكنك فقط عرض اسم المريض الحالي وعدم السماح بتغييره
            'doctors',
            'sections',
            'availableBeds',
            'admissionStatuses'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Dashboard\PatientAdmission\UpdatePatientAdmissionRequest  $request
     * @param  \App\Models\PatientAdmission  $patientAdmission
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePatientAdmissionRequest $request, PatientAdmission $patientAdmission)
    {
        Log::info("PatientAdmissionController@update: Attempting to update Admission ID: {$patientAdmission->id}", $request->except('_token', '_method'));
        DB::beginTransaction();
        try {
            $validatedData = $request->validated();
            $originalBedId = $patientAdmission->bed_id; // السرير القديم قبل التحديث

            // تحديث البيانات الأساسية لسجل الدخول
            $patientAdmission->doctor_id = $validatedData['doctor_id'] ?? $patientAdmission->doctor_id;
            $patientAdmission->section_id = $validatedData['section_id'] ?? $patientAdmission->section_id;
            $patientAdmission->bed_id = $validatedData['bed_id'] ?? $patientAdmission->bed_id;
            $patientAdmission->admission_date = $validatedData['admission_date'];
            $patientAdmission->discharge_date = $validatedData['discharge_date'] ?? null; // مهم لجعله null إذا لم يتم إرساله
            $patientAdmission->reason_for_admission = $validatedData['reason_for_admission'] ?? $patientAdmission->reason_for_admission;
            $patientAdmission->admitting_diagnosis = $validatedData['admitting_diagnosis'] ?? $patientAdmission->admitting_diagnosis;
            $patientAdmission->discharge_reason = $validatedData['discharge_reason'] ?? null;
            $patientAdmission->discharge_diagnosis = $validatedData['discharge_diagnosis'] ?? null;
            $patientAdmission->status = $validatedData['status'];
            $patientAdmission->notes = $validatedData['notes'] ?? $patientAdmission->notes;

            $patientAdmission->save(); // الحفظ سيُشغل الـ 'saved' event في موديل PatientAdmission
            Log::info("Patient admission record ID: {$patientAdmission->id} updated.");

            // الـ 'saved' event في PatientAdmission سيهتم بتحديث حالة السرير الجديد والقديم
            // ولكن للتأكيد، إذا تم تغيير السرير يدويًا هنا، يمكننا تحديث القديم
            if ($originalBedId && $originalBedId != $patientAdmission->bed_id) {
                $oldBed = Bed::find($originalBedId);
                if ($oldBed) {
                    $oldBed->status = Bed::STATUS_AVAILABLE;
                    $oldBed->saveQuietly(); // لتجنب حلقة إذا كان هناك observer على Bed
                    Log::info("Old Bed ID: {$originalBedId} status set to available after patient transfer/discharge.");
                }
            }

            DB::commit();
            return redirect()->route('admin.patient_admissions.show', $patientAdmission->id) // توجيه لصفحة العرض بعد التعديل
                             ->with('success', 'تم تحديث بيانات سجل الدخول بنجاح.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error("PatientAdmissionController@update: Validation exception for Admission ID {$patientAdmission->id}.", ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("PatientAdmissionController@update: General exception for Admission ID {$patientAdmission->id}: " . $e->getMessage(), [
                'trace' => substr($e->getTraceAsString(), 0, 1000),
                'request_data' => $request->all()
            ]);
            return redirect()->back()->withInput()->with('error', 'حدث خطأ غير متوقع أثناء تحديث السجل: ' . $e->getMessage());
        }
    }

    public function destroy(PatientAdmission $patientAdmission)
    {
        Log::info("PatientAdmissionController@destroy: Attempting to delete Admission ID: {$patientAdmission->id} for Patient ID: {$patientAdmission->patient_id}");
        DB::beginTransaction();
        try {
            // لا يمكن حذف سجل دخول لمريض لا يزال مقيمًا (يجب تسجيل خروجه أولاً)
            // هذا مجرد إجراء احترازي، عملية تسجيل الخروج هي التي يجب أن تحدث
            if ($patientAdmission->status === PatientAdmission::STATUS_ADMITTED && is_null($patientAdmission->discharge_date)) {
                Log::warning("Attempt to delete an active admission record (ID: {$patientAdmission->id}). Operation aborted.");
                return redirect()->route('admin.patient_admissions.index')
                                 ->with('error', 'لا يمكن حذف سجل دخول نشط. يجب تسجيل خروج المريض أولاً.');
            }

            $patientName = $patientAdmission->patient->name ?? 'غير معروف';
            $admissionDate = $patientAdmission->admission_date->format('Y-m-d');

            // الـ 'deleted' event في موديل PatientAdmission سيهتم بتحديث حالة السرير إذا كان مرتبطًا
            $patientAdmission->delete();

            DB::commit();
            Log::info("Admission record ID: {$patientAdmission->id} for patient '{$patientName}' deleted successfully.");
            return redirect()->route('admin.patient_admissions.index')
                             ->with('success', "تم حذف سجل الدخول للمريض '{$patientName}' (تاريخ الدخول: {$admissionDate}) بنجاح.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("PatientAdmissionController@destroy: Error deleting Admission ID {$patientAdmission->id}: " . $e->getMessage(), [
                'trace' => substr($e->getTraceAsString(), 0, 500)
            ]);
            return redirect()->route('admin.patient_admissions.index')
                             ->with('error', 'حدث خطأ أثناء حذف سجل الدخول: ' . $e->getMessage());
        }
    }
}
