{{-- Shared design: Dashboard/auth/passwords/reset.blade.php --}}
@include('Dashboard.auth.passwords.reset', ['role' => 'admin', 'token' => $token, 'email' => $email ?? null])
