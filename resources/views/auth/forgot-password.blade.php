<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - Inventori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center min-vh-100">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-dark text-white text-center py-3">
                        <h5 class="mb-0">Recuperar Contraseña</h5>
                    </div>
                    <div class="card-body p-4">
                        @if (session('status'))
                            <div class="alert alert-success small">{{ session('status') }}</div>
                        @endif
                        
                        @if (session('success'))
                            <div class="alert alert-success small">{{ session('success') }}</div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger small">
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label text-muted small fw-bold">Correo Electrónico registrado</label>
                                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required autofocus placeholder="tu.correo@ejemplo.com">
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-2">Enviar Enlace de Restablecimiento</button>
                        </form>
                        
                        <div class="text-center mt-3">
                            <a href="{{ route('login') }}" class="text-decoration-none small">Volver al inicio de sesión</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>