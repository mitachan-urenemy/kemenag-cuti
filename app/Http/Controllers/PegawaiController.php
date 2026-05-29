<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\Pegawai\StorePegawaiRequest;
use App\Http\Requests\Pegawai\UpdatePegawaiRequest;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Pegawai::with('user');
        if ($request->filled('search')) {
            $searchValue = $request->input('search');
            $query->where(function ($q) use ($searchValue) {
                $q->where('nama_lengkap', 'like', "%{$searchValue}%")
                    ->orWhere('nip', 'like', "%{$searchValue}%")
                    ->orWhereHas('user', function ($uq) use ($searchValue) {
                        $uq->where('username', 'like', "%{$searchValue}%");
                    });
            });
        }

        $sortBy = $request->input('sort_by', 'nama_lengkap');
        $sortDir = $request->input('sort_dir', 'asc');
        $query->orderBy($sortBy, $sortDir);

        $perPage = $request->input('limit', 10);
        $pegawais = $query->paginate($perPage);

        $pegawais->getCollection()->transform(function ($pegawai) {
            $pegawai->username = $pegawai->user->username ?? '-';
            return $pegawai;
        });

        if ($request->wantsJson()) {
            return response()->json([
                'data' => $pegawais->items(),
                'total' => $pegawais->total(),
            ]);
        }

        return view('manajemen-pegawai.index', [
            'pegawai' => $pegawais->appends(request()->query())
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $currentAtasan = Pegawai::where('is_atasan', true)->first();

        return view('manajemen-pegawai.create', compact('currentAtasan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePegawaiRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated) {
            if (isset($validated['is_atasan']) && $validated['is_atasan'] == 1) {
                Pegawai::where('is_atasan', true)->get()->each(function (Pegawai $oldAtasan) {
                    $oldAtasan->update(['is_atasan' => false]);
                    if ($oldAtasan->user && $oldAtasan->user->role !== 'admin') {
                        $oldAtasan->user->update(['role' => 'pegawai']);
                    }
                });
            }

            $user = User::create([
                'username' => $validated['username'],
                'password' => Hash::make($validated['password']),
                'role' => (isset($validated['is_atasan']) && $validated['is_atasan'] == 1) ? 'pimpinan' : 'pegawai',
                'status' => true,
            ]);

            Pegawai::create([
                'user_id' => $user->id,
                'is_atasan' => $validated['is_atasan'],
                'nama_lengkap' => $validated['nama_lengkap'],
                'nip' => $validated['nip'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'tempat_lahir' => $validated['tempat_lahir'] ?? null,
                'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
                'status_kepegawaian' => $validated['status_kepegawaian'],
                'pangkat_golongan' => $validated['pangkat_golongan'] ?? null,
                'jabatan' => $validated['jabatan'] ?? null,
                'unit_kerja' => $validated['unit_kerja'] ?? null,
                'pendidikan' => $validated['pendidikan'] ?? null,
                'nomor_hp' => $validated['nomor_hp'] ?? null,
                'email' => $validated['email'] ?? null,
            ]);
        });

        return redirect()->route('manajemen-pegawai.index')->with('notification', [
            'type' => 'success',
            'title' => 'Berhasil!',
            'message' => 'Data pegawai baru berhasil ditambahkan.'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Pegawai $manajemen_pegawai)
    {
        return redirect()->route('manajemen-pegawai.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pegawai $manajemen_pegawai)
    {
        $currentAtasan = Pegawai::where('is_atasan', true)
            ->where('id', '!=', $manajemen_pegawai->id)
            ->first();

        return view('manajemen-pegawai.edit', [
            'pegawai' => $manajemen_pegawai,
            'currentAtasan' => $currentAtasan
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePegawaiRequest $request, Pegawai $manajemen_pegawai)
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $manajemen_pegawai) {
            if (isset($validated['is_atasan']) && $validated['is_atasan'] == 1) {
                Pegawai::where('is_atasan', true)
                    ->where('id', '!=', $manajemen_pegawai->id)
                    ->get()
                    ->each(function (Pegawai $oldAtasan) {
                        $oldAtasan->update(['is_atasan' => false]);
                        if ($oldAtasan->user && $oldAtasan->user->role !== 'admin') {
                            $oldAtasan->user->update(['role' => 'pegawai']);
                        }
                    });
            }

            $manajemen_pegawai->update([
                'is_atasan' => $validated['is_atasan'],
                'nama_lengkap' => $validated['nama_lengkap'],
                'nip' => $validated['nip'],
                'jenis_kelamin' => $validated['jenis_kelamin'],
                'tempat_lahir' => $validated['tempat_lahir'] ?? null,
                'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
                'status_kepegawaian' => $validated['status_kepegawaian'],
                'pangkat_golongan' => $validated['pangkat_golongan'] ?? null,
                'jabatan' => $validated['jabatan'] ?? null,
                'unit_kerja' => $validated['unit_kerja'] ?? null,
                'pendidikan' => $validated['pendidikan'] ?? null,
                'nomor_hp' => $validated['nomor_hp'] ?? null,
                'email' => $validated['email'] ?? null,
            ]);

            if (isset($validated['is_atasan']) && $manajemen_pegawai->user && $manajemen_pegawai->user->role !== 'admin') {
                $manajemen_pegawai->user->update([
                    'role' => $validated['is_atasan'] == 1 ? 'pimpinan' : 'pegawai'
                ]);
            }
        });

        return redirect()->route('manajemen-pegawai.index')->with('notification', [
            'type' => 'success',
            'title' => 'Berhasil!',
            'message' => 'Data pegawai berhasil diperbarui.'
        ]);
    }

    /**
     * Toggle the status of the associated user.
     */
    public function status($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $user = $pegawai->user;

        if (auth()->id() === $user->id) {
            return redirect()->route('manajemen-pegawai.index')->with('notification', [
                'type' => 'error',
                'title' => 'Gagal!',
                'message' => 'Anda tidak dapat menonaktifkan akun Anda sendiri.'
            ]);
        }

        $user->status = !$user->status;
        $user->save();

        $statusStr = $user->status ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('manajemen-pegawai.index')->with('notification', [
            'type' => 'success',
            'title' => 'Berhasil!',
            'message' => "Akun pegawai berhasil {$statusStr}."
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pegawai $manajemen_pegawai)
    {
        if ($manajemen_pegawai->user) {
            $manajemen_pegawai->user->delete();
        } else {
            $manajemen_pegawai->delete();
        }

        return redirect()->route('manajemen-pegawai.index')->with('notification', [
            'type' => 'success',
            'title' => 'Berhasil!',
            'message' => 'Data pegawai berhasil dihapus.'
        ]);
    }
}
