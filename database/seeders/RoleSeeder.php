<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Crear roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // Crear usuario administrador
        $admin = User::firstOrCreate(
            ['email' => 'admin@demo.com'],
            [
                'name' => 'Admin Demo',
                'password' => bcrypt('12345678'),
                'is_admin' => true
            ]
        );
        $admin->assignRole($adminRole);

        // Crear usuario regular
        $user = User::firstOrCreate(
            ['email' => 'usuario@demo.com'],
            [
                'name' => 'Usuario Demo',
                'password' => bcrypt('12345678')
            ]
        );
        $user->assignRole($userRole);
    }
}
