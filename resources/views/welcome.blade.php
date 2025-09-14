<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TurisKuy | Turismo Inteligente en Capachica</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="{{ asset('assets/img/favicon.png') }}" rel="icon">
  <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto&family=Poppins&family=Raleway&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

  <!-- Vendor CSS -->
  <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/aos/aos.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

  <!-- Main CSS -->
  <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet">

<meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body class="index-page">

  <!-- Header -->
  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">
      <a href="/" class="logo d-flex align-items-center">
        <img src="{{ asset('assets/img/logo.png') }}" alt="TurisKuy" class="logo-img">
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="#hero" class="active">Home</a></li>
          <li><a href="#ubicacion">Ubicación</a></li>
          @guest
            <li><a href="{{ route('login') }}" class="btn-login">Login</a></li>
            <li><a href="{{ route('register') }}" class="btn-register">Register</a></li>
          @endguest
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>
    </div>
  </header>

  <!-- Main -->
  <main class="main">
    <!-- Hero Section -->
    <section id="hero" class="hero section dark-background">
      <img src="{{ asset('assets/img/hero-bg-2.jpg') }}" alt="Fondo" class="hero-bg">

  <div class="container py-5 text-center text-white animate__animated animate__fadeInUp">
  <h1 class="display-3 fw-bold mb-3" style="font-family: 'Poppins', sans-serif; letter-spacing: 1px; text-shadow: 2px 2px 6px rgba(0,0,0,0.4);">
    🌄 TurisKuy
  </h1>
  <p class="lead mb-0" style="font-size: 1.3rem; font-weight: 500; text-shadow: 1px 1px 3px rgba(0,0,0,0.3);">
    Turismo inteligente y sostenible en <strong>Capachica</strong>
  </p>
</div>


      <svg class="hero-waves" xmlns="http://www.w3.org/2000/svg" viewBox="0 24 150 28" preserveAspectRatio="none">
        <defs>
          <path id="wave-path" d="M-160 44c30 0 58-18 88-18s58 18 88 18 58-18 88-18 58 18 88 18v44h-352z"></path>
        </defs>
        <g class="wave1"><use xlink:href="#wave-path" x="50" y="3"/></g>
        <g class="wave2"><use xlink:href="#wave-path" x="50" y="0"/></g>
        <g class="wave3"><use xlink:href="#wave-path" x="50" y="9"/></g>
      </svg>
    </section>

<!-- Sección de Identificación -->
<div id="form-identidad" class="container py-5">
  <h2 class="text-center mb-4">Identifícate</h2>
  <div class="row justify-content-center">
    <div class="col-md-6">
      <input type="text" id="nombre" class="form-control mb-2" placeholder="Nombre completo" required>
      <input type="tel" id="telefono" class="form-control mb-2" placeholder="Número de celular" required>
      <button onclick="guardarIdentidad()" class="btn btn-success w-100">Guardar y Continuar</button>
    </div>
  </div>
</div>

<!-- Sección de Ubicación -->

<section id="ubicacion" class="container py-5">
<h2 class="text-center fw-bold mb-5" style="
  font-size: 2.6rem;
  color: #112b66;
  font-family: 'Poppins', sans-serif;
  background: linear-gradient(90deg, #0d6efd, #305abd);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  text-shadow: 1px 1px 2px rgba(0,0,0,0.15);
  letter-spacing: 1px;
">
  <i class="bi bi-broadcast-pin me-2 text-danger" style="font-size: 2.2rem; vertical-align: middle;"></i>
  ¡Comparte tu ubicación en tiempo real!
</h2>


<!-- Selector de modo con diseño más moderno y minimalista -->
<div class="d-flex justify-content-center mb-4">
  <div class="btn-group btn-group-toggle w-100" role="group" aria-label="Modo de ubicación">
    <input type="radio" class="btn-check" name="modoUbicacion" id="modoManual" autocomplete="off" checked onchange="cambiarModo('manual')">
    <label class="btn btn-lg text-white w-50 py-3 rounded-pill shadow-sm" for="modoManual" style="background-color: #264da7;">
      <i class="bi bi-geo-alt-fill me-2"></i>Obtener
    </label>

    <input type="radio" class="btn-check" name="modoUbicacion" id="modoRastreo" autocomplete="off" onchange="cambiarModo('rastreo')">
    <label class="btn btn-lg text-white w-50 py-3 rounded-pill shadow-sm" for="modoRastreo" style="background-color: #264da7;">
      <i class="bi bi-broadcast-pin me-2"></i>Rastreo
    </label>
  </div>
</div>

<!-- Estilos simples y modernos -->
<style>
  .btn-check:checked + label {
    background-color: #1c3a74; /* Tonalidad más oscura al seleccionar */
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2); /* Sombra al seleccionar */
  }

  .btn:hover {
    background-color: #1c3a74; /* Tonalidad al hacer hover */
    transition: background-color 0.3s ease;
  }
