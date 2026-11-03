<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogBentrok extends Model
{
    use HasFactory;

    protected $table = 'log_bentrok';
    protected $primaryKey = 'id_log';

    protected $fillable = [
        'jenis_bentrok',
        'jenis_jadwal',
        'id_referensi',
        'deskripsi',
        'waktu_deteksi',
    ];

    protected $casts = [
        'waktu_deteksi' => 'datetime',
    ];
}

