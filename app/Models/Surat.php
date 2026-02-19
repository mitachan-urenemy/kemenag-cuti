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
        'nomor_surat',
        'jenis_surat',
        'tanggal_surat',
        'perihal',

        // Manual input pegawai & kepala pegawai
        'nama_lengkap_pegawai',
        'nip_pegawai',
        'pangkat_golongan_pegawai',
        'jabatan_pegawai',
        'bidang_seksi_pegawai',
        'status_pegawai',

        'nama_lengkap_kepala_pegawai',
        'nip_kepala_pegawai',
        'jabatan_kepala_pegawai',

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
