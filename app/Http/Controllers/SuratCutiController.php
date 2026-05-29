<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Surat;
use App\Http\Requests\SuratCuti\StoreSuratCutiRequest;
use App\Http\Requests\SuratCuti\UpdateSuratCutiRequest;
use App\Support\SuratStatusTransition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuratCutiController extends Controller
{
    // ─────────────────────────────────────────────────────────────────────────
    // Helper
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
            ->where('jenis_surat', 'cuti')
            ->where('nomor_surat', 'like', 'B-%')
            ->latest('id')
            ->first();

        $nextSeq = 1;
        if ($lastSurat && preg_match('/^B-(\d+)\//', $lastSurat->nomor_surat, $m)) {
            $nextSeq = (int) $m[1] + 1;
        }

        $nomorUrut = str_pad($nextSeq, 3, '0', STR_PAD_LEFT);

        return "B-{$nomorUrut}/KK.01.1.19/KP.08.2/{$bulan}/{$tahun}";
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Resource Methods
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Tampilkan daftar surat cuti (Pegawai: miliknya, Pimpinan/Admin: semua/yang perlu diproses).
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Surat::with('pegawai', 'approvedBy')->where('jenis_surat', 'cuti');

        if ($user->role === 'pegawai') {
            $pegawai = $user->pegawai;
            if (!$pegawai) {
                if ($request->wantsJson()) {
                    return response()->json(['data' => [], 'total' => 0]);
                }
                return view('surat-cuti.index');
            }
            $query->where('pegawai_id', $pegawai->id);
        } elseif ($user->role === 'pimpinan') {
            $query->whereIn('status', ['diproses', 'disetujui', 'ditolak']);
        }

        if ($request->filled('search')) {
            $kw = $request->input('search');
            $query->where(function ($q) use ($kw) {
                $q->where('nomor_surat', 'like', "%{$kw}%")
                    ->orWhere('jenis_cuti', 'like', "%{$kw}%")
                    ->orWhereHas('pegawai', fn($pq) => $pq
                        ->where('nama_lengkap', 'like', "%{$kw}%")
                        ->orWhere('nip', 'like', "%{$kw}%"));
            });
        }

        if ($request->filled('jenis_cuti')) {
            $query->where('jenis_cuti', $request->input('jenis_cuti'));
        }
        if ($request->filled('filter_status')) {
            $query->where('status', $request->input('filter_status'));
        }
        if ($request->filled('status_kepegawaian')) {
            $query->whereHas('pegawai', fn($pq) => $pq->where('status_kepegawaian', $request->input('status_kepegawaian')));
        }
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_surat', $request->input('bulan'));
        }

        $sortBy = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc');
        $surats = $query->orderBy($sortBy, $sortDir)->paginate($request->input('limit', 10));

        $surats->getCollection()->transform(function ($surat) {
            $surat->pegawai_nama = $surat->pegawai->nama_lengkap ?? '-';
            $surat->pegawai_nip = $surat->pegawai->nip ?? '-';
            $surat->pegawai_status_kepegawaian = $surat->pegawai->status_kepegawaian ?? '-';
            $surat->jumlah_hari = ($surat->tanggal_mulai_cuti && $surat->tanggal_selesai_cuti)
                ? $surat->tanggal_mulai_cuti->diffInDays($surat->tanggal_selesai_cuti) + 1
                : 0;
            return $surat;
        });

        if ($request->wantsJson()) {
            return response()->json([
                'data' => $surats->items(),
                'total' => $surats->total(),
            ]);
        }

        $nextNomorSurat = '';
        if ($user->role === 'admin') {
            $nextNomorSurat = $this->generateNomorSurat();
        }

        return view('surat-cuti.index', [
            'surats' => $surats->appends(request()->query()),
            'nextNomorSurat' => $nextNomorSurat
        ]);
    }

    /**
     * Form pengajuan cuti.
     * - Pegawai  : form untuk diri sendiri.
     * - Admin    : form dengan dropdown pilih pegawai.
     */
    public function create()
    {
        $user = auth()->user();
        $pimpinan = Pegawai::where('is_atasan', true)->first();

        if ($user->role === 'admin') {
            $pegawais = Pegawai::orderBy('nama_lengkap')->get();
            $generatedNomorSurat = $this->generateNomorSurat();
            return view('surat-cuti.create', compact('pimpinan', 'generatedNomorSurat', 'pegawais'));
        }

        $pegawai = $user->pegawai;
        if (!$pegawai) {
            return redirect()->route('surat-cuti.index')
                ->with('notification', ['type' => 'error', 'title' => 'Gagal', 'message' => 'Data pegawai Anda tidak ditemukan.']);
        }

        // Pegawai: dapat nomor draft sementara
        $generatedNomorSurat = 'DRAFT-' . time();

        return view('surat-cuti.create', compact('pegawai', 'pimpinan', 'generatedNomorSurat'));
    }

    /**
     * Simpan permohonan cuti baru.
     * - Pegawai : untuk diri sendiri.
     * - Admin   : pilih pegawai via pegawai_id di form.
     */
    public function store(StoreSuratCutiRequest $request)
    {
        $validated = $request->validated();
        $user = auth()->user();

        // Tentukan pegawai: admin bisa pilih, pegawai hanya dirinya sendiri
        if ($user->role === 'admin' && !empty($validated['pegawai_id'])) {
            $pegawai = Pegawai::findOrFail($validated['pegawai_id']);
        } else {
            $pegawai = $user->pegawai;
        }

        if (!$pegawai) {
            return back()->withErrors(['error' => 'Data pegawai tidak ditemukan.']);
        }

        $surat = DB::transaction(function () use ($validated, $pegawai) {
            return Surat::create([
                'pegawai_id' => $pegawai->id,
                'jenis_surat' => 'cuti',
                'nomor_surat' => $validated['nomor_surat'],
                'tanggal_surat' => $validated['tanggal_surat'],
                'perihal' => 'Permohonan Cuti ' . ucfirst(str_replace('_', ' ', $validated['jenis_cuti'])),
                'status' => 'diajukan',
                'jenis_cuti' => $validated['jenis_cuti'],
                'tanggal_mulai_cuti' => $validated['tanggal_mulai_cuti'],
                'tanggal_selesai_cuti' => $validated['tanggal_selesai_cuti'],
                'keterangan_cuti' => $validated['keterangan_cuti'] ?? null,
                'tembusan' => $validated['tembusan'] ?? null,
            ]);
        });

        return redirect()->route('surat-cuti.show', $surat)
            ->with('notification', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => 'Surat cuti berhasil dibuat.',
            ]);
    }

    /**
     * Detail surat cuti + preview cetak.
     */
    public function show(Surat $surat_cuti)
    {
        $surat = $surat_cuti->load('pegawai', 'approvedBy');
        $pegawai = $surat->pegawai;
        $pimpinan = $surat->approvedBy ?? Pegawai::where('is_atasan', true)->first();

        $lamaCutiStr = '';
        if ($surat->tanggal_mulai_cuti && $surat->tanggal_selesai_cuti) {
            $hari = $surat->tanggal_mulai_cuti->diffInDays($surat->tanggal_selesai_cuti) + 1;
            $lamaCutiStr = $hari . ' hari';
        }

        $templateData = [
            'surat' => $surat,
            'pegawai' => $pegawai,
            'pimpinan' => $pimpinan,
            'lama_cuti' => $lamaCutiStr,
        ];

        $template = 'surat-cuti.template';

        if (request()->has('print')) {
            return view($template, $templateData);
        }

        return view('surat-cuti.show', [
            'surat' => $surat,
            'template' => $template,
            'template_data' => $templateData,
        ]);
    }

    /**
     * Form edit surat cuti.
     * - Admin   : bisa edit semua surat (status draft/diajukan)
     * - Pegawai : hanya surat milik sendiri (status draft/diajukan)
     */
    public function edit(Surat $surat_cuti)
    {
        $user = auth()->user();

        // Hanya admin atau pemilik surat yang boleh edit
        if ($user->role === 'pegawai' && $surat_cuti->pegawai_id !== $user->pegawai?->id) {
            abort(403, 'Anda tidak memiliki izin untuk mengedit surat ini.');
        }
        if ($user->role === 'pimpinan') {
            abort(403, 'Pimpinan tidak diizinkan mengedit surat cuti.');
        }

        if (!in_array($surat_cuti->status, ['draft', 'diajukan'])) {
            return redirect()->route('surat-cuti.show', $surat_cuti)
                ->with('notification', ['type' => 'error', 'title' => 'Tidak Diizinkan', 'message' => 'Surat yang sudah diproses tidak dapat diubah.']);
        }

        return view('surat-cuti.edit', [
            'surat_cuti' => $surat_cuti->load('pegawai'),
        ]);
    }

    /**
     * Update surat cuti.
     * - Admin   : bisa update semua surat (status draft/diajukan)
     * - Pegawai : hanya surat milik sendiri (status draft/diajukan)
     */
    public function update(UpdateSuratCutiRequest $request, Surat $surat_cuti)
    {
        $user = auth()->user();

        if ($user->role === 'pegawai' && $surat_cuti->pegawai_id !== $user->pegawai?->id) {
            abort(403);
        }
        if ($user->role === 'pimpinan') {
            abort(403);
        }

        if (!in_array($surat_cuti->status, ['draft', 'diajukan'])) {
            return back()->with('notification', ['type' => 'error', 'title' => 'Gagal', 'message' => 'Surat yang sudah diproses tidak dapat diubah.']);
        }

        $validated = $request->validated();

        DB::transaction(function () use ($validated, $surat_cuti) {
            $surat_cuti->update([
                'tanggal_surat' => $validated['tanggal_surat'],
                'perihal' => 'Permohonan Cuti ' . ucfirst(str_replace('_', ' ', $validated['jenis_cuti'])),
                'jenis_cuti' => $validated['jenis_cuti'],
                'tanggal_mulai_cuti' => $validated['tanggal_mulai_cuti'],
                'tanggal_selesai_cuti' => $validated['tanggal_selesai_cuti'],
                'keterangan_cuti' => $validated['keterangan_cuti'] ?? null,
                'tembusan' => $validated['tembusan'] ?? null,
            ]);
        });

        return redirect()->route('surat-cuti.show', $surat_cuti)
            ->with('notification', ['type' => 'success', 'title' => 'Berhasil!', 'message' => 'Surat cuti telah berhasil diperbarui.']);
    }

    /**
     * Hapus surat cuti.
     */
    public function destroy(string $id)
    {
        Surat::destroy($id);

        return redirect()->route('surat-cuti.index')
            ->with('notification', ['type' => 'success', 'title' => 'Dihapus!', 'message' => 'Surat cuti telah berhasil dihapus.']);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Status Transition Actions
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Admin: ubah status diajukan > diproses (lapor ke pimpinan) dan berikan nomor surat resmi.
     */
    public function verifikasi(Request $request, Surat $surat_cuti)
    {
        try {
            SuratStatusTransition::assertValid($surat_cuti->status, 'diproses');
        } catch (\InvalidArgumentException $e) {
            return back()->with('notification', ['type' => 'error', 'title' => 'Gagal', 'message' => $e->getMessage()]);
        }

        $request->validate([
            'nomor_surat' => 'required|string|max:100|unique:surats,nomor_surat,' . $surat_cuti->id,
        ]);

        $surat_cuti->update([
            'status' => 'diproses',
            'keterangan' => $request->input('keterangan'),
            'nomor_surat' => $request->input('nomor_surat'),
        ]);

        return back()->with('notification', [
            'type' => 'success',
            'title' => 'Diteruskan!',
            'message' => 'Surat cuti telah diteruskan ke pimpinan dengan nomor resmi.',
        ]);
    }

    /**
     * Pimpinan: setujui surat cuti (diproses > disetujui) + kurangi kuota.
     */
    public function setujui(Request $request, Surat $surat_cuti)
    {
        try {
            SuratStatusTransition::assertValid($surat_cuti->status, 'disetujui');
        } catch (\InvalidArgumentException $e) {
            return back()->with('notification', ['type' => 'error', 'title' => 'Gagal', 'message' => $e->getMessage()]);
        }

        $pimpinanPegawai = auth()->user()->pegawai;

        DB::transaction(function () use ($surat_cuti, $pimpinanPegawai) {
            $surat_cuti->update([
                'status' => 'disetujui',
                'approved_by' => $pimpinanPegawai?->id,
            ]);
        });

        return back()->with('notification', ['type' => 'success', 'title' => 'Disetujui!', 'message' => 'Surat cuti telah disetujui.']);
    }

    /**
     * Pimpinan: tolak surat cuti (diproses > ditolak) + simpan alasan.
     */
    public function tolak(Request $request, Surat $surat_cuti)
    {
        $request->validate([
            'ditolak_alasan' => ['required', 'string', 'max:500'],
        ]);

        try {
            SuratStatusTransition::assertValid($surat_cuti->status, 'ditolak');
        } catch (\InvalidArgumentException $e) {
            return back()->with('notification', ['type' => 'error', 'title' => 'Gagal', 'message' => $e->getMessage()]);
        }

        $pimpinanPegawai = auth()->user()->pegawai;

        $surat_cuti->update([
            'status' => 'ditolak',
            'approved_by' => $pimpinanPegawai?->id,
            'ditolak_alasan' => $request->input('ditolak_alasan'),
        ]);

        return back()->with('notification', ['type' => 'info', 'title' => 'Ditolak', 'message' => 'Surat cuti telah ditolak.']);
    }
}
