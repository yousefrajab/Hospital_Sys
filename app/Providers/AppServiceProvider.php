<?php

namespace App\Providers;


use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Http\View\Composers\HeaderDataComposer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('Dashboard.layouts.main-header', HeaderDataComposer::class);

        // The dashboard shell (sidebar, header) needs to know who is signed in.
        View::composer(['Dashboard.layouts.master', 'Dashboard.layouts.main-sidebar', 'Dashboard.layouts.main-header'], function ($view) {
            $view->with('hmsUser', \App\Support\CurrentUser::resolve());
        });
    }
}
