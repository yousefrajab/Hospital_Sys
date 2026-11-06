<!DOCTYPE html>
<html lang="{{ App::getLocale() }}" dir="{{ App::getLocale() === 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@hasSection('title')@yield('title') · @endif{{ trans('ui.app_name') }}</title>
    <link rel="icon" href="{{ asset('Dashboard/img/brand/favicon.png') }}" type="image/png">
    <script>
        try {
            var t = localStorage.getItem('hms-theme');
            if (t === 'dark' || (!t && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.setAttribute('data-theme', 'dark');
            }
        } catch (e) {}
    </script>
    <link href="{{ asset(App::getLocale() === 'ar' ? 'vendor/hms/bootstrap/bootstrap-rtl.min.css' : 'vendor/hms/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('Dashboard/plugins/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('hms/css/hms.css') }}?v={{ @filemtime(public_path('hms/css/hms.css')) }}" rel="stylesheet">
    @yield('css')
</head>

<body class="hms-body">
    @yield('content')
    <script src="{{ asset('hms/js/hms.js') }}?v={{ @filemtime(public_path('hms/js/hms.js')) }}"></script>
    @yield('js')
</body>

</html>
