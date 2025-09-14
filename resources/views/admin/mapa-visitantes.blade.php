<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>🌍 Mapa en Tiempo Real | TurisKuy</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

  <!-- Estilos personalizados -->
  <style>
    html, body {
      height: 100%;
      margin: 0;
      padding: 0;
      overflow: hidden;
    }

    #map {
      height: 100vh;
      width: 100%;
      position: relative;
      z-index: 0;
    }

    .contador-box {
      position: absolute;
      top: 10px;
      right: 10px;
      background: white;
      padding: 6px 12px;
      border-radius: 6px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.2);
      font-weight: bold;
      z-index: 9999;
    }

    .btn-volver {
      position: absolute;
      top: 10px;
      left: 10px;
      background: #f3f3f3;
      padding: 6px 12px;
      border-radius: 6px;
      border: none;
      cursor: pointer;
      font-weight: bold;
      z-index: 9999;
    }
  </style>
</head>
<body>

  <!-- Botón de volver -->
  <button class="btn-volver" onclick="window.history.back()">⬅ Volver</button>

  <!-- Contador de visitantes -->
  <div class="contador-box">
    👥 Visitantes en el mapa: <span id="contadorVisitantes">0</span>
  </div>

  <!-- Mapa -->
  <div id="map"></div>

  <!-- Leaflet JS -->
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

  <!-- Pasamos los lugares desde Laravel al JS -->
  <script>
    window.lugaresTuristicos = @json($lugares);
  </script>

  <!-- Script principal de seguimiento -->
  <script src="{{ asset('assets/js/mapa-visitantes.js') }}"></script>

</body>
</html>
