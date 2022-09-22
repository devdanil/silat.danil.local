<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="/img/icon.jpg" type="image/jpg">
    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix('css/sipelatihan.min.css') }}">

    <!-- Scripts -->
    @routes
    <script src="{{ mix('js/sipelatihan.min.js') }}" defer></script>
    @inertiaHead
</head>

<body class="font-sans antialiased">
    @inertia
</body>

</html>