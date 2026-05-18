<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('kunjungans', function (Blueprint $table) {
            if (!Schema::hasColumn('kunjungans', 'status')) {
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            }
            if (!Schema::hasColumn('kunjungans', 'catatan_manager')) {
                $table->text('catatan_manager')->nullable();
            }
            if (!Schema::hasColumn('kunjungans', 'approved_at')) {
                $table->timestamp('approved_at')->nullable();
            }
            if (!Schema::hasColumn('kunjungans', 'approved_by')) {
                $table->unsignedBigInteger('approved_by')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('kunjungans', function (Blueprint $table) {
            $table->dropColumn(['status', 'catatan_manager', 'approved_at', 'approved_by']);
        });
    }
};