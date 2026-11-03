<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Si no está logueado, directo al login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Si el id_rol del usuario coincide con los roles permitidos en la ruta, continúa
        if (in_array((string)$user->id_rol, $roles)) {
            return $next($request);
        }

        if ($user->id_rol == 3) {
            return redirect()->route('superadmin.dashboard');
        } elseif ($user->id_rol == 1) {
            return redirect()->route('admin.productos');
        } elseif ($user->id_rol == 2) {
            return redirect()->route('empleado.pos');
        }

        return redirect()->route('login');
    }
}