<?php

use App\Models\Doctor;
use App\Models\Section;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebsiteController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

/*
|--------------------------------------------------------------------------
| Web Routes
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
        'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath', 'auth']
    ],
    function () {
       
        //################################ dashboard patient ########################################

        Route::get('/home', [WebsiteController::class, 'home'])->name('home');

        Route::get('/departments', [WebsiteController::class, 'showAllDepartments'])->name('website.departments.all');
        Route::get('/department/{id}', [WebsiteController::class, 'showDepartmentDetails'])->name('website.department.details');
        // يمكنك استخدام {slug} بدلاً من {id} إذا أردت روابط صديقة لمحركات البحث،
        // لكن هذا يتطلب إضافة حقل slug لموديل Section وتعديل الكنترولر للبحث بالـ slug.
        Route::get('/services', [WebsiteController::class, 'showAllServices'])->name('website.services.all');

        // Routes الجديدة للأطباء
        Route::get('/doctors', [WebsiteController::class, 'showAllDoctors'])->name('website.doctors.all');
        Route::get('/doctor/{id}', [WebsiteController::class, 'showDoctorDetails'])->name('website.doctor.details');
        //################################ end dashboard patient #####################################
    }
);



            // Routes الجديدة للأقسام
