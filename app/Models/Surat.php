<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Surat extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pegawai_id',
        'kop_surat_id',
        'no_surat',
        'perihal',
        'tanggal_surat',
        'file_path',
    ];

    /**
     * Get the pegawai that owns the Surat.
     */
    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }

    /**
     * Get the kop_surat that owns the Surat.
     */
    public function kopSurat(): BelongsTo
    {
        return $this->belongsTo(KopSurat::class, 'kop_surat_id');
    }

    /**
     * Get the cuti associated with the Surat.
     */
    public function cuti(): HasOne
    {
        return $this->hasOne(Cuti::class);
    }
}
