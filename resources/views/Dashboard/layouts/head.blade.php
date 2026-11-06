<title>@hasSection('title')@yield('title') · @endif{{ trans('ui.app_name') }}</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="icon" href="{{ asset('Dashboard/img/brand/favicon.png') }}" type="image/png">

{{-- Bootstrap 4.6 (MIT) and its RTL build: grid and components that the pages are written with. --}}
@if (App::getLocale() === 'ar')
    <link href="{{ asset('vendor/hms/bootstrap/bootstrap-rtl.min.css') }}" rel="stylesheet">
@else
    <link href="{{ asset('vendor/hms/bootstrap/bootstrap.min.css') }}" rel="stylesheet">
@endif

{{-- Open-source icon fonts used across the pages. --}}
<link href="{{ asset('Dashboard/plugins/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
<link href="{{ asset('Dashboard/plugins/line-awesome/css/line-awesome.min.css') }}" rel="stylesheet">
<link href="{{ asset('Dashboard/plugins/feather/feather.css') }}" rel="stylesheet">
<link href="{{ asset('Dashboard/plugins/typicons.font/typicons.css') }}" rel="stylesheet">
<link href="{{ asset('Dashboard/plugins/themify/themify.css') }}" rel="stylesheet">
<link href="{{ asset('Dashboard/plugins/materialdesignicons/materialdesignicons.css') }}" rel="stylesheet">
<link href="{{ asset('Dashboard/plugins/boxicons/css/boxicons.min.css') }}" rel="stylesheet">

{{-- Data tables and the date picker. --}}
<link href="{{ asset('Dashboard/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ asset('Dashboard/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ asset('Dashboard/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css">

{{-- jQuery first: many pages run inline scripts that use it. --}}
<script src="{{ asset('Dashboard/plugins/jquery/jquery.min.js') }}"></script>

{{-- Our design system, then each page's own styles (which may refine it). --}}
<link href="{{ asset('hms/css/hms.css') }}?v={{ @filemtime(public_path('hms/css/hms.css')) }}" rel="stylesheet">
@livewireStyles
@yield('css')
