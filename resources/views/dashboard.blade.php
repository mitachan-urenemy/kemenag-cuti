<x-app-layout>
    <div class="space-y-6">
        {{-- Welcome Header (Hero Style) --}}
        <div class="relative overflow-hidden bg-gradient-to-br from-green-600 via-green-600 to-emerald-700 rounded-2xl shadow-xl p-8 lg:p-10 text-white">
            <!-- Animated background pattern -->
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
                <div class="flex gap-3">
                    <x-modal-surat>
                        <x-slot name="trigger">
                            <span class="px-6 py-3 bg-white text-blue-600 rounded-xl font-semibold hover:bg-blue-50 transition-all cursor-pointer flex items-center gap-2">
                                <x-lucide-plus class="w-5 h-5" />
                                Buat Surat
                            </span>
                        </x-slot>
                    </x-modal-surat>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
