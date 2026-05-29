<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MCTandil | SaaS, IoT, Meteorología e Impresión 3D</title>

    <style>
        body { margin: 0; font-family: Arial, sans-serif; background: #020617; color: #f8fafc; }
        .container { max-width: 1100px; margin: 0 auto; padding: 28px 20px; }
        .hero { min-height: 90vh; display: flex; align-items: center; }
        .badge { display: inline-block; background: rgba(249,115,22,.15); color: #fb923c; border: 1px solid rgba(249,115,22,.35); padding: 8px 14px; border-radius: 999px; font-size: 13px; font-weight: bold; margin-bottom: 18px; }
        h1 { font-size: clamp(42px, 7vw, 82px); line-height: .95; margin: 0; font-weight: 900; }
        .subtitle { margin-top: 22px; color: #cbd5e1; font-size: 20px; max-width: 760px; line-height: 1.5; }
        .nav { margin-top: 34px; display: flex; flex-wrap: wrap; gap: 12px; }
        .nav a { color: white; text-decoration: none; background: #f97316; padding: 12px 18px; border-radius: 16px; font-weight: bold; }
        .nav a.secondary { background: #111827; border: 1px solid #334155; }
        .section { padding: 60px 0; }
        .section h2 { font-size: 34px; margin-bottom: 14px; }
        .section p { color: #cbd5e1; line-height: 1.6; font-size: 17px; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 18px; margin-top: 26px; }
        .card { background: #0f172a; border: 1px solid #1e293b; border-radius: 24px; padding: 24px; box-shadow: 0 20px 50px rgba(0,0,0,.25); }
        .card h3 { margin-top: 0; font-size: 22px; }
        .footer { border-top: 1px solid #1e293b; padding: 28px 0; color: #94a3b8; font-size: 14px; }
        .construction { margin-top: 28px; background: rgba(251,146,60,.12); border: 1px solid rgba(251,146,60,.35); color: #fed7aa; padding: 16px; border-radius: 18px; font-weight: bold; }
    </style>
</head>

<body>

    <main class="container hero">
        <div>
            <div class="badge">🚧 Sitio en construcción</div>

            <h1>MCTandil</h1>

            <p class="subtitle">
                SaaS · IoT · Meteorología · Automatización · Impresión 3D.
                Conectando ideas, creando soluciones tecnológicas para proyectos locales,
                empresas y organizaciones.
            </p>

            <div class="nav">
                <a href="#meteo">Meteorología</a>
                <a href="#iot" class="secondary">IoT</a>
                <a href="#saas" class="secondary">SaaS</a>
                <a href="#impresion3d" class="secondary">Impresión 3D</a>
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
            Desarrollo de estaciones meteorológicas, monitoreo ambiental, datos en tiempo real,
            almanaque astronómico y soluciones para observación climática.
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
            Sensores, ESP32, LoRa, telemetría, automatización, monitoreo remoto,
            robótica y soluciones para el sector agropecuario, industrial y urbano.
        </p>

        <div class="grid">
            <div class="card">
                <h3>ESP32, LoRa y sensores</h3>
                <p>Integración de hardware con plataformas web, dashboards y bases de datos.</p>
            </div>

            <div class="card">
                <h3>Automatización</h3>
                <p>Riego automático, bombas, alarmas, monitoreo remoto y control de dispositivos.</p>
            </div>

            <div class="card">
                <h3>Proyectos a medida</h3>
                <p>Soluciones tecnológicas adaptadas a necesidades reales del mundo físico.</p>
            </div>
        </div>
    </section>

    <section id="saas" class="container section">
        <h2>📱 SaaS y Aplicaciones</h2>
        <p>
            Desarrollo de soluciones SaaS, aplicaciones web, PWA y sistemas de gestión
            para estudios, gimnasios, clubes y organizaciones.
        </p>

        <div class="grid">
            <div class="card">
                <h3>⚖️ Abogados App</h3>
                <p>Gestión para estudios jurídicos, clientes, documentos, vencimientos y comunicación.</p>
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

    <section id="impresion3d" class="container section">
        <h2>🖨️ Impresión 3D y Prototipado</h2>
        <p>
            Diseño paramétrico, prototipado rápido, fabricación de piezas personalizadas
            y desarrollo de soluciones para proyectos tecnológicos.
        </p>

        <div class="grid">
            <div class="card">
                <h3>Diseño paramétrico</h3>
                <p>Modelado de piezas, soportes, gabinetes y componentes personalizados.</p>
            </div>

            <div class="card">
                <h3>Prototipos funcionales</h3>
                <p>Fabricación de prototipos para pruebas, validación y mejora de proyectos.</p>
            </div>

            <div class="card">
                <h3>Gabinetes y soportes</h3>
                <p>Piezas para ESP32, sensores, estaciones meteorológicas e IoT.</p>
            </div>
        </div>
    </section>

    <section id="contacto" class="container section">
        <h2>📞 Contacto</h2>
        <p>
            Para consultas sobre SaaS, apps, automatización, IoT, impresión 3D
            o proyectos meteorológicos.
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
        © {{ date('Y') }} MCTandil · SaaS · IoT · Meteorología · Automatización · Impresión 3D.
    </footer>

</body>
</html>
