<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atelier Aruna - Login</title>

    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app-modern.css') }}">
</head>

<body class="hold-transition login-page bg-light">

    <div class="login-box">
        <div class="login-logo">
            <a href="{{ route('home') }}"><b>Atelier</b>Aruna</a>
        </div>

        <div class="card card-outline card-primary shadow-lg">
            <div class="card-body login-card-body rounded">
                <p class="login-box-msg font-weight-bold text-secondary">Masuk atau buat akun untuk melanjutkan belanja</p>

                @yield('content')

            </div>
        </div>
    </div>

    <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>
