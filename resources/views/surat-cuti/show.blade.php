<x-app-layout>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- TAMBAHKAN CLASS 'no-print' DI SINI -->
        <x-content-card
            icon="file-check"
            title="Detail Surat Cuti"
            subtitle="Surat cuti telah berhasil dibuat. Anda dapat mencetaknya atau mengunduh sebagai PDF."
            class="no-print"
        >
            <x-slot name="action">
                <div class="flex items-center gap-2">
                    <a href="{{ route('surat-cuti.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-gray-700 uppercase transition-colors duration-200 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200">
                        <x-lucide-arrow-left class="w-4 h-4" />
                        Kembali
                    </a>



                    <!-- Tombol Cetak -->
                    <a href="{{ route('surat-cuti.show', [$surat, 'print' => true]) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white uppercase transition-colors duration-200 bg-gray-600 border border-transparent rounded-lg hover:bg-gray-700">
                        <x-lucide-printer class="w-4 h-4" />
                        Cetak
                    </a>
                </div>
            </x-slot>

            {{-- Area Preview Surat --}}
            <div class="mt-6 flex justify-center bg-gray-100/50 p-8 rounded-xl border border-gray-200 overflow-auto">
                 {{-- Wrapper to ensure the paper centers correctly and shadows are visible --}}
                 <div class="transform scale-[0.8] md:scale-100 origin-top">
                     @include($template, $data)
                 </div>
            </div>
        </x-content-card>
    </div>
</x-app-layout>
