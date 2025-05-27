<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Bed;
use App\Models\Room;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Section;
use App\Models\Appointment;
use App\Models\GlobalEmail;
use App\Models\RayEmployee;
use Illuminate\Support\Carbon;
use App\Models\PharmacyManager;
use App\Models\PatientAdmission;
use App\Models\PharmacyEmployee;
use Illuminate\Support\Facades\DB;
use App\Models\LaboratorieEmployee;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;                                 // **استيراد موديل Admin**
use App\Traits\UploadTrait;                           // **استيراد UploadTrait**
use App\Http\Requests\Admin\UpdateAdminProfileRequest; // **استيراد الـ FormRequest**
use Illuminate\Http\Request; // لا يزال مطلوبًا إذا كنت ستستخدم $request مباشرة في أي مكان
use Illuminate\Validation\ValidationException;        // **لرمي أخطاء التحقق يدويًا إذا لزم الأمر**

class AdminProfileController extends Controller
{
    use UploadTrait; // **استخدام الـ Trait**

    public function dashboard(Request $request)
    {
        $admin = Auth::guard('admin')->user();
        Log::info("Admin Dashboard: User {$admin->name} (ID: {$admin->id}) accessed.");

        // --- 1. إحصائيات البطاقات العلوية ---
        $doctorsCount = Doctor::count();
        $patientsCount = Patient::count();
        $empCount = PharmacyEmployee::count();
        $manCount = PharmacyManager::count();
        $RayCount = RayEmployee::count();
        $LabCount = LaboratorieEmployee::count();
        $sectionsCount = Section::count();
        $roomsCount = Room::count();
        $availableBedsCount = Bed::where('status', 'available')->count();
        $occupiedBedsCount = Bed::where('status', 'occupied')->count();
        $totalBedsCount = Bed::count();
        $occupancyRate = $totalBedsCount > 0 ? round(($occupiedBedsCount / $totalBedsCount) * 100) : 0;

        $currentAdmissionsCount = PatientAdmission::where('status', 'admitted')->whereNull('discharge_date')->count();
        $icuAdmissionsCount = PatientAdmission::whereNull('discharge_date')
            ->whereHas('bed.room', fn($q) => $q->where('type', 'icu_room'))
            ->count();

        // *** جديد: عدد المواعيد التي تنتظر التأكيد من الأدمن ***
        $pendingAppointmentsCount = Appointment::where('type', Appointment::STATUS_PENDING)
            // (اختياري) يمكنك إضافة شرط زمني إذا أردت (مثلاً، الطلبات خلال آخر 7 أيام)
            // ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->count();

        // (اختياري) عدد المستخدمين الإجمالي إذا كنت ستعرضه
        $totalUsersCount = $doctorsCount + $patientsCount + RayEmployee::count() + LaboratorieEmployee::count() + $manCount + $empCount + 1; // افترض 1 للأدمن نفسه

        // لمعرفة ما إذا كان هناك تنبيهات هامة
        $hasAdminAlerts = ($pendingAppointmentsCount > 0);


        // --- 2. قوائم مختصرة للعرض في الداش بورد (اختياري) ---
        // أ. آخر 5 مواعيد تنتظر التأكيد
        $latestPendingAppointments = Appointment::where('type', Appointment::STATUS_PENDING)
            ->with(['patient:id', 'doctor:id', 'section:id'])
            ->latest('created_at') // الأحدث طلباً
            ->take(5)
            ->get();

        // ب. آخر 5 مرضى تم تسجيلهم
        $recentPatients = Patient::with('image')
            ->latest()
            ->take(5)
            ->get();


        return view('Dashboard.Admin.dashboard', compact(
            'admin',
            'doctorsCount',
            'patientsCount',
            'sectionsCount',
            'roomsCount',
            'availableBedsCount',
            'occupiedBedsCount',
            'totalBedsCount',
            'occupancyRate',
            'currentAdmissionsCount',
            'icuAdmissionsCount',
            'pendingAppointmentsCount', //  <--- تمرير عدد المواعيد غير المؤكدة
            'totalUsersCount',
            'hasAdminAlerts',
            'latestPendingAppointments',
            'recentPatients',
            'request',
            'empCount',
            'manCount',
            'RayCount',
            'LabCount'
        ));
    }

    public function show()
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            // يمكنك توجيه إلى صفحة تسجيل الدخول أو خطأ مخصص
            return redirect()->route('admin.login.form')->with('error', 'يرجى تسجيل الدخول أولاً.');
        }
        return view('Dashboard.Admin.profile.show', compact('admin'));
    }

    public function edit()
    {
        $admin = Auth::guard('admin')->user();
        if (!$admin) {
            return redirect()->route('admin.login.form')->with('error', 'يرجى تسجيل الدخول أولاً.');
        }
        return view('Dashboard.Admin.profile.edit', compact('admin'));
    }

    public function update(UpdateAdminProfileRequest $request) // ** استخدام الـ FormRequest **
    {
        $admin = Auth::guard('admin')->user();
        $validatedData = $request->validated(); // الحصول على البيانات المتحقق منها

        DB::beginTransaction();
        try {
            $dataToUpdate = [
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
            ];
            if (isset($validatedData['phone'])) {
                $dataToUpdate['phone'] = $validatedData['phone'];
            }
            if (!empty($validatedData['password'])) { // تحقق مما إذا تم إرسال كلمة مرور جديدة
                $dataToUpdate['password'] = Hash::make($validatedData['password']);
                Log::info("Admin ID: {$admin->id} password will be updated.");
            }

            $admin->update($dataToUpdate); // تحديث البيانات الأساسية
            // الـ Observer سيهتم بتحديث global_emails إذا تغير الإيميل

            // التعامل مع رفع الصورة
            if ($request->hasFile('photo')) {
                if ($admin->image) {
                    $this->Delete_attachment('upload_image', 'admin_photos/' . $admin->image->filename, $admin->id, Admin::class);
                }
                $this->verifyAndStoreImage($request, 'photo', 'admin_photos', 'upload_image', $admin->id, Admin::class);
            }

            DB::commit();
            Log::info("Admin ID: {$admin->id} profile updated successfully.");
            return redirect()->route('admin.profile.show')->with('success', 'تم تحديث الملف الشخصي بنجاح.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error updating admin profile ID {$admin->id}: " . $e->getMessage() . " TRACE: " . $e->getTraceAsString());
            return redirect()->back()->withInput()->with('error', 'حدث خطأ أثناء تحديث الملف الشخصي: ' . $e->getMessage());
        }
    }
}
