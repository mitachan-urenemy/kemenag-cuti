<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

class Surat extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // Common fields
        'pegawai_id',
        'approved_by',   // pimpinan yang menyetujui/menolak (nullable)
        'nomor_surat',
        'jenis_surat',
        'tanggal_surat',
        'perihal',

        // Status
        'status',
        'keterangan',
        'ditolak_alasan', // alasan penolakan oleh pimpinan

        // Cuti-specific fields
        'jenis_cuti',
        'tanggal_mulai_cuti',
        'tanggal_selesai_cuti',
        'keterangan_cuti',
        'tembusan',

        // Tugas-specific fields
        'dasar_hukum',
        'tujuan_tugas',
        'lokasi_tugas',
        'tanggal_mulai_tugas',
        'tanggal_selesai_tugas',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_surat' => 'date',
        'tanggal_mulai_cuti' => 'date',
        'tanggal_selesai_cuti' => 'date',
        'tanggal_mulai_tugas' => 'date',
        'tanggal_selesai_tugas' => 'date',
    ];

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id', 'id');
    }

    /**
     * Pimpinan yang menyetujui atau menolak surat ini.
     * Null jika surat belum diproses oleh pimpinan.
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'approved_by', 'id');
    }

    /**
     * Calculate the duration of cuti and remaining cuti.
     * (Durasi cuti dan sisa cuti)
     */
    public function hitungCuti($tanggalMulai, $tanggalSelesai)
    {
        $mulai = Carbon::parse($tanggalMulai);
        $selesai = Carbon::parse($tanggalSelesai);
        $hariIni = Carbon::today();

        if ($selesai->lt($mulai)) {
            throw new \InvalidArgumentException('Tanggal selesai tidak boleh lebih kecil dari tanggal mulai');
        }

        // total durasi cuti (inklusif)
        $durasi = $mulai->diffInDays($selesai) + 1;

        // sisa cuti dari hari ini sampai tanggal selesai
        $sisa = $hariIni->gt($selesai)
            ? 0
            : $hariIni->diffInDays($selesai) + 1;

        return [
            'durasi' => $durasi,
            'sisa_cuti' => $sisa,
            'status' => match (true) {
                $hariIni->lt($mulai) => 'BELUM_DIMULAI',
                $hariIni->between($mulai, $selesai) => 'SEDANG_CUTI',
                default => 'SELESAI',
            },
        ];
    }
}
