<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvisoPrivacidadController extends Controller
{
    /**
     * Procesa y registra la aceptación del aviso de privacidad.
     */
    public function aceptar(Request $request)
    {
        // Validar que el checkbox venga marcado en la petición
        $request->validate([
            'aceptar' => 'required',
        ]);

        // Obtener al usuario que tiene la sesión activa
        $user = Auth::user();

        // Cambiamos el estatus a 1 (Leído y Aceptado)
        $user->acepto_aviso = 1;
        $user->save();

        // Lo redireccionamos al dashboard con un mensaje de éxito silencioso
        return redirect()->route('dashboard')->with('success', 'Aviso de privacidad registrado correctamente.');
    }
}