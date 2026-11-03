<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'guru';
    protected $primaryKey = 'id_guru';

    protected $fillable = [
        'nip',
        'nama',
        'email',
        'no_hp',
    ];

    public function mataPelajaran(): BelongsToMany
    {
        return $this->belongsToMany(MataPelajaran::class, 'guru_mapel', 'id_guru', 'id_mapel');
    }

    public function guruMapel(): HasMany
    {
        return $this->hasMany(GuruMapel::class, 'id_guru', 'id_guru');
    }

    public function jadwalPelajaran(): HasMany
    {
        return $this->hasMany(JadwalPelajaran::class, 'id_guru', 'id_guru');
    }

    public function jadwalPengawas(): HasMany
    {
        return $this->hasMany(JadwalPengawas::class, 'id_guru', 'id_guru');
    }

    public function kegiatanSekolah(): HasMany
    {
        return $this->hasMany(KegiatanSekolah::class, 'id_penanggung_jawab', 'id_guru');
    }
}

