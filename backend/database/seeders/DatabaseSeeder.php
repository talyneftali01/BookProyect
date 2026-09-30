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
            ['id' => '01', 'name' => 'Administrador', 'created_at' => now(), 'updated_at' => now()],
            ['id' => '02', 'name' => 'Modulador', 'created_at' => now(), 'updated_at' => now()],
            ['id' => '03', 'name' => 'Usuario', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 2. Inyectar el Superadmin inicial
        User::create([
            'name' => 'Administrador',
            'email' => 'lunamartinezfatimaneftali@gmail.com',
            'password' => Hash::make('admin12345'),
            'role_id' => '01', 
        ]);
        DB::table('geners')->insert(
            [
                ['name'=> 'Ficción','created_at' => now(), 'updated_at'=> now()],
                ['name'=> 'Romantico','created_at' => now(), 'updated_at'=> now()],
                ['name'=> 'Aventura','created_at' => now(), 'updated_at'=> now()],
                ['name'=> 'Historia','created_at' => now(), 'updated_at'=> now()],
                ['name'=> 'Terror','created_at' => now(), 'updated_at'=> now()],
                ['name'=> 'Poesia','created_at' => now(), 'updated_at'=> now()],
                ['name'=> 'Infantil','created_at' => now(), 'updated_at'=> now()],
                ['name'=> 'Cocina','created_at' => now(), 'updated_at'=> now()],
            ]
        );
        DB::table('report_type')->insert(
            [
                ['type'=> 'Vocabulario','created_at' => now(), 'updated_at'=> now()],
                ['type'=> 'Contenido','created_at' => now(), 'updated_at'=> now()],
                ['type'=> 'Falsedad','created_at' => now(), 'updated_at'=> now()],
            ]
        );
    }
}
