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
            <div class="p-8 bg-white border border-gray-300 rounded-lg shadow-inner">
                @include($template, $data)
            </div>
        </x-content-card>
    </div>
</x-app-layout>
