<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>{{ $title ?? 'TurisKuy' }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- CSS -->
  <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">
</head>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">
      <a href="/" class="logo d-flex align-items-center">
        <img src="{{ asset('assets/img/logo.png') }}" alt="TurisKuy" class="logo-img">
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="#hero" class="active">Home</a></li>
          <li><a href="#about">About</a></li>
          @guest
            <li><a href="{{ route('login') }}" class="btn-login">Login</a></li>
            <li><a href="{{ route('register') }}" class="btn-register">Register</a></li>
          @endguest
        </ul>
      </nav>
    </div>
  </header>

  <main class="main pt-5">
    {{ $slot }}
  </main>

  <footer id="footer" class="footer dark-background">
    <div class="container text-center mt-4">
      <p>© <strong class="sitename">TurisKuy</strong> Todos los derechos reservados</p>
    </div>
  </footer>

  <!-- JS -->
  <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/js/main.js') }}"></script>
</body>
</html>
