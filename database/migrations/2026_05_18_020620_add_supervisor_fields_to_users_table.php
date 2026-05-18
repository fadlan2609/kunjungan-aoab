<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Untuk supervisor yang hanya mengawasi cabang tertentu (opsional)
            // Jika NULL, berarti bisa lihat semua cabang
            $table->json('cabang_binaan')->nullable()->after('cabang');
            // Level hierarki: 1=ao, 2=manager, 3=supervisor, 4=admin
            $table->integer('level')->default(1)->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['cabang_binaan', 'level']);
        });
    }
};