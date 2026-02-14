<x-app-layout>
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-content-card
            icon="briefcase"
            title="Detail Surat Tugas"
            subtitle="Surat tugas telah berhasil dibuat. Anda dapat mencetaknya atau mengunduh sebagai PDF."
        >
            <x-slot name="action">
                <div class="flex items-center gap-2">
                    <a href="{{ route('surat-tugas.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-gray-700 uppercase transition-colors duration-200 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200">
                        <x-lucide-arrow-left class="w-4 h-4" />
                        Kembali
                    </a>

                    <a href="{{ route('surat-tugas.show', ['surat_tugas' => $surat->id, 'print' => true]) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 text-xs font-semibold text-white uppercase transition-colors duration-200 bg-gray-600 border border-transparent rounded-lg hover:bg-gray-700">
                        <x-lucide-printer class="w-4 h-4" />
                        Cetak
                    </a>
                </div>
            </x-slot>

            {{-- Letter Preview --}}
            <div class="mt-6 flex justify-center bg-gray-100/50 p-8 rounded-xl border border-gray-200 overflow-auto">
                 {{-- Wrapper to ensure the paper centers correctly and shadows are visible --}}
                 <div class="transform scale-[0.8] md:scale-100 origin-top">
                     @include($template, $data)
                 </div>
            </div>
        </x-content-card>
    </div>
</x-app-layout>
