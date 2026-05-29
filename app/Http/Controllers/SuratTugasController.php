<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Surat;
use App\Http\Requests\SuratTugas\StoreSuratTugasRequest;
use App\Http\Requests\SuratTugas\UpdateSuratTugasRequest;
use App\Support\SuratStatusTransition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SuratTugasController extends Controller
{
    // ─────────────────────────────────────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────────────────────────────────────

    private function romanMonth(int $month): string
    {
        return ['', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'][$month];
    }

    private function generateNomorSurat(): string
    {
        $tahun = date('Y');
        $bulan = $this->romanMonth((int) date('n'));

        $lastSurat = Surat::whereYear('created_at', $tahun)
            ->where('jenis_surat', 'tugas')
            ->where('nomor_surat', 'like', 'B-%')
            ->latest('id')
            ->first();

        $nextSeq = 1;
        if ($lastSurat && preg_match('/^B-(\d+)\//', $lastSurat->nomor_surat, $m)) {
            $nextSeq = (int) $m[1] + 1;
        }

        $nomorUrut = str_pad($nextSeq, 3, '0', STR_PAD_LEFT);

        return "B-{$nomorUrut}/KK.01.19/I/KP.02.2/{$bulan}/{$tahun}";
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Resource Methods
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Daftar & monitoring surat tugas.
     */
    public function index(Request $request)
    {
        $today = Carbon::today();
        $user = auth()->user();

        $query = Surat::with('pegawai', 'approvedBy')->where('jenis_surat', 'tugas');

        if ($user->role === 'pegawai') {
            $pegawai = $user->pegawai;
            if (!$pegawai) {
                if ($request->wantsJson())
                    return response()->json(['data' => [], 'total' => 0]);
                return view('surat-tugas.index');
            }
            $query->where('pegawai_id', $pegawai->id);
        }

        if ($request->filled('search')) {
            $kw = $request->input('search');
            $query->where(function ($q) use ($kw) {
                $q->where('nomor_surat', 'like', "%{$kw}%")
                    ->orWhere('tujuan_tugas', 'like', "%{$kw}%")
                    ->orWhere('lokasi_tugas', 'like', "%{$kw}%")
                    ->orWhereHas('pegawai', fn($pq) => $pq
                        ->where('nama_lengkap', 'like', "%{$kw}%")
                        ->orWhere('nip', 'like', "%{$kw}%"));
            });
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_surat', $request->input('bulan'));
        }
        if ($request->filled('filter_status_tugas')) {
            $statusTugas = $request->input('filter_status_tugas');
            if ($statusTugas === 'SEDANG_BERJALAN') {
                $query->whereDate('tanggal_mulai_tugas', '<=', $today)
                    ->whereDate('tanggal_selesai_tugas', '>=', $today);
            } elseif ($statusTugas === 'SELESAI') {
                $query->whereDate('tanggal_selesai_tugas', '<', $today);
            }
        }

        $sortBy = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc');
        $surats = $query->orderBy($sortBy, $sortDir)->paginate($request->input('limit', 10));

        $surats->getCollection()->transform(function ($surat) use ($today) {
            $surat->pegawai_nama = $surat->pegawai->nama_lengkap ?? '-';
            $surat->pegawai_nip = $surat->pegawai->nip ?? '-';

            if ($surat->tanggal_mulai_tugas && $surat->tanggal_selesai_tugas) {
                $mulai = $surat->tanggal_mulai_tugas;
                $selesai = $surat->tanggal_selesai_tugas;

                $surat->status_tugas = match (true) {
                    $today->lt($mulai) => 'BELUM_DIMULAI',
                    $today->between($mulai, $selesai) => 'SEDANG_BERJALAN',
                    default => 'SELESAI',
                };
            } else {
                $surat->status_tugas = '-';
            }

            return $surat;
        });

        if ($request->wantsJson()) {
            return response()->json([
                'data' => $surats->items(),
                'total' => $surats->total(),
            ]);
        }

        return view('surat-tugas.index', [
            'surats' => $surats->appends(request()->query()),
        ]);
    }

    /**
     * Form buat surat tugas (admin).
     */
    public function create()
    {
        $pegawais = Pegawai::orderBy('nama_lengkap')->get();
        $pimpinan = Pegawai::where('is_atasan', true)->first();
        $generatedNomorSurat = $this->generateNomorSurat();

        return view('surat-tugas.create', compact('pegawais', 'pimpinan', 'generatedNomorSurat'));
    }

    /**
     * Simpan surat tugas - satu row per pegawai yang dipilih.
     * Jika multi-pegawai, nomor surat diberi seri: /1, /2, dst.
     */
    public function store(StoreSuratTugasRequest $request)
    {
        $validated = $request->validated();
        $pegawaiIds = $validated['pegawai_ids'];
        $multi = count($pegawaiIds) > 1;

        $pertamaSurat = DB::transaction(function () use ($validated, $pegawaiIds, $multi) {
            $pertama = null;

            foreach ($pegawaiIds as $index => $pegawaiId) {
                $pegawai = Pegawai::findOrFail($pegawaiId);
                $nomor = $multi
                    ? $validated['nomor_surat'] . '/' . ($index + 1)
                    : $validated['nomor_surat'];

                $surat = Surat::create([
                    'pegawai_id' => $pegawai->id,
                    'jenis_surat' => 'tugas',
                    'nomor_surat' => $nomor,
                    'tanggal_surat' => $validated['tanggal_surat'],
                    'perihal' => $validated['perihal'],
                    'status' => 'diproses', // masuk ke pimpinan dulu
                    'dasar_hukum' => $validated['dasar_hukum'],
                    'tujuan_tugas' => $validated['tujuan_tugas'],
                    'lokasi_tugas' => $validated['lokasi_tugas'],
                    'tanggal_mulai_tugas' => $validated['tanggal_mulai_tugas'],
                    'tanggal_selesai_tugas' => $validated['tanggal_selesai_tugas'],
                ]);

                if ($index === 0)
                    $pertama = $surat;
            }

            return $pertama;
        });

        return redirect()->route('surat-tugas.show', ['surat_tugas' => $pertamaSurat])
            ->with('notification', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => count($pegawaiIds) > 1
                    ? count($pegawaiIds) . ' surat tugas berhasil dibuat.'
                    : 'Surat tugas berhasil dibuat.',
            ]);
    }

    /**
     * Detail surat tugas.
     */
    public function show(Surat $surat_tugas)
    {
        $surat = $surat_tugas->load('pegawai', 'approvedBy');
        $pegawai = $surat->pegawai;
        $pimpinan = $surat->approvedBy ?? Pegawai::where('is_atasan', true)->first();

        $templateData = [
            'surat' => $surat,
            'pegawai' => $pegawai,
            'pimpinan' => $pimpinan,
        ];

        $template = 'surat-tugas.template';

        if (request()->has('print')) {
            return view($template, $templateData);
        }

        return view('surat-tugas.show', [
            'surat' => $surat,
            'template' => $template,
            'template_data' => $templateData,
        ]);
    }

    /**
     * Form edit surat tugas (admin only).
     */
    public function edit(Surat $surat_tugas)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Hanya admin yang dapat mengedit surat tugas.');
        }

        return view('surat-tugas.edit', compact('surat_tugas'));
    }

    /**
     * Update surat tugas (admin only).
     */
    public function update(UpdateSuratTugasRequest $request, Surat $surat_tugas)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $surat_tugas) {
            $surat_tugas->update([
                'tanggal_surat' => $validated['tanggal_surat'],
                'perihal' => $validated['perihal'],
                'dasar_hukum' => $validated['dasar_hukum'],
                'tujuan_tugas' => $validated['tujuan_tugas'],
                'lokasi_tugas' => $validated['lokasi_tugas'],
                'tanggal_mulai_tugas' => $validated['tanggal_mulai_tugas'],
                'tanggal_selesai_tugas' => $validated['tanggal_selesai_tugas'],
            ]);
        });

        return redirect()->route('surat-tugas.show', $surat_tugas)
            ->with('notification', ['type' => 'success', 'title' => 'Berhasil!', 'message' => 'Surat tugas berhasil diperbarui.']);
    }

    /**
     * Hapus surat tugas.
     */
    public function destroy(string $id)
    {
        Surat::destroy($id);

        return redirect()->route('surat-tugas.index')
            ->with('notification', ['type' => 'success', 'title' => 'Dihapus!', 'message' => 'Surat tugas berhasil dihapus.']);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Status Transition Actions
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Pimpinan: setujui surat tugas (diproses > disetujui).
     */
    public function setujui(Request $request, Surat $surat_tugas)
    {
        try {
            SuratStatusTransition::assertValid($surat_tugas->status, 'disetujui');
        } catch (\InvalidArgumentException $e) {
            return back()->with('notification', ['type' => 'error', 'title' => 'Gagal', 'message' => $e->getMessage()]);
        }

        $pimpinanPegawai = auth()->user()->pegawai;

        $surat_tugas->update([
            'status' => 'disetujui',
            'approved_by' => $pimpinanPegawai ? $pimpinanPegawai->id : null,
        ]);

        return back()->with('notification', [
            'type' => 'success',
            'title' => 'Disetujui!',
            'message' => 'Surat tugas telah disetujui.',
        ]);
    }

    /**
     * Pimpinan: tolak surat tugas (diproses > ditolak).
     */
    public function tolak(Request $request, Surat $surat_tugas)
    {
        try {
            SuratStatusTransition::assertValid($surat_tugas->status, 'ditolak');
        } catch (\InvalidArgumentException $e) {
            return back()->with('notification', ['type' => 'error', 'title' => 'Gagal', 'message' => $e->getMessage()]);
        }

        $request->validate([
            'ditolak_alasan' => 'required|string|max:255',
        ]);

        $surat_tugas->update([
            'status' => 'ditolak',
            'ditolak_alasan' => $request->input('ditolak_alasan'),
        ]);

        return back()->with('notification', [
            'type' => 'success',
            'title' => 'Ditolak!',
            'message' => 'Surat tugas telah ditolak.',
        ]);
    }
}
