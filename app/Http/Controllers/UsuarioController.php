<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    // Listar usuarios filtrando los bloqueados por ARCO
    public function index(Request $request)
    {
        $verBloqueados = $request->query('ver_bloqueados', 0);

        $query = User::query();

        if (!$verBloqueados) {
            $query->where('status', '!=', 'bloqueado_arco')
                  ->orWhereNull('status');
        }

        $usuarios = $query->get();

        return view('superadmin.usuarios.index', compact('usuarios', 'verBloqueados'));
    }

    // Mostrar el formulario de creación
    public function create()
    {
        return view('superadmin.usuarios.create');
    }

    // Guardar el nuevo usuario en la base de datos
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'id_rol' => 'required|in:1,2,3',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'id_rol' => $request->id_rol,
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado exitosamente.');
    }

    // Mostrar el formulario de edición
    public function edit($id)
    {
        $usuario = User::findOrFail($id);

        return view('superadmin.usuarios.edit', compact('usuario'));
    }

    // Guardar cambios del formulario de edición
    public function update(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $usuario->id,
            'password' => 'nullable|string|min:8|confirmed',
            'id_rol' => 'required|in:1,2,3',
        ]);

        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->id_rol = $request->id_rol;

        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->password);
        }

        $usuario->save();

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    // Eliminar el usuario de la base de datos
    public function destroy($id)
    {
        $usuario = User::findOrFail($id);
        
        $usuario->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario de baja del sistema.');
    }

    // Solicitar derecho ARCO desde la cuenta del usuario
    public function solicitarArco(Request $request)
    {
        $user = auth()->user();

        $user->update([
            'status' => 'solicitado_arco',
            'arco_requested_at' => now(),
        ]);

        return redirect()->back()->with('arco_enviado', 'Tu solicitud de derechos ARCO ha sido enviada con éxito.');
    }

    // Aprobar solicitud y bloquear usuario
    public function aprobarArco($id)
    {
        $usuario = User::findOrFail($id);
        
        $usuario->update([
            'status' => 'bloqueado_arco'
        ]);

        return redirect()->route('usuarios.index')->with('success', "La solicitud ARCO de {$usuario->name} ha sido aprobada. Su cuenta ahora está bloqueada.");
    }

    // Rechazar la solicitud ARCO y activar usuario
    public function rechazarArco($id)
    {
        $usuario = User::findOrFail($id);
        
        $usuario->update([
            'status' => 'activo' 
        ]);

        return redirect()->route('usuarios.index')->with('status', "La solicitud ARCO de {$usuario->name} ha sido rechazada.");
    }

    // Reactivar usuario bloqueado por ARCO
    public function reactivarArco($id)
    {
        $usuario = User::findOrFail($id);
        
        $usuario->update([
            'status' => 'activo'
        ]);

        return redirect()->route('usuarios.index')->with('success', "La cuenta de {$usuario->name} ha sido reactivada con éxito.");
    }
}