<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\RayEmployee;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\LaboratorieEmployee;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use App\Models\Section; // استيراد Section
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash; // لاستخدام Hash
use App\Traits\UploadTrait;         // لاستخدام UploadTrait
// ستحتاج لإنشاء FormRequests إذا لم تكن موجودة
// use App\Http\Requests\Dashboard\User\UpdateDoctorUserRequest; // مثال
// use App\Http\Requests\Dashboard\User\UpdateRayEmployeeUserRequest; // مثال

class UserRoleController extends Controller
{
    use UploadTrait;

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 15); // السماح بتغيير عدد العناصر لكل صفحة
        $locale = app()->getLocale();
        Log::info("UserRoleController: Fetching users for roles page...");

        $mapUser = function ($user, $roleName, $roleKey, $isTranslatableName = false) use ($locale) {
            $user->role_name = $roleName;
            $user->role_key = $roleKey;
            if ($isTranslatableName && method_exists($user, 'getTranslation')) {
                $user->display_name = $user->getTranslation('name', $locale, false) ?: $user->name;
            } else {
                $user->display_name = $user->name;
            }
            // التحقق من وجود خاصية status قبل الوصول إليها
            if (property_exists($user, 'status') && $user->status !== null) {
                $user->status_display = $user->status ? 'نشط' : 'غير نشط';
            } else {
                // إذا لم يكن للمستخدم status، افترض أنه نشط أو أي قيمة تراها مناسبة
                $user->status = true; // قيمة افتراضية للحالة
                $user->status_display = 'نشط';
            }
            return $user;
        };

        $doctors = Doctor::with(['image', 'translations']) // تحميل الترجمة مع الصورة
            ->select('id', 'email', 'created_at', 'status', 'phone', 'section_id') // أضف section_id
            ->get()
            ->map(fn($user) => $mapUser($user, 'طبيب', 'doctor', true)); // true لأن الاسم مترجم

        $patients = Patient::with(['image', 'translations']) // تحميل الترجمة مع الصورة
            ->select('id', 'email', 'created_at', 'phone') // المرضى قد لا يكون لديهم status
            ->get()
            ->map(fn($user) => $mapUser($user, 'مريض', 'patient', true));

        $rayEmployees = RayEmployee::with('image')
            ->select('id', 'name', 'email', 'created_at', 'status', 'phone')
            ->get()
            ->map(fn($user) => $mapUser($user, 'موظف أشعة', 'ray_employee'));

        $labEmployees = LaboratorieEmployee::with('image')
            ->select('id', 'name', 'email', 'created_at', 'status', 'phone')
            ->get()
            ->map(fn($user) => $mapUser($user, 'موظف مختبر', 'laboratorie_employee'));

        $allUsersCollection = new Collection(array_merge(
            $doctors->all(),
            $patients->all(),
            $rayEmployees->all(),
            $labEmployees->all()
        ));
        Log::info("Total users merged: " . $allUsersCollection->count());

        $filteredUsers = $allUsersCollection;
        if ($request->filled('role')) {
            $filteredUsers = $filteredUsers->where('role_key', $request->role);
        }
        // فلتر الحالة
        if ($request->filled('status') && $request->status !== '') { // تحقق أن القيمة ليست سلسلة فارغة
            $statusValue = (bool) $request->status; // تحويل إلى boolean
            $filteredUsers = $filteredUsers->filter(function ($user) use ($statusValue) {
                // التأكد من وجود خاصية status قبل فلترتها
                return property_exists($user, 'status') && $user->status === $statusValue;
            });
        }

        if ($request->filled('search')) {
            $searchTerm = mb_strtolower(trim($request->search), 'UTF-8');
            $filteredUsers = $filteredUsers->filter(function ($user) use ($searchTerm) {
                $nameMatch = mb_strpos(mb_strtolower($user->display_name ?? '', 'UTF-8'), $searchTerm) !== false;
                $emailMatch = mb_strpos(mb_strtolower($user->email ?? '', 'UTF-8'), $searchTerm) !== false;
                $phoneMatch = $user->phone ? (mb_strpos(mb_strtolower((string)$user->phone, 'UTF-8'), $searchTerm) !== false) : false;
                return $nameMatch || $emailMatch || $phoneMatch;
            });
        }

        $sortedUsers = $filteredUsers->sortBy('display_name');
        $currentPage = LengthAwarePaginator::resolveCurrentPage('page');
        $currentPageItems = $sortedUsers->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $paginatedUsers = new LengthAwarePaginator($currentPageItems, $sortedUsers->count(), $perPage, $currentPage, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);

        return view('Dashboard.Admin.users_roles.index', [
            'users' => $paginatedUsers,
            'roles' => [
                'doctor' => 'طبيب',
                'patient' => 'مريض',
                'ray_employee' => 'موظف أشعة',
                'laboratorie_employee' => 'موظف مختبر',
            ],
            'request' => $request,
        ]);
    }

    public function editUser($role_key, $id)
    {
        $editRouteName = null;

        switch ($role_key) {
            case 'doctor':
                // افترض أن اسم route تعديل الطبيب هو 'admin.Doctors.edit'
                $editRouteName = 'admin.Doctors.edit';
                break;
            case 'patient':
                // افترض أن اسم route تعديل المريض هو 'admin.Patients.edit'
                $editRouteName = 'admin.Patients.edit';
                break;
            case 'ray_employee':
                // افترض أن اسم route تعديل موظف الأشعة هو 'admin.ray_employee.edit'
                $editRouteName = 'admin.ray_employee.edit';
                break;
            case 'laboratorie_employee':
                // افترض أن اسم route تعديل موظف المختبر هو 'admin.laboratorie_employee.edit'
                $editRouteName = 'admin.laboratorie_employee.edit';
                break;
            default:
                Log::warning("UserRoleController: Unknown role_key '{$role_key}' for editing user ID {$id}. Cannot redirect.");
                return redirect()->route('admin.users.roles.index')->with('error', 'لا يمكن تعديل هذا النوع من المستخدمين من هنا.');
        }

        // التحقق من وجود الـ route قبل التوجيه
        if ($editRouteName && \Illuminate\Support\Facades\Route::has($editRouteName)) {
            // التوجيه إلى الـ route المحدد مع تمرير الـ ID
            // لاحظ أن اسم البارامتر في الـ route قد يختلف (مثلاً 'doctor' بدلاً من 'id')
            // تأكد من أن الـ route يتوقع 'id' أو قم بتعديل البارامتر هنا
            // سأفترض أن الـ routes تتوقع بارامتر اسمه مطابق لاسم الموديل (doctor, patient, etc.)
            $paramName = $role_key; // افتراض أولي
            // تعديل خاص لأسماء الـ routes الشائعة
            if ($role_key === 'doctor') $paramName = 'Doctor'; // إذا كان route model binding يتوقع Doctor
            if ($role_key === 'patient') $paramName = 'Patient';

            // تحقق من اسم البارامتر المتوقع في الـ route المحدد
            // هذا الجزء قد يحتاج لتعديل بناءً على تعريف الـ routes لديك
            try {
                // إذا كان الـ route يتوقع ID فقط
                if (str_contains(app('router')->getRoutes()->getByName($editRouteName)->uri(), '{id}')) {
                    return Redirect::route($editRouteName, ['id' => $id]);
                }
                // إذا كان الـ route يتوقع اسم الموديل (Route Model Binding)
                else {
                    // قد تحتاج لتمرير الكائن كله أو فقط ال ID بناءً على تعريف الـ route
                    // سأمرر الـ ID هنا كافتراض أكثر أمانًا يتوافق مع معظم التعريفات
                    // التي لا تستخدم Route Model Binding صريح في اسم الـ route
                    // تحقق من اسم البارامتر في تعريف الـ route (قد يكون doctor, patient, ray_employee, id)
                    // مثال: إذا كان Route::get('/doctors/{doctor}/edit', ...)->name('admin.Doctors.edit');
                    $routeParameters = app('router')->getRoutes()->getByName($editRouteName)->parameterNames();
                    $parameterName = $routeParameters[0] ?? 'id'; // الحصول على اسم أول بارامتر
                    return Redirect::route($editRouteName, [$parameterName => $id]);
                }
            } catch (\Exception $e) {
                Log::error("Error generating route '{$editRouteName}' for user ID {$id}: " . $e->getMessage());
                return redirect()->route('admin.users.roles.index')->with('error', 'خطأ في الوصول لصفحة التعديل.');
            }
        } else {
            Log::warning("UserRoleController: Edit route '{$editRouteName}' not found for role_key '{$role_key}'.");
            return redirect()->route('admin.users.roles.index')->with('error', 'صفحة التعديل غير متاحة لهذا الدور.');
        }
    }
}
