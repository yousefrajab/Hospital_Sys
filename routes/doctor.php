<?php

// use App\Models\Mainn;
use App\Http\Livewire\Chat\Main;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Chat\ChatWindow;
use App\Http\Livewire\Chat\Createchat;
use App\Http\Middleware\CheckDoctorStatus;
use App\Http\Controllers\doctor\InvoiceController;
use App\Http\Controllers\Dashboard\DoctorController;
use App\Http\Controllers\Dashboard_Doctor\RayController;
use App\Http\Controllers\Dashboard\PrescriptionController;
use App\Http\Controllers\Doctor\ServiceManagementController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\Dashboard\Doctors\ProfileController;
use App\Http\Controllers\Dashboard_Doctor\DiagnosticController;
use App\Http\Controllers\Dashboard_Doctor\LaboratorieController;
use App\Http\Controllers\Dashboard_Doctor\PatientDetailsController;

/*
|--------------------------------------------------------------------------
| doctor Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
], function () {


    //################################ dashboard doctor ########################################

    //################################ end dashboard doctor #####################################

    //---------------------------------------------------------------------------------------------------------------
    Route::get('/dashboard/doctor', [DoctorController::class, 'dashboard'])->middleware(['auth:doctor', 'doctor.status'])->name('dashboard.doctor');

    Route::middleware(['auth:doctor', 'doctor.status'])->prefix('doctor')->name('doctor.')->group(function () {



        //############################# SERVICES MANAGEMENT BY DOCTOR #####################################
        Route::prefix('services-management')->name('services_management.')->group(function () {
            Route::get('/', [ServiceManagementController::class, 'index'])->name('index'); // عرض كل الخدمات (الخاصة به + التي يمكنه إضافتها)
            Route::get('/create', [ServiceManagementController::class, 'create'])->name('create'); // فورم إنشاء خدمة جديدة
            Route::post('/', [ServiceManagementController::class, 'store'])->name('store'); // حفظ الخدمة الجديدة
            Route::get('/{service}/edit', [ServiceManagementController::class, 'edit'])->name('edit'); // فورم تعديل خدمة (يجب أن يكون الطبيب هو المنشئ أو لديه صلاحية)
            Route::put('/{service}', [ServiceManagementController::class, 'update'])->name('update'); // تحديث الخدمة
            Route::delete('/{service}', [ServiceManagementController::class, 'destroy'])->name('destroy'); // حذف خدمة (إذا كان هو المنشئ)

            // لربط/فصل الخدمات العامة التي لم ينشئها هو
            Route::post('/attach-existing', [ServiceManagementController::class, 'attachExistingService'])->name('attach_existing');
            Route::delete('/detach-existing/{service}', [ServiceManagementController::class, 'detachExistingService'])->name('detach_existing');
        });
        //############################# END SERVICES MANAGEMENT BY DOCTOR #################################

        //############################# completed_invoices route ##########################################

        //############################# end invoices route ######################################


        //############################# review_invoices route ##########################################
        Route::post('add_review', [DiagnosticController::class, 'addReview'])->name('add_review');
        //############################# end invoices route #############################################


        //############################# Diagnostics route ##########################################

        Route::resource('Diagnostics', DiagnosticController::class);

        //############################# end Diagnostics route ######################################
        // Route::resource('Doctors', DoctorController::class);

        // إضافة مسار تعديل الجدول
        // --- مسارات جدول العمل ---
        Route::get('/schedule', [DoctorController::class, 'showSchedule'])->name('schedule.show'); // <-- المسار الجديد لعرض الجدول
        Route::get('/schedule/edit/{id}', [DoctorController::class, 'editSchedule'])->name('schedule.edit');
        Route::put('/schedule/update', [DoctorController::class, 'updateSchedule'])->name('schedule.update'); // <-- رابط تحديث الجدول (استخدم ID الطبيب الحالي)

        // في ملف routes/web.php


        Route::get('/doctors/{doctor}/edit-schedule', [DoctorController::class, 'editSchedulee'])
            ->name('doctors.schedule.editt'); // ->middleware('can:view,doctor') // يمكنك إضافة policy

        Route::put('/doctors/{doctor}/update-schedule', [DoctorController::class, 'updateSchedulee'])
            ->name('doctor.schedule.updatee');


        //############################# rays route ##########################################

        Route::resource('rays', RayController::class);

        //############################# end rays route ######################################


        //############################# Laboratories route ##########################################

        Route::resource('Laboratories', LaboratorieController::class);
        Route::get('show_laboratorie/{id}', [InvoiceController::class, 'showLaboratorie'])->name('show.laboratorie');

        //############################# end Laboratories route ######################################


        //############################# rays route ##########################################

        Route::get('patient_details/{id}', [PatientDetailsController::class, 'indexx'])->name('patient_details');

        //############################# end rays route ######################################

        ############################# Chat route ##########################################
        Route::get('list/patients', ChatWindow::class)->name('list.patients');
        Route::get('chat/patients', Main::class)->name('chat.patients');

        ############################# end Chat route ######################################

        Route::get('prescriptions/approval-requests', [PrescriptionController::class, 'approvalRequests'])->name('prescriptions.approvalRequests');
        Route::post('prescriptions/{prescription}/approve-refill', [PrescriptionController::class, 'approveRefill'])->name('prescriptions.approveRefill');
        Route::post('prescriptions/{prescription}/deny-refill', [PrescriptionController::class, 'denyRefill'])->name('prescriptions.denyRefill');
        Route::get('prescriptions/adherence-dashboard', [PrescriptionController::class, 'adherenceDashboard'])->name('prescriptions.adherenceDashboard');
        Route::resource('invoices', InvoiceController::class);

        Route::get('/404', function () {
            return view('Dashboard.404');
        })->name('404');
    });

    Route::get('/profile', [ProfileController::class, 'showProfile'])->name('doctor.profile.show'); // <--- تغيير اسم الدالة إذا أردت
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('doctor.profile.edit'); // للتعديل
    Route::put('/profile', [ProfileController::class, 'update'])->name('doctor.profile.update'); // لحفظ التعديل


    Route::get('completed_invoices', [InvoiceController::class, 'completedInvoices'])->middleware(['auth:doctor', 'doctor.status'])->name('doctor.completedInvoices');
    //############################# end invoices route ################################################

    //############################# review_invoices route ##########################################
    Route::get('review_invoices', [InvoiceController::class, 'reviewInvoices'])->middleware(['auth:doctor', 'doctor.status'])->name('doctor.reviewInvoices');
    //############################# end invoices route #############################################

    //############################# invoices route ##########################################
    // Route::get('invoices', [InvoiceController::class,'index'])->name('invoices.index');


    Route::resource('prescriptions', PrescriptionController::class);
    Route::get('/patients/search-for-prescription', [App\Http\Controllers\Dashboard\DoctorController::class, 'searchPatientsForPrescription'])->name('doctor.patients.search_for_prescription');        // أو إذا أنشأت كنترولر خاص:
    Route::get('/patient-details/{id}', [PatientDetailsController::class, 'index'])->name('doctor.patient.details');

    Route::get('/my-appointments', [DoctorController::class, 'myAppointments'])->name('doctor.appointments');
    Route::patch('/my-appointments/{appointment}/confirm', [DoctorController::class, 'confirmAppointment'])->name('doctor.appointments.confirm');
    Route::patch('/my-appointments/{appointment}/cancel', [DoctorController::class, 'cancelAppointment'])->name('doctor.appointments.cancel');
    Route::patch('/my-appointments/{appointment}/complete', [DoctorController::class, 'completeAppointment'])->name('doctor.appointments.complete');


    require __DIR__ . '/auth.php';
});
