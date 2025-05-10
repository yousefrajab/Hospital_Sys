{{-- resources/views/Dashboard/layouts/master-auth.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-f">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">


    <!-- Styles -->
    {{-- أضف أي ملفات CSS أساسية هنا --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    @yield('css') {{-- للسماح للصفحات الفرعية بإضافة CSS خاص بها --}}

    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f4f7f6; /* لون خلفية بسيط */
        }
    </style>
</head>
<body>
    <div id="app">
        <main class="py-4">
            @yield('content') {{-- هنا سيتم عرض محتوى صفحة email.blade.php --}}
        </main>
    </div>

    <!-- Scripts -->
    {{-- أضف أي ملفات JS أساسية هنا --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('js') {{-- للسماح للصفحات الفرعية بإضافة JS خاص بها --}}
</body>
</html>
