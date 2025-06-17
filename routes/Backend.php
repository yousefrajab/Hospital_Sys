<?php

use App\Models\Admin;
use App\Events\MyEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AdminController;
use App\Http\Controllers\Dashboard\BedController;
use App\Http\Controllers\Dashboard\RoomController;
use App\Http\Controllers\Dashboard\DoctorController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Dashboard\DiseaseController;
use App\Http\Controllers\Dashboard\PatientController;
use App\Http\Controllers\Dashboard\SectionController;
use App\Http\Controllers\Dashboard\AmbulanceController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\InsuranceController;
use App\Http\Controllers\Dashboard\VitalSignController;
use App\Http\Controllers\Dashboard\MedicationController;
use App\Http\Controllers\Dashboard\RayEmployeeController;
use App\Http\Controllers\Dashboard\AdminProfileController;
use App\Http\Controllers\Dashboard\SingleServiceController;
use App\Http\Controllers\Dashboard\Admin\UserRoleController;
use App\Http\Controllers\Dashboard\PaymentAccountController;
use App\Http\Controllers\Dashboard\ReceiptAccountController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\Dashboard\PharmacyManagercontroller;
use App\Http\Controllers\Dashboard\PatientAdmissionController;
use App\Http\Controllers\Dashboard\PharmacyEmployeeController;
use App\Http\Controllers\Dashboard\LaboratorieEmployeeController;
use App\Http\Controllers\Dashboard\appointments\AppointmentController;

