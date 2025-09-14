<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Ejecutar seeder de roles y usuarios
        $this->call([
            RoleSeeder::class,
        ]);
    }
}
