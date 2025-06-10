<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Service;
use App\Models\Appointment;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ReceiptAccount;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentCancelledByPatientToDoctor;
use App\Models\Section; // تأكد من استيراد موديل Section

class WebsiteController extends Controller
{

    public function home()
    {
        $sections = Section::with(['doctors.image', 'Service']) // افترض أن الأطباء لديهم علاقة image
            ->orderBy('id', 'asc')
            ->get();


        $featuredDoctors = Doctor::with('image', 'section')
            ->where('status', true) // أو 1 إذا كان integer
            ->take(7)
            ->get();

        $latestServices = Service::where('status', 1)
            ->with([
                // 'translations', // إذا كنت تستخدمها
                'doctor' => function ($query) {
                    $query->with([/*'translations',*/'section' /*=> function($q_section){
                        $q_section->with('translations');
                    }*/]); // تحميل ترجمات الطبيب والقسم
                }
            ])
            ->latest() // لجلب الأحدث
            ->take(6) // عدد الخدمات المراد عرضها في السلايدر
            ->get();

        // جلب آخر 6 باقات خدمات نشطة
        $latestGroupedServices = Group::with([
            // 'translations',
            'service_group' => function ($query) {
                $query->with([
                    // 'translations',
                    'doctor' => function ($q_doctor) {
                        $q_doctor->with([/*'translations',*/'section' /*=> function($q_section){
                                $q_section->with('translations');
                            }*/]);
                    }
                ])->where('status', 1);
            }
        ])
            // ->where('status', 1) // إذا كان للباقة نفسها حقل حالة
            ->latest()
            ->take(6) // عدد الباقات المراد عرضها في السلايدر
            ->get();

        // // جلب الأقسام لعرضها في الصفحة الرئيسية (كما في الكود الذي أرسلته)
        // $sections = Section::with(['doctors.image', /*'translations'*/])
        //     ->where('status', 1) // فقط الأقسام المفعلة
        //     ->orderBy('name') // أو أي ترتيب تفضله
        //     ->get();


        return view('welcome', compact('sections', 'featuredDoctors', 'latestServices', 'latestGroupedServices'));
    }

    public function showAllDepartments()
    {
        $sections = Section::withCount('doctors') // جلب عدد الأطباء في كل قسم
            ->orderByTranslation('name', 'asc')
            ->paginate(9); // مثال: 9 أقسام في الصفحة (يمكنك تغيير هذا الرقم)

        return view('WebSite.departments.all_departments', compact('sections'));
    }

    /**
     * عرض صفحة تفاصيل قسم معين.
     */
    public function showDepartmentDetails($id)
    {
        // جلب القسم مع الأطباء (مع صورهم) والخدمات
        // استخدام findOrFail لإرجاع 404 إذا لم يتم العثور على القسم
        $section = Section::with(['doctors.image', 'Service']) // افترض أن الطبيب له علاقة specializations
            ->findOrFail($id);

        // يمكنك أيضًا جلب أقسام أخرى لعرضها كـ "أقسام مشابهة" أو قائمة جانبية
        $otherSections = Section::findOrFail($id)
            ->orderByTranslation('name', 'asc')
            ->take(5)
            ->get();

        return view('WebSite.departments.department_details', compact('section', 'otherSections'));
    }

    /**
     * عرض صفحة قائمة جميع الأطباء.
     */
    public function showAllDoctors(Request $request) // إضافة Request للتعامل مع الفلاتر لاحقًا
    {
        $query = Doctor::query()->with(['image', 'section']); // ابدأ ببناء الاستعلام

        // مثال بسيط لفلتر حسب القسم (يمكن تطويره)
        if ($request->has('section_id') && $request->section_id != '') {
            $query->where('section_id', $request->section_id);
        }


        $doctors = $query->orderByTranslation('name', 'asc')
            ->paginate(12); // مثال: 12 طبيب في الصفحة

        $sectionsForFilter = Section::orderByTranslation('name', 'asc')->get(); // لجلب الأقسام لـ dropdown الفلتر

        return view('WebSite.doctors.all_doctors', compact('doctors', 'sectionsForFilter'));
    }

