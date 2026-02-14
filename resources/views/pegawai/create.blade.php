<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form method="post" action="{{ route('pegawai.store') }}">
                @csrf
                <x-content-card
                    icon="user-plus"
                    title="Tambah Pegawai Baru"
                    subtitle="Isi formulir di bawah ini untuk menambahkan data pegawai baru."
                >
                    <x-slot name="action">
                        <a href="{{ route('pegawai.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg border border-gray-300 transition-colors duration-200 text-xs uppercase">
                            <x-lucide-arrow-left class="w-4 h-4" />
                            Kembali
                        </a>
                    </x-slot>

                    <x-forms.text
                        title="Nama Lengkap"
                        name="nama_lengkap"
                        placeholder="Masukkan Nama Lengkap"
                        :value="old('nama_lengkap')"
                        required
                        autofocus
                    />

                    <x-forms.text
                        title="NIP (Nomor Induk Pegawai)"
                        name="nip"
                        placeholder="Masukkan NIP"
                        :value="old('nip')"
                        note="Kosongkan jika bukan PNS."
                    />

                    <x-forms.text
                        title="Jabatan"
                        name="jabatan"
                        placeholder="Masukkan Jabatan"
                        :value="old('jabatan')"
                        required
                    />

                    <x-forms.select
                        title="Status Pegawai"
                        name="status_pegawai"
                        :options="['PNS' => 'PNS', 'PPPK' => 'PPPK']"
                        :selected="old('status_pegawai')"
                        required
                    />

                    <x-forms.text
                        title="Pangkat / Golongan"
                        name="pangkat_golongan"
                        placeholder="Masukkan Pangkat / Golongan"
                        :value="old('pangkat_golongan')"
                    />

                    <x-forms.text
                        title="Bidang / Seksi"
                        name="bidang_seksi"
                        placeholder="Masukkan Bidang / Seksi"
                        :value="old('bidang_seksi')"
                    />

                    <x-slot name="footer">
                        <div class="flex items-center justify-end gap-4">
                            <a href="{{ route('pegawai.index') }}" class="text-sm text-gray-600 hover:text-gray-900">{{ __('Batal') }}</a>
                            <x-primary-button>{{ __('Simpan Data') }}</x-primary-button>
                        </div>
                    </x-slot>
                </x-content-card>
            </form>
        </div>
    </div>
</x-app-layout>
