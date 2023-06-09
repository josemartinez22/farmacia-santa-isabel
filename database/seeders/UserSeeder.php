<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $user = User::create([
            'first_name' => 'Administrador',
            'last_name' => 'Administrador',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin@admin.com'),
            'status' => true,
            'rol_name' => 'Administrador'
        ]);

        $user->syncRoles('Administrador');

        $user = User::create([
            'first_name' => 'Encargado',
            'last_name' => 'Encargado',
            'email' => 'encargado@encargado.com',
            'password' => bcrypt('encargado@encargado.com'),
            'status' => true,
            'rol_name' => 'Encargado'
        ]);

        $user->syncRoles('Encargado');

        $user = User::create([
            'first_name' => 'Ventas',
            'last_name' => 'Ventas',
            'email' => 'ventas@ventas.com',
            'password' => bcrypt('ventas@ventas.com'),
            'status' => true,
            'rol_name' => 'Ventas'
        ]);

        $user->syncRoles('Ventas');
    }
}
