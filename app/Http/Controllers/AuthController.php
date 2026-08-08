<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\ValidarPasswordEstricto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;

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

        User::create([
            'name' => strip_tags($request->name),
            'email' => filter_var($request->email, FILTER_SANITIZE_EMAIL),
            'password' => Hash::make($request->password),
            'id_rol' => 2,
        ]);

        return redirect()->route('login')->with('success', 'Registro exitoso. Espera a que un administrador valide tu rol.');
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

        // 3. VERIFICACIÓN DE GOOGLE reCAPTCHA
        $secret = env('RECAPTCHA_SECRET_KEY', '');
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
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
            // Si las credenciales son correctas, limpiamos el candado
            RateLimiter::clear($key);

            // Session Fixation Protection
            $request->session()->regenerate();

            // OBTENEMOS EL USUARIO AUTENTICADO
            $user = Auth::user();

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