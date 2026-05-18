<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kunjungan extends Model
{
    use HasFactory;

    protected $table = 'kunjungan';

    protected $fillable = [
        'nama_cabang',
        'nama_ao',
        'nama_nasabah',
        'no_pembiayaan',
        'alamat',
        'tanggal_kunjungan',
        'keterangan',
        'foto_url',
        'waktu_input',
        'status',
        'catatan_manager',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'tanggal_kunjungan' => 'date',
        'waktu_input' => 'datetime',
        'approved_at' => 'datetime',
    ];
}