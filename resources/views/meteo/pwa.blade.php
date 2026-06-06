<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="manifest" href="/manifest-mctandil.json">
    <meta name="theme-color" content="#0b1630">

    <title>MCTandil App</title>

    <link rel="stylesheet" href="{{ asset('meteo-assets/pwa.css') }}?v=2">
</head>
<body>

<div id="appLoader" class="app-loader">
    <div class="loader-card">
        <img src="{{ asset('meteo-assets/img/logo-mctandil.png') }}" alt="MCTandil" class="loader-logo">

        <h2>MCTandil</h2>
        <p>Meteorología en tiempo real</p>

        <div class="loader-steps">
              <span id="loadStep1">📡 Conectando estación...</span>
              <span id="loadStep2">☀️ Cargando astronomía...</span>
              <span id="loadStep3">🌧️ Actualizando meteorología...</span>
              <span id="loadStep4">✅ Sistema listo</span>
        </div>

        <div class="loader-bar">
            <div class="loader-progress"></div>
        </div>
    </div>
</div>

<div class="app">

    <header class="app-header">
        <img src="{{ asset('meteo-assets/img/logo-mctandil.png') }}" alt="MCTandil" class="logo">

        <div>
            <h1>MCTandil</h1>
            <p>Estación meteorológica automática</p>
        </div>
    </header>

    <section class="status-card">
        <div class="status-line">
            <span class="status-dot" id="statusDot"></span>
            <strong id="connText">Sin datos</strong>
        </div>

        <p id="updatedAt">Actualizando datos...</p>

        <div class="location-box">
            <span>📍 Tandil, Buenos Aires</span>
            <small>
                Lat: <b id="latitud">--</b> |
                Lon: <b id="longitud">--</b> |
                Alt: <b id="altitud">--</b> m
            </small>
        </div>
    </section>

    <details class="section" open>
        <summary>🌙 Astronomía</summary>

        <section class="grid">
            <div class="card"><span>Alba</span><strong id="alba">--</strong></div>
            <div class="card"><span>Salida sol</span><strong id="salida_sol">--</strong></div>
            <div class="card"><span>Puesta sol</span><strong id="puesta_sol">--</strong></div>
            <div class="card"><span>Anochecer</span><strong id="anochecer">--</strong></div>
            <div class="card"><span>Duración día</span><strong id="duracion_dia">--</strong></div>
            <div class="card"><span>Salida luna</span><strong id="salida_luna">--</strong></div>
            <div class="card"><span>Puesta luna</span><strong id="puesta_luna">--</strong></div>
            <div class="card"><span>Fase lunar</span><strong id="fase_lunar">--</strong></div>
            <div class="card"><span>Ciclo lunar</span><strong id="ciclo_lunar">--</strong></div>
            <div class="card"><span>Visibilidad lunar</span><strong><span id="visibilidad_lunar">--</span> %</strong></div>
        </section>
    </details>

    <details class="section" open>
        <summary>🌡️ Temperatura y atmósfera</summary>

        <section class="grid">
            <div class="card"><span>Temperatura</span><strong><span id="temperatura">--</span> °C</strong></div>
            <div class="card"><span>Sensación térmica</span><strong><span id="sensacion_termica">--</span> °C</strong></div>
            <div class="card"><span>Humedad</span><strong><span id="humedad">--</span> %</strong></div>
            <div class="card"><span>Punto rocío</span><strong><span id="punto_rocio">--</span> °C</strong></div>
            <div class="card"><span>Presión</span><strong><span id="presion">--</span> hPa</strong></div>
            <div class="card"><span>Techo nubes</span><strong><span id="techo_nubes">--</span> m</strong></div>
        </section>
    </details>

    <details class="section">
        <summary>💨 Viento</summary>

        <section class="grid">
            <div class="card"><span>Dirección</span><strong id="direccion_viento">--</strong></div>
            <div class="card"><span>Ángulo</span><strong><span id="angulo_viento">--</span>°</strong></div>
            <div class="card"><span>Velocidad</span><strong><span id="velocidad_viento">--</span> km/h</strong></div>
            <div class="card"><span>Ráfagas</span><strong><span id="rafagas_viento">--</span> km/h</strong></div>
        </section>
    </details>

    <details class="section">
        <summary>🌧️ Lluvia</summary>

        <section class="grid">
            <div class="card"><span>Lluvia diaria</span><strong><span id="lluvia_acumulada">--</span> mm</strong></div>
            <div class="card"><span>Intensidad máxima</span><strong><span id="intensidad_lluvia_maxima_diaria">--</span> mm/h</strong></div>
            <div class="card"><span>Intensidad promedio</span><strong><span id="intensidad_lluvia_promedio_diaria">--</span> mm/h</strong></div>
        </section>
    </details>

    <details class="section">
        <summary>☀️ Radiación y luz</summary>

        <section class="grid">
            <div class="card"><span>Índice UV</span><strong><span id="indice_uv">--</span></strong></div>
            <div class="card"><span>Peligrosidad UV</span><strong id="uv_peligrosidad">--</strong></div>
            <div class="card"><span>Luminosidad</span><strong><span id="luminosidad">--</span> lx</strong></div>
        </section>
    </details>

</div>

<script src="{{ asset('meteo-assets/pwa.js') }}?v=2"></script>

<script>
if ("serviceWorker" in navigator) {
    navigator.serviceWorker.register("/service-worker-mctandil.js");
}
</script>
</body>
</html>
