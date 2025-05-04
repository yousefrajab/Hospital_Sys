<?php

namespace App\Providers;

use App\Models\Doctor;
use App\Models\Appointment;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // *** تعريف الـ Gate هنا ***
        Gate::define('manage-appointment', function (Doctor $doctor, Appointment $appointment) {
            // يتحقق إذا كان معرّف الطبيب الحالي يطابق معرّف الطبيب في الموعد
            return $doctor->id === $appointment->doctor_id;
        });
    }
}
