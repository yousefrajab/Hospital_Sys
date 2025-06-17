<?php


use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\Dashboard_Doctor\DiagnosticController;
use App\Http\Controllers\Dashboard_Doctor\LaboratorieController;
// use App\Http\Controllers\Dashboard\Patients\ProfileRayController;
use App\Http\Controllers\Dashboard_Ray_Employee\InvoiceController;
use App\Http\Controllers\Dashboard_Doctor\PatientDetailsController;
use App\Http\Controllers\Dashboard\RayEmployee\ProfileRayController;
use App\Http\Controllers\Dashboard\PharmacyManager\PharmacyStockController;
use App\Http\Controllers\Dashboard\PharmacyEmployee\ProfilePharmacyController;
use App\Http\Controllers\Dashboard\PharmacyEmployee\PharmacyEmployeePrescriptionController;

/*
|--------------------------------------------------------------------------
| pharmacy_employee Routes
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


        //################################ dashboard pharmacy_employee ########################################

        Route::get('/dashboard/pharmacy_employee', function () {
            return view('Dashboard.dashboard_PharmacyEmployee.dashboard');
        })->middleware(['auth:pharmacy_employee'])->name('dashboard.pharmacy_employee');
        //################################ end dashboard ray_employee #####################################

        Route::middleware(['auth:pharmacy_employee'])->prefix('pharmacy_employee')->name('pharmacy_employee.')->group(function () {


            Route::get('/profile', [ProfilePharmacyController::class, 'show'])->name('profile.show'); // <--- تغيير اسم الدالة إذا أردت
            Route::get('/profile/edit', [ProfilePharmacyController::class, 'edit'])->name('profile.edit'); // للتعديل
            Route::put('/profile', [ProfilePharmacyController::class, 'update'])->name('profile.update'); // لحفظ التعديل




            Route::get('/prescriptions', [PharmacyEmployeePrescriptionController::class, 'index'])->name('prescriptions.index');
            Route::get('/prescriptions/{prescription}/dispense', [PharmacyEmployeePrescriptionController::class, 'showDispenseForm'])->name('prescriptions.dispense.form');
            Route::post('/prescriptions/{prescription}/dispense', [PharmacyEmployeePrescriptionController::class, 'processDispense'])->name('prescriptions.dispense.process');
            Route::get('/prescriptions/dispensed', [PharmacyEmployeePrescriptionController::class, 'dispensedPrescriptions'])->name('prescriptions.dispensed'); // *** جديد: الوصفات المصروفة ***
            Route::get('/prescriptions/on-hold', [PharmacyEmployeePrescriptionController::class, 'onHoldPrescriptions'])->name('prescriptions.on_hold');       // *** جديد: وصفات قيد الانتظار ***
            //     // مسارات البحث عن الأدوية (يمكن أن تشير إلى MedicationController مع صلاحيات محدودة)
            Route::get('/medications/search', [App\Http\Controllers\Dashboard\MedicationController::class, 'searchIndexForPharmacy'])->name('medications.search');
            Route::get('/medications/{medication}/stocks', [PharmacyStockController::class, 'index'])->name('medications.stocks.index');

        });




        //---------------------------------------------------------------------------------------------------------------


        require __DIR__ . '/auth.php';
    }
);
