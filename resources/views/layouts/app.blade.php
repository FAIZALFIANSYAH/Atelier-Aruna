<!DOCTYPE html>
<html>

<head>
    <title>Atelier Aruna</title>

    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app-modern.css') }}">
    <link rel="stylesheet" href="{{ asset('css/management-pages.css') }}">
    <link rel="stylesheet" href="{{ asset('css/catalog.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar-tree.css') }}">
    @yield('styles')
</head>

<body class="hold-transition sidebar-mini">

    <div class="wrapper">

        @include('partials.navbar')

        @include('partials.sidebar')

        <div class="content-wrapper">

            @yield('content')

        </div>

    </div>

    <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('adminlte/dist/js/adminlte.min.js') }}"></script>
    @stack('scripts')

</body>

</html>
