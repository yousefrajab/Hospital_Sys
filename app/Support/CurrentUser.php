<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/**
 * The signed-in user of the dashboard, whatever guard they use, with everything the layout needs:
 * name, avatar, role label and the role's home / profile / logout routes.
 */
class CurrentUser
{
    /** guard => [image folder under public/Dashboard/img, home route, profile show, profile edit, logout] */
    private const ROLES = [
        'admin' => ['admin_photos', 'dashboard.admin', 'admin.profile.show', 'admin.profile.edit', 'logout.admin'],
        'doctor' => ['doctors', 'dashboard.doctor', 'doctor.profile.show', 'doctor.profile.edit', 'logout.doctor'],
        'patient' => ['patients', 'dashboard.patient', 'profile.show', 'profile.edit', 'logout.patient'],
        'ray_employee' => ['rayEmployees', 'dashboard.ray_employee', 'ray_employee.profile.show', 'ray_employee.profile.edit', 'logout.ray_employee'],
        'laboratorie_employee' => ['laboratorieEmployees', 'dashboard.laboratorie_employee', 'laboratorie_employee.profile.show', 'laboratorie_employee.profile.edit', 'logout.laboratorie_employee'],
        'pharmacy_employee' => ['pharmacyEmployees', 'dashboard.pharmacy_employee', 'pharmacy_employee.profile.show', 'pharmacy_employee.profile.edit', 'logout.pharmacy_employee'],
        'pharmacy_manager' => ['pharmacy_managers', 'dashboard.pharmacy_manager', 'pharmacy_manager.profile.show', 'pharmacy_manager.profile.edit', 'logout.pharmacy_manager'],
    ];

    public function __construct(public readonly ?string $guard, public readonly ?Model $user)
    {
    }

    public static function resolve(): self
    {
        foreach (array_keys(self::ROLES) as $guard) {
            if (Auth::guard($guard)->check()) {
                return new self($guard, Auth::guard($guard)->user());
            }
        }

        return new self(null, null);
    }

    public function check(): bool
    {
        return $this->user !== null;
    }

    public function name(): string
    {
        return (string) ($this->user->name ?? '');
    }

    public function email(): string
    {
        return (string) ($this->user->email ?? '');
    }

    public function initials(): string
    {
        $parts = preg_split('/\s+/u', trim($this->name())) ?: [];
        $letters = array_map(fn ($p) => mb_substr($p, 0, 1), array_slice(array_filter($parts), 0, 2));

        return mb_strtoupper(implode('', $letters)) ?: '?';
    }

    /** Uploaded photo URL, or null to show initials instead. */
    public function avatarUrl(): ?string
    {
        $filename = $this->user?->image?->filename;
        if (! $filename || ! $this->guard) {
            return null;
        }
        $relative = 'Dashboard/img/' . self::ROLES[$this->guard][0] . '/' . $filename;

        return is_file(public_path($relative)) ? asset($relative) : null;
    }

    public function roleLabel(): string
    {
        return $this->guard ? trans('ui.roles.' . $this->guard) : '';
    }

    public function homeUrl(): string
    {
        return $this->route(1) ?? url('/');
    }

    public function profileUrl(): ?string
    {
        return $this->route(2);
    }

    public function profileEditUrl(): ?string
    {
        return $this->route(3);
    }

    public function logoutUrl(): ?string
    {
        return $this->route(4);
    }

    private function route(int $index): ?string
    {
        $name = $this->guard ? self::ROLES[$this->guard][$index] : null;

        return $name && Route::has($name) ? route($name) : null;
    }
}
