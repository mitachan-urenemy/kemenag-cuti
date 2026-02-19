<?php

namespace App\Http\Controllers;


use App\Models\Surat;
use App\Http\Requests\StoreSuratCutiRequest;
use App\Http\Requests\UpdateSuratCutiRequest; // Import the new Request
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

class SuratCutiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $today = Carbon::today();
        $query = Surat::where('jenis_surat', 'cuti')
            ->whereDate('tanggal_selesai_cuti', '>=', $today);

        // Handle search
        if ($request->filled('search')) {
            $searchValue = $request->input('search');
            $query->where(function ($q) use ($searchValue) {
                $q->where('nomor_surat', 'like', "%{$searchValue}%")
                    ->orWhere('jenis_cuti', 'like', "%{$searchValue}%")
                    ->orWhere('nama_lengkap_pegawai', 'like', "%{$searchValue}%")
                    ->orWhere('nip_pegawai', 'like', "%{$searchValue}%");
            });
        }

        // Handle sorting
        $sortBy = $request->input('sort_by', 'tanggal_surat');
        $sortDir = $request->input('sort_dir', 'desc');
        $query->orderBy($sortBy, $sortDir);

        $perPage = $request->input('limit', 10);
        $surats = $query->paginate($perPage);

        // Transform data
        $surats->getCollection()->transform(function ($surat) {
            $cuti = $surat->hitungCuti(
                $surat->tanggal_mulai_cuti,
                $surat->tanggal_selesai_cuti
            );

            $surat->pegawai_nama = $surat->nama_lengkap_pegawai;
            $surat->status_cuti = $cuti['status'];       // BELUM_DIMULAI | SEDANG_CUTI
            $surat->sisa_cuti = $cuti['sisa_cuti'];      // countdown

            return $surat;
        });

        // Handle status
        if ($request->filled('status')) {
            $status = $request->input('status');

            $surats->setCollection(
                $surats->getCollection()->filter(
                    fn ($s) => $s->status_cuti === $status
                )->values()
            );
        }

        if ($request->wantsJson()) {
            return response()->json([
                'data' => $surats->items(),
                'total' => $surats->total(),
            ]);
        }

        return view('surat-cuti.index', [
            'surats' => $surats->appends(request()->query())
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Generate Nomor Surat Cuti: Format: B–001/KK.01.1.19/KP.08.2/01/2025
        $bulan = date('m');
        $tahun = date('Y');

        // Find the last CUTI letter created this year to determine the sequence
        // We filter by 'B-' prefix and ensures it's a 'cuti' type for safety
        $lastSurat = Surat::whereYear('tanggal_surat', $tahun)
                        ->where('jenis_surat', 'cuti')
                        ->where('nomor_surat', 'like', 'B-%')
                        ->latest('id')
                        ->first();

        $nextSequence = 1;
        if ($lastSurat) {
            // Extract the sequence number from format B-XXX/...
            if (preg_match('/B-(\d+)\//', $lastSurat->nomor_surat, $matches)) {
                $nextSequence = intval($matches[1]) + 1;
            }
        }

        $nomorUrut = str_pad($nextSequence, 3, '0', STR_PAD_LEFT);
        $generatedNomorSurat = "B-{$nomorUrut}/KK.01.1.19/KP.08.2/{$bulan}/{$tahun}";

        return view('surat-cuti.create', compact('generatedNomorSurat'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSuratCutiRequest $request)
    {
        $validated = $request->validated();

        $surat = DB::transaction(function () use ($validated, $request) {
            $surat = Surat::create([
                'jenis_surat' => 'cuti',
                'nomor_surat' => $validated['nomor_surat'],
                'tanggal_surat' => $validated['tanggal_surat'],
                'perihal' => 'Permohonan cuti ' . ucfirst($validated['jenis_cuti']),
                'created_by_user_id' => $request->user()->id,

                // Manual input pegawai
                'nama_lengkap_pegawai' => $validated['nama_lengkap_pegawai'],
                'nip_pegawai' => $validated['nip_pegawai'],
                'pangkat_golongan_pegawai' => $validated['pangkat_golongan_pegawai'],
                'jabatan_pegawai' => $validated['jabatan_pegawai'],
                'bidang_seksi_pegawai' => $validated['bidang_seksi_pegawai'],
                'status_pegawai' => $validated['status_pegawai'],

                // Manual input penandatangan
                'nama_lengkap_kepala_pegawai' => $validated['nama_lengkap_kepala_pegawai'],
                'nip_kepala_pegawai' => $validated['nip_kepala_pegawai'],
                'jabatan_kepala_pegawai' => $validated['jabatan_kepala_pegawai'],

                'jenis_cuti' => $validated['jenis_cuti'],
                'tanggal_mulai_cuti' => $validated['tanggal_mulai_cuti'],
                'tanggal_selesai_cuti' => $validated['tanggal_selesai_cuti'],
                'keterangan_cuti' => $validated['keterangan_cuti'],
                'tembusan' => $validated['tembusan'] ?? null,
            ]);

            return $surat;
        });

        return redirect()->route('surat-cuti.show', ['surat_cuti' => $surat])
            ->with('notification', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => 'Surat cuti telah berhasil dibuat. Silakan periksa detailnya.'
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Surat $surat_cuti)
    {
        $surat = $surat_cuti;

        $template = 'surat-cuti.template';

        // Calculate duration
        $lama_cuti_str = '';
        if ($surat->tanggal_mulai_cuti && $surat->tanggal_selesai_cuti) {
            $lama_cuti_hari = $surat->tanggal_mulai_cuti
                ->diffInDays($surat->tanggal_selesai_cuti) + 1; // +1 kalau mau inklusif

            $lama_cuti_str = $lama_cuti_hari . ' hari';
        }

        $data = [
            'nomor_surat' => $surat->nomor_surat,
            'tanggal_surat' => Carbon::parse($surat->tanggal_surat)->translatedFormat('d F Y'),

            'nama' => $surat->nama_lengkap_pegawai ?? '',
            'nip' => $surat->nip_pegawai ?? '',
            'pangkat' => $surat->pangkat_golongan_pegawai ?? '',
            'jabatan' => $surat->jabatan_pegawai ?? '',
            'unit_kerja' => $surat->bidang_seksi_pegawai ?? 'Kantor Kementerian Agama Kabupaten Bener Meriah',

            'lama_cuti' => $lama_cuti_str,
            'tanggal_mulai' => Carbon::parse($surat->tanggal_mulai_cuti)->translatedFormat('d F Y'),
            'tanggal_selesai' => Carbon::parse($surat->tanggal_selesai_cuti)->translatedFormat('d F Y'),

            'nama_kepala' => $surat->nama_lengkap_kepala_pegawai ?? 'Kepala Dinas',
            'nip_kepala' => $surat->nip_kepala_pegawai ?? '',
            'jabatan_kepala' => $surat->jabatan_kepala_pegawai ?? '',

            'surat' => $surat,
        ];


        if (request()->has('print')) {
            return view($template, $data);
        }

        return view('surat-cuti.show', [
            'surat' => $surat, // for action buttons
            'template' => $template,
            'data' => $data,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Surat $surat_cuti)
    {
        return view('surat-cuti.edit', compact('surat_cuti'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSuratCutiRequest $request, Surat $surat_cuti)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $surat_cuti) {
            $surat_cuti->update([
                'tanggal_surat' => $validated['tanggal_surat'],
                'perihal' => 'Permohonan cuti ' . ucfirst($validated['jenis_cuti']),

                // Manual input pegawai
                'nama_lengkap_pegawai' => $validated['nama_lengkap_pegawai'],
                'nip_pegawai' => $validated['nip_pegawai'],
                'pangkat_golongan_pegawai' => $validated['pangkat_golongan_pegawai'],
                'jabatan_pegawai' => $validated['jabatan_pegawai'],
                'bidang_seksi_pegawai' => $validated['bidang_seksi_pegawai'],
                'status_pegawai' => $validated['status_pegawai'],

                // Manual input penandatangan
                'nama_lengkap_kepala_pegawai' => $validated['nama_lengkap_kepala_pegawai'],
                'nip_kepala_pegawai' => $validated['nip_kepala_pegawai'],
                'jabatan_kepala_pegawai' => $validated['jabatan_kepala_pegawai'],

                'jenis_cuti' => $validated['jenis_cuti'],
                'tanggal_mulai_cuti' => $validated['tanggal_mulai_cuti'],
                'tanggal_selesai_cuti' => $validated['tanggal_selesai_cuti'],
                'keterangan_cuti' => $validated['keterangan_cuti'],
                'tembusan' => $validated['tembusan'] ?? null,
            ]);
        });

        return redirect()->route('surat-cuti.show', $surat_cuti)
            ->with('notification', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => 'Surat cuti telah berhasil diperbarui.'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Surat::destroy($id);
        return redirect()->route('surat-cuti.index')->with('notification', [
            'type' => 'success',
            'title' => 'Dihapus!',
            'message' => 'Surat cuti telah berhasil dihapus.'
        ]);
    }
}
