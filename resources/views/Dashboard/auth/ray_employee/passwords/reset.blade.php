{{-- Shared design: Dashboard/auth/passwords/reset.blade.php --}}
@include('Dashboard.auth.passwords.reset', ['role' => 'ray_employee', 'token' => $token, 'email' => $email ?? null])
