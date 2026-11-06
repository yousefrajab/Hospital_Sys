<?php

namespace Tests\Feature;

use Database\Seeders\DemoDataSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Every role's home page renders with realistic data (DemoDataSeeder): KPIs, charts and lists.
 */
class DashboardsTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withHeader('Accept-Language', 'ar');
        $this->seed(DemoDataSeeder::class);
    }

    public static function roles(): array
    {
        return [
            'admin' => ['admin', 'admin@hospital.com', '/dashboard/admin', 'data-hms-chart'],
            'doctor' => ['doctor', 'doctor@hospital.com', '/dashboard/doctor', 'hms-stat'],
            'patient' => ['patient', 'demo.patient1@hospital.test', '/patient/dashboard/patient', 'hms-next'],
            'ray employee' => ['ray_employee', 'ray@hospital.com', '/dashboard/ray_employee', 'data-hms-chart'],
            'lab employee' => ['laboratorie_employee', 'lab@hospital.com', '/dashboard/laboratorie_employee', 'data-hms-chart'],
            'pharmacy employee' => ['pharmacy_employee', 'pharmacy.employee@hospital.com', '/dashboard/pharmacy_employee', 'data-hms-chart'],
            'pharmacy manager' => ['pharmacy_manager', 'pharmacy.manager@hospital.com', '/dashboard/pharmacy_manager', 'data-hms-chart'],
        ];
    }

    #[DataProvider('roles')]
    public function test_dashboard_renders_with_data(string $guard, string $email, string $url, string $marker): void
    {
        $this->post("/login/{$guard}", ['email' => $email, 'password' => '12345678']);
        $this->assertAuthenticated($guard);

        $this->get($url)->assertOk()->assertSee($marker, false);
    }
}
