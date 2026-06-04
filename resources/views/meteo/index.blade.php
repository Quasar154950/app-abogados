<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Estación Meteorológica Automática - Microclima Tandil</title>

  <!-- CSS principal -->
  <link rel="stylesheet" href="{{ asset('meteo-assets/styles.css') }}?v=?v=10004" />

  <!-- Iconos -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

  <!-- Leaflet (para el mapa) -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>
<body>
<header>
  <h1>
    <a href="{{ url('/') }}" aria-label="Volver al inicio de MCTandil">
      <img src="{{ asset('meteo-assets/img/logo-mctandil.png') }}" alt="Microclima Tandil" class="logo-inline" style="height:68px;width:auto;">
    </a>
    <span class="site-title">Microclima Tandil - Estaciones Meteorológicas Automáticas</span>
  </h1>

  <div class="search-container" id="searchIcon">
    <i class="fas fa-search"></i>
  </div>
</header>

  <div class="search-bar-wrapper" id="searchBar">
    <input type="text" placeholder="Buscar en el sitio..." class="search-input">
    <button class="search-button">Buscar</button>
  </div>

  <marquee class="marquee-text" behavior="scroll" direction="left" scrollamount="5">
    Esta estación mide condiciones meteorológicas reales sin interpolaciones ni modelos predictivos. Datos actualizados cada 10 segundos.
  </marquee>

  <nav class="main-nav">
    <!-- ====== TANDIL con iconitos ====== -->
    <div class="nav-dropdown">
      <a href="#" class="nav-button active dropdown-toggle" id="tandilDropdownBtn">
        Tandil <i class="fas fa-chevron-down dropdown-arrow"></i>
      </a>

      <div class="dropdown-menu" id="tandilDropdownMenu">

        <!-- Datos Actuales con submenú -->
        <div class="dropdown-item has-sub" tabindex="0" aria-haspopup="true" aria-expanded="false">
          <span class="with-icon">
            <span class="nav-ico-wrap"><i class="fa-solid fa-gauge-high nav-ico" aria-hidden="true"></i></span>
            Datos Actuales
            <i class="fas fa-chevron-right subchev" aria-hidden="true"></i>
          </span>
          <div class="sub-menu" role="menu" aria-label="Submenú Datos Actuales">
            <a href="{{ route('meteo.graficas') }}" class="dropdown-subitem with-icon" role="menuitem">
              <span class="nav-ico-wrap"><i class="fa-solid fa-chart-line nav-ico" aria-hidden="true"></i></span>
              Gráficas
            </a>
          </div>
        </div>

        <!-- Ítems planos con icono -->
        <a href="#" class="dropdown-item with-icon">
          <span class="nav-ico-wrap"><i class="fa-solid fa-calendar-days nav-ico" aria-hidden="true"></i></span>
          Datos Mensuales
        </a>

        <a href="#" class="dropdown-item with-icon">
          <span class="nav-ico-wrap"><i class="fa-solid fa-image nav-ico" aria-hidden="true"></i></span>
          Galería de Fotos
        </a>

        <a href="#" class="dropdown-item with-icon">
          <span class="nav-ico-wrap"><i class="fa-solid fa-camera nav-ico" aria-hidden="true"></i></span>
          Web Cam
        </a>
      </div>
    </div>

    <a href="gardey.html" class="nav-button">Gardey</a>
    <a href="azucena.html" class="nav-button">Azucena</a>

    <!-- Descargas -->
    <div class="nav-dropdown">
      <a href="#" class="nav-button dropdown-toggle" id="descargasDropdownBtn">
        Descargas <i class="fas fa-chevron-down dropdown-arrow"></i>
      </a>

      <div class="dropdown-menu" id="descargasDropdownMenu">
        <a href="{{ route('meteo.reporte') }}" class="dropdown-item">📄 Reporte diario (PDF)</a>
        <a href="#" class="dropdown-item">📊 Reporte mensual (PDF)</a>
      </div>
    </div>

    <!-- NASA -->
    <div class="nav-dropdown">
      <a href="#" class="nav-button dropdown-toggle" id="nasaDropdownBtn">
        NASA <i class="fas fa-chevron-down dropdown-arrow"></i>
      </a>

      <div class="dropdown-menu" id="nasaDropdownMenu">
        <a href="https://www.youtube.com/nasa/live" target="_blank" rel="noopener" class="dropdown-item">📺 ISS Live (cámara ISS)</a>
        <a href="https://eyes.nasa.gov/apps/earth" target="_blank" rel="noopener" class="dropdown-item">🛰️ Eyes on the Earth (3D)</a>
      </div>
    </div>

     <!-- BOTÓN INICIO -->
    <a href="{{ url('/') }}" class="nav-button nav-home">
      <i class="fas fa-house"></i> Inicio
    </a>

  </nav>

  <main class="content-wrapper">

    <!-- ===== Coordenadas ===== -->
    <section id="coordenadas-container">
      <div class="coords-header">
        <h2>Coordenadas Geográficas</h2>
        <button id="openMapBtn" class="coords-map-btn" title="Ver mapa">
          <i class="fas fa-location-dot"></i>
        </button>
      </div>

      <div>
        Latitud: <span id="latitud" class="dato-value--bold">--</span> |
        Longitud: <span id="longitud" class="dato-value--bold">--</span> |
        Altitud: <span id="altitud" class="dato-value--bold">--</span> m
      </div>

      <div id="mapWrapper" class="map-wrapper">
        <div id="map"></div>
        <div id="mapMsg" class="map-msg">Sin señal GPS</div>
      </div>
    </section>

    <!-- ===== Astronomía ===== -->
    <section id="astronomia-container">
      <div class="astro-title">

        <img src="{{ asset('meteo-assets/img/sol-horizonte-transparente.png') }}"
         alt="Astronomía"
         class="astro-sun-icon">

    <h2>Datos Astronómicos</h2>

        <!-- 🌙 LUNA -->
        <div id="ml-widget" aria-label="Fase lunar" role="img">
          <svg id="ml-svg" viewBox="-130 -130 260 260">
            <defs>
              <mask id="ml-mask">
                <rect x="-130" y="-130" width="260" height="260" fill="black"/>
                <circle id="ml-c1" r="110" fill="white"/>
                <circle id="ml-c2" r="110" fill="black"/>
              </mask>
              <clipPath id="ml-disc"><circle r="110"/></clipPath>
              <filter id="ml-soft"><feGaussianBlur stdDeviation="1.7"/></filter>
            </defs>
            <circle r="110" fill="#000" clip-path="url(#ml-disc)"/>
            <image id="ml-img-base" href="img/luna_llena.png"
                   x="-130" y="-130" width="260" height="260"
                   clip-path="url(#ml-disc)" opacity="0.00"/>
            <g mask="url(#ml-mask)">
              <image id="ml-img-lit" href="{{ asset('meteo-assets/img/luna_llena.png') }}"
                     x="-130" y="-130" width="260" height="260"/>
              <circle r="110" fill="none" stroke="white" stroke-opacity="0.6"
                      stroke-width="1.2" filter="url(#ml-soft)"/>
            </g>
          </svg>
        </div>
      </div>

      <div class="row">
        <div>
          <div>Alba: <span id="alba" class="dato-value--bold">--</span></div>
          <div>Salida del sol: <span id="salida_sol" class="dato-value--bold">--</span></div>
          <div>Puesta del sol: <span id="puesta_sol" class="dato-value--bold">--</span></div>
          <div>Duración del día: <span id="duracion_dia" class="dato-value--bold">--</span></div>
          <div>Anochecer: <span id="anochecer" class="dato-value--bold">--</span></div>
        </div>
        <div>
          <div>Salida de la luna: <span id="salida_luna" class="dato-value--bold">--</span></div>
          <div>Puesta de la luna: <span id="puesta_luna" class="dato-value--bold">--</span></div>
          <div>Fase lunar: <span id="fase_lunar" class="dato-value--bold">--</span></div>
          <div>Ciclo lunar: <span id="ciclo_lunar" class="dato-value--bold">--</span></div>
          <div>Visibilidad lunar: <span id="visibilidad_lunar" class="dato-value--bold">--</span></div>
        </div>
      </div>
    </section>

    <!-- ===== Meteorología ===== -->
    <section id="datos-grid-container">
      <div id="temperatura" class="dato-container">
        <i class="fa-solid fa-temperature-half dato-icon"></i>
        Temperatura: <span id="temperatura-value" class="dato-value--bold">--</span> °C
      </div>

      <div id="humedad" class="dato-container">
        <i class="fa-solid fa-droplet dato-icon"></i>
        Humedad Relativa: <span id="humedad-value" class="dato-value--bold">--</span> %
      </div>

      <div id="lluvia-acumulada" class="dato-container">
        <i class="fa-solid fa-cloud-rain dato-icon"></i>
        Lluvia Diaria: <span id="lluvia-value" class="dato-value--bold">--</span> mm
      </div>

      <div id="intensidad-lluvia-maxima" class="dato-container">
        <i class="fa-solid fa-cloud-showers-heavy dato-icon icon-max"></i>
        Intensidad Lluvia Máxima:
        <span id="intensidad-lluvia-maxima-value" class="dato-value--bold">--</span> mm
      </div>

      <div id="intensidad-lluvia-promedio" class="dato-container">
        <i class="fa-solid fa-cloud-showers-heavy dato-icon icon-prom"></i>
        Intensidad Lluvia:
        <span id="intensidad-lluvia-promedio-value" class="dato-value--bold">--</span> mm
      </div>

      <div id="luminosidad" class="dato-container">
        <i class="fa-regular fa-sun dato-icon"></i>
        Luminosidad: <span id="luminosidad-value" class="dato-value--bold">--</span> Lux
      </div>

      <div id="indice-uv" class="dato-container">
        <i class="fa-solid fa-sun dato-icon"></i>
        Índice UV: <span id="uv-value" class="dato-value--bold">--</span>
      </div>

      <div id="presion-atmosferica" class="dato-container">
        <i class="fa-solid fa-gauge-high dato-icon"></i>
        Presión Atmosférica: <span id="presion-value" class="dato-value--bold">--</span> hPa
      </div>

      <div id="techo-nubes" class="dato-container">
        <i class="fa-solid fa-cloud dato-icon"></i>
        Techo estimado de Nubes: <span id="nubes-value" class="dato-value--bold">--</span> m
      </div>

      <div id="punto-rocio" class="dato-container">
        <i class="fa-solid fa-temperature-low dato-icon"></i>
        Punto de Rocío: <span id="rocio-value" class="dato-value--bold">--</span> °C
      </div>

      <div id="viento-direccion" class="dato-container">
        <i class="fa-solid fa-compass dato-icon"></i>
        Dirección del Viento:
        <span id="direccion-value" class="dato-value--bold">--</span>
        <i id="flecha-viento" class="fa-solid fa-arrow-up"></i>
      </div>

      <div id="velocidad-promedio-viento" class="dato-container">
        <i class="fa-solid fa-wind dato-icon"></i>
        Velocidad Promedio Viento: <span id="velocidad-value" class="dato-value--bold">--</span> km/h
      </div>

      <div id="rafagas-viento" class="dato-container">
        <i class="fa-solid fa-wind dato-icon"></i>
        Ráfagas Viento: <span id="rafagas-value" class="dato-value--bold">--</span> km/h
      </div>

      <div id="sensacion-termica" class="dato-container">
        <i class="fa-solid fa-temperature-three-quarters dato-icon"></i>
        Sensación Térmica: <span id="sensacion-value" class="dato-value--bold">--</span> °C
      </div>
    </section>
  </main>

