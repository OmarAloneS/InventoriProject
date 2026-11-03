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

        // En lugar de usar ->route('dashboard'), usamos back() 
        // para que recargue la misma página donde estaba, pero ya con el modal oculto.
        return redirect()->back()->with('status', 'Aviso de privacidad aceptado.');
    }
}