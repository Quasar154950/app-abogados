<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soluciones SaaS | MCTandil</title>

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #020617;
            color: #f8fafc;
        }

        .container {
            max-width: 1180px;
            margin: 0 auto;
            padding: 28px 20px;
        }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 50;
            background: rgba(2, 6, 23, .88);
            backdrop-filter: blur(14px);
            border-bottom: 1px solid rgba(148,163,184,.15);
        }

        .topbar-inner {
            max-width: 1180px;
            margin: 0 auto;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 900;
        }

        .brand img {
            width: 68px;
            height: auto;
        }

        .back {
            color: #cbd5e1;
            text-decoration: none;
            font-weight: bold;
        }

        .back:hover {
            color: #fb923c;
        }

        .hero {
            padding: 70px 0 40px;
            text-align: center;
        }

        .badge {
            display: inline-block;
            background: rgba(249,115,22,.14);
            color: #fb923c;
            border: 1px solid rgba(249,115,22,.35);
            padding: 8px 14px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 18px;
        }

        h1 {
            font-size: clamp(38px, 6vw, 76px);
            line-height: .95;
            margin: 0;
            font-weight: 900;
        }

        .subtitle {
            max-width: 820px;
            margin: 22px auto 0;
            color: #cbd5e1;
            font-size: 19px;
            line-height: 1.55;
        }

        .catalog {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 24px;
            margin-top: 42px;
        }

        .card {
            background: rgba(15,23,42,.92);
            border: 1px solid #1e293b;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 28px 70px rgba(0,0,0,.35);
            transition: .22s;
        }

        .card:hover {
            transform: translateY(-5px);
            border-color: rgba(249,115,22,.65);
        }

        .screenshot {
            min-height: 230px;
            background:
                linear-gradient(135deg, rgba(249,115,22,.22), rgba(15,23,42,.95)),
                #111827;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            font-weight: bold;
            text-align: center;
            padding: 20px;
        }

        .screenshot img {
            width: 100%;
            height: 260px;
            object-fit: cover;
            display: block;
        }

        .content {
            padding: 24px;
        }

        .content h2 {
            margin: 0;
            font-size: 28px;
        }

        .rating {
            color: #fbbf24;
            font-size: 20px;
            margin-top: 8px;
            letter-spacing: 2px;
        }

        .functional {
            color: #94a3b8;
            font-size: 14px;
            margin-top: 4px;
            font-weight: bold;
        }

        .content p {
            color: #cbd5e1;
            line-height: 1.55;
        }
        
        .features {
            display: grid;
            gap: 8px;
            margin: 18px 0;
            color: #e2e8f0;
            font-size: 15px;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 22px;
        }

        .btn {
            text-decoration: none;
            color: white;
            background: #f97316;
            padding: 12px 16px;
            border-radius: 14px;
            font-weight: bold;
            display: inline-flex;
        }

        .btn.secondary {
            background: #111827;
            border: 1px solid #334155;
        }

        .demo-box {
            margin-top: 16px;
            background: rgba(2,6,23,.72);
            border: 1px solid #1e293b;
            border-radius: 16px;
            padding: 14px;
            color: #cbd5e1;
            font-size: 14px;
            line-height: 1.6;
        }

        .coming {
            margin-top: 32px;
            background: rgba(249,115,22,.10);
            border: 1px solid rgba(249,115,22,.32);
            border-radius: 24px;
            padding: 26px;
            text-align: center;
            color: #fed7aa;
        }

        .footer {
            border-top: 1px solid #1e293b;
            margin-top: 60px;
            padding: 28px 0;
            text-align: center;
            color: #94a3b8;
            font-size: 14px;
        }

        @media (max-width: 640px) {
            .topbar-inner {
                justify-content: center;
                gap: 14px;
            }

            .brand span {
                display: none;
            }

            .hero {
                padding-top: 42px;
            }

            .screenshot img {
                height: 210px;
            }
        }
    </style>
</head>

<body>

