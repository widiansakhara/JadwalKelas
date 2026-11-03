<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TahunAjaran extends Model
{
    use HasFactory;

    protected $table = 'tahun_ajaran';
    protected $primaryKey = 'id_tahun_ajaran';

    protected $fillable = [
        'tahun',
        'semester',
        'status_aktif',
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
    ];

    public function jadwalPelajaran(): HasMany
    {
        return $this->hasMany(JadwalPelajaran::class, 'id_tahun_ajaran', 'id_tahun_ajaran');
    }

    public function jadwalUjian(): HasMany
    {
        return $this->hasMany(JadwalUjian::class, 'id_tahun_ajaran', 'id_tahun_ajaran');
    }

    public function getNamaLengkapAttribute(): string
    {
        return "{$this->tahun} ({$this->semester})";
    }
}

