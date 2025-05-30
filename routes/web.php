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

       
        //################################ end dashboard patient #####################################
    }
);



            // Routes الجديدة للأقسام
