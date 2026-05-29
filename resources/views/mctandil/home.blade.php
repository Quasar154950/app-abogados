<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MCTandil | Tecnología, IoT, Meteorología y Apps</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #020617;
            color: #f8fafc;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 28px 20px;
        }

        .hero {
            min-height: 90vh;
            display: flex;
            align-items: center;
        }

        .badge {
            display: inline-block;
            background: rgba(249, 115, 22, 0.15);
            color: #fb923c;
            border: 1px solid rgba(249, 115, 22, 0.35);
            padding: 8px 14px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 18px;
        }

        h1 {
            font-size: clamp(42px, 7vw, 82px);
            line-height: 0.95;
            margin: 0;
            font-weight: 900;
        }

        .subtitle {
            margin-top: 22px;
            color: #cbd5e1;
            font-size: 20px;
            max-width: 720px;
            line-height: 1.5;
        }

        .nav {
            margin-top: 34px;
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .nav a {
            color: white;
            text-decoration: none;
            background: #f97316;
            padding: 12px 18px;
            border-radius: 16px;
            font-weight: bold;
        }

        .nav a.secondary {
            background: #111827;
            border: 1px solid #334155;
        }

        .section {
            padding: 60px 0;
        }

        .section h2 {
            font-size: 34px;
            margin-bottom: 14px;
        }

        .section p {
            color: #cbd5e1;
            line-height: 1.6;
            font-size: 17px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 18px;
            margin-top: 26px;
        }

        .card {
            background: #0f172a;
            border: 1px solid #1e293b;
            border-radius: 24px;
            padding: 24px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.25);
        }

        .card h3 {
            margin-top: 0;
            font-size: 22px;
        }

        .footer {
            border-top: 1px solid #1e293b;
            padding: 28px 0;
            color: #94a3b8;
            font-size: 14px;
        }

        .construction {
            margin-top: 28px;
            background: rgba(251, 146, 60, 0.12);
            border: 1px solid rgba(251, 146, 60, 0.35);
            color: #fed7aa;
            padding: 16px;
            border-radius: 18px;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <main class="container hero">
        <div>
            <div class="badge">
                🚧 Sitio en construcción
            </div>

            <h1>MCTandil</h1>

            <p class="subtitle">
                Tecnología, IoT, automatización, meteorología y desarrollo de apps
                para empresas, profesionales y proyectos locales.
            </p>

            <div class="nav">
                <a href="#meteo">Meteorología</a>
                <a href="#iot" class="secondary">IoT</a>
                <a href="#apps" class="secondary">Apps</a>
                <a href="#contacto" class="secondary">Contacto</a>
            </div>

            <div class="construction">
                Estamos construyendo el portal oficial de MCTandil.
            </div>
        </div>
    </main>

    <section id="meteo" class="container section">
        <h2>🌦️ Meteorología</h2>
        <p>
            Desarrollo de estación meteorológica propia, monitoreo de variables ambientales,
            datos en tiempo real, almanaque astronómico y visualización web.
        </p>

        <div class="grid">
            <div class="card">
                <h3>Estación meteorológica</h3>
                <p>Sensores de temperatura, humedad, presión, lluvia, luminosidad y más.</p>
            </div>

            <div class="card">
                <h3>Datos en tiempo real</h3>
                <p>Visualización web de información meteorológica local.</p>
            </div>

            <div class="card">
                <h3>Almanaque astronómico</h3>
                <p>Datos solares y lunares integrados al sistema.</p>
            </div>
        </div>
    </section>

    <section id="iot" class="container section">
        <h2>🤖 IoT y Automatización</h2>
        <p>
            Proyectos con ESP32, sensores, monitoreo remoto, automatización y sistemas conectados.
        </p>

        <div class="grid">
            <div class="card">
                <h3>ESP32 y sensores</h3>
                <p>Integración de hardware con plataformas web y dashboards.</p>
            </div>

            <div class="card">
                <h3>Automatización</h3>
                <p>Control y monitoreo de dispositivos físicos desde sistemas digitales.</p>
            </div>

            <div class="card">
                <h3>Proyectos a medida</h3>
                <p>Soluciones tecnológicas adaptadas a cada necesidad.</p>
            </div>
        </div>
    </section>

    <section id="apps" class="container section">
        <h2>📱 Apps y sistemas</h2>
        <p>
            Desarrollo de aplicaciones web, PWA y sistemas de gestión para distintos rubros.
        </p>

        <div class="grid">
            <div class="card">
                <h3>⚖️ Abogados App</h3>
                <p>Gestión para estudios jurídicos, clientes, documentos y vencimientos.</p>
                
            </div>

            <div class="card">
                <h3>🏋️ Gimnasios App</h3>
                <p>Socios, cuotas, turnos, reservas, QR, asistencia y pagos.</p>
            </div>

            <div class="card">
                <h3>🎾 Padel App</h3>
                <p>Reservas, torneos, cuadros, rankings y resultados. Próximamente.</p>
            </div>
        </div>
    </section>

    <section id="contacto" class="container section">
        <h2>📞 Contacto</h2>
        <p>
            Para consultas sobre apps, automatización, IoT o proyectos meteorológicos.
        </p>

        <div class="grid">
            <div class="card">
                <h3>Email</h3>
                <p>contacto@mctandil.com</p>
            </div>

            <div class="card">
                <h3>Ubicación</h3>
                <p>Tandil, Buenos Aires, Argentina.</p>
            </div>
        </div>
    </section>

    <footer class="container footer">
        © {{ date('Y') }} MCTandil · Tecnología, IoT, Meteorología y Apps.
    </footer>

</body>
</html>
