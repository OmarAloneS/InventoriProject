<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsuariosPruebaSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear el Superadmin (id_rol = 3)
        User::create([
            'name' => 'Super Administrador',
            'email' => 'superadmin@inventori.com',
            'password' => Hash::make('Password@123'),
            'id_rol' => 3
        ]);

        // 2. Crear el Admin (id_rol = 1)
        User::create([
            'name' => 'Admin Proyecto',
            'email' => 'admin@inventori.com',
            'password' => Hash::make('Password@123'),
            'id_rol' => 1
        ]);

        // 3. Crear el Empleado (id_rol = 2)
        User::create([
            'name' => 'Empleado Mostrador',
            'email' => 'empleado@inventori.com',
            'password' => Hash::make('Password@123'),
            'id_rol' => 2
        ]);
    }
}