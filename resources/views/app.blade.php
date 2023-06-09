<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ 'Farmacia Santa Isabel' }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @routes
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        @inertiaHead
        <script type="text/javascript">
            window.Laravel = {
                csrfToken: "{{ csrf_token() }}",
                jsPermissions: {!! auth()->check()?auth()->user()->jsPermissions():0 !!}
            }
        </script>
    </head>
    <body class="font-sans antialiased">
        @inertia
        <script src="{{ mix('js/app.js') }}" defer></script>
    </body>
</html>
