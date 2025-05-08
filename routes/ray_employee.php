<?php


use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\Dashboard_Doctor\DiagnosticController;
use App\Http\Controllers\Dashboard_Doctor\LaboratorieController;
// use App\Http\Controllers\Dashboard\Patients\ProfileRayController;
use App\Http\Controllers\Dashboard_Ray_Employee\InvoiceController;
use App\Http\Controllers\Dashboard_Doctor\PatientDetailsController;
use App\Http\Controllers\Dashboard\RayEmployee\ProfileRayController;

/*
|--------------------------------------------------------------------------
| ray_employee Routes
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


        //################################ dashboard ray_employee ########################################

        Route::get('/dashboard/ray_employee', function () {
            return view('Dashboard.dashboard_RayEmployee.dashboard');
        })->middleware(['auth:ray_employee'])->name('dashboard.ray_employee');
        //################################ end dashboard ray_employee #####################################

        Route::middleware(['auth:ray_employee'])->prefix('ray_employee')->name('ray_employee.')->group(function () {


            //############################# invoices route ##########################################
            Route::resource('invoices_ray_employee', InvoiceController::class);
            Route::get('completed_invoices', [InvoiceController::class, 'completed_invoices'])->name('completed_invoices');
            Route::get('view_rays/{id}', [InvoiceController::class, 'viewRays'])->name('view_rays');

            //############################# end invoices route ######################################

            Route::get('/profile', [ProfileRayController::class, 'show'])->name('profile.show'); // <--- تغيير اسم الدالة إذا أردت
            Route::get('/profile/edit', [ProfileRayController::class, 'edit'])->name('profile.edit'); // للتعديل
            Route::put('/profile', [ProfileRayController::class, 'update'])->name('profile.update'); // لحفظ التعديل
        });



        //---------------------------------------------------------------------------------------------------------------


        require __DIR__ . '/auth.php';
    }
);
