<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\RayEmployee;
use App\Models\Section;
use Illuminate\Http\Request;
use App\Models\PharmacyManager;       // <--- *** استيراد موديل مدير الصيدلية ***
use App\Models\PharmacyEmployee;     // <--- *** استيراد موديل موظف الصيدلية ***
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\LaboratorieEmployee;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use App\Traits\UploadTrait;

class UserRoleController extends Controller
{
    use UploadTrait;

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 15);
        $locale = app()->getLocale();
        Log::info("UserRoleController: Fetching users for roles page...");

        $mapUser = function ($user, $roleName, $roleKey, $isTranslatableName = false) use ($locale) {
            $user->role_name = $roleName;
            $user->role_key = $roleKey; // مهم للـ CSS والفلاتر
            if ($isTranslatableName && method_exists($user, 'getTranslation')) {
                $user->display_name = $user->getTranslation('name', $locale, false) ?: $user->name;
            } else {
                $user->display_name = $user->name;
            }
            if (property_exists($user, 'status') && $user->status !== null) {
                $user->status_display = $user->status ? 'نشط' : 'غير نشط';
            } else {
                $user->status = true;
                $user->status_display = 'نشط';
            }
            return $user;
        };

        // جلب الأطباء
        $doctors = Doctor::with(['image', 'translations', 'section' => function($q_section) {
                $q_section->withTranslation()->select('id'); // جلب القسم مع الترجمة واسم محدد
            }])
            ->select('id', 'email', 'created_at', 'status', 'phone', 'section_id')
            ->get()
            ->map(function($user) use ($mapUser, $locale) { // تمرير $locale هنا
                $user = $mapUser($user, 'طبيب', 'doctor', true);
                // جلب اسم القسم المترجم إذا كان موجودًا
                $user->section_name = $user->section ? ($user->section->getTranslation('name', $locale, false) ?: $user->section->name) : 'غير محدد';
                return $user;
            });


        // جلب المرضى
        $patients = Patient::with(['image', 'translations'])
            ->select('id', 'email', 'created_at', 'phone') // المرضى قد لا يكون لديهم status
            ->get()
            ->map(fn($user) => $mapUser($user, 'مريض', 'patient', true));

        // جلب موظفي الأشعة
        $rayEmployees = RayEmployee::with('image')
            ->select('id', 'name', 'email', 'created_at', 'status', 'phone')
            ->get()
            ->map(fn($user) => $mapUser($user, 'موظف أشعة', 'ray_employee'));

        // جلب موظفي المختبر
        $labEmployees = LaboratorieEmployee::with('image')
            ->select('id', 'name', 'email', 'created_at', 'status', 'phone')
            ->get()
            ->map(fn($user) => $mapUser($user, 'موظف مختبر', 'laboratorie_employee'));

        // *** جلب مديري الصيدلية ***
        $pharmacyManagers = PharmacyManager::with('image') // افترض أن لديهم علاقة image
            ->select('id', 'name', 'email', 'created_at', 'status', 'phone') // تأكد من أن هذه الأعمدة موجودة
            ->get()
            ->map(fn($user) => $mapUser($user, 'مدير صيدلية', 'pharmacy_manager'));

        // *** جلب موظفي الصيدلية ***
        $pharmacyEmployees = PharmacyEmployee::with('image') // افترض أن لديهم علاقة image
            ->select('id', 'name', 'email', 'created_at', 'status', 'phone') // تأكد من أن هذه الأعمدة موجودة
            ->get()
            ->map(fn($user) => $mapUser($user, 'موظف صيدلية', 'pharmacy_employee'));


        // دمج جميع المستخدمين في مجموعة واحدة
        $allUsersCollection = new Collection(array_merge(
            $doctors->all(),
            $patients->all(),
            $rayEmployees->all(),
            $labEmployees->all(),
            $pharmacyManagers->all(),     // <--- *** إضافة مديري الصيدلية ***
            $pharmacyEmployees->all()    // <--- *** إضافة موظفي الصيدلية ***
        ));
        Log::info("Total users merged including pharmacy staff: " . $allUsersCollection->count());

        // تطبيق الفلاتر (تبقى كما هي)
        $filteredUsers = $allUsersCollection;
        if ($request->filled('role')) {
            $filteredUsers = $filteredUsers->where('role_key', $request->role);
        }
        // فلتر الحالة
        if ($request->filled('status') && $request->status !== '') {
            $statusValue = (bool) $request->status;
            $filteredUsers = $filteredUsers->filter(function ($user) use ($statusValue) {
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

        // ترتيب وترقيم النتائج (تبقى كما هي)
        $sortedUsers = $filteredUsers->sortByDesc('created_at')->sortBy('display_name'); // ترتيب إضافي حسب تاريخ الإنشاء (الأحدث أولاً) ثم الاسم
        $currentPage = LengthAwarePaginator::resolveCurrentPage('page');
        $currentPageItems = $sortedUsers->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $paginatedUsers = new LengthAwarePaginator($currentPageItems, $sortedUsers->count(), $perPage, $currentPage, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);

        return view('Dashboard.Admin.users_roles.index', [
            'users' => $paginatedUsers,
            'roles' => [ // *** تحديث قائمة الأدوار ***
                'doctor' => 'طبيب',
                'patient' => 'مريض',
                'ray_employee' => 'موظف أشعة',
                'laboratorie_employee' => 'موظف مختبر',
                'pharmacy_manager' => 'مدير صيدلية',      // <--- *** إضافة دور مدير الصيدلية ***
                'pharmacy_employee' => 'موظف صيدلية',   // <--- *** إضافة دور موظف الصيدلية ***
            ],
            'request' => $request,
        ]);
    }

    public function editUser($role_key, $id)
    {
        $editRouteName = null;
        // ... (الـ switch case لديك) ...
        switch ($role_key) {
            case 'doctor':
                $editRouteName = 'admin.Doctors.edit';
                break;
            case 'patient':
                $editRouteName = 'admin.Patients.edit';
                break;
            case 'ray_employee':
                $editRouteName = 'admin.ray_employee.edit';
                break;
            case 'laboratorie_employee':
                $editRouteName = 'admin.laboratorie_employee.edit';
                break;
            // *** إضافة حالات لمدير وموظف الصيدلية ***
            case 'pharmacy_manager':
                // افترض أن لديك route لتعديل مدير الصيدلية
                $editRouteName = 'admin.pharmacy_manager.edit'; // تأكد من اسم الـ route لديك
                break;
            case 'pharmacy_employee':
                // افترض أن لديك route لتعديل موظف الصيدلية
                $editRouteName = 'admin.pharmacy_employee.edit'; // تأكد من اسم الـ route لديك
                break;
            default:
                Log::warning("UserRoleController: Unknown role_key '{$role_key}' for editing user ID {$id}. Cannot redirect.");
                return redirect()->route('admin.users.roles.index')->with('error', 'لا يمكن تعديل هذا النوع من المستخدمين من هنا.');
        }

        // ... (باقي كود التوجيه كما هو، سيفترض أن الـ routes الجديدة تتبع نفس النمط) ...
         if ($editRouteName && \Illuminate\Support\Facades\Route::has($editRouteName)) {
            try {
                $routeParameters = app('router')->getRoutes()->getByName($editRouteName)->parameterNames();
                $parameterNameInRoute = $routeParameters[0] ?? 'id'; // الحصول على اسم أول بارامتر في تعريف الـ route
                return Redirect::route($editRouteName, [$parameterNameInRoute => $id]);
            } catch (\Exception $e) {
                Log::error("Error generating route '{$editRouteName}' for user (role: {$role_key}, ID: {$id}): " . $e->getMessage());
                return redirect()->route('admin.users.roles.index')->with('error', 'خطأ في الوصول لصفحة التعديل.');
            }
        } else {
            Log::warning("UserRoleController: Edit route '{$editRouteName}' not found for role_key '{$role_key}'.");
            return redirect()->route('admin.users.roles.index')->with('error', 'صفحة التعديل غير متاحة لهذا الدور.');
        }
    }

    // يمكنك إضافة دوال لـ updatePassword, updateStatus إذا كانت عامة أو تحتاج لتكييف خاص
    // إذا كانت هذه الدوال موجودة في الكنترولرات الخاصة بكل دور، فالأمر جيد.
}
