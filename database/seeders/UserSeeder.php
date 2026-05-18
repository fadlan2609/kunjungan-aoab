<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        DB::table('users')->insert([
            'name' => 'Administrator',
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'cabang' => null,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Manager untuk setiap cabang
        $managers = [
            ['name' => 'Manager Pusat', 'username' => 'manager_pusat', 'cabang' => 'Pusat'],
            ['name' => 'Manager Kisaran', 'username' => 'manager_kisaran', 'cabang' => 'Kisaran'],
            ['name' => 'Manager Perdagangan', 'username' => 'manager_perdagangan', 'cabang' => 'Perdagangan'],
            ['name' => 'Manager Pematangsiantar', 'username' => 'manager_pematangsiantar', 'cabang' => 'Pematangsiantar'],
            ['name' => 'Manager Sidamanik', 'username' => 'manager_sidamanik', 'cabang' => 'Sidamanik'],
            ['name' => 'Manager Stabat', 'username' => 'manager_stabat', 'cabang' => 'Stabat'],
        ];

        foreach ($managers as $manager) {
            DB::table('users')->insert([
                'name' => $manager['name'],
                'username' => $manager['username'],
                'password' => Hash::make('manager123'),
                'role' => 'manager',
                'cabang' => $manager['cabang'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // AO untuk setiap cabang
        $aos = [
            ['name' => 'AO Pusat', 'username' => 'ao_pusat', 'cabang' => 'Pusat'],
            ['name' => 'AO Kisaran', 'username' => 'ao_kisaran', 'cabang' => 'Kisaran'],
            ['name' => 'AO Perdagangan', 'username' => 'ao_perdagangan', 'cabang' => 'Perdagangan'],
            ['name' => 'AO Pematangsiantar', 'username' => 'ao_pematangsiantar', 'cabang' => 'Pematangsiantar'],
            ['name' => 'AO Sidamanik', 'username' => 'ao_sidamanik', 'cabang' => 'Sidamanik'],
            ['name' => 'AO Stabat', 'username' => 'ao_stabat', 'cabang' => 'Stabat'],
        ];

        foreach ($aos as $ao) {
            DB::table('users')->insert([
                'name' => $ao['name'],
                'username' => $ao['username'],
                'password' => Hash::make('ao123'),
                'role' => 'ao',
                'cabang' => $ao['cabang'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}