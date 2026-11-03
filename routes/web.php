<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecepcionController;
use App\Http\Controllers\EmpleadoController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;

// 1. RUTAS DE INVITADOS (Solo entran si NO están logueados)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']); 
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // --- RUTAS DE RECUPERACIÓN DE CONTRASEÑA ---
    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');

    Route::post('/forgot-password', function (Request $request) {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
                    ? back()->with('success', '¡Te hemos enviado por correo el enlace para restablecer tu contraseña!')
                    : back()->withErrors(['email' => __($status)]);
    })->name('password.email');

    Route::get('/reset-password/{token}', function (string $token) {
        return view('auth.reset-password', ['token' => $token]);
    })->name('password.reset');

    Route::post('/reset-password', function (Request $request) {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed', new \App\Rules\ValidarPasswordEstricto],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('success', '¡Tu contraseña ha sido restablecida con éxito!')
                    : back()->withErrors(['email' => __($status)]);
    })->name('password.update');

    Route::get('/redirect-reset/{token}', function (Illuminate\Http\Request $request, $token) {
        $email = $request->query('email');
        $ipLocal = '192.168.0.14';
        
        $pathReset = "/inventori/public/reset-password/{$token}?email=" . urlencode($email);
        
        return "
        <!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Redirigiendo...</title>
        </head>
        <body style='font-family: sans-serif; text-align: center; padding-top: 50px;'>
            <p>Cargando formulario de restablecimiento...</p>
            <script>
                var path = '{$pathReset}';
                var userAgent = navigator.userAgent || navigator.vendor || window.opera;
                var isMobile = /android|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(userAgent);
                var targetUrl = '';

                // Si NO es un dispositivo móvil, asumimos que es la computadora y usamos HTTPS localhost
                if (!isMobile) {
                    targetUrl = 'https://localhost' + path;
                } else {
                    // Si es un celular o Tablet, usamos la IP por HTTP
                    targetUrl = 'http://{$ipLocal}' + path;
                }

                window.location.href = targetUrl;
            </script>
        </body>
        </html>
        ";
    })->name('password.bridge');
});

// --- RUTAS DE VERIFICACIÓN DE CORREO (Públicas / Puente) ---

// Vista informativa de aviso de verificación enviado
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->name('verification.notice');

// RUTA PUENTE: Detecta si es Laptop (HTTPS localhost) o Móvil (HTTP IP)
Route::get('/redirect-verify/{id}/{hash}', function (Request $request, $id, $hash) {
    $ipLocal = '192.168.0.14';
    $pathVerify = "/inventori/public/email/verify/{$id}/{$hash}";

    return "
    <!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Redirigiendo activación...</title>
    </head>
    <body style='font-family: sans-serif; text-align: center; padding-top: 50px;'>
        <p>Verificando y redirigiendo...</p>
        <script>
            var path = '{$pathVerify}';
            var userAgent = navigator.userAgent || navigator.vendor || window.opera;
            var isMobile = /android|iphone|ipad|ipod|blackberry|iemobile|opera mini/i.test(userAgent);
            var targetUrl = '';

            if (!isMobile) {
                targetUrl = 'https://localhost' + path;
            } else {
                targetUrl = 'http://{$ipLocal}' + path;
            }

            window.location.href = targetUrl;
        </script>
    </body>
    </html>
    ";
})->name('verification.bridge');

// PROCESA LA VERIFICACIÓN Y ACTIVACIÓN (Sin middleware signed para evitar conflicto de IP/Localhost)
Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
    $user = \App\Models\User::findOrFail($id);

    // Valida que el hash coincida con el correo del usuario registrado
    if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        return redirect()->route('login')->withErrors(['email' => 'El enlace de verificación no es válido o ha sido alterado.']);
    }

    // Confirms correo y ACTIVA la cuenta
    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        $user->status = 'activo';
        $user->save();
    }

    return redirect()->route('login')->with('success', '¡Correo verificado con éxito! Ya puedes iniciar sesión.');
})->name('verification.verify');

// Reenviar correo de verificación
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('success', '¡Enlace de verificación enviado!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


// 2. RUTAS PROTEGIDAS GENERALES (Cualquier usuario logueado entra aquí)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // <-- AGREGA ESTA RUTA AQUí -->
    Route::get('/productos', function () {
        return 'Vista de productos'; 
    })->name('productos.index');

    Route::post('/arco/solicitar', [UsuarioController::class, 'solicitarArco'])->name('arco.solicitar');

    Route::post('/aviso-privacidad/aceptar', [App\Http\Controllers\AvisoPrivacidadController::class, 'aceptar'])->name('aviso.aceptar');
});

// GRUPO PROTEGIDO: Solo Administrador (id_rol = 1)
Route::middleware(['auth', 'role:1'])->group(function () {
    Route::get('/admin/recepcion', [RecepcionController::class, 'index'])->name('admin.recepcion.index');
    Route::post('/admin/recepcion', [RecepcionController::class, 'store'])->name('admin.recepcion.store');
    Route::put('/recepcion/{id}', [RecepcionController::class, 'update'])->name('admin.recepcion.update');
    Route::delete('/recepcion/{id}', [RecepcionController::class, 'destroy'])->name('admin.recepcion.destroy');
});

// GRUPO PROTEGIDO: Solo Empleado (id_rol = 2)
Route::middleware(['auth', 'role:2'])->prefix('empleado')->group(function () {
    Route::get('/pos', [EmpleadoController::class, 'index'])->name('empleado.pos');
    Route::post('/pos/venta', [EmpleadoController::class, 'store'])->name('empleado.pos.store');
});

// 3. GRUPO PROTEGIDO POR ROL: Solo usuarios autenticados con id_rol = 3 (Superadmin)
Route::middleware(['auth', 'role:3'])->prefix('superadmin')->group(function () {
    
    // Panel principal del Superadmin
    Route::get('/dashboard', [AuthController::class, 'showSuperAdminDashboard'])->name('superadmin.dashboard');
    
    // CRUD del Catálogo de Usuarios
    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');          // Listar
    Route::get('/usuarios/crear', [UsuarioController::class, 'create'])->name('usuarios.create');    // Formulario Crear
    Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');          // Guardar
    Route::get('/usuarios/{id}/editar', [UsuarioController::class, 'edit'])->name('usuarios.edit');  // Formulario Editar
    Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');    // Actualizar
    Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');// Eliminar 
    Route::post('/arco/aprobar/{id}', [UsuarioController::class, 'aprobarArco'])->name('superadmin.arco.aprobar');
    Route::post('/arco/rechazar/{id}', [UsuarioController::class, 'rechazarArco'])->name('superadmin.arco.rechazar');
    Route::post('/arco/reactivar/{id}', [UsuarioController::class, 'reactivarArco'])->name('superadmin.arco.reactivar');
});