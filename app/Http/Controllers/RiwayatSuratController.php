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
        $jenisCutiOptions = ['tahunan', 'sakit', 'melahirkan', 'alasan_penting', 'besar'];
        if ($request->wantsJson()) {
            $user = auth()->user();
            $query = Surat::with('pegawai', 'approvedBy');

            if ($user->role === 'pegawai') {
                $query->where('pegawai_id', $user->pegawai?->id);
            }

            if ($search = $request->input('search')) {
                $query->where(function ($q) use ($search) {
                    $q->where('nomor_surat', 'like', "%{$search}%")
                        ->orWhere('perihal', 'like', "%{$search}%")
                        ->orWhereHas('pegawai', function ($q2) use ($search) {
                            $q2->where('nama_lengkap', 'like', "%{$search}%")
                                ->orWhere('nip', 'like', "%{$search}%");
                        });
                });
            }

            if ($jenisSurat = $request->input('jenis_surat')) {
                if (in_array($jenisSurat, ['cuti', 'tugas'])) {
                    $query->where('jenis_surat', $jenisSurat);
                }
            }
            if ($jenisCuti = $request->input('jenis_cuti')) {
                if (in_array($jenisCuti, $jenisCutiOptions)) {
                    $query->where('jenis_surat', 'cuti')
                        ->where('jenis_cuti', $jenisCuti);
                }
            }
            if ($statusPegawai = $request->input('status_pegawai')) {
                if (in_array($statusPegawai, ['PNS', 'PPPK'])) {
                    $query->whereHas('pegawai', function ($q2) use ($statusPegawai) {
                        $q2->where('status_kepegawaian', $statusPegawai);
                    });
                }
            }

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

            $limit = $request->input('limit', 10);
            $surats = $query->paginate($limit);

            $surats->getCollection()->transform(function ($surat) {
                $surat->pegawai_nama = $surat->pegawai->nama_lengkap ?? '-';
                $surat->created_by_name = $surat->pegawai->user->username ?? 'System';
                return $surat;
            });

            return response()->json([
                'data' => $surats->items(),
                'total' => $surats->total(),
            ]);
        }

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
