<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

// 1. RUTAS DE INVITADOS (Solo entran si NO están logueados)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    // Le dejamos el middleware de throttle si quieres mantener el control estricto de intentos
    Route::post('/login', [AuthController::class, 'login']); 

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// 2. RUTAS PROTEGIDAS GENERALES (Cualquier usuario logueado entra aquí)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Tu vista temporal general
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
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
    
    // Ruta para procesar la aceptación del aviso de privacidad
    Route::post('/aviso-privacidad/aceptar', [App\Http\Controllers\AvisoPrivacidadController::class, 'aceptar'])->name('aviso.aceptar')->middleware('auth');
});