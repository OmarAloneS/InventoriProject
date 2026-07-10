<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Vinculación al CSS externo -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <script src="https://www.google.com/recaptcha/enterprise.js" async defer></script>
</head>
<body>

    <div class="auth-container">
        <form id="loginForm" method="POST" action="{{ route('login') }}" novalidate>
            @csrf

            <h2>Iniciar Sesión</h2>

            @if(session('success'))
                <span class="success-msg">{{ session('success') }}</span>
            @endif

            @if($errors->any())
                <div style="margin-bottom: 15px;">
                    @foreach ($errors->all() as $error)
                        <span class="error-msg">{{ $error }}</span>
                    @endforeach
                </div>
            @endif

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="usuario@correo.com">
            </div>

            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="password" required placeholder="••••••••">
            </div>

            <!-- Contenedor del reCAPTCHA -->
           <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>

            <button type="submit">Ingresar</button>

            <div class="link-container">
                <a href="{{ route('register') }}">¿No tienes cuenta? Regístrate aquí</a>
            </div>
        </form>
    </div>

    <!-- El Javascript de validaciones se puede quedar aquí al final, o si lo prefieres estricto en un archivo externo, me dices y también lo mandamos a public/js/ -->
    <script>
        const form = document.getElementById('loginForm');
        form.addEventListener('submit', function (e) {
            const email = form.email.value.trim();
            const password = form.password.value;
            const captcha = document.querySelector('#g-recaptcha-response') ? document.querySelector('#g-recaptcha-response').value : '';

            if (!email || !password) {
                alert('Todos los campos son obligatorios.');
                e.preventDefault();
                return;
            }

            if (!captcha) {
                alert('Por favor, completa el CAPTCHA.');
                e.preventDefault();
            }
        });
    </script>
</body>
</html>