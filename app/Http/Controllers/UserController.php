<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $query = User::query();

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('username', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            if ($request->filled('sort_by')) {
                $sort_by = $request->sort_by;
                $sort_dir = $request->sort_dir ?? 'asc';
                $query->orderBy($sort_by, $sort_dir);
            }

            $data = $query->paginate($request->per_page ?? 10);

            return response()->json([
                'data' => $data->items(),
                'total' => $data->total(),
            ]);
        }
        return view('user.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pegawais = Pegawai::whereDoesntHave('user')->pluck('nama_lengkap', 'id');
        return view('user.create', compact('pegawais'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();

        User::create([
            'pegawai_id' => $validated['pegawai_id'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $notification = [
            'type' => 'success',
            'title' => 'Berhasil!',
            'message' => 'User baru berhasil ditambahkan.',
        ];

        return redirect()->route('users.index')->with('notification', $notification);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $validated = $request->validated();

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        $notification = [
            'type' => 'success',
            'title' => 'Berhasil!',
            'message' => 'Data user berhasil diperbarui.',
        ];

        return redirect()->route('users.index')->with('notification', $notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if (Auth::id() === $user->id) {
            $notification = [
                'type' => 'danger',
                'title' => 'Gagal!',
                'message' => 'Anda tidak dapat menghapus akun Anda sendiri.',
            ];
            return redirect()->route('users.index')->with('notification', $notification);
        }

        $user->delete();

        $notification = [
            'type' => 'success',
            'title' => 'Berhasil!',
            'message' => 'User berhasil dihapus.',
        ];

        return redirect()->route('users.index')->with('notification', $notification);
    }
}
