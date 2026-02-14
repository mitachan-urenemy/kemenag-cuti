<div x-data="{ open: false }"
     x-init="$watch('open', val => {
         if (val) document.body.classList.add('overflow-hidden');
         else document.body.classList.remove('overflow-hidden');
     })"
     @keydown.escape.window="open = false">

    <!-- Trigger Button -->
    <button {{ $attributes->merge(['type' => 'button', '@click' => 'open = true']) }}>
        {{ $trigger ?? 'Open Modal' }}
    </button>

    <!-- Modal Background Overlay -->
    <div x-show="open"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 bg-gray-900/80 backdrop-blur-sm transition-opacity"
         aria-hidden="true"
         style="display: none;"
         @click="open = false"
    ></div>

    <!-- Modal Dialog -->
    <div x-show="open"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         class="fixed inset-0 z-50 overflow-y-auto"
         style="display: none;"
    >
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div @click.away="open = false"
                 class="relative transform overflow-hidden rounded-2xl bg-white shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl"
            >
                <!-- Close Button -->
                <button @click="open = false"
                        class="absolute right-4 top-4 text-gray-400 hover:text-gray-600 transition-colors z-30">
                    <x-lucide-x class="w-6 h-6" />
                </button>

                <!-- Header Section with Gradient -->
                <div class="bg-gradient-to-br from-green-600 via-green-600 to-green-700 px-6 py-8 text-center">
                    <div class="mx-auto w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center mb-4">
                        <x-lucide-file-plus class="w-8 h-8 text-white" />
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2">
                        Buat Surat Baru
                    </h3>
                    <p class="text-blue-100 text-sm max-w-md mx-auto">
                        Pilih jenis surat yang ingin Anda buat. Anda akan diarahkan ke form pembuatan surat.
                    </p>
                </div>

                <!-- Content Section -->
                <div class="px-6 py-8">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <!-- Surat Cuti Card -->
                        <a href="{{ route('surat-cuti.create') }}"
                           @click="open = false"
                           class="group relative overflow-hidden bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-200 rounded-2xl p-6 hover:border-green-400 hover:shadow-xl hover:-translate-y-2 transition-all duration-300"
                        >
                            <!-- Decorative Circle -->
                            <div class="absolute -right-8 -top-8 w-24 h-24 bg-green-200/30 rounded-full group-hover:scale-150 transition-transform duration-500"></div>

                            <div class="relative z-30">
                                <div class="inline-flex p-4 bg-white rounded-2xl shadow-sm mb-4 group-hover:bg-green-600 group-hover:scale-110 transition-all duration-300">
                                    <x-lucide-file-text class="w-10 h-10 text-green-600 group-hover:text-gray-200 transition-colors" />
                                </div>

                                <h4 class="text-xl font-bold text-gray-800 mb-2 group-hover:text-green-700 transition-colors">
                                    Surat Cuti
                                </h4>

                                <p class="text-sm text-gray-600 mb-4">
                                    Buat surat izin cuti untuk pegawai yang akan mengambil cuti.
                                </p>

                                <div class="flex items-center text-green-600 font-semibold text-sm group-hover:translate-x-2 transition-transform">
                                    Pilih
                                    <x-lucide-arrow-right class="w-4 h-4 ml-1" />
                                </div>
                            </div>

                            <!-- Hover Shine Effect -->
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                        </a>

                        <!-- Surat Tugas Card -->
                        <a href="{{ route('surat-tugas.create') }}"
                           @click="open = false"
                           class="group relative overflow-hidden bg-gradient-to-br from-blue-50 to-green-50 border-2 border-blue-200 rounded-2xl p-6 hover:border-blue-400 hover:shadow-xl hover:-translate-y-2 transition-all duration-300"
                        >
                            <!-- Decorative Circle -->
                            <div class="absolute -right-8 -top-8 w-24 h-24 bg-blue-200/30 rounded-full group-hover:scale-150 transition-transform duration-500"></div>

                            <div class="relative z-30">
                                <div class="inline-flex p-4 bg-white rounded-2xl shadow-sm mb-4 group-hover:bg-blue-600 group-hover:scale-110 transition-all duration-300">
                                    <x-lucide-briefcase class="w-10 h-10 text-blue-600 group-hover:text-gray-200 transition-colors" />
                                </div>

                                <h4 class="text-xl font-bold text-gray-800 mb-2 group-hover:text-blue-700 transition-colors">
                                    Surat Tugas
                                </h4>

                                <p class="text-sm text-gray-600 mb-4">
                                    Buat surat penugasan untuk pegawai yang akan melaksanakan tugas.
                                </p>

                                <div class="flex items-center text-blue-600 font-semibold text-sm group-hover:translate-x-2 transition-transform">
                                    Pilih
                                    <x-lucide-arrow-right class="w-4 h-4 ml-1" />
                                </div>
                            </div>

                            <!-- Hover Shine Effect -->
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                        </a>

                    </div>

                    <!-- Info Text -->
                    <div class="mt-6 text-center">
                        <p class="text-xs text-gray-500 flex items-center justify-center gap-1">
                            <x-lucide-info class="w-4 h-4" />
                            Tekan <kbd class="px-2 py-1 bg-gray-400 rounded text-xs font-semibold">ESC</kbd> untuk menutup
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
