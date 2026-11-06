<!DOCTYPE html>
@php($hmsRtl = App::getLocale() === 'ar')
<html lang="{{ App::getLocale() }}" dir="{{ $hmsRtl ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ trans('ui.app_name') }}">
    {{-- Apply the saved theme before first paint, so dark mode never flashes white. --}}
    <script>
        try {
            var t = localStorage.getItem('hms-theme');
            if (t === 'dark' || (!t && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.setAttribute('data-theme', 'dark');
            }
            if (localStorage.getItem('hms-sidebar') === 'collapsed') {
                document.documentElement.classList.add('hms-collapsed');
            }
        } catch (e) {}
    </script>
    @include('Dashboard.layouts.head')
</head>

<body class="hms-body">
    @include('Dashboard.layouts.main-sidebar')
    <div class="hms-backdrop" data-hms-toggle="sidebar"></div>

    <div class="hms-main">
        @include('Dashboard.layouts.main-header')

        <main class="hms-content container-fluid">
            @yield('page-header')
            @yield('content')
        </main>

        @include('Dashboard.layouts.footer')
    </div>

    @include('Dashboard.layouts.footer-scripts')
</body>

</html>