    /**
     * عرض صفحة تفاصيل طبيب معين.
     */
    public function showDoctorDetails($id)
    {
        // جلب الطبيب مع العلاقات الأساسية لعرضها في ملفه الشخصي
        $doctor = Doctor::with([
            'image',
            'section', // القسم الذي يعمل به
            'workingDays' => function ($query) { // أيام عمله
                $query->where('active', true) // فقط أيام العمل النشطة
                    ->with(['breaks' => function ($q_break) { // استراحاته خلال أيام العمل
                        $q_break->orderBy('start_time', 'asc');
                    }])
                    ->orderByRaw("FIELD(day, 'Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday')"); // لترتيب الأيام
            }
            // يمكنك إضافة 'specializations' هنا بمجرد تعريف العلاقة في موديل Doctor
            // 'specializations'
        ])
            ->findOrFail($id); //  إرجاع 404 إذا لم يتم العثور على الطبيب

        // جلب أطباء آخرين من نفس القسم لعرضهم كـ "أطباء مشابهون" (باستثناء الطبيب الحالي)
        $relatedDoctors = [];
        if ($doctor->section) { // تحقق من وجود قسم للطبيب أولاً
            $relatedDoctors = Doctor::where('section_id', $doctor->section_id)
                ->where('id', '!=', $doctor->id) // لا تجلب الطبيب الحالي
                ->where('status', true) // فقط الأطباء النشطين
                ->with('image') // جلب صورهم
                ->take(4) // عدد الأطباء المشابهين للعرض
                ->get();
        }

        // تجهيز بيانات جدول العمل بشكل مناسب للعرض
        $scheduleData = [];
        $daysOrder = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']; //  أو حسب ترتيبك المفضل
        $activeWorkingDays = $doctor->workingDays->keyBy('day'); // لسهولة الوصول لليوم

        foreach ($daysOrder as $dayName) {
            if ($activeWorkingDays->has($dayName)) {
                $workDay = $activeWorkingDays[$dayName];
                $scheduleData[$dayName] = [
                    'active' => true,
                    'start_time' => \Carbon\Carbon::parse($workDay->start_time)->translatedFormat('h:i A'),
                    'end_time' => \Carbon\Carbon::parse($workDay->end_time)->translatedFormat('h:i A'),
                    'appointment_duration' => $workDay->appointment_duration,
                    'breaks' => $workDay->breaks->map(function ($break) {
                        return [
                            'start_time' => \Carbon\Carbon::parse($break->start_time)->translatedFormat('h:i A'),
                            'end_time' => \Carbon\Carbon::parse($break->end_time)->translatedFormat('h:i A'),
                            'reason' => $break->reason,
                        ];
                    })
                ];
            } else {
                $scheduleData[$dayName] = ['active' => false]; // اليوم غير نشط للطبيب
            }
        }

        // إذا كنت ستضيف نظام التخصصات لاحقًا، ستحتاج لجلبها وعرضها
        // $specializations = $doctor->specializations;

        return view('WebSite.doctors.doctor_details', compact(
            'doctor',
            'relatedDoctors',
            'scheduleData'
            // 'specializations' //  مررها للـ view عندما تكون جاهزة
        ));
    }
    public function showAllServices(Request $request)
    {
        // جلب جميع الخدمات النشطة مع تحميل علاقات الطبيب وقسم الطبيب والترجمات
        $services = Service::where('status', 1) // فقط الخدمات المفعلة

            ->orderBy('created_at', 'desc') // أو أي ترتيب تفضله
            ->paginate(9); // مثال: 9 خدمات في الصفحة

        // جلب الإعدادات إذا كنت تستخدمها (مثال من كود Blade الخاص بك)
        // تأكد أن دالة settings() أو موديل Setting موجود ويعمل
        // $settings = Setting::first()->toArray(); // أو settings() إذا كانت دالة helper

        return view('WebSite.services.all_services_standalone', compact('services'));
    }

    public function showAllGroupServices(Request $request)
    {
        // جلب باقات الخدمات مع تحميل الخدمات المفردة داخلها،
        // ومعلومات الطبيب والقسم لكل خدمة مفردة
        $groupedServices = Group::with([
            // إذا كنت تستخدمها لـ Group
            'service_group' => function ($query) { // service_group هي علاقة الخدمات داخل الباقة
                $query->with([
                    // ترجمات الخدمة المفردة
                    'doctor' => function ($q_doctor) {
                        $q_doctor->with(['translations', 'section' => function ($q_section) {
                            $q_section->with('translations');
                        }]);
                    }
                ])->where('status', 1); // فقط الخدمات المفعلة داخل الباقة
            }
        ])
            // يمكنك إضافة ->where('status', 1) إذا كان للباقة نفسها حقل حالة
            ->orderBy('created_at', 'desc')
            ->paginate(6); // مثال: 6 باقات في الصفحة

        // $settings = Setting::first()->toArray(); // أو settings()

        return view('WebSite.services.all_group_services_standalone', compact('groupedServices'));
    }

