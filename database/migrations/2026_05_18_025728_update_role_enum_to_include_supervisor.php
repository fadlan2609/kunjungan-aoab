<?php
// database/migrations/2026_05_18_xxxxxx_update_role_enum_to_include_supervisor.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Untuk MySQL
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('ao', 'manager', 'supervisor', 'admin') NOT NULL DEFAULT 'ao'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('ao', 'manager', 'admin') NOT NULL DEFAULT 'ao'");
    }
};