<header class="topbar">
    <div class="topbar-inner">
        <div class="brand">
            <img src="{{ asset('images/logo-mctandil-transparent.png') }}" alt="MCTandil">
            <span>MCTandil</span>
        </div>

        <a href="{{ route('home') }}" class="back">← Volver al inicio</a>
    </div>
</header>

<main class="container">

    <section class="hero">
        <div class="badge">🚀 Modelos en funcionamiento</div>

        <h1>Soluciones SaaS de MCTandil</h1>

        <p class="subtitle">
            Sistemas reales desarrollados para digitalizar negocios, profesionales y organizaciones.
            Probá nuestras demos y conocé cómo podemos adaptar una solución a tu proyecto.
        </p>
    </section>

    <section class="catalog">

        <article class="card">
            <div class="screenshot">
                <span>Acá va captura real de DemoGym</span>
            </div>

            <div class="content">
                <h2>🏋️ DemoGym</h2>
                
                <div class="rating">⭐⭐⭐⭐⭐</div>
                <div class="functional">Modelo funcional</div>

                <p>
                    Plataforma para gimnasios, clubes y espacios deportivos.
                    Gestión de socios, cuotas, reservas, asistencias y acceso por QR.
                </p>

                <div class="features">
                    <div>✔ Gestión de socios</div>
                    <div>✔ Control de cuotas</div>
                    <div>✔ Reservas online</div>
                    <div>✔ QR de acceso</div>
                    <div>✔ Mercado Pago</div>
                    <div>✔ Panel para socios</div>
                </div>

                <div class="demo-box">
                    <strong>Usuario demo:</strong><br>
                    demo@gimnasio.com<br>
                    <strong>Contraseña:</strong><br>
                    demo1234
                </div>

                <div class="actions">
                    <a class="btn" href="https://app-abogados-production.up.railway.app/estudio/demo" target="_blank">
                        Ver Demo
                    </a>

                    <a class="btn secondary" href="#">
                        Ver Video
                    </a>

                    <a class="btn secondary" href="https://wa.me/5492494631299?text=Hola,%20quiero%20informaci%C3%B3n%20sobre%20DemoGym" target="_blank">
                        Solicitar información
                    </a>
                </div>
            </div>
        </article>

        <article class="card">
            <div class="screenshot">
                <span>Acá va captura real de Demo Abogados</span>
            </div>

            <div class="content">
                <h2>⚖️ Abogados</h2>
                
                <div class="rating">⭐⭐⭐⭐⭐</div>
                <div class="functional">Modelo funcional</div>

                <p>
                    Sistema para estudios jurídicos, profesionales independientes y equipos legales.
                    Organiza clientes, expedientes, tareas, documentos y comunicación.
                </p>

                <div class="features">
                    <div>✔ Gestión de clientes</div>
                    <div>✔ Expedientes</div>
                    <div>✔ Seguimientos</div>
                    <div>✔ Chat interno</div>
                    <div>✔ Documentos</div>
                    <div>✔ Portal para clientes</div>
                </div>

                <div class="demo-box">
                    <strong>Usuario demo:</strong><br>
                    demo@abogados.com<br>
                    <strong>Contraseña:</strong><br>
                    demo1234
                </div>

                <div class="actions">
                    <a class="btn" href="#" target="_blank">
                        Ver Demo
                    </a>

                    <a class="btn secondary" href="#">
                        Ver Video
                    </a>

                    <a class="btn secondary" href="https://wa.me/5492494631299?text=Hola,%20quiero%20informaci%C3%B3n%20sobre%20la%20app%20para%20abogados" target="_blank">
                        Solicitar información
                    </a>
                </div>
            </div>
        </article>

    </section>

    <section class="coming">
        <h2>🚧 Próximamente más modelos</h2>
        <p>
            También desarrollamos sistemas a medida para turnos, clubes, comercios,
            IoT, automatización y proyectos especiales.
        </p>
    </section>

</main>

<footer class="container footer">
    © {{ date('Y') }} MCTandil · Soluciones SaaS · Apps · Desarrollo a medida.
</footer>

</body>
</html>