{{-- Shared design: Dashboard/auth/passwords/reset.blade.php --}}
@include('Dashboard.auth.passwords.reset', ['role' => 'patient', 'token' => $token, 'email' => $email ?? null])
