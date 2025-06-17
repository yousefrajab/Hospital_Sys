<?php

use App\Models\Section;
use App\Http\Livewire\Chat\Main;
use Illuminate\Support\Facades\Route;


use App\Http\Livewire\Chat\Createchat;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\Patient\PatientPharmacyController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\Dashboard_Patient\PatientController;
use App\Http\Controllers\Dashboard\Patient\ProfilePatController;
use App\Http\Controllers\Dashboard_Ray_Employee\InvoiceController;
use App\Http\Controllers\Dashboard\PatientSideAppointmentController;

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
    ],
    function () {


        //################################ dashboard patient ########################################


        //################################ end dashboard patient #####################################
        Route::get('/home', [WebsiteController::class, 'home'])->middleware(['auth:patient'])->name('home');


        Route::middleware(['auth:patient'])->prefix('patient')->group(function () {


            Route::get('/departments', [WebsiteController::class, 'showAllDepartments'])->name('website.departments.all');
            Route::get('/department/{id}', [WebsiteController::class, 'showDepartmentDetails'])->name('website.department.details');
            // يمكنك استخدام {slug} بدلاً من {id} إذا أردت روابط صديقة لمحركات البحث،
            // لكن هذا يتطلب إضافة حقل slug لموديل Section وتعديل الكنترولر للبحث بالـ slug.
            Route::get('/services', [WebsiteController::class, 'showAllServices'])->name('website.services.all');
            Route::get('/group-services', [WebsiteController::class, 'showAllGroupServices'])->name('website.group_services.all');

            // Routes الجديدة للأطباء
            Route::get('/doctors', [WebsiteController::class, 'showAllDoctors'])->name('website.doctors.all');
            Route::get('/doctor/{id}', [WebsiteController::class, 'showDoctorDetails'])->name('website.doctor.details');
            Route::get('/dashboard/patient', [PatientController::class, 'dashboard'])->middleware(['auth:patient'])->name('dashboard.patient');

            Route::get('/my-appointments', [WebsiteController::class, 'myAppointments'])->name('website.my.appointments')->middleware('auth:patient'); // مثال لحماية المسار

            // مسار لإلغاء الموعد (يفضل استخدام PATCH أو DELETE، ولكن POST أسهل مع الفورم)
            Route::post('/my-appointments/{appointment}/cancel', [WebsiteController::class, 'cancelAppointmentFromWebsite'])->name('website.appointment.cancel')->middleware('auth:patient');
            Route::get('/my-invoices', [WebsiteController::class, 'myInvoices'])->name('website.my.invoices')->middleware('auth:patient');

            Route::get('/my-invoices/{invoice}/print', [WebsiteController::class, 'printInvoice'])
                ->name('website.invoice.print')
                ->middleware('auth:patient');

            Route::get('/my-account', [WebsiteController::class, 'myAccountStatement'])->name('website.my.account')->middleware('auth:patient');
            Route::get('/my-receipts/{receiptAccount}/print', [WebsiteController::class, 'printReceipt'])->name('website.receipt.print')->middleware('auth:patient');


            Route::get('/profile', [ProfilePatController::class, 'show'])->name('profile.show');
            Route::get('/profile/edit', [ProfilePatController::class, 'edit'])->name('profile.edit');
            Route::put('/profile', [ProfilePatController::class, 'update'])->name('profile.update');

            //############################# patients route ##########################################
            Route::get('invoices', [PatientController::class, 'invoices'])->name('invoices.patient');
            Route::get('laboratories', [PatientController::class, 'laboratories'])->name('laboratories.patient');
            Route::get('view_laboratoriess/{id}', [PatientController::class, 'viewLaboratories'])->name('laboratories.view');
            Route::get('rays', [PatientController::class, 'rays'])->name('rays.patient');
            Route::get('view_rayss/{id}', [PatientController::class, 'viewRays'])->name('rays.view');
            Route::get('payments', [PatientController::class, 'payments'])->name('payments.patient');
            //############################# end patients route ######################################


            ############################# Chat route ##########################################
            Route::get('list/doctors', Createchat::class)->name('list.doctors');
            Route::get('chat/doctors', Main::class)->name('chat.doctors');

            ############################# end Chat route ######################################



            Route::get('/appointments/upcoming', [PatientController::class, 'upcomingAppointments'])->name('appointments.upcoming');
            Route::get('/appointments/past', [PatientController::class, 'pastAppointments'])->name('appointments.past');

            Route::patch('/appointments/{appointment}/cancel-by-patient', [PatientController::class, 'cancelAppointmentByPatient'])->name('appointments.cancelByPatient');

            Route::view('dashboard', 'livewire.dashboard.patient-appointment-form')->name('patient-appointment-form');
        });


        // Route لعرض فورم حجز الموعد
        Route::get('/patient/appointments/create-form', [PatientSideAppointmentController::class, 'create'])
            ->name('patient.appointments.create.form') // اسم مختلف عن route الـ store
            ->middleware(['auth:patient']); // مثال لحماية الـ route

        // Route لحفظ الموعد
        Route::post('/patient/appointments', [PatientSideAppointmentController::class, 'store'])
            ->name('patient.appointments.store')
            ->middleware(['auth:patient']);


        // ***** Routes جديدة لـ AJAX *****
        Route::get('/ajax/doctors-by-section', [PatientSideAppointmentController::class, 'getDoctorsBySection'])
            ->name('ajax.get_doctors_by_section')
            ->middleware(['auth:patient']); // أو أي middleware مناسب

        Route::get('/ajax/available-times', [PatientSideAppointmentController::class, 'getAvailableTimes'])
            ->name('ajax.get_available_times')
            ->middleware(['auth:patient']); // أو أي middleware مناسب

        // Route لصفحة نجاح الحجز (اختياري)
        Route::get('/patient/appointment/success', function () {
            // يمكنك عرض رسالة نجاح بسيطة هنا أو توجيه لصفحة أخرى
            if (session('success_message')) {
                return view('dashboard_pages.appointment_success', ['message' => session('success_message')]);
            }
            return redirect()->route('dashboard.patient'); // صفحة المريض الرئيسية
        })->name('patient.appointment.success')->middleware('auth:patient');
        Route::get('/ajax/get-doctor-available-dates', [PatientSideAppointmentController::class, 'getDoctorAvailableDates'])->name('ajax.get_doctor_available_dates');



        Route::get('patient/pharmacy/', [PatientPharmacyController::class, 'index'])->name('patient.pharmacy.index'); // هذا هو الرابط المطلوب
        Route::get('/{prescription}', [PatientPharmacyController::class, 'show'])->name('patient.pharmacy.show'); // لاحقًا لتفاصيل الوصفة
        Route::post('/{prescription}/request-refill', [PatientPharmacyController::class, 'requestRefill'])->name('patient.pharmacy.request-refill');
        Route::get('/refill-requests/pending', [PatientPharmacyController::class, 'pendingRefillRequests'])
            ->name('patient.pharmacy.refill-requests.pending');

        Route::get('/testimonials/create', [App\Http\Controllers\Dashboard_Patient\PatientController::class, 'createTestimonial'])->name('patient.testimonials.create');
        Route::post('/testimonials', [App\Http\Controllers\Dashboard_Patient\PatientController::class, 'storeTestimonial'])->name('patient.testimonials.store');



        require __DIR__ . '/auth.php';
    }
);
