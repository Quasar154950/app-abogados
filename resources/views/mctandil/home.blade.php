<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MCTandil | SaaS, IoT, Meteorología e Impresión 3D</title>

    <style>
        html { scroll-behavior: smooth; }

        body {
    margin: 0;
    font-family: Arial, sans-serif;
    background:
        url('/images/mctandil-background.png')
        center bottom / cover
        no-repeat fixed;
    background-color: #020617;
    color: #f8fafc;
    overflow-x: hidden;
}

        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background:
                radial-gradient(circle at top left, rgba(249,115,22,.20), transparent 35%),
                radial-gradient(circle at bottom right, rgba(14,165,233,.10), transparent 35%);
            pointer-events: none;
            z-index: -1;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 28px 20px;
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 50;
            backdrop-filter: blur(14px);
            background: rgba(2, 6, 23, .78);
            border-bottom: 1px solid rgba(148,163,184,.15);
        }

        .topbar-inner {
            max-width: 1100px;
            margin: 0 auto;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 900;
            letter-spacing: .5px;
        }

        .brand img {
            width: 34px;
            height: 34px;
            object-fit: contain;
        }

        .toplinks {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
        }

        .toplinks a {
            color: #cbd5e1;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
        }

        .toplinks a:hover { color: #fb923c; }

        .hero {
            min-height: 90vh;
            display: flex;
            align-items: center;
            text-align: center;
        }

        .hero-content { width: 100%; }

        .logo {
            width: 190px;
            max-width: 70%;
            margin: 0 auto 22px;
            display: block;
            filter: drop-shadow(0 18px 35px rgba(249,115,22,.25));
        }

        .badge {
            display: inline-block;
            background: rgba(249,115,22,.15);
            color: #fb923c;
            border: 1px solid rgba(249,115,22,.35);
            padding: 8px 14px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 18px;
        }

        .eyebrow {
            font-size: 14px;
            letter-spacing: 3px;
            color: #fb923c;
            font-weight: bold;
            margin-bottom: 12px;
        }

        h1 {
            font-size: clamp(42px, 7vw, 82px);
            line-height: .95;
            margin: 0;
            font-weight: 900;
        }

        .subtitle {
            margin: 22px auto 0;
            color: #cbd5e1;
            font-size: 20px;
            max-width: 800px;
            line-height: 1.5;
        }

        .nav {
            margin-top: 34px;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 12px;
        }

        .nav a {
            color: white;
            text-decoration: none;
            background: #f97316;
            padding: 12px 18px;
            border-radius: 16px;
            font-weight: bold;
            transition: .2s;
        }

        .nav a:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(249,115,22,.25);
        }

        .nav a.secondary {
            background: #111827;
            border: 1px solid #334155;
        }

        .construction {
            max-width: 720px;
            margin: 28px auto 0;
            background: rgba(251,146,60,.12);
            border: 1px solid rgba(251,146,60,.35);
            color: #fed7aa;
            padding: 16px;
            border-radius: 18px;
            font-weight: bold;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
            margin-top: 34px;
        }

        .stat {
            background: rgba(15,23,42,.75);
            border: 1px solid #1e293b;
            border-radius: 22px;
            padding: 20px;
        }

        .stat strong {
            display: block;
            color: #fb923c;
            font-size: 28px;
            margin-bottom: 6px;
        }

        .stat span {
            color: #cbd5e1;
            font-size: 14px;
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
            background: rgba(15,23,42,.86);
            border: 1px solid #1e293b;
            border-radius: 24px;
            padding: 24px;
            box-shadow: 0 20px 50px rgba(0,0,0,.25);
            transition: .2s;
        }

        .card:hover {
            transform: translateY(-4px);
            border-color: rgba(249,115,22,.55);
            box-shadow: 0 24px 60px rgba(0,0,0,.35);
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
            text-align: center;
        }

        .whatsapp {
            position: fixed;
            right: 18px;
            bottom: 18px;
            z-index: 60;
            background: #25d366;
            color: white;
            text-decoration: none;
            font-weight: bold;
            padding: 14px 18px;
            border-radius: 999px;
            box-shadow: 0 14px 40px rgba(0,0,0,.35);
        }

        .whatsapp:hover {
            transform: translateY(-2px);
        }

        @media (max-width: 640px) {
            .topbar-inner {
                justify-content: center;
            }

            .toplinks {
                display: none;
            }

            .hero {
                min-height: auto;
                padding-top: 55px;
                padding-bottom: 55px;
            }

            .logo {
                width: 150px;
            }

            .subtitle {
                font-size: 17px;
            }

            .section h2 {
                font-size: 28px;
            }

            .whatsapp {
                right: 12px;
                bottom: 12px;
                padding: 12px 15px;
                font-size: 14px;
            }
        }
    </style>
