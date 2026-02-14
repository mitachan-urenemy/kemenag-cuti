<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePegawaiRequest;
use App\Http\Requests\UpdatePegawaiRequest;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Log::info($request->all());
        if ($request->wantsJson()) {
            $param = $request->query('param');
            Log::info('Parameter: ' . $param);
            $query = Pegawai::query();

            $query->when($request->input('search'), function ($q, $search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nip', 'like', "%{$search}%")
                  ->orWhere('jabatan', 'like', "%{$search}%");
            });

            // Apply sorting
            $query->orderBy(
                $request->input('sort', 'nama_lengkap'),
                $request->input('dir', 'asc')
            );

            // Get total count before pagination
            $total = $query->count();

            // Apply pagination
            $pegawais = $query->paginate($request->input('limit', 10));

            Log::info($pegawais);

            return response()->json([
                'data' => $pegawais->items(),
                'total' => $total,
            ]);
        }

        return view('pegawai.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pegawai.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePegawaiRequest $request)
    {
        Pegawai::create($request->validated());

        $notification = [
            'type' => 'success',
            'title' => 'Berhasil!',
            'message' => 'Data pegawai berhasil ditambahkan.',
            'autoClose' => true
        ];

        return redirect()->route('pegawai.index')->with('notification', $notification);
    }

    /**
     * Display the specified resource.
     */
    public function show(Pegawai $pegawai)
    {
        // Not used for this feature
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pegawai $pegawai)
    {
        return view('pegawai.edit', compact('pegawai'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePegawaiRequest $request, Pegawai $pegawai)
    {
        $pegawai->update($request->validated());

        $notification = [
            'type' => 'success',
            'title' => 'Berhasil!',
            'message' => 'Data pegawai berhasil diperbarui.',
            'autoClose' => true
        ];

        return redirect()->route('pegawai.index')->with('notification', $notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pegawai $pegawai)
    {
        // Check if the pegawai is linked to any surat as a penandatangan before deleting
        if ($pegawai->suratsAsPenandatangan()->exists() || $pegawai->surats()->exists()) {
            $notification = [
                'type' => 'error',
                'title' => 'Gagal!',
                'message' => 'Tidak dapat menghapus pegawai. Pegawai ini masih terkait dengan data surat.',
                'autoClose' => true
            ];
            return redirect()->route('pegawai.index')->with('notification', $notification);
        }

        $pegawai->delete();

        $notification = [
            'type' => 'success',
            'title' => 'Berhasil!',
            'message' => 'Data pegawai berhasil dihapus.',
            'autoClose' => true

        ];

        return redirect()->route('pegawai.index')->with('notification', $notification);
    }
}
