<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    // 1. Listar todos los usuarios en una tabla
    public function index()
    {
        $usuarios = User::all();
        // Retorna la vista pasando la colección de usuarios
        return view('superadmin.usuarios.index', compact('usuarios'));
    }

    // 2. Mostrar el formulario de creación
    public function create()
    {
        return view('superadmin.usuarios.create');
    }

    // 3. Guardar el nuevo usuario en la base de datos
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'id_rol' => 'required|in:1,2,3', // Valida que solo sean los roles existentes
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'id_rol' => $request->id_rol,
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado exitosamente.');
    }

    // --- AGREGA ESTA FUNCIÓN PARA MOSTRAR EL FORMULARIO DE EDICIÓN ---
    public function edit($id)
    {
        // Buscamos al usuario por su ID en la base de datos
        // Si no existe, lanza un error 404 (página no encontrada) automáticamente
        $usuario = User::findOrFail($id);

        // Retornamos la vista 'edit.blade.php' que ya creamos antes, pasándole los datos del usuario
        return view('superadmin.usuarios.edit', compact('usuario'));
    }

    // --- AGREGA ESTA FUNCIÓN PARA PROCESAR LOS CAMBIOS DE LA EDICIÓN ---
    public function update(Request $request, $id)
    {
        // 1. Buscamos al usuario
        $usuario = User::findOrFail($id);

        // 2. Validamos los datos recibidos del formulario
        $request->validate([
            'name' => 'required|string|max:255',
            // unique:users,email,[id] -> permite guardar el mismo correo si no se cambió
            'email' => 'required|string|email|max:255|unique:users,email,' . $usuario->id,
            // nullable -> permite dejar la contraseña en blanco si no se quiere cambiar
            'password' => 'nullable|string|min:8|confirmed',
            'id_rol' => 'required|in:1,2,3',
        ]);

        // 3. Actualizamos los campos básicos
        $usuario->name = $request->name;
        $usuario->email = $request->email;
        $usuario->id_rol = $request->id_rol;

        // 4. Solo actualizamos la contraseña si el Superadmin escribió algo
        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->password);
        }

        // 5. Guardamos los cambios en la base de datos
        $usuario->save();

        // 6. Redirigimos a la tabla principal con un mensaje de éxito
        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    // --- FUNCIÓN PARA ELIMINAR EL USUARIO DE LA BASE DE DATOS ---
    public function destroy($id)
    {
        // 1. Buscamos al usuario por su ID
        $usuario = User::findOrFail($id);
        
        // 2. Lo borramos de la tabla
        $usuario->delete();

        // 3. Recargamos la tabla con el mensaje de éxito
        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente del sistema.');
    }
}