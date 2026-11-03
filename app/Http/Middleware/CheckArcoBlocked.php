<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckArcoBlocked
{
    /**
     * Maneja una solicitud entrante.
     */
    public function handle(Request $request, Closure $next)
    {
        // Si el usuario está logueado y su estado en la BD es bloqueado por ARCO
        if (Auth::check() && Auth::user()->status === 'bloqueado_arco') {
            
            Auth::logout(); // Le cerramos la sesión de inmediato

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Lo mandamos de regreso al login con un mensaje explicativo
            return redirect()->route('login')->withErrors([
                'email' => 'Tu cuenta ha sido bloqueada temporalmente debido a la resolución de tu solicitud de derechos ARCO.'
            ]);
        }

        return $next($request);
    }
}