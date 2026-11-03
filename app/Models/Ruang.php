<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ruang extends Model
{
    use HasFactory;

    protected $table = 'ruang';
    protected $primaryKey = 'id_ruang';

    protected $fillable = [
        'kode_ruang',
        'nama_ruang',
        'jenis',
        'kapasitas',
    ];

    public function jadwalPelajaran(): HasMany
    {
        return $this->hasMany(JadwalPelajaran::class, 'id_ruang', 'id_ruang');
    }

    public function jadwalUjian(): HasMany
    {
        return $this->hasMany(JadwalUjian::class, 'id_ruang', 'id_ruang');
    }

    public function kegiatanSekolah(): HasMany
    {
        return $this->hasMany(KegiatanSekolah::class, 'id_ruang', 'id_ruang');
    }
}

