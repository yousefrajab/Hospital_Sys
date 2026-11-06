<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Opens every parameter-less GET page as each role and fails on any server error.
 * Catches broken views, missing translations keys that throw, bad route names, etc.
 */
class PageSmokeTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    /** GET routes that change state or stream files; never crawl them. */
    private const SKIP = ['logout', 'mark', 'read-all', 'readall', 'export', 'download', 'print', 'livewire', 'sanctum', 'ignition', '_debugbar', 'storage', 'up'];

    protected function setUp(): void
    {
        parent::setUp();
        $this->withHeader('Accept-Language', 'ar');
    }

    public static function roles(): array
    {
        return [
            'guest' => [null, null],
            'admin' => ['admin', 'admin@hospital.com'],
            'doctor' => ['doctor', 'doctor@hospital.com'],
            'patient' => ['patient', 'patient@hospital.com'],
            'ray employee' => ['ray_employee', 'ray@hospital.com'],
            'lab employee' => ['laboratorie_employee', 'lab@hospital.com'],
            'pharmacy employee' => ['pharmacy_employee', 'pharmacy.employee@hospital.com'],
            'pharmacy manager' => ['pharmacy_manager', 'pharmacy.manager@hospital.com'],
        ];
    }

    #[DataProvider('roles')]
    public function test_no_page_returns_a_server_error(?string $guard, ?string $email): void
    {
        if ($guard) {
            $this->post("/login/{$guard}", ['email' => $email, 'password' => '12345678']);
            $this->assertAuthenticated($guard);
        }

        $failures = [];
        $visited = 0;
        foreach (Route::getRoutes() as $route) {
            $uri = $route->uri();
            if (! in_array('GET', $route->methods(), true) || str_contains($uri, '{')) {
                continue;
            }
            $name = (string) $route->getName();
            if (Str::contains(strtolower($uri . ' ' . $name), self::SKIP)) {
                continue;
            }

            $status = $this->get('/' . ltrim($uri, '/'))->status();
            $visited++;
            if ($status >= 500) {
                $failures[] = "{$status} /{$uri} ({$name})";
            }
        }

        $this->assertGreaterThan(20, $visited);
        $this->assertSame([], $failures, 'Pages with server errors for ' . ($guard ?? 'guest'));
    }
}
