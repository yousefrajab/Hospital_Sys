<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Every role can sign in with its seeded account and open its dashboard.
 * Runs against hos_db_test (see phpunit.xml), never the real database.
 */
class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    protected function setUp(): void
    {
        parent::setUp();

        // Arabic is the default (unprefixed) locale; without this header the localization
        // middleware redirects to /en, which is not registered when routes boot without a request.
        $this->withHeader('Accept-Language', 'ar');
    }

    public static function roles(): array
    {
        return [
            'admin' => ['admin', 'admin@hospital.com'],
            'doctor' => ['doctor', 'doctor@hospital.com'],
            'patient' => ['patient', 'patient@hospital.com'],
            'ray employee' => ['ray_employee', 'ray@hospital.com'],
            'lab employee' => ['laboratorie_employee', 'lab@hospital.com'],
            'pharmacy employee' => ['pharmacy_employee', 'pharmacy.employee@hospital.com'],
            'pharmacy manager' => ['pharmacy_manager', 'pharmacy.manager@hospital.com'],
        ];
    }

    public function test_home_and_login_pages_render(): void
    {
        $this->followingRedirects()->get('/')->assertOk();
        $this->followingRedirects()->get('/login')->assertOk();
    }

    #[DataProvider('roles')]
    public function test_role_can_log_in_and_open_dashboard(string $guard, string $email): void
    {
        $response = $this->post("/login/{$guard}", ['email' => $email, 'password' => '12345678']);

        $response->assertRedirect();
        $this->assertAuthenticated($guard);
        $this->followingRedirects()->get($response->headers->get('Location'))->assertOk();
    }

    #[DataProvider('roles')]
    public function test_wrong_password_is_rejected(string $guard, string $email): void
    {
        $this->from('/login')->post("/login/{$guard}", ['email' => $email, 'password' => 'wrong-password']);

        $this->assertGuest($guard);
    }

    #[DataProvider('roles')]
    public function test_remember_me_works(string $guard, string $email): void
    {
        $response = $this->post("/login/{$guard}", ['email' => $email, 'password' => '12345678', 'remember' => '1']);

        $response->assertRedirect();
        $this->assertAuthenticated($guard);
        $this->assertNotNull(auth($guard)->user()->fresh()->remember_token);
    }
}
