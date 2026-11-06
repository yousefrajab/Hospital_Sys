{{-- Shared design: Dashboard/auth/passwords/reset.blade.php --}}
@include('Dashboard.auth.passwords.reset', ['role' => 'laboratorie_employee', 'token' => $token, 'email' => $email ?? null])
