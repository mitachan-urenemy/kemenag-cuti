<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cuti extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pegawai_id',
        'jenis_cuti',
        'alasan_cuti',
        'tanggal_mulai',
        'tanggal_selesai',
        'lama_cuti',
        'alamat_selama_cuti',
        'status_persetujuan',
        'atasan_id',
        'pejabat_id',
        'surat_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    /**
     * Get the pegawai that owns the Cuti.
     */
    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class);
    }

    /**
     * Get the atasan (supervisor) for the Cuti.
     */
    public function atasan(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'atasan_id');
    }

    /**
     * Get the pejabat (official) for the Cuti.
     */
    public function pejabat(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'pejabat_id');
    }

    /**
     * Get the surat associated with the Cuti.
     */
    public function surat(): BelongsTo
    {
        return $this->belongsTo(Surat::class);
    }
}
