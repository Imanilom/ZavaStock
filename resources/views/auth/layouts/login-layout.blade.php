<!-- resources/views/auth/layouts/login-layout.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>{{ $title ?? 'ZavaStock' }}</title>

    <!-- Custom fonts for this template -->
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href=" https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href=" https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css " rel="stylesheet">

    <style>
        .bg-web {
            background-color: #FAFAFA;
        }
    </style>
</head>
<body class="bg-web">

    @yield('content')

    <!-- Bootstrap core JavaScript -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript -->
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages -->
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

    <!-- Footer -->
    <center>
        <blockquote class="blockquote text-center" style="margin-top: 600px;">
            <footer class="blockquote-footer" style="color: #000; font-family: 'Alata', sans-serif; text-align: center; font-weight: bold;">
                © Mode Aktif
            </footer>
        </blockquote>
    </center>
</body>
</html>