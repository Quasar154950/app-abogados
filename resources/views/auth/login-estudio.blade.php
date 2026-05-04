<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso al sistema</title>
</head>
<body style="margin:0; font-family: Arial, sans-serif; background:#f5f5f5; min-height:100vh; display:flex; align-items:center; justify-content:center;">

    <div style="background:white; width:100%; max-width:420px; padding:32px; border-radius:18px; text-align:center; box-shadow:0 10px 30px rgba(0,0,0,.08);">

        <img src="{{ asset($userEstudio->logo_estudio ?: 'images/logo.png') }}"
             style="width:80px; height:80px; object-fit:cover; border-radius:50%; margin-bottom:16px;">

        <h1 style="margin:0; font-size:24px;">
            {{ $userEstudio->nombre_estudio ?? 'Acceso al sistema' }}
        </h1>

        <p style="font-size:14px; color:#666; margin-bottom:24px;">
            Acceso al sistema
        </p>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <input type="email" name="email" placeholder="Correo electrónico" required
                   style="width:100%; padding:12px; margin-bottom:12px; border:1px solid #ddd; border-radius:10px; box-sizing:border-box;">

            <input type="password" name="password" placeholder="Contraseña" required
                   style="width:100%; padding:12px; margin-bottom:16px; border:1px solid #ddd; border-radius:10px; box-sizing:border-box;">

            <button type="submit"
                    style="width:100%; padding:12px; border:none; border-radius:10px; background:#111827; color:white; font-weight:bold; cursor:pointer;">
                Ingresar
            </button>
        </form>
    </div>

</body>
</html>
