<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->fill($request->validated());

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($user->image_path && Storage::disk('public')->exists($user->image_path)) {
                Storage::disk('public')->delete($user->image_path);
            }

            $image = $request->file('image');
            $filename = uniqid('avatar_', true) . '.' . $image->getClientOriginalExtension();
            $path = 'avatars/' . $filename;

            // Baca gambar, ubah ukuran, simpan dengan kompresi
            $img = Image::read($image);
            $encodedImage = $img
                    ->orient()
                    ->cover(300, 300)
                    ->toJpeg(70);

            Storage::disk('public')->put($path, $encodedImage);
            $user->image_path = $path;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('notification', [
            'type' => 'success',
            'title' => 'Profil Diperbarui!',
            'message' => 'Data profil Anda telah berhasil diperbarui.',
            'autoClose' => true,
        ]);
    }
}
