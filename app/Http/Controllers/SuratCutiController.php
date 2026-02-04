<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Surat;
use App\Http\Requests\StoreSuratCutiRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\Snappy\Facades\SnappyPdf as PDF;
use Carbon\Carbon;

class SuratCutiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $today = Carbon::today();
            $query = Surat::where('jenis_surat', 'cuti')
                ->whereDate('tanggal_selesai_cuti', '>=', $today)
                ->with('pegawais');

            // Handle search
            if ($request->filled('search')) {
                $searchValue = $request->input('search');
                $query->where(function ($q) use ($searchValue) {
                    $q->where('nomor_surat', 'like', "%{$searchValue}%")
                        ->orWhere('jenis_cuti', 'like', "%{$searchValue}%")
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
                $pegawai = $surat->pegawais->first();

                if ($pegawai) {
                    $cuti = $pegawai->hitungCuti(
                        $surat->tanggal_mulai_cuti,
                        $surat->tanggal_selesai_cuti
                    );

                    $surat->pegawai_nama = $pegawai->nama_lengkap;
                    $surat->status_cuti = $cuti['status'];       // BELUM_DIMULAI | SEDANG_CUTI
                    $surat->sisa_cuti = $cuti['sisa_cuti'];      // countdown
                } else {
                    $surat->pegawai_nama = 'N/A';
                    $surat->status_cuti = null;
                    $surat->sisa_cuti = null;
                }

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

            return response()->json([
                'data' => $surats->items(),
                'total' => $surats->total(),
            ]);
        }

        return view('surat-cuti.index');
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

        return view('surat-cuti.create', compact('pegawais', 'kepalaPegawai'));
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
                'nomor_surat' => 'TEMP-' . uniqid(), // Temporary number
                'tanggal_surat' => $validated['tanggal_surat'],
                'perihal' => 'Permohonan cuti ' . ucfirst($validated['jenis_cuti']),
                'created_by_user_id' => $request->user()->id,
                'penandatangan_id' => $validated['penandatangan_id'],
                'jenis_cuti' => $validated['jenis_cuti'],
                'tanggal_mulai_cuti' => $validated['tanggal_mulai_cuti'],
                'tanggal_selesai_cuti' => $validated['tanggal_selesai_cuti'],
                'keterangan_cuti' => $validated['keterangan_cuti'],
            ]);

            // Attach the employee who is taking the leave
            $surat->pegawais()->attach($validated['pegawai_id']);

            // Generate the real letter number
            // Format: B–001/KK.01.1.19/KP.08.2/01/2025
            $nomorUrut = str_pad($surat->id, 3, '0', STR_PAD_LEFT);
            $tahun = date('Y', strtotime($validated['tanggal_surat']));
            $bulan = date('m', strtotime($validated['tanggal_surat']));

            $nomorSurat = "B-{$nomorUrut}/KK.01.1.19/KP.08.2/{$bulan}/{$tahun}";
            $surat->update(['nomor_surat' => $nomorSurat]);

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
        // The main variable is $surat_cuti, but we'll call it $surat for consistency in the logic below
        $surat = $surat_cuti->load('pegawais', 'penandatangan', 'createdBy');

        $template = match($surat->jenis_cuti) {
            'melahirkan' => 'surat-cuti.templates.template-cuti-melahirkan',
            'sakit' => 'surat-cuti.templates.template-cuti-sakit',
            'tahunan' => 'surat-cuti.templates.template-cuti-tahunan',
            default => abort(404),
        };

        $pegawai = $surat->pegawais->first();
        $penandatangan = $surat->penandatangan;

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
            'nama' => $pegawai->nama_lengkap ?? '',
            'nip' => $pegawai->nip ?? '',
            'pangkat' => $pegawai->pangkat_golongan ?? '',
            'jabatan' => $pegawai->jabatan ?? '',
            'unit_kerja' => $pegawai->bidang_seksi ?? 'Kantor Kementerian Agama Kabupaten Bener Meriah',
            'lama_cuti' => $lama_cuti_str,
            'tanggal_mulai' => Carbon::parse($surat->tanggal_mulai_cuti)->translatedFormat('d F Y'),
            'tanggal_selesai' => Carbon::parse($surat->tanggal_selesai_cuti)->translatedFormat('d F Y'),
            'nama_kepala' => $penandatangan->nama_lengkap ?? 'Kepala Dinas',
            'nip_kepala' => $penandatangan->nip ?? '',
            'jabatan_kepala' => $penandatangan->jabatan ?? '',
            'surat' => $surat, // Pass the original object for templates that need it
        ];


        return view('surat-cuti.show', [
            'surat' => $surat, // for action buttons
            'template' => $template,
            'data' => $data,
        ]);
    }

    /**
     * Generate and download the PDF for the specified resource.
     */
    public function download(Surat $surat)
    {
        $surat->load('pegawais', 'penandatangan', 'createdBy');

        $template = match($surat->jenis_cuti) {
            'melahirkan' => 'surat-cuti.templates.template-cuti-melahirkan',
            'sakit' => 'surat-cuti.templates.template-cuti-sakit',
            'tahunan' => 'surat-cuti.templates.template-cuti-tahunan',
            default => abort(404),
        };

        $pegawai = $surat->pegawais->first();
        $penandatangan = $surat->penandatangan;

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
            'nama' => $pegawai->nama_lengkap ?? '',
            'nip' => $pegawai->nip ?? '',
            'pangkat' => $pegawai->pangkat_golongan ?? '',
            'jabatan' => $pegawai->jabatan ?? '',
            'unit_kerja' => $pegawai->bidang_seksi ?? 'Kantor Kementerian Agama Kabupaten Bener Meriah',
            'lama_cuti' => $lama_cuti_str,
            'tanggal_mulai' => Carbon::parse($surat->tanggal_mulai_cuti)->translatedFormat('d F Y'),
            'tanggal_selesai' => Carbon::parse($surat->tanggal_selesai_cuti)->translatedFormat('d F Y'),
            'nama_kepala' => $penandatangan->nama_lengkap ?? 'Kepala Dinas',
            'nip_kepala' => $penandatangan->nip ?? '',
            'jabatan_kepala' => $penandatangan->jabatan ?? '',
            'surat' => $surat, // Pass the original object for templates that need it
        ];

        $pdf = Pdf::loadView($template, $data)->setPaper('letter', 'portrait');

        // Generate filename
        $filename = 'Surat_Cuti_' . $surat->jenis_cuti . '_' .
                    str_replace(' ', '_', $pegawai->nama_lengkap ?? 'unknown') . '_' .
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
        //
    }
}
