<x-app-layout>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="col-span-1 md:col-span-2 lg:col-span-3">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-4 md:p-6 border-b border-gray-200 bg-gray-50">
                    <div class="flex items-center gap-2 mb-3">
                        <x-lucide-house class="w-8 h-8 text-gray-600" />
                        <h2 class="text-lg font-semibold text-gray-800">Dashboard</h2>
                    </div>
                    <p class="text-sm text-gray-500">
                        Selamat datang di dashboard Kemenag Bener Meriah!
                    </p>
                </div>
            </div>
        </div>

        {{-- Card 1: Manajemen Pegawai --}}
        <x-card
            icon="users"
            title="Manajemen Pegawai"
            description="Kelola data, tambah, ubah, dan hapus pegawai."
            href="{{ route('pegawai.index') }}"
        />

        {{-- Card 2: Manajemen User --}}
        <x-card
            icon="user-cog"
            title="Manajemen User"
            description="Atur pengguna yang dapat mengakses sistem."
            href="{{ route('users.index') }}"
        />

        {{-- Card 3: Buat Surat Cuti --}}
        <x-card
            icon="file-text"
            title="Buat Surat Cuti"
            description="Buat dan arsipkan surat izin cuti untuk pegawai."
            href="{{ route('surat-cuti.create') }}"
        />

        {{-- Card 4: Buat Surat Tugas --}}
        <x-card
            icon="briefcase"
            title="Buat Surat Tugas"
            description="Buat dan arsipkan surat perintah tugas untuk pegawai."
            href="{{ route('surat-tugas.create') }}"
        />
    </div>
</x-app-layout>