<footer>
  <p>
    Microclima Tandil — Datos en tiempo real
    <!-- WhatsApp -->
    <a
      href="https://wa.me/5492494631299"
      class="footer-link icon-link"
      target="_blank" rel="noopener"
      aria-label="Escribir por WhatsApp"
      title="WhatsApp"
    >
      <i class="fa-brands fa-whatsapp icon-whatsapp"></i>
    </a>
  </p>

  <a href="acerca-de.html" class="info-icon-footer" title="Más información sobre la estación">
    <i class="fa-solid fa-circle-info"></i>
  </a>
</footer>

  <div class="social-icons-container">
    <a href="https://www.instagram.com/tu_usuario_instagram" target="_blank" class="social-icon instagram"><i class="fa-brands fa-instagram"></i></a>
    <a href="https://www.facebook.com/tu_pagina_facebook" target="_blank" class="social-icon facebook"><i class="fa-brands fa-facebook-f"></i></a>
    <a href="https://twitter.com/tu_usuario_twitter" target="_blank" class="social-icon twitter"><i class="fa-brands fa-x-twitter"></i></a>
    <a href="https://www.youtube.com/channel/tu_canal" target="_blank" class="social-icon youtube"><i class="fa-brands fa-youtube"></i></a>
    <a href="mailto:tu_correo@example.com" class="social-icon email"><i class="fa-solid fa-envelope"></i></a>
  </div>

  <!-- Tu JS principal -->
  <script src="{{ asset('meteo-assets/script.js') }}"></script>

  <!-- Toggle simple para el menú NASA (no rompe lo demás) -->
  <script>
    (function(){
      const nasaBtn = document.getElementById('nasaDropdownBtn');
      const nasaMenu = document.getElementById('nasaDropdownMenu');

      if(!nasaBtn || !nasaMenu) return;

      nasaBtn.addEventListener('click', function(e){
        e.preventDefault();
        e.stopPropagation();
        nasaMenu.classList.toggle('show');
        if(nasaMenu.classList.contains('show')) nasaMenu.removeAttribute('hidden');
        else nasaMenu.setAttribute('hidden', '');
      });

      document.addEventListener('click', function(e){
        if (!nasaMenu.contains(e.target) && e.target !== nasaBtn){
          nasaMenu.classList.remove('show');
          nasaMenu.setAttribute('hidden', '');
        }
      });
    })();
  </script>
</body>
</html>
