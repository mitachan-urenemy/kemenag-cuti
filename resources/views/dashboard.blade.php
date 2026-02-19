<x-app-layout>
    <div class="space-y-8">
        <div class="bg-gray-50 rounded-2xl p-8 text-gray-900 shadow-sm">
            <h2 class="text-2xl font-bold">Selamat Datang, {{ ucfirst(Auth::user()->username) }}!</h2>
            <p class="text-gray-500">Dashboard Sistem Informasi Kepegawaian Kantor Kemenag Bener Meriah.</p>
        </div>

        {{-- Stats & Quick Actions Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Surat Tugas Card -->
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300">
                <div class="flex justify-between items-start mb-6">
                    <div class="p-3 bg-blue-600 rounded-xl">
                        <x-lucide-briefcase class="w-6 h-6 text-white" />
                    </div>
                    <span class="text-3xl font-bold text-gray-900">{{ $suratTugasCount }}</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-1">Surat Tugas</h3>
                <p class="text-sm text-gray-500 mb-6">Buat surat perintah tugas untuk pegawai</p>
                <div class="flex items-center">
                    <a href="{{ route('surat-tugas.create') }}" class="w-full py-3 bg-gray-50 text-gray-700 rounded-xl border border-gray-200 font-semibold hover:bg-gray-100 transition-all cursor-pointer flex items-center justify-center gap-2">
                        <x-lucide-plus class="w-5 h-5" />
                        Buat Surat
                    </a>
                </div>
            </div>

            <!-- Surat Cuti Card -->
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300">
                <div class="flex justify-between items-start mb-6">
                    <div class="p-3 bg-green-600 rounded-xl">
                        <x-lucide-file-text class="w-6 h-6 text-white" />
                    </div>
                    <span class="text-3xl font-bold text-gray-900">{{ $suratCutiCount }}</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-1">Surat Cuti</h3>
                <p class="text-sm text-gray-500 mb-6">Buat surat permohonan cuti pegawai</p>
                <div class="flex items-center">
                    <a href="{{ route('surat-cuti.create') }}" class="w-full py-3 bg-gray-50 text-gray-700 rounded-xl border border-gray-200 font-semibold hover:bg-gray-100 transition-all cursor-pointer flex items-center justify-center gap-2">
                        <x-lucide-plus class="w-5 h-5" />
                        Buat Surat
                    </a>
                </div>
            </div>

            <!-- Riwayat Surat Card -->
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300">
                <div class="flex justify-between items-start mb-6">
                    <div class="p-3 bg-orange-600 rounded-xl">
                        <x-lucide-history class="w-6 h-6 text-white" />
                    </div>
                    <span class="text-3xl font-bold text-gray-900">{{ $totalSuratCount }}</span>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-1">Riwayat Surat</h3>
                <p class="text-sm text-gray-500 mb-6">Lihat daftar semua surat yang telah dibuat</p>
                <div class="flex items-center">
                    <a href="{{ route('riwayat-surat') }}" class="w-full py-3 bg-gray-50 text-gray-700 rounded-xl border border-gray-200 font-semibold hover:bg-gray-100 transition-all cursor-pointer flex items-center justify-center gap-2">
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
                                    {{ $activity->jenis_surat == 'cuti' ? 'Surat Cuti - ' . $activity->nama_lengkap_pegawai : 'Surat Tugas - ' . $activity->nama_lengkap_pegawai }}
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