    public function myAppointments(Request $request)
    {
        if (!Auth::guard('patient')->check()) {
        }

        $patient = Auth::guard('patient')->user();
        $patientId = $patient->id; // احصل على ID المريض

        // --- جلب المواعيد القادمة ---
        $upcomingAppointments = Appointment::where('patient_id', $patientId) // <<< قيد إضافي هنا
            ->where('appointment', '>=', now()->startOfDay())
            ->whereIn('type', [
                Appointment::STATUS_PENDING,
                Appointment::STATUS_CONFIRMED
            ])
            ->with([ /* ... with clauses ... */])
            ->orderBy('appointment', 'asc')
            ->paginate(config('pagination.website_patient_appointments_upcoming', 6), ['*'], 'upcoming_page');

        // --- جلب المواعيد السابقة ---
        $pastAppointmentsQuery = Appointment::where('patient_id', $patientId) // <<< قيد إضافي هنا
            ->where(function ($query) {
                $query->where('appointment', '<', now()->startOfDay())
                    ->orWhereIn('type', [
                        Appointment::STATUS_COMPLETED,
                        Appointment::STATUS_CANCELLED,
                        Appointment::STATUS_LAPSED,
                    ]);
            })
            ->with([ /* ... with clauses ... */])
            ->orderBy('appointment', 'desc');

        $pastAppointments = $pastAppointmentsQuery->paginate(config('pagination.website_patient_appointments_past', 6), ['*'], 'past_page');

        return view('WebSite.appointments.my_appointments_standalone', compact(
            'patient',
            'upcomingAppointments',
            'pastAppointments'
        ));
    }

    public function cancelAppointmentFromWebsite(Request $request, Appointment $appointment)
    {
        $patient = Auth::guard('patient')->user();

        // 1. التحقق من ملكية الموعد للمريض الحالي
        if ($appointment->patient_id !== $patient->id) {
            Log::warning("AUTH_FAIL_CANCEL_PATIENT: Patient {$patient->id} attempted to cancel appointment {$appointment->id} not belonging to them (belongs to patient {$appointment->patient_id}).");
            return response()->json(['message' => 'غير مصرح لك بإلغاء هذا الموعد.'], 403);
        }

        // 2. التحقق من أن الموعد قابل للإلغاء (حالته 'غير مؤكد' أو 'مؤكد')
        $cancellableTypes = [Appointment::STATUS_PENDING, Appointment::STATUS_CONFIRMED];
        if (!in_array($appointment->type, $cancellableTypes, true)) {
            $message = 'لا يمكن إلغاء هذا الموعد لأن حالته الحالية هي: (' . ($appointment->status_display ?? $appointment->type) . ').';
            Log::info("CANCEL_DENIED_STATUS_PATIENT: Patient {$patient->id} failed to cancel appointment {$appointment->id}. Status: '{$appointment->type}'.");
            return response()->json(['message' => $message], 422);
        }

        // 3. (اختياري، ولكنه مهم كما ناقشنا) التحقق من الوقت المتبقي للموعد
        // يمكنك إضافة قيمة config لعدد الساعات المسموح بها للإلغاء قبل الموعد
        $cancellationWindowHours = config('settings.patient_appointment_cancellation_window_hours', 24); // مثال: 24 ساعة
        if (Carbon::parse($appointment->appointment)->isPast() || Carbon::parse($appointment->appointment)->diffInHours(now(), false) < $cancellationWindowHours) {
            // diffInHours(now(), false)  تعطي فرقاً سالباً إذا كان الموعد في المستقبل
            // لذا، إذا كان الفرق أقل من 24 ساعة (أو سالب، أي في الماضي القريب جداً ولم يُعالج بعد كفائت)
            if (Carbon::parse($appointment->appointment)->isFuture() && Carbon::parse($appointment->appointment)->diffInHours(now()) < $cancellationWindowHours) {
                $errorMessage = "لا يمكن إلغاء الموعد قبل أقل من {$cancellationWindowHours} ساعة من وقته المحدد. يرجى التواصل مباشرة مع العيادة إذا لزم الأمر.";
                Log::info("CANCEL_DENIED_TIME_PATIENT: Patient {$patient->id} for appt {$appointment->id}. Appt time: {$appointment->appointment}, Window: {$cancellationWindowHours}h.");
                return response()->json(['message' => $errorMessage], 422);
            }
        }
        // --- نهاية التحقق من الوقت ---

        DB::beginTransaction();
        try {
            $oldType = $appointment->type;

            // تحديث الحقول الأساسية فقط
            $appointment->type = Appointment::STATUS_CANCELLED; //  قيمته يجب أن تكون 'ملغي' matching ENUM

            // (مهم) إذا أردت تسجيل من قام بالإلغاء، أضف أعمدة للجدول والموديل
            // $appointment->cancellation_reason = $request->input('reason_patient', 'تم الإلغاء بواسطة المريض عبر البوابة.');
            // $appointment->cancelled_by_user_type = Patient::class;
            // $appointment->cancelled_by_user_id = $patient->id;
            // $appointment->cancelled_at = now();

            $appointment->saveOrFail(); // استخدام saveOrFail أفضل

            Log::info("APPOINTMENT_CANCELLED_BY_PATIENT: ID {$appointment->id}, NewType: '{$appointment->type}' (Old: '{$oldType}'), ByPatientID: {$patient->id}.");

            // إرسال إشعار للطبيب (بافتراض أن Mailable موجود ويعمل)
            if ($appointment->doctor && $appointment->doctor->email) {
                try {
                    Mail::to($appointment->doctor->email)
                        ->send(new AppointmentCancelledByPatientToDoctor($appointment, $patient));
                    Log::info("Mail_Sent: Patient cancellation email to Doctor {$appointment->doctor->email} for appt ID {$appointment->id}.");
                } catch (\Exception $e) {
                    Log::error("Mail_Error: Failed sending patient cancellation email to doctor for appt ID {$appointment->id}: " . $e->getMessage());
                }
            } else {
                Log::warning("Mail_Warn: Doctor or Doctor's email not found for appt ID {$appointment->id} (Patient Cancel).");
            }

            DB::commit();
            $successMessage = 'تم إلغاء موعدك بنجاح. سيتم إشعار الطبيب بذلك.';

            return response()->json([
                'message' => $successMessage,
                'appointment_id' => $appointment->id, // لإمكانية تحديث الـ UI
                'new_status' => $appointment->type,    // الحالة الجديدة => 'ملغي'
                'status_display' => $appointment->status_display //  النص المقابل للحالة الجديدة
            ]);
        } catch (\Illuminate\Database\QueryException $qe) {
            DB::rollBack();
            Log::critical("CRITICAL_DB_PATIENT_CANCEL_ERROR: PatientID {$patient->id} for ApptID {$appointment->id}: " . $qe->getMessage(), [
                'sql' => $qe->getSql(),
                'bindings' => $qe->getBindings()
            ]);
            return response()->json(['message' => 'حدث خطأ أثناء محاولة تحديث حالة الموعد في قاعدة البيانات. يرجى المحاولة مرة أخرى.'], 500);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::critical("CRITICAL_GENERAL_PATIENT_CANCEL_ERROR: PatientID {$patient->id} for ApptID {$appointment->id}: " . $e->getMessage(), ['trace_short' => Str::limit($e->getTraceAsString(), 1000)]);
            return response()->json(['message' => 'حدث خطأ غير متوقع أثناء إلغاء الموعد. تم تسجيل الخطأ.'], 500);
        }
    }


