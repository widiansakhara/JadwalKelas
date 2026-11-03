<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MataPelajaran extends Model
{
    use HasFactory;

    protected $table = 'mata_pelajaran';
    protected $primaryKey = 'id_mapel';

    protected $fillable = [
        'kode_mapel',
        'nama_mapel',
        'jam_per_minggu',
    ];

    public function guru(): BelongsToMany
    {
        return $this->belongsToMany(Guru::class, 'guru_mapel', 'id_mapel', 'id_guru');
    }

    public function guruMapel(): HasMany
    {
        return $this->hasMany(GuruMapel::class, 'id_mapel', 'id_mapel');
    }

    public function jadwalPelajaran(): HasMany
    {
        return $this->hasMany(JadwalPelajaran::class, 'id_mapel', 'id_mapel');
    }

    public function jadwalUjian(): HasMany
    {
        return $this->hasMany(JadwalUjian::class, 'id_mapel', 'id_mapel');
    }
}

