<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\MedicationController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\Dashboard_Doctor\DiagnosticController;
// use App\Http\Controllers\Dashboard\Patients\ProfileRayController;
use App\Http\Controllers\Dashboard_Doctor\LaboratorieController;
use App\Http\Controllers\Dashboard_Ray_Employee\InvoiceController;
use App\Http\Controllers\Dashboard_Doctor\PatientDetailsController;
use App\Http\Controllers\Dashboard\RayEmployee\ProfileRayController;
use App\Http\Controllers\Dashboard\PharmacyManager\PharmacyStockController;
use App\Http\Controllers\Dashboard\PharmacyManager\ProfilePharmacyManagerController;

/*
|--------------------------------------------------------------------------
| PharmacyManager Routes
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


        //################################ dashboard PharmacyManager ########################################

        Route::get('/dashboard/pharmacy_manager', function () {
            return view('Dashboard.dashboard_PharmacyManager.dashboard');
        })->middleware(['auth:pharmacy_manager'])->name('dashboard.pharmacy_manager');
        //################################ end dashboard ray_employee #####################################

        Route::middleware(['auth:pharmacy_manager'])->prefix('PharmacyManager')->name('pharmacy_manager.')->group(function () {
            // //############################# end invoices route ######################################

            Route::get('/profile', [ProfilePharmacyManagerController::class, 'show'])->name('profile.show'); // <--- تغيير اسم الدالة إذا أردت
            Route::get('/profile/edit', [ProfilePharmacyManagerController::class, 'edit'])->name('profile.edit'); // للتعديل
            Route::put('/profile', [ProfilePharmacyManagerController::class, 'update'])->name('profile.update'); // لحفظ التعديل


            Route::resource('medications', MedicationController::class)->names('medications');


            Route::get('/medications/{medication}/stocks', [PharmacyStockController::class, 'index'])->name('medications.stocks.index');
            Route::get('/medications/{medication}/stocks/create', [PharmacyStockController::class, 'create'])->name('medications.stocks.create');
            Route::post('/medications/{medication}/stocks', [PharmacyStockController::class, 'store'])->name('medications.stocks.store');

            // لـ show, edit, update, destroy (تحتاج لمعرف الدفعة فقط، معرف الدواء موجود في الدفعة نفسها)
            // يمكن استخدام Route Model Binding لـ PharmacyStock مباشرة هنا
            Route::get('/stocks/{stock}', [PharmacyStockController::class, 'show'])->name('stocks.show'); // اسم route بسيط
            Route::get('/stocks/{stock}/edit', [PharmacyStockController::class, 'edit'])->name('stocks.edit');
            Route::put('/stocks/{stock}', [PharmacyStockController::class, 'update'])->name('stocks.update'); // أو PATCH
            Route::delete('/stocks/{stock}', [PharmacyStockController::class, 'destroy'])->name('stocks.destroy');
        });



        //---------------------------------------------------------------------------------------------------------------


        require __DIR__ . '/auth.php';
    }
);
