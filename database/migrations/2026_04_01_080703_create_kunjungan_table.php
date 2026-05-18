<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kunjungan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_cabang');
            $table->string('nama_ao');
            $table->string('nama_nasabah');
            $table->string('no_pembiayaan');
            $table->text('alamat');
            $table->date('tanggal_kunjungan');
            $table->string('keterangan')->nullable();
            $table->string('foto_path')->nullable();
            $table->string('foto_name')->nullable();
            $table->timestamp('waktu_input')->useCurrent();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kunjungan');
    }
};