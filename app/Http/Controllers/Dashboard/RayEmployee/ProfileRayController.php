<?php

namespace App\Http\Controllers\Dashboard\RayEmployee;

use App\Models\Ray;
use App\Models\RayEmployee;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Dashboard\RayEmployee\UpdateRayEmployeeProfileRequest; // استيراد FormRequest

class ProfileRayController extends Controller // يمكنك تسميته ProfileController داخل مجلد RayEmployee
{
    use UploadTrait;

    /**
     * عرض الملف الشخصي لموظف الأشعة المسج
     * ل.
     */

    public function dashboard()
    {
        // الإحصائيات الأساسية (كما هي في الـ view الحالي)
        $totalRays = Ray::count();
        $pendingRays = Ray::where('case', 0)->count();
        $completedRays = Ray::where('case', 1)->count();

        // يمكن تخصيص هذه الإحصائيات لتكون خاصة بالموظف الحالي إذا أردت
        // $employeeId = Auth::guard('ray_employee')->id();
        // $myCompletedRays = Ray::where('case', 1)->where('employee_id', $employeeId)->count();

        // آخر 5 طلبات أشعة (يمكنك زيادة العدد أو جعله قابل للتخصيص)
        $latestRays = Ray::with(['patient', 'doctor']) // Eager load relations
            ->latest() // Order by created_at desc
            ->take(5)
            ->get();

        // يمكنك إضافة المزيد من البيانات هنا إذا احتجت (مثل رسوم بيانية أكثر تفصيلاً)
        // مثال لبيانات رسم بياني لعدد الطلبات شهريًا (تحتاج لتعديل حسب احتياجك)
        $monthlyRayCounts = Ray::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->all();

        // تحويل أرقام الشهور إلى أسمائها (مثال بسيط)
        $monthLabels = [];
        $monthData = [];
        $monthNames = ["يناير", "فبراير", "مارس", "أبريل", "مايو", "يونيو", "يوليو", "أغسطس", "سبتمبر", "أكتوبر", "نوفمبر", "ديسمبر"];

        for ($m = 1; $m <= 12; $m++) {
            $monthLabels[] = $monthNames[$m - 1];
            $monthData[] = $monthlyRayCounts[$m] ?? 0;
        }


        return view('Dashboard.dashboard_RayEmployee.dashboard', compact(
            'totalRays',
            'pendingRays',
            'completedRays',
            'latestRays',
            'monthLabels',  // لإرسالها إلى الـ view للرسم البياني
            'monthData'     // لإرسالها إلى الـ view للرسم البياني
        ));
    }

    public function show()
    {
        $employee = Auth::guard('ray_employee')->user();
        if (!$employee) {
            // يمكنك التوجيه لصفحة تسجيل الدخول أو عرض خطأ
            return redirect()->route('ray_employee.login.form'); // افترض وجود هذا الـ route
        }
        // تحميل علاقة الصورة إذا كانت موجودة
        $employee->load('image');
        return view('Dashboard.ray_employee.profile.show', compact('employee'));
    }

    /**
     * عرض فورم تعديل الملف الشخصي.
     */
    public function edit()
    {
        $employee = Auth::guard('ray_employee')->user();
        if (!$employee) {
            return redirect()->route('ray_employee.login.form');
        }
        $employee->load('image');
        return view('Dashboard.ray_employee.profile.edit', compact('employee'));
    }

    /**
     * تحديث الملف الشخصي لموظف الأشعة.
     */
    public function update(UpdateRayEmployeeProfileRequest $request)
    {
        $employee = Auth::guard('ray_employee')->user();
        if (!$employee) {
            abort(403, 'غير مصرح لك.');
        }

        $validatedData = $request->validated();
        Log::info("Attempting profile update for RayEmployee ID: {$employee->id}");
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
                Log::info("Password updated for RayEmployee ID: {$employee->id}");
            } elseif ($request->filled('password') && !$request->filled('current_password')) {
                DB::rollBack();
                throw ValidationException::withMessages([
                    'current_password' => 'يجب إدخال كلمة المرور الحالية لتغيير كلمة المرور.',
                ]);
            }

            // التعامل مع رفع الصورة
            // تحديث الصورة إذا تم رفع صورة جديدة
            if ($request->hasFile('photo')) {
                // حذف الصورة القديمة إذا كانت موجودة
                if ($employee->image) {
                    $this->Delete_attachment(
                        'upload_image',
                        'rayEmployees/' . $employee->image->filename,
                        $employee->id,
                        'App\Models\RayEmployee'
                    );

                    $this->verifyAndStoreImage(
                        $request,
                        'photo',
                        'rayEmployees',
                        'upload_image',
                        $employee->id,
                        'App\Models\RayEmployee'
                    );
                }
                // رفع الصورة الجديدة

            }

            // حفظ كل التغييرات
            $employee->save();
            DB::commit();
            Log::info("Profile update committed successfully for RayEmployee ID {$employee->id}");

            // إعادة التوجيه مع رسالة نجاح
            return redirect()->route('ray_employee.profile.show') // اسم route عرض الملف الشخصي
                ->with('success', 'تم تحديث الملف الشخصي بنجاح.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack(); // تراجع في حالة خطأ التحقق الداخلي (مثل كلمة المرور الحالية)
            Log::error("RayEmployee Profile Update Validation Error ID {$employee->id}: " . json_encode($e->errors()));
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error("Error updating ray employee profile for ID {$employee->id}: " . $e->getMessage());
            Log::error($e->getTraceAsString());
            return redirect()->back()->withInput()->with('error', 'حدث خطأ غير متوقع. يرجى المحاولة مرة أخرى.');
        }
    }
}
