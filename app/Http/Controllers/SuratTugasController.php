<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Surat;
use App\Http\Requests\StoreSuratTugasRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Carbon\Carbon;

class SuratTugasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $query = Surat::where('jenis_surat', 'tugas')->with('pegawais');

            // Handle search
            if ($request->filled('search')) {
                $searchValue = $request->input('search');
                $query->where(function ($q) use ($searchValue) {
                    $q->where('nomor_surat', 'like', "%{$searchValue}%")
                        ->orWhere('tujuan_tugas', 'like', "%{$searchValue}%")
                        ->orWhereHas('pegawais', function ($q) use ($searchValue) {
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
                $surat->pegawai_names = $surat->pegawais->pluck('nama_lengkap')->implode(', ');
                return $surat;
            });

            return response()->json([
                'data' => $surats->items(),
                'total' => $surats->total(),
            ]);
        }

        return view('surat-tugas.index');
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

        return view('surat-tugas.create', compact('pegawais', 'kepalaPegawai'));
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
                'nomor_surat' => 'TEMP-' . uniqid(), // Temporary number
                'tanggal_surat' => $validated['tanggal_surat'],
                'perihal' => $validated['tujuan_tugas'],
                'created_by_user_id' => $request->user()->id,
                'penandatangan_id' => $validated['penandatangan_id'],

                // Tugas-specific fields
                'dasar_hukum' => $validated['dasar_hukum'],
                'tujuan_tugas' => $validated['tujuan_tugas'],
                'lokasi_tugas' => $validated['lokasi_tugas'],
                'tanggal_mulai_tugas' => $validated['tanggal_mulai_tugas'],
                'tanggal_selesai_tugas' => $validated['tanggal_selesai_tugas'],
            ]);

            // Attach multiple employees
            $surat->pegawais()->attach($validated['pegawai_ids']);

            // Generate the real letter number
            // Format: B–001/KK.01.1.19/KP.08.2/01/2025
            $nomorUrut = str_pad($surat->id, 3, '0', STR_PAD_LEFT);
            $tahun = date('Y', strtotime($validated['tanggal_surat']));
            $bulan = date('m', strtotime($validated['tanggal_surat']));

            $nomorSurat = "B-{$nomorUrut}/KK.01.1.19/KP.08.2/{$bulan}/{$tahun}";
            $surat->update(['nomor_surat' => $nomorSurat]);

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
        $surat = $surat_tugas->load('pegawais', 'penandatangan', 'createdBy');

        $template = 'surat-tugas.template'; // For now, a single template for Surat Tugas

        $pegawais_ditugaskan = $surat->pegawais; // Collection of Pegawai
        $penandatangan = $surat->penandatangan;

        $data = [
            'nomor_surat' => $surat->nomor_surat,
            'tanggal_surat' => Carbon::parse($surat->tanggal_surat)->translatedFormat('d F Y'),
            'dasar_hukum' => $surat->dasar_hukum,
            'tujuan_tugas' => $surat->tujuan_tugas,
            'lokasi_tugas' => $surat->lokasi_tugas,
            'tanggal_mulai_tugas' => Carbon::parse($surat->tanggal_mulai_tugas)->translatedFormat('d F Y'),
            'tanggal_selesai_tugas' => Carbon::parse($surat->tanggal_selesai_tugas)->translatedFormat('d F Y'),
            'pegawais_ditugaskan' => $pegawais_ditugaskan, // Pass collection
            'nama_penandatangan' => $penandatangan->nama_lengkap ?? '',
            'nip_penandatangan' => $penandatangan->nip ?? '',
            'jabatan_penandatangan' => $penandatangan->jabatan ?? '',
            'surat' => $surat, // Pass the original object for templates that need it
        ];

        return view('surat-tugas.show', [
            'surat' => $surat, // for action buttons
            'template' => $template,
            'data' => $data,
        ]);
    }

    /**
     * Generate and download the PDF for the specified resource.
     */
    public function a(Surat $surat)
    {
        $surat->load('pegawais', 'penandatangan', 'createdBy');

        $template = 'surat-tugas.template'; // For now, a single template for Surat Tugas

        $pegawais_ditugaskan = $surat->pegawais; // Collection of Pegawai
        $penandatangan = $surat->penandatangan;

        $data = [
            'nomor_surat' => $surat->nomor_surat,
            'tanggal_surat' => Carbon::parse($surat->tanggal_surat)->translatedFormat('d F Y'),
            'dasar_hukum' => $surat->dasar_hukum,
            'tujuan_tugas' => $surat->tujuan_tugas,
            'lokasi_tugas' => $surat->lokasi_tugas,
            'tanggal_mulai_tugas' => Carbon::parse($surat->tanggal_mulai_tugas)->translatedFormat('d F Y'),
            'tanggal_selesai_tugas' => Carbon::parse($surat->tanggal_selesai_tugas)->translatedFormat('d F Y'),
            'pegawais_ditugaskan' => $pegawais_ditugaskan, // Pass collection
            'nama_penandatangan' => $penandatangan->nama_lengkap ?? '',
            'nip_penandatangan' => $penandatangan->nip ?? '',
            'jabatan_penandatangan' => $penandatangan->jabatan ?? '',
            'surat' => $surat, // Pass the original object for templates that need it
        ];

        $pdf = PDF::loadView($template, $data)
            ->setPaper('a4', 'portrait');

        // Generate filename
        $filename = 'Surat_Tugas_' . str_replace(' ', '_', $surat->tujuan_tugas) . ' _' .
                    now()->format('YmdHis') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
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