</style>



<!-- Botón para obtener ubicación -->
<div id="botonManual" class="text-center">
  <button id="btnObtenerUbicacion" onclick="getLocation()"
          class="btn btn-lg shadow px-5 py-3 rounded-pill text-white"
          style="background-color: #264da7; min-width: 250px;">
    <i class="bi bi-geo-alt-fill me-2"></i>Obtener Ubicación
  </button>
</div>

<!-- Botón de Rastreo -->
<div id="botonRastreo" class="text-center d-none animate__animated animate__fadeIn">
  <button onclick="startRealtimeTracking()"
          class="btn btn-lg shadow px-5 py-3 rounded-pill text-white"
          style="background-color: #264da7; min-width: 250px;">
    <i class="bi bi-broadcast-pin me-2"></i>Rastreo en Vivo
  </button>
  <p id="estado" class="mt-3 fw-bold text-success"></p>
</div>

<!-- Resultado -->
<div id="location-result" class="mt-5 mx-auto p-4 bg-light border rounded shadow-sm text-secondary text-center" style="max-width: 600px;">
  <i class="bi bi-info-circle-fill text-warning me-2"></i>
  Ubicación no detectada aún.
</div>




<script>
  let rastreoIntervalId = null;

  function cambiarModo(modo) {
    const botonManual = document.getElementById('botonManual');
    const botonRastreo = document.getElementById('botonRastreo');

    if (modo === 'manual') {
      botonManual.classList.remove('d-none');
      botonRastreo.classList.add('d-none');
    } else if (modo === 'rastreo') {
      botonManual.classList.add('d-none');
      botonRastreo.classList.remove('d-none');
    }
  }

  function startRealtimeTracking() {
    const estado = document.getElementById('estado');
    estado.innerText = 'Rastreo activado...';
    localStorage.setItem('rastreo_activado', 'true');

    if (!navigator.geolocation) return alert("Tu navegador no soporta GPS.");
    if (rastreoIntervalId) return; // evita múltiples intervalos

    rastreoIntervalId = setInterval(() => {
      navigator.geolocation.getCurrentPosition(position => {
        console.log("Enviando en tiempo real:", position.coords.latitude, position.coords.longitude);

        fetch('/ubicacion/guardar', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({
            visitor_id: localStorage.getItem('visitor_id'),
            lat: position.coords.latitude,
            lon: position.coords.longitude
          })
        });
      }, error => {
        console.error("Error al obtener ubicación:", error);
      });
    }, 5000);
  }

  // 🚀 Al cargar la página, si rastreo estaba activado antes, reactívalo
  window.addEventListener('DOMContentLoaded', () => {
    if (localStorage.getItem('rastreo_activado') === 'true') {
      startRealtimeTracking();
    }
  });
</script>



</body>

    <!-- Favicons
  <div class="text-center">
    <button onclick="startRealtimeTracking()" class="px-4 py-2 bg-blue-600 text-white rounded">
    Iniciar Tracking
</button>


    <div id="location-result" class="mt-4 p-4 bg-white border rounded shadow-sm text-secondary">
      <i class="bi bi-info-circle-fill text-warning me-2"></i>Tracking aún no iniciado.
    </div>
</div> -->

</section>
<button onclick="borrarLocalStorage()" style="position:fixed;bottom:20px;right:20px;padding:10px 15px;background-color:#dc3545;color:#fff;border:none;border-radius:5px;cursor:pointer;z-index:9999;">
    🧹 Limpiar Datos
</button>

<script>
function borrarLocalStorage() {
    if (confirm("¿Estás seguro de borrar todos los datos del visitante?")) {
        localStorage.clear();
        alert("Datos eliminados. Recarga para empezar como visitante nuevo.");
        location.reload();
    }
}
</script>


<!-- Bootstrap Icons (si aún no lo tienes incluido) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">


  </main>

<!-- Footer corto, limpio y profesional -->
<footer style="background-color: #112b66; color: #ffffff; padding: 15px 0;">
    <div style="max-width: 1200px; margin: 0 auto; text-align: center; font-size: 13px;">
        © {{ date('Y') }} TurisKuy - Desarrollado por Gary Fernando Yunganina Mamani, UPeU Juliaca.
    </div>
</footer>




  <!-- JS Scripts -->
  <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>
  <script src="{{ asset('assets/vendor/aos/aos.js') }}"></script>
  <script src="{{ asset('assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
  <script src="{{ asset('assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
  <script src="{{ asset('assets/js/main.js') }}"></script>
  <script src="{{ asset('assets/js/ubicaciones.js') }}"></script>




</body>
</html>
