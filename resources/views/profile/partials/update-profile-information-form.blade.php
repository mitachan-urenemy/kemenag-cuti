<form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
    @csrf
    @method('patch')

    <x-content-card icon="user-circle" title="{{ __('Informasi Profil') }}"
        subtitle="{{ __('Perbarui informasi profil akun Anda serta alamat email.') }}">
        <div class="space-y-4">
            <div>
                <x-input-label for="image" :value="__('Avatar')" />
                <div class="mt-2 flex items-center gap-x-3">
                    <img class="h-16 w-16 rounded-full"
                        src="{{ $user->image_path ? asset('storage/' . $user->image_path) : 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($user->pegawai->email ?? 'example@example.com'))) . '?d=mp' }}"
                        alt="Current avatar">
                    <input id="image" name="image" type="file"
                        class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-800 hover:file:bg-gray-200" />
                </div>
                <x-input-error class="mt-2" :messages="$errors->get('image')" />
            </div>

            <div>
                <x-input-label for="username" :value="__('Username')" />
                <x-text-input id="username" name="username" type="text" class="mt-1 block w-full"
                    :value="old('username', $user->username)" required autofocus autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('username')" />
            </div>
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->pegawai->email ?? '')" autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </div>
            <div>
                <x-input-label for="nomor_hp" :value="__('Nomor HP')" />
                <x-text-input id="nomor_hp" name="nomor_hp" type="text" class="mt-1 block w-full"
                    :value="old('nomor_hp', $user->pegawai->nomor_hp ?? '')" autocomplete="tel" />
                <x-input-error class="mt-2" :messages="$errors->get('nomor_hp')" />
            </div>
        </div>

        <x-slot name="footer">
            <div class="flex items-center gap-4">
                <x-primary-button>{{ __('Simpan') }}</x-primary-button>
            </div>
        </x-slot>
    </x-content-card>
</form>