</head>

<body>

    <header class="topbar">
        <div class="topbar-inner">
            <div class="brand">
                <img src="{{ asset('images/logo-mctandil.png') }}" alt="Logo MCTandil">
                <span>MCTandil</span>
            </div>

            <nav class="toplinks">
                <a href="{{ route('meteo.index') }}">Meteorología</a>
                <a href="#iot">IoT</a>
                <a href="#saas">SaaS</a>
                <a href="#impresion3d">Impresión 3D</a>
                <a href="#contacto">Contacto</a>
            </nav>
        </div>
    </header>

    <main class="container hero">
        <div class="hero-content">
            <div class="badge">🚧 Sitio en construcción</div>

            <img src="{{ asset('images/logo-mctandil.png') }}" alt="Logo MCTandil" class="logo">

            <div class="eyebrow">TECNOLOGÍA APLICADA AL MUNDO REAL</div>

            <h1>MCTandil</h1>

            <p class="subtitle">
                Desarrollo de software, SaaS, IoT, meteorología, automatización e impresión 3D
                para empresas, instituciones y proyectos tecnológicos.
            </p>

            <div class="nav">
                <a href="#contacto">Consultar proyecto</a>
                <a href="{{ route('meteo.index') }}" class="secondary">Meteorología</a>
                <a href="#iot" class="secondary">IoT</a>
                <a href="#saas" class="secondary">SaaS</a>
                <a href="#impresion3d" class="secondary">Impresión 3D</a>
            </div>

            <div class="stats">
                <div class="stat">
                    <strong>4</strong>
                    <span>Áreas tecnológicas integradas</span>
                </div>

                <div class="stat">
                    <strong>100%</strong>
                    <span>Desarrollo propio y adaptable</span>
                </div>

                <div class="stat">
                    <strong>Tandil</strong>
                    <span>Tecnología local con mirada real</span>
                </div>
            </div>

            <div class="construction">
                Estamos construyendo el portal oficial de MCTandil.
            </div>
        </div>
    </main>

    <section class="container section">
        <h2>🚀 ¿Por qué elegir MCTandil?</h2>
        <p>
            Integramos software, hardware, sensores, automatización y fabricación digital
            para crear soluciones reales, útiles y adaptadas a cada proyecto.
        </p>

        <div class="grid">
            <div class="card">
                <h3>Soluciones a medida</h3>
                <p>Desarrollos pensados según la necesidad real de cada cliente o institución.</p>
            </div>

            <div class="card">
                <h3>Hardware + Software</h3>
                <p>Conectamos sensores, placas, bases de datos, paneles web y aplicaciones.</p>
            </div>

            <div class="card">
                <h3>Tecnología local</h3>
                <p>Proyectos desarrollados desde Tandil, con soporte cercano y personalizado.</p>
            </div>
        </div>
    </section>

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

    <a class="whatsapp" href="https://wa.me/5492494631299" target="_blank">
        💬 WhatsApp
    </a>

</body>
</html>