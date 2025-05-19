<?php


use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\Dashboard_Doctor\DiagnosticController;
use App\Http\Controllers\Dashboard_Doctor\LaboratorieController;
// use App\Http\Controllers\Dashboard\Patients\ProfileRayController;
use App\Http\Controllers\Dashboard_Ray_Employee\InvoiceController;
use App\Http\Controllers\Dashboard_Doctor\PatientDetailsController;
use App\Http\Controllers\Dashboard\RayEmployee\ProfileRayController;
use App\Http\Controllers\Dashboard\PharmacyEmployee\ProfilePharmacyController;

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
        });



        //---------------------------------------------------------------------------------------------------------------


        require __DIR__ . '/auth.php';
    }
);