    public function myInvoices(Request $request)
    {
        if (!Auth::guard('patient')->check()) {
            session()->flash('info_notify', 'يرجى تسجيل الدخول أولاً لعرض فواتيرك.');
            return redirect()->route('login');
        }

        $patient = Auth::guard('patient')->user();

        // جلب فواتير المريض مع العلاقات اللازمة (مثال: الخدمة، الطبيب، القسم)
        // يمكنك تحديد نوع الفاتورة إذا كان لديك أنواع مختلفة (مثلاً: 1 للخدمات المفردة، 2 للمجموعات)
        $invoices = Invoice::where('patient_id', $patient->id)
            // ->where('invoice_type', 1) //  إذا كنت تريد فقط فواتير الخدمات المفردة كمثال
            ->with([
                'Service',
                'Group',
                'Doctor' => function ($query) { // افترض أن العلاقة مع Doctor اسمها Doctor
                    $query->withTranslation()->select('id'); // اختر الحقول اللازمة فقط
                },
                'Section' => function ($query) { // افترض أن العلاقة مع Section اسمها Section
                    $query->withTranslation()->select('id');
                }
            ])
            ->orderBy('invoice_date', 'desc') // الأحدث أولاً
            ->orderBy('created_at', 'desc')
            ->paginate(config('pagination.website_patient_invoices', 10)); // 10 فواتير في الصفحة

        return view('WebSite.invoices.my_invoices_standalone', compact('patient', 'invoices'));
    }

