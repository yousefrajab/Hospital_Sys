<?php

use Illuminate\Support\Facades\Route;

// Controllers تسجيل الدخول والخروج الحالية (تبقى كما هي)
use App\Http\Controllers\Auth\AdminController;
use App\Http\Controllers\Auth\DoctorController;
use App\Http\Controllers\Auth\PatientController;
use App\Http\Controllers\Auth\RayEmployeeController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\RegisteredPatientController;

// Controllers تسجيل المستخدمين (تبقى كما هي)
use App\Http\Controllers\Auth\LaboratorieEmployeeController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// ### Controllers "نسيت كلمة المرور" المخصصة (يجب إنشاؤها) ###
// يجب أن تكون هذه الـ Controllers موجودة في المسارات المحددة
use App\Http\Controllers\Auth\NewPasswordController; // لـ reset


use App\Http\Controllers\Auth\PasswordResetLinkController; // لـ forgot
use App\Http\Controllers\Auth\Admin\ResetPasswordController as AdminResetPasswordController;
use App\Http\Controllers\Auth\Admin\ForgotPasswordController as AdminForgotPasswordController;

use App\Http\Controllers\Auth\Doctor\ResetPasswordController as DoctorResetPasswordController;
use App\Http\Controllers\Auth\Doctor\ForgotPasswordController as DoctorForgotPasswordController;

use App\Http\Controllers\Auth\Patient\ResetPasswordController as PatientResetPasswordController;
use App\Http\Controllers\Auth\Patient\ForgotPasswordController as PatientForgotPasswordController;

// Controllers العامة (تبقى كما هي للـ Guard 'web' إذا كنت تستخدمه)
use App\Http\Controllers\Auth\RayEmployee\ResetPasswordController as RayEmployeeResetPasswordController;
use App\Http\Controllers\Auth\RayEmployee\ForgotPasswordController as RayEmployeeForgotPasswordController;
use App\Http\Controllers\Auth\LaboratorieEmployee\ResetPasswordController as LaboratorieEmployeeResetPasswordController;
use App\Http\Controllers\Auth\LaboratorieEmployee\ForgotPasswordController as LaboratorieEmployeeForgotPasswordController;
// ... (بقية الـ Controllers العامة للتحقق من الإيميل وتأكيد كلمة المرور إذا كنت تستخدمها للـ web guard)


/*
|--------------------------------------------------------------------------
| المسارات العامة (Web Guard - إذا كان لديك مستخدمين عاديين)
|--------------------------------------------------------------------------
*/
// Route::middleware('guest:web')->group(function () {
//     Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
//     Route::post('/register', [RegisteredUserController::class, 'store']);
//     Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
//     Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.user');

//     // نسيت كلمة المرور للمستخدم العادي (web guard) - تستخدم الـ Controllers العامة
//     Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
//     Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
//     Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
//     Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');
// });
// Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth:web')->name('logout.user');
// // ... (مسارات التحقق من الإيميل وتأكيد كلمة المرور للـ web guard يمكن أن تبقى هنا إذا كنت تستخدمها)


/*
|--------------------------------------------------------------------------
| مسارات الأدمن (Admin Guard)
|--------------------------------------------------------------------------
*/
// تسجيل الدخول والخروج للأدمن (كما هي لديك)
Route::post('/login/admin', [AdminController::class, 'store'])->middleware('guest:admin')->name('login.admin');
Route::post('/logout/admin', [AdminController::class, 'destroy'])->middleware('auth:admin')->name('logout.admin');

// نسيت كلمة المرور للأدمن
Route::get('/forgot-password/admin', [AdminForgotPasswordController::class, 'showLinkRequestForm'])
    ->middleware('guest:admin')->name('admin.password.request');
Route::post('/forgot-password/admin', [AdminForgotPasswordController::class, 'sendResetLinkEmail'])
    ->middleware('guest:admin')->name('admin.password.email');
Route::get('/reset-password/admin/{token}', [AdminResetPasswordController::class, 'showResetForm'])
    ->middleware('guest:admin')->name('admin.password.reset');
Route::post('/reset-password/admin', [AdminResetPasswordController::class, 'reset']) // استخدام دالة reset
    ->middleware('guest:admin')->name('admin.password.update');


/*
|--------------------------------------------------------------------------
| مسارات الطبيب (Doctor Guard)
|--------------------------------------------------------------------------
*/
Route::post('/login/doctor', [DoctorController::class, 'store'])->middleware('guest:doctor')->name('login.doctor');
Route::post('/logout/doctor', [DoctorController::class, 'destroy'])->middleware('auth:doctor')->name('logout.doctor');

// نسيت كلمة المرور للطبيب
Route::get('/forgot-password/doctor', [DoctorForgotPasswordController::class, 'showLinkRequestForm'])
    ->middleware('guest:doctor')->name('doctor.password.request');
