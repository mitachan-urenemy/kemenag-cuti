<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
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
            ->whereDate('tanggal_selesai_cuti', '>=', $today)
            ->with('pegawai');

        // Handle search
        if ($request->filled('search')) {
            $searchValue = $request->input('search');
            $query->where(function ($q) use ($searchValue) {
                $q->where('nomor_surat', 'like', "%{$searchValue}%")
                    ->orWhere('jenis_cuti', 'like', "%{$searchValue}%")
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
            $pegawai = $surat->pegawai;

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
        $pegawais = Pegawai::pluck('nama_lengkap', 'id');

        $kepalaPegawai = Pegawai::where('is_kepala', true)
            ->get()
            ->mapWithKeys(function ($kepala) {
                return [
                    $kepala->id => $kepala->nama_lengkap . ' (' . $kepala->jabatan . ')',
                ];
            });

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

        return view('surat-cuti.create', compact('pegawais', 'kepalaPegawai', 'generatedNomorSurat'));
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
                'pegawai_id' => $validated['pegawai_id'],
                'penandatangan_id' => $validated['penandatangan_id'],
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
        // The main variable is $surat_cuti, but we'll call it $surat for consistency in the logic below
        $surat = $surat_cuti->load('pegawai', 'penandatangan', 'createdBy');

        $template = 'surat-cuti.template';

        $pegawai = $surat->pegawai;
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
        $surat_cuti->load('pegawai'); // Eager load related pegawai

        $pegawais = Pegawai::pluck('nama_lengkap', 'id');

        $kepalaPegawai = Pegawai::where('is_kepala', true)
            ->get()
            ->mapWithKeys(function ($kepala) {
                return [
                    $kepala->id => $kepala->nama_lengkap . ' (' . $kepala->jabatan . ')',
                ];
            });

        return view('surat-cuti.edit', compact('surat_cuti', 'pegawais', 'kepalaPegawai'));
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
                'pegawai_id' => $validated['pegawai_id'],
                'penandatangan_id' => $validated['penandatangan_id'],
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
        //
    }
}
