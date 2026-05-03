<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cuenta suspendida</title>
</head>
<body style="margin:0; font-family: Arial, sans-serif; background:#f5f5f5; display:flex; align-items:center; justify-content:center; min-height:100vh;">

    <div style="background:white; max-width:420px; padding:32px; border-radius:18px; text-align:center; box-shadow:0 10px 30px rgba(0,0,0,.08);">
        <h1 style="margin-top:0; color:#b91c1c;">Cuenta suspendida</h1>

        <p style="font-size:16px; color:#444;">
            El acceso a esta cuenta se encuentra suspendido temporalmente.
        </p>

        <p style="font-size:14px; color:#666;">
            Para regularizar el servicio, comunicate con el administrador.
        </p>

        <!-- BOTÓN LOGOUT -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button style="margin-top:20px; padding:10px 18px; border:none; border-radius:8px; background:#b91c1c; color:white; font-weight:bold; cursor:pointer;">
                Cerrar sesión
            </button>
        </form>

    </div>

</body>
</html>
