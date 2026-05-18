<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('kunjungan', function (Blueprint $table) {
            // Cek apakah kolom sudah ada sebelum menambah
            if (!Schema::hasColumn('kunjungan', 'status')) {
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('keterangan');
            }
            
            if (!Schema::hasColumn('kunjungan', 'catatan_manager')) {
                $table->text('catatan_manager')->nullable()->after('status');
            }
            
            if (!Schema::hasColumn('kunjungan', 'catatan_reject')) {
                $table->text('catatan_reject')->nullable()->after('catatan_manager');
            }
            
            if (!Schema::hasColumn('kunjungan', 'approved_by')) {
                $table->unsignedBigInteger('approved_by')->nullable()->after('catatan_reject');
            }
            
            if (!Schema::hasColumn('kunjungan', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('approved_by');
            }
            
            if (!Schema::hasColumn('kunjungan', 'rejected_by')) {
                $table->unsignedBigInteger('rejected_by')->nullable()->after('approved_at');
            }
            
            if (!Schema::hasColumn('kunjungan', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable()->after('rejected_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kunjungan', function (Blueprint $table) {
            $table->dropColumn([
                'status',
                'catatan_manager',
                'catatan_reject',
                'approved_by',
                'approved_at',
                'rejected_by',
                'rejected_at'
            ]);
        });
    }
};