    public function printInvoice(Request $request, Invoice $invoice) // استخدام Route Model Binding
    {
        $patient = Auth::guard('patient')->user();

        // التحقق من أن الفاتورة تخص المريض المسجل دخوله
        if ($invoice->patient_id !== $patient->id) {
            // يمكنك إعادة توجيه لصفحة خطأ أو صفحة الفواتير مع رسالة خطأ
            abort(403, 'غير مصرح لك بعرض هذه الفاتورة.');
        }

        // تحميل العلاقات اللازمة التي ستُعرض في الفاتورة المطبوعة
        $invoice->load([
            'Service',
            'Group',
            'Doctor' => function ($query) {
                $query->withTranslation()->select('id', /* 'name' - إذا لم يكن مترجمًا */);
            },
            'Section' => function ($query) {
                $query->withTranslation()->select('id', /* 'name' - إذا لم يكن مترجمًا */);
            },
            'patient:id,email,Phone' // جلب بيانات محددة للمريض
        ]);

        // يمكنك جلب إعدادات المستشفى/العيادة (الاسم، العنوان، الشعار، الرقم الضريبي) من قاعدة البيانات أو ملف config
        $hospitalSettings = [
            'name' => config('app.name', 'المنصة الطبية'),
            'logo' => asset('path/to/your/logo_for_print.png'), // ضع مسار الشعار هنا
            'address' => '123 شارع الصحة، مدينة العافية',
            'phone' => '012-345-6789',
            'email' => 'info@example.com',
            'tax_number' => '123456789012345', // الرقم الضريبي إذا وجد
        ];

        return view('WebSite.invoices.print_invoice_standalone', compact('invoice', 'hospitalSettings'));
    }

    public function myAccountStatement(Request $request)
    {
        if (!Auth::guard('patient')->check()) {
            session()->flash('info_notify', 'يرجى تسجيل الدخول أولاً لعرض كشف حسابك.');
            return redirect()->route('login');
        }

        $patient = Auth::guard('patient')->user();

        // جلب سندات القبض (المدفوعات) الخاصة بالمريض
        // ReceiptAccount يخزن المبالغ التي دفعها المريض (عادة ما تكون Debit للصندوق و Credit لحساب المريض)
        $receipts = ReceiptAccount::where('patient_id', $patient->id)
            ->orderBy('date', 'desc') // الأحدث أولاً
            ->orderBy('created_at', 'desc')
            ->paginate(config('pagination.website_patient_receipts', 10));

        // (اختياري متقدم) يمكنك هنا أيضًا جلب الفواتير الآجلة غير المسددة إذا أردت عرض رصيد كلي
        // $unpaid_invoices_total = Invoice::where('patient_id', $patient->id)
        //                                 ->where('type', 2) // آجل
        //                                 ->where('invoice_status', 1) // غير مدفوعة
        //                                 ->sum('total_with_tax');
        // $total_paid = $receipts->sum('amount'); // أو $patient->patient_accounts()->sum('credit') - $patient->patient_accounts()->sum('Debit')

        return view('WebSite.account.my_account_statement_standalone', compact(
            'patient',
            'receipts'
            // 'unpaid_invoices_total',
            // 'total_paid'
        ));
    }

    public function printReceipt(Request $request, ReceiptAccount $receiptAccount)
    {
        $currentUser = Auth::guard('patient')->user(); // استخدام متغير مختلف لتجنب الالتباس مع $patient المحمل من العلاقة

        // التحقق من أن السند يخص المريض المسجل دخوله
        if ($receiptAccount->patient_id !== $currentUser->id) {
            abort(403, 'غير مصرح لك بعرض هذا السند.');
        }

        // تحميل علاقة المريض مع ترجماته (إذا كان اسم المريض مترجمًا)
        // وتحميل فقط الأعمدة المطلوبة من جدول patients إذا أردت
        $receiptAccount->load([
            'patients' => function ($query) {
                // $query->select('id', 'email', 'Phone'); // حدد الأعمدة غير المترجمة التي تحتاجها من جدول patients
                $query->withTranslation(app()->getLocale()); // تحميل الترجمة للغة الحالية
            }
        ]);

        // الآن يمكنك الوصول إلى اسم المريض المترجم عبر: $receiptAccount->patient->name

        $hospitalSettings = [
            'name' => config('app.name', 'المنصة الطبية'),
            'logo' => asset('path/to/your/logo_for_print.png'), // ضع مسار الشعار هنا
            'address' => '123 شارع الصحة، مدينة العافية',
            'phone' => '012-345-6789',
            'email' => 'info@example.com',
            'tax_number' => '123456789012345',
        ];

        return view('WebSite.account.print_receipt_standalone', compact('receiptAccount', 'hospitalSettings'));
    }
}
