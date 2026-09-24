<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Inyectar los roles del sistema
        DB::table('roles')->insert([
            ['id' => '01', 'nombre' => 'Administrador', 'created_at' => now(), 'updated_at' => now()],
            ['id' => '02', 'nombre' => 'Modulador', 'created_at' => now(), 'updated_at' => now()],
            ['id' => '03', 'nombre' => 'Usuario', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 2. Inyectar el Superadmin inicial
        User::create([
            'name' => 'Administrador',
            'email' => 'lunamartinezfatimaneftali@gmail.com',
            'password' => Hash::make('admin12345'),
            'role_id' => '01', 
        ]);
    }
}
