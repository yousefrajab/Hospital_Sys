<?php

namespace App\Http\Controllers\Dashboard\LabEmployee;

use App\Models\Laboratorie;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LaboratorieEmployee;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Dashboard\LabEmployee\UpdateLabEmployeeProfileRequest;

class ProfileLabController extends Controller // يمكنك تسميته ProfileController داخل مجلد LaboratorieEmployee
{
    use UploadTrait;

    /**
     * عرض الملف الشخصي لموظف الأشعة المسجل.
     */
    public function dashboard()
    {
        $employeeGuard = Auth::guard('laboratorie_employee'); // تحديد الحارس
        $employeeName = $employeeGuard->user()->name ?? 'زائر';

        // الإحصائيات الأساسية
        $totalLabs = Laboratorie::count();
        $pendingLabs = Laboratorie::where('case', 0)->count();
        $completedLabs = Laboratorie::where('case', 1)->count();

        // آخر 5 طلبات مختبر (يمكنك زيادة العدد)
        $latestLabs = Laboratorie::with(['patient', 'doctor']) // Eager load relations
            ->latest() // Order by created_at desc
            ->take(5)
            ->get();

        // بيانات رسم بياني لعدد الطلبات شهريًا
        $monthlyLabCounts = Laboratorie::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->all();

        $monthLabels = [];
        $monthData = [];
        $monthNames = ["يناير", "فبراير", "مارس", "أبريل", "مايو", "يونيو", "يوليو", "أغسطس", "سبتمبر", "أكتوبر", "نوفمبر", "ديسمبر"];

        for ($m = 1; $m <= 12; $m++) {
            $monthLabels[] = $monthNames[$m - 1];
            $monthData[] = $monthlyLabCounts[$m] ?? 0;
        }

        return view('Dashboard.dashboard_LaboratorieEmployee.dashboard', compact(
            'employeeName', // اسم الموظف المسجل
            'totalLabs',
            'pendingLabs',
            'completedLabs',
            'latestLabs',
            'monthLabels',
            'monthData'
        ));
    }
    public function showw()
    {
        $employee = Auth::guard('laboratorie_employee')->user();
        if (!$employee) {
            // يمكنك التوجيه لصفحة تسجيل الدخول أو عرض خطأ
            return redirect()->route('laboratorie_employee.login.form'); // افترض وجود هذا الـ route
        }
        // تحميل علاقة الصورة إذا كانت موجودة
        $employee->load('image');
        return view('Dashboard.laboratorie_employee.profile.show', compact('employee'));
    }

    /**
     * عرض فورم تعديل الملف الشخصي.
     */
    public function editt()
    {
        $employee = Auth::guard('laboratorie_employee')->user();
        if (!$employee) {
            return redirect()->route('laboratorie_employee.profile.show');
        }
        $employee->load('image');
        return view('Dashboard.laboratorie_employee.profile.edit', compact('employee'));
    }

    /**
     * تحديث الملف الشخصي لموظف الأشعة.
     */
    public function updatee(UpdateLabEmployeeProfileRequest $request)
    {
        $employee = Auth::guard('laboratorie_employee')->user();
        if (!$employee) {
            abort(403, 'غير مصرح لك.');
        }

        $validatedData = $request->validated();
        Log::info("Attempting profile update for LaboratorieEmployee ID: {$employee->id}");
        DB::beginTransaction();

        try {
            // تحديث البيانات الأساسية
            $employee->name = $validatedData['name'];
            $employee->email = $validatedData['email'];
            $employee->national_id = $validatedData['national_id'];
            if (isset($validatedData['phone'])) {
                $employee->phone = $validatedData['phone'];
            }

            // تحديث كلمة المرور (إذا تم تقديمها والتحقق منها)
            if ($request->filled('password') && $request->filled('current_password')) {
                if (!Hash::check($request->current_password, $employee->password)) {
                    DB::rollBack(); // تراجع قبل رمي الاستثناء
                    throw ValidationException::withMessages([
                        'current_password' => __('validation.current_password'),
                    ]);
                }
                $employee->password = Hash::make($validatedData['password']);
                Log::info("Password updated for LaboratorieEmployee ID: {$employee->id}");
            } elseif ($request->filled('password') && !$request->filled('current_password')) {
                DB::rollBack();
                throw ValidationException::withMessages([
                    'current_password' => 'يجب إدخال كلمة المرور الحالية لتغيير كلمة المرور.',
                ]);
            }

            // التعامل مع رفع الصورة
            if ($request->hasFile('photo')) {
                Log::info("[LaboratorieEmployee Profile Update] Image file detected for ID: {$employee->id}");
                // حذف الصورة القديمة إذا كانت موجودة
                if ($employee->image) {
                    $this->Delete_attachment(
                        'upload_image', // اسم القرص
                        'laboratorieEmployees/' . $employee->image->filename, // المسار (تأكد من صحته)
                        $employee->id,
                        LaboratorieEmployee::class // FQCN للموديل
                    );
                    Log::info("[LaboratorieEmployee Profile Update] Old image deleted for ID: {$employee->id}");
                }
                // رفع الصورة الجديدة
                $this->verifyAndStoreImage(
                    $request, // كائن الطلب
                    'photo',   // اسم الحقل
                    'laboratorieEmployees', // اسم المجلد
                    'upload_image', // اسم القرص
                    $employee->id,
                    LaboratorieEmployee::class // FQCN للموديل
                );
                Log::info("[LaboratorieEmployee Profile Update] New image stored for ID: {$employee->id}");
            }

            // حفظ كل التغييرات
            $employee->save();
            DB::commit();
            Log::info("Profile update committed successfully for LaboratorieEmployee ID {$employee->id}");

            // إعادة التوجيه مع رسالة نجاح
            return redirect()->route('laboratorie_employee.profile.show') // اسم route عرض الملف الشخصي
                ->with('success', 'تم تحديث الملف الشخصي بنجاح.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack(); // تراجع في حالة خطأ التحقق الداخلي (مثل كلمة المرور الحالية)
            Log::error("LaboratorieEmployee Profile Update Validation Error ID {$employee->id}: " . json_encode($e->errors()));
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error updating ray employee profile for ID {$employee->id}: " . $e->getMessage());
            Log::error($e->getTraceAsString());
            return redirect()->back()->withInput()->with('error', 'حدث خطأ غير متوقع. يرجى المحاولة مرة أخرى.');
        }
    }
}
