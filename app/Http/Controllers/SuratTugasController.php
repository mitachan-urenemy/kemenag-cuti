<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use App\Http\Requests\StoreSuratTugasRequest;
use App\Http\Requests\UpdateSuratTugasRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

class SuratTugasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $today = Carbon::today();
        $query = Surat::where('jenis_surat', 'tugas')
            ->whereDate('tanggal_selesai_tugas', '>=', $today);

        // Handle search
        if ($request->filled('search')) {
            $searchValue = $request->input('search');
            $query->where(function ($q) use ($searchValue) {
                $q->where('nomor_surat', 'like', "%{$searchValue}%")
                    ->orWhere('tujuan_tugas', 'like', "%{$searchValue}%")
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
            $surat->nama_lengkap_pegawai = $surat->nama_lengkap_pegawai ?? '-';
            return $surat;
        });

        if ($request->wantsJson()) {
            return response()->json([
                'data' => $surats->items(),
                'total' => $surats->total(),
            ]);
        }

        return view('surat-tugas.index', [
            'surats' => $surats->appends(request()->query())
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Generate Nomor Surat Tugas: Format: B–001/KK.01.19/I/KP.02.2/01/2025
        // Prefix B- (DIKEMBALIKAN SESUAI PERMINTAAN USER)
        $bulan = Carbon::now()->format('m');
        $tahun = Carbon::now()->format('Y');

        // Cari surat terakhir yang dibuat untuk tahun ini
        $lastSurat = Surat::whereYear('tanggal_surat', $tahun)
                        ->where('jenis_surat', 'tugas')
                        ->where('nomor_surat', 'like', 'B-%')
                        ->latest('id')
                        ->first();

        $nextSequence = 1;
        if ($lastSurat) {
            // Ekstrak nomor surat terakhir dari format B-XXX/...
            if (preg_match('/B-(\d+)\//', $lastSurat->nomor_surat, $matches)) {
                $nextSequence = intval($matches[1]) + 1;
            }
        }

        $nomorUrut = str_pad($nextSequence, 3, '0', STR_PAD_LEFT);
        $generatedNomorSurat = "B-{$nomorUrut}/KK.01.19/I/KP.02.2/{$bulan}/{$tahun}";

        return view('surat-tugas.create', compact('generatedNomorSurat'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSuratTugasRequest $request)
    {
        $validated = $request->validated();

        $surat = DB::transaction(function () use ($validated, $request) {
            $surat = Surat::create([
                'jenis_surat' => 'tugas',
                'nomor_surat' => $validated['nomor_surat'],
                'tanggal_surat' => $validated['tanggal_surat'],
                'perihal' => $validated['tujuan_tugas'],
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

                'dasar_hukum' => $validated['dasar_hukum'],
                'tujuan_tugas' => $validated['tujuan_tugas'],
                'lokasi_tugas' => $validated['lokasi_tugas'],
                'tanggal_mulai_tugas' => $validated['tanggal_mulai_tugas'],
                'tanggal_selesai_tugas' => $validated['tanggal_selesai_tugas'],
            ]);

            return $surat;
        });

        return redirect()->route('surat-tugas.show', ['surat_tugas' => $surat])
            ->with('notification', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => 'Surat tugas telah berhasil dibuat. Silakan periksa detailnya.'
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Surat $surat_tugas)
    {
        $surat = $surat_tugas;

        $template = 'surat-tugas.template'; // For now, a single template for Surat Tugas

        $data = [
            'nomor_surat' => $surat->nomor_surat,
            'tanggal_surat' => Carbon::parse($surat->tanggal_surat)->translatedFormat('d F Y'),
            'dasar_hukum' => $surat->dasar_hukum,
            'tujuan_tugas' => $surat->tujuan_tugas,
            'lokasi_tugas' => $surat->lokasi_tugas,
            'tanggal_mulai_tugas' => Carbon::parse($surat->tanggal_mulai_tugas)->translatedFormat('d F Y'),
            'tanggal_selesai_tugas' => Carbon::parse($surat->tanggal_selesai_tugas)->translatedFormat('d F Y'),

            // To be compatible with view that expects 'pegawai' object
            'pegawai' => (object) [
                'nama_lengkap' => $surat->nama_lengkap_pegawai,
                'nip' => $surat->nip_pegawai,
                'pangkat_golongan' => $surat->pangkat_golongan_pegawai,
                'jabatan' => $surat->jabatan_pegawai,
                'bidang_seksi' => $surat->bidang_seksi_pegawai,
                'status_pegawai' => $surat->status_pegawai,
            ],

            'nama_penandatangan' => $surat->nama_lengkap_kepala_pegawai ?? '',
            'nip_penandatangan' => $surat->nip_kepala_pegawai ?? '',
            'jabatan_penandatangan' => $surat->jabatan_kepala_pegawai ?? '',
            'surat' => $surat, // Pass the original object for templates that need it
        ];

        if (request()->has('print')) {
            $data['trigger_print'] = true;
            return view($template, $data);
        }

        return view('surat-tugas.show', [
            'surat' => $surat, // for action buttons
            'template' => $template,
            'data' => $data,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Surat $surat_tugas)
    {
        return view('surat-tugas.edit', compact('surat_tugas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSuratTugasRequest $request, Surat $surat_tugas)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $surat_tugas) {
            $surat_tugas->update([
                'tanggal_surat' => $validated['tanggal_surat'],
                'perihal' => $validated['tujuan_tugas'],

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

                'dasar_hukum' => $validated['dasar_hukum'],
                'tujuan_tugas' => $validated['tujuan_tugas'],
                'lokasi_tugas' => $validated['lokasi_tugas'],
                'tanggal_mulai_tugas' => $validated['tanggal_mulai_tugas'],
                'tanggal_selesai_tugas' => $validated['tanggal_selesai_tugas'],
            ]);
        });

        return redirect()->route('surat-tugas.show', $surat_tugas)
            ->with('notification', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => 'Surat tugas telah berhasil diperbarui.'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Surat::destroy($id);
        return redirect()->route('surat-tugas.index')->with('notification', [
            'type' => 'success',
            'title' => 'Dihapus!',
            'message' => 'Surat tugas telah berhasil dihapus.'
        ]);
    }
}
