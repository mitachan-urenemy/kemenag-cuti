<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Surat;
use App\Http\Requests\StoreSuratTugasRequest;
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
        $query = Surat::where('jenis_surat', 'tugas')->with('pegawai');

        // Handle search
        if ($request->filled('search')) {
            $searchValue = $request->input('search');
            $query->where(function ($q) use ($searchValue) {
                $q->where('nomor_surat', 'like', "%{$searchValue}%")
                    ->orWhere('tujuan_tugas', 'like', "%{$searchValue}%")
                    ->orWhereHas('pegawai', function ($q) use ($searchValue) {
                        $q->where('nama_lengkap', 'like', "%{$searchValue}%");
                    });
            });
        }

        // Handle sorting
        $sortBy = $request->input('sort_by', 'tanggal_surat');
        $sortDir = $request->input('sort_dir', 'desc');
        $query->orderBy($sortBy, $sortDir);

        $perPage = $request->input('per_page', 10);
        $surats = $query->paginate($perPage);

        // Transform data
        $surats->getCollection()->transform(function ($surat) {
            $surat->pegawai_nama = $surat->pegawai->nama_lengkap ?? '-';
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
        $pegawais = Pegawai::pluck('nama_lengkap', 'id');

        $kepalaPegawai = Pegawai::where('is_kepala', true)
            ->get()
            ->mapWithKeys(function ($kepala) {
                return [
                    $kepala->id => $kepala->nama_lengkap . ' (' . $kepala->jabatan . ')',
                ];
            });

        // Generate Nomor Surat Tugas: Format: B–001/KK.01.1.19/KP.02.3/01/2025
        // KP.02.3 = Perjalanan Dinas (TETAP)
        // Prefix B- (DIKEMBALIKAN SESUAI PERMINTAAN USER)
        $bulan = date('m');
        $tahun = date('Y');

        // Find the last TUGAS letter created this year to determine the sequence
        // We filter by 'B-' prefix AND 'tugas' type to separate from Cuti
        $lastSurat = Surat::whereYear('tanggal_surat', $tahun)
                        ->where('jenis_surat', 'tugas')
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
        // Changed code to KP.02.3 (Perjalanan Dinas Dalam Negeri)
        $generatedNomorSurat = "B-{$nomorUrut}/KK.01.1.19/KP.02.3/{$bulan}/{$tahun}";

        return view('surat-tugas.create', compact('pegawais', 'kepalaPegawai', 'generatedNomorSurat'));
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
                'pegawai_id' => $validated['pegawai_id'],
                'penandatangan_id' => $validated['penandatangan_id'],

                // Tugas-specific fields
                'dasar_hukum' => $validated['dasar_hukum'],
                'tujuan_tugas' => $validated['tujuan_tugas'],
                'lokasi_tugas' => $validated['lokasi_tugas'],
                'tanggal_mulai_tugas' => $validated['tanggal_mulai_tugas'],
                'tanggal_selesai_tugas' => $validated['tanggal_selesai_tugas'],
            ]);

            return $surat;
        });

        return redirect()->route('surat-tugas.show', ['surat-tugas' => $surat])
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
        // Eager load necessary relationships
        $surat = $surat_tugas->load('pegawai', 'penandatangan', 'createdBy');

        $template = 'surat-tugas.template'; // For now, a single template for Surat Tugas

        $pegawai = $surat->pegawai;
        $penandatangan = $surat->penandatangan;

        $data = [
            'nomor_surat' => $surat->nomor_surat,
            'tanggal_surat' => Carbon::parse($surat->tanggal_surat)->translatedFormat('d F Y'),
            'dasar_hukum' => $surat->dasar_hukum,
            'tujuan_tugas' => $surat->tujuan_tugas,
            'lokasi_tugas' => $surat->lokasi_tugas,
            'tanggal_mulai_tugas' => Carbon::parse($surat->tanggal_mulai_tugas)->translatedFormat('d F Y'),
            'tanggal_selesai_tugas' => Carbon::parse($surat->tanggal_selesai_tugas)->translatedFormat('d F Y'),
            'pegawai' => $pegawai, // Pass single object
            'nama_penandatangan' => $penandatangan->nama_lengkap ?? '',
            'nip_penandatangan' => $penandatangan->nip ?? '',
            'jabatan_penandatangan' => $penandatangan->jabatan ?? '',
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
        $surat_tugas->load('pegawai'); // Eager load related pegawai

        $pegawais = Pegawai::pluck('nama_lengkap', 'id');

        $kepalaPegawai = Pegawai::where('is_kepala', true)
            ->get()
            ->mapWithKeys(function ($kepala) {
                return [
                    $kepala->id => $kepala->nama_lengkap . ' (' . $kepala->jabatan . ')',
                ];
            });

        return view('surat-tugas.edit', compact('surat_tugas', 'pegawais', 'kepalaPegawai'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreSuratTugasRequest $request, Surat $surat_tugas)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $surat_tugas) {
            $surat_tugas->update([
                'tanggal_surat' => $validated['tanggal_surat'],
                'perihal' => $validated['tujuan_tugas'],
                'pegawai_id' => $validated['pegawai_id'],
                'penandatangan_id' => $validated['penandatangan_id'],
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
