<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\RayEmployee;
use App\Observers\AdminObserver;
use App\Observers\DoctorObserver;
use App\Observers\PatientObserver;
use App\Models\LaboratorieEmployee;
use Illuminate\Support\Facades\Event;
use App\Observers\RayEmployeeObserver;
use Illuminate\Auth\Events\Registered;
use App\Observers\LaboratorieEmployeeObserver;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Admin::observe(AdminObserver::class);
        Doctor::observe(DoctorObserver::class);
        Patient::observe(PatientObserver::class);
        LaboratorieEmployee::observe(LaboratorieEmployeeObserver::class);
        RayEmployee::observe(RayEmployeeObserver::class);

    }
}
