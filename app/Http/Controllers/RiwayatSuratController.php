<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class RiwayatSuratController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Data untuk filter jenis cuti
        $jenisCutiOptions = ['tahunan', 'sakit', 'melahirkan', 'alasan_penting', 'besar'];

        // Jika request adalah AJAX (untuk DataTable)
        if ($request->wantsJson()) {
            $query = Surat::query();

            // Search - cari di nomor surat, perihal, atau nama pegawai
            if ($search = $request->input('search')) {
                $query->where(function ($q) use ($search) {
                    $q->where('nomor_surat', 'like', "%{$search}%")
                        ->orWhere('perihal', 'like', "%{$search}%")
                        ->orWhere('nama_lengkap_pegawai', 'like', "%{$search}%")
                        ->orWhere('nip_pegawai', 'like', "%{$search}%");
                });
            }

            // Filter by jenis_surat (cuti/tugas)
            if ($jenisSurat = $request->input('jenis_surat')) {
                if (in_array($jenisSurat, ['cuti', 'tugas'])) {
                    $query->where('jenis_surat', $jenisSurat);
                }
            }

            // Filter by jenis_cuti (hanya berlaku jika jenis_surat adalah 'cuti')
            if ($jenisCuti = $request->input('jenis_cuti')) {
                if (in_array($jenisCuti, $jenisCutiOptions)) {
                    $query->where('jenis_surat', 'cuti')
                          ->where('jenis_cuti', $jenisCuti);
                }
            }

            // Filter by status_pegawai (PNS/PPPK) - Menggunakan kolom snapshot di tabel surat
            if ($statusPegawai = $request->input('status_pegawai')) {
                if (in_array($statusPegawai, ['PNS', 'PPPK'])) {
                    $query->where('status_pegawai', $statusPegawai);
                }
            }

            // Sorting
            $allowedSortColumns = ['nomor_surat', 'jenis_surat', 'tanggal_surat', 'created_at'];
            $sort = $request->input('sort', 'tanggal_surat');
            $direction = $request->input('dir', 'desc');

            if (!in_array($direction, ['asc', 'desc'])) {
                $direction = 'desc';
            }
            if ($sort && in_array($sort, $allowedSortColumns)) {
                $query->orderBy($sort, $direction);
            } else {
                $query->orderBy('tanggal_surat', 'desc')->orderBy('created_at', 'desc');
            }

            // Pagination
            $limit = $request->input('limit', 10);
            $surats = $query->paginate($limit);

            // Parse data to add custom attributes
            $surats->getCollection()->transform(function ($surat) {
                $surat->pegawai_nama = $surat->nama_lengkap_pegawai ?? '-';
                return $surat;
            });

            return response()->json([
                'data' => $surats->items(),
                'total' => $surats->total(),
            ]);
        }

        // Jika bukan AJAX, tampilkan view
        return view('riwayat-surat.index', compact('jenisCutiOptions'));
    }

    /**
     * Get statistics for riwayat surat (optional, untuk summary)
     */
    public function statistics()
    {
        $currentYear = date('Y');

        $stats = [
            'total_surat' => Surat::count(),
            'total_cuti' => Surat::where('jenis_surat', 'cuti')->count(),
            'total_tugas' => Surat::where('jenis_surat', 'tugas')->count(),
            'surat_tahun_ini' => Surat::whereYear('tanggal_surat', $currentYear)->count(),
            'surat_bulan_ini' => Surat::whereYear('tanggal_surat', $currentYear)
                                      ->whereMonth('tanggal_surat', date('m'))
                                      ->count(),
        ];

        return response()->json($stats);
    }
}
