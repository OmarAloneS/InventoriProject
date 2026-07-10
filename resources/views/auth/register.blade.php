<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Requerimientos Estrictos</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <style>
        .error-msg { color: red; font-size: 0.85rem; margin-top: 4px; display: block; }
        .info-box { background: #f0f4f8; padding: 10px; border-radius: 5px; font-size: 0.8rem; margin-bottom: 15px; color: #334e68; }
    </style>
</head>
<body class="login-body">

    <form class="login-form" method="POST" action="{{ route('register') }}" novalidate>
        @csrf

        <h2>Crear Cuenta</h2>

        <div class="info-box">
            <strong>Requisitos de la contraseña:</strong>
            <ul>
                <li>Mínimo 8 caracteres</li>
                <li>Al menos una mayúscula y una minúscula</li>
                <li>Al menos un carácter especial</li>
                <li>Sin números consecutivos (ej. 12, 89)</li>
                <li>Sin letras consecutivas del abecedario (ej. ab, xy)</li>
            </ul>
        </div>

        <label>Nombre Completo</label>
        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Tu nombre">
        @error('name') <span class="error-msg">{{ $message }}</span> @enderror

        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required placeholder="correo@ejemplo.com">
        @error('email') <span class="error-msg">{{ $message }}</span> @enderror

        <label>Contraseña</label>
        <input type="password" name="password" required placeholder="••••••••">
        @error('password') <span class="error-msg">{{ $message }}</span> @enderror

        <label>Confirmar Contraseña</label>
        <input type="password" name="password_confirmation" required placeholder="••••••••">

        <button type="submit">Registrar Cuenta</button>

        <div style="margin-top: 15px; text-align: center;">
            <a href="{{ route('login') }}">¿Ya tienes cuenta? Inicia sesión</a>
        </div>
    </form>

</body>
</html>