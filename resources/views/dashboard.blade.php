<x-app-layout>
    <div class="space-y-8">
        {{-- Welcome Header (Hero Style) --}}
        <div class="relative overflow-hidden bg-gradient-to-br from-green-600 via-green-600 to-emerald-700 rounded-2xl shadow-xl p-8 lg:p-10 text-white">
            <div class="absolute top-0 right-0 -mt-8 -mr-8 w-40 h-40 bg-white opacity-10 rounded-full blur-2xl animate-pulse"></div>
            <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-32 h-32 bg-white opacity-10 rounded-full blur-xl animate-pulse" style="animation-delay: 1s;"></div>

            <div class="relative flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h2 class="text-3xl lg:text-4xl font-bold mb-2 bg-clip-text text-transparent bg-gradient-to-r from-white to-green-100">
                        Selamat Datang, {{ ucfirst(Auth::user()->username) ?? 'Admin' }}!
                    </h2>
                    <p class="text-green-100 max-w-2xl text-lg leading-relaxed">
                        Dashboard Sistem Informasi Kepegawaian Kantor Kementerian Agama Kabupaten Bener Meriah.
                    </p>
                </div>
            </div>
        </div>

        {{-- Stats & Quick Actions Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Surat Tugas Card -->
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300">
                <div class="flex justify-between items-start mb-6">
                    <div class="p-3 bg-blue-50 rounded-xl">
                        <x-lucide-briefcase class="w-6 h-6 text-blue-600" />
                    </div>
                    <span class="text-3xl font-bold text-gray-900">{{ $suratTugasCount }}</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-1">Surat Tugas</h3>
                <p class="text-sm text-gray-500 mb-6">Buat surat perintah tugas untuk pegawai</p>
                <div class="flex items-center">
                    <a href="{{ route('surat-tugas.create') }}" class="w-full py-3 bg-blue-50 text-blue-700 rounded-xl font-semibold hover:bg-blue-100 transition-all cursor-pointer flex items-center justify-center gap-2">
                        <x-lucide-plus class="w-5 h-5" />
                        Buat Surat
                    </a>
                </div>
            </div>

            <!-- Surat Cuti Card -->
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300">
                <div class="flex justify-between items-start mb-6">
                    <div class="p-3 bg-green-50 rounded-xl">
                        <x-lucide-file-text class="w-6 h-6 text-green-600" />
                    </div>
                    <span class="text-3xl font-bold text-gray-900">{{ $suratCutiCount }}</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-1">Surat Cuti</h3>
                <p class="text-sm text-gray-500 mb-6">Buat surat permohonan cuti pegawai</p>
                <div class="flex items-center">
                    <a href="{{ route('surat-cuti.create') }}" class="w-full py-3 bg-green-50 text-green-700 rounded-xl font-semibold hover:bg-green-100 transition-all cursor-pointer flex items-center justify-center gap-2">
                        <x-lucide-plus class="w-5 h-5" />
                        Buat Surat
                    </a>
                </div>
            </div>

            <!-- Riwayat Surat Card -->
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300">
                <div class="flex justify-between items-start mb-6">
                    <div class="p-3 bg-orange-50 rounded-xl">
                        <x-lucide-history class="w-6 h-6 text-orange-600" />
                    </div>
                    <span class="text-3xl font-bold text-gray-900">{{ $totalSuratCount }}</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-1">Riwayat Surat</h3>
                <p class="text-sm text-gray-500 mb-6">Lihat daftar semua surat yang telah dibuat</p>
                <div class="flex items-center">
                    <a href="{{ route('riwayat-surat') }}" class="w-full py-3 bg-orange-50 text-orange-700 rounded-xl font-semibold hover:bg-orange-100 transition-all cursor-pointer flex items-center justify-center gap-2">
                        <x-lucide-history class="w-5 h-5" />
                        Lihat Riwayat
                    </a>
                </div>
            </div>
        </div>

        {{-- Recent Activity --}}
        <div class="bg-white rounded-2xl p-8 border border-gray-100 shadow-sm">
            <h3 class="text-lg font-bold text-gray-900 mb-1">Aktivitas Terakhir</h3>
            <p class="text-sm text-gray-500 mb-6">5 surat terakhir yang dibuat</p>

            <div class="space-y-4">
                @forelse($recentActivities as $activity)
                    <div class="flex items-center justify-between p-4 rounded-xl hover:bg-gray-50 transition-colors border border-transparent hover:border-gray-100">
                        <div class="flex items-center gap-4">
                            <div class="p-2 rounded-lg {{ $activity->jenis_surat == 'cuti' ? 'bg-green-100 text-green-600' : 'bg-blue-100 text-blue-600' }}">
                                <x-dynamic-component :component="$activity->jenis_surat == 'cuti' ? 'lucide-file-text' : 'lucide-briefcase'" class="w-5 h-5" />
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900">{{ $activity->nomor_surat }}</h4>
                                <p class="text-xs text-gray-500">
                                    {{ $activity->jenis_surat == 'cuti' ? 'Surat Cuti - ' . $activity->pegawai->nama_lengkap : 'Surat Tugas - ' . $activity->pegawai->nama_lengkap }}
                                </p>
                            </div>
                        </div>
                        <span class="text-xs text-gray-400 font-medium">
                            {{ $activity->created_at->diffForHumans() }}
                        </span>
                    </div>
                @empty
                    <div class="flex flex-col items-center justify-center py-12 text-center">
                        <div class="p-4 bg-gray-50 rounded-full mb-4">
                            <x-lucide-history class="w-8 h-8 text-gray-300" />
                        </div>
                        <h4 class="text-base font-medium text-gray-900">Belum ada aktivitas</h4>
                        <p class="text-sm text-gray-500 mt-1">Belum ada surat yang dibuat dalam sistem ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
