<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso al sistema</title>
</head>
<body style="margin:0; font-family: Arial, sans-serif; background:#f5f5f5; min-height:100vh; display:flex; align-items:center; justify-content:center;">

    <div style="background:white; width:100%; max-width:400px; padding:32px; border-radius:18px; text-align:center; box-shadow:0 10px 30px rgba(0,0,0,.08);">

        <!-- LOGO -->
        <img src="{{ asset($userEstudio->logo_estudio ?: 'images/logo.png') }}"
             style="width:80px; height:80px; object-fit:cover; border-radius:50%; margin-bottom:16px;">

        <!-- NOMBRE -->
        <h1 style="margin:0; font-size:22px;">
            {{ $userEstudio->nombre_estudio ?? 'Acceso al sistema' }}
        </h1>

        <p style="font-size:14px; color:#666; margin-bottom:24px;">
            Ingresá con tus datos
        </p>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- EMAIL -->
            <input type="email" name="email" placeholder="Correo electrónico" required
                   style="width:100%; padding:12px; margin-bottom:12px; border:1px solid #ddd; border-radius:10px; box-sizing:border-box;">

            <!-- PASSWORD CON OJITO -->
            <div style="position:relative; margin-bottom:16px;">
                <input type="password" id="password" name="password" placeholder="Contraseña" required
                       style="width:100%; padding:12px; padding-right:40px; border:1px solid #ddd; border-radius:10px; box-sizing:border-box;">

                <span onclick="togglePassword()" 
                      style="position:absolute; right:10px; top:50%; transform:translateY(-50%); cursor:pointer; font-size:16px;">
                    👁️
                </span>
            </div>

            <!-- BOTÓN -->
            <button type="submit"
                    style="width:100%; padding:12px; border:none; border-radius:10px; background:#111827; color:white; font-weight:bold; cursor:pointer;">
                Ingresar
            </button>

        </form>

    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>

</body>
</html>
