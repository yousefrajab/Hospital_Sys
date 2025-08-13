<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\RayEmployee;
use App\Models\Section;
use Illuminate\Http\Request;
use App\Models\PharmacyManager;
use App\Models\PharmacyEmployee;
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
            $user->role_key = $roleKey;
            if ($isTranslatableName && method_exists($user, 'getTranslation')) {
                $user->display_name = $user->getTranslation('name', $locale, false) ?: $user->name;
            } else {
                $user->display_name = $user->name;
            }
            if (property_exists($user, 'status') && $user->status !== null) {
                $user->status_display = $user->status ?  __('AdminSb.active') : __('AdminSb.Inactive');
            } else {
                $user->status = true;
                $user->status_display =  __('AdminSb.active');
            }
            return $user;
        };

        $doctors = Doctor::with(['image', 'section' => function ($query) {
            $query->withTranslation();
        }])
            ->get()
            ->map(function ($user) use ($mapUser, $locale) {
                $user = $mapUser($user, __('AdminSb.Doctors'), 'doctor', true);
                $user->section_name = $user->section->name ?? 'غير محدد';
                return $user;
            });


        $patients = Patient::with(['image'])
            ->get()
            ->map(fn($user) => $mapUser($user, __('AdminSb.Patients'), 'patient', true));

        $rayEmployees = RayEmployee::with('image')
            ->select('id', 'name', 'email', 'created_at', 'status', 'phone')
            ->get()
            ->map(fn($user) => $mapUser($user, __('AdminSb.Radiology staff'), 'ray_employee'));

        $labEmployees = LaboratorieEmployee::with('image')
            ->select('id', 'name', 'email', 'created_at', 'status', 'phone')
            ->get()
            ->map(fn($user) => $mapUser($user, __('AdminSb.laboratory staff'), 'laboratorie_employee'));

        $pharmacyManagers = PharmacyManager::with('image')
            ->select('id', 'name', 'email', 'created_at', 'status', 'phone')
            ->get()
            ->map(fn($user) => $mapUser($user, __('AdminSb.PharmacyManager'), 'pharmacy_manager'));

        $pharmacyEmployees = PharmacyEmployee::with('image')
            ->select('id', 'name', 'email', 'created_at', 'status', 'phone')
            ->get()
            ->map(fn($user) => $mapUser($user, __('AdminSb.PharmacyEmployees'), 'pharmacy_employee'));


        $allUsersCollection = new Collection(array_merge(
            $doctors->all(),
            $patients->all(),
            $rayEmployees->all(),
            $labEmployees->all(),
            $pharmacyManagers->all(),
            $pharmacyEmployees->all()
        ));
        Log::info("Total users merged including pharmacy staff: " . $allUsersCollection->count());

        $filteredUsers = $allUsersCollection;
        if ($request->filled('role')) {
            $filteredUsers = $filteredUsers->where('role_key', $request->role);
        }
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

        $sortedUsers = $filteredUsers->sortByDesc('created_at')->sortBy('display_name');
        $currentPage = LengthAwarePaginator::resolveCurrentPage('page');
        $currentPageItems = $sortedUsers->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $paginatedUsers = new LengthAwarePaginator($currentPageItems, $sortedUsers->count(), $perPage, $currentPage, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);

        return view('Dashboard.Admin.users_roles.index', [
            'users' => $paginatedUsers,
            'roles' => [
                'doctor' => __('AdminSb.Doctors'),
                'patient' => __('AdminSb.Patients'),
                'ray_employee' => __('AdminSb.Radiology staff'),
                'laboratorie_employee' => __('AdminSb.laboratory staff'),
                'pharmacy_manager' => __('AdminSb.PharmacyManager'),
                'pharmacy_employee' =>  __('AdminSb.PharmacyEmployees'),
            ],
            'request' => $request,
        ]);
    }

    public function editUser($role_key, $id)
    {
        $editRouteName = null;
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
            case 'pharmacy_manager':
                $editRouteName = 'admin.pharmacy_manager.edit';
                break;
            case 'pharmacy_employee':
                $editRouteName = 'admin.pharmacy_employee.edit';
                break;
            default:
                Log::warning("UserRoleController: Unknown role_key '{$role_key}' for editing user ID {$id}. Cannot redirect.");
                return redirect()->route('admin.users.roles.index')->with('error', 'لا يمكن تعديل هذا النوع من المستخدمين من هنا.');
        }

        if ($editRouteName && \Illuminate\Support\Facades\Route::has($editRouteName)) {
            try {
                $routeParameters = app('router')->getRoutes()->getByName($editRouteName)->parameterNames();
                $parameterNameInRoute = $routeParameters[0] ?? 'id';
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


}