Route::post('/forgot-password/doctor', [DoctorForgotPasswordController::class, 'sendResetLinkEmail'])
    ->middleware('guest:doctor')->name('doctor.password.email');
Route::get('/reset-password/doctor/{token}', [DoctorResetPasswordController::class, 'showResetForm'])
    ->middleware('guest:doctor')->name('doctor.password.reset');
Route::post('/reset-password/doctor', [DoctorResetPasswordController::class, 'reset'])
    ->middleware('guest:doctor')->name('doctor.password.update');


/*
|--------------------------------------------------------------------------
| مسارات المريض (Patient Guard)
|--------------------------------------------------------------------------
*/
Route::get('/register/patient', [RegisteredPatientController::class, 'create'])->middleware('guest:patient')->name('register.patient');
Route::post('/register/patient', [RegisteredPatientController::class, 'store'])->middleware('guest:patient'); // تم تعديل اسم الـ route ليكون فريدًا إذا لم يكن كذلك
Route::post('/login/patient', [PatientController::class, 'store'])->middleware('guest:patient')->name('login.patient');
Route::post('/logout/patient', [PatientController::class, 'destroy'])->middleware('auth:patient')->name('logout.patient');

// نسيت كلمة المرور للمريض
Route::get('/forgot-password/patient', [PatientForgotPasswordController::class, 'showLinkRequestForm'])
    ->middleware('guest:patient')->name('patient.password.request');
Route::post('/forgot-password/patient', [PatientForgotPasswordController::class, 'sendResetLinkEmail'])
    ->middleware('guest:patient')->name('patient.password.email');
Route::get('/reset-password/patient/{token}', [PatientResetPasswordController::class, 'showResetForm'])
    ->middleware('guest:patient')->name('patient.password.reset');
Route::post('/reset-password/patient', [PatientResetPasswordController::class, 'reset'])
    ->middleware('guest:patient')->name('patient.password.update');


/*
|--------------------------------------------------------------------------
| مسارات موظف الأشعة (RayEmployee Guard)
|--------------------------------------------------------------------------
*/
Route::post('/login/ray_employee', [RayEmployeeController::class, 'store'])->middleware('guest:ray_employee')->name('login.ray_employee');
Route::post('/logout/ray_employee', [RayEmployeeController::class, 'destroy'])->middleware('auth:ray_employee')->name('logout.ray_employee');

// نسيت كلمة المرور لموظف الأشعة
Route::get('/forgot-password/ray-employee', [RayEmployeeForgotPasswordController::class, 'showLinkRequestForm']) // استخدام ray-employee في الـ URL
    ->middleware('guest:ray_employee')->name('ray_employee.password.request');
Route::post('/forgot-password/ray-employee', [RayEmployeeForgotPasswordController::class, 'sendResetLinkEmail'])
    ->middleware('guest:ray_employee')->name('ray_employee.password.email');
Route::get('/reset-password/ray-employee/{token}', [RayEmployeeResetPasswordController::class, 'showResetForm'])
    ->middleware('guest:ray_employee')->name('ray_employee.password.reset');
Route::post('/reset-password/ray-employee', [RayEmployeeResetPasswordController::class, 'reset'])
    ->middleware('guest:ray_employee')->name('ray_employee.password.update');


/*
|--------------------------------------------------------------------------
| مسارات موظف المختبر (LaboratorieEmployee Guard)
|--------------------------------------------------------------------------
*/
Route::post('/login/laboratorie_employee', [LaboratorieEmployeeController::class, 'store'])->middleware('guest:laboratorie_employee')->name('login.laboratorie_employee');
Route::post('/logout/laboratorie_employee', [LaboratorieEmployeeController::class, 'destroy'])->middleware('auth:laboratorie_employee')->name('logout.laboratorie_employee');

// نسيت كلمة المرور لموظف المختبر
Route::get('/forgot-password/laboratorie-employee', [LaboratorieEmployeeForgotPasswordController::class, 'showLinkRequestForm']) // استخدام laboratorie-employee في الـ URL
    ->middleware('guest:laboratorie_employee')->name('laboratorie_employee.password.request');
Route::post('/forgot-password/laboratorie-employee', [LaboratorieEmployeeForgotPasswordController::class, 'sendResetLinkEmail'])
    ->middleware('guest:laboratorie_employee')->name('laboratorie_employee.password.email');
Route::get('/reset-password/laboratorie-employee/{token}', [LaboratorieEmployeeResetPasswordController::class, 'showResetForm'])
    ->middleware('guest:laboratorie_employee')->name('laboratorie_employee.password.reset');
Route::post('/reset-password/laboratorie-employee', [LaboratorieEmployeeResetPasswordController::class, 'reset'])
    ->middleware('guest:laboratorie_employee')->name('laboratorie_employee.password.update');
