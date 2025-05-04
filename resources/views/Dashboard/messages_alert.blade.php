@if (count($errors) > 0)
    <script>
        window.onload = function() {
            notif({
                msg: "@foreach ($errors->all() as $error) {{ $error }} @endforeach",
                type: "error",
                position: "right"
            });
        }
    </script>
@endif

@if (session()->has('edit'))
    <script>
        window.onload = function() {
            notif({
                msg: "{{ trans('Dashboard/messages.edit') }}",
                type: "info",
                position: "right"
            });
        }
    </script>
@endif
@if (session()->has('delete'))
    <script>
        window.onload = function() {
            notif({
                msg: "{{ trans('Dashboard/messages.delete') }}",
                type: "error",
                position: "right"
            });
        }
    </script>
@endif

@if (session()->has('add'))
    <script>
        window.onload = function() {
            notif({
                msg: "{{ trans('Dashboard/messages.add') }}",
                type: "success",
                position: "right"
            });
        }
    </script>
@endif



@if (session()->has('update_password'))
    <script>
        window.onload = function() {
            notif({
                msg: "{{ trans('Dashboard/messages.update_password') }}",
                type: "warning",
                position: "right"
            });
        }
    </script>
@endif

@if (session()->has('update_status'))
    <script>
        window.onload = function() {
            notif({
                msg: "{{ trans('Dashboard/messages.update_status') }}",
                type: "warning",
                position: "right"
            });
        }
    </script>
@endif
