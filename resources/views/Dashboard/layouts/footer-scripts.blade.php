{{-- Bootstrap 4.6 behaviour (dropdowns, modals, tabs, tooltips). jQuery is loaded in the head. --}}
<script src="{{ asset('vendor/hms/bootstrap/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('Dashboard/plugins/moment/moment.js') }}"></script>

<script src="{{ asset('Dashboard/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('Dashboard/plugins/datatable/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{ asset('Dashboard/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('Dashboard/plugins/datatable/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('Dashboard/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('Dashboard/plugins/datatable/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('Dashboard/plugins/datatable/js/jszip.min.js') }}"></script>
<script src="{{ asset('Dashboard/plugins/datatable/js/pdfmake.min.js') }}"></script>
<script src="{{ asset('Dashboard/plugins/datatable/js/vfs_fonts.js') }}"></script>
<script src="{{ asset('Dashboard/plugins/datatable/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('Dashboard/plugins/datatable/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('Dashboard/plugins/datatable/js/buttons.colVis.min.js') }}"></script>

{{-- Laravel Echo / Pusher for live chat and notifications. --}}
<script src="{{ asset('js/app.js') }}"></script>

{{-- Sidebar, theme and menu behaviour. --}}
<script src="{{ asset('hms/js/hms.js') }}?v={{ @filemtime(public_path('hms/js/hms.js')) }}"></script>

@yield('js')
@stack('hms-scripts')
@livewireScripts
