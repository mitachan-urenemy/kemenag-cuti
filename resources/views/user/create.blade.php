<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form method="post" action="{{ route('users.store') }}">
                @csrf
                <x-content-card
                    icon="user-plus"
                    title="Tambah User Baru"
                    subtitle="Pilih pegawai yang akan dibuatkan akun, lalu isi detail akun."
                >
                    <x-slot name="action">
                        <a href="{{ route('users.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg border border-gray-300 transition-colors duration-200 text-xs uppercase">
                            <x-lucide-arrow-left class="w-4 h-4" />
                            Kembali
                        </a>
                    </x-slot>

                    <div class="space-y-4">
                        <x-forms.select-search
                            title="Pilih Pegawai"
                            name="pegawai_id"
                            :options="$pegawais"
                            placeholder="Cari dan pilih pegawai..."
                            :selected="old('pegawai_id')"
                            required
                        />

                        <x-forms.text
                            title="Username"
                            name="username"
                            placeholder="Masukkan username unik"
                            :value="old('username')"
                            required
                            autofocus
                        />

                        <x-forms.text
                            title="Email"
                            name="email"
                            type="email"
                            placeholder="Masukkan alamat email"
                            :value="old('email')"
                            required
                        />

                        <x-forms.password
                            title="Password"
                            name="password"
                            placeholder="Masukkan password baru"
                            required
                        />

                        <x-forms.password
                            title="Konfirmasi Password"
                            name="password_confirmation"
                            placeholder="Ulangi password baru"
                            required
                        />
                    </div>

                    <x-slot name="footer">
                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('users.index') }}" class="text-sm text-gray-600 hover:text-gray-900">{{ __('Batal') }}</a>
                            <x-primary-button>{{ __('Simpan User') }}</x-primary-button>
                        </div>
                    </x-slot>
                </x-content-card>
            </form>
        </div>
    </div>
</x-app-layout>
