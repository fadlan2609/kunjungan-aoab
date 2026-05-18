<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'ao'])->default('ao');
            $table->string('cabang')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // Insert default users
        DB::table('users')->insert([
            // Admin
            [
                'name' => 'Administrator',
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'cabang' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            // AO Pusat
            [
                'name' => 'AO Pusat',
                'username' => 'ao_pusat',
                'password' => Hash::make('ao123'),
                'role' => 'ao',
                'cabang' => 'Pusat',
                'created_at' => now(),
                'updated_at' => now()
            ],
            // AO Kisaran
            [
                'name' => 'AO Kisaran',
                'username' => 'ao_kisaran',
                'password' => Hash::make('ao123'),
                'role' => 'ao',
                'cabang' => 'Kisaran',
                'created_at' => now(),
                'updated_at' => now()
            ],
            // AO Perdagangan
            [
                'name' => 'AO Perdagangan',
                'username' => 'ao_perdagangan',
                'password' => Hash::make('ao123'),
                'role' => 'ao',
                'cabang' => 'Perdagangan',
                'created_at' => now(),
                'updated_at' => now()
            ],
            // AO Pematangsiantar
            [
                'name' => 'AO Pematangsiantar',
                'username' => 'ao_pematangsiantar',
                'password' => Hash::make('ao123'),
                'role' => 'ao',
                'cabang' => 'Pematangsiantar',
                'created_at' => now(),
                'updated_at' => now()
            ],
            // AO Sidamanik
            [
                'name' => 'AO Sidamanik',
                'username' => 'ao_sidamanik',
                'password' => Hash::make('ao123'),
                'role' => 'ao',
                'cabang' => 'Sidamanik',
                'created_at' => now(),
                'updated_at' => now()
            ],
            // AO Stabat
            [
                'name' => 'AO Stabat',
                'username' => 'ao_stabat',
                'password' => Hash::make('ao123'),
                'role' => 'ao',
                'cabang' => 'Stabat',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};