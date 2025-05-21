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

    Route::get('/dashboard/doctor', function () {
        return view('Dashboard.doctor.dashboard');
    })->middleware(['auth:doctor'])->name('dashboard.doctor');

    //################################ end dashboard doctor #####################################

    //---------------------------------------------------------------------------------------------------------------

    Route::middleware(['auth:doctor', 'doctor.status'])->prefix('doctor')->name('doctor.')->group(function () {



        //############################# completed_invoices route ##########################################
        Route::get('completed_invoices', [InvoiceController::class, 'completedInvoices'])->name('completedInvoices');
        //############################# end invoices route ################################################

        //############################# review_invoices route ##########################################
        Route::get('review_invoices', [InvoiceController::class, 'reviewInvoices'])->name('reviewInvoices');
        //############################# end invoices route #############################################

        //############################# invoices route ##########################################
        Route::resource('invoices', InvoiceController::class);
        // Route::get('invoices', [InvoiceController::class,'index'])->name('invoices.index');

        //############################# end invoices route ######################################


        //############################# review_invoices route ##########################################
        Route::post('add_review', [DiagnosticController::class, 'addReview'])->name('add_review');
        //############################# end invoices route #############################################


        //############################# Diagnostics route ##########################################

        Route::resource('Diagnostics', DiagnosticController::class);

        //############################# end Diagnostics route ######################################
        // Route::resource('Doctors', DoctorController::class);

        Route::get('/profile', [ProfileController::class, 'showProfile'])->name('profile.show'); // <--- تغيير اسم الدالة إذا أردت
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit'); // للتعديل
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update'); // لحفظ التعديل
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

        Route::get('/my-appointments', [DoctorController::class, 'myAppointments'])->name('appointments');
        Route::patch('/my-appointments/{appointment}/confirm', [DoctorController::class, 'confirmAppointment'])->name('appointments.confirm');
        Route::patch('/my-appointments/{appointment}/cancel', [DoctorController::class, 'cancelAppointment'])->name('appointments.cancel');
        Route::patch('/my-appointments/{appointment}/complete', [DoctorController::class, 'completeAppointment'])->name('appointments.complete');

        //############################# rays route ##########################################

        Route::resource('rays', RayController::class);

        //############################# end rays route ######################################


        //############################# Laboratories route ##########################################

        Route::resource('Laboratories', LaboratorieController::class);
        Route::get('show_laboratorie/{id}', [InvoiceController::class, 'showLaboratorie'])->name('show.laboratorie');

        //############################# end Laboratories route ######################################


        //############################# rays route ##########################################

        Route::get('patient_details/{id}', [PatientDetailsController::class, 'index'])->name('patient_details');

        //############################# end rays route ######################################

        ############################# Chat route ##########################################
        Route::get('list/patients', ChatWindow::class)->name('list.patients');
        Route::get('chat/patients', Main::class)->name('chat.patients');

        ############################# end Chat route ######################################

        Route::resource('prescriptions', PrescriptionController::class);

        Route::get('/patients/search-for-prescription', [App\Http\Controllers\Dashboard\DoctorController::class, 'searchPatientsForPrescription'])->name('patients.search_for_prescription');        // أو إذا أنشأت كنترولر خاص:
        Route::get('/patient-details/{id}', [PatientDetailsController::class, 'index'])->name('patient.details');

        Route::get('/404', function () {
            return view('Dashboard.404');
        })->name('404');
    });
    Route::get('prescriptions/approval-requests', [PrescriptionController::class, 'approvalRequests'])->name('doctor.prescriptions.approvalRequests');
    Route::post('prescriptions/{prescription}/approve-refill', [PrescriptionController::class, 'approveRefill'])->name('doctor.prescriptions.approveRefill');
    Route::post('prescriptions/{prescription}/deny-refill', [PrescriptionController::class, 'denyRefill'])->name('doctor.prescriptions.denyRefill');
    Route::get('prescriptions/adherence-dashboard', [PrescriptionController::class, 'adherenceDashboard'])->name('doctor.prescriptions.adherenceDashboard');

    require __DIR__ . '/auth.php';
});