/*
|--------------------------------------------------------------------------
| Backend Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ],
    function () {
        //################################ dashboard admin ########################################
        // Route::get('/dashboard/admin', function () {
        //     return view('Dashboard.Admin.dashboard');
        // })->middleware(['auth:admin'])->name('dashboard.admin');

        Route::get('/dashboard/admin', [AdminProfileController::class, 'dashboard'])->middleware(['auth:admin'])->name('dashboard.admin');

        //################################ end dashboard admin ####################################
        Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {


            Route::get('/profile', [AdminProfileController::class, 'show'])->name('profile.show');
            Route::get('/profile/edit', [AdminProfileController::class, 'edit'])->name('profile.edit'); // للتعديل
            Route::patch('/profile', [AdminProfileController::class, 'update'])->name('profile.update'); // لحفظ التعديل



            //############################# sections route ##########################################

            Route::resource('Sections', SectionController::class);

            //############################# end sections route ######################################


            //############################# Doctors route ##########################################

            Route::resource('Doctors', DoctorController::class);
            Route::post('update_password', [DoctorController::class, 'update_password'])->name('update_password');
            Route::post('update_status', [DoctorController::class, 'update_status'])->name('update_status');
            // 1. مسار GET لعرض صفحة تعديل جدول العمل
            Route::get('/doctors/{id}/edit-schedule', [DoctorController::class, 'editSchedule'])->name('doctors.schedule.edit');

            // 2. مسار PUT (أو POST) لمعالجة تحديث جدول العمل
            Route::put('/doctors/schedule/{id}', [DoctorController::class, 'updateSchedule'])->name('doctors.schedule.update');
            //############################# end Doctors route ######################################


            //############################# sections route ##########################################

            Route::resource('Service', SingleServiceController::class);

            //############################# end sections route ######################################

            //############################# GroupServices route ##########################################

            Route::view('Add_GroupServices', 'livewire.GroupServices.include_create')->name('Add_GroupServices');

            //############################# end GroupServices route ######################################

            //############################# insurance route ##########################################

            Route::resource('insurance', InsuranceController::class);

            //############################# end insurance route ######################################

            //############################# Ambulance route ##########################################

            Route::resource('Ambulance', AmbulanceController::class);

            //############################# end Ambulance route ######################################


            //############################# Patients route ##########################################

            Route::resource('Patients', PatientController::class);
            Route::get('/QR/patient/{id}/showQR', [PatientController::class, 'showQR'])->name('showQR');

            //############################# end Patients route ######################################



            //############################# single_invoices route ##########################################

            Route::view('single_invoices', 'livewire.single_invoices.index')->name('single_invoices');

            Route::view('Print_single_invoices', 'livewire.single_invoices.print')->name('Print_single_invoices');

            //############################# end single_invoices route ######################################

            //############################# Receipt route ##########################################

            Route::resource('Receipt', ReceiptAccountController::class);

            //############################# end Receipt route ######################################

            //############################# Payment route ##########################################

            Route::resource('Payment', PaymentAccountController::class);

            //############################# end Payment route ######################################


            //############################# RayEmployee route ##########################################

            Route::resource('ray_employee', RayEmployeeController::class);
            Route::get('/ray_employee/{id}/edit', [RayEmployeeController::class, 'edit'])->name('ray_employee.edit');

            //############################# end RayEmployee route ######################################

            //############################# laboratorie_employee route ##########################################

            Route::resource('laboratorie_employee', LaboratorieEmployeeController::class);

            Route::get('/laboratorie_employee/{id}/edit', [LaboratorieEmployeeController::class, 'edit'])->name('laboratorie_employee.edit');


            //############################# end laboratorie_employee route ######################################


            //############################# single_invoices route ##########################################

            Route::view('group_invoices', 'livewire.Group_invoices.index')->name('group_invoices');

            Route::view('group_Print_single_invoices', 'livewire.Group_invoices.print')->name('group_Print_single_invoices');

            //############################# end single_invoices route ######################################


            Route::get('appointments', [AppointmentController::class, 'index'])->name('appointments.index');
            Route::put('appointments/approval/{id}', [AppointmentController::class, 'approval'])->name('appointments.approval');
            Route::get('appointments/approval', [AppointmentController::class, 'index2'])->name('appointments.index2');
            Route::delete('appointments/destroy/{id}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');
            // *** المسارات الجديدة ***
            Route::get('/completed', [AppointmentController::class, 'indexCompleted'])->name('completed'); // منتهية
            Route::get('/cancelled', [AppointmentController::class, 'indexCancelled'])->name('cancelled'); // ملغاة
            Route::patch('/admin-cancel/{appointment}', [AppointmentController::class, 'adminCancelAppointment'])->name('admin_cancel');
            Route::get('appointments/lapsed', [AppointmentController::class, 'lapsedAppointments'])->name('appointments.lapsed');
            // استخدمنا {appointment} للاستفادة من Route Model Binding

            // (اختياري) مسار الحذف النهائي (قد يكون موجوداً ضمن destroy في Route::resource)
            // Route::delete('/delete/{appointment}', [AppointmentController::class, 'destroy'])->name('destroy');

            // مسار عرض المستخدمين وأدوارهم
            Route::get('/users-roles', [UserRoleController::class, 'index'])->name('users.roles.index');

            Route::get('/users-roles/{role_key}/{id}/edit', [UserRoleController::class, 'editUser'])->name('users.roles.edit');
            // Route لتحديث بيانات المستخدم
            Route::match(['put', 'patch'], '/users-roles/{role_key}/{id}', [UserRoleController::class, 'updateUser'])->name('users.roles.update');

            Route::resource('rooms', RoomController::class);
            Route::resource('beds', BedController::class);
            Route::resource('patient-admissions', PatientAdmissionController::class)->names('patient_admissions');


            Route::resource('diseases', DiseaseController::class)->names('diseases');

            Route::resource('pharmacy_employee', PharmacyEmployeeController::class);
            Route::get('/pharmacy_employee/{id}/edit', [PharmacyEmployeeController::class, 'edit'])->name('pharmacy_employee.edit');

            Route::resource('medications', MedicationController::class);


            Route::resource('pharmacy_manager', PharmacyManagercontroller::class);
            Route::get('/pharmacy_manager/{id}/edit', [PharmacyManagerController::class, 'edit'])->name('pharmacy_manager.edit');

            Route::post('/patient-admissions/{patient_admission}/vital-signs', [VitalSignController::class, 'store'])->name('vital_signs.store');

            Route::get('/patient-admissions/{patient_admission}/vital-signs-monitoring', [PatientAdmissionController::class, 'vitalSignsMonitoringSheet'])->name('patient_admissions.vital_signs_sheet');

            // يمكنك إضافة مسارات أخرى لإدارة العلامات الحيوية لاحقاً
            Route::delete('/vital-signs/{vital_sign}', [VitalSignController::class, 'destroy'])->name('vital_signs.destroy');
            Route::get('/vital-signs/{vital_sign}/edit', [VitalSignController::class, 'edit'])->name('vital_signs.edit');
            Route::put('/vital-signs/{vital_sign}', [VitalSignController::class, 'update'])->name('vital_signs.update');

            Route::resource('testimonials', TestimonialController::class)->except(['show']); // لا نحتاج show عادة
            Route::patch('testimonials/{testimonial}/approve', [TestimonialController::class, 'approve'])->name('testimonials.approve');
            Route::patch('testimonials/{testimonial}/reject', [TestimonialController::class, 'reject'])->name('testimonials.reject');
        });
        require __DIR__ . '/auth.php';
    }
);
