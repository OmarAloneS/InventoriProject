<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\ValidarPasswordEstricto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
    public function showLogin() { return view('auth.login'); }
    public function showRegister() { return view('auth.register'); }

    public function register(Request $request)
    {
        // Validaciones sanitizadas y protegidas
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', new ValidarPasswordEstricto],
        ]);

        $user = User::create([
            'name' => strip_tags($request->name),
            'email' => filter_var($request->email, FILTER_SANITIZE_EMAIL),
            'password' => Hash::make($request->password),
            'id_rol' => 2,
            'status' => 'inactivo', // <-- Único cambio: define la cuenta inactiva hasta verificar correo
        ]);

        event(new Registered($user));

        return redirect()->route('login')->with('success', 'Registro exitoso. Revisa tu correo para activar tu cuenta.');
    }

    // Procesar el Login
    public function login(Request $request)
    {
        // Creamos la llave única usando una estructura limpia (usando Str::lower es más seguro)
        $key = strtolower($request->input('email')) . '|' . $request->ip();

        // 1. REVISAR SI YA EXCEDIÓ LOS INTENTOS
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'email' => "Demasiados intentos fallidos. Cuenta bloqueada temporalmente. Intenta de nuevo en $seconds segundos."
            ])->withInput($request->only('email'));
        }

        // 2. Validar campos básicos del formulario
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'g-recaptcha-response' => 'required'
        ], [
            'g-recaptcha-response.required' => 'Por favor, verifica que no eres un robot.'
        ]);

        // 3. VERIFICACIÓN DE GOOGLE reCAPTCHA (Optimizado para Docker/IPv4)
        $secret = config('services.recaptcha.secret_key');
        $response = Http::asForm()
            ->withOptions([
                'curl' => [
                    CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
                ],
                'connect_timeout' => 3,
                'timeout' => 5,
            ])
            ->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secret,
                'response' => $request->input('g-recaptcha-response'),
                'remoteip' => $request->ip(),
            ]);

        if (!$response->json()['success']) {
            return back()->withErrors(['captcha' => 'Error en la verificación de reCAPTCHA.'])->withInput($request->only('email'));
        }

        // 4. Intento de autenticación segura
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // OBTENEMOS EL USUARIO AUTENTICADO
            $user = Auth::user();

            // VALIDACIÓN DE CORREO VERIFICADO Y ESTATUS DE CUENTA
            if (is_null($user->email_verified_at) || $user->status !== 'activo') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Debes confirmar tu correo electrónico antes de iniciar sesión. Revisa tu bandeja de entrada.'
                ])->withInput($request->only('email'));
            }

            // Si las credenciales son correctas, limpiamos el candado
            RateLimiter::clear($key);

            // Session Fixation Protection
            $request->session()->regenerate();

            // Redirección inteligente según el id_rol
            if ($user->id_rol == 3) {
                // Si es Superadmin, lo mandamos directo al catálogo de usuarios
                return redirect()->route('usuarios.index');

            }elseif ($user->id_rol == 1) {
                // Administrador -> Recepción e Inventario
                return redirect()->route('admin.recepcion.index'); 

            } elseif ($user->id_rol == 2) {
                // Empleado Mostrador -> Punto de Venta 
                return redirect()->route('empleado.pos');
            }
            
            return redirect()->intended('/dashboard');
        }

        // 5. Si falla el login, sumamos un intento al limitador
        RateLimiter::hit($key, 60); // Bloqueo por 60 segundos al llegar a 3 fallos

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros. (Intento ' . RateLimiter::attempts($key) . ' de 3)',
        ])->withInput($request->only('email'));
    }

    // Cerrar sesión
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}