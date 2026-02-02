<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pegawai extends Model
{
    protected $fillable = [
        'nama',
        'nip',
        'image_path',
        'jabatan',
        'unit_kerja',
        'pangkat_golonganruang',
        'kepala',
    ];

    /**
     * Get the user associated with the Pegawai.
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    /**
     * Get the surats for the Pegawai.
     */
    public function surats(): HasMany
    {
        return $this->hasMany(Surat::class);
    }

    /**
     * Get the cuti requests made by this Pegawai.
     */
    public function cutis(): HasMany
    {
        return $this->hasMany(Cuti::class);
    }

    /**
     * Get the cuti requests where this Pegawai is the atasan.
     */
    public function cutisAsAtasan(): HasMany
    {
        return $this->hasMany(Cuti::class, 'atasan_id');
    }

    /**
     * Get the cuti requests where this Pegawai is the pejabat.
     */
    public function cutisAsPejabat(): HasMany
    {
        return $this->hasMany(Cuti::class, 'pejabat_id');
    }
}
