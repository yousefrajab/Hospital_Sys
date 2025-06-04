<?php

use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\Dashboard\LabEmployee\ProfileLabController;
use App\Http\Controllers\Dashboard_Laboratorie_Employee\InvoiceController;

/*
 |--------------------------------------------------------------------------
 | laboratorie_employee Routes
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


        //################################ dashboard doctor ########################################

        Route::get('/dashboard/laboratorie_employee', function () {
            return view('Dashboard.dashboard_LaboratorieEmployee.dashboard');
        })->middleware(['auth:laboratorie_employee'])->name('dashboard.laboratorie_employee');

        Route::get('/dashboard/laboratorie_employee', [ProfileLabController::class, 'dashboard'])->middleware(['auth:laboratorie_employee'])->name('dashboard.laboratorie_employee');

        //################################ end dashboard doctor #####################################

        Route::middleware(['auth:laboratorie_employee'])->prefix('laboratorie_employee')->name('laboratorie_employee.')->group(function () {


            //############################# invoices route ##########################################
            Route::resource('invoices_laboratorie_employee', InvoiceController::class);
            Route::get('completed_invoicess', [InvoiceController::class, 'completed_invoices'])->name('completed_invoicess');
            Route::get('view_laboratories/{id}', [InvoiceController::class, 'view_laboratories'])->name('view_laboratories');
            //############################# end invoices route ######################################

            Route::get('/profile', [ProfileLabController::class, 'showw'])->name('profile.show'); // <--- تغيير اسم الدالة إذا أردت
            Route::get('/profile/edit', [ProfileLabController::class, 'editt'])->name('profile.edit'); // للتعديل
            Route::put('/profile', [ProfileLabController::class, 'updatee'])->name('profile.update'); // لحفظ التعديل

        });

        require __DIR__ . '/auth.php';
    }
);
