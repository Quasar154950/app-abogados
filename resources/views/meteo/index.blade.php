<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estación Meteorológica | MCTandil</title>

    <style>
        body{
            margin:0;
            background:#020617;
            color:white;
            font-family:Arial,sans-serif;
            display:flex;
            justify-content:center;
            align-items:center;
            min-height:100vh;
            text-align:center;
        }

        .card{
            max-width:700px;
            padding:40px;
            background:#0f172a;
            border:1px solid #1e293b;
            border-radius:24px;
        }

        h1{
            color:#fb923c;
            margin-bottom:20px;
        }

        p{
            color:#cbd5e1;
            line-height:1.6;
        }

        a{
            display:inline-block;
            margin-top:20px;
            background:#f97316;
            color:white;
            text-decoration:none;
            padding:12px 20px;
            border-radius:12px;
            font-weight:bold;
        }
    </style>
</head>
<body>

<div class="card">

    <h1>🌦️ Estación Meteorológica MCTandil</h1>

    <p>
        Próximamente podrás consultar datos meteorológicos en tiempo real,
        información astronómica, registros históricos y sensores IoT
        desarrollados por MCTandil.
    </p>

    <a href="{{ route('home') }}">
        ← Volver a MCTandil
    </a>

</div>

</body>
</html>
